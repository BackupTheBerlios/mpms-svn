{include file="index.tpl"}
{if $mess==1}
<div class="mpms_mess_ok">{$del_person|string_format:"{ki const="pdeleted"}Person %s has been deleted.{/ki}"}</div>
{/if}
{if $mess==2}
<div class="mpms_mess_err">{$del_person|string_format:"{ki const="cnotdeleted"}Error. Company %s could not be deleted. Probablly you are not owner of contact.{/ki}"}</div>
{/if}
<form action="{$search_form->action}" method="post">
<fieldset class="mpms_fieldset">
<legend>{ki const="search"}Person Details Search{/ki}</legend>
<table><tr><td><select name="{$field->name}">
{koption name=$field context="-{ki const="all_fields"}All Fields{/ki}-" value="1"}
{koption name=$field context="{ki const="first"}First Name{/ki}" value="first"}
{koption name=$field context="{ki const="last"}Last Name{/ki}" value="last"}
{koption name=$field context="{ki const="nick"}Nick{/ki}" value="nickname"}
{koption name=$field context="{ki const="mobile"}Mobile{/ki}" value="mobile"}
{koption name=$field context="{ki const="email"}E-mail{/ki}" value="email"}
{koption name=$field context="{ki const="address"}Address{/ki}" value="addres"}
{koption name=$field context="{ki const="company"}Company Name{/ki}" value="company"}
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
<tr><th>{ki const="firsth"}First{/ki}</th><th>{ki const="lasth"}Last{/ki}</th><th>{ki const="nickh"}Nick{/ki}</th><th>{ki const="mobileh"}Mobile{/ki}</th><th>{ki const="emailh"}Email{/ki}</th><th>{ki const="cpriv"}Private{/ki}</th><th>{ki const="companyh"}Company{/ki}</th></tr>
{section loop=$finded name="cur"}
<tr><td>{$finded[cur].first}</td><td>{$finded[cur].last}</td><td>{$finded[cur].nickname}</td><td>{$finded[cur].mobile}</td><td>{$finded[cur].email}</td><td>{$finded[cur].private}</td><td><a href="?kmmodule=kaddressbook&amp;action=cview&amp;c={$finded[cur].cindex}">{$finded[cur].cname}</a></td><td><a href="?kmmodule=kaddressbook&amp;action=pview&amp;p={$finded[cur].pindex}">{ki const="pview"}View{/ki}</a></td><td><a href="?kmmodule=kaddressbook&amp;action=pedit&amp;p={$finded[cur].pindex}">{ki const="pedit"}Edit{/ki}</a></td><td><a href="?kmmodule=kaddressbook&amp;action=pdel&amp;p={$finded[cur].pindex}" onclick="return isok2del('{$finded[cur].first} {$finded[cur].last}')">{ki const="cdel"}Delete{/ki}</a></td></tr>
{/section}
</table>
</div>
{/if}
{include file="index_end.tpl"}
