<?php

/*
    test_ksmess.php - tests for mpms system messages library
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
require_once '../ksmess.php';

//initase engine
$engine =& new ksmess_engine();
//create message to send
$message =& new ksmessage_in(15,11,1,"Test 1", "Ovo je prvi test sa attachmentom!!!");
$message->add_attachment("text.txt", "text/plain", new ksh_memory("Ovo je text u attachementu. Da vidimo."));

$engine->send($message);
$messages = $engine->check(11);
foreach($messages as $value){
	//$engine->mdelete($value);kfname_gen(ksmess_conf::att_dir_tree_deep)
	$rec =& $engine->receive($value);
	$no = $rec->count_attach();
	for($i=0;$i<$no;$i++){
		$att =& $rec->get_attachment($i);
		$att_data=fread($att["fp"]->fp, 29000);
		print $att_data;
	}
	//var_dump($rec);
}

?>
