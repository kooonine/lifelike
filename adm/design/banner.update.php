<?php
//$sub_menu = '100310';
$sub_menu = '10';
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check($auth[substr($sub_menu,0,2)], "d");
else
    auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

$ba_subject = isset($_POST['ba_subject']) ? strip_tags($_POST['ba_subject']) : '';

$image_regex = "/(\.(gif|jpe?g|png))$/i";
$chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

//동영상 이미지 파일업로드
if (isset($_FILES['ba_image']) && is_uploaded_file($_FILES['ba_image']['tmp_name'])) {
    // 기존 동영상 이미지가 있는 경우 삭제
    if ($_POST['org_ba_image'])
        @unlink(G5_DATA_PATH . '/banner/' . $_POST['org_ba_image']);

    if (preg_match($image_regex, $_FILES['ba_image']['name'])) {

        $design_dir = G5_DATA_PATH . '/banner/';
        @mkdir($design_dir, G5_DIR_PERMISSION);
        @chmod($design_dir, G5_DIR_PERMISSION);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($_FILES['ba_image']['name']);

        $dest_path = $design_dir . '/' . $dest_file;

        move_uploaded_file($_FILES['ba_image']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);

        $ba_image = $dest_file;
    }
} else {
    $ba_image = $_POST['org_ba_image'];
}

$sql_common = "ba_subject = '{$ba_subject}',
               ba_content = '{$_POST['ba_content']}',
               ba_type = '{$_POST['ba_type']}',
               ba_use = '{$_POST['ba_use']}',
               ba_color = '{$_POST['ba_color']}',
               ba_bg_color = '{$_POST['ba_bg_color']}',
               ba_start_date = '{$_POST['ba_start_date']}',
               ba_end_date = '{$_POST['ba_end_date']}',
               ba_link = '{$_POST['ba_link']}',
               ba_image = '{$ba_image}'
";

if ($w == "") {
    $sql = " insert lt_banner set $sql_common ";
    sql_query($sql);
    $ba_id = sql_insert_id();
} else if ($w == "u") {
    $sql = " update lt_banner set $sql_common where ba_id = '$ba_id' ";
    sql_query($sql);
} else if ($w == "d") {
    $sql = " delete from lt_banner where ba_id = '$ba_id' ";
    sql_query($sql);
}

if ($w == "d") {
    goto_url('./design_banner.php');
} else {
    goto_url("./banner.update.form.php?w=u&amp;ba_id=$ba_id");
}
