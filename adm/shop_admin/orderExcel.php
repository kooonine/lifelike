<?php
// $sub_menu = '92';
include_once('./_common.php');
include_once(G5_ADMIN_PATH.'/shop_admin/admin.shop.lib.php');

// auth_check($auth[substr($sub_menu,0,2)], "w");

// define("_ORDERMAIL_", true);

// $sms_count = 0;
// $sms_messages = array();

if($_FILES['excelfile']['tmp_name']) {

    // alert('test');
    // return
    $file = $_FILES['excelfile']['tmp_name'];
    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/



    /***
    *  Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read($file);

    /*
    

     $data->sheets[0]['numRows'] - count rows
     $data->sheets[0]['numCols'] - count columns
     $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

     $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

        $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
            if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
        $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
        $data->sheets[0]['cellsInfo'][$i][$j]['colspan']
        $data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */

    error_reporting(E_ALL ^ E_NOTICE);

    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;
    $fail_ct_id = array();
    // $i 사용시 ordermail.inc.php의 $i 때문에 무한루프에 빠짐
    for ($k = 2; $k <= $data->sheets[0]['numRows']; $k++) {
        // $it_id = addslashes(trim($data->sheets[0]['cells'][$k][1]));
        $od_id = addslashes(trim($data->sheets[0]['cells'][$k][1]));
        $ct_id = addslashes(trim($data->sheets[0]['cells'][$k][2]));
        $delivery_company = addslashes(trim($data->sheets[0]['cells'][$k][3]));
        $invoice = addslashes(trim($data->sheets[0]['cells'][$k][4]));
        $total_count++;
        $nowInvoiceTime =  G5_TIME_YMDHIS;

        $checkStatus = sql_fetch("SELECT cart.*, sorder.od_status FROM lt_shop_cart AS cart LEFT JOIN lt_shop_order AS sorder ON cart.od_id = sorder.od_id WHERE cart.od_id = '$od_id' AND cart.ct_id = '$ct_id' AND sorder.od_status IN ('배송중','상품준비중')");
        if (!$checkStatus) {
            $fail_count++;
            $fail_ct_id[] = $ct_id;
            continue;
        }

        $result = sql_query("update lt_shop_cart set ct_invoice_time = '$nowInvoiceTime', ct_delivery_company = '$delivery_company', ct_invoice = '$invoice', ct_status = '배송중' WHERE od_id = '$od_id' AND ct_id ='$ct_id'");
        if (!$result) {
            $fail_count++;
            $fail_ct_id[] = $ct_id;
            continue;
        }
        $result2 = sql_query("update lt_shop_order set od_status = '배송중' WHERE od_id = '$od_id'");
        if (!$result2) {
            $fail_count++;
            $fail_ct_id[] = $ct_id;
            continue;
        }

        $sql = "select * from lt_shop_cart where od_id = '$od_id' AND ct_id = '$ct_id'";
        $result3 = sql_query($sql);

        for ($i = 0; $row = sql_fetch_array($result3); $i++) {

            // 재고를 사용하지 않았다면
            $stock_use = $row['ct_stock_use'];
    
            if (!$row['ct_stock_use']) {
                // 재고에서 뺀다.
                subtract_io_stock($row['it_id'], $row['ct_qty'], $row['io_id'], $row['io_type']);
                $stock_use = 1;
    
                $sql = " update lt_shop_cart set ct_stock_use  = '$stock_use' where ct_id = '{$row['ct_id']}' ";
                sql_query($sql);
            }
        }


        $succ_count++;
    
        // alert('$it_id',$it_id);
        // if($it_id) {
        //     $total_count++;
        
        //     if(!$it_id || $it_commission == '') {
        //         $fail_count++;
        //         $fail_it_id[] = $it_id;
        //         continue;
        //     }
    
        //     // 상품정보
        //     $it = sql_fetch(" select * from lt_shop_item where it_id = '$it_id' ");
        //     if (!$it) {
        //         $fail_count++;
        //         $fail_it_id[] = $it_id;
        //         continue;
        //     }
        //     $result = sql_query("update lt_shop_item set it_commission = '$it_commission' where it_id = '$it_id' ");
        //     if (!$result) {
        //         $fail_count++;
        //         $fail_it_id[] = $it_id;
        //         continue;
        //     }
    
        //     $succ_count++;
        // }
    }
}

$msg = "엑셀 일괄변경 처리를 완료했습니다.";

$msg .= "\\n 총건수:".number_format($total_count);
$msg .= "\\n 완료건수:".number_format($succ_count);
$msg .= "\\n 실패건수:".number_format($fail_count);
if($fail_count > 0) {
    $msg .= "\\n 실패상품코드:".implode(', ', $fail_ct_id);
}

alert($msg);

?>