{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="List Users" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
<h1>Edit User</h1>
<fieldset><legend>Display Options</legend>
<form name="{$klist_user->name}" method="post" action="klist_user.php">
{kinput name=$knamep label="Username" type="text"}
<select name="{$kusr_ppage->name}">
{koption name=$kusr_ppage label="5" value="5" context="5"}
{koption name=$kusr_ppage label="15" value="15" context="15"}
{koption name=$kusr_ppage label="25" value="25" context="25" selected="sel"}
{koption name=$kusr_ppage label="50" value="50" context="50"}
</select>
{kinput name=$list_user type="submit" label="Search" class="ksubmit"}
</form>
</fieldset>
{if $no_of_users > 0}
<p>{$no_of_users} users found</p>
<p>Pages:
{foreach from=$no_of_pages item=page}
	{if $userp == $page}
	<span>{$page}</span>
	{else}
	<a href="klist_user.php?kupp={$userspp}&ksearch={$usearch}&p={$page}">{$page}</a>
	{/if}
{/foreach}
</p>
<table>
{section name=users loop=$fusers}
	<tr class="{cycle values="first, second"}"><td>{$fusers[users].username}</td><td class="action"><a href="kchange_pass.php?uname={$fusers[users].username}&index={$fusers[users].index}">Change Passwd</a></td><td class="action"><a href="kuser_groups.php?uname={$fusers[users].username}&index={$fusers[users].index}">Groups</a></td><td class="action"><a href="kdel_user.php?uname={$fusers[users].username}&index={$fusers[users].index}">Delete</a></td>
{if $fusers[users].failed >= $failed_limit}
<td class="action"><a href="kenable_user.php?uname={$fusers[users].username}&index={$fusers[users].index}">Enable</a></td>
{else}
<td class="action"><a href="kdisable_user.php?uname={$fusers[users].username}&index={$fusers[users].index}">Disable</a></td>
{/if}
</tr>
{/section}
</table>
{else}
<p>Your query did not return any user.</p>
{/if}
{include file="kfooter_en.tpl"}

