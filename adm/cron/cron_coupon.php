<? #!/usr/local/php53/bin/php
$chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
$root_path = str_replace('\\', '/', $chroot.dirname(__FILE__));

include_once($root_path.'/../../common.php');
$rtnData = array();

//생일인 회원목록
$sql = "select * from lt_member where DATE_FORMAT(mb_birth, '%m-%d') = DATE_FORMAT(now(), '%m-%d') ";
$result = sql_query($sql);
$cnt = sql_num_rows($result);
if($cnt == 0) {
    array_push($rtnData, array("RESULT" => "발급 대상이 없습니다."));
}

for ($c=0; $mb=sql_fetch_array($result); $c++){
    
    $couponsql = "select * from lt_shop_coupon_mng where cm_target_type = '1' and cm_target_type2 = '생일' and cm_status = '발급중'";
    $couponresult = sql_query($couponsql);
    
    if(sql_num_rows($couponresult) > 0) {
        for($i=0; $cmg = sql_fetch_array($couponresult); $i++) {
            
            //발급중인 생일 쿠폰수 만큼 발급
            $j = 0;
            $create_coupon = false;
            do {
                $cp_id = get_coupon_id();
                
                $sql3 = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
                $row3 = sql_fetch($sql3);
                
                if(!$row3['cnt']) {
                    $create_coupon = true;
                    break;
                } else {
                    if($j > 20)
                        break;
                }
            } while(1);
            
            if($create_coupon) {
                $cp_subject = $cmg['cm_subject'];
                $cp_method = $cmg['cm_method'];
                $cp_target = '';
                $cp_start = G5_TIME_YMD;
                $cp_end = date("Y-m-d", (G5_SERVER_TIME + (86400 * ((int)$cmg['cm_end_time'] - 1))));
                $cp_type = $cmg['cm_type'];
                $cp_price = $cmg['cm_price'];
                $cp_trunc = $cmg['cm_trunc'];
                $cp_minimum = $cmg['cm_minimum'];
                $cp_maximum = $cmg['cm_maximum'];
                $cm_no = $cmg['cm_no'];
                
                $sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
                            ( cp_id, cp_subject, cp_method, cp_target, mb_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime, cm_no )
                        VALUES
                            ( '$cp_id', '$cp_subject', '$cp_method', '$cp_target', '{$mb['mb_id']}', '$cp_start', '$cp_end', '$cp_type', '$cp_price', '$cp_trunc', '$cp_minimum', '$cp_maximum', '".G5_TIME_YMDHIS."', '$cm_no' ) ";
                
                $res = sql_query($sql, false);
                
                array_push($rtnData, array("RESULT" => $mb['mb_id']."/".$cp_subject." : 발급되었습니다."));
                
                //쿠폰발급 알림
                $arr_change_data = array();
                $arr_change_data['쿠폰명'] = $cmg['cm_subject'];
                if($cp_end != '0000-00-00') {
                    $arr_change_data['사용기간'] = substr($cp_start, 0, 10)."~".substr($cp_end, 0, 10);
                } else {
                    $arr_change_data['사용기간'] = "기간 제한 없음";
                }
                $cm_summary = "";
                
                if($cmg['cm_summary']) $cm_summary .= $cmg['cm_summary']."<br/>";
                if($cmg['cm_minimum']) $cm_summary .= "결제 시 ".$cmg['cm_minimum']."원 이상 구매 시 사용<br/>";
                if($cmg['cm_maximum']) $cm_summary .= "최대 할인 금액 ".$cmg['cm_maximum']." 원<br/>";
                
                $arr_change_data['사용범위안내'] = $cm_summary;
                
                if($cmg['cm_sms_send']) msg_autosend("광고", "생일 쿠폰 발급 안내", $mb['mb_id'], $arr_change_data);
            }
        
        }
        
    }
    if ($config['cf_extra_point_1'] && $config['cf_extra_point_1'] != 0) insert_point($mb['mb_id'], $config['cf_extra_point_1'], '생일축하 기념 포인트', '@birthday', $mb['mb_id'], '생일');
}


echo  json_encode_raw($rtnData);

?>