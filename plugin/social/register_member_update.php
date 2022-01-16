<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/register.lib.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');
// dd(trim($_POST['appleRMCheck']));
if (trim($_POST['provider']) == 'apple') {

} else {
    if (!$config['cf_social_login_use']) {
        alert('소셜 로그인을 사용하지 않습니다.', G5_URL);
    }
    
    if ($is_member) {
        alert('이미 회원가입 하였습니다.', G5_URL);
    }
    
    $provider_name = social_get_request_provider();
    $user_profile = social_session_exists_check();
    if (!$user_profile) {
        alert("소셜로그인 을 하신 분만 접근할 수 있습니다.", G5_URL);
    }  
}
// 소셜 가입된 내역이 있는지 확인 상수 G5_SOCIAL_DELETE_DAY 관련
$is_exists_social_account = social_before_join_check(G5_URL);

$sm_id = $user_profile->sid;
$mb_id = trim($_POST['mb_id']);
$mb_password    = trim($_POST['mb_password']);
$mb_password_re = trim($_POST['mb_password_re']);
$mb_nick        = trim(strip_tags($_POST['mb_nick']));
$mb_email       = trim($_POST['mb_email']);
$mb_name        = trim($_POST['mb_name']);
$mb_tel         = isset($_POST['mb_tel'])           ? trim($_POST['mb_tel'])         : "";
$mb_hp          = isset($_POST['mb_hp'])            ? trim($_POST['mb_hp'])          : "";
$mb_zip1        = isset($_POST['mb_zip'])           ? substr(trim($_POST['mb_zip']), 0, 3) : "";
$mb_zip2        = isset($_POST['mb_zip'])           ? substr(trim($_POST['mb_zip']), 3)    : "";
$mb_addr1       = isset($_POST['mb_addr1'])         ? trim($_POST['mb_addr1'])       : "";
$mb_addr2       = isset($_POST['mb_addr2'])         ? trim($_POST['mb_addr2'])       : "";
$mb_addr3       = isset($_POST['mb_addr3'])         ? trim($_POST['mb_addr3'])       : "";
$mb_addr_jibeon = isset($_POST['mb_addr_jibeon'])   ? trim($_POST['mb_addr_jibeon']) : "";

$mb_mailling    = isset($_POST['mb_mailling'])      ? trim($_POST['mb_mailling'])    : "";
$mb_sms         = isset($_POST['mb_sms'])           ? trim($_POST['mb_sms'])         : "";

$mb_name        = clean_xss_tags($mb_name);
$mb_email       = get_email_address($mb_email);
$mb_tel         = clean_xss_tags($mb_tel);
$mb_zip1        = preg_replace('/[^0-9]/', '', $mb_zip1);
$mb_zip2        = preg_replace('/[^0-9]/', '', $mb_zip2);
$mb_addr1       = clean_xss_tags($mb_addr1);
$mb_addr2       = clean_xss_tags($mb_addr2);
$mb_addr3       = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';
$mb_10          = isset($_POST['mb_10'])            ? trim($_POST['mb_10'])          : 0;
$register_type  = isset($_POST['register_type'])    ? trim($_POST['register_type'])  : "";
$mb_certify  = isset($_POST['mb_certify'])    ? trim($_POST['mb_certify'])  : "";
$mb_dupinfo  = isset($_POST['mb_dupinfo'])    ? trim($_POST['mb_dupinfo'])  : "";

if ($msg = empty_mb_id($mb_id))         alert($msg, "", true, true); // alert($msg, $url, $error, $post);
if ($msg = valid_mb_id($mb_id))         alert($msg, "", true, true);
if ($msg = count_mb_id($mb_id))         alert($msg, "", true, true);

// 이름, 닉네임에 utf-8 이외의 문자가 포함됐다면 오류
// 서버환경에 따라 정상적으로 체크되지 않을 수 있음.
$tmp_mb_name = iconv('UTF-8', 'UTF-8//IGNORE', $mb_name);
if ($tmp_mb_name != $mb_name) {
    $mb_name = $tmp_mb_name;
}
$tmp_mb_nick = iconv('UTF-8', 'UTF-8//IGNORE', $mb_nick);
if ($tmp_mb_nick != $mb_nick) {
    $mb_nick = $tmp_mb_nick;
}

if (!$mb_nick || !$mb_name) {
    $tmp = explode('@', $mb_email);
    $mb_nick = $mb_nick ? $mb_nick : $tmp[0];
    $mb_name = $mb_name ? $mb_name : $tmp[0];
}

if (!isset($mb_password) || !$mb_password) {
    $mb_password = md5(pack('V*', rand(), rand(), rand(), rand()));
}

if ($msg = empty_mb_name($mb_name))       alert($msg, "", true, true);
if ($msg = empty_mb_email($mb_email))     alert($msg, "", true, true);
if ($msg = reserve_mb_id($mb_id))         alert($msg, "", true, true);
if ($msg = valid_mb_email($mb_email))     alert($msg, "", true, true);
if ($msg = prohibit_mb_email($mb_email))  alert($msg, "", true, true);

if ($msg = exist_mb_id($mb_id))           alert($msg);

if (empty($mb_certify) || empty($mb_dupinfo))
    alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");

$mb_hp = hyphen_hp_number($mb_hp);

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


//if ($msg = empty_mb_nick($mb_nick))     alert($msg, "", true, true);
//if ($msg = reserve_mb_nick($mb_nick))   alert($msg, "", true, true);
// 이름에 한글명 체크를 하지 않는다.
//if ($msg = valid_mb_name($mb_name))     alert($msg, "", true, true);
//if ($msg = valid_mb_nick($mb_nick))     alert($msg, "", true, true);
//if ($msg = exist_mb_nick($mb_nick, $mb_id))     alert($msg, "", true, true);
//if ($msg = exist_mb_email($mb_email, $mb_id))   alert($msg, "", true, true);

$mb_email_certify = G5_TIME_YMDHIS;

//메일인증을 사용한다면
if (defined('G5_SOCIAL_CERTIFY_MAIL') && G5_SOCIAL_CERTIFY_MAIL && $config['cf_use_email_certify']) {
    $mb_email_certify = '';
}

//회원 메일 동의
$mb_mailling = (isset($_POST['mb_mailling']) && $_POST['mb_mailling']) ? 1 : 0;
//회원 정보 공개
$mb_open = (isset($_POST['mb_open']) && $_POST['mb_open']) ? 1 : 0;

$mb_sms = (isset($_POST['mb_sms']) && $_POST['mb_sms']) ? 1 : 0;

// 회원정보 입력
$sql = "insert into {$g5['member_table']}
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
        mb_10 = '{$mb_10}',
        mb_hp = '{$mb_hp}',
        mb_certify = '{$mb_certify}',
        mb_adult = 0,
        mb_birth = '{$mb_birth}',
        mb_sex = '{$mb_sex}'";

if (!empty($mb_dupinfo)) {
    $sql .= ", mb_dupinfo='{$mb_dupinfo}'";
}

// 이메일 인증을 사용하지 않는다면 이메일 인증시간을 바로 넣는다
if (!$config['cf_use_email_certify'])
    $sql .= " , mb_email_certify = '" . G5_TIME_YMDHIS . "' ";

$result = sql_query($sql, false);

if ($result) {

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

    // 최고관리자님께 메일 발송
    /*
    if ($config['cf_email_mb_super_admin']) {
        $subject = '['.$config['cf_title'].'] '.$mb_nick .' 님께서 회원으로 가입하셨습니다.';

        ob_start();
        include_once (G5_BBS_PATH.'/register_form_update_mail2.php');
        $content = ob_get_contents();
        ob_end_clean();

        mailer($mb_nick, $mb_email, $config['cf_admin_email'], $subject, $content, 1);
    }*/

    $mb = get_member($mb_id);

    //소셜 로그인 계정 추가
    if (function_exists('social_login_success_after') || trim($_POST['provider']) == 'apple') {
        $sociPro = '';
        if (trim($_POST['provider']) == 'apple') {
            $sociPro = 'appleRes';
        }
        social_login_success_after($mb, $sociPro, 'register');
    }

    /*
    if( !empty($user_profile->photoURL) && ($config['cf_register_level'] >= $config['cf_icon_level']) ){  //회원 프로필 사진이 있고, 회원 아이콘를 올릴수 있는 조건이면
        
        // 회원아이콘
        $mb_dir = G5_DATA_PATH.'/member/'.substr($mb_id,0,2);
        @mkdir($mb_dir, G5_DIR_PERMISSION);
        @chmod($mb_dir, G5_DIR_PERMISSION);
        $dest_path = "$mb_dir/$mb_id.gif";
        
        social_profile_img_resize($dest_path, $user_profile->photoURL, $config['cf_member_icon_width'], $config['cf_member_icon_height'] );
        
        // 회원이미지
        if( is_dir(G5_DATA_PATH.'/member_image/') ) {
            $mb_dir = G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2);
            @mkdir($mb_dir, G5_DIR_PERMISSION);
            @chmod($mb_dir, G5_DIR_PERMISSION);
            $dest_path = "$mb_dir/$mb_id.gif";
            
            social_profile_img_resize($dest_path, $user_profile->photoURL, $config['cf_member_img_width'], $config['cf_member_img_height'] );
        }
    }
    */
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
                $cm_no = $cmg['cm_no'];

                $sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
                            ( cp_id, cp_subject, cp_method, cp_target, mb_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime, cm_no )
                        VALUES
                            ( '$cp_id', '$cp_subject', '$cp_method', '$cp_target', '$mb_id', '$cp_start', '$cp_end', '$cp_type', '$cp_price', '$cp_trunc', '$cp_minimum', '$cp_maximum', '" . G5_TIME_YMDHIS . "', '$cm_no' ) ";

                $res = sql_query($sql, false);
            }
        }

        if ($res)
            set_session('ss_member_reg_coupon', 1);
    }


    try {
        //삼진 데이타 연동
        $mb = get_member($mb_id);
        $result = SM_SEND_CUST_DATA($mb);
    } catch (Exception $e) {
    }

    social_provider_logout();
    //session_unset(); // 모든 세션변수를 언레지스터 시켜줌
    //session_destroy(); // 세션해제함

    set_session('ss_mb_reg', $mb['mb_id']);
    //바로 로그인 처리
    set_session('ss_mb_id', $mb['mb_id']);
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

    try {
        //회원가입 메시지 발송
        $arr_change_data = array();
        $arr_change_data['사원명'] = $mb_name;

        msg_autosend('회원', '회원 가입', $mb_id, $arr_change_data);
    } catch (Exception $e) {
    }
    /*
    if( $mb_email_certify ){    //메일인증 사용 안하면

        //바로 로그인 처리
        set_session('ss_mb_id', $mb['mb_id']);

    } else {    // 메일인증을 사용한다면
        $subject = '['.$config['cf_title'].'] 인증확인 메일입니다.';

        // 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
        $mb_md5 = md5(pack('V*', rand(), rand(), rand(), rand()));

        sql_query(" update {$g5['member_table']} set mb_email_certify2 = '$mb_md5' where mb_id = '$mb_id' ");

        $certify_href = G5_BBS_URL.'/email_certify.php?mb_id='.$mb_id.'&amp;mb_md5='.$mb_md5;

        ob_start();
        include_once (G5_BBS_PATH.'/register_form_update_mail3.php');
        $content = ob_get_contents();
        ob_end_clean();

        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 1);
    }
    */

    // 사용자 코드 실행
    // @include_once($member_skin_path . '/register_form_update.tail.skin.php');

    // confirm('회원 가입이 완료 되었습니다\\n 추가정보를 입력 하시겠어요?',G5_HTTP_BBS_URL.'/register_add_info.php?type='.$register_type,G5_HTTP_BBS_URL.'/register_result.php');
    // goto_url('/auth/join.complate.php?welcome=' . urlencode($mb_name));
    header('Location: /auth/join.complate.php?welcome=' . urlencode($mb_name));
} else {

    alert('회원 가입 오류!', G5_URL);
}
