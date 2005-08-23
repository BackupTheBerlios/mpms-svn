{include file="index.tpl"}
{if $mess==1}
<div class="mpms_mess_ok">{$del_comp|string_format:"{ki const="cdeleted"}Company %s has been deleted.{/ki}"}</div>
{/if}
{if $mess==2}
<div class="mpms_mess_err">{$del_comp|string_format:"{ki const="cnotdeleted"}Error. Company %s could not be deleted. Probablly you are not owner of contact.{/ki}"}</div>
{/if}
<form action="{$search_form->action}" method="post">
<fieldset class="mpms_fieldset">
<legend>{ki const="search"}Company Details Search{/ki}</legend>
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
{kinput name=$search type="submit" label="{ki const="srez"}Show Results{/ki}"}
</fieldset>
</form>
{if $finded_no == 0}
<div><p>{ki const="not_finded"}No data found!{/ki}</p></div>
{elseif $finded_no >0}
<script type="text/javascript">
// <[CDATA[
{literal}
function isok2del(name){
	return window.confirm("{ki const="del_confirm"}Are you sure that you want to delete: {/ki}"+name);
}
{/literal}
// ]]>
</script>
<div>
<table>
<tr><th>{ki const="cname"}Name{/ki}</th><th>{ki const="caddress"}Address{/ki}</th><th>{ki const="ccity"}City{/ki}</th><th>{ki const="ccountry"}Country{/ki}</th><th>{ki const="cvat"}VAT no.{/ki}</th><th>{ki const="cpriv"}Private{/ki}</th></tr>
{section loop=$finded name="cur"}
<tr><td>{$finded[cur].name}</td><td>{$finded[cur].address}</td><td>{$finded[cur].city}</td><td>{$finded[cur].country}</td><td>{$finded[cur].vat_no}</td><td>{$finded[cur].private}</td><td><a href="?kmmodule=kaddressbook&amp;action=cview&amp;c={$finded[cur].cindex}">{ki const="cview"}View{/ki}</a></td><td><a href="?kmmodule=kaddressbook&amp;action=cedit&amp;c={$finded[cur].cindex}">{ki const="cedit"}Edit{/ki}</a></td><td><a href="?kmmodule=kaddressbook&amp;action=cdel&amp;c={$finded[cur].cindex}" onclick="return isok2del('{$finded[cur].name}')">{ki const="cdel"}Delete{/ki}</a></td></tr>
{/section}
</table>
</div>
{/if}
{include file="index_end.tpl"}

