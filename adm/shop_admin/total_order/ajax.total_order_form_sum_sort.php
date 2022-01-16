<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);
//합포 다시 계산


// $form_date = date("2020-12-18");
$sql_form = "SELECT * FROM sabang_lt_order_form WHERE order_invoice IS NULL";
$result_form = sql_query($sql_form);

for($fii = 0 ; $row_form= sql_fetch_array($result_form); $fii++){
    
    $pos = strpos($row_form['mall_order_no'] , '-');
    if($pos === false){
        $mall_order_no = $row_form['mall_order_no'];
    }else{
        $temp_order_no = explode("-", $row_form['mall_order_no']);
        $mall_order_no = $temp_order_no[0];
    }

    $form_date = substr($row_form['reg_dt'] , 0 , 10);
    $sum_chk = "SELECT COUNT(*) AS cnt FROM sabang_lt_order_form 
    WHERE (sno = '{$row_form['sno']}' OR mall_order_no LIKE ('{$mall_order_no}%') AND mall_id ='{$row_form['mall_id']}' AND reg_dt LIKE '%{$form_date}%' AND order_name ='{$row_form['order_name']}') 
    AND dpartner_id = '{$row_form['dpartner_id']}' AND order_invoice IS NULL ORDER BY order_sum_sno DESC LIMIT 1";

    $sum_cnt = sql_fetch($sum_chk);

    $sum_cnt_updateSql = "UPDATE sabang_lt_order_form SET order_sum_sno ='{$sum_cnt['cnt']}' WHERE sno = '{$row_form['sno']}'";
    sql_query($sum_cnt_updateSql);
}

goto_url("./total_order_form.php?");
