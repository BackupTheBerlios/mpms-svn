<?php
/*
    kstring_functions - some string functions needed by MPMS
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
/**some file io functions needed by MPMS
* @package kfunctions
* @author Boris Tomić*/


/**file name generator
* this function generate file random unique (there is big posibilite that file will be
unique because there is usage of timestamp) file name.
* @param int $deep deepth of random lowercase letters*/
function kfname_gen($deep){
	$name = "";
	for($i=0;$i<$deep;$i++)
		$name.=chr(rand(97,122));
	$name.=sha1($name.time());
	return $name;
}
?>
