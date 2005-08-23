{include file="index.tpl"}
<h2>{ki const="company_info"}Company Info{/ki}</h2>
{if $row==false}
<div class="mpms_mess_ok">{ki const="verror"}Sistem error! Could not display conpany info.{/ki}<div>
{else}
<div class="dsection_head">{ki const="general"}General{/ki}</div>
<div class="dsection">
<table>
<tr><td class="field">{ki const="name"}Conpany Name{/ki}:</td><td class="fvalue">{$row.name}</td></tr>
<tr><td class="field">{ki const="address"}Address{/ki}:</td><td class="fvalue">{$row.address}</td></tr>
<tr><td class="field">{ki const="city"}City{/ki}:</td><td class="fvalue">{$row.city}</td></tr>
<tr><td class="field">{ki const="zip"}Zip{/ki}:</td><td class="fvalue">{$row.zip}</td></tr>
<tr><td class="field">{ki const="state"}State{/ki}:</td><td class="fvalue">{$row.state}</td></tr>
<tr><td class="field">{ki const="country"}Country{/ki}:</td><td class="fvalue">{$row.country}</td></tr>
<tr><td class="field">{ki const="vat"}VAT no.{/ki}:</td><td class="fvalue">{$row.vat_no}</td></tr>
<tr><td class="field">{ki const="tel"}Phone{/ki}:</td><td class="fvalue">{$row.tel}</td></tr>
<tr><td class="field">{ki const="fax"}Fax{/ki}:</td><td class="fvalue">{$row.fax}</td></tr>
<tr><td class="field">{ki const="web"}WWW{/ki}:</td><td class="fvalue">{$row.web}</td></tr>
<tr><td class="field">{ki const="notes"}Notes{/ki}:</td><td class="fvalue">{$row.note}</td></tr>
<tr><td class="field">{ki const="private"}Private{/ki}:</td><td class="fvalue">
{if $row.private=='t'}
YES
{else}
NO
{/if}
</td></tr>
</table>
</div>
{/if}
{include file="index_end.tpl"}