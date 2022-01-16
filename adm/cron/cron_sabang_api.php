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
$sabang_send_goods = "select * from sabang_goods_origin 
                    WHERE char_2_val NOT LIKE '%,%' AND char_1_val NOT LIKE '%,%'
                    AND (compayny_goods_cd IS NOT NULL AND compayny_goods_cd <> '' AND compayny_goods_cd <> '등록전')
                    AND stock_send = 'Y'
";

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

                    $jego = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
                    // $jego = NM_GET_STOCK(0,$BARCODE,3);
                    if(count($jego) > 0){
                        for($t=0 ;  $t< count($jego); $t++){
                            if ($jego[$t]['C_NO'] == 2 || $jego[$t]['C_NO'] == 3 || $jego[$t]['C_NO'] == 4 || $jego[$t]['C_NO'] == 8 ) {
                                if($jego[$t]['C_NO'] == 2 || $jego[$t]['C_NO'] == 8){
                                    $total_stock += floor($jego[$t]['STOCK2'] / 2);
                                }else if ($jego[$t]['C_NO'] == 3 || $jego[$t]['C_NO'] == 4 ){
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

    // $sap_code = $sb_row['model_no'];
    // $color = $sb_row['char_1_val'];
    // $hoching = $sb_row['char_2_val'];
    // $compayny_goods_cd = $sb_row['compayny_goods_cd'];
    // $total_stock =null;
    // $jego=null;

    
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
    $send_count = $sbi;
}

$xml_code.="</SABANG_GOODS_REGI>\n";


$toDate = date("YmdHis");

$file_name =  $toDate."_small_goods"; //파일명지정
$dir_name = "./xml_sabang/".$file_name.".xml"; //디렉토리지정
file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함)

$history_sql = "insert into sabang_xml_history 
                set xml_name = '".$file_name."' 
                    , reg_date = '".$toDate."'
                ";
sql_query($history_sql);