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
require XOOPS_ROOT_PATH.'/header.php';
$myts = MyTextSanitizer::getInstance();

$id = $_GET['id'] ?? 0;
$ok = $_GET['ok'] ?? 0;
global $xoopsUser, $xoopsDB;

OpenTable();
if (!$id) {
	echo '<p>'._ERRORMSG1.'';
	break;
}

 echo '<B>'._MSG3.'</B>';
$query = $xoopsDB->query('SELECT *, RIGHT(stamp, 8) AS thetime
	, SUBSTRING(stamp FROM 9 FOR 2) AS 
	theday FROM '.$xoopsDB->prefix('eCal').
	" WHERE id = '$id' AND valid='yes'");
$row = $xoopsDB->fetchArray($query);

$subject = htmlspecialchars($row['subject'], ENT_QUOTES | ENT_HTML5);
$description = htmlspecialchars($row['description'], ENT_QUOTES | ENT_HTML5);

echo "<form method=post action=action.php>
<input type=hidden name=id value='{$row['id']}'>
<table><tr><td><b>"._USER.'</td>';


echo "<td>$row['username'] <input type=hidden name=username 
	value='{$row['username']}'></td></tr>";

echo '<tr><td><b>'._D.'</td><td><select name=day size=1>';
 						buildDaySelect($day);
                        echo '</select><select size=1 name=month>';
                      	buildMonthSelect($month);
                        echo '</select><select size=1 name=year>';
 						buildYearSelect($year);
                        echo '</select></tr>

<tr><td><b>'._TSHOW."</td><td><input type=text name=time value='{$row['thetime']}'></td></tr>
 <tr><td><b>"._SUBSHOW."</td><td><input type=text name=subject value=\"$subject \" size=40></td></tr>
<tr><td><b>"._DESC."</td><td><textarea wrap=virtual rows=5 cols=50 name=description>$description</textarea></td></tr>
<tr><td><b>"._CAL_URL."</td><td><input type=text name=url value=\"$row['url']\" size=40></td></tr>
</table>";
	if ($moderat == '1') {
		echo '<input type=hidden name=valid value="no">';
	} else {
		echo '<input type=hidden name=valid value="yes">';
	}
echo '<input type=hidden name=pa value=ModifEvent>
<input type=submit value="'._VALID.'">
</form>';
					
CloseTable();
	require XOOPS_ROOT_PATH.'/footer.php';

