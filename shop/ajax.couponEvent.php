<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/json.lib.php');

if (!$member['mb_id'])
    die(json_encode(array('error' => '회원 로그인 후 이용해 주십시오.')));

$cp_start = date('Y-m-d', G5_SERVER_TIME);

$sqlSoupon = sql_fetch(" SELECT * FROM {$g5['g5_shop_coupon_table']} WHERE cp_start='{$cp_start}' AND cp_end='{$cp_start}' AND (mb_id = NULL OR mb_id = '') AND cz_id='125' ORDER BY cp_no ASC LIMIT 1 ");

if (!$sqlSoupon) {
    die(json_encode(array('error' => '모든 쿠폰이 소진되었습니다.')));
} else {
    $sqlSoupon2 = sql_fetch(" SELECT * FROM {$g5['g5_shop_coupon_table']} WHERE cp_start='{$cp_start}' AND cp_end='{$cp_start}' AND mb_id = '{$member['mb_id']}' AND cz_id='125' ORDER BY cp_no ASC LIMIT 1 ");
    if (!$sqlSoupon2) { 
    } else {
        die(json_encode(array('error' => '하루에 한번만 다운로드하실수 있습니다.')));
    }
    sql_query(" UPDATE {$g5['g5_shop_coupon_table']} SET mb_id ='{$member['mb_id']}' WHERE cp_no = '{$sqlSoupon['cp_no']}' ");
    die(json_encode(array('success' => '쿠폰이 발급되었습니다.')));
}
return;


