<?php
include_once('./_common.php');
$now_date = date("Y-m-d H:i:s");




$order_memo  = $_POST['order_memo'];
$sabang_idx  = $_POST['sabang_idx'];
$user = $member['mb_id'];

$chk = "select count(*) AS cnt from sabang_lt_order_memo where sabang_ord_no = '{$sabang_idx}' ";
$chk_cnt = sql_fetch($chk);

if($chk_cnt['cnt'] < 1 ){
    $memo_sql = "INSERT INTO sabang_lt_order_memo SET sabang_ord_no = '{$sabang_idx}', reg_user = '{$user}' , order_memo ='{$order_memo}' , reg_dt  = '{$now_date}'  ";    
    sql_query($memo_sql);
}else{
    $memo_sql = "UPDATE sabang_lt_order_memo SET order_memo ='{$order_memo}', up_user = '{$user}' , up_dt = '{$now_date}'  where sabang_ord_no = '{$sabang_idx}' ";    
    sql_query($memo_sql);
}



$result = $user;
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;
?>