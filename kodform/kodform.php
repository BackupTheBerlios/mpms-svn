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

/**This is kodform libary.
*
*File: kodform.php
*
*This libary purpose is to create logic for HTML forms. I use smarty for 
*presentation and classes defied in this file for form logic. There is also plugin directory for
*smarty plugins which makes presentation in smarty easy and totaly independent.
*
*Currently all forms are handeled by POST request. They will not work with GET. If needed it
*can be easyly implemented.
*
*@package kodform
*/

//required because we need to assign some classes to smarty
require_once 'Smarty.class.php';

/**Also needed for smarty to find plugins*/
//I am not shure but this is not working
$plugins_dir="/home/http/kodform";

/**Implements form logic*/
class kform{
	/**form name */
	public $name;
	
	/**array of all input components of form*/
	public $inputs;
	
	/**array of all submit buttons of form*/
	public $submits;

	/***/
	public $error = null;

	/**url for processing form*/
	public $action;
	
	/**creates new from object
	*
	*@param string $name name of form - for now is not usefull
	*@param smarty $smarty object to which will this form be assigned by reference*/
	function __construct($name, &$smarty, $action=null){
		$this->name = $name;
		$this->action = $action;
		$this->inputs = array();
		$this->submits=array();
		$smarty->assign_by_ref($this->name, $this);
	}

	/**call this to see if form is submitted.
	*
	*This function will check if some of submited buttons is clicked and will call
	*submitted button function "submited" which can than process this from because all inputs
	*of this button are passed to that function.
	*
	*@return boolean true if form is submited and valid else false*/
	function submited(){
		foreach($this->submits as $name => $value){
			if(isset($_POST[$name])){
				foreach($this->inputs as $input)
					$input->process();
				if($this->is_valid())
					return $value->submited(&$this->inputs);
			}
		}
		return false;
	}
	/**add new input control
	*
	*@param kinput $input new input control to be passed*/
	function add_input(kform_object &$input){
		$this->inputs[$input->name] =& $input;
	}

	/**add new submitt control
	*
	*@param ksubmit $submit adds submit button*/
	function add_submit(ksubmit &$submit){
		$this->submits[$submit->name] =& $submit;
	}
	/**check is all input components of this form have valid values
	*
	*@return boolean if all are valid returns true else false*/
 	function is_valid(){
		foreach($this->inputs as $input){
			if(!$input->is_valid())
				return false;
		}
		return true;
	}
	/**resets form inputs.
	* It set all form input values to null. Useful when from is processed and new input is expected.*/
	function freset(){
		foreach($this->inputs as $input){
			$input->value=null;
		}
	}
	/**to submit form in code not by user
	* @param string $sname name of ksubmit*/
	function submit($sname){
		$_POST[$sname]=1;	
	}
}

/**base class fro implementing validation of input conponents
*
*Should be used with kinput*/
class kvalidator{
	function is_valid($value){
		return true;
	}
}
/**min max charachter validator
*
*Check if iunput contains min or max charachters. If yes return true else return false*/
class kv_min_max extends kvalidator{
	private $min;
	private $max;
	function __construct($min, $max){
		$this->min = $min;
		$this->max = $max;
	}
	function is_valid($value){
		$length=strlen($value);
		if(($length>=$this->min && $length<=$this->max) || $length==0)
			return true;
		return false;
	}
}
/**regex validator class
*
* you can use it to check field value against regex expression*/
class kv_regex extends kv_min_max{
	private $regex;
	function __construct($min, $max, $regex){
		parent::__construct($min, $max);
		$this->regex=$regex;
	}
	function is_valid($value){
		if(parent::is_valid($value)){
			if(preg_match($this->regex, $value)===1)
				return true;
		}
		return false;	
	}
}
abstract class kform_object{
	abstract public function process();
	abstract public function is_valid();
	abstract public function get_value($ret_flag = false);
	abstract public function set_value($value);
}
/**class which implements input tag logic*/
class kinput extends kform_object{
	/**name of input*/
	public $name;
	/**value of input
	* this is public variable but you should use get_value if you want valid value*/
	public $value = null;
	/**validator of input*/
	protected $validator;
	/**creates new kinput object
	*@param string $name neame of kinput component same as input tag name attribute
	*@param smarty $smarty object ro wich this component will be assigned by ref
	*@param kvalidator $validator object which implements new object validation if not set
	*is set to be kvalidator object*/
	function __construct($name, &$smarty, &$validator = null, $value=null){	
		$this->validator=&$validator;
		if($validator==null)
			$this->validator=&new kvalidator();
		$this->name=$name;
		$this->value=$value;
		//$this->process();
		$smarty->assign_by_ref($this->name, $this);
	}

	/**protected class which implements processing of POST requiest.
	*It is called in constructor.*/
	public function process(){
		if(isset($_POST[$this->name])){
			$this->value = $_POST[$this->name];
		}
	}
	/**function that checks is current input vlaue valid
	*
	*It uses $validator object for validation*/
	function is_valid(){
		if($this->validator->is_valid($this->value))
			return true;
		return false;
	}
	/**returns value of this kinput component
	*
	*You can use parametar $ret flag to return valid or also not valid data
	*@param bool $ret_flag if true then not valid data is also returned*/
	function get_value($ret_flag = false){
		if($this->is_valid() || $ret_flag)
			return $this->value;
		return null;
	}
	/**sets new value for this component.
	*
	*It does not check is this value valid.
	*@param string $value new vlaue for this input component*/
	function set_value($value){
		$this->value=$value;
		//$smarty->assign_by_ref($this->name, $this);
	}
}
/**usefull class whan empty values have to have null value instead empty string
you should use get_value() fuinction to get value otherwise ($object->value) it want work correctly*/
class kdb_input extends kinput{
	/**overiden function which converts empty string to null value*/
	function get_db_value($ret_flag=false){
		$value = parent::get_value();
		if($value ==""){
			$value=NULL;
		}
		return $value;
	}
}

/**class which implements drop-down list (select html tag)
*
*You should use it with koption smarty function (see plugins)
*/
class kddlist extends kform_object{
	/**name of select*/
	public $name;
	/**value of select set by user*/
	public $value=null;
	/**creates kddlist object
	*
	*@param string $name name of drop down list*/
	function __construct($name, &$smarty, $value=null){
		$this->name=$name;
		$this->value=$value;
		$smarty->assign_by_ref($this->name, $this);
		//$this->process();
	}
	/**Internal method which process POST variable to set this iobject value*/
	function process(){
		if(isset($_POST[$this->name]))
			$this->value=$_POST[$this->name];
	}
	function is_valid(){
		return true;
	}
	/**sets new value for this component.
	*
	*It does not check is this value valid.
	*@param string $value new vlaue for this list component*/
	function set_value($value){
		$this->value=$value;
	}

	/**returns value of this kddlist component
	*
	*You can use parametar $ret flag to return valid or also not valid data
	*@param bool $ret_flag if true then not valid data is also returned*/
	function get_value($ret_flag = false){
		if($this->is_valid() || $ret_flag)
			return $this->value;
		return null;
	}

}

/**implements check box*/
class kcheckbox extends kform_object{
	/**name of check box*/
	public $name;
	/**value of checkbox set by user*/
	public $value=null;
	/**is checked*/
	public $checked;
	/**creates kcheckbox object
	*
	*@param string $name name of kcheckbox*/
	function __construct($name, &$smarty, $checked = false){
		$this->name=$name;
		$smarty->assign_by_ref($this->name, $this);	
		$this->checked = $checked;
		//$this->process();
	}
	
	/**Internal method which process POST variable to set this iobject value*/
	function process(){
		if(isset($_POST[$this->name])){
			$this->value=$_POST[$this->name];
			$this->checked = true;
		}
		else
			$this->checked = false;
	}
	/**Checks if value is valid. For this class it is always valid*/
	function is_valid(){
		return true;
	}
	/**sets new value for this component.
	*
	*It does not check is this value valid.
	*@param string $value new vlaue for this list component*/
	function set_value($value){
		$this->value=$value;
	}

	/**returns value of this kddlist component
	*
	*You can use parametar $ret flag to return valid or also not valid data
	*@param bool $ret_flag if true then not valid data is also returned*/
	function get_value($ret_flag = false){
		if($this->is_valid() || $ret_flag)
			return $this->value;
		return null;
	}

}
/**impements submit button logic.
*
*This is abstract class and it should be extended. Extendex class must implement submit*/
abstract class ksubmit{
	/**name of submmit buton. Same as input tag name attrubte.*/
	public $name;
	/**error to be set when form is submitted*/
	public $error=null;
	/**creates new ksubmit object
	*
	*@param string $name name of submit input.
	*@param smarty $smarty smarty object to which new submit object will be assigned*/
	function __construct($name, &$smarty){
		$this->name=$name;
		$smarty->assign_by_ref($this->name, $this);
		$smarty->assign_by_ref($this->name."_error", $this->error);
	}
	/**function which implements action wich will be taken when this button will be clicked (from submitted)*/
	abstract function submited(&$inputs);
}
?>
