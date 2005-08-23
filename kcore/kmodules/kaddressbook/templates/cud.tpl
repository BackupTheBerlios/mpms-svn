{include file="index.tpl"}
{if $mess==1}
<div class="mpms_mess_ok">{ki const="compd_del"}User company info deleted.{/ki}</div>
{elseif $mess==2}
<div class="mpms_mess_err">{ki const="compd_not_del"}System error. User company info could not be deleted.{/ki}</div>
{/if}
{include file="index_end.tpl"}
<div><a href="manager.php?kmmodule=kaddressbook&action=psearch&p={$pindex}">{ki const="back"}Back.{/ki}</a></div>