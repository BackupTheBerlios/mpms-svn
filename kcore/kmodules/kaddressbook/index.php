<?php
/*
    kaddressbook module
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
//require_once kconf::kodform_dir."/kodform.php";

if(!isset($auth)){
	$kmmlog->debug("auth variable is not set. Exiting.");
	exit(0);
}
if(!$auth->check()){
	$kmmlog->err("Authentification error. Exiting.");
	exit(1);
}

//here goes code for logger initalization
//global $kablog;
//$kablog = Log::singleton("error_log", PEAR_LOG_TYPE_SYSTEM, "kaddressbook");

//smarty object
//$kaSmarty =& new kmSmarty($prefs->lang);

//here comes code for group checking
//if(!$auth->check_group("kaddressbook")){
//	$kablog->info("User ".$auth->username." is not memeber of kaddressbook group so access is denied to him.");
//	$kaSmarty->display("noaccess.tpl");	
//	return;
//}

//should be dleted
//message colector class
//global $mcoll;
//$mcoll= new mcollect();

abstract class kabsubmit extends ksubmit{
	protected $query;
	protected $mcoll;
	protected $log;
	function __construct($name, &$smarty, &$query, &$mcoll, &$log){
		parent::__construct($name, &$smarty);
		$this->query=&$query;
		$this->mcoll=&$mcoll;
		$this->log=&$log;
	}
}

class padd_submit extends kabsubmit{
	public $inserted;
	function __construct($name, &$smarty, &$query, &$mcoll, &$log){
		parent::__construct($name, &$smarty, &$query, &$mcoll, &$log);
		$this->inserted=false;
	}
	function submited(&$inputs){
		global $auth;
		$private = "false";
		if($inputs['private']->checked)
			$private = "true";
		$rez=null;
		//first, middle, last, nickname, jtitle, home, work, fax, mobile, pager, email, addres, city, state, zip, country, notes, web, user_index, private
		$squery="SELECT * FROM kaddressbook.add_person($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20);";
		$qparams=array($inputs['name']->get_db_value(),$inputs['mname']->get_db_value(),$inputs['lname']->get_db_value(),$inputs['nik']->get_db_value(),$inputs['jtitle']->get_db_value(),$inputs['htel']->get_db_value(),$inputs['wtel']->get_db_value(),$inputs['fax']->get_db_value(),$inputs['mobile']->get_db_value(),$inputs['pager']->get_db_value(),$inputs['email']->get_db_value(),$inputs['haddress']->get_db_value(),$inputs['city']->get_db_value(),$inputs['state']->get_db_value(),$inputs['zip']->get_db_value(),$inputs['country']->get_db_value(),$inputs['notess']->get_db_value(),$inputs['web']->get_db_value(),$auth->userindex,$private);
		try{
			$rez=&$this->query->query_params($squery,&$qparams);
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			$this->mcoll->err(2);
		}
		if($rez!=false &&($row = $rez->next())){
			$this->inserted=$row[0];
			return true;
		}
		return false;
	}
}

class cadd_sub extends kabsubmit{
	function __construct($name, &$smarty, &$query, &$mcoll, &$log){
		parent::__construct($name, &$smarty, &$query, &$mcoll, &$log);
	}
	function submited(&$inputs){
		global $auth;
		$private = "false";
		if($inputs['private']->checked)
			$private = "true";
		$rez=null;
		$squery = "SELECT kaddressbook.add_company($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13)";
		$qparams=array($inputs['name']->get_db_value(),$inputs['address']->get_db_value(),$inputs['city']->get_db_value(),$inputs['state']->get_db_value(),$inputs['zip']->get_db_value(),$inputs['country']->get_db_value(),$inputs['tel']->get_db_value(),$inputs['fax']->get_db_value(),$inputs['web']->get_db_value(),$inputs['vat']->get_db_value(),$auth->userindex,$inputs['notess']->get_db_value(),$private);
		try{
			$rez =& $this->query->query_params($squery,&$qparams);
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			$this->mcoll->err(2);
		}
		if($rez!=false){
			return true;	
		}
		return false;
	}
}
class cedit_sub extends kabsubmit{
	private $row;
	function __construct($name, &$smarty, &$query, &$mcoll, &$log, &$row){
		parent::__construct($name, &$smarty, &$query, &$mcoll, &$log);
		$this->row=&$row;
	}
	function submited(&$inputs){
		global $auth;
		$private = "false";
		if($inputs['private']->checked)
			$private = "true";
		$rez=null;
		$sub_query="";
		$query="";
		$query_params=null;
		if($this->row['user_index']!=$auth->user_index && $thhis->row['private']=='t'){
			$this->mcoll->err(5);
			return false;
		}
		else if($this->row['user_index']!=$auth->user_index && $this->row['private']=='f'){
			$query = "UPDATE kaddressbook.company SET name=$1, address=$2, city=$3, state=$4, zip=$5, country=$6, tel=$7, fax=$8, web=$9, vat_no=$10, note=$12 WHERE index=$11";
			$query_params=array($inputs['name']->value, $inputs['address']->value, $inputs['city']->value, $inputs['state']->value, $inputs['zip']->value, $inputs['country']->value, $inputs['tel']->value, $inputs['fax']->value, $inputs['web']->value, $inputs['vat']->value, $inputs['cindex']->value, $inputs['notess']->value);
		}
		else
			$query = "UPDATE kaddressbook.company SET name=$1, address=$2, city=$3, state=$4, zip=$5, country=$6, tel=$7, fax=$8, web=$9, vat_no=$10, note=$12, private=$13 WHERE index=$11";
		$query_params=array($inputs['name']->value, $inputs['address']->value, $inputs['city']->value, $inputs['state']->value, $inputs['zip']->value, $inputs['country']->value, $inputs['tel']->value, $inputs['fax']->value, $inputs['web']->value, $inputs['vat']->value, $inputs['cindex']->value, $inputs['notess']->value, $private);
		try{
			$rez =&$this->query->query_params($query,$query_params);	
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			$this->mcoll->err(2);
		}
		if($rez!=false){
			return true;	
		}
		return false;
	}
}

class kcsearch_sub extends kabsubmit{
	public $rezult;
	function __construct($name, &$smarty, &$query, &$mcoll, &$log){
		parent::__construct($name, &$smarty, &$query, &$mcoll, &$log);
		$this->rezult=false;
	}
	function submited(&$inputs){
		global $auth;
		$query=null;
		$fields=array("name","address","vat_no","city","tel","fax");
		//selectiong operator
		$operator=null;
		$separator="OR";
		switch($inputs['operation']->value){
			case 2:
				$operator=" ILIKE $1";
				break;
			case 3:
				$operator=" ILIKE $1||'%'";
				break;
			case 4:
				$operator=" ILIKE '%'||$1";
				break;
			case 5:
				$separator="AND";
				$operator=" NOT ILIKE '%'||$1||'%'";
				break;
			default:
				$operator=" ILIKE '%'||$1||'%'";
		}
		if($inputs['field']->value==1)
			$query = "SELECT * FROM kaddressbook.company WHERE (name".$operator." ".$separator." address".$operator." ".$separator." vat_no".$operator." ".$separator." city".$operator." ".$separator." tel".$operator." ".$separator." fax".$operator.") AND (private=false OR (private=true AND user_index=$2))";
		else if(in_array($inputs['field']->value, $fields)){
			$query ="SELECT * FROM kaddressbook.company WHERE ".$inputs['field']->value.$operator." AND (private=false OR (private=true AND user_index=$2))";
		}
		else{
			$this->log->warning("Unknown field. Possible sql injection attack (ip=".$_SERVER['REMOTE_ADDR'].").");
			return false;
		}
		try{
			$this->rezult=&$this->query->query_params($query, array($inputs['value']->value,$auth->userindex));
		}
		catch(Exception $e){
			$this->log->err($e->getMessage());
			return false;
		}
		return true;
	}
}

class kpsearch_sub extends kabsubmit{
	public $rezult;
	function __construct($name, &$smarty, &$query, &$mcoll, &$log){
		parent::__construct($name, &$smarty, &$query, &$mcoll, &$log);
		$this->rezult=false;
	}
	function submited(&$inputs){
		global $auth;
		$query=null;
		$fields=array("first","last","nickname","mobile","email","addres", "company");
		//selectiong operator
		$operator=null;
		$separator="OR";
		switch($inputs['operation']->value){
			case 2:
				$operator=" ILIKE $1";
				break;
			case 3:
				$operator=" ILIKE $1||'%'";
				break;
			case 4:
				$operator=" ILIKE '%'||$1";
				break;
			case 5:
				$separator="AND";
				$operator=" NOT ILIKE '%'||$1||'%'";
				break;
			default:
				$operator=" ILIKE '%'||$1||'%'";
		}
		if($inputs['field']->value==1)
			$query = "SELECT per.*, comp.name FROM kaddressbook.persons AS per LEFT OUTER JOIN kaddressbook.company AS comp ON comp.index=per.company WHERE (per.first".$operator." ".$separator." per.last".$operator." ".$separator." per.nickname".$operator." ".$separator." per.mobile".$operator." ".$separator." per.addres".$operator." ".$separator." comp.name".$operator.") AND (per.private=false OR (per.private=true AND per.user_index=$2));";
		else if(in_array($inputs['field']->value, $fields)){
			if($inputs['field']->value=="company")
				$query ="SELECT per.*, comp.name FROM kaddressbook.persons AS per, kaddressbook.company AS comp WHERE (per.private=false OR (per.private=true AND per.user_index=$2)) AND comp.index=per.company AND comp.name".$operator.";";
			else
				$query ="SELECT per.*, comp.name FROM kaddressbook.persons AS per LEFT OUTER JOIN kaddressbook.company AS comp ON comp.index=per.company WHERE per.".$inputs['field']->value.$operator." AND (per.private=false OR (per.private=true AND per.user_index=$2));";
		}
		else{
			$this->log->warning("Unknown field. Possible sql injection attack (ip=".$_SERVER['REMOTE_ADDR'].").");
			return false;
		}
		print $query;
		print $inputs['value']->value;
		try{
			$this->rezult=&$this->query->query_params($query, array($inputs['value']->value,$auth->userindex));
		}
		catch(Exception $e){
			$this->log->err($e->getMessage());
			return false;
		}
		return true;
	}
}

class kaddressbook{
	public $smarty;
	public $kablog;
	public $sql_query;
	public $mcoll;
	function __construct(){
		global $prefs;
		$this->smarty =& new kmSmarty($prefs->lang);
		$this->kablog =& Log::singleton("error_log", PEAR_LOG_TYPE_SYSTEM, "kaddressbook");
		$this->sql_query =& get_kdb_connection();
		$this->mcoll =& new mcollect();
	}
	function display(){
		//check user premissions
		if($this->auth_check()){
			$this->get_processor();		
		}
	}
	function auth_check(){
		global $auth;
		if(!$auth->check_group("kaddressbook")){
			$this->kablog->info("User ".$auth->username." is not memeber of kaddressbook group so access is denied to him.");
			$this->smarty->display("noaccess.tpl");	
			return false;
		}
		return true;
	}
	function get_processor(){
		if(isset($_GET['action'])){
			if($_GET['action']=="padd"){
				$this->person_add();
			}
			else if($_GET['action']=="psearch"){
				$this->person_search();
			}
			else if($_GET['action']=="cedit"){
				$this->company_edit();
			}
			else if($_GET['action']=="cadd"){
				$this->company_add();
			}
			else if($_GET['action']=="csearch" || $_GET['action']=="cdel"){
				$this->company_search();
			}
			else if($_GET['action']=="cu"){
				$this->user_company();
				//$this->company_search("user_company.tpl");
			}
			else if($_GET['action']=="cua"){
				$this->user_company_assing();
			}
			else{
				$this->smarty->display("main.tpl");	
			}
		}
		else{
			$this->smarty->display("main.tpl");	
		}
	}
	function person_add(){
		$form=&new kform("new_person", &$this->smarty);
		$form->add_input(new kdb_input("name", &$this->smarty));
		$form->add_input(new kdb_input("lname", &$this->smarty));
		$form->add_input(new kdb_input("mname", &$this->smarty));
		$form->add_input(new kdb_input("nik", &$this->smarty));
		$form->add_input(new kdb_input("jtitle", &$this->smarty));
		$form->add_input(new kdb_input("htel", &$this->smarty));
		$form->add_input(new kdb_input("wtel", &$this->smarty));
		$form->add_input(new kdb_input("fax", &$this->smarty));
		$form->add_input(new kdb_input("mobile", &$this->smarty));
		$form->add_input(new kdb_input("pager", &$this->smarty));
		$form->add_input(new kdb_input("email", &$this->smarty));
		$form->add_input(new kdb_input("cother", &$this->smarty));
		$form->add_input(new kdb_input("haddress", &$this->smarty));
		$form->add_input(new kdb_input("city", &$this->smarty));
		$form->add_input(new kdb_input("zip", &$this->smarty));
		$form->add_input(new kdb_input("state", &$this->smarty));
		$form->add_input(new kdb_input("country", &$this->smarty));	
		$form->add_input(new kdb_input("notess", &$this->smarty));	
		$form->add_input(new kdb_input("web", &$this->smarty));	
		$form->add_input(new kcheckbox("private", &$this->smarty));
		$pasubmit =&new padd_submit("save", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->kablog);
		$form->add_submit(&$pasubmit);
		if($form->submited()){
			$this->smarty->clear_all_assign();
			$this->smarty->assign("pindex", $pasubmit->inserted);
			$this->mcoll->ok(1);
			$this->smarty->assign_by_ref("mess",&$this->mcoll);
			$this->smarty->display("padd_ok.tpl");
			return;
		}
		$this->smarty->display("padd.tpl");
	}
	function company_edit(){
		$company = explode("&",rawurldecode($_GET['c']));	
		$query="SELECT * FROM kaddressbook.company WHERE index=$1";
		$rezult = false;
		try{
			$rezult=&$this->sql_query->query_params($query, array($company[0]));
		}
		catch(Exception $e){
			$this->kablog->err($e->getMessage());
		}
		if($rezult != false && ($row=$rezult->next())){
			$form=&new kform("new_company", &$this->smarty);
			$kcname =&new kinput("name", &$this->smarty);
			$kcvat =&new kinput("vat", &$this->smarty);
			$kcaddress =&new kinput("address", &$this->smarty);
			$kccity=&new kinput("city", &$this->smarty);
			$kczip=&new kinput("zip", &$this->smarty);
			$kcstate=&new kinput("state", &$this->smarty);
			$kccountry=&new kinput("country", &$this->smarty);
			$kctel=&new kinput("tel", &$this->smarty);
			$kcfax=&new kinput("fax", &$this->smarty);
			$kcweb=&new kinput("web", &$this->smarty);
			$kcnotess=&new kinput("notess", &$this->smarty);
			$kcprivate =&new kcheckbox("private", &$this->smarty);
			$kcindex =& new kinput("cindex", &$this->smarty);
			$kcget =& new kinput("cget", &$this->smarty);
			$kuindex =& new kinput("uindex", &$this->smarty);
			$form->add_input(&$kcindex);
			$form->add_input(&$kuindex);
			$form->add_input(&$kcget);
			$form->add_input(&$kcname);
			$form->add_input(&$kcvat);
			$form->add_input(&$kcaddress);
			$form->add_input(&$kccity);
			$form->add_input(&$kczip);
			$form->add_input(&$kcstate);
			$form->add_input(&$kccountry);
			$form->add_input(&$kctel);
			$form->add_input(&$kcfax);
			$form->add_input(&$kcweb);
			$form->add_input(&$kcnotess);
			$form->add_input(&$kcprivate);
			$form->action="manager.php?action=cedit&c=".$_GET['c'];
			$this->smarty->assign("status",0);
			$form->add_submit(new cedit_sub("edit_company", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->kablog, &$row));
			//get data from database
			if($form->submited()){	
				$this->mcoll->ok(1);
			}
			else{
				if($row['user_index']!=$auth->userindex && $row['private']=='t'){
					$this->smarty->assign("status", 1);
				}
				else{
					if($row['user_index']!=$auth->userindex && $row['private']=='f')
						$this->smarty->assign("status",2);
					$kcname->value=$row['name'];
					$kcvat->value=$row['vat_no'];
					$kcaddress->value=$row['address'];
					$kccity->value=$row['city'];
					$kczip->value=$row['zip'];
					$kcstate->value=$row['state'];
					$kccountry->value=$row['country'];
					$kctel->value=$row['tel'];
					$kcfax->value=$row['fax'];
					$kcweb->value=$row['web'];
					$kcnotess->value=$row['note'];
				
					$kcindex->value=$row['index'];
					$kuindex->value=$row['user_index'];	
					$kcget->value=$_GET['c'];
				
					$kcprivate->checked=false;
					if($row['private']=='t')
						$kcprivate->checked=true;
				}
			}	
		}
		$this->smarty->assign_by_ref("mess",&$this->mcoll);
		$this->smarty->display("cedit.tpl");
	}
	function company_add(){
		$form=&new kform("new_company", &$this->smarty);
		$kcname =&new kdb_input("name", &$this->smarty);
		$kcvat =&new kdb_input("vat", &$this->smarty);
		$kcaddress =&new kdb_input("address", &$this->smarty);
		$kccity=&new kdb_input("city", &$this->smarty);
		$kczip=&new kdb_input("zip", &$this->smarty);
		$kcstate=&new kdb_input("state", &$this->smarty);
		$kccountry=&new kdb_input("country", &$this->smarty);
		$kctel=&new kdb_input("tel", &$this->smarty);
		$kcfax=&new kdb_input("fax", &$this->smarty);
		$kcweb=&new kdb_input("web", &$this->smarty);
		$kcnotess=&new kdb_input("notess", &$this->smarty);
		$kcprivate =&new kcheckbox("private", &$this->smarty);
		$form->add_input(&$kcname);
		$form->add_input(&$kcvat);
		$form->add_input(&$kcaddress);
		$form->add_input(&$kccity);
		$form->add_input(&$kczip);
		$form->add_input(&$kcstate);
		$form->add_input(&$kccountry);
		$form->add_input(&$kctel);
		$form->add_input(&$kcfax);
		$form->add_input(&$kcweb);
		$form->add_input(&$kcnotess);
		$form->add_input(&$kcprivate);
		$submit =& new cadd_sub("add_company", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->kablog);
		$form->add_submit(&$submit);	
		if($form->submited()){
			$this->mcoll->ok(1);
			$form->freset();
		}
		$this->smarty->assign_by_ref("mess",&$this->mcoll);
		$this->smarty->display("cadd.tpl");
	}
	function company_search($page){
		global $auth;			
		$form=&new kform("search_form", &$this->smarty);
		$kcvalue =&new kinput("value", &$this->smarty);
		$form->add_input(&$kcvalue);
		$kcoper =& new kddlist("operation", &$this->smarty);
		$form->add_input(&$kcoper);
		$kcfield =& new kddlist("field", &$this->smarty);
		$form->add_input(&$kcfield);
		$search_submit =& new kcsearch_sub("search", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->kablog);
		$form->add_submit(&$search_submit);
		$finded_count = -1;
		$this->smarty->assign_by_ref("finded_no", $finded_count);
		$err_ok_mess = 0;
		$form->action="?kmmodule=kaddressbook&action=csearch";
		if($_GET['action']=="cdel" && isset($_GET['c'])){
			$company = explode("&",rawurldecode($_GET['c']));
			$query="DELETE FROM kaddressbook.company WHERE index=$1 and user_index=$2";
			$rezult = false;
			try{
				$rezult=&$this->sql_query->query_params($query, array($company[0], $auth->userindex));
			}
			catch(Exception $e){
				$this->kablog->err($e->getMessage());
			}
			$this->smarty->assign("del_comp", $company[1]);
			if($rezult != false && $rezult->affected_rows()==1){
				$err_ok_mess=1;
				$kcvalue->value=$company[2];
				$kcoper->value=$company[3];
				$kcfield->value=$company[4];
				$form->submit("search");
			}
			else{
				$err_ok_mess=2;
			}
		}
		$this->smarty->assign("mess",$err_ok_mess);
		if($form->submited()){
			$rez =& $search_submit->rezult;
			$rezults = array();
			while(($row = $rez->next())){
				$row["cindex"]=rawurlencode($row["index"]."&".$row['name']."&".$kcvalue->value."&".$kcoper->value."&".$kcfield->value);
				$rezults[]=$row;
			}
			$this->smarty->assign_by_ref("finded", &$rezults);
			$finded_count=count($rezults);
		}
		$this->smarty->display("csearch_form.tpl");
	}
	function user_company(){
		global $auth;			
		$form=&new kform("search_form", &$this->smarty);
		$kcvalue =&new kinput("value", &$this->smarty);
		$form->add_input(&$kcvalue);
		$kperson =&new kinput("person", &$this->smarty);
		if(isset($_GET['p'])){
			$kperson->value=$_GET['p'];
		}
		$form->add_input(&$kperson);
		$kcoper =& new kddlist("operation", &$this->smarty);
		$form->add_input(&$kcoper);
		$kcfield =& new kddlist("field", &$this->smarty);
		$form->add_input(&$kcfield);
		$search_submit =& new kcsearch_sub("search", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->kablog);
		$form->add_submit(&$search_submit);
		$finded_count = -1;
		$this->smarty->assign_by_ref("finded_no", $finded_count);
		$err_ok_mess = 0;
		$form->action="?kmmodule=kaddressbook&action=cu";
		$this->smarty->assign("mess",$err_ok_mess);
		if($form->submited()){
			$rez =& $search_submit->rezult;
			$rezults = array();
			while(($row = $rez->next())){
				$row["cindex"]=rawurlencode($row["index"]/*."&".$row['name']."&".$kcvalue->value."&".$kcoper->value."&".$kcfield->value*/);
				$rezults[]=$row;
			}
			$this->smarty->assign_by_ref("finded", &$rezults);
			$finded_count=count($rezults);
		}
		$this->smarty->display("user_company.tpl");
	}
	function user_company_assing(){
		global $auth;
		$err_ok=1;
		$this->smarty->assign_by_ref("mess", &$err_ok);
		if(isset($_GET['c']) && isset($_GET['p'])){
			$query="SELECT * FROM kaddressbook.user_company($1,$2,$3);";
			try{
				$rezult=&$this->sql_query->query_params($query, array($auth->userindex, $_GET['p'], $_GET['c']));
			}
			catch(Exception $e){
				$this->kablog->err($e->getMessage());
			}
			if($rezult!= false && ($row=$rezult->next())){
				if($row[0]==0)
					$err_ok=0;
			}
		}
		$this->smarty->display("user_company_assign.tpl");	
	}
	function person_search(){
		global $auth;			
		$form=&new kform("search_form", &$this->smarty);
		$kcvalue =&new kinput("value", &$this->smarty);
		$form->add_input(&$kcvalue);
		$kcoper =& new kddlist("operation", &$this->smarty);
		$form->add_input(&$kcoper);
		$kcfield =& new kddlist("field", &$this->smarty);
		$form->add_input(&$kcfield);
		$search_submit =& new kpsearch_sub("search", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->kablog);
		$form->add_submit(&$search_submit);
		$finded_count = -1;
		$this->smarty->assign_by_ref("finded_no", $finded_count);
		$err_ok_mess = 0;
		$form->action="?kmmodule=kaddressbook&action=psearch";
		if($_GET['action']=="pdel" && isset($_GET['c'])){
			$person = explode("&",rawurldecode($_GET['c']));
			$query="DELETE FROM kaddressbook.persons WHERE index=$1 and user_index=$2";
			$rezult = false;
			try{
				$rezult=&$this->sql_query->query_params($query, array($person[0], $auth->userindex));
			}
			catch(Exception $e){
				$this->kablog->err($e->getMessage());
			}
			$this->smarty->assign("del_person", $person[1]);
			if($rezult != false && $rezult->affected_rows()==1){
				$err_ok_mess=1;
				$kcvalue->value=$person[2];
				$kcoper->value=$person[3];
				$kcfield->value=$person[4];
				$form->submit("search");
			}
			else{
				$err_ok_mess=2;
			}
		}
		$this->smarty->assign("mess",$err_ok_mess);
		if($form->submited()){
			$rez =& $search_submit->rezult;
			$rezults = array();
			while(($row = $rez->next())){
				$row["pindex"]=rawurlencode($row["index"]."&".$row['first']." ".$row['last']."&".$kcvalue->value."&".$kcoper->value."&".$kcfield->value);
				$rezults[]=$row;
			}
			$this->smarty->assign_by_ref("finded", &$rezults);
			$finded_count=count($rezults);
		}
		$this->smarty->display("psearch_form.tpl");
	}
}

$main =& new kaddressbook;
$main->display();
?>
