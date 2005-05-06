<?php

/*
    kaute - authentification and access library
    Copyright (C) 2005  Boris Tomić

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require_once 'kauto.php';
require_once 'kauto_conf.php';
require_once kconf::kodform_dir.'/kodform.php';
require_once kconf::logger;

$auth = new kauth(kconf::admin_group,"kuadmin.php");

//handles group list display option form
class ulist_group_submit extends ksubmit{
	private $user_groups, $admin;
	function __construct(&$smarty, &$admin){
		parent::__construct("list_group", &$smarty);
		$this->admin=&$admin;
	}
	function submited(&$inputs){
		$this->admin->groupspp=$inputs['kgroup_ppage']->value;
		$this->admin->gsearch=$inputs['knamep']->get_value();
		return true;
	}
}

//handles user groups change
class uuser_group_submit extends ksubmit{
	private $admin;
	function __construct(&$smarty, &$admin){
		parent::__construct("user_group", &$smarty);
		$this->admin=&$admin;
	}
	function submited(&$inputs){
		$user_groups = "ARRAY[";
		$first_pass = true;
		foreach($inputs as $key => $value){
			$key_split = explode("_",$key);
			if($key_split[0]=="g"){
				if(!$first_pass)
					$user_groups.=",";
				else
					$first_pass = false;
				$user_groups.="[".$key_split[1].",";
				if($value->checked)
					$user_groups.="1]";
				else
					$user_groups.="0]";
			}
		}
		$user_groups.="]";
		//set new user groups (and delete unwanted)
		$rez = null;
		try{
			$rez =&$this->admin->query->execute("SELECT kaute.set_user_groups('".$inputs['ug_uindex']->get_value()."',".$user_groups.")");
		}
		catch(Exception $e){
			$this->admin->log->emerg($e->getMessage());
			exit(1);
		}
		return true;
	}
}

class kuser_groups{
	public $groupspp=25;//default users per page in listng
        public $gsearch="";//default user search string
	public $gpage=1;//defaukt users page
	public $log;
	private $smarty;
	public $query;
	function __construct(){
		$this->log=&get_logger();
		$this->smarty=&new Smarty();
		$this->query=&get_kdb_connection();
	}

	function main(){
		array_push($this->smarty->plugins_dir, kconf::kodform_plugin_dir);
		//form for displasy options	
		$form =& new kform("klist_group", &$this->smarty);
		$search =&new kinput("knamep",&$this->smarty);
		$form->add_input(&$search);
		if(isset($_GET['ksearch'])){
			$search->set_value($_GET['ksearch']);
			$this->gsearch=$_GET['ksearch'];
		}
		$grouppp =&new kddlist("kgroup_ppage",&$this->smarty);
		if(isset($_GET['kgpp'])){
			$grouppp->set_value($_GET['kgpp']);
			$this->groupspp=$_GET['kgpp'];
		}
		$form->add_input(&$grouppp);
		//user info for user groups
		$kuname =& new kinput("kuname", &$this->smarty);
		if(isset($_GET['uname']))
			$kuname->set_value($_GET['uname']);
		$form->add_input(&$kuname);
		$kuindex =& new kinput("uindex", &$this->smarty);
		if(isset($_GET['index']))
			$kuindex->set_value($_GET['index']);
		$form->add_input(&$kuindex);
		//submit button
		$form->add_submit(new ulist_group_submit(&$this->smarty, &$this));
		
		if((!$form->submited()) && isset($_GET['kgpp'])){
			$this->gpage = $_GET['p'];
		}	
		
		//set current page
		$this->smarty->assign("groupp", $this->gpage);
		
		$this->gpage--;
		$this->smarty->assign("groupspp", $this->groupspp);
		$this->smarty->assign("gsearch", $this->gsearch);
		
		$rez=false;
		$rez1=false;
		//no of pages
		$group_no=$this->get_no_pages($this->gsearch);
		$this->smarty->assign("no_of_groups", $group_no);
		//calculate no of pages	
		$no_of_pages = ceil($group_no/$this->groupspp);
		
		if($no_of_pages>0){
			$this->smarty->assign("no_of_pages",range(1,$no_of_pages));
			
			//fing groups on selected page
			$finded_groups =&$this->get_groups($this->gsearch,$this->gpage*$this->groupspp,$this->groupspp);
			$this->smarty->assign_by_ref("fgroups", &$finded_groups);
			//form for seting user groups
			$ug_form =& new kform("kuser_group", &$this->smarty);
			$ug_kuindex =& new kinput("ug_uindex", &$this->smarty);
			$ug_kuindex->set_value($kuindex->get_value());
			$ug_form->add_input(&$ug_kuindex);
			//$ug_uname = $kuname;
			//$ug_uname->name="ug_kuname";
			//$ug_form->add_input(&$ug_kuname);
			$ug_form->add_submit(new uuser_group_submit(&$this->smarty, &$this));
			//create checkboxes
			foreach($finded_groups as &$value){
				$cb =& new kcheckbox("g_".$value['index'],$this->smarty);
				$cb->value = $value['index'];
				$ug_form->add_input(&$cb);
				$value['cb']=&$cb;
			}
			//check if form is submited
			if($ug_form->submited())
				$this->smarty->assign("groups_changed", true);
			//find user groups
			$user_groups =& $this->get_user_groups($kuindex->get_value(), $this->gsearch, $this->gpage*$this->groupspp, $this->groupspp);
			foreach($finded_groups as &$value){
				if(in_array($value['index'], $user_groups))
					$value['cb']->checked=true;
			}
		}	
		klang::display(&$this->smarty,'kuser_group');
	}

		/*$smarty=&new Smarty();
		array_push($smarty->plugins_dir, kconf::kodform_plugin_dir);
		$form =& new kform("kuser_group", &$smarty);
		$kname =& new kinput("kname", &$smarty);
		if(isset($_GET['uname']))
			$kname->set_value($_GET['uname']);
		$form->add_input(&$kname);
		$kindex =& new kinput("index", &$smarty);
		if(isset($_GET['index']))
			$kindex->set_value($_GET['index']);
		$form->add_input(&$kindex);*/
		
	function get_no_pages($search_string){
		$group_no=0;
		//get number of groups from db
		try{
			$rez1 =&$this->query->execute("SELECT * FROM kaute.count_groups('".$search_string."')");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		if($row1 = $rez1->next())
			$group_no = $row1[0];
		return $group_no;
	}

	function &get_groups($search, $offset, $limit){
		//get all groups on current page
		$rez = null;
		try{
			$rez =&$this->query->execute("SELECT * FROM kaute.list_groups('".$search."',".$offset."::int2,".$limit."::int2)");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		$finded_groups = array();
		while($row=$rez->next())
			$finded_groups[]=$row;
		return $finded_groups;
	}

	function &get_user_groups($user, $search, $offset, $limit){
		//get users groups on current page
		try{
			$rez1 =&$this->query->execute("SELECT * FROM kaute.user_groups('".$search."',".$offset."::int2,".$limit."::int2, ".$user.")");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		$user_groups = array();
		while($row1 = $rez1->next())
			array_push($user_groups, $row1['group_index']);
		return $user_groups;
	}	
}

$ku_page =& new kuser_groups();
$ku_page->main();

?>
