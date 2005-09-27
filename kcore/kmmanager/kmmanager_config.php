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

/**log definition*/
$kmmlog =& Log::singleton("error_log", PEAR_LOG_TYPE_SYSTEM, "kmmanager");

class kmmodule_conf{
	/**default language*/
	const dlang="en";
	/**defualt time fromat*/
	const dtime="H:i:s";
	/**default date format*/
	const ddate="d.m.Y";
	/**default skin*/
	const dskin="default";
	/**defult timezone - in seconds from G*/
	const dtzone=7200;
	/**maximum query limit - maximum rows returned*/
	const dqlimit = 250;
}
/**module directory*/
define("kmodules_dir",kconf::install_root_dir."/kmodules");
/**skin directory*/
define("kskin_dir", "http://".kconf::host_name."/".kconf::host_dir."/kskins");
/**avaliable modules*/
$__kmmodules = array("kworktime"=> "WorkingTime", "kaddressbook" => "AddressBook", "ktasker" => "Tasker", "kinventory" => "Inventory", "koffers" => "Offers");

/**class used in as modules smarty. It only sets default smarty directories*/
class kmSmarty extends klangSmarty{
	function __construct($lang, $module, $debug=false){
		parent::__construct($lang, $debug);
		$this->template_dir=kmodules_dir."/".$module."/templates/";
	        $this->compile_dir=kmodules_dir."/".$module."/templates_c/";
		$this->config_dir=kmodules_dir."/".$module."/configs/";
		$this->cache_dir=kmodules_dir."/".$module."/cache/";
		//$this->debugging=true;
		array_push($this->plugins_dir, kconf::kodform_plugin_dir);
	}
}
?>
