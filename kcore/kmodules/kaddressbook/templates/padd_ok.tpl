{include file="index.tpl"}
{foreach from=$mess->ok item="message"}
{if $message == 1}
<div class="mpms_mess_ok">{ki const="comp_added"}New person contact added.{/ki}</div>
{/if}
{/foreach}
{foreach from=$mess->err item="message"}
{if $message == 2}
<div class="mpms_mess_err">{ki const="comp__notadded"}Error. New company address cound not be added. Probably it already exists or some system error ocured.{/ki}</div>
{/if}
{/foreach}
<fieldset>
<legend>{ki const="questionf"}Choose Company{/ki}</legend>
<div>Do you wnat to assign company to new user?</div>
<div><a href="?kmmodule=kaddressbook&action=cu&p={$pindex}">{ki const="yes"}Yes{/ki}</a><a href="?kmmodule=kaddressbook&action=padd">{ki const="no"}No{/ki}</a></div>
</fieldset>
{include file="index_end.tpl"}