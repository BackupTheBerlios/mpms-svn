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

$auth = new kauth("kauto_test.php");

if(!$auth->check_group("test")){
	$smarty =& new Smarty();
	$smarty->display("kno_premission_en.tpl");
	exit(0);
}

var_dump($auth);
if(isset($_GET['logout'])){
	$auth->logout("kauto_test.php");
	exit(0);
}

var_dump($_SESSION);

?>
<a href="kauto_test.php?logout=yes">Logout</a>
