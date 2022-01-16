<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/faq.php'));
}

$sql_faq = "SELECT * FROM lt_faq";
$sql_where = array();

if (!empty($filter)) $sql_where[] = "fa_category1='" . addslashes($filter) . "'";

$skeyword = trim(clean_xss_tags($skeyword));
if (!empty($skeyword)) {
    $search_keyword = explode(' ', $skeyword);

    $arr_search_keyword = array(
        'fa_subject' => array(),
        'fa_content' => array(),
    );
    foreach ($search_keyword as $sk) {
        $arr_search_keyword['fa_subject'][] = "fa_subject LIKE '%{$sk}%'";
        $arr_search_keyword['fa_content'][] = "fa_content LIKE '%{$sk}%'";
    }
}

if (!empty($arr_search_keyword['fa_subject'])) {
    $sql_where[] = "((" . implode(' AND ', $arr_search_keyword['fa_subject']) . ") OR (" . implode(' AND ', $arr_search_keyword['fa_content']) . "))";
}
if (!empty($sql_where)) $sql_faq .= " WHERE " . implode(' AND ', $sql_where);

$db_count = sql_query($sql_faq);

$perpage = 10;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$total_count = $db_count->num_rows;
$total_page  = ceil($total_count / $perpage);

$qstr = "filter={$filter}";
$qstr .= "&amp;skeyword={$skeyword}";
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

$sql_faq .= " ORDER BY fa_order ASC LIMIT {$fr}{$perpage}";
$db_faq = sql_query($sql_faq);

$sql_filter = "SELECT fm_subject FROM lt_faq_master";
$db_filter = sql_fetch($sql_filter);

$contents = include_once(G5_VIEW_PATH . "/member.faq.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
