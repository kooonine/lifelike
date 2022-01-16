<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot . dirname(__FILE__));

include_once($root_path . '/../../common.php');
include_once(G5_LIB_PATH . '/samjin.lib.php');


function color_table($text){
    if(preg_match("/[a-zA-Z]/",$text)){
        switch($text){
            case 'AA' : $color_nm = "'AA' ,'기타'"; break;
            case 'BE' : $color_nm = "'BE', '베이지'"; break;
            case 'BK' : $color_nm = "'BK', '블랙'"; break;
            case 'BL' : $color_nm = "'BL', '블루'"; break;
            case 'BR' : $color_nm = "'BR', '브라운'"; break;
            case 'CR' : $color_nm = "'CR', '크림'"; break;
            case 'DB' : $color_nm = "'DB', '진블루'"; break;
            case 'DP' : $color_nm = "'DP', '진핑크'"; break;
            case 'FC' : $color_nm = "'FC', '푸시아'"; break;
            case 'GD' : $color_nm = "'GD', '골드'"; break;
            case 'GN' : $color_nm = "'GN', '그린'"; break;
            case 'GR' : $color_nm = "'GR', '그레이'"; break;
            case 'IV' : $color_nm = "'IV', '아이보리'"; break;
            case 'KA' : $color_nm = "'KA', '카키'"; break;
            case 'LB' : $color_nm = "'LB', '연블루'"; break;
            case 'LG' : $color_nm = "'LG', '연그레이'"; break;
            case 'LP' : $color_nm = "'LP', '연핑크'"; break;
            case 'LV' : $color_nm = "'LV', '라벤다'"; break;
            case 'MT' : $color_nm = "'MT', '민트'"; break;
            case 'MU' : $color_nm = "'MU', '멀티'"; break;
            case 'MV' : $color_nm = "'MV', '모브'"; break;
            case 'MX' : $color_nm = "'MX', '혼합'"; break;
            case 'NC' : $color_nm = "'NC', '내츄럴'"; break;
            case 'NV' : $color_nm = "'NV', '네이비'"; break;
            case 'OR' : $color_nm = "'OR', '오렌지'"; break;
            case 'PC' : $color_nm = "'PC', '청록'"; break;
            case 'PK' : $color_nm = "'PK', '핑크'"; break;
            case 'PU' : $color_nm = "'PU', '퍼플'"; break;
            case 'RD' : $color_nm = "'RD', '레드'"; break;
            case 'WH' : $color_nm = "'WH', '화이트'"; break;
            case 'YE' : $color_nm = "'YE', '노랑'"; break;
        }
    }else{
        $color_nm = $text;
    }
    return $color_nm;
}

$test = false;
$outputs = array();

//크론1 : 상품준비중 => 배송중, RFID 변경
$connect_db = mssql_sql_connect(SAMJIN_MSSQL_HOST, SAMJIN_MSSQL_USER, SAMJIN_MSSQL_PASSWORD) or die('MSSQL Connect Error!!!');
$g5['connect_samjindb'] = $connect_db;

$now_date = date("Y-m-d H:i:s");

$today= date("Ymd");
$outputs = array();
$outputs[] = date('Y-m-d H:i:s', time()) . " : cron_samjin_stock_prod_info 시작  ";

$ps_item_sql = " SELECT *,CONCAT(ps_code_gubun, ps_code_brand, ps_code_year, ps_code_season, ps_code_item_type, ps_code_index, ps_code_item_name) AS withCode FROM lt_prod_schedule WHERE ps_display = 'Y'  ";
$result = sql_query($ps_item_sql);

for ($i = 0; $row = sql_fetch_array($result); $i++) {  
    $comStr = '';
    $stockWith = NM_GET_STOCK_WITH_SAP_CODE(2,0,$row['withCode'],null,null);
    $outputs[] = $row['withCode']. " start";
    for ($j=0; $j < count($stockWith); $j++) { 
        if ($j==0) {
            // $updateSql = "UPDATE lt_prod_schedule SET ps_dpart_stock ='Y' WHERE CONCAT(ps_code_gubun, ps_code_brand, ps_code_year, ps_code_season, ps_code_item_type, ps_code_index, ps_code_item_name) = '{$row['withCode']}'";
            $updateSql = "UPDATE lt_prod_schedule SET ps_dpart_stock ='Y' WHERE ps_code_gubun = '{$row['ps_code_gubun']}' AND ps_code_brand = '{$row['ps_code_brand']}' AND ps_code_year = '{$row['ps_code_year']}' AND ps_code_season = '{$row['ps_code_season']}' AND ps_code_item_type = '{$row['ps_code_item_type']}' AND ps_code_index = '{$row['ps_code_index']}' AND ps_code_item_name = '{$row['ps_code_item_name']}'";
            sql_query($updateSql);
            // $updateSql2 = "UPDATE lt_prod_schedule SET ps_ipgo_status ='Y' WHERE CONCAT(ps_code_gubun, ps_code_brand, ps_code_year, ps_code_season, ps_code_item_type, ps_code_index, ps_code_item_name) = '{$row['withCode']}' AND ps_re_order = 'N' ";
            $updateSql2 = "UPDATE lt_prod_schedule SET ps_ipgo_status ='Y' WHERE ps_code_gubun = '{$row['ps_code_gubun']}' AND ps_code_brand = '{$row['ps_code_brand']}' AND ps_code_year = '{$row['ps_code_year']}' AND ps_code_season = '{$row['ps_code_season']}' AND ps_code_item_type = '{$row['ps_code_item_type']}' AND ps_code_index = '{$row['ps_code_index']}' AND ps_code_item_name = '{$row['ps_code_item_name']}' AND ps_re_order = 'N' ";
            sql_query($updateSql2);
            $outputs[] = $row['withCode']. " ps_dpart_stock Y";
        }
        $comItId = $stockWith[$j]['SAP_CODE'].trim($stockWith[$j]['COLOR']).trim($stockWith[$j]['HOCHING']);
        $comItId = preg_replace("/\s+/", "", $comItId);
        $outputs[] =$comItId. " pi_samjin_stock ".$stockWith[$j]['STOCK2'];

        $sap_code = trim($stockWith[$j]['SAP_CODE']);
        $sap_color = trim($stockWith[$j]['COLOR']);
        $ser_colors = color_table($sap_color);
        $sap_hoching = trim($stockWith[$j]['HOCHING']);
        if(strpos($sap_hoching, '*') !== false){
            
            $up_id_sql = "SELECT * FROM lt_prod_info WHERE pi_model_no = '{$sap_code}' AND  pi_color = '{$ser_colors}' AND  pi_cisu = '{$sap_hoching}' limit 1" ; 
        }else{
            $up_id_sql = "SELECT * FROM lt_prod_info WHERE pi_model_no = '{$sap_code}' AND  pi_color = '{$ser_colors}' AND  pi_size = '{$sap_hoching}' limit 1" ; 
        }

        $up_id_res = sql_fetch($up_id_sql);

        $pi_id = $up_id_res['pi_id'];

        if ($stockWith[$j]['C_NO'] == 2 || $stockWith[$j]['C_NO'] == 3 || $stockWith[$j]['C_NO'] == 4 || $stockWith[$j]['C_NO'] == 8 || $stockWith[$j]['C_NO'] == 17) { 
            if(strpos($comStr, $comItId) !== false) {   
                $updateSql = "UPDATE lt_prod_info SET pi_samjin_stock  = pi_samjin_stock + {$stockWith[$j]['STOCK2']}, pi_stock_up_dt ='$now_date' WHERE pi_id = '$pi_id'";
            } else {  
                $updateSql = "UPDATE lt_prod_info SET pi_samjin_stock  = '{$stockWith[$j]['STOCK2']}', pi_stock_up_dt ='$now_date' WHERE pi_id = '$pi_id'";
                $comStr = $comStr.'||'.$comItId; 
            }  
            sql_query($updateSql);
        }
    }
}
$outputs[] = date('Y-m-d H:i:s', time()) . " : cron_samjin_stock_prod_info 끝  ";
print_raw($outputs);
