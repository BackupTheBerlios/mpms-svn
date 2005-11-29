<?php

interface iDynTrans{
	public function translate($text, $index = "");
}

/**php version of dynamic translations*/
class dynPHP implements iDynTrans{
	private $trans;
	function __construct($lang, $name, $dir = "dyntrans"){
		$fileName = $dir . DIRECTORY_SEPARATOR . $name . "_" . $lang . ".php";
		if(file_exists($fileName))
			$this->trans = include($fileName);
		else
			$this->trans = array();
	}
	function translate($text, $index = ""){
		if(array_key_exists($index, $this->trans))
			return $this->trans[$index];
		return $text;
	}
}

/**iloc version of dinymic translations*/
class dynIloc implements iDynTrans{
	protected $dir;
	protected $file;
	protected $lang;
	private $trans;
	function __construct($lang, $name, $dir = "dyntrans"){
		$this->dir = $dir;
		$this->file = $name;
		$this->lang = $lang;
		$this->trans =& $this->getDynTrans();
	}	
	private function &getDynTrans(){
		$trans = array();
		$dynDir = $this->dir . DIRECTORY_SEPARATOR;
		$fileDynName = $dynDir . $this->file;
		$xmlDynName = $fileDynName . ".xml";
		$phpDynName = $fileDynName . "_" . $this->lang . ".php";
		if(file_exists($xmlDynName)){
			if(file_exists($phpDynName)){
				if(filemtime($xmlDynName) > filemtime($phpDynName)){
					$trans =& $this->getTrans($dynDir, $this->file, $this->lang);
					$this->createPHPDyn($phpDynName, &$trans);
				}
				else{
					$trans = include($phpDynName);
				}
			}
			else{
				$trans =& $this->getTrans($dynDir, $this->file, $this->lang);
				$this->createPHPDyn($phpDynName, &$trans);
			}
		}
		return $trans;
	}
	private function createPHPDyn($file, &$trans){
		//write new translations to php chache
		$fp = fopen($file, "w");
		if($fp != false){
			fwrite($fp, "<?php\n\$trans = array();\n");
			foreach($trans as $key => $value){
				fwrite($fp, "\$trans[\"" . $key . "\"] = \"" . $value . "\";\n");
			}
			fwrite($fp, "return \$trans;?>");
			fclose($fp);
		}
	}
	public function translate($text, $index = ""){
		if(array_key_exists($index, $this->trans))
			return $this->trans[$index];
		return $text;
	}
	private function &getTrans($dir, $file, $lang){
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
		}//end of file exists
		return $trans;
	}
	private function ilocGetDefault($constantNode){
		$default=$constantNode->getElementsByTagName("default");
		return $default->item(0)->nodeValue;
	}
}



?>