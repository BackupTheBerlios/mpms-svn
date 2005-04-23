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

class dis_user_submit extends ksubmit{
	private $query;
	private $log;
	function __construct(&$smarty){
		parent::__construct("dis_user", &$smarty);
		$this->query=get_kdb_connection();
		$this->log =& get_logger();
	}
	function submited(&$inputs){
		$rez=null;
		try{
			$rez =&$this->query->execute("SELECT * FROM kaute.dis_user(".$inputs['index']->get_value().", ".kauth::auth_fail_limit."::int2);");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		$row = $rez->next();
		if($row[0] == 't'){	
			$this->log->notice("User ".$inputs['kname']->get_value()." has been disabled.");
			return true;
		}
		else{
			$this->error = 2;
			$this->log->notice("User ".$inputs['kname']->get_value()." could not been disabled. Reason unknown.");
		}
		return false;
	}
}
class kdis_user{
	function main(){
		$smarty =&new Smarty();
		array_push($smarty->plugins_dir, kaute_conf::kodform_plugin_dir);
		$form =& new kform("kdis_user", &$smarty);
		$kname =& new kinput("kname", &$smarty);
		if(isset($_GET['uname']))
			$kname->set_value($_GET['uname']);
		$form->add_input(&$kname);
		$kindex =& new kinput("index", &$smarty);
		if(isset($_GET['index']))
			$kindex->set_value($_GET['index']);
		$form->add_input(&$kindex);
		$form->add_submit(new dis_user_submit(&$smarty));
		if($form->submited())
			$smarty->assign("mess", 1);
		klang::display(&$smarty,'kdis_user');
	}
}

$kpage = new kdis_user();
$kpage->main();

?>
