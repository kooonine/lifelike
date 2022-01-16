<?php
include_once('./_common.php');

$qaconfig = get_qa_config();

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

include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$itemqa_list = G5_BBS_URL."/qalist.php";

if($is_member)
    $itemqa_form = G5_BBS_URL."/qawrite.php?it_id=".$it_id;

$sql_common = " from {$g5['qa_content_table']} ";
$sql_search = " where qa_type = '0' ";
$sql_search .= " and it_id = '".$it_id."' ";

if(isset($qa_status) && $qa_status != "") {
    $sql_search .= " and qa_status = '".$qa_status."' ";
}

if(!$is_admin && !$is_guest){
    $sql_search .= " and (mb_id = '{$member['mb_id']}' or INSTR(qa_category, '기타') > 0) ";
}

if($is_guest){
    $sql_search .= " and INSTR(qa_category, '기타') > 0 ";
}

//if(!$is_admin)
//    $sql_search .= " and mb_id = '{$member['mb_id']}' ";
    
$sql_order = " order by qa_num ";
    
// 테이블의 전체 레코드수만 얻음

$sql = " select count(*) as cnt
                $sql_common
                $sql_search ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];


$page_rows = G5_IS_MOBILE ? $qaconfig['qa_mobile_page_rows'] : $qaconfig['qa_page_rows'];
$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

$sql = " select *
                $sql_common
                $sql_search
                $sql_order
                limit $from_record, $page_rows ";
$result = sql_query($sql);

$list = array();
$num = $total_count - ($page - 1) * $page_rows;

$subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];
for($i=0; $row=sql_fetch_array($result); $i++) {
    $list[$i] = $row;
    
    $list[$i]['category'] = get_text($row['qa_category']);
    $list[$i]['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
    if ($stx) {
        $list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
    }
    
    if($member['mb_id'] == $list[$i]['mb_id'] || $is_admin)
    {
        $list[$i]['view_href'] = G5_BBS_URL.'/qaview.php?qa_id='.$row['qa_id'].$qstr;
    } else {
        $list[$i]['view_href'] = "javascript:void(0);";
    }
    
    $list[$i]['icon_file'] = '';
    if(trim($row['qa_file1']) || trim($row['qa_file2']))
        $list[$i]['icon_file'] = '<img src="'.$qa_skin_url.'/img/icon_file.gif">';
        
    $list[$i]['name'] = get_text($row['qa_name']);
    // 사이드뷰 적용시
    //$list[$i]['name'] = get_sideview($row['mb_id'], $row['qa_name']);
    $list[$i]['date'] = substr($row['qa_datetime'], 2, 8);
    
    $list[$i]['num'] = $num - $i;
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/itemqa.php');
    return;
}// 현재페이지, 총페이지수, 한페이지에 보여줄 행, URL
function itemqa_page($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';
    
    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="'.$url.'1'.$add.'" class="qa_page prev"><span class="blind">처음</span></a>'.PHP_EOL;
    }
    
    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;
    
    if ($end_page >= $total_page) $end_page = $total_page;
    
    if ($start_page > 1) $str .= '<a href="'.$url.($start_page-1).$add.'" class="qa_page prev"><span class="blind">이전</span></a>'.PHP_EOL;
    
    $str .= '<span class="num">';
    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="'.$url.$k.$add.'" class="qa_page current">'.$k.'</a>'.PHP_EOL;
                else
                    $str .= '<a href="#" class="current">'.$k.'</a>'.PHP_EOL;
        }
    }
    $str .= '</span>';
    
    if ($total_page > $end_page) $str .= '<a href="'.$url.($end_page+1).$add.'" class="qa_page next"><span class="blind">다음</span></a>'.PHP_EOL;
    
    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url.$total_page.$add.'" class="qa_page next"><span class="blind">맨끝</span></a>'.PHP_EOL;
    }
    
    if ($str)
        return "<div class=\"paging\">{$str}</div>";
        else
            return "";
}

$itemqa_skin = G5_SHOP_SKIN_PATH.'/itemqa.skin.php';

if(!file_exists($itemqa_skin)) {
    echo str_replace(G5_PATH.'/', '', $itemqa_skin).' 스킨 파일이 존재하지 않습니다.';
} else {
    include_once($itemqa_skin);
}
