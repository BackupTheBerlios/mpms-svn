{include file="begin.tpl"}
{karray name="wdate" key="0"}{ki const="mon"}Monday{/ki}{/karray}
{karray name="wdate" key="1"}{ki const="tue"}Tuesday{/ki}{/karray}
{karray name="wdate" key="2"}{ki const="wen"}Wensday{/ki}{/karray}
{karray name="wdate" key="3"}{ki const="thu"}Thursday{/ki}{/karray}
{karray name="wdate" key="4"}{ki const="fri"}Firday{/ki}{/karray}
{karray name="wdate" key="5"}{ki const="sat"}Saturday{/ki}{/karray}
{karray name="wdate" key="6"}{ki const="sun"}Sunday{/ki}{/karray}
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
	<div>{kprintf format="{ki const="report"}Day Report for %s{/ki}" arg0=$ddate}</div>
	<div>{ki const="details"}TimeClock data{/ki}</div>
{/if}
{include file="end.tpl"}