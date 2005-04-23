{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="Login" kpcss=$smarty.capture.css}
{if $login_error == -1}
<div class="error">Your accaunt is disabled please contact system administrator.</div>
{elseif $login_error == -2}
<div class="error">Password does not match. Please check your password and try again.</div>
{elseif $login_error == -3}
<div class="error">Unknown user name. Please check username and try again.</div>
{/if}
{if $idle == true}
<div class="error">You were idle for too long. Please login.</div>
{/if}
{knot_valid name=$user}<div class="error">Username should be between 4 and 20 charachters long</div>{/knot_valid}
{knot_valid name=$pass}<div class="error">Password should be between 4 and 10 charachters long</div>{/knot_valid}
<form id="{$klogin->name}" action="{$klogin->action}" method="post">
<fieldset title="Login"><legend>Login</legend>
{kinput name=$user label="Username" type="text"}
{kinput name=$pass label="Password" type="password"}
<hr style="height: 1px;" />
{kinput name=$login type="submit" label="Login" class="ksubmit"}
</fieldset>
</form>
{include file="kfooter_en.tpl"}
