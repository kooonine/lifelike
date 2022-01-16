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

$reg_dt = date("Y-m-d");

// 전체 상품
$sabang_invoice_send = "select * from sabang_lt_order_form 
                    WHERE reg_dt LIKE '%{$reg_dt}%'
                    AND order_invoice is not null and invoice_up_dt is not null and mall_id not in (15001,19978) 
";

$sb_result = sql_query($sabang_invoice_send);
$sb_data = sql_fetch($sabang_invoice_send);

// xml 데이터 설정


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


$send_count = 0;

for($sbi = 0 ; $sb_row=sql_fetch_array($sb_result); $sbi++ ){

    $xml_code.="<DATA>\n";
        $xml_code.="<SABANGNET_IDX><![CDATA[{$sb_row['sabang_ord_no']}]]></SABANGNET_IDX>\n";
        $xml_code.="<TAK_CODE><![CDATA[{$sb_row['tak_code']}]]></TAK_CODE>\n";
        $xml_code.="<TAK_INVOICE><![CDATA[{$sb_row['order_invoice']}]]></TAK_INVOICE>\n";
        $xml_code.="<DELV_HOPE_DATE></DELV_HOPE_DATE>\n";
    $xml_code.="</DATA>\n";

    
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
$url = 'https://r.sabangnet.co.kr/RTL_API/xml_order_invoice.html?xml_url=https://lifelike.co.kr/adm/cron/sabang_invoice/'.$send_xml_name['xml_name'].'.xml';
$ch = cURL_init();


cURL_setopt($ch, CURLOPT_URL, $url);
cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = cURL_exec($ch);
cURL_close($ch); 
