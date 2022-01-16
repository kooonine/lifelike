<?php
$sub_menu = '40';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$auth_sub_menu = $auth[substr($sub_menu,0,2)];
if ($is_admin == "brand") $auth_sub_menu = $auth['92'];
auth_check($auth_sub_menu, 'w');

if (!trim($mod_memo))
    alert('취소 사유를 입력해 주십시오.');

// 주문정보
$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);

switch ($od['od_type']) {
    case 'O':
        $od_type_name = '주문';
        break;
    case 'R':
        $od_type_name = '계약';
        break;
    case 'L':
        $od_type_name = '세탁';
        break;
    case 'K':
        $od_type_name = '세탁보관';
        break;
    case 'S':
        $od_type_name = '수선';
        break;
    default:
        $od_type_name = '주문';
        break;
}

if (!$od['od_id'])
    alert_close('주문정보가 존재하지 않습니다.');

if ($od['od_settle_case'] == '계좌이체' && substr($od['od_receipt_time'], 0, 10) >= G5_TIME_YMD)
    alert_close('실시간 계좌이체건의 부분취소 요청은 결제일 익일에 가능합니다.');

// 금액비교
$count = count($_POST['chk']);

if (!$count)
    alert('취소할 제품을 1개 이상 선택해 주세요.');

if ($od['od_type'] == 'R') {
    for ($i = 0; $i < $count; $i++) {
        $k = $_POST['chk'][$i];
        $ct_id = $_POST['ct_id'][$k];
        $it_name = $_POST['it_name'][$k];

        sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '계약취소', ct_status_claim = '계약취소' where ct_id = '$ct_id' ");

        $sql = " insert into lt_shop_order_history
                        (od_id, ct_id, it_name, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id)
                     values
                        ('$od_id', '$ct_id', '$it_name', 0, '[계약취소] " . $mod_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}'); ";
        sql_query($sql);
    }

    $sql = " update {$g5['g5_shop_order_table']}
                    set od_status = '주문취소',
                        od_status_claim = '계약취소',
                        od_status_claim_date = '" . G5_TIME_YMDHIS . "',
                        od_shop_memo = concat(od_shop_memo, \"$mod_memo\")
                    where od_id = '{$od['od_id']}' ";
    sql_query($sql);

    //계약 취소건 삼진 연동.
    //SM_ADD_RENTAL_CANCEL($od['od_id'], 2);

} else if ($od['od_type'] == 'O') {
    $tax_mny = 0;
    for ($i = 0; $i < $count; $i++) {
        $k = $_POST['chk'][$i];
        $ct_id = $_POST['ct_id'][$k];
        $tax_mny += preg_replace('/[^0-9]/', '', $_POST['stotal'][$k]);
    }

    if ($tax_mny == 0) {
        alert('취소할 금액이 없습니다.');
    }

    // PG사별 부분취소 실행
    include_once(G5_SHOP_PATH . '/lg/orderpartcancel.inc.php');

    //계약 취소건 삼진 연동.
    //SM_ADD_PARTIAL_SALE_CANCEL($od_id, 2);

    $od_type = $od['od_type'];
    include_once(G5_SHOP_PATH . '/ordermail1.inc.php');
    $arr_change_data = array();
    $arr_change_data["od_list"] = $list;
    $arr_change_data['od_type'] = $od_type;
    $arr_change_data['od_id'] = $od_id;
    $arr_change_data['취소금액'] = number_format($tax_mny) . "원";
    msg_autosend('주문', '취소 완료', $od['mb_id'], $arr_change_data);
} else if ($od['od_type'] == 'L' || $od['od_type'] == 'K' || $od['od_type'] == 'S') {

    //수선,보관의 주문취소는 전체 취소
    if ($od['od_tno']) {
        require_once('./settle_lg3.inc.php');
        $LGD_TID    = $od['od_tno'];        //LG유플러스으로 부터 내려받은 거래번호(LGD_TID)

        $xpay = new XPay($configPath, $CST_PLATFORM);

        // Mert Key 설정
        $xpay->set_config_value('t' . $LGD_MID, $LGD_MERTKEY);
        $xpay->set_config_value($LGD_MID, $LGD_MERTKEY);
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
    }

    $ct = sql_fetch(" select * from {$g5['g5_shop_cart_table']} where od_id = '$od_id' ");

    //상품 주문취소
    sql_query(" update {$g5['g5_shop_cart_table']} set ct_status = '주문취소', ct_status_claim = '주문취소' where ct_id = '{$ct['ct_id']}' ");

    //취소이력 저장
    $sql = " insert into lt_shop_order_history
                        (od_id, ct_id, it_name, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id)
                     values
                        ('$od_id', '{$ct['ct_id']}', '$it_name', 0, '[주문취소] " . $mod_memo . "', '" . G5_TIME_YMDHIS . "', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}'); ";
    sql_query($sql);

    //주문취소
    $sql = " update {$g5['g5_shop_order_table']}
                    set     od_send_cost = '0',
                            od_send_cost2 = '0',
                            od_receipt_price = '0',
                            od_receipt_point = '0',
                            od_misu = '0',
                            od_cancel_price = '{$od['od_receipt_price']}',
                            od_cart_coupon = '0',
                            od_coupon = '0',
                            od_send_coupon = '0',
                            od_refund_price = '0',
                            od_status = '주문취소',
                            od_status_claim = '주문취소',
                            od_status_claim_date = '" . G5_TIME_YMDHIS . "',
                            od_shop_memo = concat(od_shop_memo, \"$mod_memo\")
                    where od_id = '{$od['od_id']}' ";
    sql_query($sql);

    //대상제품의 상태 초기화
    sql_query("update lt_shop_order_item set ct_status = '' where ct_id = '{$ct['buy_ct_id']}' and od_sub_id  = '{$ct['buy_od_sub_id']}'");
}

include_once(G5_PATH . '/head.sub.php');
?>

<script>
    alert("<?php echo $od_type_name ?> 취소 처리됐습니다.");
    opener.document.location.reload();
    self.close();
</script>

<?php
include_once(G5_PATH . '/tail.sub.php');
?>