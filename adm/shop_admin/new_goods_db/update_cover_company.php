<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("qstr=" , $referer);

$id = $_POST['id'];
$price = preg_replace('/,/', '',$_POST['price']);



$qstr=$cut_url[1];


if(!empty($id)){

    $sql = "UPDATE lt_company_manufacturing SET cm_append_price = '{$price}' WHERE cm_id = {$id}";
    
    sql_query($sql);
    
}


$result = '200';
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;

?>
// goto_url("./new_goods_db_cover_main.php?".$qstr);