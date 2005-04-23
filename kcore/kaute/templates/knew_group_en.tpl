{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="New Group" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
<h1>New Group</h1>
{if $new_group_error == 2}
<div class="error">Could not create group. Group with {$gname->get_value()} name exists.</div>
{/if}
{if $mess == 1}
<div class="ok">Group created.</div>
{/if}
{knot_valid name=$gname}<div class="error">Group name should be betwine 1 and 20 charachters long.</div>{/knot_valid}
{knot_valid name=$description}<div class="error">Group description should be maximum 200 charachters long.</div>{/knot_valid}
<form id="{$knew_user->name}" method="post" action="knew_group.php">
<fieldset title="New User"><legend>New Group</legend>
{kinput name=$gname label="Username" type="text"}
<hr style="height: 1px;"/>
{ktextarea name=$description rows="5" cols="50" label="Group Description"}
<br />
{kinput name=$new_group type="submit" label="Create" class="ksubmit"}
</fieldset>
</form>
{include file="kfooter_en.tpl"}
