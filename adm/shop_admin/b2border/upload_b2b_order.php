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

$cp_info = "SELECT * FROM b2b_company WHERE cp_code = '{$cp_code}' limit 1 ";
$cp = sql_fetch($cp_info);

if($cp_code == '19941'){
//쿠팡
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        
        $mall_code = '쿠팡로켓배송';
        $supply_cp = '쿠팡';
        $order_no = $data->sheets[0]['cells'][$i][1];
        $reg_date = $toDate;
        $order_qty = $data->sheets[0]['cells'][$i][9];
        $st_name = $data->sheets[0]['cells'][$i][6];

        $sku_id = $data->sheets[0]['cells'][$i][3];
        
        $receive_name = $data->sheets[0]['cells'][$i][6];
        
        $rec = "SELECT * FROM b2b_store_list WHERE cp_code = '{$cp_code}' AND st_name = '{$st_name}' LIMIT 1";
        $recv = sql_fetch($rec);
        
        $st_tel = $recv['receive_tel'];
        $receive_tel = $recv['st_tel'];
        $receive_zip = $recv['st_zip'];
        $receive_addr1 = $recv['st_addr1'];
        $receive_addr2 = $recv['st_addr2'];

        //삼진

        $samjin_modi_it_code = $data->sheets[0]['cells'][$i][5];

        $sap_code = substr($samjin_modi_it_code,0,12);
        $color = substr($samjin_modi_it_code,12,2);
        $size = substr($samjin_modi_it_code,14);
        if($size == 'L'){
            $size = '50*70';
        }

        $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
        $g5['connect_samjindb'] = $connect_db;
        $sqlSamjin = "SELECT * FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sap_code}' AND COLOR = '$color' AND HOCHING = '$size'";
        $rsSamjin = mssql_sql_query($sqlSamjin);

        for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
            $sm_samjin_name = $samrow['ITEM'];
            $sm_samjin_code = trim($samrow['ORDER_NO']);
            $sm_sap_code = trim($samrow['SAP_CODE']);
            $sm_samjin_wonga = $samrow['WONGA'];
            $sm_color = trim($samrow['COLOR']);
            $sm_size = trim($samrow['HOCHING']);
        }


        $order_price = $data->sheets[0]['cells'][$i][17];
        
        $dpart_type = '택배';
        $deliver_tpye = '신용';

        $ord_common = "";

        $ord_common .= "cp_name = '{$cp['cp_name']}', ";
        $ord_common .= "cp_code = '{$cp['cp_code']}', ";
        $ord_common .= "order_no = '{$order_no}', ";
        $ord_common .= "order_status = '출고요청', ";
        $ord_common .= "reg_date = '{$reg_date}', ";
        $ord_common .= "order_qty = '{$order_qty}', ";
        $ord_common .= "st_name = '{$st_name}', ";
        $ord_common .= "st_tel = '{$st_tel}', ";
        $ord_common .= "it_name = '{$sm_samjin_name}', ";
        $ord_common .= "receive_name = '{$receive_name}', ";
        $ord_common .= "receive_tel = '{$receive_tel}', ";
        $ord_common .= "receive_zip = '{$receive_zip}', ";
        $ord_common .= "receive_addr1 = '{$receive_addr1}', ";
        $ord_common .= "receive_addr2 = '{$receive_addr2}', ";
        
        $ord_common .= "samjin_it_name = '{$sm_samjin_name}', ";
        $ord_common .= "samjin_code = '{$sm_samjin_code}', ";
        
        $ord_common .= "sap_code = '{$sm_sap_code}', ";
        $ord_common .= "color = '{$sm_color}', ";
        $ord_common .= "size = '{$sm_size}', ";
        
        $ord_common .= "order_price = '{$order_price}', ";
        $ord_common .= "dpart_type = '{$dpart_type}', ";
        $ord_common .= "deliver_type = '{$deliver_tpye}', ";
        $ord_common .= "mall_code = '{$mall_code}', ";
        $ord_common .= "supply_cp = '{$supply_cp}', ";
        $ord_common .= "sku_id = '{$sku_id}' ";
        
        
        $in_sql = "insert into b2b_order set " . $ord_common;
    
        sql_query($in_sql);
        

    }

}else {
    //이외 b2b 특판

    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        $mall_code = $data->sheets[0]['cells'][$i][1];
        $supply_cp = $data->sheets[0]['cells'][$i][2];
        $order_no = $data->sheets[0]['cells'][$i][3];
        $reg_date = $data->sheets[0]['cells'][$i][4];
        $order_qty = $data->sheets[0]['cells'][$i][5];
        $st_name = $data->sheets[0]['cells'][$i][6];
        
        $receive_name = $data->sheets[0]['cells'][$i][7];
        $receive_tel = $data->sheets[0]['cells'][$i][8];
        $receive_zip = $data->sheets[0]['cells'][$i][9];
        $receive_addr1 = $data->sheets[0]['cells'][$i][10];
        $receive_addr2 = $data->sheets[0]['cells'][$i][11];

        $it_name = $data->sheets[0]['cells'][$i][12];
        $color = $data->sheets[0]['cells'][$i][13];
        $size = $data->sheets[0]['cells'][$i][14];
        $sap_code = $data->sheets[0]['cells'][$i][15];
        $normal_price = $data->sheets[0]['cells'][$i][16];
        $supply_price = $data->sheets[0]['cells'][$i][17];
        $dpart_type = $data->sheets[0]['cells'][$i][18];
        $deliver_tpye = $data->sheets[0]['cells'][$i][19];
        $order_memo = $data->sheets[0]['cells'][$i][20];

        //삼진

        $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
        $g5['connect_samjindb'] = $connect_db;
        $sqlSamjin = "SELECT ORDER_NO, SAP_CODE, ITEM , WONGA FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sap_code}' AND COLOR = '$color' AND HOCHING = '$size'";
        $rsSamjin = mssql_sql_query($sqlSamjin);

        for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
            $sm_samjin_name = $samrow['ITEM'];
            $sm_samjin_code = $samrow['ORDER_NO'];
            $sm_sap_code = $samrow['SAP_CODE'];
            $sm_samjin_wonga = $samrow['WONGA'];
        }

        $ord_common = "";

        $ord_common .= "cp_name = '{$cp['cp_name']}', ";
        $ord_common .= "cp_code = '{$cp['cp_code']}', ";
        $ord_common .= "order_no = '{$order_no}', ";
        $ord_common .= "order_status = '주문접수', ";
        $ord_common .= "reg_date = '{$reg_date}', ";
        $ord_common .= "order_qty = '{$order_qty}', ";
        $ord_common .= "st_name = '{$st_name}', ";
        
        $ord_common .= "it_name = '{$it_name}', ";
        $ord_common .= "receive_name = '{$receive_name}', ";
        $ord_common .= "receive_tel = '{$receive_tel}', ";
        $ord_common .= "receive_zip = '{$receive_zip}', ";
        $ord_common .= "receive_addr1 = '{$receive_addr1}', ";
        $ord_common .= "receive_addr2 = '{$receive_addr2}', ";
        
        $ord_common .= "samjin_it_name = '{$sm_samjin_name}', ";
        $ord_common .= "samjin_code = '{$sm_samjin_code}', ";
        
        $ord_common .= "sap_code = '{$sap_code}', ";
        $ord_common .= "color = '{$color}', ";
        $ord_common .= "size = '{$size}', ";
        $ord_common .= "normal_price = '$normal_price', ";
        $ord_common .= "supply_price = '$supply_price', ";
        $ord_common .= "order_price = '{$order_price}', ";
        $ord_common .= "dpart_type = '{$dpart_type}', ";
        $ord_common .= "deliver_type = '{$deliver_tpye}', ";
        $ord_common .= "mall_code = '{$mall_code}', ";
        $ord_common .= "supply_cp = '{$supply_cp}' ";
        
        
        $in_sql = "insert into b2b_order set " . $ord_common;
    
        sql_query($in_sql);



        
        
        // $sno = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][0]);
        // $receive_name =  $data->sheets[0]['cells'][$i][1];
        // $sabang_ord_no =  $data->sheets[0]['cells'][$i][17];
        // $invoice = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][19]);
    
        // $dpartner_add =  $data->sheets[0]['cells'][$i][16];
    }
}


goto_url("./b2b_order.php?".$qstr);