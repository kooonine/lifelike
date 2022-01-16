<?php
include_once './../common.php';

$result = array(
    "result" => false,
    "msg" => ""
);

$mb_id = $_REQUEST["id"];
$mb_email = $_REQUEST["email"];

switch ($_REQUEST['action']) {
    case "check_id":
        $sql_member = "SELECT mb_id FROM lt_member WHERE mb_id='{$mb_id}'";
        $db_mb = sql_fetch($sql_member);

        if (empty($db_mb['mb_id'])) {
            $result['result'] = true;
        } else {
            $result['msg'] = "MEMBER_ID_EXISTS";
        }
        break;
    case "check_email":
        $sql_member = "SELECT mb_email FROM lt_member WHERE mb_email='{$mb_email}'";
        $db_mb = sql_fetch($sql_member);
        if (empty($db_mb['mb_email'])) {
            $result['result'] = true;
        } else {
            $result['msg'] = "MEMBER_EMAIL_EXISTS";
        }
        break;
}

echo json_encode($result);
