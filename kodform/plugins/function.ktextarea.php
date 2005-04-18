<?php
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
