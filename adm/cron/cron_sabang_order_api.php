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

// 전체 상품
$sabang_send_goods = "select * from sabang_goods_origin ";

$sb_result = sql_query($sabang_send_goods);
$sb_data = sql_fetch($sabang_send_goods);

// xml 데이터 설정
//색상:사이즈 [단품]  sku_val
$today= date("Ymd");

$stday= date("Ymd" , strtotime($day." -7 day"));

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
        $xml_code.="<ORD_ST_DATE>$stday</ORD_ST_DATE>\n";
        $xml_code.="<ORD_ED_DATE>$today</ORD_ED_DATE>\n";

        $xml_code.="<ORD_FIELD><![CDATA[IDX|ORDER_ID|MALL_ID|MALL_USER_ID|ORDER_STATUS|USER_ID|USER_NAME|USER_TEL|USER_CEL|USER_EMAIL|RECEIVE_TEL|RECEIVE_CEL|RECEIVE_EMAIL|DELV_MSG|RECEIVE_NAME|RECEIVE_ZIPCODE|RECEIVE_ADDR|TOTAL_COST|PAY_COST|ORDER_DATE|PARTNER_ID|DPARTNER_ID|MALL_PRODUCT_ID|PRODUCT_ID|SKU_ID|P_PRODUCT_NAME|P_SKU_VALUE|PRODUCT_NAME|SALE_COST|MALL_WON_COST|WON_COST|SKU_VALUE|SALE_CNT|DELIVERY_METHOD_STR|DELV_COST|COMPAYNY_GOODS_CD|SKU_ALIAS|BOX_EA|JUNG_CHK_YN|MALL_ORDER_SEQ|MALL_ORDER_ID|ETC_FIELD3|ORDER_GUBUN|P_EA|REG_DATE|ORDER_ETC_1|ORDER_ETC_2|ORDER_ETC_3|ORDER_ETC_4|ORDER_ETC_5|ORDER_ETC_6|ORDER_ETC_7|ORDER_ETC_8|ORDER_ETC_9|ORDER_ETC_10|ORDER_ETC_11|ORDER_ETC_12|ORDER_ETC_13|ORDER_ETC_14|ord_field2|copy_idx|GOODS_NM_PR|GOODS_KEYWORD|ORD_CONFIRM_DATE|RTN_DT|CHNG_DT|DELIVERY_CONFIRM_DATE|CANCEL_DT|CLASS_CD1|CLASS_CD2|CLASS_CD3|CLASS_CD4|BRAND_NM|DELIVERY_ID|INVOICE_NO|HOPE_DELV_DATE|FLD_DSP|INV_SEND_MSG|MODEL_NO|SET_GUBUN|ETC_MSG|DELV_MSG1|MUL_DELV_MSG|BARCODE|INV_SEND_DM|DELIVERY_METHOD_STR2]]></ORD_FIELD>\n";
        $xml_code.="<ORDER_STATUS>001</ORDER_STATUS>\n";
        $xml_code.="<LANG>UTF-8</LANG>\n";
    $xml_code.="</DATA>\n";
$xml_code.="</SABANG_ORDER_LIST>\n";


$toDate = date("YmdHis");

$file_name =  $toDate."_order_collection"; //파일명지정
$dir_name = "./sabang_order/".$file_name.".xml"; //디렉토리지정
file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함)

$history_sql = "insert into sabang_order_xml_history 
                set xml_name = '".$file_name."' 
                    , reg_date = '".$toDate."'
                ";
sql_query($history_sql);


sleep(10);


$send_xml_sql = "select xml_name from sabang_order_xml_history where status = 0 order by no desc limit 1";

$send_xml_name = sql_fetch($send_xml_sql);


$url = 'https://r.sabangnet.co.kr/RTL_API/xml_order_info.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_order/'.$send_xml_name['xml_name'].'.xml';
$ch = cURL_init();

cURL_setopt($ch, CURLOPT_URL, $url);
cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = cURL_exec($ch);
cURL_close($ch); 

$object = simplexml_load_string($response);

$toDate = date("YmdH");


foreach($object->children() as $orders) {
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
                  ,PRODUCT_NAME = '{$orders->PRODUCT_NAME}'
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
