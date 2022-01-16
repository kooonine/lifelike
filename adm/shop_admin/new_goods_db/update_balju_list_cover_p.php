<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


$referer = $_SERVER["HTTP_REFERER"];


// $pno = $_POST['pno'];
// $ps_id = $_POST['ps_id'];
// $item_name = $_POST['item_name'];
// $comform_user = $_POST['comform_user'];

// $ps_year = $_POST['ps_year'];
// $ps_season = $_POST['ps_season'];
// $ps_job_gubun = $_POST['ps_job_gubun'];
// $brand = $_POST['brand'];
// $prod_user = $_POST['prod_user'];
// $balju_date = $_POST['balju_date'];
// $balju_limit_date = $_POST['balju_limit_date'];
// $mater_name = $_POST['mater_name'];

$now_date = date('Y-m-d H:i:s');

// $mater_info = array();
// foreach ($_POST["mater_info"] as $mi => $info) {
//     $temp_item_set = array(  
//         "main" => $_POST['mater_info_main'][$mi],
//         "soje" => $_POST['mater_info_soje'][$mi],
//         "color" => $_POST['mater_info_color'][$mi],
//         "size" => $_POST['mater_info_size'][$mi],
//         "yd" => preg_replace('/,/', '' ,  $_POST['mater_info_yd'][$mi]),
//         "danga" => preg_replace('/,/', '', $_POST['mater_info_danga'][$mi]),
//         "price" => preg_replace('/,/', '', $_POST['mater_info_price'][$mi]),
//         "mater_name" => $_POST['mater_info_mater_name'][$mi],
//         "etc" => preg_replace('/,/', '', $_POST['mater_info_etc'][$mi])
        
//     );

//     $mater_info[$mi] = $temp_item_set;
// }
$pno_list = array();
foreach ($_POST["pno"] as $pn => $p_no) {
    // $pno_list[$pn] = $p_no;
    $sql_common = "";
    $mater_info_list = array();
    foreach ($_POST["mater_info_list_".$p_no.""] as $mil => $list) {
        $temp_list_set = array(  
            "hk" => $_POST['mater_info_list_'.$p_no.'_hk'][$mil],
            "hk_p" =>  preg_replace('/,/', '' ,  $_POST['mater_info_list_'.$p_no.'_hk_p'][$mil]),
            "total_p" =>  preg_replace('/,/', '' , $_POST['mater_info_list_'.$p_no.'_total_p'][$mil]),
            "yj" =>  $_POST['mater_info_list_'.$p_no.'_yj'][$mil],
            "mater_name" =>  $_POST['mater_info_list_'.$p_no.'_mater_name'][$mil],
            "mc" =>  preg_replace('/,/', '' , $_POST['mater_info_list_'.$p_no.'_mc'][$mil]),
            "pick_date" =>  $_POST['mater_info_list_'.$p_no.'_pick_date'][$mil],
            "rt" =>  $_POST['mater_info_list_'.$p_no.'_rt'][$mil]
            
        );
    
        $mater_info_list[$mil] = $temp_list_set;
    }

    $sql_common .= "mater_info_list = '" . addslashes(json_encode($mater_info_list, JSON_UNESCAPED_UNICODE)) . "'";

    if(!empty($p_no)){
    
        $up_baluju_sql = "update lt_prod_schedule_balju_print set  $sql_common where pno = '$p_no' ";
        sql_query($up_baluju_sql);
    }

    // $pno_list[$pn] = $mater_info_list;


}




// $meg_list = array();
// foreach ($_POST["meg_item"] as $ml => $meg) {
//     $meg_list[$ml] = $meg;
// }


// $sql_common = "ps_id = '$ps_id'
            
// ";


// $sql_common .= ",mater_info_list = '" . addslashes(json_encode($mater_info_list, JSON_UNESCAPED_UNICODE)) . "'";




// if(!empty($pno)){
    
//     $up_baluju_sql = "update lt_prod_schedule_balju_print set  $sql_common where pno = '$pno' ";
//     sql_query($up_baluju_sql);
// }


$result = '200';
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;

?>
// goto_url("./new_goods_db_cover_main.php?".$qstr);