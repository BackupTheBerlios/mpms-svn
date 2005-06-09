<?php

require_once 'ksmess_conf.php';
require_once '../kconf/kconf.php';
require_once '../kfunctions/kfileio_func.php';

print "Ceating directory tree for system messages in ".ksmess_conf::attachements_home." dir\n";

dir_gen(ksmess_conf::att_dir_tree_deep, ksmess_conf::attachements_home);

?>
