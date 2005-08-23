{include file="index.tpl"}
<div class="mpms_mess_ok">{ki const="messok"}You have changed contact data.{/ki}</div>
<fieldset>
<legend>{ki const="questionf"}Contac Company{/ki}</legend>
<div>Do you wnat to:</div>
<div><a href="?kmmodule=kaddressbook&amp;action=cu&amp;p={$pindex}">
{if $company==""}
{ki const="add"}Add Person Company{/ki}
{else}
{ki const="change"}Change Person Company{/ki}
{/if}
</a>
{if $company!=""}
<a href="?kmmodule=kaddressbook&amp;action=cud&amp;p={$pindex}">{ki const="delete"}Remove Company Info{/ki}</a>
{/if}
<a href="?kmmodule=kaddressbook&amp;action=psearch&amp;p={$pindex}">{ki const="back"}Go Back{/ki}</a></div>
</fieldset>
{include file="index_end.tpl"}