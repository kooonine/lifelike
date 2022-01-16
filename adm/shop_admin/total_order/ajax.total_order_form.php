<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);

$sno = $_POST['sno'];

$type = $_POST['type'];

$today = date("Y-m-d H:i:s");
if($type =="handDel"){ 
    // $kuengmin_meg = 0;
    // $assist_meg = 0;
    // $sql_common ="dpartner_stat = '출고전취소' , update_dt = '{$today}'";
    // $sql = " update sabang_lt_order_form set $sql_common where sno in ($sno) ";
    // sql_query($sql);
    // $view_sql="select * from sabang_lt_order_form where sno in ($sno)";
    // $view_result = sql_query($view_sql);
    // for($vi = 0 ; $view_row = sql_fetch_array($view_result); $vi++){
    //     if(!empty($view_row['sabang_ord_no'])){
    //         $vi_sql = "update sabang_lt_order_view set ov_order_status = '출고전취소', ov_distribution_status = '출고전취소' , ov_update_datetime = '{$today}' where ov_IDX = '{$view_row['sabang_ord_no']}'";
    //         sql_query($vi_sql);
    //         $vi_sql = "update sabang_lt_order_form set $sql_common where sabang_ord_no = '{$view_row['sabang_ord_no']}'";
    //         sql_query($vi_sql);

    //         if ($view_row['dpartner_id'] == '경민실업') $kuengmin_meg++;
    //         if ($view_row['dpartner_id'] == '어시스트') $assist_meg++;
    //     }
    // }

    // if($kuengmin_meg > 0){
    //     $msg_body = "[리탠다드] 취소건이 있습니다.";
    //     $param = array('send_time' => $send_time
    //     ,'dest_phone' => '01047151469'
    //     ,'dest_name' => ''
    //     ,'send_phone' => '0234947641'
    //     ,'send_name' => 'LITANDARD'
    //     ,'subject' => '출고전취소안내'
    //     ,'msg_body' => $msg_body
    //     );
    //     $response = Unirest::post("http://api.apistore.co.kr/ppurio/1/message/sms/".API_STORE_ID,
    //     array(
    //         "x-waple-authorization" => API_STORE_KEY
    //     ),
    //     $param
    //     );
    //     $resbody = get_object_vars($response->body);
    //     if ($resbody['result_code'] == '200') {
    //     } else {
        
    //     }
    //     $msg_body = "[리탠다드] 취소건이 있습니다.";
    //     $param = array('send_time' => $send_time
    //     ,'dest_phone' => '01086344248'
    //     ,'dest_name' => ''
    //     ,'send_phone' => '0234947641'
    //     ,'send_name' => 'LITANDARD'
    //     ,'subject' => '출고전취소안내'
    //     ,'msg_body' => $msg_body
    //     );
    //     $response = Unirest::post("http://api.apistore.co.kr/ppurio/1/message/sms/".API_STORE_ID,
    //     array(
    //         "x-waple-authorization" => API_STORE_KEY
    //     ),
    //     $param
    //     );
    //     $resbody = get_object_vars($response->body);
    //     if ($resbody['result_code'] == '200') {
    //     } else {
        
    //     }
    // }
    // if($assist_meg > 0){
    //     $msg_body = "[리탠다드] 취소건이 있습니다.";
    //     $param = array('send_time' => $send_time
    //     ,'dest_phone' => '01034469808'
    //     ,'dest_name' => ''
    //     ,'send_phone' => '0234947641'
    //     ,'send_name' => 'LITANDARD'
    //     ,'subject' => '출고전취소안내'
    //     ,'msg_body' => $msg_body
    //     );
    //     $response = Unirest::post("http://api.apistore.co.kr/ppurio/1/message/sms/".API_STORE_ID,
    //     array(
    //         "x-waple-authorization" => API_STORE_KEY
    //     ),
    //     $param
    //     );
    //     $resbody = get_object_vars($response->body);
    //     if ($resbody['result_code'] == '200') {
    //     } else {
        
    //     }
    //     $msg_body = "[리탠다드] 취소건이 있습니다.";
    //     $param = array('send_time' => $send_time
    //     ,'dest_phone' => '01041635912'
    //     ,'dest_name' => ''
    //     ,'send_phone' => '0234947641'
    //     ,'send_name' => 'LITANDARD'
    //     ,'subject' => '출고전취소안내'
    //     ,'msg_body' => $msg_body
    //     );
    //     $response = Unirest::post("http://api.apistore.co.kr/ppurio/1/message/sms/".API_STORE_ID,
    //     array(
    //         "x-waple-authorization" => API_STORE_KEY
    //     ),
    //     $param
    //     );
    //     $resbody = get_object_vars($response->body);
    //     if ($resbody['result_code'] == '200') {
    //     } else {
        
    //     }
    // }

}else if($type =="soldout"){
    $sql_common ="dpartner_stat = '물류품절' , update_dt = '{$today}'";
    
    
    $sql = " update sabang_lt_order_form set $sql_common where sno in  ({$sno}) ";
    sql_query($sql);
    
    $view_sql="select * from sabang_lt_order_form where sno in  ({$sno})";
    $view_result = sql_query($view_sql);
    
    $stock_send_sb_cds = '';
    for($vi = 0 ; $view_row = sql_fetch_array($view_result); $vi++){
        if(!empty($view_row['sabang_ord_no'])){
            $vi_sql = "update sabang_lt_order_view set ov_distribution_status = '물류품절' , ov_update_datetime = '{$today}' where ov_IDX = '{$view_row['sabang_ord_no']}'";
            sql_query($vi_sql);
        }
        
        if(!empty($view_row['product_ID'])){
            if(empty($stock_send_sb_cds)){
                $stock_send_sb_cds = $view_row['product_ID'];
            }else{
                $stock_send_sb_cds .= ','.$view_row['product_ID'];
            }
        }
    }
    //재고연동 끊기
    $upStock_sql = "UPDATE sabang_goods_origin SET stock_send = 'N' WHERE sabang_goods_cd IN ({$stock_send_sb_cds}) ";
    sql_query($upStock_sql);

    //재고 사방넷 0 송신
    $sabang_goods_list = "SELECT * FROM sabang_goods_origin WHERE sabang_goods_cd IN ({$stock_send_sb_cds}) ";
    $sb_result = sql_query($sabang_goods_list);

    $today= date("Ymd");

    //xml 상단에 무조건 있어야하는 코드
    $xml_code = "";
    $xml_code = "<?xml version=\"1.0\" encoding=\"euc-kr\" ?>\n";  

    $xml_code.="<SABANG_GOODS_REGI>\n";
        $xml_code.="<HEADER>\n";
            $xml_code.="<SEND_COMPAYNY_ID>LITANDARD</SEND_COMPAYNY_ID>\n";
            $xml_code.="<SEND_AUTH_KEY><![CDATA[Z6MV9M8HK9HSK0xx0XYxG7EACRMGJ0KTX6]]></SEND_AUTH_KEY>\n";
            $xml_code.="<SEND_DATE><![CDATA[".$today."]]></SEND_DATE>\n";
            $xml_code.="<SEND_GOODS_CD_RT>Y</SEND_GOODS_CD_RT>\n";
            $xml_code.="<RESULT_TYPE>XML</RESULT_TYPE>\n";
        $xml_code.="</HEADER>\n";


    $send_count = 0;

    for($sbi = 0 ; $sb_row=sql_fetch_array($sb_result); $sbi++ ){
        
        
        $total_stock = 0;

        if( strpos($sb_row['char_2_val'],',') === false ){
            $xml_code.="<DATA>\n";
        
                $xml_code.="<GOODS_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$sb_row['goods_nm'])."]]></GOODS_NM>\n";
                $xml_code.="<COMPAYNY_GOODS_CD><![CDATA[".iconv("UTF-8", "EUC-KR",$sb_row['compayny_goods_cd'])."]]></COMPAYNY_GOODS_CD>\n";
                $xml_code.="<STATUS>".$sb_row['status']."</STATUS>\n";
                $xml_code.="<GOODS_COST>".$sb_row['goods_cost']."</GOODS_COST>\n";
                $xml_code.="<GOODS_PRICE>".$sb_row['goods_price']."</GOODS_PRICE>\n";
                $xml_code.="<GOODS_CONSUMER_PRICE>".$sb_row['goods_consumer_price']."</GOODS_CONSUMER_PRICE>\n";
        
                $xml_code.="<SKU_INFO>\n";
                if(empty($sb_row['char_2_val'])){
                    $xml_code.="<SKU_VALUE>".iconv("UTF-8", "EUC-KR","단품")."^^".$total_stock."</SKU_VALUE>\n";     
                }else{
                    $xml_code.="<SKU_VALUE>".iconv("UTF-8", "EUC-KR",$sb_row['char_1_val']).":".iconv("UTF-8", "EUC-KR",$sb_row['char_2_val'])."^^".$total_stock."</SKU_VALUE>\n";    
                }
                $xml_code.="</SKU_INFO>\n";
                
            $xml_code.="</DATA>\n";
        }
    

        $send_count = $sbi;
    }

    $xml_code.="</SABANG_GOODS_REGI>\n";

    $toDate = date("YmdHis");

    $file_name =  $toDate."_goods_stock_send"; //파일명지정
    $dir_name = "./sabang_stock_zero/".$file_name.".xml"; //디렉토리지정
    file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함)


    $history_sql = "insert into sabang_goods_stat_xml_history 
                    set xml_name = '".$file_name."' 
                        , reg_date = '".$toDate."'
                    ";
    sql_query($history_sql);

    $send_xml_sql = "select xml_name from sabang_goods_stat_xml_history where status = 0 order by no desc limit 1";

    $send_xml_name = sql_fetch($send_xml_sql);

    //사방넷 상품 전송
    $url = 'http://a.sabangnet.co.kr/RTL_API/xml_goods_info2.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_status/'.$send_xml_name['xml_name'].'.xml';    


    $ch = cURL_init();

    cURL_setopt($ch, CURLOPT_URL, $url);
    cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = cURL_exec($ch);
    cURL_close($ch); 

    
$result = 200;
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return false;



}else if($type == "reorder"){

    $decide_date = date("Y-m-d");
    $view_sql="select * from sabang_lt_order_form where sno in  ('{$sno}')";
    $view_result = sql_query($view_sql);
    for($vi = 0 ; $view_row = sql_fetch_array($view_result); $vi++){
    if(!empty($view_row['sabang_ord_no'])){
            $vi_sql = "update sabang_lt_order_view set ov_order_status = '신규주문', ov_distribution_status = '정상' , ov_update_datetime = '{$today}' where ov_IDX = '{$view_row['sabang_ord_no']}'";
            sql_query($vi_sql);
        }
    }
    $sql = " DELETE FROM sabang_lt_order_form  where sno in  ('{$sno}') and dpartner_stat = '물류품절' and (order_invoice is null OR order_invoice ='')  ";
    sql_query($sql);
    

}else if($type == "error"){
    
    $sb_sql = "SELECT * FROM sabang_lt_order_form WHERE  sno = '{$sno}' limit 1 ";
    $sb_result = sql_fetch($sb_sql);

    $result = $sb_result['sabang_ord_no'];
    
    if(!empty($result)){
        //판매등록 삭제
        $reg_sql = "DELETE FROM samjin_order_sale_registration WHERE order_form_no = '{$sno}' ";
        sql_query($reg_sql);
        //출고지시 삭제
        $deli_sql ="DELETE FROM samjin_order_delivery_order WHERE order_form_no = '{$sno}' ";
        sql_query($deli_sql);

        //발주서 송장 삭제 및 상태 물류 품절
        $form_sql = "UPDATE sabang_lt_order_form SET dpartner_stat = '물류품절' , update_dt  = '{$today}' , order_invoice = null , invoice_up_dt = null  WHERE sno = '{$sno}' ";
        sql_query($form_sql);

        $view_sql = "UPDATE sabang_lt_order_view SET ov_invoice_no = null   WHERE ov_idx = '{$result}' ";
        sql_query($view_sql);
    }

}else if($type == "sms"){
    foreach($sno as $sKey) { 
        $sms_sql = "SELECT * FROM sabang_lt_order_form WHERE  sno = '{$sKey}' limit 1 ";
        $sms_result = sql_fetch($sms_sql);
        
        $send_time = '';
        $prodoctsName = $sms_result['samjin_name'];
        $mallMemberName = $sms_result['receive_name'];
        $mallName = $sms_result['mall_name'];
        $mallId = $sms_result['mall_id'];
        $mallOdId = $sms_result['mall_order_no'];
        $mallCtId = $sms_result['sabang_ord_no'];
        if ($sms_result['receive_cel'] && $sms_result['receive_cel'] != '') {
            $mallPhoneNumber = $sms_result['receive_cel'];
        } else {
            $mallPhoneNumber = $sms_result['receive_tel']; 
        }

        $prodoctsName2 = "▶품절상품: $prodoctsName";
    
        $sms_cnt_sql = "SELECT COUNT(*) AS CNT FROM sabang_lt_order_form WHERE  mall_order_no = '{$mallOdId}' AND form_sms_check = 1 ";
        $sms_cnt_result = sql_fetch($sms_cnt_sql);
    
        if ($sms_cnt_result['CNT'] == 0 && $mallId == '15001') {
            $msg_body = "
안녕하세요. $mallMemberName 고객님.
프리미엄 구스 침구 브랜드 리탠다드입니다.
재고부족으로 인하여 품절안내 드리는점 양해부탁드립니다
    
▶구매쇼핑몰: $mallName
▶주문번호: $mallOdId
$prodoctsName2
    
재고소진 상품은 수급이 어려워 부득이하게 환불 진행되며, 영업일 2일 이내에 자동 취소예정입니다. 
다시한번 시간을 내어 주문해주신 고객님께 불편을 드려 죄송합니다. 
라이프라이크 고객님들께 품절보상으로 즉시 사용 가능한 3,000P를 지급해드렸습니다.
    
감사합니다.";
            $id_sql = "SELECT mb_id FROM lt_shop_order WHERE od_id = '{$mallOdId}' limit 1 ";
            $id_result = sql_fetch($id_sql);
            insert_point($id_result['mb_id'], 3000, '품절보상포인트지급', '@soldOutPoint', $id_result['mb_id'],$mallOdId,100);    
        } else {
            $msg_body = "
안녕하세요. $mallMemberName 고객님.
프리미엄 구스 침구 브랜드 리탠다드입니다.
재고부족으로 인하여 품절안내 드리는점 양해부탁드립니다
    
▶구매쇼핑몰: $mallName
▶주문번호: $mallOdId
$prodoctsName2
    
재고소진 상품은 수급이 어려워 부득이하게 환불 진행되며, 영업일 2일 이내에 자동 취소예정입니다. 
다시한번 시간을 내어 주문해주신 고객님께 불편을 드려 죄송합니다. 
               
감사합니다.";
    
        }
    
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
        if ($resbody['result_code'] == '200') {
            $smsStatus = 1;
            $smsCmid = $resbody['cmid'];
        } else {
            $smsStatus = 2;
            $smsCmid = '';
        }
        $update_sql = "UPDATE sabang_lt_order_form SET form_sms_check = 1 WHERE sno = '{$sKey}' ";
        sql_query($update_sql);
        
        $update_sql = "UPDATE sabang_lt_order_view SET ov_sms_check = 1 WHERE ov_IDX = '{$mallCtId}'";
        sql_query($update_sql);

        $ssInsertSql = "INSERT INTO lt_sms_soldout SET ss_mallname = '$mallName', ss_od_id = '$mallOdId', ss_products1 = '$prodoctsName', ss_cart_id1 = '$mallCtId'
        , ss_mb_name = '$mallMemberName', ss_phone_number = '$mallPhoneNumber', ss_status= '$smsStatus', ss_numbers = '1', ss_cmid='$smsCmid'";
        $res = sql_query($ssInsertSql);
        if ($res == 1) {
            $ss_op_id = sql_insert_id();
            $ssUpdateSql = "UPDATE lt_sms_soldout SET ss_op_id = $ss_op_id WHERE ss_id = $ss_op_id";
            sql_query($ssUpdateSql);
        }

    }
}

goto_url("./total_order_form.php?");
