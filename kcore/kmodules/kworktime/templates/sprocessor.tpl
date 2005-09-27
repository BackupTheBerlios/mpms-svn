{if $status==1 || $status==4 || $status==8 || $status==6}
{ki const="working"}working{/ki}
{elseif $status==2}
{ki const="notworking"}wot working{/ki}
{elseif $status==3}
{ki const="lunch"}lunch{/ki}
{elseif $status==5}
{ki const="out"}out{/ki}
{elseif $status==7}
{ki const="break"}on break{/ki}
{elseif $status==9}
{ki const="snotworking"}not working (set by system){/ki}
{elseif $status==10}
{ki const="slunchend"}not working (set by system because you were to long on lunch){/ki}
{elseif $status==11}
{ki const="slunchend"}not working (set by system because you were to long on break){/ki}
{elseif $status==12}
{ki const="slunchend"}not working (set by system because you were out to long){/ki}
{else}
{ki const="unknown"}Unknown{/ki}
{/if}