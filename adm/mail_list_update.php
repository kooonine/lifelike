<?php
$sub_menu = '200300';
include_once('./_common.php');

check_demo();

auth_check($auth[substr($sub_menu,0,2)], 'w');

check_admin_token();

$count = count($_POST['ma_id']);

for($i=0; $i<$count; $i++) {
    $ma_id = $_POST['ma_id'][$i];
    
    $ma_use = ($_POST['chk'][$i])?"1":"0";

    $sql = " update {$g5['mail_table']}
             set   ma_use = '$ma_use' 
             where ma_id = '$ma_id' ";
    
    sql_query($sql);
}

goto_url('./mail_list.php');
?>