{include file="index.tpl"}
<h2>{ki const="person_info"}Person Info{/ki}</h2>
{if $row==false}
<div class="mpms_mess_ok">{ki const="verror"}Sistem error! Could not display person info.{/ki}<div>
{else}
<div class="dsection_head">{ki const="general"}General{/ki}</div>
<div class="dsection">
<table>
<tr><td class="field">{ki const="name"}First Name{/ki}:</td><td class="fvalue">{$row.first}</td></tr>
<tr><td class="field">{ki const="mname"}Middle Name{/ki}:</td><td class="fvalue">{$row.middle}</td></tr>
<tr><td class="field">{ki const="lname"}Last Name{/ki}:</td><td class="fvalue">{$row.last}</td></tr>
<tr><td class="field">{ki const="nick"}Nick{/ki}:</td><td class="fvalue">{$row.nickname}</td></tr>
<tr><td class="field">{ki const="jtitle"}Job Title{/ki}:</td><td class="fvalue">{$row.jtitle}</td></tr>
</table>
</div>
<div class="dsection_head">{ki const="contacts"}Contacts{/ki}</div>
<div class="dsection">
<table>
<tr><td class="field">{ki const="hphone"}Home Phone{/ki}:</td><td class="fvalue">{$row.home}</td></tr>
<tr><td class="field">{ki const="wphone"}Work Phone{/ki}:</td><td class="fvalue">{$row.work}</td></tr>
<tr><td class="field">{ki const="mobile"}Mobile{/ki}:</td><td class="fvalue">{$row.mobile}</td></tr>
<tr><td class="field">{ki const="fax"}Fax{/ki}:</td><td class="fvalue">{$row.fax}</td></tr>
<tr><td class="field">{ki const="pager"}Pager{/ki}:</td><td class="fvalue">{$row.pager}</td></tr>
<tr><td class="field">{ki const="email"}Email{/ki}:</td><td class="fvalue">{$row.email}</td></tr>
</table>
</div>
<div class="dsection_head">{ki const="address_h"}Address{/ki}</div>
<div class="dsection">
<table>
<tr><td class="field">{ki const="address"}Address{/ki}:</td><td class="fvalue">{$row.addres}</td></tr>
<tr><td class="field">{ki const="city"}City{/ki}:</td><td class="fvalue">{$row.city}</td></tr>
<tr><td class="field">{ki const="zip"}Zip{/ki}:</td><td class="fvalue">{$row.zip}</td></tr>
<tr><td class="field">{ki const="state"}State{/ki}:</td><td class="fvalue">{$row.state}</td></tr>
<tr><td class="field">{ki const="country"}Country{/ki}:</td><td class="fvalue">{$row.country}</td></tr>
</table>
</div>
<div class="dsection_head">{ki const="other"}Other{/ki}</div>
<div class="dsection">
<table>
<tr><td class="field">{ki const="notes"}Notes{/ki}:</td><td class="fvalue">{$row.notes}</td></tr>
<tr><td class="field">{ki const="web"}WWW{/ki}:</td><td class="fvalue">{$row.web}</td></tr>
<tr><td class="field">{ki const="private"}Private{/ki}:</td><td class="fvalue">
{if $row.private=='t'}
YES
{else}
NO
{/if}
</td></tr>
</table>
</div>
{if $row.company!=null}
<div class="dsection_head">{ki const="Company"}Company{/ki}</div>
<div class="dsection">
<table>
<tr><td class="field">{ki const="cname"}Name{/ki}:</td><td class="fvalue">{$row.cname}</td><td class="fvalue"><a class="dsection_more" href="manager.php?kmmodule=kaddressbook&action=cview&c={$row.cindex}">{ki const="vcinfo"}View Info{/ki}</a></td></tr>
</table>
</div>
{/if}
{/if}
{include file="index_end.tpl"}