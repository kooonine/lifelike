<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');



$url = 'https://r.sabangnet.co.kr/RTL_API/xml_order_info.html?xml_url=https://lifelike.co.kr/adm/cron/sabang_order/20210930082502_order_collection.xml';
// $url = 'https://r.sabangnet.co.kr/RTL_API/xml_order_info.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_order/20201201180440_order_collection.xml';
$ch = cURL_init();

cURL_setopt($ch, CURLOPT_URL, $url);
cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = cURL_exec($ch);
cURL_close($ch); 

$object = simplexml_load_string($response);

$toDate = date("YmdH");


foreach($object->children() as $orders) {
  $outputs[] = date('Y-m-d H:i:s', time()) . " : 사방 주문 수집 ".$orders->IDX;

  $PRODUCT_NAME = preg_replace("/[\"\']/i", "",  $orders->PRODUCT_NAME);

  // idx check
  $idxSelectSql = "SELECT COUNT(*) AS CNT FROM sabang_order_origin WHERE IDX ='{$orders->IDX}'";
  $idxCheck = sql_fetch($idxSelectSql);

  if($idxCheck['CNT'] <1) {
    $sql_common = " receive_date = '{$toDate}'
                    ,IDX = '{$orders->IDX}'
                    ,ORDER_ID = '{$orders->ORDER_ID}'
                    ,MALL_ID = '{$orders->MALL_ID}'
                    ,MALL_USER_ID = '{$orders->MALL_USER_ID}'
                    ,ORDER_STATUS = '{$orders->ORDER_STATUS}'
                    ,USER_ID = '{$orders->USER_ID}'
                    ,USER_NAME = '{$orders->USER_NAME}'
                    ,USER_TEL = '{$orders->USER_TEL}'
                    ,USER_CEL = '{$orders->USER_CEL}'
                    ,USER_EMAIL = '{$orders->USER_EMAIL}'
                    ,RECEIVE_TEL = '{$orders->RECEIVE_TEL}'
                    ,RECEIVE_CEL = '{$orders->RECEIVE_CEL}'
                    ,RECEIVE_EMAIL = '{$orders->RECEIVE_EMAIL}'
                    ,DELV_MSG = '{$orders->DELV_MSG}'
                    ,RECEIVE_NAME = '{$orders->RECEIVE_NAME}'
                    ,RECEIVE_ZIPCODE = '{$orders->RECEIVE_ZIPCODE}'
                    ,RECEIVE_ADDR = '{$orders->RECEIVE_ADDR}'
                    ,TOTAL_COST = '{$orders->TOTAL_COST}'
                    ,PAY_COST = '{$orders->PAY_COST}'
                    ,ORDER_DATE = '{$orders->ORDER_DATE}'
                    ,PARTNER_ID = '{$orders->PARTNER_ID}'
                    ,DPARTNER_ID = '{$orders->DPARTNER_ID}'
                    ,MALL_PRODUCT_ID = '{$orders->MALL_PRODUCT_ID}'
                    ,PRODUCT_ID = '{$orders->PRODUCT_ID}'
                    ,SKU_ID = '{$orders->SKU_ID}'
                    ,P_PRODUCT_NAME = '{$orders->P_PRODUCT_NAME}'
                    ,P_SKU_VALUE = '{$orders->P_SKU_VALUE}'
                    ,PRODUCT_NAME = '{$PRODUCT_NAME}'
                    ,SALE_COST = '{$orders->SALE_COST}'
                    ,MALL_WON_COST = '{$orders->MALL_WON_COST}'
                    ,WON_COST = '{$orders->WON_COST}'
                    ,SKU_VALUE = '{$orders->SKU_VALUE}'
                    ,SALE_CNT = '{$orders->SALE_CNT}'
                    ,DELIVERY_METHOD_STR = '{$orders->DELIVERY_METHOD_STR}'
                    ,DELV_COST = '{$orders->DELV_COST}'
                    ,COMPAYNY_GOODS_CD = '{$orders->COMPAYNY_GOODS_CD}'
                    ,SKU_ALIAS = '{$orders->SKU_ALIAS}'
                    ,BOX_EA = '{$orders->BOX_EA}'
                    ,JUNG_CHK_YN = '{$orders->JUNG_CHK_YN}'
                    ,MALL_ORDER_SEQ = '{$orders->MALL_ORDER_SEQ}'
                    ,MALL_ORDER_ID = '{$orders->MALL_ORDER_ID}'
                    ,ETC_FIELD3 = '{$orders->ETC_FIELD3}'
                    ,ORDER_GUBUN = '{$orders->ORDER_GUBUN}'
                    ,P_EA = '{$orders->P_EA}'
                    ,REG_DATE = '{$orders->REG_DATE}'
                    ,ORDER_ETC_1 = '{$orders->ORDER_ETC_1}'
                    ,ORDER_ETC_2 = '{$orders->ORDER_ETC_2}'
                    ,ORDER_ETC_3 = '{$orders->ORDER_ETC_3}'
                    ,ORDER_ETC_4 = '{$orders->ORDER_ETC_4}'
                    ,ORDER_ETC_5 = '{$orders->ORDER_ETC_5}'
                    ,ORDER_ETC_6 = '{$orders->ORDER_ETC_6}'
                    ,ORDER_ETC_7 = '{$orders->ORDER_ETC_7}'
                    ,ORDER_ETC_8 = '{$orders->ORDER_ETC_8}'
                    ,ORDER_ETC_9 = '{$orders->ORDER_ETC_9}'
                    ,ORDER_ETC_10 = '{$orders->ORDER_ETC_10}'
                    ,ORDER_ETC_11 = '{$orders->ORDER_ETC_11}'
                    ,ORDER_ETC_12 = '{$orders->ORDER_ETC_12}'
                    ,ORDER_ETC_13 = '{$orders->ORDER_ETC_13}'
                    ,ORDER_ETC_14 = '{$orders->ORDER_ETC_14}'
                    ,ord_field2 = '{$orders->ord_field2}'
                    ,copy_idx = '{$orders->copy_idx}'
                    ,GOODS_NM_PR = '{$orders->GOODS_NM_PR}'
                    ,GOODS_KEYWORD = '{$orders->GOODS_KEYWORD}'
                    ,ORD_CONFIRM_DATE = '{$orders->ORD_CONFIRM_DATE}'
                    ,RTN_DT = '{$orders->RTN_DT}'
                    ,CHNG_DT = '{$orders->CHNG_DT}'
                    ,DELIVERY_CONFIRM_DATE = '{$orders->DELIVERY_CONFIRM_DATE}'
                    ,CANCEL_DT = '{$orders->CANCEL_DT}'
                    ,CLASS_CD1 = '{$orders->CLASS_CD1}'
                    ,CLASS_CD2 = '{$orders->CLASS_CD2}'
                    ,CLASS_CD3 = '{$orders->CLASS_CD3}'
                    ,CLASS_CD4 = '{$orders->CLASS_CD4}'
                    ,BRAND_NM = '{$orders->BRAND_NM}'
                    ,DELIVERY_ID = '{$orders->DELIVERY_ID}'
                    ,INVOICE_NO = '{$orders->INVOICE_NO}'
                    ,HOPE_DELV_DATE = '{$orders->HOPE_DELV_DATE}'
                    ,FLD_DSP = '{$orders->FLD_DSP}'
                    ,INV_SEND_MSG = '{$orders->INV_SEND_MSG}'
                    ,MODEL_NO = '{$orders->MODEL_NO}'
                    ,SET_GUBUN = '{$orders->SET_GUBUN}'
                    ,ETC_MSG = '{$orders->ETC_MSG}'
                    ,DELV_MSG1 = '{$orders->DELV_MSG1}'
                    ,MUL_DELV_MSG = '{$orders->MUL_DELV_MSG}'
                    ,BARCODE = '{$orders->BARCODE}'
                    ,INV_SEND_DM = '{$orders->INV_SEND_DM}'
                    ,DELIVERY_METHOD_STR2 = '{$orders->DELIVERY_METHOD_STR2}'
    ";
    if(!empty($orders->IDX)){
      $sql = " insert sabang_order_origin set $sql_common ";
      sql_query($sql);
    }
  }
}

// $sql = "SELECT ps_id , jo_user FROM lt_job_order  GROUP BY ps_id ";

// $row_jo = sql_query($sql);
// for($i=0; $row = sql_fetch_array($row_jo); $i++){
//     $user = $row['jo_user'];
//     $no = $row['ps_id'];
//     if(!empty($user)){
//         $upSql = "UPDATE lt_prod_schedule SET ps_user = '{$user}'  WHERE ps_id  = '$no' ";
//         sql_query($upSql);
//     }
// }



// $sql = "SELECT ps_id , jo_gubun FROM lt_job_order  GROUP BY ps_id ";

// $row_jo = sql_query($sql);
// for($i=0; $row = sql_fetch_array($row_jo); $i++){
//     $jo_gubun = $row['jo_gubun'];
//     $no = $row['ps_id'];
//     if(!empty($jo_gubun)){
//         $upSql = "UPDATE lt_prod_schedule SET ps_job_gubun = '{$jo_gubun}'  WHERE ps_id  = '$no' ";
//         sql_query($upSql);
//     }
// }


// $sql = "SELECT * FROM lt_prod_schedule where ps_display = 'Y' AND ps_item_nm is null";
// $ps_res = sql_query($sql);

// for($i=0; $row = sql_fetch_array($ps_res); $i++){
//     $no = $row['ps_id'];
//     $name_array = explode('(', $row['ps_it_name']);
//     $ps_item_nm = $name_array[0];


//     $upSql = "UPDATE lt_prod_schedule SET ps_item_nm = '{$ps_item_nm}'  WHERE ps_id  = '$no' ";
//     sql_query($upSql);

// }

// $sql = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%' AND mall_order_no = 'OD202104166856617' AND sabang_ord_no = 3930059";
// $res = sql_query($sql);

// $cnt_sql = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%' AND mall_order_no = 'OD202104166856617' AND sabang_ord_no = 3930059 limit 1";
// $cnt_res = sql_fetch($cnt_sql);

// echo $cnt_res['receive_name'];
// echo $cnt_res['sabang_ord_no'];
// $cp_receive_name = $cnt_res['receive_name'];
// $cp_sabang_ord_no = $cnt_res['sabang_ord_no'];

// for($i = 0 ; $row = sql_fetch_array($res);  $i++ ){
    
//     if($i != 0){
//         $sno = $row['sno'];
//         $cp_name = $cp_receive_name.$i;
//         $cp_sb_no = $cp_sabang_ord_no.'_'.$i;
//         $up_sql = "UPDATE sabang_lt_order_form SET receive_name = '{$cp_name}' , sabang_ord_no = '{$cp_sb_no}' WHERE sno = '$sno' ";  
//         // echo $up_sql;
//         sql_query($up_sql);
        
//     }


// }

// $deliv_item = "SELECT * FROM sabang_lt_order_form WHERE sabang_ord_no = '106979370'";
//         $item_result = sql_query($deliv_item);
        
    
//         if(!empty($item_result)){
//             for($odoi = 0 ; $odo_row = sql_fetch_array($item_result); $odoi++){

//                 $delivery_order_common = "reg_dt = '{$toDate}'
//                                         , order_form_no = '{$odo_row['sno']}'
//                                         , mall_order_no = '{$odo_row['mall_order_no']}'
//                                         , sabang_ord_no = '{$odo_row['sabang_ord_no']}'
//                                         , dpartner_name = '{$odo_row['dpartner_id']}'
//                                         , samjin_name = '{$odo_row['samjin_name']}'
//                                         , samjin_code = '{$odo_row['samjin_code']}'
//                                         , samjin_color = '{$odo_row['order_it_color']}'
//                                         , samjin_size = '{$odo_row['order_it_size']}'
//                                         , warehouse_no = '{$odo_row['warehouse_no']}'
//                 ";
            
//                 $collum = '';
//                 $collum_val = 0;

//                 if($odo_row['dpartner_id'] == '어시스트'){
//                     $delivery_order_common .= ", samjin_brand = '{$odo_row['order_it_brand']}'";
//                     $delivery_order_common .= ", dpartner_id = 200 ";
//                 }else if($odo_row['dpartner_id'] == '경민실업'){
//                     $delivery_order_common .= ", samjin_brand = ''";
//                     $delivery_order_common .= ", dpartner_id = 100 ";
//                 }else{
//                     $delivery_order_common .= ", samjin_brand = ''";
//                     $delivery_order_common .= ", dpartner_id = 300 ";
//                 }


            
//                 switch($odo_row['mall_id']) {
//                     case '15001' :           $collum = 'm15001';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19940' :           $collum = 'm19940';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19942' :           $collum = 'm19942';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19943' :           $collum = 'm19943';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19944' :           $collum = 'm19944';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19950' :           $collum = 'm19950';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19951' :           $collum = 'm19951';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19952' :           $collum = 'm19952';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19953' :           $collum = 'm19953';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19954' :           $collum = 'm19954';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19955' :           $collum = 'm19955';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19956' :           $collum = 'm19956';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19957' :           $collum = 'm19957';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19958' :           $collum = 'm19958';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19961' :           $collum = 'm19961';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19962' :           $collum = 'm19962';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19963' :           $collum = 'm19963';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19964' :           $collum = 'm19964';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19965' :           $collum = 'm19965';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19966' :           $collum = 'm19966';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19967' :           $collum = 'm19967';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19968' :           $collum = 'm19968';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19970' :           $collum = 'm19970';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19971' :           $collum = 'm19971';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19972' :           $collum = 'm19972';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19973' :           $collum = 'm19973';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19974' :           $collum = 'm19974';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19975' :           $collum = 'm19975';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19976' :           $collum = 'm19976';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19977' :           $collum = 'm19977';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19978' :           $collum = 'm19978';   $collum_val = $odo_row['order_it_cnt'];        break;
//                     case '19979' :           $collum = 'm19979';   $collum_val = $odo_row['order_it_cnt'];        break;
//                 }
            
//                 $delivery_order_common .= ",{$collum} = {$collum_val}";
                
//                 $chk_sql = "select count(*) AS cnt from samjin_order_delivery_order where order_form_no = '{$odo_row['sno']}'";
//                 $chk_result = sql_fetch($chk_sql);

//                 if($chk_result['cnt'] < 1 ){
//                     $delivery_order_sql = "insert samjin_order_delivery_order set $delivery_order_common";
//                     sql_query($delivery_order_sql);

//                     //사방넷 송장 xml 파일 생성
//                     if($odo_row['mall_id'] != '15001'){
//                         if( $odo_row['mall_id'] != '19978'){
//                             $xml_code.="<DATA>\n";
//                                 $xml_code.="<SABANGNET_IDX><![CDATA[{$odo_row['sabang_ord_no']}]]></SABANGNET_IDX>\n";
//                                 $xml_code.="<TAK_CODE><![CDATA[{$odo_row['tak_code']}]]></TAK_CODE>\n";
//                                 $xml_code.="<TAK_INVOICE><![CDATA[{$odo_row['order_invoice']}]]></TAK_INVOICE>\n";
//                                 $xml_code.="<DELV_HOPE_DATE></DELV_HOPE_DATE>\n";
//                             $xml_code.="</DATA>\n";
//                         }
//                     }else{
//                         // 자사몰
//                         $LL_chk = "select * from lt_shop_cart where ct_id = '{$odo_row['sabang_ord_no']}' limit 1";
//                         $LL_chk_result=sql_fetch($LL_chk);
//                         $tax_name = "select * from sabang_lt_order_view where ov_IDX = '{$odo_row['sabang_ord_no']}' limit 1";
//                         $tax_name_result=sql_fetch($tax_name);

//                         if($LL_chk_result['ct_status'] == '상품준비중'){
//                             $LL_up_sql = "update lt_shop_cart set ct_status='배송중', ct_delivery_company = '{$tax_name_result['ov_delivery_company']}' , ct_invoice = '{$odo_row['order_invoice']}', ct_invoice_time = '{$toDate}'
//                                             where ct_id = '{$odo_row['sabang_ord_no']}'";
                            
//                             sql_query($LL_up_sql);
//                         }

//                     }
//                 }


//                 $sale_registration_common = "reg_date = '{$toDate}'
//                                             , order_form_no = '{$odo_row['sno']}'
//                                             , order_gb = '{$odo_row['set_check']}'
//                                             , mall_code = '{$odo_row['mall_id']}'
//                                             , mall_name = '{$odo_row['mall_name']}'
//                                             , samjin_name = '{$odo_row['samjin_name']}'
//                                             , samjin_code = '{$odo_row['samjin_code']}'
//                                             , samjin_color = '{$odo_row['order_it_color']}'
//                                             , samjin_size = '{$odo_row['order_it_size']}'
//                                             , order_cnt = '{$odo_row['order_it_cnt']}'
//                                             , order_sale = ''
//                                             , order_majin = ''
//                                             , mall_order_no = '{$odo_row['mall_order_no']}'
//                                             , sabang_ord_no = '{$odo_row['sabang_ord_no']}'
//                 ";

//                 if($odo_row['mall_id'] == '15001'){
//                     $order_price_info = "select * from sabang_lt_order_view where ov_order_id = '{$odo_row['mall_order_no']}' and ov_ct_id = '{$odo_row['sabang_ord_no']}' limit 1";
//                 }else{
//                     $order_price_info = "select * from sabang_lt_order_view where ov_order_id = '{$odo_row['mall_order_no']}' and ov_IDX = '{$odo_row['sabang_ord_no']}' limit 1";
//                 }
                
//                 $order_price_info_result = sql_fetch($order_price_info);
//                 $order_price = "";
//                 $order_division_price = "";

//                 $self_goods_cd =  $odo_row['sap_code'].$odo_row['order_it_color'].str_replace('*','X' , $odo_row['order_it_size']);
//                 $etc_mall_price_info = "SELECT * FROM samjin_sale_reg_mall_goods_list WHERE prod_code = '{$self_goods_cd}' AND mall_id = '{$odo_row['mall_id']}'  limit 1  ";
//                 $etc_mall_price_info_result = sql_fetch($etc_mall_price_info);

//                 if($odoi == 0){
//                     if(strpos($odo_row['mall_order_no'] , '사은품') === false){
//                         if($odo_row['set_check'] == '001'){
//                             if($odo_row['mall_id'] == '19957' || $odo_row['mall_id'] == '19977' || $odo_row['mall_id'] == '19979' || $odo_row['mall_id'] == '19943'){
//                                 $order_price = $order_price_info_result['ov_pay_cost'];
//                                 $order_division_price = $order_price_info_result['ov_pay_cost'];
                                
//                             }else if($odo_row['mall_id'] == '19963' || $odo_row['mall_id'] == '19950'){
//                                 if(!empty($etc_mall_price_info_result)){
//                                     $order_cnt = $odo_row['order_it_cnt'];
//                                     $price = $etc_mall_price_info_result['sale_price'];
        
//                                     $order_price = ($price * $order_cnt);
//                                     $order_division_price = ($price * $order_cnt);
//                                 }else{
//                                     $order_price = $order_price_info_result['ov_pay_cost'];
//                                     $order_division_price = $order_price_info_result['ov_pay_cost'];
//                                 }
                               
//                             }else{
//                                 $order_price = $order_price_info_result['ov_total_cost'];
//                                 $order_division_price = $order_price_info_result['ov_total_cost'];
//                             }
//                         }else{
//                             if($odo_row['mall_id'] == '19957' || $odo_row['mall_id'] == '19963' || $odo_row['mall_id'] == '19977' || $odo_row['mall_id'] == '19979' || $odo_row['mall_id'] == '19943'){
//                                 $order_price = $order_price_info_result['ov_pay_cost'];
//                                 $order_division_price = "";
                               
//                             }else{
//                                 $order_price = $order_price_info_result['ov_total_cost'];
//                                 $order_division_price = "";
                               
//                             }
//                         }
//                     }else{
//                         $order_price = "1";
//                         $order_division_price = "1";
//                     }
//                 }else{
//                     $order_price = "";
//                     $order_division_price = "";
//                 }

//                 $sale_registration_common .= ", order_price = '{$order_price}'
//                                             , order_division_price = '{$order_division_price}'       
//                             ";

//                 $chk_sql2 = "select count(*) AS cnt from samjin_order_sale_registration where order_form_no = '{$odo_row['sno']}'";
//                 $chk_result2 = sql_fetch($chk_sql2);

//                 if($chk_result2['cnt'] < 1 ){
//                     $registration_order_sql = "insert samjin_order_sale_registration set $sale_registration_common";
//                     sql_query($registration_order_sql);
//                 }
            
//             }

//         }


?>
