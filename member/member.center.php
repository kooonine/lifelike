<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

// if (!$is_member) {
//     goto_url('/auth/login.php?url=' . urlencode('/member/member.center.php'));
// }


$sql_faq = "SELECT * FROM lt_faq";
$sql_where_faq = array();

if (!empty($filter_faq)) $sql_where_faq[] = "fa_category1='" . addslashes($filter_faq) . "'";

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
    $sql_where_faq[] = "((" . implode(' AND ', $arr_search_keyword['fa_subject']) . ") OR (" . implode(' AND ', $arr_search_keyword['fa_content']) . "))";
}
if (!empty($sql_where_faq)) $sql_faq .= " WHERE " . implode(' AND ', $sql_where_faq);

$db_count_faq = sql_query($sql_faq);

$perpage_faq = 10;
if ($page_faq > 1) $fr_faq = ($page_faq - 1) * $perpage_faq . ",";
$total_count_faq = $db_count_faq->num_rows;
$total_page_faq  = ceil($total_count_faq / $perpage_faq);

$qstr_faq = "filter_faq={$filter_faq}";
$qstr_faq .= "&amp;data-tab=1";
$qstr_faq .= "&amp;skeyword={$skeyword}";
$paging_faq = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page_faq, $total_page_faq, $_SERVER['SCRIPT_NAME'] . '?' . $qstr_faq. '&amp;page=');

$sql_faq .= " ORDER BY fa_order ASC LIMIT {$fr_faq}{$perpage_faq}";
$db_faq = sql_query($sql_faq);

$sql_filter_faq = "SELECT fm_subject FROM lt_faq_master";
$db_filter_faq = sql_fetch($sql_filter_faq);

//$contents = include_once(G5_VIEW_PATH . "/member.faq.list.php");


$sql_notice = "SELECT * FROM lt_write_notice WHERE wr_1='1'";
$sql_where_notice = array();
if (!empty($filter_notice)) $sql_where_notice[] = "ca_name='" . addslashes($filter_notice) . "'";
if (!empty($sql_where_notice)) $sql_notice .= " AND " . implode(' AND ', $sql_where_notice);

$db_count_noti = sql_query($sql_notice);

$perpage_noti = 10;
if ($page_noti > 1) $fr_noti = ($page_noti - 1) * $perpage_noti . ",";
$total_count_noti = $db_count_noti->num_rows;
$total_page_noti  = ceil($total_count_noti / $perpage_noti);

$qstr_noti = "filter_notice={$filter_notice}";
$paging_noti = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page_noti, $total_page_noti, $_SERVER['SCRIPT_NAME'] . '?' . $qstr_noti . '&amp;page=');

$sql_notice .= " ORDER BY wr_datetime DESC LIMIT {$fr_noti}{$perpage_noti}";
$db_notice = sql_query($sql_notice);


$contents = include_once(G5_VIEW_PATH . "/center.php");

include_once G5_LAYOUT_PATH . "/layout.php";
