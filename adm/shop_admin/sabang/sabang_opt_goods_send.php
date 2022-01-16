<?php

// $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
// $root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));
// $outputs = array();
// // $outputs[] = date('Y-m-d H:i:s', time()) . " : 크론시작  ";
// // include_once($root_path . '/../../common.php');

// include_once($root_path.'/_common.php');
include_once('./_common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');



$ps_id = $_POST['ps_id'];
$pi_it_name = $_POST['pi_it_name'];
$pi_sub_category = $_POST['pi_sub_category'];
$pi_size = $_POST['pi_size'];
$type = $_POST['type'];

function color_table($text){
    if(preg_match("/[a-zA-Z]/",$text)){
        switch($text){
            case 'AA' : $color_nm = "기타"; break;
            case 'BE' : $color_nm = "베이지"; break;
            case 'BK' : $color_nm = "블랙"; break;
            case 'BL' : $color_nm = "블루"; break;
            case 'BR' : $color_nm = "브라운"; break;
            case 'CR' : $color_nm = "크림"; break;
            case 'DB' : $color_nm = "진블루"; break;
            case 'DP' : $color_nm = "진핑크"; break;
            case 'FC' : $color_nm = "푸시아"; break;
            case 'GD' : $color_nm = "골드"; break;
            case 'GN' : $color_nm = "그린"; break;
            case 'GR' : $color_nm = "그레이"; break;
            case 'IV' : $color_nm = "아이보리"; break;
            case 'KA' : $color_nm = "카키"; break;
            case 'LB' : $color_nm = "연블루"; break;
            case 'LG' : $color_nm = "연그레이"; break;
            case 'LP' : $color_nm = "연핑크"; break;
            case 'LV' : $color_nm = "라벤다"; break;
            case 'MT' : $color_nm = "민트"; break;
            case 'MU' : $color_nm = "멀티"; break;
            case 'MV' : $color_nm = "모브"; break;
            case 'MX' : $color_nm = "혼합"; break;
            case 'NC' : $color_nm = "내츄럴"; break;
            case 'NV' : $color_nm = "네이비"; break;
            case 'OR' : $color_nm = "오렌지"; break;
            case 'PC' : $color_nm = "청록"; break;
            case 'PK' : $color_nm = "핑크"; break;
            case 'PU' : $color_nm = "퍼플"; break;
            case 'RD' : $color_nm = "레드"; break;
            case 'WH' : $color_nm = "화이트"; break;
            case 'YE' : $color_nm = "노랑"; break;
        }
    }else{
        $color_nm = $text;
    }
    return $color_nm;
}

if($type == 'S'){
    
    $piSql = "SELECT * FROM lt_prod_info WHERE ps_id = '$ps_id' ORDER BY pi_size DESC LIMIT 1 ";
    $piResult = sql_fetch($piSql);

    $items_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '$ps_id' ORDER BY pi_size DESC";
    $items_res = sql_query($items_sql);
    $option_res = sql_query($items_sql);

    $info_sql = "SELECT * FROM lt_prod_schedule  WHERE ps_id = '$ps_id'";
    $info_res = sql_fetch($info_sql);

    $c_pi_it_name = "[".$info_res['ps_brand']."]";
    $name_array = explode('(', $info_res['ps_it_name']);
    $c_pi_it_name .= " ".$name_array[0];
    $c_pi_it_name .= " ".$info_res['ps_prod_name'];

    $opt_company_id = $piResult['pi_model_no'];

    for ($si = 0; $item_row = sql_fetch_array($items_res); $si++) {
        switch(trim($item_row['pi_size'])){
            case 'S':
                $SIZE = "싱글";
                break;
            case 'Q':
                $SIZE = "퀸";
                break;
            case 'K':
                $SIZE = "킹";
                break;
            case 'SS':
                $SIZE = "슈퍼싱글";
                break;
        }
        if($si == 0){
            $c_pi_it_name .= " ".$item_row['pi_size'];
            $opt_company_id .=$item_row['pi_size'];
            $option_nm = $SIZE."(".$item_row['pi_company_it_id'].")";
        }else{
            $c_pi_it_name .= "/".$item_row['pi_size'];
            $opt_company_id .=$item_row['pi_size'];
            $option_nm .= ",".$SIZE."(".$item_row['pi_company_it_id'].")";
        }
        
        // $option_nm = $SIZE;
        // $option_nm .= "(".$item_row['pi_company_it_id'].")";
    }
    $c_pi_it_name .= "(".color_table($piResult['pi_color']).")";
    
    
    $S_COLOR = color_table($piResult['pi_color']);
    
    $CHAR_1_NM = "색상";
    $CHAR_1_VAL = "";
    $CHAR_2_NM = "사이즈";
    $CHAR_2_VAL = "";


}else if($type == 'C'){
    $piSql = "SELECT * FROM lt_prod_info WHERE ps_id = '$ps_id' AND pi_size = '{$pi_size}' ORDER BY pi_size DESC LIMIT 1 ";
    $piResult = sql_fetch($piSql);

    $info_sql = "SELECT * FROM lt_prod_schedule  WHERE ps_id = '$ps_id'";
    $info_res = sql_fetch($info_sql);

    $deff_color = "SELECT * FROM lt_prod_schedule WHERE ps_item_nm = '{$info_res['ps_item_nm']}' AND ps_prod_name = '{$info_res['ps_prod_name']}' ";
    $deff_res = sql_query($deff_color);
    $opt_deff_res = sql_query($deff_color);

    // $items_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '$ps_id' ORDER BY pi_size DESC";
    // $items_res = sql_query($items_sql);
    // $option_res = sql_query($items_sql);


    $c_pi_it_name = "[".$info_res['ps_brand']."]";
    $name_array = explode('(', $info_res['ps_it_name']);
    $c_pi_it_name .= " ".$name_array[0];
    $c_pi_it_name .= " ".$info_res['ps_prod_name'];
    

    $opt_company_id = $piResult['pi_model_no'];

    for ($si = 0; $item_row = sql_fetch_array($deff_res); $si++) {
        $items_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '{$item_row['ps_id']}' AND pi_size = '{$piResult['pi_size']}' ORDER BY pi_size DESC  LIMIT 1";
        $items_res = sql_fetch($items_sql);
        // $option_res = sql_query($items_sql);
        switch(trim($piResult['pi_size'])){
            case 'S':
                $SIZE = "싱글";
                break;
            case 'Q':
                $SIZE = "퀸";
                break;
            case 'K':
                $SIZE = "킹";
                break;
            case 'SS':
                $SIZE = "슈퍼싱글";
                break;
            default :
                $SIZE = $piResult['pi_cisu'];
                break;
        }
        if($si == 0){
            
            $option_nm = color_table($items_res['pi_color'])."(".$items_res['pi_company_it_id'].")";
        }else{
            
            $option_nm .= ",".color_table($items_res['pi_color'])."(".$items_res['pi_company_it_id'].")";
        }
        
        // $option_nm = $SIZE;
        // $option_nm .= "(".$item_row['pi_company_it_id'].")";
    }
    if(strpos($info_res['ps_prod_name'] , "베개커버") === false ){
        $opt_company_id .= "_". $piResult['pi_size'];
        $c_pi_it_name .= " ".$piResult['pi_size'];
    }else{
        $opt_company_id .= "_".  str_replace('*','X' , $piResult['pi_cisu']);
        $c_pi_it_name .= " ".str_replace('*','X' , $piResult['pi_cisu']);
    }
    
    $c_pi_it_name .= "(".$si."color)";
    
    $S_COLOR = $SIZE;

    $CHAR_1_NM = "사이즈";
    $CHAR_1_VAL = "";
    $CHAR_2_NM = "색상";
    $CHAR_2_VAL = "";


}else if($type == 'SC'){
    
    $piSql = "SELECT * FROM lt_prod_info WHERE ps_id = '$ps_id' AND pi_size = '{$pi_size}' ORDER BY pi_size DESC LIMIT 1 ";
    $piResult = sql_fetch($piSql);

    $info_sql = "SELECT * FROM lt_prod_schedule  WHERE ps_id = '$ps_id'";
    $info_res = sql_fetch($info_sql);

    $deff_color = "SELECT * FROM lt_prod_schedule WHERE ps_item_nm = '{$info_res['ps_item_nm']}' AND ps_prod_name = '{$info_res['ps_prod_name']}' ";
    $deff_res = sql_query($deff_color);
    $opt_deff_res = sql_query($deff_color);

    // $items_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '$ps_id' ORDER BY pi_size DESC";
    // $items_res = sql_query($items_sql);
    // $option_res = sql_query($items_sql);


    $c_pi_it_name = "[".$info_res['ps_brand']."]";
    $name_array = explode('(', $info_res['ps_it_name']);
    $c_pi_it_name .= " ".$name_array[0];
    $c_pi_it_name .= " ".$info_res['ps_prod_name'];
    

    $opt_company_id = $piResult['pi_model_no'];

    $opt_company_id .= "_";

    for ($si = 0; $item_row = sql_fetch_array($deff_res); $si++) {
        $items_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '{$item_row['ps_id']}'  ORDER BY pi_size DESC  LIMIT 1";
        $items_res = sql_fetch($items_sql);

        if($si == 0 ){
            $SIZE = $items_res['pi_color'];
        }else{
            $SIZE .= ','.$items_res['pi_color'];
        }

        $option_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '{$item_row['ps_id']}'  ORDER BY pi_size DESC";
        $option_res = sql_query($option_sql);

        for ($oi = 0; $option_row = sql_fetch_array($option_res); $oi++) {
            switch(trim($option_row['pi_size'])){
                case 'S':
                    $_SIZE = "싱글";
                    break;
                case 'Q':
                    $_SIZE = "퀸";
                    break;
                case 'K':
                    $_SIZE = "킹";
                    break;
                case 'SS':
                    $_SIZE = "슈퍼싱글";
                    break;
            }
            if($si == 0){
                if($oi == 0){
                    $option_nm = $_SIZE;
                    $c_pi_it_name .= ' '.$option_row['pi_size'];
                }else{
                    
                    $option_nm .= ",".$_SIZE;
                    $c_pi_it_name .= '/'.$option_row['pi_size'];
                }
                $opt_company_id .= $option_row['pi_size'];
            }else{
            }
        }

    }

    $S_COLOR = $SIZE;
  
    $c_pi_it_name .= '(색상선택)';

    $CHAR_1_NM = "사이즈";
    $CHAR_1_VAL = "";
    $CHAR_2_NM = "색상";
    $CHAR_2_VAL = "";

}


$result;


if(!empty($piResult)){

    if($piResult['pi_brand'] == '템퍼'){
        $brand_img_path = 'tempur';
    }else if ($piResult['pi_brand'] == '쉐르단'){
        $brand_img_path = 'sheridan';
    }else{
        $brand_img_path = 'sofraum';
    }

    if(file('https://lifelikecdn.co.kr/sabang/'.$brand_img_path.'/'.$piResult['pi_model_name'].'_THUM_1.jpg')){
        $THUM1 .= "https://lifelikecdn.co.kr/sabang/".$brand_img_path."/".$piResult['pi_model_name']."_THUM_1.jpg";
    }else{
        $THUM1 .= "";
        $result = "300";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }
    for($imi = 2; $imi < 10; $imi++){
        if(file('https://lifelikecdn.co.kr/sabang/'.$brand_img_path.'/'.$piResult['pi_model_name'].'_THUM_'.$imi.'.jpg')){
            ${"THUM".$imi} .= "https://lifelikecdn.co.kr/sabang/".$brand_img_path."/".$piResult['pi_model_name']."_THUM_".$imi.".jpg";
        }else{
            ${"THUM".$imi} .= "";
        }
    }
    if(empty($piResult['pi_it_name'])){ //상품명
        $result = "301";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }else if(empty($piResult['pi_model_name'])){ //모델명 sap코드
        $result = "302";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }else if(empty($piResult['pi_model_no'])){ //모델no 삼진
        $result = "303";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }else if(empty($piResult['pi_company_it_id'])){  //자체상품코드
        $result = "304";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }else if(empty($piResult['pi_origin_price'])){  //원가
        $result = "305";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }else if(empty($piResult['pi_sale_price'])){ //판가
        $result = "306";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }else if(empty($piResult['pi_tag_price'])){ //tag가
        $result = "307";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }

    $pi_images = array();
    if (!empty($piResult['pi_img'])) {
        $pi_images = json_decode($piResult['pi_img'], true);
    }

    $GOODS_REMARKS = "<div align="."center".">";

    foreach ($pi_images as $pii => $pi_image){
        if(!empty($pi_image['img'])){
            $url = str_replace(" ","",$pi_image['img']);
            if(file($url)){
                $GOODS_REMARKS .= "<img src=".$pi_image['img'].">";
            }else{
                $result = "308";
                echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                return false;
            }
        }
    }
    $GOODS_REMARKS .= "</div>";

    //삼진 등록 여부 체크 재고 확인
    $a = $piResult['pi_company_it_id'];
    $sapCode12 = substr($a, 0, 12);
    $item_code = substr($a, 9, 3);
    $color = substr($a, 12, 2);
    $size = substr($a, 14);
    $strSize = array("x","X");  
    $size = str_replace($strSize,'*', $size);
    $newSlov_id = $key;
    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
    $g5['connect_samjindb'] = $connect_db;
    $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '$color' AND HOCHING = '$size'";
    $rsSamjin = mssql_sql_query($sqlSamjin);
    $num_rows = mssql_sql_num_rows($rsSamjin);

    if($num_rows == 1){
        
        for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
            $ov_samjin_name = $samrow['ITEM'];
            $ov_samjin_code = $samrow['ORDER_NO'];
            $ov_sap_code = $samrow['SAP_CODE'];
        }
        $BARCODE =$a;
        $ORDER_NO =$ov_samjin_code;
        $COLOR = $color;
        $HOCHING = $size;


        $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);

        $add_result_no = $barcode_add[0]['V1'];
        $add_result_meg = $barcode_add[0]['RSLT'];
        
        $total_stock =null;
        $jego=null;

        // $jego = NM_GET_STOCK_WITH_SAP_CODE(0,$sapCode12,$color,$size);
        $jego = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,$size);
        // $jego = NM_GET_STOCK(0,$BARCODE,3);
        if(count($jego) > 0){
            for($t=0 ;  $t< count($jego); $t++){
                if ($jego[$t]['C_NO'] == 2 || $jego[$t]['C_NO'] == 3 || $jego[$t]['C_NO'] == 4|| $jego[$t]['C_NO'] == 8) {
                    if($jego[$t]['C_NO'] == 2 || $jego[$t]['C_NO'] == 8){
                        $total_stock += floor($jego[$t]['STOCK2'] / 2);
                    }else if ($jego[$t]['C_NO'] == 3 || $jego[$t]['C_NO'] == 4){
                        $total_stock += floor($jego[$t]['STOCK2'] * 0.9 );
                    }
                }
            }
            if($total_stock < 3){
                $total_stock = 0;
            }
            
        }else{
            $total_stock = '';
        }

        if($total_stock > 0){
            $STATUS = "2";
        }else{
            $STATUS = "3";
        }
        // $upSql = "UPDATE sabang_goods_origin SET barcode_no = '{$add_result_no}' , barcode_meg = '{$add_result_meg}' WHERE no  = '$no' ";
        // sql_query($upSql);
    }else{
        $result = "400";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;   
    }
    if($item_code == '201' || $item_code == '202' || $item_code == '203'){
        
        $SIZE = str_replace('*','X', trim($piResult['pi_cisu']));

    }else{
        // $SIZE = $piResult['pi_size'];
        // switch(trim($piResult['pi_size'])){
        //     case 'S':
        //         $SIZE = "싱글";
        //         break;
        //     case 'Q':
        //         $SIZE = "퀸";
        //         break;
        //     case 'K':
        //         $SIZE = "킹";
        //         break;
        //     case 'SS':
        //         $SIZE = "슈퍼싱글";
        //         break;
        // }

    }
    // $option_nm = $SIZE;
    // $option_nm .= "(".$piResult['pi_company_it_id'].")";
    // xml 데이터 설정
    //색상:사이즈 [단품]  sku_val
    $today= date("Ymd");

    $item_soje = $piResult['pi_item_soje']."/".$piResult['pi_item_soje_dateil'];


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

        $xml_code.="<DATA>\n";

            $xml_code.= "<GOODS_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$c_pi_it_name)."]]></GOODS_NM>\n";
            $xml_code.= "<GOODS_KEYWORD><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_it_sub_name'])."]]></GOODS_KEYWORD>\n";
            $xml_code.= "<MODEL_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_model_name'])."]]></MODEL_NM>\n";
            $xml_code.= "<MODEL_NO><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_model_no'])."]]></MODEL_NO>\n";
            $xml_code.= "<BRAND_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_brand'])."]]></BRAND_NM>\n";
            $xml_code.= "<COMPAYNY_GOODS_CD><![CDATA[".iconv("UTF-8", "EUC-KR",$opt_company_id)."]]></COMPAYNY_GOODS_CD>\n";
            $xml_code.= "<GOODS_SEARCH><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_brand'].",침구,이불,베개,세트")."]]></GOODS_SEARCH>\n";
            $xml_code.= "<GOODS_GUBUN><![CDATA[2]]></GOODS_GUBUN>\n";
            $xml_code.= "<CLASS_CD1><![CDATA[".iconv("UTF-8", "EUC-KR","OPT")."]]></CLASS_CD1>\n";
            $xml_code.= "<CLASS_CD2><![CDATA[]]></CLASS_CD2>\n";
            $xml_code.= "<CLASS_CD3><![CDATA[]]></CLASS_CD3>\n";
            $xml_code.= "<PARTNER_ID><![CDATA[LITANDARD20]]></PARTNER_ID>\n";
            $xml_code.= "<DPARTNER_ID><![CDATA[assist2020]]></DPARTNER_ID>\n";
            $xml_code.= "<MAKER><![CDATA[".iconv("UTF-8", "EUC-KR","리탠다드")."]]></MAKER>\n";
            $xml_code.= "<ORIGIN><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_maker'])."]]></ORIGIN>\n";
            $xml_code.= "<MAKE_YEAR><![CDATA[]]></MAKE_YEAR>\n";
            $xml_code.= "<MAKE_DM><![CDATA[]]></MAKE_DM>\n";
            $xml_code.= "<GOODS_SEASON></GOODS_SEASON>\n";
            $xml_code.= "<SEX></SEX>\n";
            $xml_code.= "<STATUS>3</STATUS>\n";
            $xml_code.= "<DELIV_ABLE_REGION>1</DELIV_ABLE_REGION>\n";
            $xml_code.= "<TAX_YN>1</TAX_YN>\n";
            $xml_code.= "<DELV_TYPE><![CDATA[3]]></DELV_TYPE>\n";
            // $xml_code.= "<DELV_COST><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_delivery_price'])."]]></DELV_COST>\n";
            $xml_code.= "<DELV_COST><![CDATA[3000]]></DELV_COST>\n";
            $xml_code.= "<BANPUM_AREA></BANPUM_AREA>\n";
            $xml_code.= "<GOODS_COST><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_origin_price'])."]]></GOODS_COST>\n";
            $xml_code.= "<GOODS_PRICE><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_sale_price'])."]]></GOODS_PRICE>\n";
            $xml_code.= "<GOODS_CONSUMER_PRICE><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_tag_price'])."]]></GOODS_CONSUMER_PRICE>\n";
            $xml_code.= "<CHAR_1_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$CHAR_1_NM)."]]></CHAR_1_NM>\n";
            $xml_code.= "<CHAR_1_VAL><![CDATA[".iconv("UTF-8", "EUC-KR",$S_COLOR)."]]></CHAR_1_VAL>\n";
            $xml_code.= "<CHAR_2_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$CHAR_2_NM)."]]></CHAR_2_NM> \n";
            $xml_code.= "<CHAR_2_VAL><![CDATA[".iconv("UTF-8", "EUC-KR",$option_nm)."]]></CHAR_2_VAL>\n";
            $xml_code.= "<IMG_PATH><![CDATA[".$THUM1."]]></IMG_PATH>\n";
            $xml_code.= "<IMG_PATH1><![CDATA[".$THUM1."]]></IMG_PATH1>\n";
            $xml_code.= "<IMG_PATH2><![CDATA[".$THUM2."]]></IMG_PATH2>\n";
            $xml_code.= "<IMG_PATH3><![CDATA[".$THUM3."]]></IMG_PATH3>\n";
            $xml_code.= "<IMG_PATH4><![CDATA[".$THUM4."]]></IMG_PATH4>\n";
            $xml_code.= "<IMG_PATH5><![CDATA[".$THUM5."]]></IMG_PATH5>\n";
            $xml_code.= "<IMG_PATH6><![CDATA[".$THUM6."]]></IMG_PATH6>\n";
            $xml_code.= "<IMG_PATH7><![CDATA[".$THUM7."]]></IMG_PATH7>\n";
            $xml_code.= "<IMG_PATH8><![CDATA[".$THUM8."]]></IMG_PATH8>\n";
            $xml_code.= "<IMG_PATH9><![CDATA[]]></IMG_PATH9>\n";
            $xml_code.= "<IMG_PATH10><![CDATA[]]></IMG_PATH10>\n";
            $xml_code.= "<IMG_PATH11><![CDATA[]]></IMG_PATH11>\n";
            $xml_code.= "<IMG_PATH12><![CDATA[]]></IMG_PATH12>\n";
            $xml_code.= "<IMG_PATH13><![CDATA[]]></IMG_PATH13>\n";
            $xml_code.= "<IMG_PATH14><![CDATA[]]></IMG_PATH14>\n";
            $xml_code.= "<IMG_PATH15><![CDATA[]]></IMG_PATH15>\n";
            $xml_code.= "<IMG_PATH16><![CDATA[]]></IMG_PATH16>\n";
            $xml_code.= "<IMG_PATH17><![CDATA[]]></IMG_PATH17>\n";
            $xml_code.= "<IMG_PATH18><![CDATA[]]></IMG_PATH18>\n";
            $xml_code.= "<IMG_PATH19><![CDATA[]]></IMG_PATH19>\n";
            $xml_code.= "<IMG_PATH20><![CDATA[]]></IMG_PATH20>\n";
            $xml_code.= "<IMG_PATH21><![CDATA[]]></IMG_PATH21>\n";
            $xml_code.= "<IMG_PATH22><![CDATA[]]></IMG_PATH22>\n";
            $xml_code.= "<IMG_PATH23><![CDATA[]]></IMG_PATH23>\n";
            $xml_code.= "<IMG_PATH24><![CDATA[]]></IMG_PATH24>\n";
            $xml_code.= "<GOODS_REMARKS><![CDATA[".$GOODS_REMARKS."]]></GOODS_REMARKS>\n";
            $xml_code.= "<CERTNO><![CDATA[]]></CERTNO>\n";
            $xml_code.= "<AVLST_DM></AVLST_DM>\n";
            $xml_code.= "<AVLED_DM></AVLED_DM>\n";
            $xml_code.= "<ISSUEDATE></ISSUEDATE>\n";
            $xml_code.= "<CERTDATE></CERTDATE>\n";
            $xml_code.= "<CERT_AGENCY><![CDATA[]]></CERT_AGENCY>\n";
            $xml_code.= "<CERTFIELD><![CDATA[]]></CERTFIELD>\n";
            $xml_code.= "<STOCK_USE_YN><![CDATA[N]]></STOCK_USE_YN>\n";
            $xml_code.= "<PROP_EDIT_YN>Y</PROP_EDIT_YN>\n";
            $xml_code.= "<IMPORTNO><![CDATA[]]></IMPORTNO>\n";
            $xml_code.= "<PROP1_CD>005</PROP1_CD>\n";
            $xml_code.= "<PROP_VAL1><![CDATA[".iconv("UTF-8", "EUC-KR",$$c_pi_it_name)."]]></PROP_VAL1>\n";
            $xml_code.= "<PROP_VAL2><![CDATA[".iconv("UTF-8", "EUC-KR",$itam_soje)."]]></PROP_VAL2>\n";
            $xml_code.= "<PROP_VAL3><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL3>\n";
            $xml_code.= "<PROP_VAL4><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_cisu'])."]]></PROP_VAL4>\n";
            $xml_code.= "<PROP_VAL5><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL5>\n";
            $xml_code.= "<PROP_VAL6><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_maker'])."]]></PROP_VAL6>\n";
            $xml_code.= "<PROP_VAL7><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_laundry'])."]]></PROP_VAL7>\n";
            $xml_code.= "<PROP_VAL8><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL8>\n";
            $xml_code.= "<PROP_VAL9><![CDATA[".iconv("UTF-8", "EUC-KR","관련 법 및 소비자 분쟁해결 규정에 따름")."]]></PROP_VAL9>\n";
            $xml_code.= "<PROP_VAL10><![CDATA[02-3494-7602]]></PROP_VAL10>\n";
            $xml_code.= "<PROP_VAL11><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL11>\n";
            $xml_code.= "<PROP_VAL12><![CDATA[".iconv("UTF-8", "EUC-KR","무")."]]></PROP_VAL12>\n";
            $xml_code.= "<PROP_VAL13><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL13>\n";
            $xml_code.= "<PROP_VAL14><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL14>\n";
            $xml_code.= "<PROP_VAL15><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL15>\n";
            $xml_code.= "<PROP_VAL16><![CDATA[N]]></PROP_VAL16>\n";
            $xml_code.= "<PROP_VAL17><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL17>\n";
            $xml_code.= "<PROP_VAL18><![CDATA[".iconv("UTF-8", "EUC-KR","상세페이지 참조")."]]></PROP_VAL18>\n";
            $xml_code.= "<PROP_VAL19><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_cisu'])."]]></PROP_VAL19>\n";
            $xml_code.= "<PROP_VAL20><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_prod_weight'])."]]></PROP_VAL20>\n";
            $xml_code.= "<PROP_VAL21><![CDATA[]]></PROP_VAL21>\n";
            $xml_code.= "<PROP_VAL22><![CDATA[]]></PROP_VAL22>\n";
            $xml_code.= "<PROP_VAL23><![CDATA[]]></PROP_VAL23>\n";
            $xml_code.= "<PROP_VAL24><![CDATA[]]></PROP_VAL24>\n";

        $xml_code.="</DATA>\n";
    $xml_code.="</SABANG_GOODS_REGI>\n";


    $toDate = date("YmdHis");

    $file_name =  $toDate."_".$opt_company_id; //파일명지정
    $dir_name = "./sabang_new_goods/".$file_name.".xml"; //디렉토리지정
    file_put_contents($dir_name,$xml_code); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함);;;;
 
    
    $history_sql = "insert into sabang_new_goods_xml_history 
                    set xml_name = '".$file_name."' 
                        , reg_date = '".$toDate."'
                    ";
    sql_query($history_sql);

    $send_xml_sql = "select xml_name from sabang_new_goods_xml_history where status = 0 order by no desc limit 1";

    $send_xml_name = sql_fetch($send_xml_sql);

    //사방넷 상품 전송
    $url = 'http://a.sabangnet.co.kr/RTL_API/xml_goods_info.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_new_goods/'.$send_xml_name['xml_name'].'.xml';    
    // $url = 'http://a.sabangnet.co.kr/RTL_API/xml_goods_info.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_new_goods/20210127102353_MWS20FC54701WHQ.xml';    
    
    $ch = cURL_init();
    
    cURL_setopt($ch, CURLOPT_URL, $url);
    cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = cURL_exec($ch);
    cURL_close($ch); 
    
    $object = simplexml_load_string($response);

    if($object->children()->DATA->RESULT == 'SUCCESS'){

        $sabang_IDX = $object->children()->DATA->PRODUCT_ID;
        
        // $sabang_send_sql = "UPDATE lt_prod_info SET pi_sabang_send = '100' , pi_sabang_IDX = '{$sabang_IDX}' WHERE pi_id = '{$piResult['pi_id']}'";
        // sql_query($sabang_send_sql);


        // $ps_sabang_send = "SELECT * from  lt_prod_info WHERE ps_id = '{$piResult['ps_id']}' ";
        // $sabang_send_res = sql_query($ps_sabang_send);

        // $suss_cnt = 0;
        // $fail_cnt = 0;

        // for ($ssi = 0; $send_row = sql_fetch_array($sabang_send_res); $ssi++) {
        //     if($send_row['pi_sabang_send'] == '100'){
        //         $suss_cnt = $suss_cnt + 1;
        //     }else{
        //         $fail_cnt = $fail_cnt + 1;
        //     }
        // }


        // if($suss_cnt == 0){
        //     $ps_sabang_send_sql = "UPDATE lt_prod_schedule SET ps_sabang_send = '200' WHERE ps_id = '{$piResult['ps_id']}' ";
        // }else if($fail_cnt == 0){
        //     $ps_sabang_send_sql = "UPDATE lt_prod_schedule SET ps_sabang_send = '100' WHERE ps_id = '{$piResult['ps_id']}' ";
        // }else{
        //     $ps_sabang_send_sql = "UPDATE lt_prod_schedule SET ps_sabang_send = '300' WHERE ps_id = '{$piResult['ps_id']}' ";
        // }

        // sql_query($ps_sabang_send_sql);

        $merge_sql = "select count(*) AS cnt from sabang_goods_origin where sabang_goods_cd = '{$sabang_IDX}' ";
        $merge_item = sql_fetch($merge_sql);

        $item_soje = $piResult['pi_item_soje']."/".$piResult['pi_item_soje_dateil'];

        if($merge_item['cnt'] > 0 ){
            $upSql= "update sabang_goods_origin
                        set regdate = '{$today}'
                        , barcode_no = '{$add_result_no}'
                        , barcode_meg = '{$add_result_meg}'
                        , sabang_goods_cd = '{$sabang_IDX}'
                        , goods_nm = '{$c_pi_it_name}'
                        , goods_keyword  = '{$piResult['pi_it_sub_name']}'
                        , model_nm = '{$piResult['pi_model_name']}'
                        , model_no = '{$piResult['pi_model_no']}'
                        , brand_nm = '{$piResult['pi_brand']}'
                        , compayny_goods_cd = '{$opt_company_id}'
                        , goods_search = '{$piResult['pi_brand']},침구,이불,베개,세트'
                        , goods_gubun = ''
                        , class_cd1 = 'OPT'
                        , class_cd2 = ''
                        , class_cd3 = ''
                        , partner_id = 'LITANDARD20'
                        , dpartner_id = 'assist2020'
                        , maker = '리탠다드'
                        , origin = '{$piResult['pi_meker']}'
                        , make_year = ''
                        , make_dm = ''
                        , goods_season = ''
                        , sex = ''
                        , status = '{$STATUS}'
                        , deliv_able_region = '1'
                        , tax_yn = '1'
                        , delv_type = '3'
                        , delv_cost = '3000'
                        , goods_cost = '{$piResult['pi_origin_price']}'
                        , goods_price = '{$piResult['pi_sale_price']}'
                        , goods_consumer_price = '{$piResult['pi_tag_price']}'
                        , char_1_nm = '{$CHAR_1_NM}'
                        , char_1_val = '{$S_COLOR}'
                        , char_2_nm = '{$CHAR_2_NM}'
                        , char_2_val = '{$option_nm}'
                        , img_path = '{$THUM1}'
                        , img_path1 = '{$THUM1}'
                        , img_path2 = '{$THUM2}'
                        , img_path3 = '{$THUM3}'
                        , img_path4 = '{$THUM4}'
                        , img_path5 = '{$THUM5}'
                        , img_path6 = '{$THUM6}'
                        , img_path7 = '{$THUM7}'
                        , img_path8 = '{$THUM8}'
                        , img_path9 = ''
                        , img_path10 = ''
                        , img_path11 = ''
                        , img_path12 = ''
                        , img_path13 = ''
                        , img_path14 = ''
                        , img_path15 = ''
                        , img_path16 = ''
                        , img_path17 = ''
                        , img_path18 = ''
                        , img_path19 = ''
                        , img_path20 = ''
                        , img_path21 = ''
                        , img_path22 = ''
                        , img_path23 = ''
                        , img_path24 = ''
                        , goods_remarks = '{$GOODS_REMARKS}'
                        , certno = ''
                        , avlst_dm = ''
                        , avled_dm = ''
                        , issuedate = ''
                        , certdate = ''
                        , cert_agency = ''
                        , certfield = ''
                        , stock_use_yn = 'N'
                        , opt_type = ''
                        , prop_edit_yn = 'Y'
                        , importno = ''
                        , prop1_cd = '005'
                        , prop_val1 = '{$c_pi_it_name}'
                        , prop_val2 = '{$itam_soje}'
                        , prop_val3 = '상세페이지 참조'
                        , prop_val4 = '{$piResult['pi_cisu']}'
                        , prop_val5 = '상세페이지 참조'
                        , prop_val6 = '{$piResult['pi_maker']}'
                        , prop_val7 = '{$piResult['pi_laundry']}'
                        , prop_val8 = '상세페이지 참조'
                        , prop_val9 = '관련 법 및 소비자 분쟁해결 규정에 따름'
                        , prop_val10 = '02-3494-7602'
                        , prop_val11 = '상세페이지 참조'
                        , prop_val12 = '무'
                        , prop_val13 = '상세페이지 참조'
                        , prop_val14 = '상세페이지 참조'
                        , prop_val15 = '상세페이지 참조'
                        , prop_val16 = ''
                        , prop_val17 = '상세페이지 참조'
                        , prop_val18 = '상세페이지 참조'
                        , prop_val19 = '{$piResult['pi_cisu']}'
                        , prop_val20 = '{$piResult['pi_prod_weight']}'
                        , prop_val21 = ''
                        , prop_val22 = ''
                        , prop_val23 = ''
                        , prop_val24 = ''
                        where sabang_goods_cd = '{$sabang_IDX}'
        
                        ";
            sql_query($upSql);

            ////트리거로 대체 sabang_goods_origin_after_update
            // $upSql_new= "update sabang_send_goods_list
            //             set reg_dt = '{$today}'
            //             , sabang_goods_cd = '{$sabang_IDX}'
            //             , goods_nm = '{$piResult['pi_it_name']}'
            //             , goods_keyword  = '{$piResult['pi_it_sub_name']}'
            //             , model_nm = '{$piResult['pi_model_name']}'
            //             , model_no = '{$piResult['pi_model_no']}'
            //             , brand_nm = '{$piResult['pi_brand']}'
            //             , compayny_goods_cd = '{$piResult['pi_company_it_id']}'
            //             , goods_search = ''
            //             , goods_gubun = ''
            //             , class_cd1 = ''
            //             , class_cd2 = ''
            //             , class_cd3 = ''
            //             , partner_id = 'LITANDARD20'
            //             , dpartner_id = 'assist2020'
            //             , maker = '리탠다드'
            //             , origin = '{$piResult['pi_meker']}'
            //             , make_year = ''
            //             , make_dm = ''
            //             , goods_season = ''
            //             , sex = ''
            //             , status = '{$STATUS}'
            //             , deliv_able_region = '1'
            //             , tax_yn = '1'
            //             , delv_type = '3'
            //             , delv_cost = '3000'
            //             , goods_cost = '{$piResult['pi_origin_price']}'
            //             , goods_price = '{$piResult['pi_sale_price']}'
            //             , goods_consumer_price = '{$piResult['pi_tag_price']}'
            //             , char_1_nm = '{$CHAR_1_NM}'
            //             , char_1_val = '{$piResult['pi_color']}'
            //             , char_2_nm = '{$CHAR_2_NM}'
            //             , char_2_val = '{$SIZE.'('.$piResult['pi_company_it_id'].')'}'
            //             , img_path = '{$THUM1}'
            //             , img_path1 = '{$THUM1}'
            //             , img_path2 = '{$THUM2}'
            //             , img_path3 = '{$THUM3}'
            //             , img_path4 = '{$THUM4}'
            //             , img_path5 = '{$THUM5}'
            //             , img_path6 = '{$THUM6}'
            //             , img_path7 = '{$THUM7}'
            //             , img_path8 = '{$THUM8}'
            //             , img_path9 = ''
            //             , img_path10 = ''
            //             , img_path11 = ''
            //             , img_path12 = ''
            //             , img_path13 = ''
            //             , img_path14 = ''
            //             , img_path15 = ''
            //             , img_path16 = ''
            //             , img_path17 = ''
            //             , img_path18 = ''
            //             , img_path19 = ''
            //             , img_path20 = ''
            //             , img_path21 = ''
            //             , img_path22 = ''
            //             , img_path23 = ''
            //             , img_path24 = ''
            //             , goods_remarks = '{$GOODS_REMARKS}'
            //             , certno = ''
            //             , avlst_dm = ''
            //             , avled_dm = ''
            //             , issuedate = ''
            //             , certdate = ''
            //             , cert_agency = ''
            //             , certfield = ''
            //             , stock_use_yn = 'N'
            //             , opt_type = ''
            //             , prop_edit_yn = 'Y'
            //             , importno = ''
            //             , prop1_cd = '005'
            //             , prop_val1 = '{$piResult['pi_it_name']}'
            //             , prop_val2 = '{$piResult['pi_item_soje']}'
            //             , prop_val3 = '상세페이지 참조'
            //             , prop_val4 = '{$piResult['pi_cisu']}'
            //             , prop_val5 = '상세페이지 참조'
            //             , prop_val6 = '{$piResult['pi_maker']}'
            //             , prop_val7 = '{$piResult['pi_laundry']}'
            //             , prop_val8 = '상세페이지 참조'
            //             , prop_val9 = '관련 법 및 소비자 분쟁해결 규정에 따름'
            //             , prop_val10 = '02-3494-7681'
            //             , prop_val11 = '상세페이지 참조'
            //             , prop_val12 = '무'
            //             , prop_val13 = '상세페이지 참조'
            //             , prop_val14 = '상세페이지 참조'
            //             , prop_val15 = '상세페이지 참조'
            //             , prop_val16 = ''
            //             , prop_val17 = '상세페이지 참조'
            //             , prop_val18 = '상세페이지 참조'
            //             , prop_val19 = '{$piResult['pi_cisu']}'
            //             , prop_val20 = '{$piResult['pi_prod_weight']}'
            //             , prop_val21 = ''
            //             , prop_val22 = ''
            //             , prop_val23 = ''
            //             , prop_val24 = ''
            //             where sabang_goods_cd = '{$sabang_IDX}'
        
            //             ";
            // sql_query($upSql_new);


        }else{
            $goods_sql= "insert into sabang_goods_origin
                        set regdate = '{$today}'
                        , barcode_no = '{$add_result_no}'
                        , barcode_meg = '{$add_result_meg}'
                        , sabang_goods_cd = '{$sabang_IDX}'
                        , goods_nm = '{$c_pi_it_name}'
                        , goods_keyword  = '{$piResult['pi_it_sub_name']}'
                        , model_nm = '{$piResult['pi_model_name']}'
                        , model_no = '{$piResult['pi_model_no']}'
                        , brand_nm = '{$piResult['pi_brand']}'
                        , compayny_goods_cd = '{$opt_company_id}'
                        , goods_search = '{$piResult['pi_brand']},침구,이불,베개,세트'
                        , goods_gubun = ''
                        , class_cd1 = 'OPT'
                        , class_cd2 = ''
                        , class_cd3 = ''
                        , partner_id = 'LITANDARD20'
                        , dpartner_id = 'assist2020'
                        , maker = '리탠다드'
                        , origin = '{$piResult['pi_meker']}'
                        , make_year = ''
                        , make_dm = ''
                        , goods_season = ''
                        , sex = ''
                        , status = '{$STATUS}'
                        , deliv_able_region = '1'
                        , tax_yn = '1'
                        , delv_type = '3'
                        , delv_cost = '3000'
                        , goods_cost = '{$piResult['pi_origin_price']}'
                        , goods_price = '{$piResult['pi_sale_price']}'
                        , goods_consumer_price = '{$piResult['pi_tag_price']}'
                        , char_1_nm = '{$CHAR_1_NM}'
                        , char_1_val = '{$S_COLOR}'
                        , char_2_nm = '{$CHAR_2_NM}'
                        , char_2_val = '{$option_nm}'
                        , img_path = '{$THUM1}'
                        , img_path1 = '{$THUM1}'
                        , img_path2 = '{$THUM2}'
                        , img_path3 = '{$THUM3}'
                        , img_path4 = '{$THUM4}'
                        , img_path5 = '{$THUM5}'
                        , img_path6 = '{$THUM6}'
                        , img_path7 = '{$THUM7}'
                        , img_path8 = '{$THUM8}'
                        , img_path9 = ''
                        , img_path10 = ''
                        , img_path11 = ''
                        , img_path12 = ''
                        , img_path13 = ''
                        , img_path14 = ''
                        , img_path15 = ''
                        , img_path16 = ''
                        , img_path17 = ''
                        , img_path18 = ''
                        , img_path19 = ''
                        , img_path20 = ''
                        , img_path21 = ''
                        , img_path22 = ''
                        , img_path23 = ''
                        , img_path24 = ''
                        , goods_remarks = '{$GOODS_REMARKS}'
                        , certno = ''
                        , avlst_dm = ''
                        , avled_dm = ''
                        , issuedate = ''
                        , certdate = ''
                        , cert_agency = ''
                        , certfield = ''
                        , stock_use_yn = 'N'
                        , opt_type = ''
                        , prop_edit_yn = 'Y'
                        , importno = ''
                        , prop1_cd = '005'
                        , prop_val1 = '{$c_pi_it_name}'
                        , prop_val2 = '{$piResult['pi_item_soje']}'
                        , prop_val3 = '상세페이지 참조'
                        , prop_val4 = '{$piResult['pi_cisu']}'
                        , prop_val5 = '상세페이지 참조'
                        , prop_val6 = '{$piResult['pi_maker']}'
                        , prop_val7 = '{$piResult['pi_laundry']}'
                        , prop_val8 = '상세페이지 참조'
                        , prop_val9 = '관련 법 및 소비자 분쟁해결 규정에 따름'
                        , prop_val10 = '02-3494-7681'
                        , prop_val11 = '상세페이지 참조'
                        , prop_val12 = '무'
                        , prop_val13 = '상세페이지 참조'
                        , prop_val14 = '상세페이지 참조'
                        , prop_val15 = '상세페이지 참조'
                        , prop_val16 = ''
                        , prop_val17 = '상세페이지 참조'
                        , prop_val18 = '상세페이지 참조'
                        , prop_val19 = '{$piResult['pi_cisu']}'
                        , prop_val20 = '{$piResult['pi_prod_weight']}'
                        , prop_val21 = ''
                        , prop_val22 = ''
                        , prop_val23 = ''
                        , prop_val24 = ''
        
                        ";
        
            sql_query($goods_sql);
        }


        // $result = "200";
        // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        // return false;
        
    }else{
    
    }

    //옵션 수정 바로

    $xml_code_op = "";
    $xml_code_op = "<?xml version=\"1.0\" encoding=\"euc-kr\" ?>\n";  

    $xml_code_op.="<SABANG_GOODS_REGI>\n";
        $xml_code_op.="<HEADER>\n";
            $xml_code_op.="<SEND_COMPAYNY_ID>LITANDARD</SEND_COMPAYNY_ID>\n";
            $xml_code_op.="<SEND_AUTH_KEY><![CDATA[Z6MV9M8HK9HSK0xx0XYxG7EACRMGJ0KTX6]]></SEND_AUTH_KEY>\n";
            $xml_code_op.="<SEND_DATE><![CDATA[".$today."]]></SEND_DATE>\n";
            $xml_code_op.="<SEND_GOODS_CD_RT>Y</SEND_GOODS_CD_RT>\n";
        $xml_code_op.="</HEADER>\n";

        $xml_code_op.="<DATA>\n";

            $xml_code_op.="<GOODS_NM><![CDATA[".iconv("UTF-8", "EUC-KR",$c_pi_it_name)."]]></GOODS_NM>\n";
            $xml_code_op.="<COMPAYNY_GOODS_CD><![CDATA[".iconv("UTF-8", "EUC-KR",$opt_company_id)."]]></COMPAYNY_GOODS_CD>\n";
            $xml_code_op.="<STATUS>3</STATUS>\n";
            $xml_code_op.= "<GOODS_COST><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_origin_price'])."]]></GOODS_COST>\n";
            $xml_code_op.= "<GOODS_PRICE><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_sale_price'])."]]></GOODS_PRICE>\n";
            $xml_code_op.= "<GOODS_CONSUMER_PRICE><![CDATA[".iconv("UTF-8", "EUC-KR",$piResult['pi_tag_price'])."]]></GOODS_CONSUMER_PRICE>\n";

            $xml_code_op.="<SKU_INFO>\n";

            if($type == 'S'){
                for ($sio = 0; $option_row = sql_fetch_array($option_res); $sio++) {
                    $option_val = "";
                    $option_size = "";
                    $option_price = ($option_row['pi_sale_price'] - $piResult['pi_sale_price']);
                    switch(trim($option_row['pi_size'])){
                        case 'S':
                            $option_size = "싱글";
                            break;
                        case 'Q':
                            $option_size = "퀸";
                            break;
                        case 'K':
                            $option_size = "킹";
                            break;
                        case 'SS':
                            $option_size = "슈퍼싱글";
                            break;
                        default : 
                            $option_size = $option_row['pi_cisu'];
                            break;
                    }
                    
    
                    $option_val = $option_size."(".$option_row['pi_company_it_id'].")";
    
                    $xml_code_op.="<SKU_VALUE>".iconv("UTF-8", "EUC-KR",$option_row['pi_color']).":".iconv("UTF-8", "EUC-KR",$option_val)."^^".$option_row['pi_samjin_stock']."^^".$option_price."</SKU_VALUE>\n";    
                }
            }else if ($type == 'C'){

                for ($sio = 0; $option_row = sql_fetch_array($opt_deff_res); $sio++) {
                    $items_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '{$option_row['ps_id']}' AND pi_size = '{$piResult['pi_size']}' ORDER BY pi_size DESC  LIMIT 1";
                    $items_res = sql_fetch($items_sql);
                    // $option_res = sql_query($items_sql);
                    $option_val = "";
                    $option_size = "";
                    $option_price = ($items_res['pi_sale_price'] - $piResult['pi_sale_price']);
                    switch(trim($piResult['pi_size'])){
                        case 'S':
                            $SIZE = "싱글";
                            break;
                        case 'Q':
                            $SIZE = "퀸";
                            break;
                        case 'K':
                            $SIZE = "킹";
                            break;
                        case 'SS':
                            $SIZE = "슈퍼싱글";
                            break;
                        default : 
                            $SIZE = $piResult['pi_cisu'];
                        break;
                    }
                    $option_val = color_table($items_res['pi_color'])."(".$items_res['pi_company_it_id'].")";
    
                    $xml_code_op.="<SKU_VALUE>".iconv("UTF-8", "EUC-KR",$SIZE).":".iconv("UTF-8", "EUC-KR",$option_val)."^^".$items_res['pi_samjin_stock']."^^".$option_price."</SKU_VALUE>\n";    
                    
                    // $option_nm = $SIZE;
                    // $option_nm .= "(".$item_row['pi_company_it_id'].")";
                }
            }else if ($type == 'SC'){

                for ($z = 0; $de_op_row = sql_fetch_array($opt_deff_res); $z++) {
                    $ops_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '{$de_op_row['ps_id']}'  ORDER BY pi_size DESC  LIMIT 1";
                    $ops_res = sql_fetch($ops_sql);
            
                    $val1 = color_table($ops_res['pi_color']);

                    $option_price1  = $de_op_row['pi_sale_price'];
            
                    $optval_sql = "SELECT * FROM lt_prod_info WHERE ps_id = '{$de_op_row['ps_id']}'  ORDER BY pi_size DESC";
                    $optval_res = sql_query($optval_sql);
            
                    for ($q = 0; $optval_row = sql_fetch_array($optval_res); $q++) {
                        $option_price = ($optval_row['pi_sale_price'] - $option_price1);
                        switch(trim($optval_row['pi_size'])){
                            case 'S':
                                $_SIZE = "싱글";
                                break;
                            case 'Q':
                                $_SIZE = "퀸";
                                break;
                            case 'K':
                                $_SIZE = "킹";
                                break;
                            case 'SS':
                                $_SIZE = "슈퍼싱글";
                                break;
                        }
                        $option_val = $_SIZE."(".$optval_row['pi_company_it_id'].")";

                        $xml_code_op.="<SKU_VALUE>".iconv("UTF-8", "EUC-KR",$val1).":".iconv("UTF-8", "EUC-KR",$option_val)."^^".$optval_res['pi_samjin_stock']."^^".$option_price."</SKU_VALUE>\n";    

                    }
            
                }

            }
            

            

            $xml_code_op.="</SKU_INFO>\n";
            
        $xml_code_op.="</DATA>\n";

    $xml_code_op.="</SABANG_GOODS_REGI>\n";


    $file_name =  "edit_".$toDate."_".$opt_company_id; //파일명지정
    $dir_name = "./sabang_new_goods/".$file_name.".xml"; //디렉토리지정
    file_put_contents($dir_name,$xml_code_op); //파일 생성하는 함수 (PHP5 전용, PHP4는 fopen() 사용해야함);;;;


    $history_sql = "insert into sabang_new_goods_xml_history 
                    set xml_name = '".$file_name."' 
                        , reg_date = '".$toDate."'
                    ";
    sql_query($history_sql);

    $opt_send_xml_sql = "select xml_name from sabang_new_goods_xml_history where status = 0 order by no desc limit 1";

    $opt_send_xml_name = sql_fetch($opt_send_xml_sql);

    //사방넷 수정 상품 전송
    $url = 'http://a.sabangnet.co.kr/RTL_API/xml_goods_info2.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_new_goods/'.$opt_send_xml_name['xml_name'].'.xml';    
    // $url = 'http://a.sabangnet.co.kr/RTL_API/xml_goods_info.html?xml_url=https://lifelike.co.kr/adm/shop_admin/sabang/sabang_new_goods/20210127102353_MWS20FC54701WHQ.xml';    
    
    $ch = cURL_init();
    
    cURL_setopt($ch, CURLOPT_URL, $url);
    cURL_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = cURL_exec($ch);
    cURL_close($ch); 
    

    $result = "200";
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return false;



    // $result = $response;
    // echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    // return false;
    
    
    
    
    // $object = simplexml_load_string($response);
    
    // foreach($object->children() as $res) {
        //     $sql_common = " IDX = '{$res->IDX}' " ;
        // }
        



}else{
    $result = 'error : 상품정보집 없음';
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return false;
}

?>