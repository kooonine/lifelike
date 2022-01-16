<?php
include_once('./_common.php');
$now_date = date("Y-m-d H:i:s");




$order_memo  = $_POST['order_memo'];
$order_no  = $_POST['order_no'];


$chk = "select count(*) AS cnt from b2b_order where order_no = '{$order_no}' ";
$chk_cnt = sql_fetch($chk);

if($chk_cnt['cnt'] < 1 ){
   
}else{
    $memo_sql = "UPDATE b2b_order SET order_memo ='{$order_memo}',  up_date = '{$now_date}'  where order_no = '{$order_no}' ";    
    sql_query($memo_sql);
}



$result = $user;
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
return;
?>