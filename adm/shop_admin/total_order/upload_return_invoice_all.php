<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');



$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];

$file = $_FILES['upload_excel_invoice']['tmp_name'];

$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('UTF-8');
$data->read($file);

$now_date = date("Y-m-d H:i:s");


for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
    $ret_no = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1]);
    $ret_invoice = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][2]);
    $ret_qty = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][3]);
    
    //ro_reason 정상 , return_status 입고확인


    $updateSql = "UPDATE sabang_return_origin SET ro_reason = '정상', ro_invoice = '$ret_invoice', return_status =  '입고확인' , ro_cnt ='$ret_qty', ware_datetime = '{$now_date}'  WHERE sro_id = $ret_no";

    sql_query($updateSql);
    
    // if(!empty($sabang_ord_no)){
    //     $exist_chk = "select * from samjin_order_sale_registration where sabang_ord_no = '{$sabang_ord_no}' ";
    //     $exist_chk_result = sql_query($exist_chk);

    //     for($ssi = 0 ; $inv_row = sql_fetch_array($exist_chk_result); $ssi++){
            
    //         if(!empty($price) || !empty($sub_price)){
    //             $upsql= "update samjin_order_sale_registration
    //                     set order_price = '{$price}' , order_division_price = '{$sub_price}'
    //                         where sabang_ord_no = '{$sabang_ord_no}' and samjin_name = '{$samjin_name}'
    //                         ";
    //             sql_query($upsql);
    //         }
            
    //     }
    // }


}

goto_url("./total_order_return_list.php?".$qstr);

?>