<?php
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
*@return string returns generated HTML input element
*/
function smarty_function_kinput($param, &$smarty){
	$field=&$param['name'];
	$string="";	
	if($param['type']=="text" || $param['type']=="password"){
		if($param['type']=="text"){
			$value =$field->get_value();
			if($value == null && isset($param['default']))
				$value = $param['default'];
		}
		if(isset($param['label']))
			$string.="<label for=\"".$field->name."\" class=\"".$param['classl']."\" style=\"".$param['stylel']."\">".$param['label'].":</label>";
		$string.="<input type=\"".$param['type']."\" id=\"".$field->name."\" name=\"".$field->name."\" value=\"".$value."\" class=\"".$param['class']."\" style=\"".$param['style']."\" />";
	}
	else if($param['type']=="submit")
		$string.="<input type=\"".$param['type']."\" id=\"".$field->name."\" name=\"".$field->name."\" value=\"".$param['label']."\" class=\"".$param['class']."\" style=\"".$param['style']."\" />";
	else if($param['type']=="hidden")
		$string.="<input type=\"".$param['type']."\" id=\"".$field->name."\" name=\"".$field->name."\" value=\"".$field->get_value()."\" />";
	else if($param['type']=="checkbox"){
		if(isset($param['label']))
			$string.="<label for=\"".$field->name."\" class=\"".$param['classl']."\" style=\"".$param['stylel']."\">".$param['label'].":</label>";
		$string.="<input type=\"".$param['type']."\" id=\"".$field->name."\" name=\"".$field->name."\" value=\"".$field->get_value()."\"";
		if($field->checked)
			$string.=" checked=\"checked\"";
		$string.="\" />";
	}
	return $string;
}
?>
