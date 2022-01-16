<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];

$file = $_FILES['upload_excel']['tmp_name'];

$cp_code = $_POST['cp_code'];

$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('UTF-8');
$data->read($file);

$toDate = date("Y-m-d H:i:s");

//xml
$today= date("Ymd");

if($cp_code == '19941'){
//쿠팡
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        $order_no = $data->sheets[0]['cells'][$i][17];
        $invoice_no = $data->sheets[0]['cells'][$i][19];
        $box_num = $data->sheets[0]['cells'][$i][25];
        $samjin_code = $data->sheets[0]['cells'][$i][24];
        $clgo_qty = $data->sheets[0]['cells'][$i][10];

        $inv_sql = "update b2b_order_form set clgo_qty= '$clgo_qty' ,  invoice_no = '{$invoice_no}' , invoice_up_dt = now() where order_no = '{$order_no}' and box_num = '{$box_num}' and samjin_code = '{$samjin_code}' and invoice_no is null and invoice_up_dt is null   ";
        sql_query($inv_sql);
        // $order_no = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][0]);
        // $receive_name =  $data->sheets[0]['cells'][$i][1];
        // $sabang_ord_no =  $data->sheets[0]['cells'][$i][17];
        // $invoice = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][19]);

        // $dpartner_add =  $data->sheets[0]['cells'][$i][16];
    }

}else {
    //이외 b2b 특판
    $invo_object = new stdClass();
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        $invocie_no = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][15]);
        $order_no = $data->sheets[0]['cells'][$i][3];

        $invo_object->$order_no .= $invocie_no . '\n';
        
    }

    foreach($invo_object as $order_no => $invoice){
        $in_sql = "update b2b_order_form set invoice_no = '{$invoice}' , invoice_up_dt = now() where order_no = '{$order_no}' and invoice_no is null and invoice_up_dt is null ";
        sql_query($in_sql);
    }

}


goto_url("./b2b_order_form.php?".$qstr);