<?php

require_once 'ksmess_conf.php';
require_once 'kconf/kconf.php';

$connection =& get_kdb_connection();
$to_delete=$connection->execute("SELECT path FROM ksmess.attachement WHERE message_index IS NULL");
while(($mess = $to_delete->next())){
	if(unlink($mess["path"]) || (!file_exists($mess["path"]))){
		try{
			$connection->execute("DELETE FROM ksmess.attachement WHERE path = '".$mess["path"]."' and message_index IS NULL");
		}
		catch(Exception $e){
			print "Some db error occured during cleaning of null attachment ".$mess["path"].".\n".$e->getMessage();
		}
	}
	else
		print "Could not delete file ".$mess["path"];

}
//$connection->execute("SELECT FROM ksmess.del_attachs()");

?>
