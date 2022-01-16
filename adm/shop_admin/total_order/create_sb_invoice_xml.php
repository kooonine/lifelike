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


$xml_code.="<SABANG_INV_REGI>\n";
    $xml_code.="<HEADER>\n";
        $xml_code.="<SEND_COMPAYNY_ID>LITANDARD</SEND_COMPAYNY_ID>\n";
        $xml_code.="<SEND_AUTH_KEY><![CDATA[Z6MV9M8HK9HSK0xx0XYxG7EACRMGJ0KTX6]]></SEND_AUTH_KEY>\n";
        $xml_code.="<SEND_DATE><![CDATA[".$today."]]></SEND_DATE>\n";
        $xml_code.="<SEND_INV_EDIT_YN>N</SEND_INV_EDIT_YN>\n";
    $xml_code.="</HEADER>\n";
    $xml_code.="<DATA>\n";
        $xml_code.="<SABANGNET_IDX><![CDATA[2471476]]></SABANGNET_IDX>\n";
        $xml_code.="<TAK_CODE><![CDATA[003]]></TAK_CODE>\n";
        $xml_code.="<TAK_INVOICE><![CDATA[635862084104]]></TAK_INVOICE>\n";
        $xml_code.="<DELV_HOPE_DATE></DELV_HOPE_DATE>\n";
    $xml_code.="</DATA>\n";
$xml_code.="</SABANG_INV_REGI>\n";

$toDate = date("YmdHis");

$file_name =  $toDate."_invoice_send"; //파일명지정
$dir_name = "./sabang_invocie/".$file_name.".xml"; //디렉토리지정
file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함)


$history_sql = "insert into sabang_invoice_xml_history 
                set xml_name = '".$file_name."' 
                    , reg_date = '".$toDate."'
                ";
sql_query($history_sql);

?>


<!-- <div><a href="http://r.sabangnet.co.kr/RTL_API/xml_goods_info2.html?xml_url=<?=G5_URL?>/adm/shop_admin/sabang/xml_sabang/<?=$file_name?>.xml" target ="_blank">사방넷 주문수집</a></div> -->
