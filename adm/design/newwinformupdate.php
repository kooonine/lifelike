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

$nw_subject = isset($_POST['nw_subject']) ? strip_tags($_POST['nw_subject']) : '';

$image_regex = "/(\.(gif|jpe?g|png))$/i";
$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

//동영상 이미지 파일업로드
if (isset($_FILES['nw_imgfile']) && is_uploaded_file($_FILES['nw_imgfile']['tmp_name']))
{
    // 기존 동영상 이미지가 있는 경우 삭제
    if ($_POST['orgnw_imgfile'])
        @unlink(G5_DATA_PATH.'/popup/'.$_POST['orgnw_imgfile']);
        
        if (!preg_match($image_regex, $_FILES['nw_imgfile']['name'])) {
            alert($_FILES['movieimg']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }
        
        if (preg_match($image_regex, $_FILES['nw_imgfile']['name'])) {
            
            $design_dir = G5_DATA_PATH.'/popup/';
            @mkdir($design_dir, G5_DIR_PERMISSION);
            @chmod($design_dir, G5_DIR_PERMISSION);
            
            shuffle($chars_array);
            $shuffle = implode('', $chars_array);
            $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($_FILES['nw_imgfile']['name']);
            
            $dest_path = $design_dir.'/'.$dest_file;
            
            move_uploaded_file($_FILES['nw_imgfile']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            
            $nw_imgfile = $dest_file;
        }
} else {
    $nw_imgfile = $_POST['orgnw_imgfile'];
}

$sql_common = " nw_division = '{$_POST['nw_division']}',
                nw_device = '{$_POST['nw_device']}',
                nw_begin_time = '{$_POST['nw_begin_time']}',
                nw_end_time = '{$_POST['nw_end_time']}',
                nw_disable_hours = '{$_POST['nw_disable_hours']}',
                nw_left = '{$_POST['nw_left']}',
                nw_top = '{$_POST['nw_top']}',
                nw_height = '{$_POST['nw_height']}',
                nw_width = '{$_POST['nw_width']}',
                nw_subject = '{$nw_subject}',
                nw_content = '{$_POST['nw_content']}',
                nw_content_html = '{$_POST['nw_content_html']}'
                ,nw_status = '{$_POST['nw_status']}'
                ,nw_link = '{$_POST['nw_link']}'
                ,nw_imgfile = '{$nw_imgfile}'
";

if($w == "")
{
    $sql = " insert {$g5['new_win_table']} set $sql_common ";
    $sql = $sql." ,nw_reg_datetime = now() ";
    
    sql_query($sql);

    $nw_id = sql_insert_id();
}
else if ($w == "u")
{
    $sql = " update {$g5['new_win_table']} set $sql_common where nw_id = '$nw_id' ";
    sql_query($sql);
}
else if ($w == "d")
{
    $sql = " delete from {$g5['new_win_table']} where nw_id = '$nw_id' ";
    sql_query($sql);
}

if ($w == "d")
{
    goto_url('./design_popup.php');
}
else
{
    goto_url("./newwinform.php?w=u&amp;nw_id=$nw_id");
}
?>
