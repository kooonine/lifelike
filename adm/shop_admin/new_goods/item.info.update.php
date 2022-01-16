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

$pi_id = $_POST['pi_id'];

if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check($auth[substr($sub_menu,0,2)], "d");
else
    auth_check($auth[substr($sub_menu,0,2)], "w");

// check_admin_token();

//$cp_subject = isset($_POST['cp_subject']) ? strip_tags($_POST['cp_subject']) : '';

// $cp_subject = isset($_POST['cp_subject']) ? $_POST['cp_subject'] : '';

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

            $design_dir = G5_DATA_PATH . '/banner/';
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
$pi_item_detail = '200';
$pi_images = array();
if(!empty($_POST["pi_img"])){
    foreach ($_POST['pi_img'] as $pii => $images) {
        $tmp_img_set = array(
            "img" => $_POST['pi_img'][$pii]
        );
        
        if(!empty($_POST['pi_img'][$pii])){
            $pi_item_detail = '100';
        }

        $pi_images[$pii] = $tmp_img_set;
    }
}
$pi_gumsu = $_POST["pi_gumsu"];
if(!empty($pi_gumsu)){
    if(!empty($_POST["pi_img_total"])){
        $pi_gumsu = '400';
    }
}else{

}

$pi_gumsu_sub = $_POST["pi_gumsu_sub"];
if(!empty($pi_gumsu_sub)){
    if(!empty($_POST["edit_order_content"])){
        $pi_gumsu = '400';
    }
}else{

}
if(!empty($_POST["edit_order_content"])){
    $pi_gumsu_sub = '400';
}else{
    $pi_gumsu_sub = '200';
}



$chk_gumsu_sql = "SELECT ps_gumsu FROM lt_prod_schedule WHERE ps_id = '{$ps_id}' limit 1 ";
$chk_gumsu_res = sql_fetch($chk_gumsu_sql);

$chk_ps_gumsu_yn = $chk_gumsu_res['ps_gumsu'];

if($chk_ps_gumsu_yn == '100'){
    $pi_gumsu = '100';
}


$pi_origin_price= preg_replace('/,/', '',$_POST['pi_origin_price']);
$pi_sale_price= preg_replace('/,/', '',$_POST['pi_sale_price']);
$pi_sale_price2= preg_replace('/,/', '',$_POST['pi_sale_price2']);
$pi_tag_price= preg_replace('/,/', '',$_POST['pi_tag_price']);

//작업지시서 생성
$sql_common = " pi_sub_category = '{$_POST['pi_sub_category']}',
                pi_design_style = '{$_POST['pi_design_style']}',
                pi_design_style_sub = '{$_POST['pi_design_style_sub']}',
                pi_jego_age= '{$_POST['pi_jego_age']}',
                pi_running_out= '{$_POST['pi_running_out']}',
                pi_season= '{$_POST['pi_season']}',
                pi_it_name= '{$_POST['pi_it_name']}',
                pi_it_sub_name= '{$_POST['pi_it_sub_name']}',
                pi_model_name= '{$_POST['pi_model_name']}',
                pi_model_no= '{$_POST['pi_model_no']}',
                pi_company_it_id= '{$_POST['pi_company_it_id']}',
                pi_brand= '{$_POST['pi_brand']}',
                pi_category= '{$_POST['pi_category']}',
                pi_mater= '{$_POST['pi_mater']}',
                pi_prod_date= '{$_POST['pi_prod_date']}',
                pi_age_gubun= '{$_POST['pi_age_gubun']}',
                pi_delivery_price= '{$_POST['pi_delivery_price']}',
                pi_origin_price= '{$pi_origin_price}',
                pi_sale_price= '{$pi_sale_price}',
                pi_sale_price2= '{$pi_sale_price2}',
                pi_tag_price= '{$pi_tag_price}',
                pi_item_soje= '{$_POST['pi_item_soje']}',
                pi_item_soje_detail= '{$_POST['pi_item_soje_detail']}',
                pi_color= '{$_POST['pi_color']}',
                pi_size= '{$_POST['pi_size']}',
                pi_cisu= '{$_POST['pi_cisu']}',
                pi_maker= '{$_POST['pi_maker']}',
                pi_laundry= '{$_POST['pi_laundry']}',
                pi_kc_safe_yn= '{$_POST['pi_kc_safe_yn']}',
                pi_soip_yn= '{$_POST['pi_soip_yn']}',
                pi_prod_weight= '{$_POST['pi_prod_weight']}',
                pi_xyz= '{$_POST['pi_xyz']}',
                pi_charge= '{$_POST['pi_charge']}',
                
                pi_charge_mater= '{$_POST['pi_charge_mater']}',
                pi_charge_mater_etc= '{$_POST['pi_charge_mater_etc']}',
                pi_charge_brand= '{$_POST['pi_charge_brand']}',
                pi_charge_brand_etc = '{$_POST['pi_charge_brand_etc']}',
                pi_charge_weight= '{$_POST['pi_charge_weight']}',
                pi_charge_weight_etc= '{$_POST['pi_charge_weight_etc']}',

                pi_ll_style= '{$_POST['pi_ll_style']}',
                pi_prauden_umu_yn= '{$_POST['pi_prauden_umu_yn']}',
                pi_hangkun_info= '{$_POST['pi_hangkun_info']}',
                pi_hangkun_info_txt= '{$_POST['pi_hangkun_info_txt']}',
                pi_pilpower= '{$_POST['pi_pilpower']}',
                pi_pilpower_safe_yn= '{$_POST['pi_pilpower_safe_yn']}',
                pi_info1= '{$_POST['pi_info1']}',
                pi_info2= '{$_POST['pi_info2']}',
                pi_info2_1= '{$_POST['pi_info2_1']}',
                pi_info3= '{$_POST['pi_info3']}',
                pi_manager= '{$_POST['pi_manager']}',

                pi_item_detail= '{$pi_item_detail}', 
                pi_gumsu = '{$pi_gumsu}',
                pi_gumsu_sub = '{$pi_gumsu_sub}',
                pi_img_total = '{$_POST['pi_img_total']}',
                pi_video1= '{$_POST['pi_video1']}',
                pi_video2= '{$_POST['pi_video2']}',
                pi_video3= '{$_POST['pi_video3']}',
                pi_video4= '{$_POST['pi_video4']}',
                pi_origin_image= '{$_POST['pi_origin_image']}',
                pi_detail_info= '{$_POST['pi_detail_info']}',
                pi_selling1= '{$_POST['pi_selling1']}',
                pi_selling2= '{$_POST['pi_selling2']}',
                pi_selling3= '{$_POST['pi_selling3']}',
                pi_prod_info1= '{$_POST['pi_prod_info1']}',
                pi_prod_info2= '{$_POST['pi_prod_info2']}',
                pi_prod_info3= '{$_POST['pi_prod_info3']}',
                pi_prod_info4= '{$_POST['pi_prod_info4']}',
                pi_prod_info5= '{$_POST['pi_prod_info5']}',
                pi_prod_info6= '{$_POST['pi_prod_info6']}',
                pi_prod_info7= '{$_POST['pi_prod_info7']}',
                pi_prod_info8= '{$_POST['pi_prod_info8']}',
                pi_prod_info9= '{$_POST['pi_prod_info9']}',
                pi_prod_info10= '{$_POST['pi_prod_info10']}',
                edit_order_content= '{$_POST['edit_order_content']}',
                
                tem_save = '{$_POST['tem_save']}',
                etc= '{$_POST['etc']}'
";

// $sql_common .= ",cp_banner = '" . implode(',', $_POST['cp_banner']) . "'";
$sql_common .= ",pi_img = '" . addslashes(json_encode($pi_images, JSON_UNESCAPED_UNICODE)) . "'";


if ($w == "") {
    
    $sql_common .= ",ps_id = '{$ps_id}'";


    $sql = " insert lt_prod_info set $sql_common ";
    sql_query($sql);    
} else if ($w == "u") {
    $sql = " update lt_prod_info set $sql_common where pi_id = '$pi_id' ";
    sql_query($sql);
    //상품정보집 상품기술서 자동 업데이트
    $sql_item_imgs = "pi_img = '" . addslashes(json_encode($pi_images, JSON_UNESCAPED_UNICODE)) . "'";
    $sql_item_imgs .= ",pi_item_detail = '{$pi_item_detail}' ";
    $it_sql ="update lt_prod_info set $sql_item_imgs where ps_id = '{$ps_id}' ";
    sql_query($it_sql);

    $ps_sql ="update lt_prod_schedule set ps_item_detail = '{$pi_item_detail}' where ps_id = '{$ps_id}' ";
    sql_query($ps_sql);
    
    //검수요청 경로
    $sql_total_imgs = "pi_img_total = '{$_POST['pi_img_total']}' ";
    $sql_total_imgs .= ",pi_gumsu = '400' ";
    $t_pi_sql ="update lt_prod_info set $sql_total_imgs where ps_id = '{$ps_id}' and pi_gumsu <> '100' ";
    sql_query($t_pi_sql);
    if(!empty($_POST["pi_img_total"])){

        //생산일정
        $t_ps_sql ="update lt_prod_schedule set ps_gumsu = '400' where ps_id = '{$ps_id}' and ps_gumsu <> '100' ";
        sql_query($t_ps_sql);
    }else{
        $sql_total_imgs = "pi_gumsu = '200' ";
        $t_pi_sql2 ="update lt_prod_info set $sql_total_imgs where ps_id = '{$ps_id}' ";
        sql_query($t_pi_sql2);

        //생산일정
        $t_ps_sql2 ="update lt_prod_schedule set ps_gumsu = '200' where ps_id = '{$ps_id}' ";
        sql_query($t_ps_sql2);
    }


} else if ($w == "d") {
    $sql = " delete from lt_prod_info where cp_id = '$cp_id' ";
    sql_query($sql);
}

// if(!empty($pi_item_detail)){
//     $pi_item_detail = "SELECT * from  lt_prod_info WHERE ps_id = '{$ps_id}' ";
//     $item_detail_res = sql_query($pi_item_detail);

//     $suss_cnt = 0;
//     $fail_cnt = 0;

//     for ($ssi = 0; $detail_row = sql_fetch_array($item_detail_res); $ssi++) {
//         if($detail_row['pi_item_detail'] == '100'){
//             $suss_cnt = $suss_cnt + 1;
//         }else{
//             $fail_cnt = $fail_cnt + 1;
//         }
//     }


//     if($suss_cnt == 0){
//         $ps_item_detail_sql = "UPDATE lt_prod_schedule SET ps_item_detail = '200' WHERE ps_id = '{$ps_id}' ";
//     }else if($fail_cnt == 0){
//         $ps_item_detail_sql = "UPDATE lt_prod_schedule SET ps_item_detail = '100' WHERE ps_id = '{$ps_id}' ";
//     }else{
//         $ps_item_detail_sql = "UPDATE lt_prod_schedule SET ps_item_detail = '300' WHERE ps_id = '{$ps_id}' ";
//     }

//     sql_query($ps_item_detail_sql);
// }



if($chk_ps_gumsu_yn == '100'){
    
}else{
    if(!empty($pi_gumsu)){
        $pi_gumsu_sql = "SELECT * from  lt_prod_info WHERE ps_id = '{$ps_id}' ";
        $gumsu_res = sql_query($pi_gumsu_sql);
    
        $suss_cnt_gu = 0;
        $fail_cnt_gu = 0;
    
        for ($gsi = 0; $gumsu_row = sql_fetch_array($gumsu_res); $gsi++) {
            if($gumsu_row['pi_gumsu'] == '400'){
                $suss_cnt_gu = $suss_cnt_gu + 1;
            }else{
                $fail_cnt_gu = $fail_cnt_gu + 1;
            }
        }
    
    
        if($suss_cnt_gu == 0){
            $ps_gumsu_res_sql = "UPDATE lt_prod_schedule SET ps_gumsu = '200' WHERE ps_id = '{$ps_id}' and ps_gumsu <> '100' ";
        }else if($fail_cnt_gu == 0){
            $ps_gumsu_res_sql = "UPDATE lt_prod_schedule SET ps_gumsu = '400' WHERE ps_id = '{$ps_id}' and ps_gumsu <> '100' ";
        }else{
            $ps_gumsu_res_sql = "UPDATE lt_prod_schedule SET ps_gumsu = '500' WHERE ps_id = '{$ps_id}' and ps_gumsu <> '100' ";
        }
    
        sql_query($ps_gumsu_res_sql);
    }
}


if(!empty($pi_gumsu_sub)){
    $pi_gumsu_sub_sql = "SELECT * from  lt_prod_info WHERE ps_id = '{$ps_id}' ";
    $gumsu_res = sql_query($pi_gumsu_sub_sql);

    $suss_cnt_gus = 0;
    $fail_cnt_gus = 0;

    if($pi_gumsu_sub == '400'){
        $ps_gumsus_res_sql = "UPDATE lt_prod_schedule SET ps_gumsu_sub = '400' WHERE ps_id = '{$ps_id}' and ps_gumsu_sub <> '100' and ps_gumsu_sub <> '300' ";
    }else if($pi_gumsu_sub == '200'){
        $ps_gumsus_res_sql = "UPDATE lt_prod_schedule SET ps_gumsu_sub = '200' WHERE ps_id = '{$ps_id}' and ps_gumsu_sub <> '100' and ps_gumsu_sub <> '300' ";
    }

    // for ($gssi = 0; $gumsus_row = sql_fetch_array($gumsu_res); $gssi++) {
    //     if($gumsus_row['pi_gumsu_sub'] == '400'){
    //         $suss_cnt_gus = $suss_cnt_gus + 1;
    //     }else{
    //         $fail_cnt_gus = $fail_cnt_gus + 1;
    //     }
    // }


    // if($suss_cnt_gus == 0){
    //     $ps_gumsus_res_sql = "UPDATE lt_prod_schedule SET ps_gumsu_sub = '200' WHERE ps_id = '{$ps_id}' and ps_gumsu_sub <> '100' and ps_gumsu_sub <> '300' ";
    // }else if($fail_cnt_gus == 0){
    //     $ps_gumsus_res_sql = "UPDATE lt_prod_schedule SET ps_gumsu_sub = '400' WHERE ps_id = '{$ps_id}' and ps_gumsu_sub <> '100' and ps_gumsu_sub <> '300' ";
    // }else{
    //     $ps_gumsus_res_sql = "UPDATE lt_prod_schedule SET ps_gumsu_sub = '500' WHERE ps_id = '{$ps_id}' and ps_gumsu_sub <> '100' and ps_gumsu_sub <> '300' ";
    // }

    sql_query($ps_gumsus_res_sql);
}


if ($w == "d") {
    goto_url("./new_goods_process.php?tabs=list");
} else if($cp_category == 'ETC'){
    goto_url("./new_goods_process.php?tabs=list");
} else {
    // goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
    goto_url($referer);
}
