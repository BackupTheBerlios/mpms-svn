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

$auth = new kauth(null,"kuadmin.php");

class list_user_submit extends ksubmit{
	private $admin;
	function __construct(&$smarty, &$admin){
		parent::__construct("list_user", &$smarty);
		$this->admin=&$admin;
	}
	function submited(&$inputs){
		$this->admin->userspp=$inputs['kusr_ppage']->value;
		$this->admin->usearch=$inputs['knamep']->get_value();
		return true;
	}
}

class klist_user{
	public $userspp=25;//default users per page in listng
        public $usearch="";//default user search string
	public $upage=1;//defaukt users page
	public $log;
	private $smarty;
	private $query;
	function __construct(){
		$this->log=&get_logger();
		$this->smarty=&new Smarty();
		$this->query=&get_kdb_connection();
	}

	function user_list_UI(){
		array_push($this->smarty->plugins_dir, kaute_conf::kodform_plugin_dir);
		
		$form =& new kform("klist_user", &$this->smarty);
		$search =&new kinput("knamep",&$this->smarty);
		$form->add_input(&$search);
		if(isset($_GET['ksearch'])){
			$search->set_value($_GET['ksearch']);
			$this->usearch=$_GET['ksearch'];
		}
		$userpp =&new kddlist("kusr_ppage",&$this->smarty);
		if(isset($_GET['kupp'])){
			$userpp->set_value($_GET['kupp']);
			$this->userspp=$_GET['kupp'];
		}
		$form->add_input(&$userpp);

		$form->add_submit(new list_user_submit(&$this->smarty, &$this));
		
		if((!$form->submited()) && isset($_GET['kupp'])){
			//$this->userspp=$_GET['kupp'];
			//$this->usearch=$_GET['ksearch'];
			$this->upage = $_GET['p'];
		}
		//set current page
		$this->smarty->assign("userp", $this->upage);
		
		$this->upage--;
		$this->smarty->assign("userspp", $this->userspp);
		$this->smarty->assign("usearch", $this->usearch);
		$this->smarty->assign("failed_limit", kauth::auth_fail_limit);
		
		$rez=false;
		$rez1=false;
		$user_no=0;
		//get number of users from db
		try{
			$rez1 =&$this->query->execute("SELECT * FROM kaute.count_users('".$this->usearch."')");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		if($row1 = $rez1->next())
			$user_no = $row1[0];	

		$this->smarty->assign("no_of_users", $user_no);
		//calculate no of pages	
		$no_of_pages = ceil($user_no/$this->userspp);
		if($no_of_pages>0){
			$this->smarty->assign("no_of_pages",range(1,$no_of_pages));
			//get users on current page
			try{
				$rez =&$this->query->execute("SELECT * FROM kaute.list_users('".$this->usearch."',".($this->userspp*$this->upage)."::int2,".$this->userspp."::int2)");
			}
			catch(Exception $e){
				$this->log->emerg($e->getMessage());
				exit(1);
			}
			$finded_users = array();
			$this->smarty->assign_by_ref("fusers", &$finded_users);
			while($row = $rez->next())
				array_push($finded_users, $row);
		}	
		klang::display(&$this->smarty,'klist_user');
	}
}

$lu_page = new klist_user;
$lu_page->user_list_UI();
?>
