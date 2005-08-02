{include file="index.tpl"}
<h2>{ki const="editcomp"}Edit Company{/ki}</h2>
<div>
{foreach from=$mess->ok item="message"}
{if $message == 1}
<div class="mpms_mess_ok">{ki const="comp_added"}Company contact changed.{/ki}</div>
{/if}
{/foreach}
{foreach from=$mess->err item="message"}
{if $message == 2}
<div class="mpms_mess_err">{ki const="comp_notchanged"}Company contact could not be changed. Internal server error.{/ki}</div>
{/if}
{if $message == 5}
<div class="mpms_mess_err">{ki const="comp_nopermison"}You do not have permissions to change company contact..{/ki}</div>
{/if}
{/foreach}
</div>
{if $status == 1}
<div class="mpms_mess_err">{ki const="nopermision"}You do not have permissions to change this company contact.{/ki}</div>
{else}
<form action="" method="post">
{kinput name=$cindex type="hidden"}
{kinput name=$uindex type="hidden"}
{kinput name=$cget type="hidden"}
<fieldset class="mpms_fieldset">
<legend>{ki const="general"}Company Details{/ki}</legend>
<table>
<tr><td>{kinput name=$name label="{ki const="name"}Name{/ki}" type="text"}</td><td>{kinput name=$vat label="{ki const="vat"}VAT no.{/ki}" type="text"}</td></tr>
<tr><td colspan="2">{kinput name=$address label="{ki const="addres"}Address{/ki}" type="text" style="width: 40em;"}</td><td></td></tr>
<tr><td>{kinput name=$city label="{ki const="city"}City{/ki}" type="text"}</td><td>{kinput name=$zip label="{ki const="zip"}Zip{/ki}" type="text"}</td></tr>
<tr><td>{kinput name=$state label="{ki const="state"}State{/ki}" type="text"}</td><td></td></tr>
<tr><td>{kinput name=$country label="{ki const="country"}Country{/ki}" type="text"}</td><td></td></tr>
<tr><td>{kinput name=$tel label="{ki const="tel"}Phone{/ki}" type="text"}</td><td>{kinput name=$fax label="{ki const="fax"}Fax{/ki}" type="text"}</td></tr>
<tr><td colspan="2">{kinput name=$web label="{ki const="web"}Web Address{/ki}" type="text" default="http://" style="width: 40em;"}</td><td></td></tr>
<tr><td colspan="2">{ktextarea name=$notess label="{ki const="notes"}Notes{/ki}" cols="80" rows="5"}</td><td></td></tr>
{if $status==0}
<tr><td>{kinput name=$private label="{ki const="Private"}Private{/ki}" type="checkbox"}</td><td></td></tr>
{/if}
</table>
<hr />
{kinput name=$edit_company label="{ki const="edit_company"}Save Changes{/ki}" type="submit"}
</fieldset>
</formi>
{/if}
{include file="index_end.tpl"}
