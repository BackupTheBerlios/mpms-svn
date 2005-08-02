<?php
/* 
kdb - simple database access library
Copyright (C) 2005 Boris Tomić

This library is free software; you can redistribute it and/or modify it
under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation; either version 2.1 of the License, or (at
your option) any later version.
    
This library is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser
General Public License for more details.
	
You should have received a copy of the GNU Lesser General Public License
along with this library; if not, write to the Free Software Foundation,
Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA 
*/

/**
*In this file is defined kdb_query class
*
*@author Boris Tomic
*@package kdb
*/

require_once 'kdb_connect.php';
require_once 'kdb_qrezult.php';

/**query exception*/
class kdb_query_err extends Exception{
	function __construct(){
		parent::__construct("SQL Query Error",1);
	}
}

if(defined("POSTGRES")){

/**query class
* Used for executing queries*/
class kdb_query{
	/**connection object - kdb_conn*/
	private $conn;
	/**just sets connection object
	*@param kdb_conn $conn connection object to be used for query*/
	function __construct(&$conn){
		$this->conn =& $conn;	
	}
	/**execute SQL query
	*@param string $query string containing SQL cuery
	*@return kdb_result kdb_rezult object containg rezults*/
	function &execute($query){
		$res =&pg_query($this->conn->get_conn(), $query);
		if($res == false)
			throw new kdb_query_err();
		return new kdb_qresult(&$res);
	}
	/**execute SQL query with given params
	*
	* it uses pg_query_params which is more prone to SQL injections*/
	function &query_params($query, $params){
		$res =&pg_query_params($this->conn->get_conn(),  $query, $params);
		if($res == false)
			throw new kdb_query_err();
		return new kdb_qresult(&$res);
	}
}

}//end of POSTGRES

?>
