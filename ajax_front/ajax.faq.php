<?php
include_once('./../common.php');


$page_faq = $_POST['page_faq'];
$filter_faq = $_POST['filter_faq'];

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


?>


<? for ($oi = 0; $faq_add = sql_fetch_array($db_faq); $oi++) : ?>
        <tr>
        <!-- <td><?= strip_tags($faq_add['fa_category1']) ?></td> -->
        <td>Q</td>
        <td style="text-align: left; cursor: pointer; font-weight: normal; color: #000000;" onclick="openAnswer_faq(this)"><?= $faq_add['fa_subject'] ?></td>
    </tr>
    <tr class="faq-content">
        <td class="ans">A</td>
        <td colspan=4 class="answer">
            <?= $faq_add['fa_content'] ?>
        </td>
    </tr>
<? endfor ?>
