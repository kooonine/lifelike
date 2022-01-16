<?php
//$sub_menu = '930110';
$sub_menu = '93';
include_once('./_common.php');


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("qstr=" , $referer);


$qstr=$cut_url[1];

if(!empty($_POST["no"])){
    $chk_list = array();
    foreach ($_POST["no"] as $idx => $clist) {
        $no = $_POST['no'][$idx];
        $selecter = $_POST['chk_ro'][$idx];
        $origlately_date =  $_POST['lately_date'][$idx];
        $lately_date_date = str_replace('-', '', $origlately_date );
        $newlately_date = date("Y-m-d", strtotime($lately_date_date));

        $origadd_date =  $_POST['add_date'][$idx];
        $add_date_date = str_replace('-', '', $origadd_date );
        $newadd_date = date("Y-m-d", strtotime($add_date_date));
        

        $tmp_item_set = array(        
            "no" => $no,
            "run_out" => $_POST['run_out'][$idx],
            "memo" => $_POST['memo'][$idx],
            "moq" => $_POST['moq'][$idx],
            "color_nm" => $_POST['color_nm'][$idx],
            "lately_date" => $newlately_date,
            "add_date" => $newadd_date,
            "balju_qty_s" => $_POST['balju_qty_s'][$idx],
            "balju_qty_q" => $_POST['balju_qty_q'][$idx],
            "balju_qty_k" => $_POST['balju_qty_k'][$idx],
            "etc_meg" => $_POST['etc_meg'][$idx]
        );
        $chk_list[$idx] =  $tmp_item_set;

        if($selecter=="true"){
            $new_db_ovs_common =  "
            run_out = '{$_POST['run_out'][$idx]}',
            memo = '{$_POST['memo'][$idx]}',
            moq = '{$_POST['moq'][$idx]}',
            color_nm = '{$_POST['color_nm'][$idx]}',
            lately_date = '{$newlately_date}',
            add_date = '{$newadd_date}',
            balju_qty_s = '{$_POST['balju_qty_s'][$idx]}',
            balju_qty_q = '{$_POST['balju_qty_q'][$idx]}',
            balju_qty_k = '{$_POST['balju_qty_k'][$idx]}',
            etc_meg = '{$_POST['etc_meg'][$idx]}'
            ";
    
            $new_db_ovs_sql = "UPDATE new_goods_db_overseas SET $new_db_ovs_common WHERE no = {$no}";
            
            sql_query($new_db_ovs_sql);
        }
    }
}

// dd($chk_list);



goto_url("./new_goods_db_overseas.php?".$qstr);