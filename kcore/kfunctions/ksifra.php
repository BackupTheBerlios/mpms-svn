<?php
/*
    ksifra - creating directory tree for data storage of large number of files
    Copyright (C) 2005  Boris Tomić

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/**simpe counter based on breefcase locker.
*
* In fact this creates all possible combinations of given range in given places ($size)*/
class sifra{
	protected $sifra;
	private $strat;
	private $end;
	private $size;
	private $combinations;
	private $counter;
	/**creates object
	* @param int $start start number for counter
	* @param int $end end number for counter
	* @param int $size number of places*/
	function __construct($start, $end, $size){
		$this->sifra=array();
		$this->size = $size;
		$this->start=$start;
		$this->end = $end;
		$this->set_start();
		if($end<$start)
			$this->end = $start;
		$this->combinations=pow(($this->end-$this->start), $this->size);
		$this->counter =0;
	}
	private function set_start(){
		$i=0;
		while($i<$this->size){
			$this->sifra[]=$this->start;
			$i++;
		}	
	}
	private function get_value(){
		if($this->is_valid())
			return $this->sifra;
		return false;
	}
	private function roll($i=0){
		if($i>=0 && $i<$this->size){
			$this->sifra[$i]++;
			$this->counter++;//change current
			if($this->sifra[$i]==$this->end){
				$this->sifra[$i]=$this->start;
				$this->counter--;//counter must be decreesed because following call to roll
				$this->roll($i+1);
			}
		}
	}
	/**returns number of possible combinations (this number contains not_valid combinations too)
	* @return int number of combinations*/	
	function combinations(){
		return $this->combinations;
	}
	/**function which cen be overloaded to check is some combination is valid*/
	protected function is_valid(){
		return true;
	}
	/**returns next combination
	* @return array of numbers or flase if there is no more combinations*/
	function snext(){
		while(($ret = $this->get_value())==false){
			$this->roll();
		}
		if($this->counter<$this->combinations){
			$this->roll();
			return $ret;
		}
		return false;
	}
	/**reset counter*/
	function sreset(){
		$this->counter = 0;
		$this->set_start();	
	}
}

/**class used for directory tree creation for data storage of many files*/
class dir_sifra extends sifra{
	/**it uses only lovercase letters for directory names
	* you just need to specifi how deep tree has to be
	* @param int $deep deepth of creating directory tree*/
	function __construct($deep){
		parent::__construct(96,123,$deep);
	}
	/**check if counter holds valid directory name*/
	function is_valid(){
		$no = count($this->sifra);
		$veci = false;
		for($i=$no-1;$i>=0;$i--){
			if($this->sifra[$i] <= 96 && $veci)
				return false;
			if($this->sifra[$i]>96)
				$veci = true;
		}
		return $veci;
	}
}
?>
