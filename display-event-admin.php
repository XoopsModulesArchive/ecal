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
$currenttheme = getTheme();

echo "<meta http-equiv='Content-Type' content='text/html; charset=" . _CHARSET . "'>\n";

if ($xoopsUser) {
    //include "../../themes/".$currenttheme."/theme.php";

    echo "<html>\n<head>";

    //echo "<LINK REL=\"StyleSheet\" HREF=\"../../themes/".$currenttheme."/style/style.css\" TYPE=\"text/css\">\n";

    echo "<style type=text/css>\n
            .title {font-size:14px}\n
            .tiny {font-size:10px}\n
            .normal {font-size:11px}\n
            </style>\n";
} else {
    //include "../../themes/".$currenttheme."/theme.php";

    echo "<html>\n<head>\n<title>" . $xoopsConfig['sitename'] . " $ModName</title>\n";

    //echo "<LINK REL=\"StyleSheet\" HREF=\"../../themes/".$currenttheme."/style/style.css\" TYPE=\"text/css\">\n";

    echo "<style type=text/css>\n
            .title {font-size:14px}\n
            .tiny {font-size:10px}\n
            .normal {font-size:11px}\n
            </style>\n";
}

move();

echo "<body LEFTMARGIN=3 MARGINWIDTH=3 TOPMARGIN=3 MARGINHEIGHT=3>\n";

//OpenTable();

$query = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('eCal') . " WHERE id=$id");
$results = $xoopsDB->getRowsNum($query);

if (!$results) {
    echo ' <B>' . _NOEVENTDAY . '</B><P><br>';
}

while (false !== ($row = $xoopsDB->fetchArray($query))) {
    echo "<CENTER><table cellpadding=3 cellspacing=1 border=0  class='bg2' width=100%><tr  class='bg2'>
				<td><b>" . _PSTR . '</td><td align=center><b>' . _EVEDATE . "</td>
				</tr><tr class='bg1'>";

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
		        <tr><td colspan=2 class='bg1'><b>" . _SUB . " :</b> $subject</td></tr>
		        <tr><td colspan=2 class='bg1'><B>" . _DESC . " :</B> <br>$description</td></tr>";

    if ($row['url']) {
        echo "<tr><td colspan=2 class='bg1'><B>" . _CAL_URL . " :</B> <A HREF=\"$row[url]\" TARGET=\"_blank\">$row[url]</A></td></tr>";
    }

    echo '</table></CENTER><br>';
}

echo "<center><table>
	<tr><td><a href=# onClick='window.close()'>" . _CLOSEF . '</A></td>
	</tr></table>';

//CloseTable();

echo '</body></html>';
