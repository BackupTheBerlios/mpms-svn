<?php
/*
    ktrans.php - php translation system based on iloc sistem
    Copyright (C) 2005  Boris Tomić

    This library is free software; you can redistribute it and/or
    modify it under the terms of the GNU Lesser General Public
    License as published by the Free Software Foundation; either
    version 2.1 of the License, or (at your option) any later version.

    This library is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public
    License along with this library; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'Smarty.class.php';
/**configuration class
* It holds all configuration variables*/
class klang_conf{
	/**used with sprintf to create regex to find all constans*/
	const regex_all="(.*?)";
	/**regex used to find constants (one and all)*/
	const regex_one="'{ki\s+const=\"%s\"\s*}((.|\n|\r|\s)*?){/ki}'";
}
/**smarty prefilter which is used to get translations from xml file and to replace constants with translations*/
function klang_smarty_prefilter($source, &$smarty){
	//get_translations if any
	$trans = array();
	$trans =& klang_load_translations($smarty->_tpl_vars['klang_trans_dir'].DIRECTORY_SEPARATOR.$smarty->_current_file.".xml", $smarty->_tpl_vars['klang_lang']);
	
	//get all posible constants
	$finded=array();
	$regex = sprintf(klang_conf::regex_one, klang_conf::regex_all);
	preg_match_all(sprintf(klang_conf::regex_one, klang_conf::regex_all),$source, &$finded);
	//number of finded constants
	$const_no=count($finded[1]);
	for($i=0;$i<$const_no;$i++){
		$translation="";
		if(array_key_exists($finded[1][$i], $trans)){
			$translation.=$trans[$finded[1][$i]];
			if($finded[2][$i]!=$trans[$finded[1][$i]]&&$smarty->_tpl_vars['klang_lang']=="" && $smarty->_tpl_vars['klang_debug']==true)
				$translation.="!!!UPDATE TRANSLATION!!!";//template have been modified
		}
		else{
			//there is constant with given name in translations
			$translation.=$finded[2][$i];
			if($smarty->_tpl_vars['klang_debug']==true)
				$translation.="!!!NO CONSTANT - ".$finded[1][$i]."!!!";
		}
		$source=preg_replace(sprintf(klang_conf::regex_one, $finded[1][$i]), $translation, $source);
	}
	
	return $source;
}
/**just helper function which loads translations from file*/
function &klang_load_translations($file_name, $lang){
	$trans = array();
	$doc = new DOMDocument();
	if(file_exists($file_name)){
	if($doc->load($file_name)){
		$constants = $doc->getElementsByTagName("constant");
		for($i=0;$i<$constants->length;$i++){
			$constant = $constants->item($i);
			$name = $constant->attributes->getNamedItem("name");
			$value=NULL;
			if($lang!==NULL){
				$translations = $constant->getElementsByTagName("translation");
				$j=0;
				while($j<$translations->length && $value===null){
					$tlang = $translations->item($j)->attributes->getNamedItem("lang");
					if($tlang->nodeValue == $lang){
						$value = $translations->item($j)->nodeValue;
					}
					++$j;
				}
				if($value===null)
					$value=iloc_get_default($constant);
			}
			else{
				$value=iloc_get_default($constant);
			}
			//if(isset($_GET['klang_translation_process']))
			//	$trans[$name->nodeValue]=$name->nodeValue."&gt;&gt;".$value;
			//else
				$trans[$name->nodeValue]=$value;
		}
	}
	else
		klang_trigger_error("Could not load translation file (".$file_name.")");
	}//end of file exists
	return $trans;
}
/**one more helper function for loading translations from file*/
function iloc_get_default($constant_node){
	$default=$constant_node->getElementsByTagName("default");
	return $default->item(0)->nodeValue;
}
/**helper function for producing error messages*/
function klang_trigger_error($error_msg, $error_type=E_USER_WARNING){
	trigger_error("klangSmarty error: ".$error_msg, $error_type);
}
/**this is function which implements printf PHP function
* I tought that smarty by default has that but could not find it
* is exist use default smartys function.
* It can take maksimum of 10 arguments and format string*/
function klang_printf($params, &$samrty){
	if(isset($params['format'])){
		$pf = array($params['format']);
		for($i=0;$i<10;$i++)
			if(isset($params['arg'.$i]))
				array_push($pf, $params['arg'.$i]);
		return call_user_func_array("sprintf", &$pf);
	}
}

/**this class extends Smarty class to add translation support*/
class klangSmarty extends Smarty {
	/**@var string holds laguage identifier*/
	public $lang;
	/**@var string holds directory where language translation files are.*/
	public $klang_trans_dir="klang";
	/**klang debugging*/
	public $klang_debug=false;
	/**constructor of this class
	* @param string $lang language identifier*/
	function __construct($lang="", $debug =false){
		parent::Smarty();
		$this->lang=$lang;
		$this->klang_debug = $debug;
		$this->register_prefilter("klang_smarty_prefilter");
		$this->register_function("kprintf", "klang_printf");
	}
	/**this is just overiden method of parent (Smarty)
	* it has same function, only addition is initalization of translations array*/
	function fetch($resource_name, $cache_id=null, $compile_id=null, $display=false){
		$this->assign("klang_lang", $this->lang);
		$this->assign("klang_trans_dir", $this->klang_trans_dir);
		$this->assign("klang_debug", $this->klang_debug);
		//check if file with tranaltions has changed and if it is force compile
		if(file_exists($this->klang_trans_dir.DIRECTORY_SEPARATOR.$resource_name.".xml"))
			if(filemtime($this->_get_auto_filename($this->compile_dir, $resource_name,$this->lang.$compile_id).".php")<filemtime($this->klang_trans_dir.DIRECTORY_SEPARATOR.$resource_name.".xml"))
				$this->force_compile=true;
		parent::fetch($resource_name, $cache_id, $this->lang.$compile_id, $display);
	}
}

?>
