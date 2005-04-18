<?php
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
