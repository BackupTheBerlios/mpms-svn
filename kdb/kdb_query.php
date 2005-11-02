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
	function __construct($message="Unknown DB error."){
		parent::__construct($message ,1);
	}
}

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
	* this function uses standard pg_query function and pg_last_error
	* whih is not always most relaible method. You should use query_params
	* member functions which have grater chance to give you correct error
	* message. (see PHP postgres docs for more)
	*@param string $query string containing SQL cuery
	*@return kdb_result kdb_rezult object containg rezults*/
	function execute($query){
		$res =&pg_query($this->conn->get_conn(), $query);
		if($res == false)
			throw new kdb_query_err(pg_last_error($this->conn->get_conn()));
		return new kdb_qresult(&$res);
	}
	/**execute SQL query with given params
	*
	* it uses pg_query_params which is more prone to SQL injections*/
	function query_params($query, $params){
		//needed to execute only one query
		$filter_query = split(";",$query);
		$test = pg_send_query_params($this->conn->get_conn(),  $filter_query[0], $params);
		$res =& pg_get_result($this->conn->get_conn());
		if($test == false)
			throw new kdb_query_err();
		$this->check_status(&$res);
		return new kdb_qresult(&$res);
	}
	/**execute asyn query with given parameters
	*
	* sutable if you need to execute more then one query at the same time.
	* For now this function thorws error if eny of queres fails. So there is
	* possibility that query executes ok but you do not get nothing if next
	* query fails*/
	function &send_query_params($query, $params){
		$test = $pg_send_query_params($this->conn->get_conn(),  $query, $params);
		$ress =&new kdb_mresult();
		if($test == false)
			throw new kdb_query_err();
		else{
			while(($rez=&pg_get_rezult($this->conn->get_con()))){
				$ress->push(&$rez);
			}
		}
		return $ress;
	}
	/**this function is needed for*/
	static function check_status(&$res){
		$status = pg_result_status($res);
		switch($status){
			case PGSQL_EMPTY_QUERY:
				throw new kdb_query_err("Server got empty SQL query.");						
				break;
			case PGSQL_COMMAND_OK:
			case PGSQL_TUPLES_OK:
			case PGSQL_COPY_OUT:
			case PGSQL_COPY_IN:
				break;
			case PGSQL_BAD_RESPONSE:
				throw new kdb_query_err("Bad response from DB server.");				
				break;
			case PGSQL_NONFATAL_ERROR:
			case PGSQL_FATAL_ERROR:
				throw new kdb_query_err(pg_result_error($res));
				break;
		}
	}
}

?>
