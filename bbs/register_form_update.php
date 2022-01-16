<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');
include_once(G5_LIB_PATH . '/register.lib.php');
include_once(G5_LIB_PATH . '/thumbnail.lib.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

// 리퍼러 체크
referer_check();

if (!($w == '' || $w == 'u')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == 'u' && $is_admin == 'super') {
    if (file_exists(G5_PATH . '/DEMO'))
        alert('데모 화면에서는 하실(보실) 수 없는 작업입니다.');
}

if ($w == 'u') {
    $mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';

    if ($_POST['mb_password_org']) {
        $tmp_password = get_encrypt_string($_POST['mb_password_org']);

        if ($member['mb_password'] != $tmp_password)
            alert('비밀번호가 틀립니다.');
    }
} else if ($w == '') {
    if (empty($_POST['mb_dupinfo'])) alert('본인인증 후 진행해 주시기 바랍니다.');

    $mb_id = trim($_POST['mb_id']);
} else {
    alert('잘못된 접근입니다', G5_URL);
}

if (!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');

$mb_password_org = trim($_POST['mb_password_org']);
$mb_password    = trim($_POST['mb_password']);
$mb_password_re = trim($_POST['mb_password_re']);
$mb_name        = trim($_POST['mb_name']);
$mb_nick        = trim($_POST['mb_nick']);
$mb_email       = trim($_POST['mb_email']);
$mb_sex         = isset($_POST['mb_sex'])           ? trim($_POST['mb_sex'])         : "";
$mb_birth       = isset($_POST['mb_birth'])         ? trim($_POST['mb_birth'])       : "";
$mb_homepage    = isset($_POST['mb_homepage'])      ? trim($_POST['mb_homepage'])    : "";
$mb_tel         = isset($_POST['mb_tel'])           ? trim($_POST['mb_tel'])         : "";
$mb_hp          = isset($_POST['mb_hp'])            ? trim($_POST['mb_hp'])          : "";
$mb_zip1        = isset($_POST['mb_zip'])           ? substr(trim($_POST['mb_zip']), 0, 3) : "";
$mb_zip2        = isset($_POST['mb_zip'])           ? substr(trim($_POST['mb_zip']), 3)    : "";
$mb_addr1       = isset($_POST['mb_addr1'])         ? trim($_POST['mb_addr1'])       : "";
$mb_addr2       = isset($_POST['mb_addr2'])         ? trim($_POST['mb_addr2'])       : "";
$mb_addr3       = isset($_POST['mb_addr3'])         ? trim($_POST['mb_addr3'])       : "";
$mb_addr_jibeon = isset($_POST['mb_addr_jibeon'])   ? trim($_POST['mb_addr_jibeon']) : "";
$mb_signature   = isset($_POST['mb_signature'])     ? trim($_POST['mb_signature'])   : "";
$mb_profile     = isset($_POST['mb_profile'])       ? trim($_POST['mb_profile'])     : "";
$mb_recommend   = isset($_POST['mb_recommend'])     ? trim($_POST['mb_recommend'])   : "";
$mb_mailling    = isset($_POST['mb_mailling'])      ? trim($_POST['mb_mailling'])    : "";
$mb_sms         = isset($_POST['mb_sms'])           ? trim($_POST['mb_sms'])         : "";
$mb_1           = isset($_POST['mb_1'])             ? trim($_POST['mb_1'])           : "";
$mb_2           = isset($_POST['mb_2'])             ? trim($_POST['mb_2'])           : "";
$mb_3           = isset($_POST['mb_3'])             ? trim($_POST['mb_3'])           : "";
$mb_4           = isset($_POST['mb_4'])             ? trim($_POST['mb_4'])           : "";
$mb_5           = isset($_POST['mb_5'])             ? trim($_POST['mb_5'])           : "";
$mb_6           = isset($_POST['mb_6'])             ? trim($_POST['mb_6'])           : "";
$mb_7           = isset($_POST['mb_7'])             ? trim($_POST['mb_7'])           : "";
$mb_8           = isset($_POST['mb_8'])             ? trim($_POST['mb_8'])           : "";
$mb_9           = isset($_POST['mb_9'])             ? trim($_POST['mb_9'])           : "";
$mb_10          = isset($_POST['mb_10'])            ? trim($_POST['mb_10'])          : 0;
$register_type  = isset($_POST['register_type'])    ? trim($_POST['register_type'])  : "";
$mb_certify  = isset($_POST['mb_certify'])    ? trim($_POST['mb_certify'])  : "";
$mb_dupinfo  = isset($_POST['mb_dupinfo'])    ? trim($_POST['mb_dupinfo'])  : "";

$mb_name        = clean_xss_tags($mb_name);
$mb_email       = get_email_address($mb_email);
$mb_homepage    = clean_xss_tags($mb_homepage);
$mb_tel         = clean_xss_tags($mb_tel);
$mb_zip1        = preg_replace('/[^0-9]/', '', $mb_zip1);
$mb_zip2        = preg_replace('/[^0-9]/', '', $mb_zip2);
$mb_addr1       = clean_xss_tags($mb_addr1);
$mb_addr2       = clean_xss_tags($mb_addr2);
$mb_addr3       = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';

if ($w == '' || $w == 'u') {

    if ($msg = empty_mb_id($mb_id))         alert($msg, "", true, true); // alert($msg, $url, $error, $post);
    if ($msg = valid_mb_id($mb_id))         alert($msg, "", true, true);
    if ($msg = count_mb_id($mb_id))         alert($msg, "", true, true);


    // 이름, 닉네임에 utf-8 이외의 문자가 포함됐다면 오류
    // 서버환경에 따라 정상적으로 체크되지 않을 수 있음.
    $tmp_mb_name = iconv('UTF-8', 'UTF-8//IGNORE', $mb_name);
    if ($tmp_mb_name != $mb_name) {
        alert('이름을 올바르게 입력해 주십시오.');
    }
    $tmp_mb_nick = iconv('UTF-8', 'UTF-8//IGNORE', $mb_nick);
    if ($tmp_mb_nick != $mb_nick) {
        alert('닉네임을 올바르게 입력해 주십시오.');
    }

    if ($w == '') {
        if ($msg = valid_mb_password($mb_password))       alert($msg, "", true, true);
    }
    if ($w == '' && $mb_password != $mb_password_re)
        alert('비밀번호가 일치하지 않습니다.');
    if ($w == 'u' && $mb_password_org && $mb_password != $mb_password_re)
        alert('비밀번호가 일치하지 않습니다.');

    if ($msg = empty_mb_name($mb_name))       alert($msg, "", true, true);
    //if ($msg = empty_mb_nick($mb_nick))     alert($msg, "", true, true);
    if ($msg = empty_mb_email($mb_email))   alert($msg, "", true, true);
    if ($msg = reserve_mb_id($mb_id))       alert($msg, "", true, true);
    //if ($msg = reserve_mb_nick($mb_nick))   alert($msg, "", true, true);
    // 이름에 한글명 체크를 하지 않는다.
    //if ($msg = valid_mb_name($mb_name))     alert($msg, "", true, true);
    //if ($msg = valid_mb_nick($mb_nick))     alert($msg, "", true, true);
    if ($msg = valid_mb_email($mb_email))   alert($msg, "", true, true);
    if ($msg = exist_mb_email($mb_email, $mb_id))   alert($msg, "", true, true);
    if ($msg = prohibit_mb_email($mb_email)) alert($msg, "", true, true);

    // 휴대전화 필수입력일 경우 휴대전화번호 유효성 체크
    if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {
        if ($msg = valid_mb_hp($mb_hp))     alert($msg, "", true, true);
    }

    if ($w == '') {
        if ($msg = exist_mb_id($mb_id))     alert($msg);

        // 본인확인 체크
        // if ($config['cf_cert_use'] && $config['cf_cert_req']) {
        //     if (trim($_POST['cert_no']) != $_SESSION['ss_cert_no'] || !$_SESSION['ss_cert_no'])
        //         alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
        // }
        if (empty($mb_certify))
            alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
    } else {
        // 자바스크립트로 정보변경이 가능한 버그 수정
        // 닉네임수정일이 지나지 않았다면
        if ($member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400)))
            $mb_nick = $member['mb_nick'];
        // 회원정보의 메일을 이전 메일로 옮기고 아래에서 비교함
        $old_email = $member['mb_email'];
    }

    //if ($msg = exist_mb_nick($mb_nick, $mb_id))     alert($msg, "", true, true);
    // if ($msg = exist_mb_email($mb_email, $mb_id))   alert($msg, "", true, true);
}

// 사용자 코드 실행
// @include_once($member_skin_path . '/register_form_update.head.skin.php');

//===============================================================
//  본인확인
//---------------------------------------------------------------

/*
if ($config['cf_cert_use'] && $_SESSION['ss_cert_type'] && $_SESSION['ss_cert_dupinfo']) {
    // 중복체크
    $sql = " select mb_id from {$g5['member_table']} where mb_id <> '{$member['mb_id']}' and mb_dupinfo = '{$_SESSION['ss_cert_dupinfo']}' ";
    $row = sql_fetch($sql);
    if ($row['mb_id']) {
        alert("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n회원아이디 : " . $row['mb_id']);
    }
}
*/

$mb_hp = hyphen_hp_number($mb_hp);

if ($w == '') {
    // 중복체크
    $sql = " select mb_id,mb_leave_date from {$g5['member_table']} where mb_id <> '{$mb_id}' and mb_dupinfo = '{$mb_dupinfo}' ";
    $row = sql_fetch($sql);
    if ($row['mb_id']) {
        $ts_term = strtotime("+30 day", strtotime($row['mb_leave_date'])) - time();
        if ($ts_term > 0) {
            alert("회원님께서는 " . $row['mb_leave_date'] . "일에 탈퇴하셨습니다.\\n" . date("j", $ts_term) . "일 이후 가입가능합니다.");
        }
        alert("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n아이디 찾기를 진행해주세요.", "/auth/login.account.php");
    }
}

$sql_certify = '';
$md5_cert_no = $_SESSION['ss_cert_no'];
$cert_type = $_SESSION['ss_cert_type'];
if ($config['cf_cert_use'] && $cert_type && $md5_cert_no) {
    // 해시값이 같은 경우에만 본인확인 값을 저장한다.
    if ($_SESSION['ss_cert_hash'] == md5($mb_name . $cert_type . $_SESSION['ss_cert_birth'] . $md5_cert_no)) {
        $sql_certify .= " , mb_hp = '{$mb_hp}' ";
        $sql_certify .= " , mb_certify  = '{$cert_type}' ";
        $sql_certify .= " , mb_adult = '{$_SESSION['ss_cert_adult']}' ";
        $sql_certify .= " , mb_birth = '{$_SESSION['ss_cert_birth']}' ";
        $sql_certify .= " , mb_sex = '{$_SESSION['ss_cert_sex']}' ";
        $sql_certify .= " , mb_dupinfo = '{$_SESSION['ss_cert_dupinfo']}' ";
        $sql_certify .= " , mb_name = '{$mb_name}' ";
    } else {
        $sql_certify .= " , mb_hp = '{$mb_hp}' ";
        $sql_certify .= " , mb_certify  = '' ";
        $sql_certify .= " , mb_adult = 0 ";
        $sql_certify .= " , mb_birth = '{$mb_birth}' ";
        $sql_certify .= " , mb_sex = '{$mb_sex}' ";
    }
} else {
    if (get_session("ss_reg_mb_name") != $mb_name || get_session("ss_reg_mb_hp") != $mb_hp) {
        $sql_certify .= " , mb_hp = '{$mb_hp}' ";
        $sql_certify .= " , mb_certify = '' ";
        $sql_certify .= " , mb_adult = 0 ";
        $sql_certify .= " , mb_birth = '{$mb_birth}' ";
        $sql_certify .= " , mb_sex = '{$mb_sex}' ";
    }
}
//===============================================================

if ($w == '') {
    $sql = " insert into {$g5['member_table']}
                set mb_id = '{$mb_id}',
                     mb_password = '" . get_encrypt_string($mb_password) . "',
                     mb_name = '{$mb_name}',
                     mb_nick = '{$mb_nick}',
                     mb_nick_date = '" . G5_TIME_YMD . "',
                     mb_email = '{$mb_email}',
                     mb_homepage = '{$mb_homepage}',
                     mb_tel = '{$mb_tel}',
                     mb_zip1 = '{$mb_zip1}',
                     mb_zip2 = '{$mb_zip2}',
                     mb_addr1 = '{$mb_addr1}',
                     mb_addr2 = '{$mb_addr2}',
                     mb_addr3 = '{$mb_addr3}',
                     mb_addr_jibeon = '{$mb_addr_jibeon}',
                     mb_signature = '{$mb_signature}',
                     mb_profile = '{$mb_profile}',
                     mb_today_login = '" . G5_TIME_YMDHIS . "',
                     mb_datetime = '" . G5_TIME_YMDHIS . "',
                     mb_ip = '{$_SERVER['REMOTE_ADDR']}',
                     mb_level = '{$config['cf_register_level']}',
                     mb_recommend = '{$mb_recommend}',
                     mb_login_ip = '{$_SERVER['REMOTE_ADDR']}',
                     mb_mailling = '{$mb_mailling}',
                     mb_sms = '{$mb_sms}',
                     mb_open = '{$mb_open}',
                     mb_open_date = '" . G5_TIME_YMD . "',
                     mb_1 = '{$mb_1}',
                     mb_2 = '{$mb_2}',
                     mb_3 = '{$mb_3}',
                     mb_4 = '{$mb_4}',
                     mb_5 = '{$mb_5}',
                     mb_6 = '{$mb_6}',
                     mb_7 = '{$mb_7}',
                     mb_8 = '{$mb_8}',
                     mb_9 = '{$mb_9}',
                     mb_10 = '{$mb_10}'
                     {$sql_certify} ";

    if (!empty($mb_dupinfo)) {
        $sql .= ", mb_dupinfo='{$mb_dupinfo}'";
    }

    // 이메일 인증을 사용하지 않는다면 이메일 인증시간을 바로 넣는다
    if (!$config['cf_use_email_certify'])
        $sql .= " , mb_email_certify = '" . G5_TIME_YMDHIS . "' ";
    sql_query($sql);

    //배송지 관리 등록
    $address_sql = " insert into {$g5['g5_shop_order_address_table']}
                    set ad_subject = '기본배송지'
                        , mb_id = '{$mb_id}'
                        , ad_default = '1'
                        , ad_name = '{$mb_name}'
                        , ad_tel = '{$mb_tel}'
                        , ad_hp = '{$mb_hp}'
                        , ad_zip1 = '{$mb_zip1}'
                        , ad_zip2 = '{$mb_zip2}'
                        , ad_addr1 = '{$mb_addr1}'
                        , ad_addr2 = '{$mb_addr2}'
                        , ad_addr3 = '{$mb_addr3}'
                        , ad_jibeon = '{$mb_addr_jibeon}' ";
    sql_query($address_sql);



    // 회원가입 포인트 부여
    insert_point($mb_id, $config['cf_register_point'], '회원가입 축하', '@member', $mb_id, '회원가입');

    // 추천인에게 포인트 부여
    //if ($config['cf_use_recommend'] && $mb_recommend)
    //    insert_point($mb_recommend, $config['cf_recommend_point'], $mb_id.'의 추천인', '@member', $mb_recommend, $mb_id.' 추천');
    /*
    // 회원님께 메일 발송
    if ($config['cf_email_mb_member']) {
        $subject = '['.$config['cf_title'].'] 회원가입을 축하드립니다.';

        // 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
        if ($config['cf_use_email_certify']) {
            $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));
            sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");
            $certify_href = G5_BBS_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;
        }

        ob_start();
        include_once ('./register_form_update_mail1.php');
        $content = ob_get_contents();
        ob_end_clean();

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);

        // 메일인증을 사용하는 경우 가입메일에 인증 url이 있으므로 인증메일을 다시 발송되지 않도록 함
        if($config['cf_use_email_certify'])
            $old_email = $mb_email;
    }

    // 최고관리자님께 메일 발송
    if ($config['cf_email_mb_super_admin']) {
        $subject = '['.$config['cf_title'].'] '.$mb_id .' 님께서 회원으로 가입하셨습니다.';

        ob_start();
        include_once ('./register_form_update_mail2.php');
        $content = ob_get_contents();
        ob_end_clean();

        mailer($mb_id, $mb_email, $config['cf_admin_email'], $subject, $content, 1);
    }
    */

    if ($register_type == 'company') {
        // 사업자 정보
        $company_type           = isset($_POST['company_type'])          ? trim($_POST['company_type'])       : "";
        $company_no             = isset($_POST['company_no'])            ? trim($_POST['company_no'])       : "";
        $company_name           = isset($_POST['company_name'])          ? trim($_POST['company_name'])       : "";
        $company_leader         = isset($_POST['company_leader'])        ? trim($_POST['company_leader'])       : "";
        $company_category       = isset($_POST['company_category'])      ? trim($_POST['company_category'])       : "";
        $company_hp             = isset($_POST['company_hp'])            ? trim($_POST['company_hp'])       : "";
        $company_zip1        = isset($_POST['company_zip'])           ? substr(trim($_POST['company_zip']), 0, 3) : "";
        $company_zip2        = isset($_POST['company_zip'])           ? substr(trim($_POST['company_zip']), 3)    : "";
        $company_addr1          = isset($_POST['company_addr1'])         ? trim($_POST['company_addr1'])       : "";
        $company_addr2          = isset($_POST['company_addr2'])         ? trim($_POST['company_addr2'])       : "";
        $company_addr3          = isset($_POST['company_addr3'])         ? trim($_POST['company_addr3'])       : "";
        $company_addr_jibeon    = isset($_POST['company_addr_jibeon'])   ? trim($_POST['company_addr_jibeon']) : "";

        $company_name           = clean_xss_tags($company_name);
        $company_leader         = clean_xss_tags($company_leader);
        $company_category       = clean_xss_tags($company_category);
        $company_hp             = clean_xss_tags($company_hp);
        $company_zip1        = preg_replace('/[^0-9]/', '', $company_zip1);
        $company_zip2        = preg_replace('/[^0-9]/', '', $company_zip2);
        $company_addr1          = clean_xss_tags($company_addr1);
        $company_addr2          = clean_xss_tags($company_addr2);
        $company_addr3          = clean_xss_tags($company_addr3);
        $company_addr_jibeon    = preg_match("/^(N|R)$/", $company_addr_jibeon) ? $company_addr_jibeon : '';

        $image_regex = "/(\.(gif|jpe?g|png|pdf))$/i";
        //사업자등록증 업로드
        if (isset($_FILES['company_file']) && is_uploaded_file($_FILES['company_file']['tmp_name'])) {


            if (preg_match($image_regex, $_FILES['company_file']['name'])) {

                $member_dir = G5_DATA_PATH . '/member/' . substr($mb_id, 2);
                @mkdir($member_dir, G5_DIR_PERMISSION);
                @chmod($member_dir, G5_DIR_PERMISSION);

                $dest_path = $member_dir . '/' . $_FILES['company_file']['name'];

                move_uploaded_file($_FILES['company_file']['tmp_name'], $dest_path);
                chmod($dest_path, G5_FILE_PERMISSION);

                $company_file = $_FILES['company_file']['name'];
            }
        }

        $sql = " insert into lt_member_company
                set mb_id = '{$mb_id}',
                 	company_name = '{$company_name}',
                 	company_hp = '{$company_hp}',
                 	company_zip1 = '{$company_zip1}',
                 	company_zip2 = '{$company_zip2}',
                 	company_addr1 = '{$company_addr1}',
                 	company_addr2 = '{$company_addr2}',
                 	company_addr3 = '{$company_addr3}',
                 	company_addr_jibeon = '{$company_addr_jibeon}',
	         	    company_type = '{$company_type}',
		            company_leader = '{$company_leader}',
		            company_category = '{$company_category}',
		            company_file = '{$company_file}',
		            company_no = '{$company_no}'
                     ";
        sql_query($sql);
    }

    set_session('ss_mb_reg', $mb_id);
} else if ($w == 'u') {
    if (!trim($_SESSION['ss_mb_id']))
        alert('로그인 되어 있지 않습니다.');

    if (trim($_POST['mb_id']) != $mb_id)
        alert("로그인된 정보와 수정하려는 정보가 틀리므로 수정할 수 없습니다.\\n만약 올바르지 않은 방법을 사용하신다면 바로 중지하여 주십시오.");

    $sql_password = "";
    if ($mb_password)
        $sql_password = " , mb_password = '" . get_encrypt_string($mb_password) . "' ";

    $sql_nick_date = "";
    if ($mb_nick_default != $mb_nick)
        $sql_nick_date =  " , mb_nick_date = '" . G5_TIME_YMD . "' ";

    $sql_open_date = "";
    if ($mb_open_default != $mb_open)
        $sql_open_date =  " , mb_open_date = '" . G5_TIME_YMD . "' ";

    // 이전 메일주소와 수정한 메일주소가 틀리다면 인증을 다시 해야하므로 값을 삭제
    $sql_email_certify = '';
    if ($old_email != $mb_email && $config['cf_use_email_certify'])
        $sql_email_certify = " , mb_email_certify = '' ";
    /*
        mb_mailling = '{$mb_mailling}',
        mb_sms = '{$mb_sms}',
        mb_open = '{$mb_open}',
        mb_signature = '{$mb_signature}',
        mb_profile = '{$mb_profile}',
        mb_homepage = '{$mb_homepage}',
        mb_1 = '{$mb_1}',
        mb_2 = '{$mb_2}',
        mb_3 = '{$mb_3}',
        mb_4 = '{$mb_4}',
        mb_5 = '{$mb_5}',
        mb_6 = '{$mb_6}',
        mb_7 = '{$mb_7}',
        mb_8 = '{$mb_8}',
        mb_9 = '{$mb_9}',
        mb_10 = '{$mb_10}'
*/
    $sql = " update {$g5['member_table']}
                set mb_nick = '{$mb_nick}',
                    mb_name = '{$mb_name}',
                    mb_email = '{$mb_email}',
                    mb_tel = '{$mb_tel}',
                    mb_zip1 = '{$mb_zip1}',
                    mb_zip2 = '{$mb_zip2}',
                    mb_addr1 = '{$mb_addr1}',
                    mb_addr2 = '{$mb_addr2}',
                    mb_addr3 = '{$mb_addr3}',
                    mb_addr_jibeon = '{$mb_addr_jibeon}',
                    mb_mailling = '{$mb_mailling}',
                    mb_sms = '{$mb_sms}',
                    mb_1 = '{$mb_1}',
                    mb_2 = '{$mb_2}',
                    mb_3 = '{$mb_3}',
                    mb_4 = '{$mb_4}',
                    mb_5 = '{$mb_5}',
                    mb_6 = '{$mb_6}',
                    mb_7 = '{$mb_7}',
                    mb_8 = '{$mb_8}',
                    mb_9 = '{$mb_9}',
                    mb_10 = '{$mb_10}'
                    {$sql_password}
                    {$sql_nick_date}
                    {$sql_open_date}
                    {$sql_email_certify}
                    {$sql_certify}
              where mb_id = '$mb_id' ";

    // mb_sex = '{$mb_sex}',
    // mb_birth = '{$mb_birth}'
    sql_query($sql);

    $sql = " update {$g5['g5_shop_order_address_table']}
                set
                 	ad_zip1 = '{$mb_zip1}',
                 	ad_zip2 = '{$mb_zip2}',
                 	ad_addr1 = '{$mb_addr1}',
                 	ad_addr2 = '{$mb_addr2}',
	         	    ad_addr3 = '{$mb_addr3}',
		            ad_jibeon = '{$mb_addr_jibeon}'
            where mb_id = '{$mb_id}' and ad_default = '1' ";
    sql_query($sql);

    // 비밀번호 변경안내
    if (!empty($sql_password)) {
        $arr_change_data = array();
        msg_autosend('회원', '비밀번호 안내', $mb_id, $arr_change_data);
    }
}


// 회원 아이콘
$mb_dir = G5_DATA_PATH . '/member/' . substr($mb_id, 0, 2);

// 아이콘 삭제
if (isset($_POST['del_mb_icon'])) {
    @unlink($mb_dir . '/' . $mb_id . '.gif');
}

$msg = "";

// 아이콘 업로드
$mb_icon = '';
$image_regex = "/(\.(gif|jpe?g|png))$/i";
$mb_icon_img = $mb_id . '.gif';

if (isset($_FILES['mb_icon']) && is_uploaded_file($_FILES['mb_icon']['tmp_name'])) {
    if (preg_match($image_regex, $_FILES['mb_icon']['name'])) {
        // 아이콘 용량이 설정값보다 이하만 업로드 가능
        if ($_FILES['mb_icon']['size'] <= $config['cf_member_icon_size']) {
            @mkdir($mb_dir, G5_DIR_PERMISSION);
            @chmod($mb_dir, G5_DIR_PERMISSION);
            $dest_path = $mb_dir . '/' . $mb_icon_img;
            move_uploaded_file($_FILES['mb_icon']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            if (file_exists($dest_path)) {
                //=================================================================\
                // 090714
                // gif 파일에 악성코드를 심어 업로드 하는 경우를 방지
                // 에러메세지는 출력하지 않는다.
                //-----------------------------------------------------------------
                $size = @getimagesize($dest_path);
                if (!($size[2] === 1 || $size[2] === 2 || $size[2] === 3)) { // jpg, gif, png 파일이 아니면 올라간 이미지를 삭제한다.
                    @unlink($dest_path);
                } else if ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']) {
                    $thumb = null;
                    if ($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_icon_img, $mb_dir, $mb_dir, $config['cf_member_icon_width'], $config['cf_member_icon_height'], true, true);
                        if ($thumb) {
                            @unlink($dest_path);
                            rename($mb_dir . '/' . $thumb, $dest_path);
                        }
                    }
                    if (!$thumb) {
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
                //=================================================================\
            }
        } else {
            $msg .= '회원아이콘을 ' . number_format($config['cf_member_icon_size']) . '바이트 이하로 업로드 해주십시오.';
        }
    } else {
        $msg .= $_FILES['mb_icon']['name'] . '은(는) 이미지 파일이 아닙니다.';
    }
}

// 회원 프로필 이미지
if ($config['cf_member_img_size'] && $config['cf_member_img_width'] && $config['cf_member_img_height']) {
    $mb_tmp_dir = G5_DATA_PATH . '/member_image/';
    $mb_dir = $mb_tmp_dir . substr($mb_id, 0, 2);
    if (!is_dir($mb_tmp_dir)) {
        @mkdir($mb_tmp_dir, G5_DIR_PERMISSION);
        @chmod($mb_tmp_dir, G5_DIR_PERMISSION);
    }

    // 아이콘 삭제
    if (isset($_POST['del_mb_img'])) {
        @unlink($mb_dir . '/' . $mb_icon_img);
    }

    // 회원 프로필 이미지 업로드
    $mb_img = '';
    if (isset($_FILES['mb_img']) && is_uploaded_file($_FILES['mb_img']['tmp_name'])) {

        $msg = $msg ? $msg . "\\r\\n" : '';

        if (preg_match($image_regex, $_FILES['mb_img']['name'])) {
            // 아이콘 용량이 설정값보다 이하만 업로드 가능
            if ($_FILES['mb_img']['size'] <= $config['cf_member_img_size']) {
                @mkdir($mb_dir, G5_DIR_PERMISSION);
                @chmod($mb_dir, G5_DIR_PERMISSION);
                $dest_path = $mb_dir . '/' . $mb_icon_img;
                move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
                chmod($dest_path, G5_FILE_PERMISSION);
                if (file_exists($dest_path)) {
                    $size = @getimagesize($dest_path);
                    if (!($size[2] === 1 || $size[2] === 2 || $size[2] === 3)) { // gif jpg png 파일이 아니면 올라간 이미지를 삭제한다.
                        @unlink($dest_path);
                    } else if ($size[0] > $config['cf_member_img_width'] || $size[1] > $config['cf_member_img_height']) {
                        $thumb = null;
                        if ($size[2] === 2 || $size[2] === 3) {
                            //jpg 또는 png 파일 적용
                            $thumb = thumbnail($mb_icon_img, $mb_dir, $mb_dir, $config['cf_member_img_width'], $config['cf_member_img_height'], true, true);
                            if ($thumb) {
                                @unlink($dest_path);
                                rename($mb_dir . '/' . $thumb, $dest_path);
                            }
                        }
                        if (!$thumb) {
                            // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                            @unlink($dest_path);
                        }
                    }
                    //=================================================================\
                }
            } else {
                $msg .= '회원이미지을 ' . number_format($config['cf_member_img_size']) . '바이트 이하로 업로드 해주십시오.';
            }
        } else {
            $msg .= $_FILES['mb_img']['name'] . '은(는) gif/jpg 파일이 아닙니다.';
        }
    }
}

// 인증메일 발송
if ($config['cf_use_email_certify'] && $old_email != $mb_email) {
    $subject = '[' . $config['cf_title'] . '] 인증확인 메일입니다.';

    // 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
    $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));

    sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");

    $certify_href = G5_BBS_URL . '/email_certify.php?mb_id=' . $mb_id . '&amp;mb_md5=' . $mb_md5;

    ob_start();
    include_once('./register_form_update_mail3.php');
    $content = ob_get_contents();
    ob_end_clean();

    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
}


// 신규회원 쿠폰발생
if ($w == '') {
    
    // 등급 기준 쿠폰 발급
    
    $couponRatingSql = "SELECT * FROM lt_member_rating ORDER BY mr_start_amount ASC LIMIT 1 ";
    
    $cr = sql_fetch($couponRatingSql);
    $couponProduct = $cr['mr_couponProductName'];
    $couponPlus = $cr['mr_couponPlusName'];
    $couponCart = $cr['mr_couponCartName'];

    $cpArr = array();
    $couponProductArr = explode(',', $couponProduct);
    foreach($couponProductArr as $productName) {
      $cpArr[] = $productName;
    }
    $couponPlusArr = explode(',', $couponPlus);
    foreach($couponPlusArr as $plusName) { 
      $cpArr[] = $plusName;
    }
    $couponCartArr = explode(',', $couponCart);
    foreach($couponCartArr as $cartName) { 
      $cpArr[] = $cartName;
    }

    foreach ($cpArr as $coupon) {  
        if ($coupon && $coupon !='') {
          $productSql = "SELECT * FROM lt_shop_coupon_zone WHERE cz_subject ='$coupon'";
          $cp = sql_fetch($productSql);
      
          if (!$cp) continue;
      
          for ($ci = 1; $ci <= $cp['cz_download_user_limit']; $ci++) { 
            $j = 0;
            do {
                $cp_id = get_coupon_id();
        
                $sql3 = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
                $row3 = sql_fetch($sql3);
        
                if (!$row3['cnt'])
                    break;
                else {
                    if ($j > 20)
                        die(json_encode(array('error' => 'Coupon ID Error')));
                }
                $j++;
            } while (1);
            $cp_start = G5_TIME_YMD;
            // $period = $cp['cz_period'] - 1;
            // if ($period < 0)
            //     $period = 0;
      
            // $cp_end = date('t', strtotime(G5_TIME_YMD));
            // $cp_end = date('Y-m').'-'.$cp_end;
            $cp_end = date('Y-m-d', strtotime("+30 days", G5_SERVER_TIME));
      
            $sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
            ( cp_id, cp_subject, cp_desc, cp_method, cp_target, cz_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime, cp_weekday, cp_week, mb_id )
            VALUES
            ( '$cp_id', '{$cp['cz_subject']}', '{$cp['cz_desc']}', '{$cp['cp_method']}', '{$cp['cp_target']}', '{$cp['cz_id']}', '$cp_start', '$cp_end', '{$cp['cp_type']}', '{$cp['cp_price']}', '{$cp['cp_trunc']}', '{$cp['cp_minimum']}', '{$cp['cp_maximum']}', '" . G5_TIME_YMDHIS . "', '{$cp['cz_weekday']}', '{$cp['cz_week']}', '{$mb_id}' ) ";
            
            $result = sql_query($sql);
            sql_query(" update {$g5['g5_shop_coupon_zone_table']} set cz_download = cz_download + 1 where cz_id = '{$cp['cz_id']}' ");
          }
        } 
      }
    
    
    //신규회원 쿠폰 조회
    //&& $default['de_member_reg_coupon_use'] && $default['de_member_reg_coupon_term'] > 0 && $default['de_member_reg_coupon_price'] > 0) {
    $couponsql = "select * from lt_shop_coupon_mng where cm_target_type = '1' and cm_target_type2 = '회원가입' and cm_status = '발급중'";
    $couponresult = sql_query($couponsql);

    if (sql_num_rows($couponresult) > 0) {
        for ($i = 0; $cmg = sql_fetch_array($couponresult); $i++) {

            //발급중인 회원가입 쿠폰수 만큼 발급
            $j = 0;
            $create_coupon = false;

            do {
                $cp_id = get_coupon_id();

                $sql3 = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
                $row3 = sql_fetch($sql3);

                if (!$row3['cnt']) {
                    $create_coupon = true;
                    break;
                } else {
                    if ($j > 20)
                        break;
                }
            } while (1);

            if ($create_coupon) {
                $cp_subject = $cmg['cm_subject'];
                $cp_method = $cmg['cm_method'];
                $cp_target = '';
                $cp_start = G5_TIME_YMD;
                $cp_end = date("Y-m-d", (G5_SERVER_TIME + (86400 * ((int) $cmg['cm_end_time'] - 1))));
                $cp_type = $cmg['cm_type'];
                $cp_price = $cmg['cm_price'];
                $cp_trunc = $cmg['cm_trunc'];
                $cp_minimum = $cmg['cm_minimum'];
                $cp_maximum = $cmg['cm_maximum'];
                $cp_weekday = $cmg['cm_weekday'];
                $cp_week = $cmg['cm_week'];
                $cm_no = $cmg['cm_no'];

                $sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
                            ( cp_id, cp_subject, cp_method, cp_target, mb_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_weekday, cp_week, cp_datetime, cm_no )
                        VALUES
                            ( '$cp_id', '$cp_subject', '$cp_method', '$cp_target', '$mb_id', '$cp_start', '$cp_end', '$cp_type', '$cp_price', '$cp_trunc', '$cp_minimum', '$cp_maximum', '$cp_weekday', '$cp_week', '" . G5_TIME_YMDHIS . "', '$cm_no' ) ";

                $res = sql_query($sql, false);
            }
        }

        if ($res)
            set_session('ss_member_reg_coupon', 1);
    }
}
try {
    //삼진 데이타 연동
    $mb = get_member($mb_id);
    $result = SM_SEND_CUST_DATA($mb);
} catch (Exception $e) {
}

// 사용자 코드 실행
// @include_once($member_skin_path . '/register_form_update.tail.skin.php');

unset($_SESSION['ss_cert_type']);
unset($_SESSION['ss_cert_no']);
unset($_SESSION['ss_cert_hash']);
unset($_SESSION['ss_cert_birth']);
unset($_SESSION['ss_cert_adult']);

if ($msg)
    echo '<script>alert(\'' . $msg . '\');</script>';

if ($w == '') {

    /*
    if (!$is_member) {
        //회원가입 후 자동 로그인으로 처리

        //회원아이디 세션 생성
        set_session('ss_mb_id', $mb_id);
        // FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함 - 110106
        set_session('ss_mb_key', md5($G5_TIME_YMDHIS . get_real_client_ip() . $_SERVER['HTTP_USER_AGENT']));

        //로그인 이력
        $remote_addr = get_real_client_ip();
        $referer = "";
        if (isset($_SERVER['HTTP_REFERER']))
            $referer = escape_trim(clean_xss_tags($_SERVER['HTTP_REFERER']));
        $user_agent  = escape_trim(clean_xss_tags($_SERVER['HTTP_USER_AGENT']));
        $vi_browser = '';
        $vi_os = '';
        $vi_device = '';
        if (version_compare(phpversion(), '5.3.0', '>=') && defined('G5_BROWSCAP_USE') && G5_BROWSCAP_USE) {
            include_once(G5_BBS_PATH . '/visit_browscap.inc.php');
        }

        $lh_sql = " insert lt_login_history ( lh_ip, mb_id, lh_date_time, lh_referer, lh_agent, lh_browser, lh_os, lh_device )
            values
            ( '{$remote_addr}', '{$mb_id}', '" . G5_TIME_YMDHIS . "', '{$referer}', '{$user_agent}', '{$vi_browser}', '{$vi_os}', '{$vi_device}' ) ";

        sql_query($lh_sql);
    }
*/

    try {
        //회원가입 메시지 발송
        $arr_change_data = array();
        $arr_change_data['사원명'] = $mb_name;

        msg_autosend('회원', '회원 가입', $mb_id, $arr_change_data);
    } catch (Exception $e) {
    }

    header('Location: /auth/join.complate.php?welcome=' . urlencode($mb_name));

    // confirm('회원 가입이 완료 되었습니다\\n 추가정보를 입력 하시겠어요?', G5_HTTP_BBS_URL . '/register_add_info.php?type=' . $register_type, G5_HTTP_BBS_URL . '/register_result.php');
} else if ($w == 'u') {
    $row  = sql_fetch(" select mb_password from {$g5['member_table']} where mb_id = '{$member['mb_id']}' ");
    $tmp_password = $row['mb_password'];

    if ($old_email != $mb_email && $config['cf_use_email_certify']) {
        set_session('ss_mb_id', '');
        alert('회원 정보가 수정 되었습니다.\n\nE-mail 주소가 변경되었으므로 다시 인증하셔야 합니다.', G5_URL);
    } else {
        echo '
        <!doctype html>
        <html lang="ko">
        <head>
        <meta charset="utf-8">
        <title>회원정보수정</title>
        <body>
        <form name="fregisterupdate" method="post" action="/member/info.php">
        <input type="hidden" name="w" value="u">
        <input type="hidden" name="step" value="1">
        <input type="hidden" name="mb_id" value="' . $mb_id . '">
        <input type="hidden" name="cert" value="' . $tmp_password . '">
        <input type="hidden" name="is_update" value="1">
        </form>
        <script>
        alert("회원 정보가 수정 되었습니다.");
        document.fregisterupdate.submit();
        </script>
        </body>
        </html>';
    }
}
