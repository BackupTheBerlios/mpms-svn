<!-- start of module menu-->
<div id="mpms_module_menu">
<div class="mpms_menu_head">{ki const="TimeClock"}TimeClock{/ki}</div>
<div class="mpms_menu_item"><a href="manager.php?kmmodule=kworktime">{ki const="wtime"}Set Time{/ki}</a></div>
<div class="mpms_menu_head">{ki const="treports"}Reports{/ki}</div>
<div class="mpms_menu_item"><a href="manager.php?kmmodule=kworktime&amp;action=rday">{ki const="day"}Day{/ki}</a></div>
<div class="mpms_menu_item"><a href="manager.php?kmmodule=kworktime&amp;action=rrange">{ki const="week"}Range{/ki}</a></div>
<div class="mpms_menu_item"><a href="manager.php?kmmodule=kworktime&amp;action=rmonth">{ki const="month"}Month{/ki}</a></div>
<div class="mpms_menu_item"><a href="manager.php?kmmodule=kworktime&amp;action=ryear">{ki const="yewar"}Year{/ki}</a></div>
{if $kwtadmin==true}
<div class="mpms_menu_head">{ki const="admin"}Admin Reports{/ki}</div>
<div class="mpms_menu_item"><a href="manager.php?kmmodule=kworktime&amp;action=arday">{ki const="aday"}Day{/ki}</a></div>
<div class="mpms_menu_item"><a href="manager.php?kmmodule=kworktime&amp;action=armonth">{ki const="amonth"}Month{/ki}</a></div>
{/if}
<!-- end of module menu-->
</div>
<!-- start of body-->
<div id="mpms_module_body">