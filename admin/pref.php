<?php

#####################################################
#  Based on PHP-NUKE: eCalendar System
#  by Don Grabowski Don@ecomjunk.com -  http://ecomjunk.com
#
#  Calendrier version 1.1 Beta for Xoops 1.0 RC2
#  Copyright © 2002, Pascal Le Boustouller
#  webmaster@i-annonces.net - http://www.i-annonces.net
#  Licence: GPL
#
#  Merci de laisser ce copyright en place...
#####################################################
include 'admin_header.php';
include '../cache/config.php';
//s at the end of variable means its input while in config file
// the variable name does not have the s
$pa = $_GET['pa'] ?? '';
$annoevents = $_POST['annoevents'] ?? 0;
$daycolors = $_POST['daycolors'] ?? 0;
$moderats = $_POST['moderats'] ?? 0;
$nextncounts = $_POST['nextncounts'] ?? 0;
$blcklmts = $_POST['blcklmts'] ?? 0;

function Conf()
{
    global $xoopsUser, $xoopsConfig, $xoopsModule, $annoevent, $daycolor, $moderat, $nextncount, $blcklmt;

    xoops_cp_header();

    OpenTable();

    echo '<B>' . _CONFEVENT . '</B>';

    echo '<FORM ACTION="pref.php?pa=ConfOk" METHOD=POST>
		<TABLE BORDER=0>
		<TR>
	      <TD>' . _CAL_MODERATE . '</TD>
	      <TD><SELECT NAME="moderats">';

    if (1 == $moderat) {
        echo '<OPTION VALUE="1" selected>  ' . _OUI . '';

        echo '<OPTION VALUE="0">  ' . _NON . '';
    } else {
        echo '<OPTION VALUE="1"> ' . _OUI . '';

        echo '<OPTION VALUE="0" selected>  ' . _NON . '';
    }

    echo ' </SELECT></TD>
	    </TR>
	    <TR>
	      <TD>' . _ANNOEVENT . '</TD>
	      <TD><SELECT NAME="annoevents">';

    if (1 == $anoevent) {
        echo '<OPTION VALUE="1" selected>  ' . _OUI . '';

        echo '<OPTION VALUE="0">  ' . _NON . '';
    } else {
        echo '<OPTION VALUE="1"> ' . _OUI . '';

        echo '<OPTION VALUE="0" selected>  ' . _NON . '';
    }

    echo ' </SELECT></TD>
	    </TR>
	    <TR>
	      <TD>' . _DAYCOLOREV . "</TD>
	      <TD><INPUT TYPE=\"text\" NAME=\"daycolors\" SIZE=10 value=\"$daycolor\"></TD>
	    </TR>
	    <TR>
	      <TD>" . _NEXTNCOUNT . "</TD>
	      <TD><INPUT TYPE=\"text\" NAME=\"nextncounts\" SIZE=10 value=\"$nextncount\"></TD>
	    </TR>
	    <TR>
	      <TD>" . _BLCKLMT . "</TD>
	      <TD><INPUT TYPE=\"text\" NAME=\"blcklmts\" SIZE=10 value=\"$blcklmt\"></TD>
	    </TR>
	</TABLE><P>
	<INPUT TYPE=\"submit\" VALUE=\"" . _VALID . '">
	</FORM>';

    CloseTable();

    xoops_cp_footer();
}

function ConfOK($annoevents, $daycolors, $moderats, $nextncounts, $blcklmts)
{
    global $xoopsUser;

    $file = fopen('../cache/config.php', 'wb');

    $content = "<?php\n";

    $content .= "#####################################################\n";

    $content .= "#  Based on PHP-NUKE: eCalendar System\n";

    $content .= "#  by Don Grabowski Don@ecomjunk.com -  http://ecomjunk.com\n";

    $content .= "#\n";

    $content .= "#  Calendrier version 1.1 Beta for Xoops 1.0 RC2\n";

    $content .= "#  Copyright © 2002, Pascal Le Boustouller\n";

    $content .= "#  webmaster@i-annonces.net - http://www.i-annonces.net\n";

    $content .= "#  Licence: GPL\n";

    $content .= "#\n";

    $content .= "#  Merci de laisser ce copyright en place...\n";

    $content .= "#####################################################\n\n";

    $content .= "// Moderate events : yes=1 no=0\n";

    $content .= "// Modération des évenements : oui=1 non=0\n";

    $content .= "\$moderat = \"$moderats\";\n\n";

    $content .= "// 1 les anonymes peuvent ajouter un evenement - 0 les anonymes ne peuvent pas ajouter un evenement\n";

    $content .= "// 1 Anonymous can add an event - 0 Anonymous can't add an event;\n";

    $content .= "\$anoevent = \"$annoevents\";\n\n";

    $content .= "// Couleur du jour\n";

    $content .= "// Day color\n";

    $content .= "\$daycolor = \"$daycolors\";\n\n";

    $content .= "// numbre evenements pour Next N block\n";

    $content .= "// number of events in Next N events block\n";

    $content .= "\$nextncount = \"$nextncounts\";\n\n";

    $content .= "// numbre evenements pour limit block length \n";

    $content .= "// number of events to limit all events block\n";

    $content .= "\$blcklmt = \"$blcklmts\";\n\n";

    $content .= '?>';

    fwrite($file, $content);

    fclose($file);

    redirect_header('pref.php', 1, _OKCONFIG);

    exit();
}

switch ($pa) {
    case 'ConfOk':
        ConfOK($annoevents, $daycolors, $moderats, $nextncounts, $blcklmts);
        break;
    default:
        Conf();
        break;
}
