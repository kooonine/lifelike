<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/json.lib.php');

if (!$member['mb_id'])
    die(json_encode(array('error' => '회원 로그인 후 이용해 주십시오.')));

if (!$cp_id)
    die(json_encode(array('error' => '쿠폰 번호를 입력해주세요.')));

$cp_id = strtoupper($cp_id);

$sql_check_coupon = "SELECT * FROM {$g5['g5_shop_coupon_table']} WHERE cp_id='{$cp_id}'";
$coupon = sql_fetch($sql_check_coupon);

if (empty($coupon['cp_no']))
    die(json_encode(array('error' => '쿠폰을 찾을 수 없습니다.')));
if (!empty($coupon['mb_id']) && $coupon['cp_method'] != 31)
    die(json_encode(array('error' => '해당 쿠폰은 이미 발행되었습니다.')));

$sql_cz = "SELECT cz_download, cz_download_limit, cz_period, cz_download_user_limit FROM {$g5['g5_shop_coupon_zone_table']} WHERE cz_id='{$coupon['cz_id']}'";
$cz = sql_fetch($sql_cz);

// 발급제한이 있는경우 확인
if ($cz['cz_download_limit'] > 0 && $cz['cz_download'] >= $cz['cz_download_limit'])
    die(json_encode(array('error' => '쿠폰 발행이 마감되었습니다.')));

if ($coupon['cp_method'] == 31 && !empty($coupon['mb_id']) && $coupon['mb_id'] != $member['mb_id']) {
    $sql_cz_point = "SELECT cz_start, cz_end FROM {$g5['g5_shop_coupon_zone_table']} WHERE cz_id='{$coupon['cz_id']}'";
    $czp = sql_fetch($sql_cz_point);

    if (!($czp['cz_start'] <= G5_TIME_YMD && $czp['cz_end'] >= G5_TIME_YMD))
        die(json_encode(array('error' => '쿠폰 발행기간이 지났습니다.')));
    
    $sql_check_coupon_mb = "SELECT * FROM {$g5['g5_shop_coupon_table']} WHERE cz_id='{$coupon['cz_id']}' AND mb_id = '{$member['mb_id']}'";
    $coupon_mb = sql_fetch($sql_check_coupon_mb);
    if (!empty($coupon_mb['mb_id']))
        die(json_encode(array('error' => '해당 쿠폰은 이미 발행되었습니다.')));

    $j = 0;
    do {
        $cp_id = get_coupon_id();

        $sql3 = " select count(*) as cnt from {$g5['g5_shop_coupon_table']} where cp_id = '$cp_id' ";
        $row3 = sql_fetch($sql3);

        if (!$row3['cnt'])
            break;
        else {
            if ($j > 20)
                die(json_encode(array('error' => 'Coupon ID Error')));
        }
        $j++;
    } while (1);

    $sql_cz_point = "SELECT cz_start, cz_end FROM {$g5['g5_shop_coupon_zone_table']} WHERE cz_id='{$coupon['cz_id']}'";
    $czp = sql_fetch($sql_cz_point);


    $sql = " INSERT INTO {$g5['g5_shop_coupon_table']}
    ( cp_id, cp_subject, cp_desc, cp_method, cp_target, cz_id, cp_start, cp_end, cp_type, cp_price, cp_trunc, cp_minimum, cp_maximum, cp_datetime, cp_weekday, cp_week, cp_point_coupon_amount )
    VALUES
    ( '$cp_id', '{$coupon['cp_subject']}', '{$coupon['cp_desc']}', '{$coupon['cp_method']}', '{$coupon['cp_target']}', '{$coupon['cz_id']}', '{$czp['cz_start']}', '{$czp['cz_end']}', '{$coupon['cp_type']}', '{$coupon['cp_price']}', '{$coupon['cp_trunc']}', '{$coupon['cp_minimum']}', '{$coupon['cp_maximum']}', '" . G5_TIME_YMDHIS . "', '{$coupon['cp_weekday']}', '{$coupon['cp_week']}', '{$coupon['cp_point_coupon_amount']}' ) ";
    $result = sql_query($sql);

    $sql_check_coupon = "SELECT * FROM {$g5['g5_shop_coupon_table']} WHERE cp_id='{$cp_id}'";
    $coupon = sql_fetch($sql_check_coupon);
}

// 이미 발행받은 쿠폰 !!!!
$sql_cp_count = "SELECT count(cp_no) AS cnt FROM {$g5['g5_shop_coupon_table']} WHERE cz_id='{$coupon['cz_id']}' AND mb_id = '{$member['mb_id']}'";
$cp_count= sql_fetch($sql_cp_count);
$coupon_count = $cp_count['cnt'];
if ($cz['cz_download_user_limit'] <= $coupon_count)
    die(json_encode(array('error' => '다운로드쿠폰은 ID별 1회만 등록가능합니다.')));

if ($coupon['cp_method'] == 31) {
    if (!($coupon['cp_start'] <= G5_TIME_YMD && $coupon['cp_end'] >= G5_TIME_YMD))
        die(json_encode(array('error' => '쿠폰 발행기간이 지났습니다.')));

    insert_point($member['mb_id'], $coupon['cp_point_coupon_amount'], '쿠폰전환포인트', '@pointCoupon', $member['mb_id'],$coupon['cp_subject'],30);
    $cp_datetime = G5_TIME_YMDHIS;
    $sql = "UPDATE {$g5['g5_shop_coupon_table']} SET mb_id='{$member['mb_id']}', cp_start='2000-00-00', cp_end='2000-00-00', cp_datetime='{$cp_datetime}' WHERE cp_id='{$coupon['cp_id']}'";
    $result = sql_query($sql);

    sql_query("UPDATE {$g5['g5_shop_coupon_zone_table']} set cz_download = cz_download + 1 where cz_id = '{$coupon['cz_id']}' ");
    
    $sql = " INSERT INTO lt_shop_coupon_log SET cp_id='{$cp_id}',mb_id='{$member['mb_id']}',cp_price={$coupon['cp_point_coupon_amount']},cl_datetime=NOW() ";
    $result = sql_query($sql);
    die(json_encode(array('success' => 'pointcoupon')));
} else {
    $period = $cz['cz_period'] - 1;
    if ($period < 0)
        $period = 0;
    $cp_start = G5_TIME_YMD;
    $cp_end = date('Y-m-d', strtotime("+{$period} days", G5_SERVER_TIME));
    $cp_datetime = G5_TIME_YMDHIS;
    $result = false;
    
    $sql = "UPDATE {$g5['g5_shop_coupon_table']} SET mb_id='{$member['mb_id']}', cp_start='{$cp_start}', cp_end='{$cp_end}', cp_datetime='{$cp_datetime}' WHERE cp_id='{$coupon['cp_id']}'";
    
    $result = sql_query($sql);
    
    sql_query("UPDATE {$g5['g5_shop_coupon_zone_table']} set cz_download = cz_download + 1 where cz_id = '{$coupon['cz_id']}' ");
}

die(json_encode(array('error' => '')));
