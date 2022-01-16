<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$itemqa_skin = G5_MSHOP_SKIN_PATH.'/itemqa.skin.php';
// 현재페이지, 총페이지수, 한페이지에 보여줄 행, URL
function itemqa_page_mobile($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';
    
    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="'.$url.'1'.$add.'" class="qa_page pg-prev">&lt;</a>'.PHP_EOL;
    }
    
    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;
    
    if ($end_page >= $total_page) $end_page = $total_page;
    
    if ($start_page > 1) $str .= '<a href="'.$url.($start_page-1).$add.'" class="qa_page pg-prev">&lt;</a>'.PHP_EOL;
    
    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="'.$url.$k.$add.'" class="qa_page">'.$k.'</a>'.PHP_EOL;
                else
                    $str .= '<strong class="current">'.$k.'</strong>'.PHP_EOL;
        }
    }
    
    if ($total_page > $end_page) $str .= '<a href="'.$url.($end_page+1).$add.'" class="qa_page pg-next">&gt;</a>'.PHP_EOL;
    
    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url.$total_page.$add.'" class="qa_page pg-next">&gt;</a>'.PHP_EOL;
    }
    
    if ($str)
        return "<div class=\"pagination\">{$str}</div>";
        else
            return "";
}

if(!file_exists($itemqa_skin)) {
    echo str_replace(G5_PATH.'/', '', $itemqa_skin).' 스킨 파일이 존재하지 않습니다.';
} else {
    include_once($itemqa_skin);
}
?>