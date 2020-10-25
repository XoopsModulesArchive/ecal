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

require XOOPS_ROOT_PATH . '/header.php';
$myts = MyTextSanitizer::getInstance();

global $xoopsUser, $xoopsDB;

$month = $_GET['month'] ?? 0;
$lastday = $_GET['lastday'] ?? 0;
$year = $_GET['year'] ?? 0;

echo '<script>
          function verify() {
                var msg = "' . _CAL_VALIDERORMSG . '\\n__________________________________________________\\n\\n";
                var errors = "FALSE";

                if (document.Add.username.value == "0") {
                        errors = "TRUE";
                        msg += "' . _CAL_VALIDNAME . '\\n";
                }
				
				if (document.Add.subject.value == "") {
                        errors = "TRUE";
                        msg += "' . _CAL_VALIDTITRE . '\\n";
                }
				
				if (document.Add.description.value == "") {
                        errors = "TRUE";
                        msg += "' . _CAL_VALIDDESC . '\\n";
                }
  
                if (errors == "TRUE") {
                        msg += "__________________________________________________\\n\\n' . _CAL_VALIDMSG . '\\n";
                        alert(msg);
                        return false;
                }
          }
          </script>';

if ('0' == $anoevent && !$xoopsUser) {
    redirect_header(
        XOOPS_URL . '/modules/eCal/index.php',
        1,
        _EC_ANON_CANNOT_POST_SORRY
    );

    exit();
}

if (!$month && !$lastday && !$year) {
    $usertimevent = time() + (userTimeOffset() * 3600);

    $day = date('j', $usertimevent);

    $month = date('m', $usertimevent);

    $year = date('Y', $usertimevent);
}

OpenTable();

$lastday = 1;
while (checkdate($month, $lastday, $year)) {
    $lastday++;
}

if ('1' == $moderat) {
    echo '<b>' . _ADDN . '</b><br><br><CENTER>' . _CAL_YESMODERATE . '</CENTER><br><br>';
} else {
    echo '<b>' . _ADDN . '</b><br><br><CENTER>' . _CAL_NOMODERATE . '</CENTER><br><br>';
}

echo '' . _CAL_SELECT . '<P>
<form method=post action=action.php onSubmit="return verify();" ENCTYPE="multipart/form-data" NAME="Add">';

echo '<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=5><TR><TD valign="top" align="center">';
echo getMonthName($month);
echo " $year";
littlecal($month, $year);
echo '</TD></TR>';

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

echo '<tr><td colspan="2"><center>';
echo '<br>';
echo "<a href='addevent.php?month=$lastmonth&year=$lastyear'>" . _BAC . '</a>';
echo '&nbsp;&nbsp;&nbsp;';
echo "<a href='addevent.php?month=$nextmonth&year=$nextyear'>" . _FOR . '</center>';
echo '</td></tr>';
echo '</table><P>';

$query = $xoopsDB->query('SELECT max(id) as id FROM ' . $xoopsDB->prefix('eCal') . '');
if ($query) {
    $result = $xoopsDB->fetchArray($query);

    $result['id']++;
} else {
    $result['id'] = 0;
}

echo "
					<input type=hidden name=id value=$result[id]>
					<table border=0>
					<tr><td>" . _USER . '</td>';

if (!$xoopsUser) {
    $calvisit = $xoopsConfig['anonymous'];

    echo "<td>$calvisit <input type=hidden name=username value=\"$calvisit\"></td></tr>";
} else {
    $iddds = $xoopsUser->getVar('uname', 'E');

    echo "<td>$iddds <input type=hidden name=username value='$iddds'></td></tr>";
}

$currentday = date('j', time());

echo '<tr><td>' . _T . '</td><td>';

echo '<select name="heure"><option value=""></option>';
buildHourSelect(9);
echo '</select>&nbsp;<select name="minute"><option value=""></option>';
buildMinSelect(0);
echo '</select>';
echo '</td></tr><tr><td>' . _SUB . '</td><td><input type=text name=subject MAXLENGTH=50  size=40></td></tr>
			<tr><td>' . _DESC2 . '</td><td><textarea wrap=virtual rows=5 cols=50 name=description></textarea></td></tr>
			<tr><td>' . _CAL_URL . '</td><td><input type=text name=url size=40></td></tr></table>';

if ('1' == $moderat) {
    echo '<input type=hidden name=valid value="no">';
} else {
    echo '<input type=hidden name=valid value="yes">';
}

echo "<input type=hidden name=month value=$month>
					<input type=hidden name=year value=$year>
					<input type=hidden name=pa value=AddEvent>
					<input type=submit value=\"" . _ADDITEM2 . '">
					</form>';

CloseTable();
require XOOPS_ROOT_PATH . '/footer.php';

