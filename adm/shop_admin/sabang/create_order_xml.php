<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

// // DB 연결
// $conn = "";
// mysql_query("set names utf8"); //쿼리에서 한글이 깨질수도 있으니 utf-8로 설정

// // xml 에 뿌려질 데이터를 가져옴

// $sql = "SELECT * FROM 테이블이름 WHERE 조건절 ";
// $result = mysql_query($sql, $conn);
// mysql_close($conn);

// $sql = "select * from lt_shop_item where it_id='010010060000159' order by it_id asc ";
// $result = sql_query($sql);
// $data = sql_fetch($sql);
// $compayny_goods_cd_p = $_POST['compayny_goods_cd'];

// $ORDER_NO = 'MWS20HC51403';

//$data1 = SM_TRAN_ORDER_DATA();
//$data2 = SM_GET_STOCK($ORDER_NO);

// ERP 에서 특정상품 재고 요청

$connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
$g5['connect_samjindb'] = $connect_db;

// $sap_code = $_POST['sap_code'];
// $min_price = $_POST['min_price'];

// $sql = "SELECT ORDER_NO,SAP_CODE,ITEM,CAT_NO,CAT_ITEM,STATUS,COLOR,COLOR_NAME,SZ,HOCHING,PRICE,STOCK
//         FROM S_MALL_ORDERS
//         WHERE SAP_CODE LIKE '{$ORDER_NO}%'
//         ORDER BY COLOR, SZ";
// $result = mssql_sql_query($sql);
// $row = mssql_sql_fetch_array($result);


// echo '<script>';
//   echo 'console.log('. json_encode( $row ) .')';
// echo '</script>';


// xml 테이터 가져오기




// xml 데이터 설정
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

?>


<!-- <div><a href="http://r.sabangnet.co.kr/RTL_API/xml_goods_info2.html?xml_url=<?=G5_URL?>/adm/shop_admin/sabang/xml_sabang/<?=$file_name?>.xml" target ="_blank">사방넷 주문수집</a></div> -->
