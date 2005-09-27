<?php
/*
    kmmanger - module manager
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
require_once 'kconf/kconf.php';
require_once 'kaute/kauto.php';
require_once 'kfunctions/kmess_colector_class.php';
require_once 'kmmanager_config.php';


//authentification
$auth =& new kauth();

class kuser_prefs{	
	//wariables which holds users values
	public $lang;
	public $time;
	public $date;
	public $skin;
	public $tzone;
	//array of possible values
	private $plang = array("en","hr");
	private $pskin = array("default", "blue");
	function __construct(){
		$this->lang=kmmodule_conf::dlang;
		$this->time=kmmodule_conf::dtime;
		$this->date=kmmodule_conf::ddate;
		$this->skin=kmmodule_conf::dskin;
		$this->tzone=kmmodule_conf::dtzone;
		global $kmmlog;
		global $auth;
		$rez =null;
		$conn =& get_kdb_connection();
		try{
			$rez=&$conn->execute("SELECT * FROM kmmanager.load_prefs(".$auth->userindex."::int8);");
		}
		catch(Exception $e){
			$kmmlog->err($e->GetMessage());
			exit(1);
		}
		$row = $rez->next();
		if($row['user_index']!=NULL){
			if($row['lang']!=NULL)
				$this->lang=$row['lang'];
			if($row['time']!=NULL)
				$this->time=$row['time'];
			if($row['date']!=NULL)
				$this->date=$row['date'];
			if($row['skin']!=NULL)
				$this->skin=$row['skin'];
			if($row['zone']!=NULL)
				$this->tzone=$row['zone'];
		}
	}
}

$prefs =& new kuser_prefs();

class kmmanager{
	private $userp;//user preferences
	function __construct(){
		global $prefs;
		$this->userp =&$prefs;
	}
	/**returns skin dir which holds css files and js files*/
	private function get_skin(){
		if(file_exists(kskin_dir."/".$this->userp->skin)){
			return kskin_dir."/".$this->userp->skin;	
		}
		return kskin_dir."/default";
	}
	private function load_module(){
		global $__kmmodules;
		global $auth;
		global $prefs;
		if(isset($_GET['kmmodule']) && array_key_exists($_GET['kmmodule'], $__kmmodules))
			include kmodules_dir."/".$_GET['kmmodule']."/index.php";
		else
			include "index.php";
	}
	function display(){	
		global $__kmmodules;
		global $auth;
		if(isset($_GET['action']) && $_GET['action']=="logout"){
			$auth->logout("manager.php");
			return;
		}
		//set header
		$smarty =&new klangSmarty($this->userp->lang);	
		$smarty->assign_by_ref("modules", &$__kmmodules);
		//title
		$smarty->assign("page_title", "main");
		$smarty->assign("page_lang", $this->userp->lang);
		$smarty->assign("skin_dir", $this->get_skin());
		$smarty->display("header.tpl");
		$smarty->clear_all_assign();	
		//load middle
		$this->load_module();
		//set foot
		$smarty->display("foot.tpl");
	}
}
$my_manager=&new kmmanager();
$my_manager->display();
?>
