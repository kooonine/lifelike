<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);


$image_regex = "/(\.(gif|jpe?g|png))$/i";
$chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));


$ps_id = $_POST['ps_id'];

$file = $_FILES['file'];

$imgfile = array();

//print_r($_FILES);
$main_img =array();


foreach ($_FILES['file']['name'] as $idx => $images) {
    if (isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'][$idx])) {
        // 기존 동영상 이미지가 있는 경우 삭제
        // if ($_POST['org_cp_image_' . $fi])
        //     @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_cp_image_' . $fi]);
        
        if (preg_match($image_regex, $_FILES['file']['name'][$idx])) {

            $design_dir = G5_DATA_PATH . '/new_goods/';
            @mkdir($design_dir, G5_DIR_PERMISSION);
            @chmod($design_dir, G5_DIR_PERMISSION);

            shuffle($chars_array);
            $shuffle = implode('', $chars_array);
            $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['file']['name'][$idx]);

            $dest_path = $design_dir . '/' . $dest_file;

            move_uploaded_file($_FILES['file']['tmp_name'][$idx], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            
        }
    }
    $imgfile[$idx] = $dest_file;

}
foreach ($imgfile as $ci => $path) {
    $tmp_img_set = array(        
        "img" => $path
    );
    $main_img[$ci] = $tmp_img_set;
}

$sql_common = "ps_prod_main_imgs = '" . addslashes(json_encode($main_img, JSON_UNESCAPED_UNICODE)) . "'";
$sql = " update lt_prod_schedule set $sql_common where ps_id = '{$ps_id}' ";
sql_query($sql);


goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
