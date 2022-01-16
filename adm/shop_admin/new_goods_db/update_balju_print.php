<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


$referer = $_SERVER["HTTP_REFERER"];


$pno = $_POST['pno'];
$ps_id = $_POST['ps_id'];
$item_name = $_POST['item_name'];
$comform_user = $_POST['comform_user'];

$ps_year = $_POST['ps_year'];
$ps_season = $_POST['ps_season'];
$ps_job_gubun = $_POST['ps_job_gubun'];
$brand = $_POST['brand'];
$prod_user = $_POST['prod_user'];
$balju_date = $_POST['balju_date'];
$balju_limit_date = $_POST['balju_limit_date'];

$main_yd = $_POST['o_main_yd'];
$codi_yd = $_POST['o_codi_yd'];
$codi1_yd = $_POST['o_codi1_yd'];

// $mater_name = $_POST['mater_name'];

$now_date = date('Y-m-d H:i:s');

$mater_info = array();
foreach ($_POST["mater_info"] as $mi => $info) {
    $temp_item_set = array(  
        "main" => $_POST['mater_info_main'][$mi],
        "soje" => $_POST['mater_info_soje'][$mi],
        "color" => $_POST['mater_info_color'][$mi],
        "size" => $_POST['mater_info_size'][$mi],
        "yd" => preg_replace('/,/', '' ,  $_POST['mater_info_yd'][$mi]),
        "origin_yd" => preg_replace('/,/', '' ,  $_POST['mater_info_origin_yd'][$mi]),
        "danga" => preg_replace('/,/', '', $_POST['mater_info_danga'][$mi]),
        "price" => preg_replace('/,/', '', $_POST['mater_info_price'][$mi]),
        "mater_name" => $_POST['mater_info_mater_name'][$mi],
        "etc" => preg_replace('/,/', '', $_POST['mater_info_etc'][$mi])
        
    );

    $mater_info[$mi] = $temp_item_set;
}

$meg_list = array();
foreach ($_POST["meg_item"] as $ml => $meg) {
    $meg_list[$ml] = $meg;
}


$sql_common = "ps_id = '$ps_id'
            , item_name = '{$item_name}'
            , comform_user = '{$comform_user}'
            , ps_year = '{$ps_year}'
            , ps_season = '{$ps_season}'
            , ps_job_gubun = '{$ps_job_gubun}'
            , brand = '{$brand}'
            , prod_user = '{$prod_user}'
            , balju_date = '{$balju_date}'
            , balju_limit_date = '{$balju_limit_date}'
            
            
";

$sql_common .= ",mater_info = '" . addslashes(json_encode($mater_info, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",meg_text = '" . addslashes(json_encode($meg_list, JSON_UNESCAPED_UNICODE)) . "'";




if(!empty($pno)){
    $sql_common .= ",print_date = '{$now_date}'";
    $up_baluju_sql = "update lt_prod_schedule_balju_print set  $sql_common where pno = '$pno' ";
    sql_query($up_baluju_sql);
}else{
    $sql_common .= ",reg_date = '{$now_date}'";
    $sql_common .= ",print_date = '{$now_date}'";
    $sql_balju_his = "insert lt_prod_schedule_balju_print set $sql_common";
    sql_query($sql_balju_his);
}


$result = '200';
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;

?>
// goto_url("./new_goods_db_cover_main.php?".$qstr);