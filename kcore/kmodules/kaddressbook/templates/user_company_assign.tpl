{include file="index.tpl"}
{if $mess==0}
<div class="mpms_mess_ok">{ki const="ok"}Company succesfuly assigned to user.{/ki}</div>
{else}
<div calss="mpms_mess_err">{ki const="err"}Error. Cound not assign company to user. Internal error.{/ki}</div>
{/if}
{include file="index_end.tpl"}