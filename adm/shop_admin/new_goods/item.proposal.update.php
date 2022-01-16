<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("qstr=" , $referer);


$qstr=$cut_url[1];

$ps_id = $_POST['ps_id'];

$ip_id = $_POST['ip_id'];

if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check($auth[substr($sub_menu,0,2)], "d");
else
    auth_check($auth[substr($sub_menu,0,2)], "w");

// check_admin_token();

//$cp_subject = isset($_POST['cp_subject']) ? strip_tags($_POST['cp_subject']) : '';

$cp_subject = isset($_POST['cp_subject']) ? $_POST['cp_subject'] : '';

$image_regex = "/(\.(gif|jpe?g|png))$/i";
$chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
//$banners = array('MAIN', 'LIST', 'GNB', 'LNB', 'HISTORY');

// //동영상 이미지 파일업로드
// for ($fi = 1; $fi <= 5; $fi++) {
//     if (isset($_FILES['cp_image_' . $fi]) && is_uploaded_file($_FILES['cp_image_' . $fi]['tmp_name'])) {
//         // 기존 동영상 이미지가 있는 경우 삭제
//         // if ($_POST['org_cp_image_' . $fi])
//         //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

//         if (preg_match($image_regex, $_FILES['cp_image_' . $fi]['name'])) {

//             $design_dir = G5_DATA_PATH . '/banner/';
//             @mkdir($design_dir, G5_DIR_PERMISSION);
//             @chmod($design_dir, G5_DIR_PERMISSION);

//             shuffle($chars_array);
//             $shuffle = implode('', $chars_array);
//             $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['cp_image_' . $fi]['name']);

//             $dest_path = $design_dir . '/' . $dest_file;

//             move_uploaded_file($_FILES['cp_image_' . $fi]['tmp_name'], $dest_path);
//             chmod($dest_path, G5_FILE_PERMISSION);

//             $cp_image[$fi] = $dest_file;
//         }
//     }
// }

// foreach ($_POST["cp_image_del"] as $idx => $del) {
//     if ($del == "1") {
//         $del_path = G5_DATA_PATH . '/banner/' . $cp['cp_image_' . $idx];
//         unlink($del_path);
//         $sql_del = "UPDATE lt_job_order SET cp_image_{$idx} = NULL WHERE cp_id='{$cp_id}'";
//         sql_query($sql_del);
//     }
// }

if (isset($_FILES['ip_yd_img']) && is_uploaded_file($_FILES['ip_yd_img']['tmp_name'])) {
    // 기존 동영상 이미지가 있는 경우 삭제
    // if ($_POST['org_cp_image_' . $fi])
    //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

    if (preg_match($image_regex, $_FILES['ip_yd_img']['name'])) {

        $design_dir = G5_DATA_PATH . '/new_goods/';
        @mkdir($design_dir, G5_DIR_PERMISSION);
        @chmod($design_dir, G5_DIR_PERMISSION);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['ip_yd_img']['name']);

        $dest_path = $design_dir . '/' . $dest_file;

        move_uploaded_file($_FILES['ip_yd_img']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);

        $ip_yd_img = $dest_file;
    }
}

// //임자재매입처

// $ip_mater_purchace = array();
// foreach ($_POST["ip_mater_purchace"] as $ipmp => $purchace) {

//     if (isset($_FILES['ip_mater_purchace_img_'.$ipmp]) && is_uploaded_file($_FILES['ip_mater_purchace_img_'.$ipmp]['tmp_name'])) {
//         // 기존 동영상 이미지가 있는 경우 삭제
//         // if ($_POST['org_cp_image_' . $fi])
//         //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);


        
//         if (preg_match($image_regex, $_FILES['ip_mater_purchace_img_'.$ipmp]['name'])) {

//             $design_dir = G5_DATA_PATH . '/new_goods/';
//             @mkdir($design_dir, G5_DIR_PERMISSION);
//             @chmod($design_dir, G5_DIR_PERMISSION);

//             shuffle($chars_array);
//             $shuffle = implode('', $chars_array);
//             $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['ip_mater_purchace_img_'.$ipmp]['name']);

//             $dest_path = $design_dir . '/' . $dest_file;

//             move_uploaded_file($_FILES['ip_mater_purchace_img_'.$ipmp]['tmp_name'], $dest_path);
//             chmod($dest_path, G5_FILE_PERMISSION);

//             // $cp_image[$fi] = $dest_file;
            
//         }
//     }
//     $tmp_purchace_set = array(
//         "purchace" => $purchace,
//         "img" => $dest_file,
//         "maip" => $_POST['ip_mater_purchace_maip'][$ipmp],
//         "danga" => preg_replace('/,/', '',$_POST['ip_mater_purchace_danga'][$ipmp]),
//         "soje" => $_POST['ip_mater_purchace_soje'][$ipmp]
//     );

//     $ip_mater_purchace[$ipmp] = $tmp_purchace_set;
// }

//임가공

// $ip_processing = array();
// foreach ($_POST["ip_processing_item"] as $ipi => $processing) {
//     $tmp_processing_set = array(
//         "item" => $_POST['ip_processing_item'][$ipi],
//         "gakong" => $_POST['ip_processing_gakong'][$ipi],
//         "gakongp" => preg_replace('/,/', '',$_POST['ip_processing_gakongp'][$ipi])
//     );

//     $ip_processing[$ipi] = $tmp_processing_set;
// }

//기획의도

$ip_proposal_memo = array();
foreach ($_POST["ip_proposal_memo"] as $ipm => $memo) {
    $tmp_proposal_memo_set = array(
        "contents" => $_POST['ip_proposal_memo'][$ipm]
    );

    $ip_proposal_memo[$ipm] = $tmp_proposal_memo_set;
}

//작업지시서 예상원가 판매가 정보

$ip_job_orders = array();
foreach ($_POST["ip_job_orders"] as $ijo => $job_orders_info) {
    $tmp_job_orders_set = array(
        "jo_id" => $_POST['ip_job_orders_jo_id'][$ijo],
        "item" => $_POST['ip_job_orders_item'][$ijo],
        "price" => preg_replace('/,/', '',$_POST['ip_job_orders_proce'][$ijo]),
        "tag" => preg_replace('/,/', '',$_POST['ip_job_orders_tag'][$ijo]),
        "sale_rate" => preg_replace('/,/', '',$_POST['ip_job_orders_sale_rate'][$ijo]),
        "sale" => preg_replace('/,/', '',$_POST['ip_job_orders_sale'][$ijo]),
        "majin_rate" => preg_replace('/,/', '',$_POST['ip_job_orders_majin_rate'][$ijo]),
        "delivery" => preg_replace('/,/', '',$_POST['ip_job_orders_delivery'][$ijo]),
        "majin" => preg_replace('/,/', '',$_POST['ip_job_orders_majin'][$ijo]),
        "qty" => preg_replace('/,/', '',$_POST['ip_job_orders_prod_qty'][$ijo]),
        "total_price" => preg_replace('/,/', '',$_POST['ip_job_orders_total_price'][$ijo])
    );

    $ip_job_orders[$ijo] = $tmp_job_orders_set;
}

// //    ip_images = '{$_POST['ip_images']}',
// $ip_images = array();
// foreach ($_POST["ip_images"] as $iii => $iimg) {
//     $tmp_ip_images_set = array(
//         "img" => $_POST['ip_images'][$iii]
//     );

//     $ip_images[$ipm] = $tmp_ip_images_set;
// }


//완제품아이템

// $ip_finished = array();
// foreach ($_POST["ip_finished_item"] as $ifi => $finished) {
//     $tmp_finished_set = array(
//         "item" => $_POST['ip_finished_item'][$ifi],
//         "size" => preg_replace('/,/', '',$_POST['ip_finished_size'][$ifi]),
//         "meip" => preg_replace('/,/', '',$_POST['ip_finished_meip'][$ifi]),
//         "onega" => preg_replace('/,/', '',$_POST['ip_finished_onega'][$ifi]),
//         "comsum" => preg_replace('/,/', '',$_POST['ip_finished_comsum'][$ifi]),
//         "srate" => $_POST['ip_finished_sale_rate'][$ifi],
//         "sprice" => preg_replace('/,/', '',$_POST['ip_finished_sale_price'][$ifi]),
//         "khrate" => $_POST['ip_finished_kh_rate'][$ifi],
//         "prodqty" => preg_replace('/,/', '',$_POST['ip_finished_prod_qty'][$ifi]),
//         "totalp" => preg_replace('/,/', '',$_POST['ip_finished_total_price'][$ifi])
//     );

//     $ip_finished[$ifi] = $tmp_finished_set;
// }

//제품기획서 생성
$sql_common = "ip_temp = '{$_POST['ip_temp']}',
               ip_brand = '{$_POST['ip_brand']}',
               ip_it_name = '{$_POST['ip_it_name']}',
               ip_reg_date = '{$_POST['ip_reg_date']}',

               ip_ipgo_date = '{$_POST['ip_ipgo_date']}',
               ip_maker_etc = '{$_POST['ip_maker_etc']}',
               ip_maker_country = '{$_POST['ip_maker_country']}',

               ip_etc = '{$_POST['ip_etc']}',

               ip_prod_name = '{$_POST['ip_prod_name']}',
               ip_gubun = '{$_POST['ip_gubun']}',
               ip_year = '{$_POST['ip_year']}',
               ip_season = '{$_POST['ip_season']}',
               ip_prod_gubun = '{$_POST['ip_prod_gubun']}',
               ip_color = '{$_POST['ip_color']}',
               ip_clha_date = '{$_POST['ip_clha_date']}',
               ip_item_ipgoer = '{$_POST['ip_item_ipgoer']}',
               ip_mater = '{$_POST['ip_mater']}',
               ip_maker = '{$_POST['ip_maker']}',
               ip_importer = '{$_POST['ip_importer']}',
               ip_seller = '{$_POST['ip_seller']}',
               ip_performance = '{$_POST['ip_prod_name']}',
               ip_nabgi_m = '{$_POST['ip_nabgi_m']}',
               ip_nabgi_limit = '{$_POST['ip_nabgi_limit']}'

               
";

if(!empty($ip_yd_img)){
    // foreach ($jo_design_img as $jdi => $path) {
        $sql_common .= ",ip_yd_img = '{$ip_yd_img}'";
    // }
}


// $sql_common .= ",cp_banner = '" . implode(',', $_POST['cp_banner']) . "'";
// $sql_common .= ",cp_item_set = '" . addslashes(json_encode($cp_item_set, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",ip_proposal_memo = '" . addslashes(json_encode($ip_proposal_memo, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",ip_job_orders = '" . addslashes(json_encode($ip_job_orders, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",ip_mater_purchace = '" . addslashes(json_encode($ip_mater_purchace, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",ip_processing = '" . addslashes(json_encode($ip_processing, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",ip_finished = '" . addslashes(json_encode($ip_finished, JSON_UNESCAPED_UNICODE)) . "'";



//생산일정 초기 세팅
$sql_ps_common = "ps_brand = '{$_POST['ip_brand']}',
                  ps_it_name = '{$_POST['ip_it_name']}',
                  ps_prod_name = '{$_POST['ip_prod_name']}'
";

//삼진코드 규칙 시작

if($_POST['ip_prod_gubun']){
    switch($_POST['ip_prod_gubun']) {
        case '완사입' :            $ps_code_gubun = 'MW';            break;
        case '임가공' :            $ps_code_gubun = 'MA';            break;
    }

    $sql_ps_common .= ",ps_code_gubun = '{$ps_code_gubun}'";
}

if($_POST['ip_year']){
    $ps_code_year = substr($_POST['ip_year'] , -2);

    $sql_ps_common .= ",ps_code_year = '{$ps_code_year}'";
}


if($_POST['ip_season']){
    switch($_POST['ip_season']) {
        case 'SS' :            $ps_code_season = 'S';            break;
        case 'HS' :            $ps_code_season = 'H';            break;
        case 'FW' :            $ps_code_season = 'F';            break;
        case 'AA' :            $ps_code_season = 'A';            break;
    }
    $sql_ps_common .= ",ps_code_season = '{$ps_code_season}'";
}

//순번



//삼진코드 규칙 끝

// foreach ($cp_image as $ci => $path) {
//     $sql_common .= ",cp_image_{$ci} = '{$path}'";
//     if($ci == 1){
//         $sql_common .= ",ba_image = '{$path}'";
//     }
//     if($ci == 2){
//         $sql_common .= ",ba_image_mo = '{$path}'";
//     }
// }


if ($w == "") {
    //$sql_common .= ",ps_id = {$ps_id}";
    // $sql_common .= ",ps_ipgo_status = 'N'";
    // $sql_common .= ",ps_re_order = 'N'";

    $sql = " insert lt_item_proposal set $sql_common ";
    sql_query($sql);
    
} else if ($w == "u") {
    $sql = " update lt_item_proposal set $sql_common where ip_id = '$ip_id' ";
    sql_query($sql);
    $ps_sql = " update lt_prod_schedule set $sql_ps_common where ps_id = '$ps_id' ";
    sql_query($ps_sql);
} else if ($w == "d") {
    $sql = " delete from lt_item_proposal where ip_id = '$ip_id' ";
    sql_query($sql);
}


if ($w == "d") {
    goto_url("./new_goods_process.php?tabs=list");
} else if($cp_category == 'ETC'){
    goto_url("./new_goods_process.php?tabs=list");
} else {
    // goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
    goto_url("./new_goods_process.php?".$qstr);
}
