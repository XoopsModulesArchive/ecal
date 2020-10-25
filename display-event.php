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
include 'cache/config.php';
$myts = MyTextSanitizer::getInstance();

global $xoopsUser, $xoopsConfig, $xoopsTheme;
$currenttheme = xoops_getcss();

echo "<meta http-equiv='Content-Type' content='text/html; charset=" . _CHARSET . "'>\n";
xoops_header();
if ($xoopsUser) {
    echo "<html>\n<head>";
} else {
    echo "<html>\n<head>\n<title>" . $xoopsConfig['sitename'] . " $ModName</title>\n";
}
move();

echo "<body LEFTMARGIN=3 MARGINWIDTH=3 TOPMARGIN=3 MARGINHEIGHT=3>\n";

$query = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('eCal') . " WHERE id=$id AND valid = 'yes'");
$results = $xoopsDB->getRowsNum($query);

if (!$results) {
    echo ' <B>' . _NOEVENTDAY . '</B><P><br>';
}

while (false !== ($row = $xoopsDB->fetchArray($query))) {
    echo "<CENTER><table cellpadding=3 cellspacing=1 border=0  class='head' width=100%><tr  class='itemhead'>
				<td><b>" . _PSTR . '</td><td align=center><b>' . _EVEDATE . "</td>
				</tr><tr class='even'>";

    $nomuser = $row['username'];

    $auteur = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('users') . " where uname='$nomuser'");

    [$uid] = $xoopsDB->fetchRow($auteur);

    if ($uid) {
        echo "<td><a href='javascript:window.opener.location=\"../../userinfo.php?uid=$uid\";javascript:window.location=\"display-event.php?id={$row['id']}\";''>$nomuser</a></td>";
    } else {
        echo "<td>$nomuser</td>";
    }

    $Calanddate = $row['stamp'];

    $Calanddate = strftime(_EVEDATESTRING, strtotime($Calanddate));

    $description = htmlspecialchars($row['description'], ENT_QUOTES | ENT_HTML5);

    $subject = htmlspecialchars($row['subject'], ENT_QUOTES | ENT_HTML5);

    echo "<td align=center>$Calanddate</td></tr>
		        <tr><td colspan=2 class='odd'><b>" . _SUB . " :</b> $subject</td></tr>
		        <tr><td colspan=2 class='outer'><B>" . _DESC . " :</B> <br>$description</td></tr>";

    if ($row['url']) {
        echo "<tr><td colspan=2 class='outer'><B>" . _CAL_URL . " :</B> <A HREF=\"$row[url]\" TARGET=\"_blank\">$row[url]</A></td></tr>";
    }

    echo '</table></CENTER><br>';
}

echo "<center><table>
			   <tr><td><center><a href=# onClick='window.close()'>" . _CLOSEF . '</A></center></td>
				</tr></table>';

echo '</body></html>';
xoops_footer();
