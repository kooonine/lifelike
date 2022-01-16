<? #!/usr/local/php53/bin/php
// $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
// $root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));
// $outputs = array();
// $outputs[] = date('Y-m-d H:i:s', time()) . " : 크론시작  ";
// include_once($root_path . '/../../common.php');
// include_once($root_path.'/_common.php');
// include_once(G5_LIB_PATH . '/samjin.lib.php');

include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');
// MAR17FC81111AAQ
$stockWith = NM_GET_STOCK_WITH_SAP_CODE(2,0,'MDS21SS03D12',null,null);

dd($stockWith);

$stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,'MOS20AS55P11','WH','S');

dd($stockSamjin);

// rmfkak kca rhocskgeks akfdldpdy sjnfkjfnkja
// dufdlf dufldlff
$selectSql = "SELECT * FROM (SELECT * FROM lt_job_order GROUP BY jo_it_name ORDER BY jo_id DESC) GROUP BY jo_it_name";

$abc = " INSERT sabang_set_code_mapping INTO () VALUE () ";
$abc = " INSERT sabang_set_code_mapping INTO (SELECT * FROM ) VALUE (SELECT * FROM ) ";


// 쿼리를
"INSERT INTO lt_cover_merge(jo_id,ip_id,ps_id,cm_temp_jo,cm_prod_type_jo,cm_season_jo,cm_scent,cm_nabgi_m_ip,cm_nabgi_limit_ip,cm_user_jo,cm_so,cm_brand_jo,cm_manufacture_gubun,cm_prod_gubun_jo,cm_it_name_jo)
SELECT jo_id,ip_id,ps_id,jo_temp,jo_prod_type,jo_season,ip_nabgi_m,ip_nabgi_limit,jo_user,jo_brand,jo_prod_gubun,jo_it_name
-- ㅋㅋㅋ
-- rmq
SELECT ov_mall_id,ov_mall_name,ov_order_id,ov_IDX,ov_dpartner,'정상',ov_decide_degress,ov_receive_name,ov_receive_hp,ov_receive_tel,ov_receive_zip,CONCAT('[',ov_receive_zip,'] ',ov_receive_addr),CONCAT(RTRIM(IF(ov_it_name LIKE '%옥의티%',CONCAT('[옥의티] ',ov_samjin_name),ov_samjin_name )),'*',cast(ov_qty_form as char)),ov_color,ov_size,ov_qty_form,'1',ov_sum_sno,ov_delv_cost,ov_order_msg,ov_samjin_code,ov_delivery_company_code,ov_invoice_no,'',ov_ct_id,ov_samjin_name,ov_brand,
IF(ov_dpartner ='경민실업','인천 서구 가좌동 585-49 CJ대한통운 내 경민실업',IF(ov_dpartner ='어시스트','경기 이천시 마장면 관리 476-1 (마도로106번길 38-10)','')),ov_od_time,ov_order_name,ov_order_hp,ov_order_tel,'$time',ov_sap_code,ov_set_check,IF(ov_it_name LIKE '%옥의티%','51',NULL),ov_sabang_code,ov_MALL_PRODUCT_ID
FROM sabang_lt_order_view WHERE sub_slov_id IN (1999)";
// 



"INSERT INTO sabang_lt_order_form(mall_id,mall_name,mall_order_no,sabang_ord_no,dpartner_id,dpartner_stat,degress,receive_name,receive_cel,receive_tel,receive_zipcode,receive_addr,samjin_it_name,order_it_color,order_it_size,order_it_cnt,order_box_cnt,order_sum_sno,order_unim,order_meg,samjin_code,tak_code,order_invoice,excel_down_load,mall_cart_id,samjin_name,order_it_brand,sender_addr,order_date, order_name, order_cel, order_tel,reg_dt,sap_code,set_check,warehouse_no,product_ID,mall_product_id) 
SELECT ov_mall_id,ov_mall_name,ov_order_id,ov_IDX,ov_dpartner,'정상',ov_decide_degress,ov_receive_name,ov_receive_hp,ov_receive_tel,ov_receive_zip,CONCAT('[',ov_receive_zip,'] ',ov_receive_addr),CONCAT(RTRIM(IF(ov_it_name LIKE '%옥의티%',CONCAT('[옥의티] ',ov_samjin_name),ov_samjin_name )),'*',cast(ov_qty_form as char)),ov_color,ov_size,ov_qty_form,'1',ov_sum_sno,ov_delv_cost,ov_order_msg,ov_samjin_code,ov_delivery_company_code,ov_invoice_no,'',ov_ct_id,ov_samjin_name,ov_brand,
IF(ov_dpartner ='경민실업','인천 서구 가좌동 585-49 CJ대한통운 내 경민실업',IF(ov_dpartner ='어시스트','경기 이천시 마장면 관리 476-1 (마도로106번길 38-10)','')),ov_od_time,ov_order_name,ov_order_hp,ov_order_tel,'$time',ov_sap_code,ov_set_check,IF(ov_it_name LIKE '%옥의티%','51',NULL),ov_sabang_code,ov_MALL_PRODUCT_ID
FROM sabang_lt_order_view WHERE sub_slov_id IN (1999)";



// sasfdsaf





// dlr fj anjgodigkgfmswl ahfmrPTek



"
select a.* from a inner join
(select max(date) as date from a group by substr(date,1,10)) b on a.date = b.date
";
// ahtgkrpTek dbdksxk
// aksgrh askgdms tkfakmefnwdpd 
// dmflajwf kajwrnd
"
select * from (select id, name, hp from member order by date desc) a group by id
";

// TodkRkrkrkrlwls


return;

// r


// $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(0,'	MOS20AS55P11','WH','Q');
// MAS20FC52201GR50*70
// MAS20FC52111GRS
// $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(0,'MAS17HC54701','GR','S');
// // echo ($stockSamjin[0]['CODE']);
// // echo 'test';
// echo $stockSamjin[0]['CODE'].' : ';
// echo $stockSamjin[0]['STOCK']. ':';
// echo $stockSamjin[1]['STOCK']. ':';
// echo $stockSamjin[2]['STOCK']. ':';
// echo '<br>------<br>-----<br>';
// MOS20AC18101NVQ
// MWS21SC07111WHS
$stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,'MAS21SC02101','GN','S');
// dd($stockSamjin);
// MAS21SC02101GNS

// return;
echo $stockSamjin[0]['CODE'].' : ';
echo $stockSamjin[0]['STOCK2']. ':';
echo $stockSamjin[1]['STOCK2']. ':';
echo $stockSamjin[2]['STOCK2']. ':';
echo '<br>------<br>------<br>';
dd($stockSamjin);

return;
$stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(1,0,'MAS20SC99403','WH','K');
echo $stockSamjin[0]['CODE'].' : ';
echo $stockSamjin[0]['STOCK']. ':';
echo $stockSamjin[1]['STOCK']. ':';
echo $stockSamjin[2]['STOCK']. ':';
echo '<br>------<br>------<br>';


// dd('test');
return;
// MOS20AS55P11WHQ
// MAO20SC02111BLQ
// if (strpos('MOS20AC08101+5','+')!== false) {
//   echo 'if 통과하나???';
//   // ㅇㅋ ?? 이런드니낌
// }
// dd('safsaf');


$q = "색상: WH / 사이즈: S(MAP19AC01201WH50x70) MOS20AS27D11WHQ+3";
preg_match_all("/[^() || \-\ \/\,]+/", $q,$c);
$optionsArr = array();
foreach($c[0] as $opc) {
  if (strlen($opc) > 14) {
    if(substr($opc, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
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
$optionsArr = array();
foreach($optionsArr as $a) { 
  echo '<br>'.$a.'<br>';
  if (strlen($a) > 14) {
    if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){ 
      $sapCode12 = substr($a, 0, 12);
      $color = substr($a, 12, 2);
      $size = substr($a, 14);
      $strSize = array("x","X");  
      $size = str_replace($strSize,'*', $size);
      echo '<br>'.$sapCode12.'<br>';
      echo '<br>'.$color.'<br>';
    }
  }
}
// foreach($optionsArr as $a) { 
//   echo $a."<br>";
// }
dd($optionsArr);
return



$test =array(); 
preg_match_all("/[^() || \-\ \/\,]+/", $q,$c);
array_push($c[0], "teste001");
foreach($c[0] as $a) {
  if (strlen($a) > 14) {
    if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
      echo "<br>".$a."<br>";
      $plusCheck = 0;
      if (strpos($a,'+')!== false) {
        preg_match_all("/[^ \+\,]+/", $a, $aPlus);
        $a = $aPlus[0][0];
        $plusCheck = $aPlus[0][1];
        array_push($test, "test00700");
      }
    }
  }
}
dd($test);
return;


preg_match_all("/[^ \+\,]+/", 'MOS20AC08101+5', $aPlus);
// preg_match_all("/[^ \+\,]+/", '네이비 / 퀸 (MSS21AC03706NVQ+3) 베개커버 (MSS21AC03201NVS/2)',$aPlus);
echo ($aPlus[0][0]);
echo '<br>';
echo ($aPlus[0][1]);
return;
// 1 번 프로시저 호출
// $orderPre = sql_query('CALL sabang_lt_order_transfer()');

// preg_match_all("/[^() || \-\ \/\,]+/", '네이비 / 퀸 (MSS21AC03706NVQ) 베개커버 (MSS21AC03201NVS/2)',$c);

// dd($c);

// $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(0,'MSS21AC03706','NV','Q');

// skfkjfnjajkw nqle
// dd($stockSamjin);

// return;
// $t = 19;
// $t -= 1;
// echo $t;
// MOS20AC71201GNS
// ZZZZZ0001700AAFREE
// return;

// $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(0,'MAS17AS60D15','GR','Q');

// skfkjfnjajkw nqle
// dd($stockSamjin);

// xxXxxqweer dlrusos qjflsrjtdmf 
$test2 = 
"SELECT bb.io_hoching , aa.* 
FROM ((SELECT a.sort AS 1sort, b.* 
FROM lt_best_item  a ,
lt_shop_item b
WHERE (a.it_id = b.it_id
AND a.bs_category = '00'
AND b.it_use = 1
AND b.it_soldout = 0
))
UNION
(SELECT @ROWNUM:=@ROWNUM+1 AS 1sort, c.* FROM lt_shop_item c 
WHERE (@ROWNUM:=6)=6 AND it_use = 1 AND it_soldout = 0 AND it_id NOT IN (SELECT it_id FROM lt_best_item WHERE bs_category = '00') AND ca_id LIKE '10%' ORDER BY it_sales_num DESC, it_order DESC LIMIT 44)
ORDER BY 1sort ASC ) aa
LEFT JOIN lt_shop_item_option bb ON (aa.it_id=bb.it_id)
WHERE aa.it_use=1  AND bb.io_use= 1 AND bb.io_stock_qty > 0
";


$test = 
"SELECT bb.io_hoching , aa.* 
FROM ((SELECT a.sort AS 1sort, b.* 
FROM lt_best_item  a ,
lt_shop_item b
WHERE (a.it_id = b.it_id
AND a.bs_category = '00'
AND b.it_use = 1
AND b.it_soldout = 0
))
UNION
(SELECT @ROWNUM:=@ROWNUM+1 AS 1sort, c.* FROM lt_shop_item c 
WHERE (@ROWNUM:=6)=6 AND it_use = 1 AND it_soldout = 0 AND it_id NOT IN (SELECT it_id FROM lt_best_item WHERE bs_category = '00') AND ca_id LIKE '10%' ORDER BY it_sales_num DESC, it_order DESC LIMIT 44)
ORDER BY 1sort ASC ) aa
LEFT JOIN lt_shop_item_option bb ON (aa.it_id=bb.it_id)
WHERE aa.it_use=1  AND bb.io_use= 1 AND bb.io_stock_qty > 0"
;

dd($test2);

return;



$connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
$g5['connect_samjindb'] = $connect_db;
$sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = 'MWD16FC05101' AND COLOR = 'AA' AND HOCHING = 'S'";
$rsSamjin = mssql_sql_query($sqlSamjin);
for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
  dd($samrow);
}
dd($rsSamjin);


return;

// $outputs = array();
// $send_xml_sql = "select xml_name from sabang_order_xml_history where status = 0 order by no desc limit 1";

// $send_xml_name = sql_fetch($send_xml_sql);

// $url = 'https://r.sabangnet.co.kr/RTL_API/xml_order_info.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_order/20201201180440_order_collection.xml';
// $ch = cURL_init();

// cURL_setopt($ch, CURLOPT_URL, $url);
// cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $response = cURL_exec($ch);
// cURL_close($ch); 

// $object = simplexml_load_string($response);

// $toDate = date("YmdH");


// foreach($object->children() as $orders) {
//   $outputs[] = date('Y-m-d H:i:s', time()) . " : 사방 주문 수집 ".$orders->IDX;

//   // idx값 체크
//   // $overlapCheck = $orders->IDX;
//   // if (true) {

//   // };


//   $sql_common = " receive_date = '{$toDate}'
//                   ,IDX = '{$orders->IDX}'
//                   ,ORDER_ID = '{$orders->ORDER_ID}'
//                   ,MALL_ID = '{$orders->MALL_ID}'
//                   ,MALL_USER_ID = '{$orders->MALL_USER_ID}'
//                   ,ORDER_STATUS = '{$orders->ORDER_STATUS}'
//                   ,USER_ID = '{$orders->USER_ID}'
//                   ,USER_NAME = '{$orders->USER_NAME}'
//                   ,USER_TEL = '{$orders->USER_TEL}'
//                   ,USER_CEL = '{$orders->USER_CEL}'
//                   ,USER_EMAIL = '{$orders->USER_EMAIL}'
//                   ,RECEIVE_TEL = '{$orders->RECEIVE_TEL}'
//                   ,RECEIVE_CEL = '{$orders->RECEIVE_CEL}'
//                   ,RECEIVE_EMAIL = '{$orders->RECEIVE_EMAIL}'
//                   ,DELV_MSG = '{$orders->DELV_MSG}'
//                   ,RECEIVE_NAME = '{$orders->RECEIVE_NAME}'
//                   ,RECEIVE_ZIPCODE = '{$orders->RECEIVE_ZIPCODE}'
//                   ,RECEIVE_ADDR = '{$orders->RECEIVE_ADDR}'
//                   ,TOTAL_COST = '{$orders->TOTAL_COST}'
//                   ,PAY_COST = '{$orders->PAY_COST}'
//                   ,ORDER_DATE = '{$orders->ORDER_DATE}'
//                   ,PARTNER_ID = '{$orders->PARTNER_ID}'
//                   ,DPARTNER_ID = '{$orders->DPARTNER_ID}'
//                   ,MALL_PRODUCT_ID = '{$orders->MALL_PRODUCT_ID}'
//                   ,PRODUCT_ID = '{$orders->PRODUCT_ID}'
//                   ,SKU_ID = '{$orders->SKU_ID}'
//                   ,P_PRODUCT_NAME = '{$orders->P_PRODUCT_NAME}'
//                   ,P_SKU_VALUE = '{$orders->P_SKU_VALUE}'
//                   ,PRODUCT_NAME = '{$orders->PRODUCT_NAME}'
//                   ,SALE_COST = '{$orders->SALE_COST}'
//                   ,MALL_WON_COST = '{$orders->MALL_WON_COST}'
//                   ,WON_COST = '{$orders->WON_COST}'
//                   ,SKU_VALUE = '{$orders->SKU_VALUE}'
//                   ,SALE_CNT = '{$orders->SALE_CNT}'
//                   ,DELIVERY_METHOD_STR = '{$orders->DELIVERY_METHOD_STR}'
//                   ,DELV_COST = '{$orders->DELV_COST}'
//                   ,COMPAYNY_GOODS_CD = '{$orders->COMPAYNY_GOODS_CD}'
//                   ,SKU_ALIAS = '{$orders->SKU_ALIAS}'
//                   ,BOX_EA = '{$orders->BOX_EA}'
//                   ,JUNG_CHK_YN = '{$orders->JUNG_CHK_YN}'
//                   ,MALL_ORDER_SEQ = '{$orders->MALL_ORDER_SEQ}'
//                   ,MALL_ORDER_ID = '{$orders->MALL_ORDER_ID}'
//                   ,ETC_FIELD3 = '{$orders->ETC_FIELD3}'
//                   ,ORDER_GUBUN = '{$orders->ORDER_GUBUN}'
//                   ,P_EA = '{$orders->P_EA}'
//                   ,REG_DATE = '{$orders->REG_DATE}'
//                   ,ORDER_ETC_1 = '{$orders->ORDER_ETC_1}'
//                   ,ORDER_ETC_2 = '{$orders->ORDER_ETC_2}'
//                   ,ORDER_ETC_3 = '{$orders->ORDER_ETC_3}'
//                   ,ORDER_ETC_4 = '{$orders->ORDER_ETC_4}'
//                   ,ORDER_ETC_5 = '{$orders->ORDER_ETC_5}'
//                   ,ORDER_ETC_6 = '{$orders->ORDER_ETC_6}'
//                   ,ORDER_ETC_7 = '{$orders->ORDER_ETC_7}'
//                   ,ORDER_ETC_8 = '{$orders->ORDER_ETC_8}'
//                   ,ORDER_ETC_9 = '{$orders->ORDER_ETC_9}'
//                   ,ORDER_ETC_10 = '{$orders->ORDER_ETC_10}'
//                   ,ORDER_ETC_11 = '{$orders->ORDER_ETC_11}'
//                   ,ORDER_ETC_12 = '{$orders->ORDER_ETC_12}'
//                   ,ORDER_ETC_13 = '{$orders->ORDER_ETC_13}'
//                   ,ORDER_ETC_14 = '{$orders->ORDER_ETC_14}'
//                   ,ord_field2 = '{$orders->ord_field2}'
//                   ,copy_idx = '{$orders->copy_idx}'
//                   ,GOODS_NM_PR = '{$orders->GOODS_NM_PR}'
//                   ,GOODS_KEYWORD = '{$orders->GOODS_KEYWORD}'
//                   ,ORD_CONFIRM_DATE = '{$orders->ORD_CONFIRM_DATE}'
//                   ,RTN_DT = '{$orders->RTN_DT}'
//                   ,CHNG_DT = '{$orders->CHNG_DT}'
//                   ,DELIVERY_CONFIRM_DATE = '{$orders->DELIVERY_CONFIRM_DATE}'
//                   ,CANCEL_DT = '{$orders->CANCEL_DT}'
//                   ,CLASS_CD1 = '{$orders->CLASS_CD1}'
//                   ,CLASS_CD2 = '{$orders->CLASS_CD2}'
//                   ,CLASS_CD3 = '{$orders->CLASS_CD3}'
//                   ,CLASS_CD4 = '{$orders->CLASS_CD4}'
//                   ,BRAND_NM = '{$orders->BRAND_NM}'
//                   ,DELIVERY_ID = '{$orders->DELIVERY_ID}'
//                   ,INVOICE_NO = '{$orders->INVOICE_NO}'
//                   ,HOPE_DELV_DATE = '{$orders->HOPE_DELV_DATE}'
//                   ,FLD_DSP = '{$orders->FLD_DSP}'
//                   ,INV_SEND_MSG = '{$orders->INV_SEND_MSG}'
//                   ,MODEL_NO = '{$orders->MODEL_NO}'
//                   ,SET_GUBUN = '{$orders->SET_GUBUN}'
//                   ,ETC_MSG = '{$orders->ETC_MSG}'
//                   ,DELV_MSG1 = '{$orders->DELV_MSG1}'
//                   ,MUL_DELV_MSG = '{$orders->MUL_DELV_MSG}'
//                   ,BARCODE = '{$orders->BARCODE}'
//                   ,INV_SEND_DM = '{$orders->INV_SEND_DM}'
//                   ,DELIVERY_METHOD_STR2 = '{$orders->DELIVERY_METHOD_STR2}'
  
  
  
//   ";
//   // echo $orders->ORDER_ID . ", ";                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
//   // echo $orders->MALL_ID . ", ";
//   // echo $orders->MALL_USER_ID . ", ";
//   // echo $orders->ORDERSTATUS . ", ";
//   // echo $orders->USER_ID . "<br>";
//   if(!empty($orders->IDX)){
//     $sql = " insert sabang_order_origin set $sql_common ";
//     sql_query($sql);
//   }
// }

// 1 번 프로시저 호출
$orderPre = sql_query('CALL sabang_lt_order_transfer()');
// 2 번 프로시저 호출 
$viewPro = sql_query('CALL sabang_lt_order_view_transfer()');


$sql = "SELECT * FROM sabang_lt_order_view WHERE samjin_link_check=0";
$result = sql_query($sql);

for ($i = 0; $row = sql_fetch_array($result); $i++) { 
  $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 시작 ".$row['slov_id'] .",".$row['ov_options'];
  if ($row['order_from'] == 1) { 
    preg_match_all("/[^() || ,]+/", $row['ov_options'],$c);
    $box_ex = 0;
    foreach($c[0] as $a) {
      if (strlen($a) > 14) {
        if(substr($a, 0, 1)=='M'){
          $box_ex += 1;
          $sapCode12 = substr($a, 0, 12);
          $color = substr($a, 12, 2);
          $size = substr($a, 14);
          $strSize = array("x","X");  
          $size = str_replace($strSize,'*', $size);

          $newSlov_id = $row['slov_id'];

          $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
          $g5['connect_samjindb'] = $connect_db;
          $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
          $rsSamjin = mssql_sql_query($sqlSamjin);
        
        
          $ov_samjin_name = '';
          $ov_samjin_code = '';
          $ov_sap_code = '';


          for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
            $ov_samjin_name = $samrow['ITEM'];
            $ov_samjin_code = $samrow['ORDER_NO'];
            $ov_sap_code = $samrow['SAP_CODE'];
          }
          $ov_options_modify = $sapCode12.$color.$size;
          if ($ov_samjin_code=='') {
            $updateSql = "UPDATE sabang_lt_order_view SET ov_options_modify = '', samjin_link_check = 2
            WHERE slov_id = {$row['slov_id']} OR sub_slov_id = {$row['slov_id']}";
            sql_query($updateSql); 
            $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진에 없는 상품입니다 ".$row['slov_id'] .",".$row['ov_options'];
            echo '<br>'.$ov_options_modify .'삼진에 없는 상품입니다 <br>';
          } else {
            if ($box_ex > 1) {
              $insertSql = "INSERT INTO sabang_lt_order_view(sub_slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime) SELECT slov_id,slod_id,samjin_link_check,order_from,receive_date,ov_od_time,ov_order_status,ov_IDX,ov_order_id,ov_mall_name,ov_mall_id,ov_order_name,ov_order_tel,ov_order_hp,ov_receive_name,ov_receive_tel,ov_receive_hp,ov_receive_zip,ov_receive_addr,ov_sap_code,ov_mall_code,ov_sabang_code,ov_it_name,ov_brand,ov_options,ov_distribution_status,ov_qty,ov_total_cost,ov_pay_cost,ov_delv_cost,ov_order_msg,ov_invoice_no,ov_delivery_company,ov_delivery_company_code,ov_sms_check,ov_ct_id,ov_sum_sno,ov_register_datetime FROM sabang_lt_order_view WHERE slov_id = {$row['slov_id']}";
              $res = sql_query($insertSql);
              if ($res) $newSlov_id = sql_insert_id();
            }
            $ov_stock1 = 0;
            $ov_stock2 = 0;
            $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(0,$sapCode12,$color,$size);
            if (count($stockSamjin) == 0) {
            } else {
              for ($j =0; $j < count($stockSamjin); $j++) {
                if ($stockSamjin[$j]['C_NO'] == 50 || $stockSamjin[$j]['C_NO'] == 51) {
                  $ov_stock1 += $stockSamjin[$j]['STOCK'];
                } else {
                  $ov_stock2 += $stockSamjin[$j]['STOCK'];
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
              $ov_delivery_company = 'CJ택배';
              $ov_delivery_company_code = '003';
            } else if ($ov_stock2 != 0 && $ov_stock2 >= (int)$row['ov_qty'] * $fromQty) {
              $ov_dpartner = '어시스트';
              $ov_delivery_company = '롯데택배';
              $ov_delivery_company_code = '002';
            } else {
              $ov_distribution_status = '물류품절';
              $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status'  WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' OR ov_order_id LIKE ('{$row['ov_order_id']}%')) AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}'";
              sql_query($updateSql); 
              $ov_dpartner = null;
              $ov_delivery_company = null;
              $ov_delivery_company_code = null;
            }
            $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE (slov_id = '{$row['slov_id']}' OR sub_slov_id = '{$row['slov_id']}' OR ov_order_id LIKE ('{$row['ov_order_id']}%')) AND ov_mall_id ='{$row['ov_mall_id']}' AND ov_order_name ='{$row['ov_order_name']}' AND ov_distribution_status = '물류품절'";
            $soldOut = sql_fetch($selectSql);
            $soldOutCnt = $soldOut['CNT'];
            if ($soldOutCnt > 0) {
              $ov_distribution_status = '물류품절';
            } 

            $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_dpartner = '$ov_dpartner', 
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
            echo $ov_options_modify;
            echo 'check CHECK !!! <br>';
            $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 성공 ".$row['slov_id'] .",".$row['ov_options'];
          }
        }
      }
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
    $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
    $rsSamjin = mssql_sql_query($sqlSamjin);
    $ov_samjin_name = '';
    $ov_samjin_code = '';
    $ov_sap_code = '';
    for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
      $ov_samjin_name = $samrow['ITEM'];
      $ov_samjin_code = $samrow['ORDER_NO'];
      $ov_sap_code = $samrow['SAP_CODE'];
    }
    $ov_options_modify = $sapCode12.$color.$size;
    if ($ov_samjin_code=='') {
      $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 2
      WHERE slov_id = {$row['slov_id']} OR sub_slov_id = {$row['slov_id']}";
      sql_query($updateSql); 
      echo '<br>'.$ov_options_modify .'삼진에 없는 상품입니다 <br>';
      $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진에 없는 상품입니다 ".$row['slov_id'] .",".$row['ov_options'];
    } else {
      $ov_stock1 = 0;
      $ov_stock2 = 0;
      $stockSamjin = NM_GET_STOCK_WITH_SAP_CODE(0,$sapCode12,$color,$size);
      if (count($stockSamjin) == 0) {
      } else {
        $ov_distribution_status = null;
        if (strpos($row['ov_it_name'],'옥의티')!== false) {
            $ov_distribution_status = '리퍼';
        } else {
          for ($j =0; $j < count($stockSamjin); $j++) {
            if ($stockSamjin[$j]['C_NO'] == 50 || $stockSamjin[$j]['C_NO'] == 51) {
              $ov_stock1 += $stockSamjin[$j]['STOCK'];
            } else {
              $ov_stock2 += $stockSamjin[$j]['STOCK'];
            }
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
        $ov_delivery_company = 'CJ택배';
        $ov_delivery_company_code = '003';
      } else if ($ov_stock2 != 0 && $ov_stock2 >= (int)$row['ov_qty'] * $fromQty) {
        $ov_dpartner = '어시스트';
        $ov_delivery_company = '롯데택배';
        $ov_delivery_company_code = '002';
      } else {
          $ov_distribution_status = '물류품절';
          $updateSql = "UPDATE sabang_lt_order_view SET ov_distribution_status = '$ov_distribution_status' WHERE ov_order_id = '{$row['ov_order_id']}'";
          sql_query($updateSql); 
          $ov_dpartner = null;
          $ov_delivery_company = null;
          $ov_delivery_company_code = null;
      }
      $selectSql = "SELECT count(*) AS CNT FROM sabang_lt_order_view WHERE ov_order_id = {$row['ov_order_id']} AND ov_distribution_status = '물류품절'";
      $soldOut = sql_fetch($selectSql);
      $soldOutCnt = $soldOut['CNT'];
      if ($soldOutCnt > 0) {
        $ov_distribution_status = '물류품절';
      } 

      $updateSql = "UPDATE sabang_lt_order_view SET samjin_link_check = 1, ov_samjin_name = '$ov_samjin_name', ov_samjin_code = '$ov_samjin_code', ov_dpartner = '$ov_dpartner', 
                    ov_stock1 = '$ov_stock1', ov_stock2 = '$ov_stock2', ov_color = '$color', ov_size = '$size', ov_sap_code = '$ov_sap_code', ov_delivery_company = '$ov_delivery_company', ov_delivery_company_code = '$ov_delivery_company_code',
                    ov_options_modify = '$ov_options_modify', ov_distribution_status = '$ov_distribution_status', ov_qty_form = ov_qty * $fromQty
                    WHERE slov_id = {$row['slov_id']}";
      sql_query($updateSql); 

      $selectSql = "SELECT IFNULL(MAX(ov_sum_sno+1), 1) AS ov_sum_sno FROM sabang_lt_order_view WHERE ov_order_id = '{$row['ov_order_id']}' AND ov_dpartner = '$ov_dpartner' ORDER BY ov_sum_sno DESC LIMIT 1";
      $sum_sno = sql_fetch($selectSql);
      $ov_sum_sno = $sum_sno['ov_sum_sno'];
      $updateSql = "UPDATE sabang_lt_order_view SET ov_sum_sno = $ov_sum_sno  WHERE ov_order_id = {$row['ov_order_id']} AND ov_dpartner = '$ov_dpartner'";
      sql_query($updateSql); 
      echo $ov_options_modify;
      echo 'check CHECK !!! <br>';
      $outputs[] = date('Y-m-d H:i:s', time()) . " : 삼진 링크 성공 ".$row['slov_id'] .",".$row['ov_options'];
    }
  }
}
print_raw($outputs);
return;
