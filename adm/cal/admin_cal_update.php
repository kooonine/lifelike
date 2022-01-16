<?php
$sub_menu = "50";

include_once("./_common.php");
auth_check($auth[substr($sub_menu,0,2)], 'w');

check_admin_token();

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST)) {
    alert("파일의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\\n서버관리자에게 문의 바랍니다.");
}

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

$cal_dir = G5_DATA_PATH.'/cal';
@mkdir($cal_dir, G5_DIR_PERMISSION);
@chmod($cal_dir, G5_DIR_PERMISSION);
if (isset($_FILES['pc_file']) && is_uploaded_file($_FILES['pc_file']['tmp_name']))
{
    $tmp_file  = $_FILES['pc_file']['tmp_name'];
    $filesize  = $_FILES['pc_file']['size'];
    $filename  = $_FILES['pc_file']['name'];
    $filename  = get_safe_filename($filename);
    
    shuffle($chars_array);
    $shuffle = implode('', $chars_array);
    
    // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
    $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);    
    $dest_full_path = $cal_dir.'/'.$dest_file;
    
    // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
    $error_code = move_uploaded_file($tmp_file, $dest_full_path) or die($_FILES['pc_file']['error'][$i]);
    
    // 올라간 파일의 퍼미션을 변경합니다.
    chmod($dest_full_path, G5_FILE_PERMISSION);
    
    if (!get_magic_quotes_gpc()) {
        $dest_file = addslashes($dest_file);
    }
    
    $pc_name = $_POST['pc_name'];
    $pc_source = $dest_file;
    $pc_file = $filename;
    $pc_filesize = $filesize;
    
    $sql = " insert lt_shop_pg_cal
                set mb_id = '{$member['mb_id']}'
                    ,mb_name = '{$member['mb_name']}'
                    ,pc_name = '{$pc_name}'
                    ,pc_source = '{$pc_source}'
                    ,pc_file = '{$pc_file}'
                    ,pc_filesize = '{$pc_filesize}'
                    ,pc_datetime = '".G5_TIME_YMDHIS."'
          ";
    sql_query($sql);
    
    $msg = "정산내역 파일을 업로드하였습니다.";
} else {
    alert('업로드 할 정산내역 파일이 없습니다.');
}

$qstr .= "&amp;cal_type=".$cal_type;
alert($msg, './admin_cal_list.php?page='.$page.$qstr, false);
?>