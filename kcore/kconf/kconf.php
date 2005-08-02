<?php
/*
    kconf - MPMS configuration
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

class kconf{
	//host
	const host_name="localhost";
	//host dir
	const host_dir="kcore";
	//home dir for kodmasin
	const install_root_dir = "/home/http/kcore";
	//kodform configuration
	const kodform_dir="/home/http/kodform";
	const kodform_plugin_dir="/home/http/kodform/plugins";
	//kdb configuration
	const kdb_dir="/home/http/kdb";
	const db_connect="host=localhost dbname=kodmasin user=kodmasin password=maskod";
	//logger librari path
	const logger="/usr/local/lib/php/Log.php";
	//which is superuser group
	const admin_group="admin";
	//directory separator
	const dir_sep = "/";
	//apache user
	const apache_user="nobody";
	const apache_group="kwww";	
	//klang dir
	const klang_dir = "/home/http/klang";
}


//set bd konection parameters
require_once kconf::kdb_dir."/kdb.php";
$kdb_conn = null;

function & get_kdb_connection(){
	global $kdb_conn;
	if($kdb_conn == null)
		$kdb_conn =&new kdb_query(new kdb_connect(kconf::db_connect));
	return $kdb_conn;
}


?>
