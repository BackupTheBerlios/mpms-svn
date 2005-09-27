{include file="begin.tpl"}
{if $endwtime!=""}
	<div class="">
	{kprintf format="{ki const="maxwtime"}You did not stoped your last worktime on time. System has stoped your last work time at %s. For any questions contact your supervisor.{/ki}" arg0=$endwtime}
	</div>
{/if}
{if $endltime!=""}
	<div class="">
	{kprintf format="{ki const="maxltime"}You did not returned from lunch on time. System has stoped your work time at %s. For any questions contact your supervisor.{/ki}" arg0=$endltime}
	</div>
{/if}
{if $endotime!=""}
	<div class="">
	{kprintf format="{ki const="maxotime"}You did not returned to work. System has stoped your last work time at %s. For any questions contact your supervisor.{/ki}" arg0=$endotime}
	</div>
{/if}
{capture name="label"}
{if $tostatus==1}
{ki const="start"}Start Working{/ki}
{elseif $tostatus==4}
{ki const="stoplunch"}Back from Lunch{/ki}
{elseif $tostatus==8}
{ki const="stopbreak"}Back from Break{/ki}
{elseif $tostatus==6}
{ki const="backin"}Back In{/ki}
{/if}
{/capture}
{include file="status.tpl"}
<fieldset>
<form name={$startworking->name} action="manager.php?kmmodule=kworktime" method="post">
{ktextarea name=$notef cols="25" rows="5" label="{ki const="note"}Note{/ki}"}<br />
{kinput name=$start type="submit" label=$smarty.capture.label}
{if $ostop!=""}
{kinput name=$ostop type="submit" label="Stop Working"}
{/if}
</form>
</fieldset>
{include file="end.tpl"}