<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);



$id = $_POST['id'];
$ps_id = $_POST['ps_id'];
$type = $_POST['type'];
$result = '201';

// $result =$ps_id ;
// echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
// return false;

if($type =='fixed'){
    $pf_sql_common ="jo_price_fixed  = '100'";
    
    $pf_sql = " update lt_job_order set $pf_sql_common where jo_id = '$id' ";
    sql_query($pf_sql);

    $pf_status_sql = "SELECT * from  lt_job_order WHERE  ps_id = '$ps_id' ";
    $pf_status_res = sql_query($pf_status_sql);

    $suss_cnt_pf = 0;
    $fail_cnt_pf = 0;
    for ($pfi = 0; $pf_status_row = sql_fetch_array($pf_status_res); $pfi++) {
        if($pf_status_row['jo_price_fixed'] == '100'){
            $suss_cnt_pf = $suss_cnt_pf + 1;
        }else{
            $fail_cnt_pf = $fail_cnt_pf + 1;
        }
    }
    if($suss_cnt_pf == 0){
        $ps_price_fixed = "UPDATE lt_prod_schedule SET ps_price_fixed = '200' WHERE ps_id = '$ps_id' ";
    }else if($fail_cnt_pf == 0){
        $ps_price_fixed = "UPDATE lt_prod_schedule SET ps_price_fixed = '100' WHERE ps_id = '$ps_id' ";
    }else{
        $ps_price_fixed = "UPDATE lt_prod_schedule SET ps_price_fixed = '300' WHERE ps_id = '$ps_id' ";
    }

    sql_query($ps_price_fixed);

    $result = "200";
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return false;
}else if($type == 'gumsu'){
    $gu_sql_common ="pi_gumsu  = '100'";
    
    $gu_sql = " update lt_prod_info set $gu_sql_common where ps_id = '$ps_id' ";
    sql_query($gu_sql);

    $ps_gumsu = "UPDATE lt_prod_schedule SET ps_gumsu = '100' WHERE ps_id = '$ps_id' ";
    // $gu_status_sql = "SELECT * from  lt_prod_info WHERE  ps_id = '$ps_id' ";
    // $gu_status_res = sql_query($gu_status_sql);

    // $suss_cnt_gu = 0;
    // $fail_cnt_gu = 0;
    // for ($ssi = 0; $gu_status_row = sql_fetch_array($gu_status_res); $ssi++) {
    //     if($gu_status_row['pi_gumsu'] == '100'){
    //         $suss_cnt_gu = $suss_cnt_gu + 1;
    //     }else{
    //         $fail_cnt_gu = $fail_cnt_gu + 1;
    //     }
    // }
    // if($suss_cnt_gu == 0){
    //     $ps_gumsu = "UPDATE lt_prod_schedule SET ps_gumsu = '200' WHERE ps_id = '$ps_id' ";
    // }else if($fail_cnt_gu == 0){
    //     $ps_gumsu = "UPDATE lt_prod_schedule SET ps_gumsu = '100' WHERE ps_id = '$ps_id' ";
    // }else{
    //     $ps_gumsu = "UPDATE lt_prod_schedule SET ps_gumsu = '300' WHERE ps_id = '$ps_id' ";
    // }

    sql_query($ps_gumsu);

    $result = "200";
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return false;


}else if($type == 'edit_complete'){
    $gus_sql_common ="pi_gumsu_sub  = '100'";
    
    $gus_sql = " update lt_prod_info set $gus_sql_common where pi_id = '$id' ";
    sql_query($gus_sql);

    $gus_status_sql = "SELECT * from  lt_prod_info WHERE  ps_id = '$ps_id' ";
    $gus_status_res = sql_query($gus_status_sql);

    $suss_cnt_gus = 0;
    $fail_cnt_gus = 0;
    for ($gssi = 0; $gus_status_row = sql_fetch_array($gus_status_res); $gssi++) {
        if($gus_status_row['pi_gumsu_sub'] == '100'){
            $suss_cnt_gus = $suss_cnt_gus + 1;
        }else{
            $fail_cnt_gus = $fail_cnt_gus + 1;
        }
    }
    if($suss_cnt_gus == 0){
        $ps_gumsu = "UPDATE lt_prod_schedule SET ps_gumsu_sub = '200' WHERE ps_id = '$ps_id' ";
    }else if($fail_cnt_gus == 0){
        $ps_gumsu = "UPDATE lt_prod_schedule SET ps_gumsu_sub = '100' WHERE ps_id = '$ps_id' ";
    }else{
        $ps_gumsu = "UPDATE lt_prod_schedule SET ps_gumsu_sub = '300' WHERE ps_id = '$ps_id' ";
    }

    sql_query($ps_gumsu);

    $result = "200";
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return false;
}


