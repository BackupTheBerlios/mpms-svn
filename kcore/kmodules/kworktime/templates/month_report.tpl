{include file="begin.tpl"}
<fieldset><legend>{ki const="sel_month"}Select Year & Month{/ki}</legend><form name="{$month_sel->name}" id="{$selday->name}" action="manager.php?kmmodule=kworktime&action=rmonth" method="post">
<script type="text/javascript">
{literal}
function value_minus(input, limit){
	var ifield = document.getElementById(input);
	ifield.value = parseInt(ifield.value) - 1;
	if(ifield.value < limit)
		ifield.value=limit;
}
function value_plus(input, limit){
	var ifield = document.getElementById(input);
	ifield.value = parseInt(ifield.value) + 1;
	if(ifield.value > limit)
		ifield.value=limit;
}
function check_range(input, min, max){
	var finput = document.getElementById(input);
	var ivalue = parseInt(finput.value);
	if(ivalue>=min && ivalue<=max)
		finput.value = ivalue;
	else if(ivalue<min)
		finput.value = min;
	else
		finput.value = max;
}
{/literal}
</script>
<table>
<tr><td><button type="button" id="yearminus" name="yearminus" onclick="value_minus('wtyear',{$month_sel->minyear})">&lt;&lt;</button></td><td><input name="{$wtyear->name}" id="{$wtyear->name}" type="text" style="width: 30px;" value="{$wtyear->get_display_value()}" onchange="return check_range('{$wtyear->name}', {$month_sel->minyear}, {$month_sel->maxyear})"/></td><td><button type="button" id="yearplus" name="yearplus" onclick="value_plus('wtyear',{$month_sel->maxyear})">&gt;&gt;</button></td></tr>
<tr><td><button type="button" id="monthminus" name="monthminus" onclick="value_minus('wtmonth',1);">&lt;&lt;</button></td><td><input name="{$wtmonth->name}" id="{$wtmonth->name}" type="text" style="width: 20px;" value="{$wtmonth->get_display_value()}" onchange="return check_range('{$wtmonth->name}', 1, 12)" /></td><td><button type="button" id="monthplus" name="monthplus" onclick="value_plus('wtmonth', 12)">&gt;&gt;</button></td></tr>
</table>
{kinput name=$month_sel_sub type="submit" label="{ki const="submit"}Submit{/ki}"}
</form>
</fieldset>
{foreach name="sbydate" key="key" item="value" from=$bydate}
	{if $smarty.foreach.sbydate.first}
	<div class="report_title">{ki const="mtr_title"}Month TimeClock Report{/ki} ({$drange})</div>
	<div>{ki const="mr_user"}For user{/ki}: {$ruser}</div>
	<table>
	<tr><th>{ki const="ddate"}Date{/ki}</th><th>{ki const="defectiv"}Effective{/ki}</th><th>{ki const="dtotal"}Total{/ki}</th></tr>
	{/if}
	<tr><td>{$key}</td><td>{$value.efectivw}</td><td>{$value.totalw}</td><td><a href="manager.php?kmmodule=kworktime&amp;action=rday&amp;day={$value.link}">{ki const="link_details"}Details{/ki}</a></td></tr>
	{if $smarty.foreach.sbydate.last}
	</table>
	<table>
	<tr><td>{ki const="totalwh"}Total Working Hours{/ki}:</td><td>{$total.work}</td></tr>
	<tr><td>{ki const="effectivewh"}Effective Working Hours{/ki}:</td><td>{$total.effectiv}</td></tr>
	</table>
	{/if}
{/foreach}

{include file="end.tpl"}