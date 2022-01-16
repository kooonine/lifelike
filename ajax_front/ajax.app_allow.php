<?php
include_once('./../common.php');
$token = $_POST['token'];
$check = $_POST['check'];

if (!empty($token)) {
    if ($check==0 || $check==1) {
        $sql = "UPDATE lt_app_users SET push_check = $check WHERE token = '$token'";
        sql_query($sql);   
    }
    if ($member['mb_id'] && count($member['mb_id']) >0) {
        $mbId = $member['mb_id'];
        $sql = "UPDATE lt_app_users SET mb_id = '$mbId' WHERE token = '$token'";
        insert_point($member['mb_id'], $config['cf_install_point'], 'APP 설치 적립', '@appinstall', $member['mb_id'],'App설치',30);
        sql_query($sql);
    }
    die(json_encode('success'));
} else {
    if ($check==3 && $member['mb_id'] && count($member['mb_id']) >0) { 
        insert_point($member['mb_id'], $config['cf_install_point'], 'APP 설치 적립', '@appinstall', $member['mb_id'],'App설치',30);
    }
    die(json_encode('success'));
}
