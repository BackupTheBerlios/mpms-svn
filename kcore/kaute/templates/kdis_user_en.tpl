{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="Disable User" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
{if $mess==1}
<div class="ok">User {$kname->get_value()} disabled.</div>
{elseif $kname->get_value() != null}
{if $dis_user_error == 2}
<div class="error">Could not disable user. Reason unknown.</div>
{/if}
<form id="{$kdis_user->name}" method="post" action="kdisable_user.php">
<fieldset title="Disable User" style=>
{kinput name=$kname type="hidden"}
{kinput name=$index type="hidden"}
Are you sure that you want to disable user {$kname->get_value()}?<br />
{kinput name=$dis_user type="submit" label="Yes"}
</fieldset>
</form>
{else}
<p>Could not process your request. Input data unknown.</p>
{/if}
{include file="kfooter_en.tpl"}
