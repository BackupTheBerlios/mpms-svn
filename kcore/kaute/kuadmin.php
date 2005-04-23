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

if(isset($_GET['logout'])){
	$auth->logout("kuadmin.php");
	exit(0);
}

class admin{
	private $smarty;
	function __construct(){
		$this->smarty=&new Smarty();
	}
	function main(){
		klang::display(&$this->smarty,"kuadmin");
	}
}

$padmin = new admin;
$padmin->main();

?>
