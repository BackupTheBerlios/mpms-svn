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
/**some general purpose functions needed by MPMS
* @package kfunctions
* @author Boris Tomić*/

/**function which generates random lower case strings
* @param int $size number of charachers of generated string
* @return string random generated string*/
function rstring($size){
	$string = "";
	for($i=0; $i<$size; $i++)
		$string.=chr(rand(97,122));
	return $string;
}

/**path string generation
* Generates path string. It is used when you need to store many files so you can put them in more folders which is faster to access then many file in one folder.
*<code>generate_path("/home/paths", "simeprtenjaca", 7, "/");
* //generates /home/paths/s/i/m/e/p/r/t/simeprtenjaca</code>
* @param string $home_dir home directoriy of new generated path
* @param string $string name of file
* @param int $deepth how deep path should be
* @param string $separator directory separator. It usualy depends on operating system.
* @return string returns new generated path or false if $string has less charachers than numbere assigned to $deepth*/
function generate_path($home_dir, $string, $deepth, $separator){
	$length = strlen($string);
	print $deepth;
	if($deepth<=$length){
		$i=0;
		while($i<$deepth){
			$home_dir.=$separator.$string[$i];	
			++$i;
		}
		return $home_dir.$separator.$string;
	}
	return false;	
}

?>
