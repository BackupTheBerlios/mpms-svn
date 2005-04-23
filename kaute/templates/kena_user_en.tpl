{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="New User" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
{if $mess==1}
<div class="ok">User {$kname->get_value()} enabled.</div>
{elseif $kname->get_value() != null}
{if $ena_user_error == 3}
<div class="error">Could not enable user. Reason unknown.</div>
{/if}
<form id="{$kena_user->name}" method="post" action="kenable_user.php">
<fieldset title="Enable User" style=>
{kinput name=$kname type="hidden"}
{kinput name=$index type="hidden"}
Are you sure that you want to enable user {$kname->get_value()}?<br />
{kinput name=$ena_user type="submit" label="Yes"}
</fieldset>
</form>
{else}
<p>Could not process your request. Input data unknown.</p>
{/if}
{include file="kfooter_en.tpl"}
