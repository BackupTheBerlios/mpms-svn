{include file="index.tpl"}
<h2>{ki const="addper"}Add Person{/ki}</h2>
<form action="" method="post">
<fieldset class="mpms_fieldset">
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
<tr><td>{kinput name=$private label="{ki const="private"}Private{/ki}" type="checkbox"}</td></tr>
</table>
{kinput name=$save type="submit" label="{ki const="save"}Save{/ki}"}
</fieldset>
</form>
{include file="index_end.tpl"}
