<?php
include_once('./_common.php');

if( isset($sfl) && ! in_array($sfl, array('b.it_name', 'a.it_id', 'a.iq_subject', 'a.iq_question', 'a.iq_name', 'a.mb_id' , 'a.iq_category')) ){
    //다른값이 들어가있다면 초기화
    $sfl = '';
}

// if (G5_IS_MOBILE) {
//     include_once(G5_MSHOP_PATH.'/itemqalist.php');
//     return;
// }

$g5['title'] = '상품문의';

$sql_iono = " SELECT io_order_no FROM `{$g5['g5_shop_item_option_table']}` WHERE it_id = '{$it_id}' LIMIT 1 ";
$io_on = sql_fetch($sql_iono);
$io_on['io_order_no'];


$sql_common = " from `{$g5['g5_shop_item_qa_table']}` a join `{$g5['g5_shop_item_table']}` b on (a.it_id=b.it_id) ";
$sql_search = " where (1) and b.lt_order_no='{$io_on['io_order_no']}' ";

if(!$sfl)
    $sfl = 'b.it_name';

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "a.it_id" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.iq_name" :
        case "a.mb_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if($select_type || $select_type != ""){
    $sql_search .= " and  ";
    $sql_search .=  "a.iq_category = '$select_type' " ;
    $sql_search .= "  ";
}

if (!$sst) {
    $sst  = "a.iq_id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

//$rows = $config['cf_page_rows'];
$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


// $qstr = "filter={$filter}";
// $qstr .= "&amp;skeyword={$skeyword}";
//$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');
$qstr = "it_id=".$it_id;
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=' , '#product-desc-qna-wrapper');


$sql = " select a.*, b.it_name
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);

$list = array();

for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $list[$i] = $row;
    $list[$i]['category'] = get_text($row['iq_category']);
    $list[$i]['icon_file'] = '';
    $list[$i]['subject'] = nl2br(strip_tags($row['iq_subject']));
    $list[$i]['question'] = nl2br(strip_tags($row['iq_question']));
    $list[$i]['answer'] = nl2br(strip_tags($row['iq_answer']));
    $list[$i]['name'] = get_text($row['iq_name']);
    $list[$i]['date'] = date("Y.m.d", strtotime($row['iq_time']));
}

// $itemqalist_skin = G5_SHOP_SKIN_PATH.'/itemqalist.skin.php';
include_once(G5_VIEW_PATH . "/product.detail.qna.php");
// if(!file_exists($itemqalist_skin)) {
//     echo str_replace(G5_PATH.'/', '', $itemqalist_skin).' 스킨 파일이 존재하지 않습니다.';
// } else {
//     include_once($itemqalist_skin);
// }

// include_once('./_tail.php');

