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
error_reporting(E_ALL);
include 'header.php';
include 'function.php';
include 'cache/config.php';
require XOOPS_ROOT_PATH . '/header.php';

echo "<script language=\"javascript\">\nfunction EC(EC) { var MainWindow = window.open (EC, \"_blank\",\"width=380,height=240,toolbar=no,location=no,menubar=no,scrollbars=yes,resizeable=yes,status=no\");}\n</script>";

OpenTable();

$lastseconds = mktime(0, 0, 0, $month, $day, $year) - (24 * 60 * 60);
$lastday = date('j', $lastseconds);
$lastmonth = date('m', $lastseconds);
$lastyear = date('Y', $lastseconds);
$nextseconds = mktime(0, 0, 0, $month, $day, $year) + (24 * 60 * 60);
$nextday = date('j', $nextseconds);
$nextmonth = date('m', $nextseconds);
$nextyear = date('Y', $nextseconds);

echo "<table border=0 width=100% class='head'><tr>";
echo "<td align=left><A HREF=\"display.php?day=$lastday&month=$lastmonth&year=$lastyear\">" . _CAL_LEFT . '</A></td>';
echo "<td align=center><b>$day ";
echo getMonthName($month);
echo " $year</b></td>";
echo "<td align=right><A HREF=\"display.php?day=$nextday&month=$nextmonth&year=$nextyear\">" . _CAL_RIGHT . '</A></td></tr></table>';

$query = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('eCal') . " WHERE (stamp >= \"$year-$month-$day 00:00:00\" AND stamp <= \"$year-$month-$day 23:59:59\") AND valid ='yes' ORDER BY stamp");
$results = $xoopsDB->getRowsNum($query);

if (!$results) {
    echo '<br><CENTER><B>' . _NOEVENTDAY . '</B></CENTER><br>';
} else {
    echo "<table cellpadding=3 cellspacing=1 border=0  class='head' width=100%>";

    while (false !== ($row = $xoopsDB->fetchArray($query))) {
        echo "<tr  class='odd'>";

        $nomuser = $row[username];

        $auteur = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('users') . " where uname='$nomuser'");

        [$uid] = $xoopsDB->fetchRow($auteur);

        $Calanddate = $row['stamp'];

        $Calanddate = strftime(_EVEDATESTRING, strtotime($Calanddate));

        $heurmin = strftime('%H:%M', strtotime($Calanddate));

        echo "<td  class='head' width=25>$heurmin</td>";

        if ($uid) {
            echo "<td width=100><A HREF=\"../../userinfo.php?uid=$uid\">$nomuser</a></td>";
        } else {
            echo "<td width=100>$nomuser</td>";
        }

        echo "<td>+ $row[subject]</td><td width=80 align=center><A href=\"javascript:EC('display-event.php?id=$row[id]')\">" . _DETAIL . '</a></td><td width=40 align=center>';

        if ($xoopsUser) {
            $calusern = $xoopsUser->getVar('uname', 'E');

            if ($nomuser == $calusern) {
                echo "<A HREF=\"modifevent.php?id=$row[id]&day=$day&month=$month&year=$year\"><IMG SRC=\"images/edit.gif\" BORDER=0 WIDTH=16 HEIGHT=19 ALT=\""
                     . _MODREVENT
                     . "\"></A><A HREF=\"action.php?pa=DelEvent&id=$row[id]\"><IMG SRC=\"images/del.gif\" BORDER=0 WIDTH=16 HEIGHT=19 ALT=\""
                     . _SUPPREVENT
                     . '"></A></td></tr>';
            } else {
                echo '</td></tr> ';
            }
        }
    }

    echo '</table>';
}

echo "<table border=0 width=100% cellpadding=2 cellspacing=0><tr class='head'>";
echo "<td align=left><A HREF=\"display.php?day=$lastday&month=$lastmonth&year=$lastyear\">" . _CAL_LEFT . '</A></td>';
if (1 == $anoevent || $xoopsUser) {
    echo "<td align=center><A HREF=\"addevent.php?month=$month&year=$year\">" . _ADDITEM . '</A></td>';
} else {
    echo '<td>&nbsp;</td>';
}
echo "<td align=right><A HREF=\"display.php?day=$nextday&month=$nextmonth&year=$nextyear\">" . _CAL_RIGHT . '</A></td></tr>';
echo '<tr><td colspan=3>&nbsp;</td></tr>';
echo '<tr><td>&nbsp;</td><td align=center><A HREF="../eCal/">' . _RETURN . '</A></td><td>&nbsp;</td></tr></table><P>&nbsp;';

CloseTable();
echo '<P>';
OpenTable();
include 'xoops_version.php';
echo '<p align="right">' . _COPYEVENT . '</p>';
CloseTable();
require XOOPS_ROOT_PATH . '/footer.php';
