<?php
include_once('./_common.php');

if (!isset($it) && !get_session("ss_tv_idx")) {
    if (!headers_sent()) {  //헤더를 보내기 전이면 검색엔진에서 제외합니다.
        echo '<meta name="robots" content="noindex, nofollow">';
    }
}

$itemuse_list = "./itemuselist.php";
$itemuse_form = "./itemuseform.php?it_id=" . $it_id;
$itemuse_formupdate = "./itemuseformupdate.php?it_id=" . $it_id;


$sqlOpt = "SELECT io_order_no FROM lt_shop_item_option  WHERE it_id = '{$it_id}' LIMIT 1";
$resOpt = sql_fetch($sqlOpt);

$sql_common = " from `{$g5['g5_shop_item_use_table']}` a where io_order_no = '{$resOpt['io_order_no']}' and is_confirm = '1' ";

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
$age20score = ($row['age20score'] != 0) ? get_star($row['age20score'] / $row['age20']) : 0;
$age30score = ($row['age30score'] != 0) ? get_star($row['age30score'] / $row['age30']) : 0;
$age40score = ($row['age40score'] != 0) ? get_star($row['age40score'] / $row['age40']) : 0;
$age50score = ($row['age50score'] != 0) ? get_star($row['age50score'] / $row['age50']) : 0;
$best = array(
    20 => $age20score,
    30 => $age30score,
    40 => $age40score,
    50 => $age50score,
);

$best = arsort($best);
// $ageBest = array_pop($best);

$sql_review_photo = "SELECT * {$sql_common} AND is_type=1 ORDER BY is_id DESC";
$db_review_photo = sql_query($sql_review_photo);
$db_m_review_photo = sql_query($sql_review_photo);
$db_modal_review_photo = sql_query($sql_review_photo);
$count_best = $db_review_photo->num_rows;


$rows = 5;
$total_page  = ceil($count_best / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$qstr = "it_id=".$it_id;
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=' , '#product-desc-review-wrapper');


$sql_review = "SELECT * {$sql_common} ORDER BY is_id DESC LIMIT {$from_record}, {$rows}";
$db_review = sql_query($sql_review);
$db_m_review = sql_query($sql_review);
$thumbnail_width = 172;





include_once(G5_VIEW_PATH . "/product.detail.review.php");
