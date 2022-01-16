<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


// $sql = "SELECT * FROM sabang_goods_origin 
//         WHERE barcode_no <> 23 
//         AND (compayny_goods_cd IS NOT NULL AND compayny_goods_cd <> '' AND compayny_goods_cd <> '등록전')  
//         AND char_2_val NOT LIKE '%,%' AND char_1_val NOT LIKE '%,%'
        
//         ";

// $row_goods1 = sql_query($sql);
// for($i=0; $row = sql_fetch_array($row_goods1); $i++){
//     $a = $row['compayny_goods_cd'];
//     $no = $row['no'];
//     if(!empty($a)){
//         $sapCode12 = substr($a, 0, 12);
//         $color = substr($a, 12, 2);
//         $size = substr($a, 14);
//         $strSize = array("x","X");  
//         $size = str_replace($strSize,'*', $size);
//         $newSlov_id = $key;
//         $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
//         $g5['connect_samjindb'] = $connect_db;
//         $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
//         $rsSamjin = mssql_sql_query($sqlSamjin);
//         $num_rows = mssql_sql_num_rows($rsSamjin);

//         for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
//             // $ov_samjin_name = $samrow['ITEM'];
//             $ov_samjin_code = $samrow['ORDER_NO'];
//             $ov_sap_code = $samrow['SAP_CODE'];
//         }

//         if($num_rows == 1){
//             $BARCODE =$a;
//             $ORDER_NO =$ov_samjin_code;
//             $COLOR = $color;
//             $HOCHING = $size;

//             $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);

//             $add_result_no = $barcode_add[0]['V1'];
//             $add_result_meg = $barcode_add[0]['RSLT'];

//             $upSql = "UPDATE sabang_goods_origin SET barcode_no = '{$add_result_no}' , barcode_meg = '{$add_result_meg}' WHERE no  = '$no' ";
//             sql_query($upSql);
//         }


//     }
// }




// $sql2 = "SELECT * FROM sabang_goods_origin 
//         WHERE barcode_no <> 23 
//         AND (compayny_goods_cd IS NOT NULL AND compayny_goods_cd <> '' AND compayny_goods_cd <> '등록전')  
//         AND char_2_val NOT LIKE '%,%' AND char_1_val NOT LIKE '%,%'
        
//         ";

// $row_goods2 = sql_query($sql2);
// for($j=0; $row2 = sql_fetch_array($row_goods2); $j++){

    
//     $value = $row2['char_2_val'];
//     preg_match_all("/[^() || \-\ \/\,]+/", $value,$c);

//     foreach($c[0] as $a) {
//         if (strlen($a) > 14) {
//             if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
//                 $no = $row2['no'];
//                 if(!empty($a)){
//                     $sapCode12 = substr($a, 0, 12);
//                     $color = substr($a, 12, 2);
//                     $size = substr($a, 14);
//                     $strSize = array("x","X");  
//                     $size = str_replace($strSize,'*', $size);
//                     $newSlov_id = $key;
//                     $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
//                     $g5['connect_samjindb'] = $connect_db;
//                     $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
//                     $rsSamjin = mssql_sql_query($sqlSamjin);
//                     $num_rows = mssql_sql_num_rows($rsSamjin);

//                     for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
//                         // $ov_samjin_name = $samrow['ITEM'];
//                         $ov_samjin_code = $samrow['ORDER_NO'];
//                         $ov_sap_code = $samrow['SAP_CODE'];
//                     }

//                     if($num_rows == 1){
//                         $BARCODE =$a;
//                         $ORDER_NO =$ov_samjin_code;
//                         $COLOR = $color;
//                         $HOCHING = $size;

//                         $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);

//                         $add_result_no = $barcode_add[0]['V1'];
//                         $add_result_meg = $barcode_add[0]['RSLT'];

//                         $upSql2 = "UPDATE sabang_goods_origin SET barcode_no = '{$add_result_no}' , barcode_meg = '{$add_result_meg}' WHERE no  = '$no' ";
//                         sql_query($upSql2);
//                     }


//                 }
//             }
//         }
//     }

    
    
// }


// //미매핑 중 공동기회 세트
// $no_mapp = "SELECT * FROM sabang_lt_order_view  WHERE ov_idx = '4323157'";
// $no_result = sql_query($no_mapp); 

// for ($mp = 0; $no_map_row = sql_fetch_array($no_result); $mp++) {
//   $ov_id = $no_map_row['slov_id'];
//   $mall_id = $no_map_row['ov_mall_id'];
//   if($mall_id == '19963'){
//     $option = preg_replace('/-/','', str_replace(" ","",$no_map_row['ov_options']));
//   }else if($mall_id == '19961'){
//     $option = str_replace("None[XX]:","",$no_map_row['ov_options']);
//   }else{
//     $option = str_replace(" ","",$no_map_row['ov_options']);
//   }
//   $it_name = $no_map_row['ov_it_name'];
//   $sabang_goods_cd = $no_map_row['ov_mall_code'];
//   $mall_goods_cd = $no_map_row['ov_MALL_PRODUCT_ID'];

//   if($mall_id == '19940'){
//     $sabang_goods_cd = $no_map_row['ov_MALL_PRODUCT_ID'];
//   }


//   set_mapping_item($mall_id , $option , $it_name , $ov_id ,$sabang_goods_cd ,$mall_goods_cd );

// }

// function set_mapping_item( $mall , $opt , $name , $ov_id, $sabang_goods_cd, $mall_goods_cd){
//   if($mall == '15001'){
//     $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value
//         FROM sabang_set_code_mapping 
//       WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
//       AND set_name = '{$name}'
//       AND mall_code = '$mall'
//       AND set_code = (SELECT  MAX(set_code) 
//         FROM sabang_set_code_mapping 
      
//         WHERE  set_name = '{$name}'
//         AND REPLACE(sku_value,' ','') LIKE '%{$opt}%'
//         AND mall_code = '$mall') LIMIT 1 ";
//     $map_result = sql_fetch($map_sql); 
//     if(!empty($map_result['set_sku_value'])){
//       $new_opt = $opt.' \n'.$map_result['set_sku_value'];
    
//       $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
//       sql_query($up_set_opt);    
//     }
//   }else if($mall == '19952'){
//     $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value
//                 FROM sabang_set_code_mapping 
//                 WHERE set_name LIKE '%{$name}%'
//                 AND (sabang_goods_cd = '{$sabang_goods_cd}'  OR mall_goods_cd = '{$mall_goods_cd}' )
//                 AND mall_code = '$mall'
//                 AND set_code = (SELECT  MAX(set_code) 
//                 FROM sabang_set_code_mapping 

//                 WHERE set_name LIKE '%{$name}%'
//                 AND (sabang_goods_cd = '{$sabang_goods_cd}'  OR mall_goods_cd = '{$mall_goods_cd}' )
//                 AND mall_code = '$mall' ORDER BY   CASE  WHEN set_code LIKE '%SET%' THEN CAST(SUBSTR(set_code , 4) AS UNSIGNED) WHEN set_code LIKE '%SINGLE%' THEN CAST(SUBSTR(set_code , 7) AS UNSIGNED) END DESC ) LIMIT 1 ";
//     $map_result = sql_fetch($map_sql); 
//     if(!empty($map_result['set_sku_value'])){
//       $new_opt = $opt.' \n'.$map_result['set_sku_value'];
    
//       $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
//       sql_query($up_set_opt);    
//     }
//   }else{
//     $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value
//     FROM sabang_set_code_mapping 
//     WHERE REPLACE(sku_value,' ','') LIKE '%{$pot}%'
//     AND (sabang_goods_cd = ''  OR mall_goods_cd LIKE '%CZF0XX01283%' )
//     AND mall_code = '$mall'
//     AND set_code = (
//       SELECT set_code  FROM (
//       SELECT  *
//       FROM sabang_set_code_mapping 
    
//       WHERE REPLACE(sku_value,' ','') LIKE '%{$pot}%'
//       AND  mall_goods_cd LIKE '%{$mall_goods_cd}%' 
//       AND mall_code = '$mall' 
//       UNION 
//       SELECT  *
//       FROM sabang_set_code_mapping 
    
//       WHERE REPLACE(sku_value,' ','') LIKE '%{$pot}%'
//       AND sabang_goods_cd = '{$sabang_goods_cd}'
//       AND mall_code = '$mall' 
//       ) AS map_u
//        ORDER BY   CASE  WHEN map_u.set_code LIKE '%SET%' THEN CAST(SUBSTR(map_u.set_code , 4) AS UNSIGNED)
//        WHEN map_u.set_code LIKE '%SINGLE%' THEN CAST(SUBSTR(map_u.set_code , 7) AS UNSIGNED) END DESC LIMIT 1
//     )";
//     $map_result = sql_fetch($map_sql); 
//     if(!empty($map_result['set_sku_value'])){
//       $new_opt = $opt.' \n'.$map_result['set_sku_value'];
    
//       $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
//       sql_query($up_set_opt);    
//     }
//   }
// }





$sql = "SELECT * FROM sabang_lt_order_view  WHERE receive_date LIKE '20210614%' AND ov_mall_id = 19940 AND ov_order_id IN (107361034,107360396,107357050,107349635,107347756,107346783,107345015,107344865,107343279,107343278,107342667,107342666,107342115,107337404,107336815,107335337,107334412,107332963,107331465,107331293,107320565,107320216,107320211,107320210,107320039,107316653,107360917,107348266,107346782,107342023,107335713,107331631,107330564,107320564,107317425,107317380,107315992)";
$result = sql_query($sql);
$mappingSuccessKey = '';
for ($i = 0; $row = sql_fetch_array($result); $i++) { 
  $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 시작 ".$row['slov_id'] .",".$row['ov_options'];
  if ($row['order_from'] == 1) { 
    if ($row['ov_mall_id'] == '19968' && $row['ov_mall_code'] =='MOS20AS30D11WHQ') {
        $row['ov_options'] = 'MOS20AS30D11WHQ';
    }
    preg_match_all("/[^() || \-\ \/\,\:\.]+/", preg_replace("/[^a-zA-Z0-9 (),\/\*.+-]/", "", preg_replace('/\n/','',$row['ov_options'])) ,$c);
    
    // preg_match_all("/[^() || \-\ \/\,]+/", preg_replace('/\n/','',$row['ov_options']),$c);
    // preg_match_all("/[^() || \-\ \/\,]+/", $row['ov_options'],$c);
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
          $box_ex += 1;
          $sapCode12 = substr($a, 0, 12);
          $color = substr($a, 12, 2);
          $size = substr($a, 14);
          $strSize = array("x","X");  
          $size = str_replace($strSize,'*', $size);

          $newSlov_id = $row['slov_id'];

          $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
          $g5['connect_samjindb'] = $connect_db;
          $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM ,WONGA FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
          $rsSamjin = mssql_sql_query($sqlSamjin);
        
        
          $ov_samjin_name = '';
          $ov_samjin_code = '';
          $ov_sap_code = '';
          $ov_samjin_wonga = '';

          for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
            $ov_samjin_name = $samrow['ITEM'];
            $ov_samjin_code = $samrow['ORDER_NO'];
            $ov_sap_code = $samrow['SAP_CODE'];
            $ov_samjin_wonga = $samrow['WONGA'];
          }
          $ov_options_modify = $sapCode12.$color.$size;
          if ($ov_samjin_code=='') {
            $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = '', samjin_link_check = 2
            WHERE slov_id = {$row['slov_id']} OR sub_slov_id = {$row['slov_id']}";
            sql_query($updateSql); 
            $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진에 없는 상품입니다 ".$row['slov_id'] .",".$row['ov_options'];
          } else {
            $optPlus += 1;
            if ($box_ex > 1) {
              $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID FROM sabang_lt_order_view WHERE slov_id = {$row['slov_id']}";
              $res = sql_query($insertSql);
              if ($res) $newSlov_id = sql_insert_id();
            }
            $ov_stock1 = 0;
            $ov_stock2 = 0;
            $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
            if (count($stockSamjin) == 0) {
            } else {
              for ($j =0; $j < count($stockSamjin); $j++) {
                if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4 || $stockSamjin[$j]['C_NO'] == 8 ) {
                  $ov_stock2 += $stockSamjin[$j]['STOCK2'];
                }
              }
            }
            $fromQty = 1;
            $sapCodeBrand = substr($ov_options_modify, 2, 1);
            if ($sapCodeBrand =='D' || $sapCodeBrand =='d') {
              $sapCodeCheck = substr($ov_options_modify, 9, 3);
                if ($sapCodeCheck == '201' || $sapCodeCheck == '202' || $sapCodeCheck == '203') {
                    $fromQty = 2;
                }
            }
            preg_match_all("/[^() || , -]+/", $row['ov_order_id'],$orderPreg);
            $row['ov_order_id'] = $orderPreg[0][0];
            $ov_distribution_status = null;
            if ($ov_stock1 != 0 && $ov_stock1 >= (int)$row['ov_qty'] * $fromQty) {
              $ov_dpartner = '경민실업';
              $ov_delivery_company = 'CJ대한통운';
              $ov_delivery_company_code = '003';
            } else if ($ov_stock2 != 0 && $ov_stock2 >= (int)$row['ov_qty'] * $fromQty) {
              $ov_dpartner = '어시스트';
              $ov_delivery_company = '롯데택배';
              $ov_delivery_company_code = '002';
            } else {
              $ov_distribution_status = '품절';
              $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status'  WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' OR ov_order_id LIKE ('{$row['ov_order_id']}%')) AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}'";
              sql_query($updateSql); 
              $ov_dpartner = null;
              $ov_delivery_company = null;
              $ov_delivery_company_code = null;
            }
            $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' OR ov_order_id LIKE ('{$row['ov_order_id']}%')) AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_distribution_status = '품절'";
            $soldOut = sql_fetch($selectSql);
            $soldOutCnt = $soldOut['CNT'];
            if ($soldOutCnt > 0) {
              $ov_distribution_status = '품절';
            } 

            $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_samjin_wonga = '$ov_samjin_wonga', ov_dpartner = '$ov_dpartner', 
                          ov_stock1 = '$ov_stock1', ov_stock2 = '$ov_stock2', ov_color = '$color', ov_size = '$size', ov_sap_code = '$ov_sap_code', ov_delivery_company = '$ov_delivery_company', ov_delivery_company_code = '$ov_delivery_company_code',
                          ov_options_modify = '$ov_options_modify', ov_distribution_status = '$ov_distribution_status', ov_qty_form = ov_qty * $fromQty
                          WHERE slov_id = '$newSlov_id'";
            sql_query($updateSql); 
            
            $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$row['slov_id']}'";
            $set = sql_fetch($selectSql);
            if ($set['cnt'] >0) {
              $setCheck = '002';
              $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002'  WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}')";
              sql_query($updateSql);
            }

            $selectSql = "SELECT IFNULL(MAX(ov_sum_sno+1), 1) AS ov_sum_sno FROM sabang_lt_order_view WHERE ov_order_id LIKE ('{$row['ov_order_id']}%') AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_dpartner = '$ov_dpartner' ORDER BY ov_sum_sno DESC LIMIT 1";
            $sum_sno = sql_fetch($selectSql);
            $ov_sum_sno = $sum_sno['ov_sum_sno'];
            $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = $ov_sum_sno  WHERE ov_order_id LIKE ('{$row['ov_order_id']}%') AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_dpartner = '$ov_dpartner'";
            sql_query($updateSql); 
            $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 성공 ".$row['slov_id'] .",".$row['ov_options'];
          }
        }
      }
    }
    if($optPlus == 1){
      $outputs[] = date('Y-m-d H:i:s', time()) . " : 프리시저 시작 ".$row['slov_id'] .",".$row['ov_options'];
      if($mappingSuccessKey == '') $mappingSuccessKey .= $row['slov_id'];
      else $mappingSuccessKey .= ','.$row['slov_id'];
      //프로시져
      $resultSql = "SELECT ov_samjin_code , ov_mall_code , ov_color, ov_size , ov_sabang_code  FROM sabang_lt_order_view WHERE slov_id = '{$row['slov_id']}' LIMIT 1 "; 
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

  } else if ($row['order_from'] == 2) {
    $sun_min = 0;
    $sun_ass = 0;
    $optExp =explode('_' , $row['ov_options']);
    $color = $optExp[0];
    $size = $optExp[1];
    $sapCode12 = substr($row['ov_mall_code'], 0, 12);
    
    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
    $g5['connect_samjindb'] = $connect_db;
    $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM,WONGA FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
    $rsSamjin = mssql_sql_query($sqlSamjin);
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
    $ov_options_modify = $sapCode12.$color.$size;
    if ($ov_samjin_code=='') {
      $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 2
      WHERE slov_id = {$row['slov_id']} OR sub_slov_id = {$row['slov_id']}";
      sql_query($updateSql); 
      $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진에 없는 상품입니다 ".$row['slov_id'] .",".$row['ov_options'];
    } else {
      $ov_stock1 = 0;
      $ov_stock2 = 0;
      $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
      if (count($stockSamjin) == 0) {
      } else {
        $ov_distribution_status = null;
        if (strpos($row['ov_it_name'],'옥의티')!== false) {
            // $ov_distribution_status = '리퍼';
        } 
        for ($j =0; $j < count($stockSamjin); $j++) {
          if (strpos($row['ov_it_name'],'옥의티')!== false) {
              if ($stockSamjin[$j]['C_NO'] == 17) {
                  $ov_stock1 += (int)$row['ov_qty'];
                } 
          } else if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4 || $stockSamjin[$j]['C_NO'] == 8)  {
            $ov_stock2 += $stockSamjin[$j]['STOCK2'];
          }
        }
      }
      $fromQty = 1;
      $sapCodeBrand = substr($ov_options_modify, 2, 1);
      if ($sapCodeBrand =='D' || $sapCodeBrand =='d') {
        $sapCodeCheck = substr($ov_options_modify, 9, 3);
          if ($sapCodeCheck == '201' || $sapCodeCheck == '202' || $sapCodeCheck == '203') {
              $fromQty = 2;
          }
      }
      if ($ov_stock1 != 0 && $ov_stock1 >= (int)$row['ov_qty'] * $fromQty) {
        $ov_dpartner = '경민실업';
        $ov_delivery_company = 'CJ대한통운';
        $ov_delivery_company_code = '003';
      } else if ($ov_stock2 != 0 && $ov_stock2 >= (int)$row['ov_qty'] * $fromQty) {
        $ov_dpartner = '어시스트';
        $ov_delivery_company = '롯데택배';
        $ov_delivery_company_code = '002';
      } else {
          $ov_distribution_status = '품절';
          $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status' WHERE ov_order_id = '{$row['ov_order_id']}'";
          sql_query($updateSql); 
          $ov_dpartner = null;
          $ov_delivery_company = null;
          $ov_delivery_company_code = null;
      }
      if (strpos($row['ov_it_name'],'옥의티')!== false) {
          $ov_dpartner = '경민실업';
          $ov_delivery_company = 'CJ대한통운';
          $ov_delivery_company_code = '003';
      } 
      $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE ov_order_id = {$row['ov_order_id']} AND ov_distribution_status = '품절'";
      $soldOut = sql_fetch($selectSql);
      $soldOutCnt = $soldOut['CNT'];
      if ($soldOutCnt > 0) {
        $ov_distribution_status = '품절';
      } 

      $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_samjin_wonga = '$ov_samjin_wonga', ov_dpartner = '$ov_dpartner', 
                    ov_stock1 = '$ov_stock1', ov_stock2 = '$ov_stock2', ov_color = '$color', ov_size = '$size', ov_sap_code = '$ov_sap_code', ov_delivery_company = '$ov_delivery_company', ov_delivery_company_code = '$ov_delivery_company_code',
                    ov_options_modify = '$ov_options_modify', ov_distribution_status = '$ov_distribution_status', ov_qty_form = ov_qty * $fromQty
                    WHERE slov_id = {$row['slov_id']}";
      sql_query($updateSql); 

      $selectSql = "SELECT IFNULL(MAX(ov_sum_sno+1), 1) AS ov_sum_sno FROM sabang_lt_order_view WHERE ov_order_id = '{$row['ov_order_id']}' AND ov_dpartner = '$ov_dpartner' ORDER BY ov_sum_sno DESC LIMIT 1";
      $sum_sno = sql_fetch($selectSql);
      $ov_sum_sno = $sum_sno['ov_sum_sno'];
      $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = $ov_sum_sno  WHERE ov_order_id = {$row['ov_order_id']} AND ov_dpartner = '$ov_dpartner'";
      sql_query($updateSql); 
      $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 성공 ".$row['slov_id'] .",".$row['ov_options'];
    }
  }
}



?>