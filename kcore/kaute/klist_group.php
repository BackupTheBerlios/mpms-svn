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
require_once kaute_conf::kodform_dir.'/kodform.php';
require_once kaute_conf::logger;

$auth = new kauth(kaute_conf::admin_group,"kuadmin.php");

class list_group_submit extends ksubmit{
	private $admin;
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

class klist_group{
	public $groupspp=25;//default users per page in listng
        public $gsearch="";//default user search string
	public $gpage=1;//defaukt users page
	public $log;
	private $smarty;
	private $query;
	function __construct(){
		$this->log=&get_logger();
		$this->smarty=&new Smarty();
		$this->query=&get_kdb_connection();
	}
	
	function group_list_UI(){
		array_push($this->smarty->plugins_dir, kaute_conf::kodform_plugin_dir);
		
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

		$form->add_submit(new list_group_submit(&$this->smarty, &$this));
		
		if((!$form->submited()) && isset($_GET['kgpp'])){
			//$this->userspp=$_GET['kupp'];
			//$this->usearch=$_GET['ksearch'];
			$this->gpage = $_GET['p'];
		}
		//set current page
		$this->smarty->assign("groupp", $this->gpage);
		
		$this->gpage--;
		$this->smarty->assign("groupspp", $this->groupspp);
		$this->smarty->assign("gsearch", $this->gsearch);
		
		$rez=false;
		$rez1=false;
		$group_no=0;
		//get number of users from db
		try{
			$rez1 =&$this->query->execute("SELECT * FROM kaute.count_groups('".$this->gsearch."')");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		if($row1 = $rez1->next())
			$group_no = $row1[0];	

		$this->smarty->assign("no_of_groups", $group_no);
		//calculate no of pages	
		$no_of_pages = ceil($group_no/$this->groupspp);
		if($no_of_pages>0){
			$this->smarty->assign("no_of_pages",range(1,$no_of_pages));
			//get users on current page
			try{
				$rez =&$this->query->execute("SELECT * FROM kaute.list_groups('".$this->gsearch."',".($this->groupspp*$this->gpage)."::int2,".$this->groupspp."::int2)");
			}
			catch(Exception $e){
				$this->log->emerg($e->getMessage());
				exit(1);
			}
			$finded_groups = array();
			$this->smarty->assign_by_ref("fgroups", &$finded_groups);
			while($row = $rez->next())
				array_push($finded_groups, $row);
		}	
		klang::display(&$this->smarty,'klist_group');
	}
}

$lu_page = new klist_group;
$lu_page->group_list_UI();

?>
