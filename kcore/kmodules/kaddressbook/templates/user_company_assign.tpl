{include file="index.tpl"}
{if $mess==0}
<div>{ki const="ok"}Company succesfuly assigned to user.{/ki}</div>
{else}
<div>{ki const="err"}Error. Cound not assign company to user. Internal error.{/ki}</div>
{/if}
{include file="index_end.tpl"}