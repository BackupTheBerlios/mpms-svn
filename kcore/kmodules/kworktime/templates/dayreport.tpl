{include file="begin.tpl"}
{karray name="sreport" key="1"}{ki const="work"}Start Working{/ki}{/karray}
{karray name="sreport" key="2"}{ki const="stop"}Stop Working{/ki}{/karray}
{karray name="sreport" key="3"}{ki const="lunch"}Lunch{/ki}{/karray}
{karray name="sreport" key="4"}{ki const="blunch"}Back From Lunch{/ki}{/karray}
{karray name="sreport" key="5"}{ki const="out"}Out{/ki}{/karray}
{karray name="sreport" key="6"}{ki const="bin"}Back In{/ki}{/karray}
{karray name="sreport" key="7"}{ki const="break"}On Break{/ki}{/karray}
{karray name="sreport" key="8"}{ki const="bbreak"}Back From Break{/ki}{/karray}
{karray name="sreport" key="9"}{ki const="sstop"}System Stop Working{/ki}{/karray}
{karray name="sreport" key="10"}{ki const="sblunch"}System Lunch End{/ki}{/karray}
{karray name="sreport" key="11"}{ki const="sbbreak"}System Break End{/ki}{/karray}
{karray name="sreport" key="12"}{ki const="sbin"}System Back In{/ki}{/karray}

{knot_valid name=$idate}<div class="mpms_mess_err">{ki const="pero"}Please enter valid date!{/ki}</div>{/knot_valid}
<fieldset><form name="{$selday->name}" id="{$selday->name}" action="manager.php?kmmodule=kworktime&action=rday" method="post">
{kinput name=$idate type="text" label="{ki const="dfield"}Date{/ki}"}
<button id="select_day" name="select_day">{ki const="sel_Date"}Select Date{/ki}</button><br />
<input name="{$dsel->name}" type="submit" value="{ki const="subm"}Submit{/ki}" />
</form>
</fieldset>
<script type="text/javascript">
    Calendar.setup({ldelim}
        inputField     :    "{$idate->name}",           //*
        ifFormat       :    "%d.%m.%Y.",
        showsTime      :    false,
        button         :    "select_day",        //*
        step           :    1,
{if $sm!=""}
        date		:   new Date({$sy},{$sm},{$sd})
{else}
	date		: new Date()
{/if}
    {rdelim});
</script>
{if $ddate!=""}
	<div class="report_title">{kprintf format="{ki const="report"}Day Report for %s{/ki}" arg0=$ddate}</div>
	<div class="report_section">{ki const="details"}TimeClock data{/ki}</div>
	{section name=det loop=$ddetails}
		{if $smarty.section.det.first}
		<table>
		<tr class="report_thead"><th>{ki const="time"}Time{/ki}</th><th>{ki const="event"}Event{/ki}</th><th>{ki const="note"}Note{/ki}</th></tr>
		{/if}
		<tr class="report_row{cycle values="0,1"}">
		<td>{$ddetails[det].stime}</td><td>{kgetavalue name="sreport" key=$ddetails[det].stype}</td><td>{$ddetails[det].note}</td>
		</tr>
		{if $smarty.section.det.last}
		</table>
		{/if}
	{sectionelse}
		<div class="mpms_mess_info">{ki const="notcdata"}There is no TimeClock data!!!{/ki}</div>
	{/section}
	<div class="report_section">{ki const="sumary"}Summary{/ki}</div>
	{if $dsumary.working}
		<div class="mpms_mess_err">{ki const="snotvalid"}Sumary is not vaild. User is still working!!!{/ki}</div>
	{/if}
	<table>
	<tr><td>{ki const="wtotal"}Total working time{/ki}:</td><td>{$dsumary.wtime}</td></tr>
	<tr><td>{ki const="ltotal"}Total lunch time{/ki}:</td><td>{$dsumary.ltime}</td></tr>
	<tr><td>{ki const="btotal"}Total break time{/ki}:</td><td>{$dsumary.btime}</td></tr>
	<tr><td>{ki const="ototal"}Total out time{/ki}:</td><td>{$dsumary.otime}</td></tr>
	</table>
{/if}

{include file="end.tpl"}