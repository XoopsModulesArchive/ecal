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
$username = $_POST['username'] ?? null;
$id = $_POST['id'] ?? null;
$valid = $_POST['valid'] ?? null;
$year = $_POST['year'] ?? null;
$month = $_POST['month'] ?? null;
$day = $_POST['day'] ?? null;
$subject = $_POST['subject'] ?? null;
$description = $_POST['description'] ?? null;
$url = $_POST['url'] ?? null;
$pa = $_POST['pa'] ?? null;

function Delete($id)
{
    global $xoopsDB;

    $GLOBALS['xoopsDB']->queryF('DELETE FROM ' . $xoopsDB->prefix('eCal') . " WHERE id = '$id'");

    redirect_header('index.php', 1, _OK_DEL);
}

function DeleteS($id)
{
    global $xoopsDB;

    $status = 0;

    $size = count($id);

    for ($i = 0; $i < $size; $i++) {
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('eCal') . ' WHERE id=' . $id[$i] . '';

        if (!$xoopsDB->queryF($sql)) {
            exit();
        }

        $status = 1;
    }

    if ($status) {
        redirect_header('index.php', 1, _OK_DEL);

        exit();
    }
}

function Valid($id, $username, $year, $month, $day, $time, $subject, $description, $url, $valid)
{
    global $xoopsDB, $myts, $xoopsUser, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    $description = $myts->addSlashes($description);

    $subject = $myts->addSlashes($subject);

    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('eCal') . " SET id='$id', username = '$username' , stamp = '$year-$month-$day $time' , subject = '$subject', description = '$description', url = '$url', valid = '$valid'  WHERE id='$id'");

    if ($username != $xoopsConfig['anonymous']) {
        $query = $xoopsDB->query('SELECT email  FROM ' . $xoopsDB->prefix('user') . " WHERE uname = '$username'");

        [$email] = $xoopsDB->fetchRow($query);

        $subject = '' . _CAL_ANNACCEPT . '';

        $message = '' . _CAL_HELLO . " $username ,\n\n " . _CAL_ANNACCEPT . " :\n\n$subject\n $description\n\n\n " . _CAL_CONSULTTO . ' 
		' . XOOPS_URL . "/modules/eCal/\n\n " . _CAL_THANK . "\n\n" . _CAL_TEAMOF . ' ' . $xoopsConfig['sitename'] . '';

        $from = $xoopsConfig['adminmail'];

        mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
    }

    redirect_header('index.php', 1, _OK_UPDATE);
}

function ModifEvent($id, $username, $year, $month, $day, $time, $subject, $description, $url, $valid)
{
    global $xoopsDB, $myts, $xoopsUser, $xoopsConfig;

    $myts = MyTextSanitizer::getInstance();

    if (!$id) {
        redirect_header('index.php', 1, _ERRORMSG1);

        break;
    }

    $description = $myts->addSlashes($description);

    $subject = $myts->addSlashes($subject);

    $xoopsDB->query(
        'UPDATE ' . $xoopsDB->prefix('eCal') . " SET id='$id',
		 username = '$username' ,
		 stamp = '$year-$month-$day $time' , 
		subject = '$subject', 
		description = '$description', 
		url = '$url',
		 valid = '$valid'  
		WHERE id='$id'"
    );

    redirect_header('index.php', 1, _ERRORMSG5);
}

switch ($pa) {
    case 'ModifEvent':
        ModifEvent($id, $username, $year, $month, $day, $time, $subject, $description, $url, $valid);
        break;
    case 'Valid':
        Valid($id, $username, $year, $month, $day, $time, $subject, $description, $url, $valid);
        break;
    case 'Delete':
        Delete($id);
        break;
    case 'DeleteS':
        DeleteS($id);
        break;
    default:
        redirect_header('index.php', 1, _NO_AUTHORIZ);
        break;
}
