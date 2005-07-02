{include file="kheader_en.tpl" kptitle="{ki const="title"}Logout{/ki}"}
{if $klogout_error == 0}
<div>{ki const="user_mess"}Thank you for using the system. Hope to see you soon. Bye{/ki}</div>
{* {elseif $klogout_error == 1}
<div>{ki const="idle_mess"}Your were idle for too long. Please relogin.{/ki}</div> *}
{/if}
{if login_url != null }
<a href="{$login_url}">{ki const="login"}Login{/ki}</a>
{/if}
{include file="kfooter_en.tpl"}
