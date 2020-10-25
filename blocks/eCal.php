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
require XOOPS_ROOT_PATH . '/modules/eCal/function.php';

function eCal_rand()
{
    // Today's Events block

    global $xoopsDB, $xoopsConfig;

    $block = [];

    $block['title'] = _MB_ECAL_RAND;

    include $xoopsConfig['root_path'] . '/modules/eCal/cache/config.php';

    $usertimevent = (userTimeOffset() * 3600) + time();

    $start = date('Y-m-j', addDays(0, $usertimevent)) . ' 00:00:00';

    $end = date('Y-m-j', addDays(60, $usertimevent)) . ' 23:59:59';

    $query = 'SELECT subject, stamp FROM ' . $xoopsDB->prefix('eCal') . " WHERE valid='yes' AND (stamp >= '$start' and stamp <= '$end') AND valid='yes' order by stamp";

    $numrows = $xoopsDB->getRowsNum($xoopsDB->query($query));

    if ($numrows > 1) {
        $numrows -= 1;

        // mt_srand((double)microtime() * 1000000);

        $srow = random_int(0, $numrows);

        $rowsleft = $numrows - $srow + 1;

        $rows = min($rowsleft, $blcklmt);

        $query = 'SELECT subject, stamp FROM ' . $xoopsDB->prefix('eCal') . " WHERE valid='yes' AND (stamp >= '$start' and stamp <= '$end')" . " order by stamp limit $srow, $rows";

        $block['content'] = content($query, '');
    } else {
        $block['content'] = _NOEVENTSCHED;
    }

    return $block;
}

function eCal_today()
{
    // Today's Events block

    global $xoopsDB, $xoopsConfig;

    $block = [];

    $block['title'] = _MB_ECAL_TODAY;

    include $xoopsConfig['root_path'] . '/modules/eCal/cache/config.php';

    $usertimevent = (userTimeOffset() * 3600) + time();

    $start = date('Y-m-j', addDays(0, $usertimevent)) . ' 00:00:00';

    $end = date('Y-m-j', addDays(0, $usertimevent)) . ' 23:59:59';

    $query = 'SELECT subject, stamp FROM ' . $xoopsDB->prefix('eCal') . " WHERE valid='yes' AND (stamp >= '$start' and stamp <= '$end')  AND valid='yes'" . " order by stamp limit 0, $blcklmt";

    $m = date('m', $usertimevent);

    $d = date('j', $usertimevent);

    $y = date('Y', $usertimevent);

    setlocale('LC_TIME', _MB_LOCALTIME);

    $aa = strftime('%d.%b.%Y', mktime(0, 0, 0, $m, $d, $y));

    $block['content'] = content($query, 'time');

    if ('' == $block['content']) {
        $block['content'] .= _NOEVENTSCHED;
    }

    $block['content'] = '<div align="center">' . $aa . '</div><br>' . $block['content'];

    return $block;
}

function eCal_week()
{
    // this weekblock

    global $xoopsDB, $xoopsConfig;

    $block = [];

    $block['title'] = _MB_ECAL_WEEK;

    include $xoopsConfig['root_path'] . '/modules/eCal/cache/config.php';

    $usertimevent = (userTimeOffset() * 3600) + time();

    $start = date('Y-m-j', addDays(0, $usertimevent)) . ' 00:00:00';

    $end = date('Y-m-j', addDays(7, $usertimevent)) . ' 23:59:59';

    $query = 'SELECT subject, stamp FROM ' . $xoopsDB->prefix('eCal') . " WHERE valid='yes' AND (stamp >= '$start' and stamp <= '$end') AND valid='yes'" . " order by stamp limit 0, $blcklmt";

    $block['content'] = content($query, '');

    if ('' == $block['content']) {
        $block['content'] .= _NOEVENTSCHED;
    }

    return $block;
}

function eCal_nextn()
{
    // Next N Events block

    global $xoopsDB, $xoopsConfig;

    $block = [];

    include $xoopsConfig['root_path'] . '/modules/eCal/cache/config.php';

    $block['title'] = str_replace('%N', $nextncount, _MB_ECAL_NEXTN);

    // how many events to show?

    $numEvents = $nextncount;

    $usertimevent = (userTimeOffset() * 3600) + time();

    $start = date('Y-m-j', addDays(0, $usertimevent)) . ' 00:00:00';

    $query = 'SELECT subject, stamp FROM ' . $xoopsDB->prefix('eCal') . " WHERE valid='yes' AND (stamp >= '$start') AND valid='yes' order by stamp limit 0, $numEvents";

    $block['content'] = content($query, '');

    if ('' == $block['content']) {
        $block['content'] .= _NOEVENTSCHED;
    }

    return $block;
}

function eCal_month()
{
    // this Month block

    global $xoopsDB, $xoopsConfig;

    $block = [];

    $block['title'] = _MB_ECAL_MONTH;

    include $xoopsConfig['root_path'] . '/modules/eCal/cache/config.php';

    $usertimevent = (userTimeOffset() * 3600) + time();

    $starttime = addMonths(0, 1, $usertimevent); // first day of this
    $endtime = addMonths(1, 0, $usertimevent); // last day of this

    $start = date('Y-m-j', addMonths(0, 1, $usertimevent)) . ' 00:00:00'; //changé100203

    $end = date('Y-m-j', addMonths(1, 0, $usertimevent)) . ' 23:59:59';

    $query = 'SELECT subject, stamp FROM ' . $xoopsDB->prefix('eCal') . " WHERE valid='yes' AND (stamp >= '$start' and stamp <= '$end')  AND valid='yes'" . " order by stamp limit 0, $blcklmt ";

    $block['content'] = content($query, '');

    if ('' == $block['content']) {
        $block['content'] .= _NOEVENTSCHED;
    }

    return $block;
}

function addDays($days, $ts)
{
    return mktime(0, 0, 0, date('m', $ts), date('d', $ts) + $days, date('Y', $ts));
}

function addMonths($months, $day, $ts)
{
    // $day = 0 for last day of prev,

    ///$day = 1 for first day.

    return mktime(0, 0, 0, date('m', $ts) + $months, $day, date('Y', $ts));
}

function content($query, $lead)
{
    global $xoopsDB, $xoopsConfig;

    //        $text = "<B>".$head."</B>";

    $text = '';

    $result = $xoopsDB->query($query);

    while (list($subject, $stamp) = $xoopsDB->fetchRow($result)) {
        $Cann = strftime('%Y', strtotime($stamp));

        $Cmoi = strftime('%m', strtotime($stamp));

        $Cjour = strftime('%d', strtotime($stamp));

        $Cdt = strftime('%d/%m', strtotime($stamp)); //changé 10/02/03

        $Ctm = date('H:i', strtotime($stamp));

        if ('time' == $lead) {
            $Clead = $Ctm;
        } else {
            $Clead = $Cdt;
        }

        $text .= "<div style='text-align: left;'>- $Clead <A href=\"" . $xoopsConfig['xoops_url'] . "/modules/eCal/display.php?day=$Cjour&month=$Cmoi&year=$Cann\">" . "$subject</A><br></div>";
    }

    $text .= '<DIV ALIGN="right"><A HREF="' . $xoopsConfig['xoops_url'] . '/modules/eCal/">' . _MB_ECAL_EV . '...</A></DIV>';

    return $text;
}

//**************************************************************************
//Kalenderblock erstellt am 24.10.2002 von Nobse Webmaster of www.E-Xoops.de
//**************************************************************************
function eCal_showm()
{
    global $xoopsDB, $xoopsConfig, $timeoffset, $xoopsUser;

    $blockm = [];

    $blockm['title'] = '' . _MB_ECAL_TITLE . '';

    include $xoopsConfig['root_path'] . '/modules/eCal/cache/config.php';

    $imagepath = $xoopsConfig['xoops_url'] . '/modules/eCal/images';

    $usertimevent = (userTimeOffset() * 3600) + time();

    $Date = date('m/d/Y');

    $m = date('m', $usertimevent);

    $d = date('j', $usertimevent);

    $y = date('Y', $usertimevent);

    //*******************

    $month = date('m', $usertimevent);

    $year = date('Y', $usertimevent);

    $monthname = getMonthName($month);

    //**** Get the Day (Integer) for the first day in the month */

    $First_Day_of_Month_Date = mktime('', '', '', $m, 1, $y);

    $Date = $First_Day_of_Month_Date;

    $Day_of_First_Week = date('w', $First_Day_of_Month_Date) - _MB_CALWEEKBEGINN;

    if ($Day_of_First_Week < 0) {
        $Day_of_First_Week = 6;
    }

    /**** Find the last day of the month */

    $Month = date('m', $Date);

    $day = 27;

    do {
        $End_of_Month_Date = mktime('', '', '', $m, $day, $y);

        $Test_Month = date('m', $End_of_Month_Date);

        $day += 1;
    } while ($Month == $Test_Month);

    $Last_Day = $day - 2;

    //****************************

    //$blockm['content'] = xdms_info_bulle();

    $blockm['content'] = '<table border="0" cellspacing="2" cellpadding="2"  width="100%"><tr><td><table border="0" cellspacing="1" cellpadding="0"   width="100%">';

    $blockm['content'] .= '<table border="0" cellspacing="2" cellpadding="2"  width="100%">';

    $blockm['content'] .= "\n<TR>\n\t<TH colspan=\"7\" class=\"even\" >";

    $blockm['content'] .= '<A HREF="' . $xoopsConfig['xoops_url'] . '/modules/eCal/">';

    $blockm['content'] .= "$monthname $year</a></TH>\n</TR>";

    $blockm['content'] .= "\n<TR align=\"center\"><TD class=\"outer\">"
                          . _MB_7DAY
                          . '</TD><TD class="outer">'
                          . _MB_1DAY
                          . '</TD><TD class="outer">'
                          . _MB_2DAY
                          . '</TD><TD class="outer">'
                          . _MB_3DAY
                          . '</TD><TD class="outer">'
                          . _MB_4DAY
                          . '</TD><TD class="outer">'
                          . _MB_5DAY
                          . '</TD><TD class="outer">'
                          . _MB_6DAY
                          . '</TD></TR>';

    $blockm['content'] .= "\n<TR>";

    if (0 != $Day_of_First_Week) {
        $blockm['content'] .= "\n\t<TD colspan=\"$Day_of_First_Week\">&nbsp;</TD>";
    }

    $day_of_week = $Day_of_First_Week + 1;

    /**** Build Current Month */

    for ($day = 1; $day <= $Last_Day; $day++) {
        if (1 == $day_of_week) {
            $blockm['content'] .= "\n<TR>";
        }

        //**********************************

        $alt1 = '';

        $result = $xoopsDB->query('SELECT Subject, stamp FROM ' . $xoopsDB->prefix('eCal') . " WHERE stamp >= \"$y-$m-$day 00:00:00\" AND stamp <=\"$y-$m-$day 23:59:00\" AND valid='yes'  ");

        $alt1 .= "$day.$m.$y
";

        while (list($alttext, $stamp) = $xoopsDB->fetchRow($result)) {
            $zeit = mb_substr($stamp, 11, 5);

            $alt1 .= "- $zeit $alttext
";
        }

        $alt1 .= '"';

        //**********************************

        $link = '<A HREF="' . $xoopsConfig['xoops_url'] . "/modules/eCal/display.php?day=$day&month=$m&year=$y\" title=\"$alt1.\">";

        $result = $xoopsDB->query('SELECT count(id) FROM ' . $xoopsDB->prefix('eCal') . " WHERE  stamp >= \"$y-$m-$day 00:00:00\" AND stamp <=\"$y-$m-$day 23:59:00\" AND valid='yes'");

        [$dayrows] = $xoopsDB->fetchRow($result);

        //**********************************

        $alt = '';

        $result = $xoopsDB->query('SELECT Subject, stamp FROM ' . $xoopsDB->prefix('eCal') . " WHERE stamp >= \"$y-$m-$day 00:00:00\" AND stamp <=\"$y-$m-$day 23:59:00\" AND valid='yes'  ");

        $alt .= "alt=\"$day.$m.$y
";

        while (list($alttext, $stamp) = $xoopsDB->fetchRow($result)) {
            $zeit = mb_substr($stamp, 11, 5);

            $alt .= "- $zeit $alttext
";
        }

        $alt .= '"';

        //**********************************

        $imgprops = 'width="13" height="14" border="0"';

        if ($day == date('d', $usertimevent)) {
            $blockm['content'] .= "\n\t<TD align=\"center\"  bgcolor=" . $daycolor . "><b>$link<b>$day</b><br>";
        } else {
            $blockm['content'] .= "\n\t<TD align=\"center\" class=\"outer\">$link $day<br>";
        }

        if (0 == $dayrows) {
            $blockm['content'] .= "<img src=\"$imagepath/events0.gif\" $imgprops $alt>";
        } elseif ($dayrows >= 4) {
            $blockm['content'] .= "<img src=\"$imagepath/events4.gif\" $imgprops $alt>";
        } elseif ($dayrows >= 3) {
            $blockm['content'] .= "<img src=\"$imagepath/events3.gif\" $imgprops $alt>";
        } elseif ($dayrows >= 2) {
            $blockm['content'] .= "<img src=\"$imagepath/events2.gif\" $imgprops $alt>";
        } else {
            $blockm['content'] .= "<img src=\"$imagepath/events1.gif\" $imgprops $alt>";
        }

        $blockm['content'] .= '</a></TD>';

        if (7 == $day_of_week) {
            $day_of_week = 0;

            $blockm['content'] .= "\n</TR>";
        }

        $day_of_week += 1;
    }

    $blockm['content'] .= '</table></td></tr></table>';

    return $blockm;
}
