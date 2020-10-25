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
include 'header.php';
require XOOPS_ROOT_PATH . '/modules/eCal/function.php';
include 'cache/config.php';

if ('eCal' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    require XOOPS_ROOT_PATH . '/header.php';

    make_cblock();

    echo '<br>';
} else {
    $xoopsOption['show_rblock'] = 0;

    require XOOPS_ROOT_PATH . '/header.php';
}
$myts = MyTextSanitizer::getInstance();

$month = $_GET['month'] ?? 0;

echo "<script language=\"javascript\">\nfunction EC(EC) { var MainWindow = window.open (EC, \"_blank\",\"width=380,height=240,toolbar=no,location=no,menubar=no,scrollbars=yes,resizeable=yes,status=no\");}\n</script>";

OpenTable();

$usertimevent = time() + (userTimeOffset() * 3600);
$currentday = date('j', $usertimevent);
$currentmonth = date('m', $usertimevent);
$currentyear = date('Y', $usertimevent);

$lastday = 1;

if (!$month) {
    $month = date('m', $usertimevent);

    $year = date('Y', $usertimevent);
}

$Date = date('F', mktime(0, 0, 0, $month, 1, $year));
$firstday = date('w', mktime(0, 0, 0, $month, 1, $year));

while (checkdate($month, $lastday, $year)) {
    $lastday++;
}

$nextmonth = $month + 1;
$nextyear = $year;

if (13 == $nextmonth) {
    $nextmonth = 1;

    $nextyear = $year + 1;
}

$lastmonth = $month - 1;
$lastyear = $year;

if (0 == $lastmonth) {
    $lastmonth = 12;

    $lastyear = $year - 1;
}

echo '<B>' . _CALENDEVENT . '</B><P>';
echo "<table border=0 width=100% class='head'><tr>";
echo "<td align=left><A HREF=\"index.php?month=$lastmonth&year=$lastyear\">" . _BAC . '</A></td>';
echo '<td align=center><form method=post action=index.php>';
echo '<select size=1 name=month>';
buildMonthSelect($month);
echo '</select>&nbsp;<select name="year">';
buildYearSelect($year);
echo '</select>&nbsp;<INPUT TYPE="submit" VALUE="ok"></td></form>';
echo "<td align=right><A HREF=\"index.php?month=$nextmonth&year=$nextyear\">" . _FOR . '</A></td></tr></table>';

echo "<table width=100% cellpadding=5  CELLSPACING=1 border=0  class='even'>";
echo "<tr class='odd'><td width=14%><b>" . _SUN . '</td><td width=14%><b>' . _MON . '</td>
                  <td width=14%><b>' . _TUE . '</td><td width=14%><b>' . _WED . '</td>
                  <td width=14%><b>' . _THU . '</td><td width=16%><b>' . _FRI . '</td>
                  <td width=14%><b>' . _SAT . '</td></tr>';

for ($i = 0; $i < 7; $i++) {
    if ($i < $firstday) {
        echo '<td></td>';
    } else {
        $thisday = ($i + 1) - $firstday;

        if ($currentyear > $year) {
            echo "<td valign=top class='head'";
        } elseif ($currentmonth > $month && $currentyear == $year) {
            echo "<td valign=top class='head'>";
        } elseif ($currentmonth == $month && $currentday > $thisday && $currentyear == $year) {
            echo "<td valign=top class='head'>";
        } elseif ($currentmonth == $month && $currentday == $thisday && $currentyear == $year) {
            echo "<td valign=top bgcolor=\"$daycolor\">";
        } else {
            echo "<td valign=top class='head'>";
        }

        echo "<a href=display.php?day=$thisday&month=$month&year=$year><B>$thisday</B></a><br><HR>";

        $query2 = $xoopsDB->query('SELECT id, subject FROM ' . $xoopsDB->prefix('eCal') . " WHERE (stamp >= \"$year-$month-$thisday 00:00:00\" and stamp <= \"$year-$month-$thisday 23:59:59\") AND valid = 'yes' ORDER BY stamp");

        for ($j = 0; $j < $xoopsDB->getRowsNum($query2); $j++) {
            $results = $xoopsDB->fetchArray($query2);

            if ($results['subject']) {
                $subject = htmlspecialchars($results['subject'], ENT_QUOTES | ENT_HTML5);

                echo "+ <A href=\"javascript:EC('display-event.php?id=$results[id]')\">$subject</a><br>";
            }
        }

        if ($xoopsDB->getRowsNum($query2) < 4) {
            for ($j = 0; $j < (4 - $xoopsDB->getRowsNum($query2)); $j++) {
                echo '<br>';
            }
        }

        echo '</td>';
    }
}

echo "</tr>\n";

$nextday = ($i + 1) - $firstday;

for ($j = 0; $j < 5; $j++) {
    //echo "<tr>";

    for ($k = 0; $k < 7; $k++) {
        if ($nextday < $lastday) {
            if ($currentyear > $year) {
                echo "<td valign=top class='head'>";
            } elseif ($currentmonth > $month && $currentyear == $year) {
                echo "<td valign=top class='head'>";
            } elseif ($currentmonth == $month && $currentday > $nextday && $currentyear == $year) {
                echo "<td valign=top class='head'>";
            } elseif ($currentmonth == $month && $currentday == $nextday && $currentyear == $year) {
                echo "<td valign=top bgcolor=\"$daycolor\">";
            } else {
                echo "<td valign=top class='head'>";
            }

            echo "<a href=display.php?day=$nextday&month=$month&year=$year><B>$nextday</B></a><br><HR>";

            $query3 = $xoopsDB->query('SELECT id, subject FROM ' . $xoopsDB->prefix('eCal') . " WHERE (stamp >= \"$year-$month-$nextday 00:00:00\" AND stamp <= \"$year-$month-$nextday 23:59:59\") AND valid = 'yes' ORDER BY stamp");

            for ($i = 0; $i < $xoopsDB->getRowsNum($query3) + 4; $i++) {
                $results2 = $xoopsDB->fetchArray($query3);

                if ($results2['subject']) {
                    $subject2 = htmlspecialchars($results2['subject'], ENT_QUOTES | ENT_HTML5);

                    echo "+ <A href=\"javascript:EC('display-event.php?id=$results2[id]')\">$subject2</a><br>";
                } elseif ($i < 4) {
                    echo '<br>';
                }
            }

            echo '</td>';

            $nextday++;
        }
    }

    echo "</tr>\n";
}
echo '</table>';

echo "<table border=0 width=100% class='head'><tr>";
echo "<td align=left><A HREF=\"index.php?month=$lastmonth&year=$lastyear\">" . _BAC . '</A></td>';
if (1 == $anoevent || $xoopsUser) {
    echo "<td align=center><A HREF=\"addevent.php?month=$month&year=$year\">" . _ADDITEM . '</A></td>';
} else {
    echo '<td>&nbsp;</td>';
}
echo "<td align=right><A HREF=\"index.php?month=$nextmonth&year=$nextyear\">" . _FOR . '</A></td></tr></table>';

CloseTable();
echo '<P>';
OpenTable();
include 'xoops_version.php';
echo '<p align="right">' . _COPYEVENT . '</p>';
CloseTable();

require XOOPS_ROOT_PATH . '/footer.php';
