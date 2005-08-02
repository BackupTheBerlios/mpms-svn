<?php

class mcollect{
	public $ok;
	public $err;
	function __construct(){
		$this->ok = array();
		$this->err = array();
	}
	public function ok($ok){
		array_push($this->ok, $ok);
	}
	public function err($err){
		array_push($this->err,$err);
	}
}

?>
