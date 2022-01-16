<?php
include_once('./_common.php');

if( !isset($it) && !get_session("ss_tv_idx") ){
    if( !headers_sent() ){  //헤더를 보내기 전이면 검색엔진에서 제외합니다.
        echo '<meta name="robots" content="noindex, nofollow">';
    }
    /*
    if( !G5_IS_MOBILE ){    //PC 에서는 검색엔진 화면에 노출하지 않도록 수정
        return;
    }
    */
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/itemuse.php');
    return;
}

include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// 현재페이지, 총페이지수, 한페이지에 보여줄 행, URL
function itemuse_page($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="'.$url.'1'.$add.'" class="pg_page pg_start">처음</a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<a href="'.$url.($start_page-1).$add.'" class="pg_page pg_prev">이전</a>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="'.$url.$k.$add.'" class="pg_page">'.$k.'</a><span class="sound_only">페이지</span>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<a href="'.$url.($end_page+1).$add.'" class="pg_page pg_next">다음</a>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url.$total_page.$add.'" class="pg_page pg_end">맨끝</a>'.PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}

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

$itemuse_skin = G5_SHOP_SKIN_PATH.'/itemuse.skin.php';

if(!file_exists($itemuse_skin)) {
    echo str_replace(G5_PATH.'/', '', $itemuse_skin).' 스킨 파일이 존재하지 않습니다.';
} else {
    include_once($itemuse_skin);
}
