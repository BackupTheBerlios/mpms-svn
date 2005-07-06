{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="Change Group" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
<h1>Change Group</h1>
{if $mess == 1}
<div class="ok">Group {$kname->get_value()} has been changed. <a href="klist_group.php">Back</a></div>
{elseif $mess == 2}
<p>Could not process your request. Input data unknown.</p>
{else}
{if $change_group_error == 2}
<div class="error">Could not change group {$kname->get_value()}. Reason unknown.</div>
{/if}
{knot_valid name=$description}<div class="error">Group description should be maximum 200 charachters long.</div>{/knot_valid}
<fieldset><legend>Change Group</legend>
<form action="kchange_group.php" method="post">
{kinput name=$index type="hidden"}
{kinput name=$kname type="hidden"}
<div>New Description for Group "{$kname->get_value()}"</div>
{ktextarea name=$description rows="5" cols="50"}<br />
{kinput name=$system type="checkbox" label="System Group"}
<hr />
{kinput name=$change_group type="submit" label="Change" class="ksubmit"}
</form>
</fieldset>
{/if}
{include file="kfooter_en.tpl"}
