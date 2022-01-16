<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$rt_id = 20190930000008;
include_once(G5_SHOP_PATH . '/ordermail.r.inc.php');

$sql_rental = "SELECT * FROM {$g5['g5_shop_rental_table']} WHERE rt_id={$rt_id}";
$rental = sql_fetch_array(sql_query($sql_rental));
$sql = "SELECT SUM((a.ct_price + a.io_price) * a.ct_qty) AS od_price,
               SUM((b.its_price + a.io_price) * a.ct_qty) AS before_price,
               COUNT(distinct a.it_id) AS cart_count 
        FROM {$g5['g5_shop_rental_order_table']} AS ro
        LEFT JOIN {$g5['g5_shop_cart_table']} a ON ro.od_id=a.od_id
        LEFT JOIN lt_shop_item_sub b ON a.it_id=b.it_id AND a.its_no=b.its_no
        WHERE ro.rt_id='{$rt_id}' AND a.ct_select = '1'
        ORDER BY ct_id";

$row = sql_fetch($sql);
$tot_ct_price = $row['od_price'];
$tot_before_price = $row['before_price'];

$arr_change_data = array();
$arr_change_data['고객명'] = $rental['rt_name'];
$arr_change_data['이름'] = $rental['rt_name'];
$arr_change_data['보낸분'] = $rental['rt_name'];
$arr_change_data['주문번호'] = $rt_id;
$arr_change_data['총주문금액'] = number_format($tot_ct_price + $od_send_cost + $od_send_cost2);
$arr_change_data['회원아이디'] = $member['mb_id'];
$arr_change_data["od_list"] = $list;
$arr_change_data['od_type'] = $rental['rt_type'];
// $arr_change_data['od_id'] = $od_id;
$arr_change_data['rt_id'] = $rt_id;

msg_autosend('리스', '계약 신청', $rental['mb_id'], $arr_change_data);

//include_once(G5_SHOP_PATH.'/ordermail2.inc.php');

// SMS BEGIN --------------------------------------------------------
// 주문고객과 쇼핑몰관리자에게 SMS 전송
if ($config['cf_sms_use'] && ($default['de_sms_use2'] || $default['de_sms_use3'])) {
    $is_sms_send = false;

    // 충전식일 경우 잔액이 있는지 체크
    if ($config['cf_icode_id'] && $config['cf_icode_pw']) {
        $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);

        if ($userinfo['code'] == 0) {
            if ($userinfo['payment'] == 'C') { // 정액제
                $is_sms_send = true;
            } else {
                $minimum_coin = 100;
                if (defined('G5_ICODE_COIN'))
                    $minimum_coin = intval(G5_ICODE_COIN);

                if ((int) $userinfo['coin'] >= $minimum_coin)
                    $is_sms_send = true;
            }
        }
    }

    if ($is_sms_send) {
        $sms_contents = array($default['de_sms_cont2'], $default['de_sms_cont3']);
        $recv_numbers = array($od_hp, $default['de_sms_hp']);
        $send_numbers = array($default['de_admin_company_tel'], $default['de_admin_company_tel']);

        $sms_count = 0;
        $sms_messages = array();

        for ($s = 0; $s < count($sms_contents); $s++) {
            $sms_content = $sms_contents[$s];
            $recv_number = preg_replace("/[^0-9]/", "", $recv_numbers[$s]);
            $send_number = preg_replace("/[^0-9]/", "", $send_numbers[$s]);

            $sms_content = str_replace("{이름}", $od_name, $sms_content);
            $sms_content = str_replace("{보낸분}", $od_name, $sms_content);
            $sms_content = str_replace("{받는분}", $od_b_name, $sms_content);
            $sms_content = str_replace("{주문번호}", $od_id, $sms_content);
            $sms_content = str_replace("{주문금액}", number_format($cart_price + $od_send_cost + $od_send_cost2), $sms_content);
            $sms_content = str_replace("{회원아이디}", $member['mb_id'], $sms_content);
            $sms_content = str_replace("{회사명}", $default['de_admin_company_name'], $sms_content);

            $idx = 'de_sms_use' . ($s + 2);

            if ($default[$idx] && $recv_number) {
                $sms_messages[] = array('recv' => $recv_number, 'send' => $send_number, 'cont' => $sms_content);
                $sms_count++;
            }
        }

        // SMS 전송
        if ($sms_count > 0) {
            if ($config['cf_sms_type'] == 'LMS') {
                include_once(G5_LIB_PATH . '/icode.lms.lib.php');

                $port_setting = get_icode_port_type($config['cf_icode_id'], $config['cf_icode_pw']);

                // SMS 모듈 클래스 생성
                if ($port_setting !== false) {
                    $SMS = new LMS;
                    $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $port_setting);

                    for ($s = 0; $s < count($sms_messages); $s++) {
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
                include_once(G5_LIB_PATH . '/icode.sms.lib.php');

                $SMS = new SMS; // SMS 연결
                $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $config['cf_icode_server_port']);

                for ($s = 0; $s < count($sms_messages); $s++) {
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
}
// SMS END   --------------------------------------------------------


// orderview 에서 사용하기 위해 session에 넣고
$uid = md5($od_id . G5_TIME_YMDHIS . $REMOTE_ADDR);
set_session('ss_orderview_uid', $uid);

// 주문번호제거
set_session('ss_order_id', '');

// 기존자료 세션에서 제거
if (get_session('ss_direct'))
    set_session('ss_cart_direct', '');

// 배송지처리
if ($is_member) {
    $sql = " select * from {$g5['g5_shop_order_address_table']}
                where mb_id = '{$member['mb_id']}'
                  and ad_name = '$od_b_name'
                  and ad_tel = '$od_b_tel'
                  and ad_hp = '$od_b_hp'
                  and ad_zip1 = '$od_b_zip1'
                  and ad_zip2 = '$od_b_zip2'
                  and ad_addr1 = '$od_b_addr1'
                  and ad_addr2 = '$od_b_addr2'
                  and ad_addr3 = '$od_b_addr3' ";
    $row = sql_fetch($sql);

    // 기본배송지 체크
    if ($ad_default) {
        $sql = " update {$g5['g5_shop_order_address_table']}
                    set ad_default = '0'
                    where mb_id = '{$member['mb_id']}' ";
        sql_query($sql);
    }

    $ad_subject = clean_xss_tags($ad_subject);

    if ($row['ad_id']) {
        $sql = " update {$g5['g5_shop_order_address_table']}
                      set ad_default = '$ad_default',
                          ad_subject = '$ad_subject',
                          ad_jibeon  = '$od_b_addr_jibeon'
                    where mb_id = '{$member['mb_id']}'
                      and ad_id = '{$row['ad_id']}' ";
    } else {
        $sql = " insert into {$g5['g5_shop_order_address_table']}
                    set mb_id       = '{$member['mb_id']}',
                        ad_subject  = '$ad_subject',
                        ad_default  = '$ad_default',
                        ad_name     = '$od_b_name',
                        ad_tel      = '$od_b_tel',
                        ad_hp       = '$od_b_hp',
                        ad_zip1     = '$od_b_zip1',
                        ad_zip2     = '$od_b_zip2',
                        ad_addr1    = '$od_b_addr1',
                        ad_addr2    = '$od_b_addr2',
                        ad_addr3    = '$od_b_addr3',
                        ad_jibeon   = '$od_b_addr_jibeon' ";
    }

    sql_query($sql);
}

// goto_url(G5_SHOP_URL . '/orderinquiryview.php?od_id=' . $new_od_id . '&amp;uid=' . $uid);
goto_url(G5_SHOP_URL . '/mypage.php?od_type=R');
?>

<html>

<head>
    <title>주문정보 기록</title>
    <script>
        // 결제 중 새로고침 방지 샘플 스크립트 (중복결제 방지)
        function noRefresh() {
            /* CTRL + N키 막음. */
            if ((event.keyCode == 78) && (event.ctrlKey == true)) {
                event.keyCode = 0;
                return false;
            }
            /* F5 번키 막음. */
            if (event.keyCode == 116) {
                event.keyCode = 0;
                return false;
            }
        }

        document.onkeydown = noRefresh;
    </script>
</head>

</html>