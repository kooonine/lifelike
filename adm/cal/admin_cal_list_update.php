<?php
$sub_menu = "50";

include_once("./_common.php");
auth_check($auth[substr($sub_menu,0,2)], 'w');

$act = $_POST['act'];

if($act == "delete"){
    check_admin_token();
    
    $pc_id = $_POST['pc_id'];
    $sql = "select * from lt_shop_pg_cal where pc_id = '".$pc_id."' ";
    $pc = sql_fetch($sql);
    
    $dest_full_path = G5_DATA_PATH.'/cal/'.$pc['pc_source'];
    @unlink($dest_full_path);
        
    $sql = " delete from lt_shop_pg_cal
            where   pc_id = '".$pc_id."' 
          ";
    sql_query($sql);
    
    $msg = "정산내역 파일을 삭제하였습니다.";
    
    $qstr .= "&amp;cal_type=".$cal_type;
    alert($msg, './admin_cal_list.php?page='.$page.$qstr, false);
}

?>