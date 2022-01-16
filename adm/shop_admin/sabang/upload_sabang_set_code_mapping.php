<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');



$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];

// $etc_mall_id = $etc_mall_id;
// if($etc_mall_id == '19963'){
//     $mall_name = '굿닷컴';
// }else if ($etc_mall_id == '19950'){
//     $mall_name = 'SSF SHOP';
// }


$file = $_FILES['upload_excel']['tmp_name'];

$UpFile	= $_FILES["upload_excel"];

$UpFileName = $UpFile["name"];

$UpFilePathInfo = pathinfo($UpFileName);
$UpFileExt		= strtolower($UpFilePathInfo["extension"]);

if($UpFileExt != "xls") {
    echo '<script>alert("파일형식이 올바르지 않습니다. 엑셀 확장자를 xlsx가 아닌 xls로 등록해주세요.");history.back();</script>';
	return false;
}




$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('UTF-8');
$data->read($file);

$toDate = date("YmdHis");


for ($j = 2; $j <= $data->sheets[0]['numRows']; $j++) {
    
    $mall_code = $data->sheets[0]['cells'][$j][1];
    $set_name = preg_replace("/[\"\']/i", "", $data->sheets[0]['cells'][$j][2]);
    $set_code = $data->sheets[0]['cells'][$j][3];
    $sku_val = ltrim($data->sheets[0]['cells'][$j][4]);
    $company_goods_cd = $data->sheets[0]['cells'][$j][5];

    $set_price = preg_replace('/,/', '',$data->sheets[0]['cells'][$j][6]);

    $merge_sql = "select count(*) AS cnt from sabang_set_code_mapping 
    where mall_code = '{$mall_code}'  and set_code = '{$set_code}' 
    ";
    $merge_item = sql_fetch($merge_sql);

    if($merge_item['cnt'] > 0 ){
        $del_sql = "DELETE FROM sabang_set_code_mapping where mall_code = '{$mall_code}'  and set_code = '{$set_code}'  ";
        sql_query($del_sql);
        // echo '<script>alert("'.$mall_code.' 몰코드에 \n 상품명 '.$set_name.' \n 세트코드 '.$set_code.' 으로 중복으로 존재합니다.");history.back();</script>';
        // return false;
    }

}


for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
    
    $mall_code = $data->sheets[0]['cells'][$i][1];
    $set_name = preg_replace("/[\"\']/i", "", $data->sheets[0]['cells'][$i][2]);
    $set_code = $data->sheets[0]['cells'][$i][3];
    $sku_val = $data->sheets[0]['cells'][$i][4];
    $company_goods_cd = $data->sheets[0]['cells'][$i][5];
    $set_price = preg_replace('/,/', '',$data->sheets[0]['cells'][$i][6]);
    if(!empty($set_price)){
        $set_price_type = "Y";
    }else{
        $set_price_type = "N";
    }

    if(!empty($mall_code)){
    
        
            $sql= "insert into sabang_set_code_mapping
                        set reg_date = '".$toDate."'
                        ,mall_code = '".$data->sheets[0]['cells'][$i][1]."'
                        
                        ,company_goods_cd = '".$data->sheets[0]['cells'][$i][5]."'
                        ,sabang_goods_cd = '".$data->sheets[0]['cells'][$i][7]."'
                        ,mall_goods_cd = '".$data->sheets[0]['cells'][$i][8]."'
                        ,set_name = '".$set_name."'
                        ,set_price = '".$set_price."'
                        ,set_price_type = '".$set_price_type."'
                        ,set_code = '".$data->sheets[0]['cells'][$i][3]."'
                        ,sku_value = '".$data->sheets[0]['cells'][$i][4]."'
                        ,it_name = '".$data->sheets[0]['cells'][$i][7]."'
                        
        
                        ";
        
            sql_query($sql);

        
    }

    

}
goto_url("./sabang_set_code_mapping.php?".$qstr);

?>