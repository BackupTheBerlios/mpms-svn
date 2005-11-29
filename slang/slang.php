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
class sLangConf{
	/**used with sprintf to create regex to find all constans*/
	const regexAll="(.*?)";
	/**regex used to find constants (one and all)*/
	const regexOne="'{ki\s+const=\"%s\"\s*}((.|\n|\r|\s)*?){/ki}'";
}
/**source of strnslations*/
interface iSlangTransSource{
	public function &getTrans($dir, $file, $lang);
	public function getFileName($dir, $file, $lang);
}

/**php implementation of iSlangTransS*/
class sLangPHP implements iSlangTransSource{
	function &getTrans($dir, $file, $lang){
		$trans = include($this->getFileName($dir, $file, $lang));
		return $trans;
	}
	function getFileName($dir, $file, $lang){
		return $dir . DIRECTORY_SEPARATOR . $file . "_" . $lang . ".php";
	}
}

/**iloc implementation of iSlangTranS*/
class sLangIlok implements iSlangTransSource{
	function getFileName($dir, $file, $lang){
		return $dir . DIRECTORY_SEPARATOR . $file . ".xml";
	}
	function &getTrans($dir, $file, $lang){
		$trans = array();
		$doc = new DOMDocument();
		$fileName = $dir . DIRECTORY_SEPARATOR . $file . ".xml";
		if(file_exists($fileName)){
			if($doc->load($fileName)){
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
							$value=$this->ilocGetDefault($constant);
					}
					else{
						$value=$this->ilocGetDefault($constant);
					}
					//if(isset($_GET['klang_translation_process']))
					//	$trans[$name->nodeValue]=$name->nodeValue."&gt;&gt;".$value;
					//else
					$trans[$name->nodeValue]=$value;
				}
			}
			else
				slangTriggerError("Could not load translation file (".$file_name.")");
		}//end of file exists
		return $trans;
	}
	private function ilocGetDefault($constantNode){
		$default=$constantNode->getElementsByTagName("default");
		return $default->item(0)->nodeValue;
	}
}
/**smarty prefilter which is used to get translations from xml file and to replace constants with translations*/
function slangSmartyPrefilter($source, &$smarty){
	//get_translations if any
	$trans = array();
	$trans =& $smarty->get_template_vars("sLangSourceObj")->getTrans($smarty->get_template_vars("sLangTransDir"), $smarty->_current_file, $smarty->get_template_vars("lang"));
	
	//get all posible constants
	$finded = array();
	$regex = sprintf(sLangConf::regexOne, sLangConf::regexAll);
	preg_match_all(sprintf(sLangConf::regexOne, sLangConf::regexAll),$source, &$finded);
	//number of finded constants
	$const_no = count($finded[1]);
	for($i = 0; $i < $const_no; $i++){
		$translation = "";
		if(array_key_exists($finded[1][$i], $trans)){
			$translation .= $trans[$finded[1][$i]];
			if($finded[2][$i] != $trans[$finded[1][$i]] && $smarty->get_template_vars("lang") == "" && $smarty->get_template_vars("sLangDebug") == true)
				$translation .= "!!!UPDATE TRANSLATION!!!";//template have been modified
		}
		else{
			//there is constant with given name in translations
			$translation .= $finded[2][$i];
			if($smarty->get_template_vars("sLangDebug") == true)
				$translation .= "!!!NO CONSTANT - " . $finded[1][$i] . "!!!";
		}
		$source=preg_replace(sprintf(slangConf::regexOne, $finded[1][$i]), $translation, $source);
	}
	
	return $source;
}

/**helper function for producing error messages*/
function slangTriggerError($error_msg, $error_type=E_USER_WARNING){
	trigger_error("slang error: ".$error_msg, $error_type);
}
/**this is function which implements printf PHP function
* I tought that smarty by default has that but could not find it
* if exist use default smartys function.
* This function is obsolete - use dynamic translations instead
* It can take maksimum of 10 arguments and format string*/
function slangPrintf($params, &$smarty){
	if(isset($params['format'])){
		$pf = array($params['format']);
		for($i=0;$i<10;$i++)
			if(isset($params['arg'.$i]))
				array_push($pf, $params['arg'.$i]);
		return call_user_func_array("sprintf", &$pf);
	}
}
/**this is helper function with wich you can create arrays in templates
* very usefull when you need to create array with translatable variables
* This function is obsolete - use dynamic translations instead*/
function slangArray( $params, $content, &$smarty, &$repeat){
	if(isset($params['name']) && isset($params['key'])){
		$karray =& $smarty->get_template_vars($params['name']);
		if($karray===null){
			$smarty->assign($params['name'], array($params['key']=> $content));
		}
		else{
			$karray[$params['key']]=$content;			
		}
	}
}
/**
* This function is obsolete - use dynamic translations instead*/
function slangGetArrayValue($params, &$smarty){
	if(isset($params['name']) && isset($params['key'])){
		$karray =& $smarty->get_template_vars($params['name']);
		if($karray !== null){
			return $karray[$params['key']];
		}
	}
	return;
}

/**this class extends Smarty class to add translation support*/
class slangSmarty extends Smarty {
	/**@var string holds laguage identifier*/
	public $lang;
	/**@var string holds directory where language translation files are.*/
	public $sLangTransDir = "slang";
	/**klang debugging*/
	public $sLangDebug = false;
	/**transsource object */
	public $sLangSourceObj;
	/**constructor of this class
	* @param string $lang language identifier*/
	function __construct($lang = "", iSlangTransSource &$sObj = null, $debug = false, $langDir = "slang"){
		parent::Smarty();
		$this->lang = $lang;
		if($sObj == null)
			$this->sLangSourceObj = new sLangIlok();
		else
			$this->sLangSourceObj =& $sObj;
		$this->sLangDebug = $debug;
		$this->sLangTransDir = $langDir;
		$this->register_prefilter("slangSmartyPrefilter");
		$this->register_function("kprintf", "slangPrintf");
		$this->register_function("kgetavalue", "slangGetArrayValue");
		$this->register_block("karray", "slangArray");
		//this is because in prefilter smarty is not this class it is Smarty_Compiler
		$this->assign_by_ref("sLangSourceObj", $this->sLangSourceObj);
		$this->assign_by_ref("sLangDebug", $this->sLangDebug);
		$this->assign_by_ref("lang", $this->lang);
		$this->assign_by_ref("sLangTransDir", $this->sLangTransDir);
	}
	/**this is just overiden method of parent (Smarty)
	* it has same function, only addition is initalization of translations array*/
	function fetch($resource_name, $cache_id=null, $compile_id=null, $display=false){
		//get file name of trans file
		$langFile = $this->sLangSourceObj->getFileName($this->sLangTransDir, $resource_name, $this->lang);
		//check if file with tranaltions has changed and if it is force compile
		if(file_exists($langFile))
			if(filemtime($this->_get_auto_filename($this->compile_dir, $resource_name,$this->lang . $compile_id) . ".php")<filemtime($langFile))
				$this->force_compile=true;
		parent::fetch($resource_name, $cache_id, $this->lang . $compile_id, $display);
	}
}

?>
