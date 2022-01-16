<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$itemuse_list = "./itemuselist.php";
$itemuse_form = "./itemuseform.php?it_id=".$it_id;
$itemuse_formupdate = "./itemuseformupdate.php?it_id=".$it_id;

 $sql_common = " from `{$g5['g5_shop_item_use_table']}` where it_id = '{$it_id}' and is_confirm = '1' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select COUNT(*) as cnt
        ,sum(IF(is_age=20, is_score, 0)) as age20score
        ,sum(IF(is_age=30, is_score, 0)) as age30score
        ,sum(IF(is_age=40, is_score, 0)) as age40score
        ,sum(IF(is_age=50, is_score, 0)) as age50score
        ,sum(IF(is_age=20, 1, 0)) as age20
        ,sum(IF(is_age=30, 1, 0)) as age30
        ,sum(IF(is_age=40, 1, 0)) as age40
        ,sum(IF(is_age=50, 1, 0)) as age50 " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$age20cnt = $row['age20'];
$age30cnt = $row['age30'];
$age40cnt = $row['age40'];
$age50cnt = $row['age50'];
$age20score = ($row['age20score'] != 0)?get_star($row['age20score']/$row['age20']):0;
$age30score = ($row['age30score'] != 0)?get_star($row['age30score']/$row['age30']):0;
$age40score = ($row['age40score'] != 0)?get_star($row['age40score']/$row['age40']):0;
$age50score = ($row['age50score'] != 0)?get_star($row['age50score']/$row['age50']):0;
$age20best = false;
$age30best = false;
$age40best = false;
$age50best = false;
if($total_count > 0) {
    if($age20score >= $age30score && $age20score >= $age40score && $age20score >= $age50score) $age20best = true;
    if($age30score >= $age20score && $age30score >= $age40score && $age30score >= $age50score) $age30best = true;
    if($age40score >= $age20score && $age40score >= $age30score && $age40score >= $age50score) $age40best = true;
    if($age50score >= $age20score && $age50score >= $age30score && $age50score >= $age40score) $age50best = true;
}

$rows = 5;
$total_page  = ceil($total_count / $rows); // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 레코드 구함

$sql = "select * $sql_common order by is_id desc limit $from_record, $rows ";
$result = sql_query($sql);

$itemuse_skin = G5_MSHOP_SKIN_PATH.'/itemuse.skin.php';

if(!file_exists($itemuse_skin)) {
    echo str_replace(G5_PATH.'/', '', $itemuse_skin).' 스킨 파일이 존재하지 않습니다.';
} else {
    include_once($itemuse_skin);
}
?>