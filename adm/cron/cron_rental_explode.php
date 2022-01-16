<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/mailer.lib.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

function print_result($od_id, $msg)
{
    $msg = sprintf("%s : %s<br />\n", $od_id, $msg);
    echo $msg;

    return;
}

// $sql_old_order = "SELECT o.*, (SELECT rt_id FROM {$g5['g5_shop_rental_order_table']} WHERE od_id=o.od_id) as rt_id FROM {$g5['g5_shop_order_table']} o HAVING rt_id IS NULL";
$sql_old_order = "SELECT o.*, ro.rt_id FROM {$g5['g5_shop_order_table']} AS o LEFT JOIN {$g5['g5_shop_rental_order_table']} AS ro ON o.od_id=ro.od_id WHERE od_type='R' AND ro.rt_id IS NULL";
$db_old_order = sql_query($sql_old_order);
$outmsg = "";

while ($old_order = sql_fetch_array($db_old_order)) {
    if (get_cart_count($old_order['od_id']) == 0) {
        print_result($old_order['od_id'], "empty lt_shop_cart");
        continue;
    }


    $error = "";
    // cart 조회
    $sql = " select it_id,
                ct_qty,
                it_name,
                io_id,
                io_type,
                ct_option
           from {$g5['g5_shop_cart_table']}
          where od_id = '{$old_order['od_id']}'
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

    if ($error != "") {
        print_result($old_order['od_id'], $error);
        continue;
    }

    $i_price     = (int) $old_order['od_price'];
    $i_temp_point = (int) $old_order['od_temp_point'];

    // 주문금액이 상이함
    $sql = " select SUM((ct_rental_price + io_price) * ct_qty) as od_price,
                SUM((ct_rental_price + io_price) * ct_qty * ct_item_rental_month) as od_misu,                
                COUNT(distinct it_id) as cart_count,
                max(ct_item_rental_month) as rt_rental_month
            from {$g5['g5_shop_cart_table']} where od_id = '{$old_order['od_id']}' and ct_select = '1' ";
    $row = sql_fetch($sql);

    $od_misu            = $row['od_misu']; //리스금액 * 리스개월수 = 총 납부할 금액
    $cart_count         = $row['cart_count']; //제품수
    $cart_price         = $od_misu; //총 주문금액 = = 총 납부할 금액

    $rt_rental_month    = $row['rt_rental_month'];
    $rt_rental_price    = $row['od_price']; //월 리스료
    $rt_payment_count   = 0;
    $rt_lgd_billkey     = $old_order['rt_lgd_billkey'];        //추후 빌링시 카드번호 대신 입력할 값입니다. 
    $rt_billday         = $old_order['rt_billday'];

    $od_tno             = $old_order['od_tno'];
    $od_app_no          = $old_order['od_app_no'];
    $od_receipt_point   = $old_order['od_receipt_point'];
    $od_receipt_time    = $old_order['od_receipt_time'];
    $od_bank_account    = $old_order['od_bank_account'];
    $od_receipt_price   = $old_order['od_receipt_price'];
    $od_status          = $old_order['od_status'];
    $od_pg              = "lg";
    $od_pwd             = $old_order['od_pwd'];
    $od_type            = 'R';
    $od_settle_case     = $old_order['od_settle_case'];

    // 주문번호를 얻는다.
    $od_id = get_shop_uniqid();
    $od_escrow = 0;

    // 복합과세 금액
    $od_tax_mny = round($od_receipt_price / 1.1);
    $od_vat_mny = $od_receipt_price - $od_tax_mny;
    $od_free_mny = 0;

    $od_email         = $old_order['od_email'];
    $od_name          = $old_order['od_name'];
    $od_tel           = $old_order['od_tel'];
    $od_hp            = $old_order['od_hp'];
    $od_zip           = $old_order['od_zip'];
    $od_zip1          = $old_order['od_zip1'];
    $od_zip2          = $old_order['od_zip2'];
    $od_addr1         = $old_order['od_addr1'];
    $od_addr2         = $old_order['od_addr2'];
    $od_addr3         = $old_order['od_addr3'];
    $od_addr_jibeon   = $old_order['od_addr_jibeon'];
    $od_b_name        = $old_order['od_b_name'];
    $od_b_tel         = $old_order['od_b_tel'];
    $od_b_hp          = $old_order['od_b_hp'];
    $od_b_zip         = $old_order['od_b_zip'];
    $od_b_zip1        = $old_order['od_b_zip1'];
    $od_b_zip2        = $old_order['od_b_zip2'];
    $od_b_addr1       = $old_order['od_b_addr1'];
    $od_b_addr2       = $old_order['od_b_addr2'];
    $od_b_addr3       = $old_order['od_b_addr3'];
    $od_b_addr_jibeon = $old_order['od_b_addr_jibeon'];
    $od_memo          = $old_order['od_memo'];
    $od_deposit_name  = $old_order['od_deposit_name'];
    $od_tax_flag      = $old_order['od_tax_flag'];

    // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
    @mkdir(G5_DATA_PATH . '/file/order', G5_DIR_PERMISSION);
    @chmod(G5_DATA_PATH . '/file/order', G5_DIR_PERMISSION);
    @mkdir(G5_DATA_PATH . '/file/order/' . $od_id, G5_DIR_PERMISSION);
    @chmod(G5_DATA_PATH . '/file/order/' . $od_id, G5_DIR_PERMISSION);

    // 기존계약서 서명파일 이동
    $old_cust_file = json_decode($old_order['cust_file']);
    $ctFileName = $old_cust_file[0]->file;
    @copy(G5_DATA_PATH . '/file/order/' . $old_order['od_id'] . '/' . $ctFileName, G5_DATA_PATH . '/file/order/' . $od_id . '/' . $ctFileName);

    // 계약서 등록
    $sql = " insert {$g5['g5_shop_rental_table']}
            set rt_id             = '$od_id',
                rt_type           = '$od_type',
                mb_id             = '{$old_order['mb_id']}',
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
                rt_shop_memo      = '{$old_order['od_shop_memo']}',
                rt_hope_date      = '{$old_order['od_hope_date']}',
                rt_time           = '{$old_order['od_time']}',
                rt_ip             = '{$old_order['od_ip']}',
                rt_settle_case    = '$od_settle_case',
                rt_test           = '0',
                rt_payment_count  = '$rt_payment_count',
                rt_payment_status = '계약등록',
                rt_month          = '$rt_rental_month',
                rt_rental_price   = '$rt_rental_price',
                rt_lgd_billkey    = '$rt_lgd_billkey',
                rt_billday        = '{$old_order['rt_billday']}',
                cust_file         = '{$old_order['cust_file']}'
                ";

    $result = sql_query($sql, false);

    // 계약정보 처리확인
    if (!$result) {
        print_result($old_order['od_id'], "계약정보 처리오류(" . $sql . ")");
        continue;
    }

    // 주문서 생성 & 주문 상품 생성 - 190910 balance@panpacific.co.kr
    //주문상품 상세 정보 저장 => lt_shop_order_item (수량별 1개씩 데이타가 생성됨. RFID, 세탁,보관,수선 추적을 위함)
    $sql = " select a.ct_id, a.od_type, a.od_id, a.mb_id
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
        		  on a.it_id = c.it_id and a.io_id = c.io_id and a.its_no = c.its_no
              inner join lt_shop_item_sub as b
                  on a.its_no = b.its_no
          where a.od_id = '{$old_order['od_id']}'
            and a.ct_select = '1' 
          order by a.it_id, a.ct_id";

    $od_sub_result = sql_query($sql);
    $od_ids = array();
    $sql_shop_order_item = "INSERT INTO {$g5['g5_shop_order_item_table']}
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
                            ) VALUES ";
    $od_type = 'R';
    $comma = '';

    for ($i = 0; $row = sql_fetch_array($od_sub_result); $i++) {
        // 주문서 생성
        $new_od_id = get_shop_uniqid();
        $od_ids[] = $new_od_id;
        $sql_insert_shop_order = " INSERT INTO {$g5['g5_shop_order_table']}
            SET od_id             = '$new_od_id',
                od_type           = '$od_type',
                mb_id             = '{$old_order['mb_id']}',
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
                od_shop_memo      = '{$old_order['od_shop_memo']}',
                od_hope_date      = '{$old_order['od_hope_date']}',
                od_time           = '{$old_order['od_time']}',
                od_ip             = '{$old_order['od_ip']}',
                od_settle_case    = '$od_settle_case',
                od_test           = '0',
                rt_payment_count  = '$rt_payment_count',
                rt_payment_status = '계약등록',
                rt_month          = '$rt_rental_month',
                rt_rental_price   = '$rt_rental_price',
                rt_lgd_billkey    = '$rt_lgd_billkey',
                rt_billday        = '{$old_order['rt_billday']}',
                cust_file         = '{$old_order['cust_file']}'
                ";
        $result = sql_query($sql_insert_shop_order, false);

        // 주문정보 입력 오류시 결제 취소
        if (!$result) {
            print_result($old_order['od_id'], "주문정보 처리오류(" . $sql_insert_shop_order . ")");
            continue 2;
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
            where od_id = '{$old_order['od_id']}'
                and ct_select = '1'
                order by it_id, ct_id
                limit 1";
        $result = sql_query($sql, false);
    }

    // dd($sql_shop_order_item);
    $result = sql_query($sql_shop_order_item);
    // 주문정보 입력 오류시 결제 취소
    if (!$result) {
        print_result($old_order['od_id'], "주문정보 변경 오류(" . $sql . ")");
        continue;
    }

    foreach ($od_ids as $oi) {
        $sql_insert_rental_order = "INSERT INTO {$g5['g5_shop_rental_order_table']} SET rt_id='{$od_id}', od_id='{$oi}'";
        $result = sql_query($sql_insert_rental_order);
    }

    print_result($old_order['od_id'], "OK");

    // 기존 주문 제거
    $sql_delete_old_order = "DELETE FROM {$g5['g5_shop_order_table']} WHERE od_id={$old_order['od_id']}";
    sql_query($sql_delete_old_order);
}
