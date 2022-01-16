<?php
//$sub_menu = '100310';
$sub_menu = '10';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);

if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check($auth[substr($sub_menu,0,2)], "d");
else
    auth_check($auth[substr($sub_menu,0,2)], "w");

// check_admin_token();

$image_regex = "/(\.(gif|jpe?g|png))$/i";
$chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

$br_images = array();
$images = array(
    'br_logo',
    'br_main_image',
    'br_main_image_mobile',
    'br_lookbook',
    'br_lookbook_mobile'
);

//동영상 이미지 파일업로드
foreach ($images as $image) {
    if (isset($_FILES[$image]) && is_uploaded_file($_FILES[$image]['tmp_name'])) {
        if (preg_match($image_regex, $_FILES[$image]['name'])) {

            $dest_dir = G5_DATA_PATH . '/brand/';
            @mkdir($dest_dir, G5_DIR_PERMISSION);
            @chmod($dest_dir, G5_DIR_PERMISSION);

            shuffle($chars_array);
            $shuffle = implode('', $chars_array);
            $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES[$image]['name']);

            $dest_path = $dest_dir . '/' . $dest_file;

            move_uploaded_file($_FILES[$image]['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            $br_images[$image] = $dest_file;
        }
    }
}

$sql_common = "br_name = '{$_POST['br_name']}',
               br_name_en = '{$_POST['br_name_en']}',
               br_slogan = '{$_POST['br_slogan']}',
               br_desc = '{$_POST['br_desc']}',
               br_use = '{$_POST['br_use']}',
               br_notice = '{$_POST['br_notice']}',
               br_notice_mobile = '{$_POST['br_notice_mobile']}',
               br_notice_use = {$_POST['br_notice_use']},
               br_notice_start_date = '{$_POST['br_notice_start_date']}',
               br_notice_end_date = '{$_POST['br_notice_end_date']}'
";

foreach ($br_images as $itype => $br_image) {
    $sql_common .= ",{$itype} = '{$br_image}'";
}

if ($w == "") {
    $sql = " insert lt_brand set $sql_common ";
    sql_query($sql);
    $cp_id = sql_insert_id();
} else if ($w == "u") {
    $sql = " update lt_brand set $sql_common where br_id = '$br_id' ";
    sql_query($sql);
} else if ($w == "d") {
    $sql = " delete from lt_brand where br_id = '$br_id' ";
    sql_query($sql);
}

if ($w == "d") {
    goto_url('./brand.list.php');
} else {
    goto_url("./brand.update.form.php?w=u&amp;br_id=$br_id");
}
