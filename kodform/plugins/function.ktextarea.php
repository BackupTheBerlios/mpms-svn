<?php
/* 
kodform - simple html form php library
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
*@filesource
*@package kodform
*/

/**
*creates textarea tag
*
*This function simulate textarea tag for kodforms
*
*/
function smarty_function_ktextarea($param, &$smarty){
	$field=&$param['name'];
	$string="";
	if(isset($param['label']))
		$string.="<label for=\"".$field->name."\" class=\"".$param['lclass']."\" style=\"".$param['lstyle']."\">".$param['label'].":</label><br />";
	$string.="<textarea id=\"".$field->name."\" name=\"".$field->name."\"  cols=\"".$param['cols']."\" rows=\"".$param['rows']."\" class=\"".$param['class']."\" style=\"".$param['style']."\"";
	if(isset($param['readonly']))
		$string.="readonly=\"readonly\"";
	if(isset($param['disabled']))
		$string.="disabled=\"disabled\"";
	$string.=">";
	if(isset($param['valid']))
		$string.=$field->get_value();
	else
		$string.=$field->get_value(true);
	$string.="</textarea>";
	return $string;
}
?>
