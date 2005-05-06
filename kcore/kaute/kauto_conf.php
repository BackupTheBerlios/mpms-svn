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

require_once '../kconf/kconf.php';

require_once kconf::kdb_dir."/kdb.php";

//set bd konection parameters
$kdb_conn = null;

function & get_kdb_connection(){
	global $kdb_conn;
	if($kdb_conn == null)
		$kdb_conn =&new kdb_query(new kdb_connect(kconf::db_connect));
	return $kdb_conn;
}

//set log parameters
$klogger = null;

function & get_logger(){
	global $klogger;
	if($klogger == null)
		$klogger =&Log::singleton('error_log', PEAR_LOG_TYPE_SYSTEM, 'kauth');
	return $klogger;
}

?>
