{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="User Groups" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
<h1>User Groups</h1>
<p>User: {$kuname->get_value()}</p>
<fieldset><legend>Display Options</legend>
<form name="{$klist_group->name}" method="post" action="kuser_groups.php">
{kinput name=$kuname type="hidden"}
{kinput name=$uindex type="hidden"}
{kinput name=$knamep label="Group name" type="text"}
<select name="{$kgroup_ppage->name}">
{koption name=$kgroup_ppage label="5" value="5" context="5"}
{koption name=$kgroup_ppage label="15" value="15" context="15"}
{koption name=$kgroup_ppage label="25" value="25" context="25" selected="sel"}
{koption name=$kgroup_ppage label="50" value="50" context="50"}
</select>
{kinput name=$list_group type="submit" label="Search" class="ksubmit"}
</form>
</fieldset>
{if $no_of_groups > 0}
<p>There are {$no_of_groups} groups found in the system.</p>
<p>Pages:
{foreach from=$no_of_pages item=page}
	{if $groupp == $page}
	<span>{$page}</span>
	{else}
	<a href="kuser_groups.php?kgpp={$groupspp}&ksearch={$gsearch}&p={$page}&uname={$kuname->get_value()}&index={$uindex->get_value()}">{$page}</a>
	{/if}
{/foreach}
</p>
<form name="{$kuser_group->name}" action="kuser_groups.php?kgpp={$groupspp}&ksearch={$gsearch}&p={$groupp}&uname={$kuname->get_value()}&index={$uindex->get_value()}" method="post">
{kinput name=$kuname type="hidden"}
{kinput name=$uindex type="hidden"}
<table>
<tr><th>Name</th><th>Description</th></tr>
{section name=groups loop=$fgroups}
	<tr class="{cycle values="first, second"}"><td>{$fgroups[groups].name}</td><td>{$fgroups[groups].description}</td>
<td class="action">{kinput name=$fgroups[groups].cb type="checkbox"}</td>
</tr>
{/section}
</table>
{kinput name=$user_group type="submit" label="Change" class="ksubmit"}
</form>
{else}
<p>Your query did not return any group.</p>
{/if}
{include file="kfooter_en.tpl"}

