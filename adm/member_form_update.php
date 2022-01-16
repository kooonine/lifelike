<?php
$sub_menu = "200100";
include_once("./_common.php");
include_once(G5_LIB_PATH . "/register.lib.php");
include_once(G5_LIB_PATH . '/thumbnail.lib.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');
include_once(G5_LIB_PATH . '/ppurioSMS.lib.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

if ($w == 'u')
    check_demo();

auth_check($auth[substr($sub_menu,0,2)], 'w');

$mb_id = trim($_POST['mb_id']);
if ($w == 'p') {
    $result = array("result" => "F", "alert" => "", "ch" => "");

    $mb = get_member($mb_id);
    if (!$mb['mb_id']) {
        $result["result"] = "F";
        $result["alert"] = "존재하지 않는 회원자료입니다.";
    } else if ($mb['mb_level'] < $member['mb_level']) {
        $result["result"] = "F";
        $result["alert"] = "자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.";
    } else {

        $chars_array = range('a', 'z');
        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        // 임시비밀번호 발급
        $change_password = substr($shuffle, 0, 4) . rand(10, 99) . substr($shuffle, 5, 2);

        $send_sms_ok = false;
        $send_email_ok = false;
        $error_msg = "";
        try {

            if ($send_sms && $mb['mb_hp'] != "") {

                $arr_change_data = array();
                $name = get_text($mb['mb_name']);
                $mb_hp = preg_replace("/[^0-9]/", "", $mb['mb_hp']);

                $arr_change_data["고객명"] = $name;
                $arr_change_data["임시비밀번호"] = $change_password;

                $sms_result = sms_autosend('회원', '비밀번호 안내', '', $name, $mb_hp, $arr_change_data);

                if ($sms_result['result_code'] == '200') {
                    $send_sms_ok = true;
                } else {
                    $send_sms_ok = false;
                    $error_msg .= "비밀번호 안내 SMS 발송이 실패하였습니다." . PHP_EOL;
                }
            }

            if ($send_email && $mb['mb_email'] != "") {

                $sw = preg_match("/[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)*@[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)*/", $mb['mb_email']);
                // 올바른 메일 주소만
                if ($sw == true) {

                    $arr_change_data = array();
                    $arr_change_data["고객명"] = $mb['mb_name'];
                    $arr_change_data["이름"] = $mb['mb_name'];
                    $arr_change_data["닉네임"] = $mb['mb_nick'];
                    $arr_change_data["회원아이디"] = $mb['mb_id'];
                    $arr_change_data["아이디"] = $mb['mb_id'];
                    $arr_change_data["이메일"] = $mb['mb_email'];
                    $arr_change_data["MEMBER_NAME"] = $mb['mb_name'];
                    $arr_change_data["임시비밀번호"] = $change_password;

                    $mail_result = mailer_autosend('회원-비밀번호 안내', $mb['mb_email'], $arr_change_data);

                    if ($mail_result) {
                        $send_email_ok = true;
                    } else {
                        $send_email_ok = false;
                        $error_msg .= "비밀번호 안내 EMAIL 발송이 실패하였습니다." . PHP_EOL;
                    }
                }
            }
        } catch (Exception $e) {
            $error_msg .= $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
        }

        if ($send_sms_ok || $send_email_ok) {
            $sql = " update {$g5['member_table']}
                        set  mb_password = '" . get_encrypt_string($change_password) . "'
                        where mb_id = '{$mb_id}' ";
            sql_query($sql);

            $sql = "  insert into lt_member_memo
                        set mb_id = '{$mb_id}',
                            mm_memo = '임시비밀번호가 발송되었습니다. " . $error_msg . "',
                            is_important = '1',
                            mm_mb_id = '{$member['mb_id']}',
                            mm_mb_name = '{$member['mb_name']}',
                            mm_time = '" . G5_TIME_YMDHIS . "' ";
            sql_query($sql);

            $result["result"] = "S";
            //$result["ch"] = $change_password;
            if ($error_msg != "") $result["alert"] = $error_msg;
        } else {
            $result["result"] = "F";
            $result["alert"] = $error_msg;
        }
    }

    echo json_encode_raw($result, JSON_UNESCAPED_UNICODE);
    exit;
}


check_admin_token();

// 휴대전화번호 체크
$mb_hp = hyphen_hp_number($_POST['mb_hp']);

$mb_block_write           = isset($_POST['mb_block_write'])             ? sql_real_escape_string(trim($_POST['mb_block_write']))           : "0";
$mb_block_shop           = isset($_POST['mb_block_shop'])             ? sql_real_escape_string(trim($_POST['mb_block_shop']))           : "0";
$mb_block_login           = isset($_POST['mb_block_login'])             ? sql_real_escape_string(trim($_POST['mb_block_login']))           : "0";

$mb_zip1 = substr($_POST['mb_zip'], 0, 3);
$mb_zip2 = substr($_POST['mb_zip'], 3);

$sql_common = "  mb_name = '{$_POST['mb_name']}',
                 mb_nick = '{$_POST['mb_nick']}',
                 mb_email = '{$_POST['mb_email']}',
                 mb_tel = '{$_POST['mb_tel']}',
                 mb_hp = '{$mb_hp}',
                 mb_zip1 = '$mb_zip1',
                 mb_zip2 = '$mb_zip2',
                 mb_addr1 = '{$_POST['mb_addr1']}',
                 mb_addr2 = '{$_POST['mb_addr2']}',
                 mb_addr3 = '{$_POST['mb_addr3']}',
                 mb_addr_jibeon = '{$_POST['mb_addr_jibeon']}',
                 mb_memo = '{$_POST['mb_memo']}',
                 mb_mailling = '{$_POST['mb_mailling']}',
                 mb_sms = '{$_POST['mb_sms']}',
                 mb_sex = '{$_POST['mb_sex']}',
                 mb_1 = '{$_POST['mb_1']}',
                 mb_2 = '{$_POST['mb_2']}',
                 mb_block_write = '" . $mb_block_write . "',
                 mb_block_shop = '" . $mb_block_shop . "',
                 mb_block_login = '" . $mb_block_login . "',
                 " . (($mb_block_login != "0") ? (" mb_intercept_date = if(ifnull(mb_intercept_date,'') = '','" . G5_TIME_YMD . "',mb_intercept_date) ,") : " mb_intercept_date = null,") . "
                 mb_7 = '{$_POST['mb_7']}'
                             ";

if ($w == '') {
    $mb = get_member($mb_id);
    if ($mb['mb_id'])
        alert('이미 존재하는 회원아이디입니다.\\nＩＤ : ' . $mb['mb_id'] . '\\n이름 : ' . $mb['mb_name'] . '\\n닉네임 : ' . $mb['mb_nick'] . '\\n메일 : ' . $mb['mb_email']);

    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$_POST['mb_nick']}' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$_POST['mb_email']}' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);

    sql_query(" insert into {$g5['member_table']} set mb_id = '{$mb_id}', mb_password = '" . get_encrypt_string($mb_password) . "', mb_datetime = '" . G5_TIME_YMDHIS . "', mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_email_certify = '" . G5_TIME_YMDHIS . "', {$sql_common} ");
} else if ($w == 'u') {
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($mb['mb_level'] < $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    // 닉네임중복체크
    /*$sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$_POST['mb_nick']}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);

    // 이메일중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$_POST['mb_email']}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if ($row['mb_id'])
        alert('이미 존재하는 이메일입니다.\\nＩＤ : '.$row['mb_id'].'\\n이름 : '.$row['mb_name'].'\\n닉네임 : '.$row['mb_nick'].'\\n메일 : '.$row['mb_email']);
    */

    $image_regex = "/(\.(gif|jpe?g|png))$/i";

    $mb_img_dir = G5_DATA_PATH . '/member_image/';
    if (!is_dir($mb_img_dir)) {
        @mkdir($mb_img_dir, G5_DIR_PERMISSION);
        @chmod($mb_img_dir, G5_DIR_PERMISSION);
    }
    $mb_img_dir .= substr($mb_id, 0, 2);

    // 회원 이미지 삭제
    if ($del_mb_img)
        @unlink($mb_img_dir . '/' . $mb_icon_img);

    // 아이콘 업로드
    if (isset($_FILES['mb_img']) && is_uploaded_file($_FILES['mb_img']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_img']['name'])) {
            alert($_FILES['mb_img']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match($image_regex, $_FILES['mb_img']['name'])) {
            @mkdir($mb_img_dir, G5_DIR_PERMISSION);
            @chmod($mb_img_dir, G5_DIR_PERMISSION);

            $dest_path = $mb_img_dir . '/' . $mb_icon_img;

            move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size[0] > $config['cf_member_img_width'] || $size[1] > $config['cf_member_img_height']) {
                    $thumb = null;
                    if ($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_icon_img, $mb_img_dir, $mb_img_dir, $config['cf_member_img_width'], $config['cf_member_img_height'], true, true);
                        if ($thumb) {
                            @unlink($dest_path);
                            rename($mb_img_dir . '/' . $thumb, $dest_path);
                        }
                    }
                    if (!$thumb) {
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
            }
        }
    }

    $sql = " update {$g5['member_table']}
                set {$sql_common}
                     {$sql_password}
                     {$sql_certify}
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
} else
    alert('제대로 된 값이 넘어오지 않았습니다.');

$result = SM_SEND_CUST_DATA($mb);
//echo print_r2($result);

goto_url('./member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $mb_id, false);
