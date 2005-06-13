<?php

require_once 'ksmess_conf.php';
require_once 'kconf/kconf.php';
print "Tu sam";
$connection =& get_kdb_connection();
$to_delete=$connection->execute("SELECT path FROM ksmess.attachement WHERE message_index IS NULL");
while(($mess = $to_delete->next())){
	print "Pero";
	if(unlink($mess["path"]) || (!file_exists($mess["path"]))){
		print "Sime";
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
