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
*Here is definition of kdb_qresult. This class is used for manupilating results of db queres
*
*@author Boris Tomic
*@package kdb
*/

if(defined("POSTGRES")){

/**
*result class
*/
class kdb_qresult{
	public $res;	
	function __construct(&$pg_result){
		$this->res =& $pg_result;
	}
	/**
	*this function returns next row from query result resource
	*
	*If no more rows returns FALSE.
	*
	*Returns an array that corresponds to the fetched row (tuples/records).
	*
	*@return array
	*/
	function next(){
		return pg_fetch_array($this->res);
	}
	/**
	*returns number of rows in result resource
	*/	
	function count(){
		return pg_num_rows($this->res);
	}
	/**
	*returns number of affected rows. Used for INSERT, UPDATE, and DELETE queries.
	*/
	function affected_rows(){	
		return pg_affected_rows($this->res);
	}
	/**
	*resets row counter so that results can be read again with next.
	*/
	function reset(){
		pg_result_seek($this->res, 0);
	}
}

}//end of POSTGRESS

?>
