<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');



$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];

   
$cno = array();
foreach ($_POST["cno"] as $cn => $c_no) {
    // $pno_list[$pn] = $p_no;
    $sql_common = "";
   
    $cno =  $_POST["item_cno"][$cn];
    $company_goods_cd =  $_POST["company_goods_cd"][$cn];
    $set_price =  preg_replace('/,/', '',$_POST["set_price"][$cn]);
    $set_price_type =  $_POST["set_price_type"][$cn];

    if(!empty($set_price)){
        $set_price_type = 'Y';
    }

    
    if(!empty($cno)){
        $sql_common .= " company_goods_cd = '{$company_goods_cd}' ";
        $sql_common .= " , set_price = '{$set_price}' ";
        $sql_common .= " , set_price_type = '{$set_price_type}' ";
        $up_set_sql = "update sabang_set_code_mapping set  $sql_common where cno = '$cno' ";
        sql_query($up_set_sql);
    }



}

goto_url("./sabang_set_code_mapping.php?".$qstr);

?>