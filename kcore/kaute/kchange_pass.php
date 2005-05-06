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

class change_pass_submit extends ksubmit{
	private $cpass;
	private $query;
	private $log;
	function __construct(&$cpass){
		parent::__construct("change_pass", &$cpass->smarty);
		$this->cpass=&$cpass;
		$this->query=&get_kdb_connection();
		$this->log =& get_logger();
	}
	function submited(&$inputs){
		$pass = $inputs['passwd']->get_value();
		if($pass == $inputs['passwdr']->get_value()){
			$rez=null;
			try{
				$rez =&$this->query->execute("SELECT * FROM kaute.chpass_user(".$inputs['index']->get_value().",'".sha1($inputs['kname']->get_value().$pass)."')");
			}
			catch(Exception $e){
				$this->log->emerg($e->getMessage());
				exit(1);
			}
			$row = $rez->next();
			if($row[0] == 't'){
				
				$this->log->notice("Password for user \"".$inputs['kname']->get_value()."\" has been changed.");
				return true;
			}
			else
				$this->error = 2;
		}
		else
			$this->error = 3;
		return false;
	}
}

class kchange_pass{
	public $smarty;
	function __construct(){
		$this->smarty=&new Smarty();
		array_push($this->smarty->plugins_dir, kconf::kodform_plugin_dir);
	}
	function main(){
		$form =& new kform("klist_user", &$this->smarty);
		$kname =& new kinput("kname", &$this->smarty);
		if(isset($_GET['uname']))
			$kname->set_value($_GET['uname']);
		$form->add_input(&$kname);
		$kindex =& new kinput("index", &$this->smarty);
		if(isset($_GET['index']))
			$kindex->set_value($_GET['index']);
		$form->add_input(&$kindex);
		$form->add_input(new kinput("passwd", &$this->smarty, new kv_min_max(kauth::min_pass, kauth::max_pass)));
		$form->add_input(new kinput("passwdr", &$this->smarty, new kv_min_max(kauth::min_pass, kauth::max_pass)));
		$form->add_submit(new change_pass_submit(&$this));
		if($form->submited())
			$this->smarty->assign("mess", 1);
		klang::display(&$this->smarty,'kchange_pass_start');	
	}
}
$chpage =&new kchange_pass;
$chpage->main();
?>
