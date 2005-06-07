<?php

class klang_conf{
	const regex_all=sprintf("(.*?)", klang_conf::regex_one);
	const regex_one="{ki\s+const=\"%s\"\s*}((.|\n|\r|\s)*?){/ki}";
}

?>
