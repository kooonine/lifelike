<?php

// $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
// $root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));
// $outputs = array();
// // $outputs[] = date('Y-m-d H:i:s', time()) . " : 크론시작  ";
// // include_once($root_path . '/../../common.php');

// include_once($root_path.'/_common.php');
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');



$sabang_goods_cds = $_POST['sabang_goods_cds'];
$sabang_status = $_POST['status'];


$sabang_goods_list = "SELECT * FROM sabang_goods_origin WHERE sabang_goods_cd IN ({$sabang_goods_cds}) ";

$sb_result = sql_query($sabang_goods_list);

$result;


// xml 데이터 설정
//색상:사이즈 [단품]  sku_val
$today= date("Ymd");

//xml 상단에 무조건 있어야하는 코드
$xml_code = "";
$xml_code = "<?xml version=\"1.0\" encoding=\"euc-kr\" ?>\n";  

$xml_code.="<SABANG_GOODS_REGI>\n";
    $xml_code.="<HEADER>\n";
        $xml_code.="<SEND_COMPAYNY_ID>LITANDARD</SEND_COMPAYNY_ID>\n";
        $xml_code.="<SEND_AUTH_KEY><![CDATA[Z6MV9M8HK9HSK0xx0XYxG7EACRMGJ0KTX6]]></SEND_AUTH_KEY>\n";
        $xml_code.="<SEND_DATE><![CDATA[".$today."]]></SEND_DATE>\n";
        $xml_code.="<SEND_GOODS_CD_RT>Y</SEND_GOODS_CD_RT>\n";
        $xml_code.="<RESULT_TYPE>XML</RESULT_TYPE>\n";
    $xml_code.="</HEADER>\n";


$send_count = 0;

for($sbi = 0 ; $sb_row=sql_fetch_array($sb_result); $sbi++ ){
    
    // $jego = NM_GET_STOCK(0,$compayny_goods_cd,3);
    // if(count($jego) > 0){
    //     for($t=0 ;  $t< count($jego); $t++){
    //         if($jego[$t]['C_NO'] == 2 || $jego[$t]['C_NO'] == 3 || $jego[$t]['C_NO'] == 4|| $jego[$t]['C_NO'] == 12){
    //             $total_stock += floor($jego[$t]['STOCK'] / 2);
    //         }else{
    //             $total_stock += $jego[$t]['STOCK'];
    //         }
    //     }
    //     if($total_stock < 3){
    //         $total_stock = 0;
    //     }
        
    // }else{
    //     $total_stock = '';
    // }

    
    $xml_code.="<DATA>\n";

        $xml_code.="<GOODS_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$sb_row['goods_nm'])."]]></GOODS_NM>\n";
        $xml_code.="<COMPAYNY_GOODS_CD><![CDATA[".iconv("UTF-8", "EUC-KR",$sb_row['compayny_goods_cd'])."]]></COMPAYNY_GOODS_CD>\n";
        $xml_code.="<STATUS>".$sabang_status."</STATUS>\n";
        $xml_code.="<GOODS_COST>".$sb_row['goods_cost']."</GOODS_COST>\n";
        $xml_code.="<GOODS_PRICE>".$sb_row['goods_price']."</GOODS_PRICE>\n";
        $xml_code.="<GOODS_CONSUMER_PRICE>".$sb_row['goods_consumer_price']."</GOODS_CONSUMER_PRICE>\n";

        
    $xml_code.="</DATA>\n";

    $send_count = $sbi;
}

$xml_code.="</SABANG_GOODS_REGI>\n";


// while($row = mysql_fetch_assoc($result)){

//     foreach((array)$row as $key => $val) {
//         $row[$key] = trim($val); // 데이터들의 앞뒤 공백을 없앤다.

//     }
//     //자기 스타일대로 데이터를 지지고 볶는다..

//     $xml_code .= "<태그명>\n";

//     $xml_code .=  "<![CDATA[ {$row[컬럼명]} ]]>";  // <![CDATA[  내용 ]]> 이렇게 넣으면 특수문자가 들어가도 에러 없음

//     $xml_code .= "</태그명>\n";
// }

$toDate = date("YmdHis");

$file_name =  $toDate."_goods_status"; //파일명지정
$dir_name = "./sabang_status/".$file_name.".xml"; //디렉토리지정
file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함)


$history_sql = "insert into sabang_goods_stat_xml_history 
                set xml_name = '".$file_name."' 
                    , reg_date = '".$toDate."'
                ";
sql_query($history_sql);

$send_xml_sql = "select xml_name from sabang_goods_stat_xml_history where status = 0 order by no desc limit 1";

$send_xml_name = sql_fetch($send_xml_sql);

//사방넷 상품 전송
$url = 'http://a.sabangnet.co.kr/RTL_API/xml_goods_info2.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_status/'.$send_xml_name['xml_name'].'.xml';    
// $url = 'http://a.sabangnet.co.kr/RTL_API/xml_goods_info2.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_status/20210202094738_goods_status.xml';

$ch = cURL_init();

cURL_setopt($ch, CURLOPT_URL, $url);
cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = cURL_exec($ch);
cURL_close($ch); 

$object = simplexml_load_string($response);

foreach($object->children()->DATA as $send) {
    if($send->RESULT == 'SUCCESS'){

        $sabang_IDX = $send->PRODUCT_ID;
        
        $merge_sql = "select count(*) AS cnt from sabang_goods_origin where sabang_goods_cd = '{$sabang_IDX}' ";
        $merge_item = sql_fetch($merge_sql);

        if($merge_item['cnt'] > 0 ){
            $upSql= "update sabang_goods_origin
                        set status = '{$sabang_status}'
                        
                        where sabang_goods_cd = '{$sabang_IDX}'
        
                        ";
            sql_query($upSql);

        }
        
    }else{

    }
}

$result = "200";
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return false;

?>