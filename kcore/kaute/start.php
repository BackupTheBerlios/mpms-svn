<?php

require_once 'kauto_conf.php';
require_once kconf::kodform_dir.'/kodform.php';
require_once kconf::logger;

$rez = null;
$query=get_kdb_connection();

try{
	$rez =&$query->execute("SELECT kaute.initize('".sha1("admin"."changeme")."');");
}
catch(Exception $e){
	print "Could not initze";
	exit(1);
}
?>
