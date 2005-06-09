#!/usr/local/bin/php
<?php

require_once 'ksmess_conf.php';
require_once '../kconf/kconf.php';

try{
	$connection =& get_kdb_connection();
	$connection->execute("SELECT FROM ksmess.del_attachs()");
}
catch(Exception $e){
	print "Some db error occured during cleaning of null attachments.\n".$e->getMessage();;
}

?>
