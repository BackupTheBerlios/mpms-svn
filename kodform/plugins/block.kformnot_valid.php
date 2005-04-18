<?php
/**Defines kformnot_valid block
*@package kodform
*@filesource
*/

/**
*prints content if from input is not valid
*
*@param string $params['name'] name of kfrom component
*/
function smarty_block_kformnot_valid($params, $content, &$smarty, &$repeat){
	if($content != null && (!($params['name']->is_valid()))){
		return $content;
	}
}

?>
