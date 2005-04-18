<?php
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
