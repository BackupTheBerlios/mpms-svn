#!/usr/local/bin/php
<?php
//config section
define("kphp_lib_dir","/home/http/kphp_lib");
define("iloc_host","http://localhost");
//end of config

set_include_path(get_include_path().":".kphp_lib_dir);

require_once 'klang.php';
require_once 'getopt.php';


class update_options extends getopt{
	function __construct(){
		parent::__construct("cdfl:t:");
	}
	function usage($error){
		parent::usage($error);
		$this->usage_help();
	}
	function usage_help(){
		print "
Usage:
	php update_translation.php -l translation_file -t template_file [options]

Options:
	-c	create translation file if it does not exist
	-d 	delete translations which are not finded in template
	-f 	force saving of new lang file eaven if it was updated by anoder application
	-l	full path to translations file		
	-t	full path to smarty template file
";
	}
}

function kerror($mess){
	print $mess."\n";
	exit();
}

$opts =& new update_options();

$template = $opts->options["-t"];
$lang_file = $opts->options["-l"];
if($template=="" && $lang_file==""){
	print "Required arguments (-l, -t) are missing.\n\n";
	exit(0);
}

if(!@file_exists($template))
	kerror("File ".$template." dose not exists.");
	
//nadi sve konstante i prijevode u sourceu
$finded=array();
if(($temp = @file_get_contents($template))){
	//get all posible constants
	preg_match_all(sprintf(klang_conf::regex_one, klang_conf::regex_all),$temp, &$finded);
}
else{
	kerror("File ".$template." can not be read.");
}

$trans_mtime=@filemtime($lang_file);

//holds dom structure with translations
$langs=null;

if(!@file_exists($lang_file)){
	if(array_key_exists("-c", $opts->options)){
		$langs = DOMImplementation::createDocument(null, "iloc", DOMImplementation::createDocumentType("iloc",null,iloc_host."/iloc/xml/iloc.dtd"));
		$dom_root = $langs->documentElement;
		$dom_root->setAttribute("version", "0.1");
		$lan=$langs->createElement("langs");
		$consts = $langs->createElement("constants");
		$dom_root->appendChild($lan);
		$dom_root->appendChild($consts);
	}
	else{
		kerror("File ".$lang_file." dose not exists.");
		exit(1);
	}
}
else
	$langs =& DOMDocument::load($lang_file);
if($langs->validate()){
	$del_array = array();//array which holds all constants in source (template)
	//element which hold constants and  translations
	$consts = $langs->getElementsByTagName("constants");
	$no_of_const = count($finded[0]);
	print "----Cecking for updates ...\n";
	for($i=0;$i<$no_of_const;$i++){
		//$value[1]constant
		//$value[2]default
		if(in_array($finded[1][$i],$del_array)){
			print "Error - you have defined two constants with same name \"".$finded[1][$i]."\". Exiting.\n";
			exit(0);
		}
		$element = $langs->getElementById($finded[1][$i]);
		if($element !== null){
			//update old element
			$def = $element->getElementsByTagName("default");
			$def_value=$def->item(0)->nodeValue;
			if($def_value==null){
				$def_value="";
			}
			if($def_value != $finded[2][$i]){
				print "Updating \"".$finded[1][$i]."\" ... ";
				$def->item(0)->nodeValue=$finded[2][$i];
				print "Done\n";
			}	
		}	
		else{	
			//create new elemnt
			print "Creating \"".$finded[1][$i]."\" ...";
			$const = $langs->createElement("constant");
			$const->setAttribute("name", $finded[1][$i]);
			$def = $langs->createElement("default");
			$def->nodeValue=$finded[2][$i];
			$const->appendChild($def);
			$consts->item(0)->appendChild($const);
			//$langs->validate()
			print "Done\n";
		}
		//preparation for deletion if wanted
		//if(array_key_exists("-d", $opts->options))
		$del_array[]=$finded[1][$i];
	}
	print "----Done\n";
	//delete trnaslation which are depricated (not in template file)
	if(array_key_exists("-d", $opts->options)){
		print "----Checking are there translations to delete ...\n";
		$constants=$langs->getElementsByTagName("constant");
		for($i=0;$i<$constants->length;$i++){
			$const=$constants->item($i);
			$const_str = $const->attributes->getNamedItem("name")->nodeValue;
			if(!in_array($const_str,$del_array)){
				print "Deleting \"".$const_str."\" ...";
				$consts->item(0)->removeChild($const);
				print "Done\n";
			}
		}
		print "----All checked\n";
	}
	//save updated translation file
	if($trans_mtime == @filemtime($lang_file) || array_key_exists("-f".$opt->options)){
		if($langs->save("$lang_file")===false)
				kerror("File ".$lang_file." can not be XML validated.");
	}
	else
		kerror("Translations file has been modified by another aplication\nTry to run this program again.");
}
else
	kerror("File ".$lang_file." can not be XML validated.");
?>
