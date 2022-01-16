<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');



$file = $_FILES['excel']['tmp_name'];

$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('UTF-8');
$data->read($file);

$toDate = date("YmdHms");

for ($i = 4; $i <= $data->sheets[0]['numRows']; $i++) {
//  for ($i = 4; $i <= 4; $i++) {
    $orderId = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1]);
    $goods_nm = iconv("EUC-KR", "UTF-8", $data->sheets[0]['cells'][$i][2]);

    $BARCODE =strtoupper($data->sheets[0]['cells'][$i][7]);
    $ORDER_NO =strtoupper($data->sheets[0]['cells'][$i][4]);
    $COLOR = strtoupper($data->sheets[0]['cells'][$i][29]);
    $HOCHING = strtoupper($data->sheets[0]['cells'][$i][31]);

    $barcode_add = NM_ADD_BARCODE($BARCODE,$ORDER_NO,$COLOR,$HOCHING);

    $add_result_no = $barcode_add[0]['V1'];
    $add_result_meg = $barcode_add[0]['RSLT'];

    $merge_sql = "select count(*) AS cnt from sabang_goods_origin where sabang_goods_cd = '{$orderId}' ";
    $merge_item = sql_fetch($merge_sql);

    if($merge_item['cnt'] > 0 ){
        $upsql= "update sabang_goods_origin
                set regdate = '".$toDate."'
                    , barcode_no = '".$add_result_no."'
                    , barcode_meg = '".$add_result_meg."'
                    , sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
                    , goods_nm = '".$data->sheets[0]['cells'][$i][2]."'
                    , goods_keyword  = '".$data->sheets[0]['cells'][$i][3]."'
                    , model_nm = '".strtoupper($data->sheets[0]['cells'][$i][4])."'
                    , model_no = '".strtoupper($data->sheets[0]['cells'][$i][5])."'
                    , brand_nm = '".$data->sheets[0]['cells'][$i][6]."'
                    , compayny_goods_cd = '".strtoupper($data->sheets[0]['cells'][$i][7])."'
                    , goods_search = '".$data->sheets[0]['cells'][$i][8]."'
                    , goods_gubun = '".$data->sheets[0]['cells'][$i][9]."'
                    , class_cd1 = ''
                    , class_cd2 = ''
                    , class_cd3 = ''
                    , partner_id = '".$data->sheets[0]['cells'][$i][11]."'
                    , dpartner_id = '".$data->sheets[0]['cells'][$i][12]."'
                    , maker = '".$data->sheets[0]['cells'][$i][13]."'
                    , origin = '".$data->sheets[0]['cells'][$i][14]."'
                    , make_year = '".$data->sheets[0]['cells'][$i][15]."'
                    , make_dm = '".$data->sheets[0]['cells'][$i][16]."'
                    , goods_season = '".$data->sheets[0]['cells'][$i][17]."'
                    , sex = '".$data->sheets[0]['cells'][$i][18]."'
                    , status = '".$data->sheets[0]['cells'][$i][19]."'
                    , deliv_able_region = '".$data->sheets[0]['cells'][$i][20]."'
                    , tax_yn = '".$data->sheets[0]['cells'][$i][21]."'
                    , delv_type = '".$data->sheets[0]['cells'][$i][22]."'
                    , delv_cost = '".$data->sheets[0]['cells'][$i][23]."'
                    , goods_cost = '".$data->sheets[0]['cells'][$i][25]."'
                    , goods_price = '".$data->sheets[0]['cells'][$i][26]."'
                    , goods_consumer_price = '".$data->sheets[0]['cells'][$i][27]."'
                    , char_1_nm = '".$data->sheets[0]['cells'][$i][28]."'
                    , char_1_val = '".$data->sheets[0]['cells'][$i][29]."'
                    , char_2_nm = '".$data->sheets[0]['cells'][$i][30]."'
                    , char_2_val = '".$data->sheets[0]['cells'][$i][31]."'
                    , img_path = '".$data->sheets[0]['cells'][$i][32]."'
                    , img_path1 = '".$data->sheets[0]['cells'][$i][33]."'
                    , img_path2 = '".$data->sheets[0]['cells'][$i][34]."'
                    , img_path3 = '".$data->sheets[0]['cells'][$i][35]."'
                    , img_path4 = '".$data->sheets[0]['cells'][$i][36]."'
                    , img_path5 = '".$data->sheets[0]['cells'][$i][37]."'
                    , img_path6 = '".$data->sheets[0]['cells'][$i][38]."'
                    , img_path7 = '".$data->sheets[0]['cells'][$i][39]."'
                    , img_path8 = '".$data->sheets[0]['cells'][$i][40]."'
                    , img_path9 = '".$data->sheets[0]['cells'][$i][41]."'
                    , img_path10 = '".$data->sheets[0]['cells'][$i][42]."'
                    , img_path11 = '".$data->sheets[0]['cells'][$i][57]."'
                    , img_path12 = '".$data->sheets[0]['cells'][$i][59]."'
                    , img_path13 = '".$data->sheets[0]['cells'][$i][60]."'
                    , img_path14 = '".$data->sheets[0]['cells'][$i][63]."'
                    , img_path15 = '".$data->sheets[0]['cells'][$i][64]."'
                    , img_path16 = '".$data->sheets[0]['cells'][$i][65]."'
                    , img_path17 = '".$data->sheets[0]['cells'][$i][66]."'
                    , img_path18 = '".$data->sheets[0]['cells'][$i][67]."'
                    , img_path19 = '".$data->sheets[0]['cells'][$i][68]."'
                    , img_path20 = '".$data->sheets[0]['cells'][$i][69]."'
                    , img_path21 = '".$data->sheets[0]['cells'][$i][70]."'
                    , img_path22 = '".$data->sheets[0]['cells'][$i][71]."'
                    , img_path23 = ''
                    , img_path24 = ''
                    , goods_remarks = '".$data->sheets[0]['cells'][$i][43]."'
                    , certno = '".$data->sheets[0]['cells'][$i][45]."'
                    , avlst_dm = '".$data->sheets[0]['cells'][$i][46]."'
                    , avled_dm = '".$data->sheets[0]['cells'][$i][47]."'
                    , issuedate = '".$data->sheets[0]['cells'][$i][48]."'
                    , certdate = '".$data->sheets[0]['cells'][$i][49]."'
                    , cert_agency = '".$data->sheets[0]['cells'][$i][51]."'
                    , certfield = '".$data->sheets[0]['cells'][$i][51]."'
                    , stock_use_yn = '".$data->sheets[0]['cells'][$i][52]."'
                    , opt_type = '".$data->sheets[0]['cells'][$i][56]."'
                    , prop_edit_yn = ''
                    , importno = '".$data->sheets[0]['cells'][$i][79]."'
                    , prop1_cd = '".$data->sheets[0]['cells'][$i][81]."'
                    , prop_val1 = '".$data->sheets[0]['cells'][$i][82]."'
                    , prop_val2 = '".$data->sheets[0]['cells'][$i][83]."'
                    , prop_val3 = '".$data->sheets[0]['cells'][$i][84]."'
                    , prop_val4 = '".$data->sheets[0]['cells'][$i][85]."'
                    , prop_val5 = '".$data->sheets[0]['cells'][$i][86]."'
                    , prop_val6 = '".$data->sheets[0]['cells'][$i][87]."'
                    , prop_val7 = '".$data->sheets[0]['cells'][$i][88]."'
                    , prop_val8 = '".$data->sheets[0]['cells'][$i][89]."'
                    , prop_val9 = '".$data->sheets[0]['cells'][$i][90]."'
                    , prop_val10 = '".$data->sheets[0]['cells'][$i][91]."'
                    , prop_val11 = '".$data->sheets[0]['cells'][$i][92]."'
                    , prop_val12 = '".$data->sheets[0]['cells'][$i][93]."'
                    , prop_val13 = '".$data->sheets[0]['cells'][$i][94]."'
                    , prop_val14 = '".$data->sheets[0]['cells'][$i][95]."'
                    , prop_val15 = '".$data->sheets[0]['cells'][$i][96]."'
                    , prop_val16 = '".$data->sheets[0]['cells'][$i][97]."'
                    , prop_val17 = '".$data->sheets[0]['cells'][$i][98]."'
                    , prop_val18 = '".$data->sheets[0]['cells'][$i][99]."'
                    , prop_val19 = '".$data->sheets[0]['cells'][$i][100]."'
                    , prop_val20 = '".$data->sheets[0]['cells'][$i][101]."'
                    , prop_val21 = '".$data->sheets[0]['cells'][$i][102]."'
                    , prop_val22 = '".$data->sheets[0]['cells'][$i][103]."'
                    , prop_val23 = '".$data->sheets[0]['cells'][$i][104]."'
                    , prop_val24 = '".$data->sheets[0]['cells'][$i][105]."'
                    where sabang_goods_cd = '{$orderId}'
                    ";
    
        // $sql = " update sabang_goods_origin
        //         set regdate = '202006251541'
        //         , sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
        //         , goods_nm = '".iconv("EUC-KR", "UTF-8", $data->sheets[0]['cells'][$i][2]);."'
        //         , goods_keyword  = '".iconv("EUC-KR", "UTF-8", $data->sheets[0]['cells'][$i][3]);."'
        //         , model_nm = '".strtoupper($data->sheets[0]['cells'][$i][4])."'
        //         , model_no = '".strtoupper($data->sheets[0]['cells'][$i][5])."'
        //         where sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
        //         " ;
    
        sql_query($upsql);
        //트리거로 대체 sabang_goods_origin_after_update
        // $upsql_list= "update sabang_send_goods_list
        //         set reg_dt = '".$toDate."'
        //             , sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
        //             , goods_nm = '".$data->sheets[0]['cells'][$i][2]."'
        //             , goods_keyword  = '".$data->sheets[0]['cells'][$i][3]."'
        //             , model_nm = '".strtoupper($data->sheets[0]['cells'][$i][4])."'
        //             , model_no = '".strtoupper($data->sheets[0]['cells'][$i][5])."'
        //             , brand_nm = '".$data->sheets[0]['cells'][$i][6]."'
        //             , compayny_goods_cd = '".strtoupper($data->sheets[0]['cells'][$i][7])."'
        //             , goods_search = '".$data->sheets[0]['cells'][$i][8]."'
        //             , goods_gubun = '".$data->sheets[0]['cells'][$i][9]."'
        //             , class_cd1 = ''
        //             , class_cd2 = ''
        //             , class_cd3 = ''
        //             , partner_id = '".$data->sheets[0]['cells'][$i][11]."'
        //             , dpartner_id = '".$data->sheets[0]['cells'][$i][12]."'
        //             , maker = '".$data->sheets[0]['cells'][$i][13]."'
        //             , origin = '".$data->sheets[0]['cells'][$i][14]."'
        //             , make_year = '".$data->sheets[0]['cells'][$i][15]."'
        //             , make_dm = '".$data->sheets[0]['cells'][$i][16]."'
        //             , goods_season = '".$data->sheets[0]['cells'][$i][17]."'
        //             , sex = '".$data->sheets[0]['cells'][$i][18]."'
        //             , status = '".$data->sheets[0]['cells'][$i][19]."'
        //             , deliv_able_region = '".$data->sheets[0]['cells'][$i][20]."'
        //             , tax_yn = '".$data->sheets[0]['cells'][$i][21]."'
        //             , delv_type = '".$data->sheets[0]['cells'][$i][22]."'
        //             , delv_cost = '".$data->sheets[0]['cells'][$i][23]."'
        //             , goods_cost = '".$data->sheets[0]['cells'][$i][25]."'
        //             , goods_price = '".$data->sheets[0]['cells'][$i][26]."'
        //             , goods_consumer_price = '".$data->sheets[0]['cells'][$i][27]."'
        //             , char_1_nm = '".$data->sheets[0]['cells'][$i][28]."'
        //             , char_1_val = '".$data->sheets[0]['cells'][$i][29]."'
        //             , char_2_nm = '".$data->sheets[0]['cells'][$i][30]."'
        //             , char_2_val = '".$data->sheets[0]['cells'][$i][31]."'
        //             , img_path = '".$data->sheets[0]['cells'][$i][32]."'
        //             , img_path1 = '".$data->sheets[0]['cells'][$i][33]."'
        //             , img_path2 = '".$data->sheets[0]['cells'][$i][34]."'
        //             , img_path3 = '".$data->sheets[0]['cells'][$i][35]."'
        //             , img_path4 = '".$data->sheets[0]['cells'][$i][36]."'
        //             , img_path5 = '".$data->sheets[0]['cells'][$i][37]."'
        //             , img_path6 = '".$data->sheets[0]['cells'][$i][38]."'
        //             , img_path7 = '".$data->sheets[0]['cells'][$i][39]."'
        //             , img_path8 = '".$data->sheets[0]['cells'][$i][40]."'
        //             , img_path9 = '".$data->sheets[0]['cells'][$i][41]."'
        //             , img_path10 = '".$data->sheets[0]['cells'][$i][42]."'
        //             , img_path11 = '".$data->sheets[0]['cells'][$i][57]."'
        //             , img_path12 = '".$data->sheets[0]['cells'][$i][59]."'
        //             , img_path13 = '".$data->sheets[0]['cells'][$i][60]."'
        //             , img_path14 = '".$data->sheets[0]['cells'][$i][63]."'
        //             , img_path15 = '".$data->sheets[0]['cells'][$i][64]."'
        //             , img_path16 = '".$data->sheets[0]['cells'][$i][65]."'
        //             , img_path17 = '".$data->sheets[0]['cells'][$i][66]."'
        //             , img_path18 = '".$data->sheets[0]['cells'][$i][67]."'
        //             , img_path19 = '".$data->sheets[0]['cells'][$i][68]."'
        //             , img_path20 = '".$data->sheets[0]['cells'][$i][69]."'
        //             , img_path21 = '".$data->sheets[0]['cells'][$i][70]."'
        //             , img_path22 = '".$data->sheets[0]['cells'][$i][71]."'
        //             , img_path23 = ''
        //             , img_path24 = ''
        //             , goods_remarks = '".$data->sheets[0]['cells'][$i][43]."'
        //             , certno = '".$data->sheets[0]['cells'][$i][45]."'
        //             , avlst_dm = '".$data->sheets[0]['cells'][$i][46]."'
        //             , avled_dm = '".$data->sheets[0]['cells'][$i][47]."'
        //             , issuedate = '".$data->sheets[0]['cells'][$i][48]."'
        //             , certdate = '".$data->sheets[0]['cells'][$i][49]."'
        //             , cert_agency = '".$data->sheets[0]['cells'][$i][51]."'
        //             , certfield = '".$data->sheets[0]['cells'][$i][51]."'
        //             , stock_use_yn = '".$data->sheets[0]['cells'][$i][52]."'
        //             , opt_type = '".$data->sheets[0]['cells'][$i][56]."'
        //             , prop_edit_yn = ''
        //             , importno = '".$data->sheets[0]['cells'][$i][79]."'
        //             , prop1_cd = '".$data->sheets[0]['cells'][$i][81]."'
        //             , prop_val1 = '".$data->sheets[0]['cells'][$i][82]."'
        //             , prop_val2 = '".$data->sheets[0]['cells'][$i][83]."'
        //             , prop_val3 = '".$data->sheets[0]['cells'][$i][84]."'
        //             , prop_val4 = '".$data->sheets[0]['cells'][$i][85]."'
        //             , prop_val5 = '".$data->sheets[0]['cells'][$i][86]."'
        //             , prop_val6 = '".$data->sheets[0]['cells'][$i][87]."'
        //             , prop_val7 = '".$data->sheets[0]['cells'][$i][88]."'
        //             , prop_val8 = '".$data->sheets[0]['cells'][$i][89]."'
        //             , prop_val9 = '".$data->sheets[0]['cells'][$i][90]."'
        //             , prop_val10 = '".$data->sheets[0]['cells'][$i][91]."'
        //             , prop_val11 = '".$data->sheets[0]['cells'][$i][92]."'
        //             , prop_val12 = '".$data->sheets[0]['cells'][$i][93]."'
        //             , prop_val13 = '".$data->sheets[0]['cells'][$i][94]."'
        //             , prop_val14 = '".$data->sheets[0]['cells'][$i][95]."'
        //             , prop_val15 = '".$data->sheets[0]['cells'][$i][96]."'
        //             , prop_val16 = '".$data->sheets[0]['cells'][$i][97]."'
        //             , prop_val17 = '".$data->sheets[0]['cells'][$i][98]."'
        //             , prop_val18 = '".$data->sheets[0]['cells'][$i][99]."'
        //             , prop_val19 = '".$data->sheets[0]['cells'][$i][100]."'
        //             , prop_val20 = '".$data->sheets[0]['cells'][$i][101]."'
        //             , prop_val21 = '".$data->sheets[0]['cells'][$i][102]."'
        //             , prop_val22 = '".$data->sheets[0]['cells'][$i][103]."'
        //             , prop_val23 = '".$data->sheets[0]['cells'][$i][104]."'
        //             , prop_val24 = '".$data->sheets[0]['cells'][$i][105]."'
        //             where sabang_goods_cd = '{$orderId}'
        //             ";
        // sql_query($upsql_list);

    }else{

        $sql= "insert into sabang_goods_origin
                    set regdate = '".$toDate."'
                    , barcode_no = '".$add_result_no."'
                    , barcode_meg = '".$add_result_meg."'
                    , sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
                    , goods_nm = '".$data->sheets[0]['cells'][$i][2]."'
                    , goods_keyword  = '".$data->sheets[0]['cells'][$i][3]."'
                    , model_nm = '".strtoupper($data->sheets[0]['cells'][$i][4])."'
                    , model_no = '".strtoupper($data->sheets[0]['cells'][$i][5])."'
                    , brand_nm = '".$data->sheets[0]['cells'][$i][6]."'
                    , compayny_goods_cd = '".strtoupper($data->sheets[0]['cells'][$i][7])."'
                    , goods_search = '".$data->sheets[0]['cells'][$i][8]."'
                    , goods_gubun = '".$data->sheets[0]['cells'][$i][9]."'
                    , class_cd1 = ''
                    , class_cd2 = ''
                    , class_cd3 = ''
                    , partner_id = '".$data->sheets[0]['cells'][$i][11]."'
                    , dpartner_id = '".$data->sheets[0]['cells'][$i][12]."'
                    , maker = '".$data->sheets[0]['cells'][$i][13]."'
                    , origin = '".$data->sheets[0]['cells'][$i][14]."'
                    , make_year = '".$data->sheets[0]['cells'][$i][15]."'
                    , make_dm = '".$data->sheets[0]['cells'][$i][16]."'
                    , goods_season = '".$data->sheets[0]['cells'][$i][17]."'
                    , sex = '".$data->sheets[0]['cells'][$i][18]."'
                    , status = '".$data->sheets[0]['cells'][$i][19]."'
                    , deliv_able_region = '".$data->sheets[0]['cells'][$i][20]."'
                    , tax_yn = '".$data->sheets[0]['cells'][$i][21]."'
                    , delv_type = '".$data->sheets[0]['cells'][$i][22]."'
                    , delv_cost = '".$data->sheets[0]['cells'][$i][23]."'
                    , goods_cost = '".$data->sheets[0]['cells'][$i][25]."'
                    , goods_price = '".$data->sheets[0]['cells'][$i][26]."'
                    , goods_consumer_price = '".$data->sheets[0]['cells'][$i][27]."'
                    , char_1_nm = '".$data->sheets[0]['cells'][$i][28]."'
                    , char_1_val = '".$data->sheets[0]['cells'][$i][29]."'
                    , char_2_nm = '".$data->sheets[0]['cells'][$i][30]."'
                    , char_2_val = '".$data->sheets[0]['cells'][$i][31]."'
                    , img_path = '".$data->sheets[0]['cells'][$i][32]."'
                    , img_path1 = '".$data->sheets[0]['cells'][$i][33]."'
                    , img_path2 = '".$data->sheets[0]['cells'][$i][34]."'
                    , img_path3 = '".$data->sheets[0]['cells'][$i][35]."'
                    , img_path4 = '".$data->sheets[0]['cells'][$i][36]."'
                    , img_path5 = '".$data->sheets[0]['cells'][$i][37]."'
                    , img_path6 = '".$data->sheets[0]['cells'][$i][38]."'
                    , img_path7 = '".$data->sheets[0]['cells'][$i][39]."'
                    , img_path8 = '".$data->sheets[0]['cells'][$i][40]."'
                    , img_path9 = '".$data->sheets[0]['cells'][$i][41]."'
                    , img_path10 = '".$data->sheets[0]['cells'][$i][42]."'
                    , img_path11 = '".$data->sheets[0]['cells'][$i][57]."'
                    , img_path12 = '".$data->sheets[0]['cells'][$i][59]."'
                    , img_path13 = '".$data->sheets[0]['cells'][$i][60]."'
                    , img_path14 = '".$data->sheets[0]['cells'][$i][63]."'
                    , img_path15 = '".$data->sheets[0]['cells'][$i][64]."'
                    , img_path16 = '".$data->sheets[0]['cells'][$i][65]."'
                    , img_path17 = '".$data->sheets[0]['cells'][$i][66]."'
                    , img_path18 = '".$data->sheets[0]['cells'][$i][67]."'
                    , img_path19 = '".$data->sheets[0]['cells'][$i][68]."'
                    , img_path20 = '".$data->sheets[0]['cells'][$i][69]."'
                    , img_path21 = '".$data->sheets[0]['cells'][$i][70]."'
                    , img_path22 = '".$data->sheets[0]['cells'][$i][71]."'
                    , img_path23 = ''
                    , img_path24 = ''
                    , goods_remarks = '".$data->sheets[0]['cells'][$i][43]."'
                    , certno = '".$data->sheets[0]['cells'][$i][45]."'
                    , avlst_dm = '".$data->sheets[0]['cells'][$i][46]."'
                    , avled_dm = '".$data->sheets[0]['cells'][$i][47]."'
                    , issuedate = '".$data->sheets[0]['cells'][$i][48]."'
                    , certdate = '".$data->sheets[0]['cells'][$i][49]."'
                    , cert_agency = '".$data->sheets[0]['cells'][$i][51]."'
                    , certfield = '".$data->sheets[0]['cells'][$i][51]."'
                    , stock_use_yn = '".$data->sheets[0]['cells'][$i][52]."'
                    , opt_type = '".$data->sheets[0]['cells'][$i][56]."'
                    , prop_edit_yn = ''
                    , importno = '".$data->sheets[0]['cells'][$i][79]."'
                    , prop1_cd = '".$data->sheets[0]['cells'][$i][81]."'
                    , prop_val1 = '".$data->sheets[0]['cells'][$i][82]."'
                    , prop_val2 = '".$data->sheets[0]['cells'][$i][83]."'
                    , prop_val3 = '".$data->sheets[0]['cells'][$i][84]."'
                    , prop_val4 = '".$data->sheets[0]['cells'][$i][85]."'
                    , prop_val5 = '".$data->sheets[0]['cells'][$i][86]."'
                    , prop_val6 = '".$data->sheets[0]['cells'][$i][87]."'
                    , prop_val7 = '".$data->sheets[0]['cells'][$i][88]."'
                    , prop_val8 = '".$data->sheets[0]['cells'][$i][89]."'
                    , prop_val9 = '".$data->sheets[0]['cells'][$i][90]."'
                    , prop_val10 = '".$data->sheets[0]['cells'][$i][91]."'
                    , prop_val11 = '".$data->sheets[0]['cells'][$i][92]."'
                    , prop_val12 = '".$data->sheets[0]['cells'][$i][93]."'
                    , prop_val13 = '".$data->sheets[0]['cells'][$i][94]."'
                    , prop_val14 = '".$data->sheets[0]['cells'][$i][95]."'
                    , prop_val15 = '".$data->sheets[0]['cells'][$i][96]."'
                    , prop_val16 = '".$data->sheets[0]['cells'][$i][97]."'
                    , prop_val17 = '".$data->sheets[0]['cells'][$i][98]."'
                    , prop_val18 = '".$data->sheets[0]['cells'][$i][99]."'
                    , prop_val19 = '".$data->sheets[0]['cells'][$i][100]."'
                    , prop_val20 = '".$data->sheets[0]['cells'][$i][101]."'
                    , prop_val21 = '".$data->sheets[0]['cells'][$i][102]."'
                    , prop_val22 = '".$data->sheets[0]['cells'][$i][103]."'
                    , prop_val23 = '".$data->sheets[0]['cells'][$i][104]."'
                    , prop_val24 = '".$data->sheets[0]['cells'][$i][105]."'
    
                    ";
    
        // $sql = " update sabang_goods_origin
        //         set regdate = '202006251541'
        //         , sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
        //         , goods_nm = '".iconv("EUC-KR", "UTF-8", $data->sheets[0]['cells'][$i][2]);."'
        //         , goods_keyword  = '".iconv("EUC-KR", "UTF-8", $data->sheets[0]['cells'][$i][3]);."'
        //         , model_nm = '".strtoupper($data->sheets[0]['cells'][$i][4])."'
        //         , model_no = '".strtoupper($data->sheets[0]['cells'][$i][5])."'
        //         where sabang_goods_cd = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][1])."'
        //         " ;
    
        sql_query($sql);
    }

    
}



?>

<?=$orderId?>
<?=$goods_nm?>


