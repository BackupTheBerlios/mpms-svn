<?php

class mcollect{
	public $ok;
	public $err;
	function __construct(){
		$this->ok = array();
		$this->err = array();
	}
	public function ok($ok, $name=null){
		if($name==null)
			array_push($this->ok, $ok);
		else
			$this->ok[$name]=$ok;
	}
	public function err($err, $name=null){
		if($name==null)
			array_push($this->err,$err);
		else
			$this->err[$name]=$err;
	}
}

?>
