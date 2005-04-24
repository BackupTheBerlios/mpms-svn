{*last xhtml check on:24.04.2005.*}
{capture name="css"}
<link rel="stylesheet" type="text/css" href="css/kauto.css" />{/capture}
{include file="kheader_en.tpl" kptitle="New User" kpcss=$smarty.capture.css}
{include file="knavig_en.tpl"}
<h1>New User</h1>
{if $new_user_error == 3}
<div class="ok">User created.</div>
{elseif $new_user_error == 1}
<div class="error">Could not create user. Reason unknown.</div>
{elseif $new_user_error == 2}
<div class="error">Could not create user. Username already exists.</div>
{elseif $new_user_error == 4}
<div class="error">Passwords do not match. Please try again.</div>
{/if}
{knot_valid name=$kname}<div class="error">Username should be between 4 and 20 charachters long</div>{/knot_valid}
{knot_valid name=$kpass}<div class="error">Password should be between 4 and 10 charachters long</div>{/knot_valid}
<form id="{$knew_user->name}" method="post" action="">
<fieldset title="New User" style="background-color: #E0E0E0;"><legend>New User</legend>
{kinput name=$kname label="Username" type="text"}
<br />
{kinput name=$kpass label="Password" type="password"}
<br />
{kinput name=$kapass label="Repeat Password" type="password"}
<br />
{kinput name=$new_user type="submit" label="Create" class="ksubmit"}
</fieldset>
</form>
{include file="kfooter_en.tpl"}
