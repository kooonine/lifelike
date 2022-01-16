<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot.dirname(__FILE__));

include_once($root_path.'/../../common.php');
$rtnData = array();

$sql = "select * from lt_member_company where cp_calculate_date1 = DATE_FORMAT(now(), '%d') ";
if(isset($company_code))
{
    $sql = " select * from lt_member_company where company_code = '{$company_code}' ";
}

$cp_result = sql_query($sql);
$cnt = sql_num_rows($cp_result);
if($cnt == 0) {
    array_push($rtnData, array("RESULT" => "정산 처리할 업체가 없습니다."));
}


for ($c=0; $cp=sql_fetch_array($cp_result); $c++){
    $company_code = $cp['company_code'];
    $cp_commission = $cp['cp_commission'];
    $cp_calculate_date1 = $cp['cp_calculate_date1'];
    
    $yymm = date('Y-m', strtotime('-1 months', G5_SERVER_TIME)); //정산체크 전월 
    $od_receipt_time_edate = $yymm.'-'.$cp_calculate_date1;
    $od_receipt_time_sdate = date('Y-m-d', strtotime('-1 months', $od_receipt_time_edate));
    if($cp_calculate_date1 == '1'){
        $od_receipt_time_sdate = $yymm.'-01';
        $od_receipt_time_edate = date('Y-m-d', G5_SERVER_TIME);
    }
    
    
    $sql = "select count(*) cnt from lt_shop_company_cal where company_code = '{$company_code}' and yymm = '".$yymm."'";
    $ch = sql_fetch($sql);
    if($ch['cnt'] != 0)
    {
        array_push($rtnData, array("RESULT" => $company_code."/".$yymm." : 정산정보가 이미 등록되었습니다."));
        continue;
    }
    
    $sql = "select  a.company_code
                    , count(*) od_count
                    , sum(od_receipt_price) as od_receipt_price
                    , sum(od_point) as od_point
                    , sum(od_coupon+od_cart_coupon) as od_coupon
                    , sum(od_receipt_point) as od_receipt_point
            from    lt_shop_order as a
            where   od_status in ('구매완료')
            and     a.company_code = '{$company_code}'
            and     a.od_receipt_time between '{$od_receipt_time_sdate}' and '{$od_receipt_time_edate}' ";
    $od = sql_fetch($sql);
    
    $sql = "insert into lt_shop_company_cal
            set company_code = '{$company_code}'
                  ,yymm = '".$yymm."'
                  ,od_receipt_price = '{$od['od_receipt_price']}'
                  ,od_count = '{$od['od_count']}'
                  ,od_point = '{$od['od_point']}'
                  ,od_coupon = '{$od['od_coupon']}'
                  ,od_receipt_point = '{$od['od_receipt_point']}'
                  ,od_vat_mny = ('{$od['od_receipt_price']}' * 0.1)
                  ,cp_commission = '{$cp_commission}'
                  ,cp_commission_mny =  ('{$od['od_receipt_price']}' / 100 * '$cp_commission')
                  ,cp_cal_price = ('{$od['od_receipt_price']}' - ('{$od['od_receipt_price']}' / 100 * '$cp_commission'))
                  ,cp_payment_date = '".date('Y-m', G5_SERVER_TIME)."-".$cp['cp_calculate_date']."'
                  ,cp_calculate_date = '{$cp['cp_calculate_date']}'
                  ,cp_calculate_date1 = '{$cp['cp_calculate_date1']}'
                  ,cp_calculate_date2 = '{$cp['cp_calculate_date1']}'
                  ,cc_status = '지급예정'
                  ,register_date = '".G5_TIME_YMDHIS."' 
            ";
    sql_query($sql, true);
    array_push($rtnData, array("RESULT" => $company_code."/".$yymm." : 정산정보를 등록되었습니다."));
}

echo  json_encode_raw($rtnData);

?>