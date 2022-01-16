<?php
include_once('./_common.php');

if ($is_guest)
    alert_close('회원만 조회하실 수 있습니다.');

$g5['title'] = get_text($member['mb_nick']).' 님의 포인트 내역';
include_once(G5_PATH.'/head.sub.php');

$sql_common = " from {$g5['point_table']} where mb_id = '".escape_trim($member['mb_id'])."' ";
$sql_order = " order by po_id desc ";

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

if ($fr_date && $to_date) {
    $sql_common .= " and po_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
} else {
    $fr_date = date_create(G5_TIME_YMD);
    date_add($fr_date, date_interval_create_from_date_string('-6 days'));
    $fr_date = date_format($fr_date,"Y-m-d");
    $to_date = G5_TIME_YMD;
    
    $sql_common .= " and po_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if (isset($point_type) && $point_type != ""){
    
    if($point_type == "1"){
        $sql_common .= " and po_point > 0 ";
    }else if($point_type == "2"){
        $sql_common .= " and po_point < 0";
    }else if($point_type == "3"){
        $sql_common .= " and po_point > 0 and po_expired = 0 and po_expire_date <= '9999-12-30'";
    }
}

$po_expire_date = date_create(G5_TIME_YMD);
date_add($po_expire_date, date_interval_create_from_date_string('-1 months'));
$po_expire_date = date_format($po_expire_date,"Y-m-d");

$sql = " select count(*) as cnt 
        ,sum(case when po_point > 0 and po_expired = 0 and po_expire_date <= '".$po_expire_date."' then po_point else 0 end) as expire_point
        ,sum(case when po_point > 0 then po_point else 0 end) as plus_point
        ,sum(case when po_point < 0 then po_point else 0 end) as minus_point
        {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$expire_point = $row['expire_point'];
$plus_point = $row['plus_point'];
$minus_point = $row['minus_point'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$qstr = "fr_date=".$fr_date."&to_date=".$to_date."&point_type=".$point_type."&chk_date=".$chk_date;


include_once($member_skin_path.'/point.skin.php');

include_once(G5_PATH.'/tail.sub.php');
?>