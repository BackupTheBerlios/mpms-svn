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

/**defines knot_valid block
*@filesource
*@package kodform*/
/**
*Print content of kodform block if kinput element is not valid
*
*@param kinput $param['name'] element for which message will be displayed if not valid value set.
*/
function smarty_block_knot_valid($param, $content, &$smarty, &$repeat){
	$field =&$param['name'];
	if(!$field->is_valid())
		return $content;
}
?>
