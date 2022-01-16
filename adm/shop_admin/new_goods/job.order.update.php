<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("qstr=" , $referer);


$qstr=$cut_url[1];
// $qstr= "tabs=list&amp;brands=".$_GET['brands']."&amp;ipgos=".$_GET['ipgos']."&amp;shootings=".$_GET['shootings']."&amp;reorders=".$_GET['reorders']."&amp;sfl=it_name&amp;stx=".$_GET['stx']."&amp;sc_it_time=".$_GET['sc_it_time']."&amp;limit_list=".$_GET['limit_list']."&amp;page=".$_GET['page'];

$copy = $_POST['w'];

$ps_id = $_POST['ps_id'];

$jo_id = $_POST['jo_id'];
if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check($auth[substr($sub_menu,0,2)], "d");
else
    auth_check($auth[substr($sub_menu,0,2)], "w");

// check_admin_token();

//$cp_subject = isset($_POST['cp_subject']) ? strip_tags($_POST['cp_subject']) : '';

// $cp_subject = isset($_POST['cp_subject']) ? $_POST['cp_subject'] : '';

// $image_regex = "/(\.(gif|jpe?g|png))$/i";
// $chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
//$banners = array('MAIN', 'LIST', 'GNB', 'LNB', 'HISTORY');



// foreach ($_POST["cp_image_del"] as $idx => $del) {
//     if ($del == "1") {
//         $del_path = G5_DATA_PATH . '/banner/' . $cp['cp_image_' . $idx];
//         unlink($del_path);
//         $sql_del = "UPDATE lt_job_order SET cp_image_{$idx} = NULL WHERE cp_id='{$cp_id}'";
//         sql_query($sql_del);
//     }
// }
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
            case 'DG' : $color_nm = "딥그레이"; break;
            case 'CO' : $color_nm = "코랄"; break;
        }
    }else{
        $color_nm = $text;
    }
    return $color_nm;
}

function trans_preg($txt){
    $text_trans = preg_replace("/[\'\"]/", "", $txt);

    return $text_trans;
}



$image_regex = "/(\.(gif|jpe?g|png))$/i";
$chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
//$banners = array('MAIN', 'LIST', 'GNB', 'LNB', 'HISTORY');

//동영상 이미지 파일업로드
// for ($fi = 1; $fi <= 5; $fi++) {
//     if (isset($_FILES['jo_design_img']) && is_uploaded_file($_FILES['jo_design_img']['tmp_name'])) {
//         // 기존 동영상 이미지가 있는 경우 삭제
//         // if ($_POST['org_cp_image_' . $fi])
//         //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

//         if (preg_match($image_regex, $_FILES['jo_design_img']['name'])) {

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
if (isset($_FILES['jo_design_img']) && is_uploaded_file($_FILES['jo_design_img']['tmp_name'])) {
    // 기존 동영상 이미지가 있는 경우 삭제
    // if ($_POST['org_cp_image_' . $fi])
    //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

    if (preg_match($image_regex, $_FILES['jo_design_img']['name'])) {

        $design_dir = G5_DATA_PATH . '/new_goods/';
        @mkdir($design_dir, G5_DIR_PERMISSION);
        @chmod($design_dir, G5_DIR_PERMISSION);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['jo_design_img']['name']);

        $dest_path = $design_dir . '/' . $dest_file;

        move_uploaded_file($_FILES['jo_design_img']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);

        $jo_design_img = $dest_file;
    }
}
$jo_main_img_img= '';
$jo_codi_img_img= '';
$jo_sub_img_img= '';
$jo_etc_img_img= '';
$jo_memo_img= '';

//원단스와치
if (isset($_FILES['jo_main_img_img']) && is_uploaded_file($_FILES['jo_main_img_img']['tmp_name'])) {
    // 기존 동영상 이미지가 있는 경우 삭제
    // if ($_POST['org_cp_image_' . $fi])
    //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);
    
    if (preg_match($image_regex, $_FILES['jo_main_img_img']['name'])) {

        $design_dir = G5_DATA_PATH . '/new_goods/';
        @mkdir($design_dir, G5_DIR_PERMISSION);
        @chmod($design_dir, G5_DIR_PERMISSION);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['jo_main_img_img']['name']);

        $dest_path = $design_dir . '/' . $dest_file;

        move_uploaded_file($_FILES['jo_main_img_img']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);

        $jo_main_img_img = $dest_file;
    }
}

if (isset($_FILES['jo_codi_img_img']) && is_uploaded_file($_FILES['jo_codi_img_img']['tmp_name'])) {
    // 기존 동영상 이미지가 있는 경우 삭제
    // if ($_POST['org_cp_image_' . $fi])
    //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

    if (preg_match($image_regex, $_FILES['jo_codi_img_img']['name'])) {

        $design_dir = G5_DATA_PATH . '/new_goods/';
        @mkdir($design_dir, G5_DIR_PERMISSION);
        @chmod($design_dir, G5_DIR_PERMISSION);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['jo_codi_img_img']['name']);

        $dest_path = $design_dir . '/' . $dest_file;

        move_uploaded_file($_FILES['jo_codi_img_img']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);

        $jo_codi_img_img = $dest_file;
    }
}
if (isset($_FILES['jo_sub_img_img']) && is_uploaded_file($_FILES['jo_sub_img_img']['tmp_name'])) {
    // 기존 동영상 이미지가 있는 경우 삭제
    // if ($_POST['org_cp_image_' . $fi])
    //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

    if (preg_match($image_regex, $_FILES['jo_sub_img_img']['name'])) {

        $design_dir = G5_DATA_PATH . '/new_goods/';
        @mkdir($design_dir, G5_DIR_PERMISSION);
        @chmod($design_dir, G5_DIR_PERMISSION);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['jo_sub_img_img']['name']);

        $dest_path = $design_dir . '/' . $dest_file;

        move_uploaded_file($_FILES['jo_sub_img_img']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);

        $jo_sub_img_img = $dest_file;
    }
}
if (isset($_FILES['jo_etc_img_img']) && is_uploaded_file($_FILES['jo_etc_img_img']['tmp_name'])) {
    // 기존 동영상 이미지가 있는 경우 삭제
    // if ($_POST['org_cp_image_' . $fi])
    //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

    if (preg_match($image_regex, $_FILES['jo_etc_img_img']['name'])) {

        $design_dir = G5_DATA_PATH . '/new_goods/';
        @mkdir($design_dir, G5_DIR_PERMISSION);
        @chmod($design_dir, G5_DIR_PERMISSION);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['jo_etc_img_img']['name']);

        $dest_path = $design_dir . '/' . $dest_file;

        move_uploaded_file($_FILES['jo_etc_img_img']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);

        $jo_etc_img_img = $dest_file;
    }
}

//메모이미지
if (isset($_FILES['jo_memo_img']) && is_uploaded_file($_FILES['jo_memo_img']['tmp_name'])) {
    // 기존 동영상 이미지가 있는 경우 삭제
    // if ($_POST['org_cp_image_' . $fi])
    //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);

    if (preg_match($image_regex, $_FILES['jo_memo_img']['name'])) {

        $design_dir = G5_DATA_PATH . '/new_goods/';
        @mkdir($design_dir, G5_DIR_PERMISSION);
        @chmod($design_dir, G5_DIR_PERMISSION);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['jo_memo_img']['name']);

        $dest_path = $design_dir . '/' . $dest_file;

        move_uploaded_file($_FILES['jo_memo_img']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);

        $jo_memo_img = $dest_file;
    }
}


if(!empty($_POST["jo_soje_subject"])){
    $jo_soje = array();
    foreach ($_POST["jo_soje_subject"] as $idx => $subject) {
        $tmp_item_set = array(        
            "subject" => $_POST['jo_soje_subject'][$idx],
            "item" => $_POST['jo_soje_item'][$idx]
        );

        $jo_soje[$idx] =  $tmp_item_set; 
    }
}

//원자재
$jo_mater = array();
foreach ($_POST["jo_mater_info"] as $mdx => $info) {
    $temp_item_set = array(  
        "no" => $_POST['jo_mater_info_no'][$mdx],
        "title" => $_POST['jo_mater_info_title'][$mdx],
        "main" => $_POST['jo_mater_info_main'][$mdx],
        "bongje" => $_POST['jo_mater_info_bongje'][$mdx],
        "size" => preg_replace('/"/', "&quot;",$_POST['jo_mater_info_size'][$mdx]),
        "hei" =>  preg_replace('/,/', '',$_POST['jo_mater_info_hei'][$mdx]),
        "length" =>  preg_replace('/,/', '',$_POST['jo_mater_info_length'][$mdx]),
        "yd" =>  preg_replace('/,/', '',$_POST['jo_mater_info_yd'][$mdx]),
        "exchange" =>  preg_replace('/,/', '',$_POST['jo_mater_info_danga_exchange'][$mdx]),
        "danga" =>  preg_replace('/,/', '',$_POST['jo_mater_info_danga'][$mdx]),
        "price" =>  preg_replace('/,/', '',$_POST['jo_mater_info_price'][$mdx])
    );

    $jo_mater[$mdx] = $temp_item_set;
}
//부자재
$jo_sub_mater = array();
foreach ($_POST["jo_sub_mater_info"] as $smdx => $sub) {
    $stmp_item_set = array(        
        "no" => $_POST['jo_sub_mater_info_no'][$smdx],
        "title" => $_POST['jo_sub_mater_info_title'][$smdx],
        "size" => preg_replace('/"/', "&quot;",$_POST['jo_sub_mater_info_size'][$smdx]),
        "hei" =>  preg_replace('/,/', '',$_POST['jo_sub_mater_info_hei'][$smdx]),
        "length" =>  preg_replace('/,/', '',$_POST['jo_sub_mater_info_length'][$smdx]),
        "yd" =>  preg_replace('/,/', '',$_POST['jo_sub_mater_info_yd'][$smdx]),
        "danga_per" =>  preg_replace('/,/', '',$_POST['jo_sub_mater_info_danga_per'][$smdx]),
        "danga" =>  preg_replace('/,/', '',$_POST['jo_sub_mater_info_danga'][$smdx]),
        "price" =>  preg_replace('/,/', '',$_POST['jo_sub_mater_info_price'][$smdx])
    );

    $jo_sub_mater[$smdx] = $stmp_item_set;
}
//가공임
if(!empty($_POST["jo_gakong_item"])){

    $jo_gakong_item = array();
    foreach ($_POST["jo_gakong_item"] as $gdx => $info) {
        $gtemp_item_set = array(  
            "no" => $_POST['jo_gakong_item_no'][$gdx], 
            "bongje" => $_POST['jo_gakong_item_bongje'][$gdx], 
            "title" => $_POST['jo_gakong_item_title'][$gdx], 
            "size" => preg_replace('/"/', "&quot;",$_POST['jo_gakong_item_size'][$gdx]),
            "hei" =>  preg_replace('/,/', '',$_POST['jo_gakong_item_hei'][$gdx]),
            "length" =>  preg_replace('/,/', '',$_POST['jo_gakong_item_length'][$gdx]),
            "yd" =>  preg_replace('/,/', '',$_POST['jo_gakong_item_yd'][$gdx]),
            "exchange" =>  preg_replace('/,/', '',$_POST['jo_gakong_item_danga_exchange'][$gdx]),
            "danga" => preg_replace('/,/', '',$_POST['jo_gakong_item_danga'][$gdx]),
            "price" => preg_replace('/,/', '',$_POST['jo_gakong_item_price'][$gdx])
        );
    
        $jo_gakong_item[$gdx] = $gtemp_item_set;
    }
}
//제품매입단가(반제피 단가)
$jo_maip_price = array();
foreach ($_POST["jo_maip_price"] as $idx => $info) {
    $itemp_item_set = array(  
        "no" => $_POST['jo_maip_price_no'][$idx], 
        "title" => $_POST['jo_maip_price_title'][$idx], 
        "size" => preg_replace('/"/', "&quot;", $_POST['jo_maip_price_size'][$idx]),
        "hei" =>  preg_replace('/,/', '',$_POST['jo_maip_price_hei'][$idx]),
        "length" =>  preg_replace('/,/', '',$_POST['jo_maip_price_length'][$idx]),
        "yd" => preg_replace('/,/', '',$_POST['jo_maip_price_yd'][$idx]),
        "exchange" =>  preg_replace('/,/', '',$_POST['jo_maip_price_danga_exchange'][$idx]),
        "danga" => preg_replace('/,/', '',$_POST['jo_maip_price_danga'][$idx]),
        "price" => preg_replace('/,/', '',$_POST['jo_maip_price_price'][$idx])
    );
    $jo_maip_price[$idx] = $itemp_item_set;
}
//원자재명

$jo_mater_name = array();
foreach ($_POST["jo_mater_name"] as $ndx => $info) {
    $ntemp_item_set = array(  
        "no" => $_POST['jo_mater_name_no'][$ndx], 
        "title" => $_POST['jo_mater_name_title'][$ndx], 
        "mater" => $_POST['jo_mater_name_mater'][$ndx],
        "danga" => preg_replace('/,/', '',$_POST['jo_mater_name_danga'][$ndx]),
        "tel" => $_POST['jo_mater_name_tel'][$ndx]
    );
    $jo_mater_name[$ndx] = $ntemp_item_set;
}
//품질표시
$jo_pumjil = array();
foreach ($_POST["jo_pumjil"] as $pdx => $info) {
    $ptemp_item_set = array(  
        "contents" => $_POST['jo_pumjil'][$pdx]
    );
    $jo_pumjil[$pdx] = $ptemp_item_set;
}
//메인솔리드
if(!empty($jo_main_img_img)){
    $jo_main_img_img = $jo_main_img_img;
}else{
    $jo_main_img_img =  $_POST['jo_main_img_img'][1];
}
$jo_main_img = array();
foreach ($_POST["jo_main_img"] as $jmi => $info) {
    $mitemp_item_set = array(  
        "title" =>  preg_replace('/"/', "&quot;",$_POST['jo_main_img_title'][$jmi]),
        "img" => $jo_main_img_img,
        "text" => $_POST['jo_main_img_text'][$jmi]
    );
    $jo_main_img[$jmi] = $mitemp_item_set;
}
//코드프린트
if(!empty($jo_codi_img_img)){
    $jo_codi_img_img = $jo_codi_img_img;
}else{
    $jo_codi_img_img =  $_POST['jo_codi_img_img'][1];
}

$jo_codi_img = array();
foreach ($_POST["jo_codi_img"] as $jci => $info) {
    $citemp_item_set = array(  
        "title" => preg_replace('/"/', "&quot;",$_POST['jo_codi_img_title'][$jci]),
        "img" => $jo_codi_img_img,
        "text" => $_POST['jo_codi_img_text'][$jci]
    );
    $jo_codi_img[$jci] = $citemp_item_set;
}
//코드1프린트
if(!empty($jo_sub_img_img)){
    $jo_sub_img_img = $jo_sub_img_img;
}else{
    $jo_sub_img_img =  $_POST['jo_sub_img_img'][1];
}
$jo_sub_img = array();
foreach ($_POST["jo_sub_img"] as $jsi => $info) {
    $sitemp_item_set = array(  
        "title" => preg_replace('/"/', "&quot;",$_POST['jo_sub_img_title'][$jsi]),
        "img" => $jo_sub_img_img,
        "text" => $_POST['jo_sub_img_text'][$jsi]
    );
    $jo_sub_img[$jsi] = $sitemp_item_set;
}

//안감프린트
if(!empty($jo_etc_img_img)){
    $jo_etc_img_img = $jo_etc_img_img;
}else{
    $jo_etc_img_img =  $_POST['jo_etc_img_img'][1];
}
$jo_etc_img = array();
foreach ($_POST["jo_etc_img"] as $jsi => $info) {
    $sitemp_item_set = array(  
        "title" => preg_replace('/"/', "&quot;",$_POST['jo_etc_img_title'][$jsi]),
        "img" => $jo_etc_img_img,
        "text" => $_POST['jo_etc_img_text'][$jsi]
    );
    $jo_etc_img[$jsi] = $sitemp_item_set;
}


//메모이미지
if(!empty($jo_memo_img)){
    $jo_memo_img = $jo_memo_img;
}else{
    $jo_memo_img =  $_POST['jo_memo_img_img'];
}

$jo_bongje = preg_replace('/,/', '',$_POST['jo_bongje']);
$jo_juip_price = preg_replace('/,/', '',$_POST['jo_juip_price']);
$jo_pack_price = preg_replace('/,/', '',$_POST['jo_pack_price']);
$jo_prod_origin_price = preg_replace('/,/', '',$_POST['jo_prod_origin_price']);

$jo_total_origin_price = preg_replace('/,/', '',$_POST['jo_total_origin_price']);

$jo_it_name_temp = preg_replace('/\r\n/', ' ',$_POST['jo_it_name']);

$jo_it_name = rtrim($jo_it_name_temp);


// dd($jo_soje);
//작업지시서 생성
$sql_common = "jo_temp = '{$_POST['jo_temp']}',
jo_gubun = '{$_POST['jo_gubun']}',
jo_prod_gubun = '{$_POST['jo_prod_gubun']}',
jo_brand = '{$_POST['jo_brand']}',
jo_it_name = '{$jo_it_name}',
jo_reg_date = '{$_POST['jo_reg_date']}',
jo_prod_type = '{$_POST['jo_prod_type']}',
jo_prod_name = '{$_POST['jo_prod_name']}',
jo_prod_year = '{$_POST['jo_prod_year']}',
jo_season = '{$_POST['jo_season']}',
jo_size_code = '{$_POST['jo_size_code']}',
jo_size = '{$_POST['jo_size']}',
jo_size_wid = '{$_POST['jo_size_wid']}',
jo_size_verti = '{$_POST['jo_size_verti']}',
jo_size_hei = '{$_POST['jo_size_hei']}',
jo_color = '{$_POST['jo_color']}',
jo_user = '{$_POST['jo_user']}',

jo_memo = '{$_POST['jo_memo']}',
jo_memo_img = '{$jo_memo_img}',
jo_un_im = '{$_POST['jo_un_im']}',
jo_customs = '{$_POST['jo_customs']}',
jo_prod_origin_price = '{$jo_prod_origin_price}',
jo_prod_control_price = '{$_POST['jo_prod_control_price']}',
jo_total_origin_price = '{$jo_total_origin_price}',

jo_etc_company = '{$_POST['jo_etc_company']}',
jo_etc_company_tel = '{$_POST['jo_etc_company_tel']}', 
jo_function_yn = '{$_POST['jo_function_yn']}'

";

// $sql_common .= ",cp_banner = '" . implode(',', $_POST['cp_banner']) . "'";
// $sql_common .= ",jo_soje = '" . addslashes(json_encode($jo_soje, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_main_img = '" . addslashes(json_encode($jo_main_img, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_codi_img = '" . addslashes(json_encode($jo_codi_img, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_sub_img = '" . addslashes(json_encode($jo_sub_img, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_etc_img = '" . addslashes(json_encode($jo_etc_img, JSON_UNESCAPED_UNICODE)) . "'";

$sql_common .= ",jo_mater_info = '" . addslashes(json_encode($jo_mater, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_sub_mater = '" . addslashes(json_encode($jo_sub_mater, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_maip_price = '" . addslashes(json_encode($jo_maip_price, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_gakong_item = '" . addslashes(json_encode($jo_gakong_item, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_mater_name = '" . addslashes(json_encode($jo_mater_name, JSON_UNESCAPED_UNICODE)) . "'";
$sql_common .= ",jo_pumjil = '" . addslashes(json_encode($jo_pumjil, JSON_UNESCAPED_UNICODE)) . "'";


if(!empty($jo_design_img)){
    // foreach ($jo_design_img as $jdi => $path) {
        $sql_common .= ",jo_design_img = '{$jo_design_img}'";
    // }
}

//생산일정 초기 세팅
$name_array = explode('(', $jo_it_name);
$ps_item_nm = $name_array[0];

$sql_ps_common = "ps_brand = '{$_POST['jo_brand']}',
                  ps_it_name = '{$jo_it_name}',
                  ps_prod_name = '{$_POST['jo_prod_name']}',
                  ps_user = '{$_POST['jo_user']}',
                  ps_item_nm = '{$ps_item_nm}',
                  ps_job_gubun = '{$_POST['jo_gubun']}'
";



if(($member['mb_id'] != 'ryun1002') && ($member['mb_id'] != 'sbs608')){
    //삼진코드 규칙 시작
    if($_POST['jo_prod_gubun']){
        switch($_POST['jo_prod_gubun']) {
            case 'MA' :            $ps_code_gubun = 'MA';            break;
            case 'MW' :            $ps_code_gubun = 'MW';            break;
            case 'MD' :            $ps_code_gubun = 'MD';            break;
            case 'MS' :            $ps_code_gubun = 'MS';            break;
            case 'MX' :            $ps_code_gubun = 'MX';            break;
            
        }

        $sql_ps_common .= ",ps_code_gubun = '{$ps_code_gubun}'";
    }


    if($_POST['jo_brand']){
        switch($_POST['jo_brand']) {
            case '소프라움' :            $ps_code_brand = 'S';            break;
            case '베온트레' :            $ps_code_brand = 'B';            break;
            case '쉐르단' :            $ps_code_brand = 'D';            break;
            case '로자리아' :            $ps_code_brand = 'R';            break;
            case '링스티드던' :            $ps_code_brand = 'G';            break;
            case '그라치아노' :            $ps_code_brand = 'I';            break;
            case '시뇨리아' :            $ps_code_brand = 'F';            break;
            case '랄프로렌홈' :            $ps_code_brand = 'L';            break;
            case '플랫폼일반' :            $ps_code_brand = 'P';            break;
            case '플랫폼렌탈' :            $ps_code_brand = 'T';            break;
            case '온라인' :            $ps_code_brand = 'O';            break;
            case '템퍼' :            $ps_code_brand = 'H';            break;
        }

        $sql_ps_common .= ",ps_code_brand = '{$ps_code_brand}'";
    }
    if($_POST['jo_prod_year']){
        $ps_code_year = substr($_POST['jo_prod_year'],-2);
        
        $sql_ps_common .= ",ps_code_year = '{$ps_code_year}'";
    }

    if($_POST['jo_season']){
        switch($_POST['jo_season']) {
            case 'SS' :            $ps_code_season = 'S';            break;
            case 'HS' :            $ps_code_season = 'H';            break;
            case 'FW' :            $ps_code_season = 'F';            break;
            case 'AA' :            $ps_code_season = 'A';            break;
        }
        
        $sql_ps_common .= ",ps_code_season = '{$ps_code_season}'";
    }

    if($_POST['jo_prod_type']){
        switch($_POST['jo_prod_type']) {
            case '커버' :            $ps_code_item_type = 'C';            break;
            case '속통' :            $ps_code_item_type = 'S';            break;
            case '기타' :            $ps_code_item_type = 'A';            break;
        }

        $sql_ps_common .= ",ps_code_item_type = '{$ps_code_item_type}'";
    }

    // if($ps_id){
    //     $code_index = str_pad($ps_id, 2, "0", STR_PAD_LEFT);

    //     $sql_ps_common .= ",ps_code_index = '{$code_index}'";
    // }

    //var 1.0
    // if($jo_it_name){
    //     $code_index = 0;

    //     $ps_code_index = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_it_name = '{$jo_it_name}' AND ps_code_index IS NOT NULL LIMIT 1";
    //     $ps_code_index_result = sql_fetch($ps_code_index);
    //     if($ps_code_index_result['ps_code_index']){
    //         $code_index = $ps_code_index_result['ps_code_index'];
    //     }else{
    //         $ps_code_index2 = "SELECT COUNT(aa.code_index) AS CNT FROM (SELECT ps_it_name AS code_index FROM lt_prod_schedule  GROUP BY ps_it_name) AS aa ";
    //         $ps_code_index_result2 = sql_fetch($ps_code_index2);
    //         $code_index = str_pad($ps_code_index_result2['CNT'], 2, "0", STR_PAD_LEFT);
    //     }
    //     $sql_ps_common .= ",ps_code_index = '{$code_index}'";
    // }

    // var 1.1
    if($jo_it_name){
        $code_index = "";
        
        $ps_code_index = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_it_name = '{$jo_it_name}' AND ps_code_gubun= '{$ps_code_gubun}' 
        AND ps_code_brand='{$ps_code_brand}' AND ps_code_year ='{$ps_code_year}' 
        AND ps_code_season='{$ps_code_season}' AND ps_code_item_type='{$ps_code_item_type}' AND ps_code_index IS NOT NULL LIMIT 1";
        $ps_code_index_result = sql_fetch($ps_code_index);
        if($ps_code_index_result['ps_code_index']){
            $code_index = $ps_code_index_result['ps_code_index'];
        }else{
            $ps_code_index2 = "SELECT ps_code_index FROM lt_prod_schedule WHERE ps_code_gubun= '{$ps_code_gubun}' 
            AND ps_code_brand='{$ps_code_brand}' AND ps_code_year ='{$ps_code_year}' 
            AND ps_code_season='{$ps_code_season}' AND ps_code_item_type='{$ps_code_item_type}' AND ps_code_index IS NOT NULL LIMIT 1";
            $ps_code_index_result2 = sql_fetch($ps_code_index2);
            if(empty($ps_code_index_result2['ps_code_index'])){
                $code_index = '01';
            }else{
                $ps_code_index3 = "SELECT MAX(temp.max_i) AS MAXM , COUNT(temp.cnt_i) AS CNT FROM (SELECT MAX(ps_code_index) AS max_i , COUNT(ps_code_index) AS cnt_i  , ps_it_name FROM lt_prod_schedule 
                WHERE ps_code_gubun= '{$ps_code_gubun}' 
                AND ps_code_brand='{$ps_code_brand}' AND ps_code_year ='{$ps_code_year}' 
                AND ps_code_season='{$ps_code_season}' AND ps_code_item_type='{$ps_code_item_type}'
                GROUP BY ps_it_name) AS temp";
                $ps_code_index_result3 = sql_fetch($ps_code_index3);

                $code_index = str_pad(($ps_code_index_result3['MAXM']+1), 2, "0", STR_PAD_LEFT);
                
                if($code_index > 99){
                    $code_index  = str_pad(($ps_code_index_result3['CNT']+1), 2, "0", STR_PAD_LEFT);
                }
            }
        }
        $sql_ps_common .= ",ps_code_index = '{$code_index}'";
    }




    if($_POST['jo_prod_name']){
        $prod_type_sql = "select * from lt_job_order_code where prod_type ='{$_POST['jo_prod_type']}' and prod_name = '{$_POST['jo_prod_name']}' limit 1" ;
        $prod_type_result = sql_fetch($prod_type_sql);
        $ps_code_item_name = $prod_type_result['prod_gb_code'];


        $sql_ps_common .= ",ps_code_item_name = '{$ps_code_item_name}'";
    }

    if($ps_code_gubun && $ps_code_brand && $ps_code_year && $ps_code_season && $ps_code_item_type && $code_index && $ps_code_item_name){
        $jo_id_code = $ps_code_gubun."".$ps_code_brand."".$ps_code_year."".$ps_code_season."".$ps_code_item_type."".$code_index."".$ps_code_item_name;
        
        //중복체크
        $chk_sql = "SELECT COUNT(*) AS CNT FROM lt_job_order WHERE jo_id_code = '{$jo_id_code}' AND jo_it_name <> '{$jo_it_name}'";
        $chk_sql_result = sql_fetch($chk_sql);
        if($chk_sql_result['CNT'] == 0 ) {
            $sql_common .= ",jo_id_code = '{$jo_id_code}'";
        }else{
            $sql_common .= ",jo_id_code = '삼진코드중복'";
        }
        
    }
}

if($member['mb_id'] == 'ryun1002' || $member['mb_id'] == 'sbs608'){
    if($_POST['jo_id_code']){
        $jo_id_code = $_POST['jo_id_code'];
        $ps_code_gubun = substr($jo_id_code,0,2);
        $ps_code_brand= substr($jo_id_code,2,1);
        $ps_code_year= substr($jo_id_code,3,2);
        $ps_code_season= substr($jo_id_code,5,1);
        $ps_code_item_type= substr($jo_id_code,6,1);
        $code_index= substr($jo_id_code,7,2);
        $ps_code_item_name= substr($jo_id_code,9,3);
        //중복체크
        $chk_sql = "SELECT COUNT(*) AS CNT FROM lt_job_order WHERE jo_id_code = '{$jo_id_code}' AND jo_it_name <> '{$jo_it_name}'";
        $chk_sql_result = sql_fetch($chk_sql);
        if($chk_sql_result['CNT'] == 0 ) {
            $sql_common .= ",jo_id_code = '{$jo_id_code}'";

            $sql_ps_common .= ",ps_code_gubun = '{$ps_code_gubun}'";
            $sql_ps_common .= ",ps_code_brand = '{$ps_code_brand}'";
            $sql_ps_common .= ",ps_code_year = '{$ps_code_year}'";
            $sql_ps_common .= ",ps_code_season = '{$ps_code_season}'";
            $sql_ps_common .= ",ps_code_item_type = '{$ps_code_item_type}'";
            $sql_ps_common .= ",ps_code_index = '{$code_index}'";
            $sql_ps_common .= ",ps_code_item_name = '{$ps_code_item_name}'";
        }else{
            $sql_common .= ",jo_id_code = '삼진코드중복'";
        }
    }
}


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
//상품정보집
$sql_pi_common = "";


if(empty($ps_id)){
    if ($w == "") {
        $sql_ps_common .= ",ps_ipgo_status = 'N'";
        $sql_ps_common .= ",ps_re_order = 'N'";
        $sql_ps_common .= ",ps_reg_date = '{$_POST['jo_reg_date']}'";
        $sql_ps = "insert lt_prod_schedule set $sql_ps_common";
        sql_query($sql_ps);
        $ps_id = sql_insert_id();
        // $code_index = str_pad($ps_id, 2, "0", STR_PAD_LEFT);
        $ps_sql = " update lt_prod_schedule set ps_origin_ps_id = '$ps_id' , ps_code_index = '$code_index'  where ps_id = '$ps_id' ";
        sql_query($ps_sql);


        $sql_common .= ",ps_id = '{$ps_id}'";
        // if($ps_code_gubun && $ps_code_brand && $ps_code_year && $ps_code_season && $ps_code_item_type && $code_index && $ps_code_item_name){
        //     $jo_id_code = $ps_code_gubun."".$ps_code_brand."".$ps_code_year."".$ps_code_season."".$ps_code_item_type."".$code_index."".$ps_code_item_name;
        //     $sql_common .= ",jo_id_code = '{$jo_id_code}'";
        // }
        $sql = " insert lt_job_order set $sql_common ";
        sql_query($sql);
        $jo_id = sql_insert_id();

        $sql_pi_common .= "ps_id = '{$ps_id}'";
        $sql_pi_common .= ",pi_size_name = '{$_POST['jo_size_code']}'";
        $sql_pi_common .= ",tem_save = 'N'";
        $sql_pi_common .= ",jo_id = '{$jo_id}'";
        $sql_pi_common .= ",pi_model_name = '{$jo_id_code}'";
        $sql_pi_common .= ",pi_model_no = '{$jo_id_code}'";

        $pi_it_name = "[".$_POST['jo_brand']."]";
        $name_array = explode('(', $_POST['jo_it_name']);
        $pi_it_name .= " ".$name_array[0];
        $pi_it_name .= " ".$_POST['jo_prod_name'];
        if(strpos($_POST['jo_prod_name'] , "베개커버") === false ){
            switch($_POST['jo_size_code']){
                case 'S':
                    $size_cisu = "싱글사이즈";
                    break;
                case 'Q':
                    $size_cisu = "퀸사이즈";
                    break;
                case 'K':
                    $size_cisu = "킹사이즈";
                    break;
                case 'SS':
                    $size_cisu = "슈퍼싱글사이즈";
                    break;
                default : 
                    $size_cisu = "";
                    break;
            }
            $pi_company_it_id = $jo_id_code.$_POST['jo_color'].$_POST['jo_size_code'];
        }else{
            $size_cisu = $_POST['jo_size_verti'].'X'.$_POST['jo_size_wid'];
            $pi_company_it_id = $jo_id_code.$_POST['jo_color'].$size_cisu;
        }
        $pi_it_name .= " ".$size_cisu;
        $pi_it_name .= "(".color_table($_POST['jo_color']).")";
    
        $pi_it_sub_name = $_POST['jo_it_name'].$_POST['jo_prod_name'];

        $sql_pi_common .= ",pi_it_name = '{$pi_it_name}'";
        $sql_pi_common .= ",pi_it_sub_name = '{$pi_it_sub_name}'";
        $sql_pi_common .= ",pi_company_it_id = '{$pi_company_it_id}'";


        $sql_pi_row_copy = "SELECT * FROM lt_prod_info AS lpi LEFT JOIN lt_job_order AS jo ON lpi.jo_id = jo.jo_id WHERE jo.jo_it_name = '{$jo_it_name}' ORDER BY jo.jo_id ASC LIMIT 1";
        
        $pi_row_copy_item = sql_fetch($sql_pi_row_copy);

        if(!empty($pi_row_copy_item)){
            $t_pi_detail_info = trans_preg($pi_row_copy_item['pi_detail_info']);
            $t_pi_selling1 = trans_preg($pi_row_copy_item['pi_selling1']);
            $t_pi_selling2 = trans_preg($pi_row_copy_item['pi_selling2']);
            $t_pi_selling3 = trans_preg($pi_row_copy_item['pi_selling3']);
            $t_pi_prod_info1 = trans_preg($pi_row_copy_item['pi_prod_info1']);
            $t_pi_prod_info2 = trans_preg($pi_row_copy_item['pi_prod_info2']);
            $t_pi_prod_info3 = trans_preg($pi_row_copy_item['pi_prod_info3']);
            $t_pi_prod_info4 = trans_preg($pi_row_copy_item['pi_prod_info4']);
            $t_pi_prod_info5 = trans_preg($pi_row_copy_item['pi_prod_info5']);
            $t_pi_prod_info6 = trans_preg($pi_row_copy_item['pi_prod_info6']);
            $t_pi_prod_info7 = trans_preg($pi_row_copy_item['pi_prod_info7']);
            $t_pi_prod_info8 = trans_preg($pi_row_copy_item['pi_prod_info8']);
            $t_pi_prod_info9 = trans_preg($pi_row_copy_item['pi_prod_info9']);
            $t_pi_prod_info10 = trans_preg($pi_row_copy_item['pi_prod_info10']);

            $sql_pi_common .= " ,pi_design_style= '{$pi_row_copy_item['pi_design_style']}',
                        pi_design_style_sub= '{$pi_row_copy_item['pi_design_style_sub']}',
                        pi_season= '{$pi_row_copy_item['pi_season']}',
                        
                        pi_brand= '{$pi_row_copy_item['pi_brand']}',
                        pi_category= '{$pi_row_copy_item['pi_category']}',
                        pi_mater= '{$pi_row_copy_item['pi_mater']}',
                        pi_prod_date= '{$pi_row_copy_item['pi_prod_date']}',
                        pi_age_gubun= '{$pi_row_copy_item['pi_age_gubun']}',
                        pi_delivery_price= '{$pi_row_copy_item['pi_delivery_price']}',
                        
                        
                        
                        pi_item_soje= '{$pi_row_copy_item['pi_item_soje']}',
                        pi_item_soje_detail= '{$pi_row_copy_item['pi_item_soje_detail']}',
                        pi_color= '{$pi_row_copy_item['pi_color']}',
                        
                        
                        pi_maker= '{$pi_row_copy_item['pi_maker']}',
                        pi_laundry= '{$pi_row_copy_item['pi_laundry']}',
                        pi_kc_safe_yn= '{$pi_row_copy_item['pi_kc_safe_yn']}',
                        pi_soip_yn= '{$pi_row_copy_item['pi_soip_yn']}',
                        
                        
                        pi_charge= '{$pi_row_copy_item['pi_charge']}',
                        
                        pi_charge_mater= '{$pi_row_copy_item['pi_charge_mater']}',
                        pi_charge_mater_etc= '{$pi_row_copy_item['pi_charge_mater_etc']}',
                        pi_charge_brand= '{$pi_row_copy_item['pi_charge_brand']}',
                        pi_charge_brand_etc = '{$pi_row_copy_item['pi_charge_brand_etc']}',
                        pi_charge_weight= '{$pi_row_copy_item['pi_charge_weight']}',
                        pi_charge_weight_etc= '{$pi_row_copy_item['pi_charge_weight_etc']}',

                        pi_ll_style= '{$pi_row_copy_item['pi_ll_style']}',
                        pi_prauden_umu_yn= '{$pi_row_copy_item['pi_prauden_umu_yn']}',
                        pi_hangkun_info= '{$pi_row_copy_item['pi_hangkun_info']}',
                        pi_hangkun_info_txt= '{$pi_row_copy_item['pi_hangkun_info_txt']}',
                        pi_pilpower= '{$pi_row_copy_item['pi_pilpower']}',
                        pi_pilpower_safe_yn= '{$pi_row_copy_item['pi_pilpower_safe_yn']}',
                        pi_info1= '{$pi_row_copy_item['pi_info1']}',
                        pi_info2= '{$pi_row_copy_item['pi_info2']}',
                        pi_info2_1= '{$pi_row_copy_item['pi_info2_1']}',
                        pi_info3= '{$pi_row_copy_item['pi_info3']}',
                        pi_manager= '{$pi_row_copy_item['pi_manager']}',
                        pi_img = '',
                        pi_img_total = '{$pi_row_copy_item['pi_img_total']}',
                        pi_video1= '{$pi_row_copy_item['pi_video1']}',
                        pi_video2= '{$pi_row_copy_item['pi_video2']}',
                        pi_video3= '{$pi_row_copy_item['pi_video3']}',
                        pi_video4= '{$pi_row_copy_item['pi_video4']}',
                        pi_origin_image= '{$pi_row_copy_item['pi_origin_image']}',
                        pi_detail_info= '{$t_pi_detail_info}',
                        pi_selling1= '{$t_pi_selling1}',
                        pi_selling2= '{$t_pi_selling2}',
                        pi_selling3= '{$t_pi_selling3}',
                        pi_prod_info1= '{$t_pi_prod_info1}',
                        pi_prod_info2= '{$t_pi_prod_info2}',
                        pi_prod_info3= '{$t_pi_prod_info3}',
                        pi_prod_info4= '{$t_pi_prod_info4}',
                        pi_prod_info5= '{$t_pi_prod_info5}',
                        pi_prod_info6= '{$t_pi_prod_info6}',
                        pi_prod_info7= '{$t_pi_prod_info7}',
                        pi_prod_info8= '{$t_pi_prod_info8}',
                        pi_prod_info9= '{$t_pi_prod_info9}',
                        pi_prod_info10= '{$t_pi_prod_info10}',
                        etc= '{$pi_row_copy_item['etc']}'
                        
            ";

        }

        $sql_pi = "insert lt_prod_info set $sql_pi_common";
        sql_query($sql_pi);


    }else if ($w == "u") {
        $sql = " update lt_job_order set $sql_common where jo_id = '$jo_id' ";
        sql_query($sql);
        $ps_sql = " update lt_prod_schedule set $sql_ps_common where ps_id = '$ps_id' ";
        sql_query($ps_sql);

        $sql_pi_common .= ",pi_size_name = '{$_POST['jo_size_code']}'";
        $sql_pi = "update lt_prod_info set $sql_pi_common where jo_id = '$jo_id'";
        sql_query($sql_pi);
        
    } else if ($w == "d") {
        $sql = " delete from lt_job_order where jo_id = '$jo_id' ";
        sql_query($sql);
    }
}else{
    if ($w == "" || $copy == "copy") {
        $sql_common .= ",ps_id = '{$ps_id}'";
        // $sql_common .= ",ps_ipgo_status = 'N'";
        // $sql_common .= ",ps_re_order = 'N'";
        // $code_index = str_pad($ps_id, 2, "0", STR_PAD_LEFT);
        // if($ps_code_gubun && $ps_code_brand && $ps_code_year && $ps_code_season && $ps_code_item_type && $code_index && $ps_code_item_name){
        //     $jo_id_code = $ps_code_gubun."".$ps_code_brand."".$ps_code_year."".$ps_code_season."".$ps_code_item_type."".$code_index."".$ps_code_item_name;
        //     $sql_common .= ",jo_id_code = '{$jo_id_code}'";
        // }

        $sql = " insert lt_job_order set $sql_common ";
        sql_query($sql);
        $jo_id = sql_insert_id();
        
        $sql_pi_first = "SELECT * FROM lt_prod_info WHERE ps_id ='{$ps_id}' ORDER BY pi_id ASC LIMIT 1";
        $pi_first_copy_item = sql_fetch($sql_pi_first);

        $sql_pi_common .= "ps_id = '{$ps_id}'";
        $sql_pi_common .= ",pi_size_name = '{$_POST['jo_size_code']}'";
        $sql_pi_common .= ",tem_save = 'N'";
        $sql_pi_common .= ",jo_id = '{$jo_id}'";
        $sql_pi_common .= ",pi_model_name = '{$jo_id_code}'";
        $sql_pi_common .= ",pi_model_no = '{$jo_id_code}'";

        $t_pi_detail_info= trans_preg($pi_first_copy_item['pi_detail_info']);
        $t_pi_selling1= trans_preg($pi_first_copy_item['pi_selling1']);
        $t_pi_selling2= trans_preg($pi_first_copy_item['pi_selling2']);
        $t_pi_selling3= trans_preg($pi_first_copy_item['pi_selling3']);
        $t_pi_prod_info1= trans_preg($pi_first_copy_item['pi_prod_info1']);
        $t_pi_prod_info2= trans_preg($pi_first_copy_item['pi_prod_info2']);
        $t_pi_prod_info3= trans_preg($pi_first_copy_item['pi_prod_info3']);
        $t_pi_prod_info4= trans_preg($pi_first_copy_item['pi_prod_info4']);
        $t_pi_prod_info5= trans_preg($pi_first_copy_item['pi_prod_info5']);
        $t_pi_prod_info6= trans_preg($pi_first_copy_item['pi_prod_info6']);
        $t_pi_prod_info7= trans_preg($pi_first_copy_item['pi_prod_info7']);
        $t_pi_prod_info8= trans_preg($pi_first_copy_item['pi_prod_info8']);
        $t_pi_prod_info9= trans_preg($pi_first_copy_item['pi_prod_info9']);
        $t_pi_prod_info10= trans_preg($pi_first_copy_item['pi_prod_info10']);

        //상품정보집 copy 추가
        $sql_pi_common .= " ,pi_sub_category = '{$pi_first_copy_item['pi_sub_category']}',
                        pi_design_style= '{$pi_first_copy_item['pi_design_style']}',
                        pi_design_style_sub= '{$pi_first_copy_item['pi_design_style_sub']}',
                        pi_season= '{$pi_first_copy_item['pi_season']}',
                        pi_it_name= '{$pi_first_copy_item['pi_it_name']}',
                        pi_it_sub_name= '{$pi_first_copy_item['pi_it_sub_name']}',
                        
                        pi_company_it_id= '{$pi_first_copy_item['pi_company_it_id']}',
                        pi_brand= '{$pi_first_copy_item['pi_brand']}',
                        pi_category= '{$pi_first_copy_item['pi_category']}',
                        pi_mater= '{$pi_first_copy_item['pi_mater']}',
                        pi_prod_date= '{$pi_first_copy_item['pi_prod_date']}',
                        pi_age_gubun= '{$pi_first_copy_item['pi_age_gubun']}',
                        pi_delivery_price= '{$pi_first_copy_item['pi_delivery_price']}',
                        
                        
                        
                        pi_item_soje= '{$pi_first_copy_item['pi_item_soje']}',
                        pi_item_soje_detail= '{$pi_first_copy_item['pi_item_soje_detail']}',
                        pi_color= '{$pi_first_copy_item['pi_color']}',
                        
                        
                        pi_maker= '{$pi_first_copy_item['pi_maker']}',
                        pi_laundry= '{$pi_first_copy_item['pi_laundry']}',
                        pi_kc_safe_yn= '{$pi_first_copy_item['pi_kc_safe_yn']}',
                        pi_soip_yn= '{$pi_first_copy_item['pi_soip_yn']}',
                        
                        
                        pi_charge= '{$pi_first_copy_item['pi_charge']}',
                        
                        pi_charge_mater= '{$pi_first_copy_item['pi_charge_mater']}',
                        pi_charge_mater_etc= '{$pi_first_copy_item['pi_charge_mater_etc']}',
                        pi_charge_brand= '{$pi_first_copy_item['pi_charge_brand']}',
                        pi_charge_brand_etc = '{$pi_first_copy_item['pi_charge_brand_etc']}',
                        pi_charge_weight= '{$pi_first_copy_item['pi_charge_weight']}',
                        pi_charge_weight_etc= '{$pi_first_copy_item['pi_charge_weight_etc']}',

                        pi_ll_style= '{$pi_first_copy_item['pi_ll_style']}',
                        pi_prauden_umu_yn= '{$pi_first_copy_item['pi_prauden_umu_yn']}',
                        pi_hangkun_info= '{$pi_first_copy_item['pi_hangkun_info']}',
                        pi_hangkun_info_txt= '{$pi_first_copy_item['pi_hangkun_info_txt']}',
                        pi_pilpower= '{$pi_first_copy_item['pi_pilpower']}',
                        pi_pilpower_safe_yn= '{$pi_first_copy_item['pi_pilpower_safe_yn']}',
                        pi_info1= '{$pi_first_copy_item['pi_info1']}',
                        pi_info2= '{$pi_first_copy_item['pi_info2']}',
                        pi_info2_1= '{$pi_first_copy_item['pi_info2_1']}',
                        pi_info3= '{$pi_first_copy_item['pi_info3']}',
                        pi_manager= '{$pi_first_copy_item['pi_manager']}',
                        pi_img = '',
                        pi_img_total = '{$pi_first_copy_item['pi_img_total']}',
                        pi_video1= '{$pi_first_copy_item['pi_video1']}',
                        pi_video2= '{$pi_first_copy_item['pi_video2']}',
                        pi_video3= '{$pi_first_copy_item['pi_video3']}',
                        pi_video4= '{$pi_first_copy_item['pi_video4']}',
                        pi_origin_image= '{$pi_first_copy_item['pi_origin_image']}',
                        pi_detail_info= '{$t_pi_detail_info}',
                        pi_selling1= '{$t_pi_selling1}',
                        pi_selling2= '{$t_pi_selling2}',
                        pi_selling3= '{$t_pi_selling3}',
                        pi_prod_info1= '{$t_pi_prod_info1}',
                        pi_prod_info2= '{$t_pi_prod_info2}',
                        pi_prod_info3= '{$t_pi_prod_info3}',
                        pi_prod_info4= '{$t_pi_prod_info4}',
                        pi_prod_info5= '{$t_pi_prod_info5}',
                        pi_prod_info6= '{$t_pi_prod_info6}',
                        pi_prod_info7= '{$t_pi_prod_info7}',
                        pi_prod_info8= '{$t_pi_prod_info8}',
                        pi_prod_info9= '{$t_pi_prod_info9}',
                        pi_prod_info10= '{$t_pi_prod_info10}',
                        etc= '{$pi_first_copy_item['etc']}'
        ";



        $sql_pi = "insert lt_prod_info set $sql_pi_common";
        sql_query($sql_pi);

    } else if ($w == "u") {
        $sql = " update lt_job_order set $sql_common where jo_id = '$jo_id' ";
        sql_query($sql);
        $ps_sql = " update lt_prod_schedule set $sql_ps_common where ps_id = '$ps_id' ";
        sql_query($ps_sql);

        $sql_pi_common .= "pi_size_name = '{$_POST['jo_size_code']}'";
        $sql_pi_common .= ",pi_model_name = '{$jo_id_code}'";
        $sql_pi_common .= ",pi_model_no = '{$jo_id_code}'";
        $sql_pi = "update lt_prod_info set $sql_pi_common where jo_id = '$jo_id'";
        sql_query($sql_pi);
        
    } else if ($w == "d") {
        $sql = " delete from lt_job_order where jo_id = '$jo_id' ";
        sql_query($sql);
    }
}



if ($w == "d") {
    goto_url("./new_goods_process.php?tabs=list");
} else if($cp_category == 'ETC'){
    goto_url("./new_goods_process.php?tabs=list");
} else {
    goto_url("./new_goods_process.php?".$qstr);
    // goto_url($referer);
}
