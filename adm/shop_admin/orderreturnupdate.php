<?php
$sub_menu = '40';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$auth_sub_menu = $auth[substr($sub_menu,0,2)];
if ($is_admin == "brand") $auth_sub_menu = $auth['92'];
auth_check($auth_sub_menu, 'w');

foreach ($_POST['ct_id'] as $ctId) {
    if (!$ctIdCart) $ctIdCart = $ctId;
    else $ctIdCart = $ctIdCart. ",".$ctId."";
}
if ($change_status == "반품승인") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);

    if (!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');

    // sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '반품수거중' where od_id = '$od_id' and ct_status = '반품요청' ");
    $cartSql = "UPDATE {$g5['g5_shop_cart_table']} SET ct_status='반품수거중' WHERE ct_id IN ($ctIdCart)";
    sql_query($cartSql);
    
    $od_b_name        = clean_xss_tags($od_b_name);
    $od_b_tel         = clean_xss_tags($od_b_tel);
    $od_b_hp          = clean_xss_tags($od_b_hp);
    $od_b_zip1  = substr($od_b_zip, 0, 3);
    $od_b_zip2  = substr($od_b_zip, 3);
    $od_b_addr1       = clean_xss_tags($od_b_addr1);
    $od_b_addr2       = clean_xss_tags($od_b_addr2);
    $od_b_addr3       = clean_xss_tags($od_b_addr3);
    $od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';

    // 반품승인
    $sql = " update {$g5['g5_shop_order_table']}
                    set od_status         = '반품수거중',
                        od_b_name         = '$od_b_name',
                        od_b_tel          = '$od_b_tel',
                        od_b_hp           = '$od_b_hp',
                        od_b_zip1         = '$od_b_zip1',
                        od_b_zip2         = '$od_b_zip2',
                        od_b_addr1        = '$od_b_addr1',
                        od_b_addr2        = '$od_b_addr2',
                        od_b_addr3        = '$od_b_addr3',
                        od_b_addr_jibeon  = '$od_b_addr_jibeon',
                        od_shop_memo = concat(od_shop_memo,\"\\n관리자 반품승인 - " . G5_TIME_YMDHIS . " \")
                    where od_id = '$od_id' ";
    sql_query($sql);

    $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '[반품승인] ', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','반품승인'); ";
    sql_query($sql);

    //CJ 반품 요청
    // $od_pickup_invoice = cj_oracle_insert_one($od_id, "반품");
    if ($od_pickup_invoice) {
        $sql = " update {$g5['g5_shop_order_table']} set od_pickup_delivery_company = 'CJ대한통운', od_pickup_invoice = '{$od_pickup_invoice}', od_pickup_invoice_time = '" . G5_TIME_YMDHIS . "' where od_id = '$od_id' ";
        sql_query($sql, true);
        $cartSql = "UPDATE {$g5['g5_shop_cart_table']} SET ct_pickup_delivery_company = 'CJ대한통운', ct_pickup_invoice = '{$od_pickup_invoice}', ct_pickup_invoice_time = '" . G5_TIME_YMDHIS . "' WHERE ct_id IN ($ctIdCart)";
        sql_query($cartSql, true);
    }
} else if ($change_status == "반품거부") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);

    if (!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');

    $ct_status = '배송완료';
    sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '반품요청' ");

    // 반품거부
    $sql = " update {$g5['g5_shop_order_table']}
                set od_status = '$ct_status',
                    od_status_claim   = '',
                    od_shop_memo = concat(od_shop_memo,\"\\n관리자 반품거부 -" . G5_TIME_YMDHIS . " (거부사유 : {$mod_memo})\")
                where od_id = '$od_id' ";
    sql_query($sql);

    $sql = " insert into lt_shop_order_history
                        (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                     values
                        ('$od_id', 1, '[반품거부] " . $mod_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','반품거부'); ";
    sql_query($sql);
} else if ($change_status == "반품철회") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);

    if (!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');

    $ct_status = '배송완료';
    sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status', ct_status_claim = '' where od_id = '$od_id' and ct_status = '반품요청' ");

    // 반품거부
    $sql = " update {$g5['g5_shop_order_table']}
                set od_status = '$ct_status',
                    od_status_claim   = '',
                    od_shop_memo = concat(od_shop_memo,\"\\n관리자 반품철회 -" . G5_TIME_YMDHIS . " (철회사유 : {$mod_memo})\")
                where od_id = '$od_id' ";
    sql_query($sql);

    $sql = " insert into lt_shop_order_history
                        (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                     values
                        ('$od_id', 1, '[반품철회] " . $mod_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','반품철회'); ";
    sql_query($sql);
} else if ($change_status == "반품완료") {
    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);

    if (!$od['od_id']) {
        alert_close('주문정보가 존재하지 않습니다.');
    }

    $ra = sql_fetch("select sum(od_receipt_price)-sum(od_refund_price) as LGD_REMAINAMOUNT from  lt_shop_order where main_od_id = '{$od['main_od_id']}';");

    //환불처리   
    if ($od['od_pg'] != 'lg') return;

    include_once(G5_SHOP_PATH . '/settle_lg.inc.php');

    $tax_mny = preg_replace('/[^0-9]/', '', $_POST['tax_mny']);

    /*
     * [결제 부분취소 요청 페이지]
     *
     * LG유플러스으로 부터 내려받은 거래번호(LGD_TID)를 가지고 취소 요청을 합니다.(파라미터 전달시 POST를 사용하세요)
     * (승인시 LG유플러스으로 부터 내려받은 PAYKEY와 혼동하지 마세요.)
     */

    $LGD_TID                      = $od['od_tno'];                                                  //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)
    $LGD_CANCELAMOUNT             = (int) $tax_mny;                                                //부분취소 금액
    $LGD_REMAINAMOUNT             = (int) $ra['LGD_REMAINAMOUNT'];   //취소전 남은금액

    $LGD_CANCELTAXFREEAMOUNT    = (int) $free_mny;                                               //면세대상 부분취소 금액 (과세/면세 혼용상점만 적용)
    $LGD_CANCELREASON             = $mod_memo;                                                    //취소사유
    $LGD_RFACCOUNTNUM           = $_POST['LGD_RFACCOUNTNUM'];                                     //환불계좌 번호(가상계좌 환불인경우만 필수)
    $LGD_RFBANKCODE             = $_POST['LGD_RFBANKCODE'];                                         //환불계좌 은행코드(가상계좌 환불인경우만 필수)
    $LGD_RFCUSTOMERNAME         = $_POST['LGD_RFCUSTOMERNAME'];                                 //환불계좌 예금주(가상계좌 환불인경우만 필수)
    $LGD_RFPHONE                = $_POST['LGD_RFPHONE'];                                         //요청자 연락처(가상계좌 환불인경우만 필수)

    $xpay = new XPay($configPath, $CST_PLATFORM);

    // Mert Key 설정
    $xpay->set_config_value('t' . $LGD_MID, $LGD_MERTKEY);
    $xpay->set_config_value($LGD_MID, $LGD_MERTKEY);

    $xpay->Init_TX($LGD_MID);

    $xpay->Set("LGD_TXNAME",                "PartialCancel");
    $xpay->Set("LGD_TID",                   $LGD_TID);
    $xpay->Set("LGD_CANCELAMOUNT",          $LGD_CANCELAMOUNT);
    $xpay->Set("LGD_REMAINAMOUNT",          $LGD_REMAINAMOUNT);
    $xpay->Set("LGD_CANCELTAXFREEAMOUNT",   $LGD_CANCELTAXFREEAMOUNT);
    $xpay->Set("LGD_CANCELREASON",          $LGD_CANCELREASON);
    $xpay->Set("LGD_RFACCOUNTNUM",          $LGD_RFACCOUNTNUM);
    $xpay->Set("LGD_RFBANKCODE",            $LGD_RFBANKCODE);
    $xpay->Set("LGD_RFCUSTOMERNAME",        $LGD_RFCUSTOMERNAME);
    $xpay->Set("LGD_RFPHONE",               $LGD_RFPHONE);
    $xpay->Set("LGD_REQREMAIN",             "0");
    $xpay->Set("LGD_ENCODING",              "UTF-8");

    /*
     * 1. 결제 부분취소 요청 결과처리
     *
     */
    if ($xpay->TX()) {
        //1)결제 부분취소결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        /*
         echo "결제 부분취소 요청이 완료되었습니다.  <br>";
         echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
         echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
         
         $keys = $xpay->Response_Names();
         foreach($keys as $name) {
         echo $name . " = " . $xpay->Response($name, 0) . "<br>";
         }
         echo "<p>";
         */

        if ('0000' == $xpay->Response_Code()) {

            $ct_status = '반품완료';
            $db_old_ctid_set = sql_fetch("SELECT GROUP_CONCAT(DISTINCT ct_id) AS ct_id_set FROM lt_shop_cart WHERE od_id='{$od_id}' AND ct_status='{$ct_status}'");
            $old_ctid_set = explode(',', $old_ctid_set['ct_id_set']);

            $ctRatPrice = sql_fetch(" SELECT sum(ct_cart_coupon_price) AS cartCouponPrice, sum(cp_price_ori) AS cpPrice FROM lt_shop_cart WHERE od_id='{$od_id}' AND ct_status='수거완료' ");
            $cartCouponPrice = $ctRatPrice['cartCouponPrice'];
            $couponPrice = $ctRatPrice['cpPrice'];
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '$ct_status' where od_id = '$od_id' and ct_status = '수거완료' ");

            $db_ctid_set = sql_fetch("SELECT GROUP_CONCAT(DISTINCT ct_id) AS ct_id_set FROM lt_shop_cart WHERE od_id='{$od_id}' AND ct_status='{$ct_status}'");
            $ctid_set = explode(',', $old_ctid_set['ct_id_set']);
            $returned = array();

            foreach ($ctid_set as $re_ct_id) {
                if (!in_array($re_ct_id, $old_ctid_set)) {
                    $returned[] = $re_ct_id;
                }
            }

            $ct_order = sql_fetch("select count(*) cnt from lt_shop_cart where od_id = '$od_id' and ct_status != '$ct_status' ");

            //부분반품일 경우 배송완료로 되돌림.
            if ((int) $ct_order['cnt'] > 0) $ct_status = '배송완료';

            // 환불금액기록
            $tno = $xpay->Response("LGD_TID", 0);
            $mod_mny = (int) $tax_mny + (int) $free_mny;
            if (!$couponPrice) $couponPrice = 0;
            if (!$cartCouponPrice) $cartCouponPrice = 0;
            // 반품완료
            $sql = " update {$g5['g5_shop_order_table']}
                    set od_refund_price = od_refund_price + '$mod_mny',
                        od_status = '$ct_status',
                        od_receipt_point = '0',
                        od_cart_coupon = '0',
                        od_coupon = '0',
                        od_send_coupon = '0',
                        od_shop_memo = concat(od_shop_memo,\"\\n관리자 반품완료 -" . G5_TIME_YMDHIS . " \"),
                        od_receipt_refund_price_ori = od_receipt_refund_price_ori-{$mod_mny},
						od_coupon_ori = od_coupon_ori-{$cartCouponPrice},
                        od_cart_coupon_ori = od_cart_coupon_ori-{$couponPrice}
                    where od_id = '$od_id' 
                      and od_tno = '$tno' ";
            sql_query($sql);

            if ($od['od_receipt_point'] > 0)
                insert_point($od['mb_id'], $od['od_receipt_point'], "주문번호 $od_id 반품완료", "@order", $od_id, "반품완료");

            //반품 회원의 쿠폰도 되돌려 줌 => 쿠폰 사용 기록 삭제
            if ($od['od_cart_coupon'] > 0 || $od['od_coupon'] > 0) {
                sql_query("delete from {$g5['g5_shop_coupon_log_table']} where od_id = '$od_id' ");
            }

            $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '[반품완료] ', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','반품완료'); ";
            sql_query($sql);

            if ((int) $ct_order['cnt'] > 0) {
                //부분반품일 경우
                SM_ADD_PARTIAL_SALE_CANCEL($od_id);
            } else {
                //반품완료 자료 삼진 전송
                SM_ADD_SALE_CANCEL($od_id);
            }

            include(G5_SHOP_PATH . '/ordermail1.inc.php');

            $item_returned = sql_fetch("SELECT it_name FROM lt_shop_cart WHERE ct_id='" . $returned[0] . "'");

            $arr_change_data = array();
            $arr_change_data['고객명'] = $od['od_name'];
            $arr_change_data['이름'] = $od['od_name'];
            $arr_change_data['보낸분'] = $od['od_name'];
            $arr_change_data['받는분'] = $od['od_b_name'];;
            $arr_change_data['주문번호'] = $od_id;
            $arr_change_data['주문금액'] = number_format($ttotal_price + $od_send_cost + $od_send_cost2);
            $arr_change_data['결제금액'] = number_format($od_receipt_price);
            $arr_change_data['회원아이디'] = $od['mb_id'];
            $arr_change_data['회사명'] = $default['de_admin_company_name'];
            $arr_change_data["아이디"] = $od['mb_id'];
            $arr_change_data["고객명(아이디)"] = $od['od_name'] . "(" . $od['mb_id'] . ")";
            $arr_change_data["od_list"] = $list;
            $arr_change_data['od_type'] = $od['od_type'];
            $arr_change_data['od_id'] = $od_id;

            $arr_change_data['반품상품'] = $list[0]['it_name'];
            if (count($returned) > 1) {
                $arr_change_data['반품상품'] .= "외 " . (count($item_returned) - 1) . "건";
            }
            msg_autosend('주문', '반품 완료', $od['mb_id'], $arr_change_data);
        } else {
            alert($xpay->Response_Msg() . ' 코드 : ' . $xpay->Response_Code());
        }
    } else {
        //2)API 요청 실패 화면처리
        /*
         echo "결제 부분취소 요청이 실패하였습니다.  <br>";
         echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
         echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
         */

        alert('반품 환불 요청이 실패하였습니다.\\n\\n' . $xpay->Response_Code() . ' : ' . $xpay->Response_Msg());
    }
} else {

    if (!trim($mod_memo))
        alert('취소 사유를 입력해 주십시오.');

    // 주문정보
    $sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
    $od = sql_fetch($sql);

    if (!$od['od_id'])
        alert_close('주문정보가 존재하지 않습니다.');

    $sql = " select SUM(IF(ct_status = '배송완료', 1, 0)) as od_count2,
                COUNT(*) as od_count1
            from {$g5['g5_shop_cart_table']}
            where od_id = '$od_id' ";
    $ct = sql_fetch($sql);

    $count = count($_POST['chk']);

    if (!$count)
        alert('반품할 제품을 1개 이상 선택해 주세요.');
    
    $return_price = $_POST['return_price'];
    $return_price_send = $_POST['return_price_send'];
    $return_price = $return_price - $return_price_send;
    $k_s = $_POST['chk'][0];
    $ct_id_s = $_POST['ct_id'][$k_s];
    if ($ct['od_count2'] == $count) {

        sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '반품수거중', ct_status_claim = '반품', ct_return_link ='$ct_id_s', ct_return_price_save ='$return_price' where od_id = '$od_id' and ct_status = '배송완료' ");

        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim)
                         values
                            ('$od_id', 1, '[반품처리CS] " . $mod_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','반품'); ";
        sql_query($sql);
    } else {
        for ($i = 0; $i < $count; $i++) {
            $k = $_POST['chk'][$i];
            $ct_id = $_POST['ct_id'][$k];
            $it_name = $_POST['it_name'][$k];
            $ct_qty = $_POST['ct_qty'][$k];

            //반품요청
            sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '반품수거중', ct_status_claim = '반품', ct_return_link ='$ct_id_s', ct_return_price_save ='$return_price' where ct_id = '$ct_id' ");

            $sql = " insert into lt_shop_order_history
                            (od_id, ct_id, it_name, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim, ct_qty)
                         values
                            ('$od_id', '$ct_id', '$it_name', 1, '[반품처리CS] " . $mod_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}','반품','$ct_qty'); ";
            sql_query($sql);
        }
    }

    $od_b_name        = clean_xss_tags($od_b_name);
    $od_b_tel         = clean_xss_tags($od_b_tel);
    $od_b_hp          = clean_xss_tags($od_b_hp);
    $od_b_zip1  = substr($od_b_zip, 0, 3);
    $od_b_zip2  = substr($od_b_zip, 3);
    $od_b_addr1       = clean_xss_tags($od_b_addr1);
    $od_b_addr2       = clean_xss_tags($od_b_addr2);
    $od_b_addr3       = clean_xss_tags($od_b_addr3);
    $od_b_addr_jibeon = preg_match("/^(N|R)$/", $od_b_addr_jibeon) ? $od_b_addr_jibeon : '';
    if (!$od_send_cost2) {
        $od_send_cost2 = $return_price_send;
    }


    // 반품요청
    $sql = " update {$g5['g5_shop_order_table']}
                    set od_status_claim   = '반품',
                        od_status_claim_date = '" . G5_TIME_YMDHIS . "',
                        od_status         = '반품수거중',
                        od_send_cost2     =  od_send_cost2 + $od_send_cost2,
                        od_b_name         = '$od_b_name',
                        od_b_tel          = '$od_b_tel',
                        od_b_hp           = '$od_b_hp',
                        od_b_zip1         = '$od_b_zip1',
                        od_b_zip2         = '$od_b_zip2',
                        od_b_addr1        = '$od_b_addr1',
                        od_b_addr2        = '$od_b_addr2',
                        od_b_addr3        = '$od_b_addr3',
                        od_b_addr_jibeon  = '$od_b_addr_jibeon',
                        od_shop_memo = concat(od_shop_memo,\"\\n관리자 반품처리CS - " . G5_TIME_YMDHIS . " (반품이유 : {$mod_memo})\")
                    where od_id = '$od_id' ";
    sql_query($sql);

    $change_status = '반품';
}

include_once(G5_PATH . '/head.sub.php');
?>

<script>
    alert("<?php echo $change_status ?> 처리됐습니다.");
    opener.document.location.reload();
    self.close();
</script>

<?php
include_once(G5_PATH . '/tail.sub.php');
?>