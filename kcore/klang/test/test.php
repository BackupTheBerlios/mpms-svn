<?php

/*just test for klangSmarty*/
require_once '../klang.php';



$kl =& new klangSmarty($_POST['lang']);

$kl->assign("pero", "sime");

$kl->display("test.tpl");

?>
