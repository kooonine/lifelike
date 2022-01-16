<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

if ($default['de_pg_service'] == 'lg' && !$_POST['LGD_BILLKEY'])
    alert('결제등록 요청 후 주문해 주십시오.');

// 장바구니가 비어있는가?
if (get_session("ss_direct"))
    $tmp_cart_id = get_session('ss_cart_direct');
else
    $tmp_cart_id = get_session('ss_cart_id');

if (get_cart_count($tmp_cart_id) == 0) // 장바구니에 담기
    alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', G5_SHOP_URL . '/cart.php');

$error = "";
// 장바구니 상품 재고 검사
$sql = " select it_id,
                ct_qty,
                it_name,
                io_id,
                io_type,
                ct_option
           from {$g5['g5_shop_cart_table']}
          where od_id = '$tmp_cart_id'
            and ct_select = '1' ";

$result = sql_query($sql);
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    // 상품에 대한 현재고수량
    if ($row['io_id']) {
        $it_stock_qty = (int) get_option_stock_qty($row['it_id'], $row['io_id'], $row['io_type']);
    } else {
        $it_stock_qty = (int) get_it_stock_qty($row['it_id']);
    }
    // 장바구니 수량이 재고수량보다 많다면 오류
    if ($row['ct_qty'] > $it_stock_qty)
        $error .= "{$row['ct_option']} 의 재고수량이 부족합니다. 현재고수량 : $it_stock_qty 개\\n\\n";
}

if ($i == 0)
    alert('장바구니가 비어 있습니다.\\n\\n이미 주문하셨거나 장바구니에 담긴 상품이 없는 경우입니다.', G5_SHOP_URL . '/cart.php');

if ($error != "") {
    $error .= "다른 고객님께서 {$od_name}님 보다 먼저 주문하신 경우입니다. 불편을 끼쳐 죄송합니다.";
    alert($error);
}

$i_price     = (int) $_POST['od_price'];
$i_temp_point = (int) $_POST['od_temp_point'];

// 주문금액이 상이함
$sql = " select SUM((ct_rental_price + io_price) * ct_qty) as od_price,
                SUM((ct_rental_price + io_price) * ct_qty * ct_item_rental_month) as od_misu,                
                COUNT(distinct it_id) as cart_count,
                max(ct_item_rental_month) as rt_rental_month
            from {$g5['g5_shop_cart_table']} where od_id = '$tmp_cart_id' and ct_select = '1' ";
$row = sql_fetch($sql);

$rt_rental_month = $row['rt_rental_month'];
$rt_rental_price = $row['od_price']; //월 리스료
$cart_count = $row['cart_count']; //제품수
$od_misu = $row['od_misu']; //리스금액 * 리스개월수 = 총 납부할 금액
$cart_price = $od_misu; //총 주문금액 = = 총 납부할 금액

$od_status = '주문';
$od_tno    = '';
$rt_payment_count = 0;

// 190818 리스 1회차 출금시점 변경 : 구매시 -> 출고시 - balance@panpacific.co.kr
/*
 include G5_SHOP_PATH.'/lg2/xpay_result.php';

$tno             = $xpay->Response('LGD_TID',0);        // 주문 트랜잭션 번호
$amount          = $xpay->Response('LGD_AMOUNT',0);
$app_time        = $xpay->Response('LGD_PAYDATE',0);
$bank_name       = $xpay->Response('LGD_FINANCENAME',0);
$depositor       = $xpay->Response('LGD_PAYER',0);
$account         = $xpay->Response('LGD_FINANCENAME',0).' '.$xpay->Response('LGD_ACCOUNTNUM',0).' '.$xpay->Response('LGD_SAOWNER',0);
$commid          = $xpay->Response('LGD_FINANCENAME',0);
$mobile_no       = $xpay->Response('LGD_TELNO',0);
$app_no          = $xpay->Response('LGD_FINANCEAUTHNUM',0);  // 승인번호
$card_name       = $xpay->Response('LGD_FINANCENAME',0);
$pay_type        = $xpay->Response('LGD_PAYTYPE',0);
$escw_yn         = $xpay->Response('LGD_ESCROWYN',0);
$card_num        = $xpay->Response('LGD_CARDNUM',0);
*/

$amount             = $_POST['LGD_AMOUNT'];
$app_time           = $_POST['LGD_PAYDATE'];
$card_name          = $_POST['LGD_FINANCENAME'];
$pay_type           = $_POST['LGD_PAYTYPE'];
$rt_lgd_billkey        = $_POST['LGD_BILLKEY'];        //추후 빌링시 카드번호 대신 입력할 값입니다. 

$od_tno             = '';
$od_app_no          = '';
$od_receipt_point   = $i_temp_point;
$od_receipt_time    = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
// $od_bank_account    = $card_name."/".$card_num;
$od_bank_account    = $card_name;
$pg_price           = (int) $amount;
$od_receipt_price   = (int) $amount;
$od_status      = '계약등록';

$od_pg = "lg";

if ($tno && $default['de_card_test']) {
    //정상카드 확인을 위한 Test결제 성공 => 환불처리 진행 X
    //최소 계약등록시 첫달 리스요금 결제, 테스트 결제 시 환불처리
    $cancel_msg = '테스트 결제 내역 환불처리 ';
    $xpay->Rollback($cancel_msg . " [TID:" . $xpay->Response("LGD_TID", 0) . ",MID:" . $xpay->Response("LGD_MID", 0) . ",OID:" . $xpay->Response("LGD_OID", 0) . "]");
    if ("0000" == $xpay->Response_Code()) {
        //$pg_price           = 0; //결제된 금액은 없음
        //$od_receipt_price   = 0; //결제된 금액은 없음
        //echo "자동취소가 정상적으로 완료 되었습니다.<br>";
    } else {
        echo "카드 등록이 정상적으로 처리되지 않았습니다.<br>";
        die("Receipt Amount Error");
    }
}


$od_pwd = $member['mb_password'];

// 주문번호를 얻는다.
$od_id = get_session('ss_order_id');

//서명파일 저장
$cust_file_data = $_POST['cust_file'];
$cust_file_data = str_replace('data:image/png;base64,', '', $cust_file_data);
$cust_file_data = str_replace(' ', '+', $cust_file_data);
$fileData = base64_decode($cust_file_data);

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir(G5_DATA_PATH . '/file/order', G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH . '/file/order', G5_DIR_PERMISSION);
@mkdir(G5_DATA_PATH . '/file/order/' . $od_id, G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH . '/file/order/' . $od_id, G5_DIR_PERMISSION);

$chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

$upload = array();

shuffle($chars_array);
$shuffle = implode('', $chars_array);
$fileName = 'signature.png';

$upload[0]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($filename);
$dest_file = G5_DATA_PATH . '/file/order/' . $od_id . '/' . $upload[0]['file'];
file_put_contents($dest_file, $fileData);

// 올라간 파일의 퍼미션을 변경합니다.
chmod($dest_file, G5_FILE_PERMISSION);
$upload[0]['filesize'] = filesize($dest_file);

$cust_file = "";
if (count($upload) > 0) {
    $cust_file = json_encode_raw($upload, JSON_UNESCAPED_UNICODE);
}

$od_escrow = 0;

// 복합과세 금액
$od_tax_mny = round($i_price / 1.1);
$od_vat_mny = $i_price - $od_tax_mny;
$od_free_mny = 0;

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
$od_b_zip   = preg_replace('/[^0-9]/', '', $od_b_zip);
$od_b_zip1  = substr($od_b_zip, 0, 3);
$od_b_zip2  = substr($od_b_zip, 3);
$od_b_addr1       = clean_xss_tags($od_b_addr1);
$od_b_addr2       = clean_xss_tags($od_b_addr2);
$od_b_addr3       = clean_xss_tags($od_b_addr3);
$od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';
$od_memo          = clean_xss_tags($od_memo);
$od_deposit_name  = clean_xss_tags($od_deposit_name);
$od_tax_flag      = $default['de_tax_flag_use'];

$rt_billday = substr(preg_replace('/[^0-9]/', '', G5_TIME_YMD), 6); //신청일에서 날짜만 구하기.
if ((int) $rt_billday >= 29) $rt_billday = '28'; //29,30,31일 결제의 경우 28일을 정기결제일로 지정


// 계약서 등록
$sql = " insert {$g5['g5_shop_rental_table']}
            set rt_id             = '$od_id',
                rt_type           = '$od_type',
                mb_id             = '{$member['mb_id']}',
                rt_pwd            = '$od_pwd',
                rt_name           = '$od_name',
                rt_email          = '$od_email',
                rt_tel            = '$od_tel',
                rt_hp             = '$od_hp',
                rt_zip1           = '$od_zip1',
                rt_zip2           = '$od_zip2',
                rt_addr1          = '$od_addr1',
                rt_addr2          = '$od_addr2',
                rt_addr3          = '$od_addr3',
                rt_addr_jibeon    = '$od_addr_jibeon',
                rt_b_name         = '$od_b_name',
                rt_b_tel          = '$od_b_tel',
                rt_b_hp           = '$od_b_hp',
                rt_b_zip1         = '$od_b_zip1',
                rt_b_zip2         = '$od_b_zip2',
                rt_b_addr1        = '$od_b_addr1',
                rt_b_addr2        = '$od_b_addr2',
                rt_b_addr3        = '$od_b_addr3',
                rt_b_addr_jibeon  = '$od_b_addr_jibeon',
                rt_deposit_name   = '$od_deposit_name',
                rt_memo           = '$od_memo',
                rt_cart_count     = '$cart_count',
                rt_cart_price     = '$cart_price',
                rt_cart_coupon    = '$tot_it_cp_price',
                rt_send_cost      = '$od_send_cost',
                rt_send_coupon    = '$tot_sc_cp_price',
                rt_send_cost2     = '$od_send_cost2',
                rt_coupon         = '$tot_od_cp_price',
                rt_receipt_price  = '$od_receipt_price',
                rt_receipt_point  = '$od_receipt_point',
                rt_bank_account   = '$od_bank_account',
                rt_receipt_time   = '$od_receipt_time',
                rt_misu           = '$od_misu',
                rt_pg             = '$od_pg',
                rt_tno            = '$od_tno',
                rt_app_no         = '$od_app_no',
                rt_escrow         = '$od_escrow',
                rt_tax_flag       = '$od_tax_flag',
                rt_tax_mny        = '$od_tax_mny',
                rt_vat_mny        = '$od_vat_mny',
                rt_free_mny       = '$od_free_mny',
                rt_status         = '$od_status',
                rt_shop_memo      = '',
                rt_hope_date      = '$od_hope_date',
                rt_time           = '" . G5_TIME_YMDHIS . "',
                rt_ip             = '$REMOTE_ADDR',
                rt_settle_case    = '$od_settle_case',
                rt_test           = '0',
                rt_payment_count  = '$rt_payment_count',
                rt_payment_status = '계약등록',
                rt_month          = '$rt_rental_month',
                rt_rental_price   = '$rt_rental_price',
                rt_lgd_billkey    = '$rt_lgd_billkey',
                rt_billday        = '$rt_billday',
                cust_file         = '$cust_file'
                ";
$result = sql_query($sql, false);
// 계약서 등록 오류시 결제 취소
if (!$result) {
    if ($tno) {
        $cancel_msg = '주문정보 입력 오류';
        include G5_SHOP_PATH . '/lg2/xpay_cancel.php';
    }

    // 관리자에게 오류 알림 메일발송
    $error = 'order';
    include G5_SHOP_PATH . '/ordererrormail.php';

    die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>' . strtoupper($od_pg) . '를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
}

// MOVE TO LINE 468
// 주문서에 입력
/*
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
                od_cart_price     = '$cart_price',
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
                od_time           = '" . G5_TIME_YMDHIS . "',
                od_ip             = '$REMOTE_ADDR',
                od_settle_case    = '$od_settle_case',
                od_test           = '0',
                rt_payment_count  = '$rt_payment_count',
                rt_payment_status = '정상납부',
                rt_month          = '$rt_rental_month',
                rt_rental_price   = '$rt_rental_price',
                rt_lgd_billkey    = '$rt_lgd_billkey',
                rt_billday        = '$rt_billday',
                cust_file         = '$cust_file'
                ";
$result = sql_query($sql, false);

// 주문정보 입력 오류시 결제 취소
if (!$result) {
    if ($tno) {
        $cancel_msg = '주문정보 입력 오류';
        include G5_SHOP_PATH . '/lg2/xpay_cancel.php';
    }

    // 관리자에게 오류 알림 메일발송
    $error = 'order';
    include G5_SHOP_PATH . '/ordererrormail.php';

    die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>' . strtoupper($od_pg) . '를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
}
*/

// 결제 정보에 입력
// 190818 리스 1회차 출금시점 변경 : 구매시 -> 출고시 - balance@panpacific.co.kr
/*
$sql = " insert lt_shop_order_add_receipt
                set od_id             = '$od_id'
                  ,od_type           = '$od_type'
                  ,mb_id             = '{$member['mb_id']}'
                  ,od_send_cost = 0
                  ,od_receipt_price = '$od_receipt_price'
                  ,od_cancel_price = 0
                  ,od_receipt_point = 0
                  ,od_refund_price = 0
                  ,od_bank_account = '$od_bank_account'
                  ,od_deposit_name = ''
                  ,od_receipt_time = '$od_receipt_time'
                  ,od_misu = 0
                  ,od_pg_id = 'lg2'
                  ,od_settle_case = '$od_settle_case'
                  ,od_mobile = 0
                  ,od_pg = '$od_pg'
                  ,od_tno = '$od_tno'
                  ,od_app_no = '$od_app_no'
                  ,od_escrow = '$od_escrow'
                  ,od_tax_flag = '$od_tax_flag'
                  ,od_tax_mny = '$od_tax_mny'
                  ,od_vat_mny = '$od_vat_mny'
                  ,od_free_mny = '$od_free_mny'
                  ,od_ip = '$REMOTE_ADDR'
                  ,od_mb_id = '{$member['mb_id']}'
                  ,od_receipt_rental_month = '1'
                  ,od_receipt_type = 'rental'
                  ,od_test = '0'
                    ";
$result = sql_query($sql, false);
*/

$od_memo = nl2br(htmlspecialchars2(stripslashes($od_memo))) . "&nbsp;";

// 주문서 생성 & 주문 상품 생성 - 190910 balance@panpacific.co.kr
//주문상품 상세 정보 저장 => lt_shop_order_item (수량별 1개씩 데이타가 생성됨. RFID, 세탁,보관,수선 추적을 위함)
$sql = " select   a.ct_id, a.od_type, a.od_id, a.mb_id
                , a.it_id,a.it_name,a.ct_status,a.ct_price,a.ct_rental_price,a.ct_option,a.ct_qty
                , a.io_id,a.io_type,a.io_price,a.ct_time,a.ct_receipt_price,a.ct_status_claim
                , b.its_sap_code, b.its_order_no, a.its_no
                , c.io_sapcode_color_gz,c.io_order_no,c.io_color_name,c.io_hoching,c.io_sap_price
                , b.its_free_laundry, b.its_free_laundry_delivery_price, b.its_laundry_use, b.its_laundry_price, b.its_laundry_delivery_price
                , b.its_laundrykeep_use, b.its_laundrykeep_lprice, b.its_laundrykeep_kprice, b.its_laundrykeep_delivery_price
                , b.its_repair_use, b.its_repair_price, b.its_repair_delivery_price
                , b.its_zbox_name, b.its_zbox_price
         from lt_shop_cart as a
              inner join lt_shop_item_option as c
        		  on a.it_id = c.it_id and a.io_id = c.io_id and a.its_no = c.its_no and a.io_sapcode_color_gz = c.io_sapcode_color_gz
              inner join lt_shop_item_sub as b
                  on a.its_no = b.its_no
          where a.od_id = '$tmp_cart_id'
            and a.ct_select = '1' 
          order by a.it_id, a.ct_id";

$od_sub_result = sql_query($sql);
$comma = '';
$od_ids = array();
$sql_shop_order_item = " INSERT INTO {$g5['g5_shop_order_item_table']}
                ( od_sub_id,  od_id, ct_id, od_type, mb_id
                    , it_id, it_name, ct_status, ct_price, ct_option
                    , io_id, io_type, io_price, ct_time, ct_receipt_price, ct_status_claim
                    , its_sap_code, its_order_no, its_no
                    , io_sapcode_color_gz,io_order_no,io_color_name,io_hoching,io_sap_price,ct_rental_price,ct_item_rental_month
                    , ct_free_laundry, ct_free_laundry_delivery_price
                    , ct_laundry_use, ct_laundry_price, ct_laundry_delivery_price
                    , ct_laundrykeep_use, ct_laundrykeep_lprice, ct_laundrykeep_kprice, ct_laundrykeep_delivery_price
                    , ct_repair_use, ct_repair_price, ct_repair_delivery_price
                    , ct_zbox_name, ct_zbox_price
                  )
      VALUES  ";

$list_items = array();
for ($i = 0; $row = sql_fetch_array($od_sub_result); $i++) {
    // 주문서 생성
    $new_od_id = get_shop_uniqid();
    $od_ids[] = $new_od_id;
    $sql_insert_shop_order = " insert {$g5['g5_shop_order_table']}
            set od_id             = '$new_od_id',
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
                od_cart_price     = '$cart_price',
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
                od_time           = '" . G5_TIME_YMDHIS . "',
                od_ip             = '$REMOTE_ADDR',
                od_settle_case    = '$od_settle_case',
                od_test           = '0',
                rt_payment_count  = '$rt_payment_count',
                rt_payment_status = '계약등록',
                rt_month          = '$rt_rental_month',
                rt_rental_price   = '$rt_rental_price',
                rt_lgd_billkey    = '$rt_lgd_billkey',
                rt_billday        = '$rt_billday',
                cust_file         = '$cust_file'
                ";
    $result = sql_query($sql_insert_shop_order, false);

    // 주문정보 입력 오류시 결제 취소
    if (!$result) {
        if ($tno) {
            $cancel_msg = '주문상태 변경 오류';
            include G5_SHOP_PATH . '/lg/xpay_cancel.php';
        }

        // 관리자에게 오류 알림 메일발송
        $error = 'status';
        include G5_SHOP_PATH . '/ordererrormail.php';

        // 주문삭제
        sql_query(" delete from {$g5['g5_shop_order_table']} where od_id = '$new_od_id' ");

        die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>' . strtoupper($od_pg) . '를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
    }

    $od_sub_id = 1;
    for ($j = 0; $j < (int) $row['ct_qty']; $j++) {
        $ct_receipt_price = (int) $row['ct_receipt_price'] / (int) $row['ct_qty'];
        $sql_shop_order_item .= $comma . "( right(concat('0000','$od_sub_id'),4), '{$new_od_id}', '{$row['ct_id']}', '{$row['od_type']}', '{$row['mb_id']}'
                      , '{$row['it_id']}', '{$row['it_name']}', '{$row['ct_status']}', '{$row['ct_price']}', '{$row['ct_option']}'
                      , '{$row['io_id']}', '{$row['io_type']}', '{$row['io_price']}', '{$row['ct_time']}', '{$ct_receipt_price}', '{$row['ct_status_claim']}'
                      , '{$row['its_sap_code']}',  '{$row['its_order_no']}',  '{$row['its_no']}'
                      , '{$row['io_sapcode_color_gz']}', '{$row['io_order_no']}', '{$row['io_color_name']}', '{$row['io_hoching']}', '{$row['io_sap_price']}', '{$row['ct_rental_price']}', '{$row['ct_item_rental_month']}'
                      , '{$row['its_free_laundry']}',  '{$row['its_free_laundry_delivery_price']}'
                      , '{$row['its_laundry_use']}',  '{$row['its_laundry_price']}',  '{$row['its_laundry_delivery_price']}'
                      , '{$row['its_laundrykeep_use']}',  '{$row['its_laundrykeep_lprice']}',  '{$row['its_laundrykeep_kprice']}',  '{$row['its_laundrykeep_delivery_price']}'
                      , '{$row['its_repair_use']}',  '{$row['its_repair_price']}',  '{$row['its_repair_delivery_price']}'
                      , '{$row['its_zbox_name']}',  '{$row['its_zbox_price']}'
                )";
        $comma = ',';
        $od_sub_id++;
    }
    // 장바구니 상태변경
    $cart_status = $od_status;

    $sql = "update {$g5['g5_shop_cart_table']}
            set od_id = '$new_od_id',
                od_type = '$od_type',
                ct_receipt_price = 0, 
                ct_status = '$cart_status'
            where od_id = '$tmp_cart_id'
                and ct_select = '1'
                order by it_id, ct_id
                limit 1";
    $result = sql_query($sql, false);
}

$result = sql_query($sql_shop_order_item);
// 주문정보 입력 오류시 결제 취소
if (!$result) {
    if ($tno) {
        $cancel_msg = '주문상태 변경 오류';
        include G5_SHOP_PATH . '/lg/xpay_cancel.php';
    }

    // 관리자에게 오류 알림 메일발송
    $error = 'status';
    include G5_SHOP_PATH . '/ordererrormail.php';

    // 주문삭제
    sql_query(" delete from {$g5['g5_shop_order_table']} where od_id = '$od_id' ");

    die('<p>고객님의 주문 정보를 처리하는 중 오류가 발생해서 주문이 완료되지 않았습니다.</p><p>' . strtoupper($od_pg) . '를 이용한 전자결제(신용카드, 계좌이체, 가상계좌 등)은 자동 취소되었습니다.');
}

foreach ($od_ids as $oi) {
    $sql_insert_rental_order = "INSERT INTO {$g5['g5_shop_rental_order_table']} SET rt_id='{$od_id}', od_id='{$oi}'";
    $result = sql_query($sql_insert_rental_order);
}

//삼진 1회차 입금처리
//SM_ADD_RENTAL_IBKEUM($od_id);

// 주문확인 메세지 발송
$rt_id = $od_id;
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

// 네이버 분석스크립트 삽입
echo '
<script type="text/javascript" src="https://wcs.naver.net/wcslog.js"></script> 
<script type="text/javascript"> 
	var _nasa={};
	_nasa["cnv"] = wcs.cnv("1","' . $od_receipt_price . '"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
	if (!wcs_add) var wcs_add={};
	wcs_add["wa"] = "s_3a59b688a58f";
	if (!_nasa) var _nasa={};
	wcs.inflow();
	wcs_do(_nasa);
</script>
';

// goto_url(G5_SHOP_URL . '/orderinquiryview.php?od_id=' . $new_od_id . '&amp;uid=' . $uid);
// 주문결과 마이페이지로 이동으로 변경(임시) - 190930 balance@panpacific.co.kr
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