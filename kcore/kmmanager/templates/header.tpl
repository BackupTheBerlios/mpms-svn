<?xml version="1.0" encoding="utf-8">
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="{$page_lang}">
<head>
<title>MPMS - {$page_title}</title>
<meta http-equiv="Content-Type" content="text/xhtml; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="{$skin_dir}/main.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$skin_dir}/main_print.css" media="print" />
<link rel="stylesheet" type="text/css" href="{$skin_dir}/calendar.css" media="screen" />
<script type="text/javascript" language="javascript" src="{$skin_dir}/main.js"></script>
<script type="text/javascript" language="javascript" src="{$skin_dir}/calendar.js"></script>
<script type="text/javascript" language="javascript" src="{$skin_dir}/calendar-setup.js"></script>
<script type="text/javascript" language="javascript" src="{$skin_dir}/clang/calendar-{$page_lang}.js"></script>
</head>
<body class="mpms_body">
<div id="mpms_header">
	<div id="mpms_logo">MPMS<span style="font-size: 10pt;">ALFA</span></div>
	<div id="mpms_hmess"><div><a href="?action=logout">{ki const="logout"}Logout{/ki}</a</div><div>Simple system for small and medium business :)</div></div>	
</div>
<div id="mpms_menu"><div class="modules_first"><a class="modules_link_first" href="manager.php">Home</a></div>
	{foreach from=$modules item="module" key="modulkey" name="fmodules"}	
	<div class="modules"><a class="modules_link" href="?kmmodule={$modulkey}">{$module}</a></div>
	{/foreach}
</div>
<div id="mpms_module">
