{include file="index.tpl"}
<form action="?kmmodule=kaddressbook&action=cu&p={$pindex}" method="post">
<fieldset class="mpms_fieldset">
<legend>{ki const="search"}Company Search{/ki}</legend>
<table><tr><td><select name="{$field->name}">
{koption name=$field context="-{ki const="all_fields"}All Fields{/ki}-" value="1"}
{koption name=$field context="{ki const="cname"}Name{/ki}" value="name"}
{koption name=$field context="{ki const="caddress"}Address{/ki}" value="address"}
{koption name=$field context="{ki const="cvat"}VAT no.{/ki}" value="vat_no"}
{koption name=$field context="{ki const="ccity"}City{/ki}" value="city"}
{koption name=$field context="{ki const="ccountry"}Country{/ki}" value="country"}
{koption name=$field context="{ki const="ctel"}Tel{/ki}" value="tel"}
{koption name=$field context="{ki const="cfax"}Fax{/ki}" value="fax"}
</select></td>
<td><select name="{$operation->name}">
{koption name=$operation context="{ki const="cconstin"}contain{/ki}" value="1"}
{koption name=$operation context="{ki const="cexact"}exact{/ki}" value="2"}
{koption name=$operation context="{ki const="cstar"}start with{/ki}" value="3"}
{koption name=$operation context="{ki const="cend"}end with{/ki}" value="4"}
{koption name=$operation context="{ki const="cnot"}does not contain{/ki}" value="5"}
</select></td><td>{kinput name=$value label="{ki const="svalue"}Value{/ki}" type="text"}</td></tr></table>
{kinput name=$search type="submit" label="{ki const="find"}find{/ki}"}
{kinput name=$person type="hidden"}
</fieldset>
</form>
{if $finded_no == 0}
<div><p>{ki const="not_finded"}No data found!{/ki}</p></div>
{elseif $finded_no >0}
<div>
<table>
<tr><th>{ki const="cname"}Name{/ki}</th><th>{ki const="caddress"}Address{/ki}</th><th>{ki const="ccity"}City{/ki}</th><th>{ki const="ccountry"}Country{/ki}</th><th>{ki const="cvat"}VAT no.{/ki}</th><th>{ki const="cpriv"}Private{/ki}</th></tr>
{section loop=$finded name="cur"}
<tr><td>{$finded[cur].name}</td><td>{$finded[cur].address}</td><td>{$finded[cur].city}</td><td>{$finded[cur].country}</td><td>{$finded[cur].vat_no}</td><td>{$finded[cur].private}</td><td><a href="?kmmodule=kaddressbook&action=cua&c={$finded[cur].cindex}&p={$person->value}">{ki const="assign"}Asign{/ki}</a></td></tr>
{/section}
</table>
</div>
{/if}
<div><p>{kprintf format="{ki const="creat_new"}If you can not find company create new company contact %shere%s.{/ki}" arg0="<a href=\"?kmmodule=kaddressbook&action=cadd\">" arg1="</a>"}</p></div>
{include file="index_end.tpl"}