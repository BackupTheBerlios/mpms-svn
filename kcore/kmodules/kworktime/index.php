<?php
/*
    kworktime module
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

require_once 'kfunctions/kser.php';
require_once 'kfunctions/kdatetime.php';

/**this class is quick solution. It is good solution but programmer
* has to be carefull while useing it. So take care that your array keys
* match default ones. Also take care that all data match default data type.
* Here this class is used to store company working time settings.
* @see kser class for more info.*/
class settings extends kser{
	public $data;
	function __construct(){
		parent::__construct("wtsettings");
		$this->data= array(	"maxwtime"=>14*360,
					"maxltime"=>40,
					"maxbtime"=>20,
					"daywhours"=>8*3600);
	}
}

class wtform extends kform{
	function __construct($name, &$smarty){
		parent::__construct($name,&$smarty);
		$this->add_input(new kdb_input("notef",&$smarty, new kv_min_max(0,249)));
	}
}

/**general submit class for kworktime submits
*
* It only initalize few variables*/
abstract class kwtsubmit extends ksubmit{
	protected $query;//
	protected $mcoll;
	protected $log;
	function __construct($name, kmSmarty &$smarty, kdb_query &$query,mcollect &$mcoll, Log &$log){
		parent::__construct($name, &$smarty);
		$this->query=&$query;
		$this->mcoll=&$mcoll;
		$this->log=&$log;
	}
}

/**submit that select day for day report*/
class kwtdr_submit extends ksubmit{
	function __construct($name, kmSmarty &$smarty){
		parent::__construct($name, &$smarty);
	}
	function submited(&$inputs){
		return true;
	}
}

/**submit that handl user status changes*/
class kwtstatus extends kwtsubmit{
	private $upref;
	private $auth;
	private $status;
	function __construct($name, kmSmarty &$smarty, kdb_query &$query,mcollect &$mcoll, Log &$log, &$auth, &$upref, $status){
		parent::__construct($name, &$smarty, &$query, &$mcoll, &$log);
		$this->upref=&$upref;
		$this->auth=&$auth;
		$this->status=$status;
	}
	function submited(&$inputs){
		$querys = "SELECT kworktime.new_status($1::int8, $2::int2, $3::varchar,now(), $4::interval);";
		$rezult = false;
		try{
			$rezult=&$this->query->query_params($querys, array($this->auth->userindex, $this->status, $inputs['notef']->get_db_value(), $this->upref->tzone));
		}
		catch(Exception $e){
			$this->log->err($e->getMessage());
			$this->mcoll->err($this->status);
			return false;
		}
		$this->mcoll->ok($this->status, "status");
		return true;
	}
}

//this bellow is not needed and it should be removed after testing day report functionality
/*class dayr_subbmit extends kwtsubmit{
	var data
	function __construct($name, kmSmarty &$smarty, kdb_query &$query,mcollect &$mcoll, Log &$log, &$auth, &$upref, $status){
		parent::__construct($name, &$smarty, &$query, &$mcoll, &$log);
}*/

class kworktime{
	const working=1;//at work working
	const not_working=2;//not working
	const lunch=3;//lunch break
	const lunch_end=4;//lunch ended
	const out=5;//gone out
	const in=6;//back in
	const wbreak=7;//break at work
	const wbreak_end=8;//break end
	const snot_working=9;//not working set by system
	const slunch_end=10;//lunch ended set by system
	const swbreak_end=11;//system break end
	const sin=12;//system back in
	public $smarty;//smarty for displaing
	public $klog;//Log object for logging
	public $db;//database coneection and query object
	public $mcoll;//message collector class for displaing messages to users
	public $uprefs;//user preferances
	public $auth;//user auth data
	/**all necesary initalization for this object*/
	function __construct(kauth &$auth, kuser_prefs &$prefs){
		$this->uprefs =&$prefs;
		$this->auth = &$auth;
		$this->smarty =& new kmSmarty($this->uprefs->lang, "kworktime");
		$this->klog =& Log::singleton("error_log", PEAR_LOG_TYPE_SYSTEM, "kworktime");
		$this->sql_query =& get_kdb_connection();
		$this->mcoll =& new mcollect();
	}
	/**main class of this object
	*
	* you should onli use this method*/
	public function display(){
		//check user premissions
		if($this->auth_check()){
			$this->process_request();
		}
	}
	/**chack is user alowed to access*/
	private function auth_check(){
		if(!$this->auth->check_group("kworktime")){
			$this->klog->info("User ".$this->auth->username." is not memeber of kworktime group so access is denied to him.");
			$this->smarty->display("noaccess.tpl");	
			return false;
		}
		return true;
	}
	private function process_request(){
		if($_GET['action']=="rday")
			$this->day_report();
		else
			$this->set_times();
	}
	/**process day report*/
	private function day_report(){
		$form =& new kform("selday", $this->smarty);
		$fi_date =& $form->add_input(new kinput("idate", $this->smarty, new kv_date(), date($this->uprefs->date)));
		$sub_date =& $form->add_submit(new kwtdr_submit("dsel", $this->smarty));
		if(isset($_GET['day'])){
			$gdate = $_GET['day'];
			if(is_int($_GET['day']))
				$gdate = date($this->uprefs->date, $_GET['day']);
			$fi_date->set_value($gdate);
			$form->submit(&$sub_date);
		}
		if($form->submited()){
			$details =& $this->day_details($this->auth->userindex, strtotime($fi_date->value));
			$this->smarty->assign("ddate", $fi_date->value);
			$this->smarty->assign_by_ref("ddetails", $details["details"]);
			$this->smarty->assign_by_ref("dsumary", $details["sumary"]);
		}
		$this->smarty->display("dayreport.tpl");
	}
	/**get working status and respond with apropiate action*/
	private function set_times(){
		//$this->smarty->display("begin.tpl");
		$info=$this->get_last_info();
		switch($info["status"]){
			case kworktime::working:
			case kworktime::lunch_end:
			case kworktime::in:
			case kworktime::wbreak_end:
				$this->sworking($info);
				break;
			case kworktime::not_working:
			case kworktime::snot_working:
			case kworktime::slunch_end:
			case kworktime::swbreak_end:
			case kworktime::sin:
				$this->change_working($info["status"], kworktime::working);
				break;
			case kworktime::lunch:
				$this->slunch($info);
				break;
			case kworktime::out:
				$this->sout($info);
				break;
			case kworktime::wbreak:
				$this->sbreak($info);
				break;
			default:
				$this->sunknown($info["status"]);
		}
		//$this->smarty->display("end.tpl");
	}
	/**returns curent users working status*/
	private function get_last_info(){
		$query="SELECT stype, stime FROM kworktime.ctime WHERE user_index=$1;";
		$rezult = false;
		try{
			$rezult=&$this->sql_query->query_params($query, array($this->auth->userindex));
		}
		catch(Exception $e){
			$this->klog->err($e->getMessage());
		}
		if($rezult==false)
			return false;
		if($rezult->count()==0)
			return array("status"=>kworktime::not_working, "time"=>0);
		if($row=$rezult->next())
			return array("status"=>$row["stype"], "time"=>$row["stime"]);
		return false;
	}
	private function sworking($info){
		//loading company settings
		$settings =& new settings;
		$settings->unserialize();
		//finding last wstart time
		$startwtime=$info["time"];
		if($info["status"]!=kworktime::working)
			 $startwtime=$this->get_last_stime();
		$startwtime=strtotime($startwtime);
		//get current rime
		$ctime = time();
		//see if maxwtime has been excited
		if((time()-$startwtime)>$settings->data["maxwtime"]){
			//calculate ending time
			$endtime = $startwtime+$settings->data["daywhours"];
			//little check so that we have nice data for reports
			if($endtime>$ctime || $endtime < $startwtime)
				$endtime=$ctime;
			//folowing if is because possible endless loop
			if($this->new_status($endtime, kworktime::snot_working)){
				$this->smarty->assign("endwtime", date($this->uprefs->time." ".$this->uprefs->date, switchzone($endtime, date("Z", $endtime), $this->uprefs->tzone)));
				$this->set_times();
			}
			else{
				$this->klog->emerg("Could not set new user status. System unusable. Exiting!!!");
				exit(0);
			}
		}
		else{
			$form =& new wtform("fstop", $this->smarty);
			$form->add_submit(new kwtstatus("stop", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->klog, &$this->auth, &$this->uprefs, kworktime::not_working));
			$form->add_submit(new kwtstatus("out", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->klog, &$this->auth, &$this->uprefs, kworktime::out));
			$form->add_submit(new kwtstatus("lunch", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->klog, &$this->auth, &$this->uprefs, kworktime::lunch));
			$form->add_submit(new kwtstatus("break", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->klog, &$this->auth, &$this->uprefs, kworktime::wbreak));
			if($form->submited()){
				$this->smarty->assign("status", $this->mcoll->ok["status"]);
				$this->smarty->display("status_changed.tpl");
			}
			else{
				$this->smarty->assign("status", $info["status"]);
				$this->smarty->display("stop_working.tpl");
			}
		}
	}
	private function change_working($status, $tostatus){
		$form =& new wtform("startform", $this->smarty);
		$form->add_submit(new kwtstatus("start", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->klog, &$this->auth, &$this->uprefs, $tostatus));
		if($form->submited()){
			$this->smarty->assign("status", $tostatus);
			$this->smarty->display("status_changed.tpl");
		}
		else{
			//$this->display_status($status);
			$this->smarty->assign("status", $status);
			$this->smarty->assign("tostatus", $tostatus);
			$this->smarty->display("change_worktime.tpl");
		}
	}
	private function display_status($status){
		//if status is end_break or sililar then this means that you are working
		if(in_array($status, array(4,6,8)))
			$status=1;
		$this->smarty->assign("status",$status);
		//$this->smarty->display("status.tpl");
		//$this->smarty->clear_assign("status");
	}
	private function sunknown($status){
		$this->klog->err("System error. Unknown user status=".$status);
		print "System error. Unknown status!!";
	}
	private function get_last_stime(){
		$query="SELECT max(stime) FROM kworktime.times WHERE user_index=$1 AND stype=$2;";
		$rezult = false;
		try{
			$rezult=&$this->sql_query->query_params($query, array($this->auth->userindex, kworktime::working));
		}
		catch(Exception $e){
			$this->klog->err($e->getMessage());
		}
		$row=$rezult->next();
		if($row==false){
			$this->klog->emerg("We expect database rezult but got error. System unusable. Exiting");
			exit(0);
		}
		return $row[0];
	}
	/**function which sets new user status*/
	private function new_status($time, $status, $note=null){
		$querys = "SELECT kworktime.new_status($1,$2,$3,$4,$5);";
		$rezult = false;
		try{
			$rezult=&$this->sql_query->query_params($querys, array($this->auth->userindex, $status, $note, date("c",$time), $this->uprefs->tzone));
		}
		catch(Exception $e){
			$this->klog->err($e->getMessage());
			$this->mcoll->err($status);
			return false;
		}
		return true;
	}
	private function slunch($info){
		//loading company settings
		$settings =& new settings;
		$settings->unserialize();
		$startltime=strtotime($info["time"]);
		//get current rime
		$ctime = time();
		if($ctime-$startltime>$settings->data["maxltime"]){
			//calculate ending time
			$endtime = $startltime+$settings->data["maxltime"];
			if($endtime>$ctime || $endtime < $startltime)
				$endtime=$ctime;
			//stop lunch and work time
			//folowing if is because possible endless loop
			if($this->new_status($endtime, kworktime::slunch_end)){
				//$this->new_status($endtime+1, kworktime::snot_working)
				$this->smarty->assign("endltime", date($this->uprefs->time." ".$this->uprefs->date, switchzone($endtime, date("Z", $endtime), $this->uprefs->tzone)));
				$this->set_times();
			}
			else{
				$this->klog->emerg("Could not set new user status. System unusable. Exiting!!!");
				exit(0);
			}
		}
		else{
			$this->change_working($info["status"], kworktime::lunch_end);
		}
	}
	private function sbreak($info){
		//loading company settings
		$settings =& new settings;
		$settings->unserialize();
		$startbtime=strtotime($info["time"]);
		//get current rime
		$ctime = time();
		if($ctime-$startbtime>$settings->data["maxbtime"]){
			//calculate ending time
			$endtime = $startbtime+$settings->data["maxbtime"];
			if($endtime>$ctime || $endtime < $startbtime)
				$endtime=$ctime;
			//stop lunch and work time
			//folowing if is because possible endless loop
			if($this->new_status($endtime, kworktime::swbreak_end)){
				$this->smarty->assign("endltime", date($this->uprefs->time." ".$this->uprefs->date, switchzone($endtime, date("Z", $endtime), $this->uprefs->tzone)));
				$this->set_times();
			}
			else{
				$this->klog->emerg("Could not set new user status. System unusable. Exiting!!!");
				exit(0);
			}
		}
		else{
			$this->change_working($info["status"], kworktime::wbreak_end);
		}
	}
	private function sout($info){
		//loading company settings
		$settings =& new settings;
		$settings->unserialize();
		$startotime=strtotime($info["time"]);
		//get current rime
		$ctime = time();
		//see if maxwtime has been excited
		if((time()-$startotime)>$settings->data["maxwtime"]){
			//calculate ending time
			$endtime = $startotime+$settings->data["daywhours"];
			if($endtime>$ctime || $endtime < $startotime)
				$endtime=$ctime;
			//folowing if is because possible endless loop
			if($this->new_status($endtime, kworktime::sin)){
				$this->smarty->assign("endotime", date($this->uprefs->time." ".$this->uprefs->date, switchzone($endtime, date("Z", $endtime), $this->uprefs->tzone)));
				$this->set_times();
			}
			else{
				$this->klog->emerg("Could not set new user status. System unusable. Exiting!!!");
				exit(0);
			}
		}
		else{
			$form =& new wtform("fstop", $this->smarty);
			$form->add_submit(new kwtstatus("start", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->klog, &$this->auth, &$this->uprefs, kworktime::in));
			$form->add_submit(new kwtstatus("ostop", &$this->smarty, &$this->sql_query, &$this->mcoll, &$this->klog, &$this->auth, &$this->uprefs, kworktime::not_working));
			if($form->submited()){
				$this->smarty->assign("status", $this->mcoll->ok["status"]);
				$this->smarty->display("status_changed.tpl");
			}
			else{
				$this->smarty->assign("status", $info["status"]);
				$this->smarty->assign("tostatus", kworktime::in);
				$this->smarty->display("change_worktime.tpl");
			}
		}
	}
	private function &day_details($user, $day){
		//get day info from db
		$query="SELECT stype, note, stime, uzone FROM kworktime.times WHERE user_index=$1 AND stime>$2 AND stime<$3 ORDER BY stime;";
		$rezult = false;
		try{
			$rezult=&$this->sql_query->query_params($query, array($user, date("c",$day), date("c", $day+86400)));
		}
		catch(Exception $e){
			$this->klog->err($e->getMessage());
		}
		$details = array();
		//prepare sumary array
		//wtime - total working time
		//ltime - total lunch time
		//btime - total break time
		//otime - total out time
		$sumary=array("wtime"=>0,"ltime"=>0,"btime"=>0,"otime"=>0);
		$cur = -1;
		$last_time=$day;
		while(($row=$rezult->next())){
			$row['stime']=strtotime($row['stime']);
			$row['total']=$row['stime']-$last_time;
			$last_time=$row['stime'];
			$details[]=$row;
			switch($row['stype']){
				case kworktime::lunch_end:
				case kworktime::slunch_end:
					$sumary['ltime']+=$row['total'];
					$sumary['wtime']+=$row['total'];
					break;
				case kworktime::wbreak_end:
				case kworktime::swbreak_end:
					$sumary['btime']+=$row['total'];
					$sumary['wtime']+=$row['total'];
					break;
				case kworktime::in:
				case kworktime::sin:
					$sumary['otime']+=$row['total'];
					$sumary['wtime']+=$row['total'];
					break;
				case kworktime::snot_working:
				case kworktime::lunch:
				case kworktime::out:
				case kworktime::wbreak:
				case kworktime::not_working:
					$sumary['wtime']+=$row['total'];
					break;
			}
			$cur++;
		}
		switch($details[$cur]['stype']){
			case kworktime::working:
			case kworktime::lunch_end:
			case kworktime::wbreak_end:
			case kworktime::in:
				$sumary['wtime']+=($day+86400-$details[$cur]['stime']);
				break;
			case kworktime::lunch:
				$sumary['wtime']+=($day+86400-$details[$cur]['stime']);
				$sumary['ltime']+=($day+86400-$details[$cur]['stime']);
				break;
			case kworktime::out:
				$sumary['wtime']+=($day+86400-$details[$cur]['stime']);
				$sumary['otime']+=($day+86400-$details[$cur]['stime']);
				break;
			case kworktime::wbreak:
				$sumary['wtime']+=($day+86400-$details[$cur]['stime']);
				$sumary['btime']+=($day+86400-$details[$cur]['stime']);
				break;
		}
		return array("deatils"=>$details, "sumary"=>$sumary);
	}
}

$main =&new kworktime($auth, $prefs);
$main->display();
?>
