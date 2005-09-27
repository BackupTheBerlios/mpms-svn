<?php
/*
    phpPrefs - application preferences library
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
/**this is simple serialization class
*
* It saves object as function to some php file
* and when it need it it just included that file and
* call that function*/
class kser{
	/**variable which holds name of php file and function which will be
	* used for saving serialized object*/
	protected $file;
	/**creates object which can use this kind ofserialization
	* @param string $name this name this object when serialized. It is also
	* function name and php file name where data will be stored*/
	function __construct($name){
		$this->file = $name;
	}
	/**call this when you want to  serialize object
	* @return boolean true if object is serialized otherwise false*/
	function serialize(){
		$fp = fopen($this->file.".php", "w");
		if($fp){
			$code = "<?php function ".$this->file."(){\nreturn \"".base64_encode(serialize($this))."\";\n}?>";
			fputs($fp, $code);
			fclose($fp);
			return true;
		}
		return false;
	}
	/**when you want to get serialized objet.
	*
	* You must get it throught return value otherwise would not work as
	* you can not assign value to $this. 
	* @return mixed unserialized object*/
	function unserialize(){
		if(file_exists($this->file.".php")){
			include $this->file.".php";
			return unserialize(base64_decode(call_user_func($this->file)));
		}
	}	
}
?>