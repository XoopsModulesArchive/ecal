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
include 'function.php';
require XOOPS_ROOT_PATH . '/header.php';
$myts = MyTextSanitizer::getInstance();

$tablename = date('Fy', mktime(0, 0, 0, $month, 1, $year));

OpenTable();
echo "<b><center>$day ";
echo getMonthName($month);
echo " $year</b></center><p>";

if ($xoopsUser) {
    if ($xoopsUser->isAdmin()) {
        $query = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('eCal') . " WHERE stamp >= \"$year-$month-$day 00:00:00\" AND stamp <= \"$year-$month-$day 23:59:59\" ORDER BY stamp");
    } else {
        $iddds = $xoopsUser->getVar('uname', 'E');
        $query = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('eCal') . " WHERE username='$iddds' AND (stamp >= \"$year-$month-$day 00:00:00\" AND stamp <= \"$year-$month-$day 23:59:59\") ORDER BY stamp");
    }
}

echo "<table cellpadding=3 cellspacing=1 border=0 width=100% class='even'>
				<tr class='even'><td><b>" . _USER . '</td><td><b>' . _T . '</td>
				<td><b>' . _SUB . '</td><td><b>' . _DESC . '</td><td><b>' . _CAL_URL . '</td>
				<td><b>' . _OPTIONEV . '</td></tr>';

$i = 0;
while (false !== ($row = $xoopsDB->fetchArray($query))) {
    $i++;

    $description = htmlspecialchars($row['description'], ENT_QUOTES | ENT_HTML5);
    $subject     = htmlspecialchars($row['subject'], ENT_QUOTES | ENT_HTML5);

    echo "<tr class='odd'>
					<td>$row['username']</td>
					<td>$row['stamp']</td>
					<td>$subject</td>
					<td>$description</td>
					<td><A HREF=\"$row['url']\" TARGET=\"_blank\">$row['url']</A></td>
					<td>[ <A HREF=\"action.php?pa=DelEvent&id={
        $row['id']}\">" . _SUPPREVENT . "</A> | <A HREF=\"modifevent.php?id=$row[id]&day=$day&month=$month&year=$year\">" . _MODREVENT . '</A> ]</td></tr>';
	}
echo '</table><p>';

CloseTable();
require XOOPS_ROOT_PATH . '/footer.php';

