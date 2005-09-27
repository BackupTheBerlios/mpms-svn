<?php
/*
    ksmess - mpms system messages library
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
* This is solution for system messages for MPMS.
* @package ksmess
* @author Boris Tomic
*/
require_once 'ksmess_conf.php';
require_once 'kfunctions/kfileio_func.php';

/**attachement abstract input handler*/
abstract class ks_handler{
	/**abstrac metod which reads data from source.
	When there is no more data it returns false*/
	abstract public function read_next();
}

/**memory handler
* the simplest handler. It is not good for large memoris storage because it reads whole data at once*/
class ksh_memory extends ks_handler{
	protected $data;
	protected $end;
	/**creates memory input handler
	* @param string $data memory data*/
	public function __construct($data){
		$this->data=&$data;
		$this->end=false;
	}
	/**returns complete data
	* @return string data or false if data was already read*/
	public function read_next(){
		if($this->end===false){
			$this->end=true;
			return $this->data;
		}
		return false;
	}
}

/**
* system message
*/
class ksmessage{
	/**holds sender id
	*
	* Sender must be valid system user
	* @var integer*/
	public $from;
	/**holds receiver id
	* Receiver must be valid system user
	* @var integer*/
	public $to;
	/** subject of message
	* @var string*/
	public $subject;
	/** type of system message
	* Known system messages are defined in (to do)
	* @var int
	**/
	public $type;
	/** body of message
	* @var string*/
	public $body="";
	/**log object*/
	protected $log;
	/**creates message object
	* @param int $from from user id
	* @param mixed $to this can be user id or array of user ids to which message will be sent
	* @param string $subject subject of message
	* @param string $body contex of message - message body*/
	function __construct($from, $to, $type, $subject="no subject", $body=""){
		$this->from = $from;
		$this->to = $to;
		$this->type = $type;
		$this->subject = $subject;
		$this->body = $body;
		$this->log=get_ksmess_logger();
	}
}
/**class for messages which will be sent*/
class ksmessage_in extends ksmessage{
	/**holds message attachments
	* This is 2D array. Attachment are described as following array:
	* <code> array("name" => $some_name, "handler" => $some_handler, "type" => some_type); </code>*/
	public $attachments=array();

	/**add attachemnts to message
	* @param string $name name of attachments
	* @param ks_handler $data_handler from where data will be readed
	* @param string type mime type of attachment*/
	function add_attachment( $name, $type,ks_handler &$data_handler){
		$this->attachments[]=array("name" => $name, "handler" => $data_handler, "type" => $type);
	}
}

/**class for reading attachment data*/
class ksattach_pointer{
	/**pointer to be used for reading attachment data. it is read only file pointer*/
	public $fp;
	/**just opens file pointer for rading
	* You can use any php file reading function on this pointer
	* @param string $path path to file which will be opened for reading*/
	function __construct($path){
		$this->fp =& fopen($path,"r");
		if($this->fp===false)
			get_ksmess_logger()->error("Error occured opening ".$path." file.");
	}
	/**close opened pointer*/
	function close(){
		fclose($this->fp);
	}
	/**on terminating object just close pointer if not already closed*/
	//it seems that this is killing fp before - could be bug
	/*function __destruct(){
		$this->close();
	}*/
}
/**class for messages which will be received*/
class ksmessage_out extends ksmessage{
	/**holds message attachments
	* This is 2D array. Attachment are described as following array:
	* <code> array("name" => $some_name, "path" => $some_path, "type" => some_type); </code>*/
	private $attachments=array();
	/**creates out message from database based on message id*/
	function __construct($mess_id){
		$rez = null;
		$rez1 = null;
		$mess = null;
		try{
			$db_conn=& get_kdb_connection();
			$rez = $db_conn->execute("SELECT * FROM ksmess.receive('".$mess_id."')");
			$rez1= $db_conn->execute("SELECT * FROM ksmess.get_attachs('".$mess_id."')");
		}
		catch(Exception $e){
			get_ksmess_logger()->err($e->getMessage());
			exit(1);
		}
		if($row = $rez->next()){
			//store message data
			/*$this->type=$row['type'];
			$this->subject=$row['subject'];
			$this->body=$row['body'];
			$this->to=$row['mto'];
			$this->from=$row['mfrom'];*/
			parent::__construct($row["mfrom"], $row["mto"], $row["type"], $row["subject"], $row["body"]);
			//get me attachments info
			while($row1 = $rez1->next())
				$this->attachments[]=array($row1['name'], $row1['path'], $row['type']);
		}
		else
			get_ksmess_logger()->err("Someting is wrong with getting attachment.");
	}
	/**counts number of attachements
	* @reutrn int number of attachments*/
	function count_attach(){
		return count($this->attachments);
	}
	/**returns attachement pointer
	* @param int $no numer which identifies attachment
	* @return array*/
	function & get_attachment($no){
		if($no >=0 && $no < count($this->attachments)){
			$ret_array=array();
			$ret_array["name"]=$this->attachments[$no][0];
			$ret_array["fp"]=& new ksattach_pointer($this->attachments[$no][1]);
			$ret_array["type"]=$this->attachments[$no][2];
			return $ret_array;
			//return array("name"=>$this->attachments[$no][0], "fp"=> & new ksattach_pointer($this->attachments[$no][1]), "type"=> $this->attachments[$no][2]);
		}
		return array();
	}
}

/**ksmess engine
* This is engine which powers system messaging and maybe in future normal messaging.*/
class ksmess_engine{
	private $log;
	private $db_conn;
	/**create engine object*/
	function __construct(){
		$this->log =& get_ksmess_logger();
		$this->db_conn =& get_kdb_connection();
	}
	/**insert attachments
	* @param array $attachment array which holds atachment data*/
	protected function send_attachments(&$mess_id, $attachments){
		$error=false;
		foreach($attachments as $attachment){
			//save attachment to file sistem
			$filename = kfname_gen(ksmess_conf::att_dir_tree_deep);
			$att_path = ksmess_conf::attachements_home."/";
			for($i=0;$i<ksmess_conf::att_dir_tree_deep;$i++)
				$att_path.=$filename[$i]."/";
			$att_path.=$filename;
			$att_handler = $attachment['handler'];
			$fp = fopen($att_path, "w");
			if($fp != false){
				while(($data = $att_handler->read_next()))
					fwrite($fp, $data);
				fclose($fp);
				//save attachment info into database
				try{
					$rez = $this->db_conn->execute("SELECT * FROM ksmess.put_attach('".$mess_id."','".$attachment['name']."','".$attachment['type']."', '".$att_path."')");
				}
				catch(Exception $e){
					$this->log->err($e->getMessage());
					$error=true;
				}
			}
			else{
				$this->log->err("Could not save attachment because could not open valid file pointer.");
				$error=true;
			}
		}
		if($error){
			$this->mdelete($mess_id);
			return false;
		}
		return true;
	}
	/**function which sends message
	* in fact it writes message into database
	* @param ksmessage $message message to be sent
	* @return array index of users to which sending has failed*/
	function send(ksmessage_in $message){
		$rez = null;
		$errors = array();
		//if to is array - many recevers
		if(is_array($message->to)){
			foreach($message->to as $to){
				try{
					//getting message data saved in db and return mess_id
					$rez = $this->db_conn->execute("SELECT * FROM ksmess.send(".$message->from."::int8,".$to."::int8, ".$message->type."::int2, '".$message->subject."', '".$message->body."')");
					
				}
				catch(Exception $e){
					$this->log->error($e->getMessage());
					$errors[]=$to;
				}
				//use mess_id to store attachments
				if(($row = $rez->next())){
					if(!$this->send_attachments($row[0]))
						$errors[]=$to;
				}
			}
		}
		//if to is not array
		else{
			try{
				$rez = $this->db_conn->execute("SELECT * FROM ksmess.send(".$message->from."::int8,".$message->to."::int8, ".$message->type."::int2, '".$message->subject."', '".$message->body."')");
			}
			catch(Exception $e){
				$this->log->error($e->getMessage());
				$errors[]=$message->to;
			}
			//use mess_id to store attachments
			if(($row = $rez->next())){
				if($this->send_attachments($row[0], $message->attachments))
					$errors[]=$message->to;
			}
		}
		return $errors;
	}
	/**check if user has some system messages
	* @param int $user_id index of user for which we want to fing messages
	* @param int $type only check for this tpe of message if not specified all types are returned
	* @return array all finded message ids in array*/
	function check($user_id, $type=0){
		$rez = null;
		try{
			//see ksmess.check for details. if type is 0 then return all user messages
			$rez = $this->db_conn->execute("SELECT * FROM ksmess.checkm(".$user_id."::int8, ".$type."::int2)");
		}
		catch(Exception $e){
			$this->log->err($e->getMessage());
		}
		$mess_ids = array();
		while($row = $rez->next())
			$mess_ids[]=$row[0];
		return $mess_ids;
	}
	/**gets message from system
	* @param string $message_id message id(index) which will be returned
	* @return kmessage_out return object containgn all message data*/
	function &receive($message_id){
		return new ksmessage_out($message_id);
	}
	/**delete message from system
	* @param string $message_id id of message which will be deleted*/
	function mdelete($message_id){
		$rez = null;
		try{
			$rez = $this->db_conn->execute("SELECT * FROM ksmess.del_mess('".$message_id."')");
		}
		catch(Exception $e){
			$this->log->err($e->getMessage());
		}
		if(($row=$rez->next()))
			if($row[0]=='t')
				return true;
		$this->log->err("Could not delete message. Reason unknown.");
		return false;
	}	
}
?>
