{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="Disable Group" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
{if $mess==1}
<div class="ok">Group {$kname->get_value()} disabled.</div>
{elseif $kname->get_value() != null}
{if $dis_group_error == 2}
<div class="error">Could not disable group. Reason unknown.</div>
{/if}
<form id="{$kdis_group->name}" method="post" action="kdisable_group.php">
<fieldset title="Disable Group" style=>
{kinput name=$kname type="hidden"}
{kinput name=$index type="hidden"}
Are you sure that you want to disable group {$kname->get_value()}?<br />
{kinput name=$dis_group type="submit" label="Yes"}
</fieldset>
</form>
{else}
<p>Could not process your request. Input data unknown.</p>
{/if}
{include file="kfooter_en.tpl"}
