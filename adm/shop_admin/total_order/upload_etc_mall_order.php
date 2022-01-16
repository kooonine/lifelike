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

$returnStatus = NULL;
$returnEnd = 0;
$kuengmin_meg = 0;
$assist_meg = 0;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
  
    $returnChe = $data->sheets[0]['cells'][$i][1];
    if(strpos($returnChe, '제휴사') !== false) continue;

    $orderId = $data->sheets[0]['cells'][$i][5];
    $orderId2 = $data->sheets[0]['cells'][$i][4];

    $BARCODE =strtoupper($data->sheets[0]['cells'][$i][7]);
    
    $returnStatus = $data->sheets[0]['cells'][$i][2];
    if ($returnStatus == '반품접수' || $returnStatus == '반품완료') {
      $returnEnd = 1;
      $returnId = $data->sheets[0]['cells'][$i][1];
      $merge_sql = "select count(*) AS cnt from sabang_order_origin where IDX = '{$returnId}' ";
      $merge_item = sql_fetch($merge_sql);
      if($merge_item['cnt'] > 0 ){
        $returnSql = "select count(*) AS cnt from sabang_return_origin where IDX = '{$returnId}' ";
        $returnCheck = sql_fetch($returnSql);
        if($returnCheck['cnt'] < 1 ){
          if ($returnStatus == '반품접수') {
            $snoSql = "SELECT sno,dpartner_id FROM sabang_lt_order_form WHERE sabang_ord_no = '{$returnId}'";
            $snoSqlQ = sql_query($snoSql);
            for($sni = 0 ; $sv= sql_fetch_array($snoSqlQ); $sni++){ 
              $sql = "INSERT INTO sabang_return_origin (sno,reg_date,reg_datetime,modify_datetime,IDX,ORDER_STATUS,CLAME_STATUS_GUBUN) 
              VALUES ('{$sv['sno']}',date_format(now(), '%Y%m%d%H'),date_format(NOW(), '%Y-%m-%d %H:%i:%S'),date_format(NOW(), '%Y-%m-%d %H:%i:%S'),'{$returnId}','{$returnStatus}','{$returnStatus}'); ";
              sql_query($sql);
              if ($sv['dpartner_id'] =='경민실업') {
                $kuengmin_meg = 1;
              }
              if ($sv['dpartner_id'] =='어시스트') {
                $assist_meg = 1;
              }
            }
            $sql = "UPDATE sabang_lt_order_view SET ov_order_status = '반품접수' WHERE ov_IDX = '{$returnId}'";
            sql_query($sql);
          } 
        } else {
          if ($returnStatus == '반품완료') {
            $sql = "UPDATE sabang_return_origin SET return_status = '반품완료',modify_datetime = date_format(NOW(), '%Y-%m-%d %H:%i:%S'), returnclear_datetime = date_format(NOW(), '%Y-%m-%d %H:%i:%S') WHERE IDX = '{$returnId}'";
            sql_query($sql);
            $sql = "UPDATE sabang_lt_order_view SET ov_order_status = '반품완료' WHERE ov_IDX = '{$returnId}'";
            sql_query($sql);
          }
        }
        // goto_url("./total_order.php?".$qstr);
        // return;
      }
    } else {
      if (!$orderId || $orderId =='') {

      } else {
        $merge_sql = "select count(*) AS cnt from sabang_order_origin where IDX = '{$orderId}' ";
        $merge_item = sql_fetch($merge_sql);
    
        if($merge_item['cnt'] > 0 ){
  
            
        }else{
    
            $sql= "insert into sabang_order_origin
                        set receive_date = '".$toDate."'
                        , IDX = '".$data->sheets[0]['cells'][$i][5]."'
                        , ORDER_ID = '".$data->sheets[0]['cells'][$i][4]."'
                        , MALL_ID = '".$data->sheets[0]['cells'][$i][1]."'
                        , MALL_USER_ID = 'LITANDARD'
                        , ORDER_STATUS  = '신규주문'
                        , USER_ID = ''
                        , USER_NAME = '".$data->sheets[0]['cells'][$i][14]."'
                        , USER_TEL = '".$data->sheets[0]['cells'][$i][15]."'
                        , USER_CEL = '".$data->sheets[0]['cells'][$i][16]."'
                        , USER_EMAIL = ''
                        , RECEIVE_TEL = '".$data->sheets[0]['cells'][$i][21]."'
                        , RECEIVE_CEL = '".$data->sheets[0]['cells'][$i][22]."'
                        , RECEIVE_EMAIL = ''
                        , DELV_MSG = '".$data->sheets[0]['cells'][$i][19]."'
                        , RECEIVE_NAME = '".$data->sheets[0]['cells'][$i][17]."'
                        , RECEIVE_ZIPCODE = '".$data->sheets[0]['cells'][$i][20]."'
                        , RECEIVE_ADDR = '".$data->sheets[0]['cells'][$i][18]."'
                        , TOTAL_COST = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][12])."'
                        , PAY_COST = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][13])."'
                        , ORDER_DATE = '".$data->sheets[0]['cells'][$i][3]."'
                        , PARTNER_ID = '리탠다드'
                        , DPARTNER_ID = '(주)어시스트'
                        , MALL_PRODUCT_ID = ''
                        , PRODUCT_ID = '".$data->sheets[0]['cells'][$i][8]."'
                        , SKU_ID = ''
                        , P_PRODUCT_NAME = '".preg_replace("/[\"\']/i", "",  $data->sheets[0]['cells'][$i][9])."'
                        , P_SKU_VALUE = '".$data->sheets[0]['cells'][$i][11]."'
                        , PRODUCT_NAME = '". preg_replace("/[\"\']/i", "", $data->sheets[0]['cells'][$i][9])."'
                        , SALE_COST = ''
                        , MALL_WON_COST = ''
                        , WON_COST = ''
                        , SKU_VALUE = '".$data->sheets[0]['cells'][$i][11]."'
                        , SALE_CNT = '".$data->sheets[0]['cells'][$i][10]."'
                        , DELIVERY_METHOD_STR = ''
                        , DELV_COST = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][24])."'
                        , COMPAYNY_GOODS_CD = '".$data->sheets[0]['cells'][$i][7]."'
                        , SKU_ALIAS = ''
                        , BOX_EA = ''
                        , JUNG_CHK_YN = ''
                        , MALL_ORDER_SEQ = ''
                        , MALL_ORDER_ID = ''
                        , ETC_FIELD3 = ''
                        , ORDER_GUBUN = ''
                        , P_EA = ''
                        , REG_DATE = '".$toDate."'
                        , ORDER_ETC_1 = ''
                        , ORDER_ETC_2 = ''
                        , ORDER_ETC_3 = ''
                        , ORDER_ETC_4 = ''
                        , ORDER_ETC_5 = ''
                        , ORDER_ETC_6 = ''
                        , ORDER_ETC_7 = ''
                        , ORDER_ETC_8 = ''
                        , ORDER_ETC_9 = ''
                        , ORDER_ETC_10 = ''
                        , ORDER_ETC_11 = ''
                        , ORDER_ETC_12 = ''
                        , ORDER_ETC_13 = ''
                        , ORDER_ETC_14 = ''
                        , ord_field2 = ''
                        , copy_idx = ''
                        , GOODS_NM_PR = ''
                        , GOODS_KEYWORD = ''
                        , ORD_CONFIRM_DATE = ''
                        , RTN_DT = ''
                        , CHNG_DT = ''
                        , DELIVERY_CONFIRM_DATE = ''
                        , CANCEL_DT = ''
                        , CLASS_CD1 = ''
                        , CLASS_CD2 = ''
                        , CLASS_CD3 = ''
                        , CLASS_CD4 = ''
                        , BRAND_NM = '".$data->sheets[0]['cells'][$i][23]."'
                        , DELIVERY_ID = ''
                        , INVOICE_NO = ''
                        , HOPE_DELV_DATE = ''
                        , FLD_DSP = ''
                        , INV_SEND_MSG = ''
                        , MODEL_NO = ''
                        , SET_GUBUN = ''
                        , ETC_MSG = ''
                        , DELV_MSG1 = '".$data->sheets[0]['cells'][$i][19]."'
                        , MUL_DELV_MSG = ''
                        , BARCODE = ''
                        , INV_SEND_DM = ''
                        , DELIVERY_METHOD_STR2 = ''
                        
                        ";
        
            // $sql = " update sabang_goods_origin
            //         set regdate = '202006251541'
            //         , sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
            //         , goods_nm = '".iconv("EUC-KR", "UTF-8", $data->sheets[0]['cells'][$i][2]);."'
            //         , goods_keyword  = '".iconv("EUC-KR", "UTF-8", $data->sheets[0]['cells'][$i][3]);."'
            //         , model_nm = '".strtoupper($data->sheets[0]['cells'][$i][4])."'
            //         , model_no = '".strtoupper($data->sheets[0]['cells'][$i][5])."'
            //         where sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
            //         " ;
        
            sql_query($sql);
        }
  
      }
    }
    // $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);

    // $add_result_no = $barcode_add[0]['V1'];
    // $add_result_meg = $barcode_add[0]['RSLT'];
}
if ($returnEnd == 1) { 
  // goto_url("./total_order.php?".$qstr);
  $msg_body = "[리탠다드] 취소건이 있습니다.";
  if($kuengmin_meg > 0){

    $param = array('send_time' => $send_time
    ,'dest_phone' => '01055687220'
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

    $param = array('send_time' => $send_time
    ,'dest_phone' => '01055687220'
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

  
  goto_url("./total_order_return_list.php?".$qstr);
  return;
}
usleep(3000000);
$viewPro = sql_query('CALL sabang_lt_order_view_transfer()');



//미매핑 중 공동기회 세트
$no_mapp = "SELECT * FROM sabang_lt_order_view WHERE samjin_link_check=0 AND ov_order_status = '신규주문' ";
$no_result = sql_query($no_mapp); 

for ($mp = 0; $no_map_row = sql_fetch_array($no_result); $mp++) {
  $ov_id = $no_map_row['slov_id'];
  $mall_id = $no_map_row['ov_mall_id'];
  $origin_option = $no_map_row['ov_options'];
  if($mall_id == '19963'){
    $option = preg_replace('/-/','', str_replace(" ","",$no_map_row['ov_options']));
  }else if($mall_id == '19961'){
    $option = str_replace("None[XX]:",'', str_replace(" ","",$no_map_row['ov_options']));
  }else{
    $option = str_replace(" ","",$no_map_row['ov_options']);
  }
  $it_name = $no_map_row['ov_it_name'];
  $sabang_goods_cd = $no_map_row['ov_mall_code'];
  $mall_goods_cd = $no_map_row['ov_MALL_PRODUCT_ID'];

  set_mapping_item($mall_id , $option , $it_name , $ov_id,$sabang_goods_cd ,$mall_goods_cd, $origin_option );

}

function set_mapping_item( $mall , $opt , $name , $ov_id, $sabang_goods_cd, $mall_goods_cd , $origin_opt){
  if($mall == '15001'){
    $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value , set_code
        FROM sabang_set_code_mapping 
      WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
      AND set_name = '{$name}'
      AND mall_code = '$mall'
      AND STATUS = '0001'
      AND set_code = (SELECT  MAX(set_code) 
        FROM sabang_set_code_mapping 
      
        WHERE  set_name = '{$name}'
        AND REPLACE(sku_value,' ','') LIKE '%{$opt}%'
        AND mall_code = '$mall') LIMIT 1 ";
    $map_result = sql_fetch($map_sql); 
    if(!empty($map_result['set_sku_value'])){
      $new_opt = $opt.' \n'.$map_result['set_sku_value'];
    
      $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}', ov_mapping_code = '{$map_result['set_sku_value']}' , ov_set_code = '{$map_result['set_code']}' , samjin_link_check = 0   where slov_id = '$ov_id'";
      sql_query($up_set_opt);    
    }else{
      $up_set_opt = "UPDATE sabang_lt_order_view set  ov_mapping_code = '{$origin_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
      sql_query($up_set_opt);    
    }
  }else if($mall == '19952'){
    $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value , set_code
                FROM sabang_set_code_mapping 
                WHERE set_name LIKE '%{$name}%'
                AND REPLACE(sku_value,' ','') LIKE '%{$opt}%'
                AND (sabang_goods_cd = '{$sabang_goods_cd}'  OR mall_goods_cd = '{$mall_goods_cd}' )
                AND mall_code = '$mall'
                AND STATUS = '0001'
                AND set_code = (SELECT  MAX(set_code) 
                  FROM sabang_set_code_mapping 

                  WHERE set_name LIKE '%{$name}%'
                  AND REPLACE(sku_value,' ','') LIKE '%{$opt}%'
                  AND (sabang_goods_cd = '{$sabang_goods_cd}'  OR mall_goods_cd = '{$mall_goods_cd}' )
                  AND mall_code = '$mall' ORDER BY   CASE  WHEN set_code LIKE '%SET%' THEN CAST(SUBSTR(set_code , 4) AS UNSIGNED) WHEN set_code LIKE '%SINGLE%' THEN CAST(SUBSTR(set_code , 7) AS UNSIGNED) WHEN set_code LIKE '%OPT%' THEN CAST(SUBSTR(set_code , 4) AS UNSIGNED) END DESC ) LIMIT 1 ";
    $map_result = sql_fetch($map_sql); 
    if(!empty($map_result['set_sku_value'])){
      $new_opt = $opt.' \n'.$map_result['set_sku_value'];
    
      $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}', ov_mapping_code = '{$map_result['set_sku_value']}' , ov_set_code = '{$map_result['set_code']}' , samjin_link_check = 0   where slov_id = '$ov_id'";
      sql_query($up_set_opt);    
    }else{
      $up_set_opt = "UPDATE sabang_lt_order_view set  ov_mapping_code = '{$origin_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
      sql_query($up_set_opt);    
    }
  }else{
    $map_sql = "SELECT GROUP_CONCAT(company_goods_cd SEPARATOR ' \n') AS set_sku_value , set_code
    FROM sabang_set_code_mapping 
    WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
    AND (sabang_goods_cd LIKE '%{$sabang_goods_cd}%'  OR mall_goods_cd LIKE '%{$mall_goods_cd}%' )
    AND mall_code = '$mall'
    AND STATUS = '0001'
    AND set_code = (
      SELECT set_code  FROM (
      SELECT  *
      FROM sabang_set_code_mapping 
    
      WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
      AND mall_goods_cd LIKE '%{$mall_goods_cd}%' 
      AND mall_code = '$mall' 
      UNION 
      SELECT  *
      FROM sabang_set_code_mapping 
    
      WHERE REPLACE(sku_value,' ','') LIKE '%{$opt}%'
      AND sabang_goods_cd = '{$sabang_goods_cd}'
      AND mall_code = '$mall' 
      ) AS map_u
       ORDER BY   CASE  WHEN map_u.set_code LIKE '%SET%' THEN CAST(SUBSTR(map_u.set_code , 4) AS UNSIGNED)
       WHEN map_u.set_code LIKE '%SINGLE%' THEN CAST(SUBSTR(map_u.set_code , 7) AS UNSIGNED) END DESC LIMIT 1
    )";
    $map_result = sql_fetch($map_sql); 
    if(!empty($map_result['set_sku_value'])){
      $new_opt = $opt.' \n'.$map_result['set_sku_value'];
    
      $up_set_opt = "UPDATE sabang_lt_order_view set ov_options = '{$new_opt}', ov_mapping_code = '{$map_result['set_sku_value']}' , ov_set_code = '{$map_result['set_code']}' , samjin_link_check = 0   where slov_id = '$ov_id'";
      sql_query($up_set_opt);    
    }else{
      $up_set_opt = "UPDATE sabang_lt_order_view set  ov_mapping_code = '{$origin_opt}' , samjin_link_check = 0   where slov_id = '$ov_id'";
      sql_query($up_set_opt);    
    }
  }
}


//자사몰 사은품 이벤트

// $ll_date = date("Ymd");

// $ll_sql = "SELECT * FROM sabang_lt_order_view WHERE receive_date LIKE '{$ll_date}%' AND sub_slov_id = 0 AND ov_mall_id IN  (15001) AND samjin_link_check=0 AND ov_order_status = '신규주문' GROUP BY ov_mall_id , ov_receive_name , ov_receive_addr ";
// $ll_result = sql_query($ll_sql);
// for ($lli = 0; $ll_item = sql_fetch_array($ll_result); $lli++) {
//   $ll_slov_id = $ll_item['slov_id'];

//   $chk_ll_i = $ll_item['ov_IDX'].'_사은품';
//   $ll_item = 'ZZZZZ0002900AAFREE';
  
//   if(!empty($ll_slov_id)){
//     $ll_chk = "SELECT count(*) AS chk_cnt FROM  sabang_lt_order_view WHERE ov_IDX = '{$chk_ll_i}' " ; 
//     $ll_chk_res = sql_fetch($ll_chk);
//     if($ll_chk_res['chk_cnt'] < 1 ){
//       $ll_insert = "INSERT INTO sabang_lt_order_view
//       (order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,
//       ov_mall_code,ov_it_name,ov_options,ov_qty,ov_total_cost,ov_pay_cost,ov_sabang_code,ov_brand,ov_delv_cost,ov_order_msg,ov_mall_id,ov_ct_id,ov_MALL_PRODUCT_ID,ov_mall_order_id,copy_idx)
//       SELECT order_from,receive_date,ov_od_time,'신규주문',CONCAT(ov_IDX , '_사은품'),ov_order_id,ov_mall_name,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,
//       ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,'{$ll_item}','자사몰 사은품 증정','{$ll_item}',1,1,1,'',ov_brand,0,'',ov_mall_id,'','','',0
//       FROM sabang_lt_order_view WHERE slov_id = {$ll_slov_id}";
//       sql_query($ll_insert);
//     }
//   }

// }

// 제휴몰 사은품 이벤트
// 금액별 주건 1, 2 
$receiveDate = date("Ymd");

$event_sql = "SELECT * FROM (
  SELECT slov_id ,ov_IDX, ov_mall_id , ov_receive_name , ov_receive_hp , ov_receive_addr  , SUM(ov_total_cost) AS t_price , SUM(ov_pay_cost) AS p_price FROM sabang_lt_order_view WHERE receive_date LIKE '{$receiveDate}%' AND sub_slov_id = 0 AND ov_mall_id  IN  (19965,19945,19968,19950,19971,19976,19967,19970,19952) AND samjin_link_check=0 AND ov_order_status = '신규주문' GROUP BY ov_mall_id , ov_receive_name  , ov_receive_addr ) AS ttt
  WHERE ttt.t_price > 200000 OR ttt.p_price > 200000";
$event_result = sql_query($event_sql);
for ($evi = 0; $event_item = sql_fetch_array($event_result); $evi++) {
  $slov_id = $event_item['slov_id'];

  $chk_eve_i = $event_item['ov_IDX'].'_사은품';

  if($event_item['ov_mall_id'] == '19943' || $event_item['ov_mall_id'] == '19957' || $event_item['ov_mall_id'] == '19977' || $event_item['ov_mall_id'] == '19979'  ){
    if($event_item['p_price'] > 300000 ){
      $event_i = array("MOS20AS53P61BL36*60","MOS20AS54P61GR36*60");
      $num = array("0","1");
      $nan = array_rand($num,1);
      $eve_item = $event_i[$nan];

    }else if ($event_item['p_price'] > 200000){
      $eve_item = "MOS20AS65P11WHL";
    }
  }else{
    if($event_item['t_price'] > 300000 ){
      $event_i = array("MOS20AS53P61BL36*60","MOS20AS54P61GR36*60");
      $num = array("0","1");
      $nan = array_rand($num,1);
      $eve_item = $event_i[$nan];

    }else if ($event_item['t_price'] > 200000){
      $eve_item = "MOS20AS65P11WHL";
    }
  }
  if(!empty($slov_id)){
    $eve_chk = "SELECT count(*) AS chk_cnt FROM  sabang_lt_order_view WHERE ov_IDX = '{$chk_eve_i}' " ; 
    $eve_chk_res = sql_fetch($eve_chk);
    if($eve_chk_res['chk_cnt'] < 1 ){
      $event_insert = "INSERT INTO sabang_lt_order_view
      (order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,
      ov_mall_code,ov_it_name,ov_options,ov_qty,ov_total_cost,ov_pay_cost,ov_sabang_code,ov_brand,ov_delv_cost,ov_order_msg,ov_mall_id,ov_ct_id,ov_MALL_PRODUCT_ID,ov_mall_order_id,copy_idx)
      SELECT order_from,receive_date,ov_od_time,'신규주문',CONCAT(ov_IDX , '_사은품'),ov_order_id,ov_mall_name,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,
      ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,'{$eve_item}','제휴몰 사은품 증정','{$eve_item}',1,1,1,'',ov_brand,0,'',ov_mall_id,'','','',0
      FROM sabang_lt_order_view WHERE slov_id = {$slov_id}";
      sql_query($event_insert);
    }
  }

}


  $sql = "SELECT * FROM sabang_lt_order_view WHERE samjin_link_check=0 AND ov_order_status = '신규주문' ";
  $result = sql_query($sql);
  $mappingSuccessKey = '';
  for ($i = 0; $row = sql_fetch_array($result); $i++) { 
    $mapping_goods_cd = $row['ov_mapping_code'];
    if(empty($mapping_goods_cd)){
      $mapping_goods_cd = $row['ov_options'];
    }
    $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 시작 ".$row['slov_id'] .",".$mapping_goods_cd;
    if ($row['order_from'] == 1) {
      preg_match_all("/[^() || \-\ \/\,\:\.]+/", preg_replace("/[^a-zA-Z0-9 (,\/\*.+-]/", "", preg_replace('/\n/','',$mapping_goods_cd)) ,$c);
      // preg_match_all("/[^() || \-\ \/\,]+/",preg_replace('/\r\n|\r|\n/','',$mapping_goods_cd),$c);
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
            $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM, WONGA FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
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
              $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = '', samjin_link_check = 2
              WHERE slov_id = {$row['slov_id']} OR sub_slov_id = {$row['slov_id']}";
              sql_query($updateSql); 
              $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진에 없는 상품입니다 ".$row['slov_id'] .",".$mapping_goods_cd;
            } else {
              $optPlus += 1;
              if ($box_ex > 1) {
                $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID,ov_mapping_code,ov_set_code) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime,ov_collection_degress,ov_MALL_PRODUCT_ID,ov_mapping_code,ov_set_code FROM sabang_lt_order_view WHERE slov_id = {$row['slov_id']}";
                $res = sql_query($insertSql);
                if ($res) $newSlov_id = sql_insert_id();
              }
              $ov_stock1 = 0;
              $ov_stock2 = 0;
              $ov_stock3 = 0;
              $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
              if (count($stockSamjin) == 0) {
              } else {
                for ($j =0; $j < count($stockSamjin); $j++) {
                  if (strpos($row['ov_it_name'],'리퍼')!== false && $row['ov_mall_id'] == '19963') {
                    if ($stockSamjin[$j]['C_NO'] == 17) {
                      $ov_stock1 += (int)$row['ov_qty'];
                    } 
                  } else if ($stockSamjin[$j]['C_NO'] == 2 || $stockSamjin[$j]['C_NO'] == 3 || $stockSamjin[$j]['C_NO'] == 4 || $stockSamjin[$j]['C_NO'] == 8) {
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
                $ov_delivery_company = 'CJ대한통운';
                $ov_delivery_company_code = '003';
              } else if ($ov_stock3 != 0 && $ov_stock3 >= (int)$row['ov_qty'] * $fromQty) {
                $ov_dpartner = '본사';
                $ov_delivery_company = '로젠택배';
                $ov_delivery_company_code = '007';
              }else {
                $ov_distribution_status = '품절';
                $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status'  WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' OR ov_order_id LIKE ('{$row['ov_order_id']}%')) AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}'";
                sql_query($updateSql); 
                $ov_dpartner = null;
                $ov_delivery_company = null;
                $ov_delivery_company_code = null;
              }
              if (strpos($row['ov_it_name'],'리퍼')!== false && $row['ov_mall_id'] == '19963') {
                $ov_dpartner = '경민실업';
                $ov_delivery_company = 'CJ대한통운';
                $ov_delivery_company_code = '003';
              } 
              $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' OR ov_order_id LIKE ('{$row['ov_order_id']}%')) AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_distribution_status = '품절'";
              $soldOut = sql_fetch($selectSql);
              $soldOutCnt = $soldOut['CNT'];
              if ($soldOutCnt > 0) {
                $ov_distribution_status = '품절';
              } 
  
              $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_samjin_wonga = '$ov_samjin_wonga', ov_dpartner = '$ov_dpartner', 
                            ov_stock1 = '$ov_stock1', ov_stock2 = '$ov_stock2', ov_stock3 = '$ov_stock3', ov_color = '$color', ov_size = '$size', ov_sap_code = '$ov_sap_code', ov_delivery_company = '$ov_delivery_company', ov_delivery_company_code = '$ov_delivery_company_code',
                            ov_options_modify = '$ov_options_modify', ov_distribution_status = '$ov_distribution_status', ov_qty_form = ov_qty * $fromQty
                            WHERE slov_id = '$newSlov_id'";
              sql_query($updateSql); 
              
              $selectSql = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_view WHERE sub_slov_id = '{$row['slov_id']}'";
              $set = sql_fetch($selectSql);
              if ($set['cnt'] >0) {
                $setCheck = '002';
                $wonga_sum_sql = "SELECT SUM(ov_samjin_wonga) AS won_sum FROM sabang_lt_order_view WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}') ";
                $wonga_sum_res =  sql_fetch($wonga_sum_sql);
                $wonga_sum = $wonga_sum_res['won_sum'];
                $updateSql = "UPDATE sabang_lt_order_view SET ov_set_check = '002' , ov_wonga_sum = '{$wonga_sum}'  WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}')";
                sql_query($updateSql);
              }
  
              $selectSql = "SELECT IFNULL(MAX(ov_sum_sno+1), 1) AS ov_sum_sno FROM sabang_lt_order_view WHERE ov_order_id LIKE ('{$row['ov_order_id']}%') AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_dpartner = '$ov_dpartner' ORDER BY ov_sum_sno DESC LIMIT 1";
              $sum_sno = sql_fetch($selectSql);
              $ov_sum_sno = $sum_sno['ov_sum_sno'];
              $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = $ov_sum_sno  WHERE ov_order_id LIKE ('{$row['ov_order_id']}%') AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_dpartner = '$ov_dpartner'";
              sql_query($updateSql); 
              $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 성공 ".$row['slov_id'] .",".$mapping_goods_cd;
            }
          }
        }
      }
      if($optPlus == 1){
        //프로시져
        if($mappingSuccessKey == '') $mappingSuccessKey .= $row['slov_id'];
        else $mappingSuccessKey .= ','.$row['slov_id'];
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
        // $sabang_send_stock_set =  sql_query('CALL sabang_send_stock_set('.$row['slov_id'].')');
      }
    }
  }


if($mappingSuccessKey != '') $sabang_send_stock_set =  sql_query('CALL sabang_send_stock_set("'.$mappingSuccessKey.'")');

goto_url("./total_order.php?".$qstr);

?>



