<?
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');
$now_date = date("Y-m-d H:i:s");
if ($buttonType == 'handCancel') {
    $kuengmin_meg = 0;
    $assist_meg = 0;
    $updateSql = "UPDATE sabang_lt_order_view SET ov_order_status = '출고전취소', ov_distribution_status = '출고전취소', ov_update_datetime = '{$now_date}' WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    sql_query($updateSql);

    $ordSql = "SELECT * FROM sabang_lt_order_view WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    $ordresult = sql_query($ordSql);
    for($or = 0 ; $ord_row = sql_fetch_array($ordresult); $or++){
        if(!empty($ord_row['ov_IDX'])){
            $formUpSql = "UPDATE sabang_lt_order_form SET dpartner_stat  = '출고전취소', update_dt = '{$now_date}' WHERE sabang_ord_no = '{$ord_row['ov_IDX']}'";
            sql_query($formUpSql);

            if ($ord_row['ov_dpartner'] == '경민실업') $kuengmin_meg++;
            if ($ord_row['ov_dpartner'] == '어시스트') $assist_meg++;

        }
    }

    if($kuengmin_meg > 0){

        $msg_body = "[리탠다드] 취소건이 있습니다.";
        $param = array('send_time' => $send_time
        ,'dest_phone' => '01055687220,'
        ,'dest_name' => ''
        ,'send_phone' => '0234947641'
        ,'send_name' => 'LITANDARD'
        ,'subject' => '출고전취소안내'
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
        } else {
        
        }
    }
    if($assist_meg > 0){
        $msg_body = "[리탠다드] 취소건이 있습니다.";
        $param = array('send_time' => $send_time
        ,'dest_phone' => '01034469808'
        ,'dest_name' => ''
        ,'send_phone' => '0234947641'
        ,'send_name' => 'LITANDARD'
        ,'subject' => '출고전취소안내'
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
        } else {
        
        }
        $msg_body = "[리탠다드] 취소건이 있습니다.";
        $param = array('send_time' => $send_time
        ,'dest_phone' => '01041635912'
        ,'dest_name' => ''
        ,'send_phone' => '0234947641'
        ,'send_name' => 'LITANDARD'
        ,'subject' => '출고전취소안내'
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
        } else {
        
        }

        $msg_body = "[리탠다드] 취소건이 있습니다.";
        $param = array('send_time' => $send_time
        ,'dest_phone' => '01055687220,'
        ,'dest_name' => ''
        ,'send_phone' => '0234947641'
        ,'send_name' => 'LITANDARD'
        ,'subject' => '출고전취소안내'
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
        } else {
        
        }
    }
}
if ($buttonType == 'companySave') {
    $updateSql = "UPDATE sabang_lt_order_view SET ov_dpartner = '$saveDpart', ov_delivery_company_code = '$saveCom', ov_delivery_company = '$saveComName' WHERE slov_id = $slov_id";
    sql_query($updateSql);
}
if ($buttonType == 'hold') {
    $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status ='출고보류' WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    sql_query($updateSql);
}
if ($buttonType == 'etc') {
    $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status ='정상' WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    sql_query($updateSql);
}
if ($buttonType == 'soldoutCancel') {
    $updateSql = "UPDATE sabang_lt_order_view SET ov_order_status ='품절취소' WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    sql_query($updateSql);
}
if ($buttonType == 'mapping') {
    $mappingCheck = 0;
    $mappingFailId = NULL;
    $mappingSuccessId = NULL;
    $mappingSuccessKey = '';

    foreach($mappingOpt as $key=>$value) {
        preg_match_all("/[^() || \-\ \/\,]+/", $value,$c);
        $optPlus = 0;
        $box_ex = 0;

        $optionsArr = array();
        foreach($c[0] as $opc) {
            if (strlen($opc) > 14) {
                if(substr($opc, 0, 1)=='M' || substr($opc, 0, 1)=='Z'){
                    if (strpos($opc,'+')!== false) {
                        preg_match_all("/[^ \+\,]+/", $opc, $opcPlus);
                        for($i=0; $i<$opcPlus[0][1]; $i++) {
                            array_push($optionsArr,  $opcPlus[0][0]);
                        }
                    } else {
                        array_push($optionsArr,  $opc);
                    }
                }
            }
        }


        foreach($optionsArr as $a) {
            if (strlen($a) > 14) {
                if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
                    $mappingCheck = 1;
                    $box_ex += 1;
                    $sapCode12 = substr($a, 0, 12);
                    $color = substr($a, 12, 2);
                    $size = substr($a, 14);
                    $strSize = array("x","X");  
                    $size = str_replace($strSize,'*', $size);
                    $newSlov_id = $key;
                    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
                    $g5['connect_samjindb'] = $connect_db;
                    $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM , WONGA FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
                    $rsSamjin = mssql_sql_query($sqlSamjin);
                    $num_rows = mssql_sql_num_rows($rsSamjin);

                    if (!$num_rows || $num_rows == null || $num_rows ==0) {
                        $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = NULL ,samjin_link_check = 2, ov_samjin_name = NULL, ov_samjin_code = NULL , ov_dpartner = NULL, ov_stock1 = NULL, ov_stock2 = NULL, ov_stock3 = NULL, ov_warehouse = NULL, ov_color = NULL, ov_size = NULL, ov_sap_code = NULL, ov_delivery_company = NULL, ov_delivery_company_code = NULL, ov_qty_form = NULL
                        WHERE slov_id = '$key' OR sub_slov_id = '$key'";
                        sql_query($updateSql);
                        // $result = '매핑에 실패하였습니다 옵션을 확인해주세요.';
                        // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                        // return false;
                    }
                    $ov_samjin_name = '';
                    $ov_samjin_code = '';
                    $ov_sap_code = '';
                    $ov_samjin_wonga = 0;

                    for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
                      $ov_samjin_name = $samrow['ITEM'];
                      $ov_samjin_code = $samrow['ORDER_NO'];
                      $ov_sap_code = $samrow['SAP_CODE'];
                      $ov_samjin_wonga = $samrow['WONGA'];
                    }

                    if ($ov_samjin_code=='') {
                        $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = '' , samjin_link_check = 2
                        WHERE slov_id = '$key' OR sub_slov_id = '$key'";
                        sql_query($updateSql); 
                        // $result = '매핑에 실패하였습니다 옵션을 확인해주세요.';
                        // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                        // return false;
                    } else {
                        $optPlus += 1;
                        if ($optPlus == 1) {
                            $deleteSql = "DELETE FROM sabang_lt_order_view WHERE sub_slov_id = '$key'";
                            sql_query($deleteSql);
                            $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = 0  WHERE slov_id = '$key'";
                            sql_query($updateSql);
                        }
                        if ($box_ex > 1) {
                            $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID,ov_mapping_code,ov_set_code,copy_idx) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID,ov_mapping_code,ov_set_code,copy_idx FROM sabang_lt_order_view WHERE slov_id = {$key}";
                        //   $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_samjin_name,ov_samjin_code,ov_options,ov_options_modify,ov_dpartner,ov_stock1,ov_stock2,ov_distribution_status,ov_qty,ov_color,ov_size,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_samjin_name,ov_samjin_code,ov_options,ov_options_modify,ov_dpartner,ov_stock1,ov_stock2,ov_distribution_status,ov_qty,ov_color,ov_size,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime FROM sabang_lt_order_view WHERE slov_id = {$key}";
                          $res = sql_query($insertSql);
                          if ($res) $newSlov_id = sql_insert_id();
                        }

                        $selectSql = "SELECT ov_order_id,ov_mall_id,ov_order_name, ov_qty, ov_it_name FROM sabang_lt_order_view WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}') LIMIT 1";
                        $mall = sql_fetch($selectSql);
                        $ov_order_id = $mall['ov_order_id'];
                        $ov_mall_id = $mall['ov_mall_id'];
                        $ov_order_name = $mall['ov_order_name'];
                        $ov_qty = $mall['ov_qty'];
                        $ov_it_name = $mall['ov_it_name'];

                        $ov_stock1 = 0;
                        $ov_stock2 = 0;
                        $ov_stock3 = 0;
                        $warehouse1 = 0;
                        $warehouse2 = 0;
                        $warehouse3 = 0;
                        $wh_stock1 = 0;
                        $wh_stock2 = 0;
                        $wh_stock3 = 0;
                        $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
                        $ov_distribution_status = '1';
                        if (count($stockSamjin) == 0) {
                        } else {
                            if (strpos($ov_it_name,'옥의티')!== false || (strpos($row['ov_it_name'],'리퍼')!== false && $row['ov_mall_id'] == '19963')) {
                                // $ov_distribution_status = '리퍼';
                            } 
                            for ($j =0; $j < count($stockSamjin); $j++) {
                                if (strpos($ov_it_name,'옥의티')!== false || (strpos($row['ov_it_name'],'리퍼')!== false && $row['ov_mall_id'] == '19963')) {
                                    if ($stockSamjin[$j]['C_NO'] == 45) {
                                        if ($wh_stock2 < 1) {
                                            $wh_stock2 = $stockSamjin[$j]['STOCK2'];
                                            $warehouse2 = $stockSamjin[$j]['C_NO'];
                                        }
                                        $ov_stock2 += $stockSamjin[$j]['STOCK2'];
                                        if($wh_stock2 < $stockSamjin[$j]['STOCK2'] ){
                                            $wh_stock2 = $stockSamjin[$j]['STOCK2'];
                                            $warehouse2 = $stockSamjin[$j]['C_NO'];
                                        }
                                    } 
                                } else if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4 ||  $stockSamjin[$j]['C_NO'] == 8) {
                                    if ($wh_stock2 < 1) {
                                      $wh_stock2 = $stockSamjin[$j]['STOCK2'];
                                      $warehouse2 = $stockSamjin[$j]['C_NO'];
                                    }
                                    $ov_stock2 += $stockSamjin[$j]['STOCK2'];
                                    if($wh_stock2 < $stockSamjin[$j]['STOCK2'] ){
                                      $wh_stock2 = $stockSamjin[$j]['STOCK2'];
                                      $warehouse2 = $stockSamjin[$j]['C_NO'];
                                    }
                                }
                                // else if ($stockSamjin[$j]['C_NO'] == 30 || $stockSamjin[$j]['C_NO'] == 33 || $stockSamjin[$j]['C_NO'] == 34) {
                                //   if ($wh_stock1 < 1) {
                                //     $wh_stock1 = $stockSamjin[$j]['STOCK2'];
                                //     $warehouse1 = $stockSamjin[$j]['C_NO'];
                                //   }
                                //   $ov_stock1 += $stockSamjin[$j]['STOCK2'];
                                //   if($wh_stock1 < $stockSamjin[$j]['STOCK2'] ){
                                //     $wh_stock1 = $stockSamjin[$j]['STOCK2'];
                                //     $warehouse1 = $stockSamjin[$j]['C_NO'];
                                //   }
                                // } else if ($stockSamjin[$j]['C_NO'] == 92 ) {
                                //   $ov_stock3 += $stockSamjin[$j]['STOCK2'];
                                //   if ($wh_stock3 < 1) {
                                //     $wh_stock3 = $stockSamjin[$j]['STOCK2'];
                                //     $warehouse3 = $stockSamjin[$j]['C_NO'];
                                //   }
                                //   if($wh_stock3 < $stockSamjin[$j]['STOCK2'] ){
                                //     $wh_stock3 = $stockSamjin[$j]['STOCK2'];
                                //     $warehouse3 = $stockSamjin[$j]['C_NO'];
                                //   }
                                // }
                            } 
                        }
                        
                        $fromQty = 1;
                        $sapCodeBrand = substr($sapCode12, 2, 1);
                        if ($sapCodeBrand =='D' || $sapCodeBrand =='d') {
                          $sapCodeCheck = substr($sapCode12, 9, 3);
                            if ($sapCodeCheck == '201' || $sapCodeCheck == '202' || $sapCodeCheck == '203') {
                                $fromQty = 2;
                            }
                        }
                        if ($ov_stock2 != 0 && $ov_stock2 >= (int)$ov_qty * $fromQty) {
                            $ov_dpartner = '어시스트';
                            $ov_delivery_company = 'CJ대한통운';
                            $ov_delivery_company_code = '003';
                            $ov_distribution_status = NULL;
                            $ov_warehouse = $warehouse2;
                        } else if ($ov_stock1 != 0 && $ov_stock1 >= (int)$ov_qty * $fromQty) {
                            $ov_dpartner = '경민실업';
                            $ov_delivery_company = 'CJ대한통운';
                            $ov_delivery_company_code = '003';
                            $ov_distribution_status = NULL;
                            $ov_warehouse = $warehouse1;
                        } else if ($ov_stock3 != 0 && $ov_stock3 >= (int)$ov_qty * $fromQty) {
                          $ov_dpartner = '본사';
                          $ov_delivery_company = '로젠택배';
                          $ov_delivery_company_code = '007';
                          $ov_distribution_status = NULL;
                          $ov_warehouse = $warehouse3;
                        } else {
                            $ov_distribution_status = '품절';
                            $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status'  WHERE slov_id = '{$key}' OR sub_slov_id = '{$key}'";
                            sql_query($updateSql); 
                            $ov_dpartner = null;
                            $ov_delivery_company = null;
                            $ov_delivery_company_code = null;
                            $ov_warehouse = null;
                        }
                        if (strpos($row['ov_it_name'],'옥의티')!== false && strpos($row['ov_it_name'],'리퍼')!== false && $row['ov_mall_id'] == '19963') {
                            $ov_dpartner = '어시스트';
                            $ov_delivery_company = 'CJ대한통운';
                            $ov_delivery_company_code = '003';
                        }
                        $whereCheck = '';
                        if ($ov_mall_id == '15001') {
                            $whereCheck = " ov_order_id = '$ov_order_id' AND ";
                        } else {
                            $whereCheck = " (slov_id = '$key' OR sub_slov_id = '$key' OR ov_order_id LIKE ('{$ov_order_id}%') AND ov_mall_id ='$ov_mall_id' AND ov_order_name ='$ov_order_name') AND ";
                            $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$key}'";
                            $set = sql_fetch($selectSql);
                            if ($set['cnt'] >0) {
                              $setCheck = '002';
                              $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002'  WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
                              sql_query($updateSql);
                            }
                        }
                        $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE $whereCheck ov_distribution_status = '품절' AND slov_id != '$newSlov_id'";
                        $soldOut = sql_fetch($selectSql);
                        $soldOutCnt = $soldOut['CNT'];
                        if ($soldOutCnt > 0) {
                          $ov_distribution_status = '품절';
                        } 

                        $ov_options_modify = $sapCode12.$color.$size;
                        $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_samjin_wonga = '$ov_samjin_wonga', ov_dpartner = '$ov_dpartner', 
                                      ov_stock1 = '$ov_stock1', ov_stock2 = '$ov_stock2', ov_stock3 = '$ov_stock3', ov_warehouse = '$ov_warehouse', ov_color = '$color', ov_size = '$size', ov_sap_code = '$ov_sap_code', ov_delivery_company = '$ov_delivery_company', ov_delivery_company_code = '$ov_delivery_company_code',
                                      ov_options_modify = '$ov_options_modify', ov_distribution_status = IF('$ov_distribution_status'='1', ov_distribution_status,'$ov_distribution_status'), ov_qty_form = ov_qty * $fromQty
                                      WHERE slov_id = '$newSlov_id'";
                        sql_query($updateSql); 

                        $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$key}'";
                        $set = sql_fetch($selectSql);
                        if ($set['cnt'] >0) {
                          $setCheck = '002';
                          $wonga_sum_sql = "SELECT SUM(ov_samjin_wonga) AS won_sum FROM sabang_lt_order_view WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}') ";
                          $wonga_sum_res =  sql_fetch($wonga_sum_sql);
                          $wonga_sum = $wonga_sum_res['won_sum'];
                          $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002' , ov_wonga_sum = '{$wonga_sum}'  WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
                          sql_query($updateSql);
                        }
                        //  check
                        $selectSql = "SELECT IFNULL(MAX(ov_sum_sno+1), 1) AS ov_sum_sno FROM sabang_lt_order_view WHERE $whereCheck ov_dpartner = '$ov_dpartner' ORDER BY ov_sum_sno DESC LIMIT 1";
                        $sum_sno = sql_fetch($selectSql);
                        $ov_sum_sno = $sum_sno['ov_sum_sno'];
                        $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = $ov_sum_sno  WHERE $whereCheck ov_dpartner = '$ov_dpartner'";
                        sql_query($updateSql); 
                    }
                }
            }
        }
        $selectSql = "SELECT ov_IDX FROM sabang_lt_order_view WHERE slov_id = '{$key}' LIMIT 1"; 
        $selIDX = sql_fetch($selectSql);
        $selIDX['ov_IDX']; 
        if ($optPlus > 0) {
            if($optPlus == 1){
                if($mappingSuccessKey == '') $mappingSuccessKey .= $key;
                else $mappingSuccessKey .= ','.$key;
                // 프로시져
                $resultSql = "SELECT ov_samjin_code , ov_mall_code , ov_color, ov_size , ov_sabang_code  FROM sabang_lt_order_view WHERE slov_id = '{$key}' LIMIT 1 "; 
                $result_stock = sql_fetch($resultSql);
                
                $BARCODE = $result_stock['ov_mall_code'];
                $ORDER_NO = $result_stock['ov_samjin_code'];
                $COLOR = $result_stock['ov_color'];
                $HOCHING = $result_stock['ov_size'];
                $SABANGCODE =  $result_stock['ov_sabang_code'];

                //삼진바코드
                $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);
                $add_result_no = $barcode_add[0]['V1'];
                $add_result_meg = $barcode_add[0]['RSLT'];
                
                $barcodeSql = "UPDATE sabang_send_goods_list SET samjin_barcode_no = '{$add_result_no}', samjin_barcode_meg = '{$add_result_meg}'
                                WHERE sabang_goods_cd = '{$SABANGCODE}'
                                ";
                sql_query($barcodeSql);
            }
            // 매핑 성공
            $mappingSuccessId .= $selIDX['ov_IDX'].' ';
        } else {
            // 매핑 실패
            $mappingFailId .= $selIDX['ov_IDX'].' ';
            $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = NULL ,samjin_link_check = 2, ov_samjin_name = NULL, ov_samjin_code = NULL , ov_dpartner = NULL, ov_stock1 = NULL, ov_stock2 = NULL, ov_stock3 = NULL, ov_warehouse = NULL, ov_color = NULL, ov_size = NULL, ov_sap_code = NULL, ov_delivery_company = NULL, ov_delivery_company_code = NULL, ov_qty_form = NULL
            WHERE (slov_id = '{$key}' OR sub_slov_id = '{$key}')";
            sql_query($updateSql);
        }
    }
    if ($mappingFailId == NULL) {
        $result = "매핑 완료 되었습니다.";    
    } else {
        $result .= "성공 매핑 : ".$mappingSuccessId;
        $result .= " , 실패 매핑 : ".$mappingFailId;     
    }
    if($mappingSuccessKey != '') $sabang_send_stock_set =  sql_query('CALL sabang_send_stock_set("'.$mappingSuccessKey.'")');
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return;
}
if ($buttonType == 'decide') {

    $selViewSql = "SELECT * FROM sabang_lt_order_view WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    $selView = sql_query($selViewSql);
    for($svi = 0 ; $sv= sql_fetch_array($selView); $svi++){
        $selFormwSql = "SELECT COUNT(*) AS CNT FROM sabang_lt_order_form WHERE mall_id =  '{$sv['ov_mall_id']}' AND mall_order_no = '{$sv['ov_order_id']}' AND sabang_ord_no = '{$sv['ov_IDX']}'";
        $formCheck = sql_fetch($selFormwSql);
        if ($formCheck['CNT'] > 0) { 
            $slov_id = str_replace($sv['slov_id'],'-1',$slov_id);
        }
    }

    $decide_date = date("Y-m-d");
    // $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status ='정상', ov_order_status ='출고확정' WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id) AND order_from = 2";
    $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status ='출고확정', ov_order_status ='주문확인' , ov_decide_date = '{$decide_date}' WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    sql_query($updateSql);

    $degress_sql = "SELECT * FROM sabang_lt_order_view WHERE ov_decide_date = '{$decide_date}' order by ov_decide_degress desc LIMIT 1 ";
    $degress_result = sql_fetch($degress_sql);
    $degress_ori = $degress_result['ov_decide_degress'];
    $degress = $degress_ori + 1;
    $degress_update = "UPDATE sabang_lt_order_view SET ov_decide_degress = '$degress' , ov_decide_date = '{$decide_date}' WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    sql_query($degress_update);

    // 프로시저로 이동....
    // $selectSql = "SELECT ov_IDX FROM sabang_lt_order_view WHERE (slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)) AND order_from = 2";

    // $cartUpdate = sql_query($selectSql);
    // for($cui = 0 ; $cu= sql_fetch_array($cartUpdate); $cui++){
    //     $cart_id = $cu['ov_IDX'];
    //     if ($cart_id && $cart_id != '' && $cart_id != null) {
    //         $updateSql = "UPDATE lt_shop_cart SET ct_status ='상품준비중' WHERE ct_id = '$cart_id'";
    //         sql_query($updateSql);
    //     }
    // }

    // $ov_IDX = sql_fetch($selectSql);
    // $cart_id = $ov_IDX['ov_IDX'];
    // if ($cart_id && $cart_id != '' && $cart_id != null) {
    //     $updateSql = "UPDATE lt_shop_cart SET ct_status ='상품준비중' WHERE ct_id = '$cart_id'";
    //     sql_query($updateSql);
    // }


    $time = date("Y-m-d H:i:s");
    $insertSql = "INSERT INTO sabang_lt_order_form(mall_id,mall_name,mall_order_no,sabang_ord_no,dpartner_id,dpartner_stat,degress,receive_name,receive_cel,receive_tel,receive_zipcode,receive_addr,samjin_it_name,order_it_color,order_it_size,order_it_cnt,order_box_cnt,order_sum_sno,order_unim,order_meg,samjin_code,tak_code,order_invoice,excel_down_load,mall_cart_id,samjin_name ,order_it_brand,sender_addr,order_date, order_name, order_cel, order_tel,reg_dt,sap_code,samjin_barcode_size,set_check,warehouse_no,product_ID,mall_product_id,sub_order_id,set_code,pay_cost,total_cost,sub_slov_id,samjin_wonga,wonga_sum,wonga_rate,wonga_rate_pay_c,wonga_rate_total_c,copy_idx) 
                SELECT ov_mall_id,ov_mall_name,ov_order_id,ov_IDX,ov_dpartner,'정상',ov_decide_degress,ov_receive_name,ov_receive_hp,ov_receive_tel,ov_receive_zip,CONCAT('[',ov_receive_zip,'] ',ov_receive_addr),CONCAT(RTRIM(IF(ov_it_name LIKE '%옥의티%' || (ov_it_name LIKE '%리퍼%' && ov_mall_id = '19963'),CONCAT('[옥의티] ',ov_samjin_name),ov_samjin_name )),'*',cast(ov_qty_form as char)),ov_color,ov_size,ov_qty_form,'1',ov_sum_sno,ov_delv_cost,ov_order_msg,ov_samjin_code,ov_delivery_company_code,ov_invoice_no,'',ov_ct_id,ov_samjin_name, ov_brand,
                IF(ov_dpartner ='경민실업','인천 서구 가좌동 585-49 CJ대한통운 내 경민실업',IF(ov_dpartner ='어시스트','경기 이천시 마장면 관리 476-1 (마도로106번길 38-10)',IF(ov_dpartner ='본사','서울특별시 구로구 디지털로32길 86 리탠다드',''))),ov_od_time,ov_order_name,ov_order_hp,ov_order_tel,'$time',ov_sap_code, IFNULL(hb.barcode_size,ov_size), ov_set_check,IF(ov_it_name LIKE '%옥의티%' || (ov_it_name LIKE '%리퍼%' && ov_mall_id = '19963'),'45',ov_warehouse),ov_sabang_code,ov_MALL_PRODUCT_ID,ov_mall_order_id,ov_set_code,ov_pay_cost,ov_total_cost,sub_slov_id,ov_samjin_wonga,ov_wonga_sum,ov_samjin_wonga/ov_wonga_sum ,ROUND((ov_samjin_wonga / ov_wonga_sum) * (ov_pay_cost / ov_qty_form ) ,0),ROUND((ov_samjin_wonga/ov_wonga_sum ) * (ov_total_cost / ov_qty_form) ,0), copy_idx
                FROM sabang_lt_order_view AS ov LEFT JOIN samjin_hoching_barcode AS hb ON hb.hoching = ov.ov_size WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    // $insertSql = "INSERT INTO sabang_lt_order_form(mall_id,mall_name,mall_order_no,sabang_ord_no,dpartner_id,dpartner_stat,degress,receive_name,receive_cel,receive_tel,receive_zipcode,receive_addr,samjin_it_name,order_it_color,order_it_size,order_it_cnt,order_box_cnt,order_sum_sno,order_unim,order_meg,samjin_code,tak_code,order_invoice,excel_down_load,mall_cart_id,samjin_name,order_it_brand,sender_addr,order_date, order_name, order_cel, order_tel,reg_dt,sap_code,set_check,warehouse_no,product_ID,mall_product_id,sub_order_id,set_code,pay_cost,total_cost,sub_slov_id) 
    //             SELECT ov_mall_id,ov_mall_name,ov_order_id,ov_IDX,ov_dpartner,'정상',ov_decide_degress,ov_receive_name,ov_receive_hp,ov_receive_tel,ov_receive_zip,CONCAT('[',ov_receive_zip,'] ',ov_receive_addr),CONCAT(RTRIM(IF(ov_it_name LIKE '%옥의티%',CONCAT('[옥의티] ',ov_samjin_name),ov_samjin_name )),'*',cast(ov_qty_form as char)),ov_color,ov_size,ov_qty_form,'1',ov_sum_sno,ov_delv_cost,ov_order_msg,ov_samjin_code,ov_delivery_company_code,ov_invoice_no,'',ov_ct_id,ov_samjin_name,ov_brand,
    //             IF(ov_dpartner ='경민실업','인천 서구 가좌동 585-49 CJ대한통운 내 경민실업',IF(ov_dpartner ='어시스트','경기 이천시 마장면 관리 476-1 (마도로106번길 38-10)','')),ov_od_time,ov_order_name,ov_order_hp,ov_order_tel,'$time',ov_sap_code,ov_set_check,IF(ov_it_name LIKE '%옥의티%','51',NULL),ov_sabang_code,ov_MALL_PRODUCT_ID,ov_mall_order_id,ov_set_code,ov_pay_cost,ov_total_cost,sub_slov_id
    //             FROM sabang_lt_order_view WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    // $insertSql = "INSERT INTO sabang_lt_order_form(mall_id,mall_name,mall_order_no,sabang_ord_no,dpartner_id,dpartner_stat,degress,receive_name,receive_cel,receive_tel,receive_zipcode,receive_addr,samjin_it_name,order_it_color,order_it_size,order_it_cnt,order_box_cnt,order_sum_sno,order_unim,order_meg,samjin_code,tak_code,order_invoice,excel_down_load,mall_cart_id,samjin_name,order_it_brand,sender_addr,order_date, order_name, order_cel, order_tel,reg_dt,sap_code,set_check,warehouse_no,product_ID,mall_product_id) 
    //             SELECT ov_mall_id,ov_mall_name,ov_order_id,ov_IDX,ov_dpartner,'정상',ov_decide_degress,ov_receive_name,ov_receive_hp,ov_receive_tel,ov_receive_zip,CONCAT('[',ov_receive_zip,'] ',ov_receive_addr),CONCAT(RTRIM(ov_samjin_name),'*',cast(ov_qty_form as char)),ov_color,ov_size,ov_qty_form,'1',ov_sum_sno,ov_delv_cost,ov_order_msg,ov_samjin_code,ov_delivery_company_code,ov_invoice_no,'',ov_ct_id,ov_samjin_name,ov_brand,
    //             IF(ov_dpartner ='경민실업','인천 서구 가좌동 585-49 CJ대한통운 내 경민실업',IF(ov_dpartner ='어시스트','경기 이천시 마장면 관리 476-1 (마도로106번길 38-10)','')),ov_od_time,ov_order_name,ov_order_hp,ov_order_tel,'$time',ov_sap_code,ov_set_check,IF(ov_it_name LIKE '%옥의티%','51',NULL),ov_sabang_code,ov_MALL_PRODUCT_ID
    //             FROM sabang_lt_order_view WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    // $insertSql = "INSERT INTO sabang_lt_order_form(mall_id,mall_name,mall_order_no,sabang_ord_no,dpartner_id,dpartner_stat,degress,receive_name,receive_cel,receive_tel,receive_zipcode,receive_addr,samjin_it_name,order_it_color,order_it_size,order_it_cnt,order_box_cnt,order_sum_sno,order_unim,order_meg,samjin_code,tak_code,order_invoice,excel_down_load,mall_cart_id,samjin_name,order_it_brand,sender_addr,order_date, order_name, order_cel, order_tel,reg_dt,sap_code,set_check,warehouse_no) 
                // SELECT ov_mall_id,ov_mall_name,ov_order_id,ov_IDX,ov_dpartner,'정상',ov_decide_degress,ov_receive_name,ov_receive_hp,ov_receive_tel,ov_receive_zip,CONCAT('[',ov_receive_zip,'] ',ov_receive_addr),CONCAT(RTRIM(ov_samjin_name),'*',cast(ov_qty_form as char)),ov_color,ov_size,ov_qty_form,'1',ov_sum_sno,ov_delv_cost,ov_order_msg,ov_samjin_code,ov_delivery_company_code,ov_invoice_no,'',ov_ct_id,ov_samjin_name,ov_brand,
                // IF(ov_dpartner ='경민실업','인천 서구 가좌동 585-49 CJ대한통운 내 경민실업',IF(ov_dpartner ='어시스트','경기 이천시 마장면 관리 476-1 (마도로106번길 38-10)','')),ov_od_time,ov_order_name,ov_order_hp,ov_order_tel,'$time',ov_sap_code,ov_set_check,IF(ov_it_name LIKE '%옥의티%','51',NULL)
                // FROM sabang_lt_order_view WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    sql_query($insertSql);


    //드라마 쿠션 합포 분리1 다중수량
    $form_date = date("Y-m-d");
    $sql_drama = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%' AND reg_dt like '%{$form_date}%' AND order_it_cnt > 1 AND order_invoice IS NULL";
    $drama_res = sql_query($sql_drama);

    for($di = 0; $row_drama = sql_fetch_array($drama_res); $di++ ){
        $sno = $row_drama['sno'];
        $cnt = $row_drama['order_it_cnt'];
        $recv_name = $row_drama['receive_name'];
        $pay_cost = round($row_drama['pay_cost'] );
        $total_cost = round($row_drama['total_cost'] );

        $cp_samjin_it_name =  substr($row_drama['samjin_it_name'] , 0 , -2);
        $cp_samjin_it_name .=  '*1';
        $cp_order_it_cnt = 1;
        $up_order_form = "UPDATE sabang_lt_order_form set  receive_name = '{$recv_name}' , samjin_it_name = '{$cp_samjin_it_name}' , order_it_cnt = '$cp_order_it_cnt', pay_cost = '$pay_cost', total_cost = '$total_cost'  WHERE sno = '$sno' ";
        sql_query($up_order_form);

        for($cop = 2 ; $cop < ($cnt + 1); $cop++){
            $copy_sql = "INSERT INTO sabang_lt_order_form(mall_id,mall_name,mall_order_no,sabang_ord_no,dpartner_id,dpartner_stat,degress,receive_name,receive_cel,receive_tel,receive_zipcode,receive_addr,samjin_it_name,order_it_color,order_it_size,order_it_cnt,order_box_cnt,order_sum_sno,order_unim,order_meg,samjin_code,tak_code,order_invoice,excel_down_load,mall_cart_id,samjin_name,order_it_brand,sender_addr,order_date, order_name, order_cel, order_tel,reg_dt,sap_code,set_check,warehouse_no,product_ID,mall_product_id,sub_order_id,set_code,pay_cost,total_cost,sub_slov_id,samjin_wonga,wonga_sum,wonga_rate,wonga_rate_pay_c,wonga_rate_total_c,copy_idx) 
                        SELECT mall_id,mall_name,mall_order_no,CONCAT(sabang_ord_no, '_$cop' ) AS sabang_ord_no,dpartner_id,dpartner_stat,degress, CONCAT(receive_name, '$cop' ) AS receive_name,
                        receive_cel,receive_tel,receive_zipcode,receive_addr,samjin_it_name,order_it_color,order_it_size,order_it_cnt,
                        order_box_cnt,order_sum_sno,order_unim,order_meg,samjin_code,tak_code,order_invoice,excel_down_load,mall_cart_id,
                        samjin_name,order_it_brand,sender_addr,order_date, order_name, order_cel, order_tel,reg_dt,sap_code,set_check,warehouse_no,product_ID,mall_product_id,sub_order_id,set_code,0,0,sub_slov_id,samjin_wonga,wonga_sum,wonga_rate,wonga_rate_pay_c,wonga_rate_total_c,copy_idx FROM  sabang_lt_order_form
                        WHERE sno = '$sno'
            ";
            sql_query($copy_sql);

        }
    }

    //합포 분리 2 1+1 다중 옵션
    $sql_drama2 = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%' AND reg_dt like '%{$form_date}%' AND order_invoice IS NULL GROUP BY receive_name ,mall_order_no HAVING COUNT(*) > 1";
    $drama_res2 = sql_query($sql_drama2);
    for($di2 = 0; $row_drama2 = sql_fetch_array($drama_res2); $di2++ ){
        $cp_mall_id = $row_drama2['mall_order_no'];
        $cp_sabang_id  =$row_drama2['sabang_ord_no'] ;

        $d2_sql = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%' AND order_invoice IS NULL AND mall_order_no = '{$cp_mall_id}' AND sabang_ord_no = '$cp_sabang_id'";
        $d2_res = sql_query($d2_sql);
        
        $d2_cnt_sql = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%' AND order_invoice IS NULL AND mall_order_no = '{$cp_mall_id}' AND sabang_ord_no = '$cp_sabang_id' limit 1";
        $d2_cnt_res = sql_fetch($d2_cnt_sql);
        
        $cp_receive_name = $d2_cnt_res['receive_name'];
        $cp_sabang_ord_no = $d2_cnt_res['sabang_ord_no'];
        
        for($cp = 0 ; $row = sql_fetch_array($d2_res);  $cp++ ){
            
            if($cp == 0){
                $cp_sno = $row['sno'];
                $cp_name = $cp_receive_name.($cp+1);
                // $cp_sb_no = $cp_sabang_ord_no.'_'.($cp+1);
                $cp_up_sql = "UPDATE sabang_lt_order_form SET receive_name = '{$cp_name}'  WHERE sno = '$cp_sno' ";  
                sql_query($cp_up_sql);
            }else{
                $cp_sno = $row['sno'];
                $cp_name = $cp_receive_name.($cp+1);
                $cp_sb_no = $cp_sabang_ord_no.'_'.($cp+1);
                $cp_up_sql = "UPDATE sabang_lt_order_form SET receive_name = '{$cp_name}' , sabang_ord_no = '{$cp_sb_no}' WHERE sno = '$cp_sno' ";  
                sql_query($cp_up_sql);

            }
        
        
        }

    }

    //합포 분리 3 동일인물 다른 주문 일경우
    $sql_drama3 = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%' AND reg_dt like '%{$form_date}%' AND order_invoice IS NULL GROUP BY receive_name ,receive_addr HAVING COUNT(*) > 1";
    $drama_res3 = sql_query($sql_drama3);
    for($di3 = 0; $row_drama3 = sql_fetch_array($drama_res3); $di3++ ){
        $cp_receive_name = $row_drama3['receive_name'];
        $cp_receive_addr  =$row_drama3['receive_addr'] ;

        $d3_sql = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%'  AND order_invoice IS NULL AND receive_name = '{$cp_receive_name}' AND receive_addr = '{$cp_receive_addr}'";
        $d3_res = sql_query($d3_sql);

        for($cp3 = 0 ; $row3 = sql_fetch_array($d3_res);  $cp3++ ){    
            $cp3_sb = $row3['sabang_ord_no'];
            $cp3_name = $cp_receive_name.($cp3+1);
            // $cp3_sb_no = $cp_sabang_ord_no.'_'.($cp3+1);
            $cp3_up_sql = "UPDATE sabang_lt_order_form SET receive_name = '{$cp3_name}'  WHERE sabang_ord_no = '$cp3_sb' ";  
            sql_query($cp3_up_sql);


        }
        
        // $d3_cnt_sql = "SELECT * FROM sabang_lt_order_form WHERE samjin_it_name  LIKE '%드라마(WH)쿠션%' AND order_invoice IS NULL AND receive_name = '{$cp_receive_name}' AND receive_addr = '{$cp_receive_addr}' limit 1";
        // $d3_cnt_res = sql_fetch($d3_cnt_sql);
        
        // $cp_receive_name = $d3_cnt_res['receive_name'];
        // // $cp_sabang_ord_no = $d3_cnt_res['sabang_ord_no'];
        
        // for($cp3 = 0 ; $row3 = sql_fetch_array($d3_res);  $cp3++ ){
            
            
        //         $cp3_sno = $row3['sno'];
        //         $cp3_name = $cp_receive_name.($cp3+1);
        //         // $cp3_sb_no = $cp_sabang_ord_no.'_'.($cp3+1);
        //         $cp3_up_sql = "UPDATE sabang_lt_order_form SET receive_name = '{$cp3_name}'  WHERE sno = '$cp3_sno' ";  
        //         sql_query($cp3_up_sql);
        // }

    }



    //합포 다시 계산
    
    $sql_form = "SELECT * FROM sabang_lt_order_form WHERE reg_dt like '%{$form_date}%' AND order_invoice IS NULL ";
    $result_form = sql_query($sql_form);

    for($fii = 0 ; $row_form= sql_fetch_array($result_form); $fii++){
        $pos = strpos($row_form['mall_order_no'] , '-');
        if($pos === false){
            $mall_order_no = $row_form['mall_order_no'];
        }else{
            $temp_order_no = explode("-", $row_form['mall_order_no']);
            $mall_order_no = $temp_order_no[0];
        }



        $sum_chk = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_form 
        WHERE (sno = '{$row_form['sno']}' OR mall_order_no LIKE ('{$mall_order_no}%') AND mall_id ='{$row_form['mall_id']}' AND reg_dt LIKE '%{$form_date}%' AND order_name ='{$row_form['order_name']}') 
        AND dpartner_id = '{$row_form['dpartner_id']}' AND order_invoice IS NULL ORDER BY order_sum_sno DESC LIMIT 1";

        $sum_cnt = sql_fetch($sum_chk);
        $sum_cnt_updateSql = "UPDATE sabang_lt_order_form SET order_sum_sno ='{$sum_cnt['cnt']}' WHERE sno = '{$row_form['sno']}'";
        sql_query($sum_cnt_updateSql);
    }


    $selectSql = "SELECT ov_dpartner FROM sabang_lt_order_view WHERE slov_id IN ($slov_id) OR sub_slov_id IN ($slov_id)";
    $dpartner = sql_query($selectSql);
    $smsPart = '';
    for($dpi = 0 ; $dp= sql_fetch_array($dpartner); $dpi++){
        $smsPart .= $dp['ov_dpartner'];
    }
    $msg_body = "[리탠다드] 출고확정되었습니다. 발주서를 확인해주세요";
    if (strpos($smsPart,'경민') !== false) {
        $param = array('send_time' => $send_time
        ,'dest_phone' => '01055687220,'
        ,'dest_name' => ''
        ,'send_phone' => '0234947641'
        ,'send_name' => 'LITANDARD'
        ,'subject' => '출고확정안내'
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
        } else {
    
        }
    } 
    if (strpos($smsPart,'어시스트') !== false) {
        $param = array('send_time' => $send_time
        ,'dest_phone' => '01055687220,'
        ,'dest_name' => ''
        ,'send_phone' => '0234947641'
        ,'send_name' => 'LITANDARD'
        ,'subject' => '출고확정안내'
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
        } else {
    
        }
        $param = array('send_time' => $send_time
        ,'dest_phone' => '01034469808'
        ,'dest_name' => ''
        ,'send_phone' => '0234947641'
        ,'send_name' => 'LITANDARD'
        ,'subject' => '출고확정안내'
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
        } else {
    
        }
        $param = array('send_time' => $send_time
        ,'dest_phone' => '01041635912'
        ,'dest_name' => ''
        ,'send_phone' => '0234947641'
        ,'send_name' => 'LITANDARD'
        ,'subject' => '출고확정안내'
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
        } else {
    
        }
    }
    // 

}
if ($buttonType == 'smsView') {
    foreach($slov_id as $sKey) { 
        $sms_sql = "SELECT * FROM sabang_lt_order_view WHERE slov_id = '{$sKey}' limit 1 ";
        $sms_result = sql_fetch($sms_sql);
        
        $send_time = '';
        $prodoctsName = $sms_result['ov_it_name'];
        $mallMemberName = $sms_result['ov_receive_name'];
        $mallName = $sms_result['ov_mall_name'];
        $mallId = $sms_result['ov_mall_id'];
        $mallOdId = $sms_result['ov_order_id'];
        $mallCtId = $sms_result['ov_IDX'];
        if ($sms_result['ov_receive_hp'] && $sms_result['ov_receive_hp'] != '') {
            $mallPhoneNumber = $sms_result['ov_receive_hp'];
        } else {
            $mallPhoneNumber = $sms_result['ov_receive_tel']; 
        }

        $prodoctsName2 = "▶품절상품: $prodoctsName";
        
        $sms_cnt_sql = "SELECT COUNT(*) AS CNT FROM sabang_lt_order_view WHERE ov_order_id = '{$mallOdId}' AND ov_sms_check = 1 ";
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
        $update_sql = "UPDATE sabang_lt_order_view SET ov_sms_check = 1 WHERE slov_id = '{$sKey}' OR sub_slov_id = '{$sKey}'";
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
$result .= $buttonType;
$result .= $slov_id;
$result .= $updateSql;
// 
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;
?>