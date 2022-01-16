<?php
//$sub_menu = '100220';
$sub_menu = '10';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

//check_admin_token();

if ($w == "")
{
    $sql_common = "";
    if($ib_mb_id) $sql_common .= " ,ib_mb_id = '{$ib_mb_id}' ";
    if($ib_bo_table) $sql_common .= " ,ib_bo_table = '{$ib_bo_table}' ";
    if($ib_wr_id) $sql_common .= " ,ib_wr_id = '{$ib_wr_id}' ";
    
    $sql = " insert lt_ipblock
                set ib_datetime = '".G5_TIME_YMDHIS."'
                    ,ib_intercept_ip = '{$ib_intercept_ip}'
                    ,ib_admin_id = '{$member['mb_id']}'
					$sql_common	";
    
    $alertMsg = "정상적으로 차단되었습니다.";
}
else if ($w == "u")
{
    if (!$ib_no)
        alert("정보가 올바르지 않습니다.","./configform_ip.php");
    
    $sql_common = "";
    if($ib_mb_id) $sql_common .= " ,ib_mb_id = '{$ib_mb_id}' ";
    if($ib_bo_table) $sql_common .= " ,ib_bo_table = '{$ib_bo_table}' ";
    if($ib_wr_id) $sql_common .= " ,ib_wr_id = '{$ib_wr_id}' ";
    
    $sql = " update lt_ipblock
                set ib_datetime = '".G5_TIME_YMDHIS."'
                    ,ib_intercept_ip = '{$ib_intercept_ip}'
                    ,ib_admin_id = '{$member['mb_id']}'
					$sql_common
              where ib_no = '$ib_no' ";
    $alertMsg = "정상적으로 차단되었습니다.";
}
 else if ($w == "d")
 {
     if (!$ib_no)
         alert("정보가 올바르지 않습니다.","./configform_ip.php");
     
     $sql = " delete from lt_ipblock where ib_no = '$ib_no' ";
     
     $alertMsg = "접속제한이 해제되었습니다.";
 }

if(false)
{
	//Test시 사용
	echo $sql;

} else {
    
    sql_query($sql);
    
    //접속 차다config 반영
    sql_query("update lt_config set cf_intercept_ip = (select  group_concat(DISTINCT ib_intercept_ip SEPARATOR  '\n') from lt_ipblock)");
    
    alert($alertMsg,"./configform_ip.php", false);
}
?>
