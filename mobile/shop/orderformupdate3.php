<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$page_return_url = G5_SHOP_URL.'/orderform.php';
if(get_session('ss_direct'))
    $page_return_url .= '?sw_direct=1';

// 결제등록 완료 체크
if($od_settle_case != '무통장' && $od_settle_case != 'KAKAOPAY') {
    if($default['de_pg_service'] == 'kcp' && ($_POST['tran_cd'] == '' || $_POST['enc_info'] == '' || $_POST['enc_data'] == ''))
        alert('결제등록 요청 후 주문해 주십시오.', $page_return_url);

    if($default['de_pg_service'] == 'lg' && !$_POST['LGD_PAYKEY'])
        alert('결제등록 요청 후 주문해 주십시오.', $page_return_url);

    if($default['de_pg_service'] == 'inicis' && !$_POST['P_HASH'])
        alert('결제등록 요청 후 주문해 주십시오.', $page_return_url);
}

// 장바구니가 비어있는가?
if (get_session('ss_direct'))
    $tmp_cart_id = get_session('ss_cart_direct');
else
    $tmp_cart_id = get_session('ss_cart_id');

if (get_cart_count($tmp_cart_id) == 0)// 장바구니에 담기
    alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', G5_SHOP_URL.'/cart.php');

$error = "";

$i_price     = (int)$_POST['od_price'];
$i_send_cost  = (int)$_POST['od_send_cost'];
$i_send_cost2  = (int)$_POST['od_send_cost2'];
$i_send_coupon  = (int)$_POST['od_send_coupon'];
$i_temp_point = (int)$_POST['od_temp_point'];

$i_price = $i_price + $i_send_cost + $i_send_cost2 - $i_temp_point - $i_send_coupon;

$order_price = 0;
switch($_POST['od_type']) {
    case 'L':
        //세탁
        $laundry_price     = (int)$_POST['laundry_price'];
        if(isset($_POST['chkFree']) && $_POST['chkFree'] == "1"){
            //무료세탁
            $order_price =  $i_send_cost;
        } else {
            //유료세탁
            $order_price = $laundry_price + $i_send_cost;
        }
        break;
    case 'K':
        //세탁보관
        $laundry_price     = (int)$_POST['laundry_price'];
        $keep_price     = (int)$_POST['keep_price']; //월 보관비용
        $ct_keep_month =  (int)$_POST['ct_keep_month']; //보관기간
        if(isset($_POST['chkFree']) && $_POST['chkFree'] == "1"){
            //무료세탁
            $order_price = ($keep_price * $ct_keep_month) + $i_send_cost;
        } else {
            //유료세탁
            $order_price = $laundry_price + ($keep_price * $ct_keep_month) + $i_send_cost;
        }
        break;
    case 'S':
        //수선
        break;
}
//$order_price = $tot_od_price + $send_cost + $send_cost2 - $tot_sc_cp_price - $od_temp_point;

$od_status = '주문';
$od_tno    = '';
if ($od_settle_case == "무통장")
{
    $od_receipt_point   = $i_temp_point;
    $od_receipt_price   = 0;
    $od_misu            = $i_price - $od_receipt_price;
    if($od_misu == 0) {
        $od_status      = '결제완료';
        $od_receipt_time = G5_TIME_YMDHIS;
    }
}
else if ($od_settle_case == "계좌이체")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include G5_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'inicis':
            include G5_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        default:
            include G5_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            $bank_name  = iconv("cp949", "utf-8", $bank_name);
            break;
    }

    $od_tno             = $tno;
    $od_receipt_price   = $amount;
    $od_receipt_point   = $i_temp_point;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $od_settle_case;
    $od_deposit_name    = $od_name;
    $od_bank_account    = $bank_name;
    $pg_price           = $amount;
    $od_misu            = $i_price - $od_receipt_price;
    if($od_misu == 0)
        $od_status      = '결제완료';
}
else if ($od_settle_case == "가상계좌")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include G5_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'inicis':
            include G5_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        default:
            include G5_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            $bankname   = iconv("cp949", "utf-8", $bankname);
            $depositor  = iconv("cp949", "utf-8", $depositor);
            break;
    }

    $od_receipt_point   = $i_temp_point;
    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_price   = 0;
    $od_bank_account    = $bankname.' '.$account;
    $od_deposit_name    = $depositor;
    $pg_price           = $amount;
    $od_misu            = $i_price - $od_receipt_price;
}
else if ($od_settle_case == "휴대전화")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include G5_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'inicis':
            include G5_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        default:
            include G5_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            break;
    }

    $od_tno             = $tno;
    $od_receipt_price   = $amount;
    $od_receipt_point   = $i_temp_point;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $commid.' '.$mobile_no;
    $pg_price           = $amount;
    $od_misu            = $i_price - $od_receipt_price;
    if($od_misu == 0)
        $od_status      = '결제완료';
}
else if ($od_settle_case == "신용카드")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include G5_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'inicis':
            include G5_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        default:
            include G5_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            $card_name  = iconv("cp949", "utf-8", $card_name);
            break;
    }

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_price   = $amount;
    $od_receipt_point   = $i_temp_point;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_misu            = $i_price - $od_receipt_price;
    if($od_misu == 0)
        $od_status      = '결제완료';
}
else if ($od_settle_case == "간편결제")
{
    switch($default['de_pg_service']) {
        case 'lg':
            include G5_SHOP_PATH.'/lg/xpay_result.php';
            break;
        case 'inicis':
            include G5_MSHOP_PATH.'/inicis/pay_result.php';
            break;
        default:
            include G5_MSHOP_PATH.'/kcp/pp_ax_hub.php';
            $card_name  = iconv("cp949", "utf-8", $card_name);
            break;
    }

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_price   = $amount;
    $od_receipt_point   = $i_temp_point;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_misu            = $i_price - $od_receipt_price;
    if($od_misu == 0)
        $od_status      = '결제완료';
}
else if ( is_inicis_order_pay($od_settle_case) )    //이니시스의 삼성페이 또는 L.pay
{
    // 이니시스에서만 지원
    include G5_MSHOP_PATH.'/inicis/pay_result.php';

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_price   = $amount;
    $od_receipt_point   = $i_temp_point;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_misu            = $i_price - $od_receipt_price;
    if($od_misu == 0)
        $od_status      = '결제완료';
}
else if ($od_settle_case == "KAKAOPAY")
{
    include G5_SHOP_PATH.'/kakaopay/kakaopay_result.php';

    $od_tno             = $tno;
    $od_app_no          = $app_no;
    $od_receipt_price   = $amount;
    $od_receipt_point   = $i_temp_point;
    $od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
    $od_bank_account    = $card_name;
    $pg_price           = $amount;
    $od_misu            = $i_price - $od_receipt_price;
    if($od_misu == 0)
        $od_status      = '결제완료';
}
else
{
    die("od_settle_case Error!!!");
}

$od_pg = $default['de_pg_service'];
if($od_settle_case == 'KAKAOPAY')
    $od_pg = 'KAKAOPAY';

// 주문금액과 결제금액이 일치하는지 체크
if($tno) {
    if((int)$order_price !== (int)$pg_price) {
        $cancel_msg = '결제금액 불일치';
        switch($od_pg) {
            case 'lg':
                include G5_SHOP_PATH.'/lg/xpay_cancel.php';
                break;
            case 'inicis':
                include G5_SHOP_PATH.'/inicis/inipay_cancel.php';
                break;
            case 'KAKAOPAY':
                $_REQUEST['TID']               = $tno;
                $_REQUEST['Amt']               = $amount;
                $_REQUEST['CancelMsg']         = $cancel_msg;
                $_REQUEST['PartialCancelCode'] = 0;
                include G5_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
                break;
            default:
                include G5_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
                break;
        }

        die("Receipt Amount Error");
    }
}

if ($is_member)
    $od_pwd = $member['mb_password'];
else
    $od_pwd = get_encrypt_string($_POST['od_pwd']);

// 주문번호를 얻는다.
$od_id = get_session('ss_order_id');

if( !$od_id ){
    die("주문번호가 없습니다.");
}

$od_escrow = 0;
if($escw_yn == 'Y')
    $od_escrow = 1;

// 복합과세 금액
$od_tax_mny = round($i_price / 1.1);
$od_vat_mny = $i_price - $od_tax_mny;
$od_free_mny = 0;
if($default['de_tax_flag_use']) {
    $od_tax_mny = (int)$_POST['comm_tax_mny'];
    $od_vat_mny = (int)$_POST['comm_vat_mny'];
    $od_free_mny = (int)$_POST['comm_free_mny'];
}

$od_email         = get_email_address($od_email);
$od_name          = clean_xss_tags($od_name);
$od_tel           = clean_xss_tags($od_tel);
$od_hp            = clean_xss_tags($od_hp);
$od_zip           = preg_replace('/[^0-9]/', '', $od_zip);
$od_zip1          = substr($od_zip, 0, 3);
$od_zip2          = substr($od_zip, 3);
$od_addr1         = clean_xss_tags($od_addr1);
$od_addr2         = clean_xss_tags($od_addr2);
$od_addr3         = clean_xss_tags($od_addr3);
$od_addr_jibeon   = preg_match("/^(N|R)$/", $od_addr_jibeon) ? $od_addr_jibeon : '';
$od_b_name        = clean_xss_tags($od_b_name);
$od_b_tel         = clean_xss_tags($od_b_tel);
$od_b_hp          = clean_xss_tags($od_b_hp);
$od_b_addr1       = clean_xss_tags($od_b_addr1);
$od_b_addr2       = clean_xss_tags($od_b_addr2);
$od_b_addr3       = clean_xss_tags($od_b_addr3);
$od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';
$od_memo          = clean_xss_tags($od_memo);
$od_deposit_name  = clean_xss_tags($od_deposit_name);
$od_tax_flag      = $default['de_tax_flag_use'];

// 주문서에 입력
$sql = " insert {$g5['g5_shop_order_table']}
            set od_id             = '$od_id',
                od_type           = '$od_type',
                mb_id             = '{$member['mb_id']}',
                od_pwd            = '$od_pwd',
                od_name           = '$od_name',
                od_email          = '$od_email',
                od_tel            = '$od_tel',
                od_hp             = '$od_hp',
                od_zip1           = '$od_zip1',
                od_zip2           = '$od_zip2',
                od_addr1          = '$od_addr1',
                od_addr2          = '$od_addr2',
                od_addr3          = '$od_addr3',
                od_addr_jibeon    = '$od_addr_jibeon',
                od_b_name         = '$od_b_name',
                od_b_tel          = '$od_b_tel',
                od_b_hp           = '$od_b_hp',
                od_b_zip1         = '$od_b_zip1',
                od_b_zip2         = '$od_b_zip2',
                od_b_addr1        = '$od_b_addr1',
                od_b_addr2        = '$od_b_addr2',
                od_b_addr3        = '$od_b_addr3',
                od_b_addr_jibeon  = '$od_b_addr_jibeon',
                od_deposit_name   = '$od_deposit_name',
                od_memo           = '$od_memo',
                od_cart_count     = '$cart_count',
                od_cart_price     = '$tot_ct_price',
                od_cart_coupon    = '$tot_it_cp_price',
                od_send_cost      = '$od_send_cost',
                od_send_coupon    = '$tot_sc_cp_price',
                od_send_cost2     = '$od_send_cost2',
                od_coupon         = '$tot_od_cp_price',
                od_receipt_price  = '$od_receipt_price',
                od_receipt_point  = '$od_receipt_point',
                od_bank_account   = '$od_bank_account',
                od_receipt_time   = '$od_receipt_time',
                od_misu           = '$od_misu',
                od_pg             = '$od_pg',
                od_tno            = '$od_tno',
                od_app_no         = '$od_app_no',
                od_escrow         = '$od_escrow',
                od_tax_flag       = '$od_tax_flag',
                od_tax_mny        = '$od_tax_mny',
                od_vat_mny        = '$od_vat_mny',
                od_free_mny       = '$od_free_mny',
                od_status         = '$od_status',
                od_shop_memo      = '',
                od_hope_date      = '$od_hope_date',
                od_time           = '".G5_TIME_YMDHIS."',
                od_mobile         = '1',
                od_ip             = '$REMOTE_ADDR',
                od_settle_case    = '$od_settle_case',
                od_test           = '{$default['de_card_test']}',
                cust_memo         = '$cust_memo'
                ";
$result = sql_query($sql, false);

// 주문정보 입력 오류시 결제 취소
if(!$result) {
    if($tno) {
        $cancel_msg = '주문정보 입력 오류';
        switch($od_pg) {
            case 'lg':
                include G5_SHOP_PATH.'/lg/xpay_cancel.php';
                break;
            case 'inicis':
                include G5_SHOP_PATH.'/inicis/inipay_cancel.php';
                break;
            case 'KAKAOPAY':
                $_REQUEST['TID']               = $tno;
                $_REQUEST['Amt']               = $amount;
                $_REQUEST['CancelMsg']         = $cancel_msg;
                $_REQUEST['PartialCancelCode'] = 0;
                include G5_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
                break;
            default:
                include G5_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
                break;
        }
    }

    // 관리자에게 오류 알림 메일발송
    $error = 'order';
    include G5_SHOP_PATH.'/ordererrormail.php';

    // 주문삭제
    sql_query(" delete from {$g5['g5_shop_order_table']} where od_id = '$od_id' ", false);

    die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>'.strtoupper($od_pg).'를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
}

// 장바구니 상태변경
// 신용카드로 주문하면서 신용카드 포인트 사용하지 않는다면 포인트 부여하지 않음
$cart_status = $od_status;
$sql_card_point = "";
if ($od_receipt_price > 0 && !$default['de_card_point']) {
    $sql_card_point = " , ct_point = '0' ";
}

$sql_keep = "";
if($_POST['od_type'] == "K" && $_POST['ct_keep_month']){
    $sql_keep = " , ct_keep_month = '".$_POST['ct_keep_month']."' ";
    $sql_keep .= " , ct_keep_start = '".G5_TIME_YMD."' ";

    $enddate = date_add(date_create(G5_TIME_YMD), date_interval_create_from_date_string($_POST['ct_keep_month']." months"));
    $sql_keep .= " , ct_keep_end = '".date_format($enddate,"Y-m-d")."' ";
}

$sql = "update {$g5['g5_shop_cart_table']}
           set od_id = '$od_id',
               ct_status = '$cart_status',
               od_sub_id = right(concat('0000',@RNUM),4)
               $sql_card_point
               $sql_keep
         where od_id = '$tmp_cart_id'
           and ct_select = '1'
           and ((@RNUM:=0)=0)
           and ((@RNUM:=IFNULL(@RNUM,0) + 1) >= 0) ";
$result = sql_query($sql, false);

// 주문정보 입력 오류시 결제 취소
if(!$result) {
    if($tno) {
        $cancel_msg = '주문상태 변경 오류';
        switch($od_pg) {
            case 'lg':
                include G5_SHOP_PATH.'/lg/xpay_cancel.php';
                break;
            case 'inicis':
                include G5_SHOP_PATH.'/inicis/inipay_cancel.php';
                break;
            case 'KAKAOPAY':
                $_REQUEST['TID']               = $tno;
                $_REQUEST['Amt']               = $amount;
                $_REQUEST['CancelMsg']         = $cancel_msg;
                $_REQUEST['PartialCancelCode'] = 0;
                include G5_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
                break;
            default:
                include G5_SHOP_PATH.'/kcp/pp_ax_hub_cancel.php';
                break;
        }
    }

    // 관리자에게 오류 알림 메일발송
    $error = 'status';
    include G5_SHOP_PATH.'/ordererrormail.php';

    // 주문삭제
    sql_query(" delete from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");

    die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>'.strtoupper($od_pg).'를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
}

// 회원이면서 포인트를 사용했다면 포인트 테이블에 사용을 추가
if ($is_member && $od_receipt_point)
    insert_point($member['mb_id'], (-1) * $od_receipt_point, "주문번호 $od_id 결제", "@order", $od_id, "결제");

$od_memo = nl2br(htmlspecialchars2(stripslashes($od_memo))) . "&nbsp;";

// 쿠폰사용내역기록
if($is_member) {
    $it_cp_cnt = count($_POST['cp_id']);
    for($i=0; $i<$it_cp_cnt; $i++) {
        $cid = $_POST['cp_id'][$i];
        $cp_it_id = $_POST['it_id'][$i];
        $cp_prc = (int)$arr_it_cp_prc[$cp_it_id];

        if(trim($cid)) {
            $sql = " insert into {$g5['g5_shop_coupon_log_table']}
                        set cp_id       = '$cid',
                            mb_id       = '{$member['mb_id']}',
                            od_id       = '$od_id',
                            cp_price    = '$cp_prc',
                            cl_datetime = '".G5_TIME_YMDHIS."' ";
            sql_query($sql);
        }

        // 쿠폰사용금액 cart에 기록
        $cp_prc = (int)$arr_it_cp_prc[$cp_it_id];
        $sql = " update {$g5['g5_shop_cart_table']}
                    set cp_price = '$cp_prc'
                    where od_id = '$od_id'
                      and it_id = '$cp_it_id'
                      and ct_select = '1'
                    order by ct_id asc
                    limit 1 ";
        sql_query($sql);
    }

    if($_POST['od_cp_id']) {
        $sql = " insert into {$g5['g5_shop_coupon_log_table']}
                    set cp_id       = '{$_POST['od_cp_id']}',
                        mb_id       = '{$member['mb_id']}',
                        od_id       = '$od_id',
                        cp_price    = '$tot_od_cp_price',
                        cl_datetime = '".G5_TIME_YMDHIS."' ";
        sql_query($sql);
    }

    if($_POST['sc_cp_id']) {
        $sql = " insert into {$g5['g5_shop_coupon_log_table']}
                    set cp_id       = '{$_POST['sc_cp_id']}',
                        mb_id       = '{$member['mb_id']}',
                        od_id       = '$od_id',
                        cp_price    = '$tot_sc_cp_price',
                        cl_datetime = '".G5_TIME_YMDHIS."' ";
        sql_query($sql);
    }
}


include_once(G5_SHOP_PATH.'/ordermail1.inc.php');
include_once(G5_SHOP_PATH.'/ordermail2.inc.php');

// SMS BEGIN --------------------------------------------------------
// 주문고객과 쇼핑몰관리자에게 SMS 전송
if($config['cf_sms_use'] && ($default['de_sms_use2'] || $default['de_sms_use3'])) {
    $is_sms_send = false;

    // 충전식일 경우 잔액이 있는지 체크
    if($config['cf_icode_id'] && $config['cf_icode_pw']) {
        $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);

        if($userinfo['code'] == 0) {
            if($userinfo['payment'] == 'C') { // 정액제
                $is_sms_send = true;
            } else {
                $minimum_coin = 100;
                if(defined('G5_ICODE_COIN'))
                    $minimum_coin = intval(G5_ICODE_COIN);

                if((int)$userinfo['coin'] >= $minimum_coin)
                    $is_sms_send = true;
            }
        }
    }

    if($is_sms_send) {
        $sms_contents = array($default['de_sms_cont2'], $default['de_sms_cont3']);
        $recv_numbers = array($od_hp, $default['de_sms_hp']);
        $send_numbers = array($default['de_admin_company_tel'], $default['de_admin_company_tel']);

        $sms_count = 0;
        $sms_messages = array();

        for($s=0; $s<count($sms_contents); $s++) {
            $sms_content = $sms_contents[$s];
            $recv_number = preg_replace("/[^0-9]/", "", $recv_numbers[$s]);
            $send_number = preg_replace("/[^0-9]/", "", $send_numbers[$s]);

            $sms_content = str_replace("{이름}", $od_name, $sms_content);
            $sms_content = str_replace("{보낸분}", $od_name, $sms_content);
            $sms_content = str_replace("{받는분}", $od_b_name, $sms_content);
            $sms_content = str_replace("{주문번호}", $od_id, $sms_content);
            $sms_content = str_replace("{주문금액}", number_format($tot_ct_price + $od_send_cost + $od_send_cost2), $sms_content);
            $sms_content = str_replace("{회원아이디}", $member['mb_id'], $sms_content);
            $sms_content = str_replace("{회사명}", $default['de_admin_company_name'], $sms_content);

            $idx = 'de_sms_use'.($s + 2);

            if($default[$idx] && $recv_number) {
                $sms_messages[] = array('recv' => $recv_number, 'send' => $send_number, 'cont' => $sms_content);
                $sms_count++;
            }
        }

        // 무통장 결제완료 때 고객에게 계좌정보 보냄
        if($od_settle_case == '무통장' && $default['de_sms_use2'] && $od_misu > 0) {
            $sms_content = $od_name."님의 입금계좌입니다.\n금액:".number_format($od_misu)."원\n계좌:".$od_bank_account."\n".$default['de_admin_company_name'];

            $recv_number = preg_replace("/[^0-9]/", "", $od_hp);
            $send_number = preg_replace("/[^0-9]/", "", $default['de_admin_company_tel']);

            $sms_messages[] = array('recv' => $recv_number, 'send' => $send_number, 'cont' => $sms_content);
            $sms_count++;
        }

        // SMS 전송
        if($sms_count > 0) {
            if($config['cf_sms_type'] == 'LMS') {
                include_once(G5_LIB_PATH.'/icode.lms.lib.php');

                $port_setting = get_icode_port_type($config['cf_icode_id'], $config['cf_icode_pw']);

                // SMS 모듈 클래스 생성
                if($port_setting !== false) {
                    $SMS = new LMS;
                    $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $port_setting);

                    for($s=0; $s<count($sms_messages); $s++) {
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

                for($s=0; $s<count($sms_messages); $s++) {
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
$uid = md5($od_id.G5_TIME_YMDHIS.$REMOTE_ADDR);
set_session('ss_orderview_uid', $uid);

// 주문 정보 임시 데이터 삭제
$sql = " delete from {$g5['g5_shop_order_data_table']} where od_id = '$od_id' and dt_pg = '$od_pg' ";
sql_query($sql);

if( $od_pg == 'inicis' && $od_tno ){
    $sql = "delete from {$g5['g5_shop_inicis_log_table']} where oid = '$od_id' and P_TID = '$od_tno' ";
    sql_query($sql, false);
}


// 주문번호제거
set_session('ss_order_id', '');

// 기존자료 세션에서 제거
if (get_session('ss_direct'))
    set_session('ss_cart_direct', '');

// 배송지처리
if($is_member) {
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
    if($ad_default) {
        $sql = " update {$g5['g5_shop_order_address_table']}
                    set ad_default = '0'
                    where mb_id = '{$member['mb_id']}' ";
        sql_query($sql);
    }

    $ad_subject = clean_xss_tags($ad_subject);

    if($row['ad_id']){
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

$is_noti_pay = isset($is_noti_pay) ? $is_noti_pay : false;

if( $is_noti_pay ){
    $order_id = $od_id;
    return;
}

goto_url(G5_SHOP_URL.'/orderinquiryview.php?od_id='.$od_id.'&amp;uid='.$uid);
?>
