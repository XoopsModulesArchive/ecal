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
#  This function  come from PostCalendar
#  http://www.bahraini.tv
#####################################################
function userTimeOffset()
{
    global $xoopsUser, $xoopsConfig, $timeoffset;

    $useroffset = '';

    if ($xoopsUser) {
        $timezone = $xoopsUser->timezone();

        if (isset($timezone)) {
            $useroffset = $xoopsUser->timezone();
        } else {
            $useroffset = $xoopsConfig['default_TZ'];
        }
    }

    return $useroffset;
}

function getMonthName($month)
{
    if ('01' == $month) {
        $monthname = _CALJAN;
    } elseif ('02' == $month) {
        $monthname = _CALFEB;
    } elseif ('03' == $month) {
        $monthname = _CALMAR;
    } elseif ('04' == $month) {
        $monthname = _CALAPR;
    } elseif ('05' == $month) {
        $monthname = _CALMAY;
    } elseif ('06' == $month) {
        $monthname = _CALJUN;
    } elseif ('07' == $month) {
        $monthname = _CALJUL;
    } elseif ('08' == $month) {
        $monthname = _CALAUG;
    } elseif ('09' == $month) {
        $monthname = _CALSEP;
    } elseif ('10' == $month) {
        $monthname = _CALOCT;
    } elseif ('11' == $month) {
        $monthname = _CALNOV;
    } elseif ('12' == $month) {
        $monthname = _CALDEC;
    }

    return $monthname;
}

function buildMonthSelect($month)
{
    for ($i = 1; $i <= 12; $i++) {
        if ($i == $month) {
            $sel = 'SELECTED';
        } else {
            $sel = '';
        }

        $nm = getMonthName($i);

        echo "<option $sel value=\"$i\">$nm\n</option>";
    }
}

function buildDaySelect($day)
{
    for ($i = 1; $i <= 31; $i++) {
        if ($i == $day) {
            $sel = 'SELECTED';
        } else {
            $sel = '';
        }

        echo "<option $sel value=\"$i\">$i\n</option>";
    }
}

function buildYearSelect($year)
{
    for ($i = 1997; $i <= 2030; $i++) {
        if ($i == $year) {
            $sel = 'SELECTED';
        } else {
            $sel = '';
        }

        echo "<option $sel value=\"$i\">$i\n</option>";
    }
}

$time24Hour = 1;  // 1 = 24 hour time... 0 = AM/PM time

function buildHourSelect($hour)
{
    global $time24Hour, $pntable;

    if (!($time24Hour)) {
        for ($i = 1; $i <= 12; $i++) {
            if ($i == $hour) {
                $sel = 'SELECTED';
            } else {
                $sel = '';
            }

            echo "<option $sel value=\"$i\">$i</option>\n";
        }
    } else {
        for ($i = 0; $i <= 23; $i++) {
            if ($i == $hour) {
                $sel = 'SELECTED';
            } else {
                $sel = '';
            }

            echo "<option $sel value=\"$i\">";

            if ($i < 10) {
                echo '0';
            }

            echo "$i</option>\n";
        }
    }
}

function buildMinSelect($min)
{
    for ($i = 0; $i <= 55;) {
        echo $i;

        if (($i == $min) | ((0 == $i) & ('00' == $min))) {
            $sel = 'SELECTED';
        } else {
            $sel = '';
        }

        echo "<option $sel value=\"";

        if ($i < 10) {
            echo '0';
        }

        echo "$i\">";

        if ($i < 10) {
            echo '0';
        }

        echo "$i</option>\n";

        $i += 5;
    }
}

function buildAMPMSelect($ampm)
{
    if ('AM' == $ampm) {
        $sel = 'SELECTED';
    } else {
        $sel = '';
    }

    echo "<option $sel value=\"AM\">AM</option>\n";

    if ('PM' == $ampm) {
        $sel = 'SELECTED';
    } else {
        $sel = '';
    }

    echo "<option $sel value=\"PM\">PM</option>\n";
}

function getTimeFormat($hour, $min, $ampm)
{
    if (('AM' == $ampm) & ('12' == $hour)) {
        $hour = '00';
    } elseif (('PM' == $ampm) & ('12' != $hour)) {
        $hour = 12 + $hour;
    }

    $time = "$hour:$min:00";

    return $time;
}

function littlecal($month, $year)
{
    if (empty($year)) {
        $year = date('Y');
    }

    if (empty($month)) {
        $month = date('m');
    }

    $date = 01;

    $day = 01;

    $off = 0;

    while (checkdate($month, $date, $year)):
        $date++;

    endwhile;

    echo "<table border='0' cellpadding='1' cellspacing='1'  class='even'><tr class='odd' align=\"center\">";

    echo '<td>' . _CAL_SUN . '</td>';

    echo '<td>' . _CAL_MON . '</td>';

    echo '<td>' . _CAL_TUE . '</td>';

    echo '<td>' . _CAL_WED . '</td>';

    echo '<td>' . _CAL_THU . '</td>';

    echo '<td>' . _CAL_FRI . '</td>';

    echo '<td>' . _CAL_SAT . '</td>';

    echo '<tr>';

    $i = 0;

    while ($day < $date):
        if ('01' == $day and 'Sunday' == date('l', mktime(0, 0, 0, $month, $day, $year))) {
            echo "<td valign='top' height='10' width='10' class='head'>";

            echo "<input type='checkbox' name='Day[$i]' value='$day'>$day</td>";

            $off = '01';
        } elseif ('01' == $day and 'Monday' == date('l', mktime(0, 0, 0, $month, $day, $year))) {
            echo "<td></td><td valign='top' height='10' width='10' class='head'><input type='checkbox' name='Day[$i]' value='$day'>$day</td>";

            $off = '02';
        } elseif ('01' == $day and 'Tuesday' == date('l', mktime(0, 0, 0, $month, $day, $year))) {
            echo "<td></td><td></td><td valign='top' height='10' width='10' class='head'><input type='checkbox' name='Day[$i]' value='$day'>$day</td>";

            $off = '03';
        } elseif ('01' == $day and 'Wednesday' == date('l', mktime(0, 0, 0, $month, $day, $year))) {
            echo "<td></td><td></td><td></td><td valign='top' height='10' width='10' class='head'><input type='checkbox' name='Day[$i]' value='$day'>$day</td>";

            $off = '04';
        } elseif ('01' == $day and 'Thursday' == date('l', mktime(0, 0, 0, $month, $day, $year))) {
            echo "<td></td><td></td><td></td><td></td><td valign='top' height='10' width='10' class='head'><input type='checkbox' name='Day[$i]' value='$day'>$day</td>";

            $off = '05';
        } elseif ('01' == $day and 'Friday' == date('l', mktime(0, 0, 0, $month, $day, $year))) {
            echo "<td></td><td></td><td></td><td></td><td></td><td valign='top' height='10' width='10' class='head'><input type='checkbox' name='Day[$i]' value='$day'>$day</td>";

            $off = '06';
        } elseif ('01' == $day and 'Saturday' == date('l', mktime(0, 0, 0, $month, $day, $year))) {
            echo "<td></td><td></td><td></td><td></td><td></td><td></td><td valign='top' height='10' width='10' class='head'><input type='checkbox' name='Day[$i]' value='$day'>$day</td>";

            $off = '07';
        } else {
            echo "<td valign='top' height='10' width='10' class='head'><input type='checkbox' name='Day[$i]' value='$day'>$day</td>";
        }

    $day++;

    $off++;

    $i++;

    if ($off > 7) {
        echo '</tr><tr>';

        $off = '01';
    } else {
        echo '';
    }

    endwhile;

    echo '</tr></table>';
}

function move()
{
    echo '<SCRIPT LANGUAGE="javascript">
<!--
window.moveTo(10,10);
//-->
</SCRIPT>';
}
