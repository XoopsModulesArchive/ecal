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

$username = $_POST['username'] ?? null;
$id = $_POST['id'] ?? null;
$valid = $_POST['valid'] ?? null;
$year = $_POST['year'] ?? null;
$month = $_POST['month'] ?? null;
$Day = $_POST['Day'] ?? null;
$subject = $_POST['subject'] ?? null;
$description = $_POST['description'] ?? null;
$url = $_POST['url'] ?? null;
$minute = $_POST['minute'] ?? null;
$heure = $_POST['heure'] ?? null;
$pa = $_POST['pa'] ?? null;

function DelEvent($id)
{
    global $xoopsDB, $xoopsUser;

    if (!$id) {
        redirect_header('index.php', 1, _ERRORMSG1);

        break;
    }

    $calusern = $xoopsUser->getVar('uname', 'E');

    $query = $xoopsDB->query('SELECT username FROM ' . $xoopsDB->prefix('eCal') . " WHERE id = $id");

    $row = $xoopsDB->fetchArray($query);

    if ($calusern == $row['username']) {
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('eCal') . " WHERE id = '$id'");

        redirect_header('index.php', 1, _ERRORMSG3);
    } else {
        redirect_header('index.php', 1, _ERRORMSG2);
    }
}

function ModifEvent($id, $username, $year, $month, $day, $time, $subject, $description, $url, $valid)
{
    global $xoopsDB, $myts, $xoopsUser;

    $myts = MyTextSanitizer::getInstance();

    if (!$id) {
        redirect_header('index.php', 1, _ERRORMSG1);

        break;
    }

    $calusern = $xoopsUser->uname();

    $query = $xoopsDB->query('SELECT username FROM ' . $xoopsDB->prefix('eCal') . " WHERE id = $id");

    $row = $xoopsDB->fetchArray($query);

    if ($calusern == $row['username']) {
        $description = $myts->addSlashes($description);

        $subject = $myts->addSlashes($subject);

        $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('eCal') . " SET id='$id', username = '$username' , stamp = '$year-$month-$day $time' , subject = '$subject', description = '$description', url = '$url', valid = '$valid'   WHERE id='$id'");

        redirect_header('index.php', 1, _ERRORMSG5);
    } else {
        redirect_header('index.php', 1, _ERRORMSG4);
    }
}

switch ($pa) {
    case 'ModifEvent':
        ModifEvent($id, $username, $year, $month, $day, $time, $subject, $description, $url, $valid);
        break;
    case 'AddEvent':
        global $xoopsDB, $xoopsUser;
        $myts = MyTextSanitizer::getInstance();

        if ($xoopsUser && $xoopsUser->isAdmin()) {
            $valid = 'yes';
        }

        foreach ($Day as $day) {
            $description = $myts->addSlashes($description);

            $subject = $myts->addSlashes($subject);

            $times = "$heure:$minute:00";

            $temp = $xoopsDB->query(
                'INSERT INTO ' . $xoopsDB->prefix('eCal') . " (username, stamp,
		subject, description,
		 url, valid)
		VALUES ('$username', 
 		'$year-$month-$day $times',
		'$subject',
		'$description',
 		'$url',
 		'$valid')"
            );
        }
        redirect_header('index.php', 1, _MSG1);
        break;
    case 'DelEvent':
        DelEvent($id);
        break;
    default:
        redirect_header('index.php', 1, _NO_AUTHORIZ);
        break;
}
