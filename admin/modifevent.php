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
xoops_cp_header();
require XOOPS_ROOT_PATH . '/modules/eCal/function.php';

$myts = MyTextSanitizer::getInstance();
$id = $_GET['id'] ?? 0;
$ok = $_GET['ok'] ?? 0;

global $xoopsUser, $xoopsDB;

OpenTable();
if (!$id) {
    echo '<p>' . _ERRORMSG1 . '';

    break;
}

if ('mod' == $ok) {
    echo '<B>' . _MSG3 . '</B>';
} else {
    echo '<B>' . _MSG4 . '</B>';
}

$query = $GLOBALS['xoopsDB']->queryF('SELECT *, RIGHT(stamp, 8) AS thetime FROM ' . $xoopsDB->prefix('eCal') . " WHERE id = '$id'");
$row = $GLOBALS['xoopsDB']->fetchBoth($query);
$description = preg_replace('<br>', "\n", $row['description']);

$Date_Array = explode('-', $row['stamp']);

echo "<form method=post action=action.php>
<input type=hidden name=id value='{$row['id']}'>
<table><tr><td><b>" . _USER . '</td>';

echo "<td>{$row['username']} <input type=hidden 
	name=username value='{$row['username']}'></td></tr>";

echo '<tr><td><b>' . _D . '</td><td><select name=day size=1>';
buildDaySelect($Date_Array[2]);
echo '</select><select size=1 name=month>';
buildMonthSelect($Date_Array[1]);
echo '</select><select size=1 name=year>';
buildYearSelect($Date_Array[0]);
echo '</select></tr>

<tr><td><b>' . _TSHOW . "</td><td><input type=text name=time value='{$row['thetime']}'></td></tr>
 <tr><td><b>" . _SUBSHOW . "</td><td><input type=text name=subject value=\"{$row['subject']}\" size=40></td></tr>
<tr><td><b>" . _DESC . "</td><td><textarea wrap=virtual rows=5 cols=50 name=description>$description</textarea></td></tr>
<tr><td><b>" . _CAL_URL . "</td><td><input type=text name=url value=\"{$row['url']}\" size=40></td></tr>
</table>";
echo '<input type=hidden name=valid value="yes">';

if ('mod' == $ok) {
    echo '<input type=hidden name=pa value=ModifEvent><input type=submit value="' . _CAL_MODIF . '">';
} else {
    echo '<input type=hidden name=pa value=Valid><input type=submit 
value="' . _VALID . '">';
}
echo '
</form>';

CloseTable();
xoops_cp_footer();

