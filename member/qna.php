<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH . '/settle_lg.inc.php');

if (!$is_member) {
    goto_url('/auth/login.php?url=' . urlencode('/member/qna.php'));
}

//이전
//$sql_cnt_qna = "SELECT COUNT(*) AS CNT FROM lt_qa_content WHERE mb_id='{$member['mb_id']}' AND qa_type=0 AND it_id !=''";
$sql_cnt_qna = "SELECT COUNT(*) AS CNT FROM lt_shop_item_qa WHERE mb_id='{$member['mb_id']}'  AND it_id !=''";
$cnt_qna = sql_fetch($sql_cnt_qna);

$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$total_count = $cnt_qna['CNT'];
$total_page  = ceil($total_count / $perpage);

//$sql_qna = "SELECT q.*, (SELECT qa_content FROM lt_qa_content AS a WHERE a.qa_parent=q.qa_id AND a.qa_type=1) AS qa_answer FROM lt_qa_content AS q WHERE mb_id='{$member['mb_id']}' AND qa_type=0 AND it_id!='' ORDER BY qa_datetime DESC LIMIT {$fr}{$perpage}";
$sql_qna = "SELECT q.* , (SELECT it_name FROM {$g5['g5_shop_item_table']} WHERE it_id = q.it_id ) AS it_name FROM lt_shop_item_qa AS q WHERE mb_id='{$member['mb_id']}' AND it_id!='' ORDER BY iq_time DESC LIMIT {$fr}{$perpage}";
$db_qna = sql_query($sql_qna);

$qstr = "";
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=');

$contents = include_once(G5_VIEW_PATH . "/member.qna.list.php");

include_once G5_LAYOUT_PATH . "/layout.php";
