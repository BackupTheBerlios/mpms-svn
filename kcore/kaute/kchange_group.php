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

class change_group_submit extends ksubmit{
	private $query;
	private $log;
	function __construct(&$smarty){
		parent::__construct("change_group", &$smarty);
		$this->query=get_kdb_connection();
		$this->log =& get_logger();
	}
	function submited(&$inputs){
		$rez=null;
		$is_system="false";
		if($inputs['system']->checked)
			$is_system="true";
		try{
			$rez =&$this->query->execute("SELECT * FROM kaute.change_group('".$inputs['description']->get_value()."',".$inputs['index']->get_value().",".$is_system.")");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		$row = $rez->next();
		if($row[0] == 't'){
			$this->log->notice("Group \"".$inputs['kname']->get_value()."\" has been changed.");
			return true;
		}
		$this->error = 2;
		$this->log->notice("Group \"".$inputs['kname']->get_value()."\" has not been changed. Reason unknown.");
		return false;
	}
}

class kchange_group{
	public $smarty;
	private $query;
	private $log;
	function __construct(){
		$this->smarty=&new klangSmarty();
		array_push($this->smarty->plugins_dir, kconf::kodform_plugin_dir);
		$this->query= &get_kdb_connection();
		$this->log =& get_logger();
	}
	function main(){	
		$form =& new kform("kchange_group", &$this->smarty);
		$kname =& new kinput("kname", &$this->smarty,  new kv_min_max(1, 20));	
		$form->add_input(&$kname);
		$kindex =& new kinput("index", &$this->smarty);
		$form->add_input(&$kindex);
		$kdesc =& new kinput("description", &$this->smarty, new kv_min_max(0, 200));
		
		$form->add_input(&$kdesc);
		$ksys =& new kcheckbox("system", &$this->smarty);
		$form->add_input(&$ksys);

		$form->add_submit(new change_group_submit(&$this->smarty));
		//get data from database
		$rez=null;
		$row=false;
		try{
			if(isset($_GET['index'])){
				$rez =&$this->query->execute("SELECT * FROM kaute.get_group(".((int)$_GET['index']).")");
			
				$row = $rez->next();
		
				if($row != false){
					//set proper form field values
					$kname->set_value($row['name']);
					$kindex->set_value($row['index']);
					$kdesc->set_value($row['description']);
					if($row['system']=='t')
						$ksys->checked=true;
				}
			}
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		
		if($form->submited())
			$this->smarty->assign("mess", 1);
		//if fields values are not set I do not know what to do
		else if($kname->get_value()==null){
			$this->smarty->assign("mess", 2);
		}
		//klang::display(&$this->smarty,'kchange_group');
		$this->smarty->display("kchange_group_en.tpl");
	}
}
$chpage =&new kchange_group;
$chpage->main();

?>
