<?php

/*
    ksmess_conf - configuration file for mpms system messages library
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
/**
* This is solution for system messages for MPMS.
* @package ksmess
* @author Boris Tomic
*/
require_once '../kconf/kconf.php';

class ksmess_conf{
	const attachements_home="/home/ksmess/attachements";
	const att_dir_tree_deep=2;
}

//set log parameters

function & get_ksmess_logger(){
	return Log::singleton('error_log', PEAR_LOG_TYPE_SYSTEM, 'ksmess');
}


?>
