<?php
/*
    kaute - authentification and access library
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
/**
*kauto.php - kodmasin autorization module
*
*
*This module is used for auhorization.
*
*@package kauto
*@author Boris Tomic
*/

require_once 'Smarty.class.php';
require_once 'kauto_conf.php';
require_once kconf::kodform_dir."/kodform.php";
require_once kconf::kdb_dir."/kdb.php";
require_once '/usr/local/lib/php/Log.php';

//$auth = new kauth("kauto_test.php");

/**Classi which implements submit button in login form*/
class kauto_submit extends ksubmit{
	/**just kauth object needed because loging and query object*/
	private $auth;

	/**Constructor wich takes kauth object and Smarty object
	* @param kauth auth
	* @param Smarty smarty*/
	function __construct(&$auth, &$smarty){
		parent::__construct("login",$smarty);
		$this->auth=&$auth;
	}
	/**function which is trigered when login info is submitted
	*
	*@see ksubmit
	*@return integer/boolean -if >0 user index
	*		-if ==-1 duisabled account
	*		-if ==-2 wrong password
	*		-if ==-3 unknown username
	*	        -if == false error*/
	function submited(&$inputs){
		$user = $inputs['user']->get_value();
		$pass = $inputs['pass']->get_value();	
		$rez=false;
		try{
			$rez =& $this->auth->query->execute("SELECT * FROM kaute.authentify('".$user."','".sha1($user.$pass)."', ".kauth::auth_fail_limit."::int2);");
		}
		catch(Exception $e){
			$this->auth->log->emerg($e->getMessage());
			exit(1);
		}
		$row = $rez->next();
		if($row!=false){
			//login success
			if($row[0]>0){
				$this->auth->username=$user;
				$this->auth->userindex=$row[0];
				$this->auth->time=time();
				$this->auth->get_user_groups();
				if(kauth::ip_check)
					$this->auth->user_ip=$_SERVER['REMOTE_ADDR'];
				$this->auth->log->info("User ".$user." logined on system from IP: ".$_SERVER['REMOTE_ADDR']);
				return true;
			}
			//disabled account
			else if($row[0]==-1){
				$this->auth->log->warning("User ".$user." tried to login on disabled account.");
				$this->error=-1;
			}
			//wrong password
			else if($row[0]==-2){
				$this->auth->log->warning("User ".$user." entered wrong password.");
				$this->error=-2;
			}
			//unknown username
			else if($row[0]==-3){
				$this->auth->log->warning("User tried to login with unknown username \"".$user."\"");
				$this->error=-3;
			}
		}
		return false;
	}
}

/**Implements user login*/
class kauth{
	/**maximum username length*/
	const max_name=20;
	/**minimum username lenght*/
	const min_name=4;
	/**maximum password length*/
	const max_pass=10;
	/**minimum password length*/
	const min_pass=4;
	/**session path usefull to increes security*/
	const session_path="/home/kauto_sessions";
	/**see session from PHP manual*/
	const session_cookie_life=0;
	/**see session from PHP manual*/
	const session_cookie_path="/";
	/**see session from PHP manual*/
	const session_cookie_domain="";
	/**see session from PHP manual*/
	const session_cookie_secure=false;
	const ip_check=true;//should I check user IP or not
	/**Let say this is session idle time
	*
	*If user is idle for this time session will become not valid*/
	const session_time=30;//min 
	/**How often is session regenerated*/
	const session_regenerate=4;//min
	/**How many times user can enter wrong password or user name*/
	const auth_fail_limit = 3;//times
	
	public  $query;//db query object
	public $username=null;
	public $userindex=null;
	public $user_ip=null;
	public $time = 0;//last no idle time
	public $last_reg_time = 0;//last session regeneration time
	public $groups=array();//user groups
	public $log=null;//Log object
	private $form_action; //login form action attribute
	private $idle_too_long = false;//is user idle for too long
	/**initaize session
	*
	*It sets session path. It is good to change it from default (security issue)
	*It sets session cookie parameters (cookie life, cookie path, cookie domain, cookie secure)*/
	function set_session(){
		session_save_path(kauth::session_path);
		session_set_cookie_params(kauth::session_cookie_life, kauth::session_cookie_path, kauth::session_cookie_domain, kauth::session_cookie_secure);
		session_start();
	}
	/*ceck session idle time if passed logout*/	
	private function session_life_check(){
		if((time()-$_SESSION['kauth']['time'])<(kauth::session_time*60)){
			return true;
		}
		//$this->logout(1);
		$this->log->info("User \"".$_SESSION['kauth']['username']."\" was idle for too long so he/she is thrown from system.");
		session_unset();
		$this->idle_too_long = true;	
		return false;
	}
	/**check if session should be rebuild*/
	private function session_rege_check(){
		if((time()-$this->last_reg_time)>(kauth::session_regenerate*60)){
			session_regenerate_id();
			$this->last_reg_time=time();
		}
	}
	/**create kauth object
	*
	*It sets session parameters and checks authentification. If auth failed than displays login form.
	*@param string $form_action login form action parametar. Put here link which will be opened after login.
	*@param Log $log log object to where all kauth log messages will go
	*@param kdb_query $query object for db access and queries*/
	function __construct($form_action=null,&$log=null, &$query=null){
		$this->form_action = $form_action;
		//here you can set logging
		if($log == null)
			$this->log =& get_logger();
		else
			$this->log =& $log;
		if($query == null)
			$this->query = &get_kdb_connection(); 
		else
			$this->query =& $query;
		//to check xhtml coment out code below
		//this will disable authentification
		$this->set_session();
		if(!$this->check()){
			$this->login();
			$this->save_session();
		}
		//end of comment out for xhtml check

	}
	/** save session information
	*
	*Initaly this was done with __destruct but it did not work so I have to user this member function. 
	*It is used in constructor*/
	function save_session(){
		if($this->username!= null){
			$_SESSION['kauth']=array("username"=> $this->username, "userindex"=>$this->userindex, "groups"=> $this->groups, "ip"=> $this->user_ip, "time" => $this->time, "reg_time" => $this->last_reg_time);
		}
	}
	/**checks user authentification
	*
	*@return boolean if authentified true else false*/
	function check(){
		if(isset($_SESSION['kauth'])){
			if(($_SESSION['kauth']['ip']==$_SERVER['REMOTE_ADDR'] || !kauth::ip_check) && $this->session_life_check()){
				if($this->username===null && $this->userindex===null){
					$this->username=$_SESSION['kauth']['username'];
					$this->userindex=$_SESSION['kauth']['userindex'];
					$this->groups=$_SESSION['kauth']['groups'];
					$this->last_reg_time=$_SESSION['kauth']['reg_time'];
					if(kauth::ip_check)
						$this->user_ip=$_SESSION['kauth']['ip'];
					//see if session id has to be regenerated
					$this->session_rege_check();
				}
				//save new action time
				$_SESSION['kauth']['time']=time();
				$this->time=$_SESSION['kauth']['time'];		
				return true;
			}
		}
		return false;
	}
	/**returns user groups needed for premisions*/
	function get_user_groups(){
		$rez = false;
		try{
			$rez =&$this->query->execute("SELECT * FROM kaute.get_user_groups('".$this->userindex."');");
		}
		catch(Exception $e){
			$this->log->emerg($e->getMessage());
			exit(1);
		}
		while(($row=$rez->next())){
			$this->groups[$row['index']]=$row['name'];
		}
	}
	/**checking if user is member of wanted group*/
	function check_group($group){
		if(in_array($group, $this->groups) || $group==null)
			return true;
		return false;
	}
	/**displays and process login form*/
	function login(){
		$smarty =& new kautoSmarty();
		array_push($smarty->plugins_dir, kconf::kodform_plugin_dir);
		if($this->idle_too_long)
			$smarty->assign("idle", true);
		$form =& new kform("klogin", &$smarty, $this->form_action);
		$form->add_input(new kinput("user", &$smarty, new kv_min_max(kauth::min_name, kauth::max_name)));
		$form->add_input(new kinput("pass", &$smarty, new kv_min_max(kauth::min_pass, kauth::max_pass)));

		$form->add_submit(new kauto_submit(&$this, &$smarty));

		if(!$form->submited()){
			$smarty->display('klogin_en.tpl');
			exit(0);
		}
	}
	/**unsets all auth data (all session)
	*
	*@param integer $error error code if loout is forced*/
	function logout($login_url=null){	
		$this->log->info("User \"".$this->username."\" has left the system (logout)");
		session_unset();
		$smarty =& new kautoSmarty();
		$smarty->assign("klogout_error", $error);
		$smarty->assign("login_url", $login_url);
		$smarty->display('klogout_en.tpl');
	}
	function no_permission(){
		$smarty =& new klangSmarty();
		$smarty->assign("klogout_error", $error);
		$smarty->display('kno_premission_en.tpl');
	}
}
?>
