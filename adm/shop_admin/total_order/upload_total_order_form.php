<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];


$file = $_FILES['upload_excel']['tmp_name'];

$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('UTF-8');
$data->read($file);

$toDate = date("YmdHis");

//xml
$today= date("Ymd");


$xml_code = "";
$xml_code = "<?xml version=\"1.0\" encoding=\"euc-kr\" ?>\n";  

$xml_code.="<SABANG_INV_REGI>\n";
$xml_code.="<HEADER>\n";
    $xml_code.="<SEND_COMPAYNY_ID>LITANDARD</SEND_COMPAYNY_ID>\n";
    $xml_code.="<SEND_AUTH_KEY><![CDATA[Z6MV9M8HK9HSK0xx0XYxG7EACRMGJ0KTX6]]></SEND_AUTH_KEY>\n";
    $xml_code.="<SEND_DATE><![CDATA[".$today."]]></SEND_DATE>\n";
    $xml_code.="<SEND_INV_EDIT_YN>N</SEND_INV_EDIT_YN>\n";
$xml_code.="</HEADER>\n";

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
    $sno = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][0]);
    $receive_name =  $data->sheets[0]['cells'][$i][1];
    $sabang_ord_no =  $data->sheets[0]['cells'][$i][17];
    $invoice = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][19]);

    $dpartner_add =  $data->sheets[0]['cells'][$i][16];

    if(strpos($dpartner_add, "경민실업") === false){
        if(strpos($dpartner_add, "마장면") === false){
            $dpartner = "본사";
        }else{
            $dpartner = "어시스트";
        }
    }else{
        $dpartner = "경민실업";
    }
    
    // $goods_nm = iconv("EUC-KR", "UTF-8", $data->sheets[0]['cells'][$i][2]);

    // $BARCODE =strtoupper($data->sheets[0]['cells'][$i][7]);
    // $ORDER_NO =strtoupper($data->sheets[0]['cells'][$i][4]);
    // $COLOR = strtoupper($data->sheets[0]['cells'][$i][29]);
    // $HOCHING = strtoupper($data->sheets[0]['cells'][$i][31]);

    // $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);

    // $add_result_no = $barcode_add[0]['V1'];.
    // $add_result_meg = $barcode_add[0]['RSLT'];

    // $merge_sql = "select count(*) AS cnt from sabang_goods_origin where sabang_goods_cd = '{$orderId}' ";
    // $merge_item = sql_fetch($merge_sql);

    
    if(!empty($sabang_ord_no)){
        $exist_chk = "select * from sabang_lt_order_form where dpartner_id = '{$dpartner}' AND receive_name = '{$receive_name}' AND sabang_ord_no = '{$sabang_ord_no}' and ( order_invoice is null OR order_invoice ='' OR invoice_up_dt is null OR invoice_up_dt ='') ";
        $exist_chk_result = sql_query($exist_chk);
        
        for($ssi = 0 ; $inv_row = sql_fetch_array($exist_chk_result); $ssi++){
            if($inv_row['dpartner_stat'] != '출고전취소' && $inv_row['dpartner_stat'] != '물류품절'){
                if(empty($inv_row['order_invoice']) || empty($inv_row['invoice_up_dt'])){
                    if(!empty($invoice)){

                        //사방넷 송장 xml 파일 생성
                        if($inv_row['mall_id'] != '15001'){
                            if( $inv_row['mall_id'] != '19978'){
                                $xml_code.="<DATA>\n";
                                    $xml_code.="<SABANGNET_IDX><![CDATA[{$inv_row['sabang_ord_no']}]]></SABANGNET_IDX>\n";
                                    $xml_code.="<TAK_CODE><![CDATA[{$inv_row['tak_code']}]]></TAK_CODE>\n";
                                    $xml_code.="<TAK_INVOICE><![CDATA[{$invoice}]]></TAK_INVOICE>\n";
                                    $xml_code.="<DELV_HOPE_DATE></DELV_HOPE_DATE>\n";
                                $xml_code.="</DATA>\n";
                            }
                        }else{
                            // 자사몰
                            $LL_chk = "select * from lt_shop_cart where ct_id = '{$inv_row['sabang_ord_no']}' limit 1";
                            $LL_chk_result=sql_fetch($LL_chk);
                            $tax_name = "select * from sabang_lt_order_view where ov_IDX = '{$inv_row['sabang_ord_no']}' limit 1";
                            $tax_name_result=sql_fetch($tax_name);

                            if($LL_chk_result['ct_status'] == '상품준비중'){
                                $LL_up_sql = "update lt_shop_cart set ct_status='배송중', ct_delivery_company = '{$tax_name_result['ov_delivery_company']}' , ct_invoice = '{$invoice}', ct_invoice_time = '{$toDate}'
                                                where ct_id = '{$inv_row['sabang_ord_no']}'";

                                sql_query($LL_up_sql);
                            }

                        }    


                        $upsql= "update sabang_lt_order_form
                            set order_invoice = '".$invoice."' , invoice_up_dt = '{$toDate}'
                                where sabang_ord_no = '{$sabang_ord_no}' and receive_name = '{$receive_name}' and dpartner_id = '{$dpartner}'
                                ";
                        sql_query($upsql);
                    }
                }
            }
        }
    }

    if(!empty($invoice) && !empty($sabang_ord_no)){
        $deliv_item = "select * from sabang_lt_order_form where sabang_ord_no = '{$sabang_ord_no}' and order_invoice ='{$invoice}'";
        $item_result = sql_query($deliv_item);
        
    
        if(!empty($item_result)){
            for($odoi = 0 ; $odo_row = sql_fetch_array($item_result); $odoi++){

                // $delivery_order_common = "reg_dt = '{$toDate}'
                //                         , order_form_no = '{$odo_row['sno']}'
                //                         , mall_order_no = '{$odo_row['mall_order_no']}'
                //                         , sabang_ord_no = '{$odo_row['sabang_ord_no']}'
                //                         , dpartner_name = '{$odo_row['dpartner_id']}'
                //                         , samjin_name = '{$odo_row['samjin_name']}'
                //                         , samjin_code = '{$odo_row['samjin_code']}'
                //                         , samjin_color = '{$odo_row['order_it_color']}'
                //                         , samjin_size = '{$odo_row['order_it_size']}'
                //                         , warehouse_no = '{$odo_row['warehouse_no']}'
                // ";
            
                // $collum = '';
                // $collum_val = 0;

                // if($odo_row['dpartner_id'] == '어시스트'){
                //     $delivery_order_common .= ", samjin_brand = '{$odo_row['order_it_brand']}'";
                //     $delivery_order_common .= ", dpartner_id = 200 ";
                // }else if($odo_row['dpartner_id'] == '경민실업'){
                //     $delivery_order_common .= ", samjin_brand = ''";
                //     $delivery_order_common .= ", dpartner_id = 100 ";
                // }else{
                //     $delivery_order_common .= ", samjin_brand = ''";
                //     $delivery_order_common .= ", dpartner_id = 300 ";
                // }


            
                // switch($odo_row['mall_id']) {
                //     case '15001' :           $collum = 'm15001';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19940' :           $collum = 'm19940';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19942' :           $collum = 'm19942';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19943' :           $collum = 'm19943';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19944' :           $collum = 'm19944';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19950' :           $collum = 'm19950';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19951' :           $collum = 'm19951';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19952' :           $collum = 'm19952';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19953' :           $collum = 'm19953';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19954' :           $collum = 'm19954';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19955' :           $collum = 'm19955';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19956' :           $collum = 'm19956';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19957' :           $collum = 'm19957';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19958' :           $collum = 'm19958';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19961' :           $collum = 'm19961';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19962' :           $collum = 'm19962';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19963' :           $collum = 'm19963';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19964' :           $collum = 'm19964';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19965' :           $collum = 'm19965';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19966' :           $collum = 'm19966';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19967' :           $collum = 'm19967';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19968' :           $collum = 'm19968';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19970' :           $collum = 'm19970';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19971' :           $collum = 'm19971';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19972' :           $collum = 'm19972';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19973' :           $collum = 'm19973';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19974' :           $collum = 'm19974';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19975' :           $collum = 'm19975';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19976' :           $collum = 'm19976';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19977' :           $collum = 'm19977';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19978' :           $collum = 'm19978';   $collum_val = $odo_row['order_it_cnt'];        break;
                //     case '19979' :           $collum = 'm19979';   $collum_val = $odo_row['order_it_cnt'];        break;
                // }

                // $delivery_order_common .= ",{$collum} = {$collum_val}";
                // ----0628
                // $chk_sql = "select count(*) AS cnt from samjin_order_delivery_order where order_form_no = '{$odo_row['sno']}'";
                // $chk_result = sql_fetch($chk_sql);

                // if($chk_result['cnt'] < 1 ){
                //     // $delivery_order_sql = "insert samjin_order_delivery_order set $delivery_order_common";
                //     // sql_query($delivery_order_sql);

                //     //사방넷 송장 xml 파일 생성
                //     if($odo_row['mall_id'] != '15001'){
                //         if( $odo_row['mall_id'] != '19978'){
                //             $xml_code.="<DATA>\n";
                //                 $xml_code.="<SABANGNET_IDX><![CDATA[{$odo_row['sabang_ord_no']}]]></SABANGNET_IDX>\n";
                //                 $xml_code.="<TAK_CODE><![CDATA[{$odo_row['tak_code']}]]></TAK_CODE>\n";
                //                 $xml_code.="<TAK_INVOICE><![CDATA[{$odo_row['order_invoice']}]]></TAK_INVOICE>\n";
                //                 $xml_code.="<DELV_HOPE_DATE></DELV_HOPE_DATE>\n";
                //             $xml_code.="</DATA>\n";
                //         }
                //     }else{
                //         // 자사몰
                //         $LL_chk = "select * from lt_shop_cart where ct_id = '{$odo_row['sabang_ord_no']}' limit 1";
                //         $LL_chk_result=sql_fetch($LL_chk);
                //         $tax_name = "select * from sabang_lt_order_view where ov_IDX = '{$odo_row['sabang_ord_no']}' limit 1";
                //         $tax_name_result=sql_fetch($tax_name);

                //         if($LL_chk_result['ct_status'] == '상품준비중'){
                //             $LL_up_sql = "update lt_shop_cart set ct_status='배송중', ct_delivery_company = '{$tax_name_result['ov_delivery_company']}' , ct_invoice = '{$odo_row['order_invoice']}', ct_invoice_time = '{$toDate}'
                //                             where ct_id = '{$odo_row['sabang_ord_no']}'";
                            
                //             sql_query($LL_up_sql);
                //         }

                //     }
                // }
                // ----0628


                // $sale_registration_common = "reg_date = '{$toDate}'
                //                             , order_form_no = '{$odo_row['sno']}'
                //                             , order_gb = '{$odo_row['set_check']}'
                //                             , mall_code = '{$odo_row['mall_id']}'
                //                             , mall_name = '{$odo_row['mall_name']}'
                //                             , samjin_name = '{$odo_row['samjin_name']}'
                //                             , samjin_code = '{$odo_row['samjin_code']}'
                //                             , samjin_color = '{$odo_row['order_it_color']}'
                //                             , samjin_size = '{$odo_row['order_it_size']}'
                //                             , order_cnt = '{$odo_row['order_it_cnt']}'
                //                             , order_sale = ''
                //                             , order_majin = ''
                //                             , mall_order_no = '{$odo_row['mall_order_no']}'
                //                             , sabang_ord_no = '{$odo_row['sabang_ord_no']}'
                // ";

                // if($odo_row['mall_id'] == '15001'){
                //     $order_price_info = "select * from sabang_lt_order_view where ov_order_id = '{$odo_row['mall_order_no']}' and ov_ct_id = '{$odo_row['sabang_ord_no']}' limit 1";
                // }else{
                //     $order_price_info = "select * from sabang_lt_order_view where ov_order_id = '{$odo_row['mall_order_no']}' and ov_IDX = '{$odo_row['sabang_ord_no']}' limit 1";
                // }
                
                // $order_price_info_result = sql_fetch($order_price_info);
                // $order_price = "";
                // $order_division_price = "";

                // $self_goods_cd =  $odo_row['sap_code'].$odo_row['order_it_color'].str_replace('*','X' , $odo_row['order_it_size']);
                // $mall_product_id = $odo_row['mall_product_id'];
                // $etc_mall_price_info = "SELECT * FROM samjin_sale_reg_mall_goods_list WHERE goods_id = '{$mall_product_id}' AND mall_id = '{$odo_row['mall_id']}'  limit 1  ";
                // $etc_mall_price_info_result = sql_fetch($etc_mall_price_info);

                // if($odoi == 0){
                //     if(strpos($odo_row['mall_order_no'] , '사은품') === false){
                //         if($odo_row['set_check'] == '001'){
                //             if($odo_row['mall_id'] == '19957' || $odo_row['mall_id'] == '19977' || $odo_row['mall_id'] == '19979' || $odo_row['mall_id'] == '19943'){
                //                 $order_price = $order_price_info_result['ov_pay_cost'];
                //                 $order_division_price = $order_price_info_result['ov_pay_cost'];
                                
                //             }else if($odo_row['mall_id'] == '19963' || $odo_row['mall_id'] == '19950' ){
                //                 if(!empty($etc_mall_price_info_result)){
                //                     $order_cnt = $odo_row['order_it_cnt'];
                //                     $price = $etc_mall_price_info_result['sale_price'];
        
                //                     $order_price = ($price * $order_cnt);
                //                     $order_division_price = ($price * $order_cnt);
                //                 }else{
                //                     $order_price = $order_price_info_result['ov_pay_cost'];
                //                     $order_division_price = $order_price_info_result['ov_pay_cost'];
                //                 }
                               
                //             }else{
                //                 $order_price = $order_price_info_result['ov_total_cost'];
                //                 $order_division_price = $order_price_info_result['ov_total_cost'];
                //             }
                //         }else{
                //             if($odo_row['mall_id'] == '19957' || $odo_row['mall_id'] == '19977' || $odo_row['mall_id'] == '19979' || $odo_row['mall_id'] == '19943'){
                //                 $order_price = $order_price_info_result['ov_pay_cost'];
                //                 $order_division_price = "";
                               
                //             }else if($odo_row['mall_id'] == '19963' || $odo_row['mall_id'] == '19950' ){
                //                 if(!empty($etc_mall_price_info_result)){
                //                     $order_cnt = $odo_row['order_it_cnt'];
                //                     $price = $etc_mall_price_info_result['sale_price'];
        
                //                     $order_price = ($price * $order_cnt);
                //                     $order_division_price = "";
                //                 }else{
                //                     $order_price = $order_price_info_result['ov_pay_cost'];
                //                     $order_division_price = "";
                //                 }
                               
                //             }else{
                //                 $order_price = $order_price_info_result['ov_total_cost'];
                //                 $order_division_price = "";
                               
                //             }
                //         }
                //     }else{
                //         $order_price = "1";
                //         $order_division_price = "1";
                //     }
                // }else{
                //     $order_price = "";
                //     $order_division_price = "";
                // }

                // $sale_registration_common .= ", order_price = '{$order_price}'
                //                             , order_division_price = '{$order_division_price}'       
                //             ";

                // $chk_sql2 = "select count(*) AS cnt from samjin_order_sale_registration where order_form_no = '{$odo_row['sno']}'";
                // $chk_result2 = sql_fetch($chk_sql2);

                // if($chk_result2['cnt'] < 1 ){
                //     $registration_order_sql = "insert samjin_order_sale_registration set $sale_registration_common";
                //     sql_query($registration_order_sql);
                // }
            
            }

        }
    
    }
    
}
$xml_code.="</SABANG_INV_REGI>\n";

$toDate = date("YmdHis");

$file_name =  $toDate."_invoice_send"; //파일명지정
$dir_name = "./sabang_invoice/".$file_name.".xml"; //디렉토리지정
file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함)


$history_sql = "insert into sabang_invoice_xml_history 
            set xml_name = '".$file_name."' 
                , reg_date = '".$toDate."'
            ";
sql_query($history_sql);


// //사방넷 API 전공
    
$send_xml_sql = "select xml_name from sabang_invoice_xml_history where status = 0 order by no desc limit 1";

$send_xml_name = sql_fetch($send_xml_sql);

// $url = 'https://r.sabangnet.co.kr/RTL_API/xml_order_invoice.html?xml_url=http://dev.lifelike.co.kr/adm/shop_admin/total_order/sabang_invoice/'.$send_xml_name['xml_name'].'.xml';
$url = 'https://r.sabangnet.co.kr/RTL_API/xml_order_invoice.html?xml_url=https://lifelike.co.kr/adm/shop_admin/total_order/sabang_invoice/'.$send_xml_name['xml_name'].'.xml';
$ch = cURL_init();


cURL_setopt($ch, CURLOPT_URL, $url);
cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = cURL_exec($ch);
cURL_close($ch); 


goto_url("./total_order_form.php?".$qstr);

?>



