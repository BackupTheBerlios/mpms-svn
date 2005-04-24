{*last xhtml check on:24.04.2005.*}
{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="User Administration" kpcss=$smarty.capture.css}
<h1>User Administration</h1>
<p><a href="knew_user.php">New User</a> | <a href="klist_user.php">Edit User</a> | <a href="knew_group.php">New Group</a> | <a href="klist_group.php">Edit Group</a> | <a href="kuadmin.php?logout=1">Logout</a></p>
{include file="kfooter_en.tpl"}
