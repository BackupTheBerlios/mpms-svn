{include file="begin.tpl"}
{include file="status.tpl"}
<fieldset>
<form name={$fstop->name} action="manager.php?kmmodule=kworktime" method="post">
{ktextarea name=$notef cols="25" rows="5" label="{ki const="note"}Note{/ki}"}<br />
{kinput name=$stop type="submit" label="{ki const="stop"}Stop{/ki}"}
{kinput name=$lunch type="submit" label="{ki const="lunch"}Lunch{/ki}"}
{kinput name=$break type="submit" label="{ki const="break"}Break{/ki}"}
{kinput name=$out type="submit" label="{ki const="out"}Out{/ki}"}
</form>
</fieldset>
{include file="end.tpl"}