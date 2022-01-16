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

$cp_subject = isset($_POST['cp_subject']) ? strip_tags($_POST['cp_subject']) : '';

$image_regex = "/(\.(gif|jpe?g|png))$/i";
$chars_array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
$banners = array('MAIN', 'LIST', 'GNB', 'LNB', 'HISTORY');

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

foreach ($_POST["cp_image_del"] as $idx => $del) {
    if ($del == "1") {
        $del_path = G5_DATA_PATH . '/banner/' . $cp['cp_image_' . $idx];
        unlink($del_path);
        $sql_del = "UPDATE lt_campaign SET cp_image_{$idx} = NULL WHERE cp_id='{$cp_id}'";
        sql_query($sql_del);
    }
}

$cp_item_set = array();

foreach ($_POST["cp_item_set_subject"] as $idx => $subject) {
    if (!$_POST['cp_promotion']) $_POST['cp_item_set_number'][$idx] = '';
    $tmp_item_set = array(
        "subject" => $subject,
        "item" => $_POST['cp_item_set_item'][$idx],
        "category" => $_POST['cp_item_set_category'][$idx],
        "number" => $_POST['cp_item_set_number'][$idx],
    );

    $cp_item_set[$idx] = $tmp_item_set;
}

if (!$_POST['cp_promotion']) {
    $_POST['cp_promotion'] = 0;
} 

$sql_common = "cp_subject = '{$cp_subject}',
               cp_desc = '{$_POST['cp_desc']}',
               cp_link = '{$_POST['cp_link']}',
               cp_category = '{$_POST['cp_category']}',
               cp_content = '{$_POST['cp_content']}',
               cp_content_mobile = '{$_POST['cp_content_mobile']}',
               cp_use = {$_POST['cp_use']},
               cp_start_date = '{$_POST['cp_start_date']}',
               cp_end_date = '{$_POST['cp_end_date']}',
               cp_promotion = '{$_POST['cp_promotion']}'
";

$sql_common .= ",cp_banner = '" . implode(',', $_POST['cp_banner']) . "'";
$sql_common .= ",cp_item_set = '" . addslashes(json_encode($cp_item_set, JSON_UNESCAPED_UNICODE)) . "'";

foreach ($cp_image as $ci => $path) {
    $sql_common .= ",cp_image_{$ci} = '{$path}'";
}

if ($w == "") {
    $sql = " insert lt_campaign set $sql_common ";
    sql_query($sql);
    $cp_id = sql_insert_id();
} else if ($w == "u") {
    $sql = " update lt_campaign set $sql_common where cp_id = '$cp_id' ";
    sql_query($sql);
} else if ($w == "d") {
    $sql = " delete from lt_campaign where cp_id = '$cp_id' ";
    sql_query($sql);
}
$sqlScz = " update lt_shop_coupon_zone set cp_promotion= 0 where cp_promotion = '$cp_id' ";
sql_query($sqlScz);

if ($_POST['cp_promotion']) {
    $sql = " update lt_shop_coupon_zone set cp_promotion= '$cp_id' where cz_id = '{$_POST['cp_promotion_select']}' ";
    sql_query($sql);
} 

if ($w == "d") {
    goto_url('./campaign.list.php');
} else {
    goto_url("./campaign.update.form.php?w=u&amp;cp_id=$cp_id");
}
