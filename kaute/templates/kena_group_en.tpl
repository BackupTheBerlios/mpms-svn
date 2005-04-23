{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="Enable Group" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
{if $mess==1}
<div class="ok">Group {$kname->get_value()} enabled.</div>
{elseif $kname->get_value() != null}
{if $ena_group_error == 2}
<div class="error">Could not enable group. Reason unknown.</div>
{/if}
<form id="{$kena_group->name}" method="post" action="kenable_group.php">
<fieldset title="Disable Group" style=>
{kinput name=$kname type="hidden"}
{kinput name=$index type="hidden"}
Are you sure that you want to enable group {$kname->get_value()}?<br />
{kinput name=$ena_group type="submit" label="Yes"}
</fieldset>
</form>
{else}
<p>Could not process your request. Input data unknown.</p>
{/if}
{include file="kfooter_en.tpl"}
