<?php
ob_start();
include_once ('../../common.php');
/*
 http://localhost/adm/penguinhouse/laundryorderproc.php?TR_NAME=STEP_POST&RFID=22222222222222&LAUNDRY_STEP=04&LAUNDRY_DATE=2019-06-14
 
 http://localhost/adm/penguinhouse/laundryorderproc.php?TR_NAME=STEP_POST&RFID=22222222222222&LAUNDRY_STEP=05&LAUNDRY_DATE=2019-06-14
 
 http://localhost/adm/penguinhouse/laundryorderproc.php?TR_NAME=STEP_POST&RFID=22222222222222&LAUNDRY_STEP=07&LAUNDRY_DATE=2019-06-14&INVOICE_NO=1234567890
 
 */
if(isset($_POST['TR_NAME']))
{
    $TR_NAME = $_POST['TR_NAME'];
    $sendtype = "post";
    
    $RFID = $_POST['RFID'];
    $LAUNDRY_STEP = $_POST['LAUNDRY_STEP'];
    $LAUNDRY_DATE = $_POST['LAUNDRY_DATE'];
    $PLT_PRICE = $_POST['PLT_PRICE'];
    $PLT_CODE = $_POST['PLT_CODE'];
    $REJCT_CODE = $_POST['REJCT_CODE'];
    $INVOICE_NO = $_POST['INVOICE_NO'];
}
elseif(isset($_GET['TR_NAME']))
{
    $TR_NAME = $_GET['TR_NAME'];
    $sendtype = "get";
    
    $RFID = $_GET['RFID'];
    $LAUNDRY_STEP = $_GET['LAUNDRY_STEP'];
    $LAUNDRY_DATE = $_GET['LAUNDRY_DATE'];
    $PLT_PRICE = $_GET['PLT_PRICE'];
    $PLT_CODE = $_GET['PLT_CODE'];
    $REJCT_CODE = $_GET['REJCT_CODE'];
    $INVOICE_NO = $_GET['INVOICE_NO'];
} else {
    $data = json_decode(file_get_contents('php://input'), true);
    $TR_NAME = $data["TR_NAME"];
    $sendtype = "curl";
    
    $RFID = $data['RFID'];
    $LAUNDRY_STEP = $data['LAUNDRY_STEP'];
    $LAUNDRY_DATE = $data['LAUNDRY_DATE'];
    $PLT_PRICE = $data['PLT_PRICE'];
    $PLT_CODE = $data['PLT_CODE'];
    $REJCT_CODE = $data['REJCT_CODE'];
    $INVOICE_NO = $data['INVOICE_NO'];
}

$result = -1;
$rtnData = array();
if(isset($TR_NAME) && $TR_NAME == "STEP_POST")
{
    $sql  = " select a.*, b.*                
                    from lt_shop_order as a
                        inner join lt_shop_cart as b
                            on a.od_id = b.od_id
                where   a.od_type IN ('L','K')
                and     a.od_status IN('세탁신청', '보관신청','수거중','수거완료','세탁중','세탁반려','배송중','보관중')
                and     b.rf_serial = '{$RFID}'
                ";
    $od = sql_fetch($sql);
    
    if(!$od['od_id']){
        $rtnData["RESULT"] = -1;
        $rtnData["RESULTMSG"] = "NO_ORDER";
        $rtnData["TR_NAME"] = $TR_NAME;
        echo  urldecode(json_encode($rtnData));
        exit;
    }
    
    $od_id = $od['od_id'];
    
    if($LAUNDRY_STEP == "04"){
        $change_status = '세탁중'; 
        //공장입고 => 세탁중
        if($PLT_PRICE && (int)$PLT_PRICE > 0){
            //추가비용이 있을 때 기입
            $sql = " update {$g5['g5_shop_order_table']}
                    set od_misu = od_misu + '{$PLT_PRICE}'
                    where od_id = '{$od['od_id']}' ";
            sql_query($sql);
            $sh_memo = '';
            switch ($PLT_CODE) {
                case "01": $sh_memo = '부분오염'; break;
                case "02": $sh_memo = '음식물유착'; break;
                case "03": $sh_memo = '황변제거'; break;
                case "04": $sh_memo = '매직/잉크'; break;
                case "05": $sh_memo = '부분토사'; break;
                case "06": $sh_memo = '토사'; break;
                case "07": $sh_memo = '혈흔'; break;
                case "08": $sh_memo = '분비물'; break;
                case "09": $sh_memo = '전체오염'; break;
            }
            
            $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, cancel_select, sh_add_price, sh_file)
                         values
                            ('{$od['od_id']}', 1, '[{$LAUNDRY_DATE}]{$sh_memo}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '펭귄하우스', 'cryptex','추가비용발생','{$PLT_PRICE}',''); ";
            sql_query($sql);
        }
        
        $sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}', od_status_claim = '' where od_id = '{$od_id}'  ";
        sql_query($sql, true);
        
        $sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}', ct_status_claim = '' where od_id = '{$od_id}' ";
        sql_query($sql, true);
        
        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id)
                         values
                            ('{$od_id}', 1, '[{$LAUNDRY_DATE}]{$change_status}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '펭귄하우스', 'cryptex'); ";
        sql_query($sql);
        $result = 1;
        //$rtnData["change_status"] = $change_status;
        
    } else if($LAUNDRY_STEP == "05"){
        
        
        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id)
                         values
                            ('{$od_id}', 1, '[".G5_TIME_YMD."] 공장출고', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '펭귄하우스', 'cryptex'); ";
        sql_query($sql);
        $result = 1;
        
    } else if($LAUNDRY_STEP == "06" && $od['od_type'] == 'K'){
        //공장출고 => 보관중!
        
        $change_status = '보관중';
        
        $sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}' where od_id = '{$od_id}' ";
        sql_query($sql, true);
        
        $ct_keep_month = $od['ct_keep_month'];
        $ct_keep_start = G5_TIME_YMD;
        $ct_keep_end=date_create(G5_TIME_YMD);
        date_add($ct_keep_end, date_interval_create_from_date_string($ct_keep_month.' months'));
        $ct_keep_end = date_format($ct_keep_end,"Y-m-d");
        $buy_ct_id = $od['buy_ct_id'];
        $buy_od_sub_id = $od['buy_od_sub_id'];
        
        $sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}', ct_keep_start = '{$ct_keep_start}', ct_keep_end = '{$ct_keep_end}'  where od_id = '{$od_id}' ";
        sql_query($sql, true);
        
        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id)
                         values
                            ('{$od_id}', 1, '[{$LAUNDRY_DATE}]{$change_status}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '펭귄하우스', 'cryptex'); ";
        sql_query($sql);
        
        $result = 1;
        //$rtnData["change_status"] = $change_status;
        
        $sql = " update lt_shop_order_item 
                    set ct_status = '{$change_status}'
                        , ct_keep_month = '{$ct_keep_month}'
                        , ct_keep_start = '{$ct_keep_start}'
                        , ct_keep_end = '{$ct_keep_end}' 
                    where ct_id = '{$buy_ct_id}' and od_sub_id = '{$buy_od_sub_id}' ";
        sql_query($sql, true);
        
        
        include(G5_SHOP_PATH.'/ordermail1.inc.php');
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
        $arr_change_data["고객명(아이디)"] = $od['od_name']."(".$od['mb_id'].")";
        $arr_change_data["od_list"] = $list;
        $arr_change_data['od_type'] = $od['od_type'];
        $arr_change_data['od_id'] = $od_id;
        
        msg_autosend('세탁보관', '보관중', $od['mb_id'], $arr_change_data);
        
    } else if($LAUNDRY_STEP == "07"){
        //고객출고=>배송
        $change_status = '배송중';
        
        $sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}', od_delivery_company = 'CJ대한통운', od_invoice = '{$INVOICE_NO}', od_invoice_time = '".$LAUNDRY_DATE."' where od_id = '$od_id' ";
        sql_query($sql, true);
        
        $sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}' where od_id = '{$od_id}' ";
        sql_query($sql, true);
        
        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id)
                         values
                            ('{$od_id}', 1, '[{$LAUNDRY_DATE}]{$change_status}', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '펭귄하우스', 'cryptex'); ";
        sql_query($sql);
        $result = 1;
        //$rtnData["change_status"] = $change_status;
        
        include(G5_SHOP_PATH.'/ordermail1.inc.php');
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
        $arr_change_data["고객명(아이디)"] = $od['od_name']."(".$od['mb_id'].")";
        $arr_change_data["od_list"] = $list;
        $arr_change_data['od_type'] = $od['od_type'];
        $arr_change_data['od_id'] = $od_id;
        
        if($od['od_type'] == "L"){
            msg_autosend('세탁', '세탁 배송 시작', $od['mb_id'], $arr_change_data);
        } else if($od['od_type'] == "K"){
            msg_autosend('세탁보관', '배송 시작', $od['mb_id'], $arr_change_data);
        }
        
    } else if($LAUNDRY_STEP == "11" || $LAUNDRY_STEP == "13"){
        //펭귄반려
        $change_status = '세탁중'; 
        $sql = " update {$g5['g5_shop_order_table']} set od_status = '{$change_status}', od_status_claim = '펭귄반려' where od_id = '{$od_id}'  ";
        sql_query($sql, true);
        
        $sql = " update {$g5['g5_shop_cart_table']} set ct_status = '{$change_status}', ct_status_claim = '펭귄반려' where od_id = '{$od_id}' ";
        sql_query($sql, true);
        
        $REJCT_MSG = "";
        if($REJCT_CODE == "01") {
            $REJCT_MSG = "고객변심";
            $cancel_select = "고객반려";
        }
        elseif($REJCT_CODE == "02") {
            $REJCT_MSG = "세탁거부";
            $cancel_select = "펭귄반려";
        }
        
        $sql = " insert into lt_shop_order_history
                            (od_id, is_important, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id, ct_status_claim, cancel_select)
                         values
                            ('{$od_id}', 1, '[{$LAUNDRY_DATE}]세탁중(펭귄반려) : ".$REJCT_MSG."', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '펭귄하우스', 'cryptex', '펭귄반려', '{$cancel_select}'); ";
        sql_query($sql);
        $result = 1;
        //$rtnData["change_status"] = $change_status;
    } else {
        $rtnData["RESULT"] = -1;
        $rtnData["RESULTMSG"] = "NO_LAUNDRY_STEP";
        $rtnData["TR_NAME"] = $TR_NAME;
        echo  json_encode($rtnData);
        exit;
    }
    
    
    $rtnData["RESULT"] = $result;
    $rtnData["TR_NAME"] = $TR_NAME;
    
    
} else {
    $rtnData["RESULT"] = -1;
    $rtnData["RESULTMSG"] = "ERROR PARAM";
}

echo  json_encode($rtnData);
$length=ob_get_length();
header('Content-Type: application/json');
header("Content-Length: $length");
header('Access-Control-Allow-Origin: *');
ob_end_flush();
?>