{include file="index.tpl"}
<h2>{ki const="title"}Person Edit{/ki}</h2>
{foreach from=$mess->ok item="message"}
{if $message == 1}
<div class="mpms_mess_ok">{ki const="person_changed"}Person contact changed.{/ki}</div>
{/if}
{/foreach}
{foreach from=$mess->err item="message"}
<div class="mpms_mess_err">
{if $message == 2}
{ki const="person_notchanged"}Person contact could not be changed. Internal server error.{/ki}
{elseif $message == 5}
{ki const="person_nopermison"}You do not have permissions to change person contact.{/ki}
{elseif $message == 10}
{ki const="person_email_exists"}You already have person with this email.{/ki}
{elseif $message == 11}
{ki const="person_mobile_exists"}You already have person with this mobile phone.{/ki}
{elseif $message == 12}
{ki const="person_nick_exists"}You already have person with this nickname.{/ki}
{/if}
</div>
{/foreach}
<form action="{$edit_person_f->action}" method="post">
{if $dprivate==false}
{kinput name=$private label="{ki const="private"}Private{/ki}" type="hidden"}
{/if}
<fieldset>
<legend>{ki const="general"}General{/ki}</legend>
<table>
<tr><td>{kinput name=$name label="{ki const="name"}Name{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$lname label="{ki const="lname"}Last Name{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$mname label="{ki const="mname"}Midlle Name{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$nik label="{ki const="nik"}Nick{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$jtitle label="{ki const="jtitle"}Job Title{/ki}" type="text"}</td></tr>
</table>
</fieldset>
<fieldset class="mpms_fieldset">
<legend>{ki const="lcontact"}Contacts{/ki}</legend>
<table>
<tr><td>{kinput name=$htel label="{ki const="htel"}Home Phone{/ki}" type="text"}</td><td>{kinput name=$mobile label="{ki const="mobile"}Mobile{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$wtel label="{ki const="wtel"}Work Phone{/ki}" type="text"}</td><td>{kinput name=$pager label="{ki const="pager"}Pager{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$fax label="{ki const="fax"}Fax{/ki}" type="text"}</td><td></td></tr>
<tr><td>{kinput name=$email label="{ki const="email"}Email{/ki}" type="text"}</td><td></td></tr>
</table>
</fieldset>
<fieldset class="mpms_fieldset">
<legend>{ki const="lhaddress"}Address{/ki}</legend>
<table>
<tr><td>{kinput name=$haddress label="{ki const="haddress"}Address{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$city label="{ki const="city"}City{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$zip label="{ki const="zip"}Zip{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$state label="{ki const="state"}State{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$country label="{ki const="country"}Country{/ki}" type="text"}</td></tr>
</table>
</fieldset>
<fieldset class="mpms_fieldset">
<legend>{ki const="lrest"}Other{/ki}</legend>
<table>
<tr><td>{ktextarea name=$notess label="{ki const="notes"}Notes{/ki}" cols="70" rows="5"}</td></tr>
<tr><td>{kinput name=$web label="{ki const="web"}Web{/ki}" type="text" value="http://"}</td></tr>
{if $dprivate==true}
<tr><td>{kinput name=$private label="{ki const="private"}Private{/ki}" type="checkbox"}</td></tr>
{/if}
</table>
</fieldset>
{kinput name=$save type="submit" label="{ki const="save"}Save{/ki}"}
<input name="back" type="button" value="{ki const="back"}Back{/ki}" onclick="return go2URL('manager.php?kmmodule=kaddressbook&amp;action=psearch&amp;p={$pindex}')" />
</form>
{include file="index_end.tpl"}