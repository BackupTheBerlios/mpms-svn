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
*In this file is defiend kdb_connect class. It is used for db connections
*
*@author Boris Tomic
*@package kdb
*/

/**
*Defines exception which should be thrown when db connection error ocures.
*/
class kdb_con_err extends Exception{
	function __construct(){
		parent::__construct("Database Connection Error", 1);
	}
}

if(defined("POSTGRES")){

/**
*connection class
*
*Used when conection to database. It creates president connection to database if available.
*/
class kdb_connect{
	private $conn = false;
	/**
	*constructs new connection object. It also makes conection to db.
	*
	*@param string db_string connection string
	*/
	function __construct($db_string){
		$this->conn = pg_pconnect($db_string);
	}
	/**cleans connection*/
	function __destruct(){
		if($this->conn != false)
			pg_close($this->conn);
	}
	
	/** returns connection resource (object)
	*
	*If connection object is not valid throws kdb_conn_err
	*	
	*@returns resource
	*/
	public function &get_conn(){
		if($this->conn == false)
			throw new kdb_conn_err();
		return $this->conn;
	}
}

}//end of POSTGRES
?>
