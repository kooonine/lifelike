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

//동영상 이미지 파일업로드
for ($fi = 1; $fi <= 5; $fi++) {
    if (isset($_FILES['cp_image_' . $fi]) && is_uploaded_file($_FILES['cp_image_' . $fi]['tmp_name'])) {
        // 기존 동영상 이미지가 있는 경우 삭제
        // if ($_POST['org_cp_image_' . $fi])
        //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

        if (preg_match($image_regex, $_FILES['cp_image_' . $fi]['name'])) {

            $design_dir = G5_DATA_PATH . '/new_goods/';
            @mkdir($design_dir, G5_DIR_PERMISSION);
            @chmod($design_dir, G5_DIR_PERMISSION);

            shuffle($chars_array);
            $shuffle = implode('', $chars_array);
            $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['cp_image_' . $fi]['name']);

            $dest_path = $design_dir . '/' . $dest_file;

            move_uploaded_file($_FILES['cp_image_' . $fi]['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            $cp_image[$fi] = $dest_file;
        }
    }
}

foreach ($_POST["cp_image_del"] as $idx => $del) {
    if ($del == "1") {
        $del_path = G5_DATA_PATH . '/banner/' . $cp['cp_image_' . $idx];
        unlink($del_path);
        $sql_del = "UPDATE lt_job_order SET cp_image_{$idx} = NULL WHERE cp_id='{$cp_id}'";
        sql_query($sql_del);
    }
}

$ps_size_set = array();
foreach ($_POST["ps_size"] as $pss => $size) {
    $tmp_size_set = array(
        "size" =>$_POST['ps_size'][$pss],
        "qty" => $_POST['ps_size_qty'][$pss]
    );

    $ps_size_set[$pss] = $tmp_size_set;
}

$ps_company_name = array();
foreach ($_POST["ps_company_name_no"] as $pcn => $info) {
    $temp_company_set = array(  
        "no" => $_POST['ps_company_name_no'][$pcn],
        "bongje" => $_POST['ps_company_name_bongje'][$pcn],
        "name" => $_POST['ps_company_name_nm'][$pcn]
    );

    $ps_company_name[$pcn] = $temp_company_set;

    $selCheck = "SELECT count(*) AS CNT FROM lt_company_manufacturing WHERE cm_bongje = '{$_POST['ps_company_name_bongje'][$pcn]}' AND cm_name = '{$_POST['ps_company_name_nm'][$pcn]}'";

    $selcount = sql_fetch($selCheck);
    $comCNT = $selcount['CNT'];
    if ($comCNT < 1) {
        $insertCm = "INSERT INTO lt_company_manufacturing (cm_bongje, cm_name) VALUES ('{$_POST['ps_company_name_bongje'][$pcn]}','{$_POST['ps_company_name_nm'][$pcn]}')";
        sql_query($insertCm);
    }
}


//생산일정 생성
$sql_common = "ps_gubun  = '{$_POST['ps_gubun']}',
                ps_limit_date  = '{$_POST['ps_limit_date']}',
                ps_os  = '{$_POST['ps_os']}',
                ps_brand  = '{$_POST['ps_brand']}',
                ps_prod_gubun  = '{$_POST['ps_prod_gubun']}',
                ps_it_name  = '{$_POST['ps_it_name']}',
                ps_prod_name  = '{$_POST['ps_prod_name']}',
                ps_code  = '{$_POST['ps_code']}',
                ps_approval_date  = '{$_POST['ps_approval_date']}',
                ps_prod_company  = '{$_POST['ps_prod_company']}',
                ps_balju  = '{$_POST['ps_balju']}',
                ps_expected_limit_date  = '{$_POST['ps_expected_limit_date']}',
                ps_gumpum  = '{$_POST['ps_gumpum']}',
                ps_prod_balju  = '{$_POST['ps_prod_balju']}',
                ps_sample_date  = '{$_POST['ps_sample_date']}',
                ps_ipgo_date  = '{$_POST['ps_ipgo_date']}',
                ps_prod_proprosal_date  = '{$_POST['ps_prod_proprosal_date']}',
                ps_real_ipgo_date  = '{$_POST['ps_real_ipgo_date']}'

";

// $sql_common .= ",cp_banner = '" . implode(',', $_POST['cp_banner']) . "'";
$sql_common .= ",ps_size = '" . addslashes(json_encode($ps_size_set, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",ps_company_name = '" . addslashes(json_encode($ps_company_name, JSON_UNESCAPED_UNICODE)) . "'";



foreach ($cp_image as $ci => $path) {
    $sql_common .= ",cp_image_{$ci} = '{$path}'";
    if($ci == 1){
        $sql_common .= ",ba_image = '{$path}'";
    }
    if($ci == 2){
        $sql_common .= ",ba_image_mo = '{$path}'";
    }
}


if ($w == "") {
   
    
    $sql_common .= ",ps_id = '{$ps_id}'";
    
    sql_query($sql);
    $cp_id = sql_insert_id();
} else if ($w == "u") {
    $sql = " update lt_prod_schedule set $sql_common where ps_id = '$ps_id' ";
    sql_query($sql);
} else if ($w == "d") {
    $sql = " delete from lt_prod_schedule where cp_id = '$cp_id' ";
    sql_query($sql);
}

if ($w == "d") {
    goto_url("./new_goods_process.php?tabs=list");
} else if($cp_category == 'ETC'){
    goto_url("./new_goods_process.php?tabs=list");
} else {
    // goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
    goto_url("./new_goods_process.php?".$qstr);
    // goto_url($referer);
}