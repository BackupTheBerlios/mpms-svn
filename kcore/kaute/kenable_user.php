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

class ena_user_submit extends ksubmit{
	private $query;
	private $log;
	function __construct(&$smarty){
		parent::__construct("ena_user", &$smarty);
		$this->query=get_kdb_connection();
		$this->log =& get_logger();
	}
	function submited(&$inputs){
		$rez=null;
		try{
			$rez =&$this->query->execute("SELECT * FROM kaute.dis_user(".$inputs['index']->get_value().", 0::int2);");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		$row = $rez->next();
		if($row[0] == 't'){	
			$this->log->notice("User ".$inputs['kname']->get_value()." has been enabled.");
			return true;
		}
		else{
			$this->error = 2;
			$this->log->notice("User ".$inputs['kname']->get_value()." could not been enable. Reason unknown.");
		}
		return false;
	}
}
class kena_user{
	function main(){
		$smarty =&new klangSmarty();
		array_push($smarty->plugins_dir, kconf::kodform_plugin_dir);
		$form =& new kform("kena_user", &$smarty);
		$kname =& new kinput("kname", &$smarty);
		if(isset($_GET['uname']))
			$kname->set_value($_GET['uname']);
		$form->add_input(&$kname);
		$kindex =& new kinput("index", &$smarty);
		if(isset($_GET['index']))
			$kindex->set_value($_GET['index']);
		$form->add_input(&$kindex);
		$form->add_submit(new ena_user_submit(&$smarty));
		if($form->submited())
			$smarty->assign("mess", 1);
		$smarty->display('kena_user_en.tpl');
	}
}

$kpage = new kena_user();
$kpage->main();

?>
