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
*creates input tag
*
*This function simulate input tag for kodforms. It currently works for
*following types: text, password, submit, hidden, checkbox.
*
*@param array $param see smarty documenation.
*@param kinput $param['name'] element which describes this field.
*@param string $param['type'] type of input
*@param string $param['default'] default value if no value set
*@param string $param['label'] label for input element.
*@param string $param['classl'] class for label
*@param string $param['stylel'] style for label
*@param string $param['class'] class for input
*@param string $param['style'] style for input
*@param string $param['disabled'] set if button is disabled
*@return string returns generated HTML input element
*/
function smarty_function_kinput($param, &$smarty){
	$field=&$param['name'];
	$string="";	
	if($param['type']=="text" || $param['type']=="password"){
		if($param['type']=="text"){
			$value =$field->get_value();
			if($value == null){
				if(isset($param['default']))
					$value = $param['default'];
				else{
					$value = $field->default;
				}
			}
		}
		if(isset($param['label']))
			$string.="<label for=\"".$field->name."\" class=\"".$param['classl']."\" style=\"".$param['stylel']."\">".$param['label'].":</label>";
		$string.="<input type=\"".$param['type']."\" id=\"".$field->name."\" name=\"".$field->name."\" value=\"".$value."\" class=\"".$param['class']."\" style=\"".$param['style']."\"";
		if(isset($param['disabled']))
			$string.=" disabled=\"disabled\"";
	}
	else if($param['type']=="submit"){
		$string.="<input type=\"".$param['type']."\" id=\"".$field->name."\" name=\"".$field->name."\" value=\"".$param['label']."\" class=\"".$param['class']."\" style=\"".$param['style']."\"";
		if(isset($param['disabled']))
			$string.=" disabled=\"disabled\"";
	}
	else if($param['type']=="hidden")
		$string.="<input type=\"".$param['type']."\" id=\"".$field->name."\" name=\"".$field->name."\" value=\"".$field->get_value()."\"";
	else if($param['type']=="checkbox"){
		if(isset($param['label']))
			$string.="<label for=\"".$field->name."\" class=\"".$param['classl']."\" style=\"".$param['stylel']."\">".$param['label'].":</label>";
		$string.="<input type=\"".$param['type']."\" id=\"".$field->name."\" name=\"".$field->name."\" value=\"".$field->get_value()."\"";
		if($field->checked)
			$string.=" checked=\"checked\"";
		if(isset($param['disabled']))
			$string.=" disabled=\"disabled\"";
	}
	$string.=" />";
	return $string;
}
?>
