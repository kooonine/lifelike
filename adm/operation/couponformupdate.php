<?php
$sub_menu = '200210';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

$_POST = array_map('trim', $_POST);

if(!$_POST['cm_no'])
    alert('잘못된 접근입니다.');

$sql = "select * from lt_shop_coupon_mng where cm_no={$cm_no} ";
$cmg = sql_fetch($sql);

if($w == '') {
    if($_POST['coupon_sel_member'] == 'all') {
        $mb_id_list = Array();
        $mb_id_list[] ='전체회원';
        
    } elseif($_POST['coupon_sel_member'] == 'member') {         
        $mb_id_list = explode(",", $_POST['mb_id_list']);
        
    } elseif($_POST['coupon_sel_member'] == 'excel') {
        //엑셀 읽기 -> $mb_id_list 회원ID 넣기
        $mb_id_list = explode(",", $_POST['mb_id_list']);
    }
    $arr_change_data = array();
    $arr_change_data['쿠폰명'] = $cmg['cm_subject'];
    if($cp_end != '0000-00-00') { 
        $arr_change_data['사용기간'] = substr($cp_start, 0, 10)."~".substr($cp_end, 0, 10);
    } else {
        $arr_change_data['사용기간'] = "기간 제한 없음";
    }
    $cm_summary = "";
    
    if($cmg['cm_summary']) $cm_summary .= $cmg['cm_summary']."<br/>";
    if($cmg['cm_minimum']) $cm_summary .= "결제 시 ".$cmg['cm_minimum']."원 이상 구매 시 사용<br/>";
    if($cmg['cm_maximum']) $cm_summary .= "최대 할인 금액 ".$cmg['cm_maximum']." 원<br/>";
    
    $arr_change_data['사용범위안내'] = $cm_summary;
    
    for ($i = 0; $i < count($mb_id_list); $i++) {
        
        if($mb_id_list[$i] != '전체회원' && $mb_id_list[$i] != '')
        {
            $sql = " select mb_id from {$g5['member_table']} where mb_id = '{$mb_id_list[$i]}' and mb_leave_date = '' and mb_intercept_date = '' ";
            $row = sql_fetch($sql);
            if(!$row['mb_id'])
                alert('입력하신 회원아이디는 존재하지 않거나 탈퇴 또는 차단된 회원아이디입니다.');
        }
        $mb_id = $mb_id_list[$i];
        
        $j = 0;
        do {
            $cp_id = get_coupon_id();
            
            $sql3 = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
            $row3 = sql_fetch($sql3);
            
            if(!$row3['cnt'])
                break;
                else {
                    if($j > 20)
                        die('Coupon ID Error');
                }
            $j++;
        } while(1);
        
        $sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
                ( cp_id, cp_subject, cp_method, cp_target, mb_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime, cm_no, admin_id )
            VALUES
                ( '$cp_id', '{$cmg['cm_subject']}', '{$cmg['cm_method']}', '', '$mb_id', '$cp_start', '$cp_end', '{$cmg['cm_type']}', '{$cmg['cm_price']}', '{$cmg['cm_trunc']}', '{$cmg['cm_minimum']}', '{$cmg['cm_maximum']}', '".G5_TIME_YMDHIS."', '{$cm_no}', '{$member['mb_id']}' ) ";
        
        sql_query($sql);
        
        if($cmg['cm_sms_send']) msg_autosend("광고", "쿠폰발급 안내", $mb_id, $arr_change_data);
    }
    
    
} else if($w == 'u') {
    $sql = " select * from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
    $cp = sql_fetch($sql);

    if(!$cp['cp_id'])
        alert('쿠폰정보가 존재하지 않습니다.', './configform_coupon_issuance_history.php?cm_no='.$_POST['cm_no']);

    if($_POST['chk_all_mb']) {
        $mb_id = '전체회원';
    }

    $sql = " update {$g5['g5_shop_coupon_table']}
                set cp_subject  = '$cp_subject',
                    cp_method   = '$cp_method',
                    cp_target   = '$cp_target',
                    mb_id       = '$mb_id',
                    cp_start    = '$cp_start',
                    cp_end      = '$cp_end',
                    cp_type     = '$cp_type',
                    cp_price    = '$cp_price',
                    cp_trunc    = '$cp_trunc',
                    cp_maximum  = '$cp_maximum',
                    cp_minimum  = '$cp_minimum'
                where cp_id = '$cp_id' ";
    sql_query($sql);
}

// 쿠폰생성알림 발송
if($w == '' && ($_POST['cp_sms_send'] || $_POST['cp_email_send']) && false) {
    include_once(G5_LIB_PATH.'/mailer.lib.php');

    $sms_count = 0;
    $arr_send_list = array();
    $sms_messages = array();

    if($_POST['chk_all_mb']) {
        $sql = " select mb_id, mb_name, mb_hp, mb_email, mb_mailling, mb_sms
                    from {$g5['member_table']}
                    where mb_leave_date = ''
                      and mb_intercept_date = ''
                      and ( mb_mailling = '1' or mb_sms = '1' )
                      and mb_id <> '{$config['cf_admin']}' ";
    } else {
        $sql = " select mb_id, mb_name, mb_hp, mb_email, mb_mailling, mb_sms
                    from {$g5['member_table']}
                    where mb_id = '$mb_id' ";
    }

    $result = sql_query($sql);

    for($i=0; $row = sql_fetch_array($result); $i++) {
        $arr_send_list[] = $row;
    }

    $count = count($arr_send_list);

    for($i=0; $i<$count; $i++) {
        if(!$arr_send_list[$i]['mb_id'])
            continue;

        // SMS
        if($config['cf_sms_use'] == 'icode' && $_POST['cp_sms_send'] && $arr_send_list[$i]['mb_hp'] && $arr_send_list[$i]['mb_sms']) {
            $sms_contents = $cp_subject.' 쿠폰이 '.get_text($arr_send_list[$i]['mb_name']).'님께 발행됐습니다. 쿠폰만료 : '.$cp_end.' '.str_replace('http://', '', G5_URL);

            if($sms_contents) {
                $receive_number = preg_replace("/[^0-9]/", "", $arr_send_list[$i]['mb_hp']);   // 수신자번호
                $send_number = preg_replace("/[^0-9]/", "", $default['de_admin_company_tel']); // 발신자번호

                if($receive_number)
                    $sms_messages[] = array('recv' => $receive_number, 'send' => $send_number, 'cont' => $sms_contents);
            }
        }

        
        // E-MAIL
        if($config['cf_email_use'] && $_POST['cp_email_send'] && $arr_send_list[$i]['mb_email'] && $arr_send_list[$i]['mb_mailling']) {
            $mb_name = get_text($arr_send_list[$i]['mb_name']);
            switch($cp_method) {
                case 2:
                    $coupon_method = '결제금액할인';
                    break;
                default:
                    $coupon_method = '개별제품 할인';
                    break;
            }
            $contents = '쿠폰명 : '.$cp_subject.'<br>';
            $contents .= '적용대상 : '.$coupon_method.'<br>';
            $contents .= '쿠폰만료 : '.$cp_end;

            $title = $config['cf_title'].' - 쿠폰발행알림 메일';
            $email = $arr_send_list[$i]['mb_email'];

            ob_start();
            include G5_SHOP_PATH.'/mail/couponmail.mail.php';
            $content = ob_get_contents();
            ob_end_clean();

            mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $email, $title, $content, 1);
        }
    }

    // SMS발송
    $sms_count = count($sms_messages);
    if($sms_count > 0) {
        if($config['cf_sms_type'] == 'LMS') {
            include_once(G5_LIB_PATH.'/icode.lms.lib.php');

            $port_setting = get_icode_port_type($config['cf_icode_id'], $config['cf_icode_pw']);

            // SMS 모듈 클래스 생성
            if($port_setting !== false) {
                $SMS = new LMS;
                $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $port_setting);

                for($s=0; $s<$sms_count; $s++) {
                    $strDest     = array();
                    $strDest[]   = $sms_messages[$s]['recv'];
                    $strCallBack = $sms_messages[$s]['send'];
                    $strCaller   = iconv_euckr(trim($default['de_admin_company_name']));
                    $strSubject  = '';
                    $strURL      = '';
                    $strData     = iconv_euckr($sms_messages[$s]['cont']);
                    $strDate     = '';
                    $nCount      = count($strDest);

                    $res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);

                    $SMS->Send();
                    $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
                }
            }
        } else {
            include_once(G5_LIB_PATH.'/icode.sms.lib.php');

            $SMS = new SMS; // SMS 연결
            $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $config['cf_icode_server_port']);

            for($s=0; $s<$sms_count; $s++) {
                $recv_number = $sms_messages[$s]['recv'];
                $send_number = $sms_messages[$s]['send'];
                $sms_content = iconv_euckr($sms_messages[$s]['cont']);

                $SMS->Add($recv_number, $send_number, $config['cf_icode_id'], $sms_content, "");
            }

            $SMS->Send();
            $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
        }
    }
}

alert("쿠폰이 발급되었습니다.", './configform_coupon_issuance_history.php?cm_no='.$_POST['cm_no'], false);
?>