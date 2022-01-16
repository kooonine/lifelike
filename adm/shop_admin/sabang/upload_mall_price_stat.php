<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/PHPExcel.php');
include_once(G5_LIB_PATH . '/Excel/reader.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');

ini_set('memory_limit', '256M');



$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("?" , $referer);


$qstr=$cut_url[1];

$etc_mall_id = $etc_mall_id;
if($etc_mall_id == '19963'){
    $mall_name = '굿닷컴';
}else if ($etc_mall_id == '19950'){
    $mall_name = 'SSF SHOP';
}


$file = $_FILES['upload_excel']['tmp_name'];

$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('UTF-8');
$data->read($file);

$toDate = date("YmdHis");


for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
    if($etc_mall_id == '19963'){
        $option_id = preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][10]);
        $sku_value =  $data->sheets[0]['cells'][$i][14] ; 
    
    
        preg_match_all("/[^() || \-\ \/\,]+/", $sku_value,$c);
        foreach($c[0] as $a) {
            if (strlen($a) > 14) {
                if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
                    $prod_code = $a;
                }
            }
        }
    
        $merge_sql = "select count(*) AS cnt from samjin_sale_reg_mall_goods_list where option_id = '{$option_id}' and mall_id = '{$etc_mall_id}' ";
        $merge_item = sql_fetch($merge_sql);
    
        if($merge_item['cnt'] > 0 ){
            $upsql= "update samjin_sale_reg_mall_goods_list
                    set up_date = '".$toDate."'
                        ,status = '".$data->sheets[0]['cells'][$i][7]."'
                        ,mall_id = '".$etc_mall_id."'
                        ,mall_name = '".$mall_name."'
                        ,goods_name = '".$data->sheets[0]['cells'][$i][4]."'
                        ,order_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][15])."'
                        ,sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][16])."'
                        ,prod_name = '".$data->sheets[0]['cells'][$i][14]."'
                        ,prod_code = '".$prod_code."'
                        ,brand_id = '".$data->sheets[0]['cells'][$i][5]."'
                        ,brand_name = '".$data->sheets[0]['cells'][$i][6]."'
                        ,category = '".$data->sheets[0]['cells'][$i][8]."'
                        ,fee = '".$data->sheets[0]['cells'][$i][9]."'
                        ,goods_id = '".$data->sheets[0]['cells'][$i][3]."'
                        ,option_id = '".$data->sheets[0]['cells'][$i][10]."'
                        ,option_id_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][11])."'
                        ,option_id_sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][12])."'
                        ,sap_code = '".$data->sheets[0]['cells'][$i][13]."'
                        ,sale_rate = '".$data->sheets[0]['cells'][$i][17]."'
                        ,discount = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][18])."'
                        ,prod_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][19])."'
                        ,saler = '".$data->sheets[0]['cells'][$i][2]."'
                        ,qty = '".$data->sheets[0]['cells'][$i][20]."'
                        ,stock = '".$data->sheets[0]['cells'][$i][21]."'
                        ,uniform_yn = '".$data->sheets[0]['cells'][$i][22]."'
    
                        where option_id = '{$option_id}' and mall_id = '{$etc_mall_id}'
                        ";
        
            sql_query($upsql);
        }else{
            $sql= "insert into samjin_sale_reg_mall_goods_list
                        set reg_date = '".$toDate."'
                        ,status = '".$data->sheets[0]['cells'][$i][7]."'
                        ,mall_id = '".$etc_mall_id."'
                        ,mall_name = '".$mall_name."'
                        ,goods_name = '".$data->sheets[0]['cells'][$i][4]."'
                        ,order_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][15])."'
                        ,sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][16])."'
                        ,prod_name = '".$data->sheets[0]['cells'][$i][14]."'
                        ,prod_code = '".$prod_code."'
                        ,brand_id = '".$data->sheets[0]['cells'][$i][5]."'
                        ,brand_name = '".$data->sheets[0]['cells'][$i][6]."'
                        ,category = '".$data->sheets[0]['cells'][$i][8]."'
                        ,fee = '".$data->sheets[0]['cells'][$i][9]."'
                        ,goods_id = '".$data->sheets[0]['cells'][$i][3]."'
                        ,option_id = '".$data->sheets[0]['cells'][$i][10]."'
                        ,option_id_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][11])."'
                        ,option_id_sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][12])."'
                        ,sap_code = '".$data->sheets[0]['cells'][$i][13]."'
                        ,sale_rate = '".$data->sheets[0]['cells'][$i][17]."'
                        ,discount = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][18])."'
                        ,prod_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][19])."'
                        ,saler = '".$data->sheets[0]['cells'][$i][2]."'
                        ,qty = '".$data->sheets[0]['cells'][$i][20]."'
                        ,stock = '".$data->sheets[0]['cells'][$i][21]."'
                        ,uniform_yn = '".$data->sheets[0]['cells'][$i][22]."'
        
                        ";
            
        
            sql_query($sql);
    
        }
    }else if($etc_mall_id == '19950'){
        //승인 
        $chk = trim($data->sheets[0]['cells'][$i][1]);
        if($chk == '승인완료'){
            $option_id =  $data->sheets[0]['cells'][$i][2];
            $sku_value =  substr($data->sheets[0]['cells'][$i][3] , 3); 
        
        
            preg_match_all("/[^() || \_\-\ \/\,]+/", $sku_value,$c);
            foreach($c[0] as $a) {
                if (strlen($a) > 14) {
                    if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
                        $prod_code = $a;
                    }
                }
            }
        
            $merge_sql = "select count(*) AS cnt from samjin_sale_reg_mall_goods_list where option_id = '{$option_id}' and mall_id = '{$etc_mall_id}' ";
            $merge_item = sql_fetch($merge_sql);
        
            if($merge_item['cnt'] > 0 ){
                $upsql= "update samjin_sale_reg_mall_goods_list
                        set up_date = '".$toDate."'
                            ,status = '".$data->sheets[0]['cells'][$i][11]."'
                            ,mall_id = '".$etc_mall_id."'
                            ,mall_name = '".$mall_name."'
                            ,goods_name = '".$data->sheets[0]['cells'][$i][4]."'
                            ,order_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][6])."'
                            ,sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][7])."'
                            ,prod_name = '".$data->sheets[0]['cells'][$i][3]."'
                            ,prod_code = '".$prod_code."'
                            ,brand_id = ''
                            ,brand_name = '".$data->sheets[0]['cells'][$i][5]."'
                            ,category = ''
                            ,fee = '".$data->sheets[0]['cells'][$i][8]."'
                            ,goods_id = '".$data->sheets[0]['cells'][$i][2]."'
                            ,option_id = '".$data->sheets[0]['cells'][$i][2]."'
                            ,option_id_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][6])."'
                            ,option_id_sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][7])."'
                            ,sap_code = ''
                            ,sale_rate = '".$data->sheets[0]['cells'][$i][9]."'
                            ,discount = ''
                            ,prod_price = ''
                            ,saler = '리탠다드(주)'
                            ,qty = ''
                            ,stock = ''
                            ,uniform_yn = ''
        
                            where option_id = '{$option_id}' and mall_id = '{$etc_mall_id}'
                            ";
            
                sql_query($upsql);
            }else{
                $sql= "insert into samjin_sale_reg_mall_goods_list
                            set reg_date = '".$toDate."'
                            ,status = '".$data->sheets[0]['cells'][$i][11]."'
                            ,mall_id = '".$etc_mall_id."'
                            ,mall_name = '".$mall_name."'
                            ,goods_name = '".$data->sheets[0]['cells'][$i][4]."'
                            ,order_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][6])."'
                            ,sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][7])."'
                            ,prod_name = '".$data->sheets[0]['cells'][$i][3]."'
                            ,prod_code = '".$prod_code."'
                            ,brand_id = ''
                            ,brand_name = '".$data->sheets[0]['cells'][$i][5]."'
                            ,category = ''
                            ,fee = '".$data->sheets[0]['cells'][$i][8]."'
                            ,goods_id = '".$data->sheets[0]['cells'][$i][2]."'
                            ,option_id = '".$data->sheets[0]['cells'][$i][2]."'
                            ,option_id_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][6])."'
                            ,option_id_sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][7])."'
                            ,sap_code = ''
                            ,sale_rate = '".$data->sheets[0]['cells'][$i][9]."'
                            ,discount = ''
                            ,prod_price = ''
                            ,saler = '리탠다드(주)'
                            ,qty = ''
                            ,stock = ''
                            ,uniform_yn = ''
            
                            ";
                
            
                sql_query($sql);
            }
        }

    }else if($etc_mall_id == '19944'){
        //승인 
        $chk = trim($data->sheets[0]['cells'][$i][1]);
        if($chk == '승인완료'){
            $option_id =  $data->sheets[0]['cells'][$i][2];
            $sku_value =  substr($data->sheets[0]['cells'][$i][2] , 3); 
        
        
            preg_match_all("/[^() || \_\-\ \/\,]+/", $sku_value,$c);
            foreach($c[0] as $a) {
                if (strlen($a) > 14) {
                    if(substr($a, 0, 1)=='M' || substr($a, 0, 1)=='Z'){
                        $prod_code = $a;
                    }
                }
            }
        
            $merge_sql = "select count(*) AS cnt from samjin_sale_reg_mall_goods_list where option_id = '{$option_id}' and mall_id = '{$etc_mall_id}' ";
            $merge_item = sql_fetch($merge_sql);
        
            if($merge_item['cnt'] > 0 ){
                $upsql= "update samjin_sale_reg_mall_goods_list
                        set up_date = '".$toDate."'
                            ,status = '".$data->sheets[0]['cells'][$i][12]."'
                            ,mall_id = '".$etc_mall_id."'
                            ,mall_name = '".$mall_name."'
                            ,goods_name = '".$data->sheets[0]['cells'][$i][3]."'
                            ,order_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][5])."'
                            ,sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][6])."'
                            ,prod_name = '".$data->sheets[0]['cells'][$i][3]."'
                            ,prod_code = '".$prod_code."'
                            ,brand_id = ''
                            ,brand_name = '".$data->sheets[0]['cells'][$i][4]."'
                            ,category = ''
                            ,fee = '".$data->sheets[0]['cells'][$i][7]."'
                            ,goods_id = '".$data->sheets[0]['cells'][$i][2]."'
                            ,option_id = '".$data->sheets[0]['cells'][$i][2]."'
                            ,option_id_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][5])."'
                            ,option_id_sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][6])."'
                            ,sap_code = ''
                            ,sale_rate = ''
                            ,discount = ''
                            ,prod_price = ''
                            ,saler = '리탠다드(주)'
                            ,qty = ''
                            ,stock = ''
                            ,uniform_yn = ''
        
                            where option_id = '{$option_id}' and mall_id = '{$etc_mall_id}'
                            ";
            
                sql_query($upsql);
            }else{
                $sql= "insert into samjin_sale_reg_mall_goods_list
                            set reg_date = '".$toDate."'
                            ,status = '".$data->sheets[0]['cells'][$i][12]."'
                            ,mall_id = '".$etc_mall_id."'
                            ,mall_name = '".$mall_name."'
                            ,goods_name = '".$data->sheets[0]['cells'][$i][3]."'
                            ,order_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][5])."'
                            ,sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][6])."'
                            ,prod_name = '".$data->sheets[0]['cells'][$i][3]."'
                            ,prod_code = '".$prod_code."'
                            ,brand_id = ''
                            ,brand_name = '".$data->sheets[0]['cells'][$i][4]."'
                            ,category = ''
                            ,fee = '".$data->sheets[0]['cells'][$i][7]."'
                            ,goods_id = '".$data->sheets[0]['cells'][$i][2]."'
                            ,option_id = '".$data->sheets[0]['cells'][$i][2]."'
                            ,option_id_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][5])."'
                            ,option_id_sale_price = '".preg_replace("/[^0-9]/", "", $data->sheets[0]['cells'][$i][6])."'
                            ,sap_code = ''
                            ,sale_rate = ''
                            ,discount = ''
                            ,prod_price = ''
                            ,saler = '리탠다드(주)'
                            ,qty = ''
                            ,stock = ''
                            ,uniform_yn = ''
            
                            ";
                
            
                sql_query($sql);
            }
        }

    }
    

}
goto_url("./mall_price_stat.php?".$qstr);

?>