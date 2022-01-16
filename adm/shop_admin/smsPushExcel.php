<?php
include_once('./_common.php');
include_once(G5_ADMIN_PATH.'/shop_admin/admin.shop.lib.php');

if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];
    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');
    $data->read($file);
    error_reporting(E_ALL ^ E_NOTICE);

    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;
    $ss_op_id = 0;
    $fail_phone = array();
    $ss_numbers = $data->sheets[0]['numRows'] - 1;
    // $i 사용시 ordermail.inc.php의 $i 때문에 무한루프에 빠짐
    for ($k = 2; $k <= $data->sheets[0]['numRows']; $k++) {
        $fail_check = 0;
        $mallName = addslashes(trim($data->sheets[0]['cells'][$k][1]));
        if (!$mallName && $mallName == '') {

        } else {
        $mallOdId = addslashes(trim($data->sheets[0]['cells'][$k][2]));
        $mallProductsName1 = addslashes(trim($data->sheets[0]['cells'][$k][3]));
        $mallCart1 = addslashes(trim($data->sheets[0]['cells'][$k][4]));
        $mallProductsName2 = addslashes(trim($data->sheets[0]['cells'][$k][5]));
        $mallCart2 = addslashes(trim($data->sheets[0]['cells'][$k][6]));
        $mallMemberName = addslashes(trim($data->sheets[0]['cells'][$k][7]));
        $mallPhoneNumber = addslashes(trim($data->sheets[0]['cells'][$k][8]));
        
        $total_count++;
        $nowInvoiceTime =  G5_TIME_YMDHIS;


        $prodoctsName = "▶품절상품: $mallProductsName1";
        if ($mallProductsName2 && count($mallProductsName2) > 0) {
            $prodoctsName = "▶품절상품1: $mallProductsName1
▶품절상품2: $mallProductsName2";
        }


        // gnagna gna 
        $msg_body = "
안녕하세요. $mallMemberName 고객님.
프리미엄 구스 침구 브랜드 리탠다드입니다.
재고부족으로 인하여 품절안내 드리는점 양해부탁드립니다

▶구매쇼핑몰: $mallName
▶주문번호: $mallOdId
$prodoctsName

재고소진 상품은 수급이 어려워 부득이하게 환불 진행되며, 영업일 2일 이내에 자동 취소예정입니다. 
다시한번 시간을 내어 주문해주신 고객님께 불편을 드려 죄송합니다. 
               
감사합니다.";

        if ($mallName =='라이프라이크' || $mallName =='자사몰' || $mallName =='LIFELIKE') {

            $orderSql = "SELECT mb_id FROM lt_shop_order WHERE od_id = '$mallOdId' LIMIT 1";
            $odMb = sql_fetch($orderSql);
            // dd($orderSql);
            $mbId = $odMb['mb_id'];
            // dd($mbId);
            if ($mbId || $mbId != null){
            $msg_body = "
안녕하세요. $mallMemberName 고객님.
프리미엄 구스 침구 브랜드 리탠다드입니다.
재고부족으로 인하여 품절안내 드리는점 양해부탁드립니다

▶구매쇼핑몰: $mallName
▶주문번호: $mallOdId
$prodoctsName

재고소진 상품은 수급이 어려워 부득이하게 환불 진행되며, 영업일 2일 이내에 자동 취소예정입니다. 
다시한번 시간을 내어 주문해주신 고객님께 불편을 드려 죄송합니다. 
라이프라이크 고객님들께 품절보상으로 즉시 사용 가능한 3,000P를 지급해드렸습니다.

감사합니다.";
                // zzz
                insert_point($mbId, 3000, '품절보상포인트지급', '@soldOutPoint', $mbId,$mallOdId,100);


            } else {
                // 자사몰 주문번호 확인해주세요 !! 
                $fail_count++;
                $fail_phone[] = $mallPhoneNumber;
                $fail_check = 1;
            }



        // $orderSql = "SELECT mb_id FROM lt_shop_order WHERE od_id = '$mallOdId' LIMIT 1";
        // $odMb = sql_fetch($orderSql);
        // // dd($orderSql);
        // $mbId = $odMb['mb_id'];
        // $mb_point = get_point_sum($mb_id);
        // $po_mb_point = $mb_point;
        // $sql = " insert into {$g5['point_table']}
        // set mb_id = '$mbId',
        //     po_datetime = '".G5_TIME_YMDHIS."',
        //     po_content = '품절문자발송',
        //     po_point = 3000,
        //     po_use_point = '0',
        //     po_mb_point = '$po_mb_point',
        //     po_expired = '$po_expired',
        //     po_expire_date = '$po_expire_date',
        //     po_rel_table = '@order',
        //     po_rel_id = '',
        //     po_rel_action = '품절문자발송',
        //     po_request_id = '$mbId' ";

        // array_push($arr_sql, $sql);
        // sql_query($sql);      
        // 하하하 

        // 포인트 UPDATE
        // $sql = " update {$g5['member_table']} set mb_point = mb_point + 3000 where mb_id = '$mbId' ";
        
        // dd($sql);
        // return;
        // sql_query($sql);

        }
        if ($fail_check != 1) {

            $param = array('send_time' => $send_time
            ,'dest_phone' => $mallPhoneNumber
            ,'dest_name' => $mallMemberName
            ,'send_phone' => '0234947641'
            ,'send_name' => 'LITANDARD'
            ,'subject' => '상품품절안내'
            ,'msg_body' => $msg_body
            );
            $response = Unirest::post("http://api.apistore.co.kr/ppurio/1/message/sms/".API_STORE_ID,
            array(
                "x-waple-authorization" => API_STORE_KEY
            ),
            $param
            );
            $resbody = get_object_vars($response->body);
            $smsStatus = 2;
            if ($resbody['result_code'] == '200') {
                $smsStatus = 1;
                $smsCmid = $resbody['cmid'];
                $succ_count++;
                if ($mallCart1 && $mallCart1 != '') {
                    $sabangUpdateSql = "UPDATE sabang_lt_order_view SET ov_sms_check = 1 WHERE ov_ct_id = '$mallCart1'";
                    sql_query($sabangUpdateSql);
                    $sabangUpdateSql = "UPDATE sabang_lt_order_form SET form_sms_check = 1 WHERE sabang_ord_no = '$mallCart1'";
                    sql_query($sabangUpdateSql);
                }
                if ($mallCart2 && $mallCart2 != '') {
                    $sabangUpdateSql = "UPDATE sabang_lt_order_view SET ov_sms_check = 1 WHERE ov_ct_id = '$mallCart2'";
                    sql_query($sabangUpdateSql);
                    $sabangUpdateSql = "UPDATE sabang_lt_order_form SET form_sms_check = 1 WHERE sabang_ord_no = '$mallCart2'";
                    sql_query($sabangUpdateSql);
                }
                if ($mallCart1 == '' && $mallCart2 == '') {
                    $selectSql = "SELECT COUNT(*) AS CNT FROM sabang_lt_order_view WHERE ov_order_id = '$mallOdId'";
                    $orderView = sql_fetch($selectSql);
                    $orderCnt = $orderView['CNT'];

                    if ($mallProductsName1 && $mallProductsName1 != '') {
                        if ($orderCnt > 1) {
                            $sabangUpdateSql = "UPDATE sabang_lt_order_view SET ov_sms_check = 1 WHERE (ov_order_id = '$mallOdId' AND ov_it_name = '$mallProductsName1')";
                            sql_query($sabangUpdateSql);
                            $sabangUpdateSql = "UPDATE sabang_lt_order_form SET form_sms_check = 1 WHERE (mall_order_no = '$mallOdId' AND samjin_name = '$mallProductsName1')";
                            sql_query($sabangUpdateSql);
                        } else {
                            $sabangUpdateSql = "UPDATE sabang_lt_order_view SET ov_sms_check = 1 WHERE (ov_order_id = '$mallOdId')";
                            sql_query($sabangUpdateSql);
                            $sabangUpdateSql = "UPDATE sabang_lt_order_form SET form_sms_check = 1 WHERE mall_order_no = '$mallOdId'";
                            sql_query($sabangUpdateSql);
                        }

                    }
                    if ($mallProductsName2 && $mallProductsName2 != '') {
                        if ($orderCnt > 1) {
                            $sabangUpdateSql = "UPDATE sabang_lt_order_view SET ov_sms_check = 1 WHERE (ov_order_id = '$mallOdId' AND ov_it_name = '$mallProductsName2')";
                            sql_query($sabangUpdateSql);
                            $sabangUpdateSql = "UPDATE sabang_lt_order_form SET form_sms_check = 1 WHERE (mall_order_no = '$mallOdId' AND samjin_name = '$mallProductsName2')";
                            sql_query($sabangUpdateSql);
                        } else {
                            $sabangUpdateSql = "UPDATE sabang_lt_order_view SET ov_sms_check = 1 WHERE (ov_order_id = '$mallOdId')";
                            sql_query($sabangUpdateSql);
                            $sabangUpdateSql = "UPDATE sabang_lt_order_form SET form_sms_check = 1 WHERE mall_order_no = '$mallOdId'";
                            sql_query($sabangUpdateSql);
                        }
                    }
                }
            } else {
                $fail_count++;
                $fail_phone[] = $mallPhoneNumber;
            }

            if($ss_op_id==0) {
                $ssInsertSql = "INSERT INTO lt_sms_soldout SET ss_op_id =  ss_id, ss_mallname = '$mallName', ss_od_id = '$mallOdId', ss_products1 = '$mallProductsName1', ss_products2 = '$mallProductsName2'
                , ss_mb_name = '$mallMemberName', ss_phone_number = '$mallPhoneNumber', ss_numbers = '$total_count', ss_status= '$smsStatus', ss_cmid='$smsCmid'";
                $res = sql_query($ssInsertSql);
                if ($res == 1) {
                    $ss_op_id = sql_insert_id();
                    $ssUpdateSql = "UPDATE lt_sms_soldout SET ss_op_id = $ss_op_id WHERE ss_id = $ss_op_id";
                    sql_query($ssUpdateSql);
                } 
            } else {
                $ssInsertSql = "INSERT INTO lt_sms_soldout SET ss_op_id =  '$ss_op_id', ss_mallname = '$mallName', ss_od_id = '$mallOdId', ss_products1 = '$mallProductsName1', ss_products2 = '$mallProductsName2'
                , ss_mb_name = '$mallMemberName', ss_phone_number = '$mallPhoneNumber', ss_numbers = '$total_count', ss_status= '$smsStatus', ss_cmid='$smsCmid'";
                sql_query($ssInsertSql);

            }
        }
    }
    }
}

$msg = "품절 SMS 발송 결과입니다.";

$msg .= "\\n 총건수:".number_format($total_count);
$msg .= "\\n 완료건수:".number_format($succ_count);
$msg .= "\\n 실패건수:".number_format($fail_count);
if($fail_count > 0) {
    $msg .= "\\n 실패번호:".implode(', ', $fail_phone);
}

alert($msg,'/adm/operation/configform_sms_send_soldout.php');

?>