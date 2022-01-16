<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

$test = false;
$outputs = array();

//크론1 : 상품준비중 => 배송중, RFID 변경
$connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
$g5['connect_samjindb'] = $connect_db;

$now_date = date("Y-m-d H:i:s");

$today= date("Ymd");

$stday= date("Ymd" , strtotime($day." -20 day"));

//xml 상단에 무조건 있어야하는 코드
$xml_code = "";
$xml_code = "<?xml version=\"1.0\" encoding=\"euc-kr\" ?>\n";  


$xml_code.="<SABANG_ORDER_LIST>\n";
    $xml_code.="<HEADER>\n";
        $xml_code.="<SEND_COMPAYNY_ID>LITANDARD</SEND_COMPAYNY_ID>\n";
        $xml_code.="<SEND_AUTH_KEY><![CDATA[Z6MV9M8HK9HSK0xx0XYxG7EACRMGJ0KTX6]]></SEND_AUTH_KEY>\n";
        $xml_code.="<SEND_DATE><![CDATA[".$today."]]></SEND_DATE>\n";
    $xml_code.="</HEADER>\n";
    $xml_code.="<DATA>\n";
        $xml_code.="<CLM_ST_DATE>$stday</CLM_ST_DATE>\n";
        $xml_code.="<CLM_ED_DATE>$today</CLM_ED_DATE>\n";

        $xml_code.="<CLM_FIELD><![CDATA[IDX|ORDER_ID|MALL_ID|MALL_USER_ID|ORDER_STATUS|USER_ID|USER_NAME|USER_TEL|USER_CEL|USER_EMAIL|RECEIVE_TEL|RECEIVE_CEL|RECEIVE_EMAIL|DELV_MSG|RECEIVE_NAME|RECEIVE_ZIPCODE|RECEIVE_ADDR|TOTAL_COST|PAY_COST|ORDER_DATE|PARTNER_ID|DPARTNER_ID|MALL_PRODUCT_ID|PRODUCT_ID|SKU_ID|P_PRODUCT_NAME|P_SKU_VALUE|PRODUCT_NAME|SALE_COST|MALL_WON_COST|WON_COST|SKU_VALUE|SALE_CNT|COMPAYNY_GOODS_CD|CLAME_STATUS_GUBUN|CLAME_CONTENT|CLAME_INS_DATE|CLAME_REG_DATE|CL_IDX|MALL_ORDER_ID]]></CLM_FIELD>\n";
        $xml_code.="<LANG>UTF-8</LANG>\n";
    $xml_code.="</DATA>\n";
$xml_code.="</SABANG_ORDER_LIST>\n";


$toDate = date("YmdHis");

$file_name =  $toDate."_clm_collection"; //파일명지정
$dir_name = "./sabang_clm/".$file_name.".xml"; //디렉토리지정
file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함)

$history_sql = "insert into sabang_clm_xml_history 
                set xml_name = '".$file_name."' 
                    , reg_date = '".$toDate."'
                ";
sql_query($history_sql);

sleep(10);

$send_xml_sql = "select xml_name from sabang_clm_xml_history where status = 0 order by no desc limit 1";

$send_xml_name = sql_fetch($send_xml_sql);

$url = 'https://r.sabangnet.co.kr/RTL_API/xml_clm_info.html?xml_url=https://lifelike.co.kr/adm/cron/sabang_clm/'.$send_xml_name['xml_name'].'.xml';

$ch = cURL_init();

cURL_setopt($ch, CURLOPT_URL, $url);
cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = cURL_exec($ch);
cURL_close($ch); 

$object = simplexml_load_string($response);

$toDate = date("YmdH");

$cancel_cnt = 0;

$kuengmin_meg = 0;
$assist_meg = 0;

foreach($object->children() as $clm) {
  // $clm_stat = $clm->CLAME_STATUS_GUBUN;
  // $sabang_id = $clm->IDX;

  $cancel1 = strpos($clm->CLAME_STATUS_GUBUN, "취소");
  $cancel2 = strpos($clm->CLAME_STATUS_GUBUN, "환불");

  $sql_common = " reg_date = '{$toDate}'
                  ,reg_datetime = '{$now_date}'
                  ,IDX = '{$clm->IDX}'
                  ,ORDER_ID = '{$clm->ORDER_ID}'
                  ,MALL_ID = '{$clm->MALL_ID}'
                  ,MALL_USER_ID = '{$clm->MALL_USER_ID}'
                  ,ORDER_STATUS = '{$clm->ORDER_STATUS}'
                  ,USER_ID = '{$clm->USER_ID}'
                  ,USER_NAME = '{$clm->USER_NAME}'
                  ,USER_TEL = '{$clm->USER_TEL}'
                  ,USER_CEL = '{$clm->USER_CEL}'
                  ,USER_EMAIL = '{$clm->USER_EMAIL}'
                  ,RECEIVE_TEL = '{$clm->RECEIVE_TEL}'
                  ,RECEIVE_CEL = '{$clm->RECEIVE_CEL}'
                  ,RECEIVE_EMAIL = '{$clm->RECEIVE_EMAIL}'
                  ,DELV_MSG = '{$clm->DELV_MSG}'
                  ,RECEIVE_NAME = '{$clm->RECEIVE_NAME}'
                  ,RECEIVE_ZIPCODE = '{$clm->RECEIVE_ZIPCODE}'
                  ,RECEIVE_ADDR = '{$clm->RECEIVE_ADDR}'
                  ,TOTAL_COST = '{$clm->TOTAL_COST}'
                  ,PAY_COST = '{$clm->PAY_COST}'
                  ,ORDER_DATE = '{$clm->ORDER_DATE}'
                  ,PARTNER_ID = '{$clm->PARTNER_ID}'
                  ,DPARTNER_ID = '{$clm->DPARTNER_ID}'
                  ,MALL_PRODUCT_ID = '{$clm->MALL_PRODUCT_ID}'
                  ,PRODUCT_ID = '{$clm->PRODUCT_ID}'
                  ,SKU_ID = '{$clm->SKU_ID}'
                  ,P_PRODUCT_NAME = '{$clm->P_PRODUCT_NAME}'
                  ,P_SKU_VALUE = '{$clm->P_SKU_VALUE}'
                  ,PRODUCT_NAME = '{$clm->PRODUCT_NAME}'
                  ,SALE_COST = '{$clm->SALE_COST}'
                  ,MALL_WON_COST = '{$clm->MALL_WON_COST}'
                  ,WON_COST = '{$clm->WON_COST}'
                  ,SKU_VALUE = '{$clm->SKU_VALUE}'
                  ,SALE_CNT = '{$clm->SALE_CNT}'
                  ,COMPAYNY_GOODS_CD = '{$clm->COMPAYNY_GOODS_CD}'
                  ,CLAME_STATUS_GUBUN = '{$clm->CLAME_STATUS_GUBUN}'
                  ,CLAME_CONTENT = '{$clm->CLAME_CONTENT}'
                  ,CLAME_INS_DATE = '{$clm->CLAME_INS_DATE}'
                  ,CLAME_REG_DATE = '{$clm->CLAME_REG_DATE}'
                  ,CL_IDX = '{$clm->CL_IDX}'
                  ,MALL_ORDER_ID = '{$clm->MALL_ORDER_ID}'
  ";
  if(!empty($clm->IDX)){
    $chk_sql = "select count(*) AS cnt from sabang_clm_origin where IDX = '{$clm->IDX}' and ORDER_STATUS = '{$clm->ORDER_STATUS}' and CLAME_STATUS_GUBUN = '{$clm->CLAME_STATUS_GUBUN}' ";
    $chk_result = sql_fetch($chk_sql);

    if($chk_result['cnt'] < 1 ){
        $sql = " insert sabang_clm_origin set $sql_common ";
        sql_query($sql);

        if($cancel1 !== false || $cancel2 !== false){
          $chk_view_sql = "select count(*) AS cnt from sabang_lt_order_view where ov_IDX = '{$clm->IDX}' AND ov_order_status <> '출고전취소'";
          $chk_view_result = sql_fetch($chk_view_sql);
          if($chk_view_result['cnt'] > 0 ){
            //출고전 취소 상태 변경
            $view_sql = "update sabang_lt_order_view set ov_order_status = '출고전취소' ,  ov_distribution_status = '출고전취소' , ov_update_datetime = '{$now_date}' where ov_IDX = '{$clm->IDX}' and ov_order_status <> '품절취소' ";
            sql_query($view_sql);
            
            
            $cancel_sql = "select count(*) AS cnt , dpartner_id from sabang_lt_order_form where sabang_ord_no = '{$clm->IDX}' and dpartner_stat <> '출고전취소' ";
            $cancel_result = sql_fetch($cancel_sql);
            if($cancel_result['dpartner_id'] == '경민실업'){
              $kuengmin_meg++;
            }else if($meg_result['dpartner_id'] == '어시스트'){
              $assist_meg++;
            }
            if($cancel_result['cnt'] > 0 ){
              $form_sql = "update sabang_lt_order_form set dpartner_stat = '출고전취소' , update_dt = '{$now_date}'  where mall_order_no = '{$clm->ORDER_ID}' AND sabang_ord_no = '{$clm->IDX}'";
              sql_query($form_sql);
              $cancel_cnt++;
            }
          }
        }
    }
    
  }
}

if($cancel_cnt > 0 ){
  if($kuengmin_meg > 0){

    $msg_body = "[리탠다드] 취소건이 있습니다.";
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
  
}

