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

require 'kuadmin_check.php';

class new_user_submit extends ksubmit{
	private $admin;
	public $query;
	function __construct(&$smarty, &$admin){
		parent::__construct("new_user", &$smarty);
		$this->admin=&$admin;
		$this->query =& get_kdb_connection();
	}
	function submited(&$inputs){
		$uname=$inputs['kname']->get_value();
		$upass=$inputs['kpass']->get_value();
		if($upass==$inputs['kapass']->get_value()){
			$rez=false;
			try{
				$rez =&$this->query->execute("SELECT * FROM kaute.new_user('".$uname."','".sha1($uname.$upass)."');");
			}
			catch(Exception $e){
				$this->admin->log->emerg($e->getMessage());
				exit(1);
			}
			$row = $rez->next();
			if($row[0] == 1){
				$this->admin->log->warning("New user \"".$uname."\" created");
				$this->error=3;
				$inputs['kname']->set_value(null);
				return true;
			}
			else if($row[0]==2){
				$this->admin->log->warning("Could not create new user \"".$uname."\" because that username already exusts.");
				$this->error=2;
			}
			else{
				$this->admin->log->warning("Could not create new user \"".$uname."\".");
				$this->error=1;
			}
			
		}
		else{
			$this->error=4;
		}
		return false;
	}
}

class knew_user{
	public $log;
	private $smarty;
	
	function __construct(){
		$this->log=&get_logger();
		$this->smarty=&new klangSmarty();	
	}
	
	function new_user_UI(){
		array_push($this->smarty->plugins_dir, kconf::kodform_plugin_dir);
		
		$form =& new kform("knew_user", &$this->smarty);
		$form->add_input(new kinput("kname",&$this->smarty, new kv_min_max(kauth::min_name, kauth::max_name)));
		$form->add_input(new kinput("kpass",&$this->smarty, new kv_min_max(kauth::min_pass, kauth::max_pass)));
		$form->add_input(new kinput("kapass",&$this->smarty, new kv_min_max(kauth::min_pass, kauth::max_pass)));

		$form->add_submit(new new_user_submit(&$this->smarty, &$this));
		$this->smarty->assign("mess",1);
		if($form->submited())
			$this->smarty->assign("mess",0);
		$this->smarty->display('knew_user_en.tpl');
	}
}

$nu_page = new knew_user;
$nu_page->new_user_UI();
?>
