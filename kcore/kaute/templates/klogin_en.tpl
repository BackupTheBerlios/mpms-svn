{capture name="css"}
<link rel="stylesheet" type="text/css" href="{$css_dir}/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="{ki const="login"}Login{/ki}" kpcss=$smarty.capture.css}
{if $login_error == -1}
<div class="error">{ki const="err_disabled"}Your accaunt is disabled please contact system administrator.{/ki}</div>
{elseif $login_error == -2}
<div class="error">{ki const="err_passwd"}Password does not match. Please check your password and try again.{/ki}</div>
{elseif $login_error == -3}
<div class="error">{ki const="err_unknown"}Unknown user name. Please check username and try again.{/ki}</div>
{/if}
{if $idle == true}
<div class="error">{ki const="err_idle"}You were idle for too long. Please login.{/ki}</div>
{/if}
{knot_valid name=$user}<div class="error">Username should be between 4 and 20 charachters long</div>{/knot_valid}
{knot_valid name=$pass}<div class="error">Password should be between 4 and 10 charachters long</div>{/knot_valid}
<form id="{$klogin->name}" action="{$klogin->action}" method="post">
<fieldset title="Login"><legend>{ki const="login_fieldset"}Login{/ki}</legend>
{kinput name=$user label="{ki const="username"}Username{/ki}" type="text"}
{kinput name=$pass label="{ki const="password"}Password{/ki}" type="password"}
<hr style="height: 1px;" />
{kinput name=$login type="submit" label="{ki const="login_label"}Login{/ki}" class="ksubmit"}
</fieldset>
</form>
{include file="kfooter_en.tpl"}
