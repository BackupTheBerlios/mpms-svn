<?php
/*
    kconf - authentification and access library
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
require_once kconf::klang_dir.'/klang.php';;

class kauto_conf{
	/**where are */
	const trans_dir="ilang";
}

//set log parameters
$klogger = null;

//this name should be changes to more specific name for kauth logger
//singelton method is doing this thing so it sould be changed completely
function & get_logger(){
	global $klogger;
	if($klogger == null)
		$klogger =&Log::singleton('error_log', PEAR_LOG_TYPE_SYSTEM, 'kauth');
	return $klogger;
}

class kautoSmarty extends klangSmarty{
	function __construct($lang="", $debug=false){
		parent::__construct($lang,$debug);
		$this->template_dir=kconf::install_root_dir."/kaute/templates";
		$this->compile_dir=kconf::install_root_dir."/kaute/templates_c";
		$this->config_dir=kconf::install_root_dir."/kaute/config";
		$this->cache_dir=kconf::install_root_dir."/kaute/cache";
		$this->assign("css_dir","http://".kconf::host_name."/kcore/kaute/css");
	}
}

?>
