<?php
$sub_menu = '40';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$auth_sub_menu = $auth[substr($sub_menu,0,2)];
if ($is_admin == "brand") $auth_sub_menu = $auth['92'];
auth_check($auth_sub_menu, "w");

if (!trim($mod_memo))
    alert('취소 사유를 입력해 주십시오.');

// 주문정보
$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

if (!$od['od_id'])
    alert_close('주문정보가 존재하지 않습니다.');

if ($od['od_settle_case'] == '계좌이체' && substr($od['od_receipt_time'], 0, 10) >= G5_TIME_YMD)
    alert_close('실시간 계좌이체건의 부분취소 요청은 결제일 익일에 가능합니다.');

if ($od['od_pg'] != 'lg') return;
if (!$od['od_tno']) return;

include_once(G5_SHOP_PATH . '/settle_lg.inc.php');

// PG 결제 취소
$LGD_TID    = $od['od_tno'];        //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)

$xpay = new XPay($configPath, $CST_PLATFORM);

// Mert Key 설정
$xpay->set_config_value('t' . $LGD_MID, $config['cf_lg_mert_key']);
$xpay->set_config_value($LGD_MID, $config['cf_lg_mert_key']);
$xpay->Init_TX($LGD_MID);

$xpay->Set("LGD_TXNAME", "Cancel");
$xpay->Set("LGD_TID", $LGD_TID);

if ($xpay->TX()) {
    //1)결제취소결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
    /*
     echo "결제 취소요청이 완료되었습니다.  <br>";
     echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
     echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";
     */
} else {
    //2)API 요청 실패 화면처리
    $msg = "결제 취소요청이 실패하였습니다.\\n";
    $msg .= "TX Response_code = " . $xpay->Response_Code() . "\\n";
    $msg .= "TX Response_msg = " . $xpay->Response_Msg();

    alert($msg);
}

// 장바구니 자료 취소
sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '주문취소', ct_status_claim = '주문취소' where od_id = '$od_id' ");

// //발주서 출고 전 취소

$ord_view = "update sabang_lt_order_view set ov_order_status = '출고전취소' , ov_distribution_status = '출고전취소' , ov_update_datetime = '{$now_date}' where ov_order_id = '{$od_id}'";
sql_query($ord_view);
$ord_form = "update sabang_lt_order_form set dpartner_stat = '출고전취소' , update_dt = '{$now_date}'  where mall_order_no = '{$od_id}'";
sql_query($ord_form);

// 주문 취소
$cancel_memo = addslashes(strip_tags($mod_memo));
$cancel_price = $od['od_cart_price'];

$sql = " update {$g5['g5_shop_order_table']}
                set od_send_cost = '0',
                    od_send_cost2 = '0',
                    od_receipt_price = '0',
                    od_receipt_point = '0',
                    od_misu = '0',
                    od_cancel_price = '$cancel_price',
                    od_cart_coupon = '0',
                    od_coupon = '0',
                    od_send_coupon = '0',
                    od_refund_price = '0',
                    od_status = '주문취소',
                    od_status_claim = '주문취소',
                    od_status_claim_date = '" . G5_TIME_YMDHIS . "',
                    od_shop_memo = concat(od_shop_memo,\"\\n주문취소 - " . G5_TIME_YMDHIS . " (취소이유 : {$cancel_memo})\"),
                    od_receipt_refund_price_ori = 0,
                    od_cart_coupon_ori = 0,
					od_coupon_ori = 0
                where od_id = '$od_id' ";
sql_query($sql);

// 주문취소 회원의 포인트를 되돌려 줌
if ($od['od_receipt_point'] > 0)
    insert_point($od['mb_id'], $od['od_receipt_point'], "주문번호 $od_id 주문취소", "@order", $od_id, "주문취소");

//주문취소 회원의 쿠폰도 되돌려 줌 => 쿠폰 사용 기록 삭제
if ($od['od_cart_coupon'] > 0 || $od['od_coupon'] > 0) {
    sql_query("delete from {$g5['g5_shop_coupon_log_table']} where od_id = '$od_id' ");
}

$sql = " insert into lt_shop_order_history
                    (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id)
                 values
                    ('$od_id', 1, '[전체주문취소] " . $mod_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}'); ";
sql_query($sql);

//주문 취소건 삼진 연동.
//SM_ADD_SALE_CANCEL($od_id, 2);

$od_type = $od['od_type'];
include_once(G5_SHOP_PATH . '/ordermail1.inc.php');
$arr_change_data = array();
$arr_change_data["od_list"] = $list;
$arr_change_data['od_type'] = $od_type;
$arr_change_data['od_id'] = $od_id;
$arr_change_data['취소금액'] = number_format($cancel_price) . "원";

msg_autosend('주문', '취소 완료', $od['mb_id'], $arr_change_data);


include_once(G5_PATH . '/head.sub.php');
?>

<script>
    alert("주문 취소 처리됐습니다.");
    opener.document.location.reload();
    self.close();
</script>

<?php
include_once(G5_PATH . '/tail.sub.php');
?>