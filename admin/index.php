<?php

#####################################################
#  Based on PHP-NUKE: eCalendar System
#  by Don Grabowski Don@ecomjunk.com -  http://ecomjunk.com
#
#  Calendrier version 2.2 Beta for Xoops 1.0 RC3
#  Copyright © 2002, Pascal Le Boustouller
#  pascal@xoopsien.net - http://www.xoopsien.net
#  Licence: GPL
#
#  Merci de laisser ce copyright en place...
#####################################################
include 'admin_header.php';
include '../function.php';
xoops_cp_header();

?>
<script language='javascript'>
    <!--
    function CheckAll() {
        for (var i = 0; i < document.events.elements.length; i++) {
            var e = document.events.elements[i];
            if ((e.name != 'toutbox') && (e.type == 'checkbox')) {
                e.checked = document.events.toutbox.checked;
            }
        }
    }

    function CheckCheckAll() {
        var TotalBoxes = 0;
        var TotalOn = 0;
        for (var i = 0; i < document.events.elements.length; i++) {
            var e = document.events.elements[i];
            if ((e.name != 'toutbox') && (e.type == 'checkbox')) {
                TotalBoxes++;
                if (e.checked) {
                    TotalOn++;
                }
            }
        }
        if (TotalBoxes == TotalOn) {
            document.events.toutbox.checked = true;
        } else {
            document.events.toutbox.checked = false;
        }
    }

    //-->
</script>
<?php

$usertimevent = userTimeOffset() * 3600 + time();
$currentday = date('j', $usertimevent);
$currentmonth = date('m', $usertimevent);
$currentyear = date('Y', $usertimevent);

echo "<script language=\"javascript\">\nfunction EC(EC) { var MainWindow = window.open (EC, \"_blank\",\"width=380,height=240,toolbar=no,location=no,menubar=no,scrollbars=yes,resizeable=yes,status=no\");}\n</script>";

OpenTable();
echo '<B>' . _EVENTADMIN . '</B><p>';

echo '<CENTER>[ <A HREF="../addevent.php">' . _ADDITEM . '</A> | <A HREF="pref.php">' . _CONFEVENT . '</A> ]</CENTER>';

echo '<P><br>';

$query = $GLOBALS['xoopsDB']->queryF('SELECT *  FROM ' . $xoopsDB->prefix('eCal') . " WHERE valid = 'no' ORDER BY stamp ASC");
$event = $GLOBALS['xoopsDB']->getRowsNum($query);

if (!$event) {
    echo '' . _NOEVENTS . '<P>';
} else {
    echo '' . _THEREIS . " <FONT COLOR=\"#FF0000\">$event</FONT> " . _EVENTSWAIT . '<p>';

    echo "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 class='head'>
    <TR>
      <TD WIDTH=20 class='head'><CENTER><B>" . _NUM . '</B></CENTER></TD>
      <TD><B>' . _USER . '</B></TD>
	  <TD><B>' . _DATEVENT . '</B></TD>
	  <TD><B>' . _SUB . '</B></TD>
      <TD><CENTER><B>' . _OPTIONEV . '</B></CENTER></TD>
    </TR>';

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($query))) {
        $Calanddate = $row['stamp'];

        $Calanddate = strftime(_EVEDATESTRING, strtotime($Calanddate));

        echo "<tr class='odd'><td>
	<CENTER>" . $row['id'] . '</CENTER>
	</td><td>' . $row['username'] . "</td>
	<td>$Calanddate</td><td><A HREF=\"javascript:EC('../display-event-admin.php?id=" . $row['id'] . "')\">" . $row['subject'] . '</A></td>
	<td align=center><A HREF="modifevent.php?id=' . $row['id'] . '&ok=val">
	<IMG SRC="../images/edit.gif" BORDER=0 WIDTH=16 HEIGHT=19 ALT="' . _CAL_MODIF . '"></A>
	<A HREF="action.php?pa=Delete&id=' . $row['id'] . '">
	<IMG SRC="../images/del.gif" BORDER=0 WIDTH=16 HEIGHT=19 ALT="' . _DELEVENT . '"></A></td></tr>';
    }

    echo '</TABLE><P><BR>';
}

echo '<P><BR>';

$query = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('eCal') . " WHERE valid = 'yes' AND (stamp >= \"$currentyear-$currentmonth-$currentday 00:00:00\") ORDER BY stamp ASC");
$event = $GLOBALS['xoopsDB']->getRowsNum($query);

if (!$event) {
    echo '' . _NOEVENTS . '<P>';
} else {
    echo '' . _THEREIS . " <FONT COLOR=\"#FF0000\">$event</FONT> " . _EVENTS . '<p>';

    echo "<form name='events' method='post' action='action.php'><INPUT TYPE=\"hidden\" NAME=\"pa\" VALUE=\"DeleteS\">";

    echo "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 class='head'>
    <TR>
      <TD class='head'><CENTER><input name='toutbox' onclick='CheckAll();' type='checkbox' value='Check tout'></CENTER></TD>
      <TD WIDTH=20 class='head'><CENTER><B>" . _NUM . '</B></CENTER></TD>
      <TD><B>' . _USER . '</B></TD>
	  <TD><B>' . _DATEVENT . '</B></TD>
	  <TD><B>' . _SUB . '</B></TD>
	  <TD>&nbsp;</TD>
    </TR>';

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($query))) {
        $Calanddate = $row['stamp'];

        $Calanddate = strftime(_EVEDATESTRING, strtotime($Calanddate));

        echo "<tr class='odd'><td><center><input type='checkbox' onclick='CheckCheckAll();' name='id[]' value='"
             . $row['id']
             . "'></center></td><td><CENTER>"
             . $row['id']
             . '</CENTER></td><td>'
             . $row['username']
             . "</td><td>$Calanddate</td><td><A HREF=\"javascript:EC('../display-event-admin.php?id="
             . $row['id']
             . "')\">"
             . $row['subject']
             . '</A></td><td><A HREF="modifevent.php?id='
             . $row['id']
             . '&ok=mod"><IMG SRC="../images/edit.gif" BORDER=0 WIDTH=16 HEIGHT=19 ALT="'
             . _CAL_MODIF
             . '"></A></td></tr>';
    }

    echo "</TABLE><br><input type='submit' name='delete_messages' value='" . _DELEVENT . "' border='0'></form><P><br>";
}

CloseTable();
xoops_cp_footer();
?>
