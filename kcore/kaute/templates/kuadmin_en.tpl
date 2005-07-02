{*last xhtml check on:24.04.2005.*}
{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="User Administration" kpcss=$smarty.capture.css}
<h1>{ki const="user_admin"}User Administration{/ki}</h1>
<p><a href="knew_user.php">{ki const="new_user"}New User{/ki}</a> | <a href="klist_user.php">{ki const="edit_user"}Edit User{/ki}</a> | <a href="knew_group.php">{ki const="new_group"}New Group{/ki}</a> | <a href="klist_group.php">{ki const="edit_group"}Edit Group{/ki}</a> | <a href="kuadmin.php?logout=1">{ki const="logout"}Logout{/ki}</a></p>
{include file="kfooter_en.tpl"}
