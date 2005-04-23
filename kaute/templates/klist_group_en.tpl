{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="List Groups" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
<h1>Edit Groups</h1>
<fieldset><legend>Display Options</legend>
<form name="{$klist_group->name}" method="post" action="klist_group.php">
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
<p>{$no_of_groups} groups found.</p>
<p>Pages:
{foreach from=$no_of_pages item=page}
	{if $groupp == $page}
	<span>{$page}</span>
	{else}
	<a href="klist_group.php?kgpp={$groupspp}&ksearch={$gsearch}&p={$page}">{$page}</a>
	{/if}
{/foreach}
</p>
<table>
<tr><th>Name</th><th>Description</th></tr>
{section name=groups loop=$fgroups}
	<tr class="{cycle values="first, second"}"><td>{$fgroups[groups].name}</td><td>{$fgroups[groups].description}</td>
<td class="action"><a href="kchange_group.php?index={$fgroups[groups].index}">Change</a></td>
<td class="action"><a href="kdel_group.php?gname={$fgroups[groups].name}&index={$fgroups[groups].index}">Delete</a></td>
{if $fgroups[groups].enabled == 'f'}
<td class="action"><a href="kenable_group.php?gname={$fgroups[groups].name}&index={$fgroups[groups].index}">Enable</a></td>
{else}
<td class="action"><a href="kdisable_group.php?gname={$fgroups[groups].name}&index={$fgroups[groups].index}">Disable</a></td>
{/if}
</tr>
{/section}
</table>
{else}
<p>Your query did not return any group.</p>
{/if}
{include file="kfooter_en.tpl"}

