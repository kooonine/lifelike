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
$compayny_goods_cd_p = $_POST['compayny_goods_cd'];

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



$sabang_send_goods = "select * from sabang_goods_origin ";
if(!empty($compayny_goods_cd_p)){

    $goods_cd_list = explode(',', $compayny_goods_cd_p);

    $in_list = empty($goods_cd_list)?'NULL':"'".join("','", $goods_cd_list)."'";
    
    $sabang_send_goods.= "where compayny_goods_cd IN({$in_list})";
    
}
$sb_result = sql_query($sabang_send_goods);
$sb_data = sql_fetch($sabang_send_goods);

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
    $xml_code.="</HEADER>\n";


$send_count = 0;

for($sbi = 0 ; $sb_row=sql_fetch_array($sb_result); $sbi++ ){

    // $sap_code = $sb_row['model_no'];
    // $color = $sb_row['char_1_val'];
    // $hoching = $sb_row['char_2_val'];
    // $compayny_goods_cd = $sb_row['compayny_goods_cd'];
    $total_stock =null;
    $jego=null;

    $value = $sb_row['char_2_val'];
    preg_match_all("/[^() || \-\ \/\,]+/", $value,$c);
    $total_stock =null;
    //단품상품
    if(empty($value)){
        $value = $sb_row['compayny_goods_cd'];
        preg_match_all("/[^_]+/", $value,$c);
    } 
    
    foreach($c[0] as $a) {
        if (strlen($a) > 14) {
            if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
                if(!empty($a)){
                    $sapCode12 = substr($a, 0, 12);
                    $color = substr($a, 12, 2);
                    $size = substr($a, 14);
                    $strSize = array("x","X");  
                    $size = str_replace($strSize,'*', $size);
                    // $newSlov_id = $key;
                    // $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
                    // $g5['connect_samjindb'] = $connect_db;
                    // $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
                    // $rsSamjin = mssql_sql_query($sqlSamjin);
                    // $num_rows = mssql_sql_num_rows($rsSamjin);

                    // for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
                    //     // $ov_samjin_name = $samrow['ITEM'];
                    //     $ov_samjin_code = $samrow['ORDER_NO'];
                    //     $ov_sap_code = $samrow['SAP_CODE'];
                    // }

                    // $BARCODE =$a;
                    // $ORDER_NO =$ov_samjin_code;
                    // $COLOR = $color;
                    // $HOCHING = $size;

                    // $jego = NM_GET_STOCK_WITH_SAP_CODE(0,$sapCode12,$color,$size);
                    $jego = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
                    // $jego = NM_GET_STOCK(0,$BARCODE,3);
                    if(count($jego) > 0){
                        for($t=0 ;  $t< count($jego); $t++){
                            if ($jego[$t]['C_NO'] == 2 || $jego[$t]['C_NO'] == 3 || $jego[$t]['C_NO'] == 4|| $jego[$t]['C_NO'] == 8) {
                                if($jego[$t]['C_NO'] == 2 || $jego[$t]['C_NO'] == 8){
                                    $total_stock += floor($jego[$t]['STOCK2'] / 2);
                                }else if ($jego[$t]['C_NO'] == 3 || $jego[$t]['C_NO'] == 4){
                                    $total_stock += floor($jego[$t]['STOCK2'] * 0.9);
                                }
                            }
                        }
                        if($total_stock < 3){
                            $total_stock = 0;
                        }
                        
                    }else{
                        $total_stock = '';
                    }                    
                }
            }
        }
    }
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

    if( strpos($sb_row['char_2_val'],',') === false ){
    $xml_code.="<DATA>\n";

        $xml_code.="<GOODS_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$sb_row['goods_nm'])."]]></GOODS_NM>\n";
        $xml_code.="<COMPAYNY_GOODS_CD><![CDATA[".iconv("UTF-8", "EUC-KR",$sb_row['compayny_goods_cd'])."]]></COMPAYNY_GOODS_CD>\n";
        $xml_code.="<STATUS>".$sb_row['status']."</STATUS>\n";
        $xml_code.="<GOODS_COST>".$sb_row['goods_cost']."</GOODS_COST>\n";
        $xml_code.="<GOODS_PRICE>".$sb_row['goods_price']."</GOODS_PRICE>\n";
        $xml_code.="<GOODS_CONSUMER_PRICE>".$sb_row['goods_consumer_price']."</GOODS_CONSUMER_PRICE>\n";

        $xml_code.="<SKU_INFO>\n";
        if(empty($sb_row['char_2_val'])){
            $xml_code.="<SKU_VALUE>".iconv("UTF-8", "EUC-KR","단품")."^^".$total_stock."</SKU_VALUE>\n";     
        }else{
            $xml_code.="<SKU_VALUE>".iconv("UTF-8", "EUC-KR",$sb_row['char_1_val']).":".iconv("UTF-8", "EUC-KR",$sb_row['char_2_val'])."^^".$total_stock."</SKU_VALUE>\n";    
        }
        $xml_code.="</SKU_INFO>\n";
        
    $xml_code.="</DATA>\n";
    }

    echo "".$sap_code."" .$color."".$hoching."".$total_stock.'<br>';

    $send_count = $sbi;
}

$xml_code.="</SABANG_GOODS_REGI>\n";


echo $send_count+1;

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

$file_name =  $toDate."_small_goods"; //파일명지정
$dir_name = "./xml_sabang/".$file_name.".xml"; //디렉토리지정
file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함)


$history_sql = "insert into sabang_xml_history 
                set xml_name = '".$file_name."' 
                    , reg_date = '".$toDate."'
                ";
sql_query($history_sql);

?>


<div><a href="http://r.sabangnet.co.kr/RTL_API/xml_goods_info2.html?xml_url=<?=G5_URL?>/adm/shop_admin/sabang/xml_sabang/<?=$file_name?>.xml" target ="_blank">사방넷 송신</a></div>
