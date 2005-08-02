<?php
//mmodule index.php - first file which will be opened if no module selected
/*
    kmmanger (index.php)- module manager
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

require_once 'kmmanager_config.php';

if(!isset($auth)){
	$kmmlog->debug("auth variable is not set. Exiting.");
	exit(0);
}
if(!$auth->check()){
	$kmmlog->err("Authentification error. Exiting");
	exit(1);
}

$smarty =& new klangSmarty();
$smarty->display("index.tpl");


?>
