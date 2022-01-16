<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


$sql = "SELECT * FROM new_goods_db_overseas" ; 
$result = sql_query($sql);

for($i=0 ; $row = sql_fetch_array($result); $i++){
    $no = $row['no'];
    $sapCode12 = $row['samjin_code'];
    $color = $row['color'];
    //재고
    $dpart_stock = NM_GET_STOCK_WITH_SAP_CODE(1,0,$sapCode12,$color,null);
    $dpart_stock_s = 0; $dpart_stock_q = 0; $dpart_stock_k = 0;
    $ORDER_NO = '';
    if(count($dpart_stock) > 0){
        for($t=0 ;  $t< count($dpart_stock); $t++){
            $ORDER_NO = $dpart_stock[0]['ORDER_NO'];
            if($color == trim($dpart_stock[$t]['COLOR'])){
                if(trim($dpart_stock[$t]['HOCHING'])=='Q'){
                    if ($dpart_stock[$t]['C_NO'] == 2 || $dpart_stock[$t]['C_NO'] == 3 || $dpart_stock[$t]['C_NO'] == 4 || $dpart_stock[$t]['C_NO'] == 8) {
                        $dpart_stock_q += $dpart_stock[$t]['STOCK2'];
                    }
                }else if(trim($dpart_stock[$t]['HOCHING'])=='K'){
                    if ($dpart_stock[$t]['C_NO'] == 2 || $dpart_stock[$t]['C_NO'] == 3 || $dpart_stock[$t]['C_NO'] == 4 || $dpart_stock[$t]['C_NO'] == 8) {
                        $dpart_stock_k += $dpart_stock[$t]['STOCK2'];
                    }
                }else{
                    if ($dpart_stock[$t]['C_NO'] == 2 || $dpart_stock[$t]['C_NO'] == 3 || $dpart_stock[$t]['C_NO'] == 4 || $dpart_stock[$t]['C_NO'] == 8) {
                        $dpart_stock_s += $dpart_stock[$t]['STOCK2'];
                    }
                }
            }
        }
    }else{
    }

    $rep_stock = NM_REP_STOCK(1,2,$ORDER_NO,$ORDER_NO,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_stock_s = 0; $rep_stock_q = 0; $rep_stock_k = 0;
    if(count($rep_stock) > 0){
        for($j=0 ;  $j< count($rep_stock); $j++){
            if($color == trim($rep_stock[$j]['COLOR'])){
                if(trim($rep_stock[$j]['HOCHING'])=='Q'){                    
                    $rep_stock_q += $rep_stock[$j]['STOCK2'];
                }else if(trim($rep_stock[$j]['HOCHING'])=='K'){
                    $rep_stock_k += $rep_stock[$j]['STOCK2'];
                }else{
                    $rep_stock_s += $rep_stock[$j]['STOCK2'];
                }
            }
        }
    }else{
        
    }
    $total_stock_s = $dpart_stock_s + $rep_stock_s;
    $total_stock_q = $dpart_stock_q + $rep_stock_q;
    $total_stock_k = $dpart_stock_k + $rep_stock_k;

    //판매량
    $time = time();
    $today = date('Y-m-d');
    $toyear = date('Y');
    $day3m = date('Y-m-d' , strtotime("-3 month", $time));
    $day6m = date('Y-m-d' , strtotime("-6 month", $time));
    $day9m = date('Y-m-d' , strtotime("-9 month", $time));
    $day1ym = date('Y-m-d' , strtotime("-12 month", $time));
    $day1ym = date('Y-m-d' , strtotime("-12 month", $time));
    $yearsto1 =$toyear-1;
    $yearsto2 = $toyear-2;
    $yst1 = $yearsto1.'-01-01';
    $yet1 = $yearsto1.'-12-31';
    $yst2 = $yearsto2.'-01-01';
    $yet2 = $yearsto2.'-12-31';

    $rep_sale_sum3m_s = 0; $rep_sale_sum3m_q = 0; $rep_sale_sum3m_k = 0;
    $rep_sale_sum3p_s = 0; $rep_sale_sum3p_q = 0; $rep_sale_sum3p_k = 0;
    $rep_sale_sum6m_s = 0; $rep_sale_sum6m_q = 0; $rep_sale_sum6m_k = 0;
    $rep_sale_sum6p_s = 0; $rep_sale_sum6p_q = 0; $rep_sale_sum6p_k = 0;
    $rep_sale_sum1y_s = 0; $rep_sale_sum1y_q = 0; $rep_sale_sum1y_k = 0;
    $rep_sale_sum2y_s = 0; $rep_sale_sum2y_q = 0; $rep_sale_sum2y_k = 0;

    $rep_sale_sum3m = NM_REP_SALE_SUM($day3m,$today,2,$ORDER_NO,$ORDER_NO,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum3p = NM_REP_SALE_SUM($day1ym,$day9m,2,$ORDER_NO,$ORDER_NO,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum6m = NM_REP_SALE_SUM($day6m,$today,2,$ORDER_NO,$ORDER_NO,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum6p = NM_REP_SALE_SUM($day1ym,$day6m,2,$ORDER_NO,$ORDER_NO,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum1y = NM_REP_SALE_SUM($yst1,$yet1,2,$ORDER_NO,$ORDER_NO,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum2y = NM_REP_SALE_SUM($yst2,$yet2,2,$ORDER_NO,$ORDER_NO,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

    if(count($rep_sale_sum3m) > 0){
        for($a=0 ;  $a< count($rep_sale_sum3m); $a++){
            if($color == trim($rep_sale_sum3m[$a]['COLOR'])){
                if(trim($rep_sale_sum3m[$a]['HOCHING'])=='Q'){
                    $rep_sale_sum3m_q += $rep_sale_sum3m[$a]['QTY'];
                }else if(trim($rep_sale_sum3m[$a]['HOCHING'])=='K'){
                    $rep_sale_sum3m_k += $rep_sale_sum3m[$a]['QTY'];
                }else{
                    $rep_sale_sum3m_s += $rep_sale_sum3m[$a]['QTY'];
                }
            }
        }
    }
    if(count($rep_sale_sum3p) > 0){
        for($b=0 ;  $b< count($rep_sale_sum3p); $b++){
            if($color == trim($rep_sale_sum3p[$b]['COLOR'])){
                if(trim($rep_sale_sum3p[$b]['HOCHING'])=='Q'){
                    $rep_sale_sum3p_q += $rep_sale_sum3p[$b]['QTY'];
                }else if(trim($rep_sale_sum3p[$b]['HOCHING'])=='K'){
                    $rep_sale_sum3p_k += $rep_sale_sum3p[$b]['QTY'];
                }else{
                    $rep_sale_sum3p_s += $rep_sale_sum3p[$b]['QTY'];
                }
            }
        }
    }
    if(count($rep_sale_sum6m) > 0){
        for($c=0 ;  $c< count($rep_sale_sum6m); $c++){
            if($color == trim($rep_sale_sum6m[$c]['COLOR'])){
                if(trim($rep_sale_sum6m[$c]['HOCHING'])=='Q'){
                    $rep_sale_sum6m_q += $rep_sale_sum6m[$c]['QTY'];
                }else if(trim($rep_sale_sum6m[$c]['HOCHING'])=='K'){
                    $rep_sale_sum6m_k += $rep_sale_sum6m[$c]['QTY'];
                }else{
                    $rep_sale_sum6m_s += $rep_sale_sum6m[$c]['QTY'];
                }
            }
        }
    }
    if(count($rep_sale_sum6p) > 0){
        for($d=0 ;  $d< count($rep_sale_sum6p); $d++){
            if($color == trim($rep_sale_sum6p[$d]['COLOR'])){
                if(trim($rep_sale_sum6p[$d]['HOCHING'])=='Q'){
                    $rep_sale_sum6p_q += $rep_sale_sum6p[$d]['QTY'];
                }else if(trim($rep_sale_sum6p[$d]['HOCHING'])=='K'){
                    $rep_sale_sum6p_k += $rep_sale_sum6p[$d]['QTY'];
                }else{
                    $rep_sale_sum6p_s += $rep_sale_sum6p[$d]['QTY'];
                }
            }
        }
    }
    if(count($rep_sale_sum1y) > 0){
        for($e=0 ;  $e< count($rep_sale_sum1y); $e++){
            if($color == trim($rep_sale_sum1y[$e]['COLOR'])){
                if(trim($rep_sale_sum1y[$e]['HOCHING'])=='Q'){
                    $rep_sale_sum1y_q += $rep_sale_sum1y[$e]['QTY'];
                }else if(trim($rep_sale_sum1y[$e]['HOCHING'])=='K'){
                    $rep_sale_sum1y_k += $rep_sale_sum1y[$e]['QTY'];
                }else{
                    $rep_sale_sum1y_s += $rep_sale_sum1y[$e]['QTY'];
                }
            }
        }
    }
    if(count($rep_sale_sum2y) > 0){
        for($f=0 ;  $f< count($rep_sale_sum2y); $f++){
            if($color == trim($rep_sale_sum2y[$f]['COLOR'])){
                if(trim($rep_sale_sum2y[$f]['HOCHING'])=='Q'){
                    $rep_sale_sum2y_q += $rep_sale_sum2y[$f]['QTY'];
                }else if(trim($rep_sale_sum2y[$f]['HOCHING'])=='K'){
                    $rep_sale_sum2y_k += $rep_sale_sum2y[$f]['QTY'];
                }else{
                    $rep_sale_sum2y_s += $rep_sale_sum2y[$f]['QTY'];
                }
            }
        }
    }

    $common = "
    total_stock_s = '{$total_stock_s}'
    ,total_stock_q = '{$total_stock_q}'
    ,total_stock_k = '{$total_stock_k}'
    ,dpart_stock_s = '{$dpart_stock_s}'
    ,dpart_stock_q = '{$dpart_stock_q}'
    ,dpart_stock_k = '{$dpart_stock_k}'
    ,rep_stock_s = '{$rep_stock_s}'
    ,rep_stock_q = '{$rep_stock_q}'
    ,rep_stock_k = '{$rep_stock_k}'
    ,rep_sale_sum3m_s = '{$rep_sale_sum3m_s}'
    ,rep_sale_sum3m_q = '{$rep_sale_sum3m_q}'
    ,rep_sale_sum3m_k = '{$rep_sale_sum3m_k}'
    ,rep_sale_sum3p_s = '{$rep_sale_sum3p_s}'
    ,rep_sale_sum3p_q = '{$rep_sale_sum3p_q}'
    ,rep_sale_sum3p_k = '{$rep_sale_sum3p_k}'
    ,rep_sale_sum6m_s = '{$rep_sale_sum6m_s}'
    ,rep_sale_sum6m_q = '{$rep_sale_sum6m_q}'
    ,rep_sale_sum6m_k = '{$rep_sale_sum6m_k}'
    ,rep_sale_sum6p_s = '{$rep_sale_sum6p_s}'
    ,rep_sale_sum6p_q = '{$rep_sale_sum6p_q}'
    ,rep_sale_sum6p_k = '{$rep_sale_sum6p_k}'
    ,rep_sale_sum1y_s = '{$rep_sale_sum1y_s}'
    ,rep_sale_sum1y_q = '{$rep_sale_sum1y_q}'
    ,rep_sale_sum1y_k = '{$rep_sale_sum1y_k}'
    ,rep_sale_sum2y_s = '{$rep_sale_sum2y_s}'
    ,rep_sale_sum2y_q = '{$rep_sale_sum2y_q}'
    ,rep_sale_sum2y_k = '{$rep_sale_sum2y_k}'
    ";

    $upsql = "UPDATE new_goods_db_overseas SET $common WHERE no = '{$no}' ";
    sql_query($upsql);
}
