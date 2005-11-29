<?php

/*just test for klangSmarty*/
require_once '../slang.php';
require_once '../dlang.php';


$kl = new slangSmarty($_POST['lang']);
$dl = new dynIloc($_POST['lang'], "trans", dirname(__FILE__) . DIRECTORY_SEPARATOR . "slang" . DIRECTORY_SEPARATOR . "dyn");

$kl->assign("tdyn", sprintf($dl->translate("There is %d players.", "dyn"), 230));

$kl->assign("pero", "sime");

$kl->display("test.tpl");

$kpl = new slangSmarty($_POST['lang'], new sLangPHP());
$dpl = new dynPHP($_POST['lang'], "trans", dirname(__FILE__) . DIRECTORY_SEPARATOR . "slang" . DIRECTORY_SEPARATOR . "dynphp");

$kpl->assign("tdyn", sprintf($dpl->translate("There is %d players.", "dyn"), 230));

$kpl->assign("pero", "sime");

$kpl->display("test.tpl");

?>
