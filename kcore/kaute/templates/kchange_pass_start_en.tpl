{*last xhtml check on:24.04.2005.*}
{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="Cange Password Users" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
<h1>Change Password</h1>
{if $mess == 1}
<div class="ok">Password for user {$kname->get_value()} has been changed.</div>
{elseif $kname->get_value() != null}
<p>Please enter new password for {$kname->get_value()}</p>
{if $change_pass_error == 3}
<div class="error">Passwords do not match. Please try again.</div>
{/if}
{knot_valid name=$passwd}<div class="error">Password should be between 4 and 20 charachters long</div>{/knot_valid}
<form action="kchange_pass.php" method="post">
<fieldset>
{kinput name=$kname type="hidden"}
{kinput name=$index type="hidden"}
{kinput name=$passwd type="password" label="New Password"}<br />
{kinput name=$passwdr type="password" label="New Password Again"}<br />
{kinput name=$change_pass type="submit" label="Change" class="ksubmit"}
</fieldset>
</form>
{else}
<p>Could not process your request. Input data unknown.</p>
{/if}
{include file="kfooter_en.tpl"}

