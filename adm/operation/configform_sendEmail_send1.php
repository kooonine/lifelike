<?php
include_once('./_common.php');
include_once(G5_LIB_PATH."/cafe24mailer.lib.php");

$testFlag = "1";

if($testFlag == "1")
{
    cafe24mailerpost($_POST['receiverlist']);
}
//cafe24mailer
?>
