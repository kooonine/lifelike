<?php
//$sub_menu = '100310';
$sub_menu = '93';
include_once('./_common.php');

// var_dump($_FILES);
// dd($_POST);

include_once(G5_LIB_PATH . '/samjin.lib.php');

$referer = $_SERVER["HTTP_REFERER"];

$cut_url = explode("qstr=" , $referer);

$result= null;


$qstr=$cut_url[1];

$ps_id = $_POST['ps_id'];

$item_type = $_POST['item_type'];

$chk_sql = "SELECT count(*) AS cnt FROM new_goods_db_cover WHERE ps_id = '{$ps_id}' AND item_type = '{$item_type}' " ; 
$chk_rew = sql_fetch($chk_sql);




if($chk_rew['cnt'] < 1){
    //리스트
    $ps_select = "SELECT * FROM lt_prod_schedule WHERE ps_id = '{$ps_id}' limit 1";
    $ps_select_res = sql_fetch($ps_select);
    
    //제품기획서
    $ip_sql = "SELECT * FROM lt_item_proposal WHERE ip_it_name = '{$ps_select_res['ps_it_name']}' ORDER BY ip_id ASC LIMIT 1";
    $ip_sql_res = sql_fetch($ip_sql);
    //작업지시서
    $jo_sql = "SELECT * FROM lt_job_order WHERE jo_it_name = '{$ps_select_res['ps_it_name']}' ORDER BY jo_id ASC LIMIT 1" ; 
    $jo_sql_res = sql_fetch($jo_sql);
    
    if(!empty($ps_select_res['ps_id'])){
        if(!empty($ps_select_res['ps_code_gubun'])){
            switch($ps_select_res['ps_code_gubun']){
                case 'MW' :
                    $channal = "오프라인";
                break;
                case 'MA' :
                    $channal = "오프라인";
                break;
                case 'MD' :
                    $channal = "온라인";
                break;
                case 'MS' :
                    $channal = "온라인";
                break;
                case 'MO' :
                    $channal = "온라인";
                break;
                case 'MX' :
                    $channal = "온라인";
                break;
            }
        }
        if(!empty($ps_select_res['ps_code_item_type'])){
            switch($ps_select_res['ps_code_item_type']){
                case 'C' :
                    $item_1 = "커버";
                break;
                case 'S' :
                    $item_1 = "속통";
                break;
                case 'A' :
                    $item_1 = "기타";
                break;
            }
        }
        switch($ps_select_res['ps_code_season']) {
            case 'S' :            $item_season = 'SS';            break;
            case 'H' :            $item_season = 'HS';            break;
            case 'F' :            $item_season = 'FW';            break;
            case 'A' :            $item_season = 'AA';            break;
        }
        $samjin_code = $ps_select_res['ps_code_gubun'].$ps_select_res['ps_code_brand'].$ps_select_res['ps_code_year'].$ps_select_res['ps_code_season'].$ps_select_res['ps_code_item_type'].$ps_select_res['ps_code_index'].$ps_select_res['ps_code_item_name'];
        $item_name = $ps_select_res['ps_it_name'].$ps_select_res['ps_prod_name'];
        $season = $ps_select_res['ps_code_year'].$item_season;
    }
    
    if(!empty($jo_sql_res['jo_id'])){
        
        switch($jo_sql_res['jo_color']){
            case 'AA' : $color_nm = "AA(기타)"; break;
            case 'BE' : $color_nm = "BE(베이지)"; break;
            case 'BK' : $color_nm = "BK(블랙)"; break;
            case 'BL' : $color_nm = "BL(블루)"; break;
            case 'BR' : $color_nm = "BR(브라운)"; break;
            case 'CR' : $color_nm = "CR(크림)"; break;
            case 'DB' : $color_nm = "DB(진블루)"; break;
            case 'DP' : $color_nm = "DP(진핑크)"; break;
            case 'FC' : $color_nm = "FC(푸시아)"; break;
            case 'GD' : $color_nm = "GD(골드)"; break;
            case 'GN' : $color_nm = "GN(그린)"; break;
            case 'GR' : $color_nm = "GR(그레이)"; break;
            case 'IV' : $color_nm = "IV(아이보리)"; break;
            case 'KA' : $color_nm = "KA(카키)"; break;
            case 'LB' : $color_nm = "LB(연블루)"; break;
            case 'LG' : $color_nm = "LG(연그레이)"; break;
            case 'LP' : $color_nm = "LP(연핑크)"; break;
            case 'LV' : $color_nm = "LV(라벤다)"; break;
            case 'MT' : $color_nm = "MT(민트)"; break;
            case 'MU' : $color_nm = "MU(멀티)"; break;
            case 'MV' : $color_nm = "MV(모브)"; break;
            case 'MX' : $color_nm = "MX(혼합)"; break;
            case 'NC' : $color_nm = "NC(내츄럴)"; break;
            case 'NV' : $color_nm = "NV(네이비)"; break;
            case 'OR' : $color_nm = "OR(오렌지)"; break;
            case 'PC' : $color_nm = "PC(청록)"; break;
            case 'PK' : $color_nm = "PK(핑크)"; break;
            case 'PU' : $color_nm = "PU(퍼플)"; break;
            case 'RD' : $color_nm = "RD(레드)"; break;
            case 'WH' : $color_nm = "WH(화이트)"; break;
            case 'YE' : $color_nm = "YE(노랑)"; break;
            case 'DG' : $color_nm = "DG(딥그레이)"; break;
            case 'CO' : $color_nm = "CO(코랄)"; break;
        }
    
        $jo_mater_name = array();
        if (!empty($jo_sql_res['jo_mater_name'])) {
            $jo_mater_name = json_decode($jo_sql_res['jo_mater_name'], true);
        }
    }
    
    if(!empty($ip_sql_res['ip_id'])){
        $maker_country = $ip_sql_res['ip_maker_country'];
        $ip_id = $ip_sql_res['id_id'];
    }else {
        $maker_country ="";
        $ip_id = '';
    }

    if($ps_select_res['ps_code_gubun'] == 'MW' || $ps_select_res['ps_code_gubun'] == 'MD'){
        if(!empty($ip_sql_res['ip_maker_country'])){
        }else{
            $result = "202";
            echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            return false;
        }
        foreach($jo_mater_name as $jmn => $mater_name){
            if($jmn == 0){
                if(!empty($mater_name['mater'])){
                }else{
                    $result = "201";
                    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                    return false;
                }
            }
        }
    }

    $sapCode12 = $samjin_code;
    $color = $jo_sql_res['jo_color'];
    //재고
    $dpart_stock = NM_GET_STOCK_WITH_SAP_CODE(2,0,$sapCode12,$color,null);
    $dpart_stock_s = 0; $dpart_stock_q = 0; $dpart_stock_k = 0;
    if(count($dpart_stock) > 0){
        for($t=0 ;  $t< count($dpart_stock); $t++){
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

    $connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
    $g5['connect_samjindb'] = $connect_db;
    $sqlSamjin = "SELECT top 1 ORDER_NO, SAP_CODE, ITEM FROM S_MALL_ORDERS WHERE SAP_CODE = '{$sapCode12}' AND COLOR = '{$jo_sql_res['jo_color']}' ";
    $rsSamjin = mssql_sql_query($sqlSamjin);
    $num_rows = mssql_sql_num_rows($rsSamjin);

    for ($k = 0; $samrow = mssql_sql_fetch_array($rsSamjin); $k++) {  
        $samjin_name = $samrow['ITEM'];
        $sm_samjin_code = $samrow['ORDER_NO'];
        $sm_sap_code = $samrow['SAP_CODE'];
    }
    
    if($samjin_name == '' || $samjin_name == null){
        $result = "203";
        echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        return false;
    }

    $rep_stock = NM_REP_STOCK(2,2,$sapCode12,$sapCode12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_stock_s = 0; $rep_stock_q = 0; $rep_stock_k = 0;
    if(count($rep_stock) > 0){
        for($j=0 ;  $j< count($rep_stock); $j++){
            if($color == trim($rep_stock[$j]['COLOR'])){
                if(trim($rep_stock[$j]['HOCHING'])=='Q'){
                    $rep_stock_q += $rep_stock[$j]['STOCK2'];
                }else if(trim($rep_stock[$j]['HOCHING']=='K')){
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

    $rep_sale_sum3m = NM_REP_SALE_SUM($day3m,$today,2,$sapCode12,$sapCode12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum3p = NM_REP_SALE_SUM($day1ym,$day9m,2,$sapCode12,$sapCode12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum6m = NM_REP_SALE_SUM($day6m,$today,2,$sapCode12,$sapCode12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum6p = NM_REP_SALE_SUM($day1ym,$day6m,2,$sapCode12,$sapCode12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum1y = NM_REP_SALE_SUM($yst1,$yet1,2,$sapCode12,$sapCode12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
    $rep_sale_sum2y = NM_REP_SALE_SUM($yst2,$yet2,2,$sapCode12,$sapCode12,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

    if(count($rep_sale_sum3m) > 0){
        for($a=0 ;  $a< count($rep_sale_sum3m); $a++){
            if($color == trim($rep_sale_sum3m[$a]['COLOR'])){
                if(trim($rep_sale_sum3m[$a]['HOCHING'])=='Q'){
                    $rep_sale_sum3m_q += $rep_sale_sum3m[$a]['QTY'];
                }else if(trim($rep_sale_sum[$a]['HOCHING']=='K')){
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
                }else if(trim($rep_sale_sum[$b]['HOCHING']=='K')){
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
                }else if(trim($rep_sale_sum[$c]['HOCHING']=='K')){
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
                }else if(trim($rep_sale_sum[$d]['HOCHING']=='K')){
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
                }else if(trim($rep_sale_sum[$e]['HOCHING']=='K')){
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
                }else if(trim($rep_sale_sum[$f]['HOCHING']=='K')){
                    $rep_sale_sum2y_k += $rep_sale_sum2y[$f]['QTY'];
                }else{
                    $rep_sale_sum2y_s += $rep_sale_sum2y[$f]['QTY'];
                }
            }
        }
    }
    
    $overseas_common = "brand = '{$ps_select_res['ps_brand']}'
    ,channal = '{$channal}'
    ,item_type = '{$item_type}'
    ,item_1 = '{$item_1}'
    ,item_2 = '{$ps_select_res['ps_prod_name']}'
    ,samjin_code = '{$samjin_code}'
    ,samjin_name = '{$samjin_name}'
    ,item_name = '{$item_name}'
    ,color = '{$jo_sql_res['jo_color']}'
    ,color_nm = '{$color_nm}'
    ,prod_gubun = '{$ps_select_res['ps_code_gubun']}'
    ,season = '{$season}'
    ,memo = ''
    ,moq = ''
    ,lately_date = ''
    ,add_date = ''
    ,balju_qty_s = ''
    ,balju_qty_q = ''
    ,balju_qty_k = ''
    ,maker_ct = '{$maker_country}'
    ,maker_cp = '{$jo_mater_name[1]['mater']}'
    ,etc_meg = ''
    ,jo_id = '{$jo_sql_res['jo_id']}'
    ,ip_id = '{$ip_id}'
    ,ps_id = '{$ps_id}'
    ,total_stock_s = '{$total_stock_s}'
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
    

    
    $overseas_send = "insert new_goods_db_cover set $overseas_common  ";
    sql_query($overseas_send);

    $result = "100";
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return false;
}



// goto_url("./new_goods_process.php?tabs=list&brands=&ipgos=&dpart_ipgos=&shootings=&reorders=&sfl=it_name&stx=&sc_it_time=&limit_list=10");
goto_url("./new_goods_process.php?".$qstr);



