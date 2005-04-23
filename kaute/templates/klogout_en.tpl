{include file="kheader_en.tpl" kptitle="Logout"}
{if $klogout_error == 0}
<div>Thank you for using the system. Hope to see you soon. Bye</div>
{* {elseif $klogout_error == 1}
<div>Your were idle for too long. Please relogin.</div> *}
{/if}
{if login_url != null }
<a href="{$login_url}">Login</a>
{/if}
{include file="kfooter_en.tpl"}
