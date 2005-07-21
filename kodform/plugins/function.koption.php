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

/**creates option tag

All parameters are same as with option html tag
*/
function smarty_function_koption($param, &$smarty){
	$select=&$param['name'];
	$string="<option";
	if($param['disabled']=="disabled")
		$string.=" disabled=\"disabled\"";
	if(isset($param['label']))
		$string.=" label=\"".$param['label']."\"";
	if($select->value==$param['value'])
		$string.=" selected=\"selected\"";
	else if($select->value==null && isset($param['selected']))
		$string.=" selected=\"selected\"";
	if(isset($param['value']))
		$string.=" value=\"".$param['value']."\"";
	$string.=">".$param['context']."</option>";
	return $string;
}

?>
