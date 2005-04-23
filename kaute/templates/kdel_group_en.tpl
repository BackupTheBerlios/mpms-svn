{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="New User" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
{if $mess==1}
<div class="ok">Group {$kname->get_value()} deleted from the system.</div>
{elseif $kname->get_value() != null}
{if $del_group_error == 2}
<div class="error">Could not delete user. Reason unknown</div>
{/if}
<form id="{$kdel_group->name}" method="post" action="kdel_group.php">
<fieldset title="Delete Group" style=>
{kinput name=$kname type="hidden"}
{kinput name=$index type="hidden"}
Are you sure that you want to delete group {$kname->get_value()}?<br />
{kinput name=$del_group type="submit" label="Yes"}
</fieldset>
</form>
{else}
<p>Could not process your request. Input data unknown.</p>
{/if}
{include file="kfooter_en.tpl"}
