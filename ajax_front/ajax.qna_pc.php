<?php
include_once('./../common.php');

$sql_common = " from `{$g5['g5_shop_item_qa_table']}` a join `{$g5['g5_shop_item_table']}` b on (a.it_id=b.it_id) ";
// if ($pick == "true") {
//     $sql_event_picked = "SELECT GROUP_CONCAT(it_id) AS picked FROM lt_shop_wish WHERE mb_id='{$member['mb_id']}' AND wi_type='event' GROUP BY mb_id";
//     $picked = sql_fetch($sql_event_picked);
//     $sql_common .= " AND cp_id IN ({$picked['picked']})";
// }
$page = $_POST['page'];
$it_id = $_POST['it_id'];
$type = $_POST['type'];

$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";


$sql_search = " where (1) and a.it_id='{$it_id}' ";
if(!empty($type)){
    $sql_search .= "and iq_category = '{$type}'";
}
$sql_order = $sql_common . " ORDER BY iq_time DESC LIMIT {$fr}{$perpage}";

$sql_qna = " select a.*, b.it_name
          $sql_common
          $sql_search
          ORDER BY a.iq_time DESC limit $fr $perpage ";
$result = sql_query($sql_qna);

$sql_cnt = " select count(*) as cnt
         $sql_common
         $sql_search
         ";
$row = sql_fetch($sql_cnt);
$total_count = $row['cnt'];

//$rows = $config['cf_page_rows'];
$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함



$qstr = "it_id=".$it_id;
$paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=' , '#product-desc-qna-wrapper');

?>


<tr class = "on-big" style="border-bottom: 1px solid var(--black-two)">
    <th class="lt-col-1" style="text-align: center;font-weight: bold;">문의종류</td>
    <td class="lt-col-2 on-big" style="text-align: center;font-weight: bold;">내용</td>
    <td class="lt-col-2 on-small" style="text-align: center;font-weight: bold; text-align:left;">내용</td>
    <td class="lt-col-3" style="text-align: center;font-weight: bold;">이름</td>
    <td class="lt-col-4" style="text-align: center;font-weight: bold;">문의일</td>
    <td class="lt-col-5" style="text-align: center;font-weight: bold;">답변여부</td>
</tr>
<? for ($ci = 0; $qna_add = sql_fetch_array($result); $ci++) : ?>
    <tr class="product-detail-qna-subject on-big" onclick="openAnswer(this)">
        <th class="lt-col-1" style="text-align: center; font-size: 18px;  font-weight: normal;  line-height: normal; color: #f93f00;"><?= $qna_add['iq_category'] ?></td>
        <td class="lt-col-2" style="font-size: 18px;  font-weight: normal;  line-height: normal; color: #333333; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;width:300px; display:block;"><?= $qna_add['iq_subject'] ?></td>
        <td class="lt-col-3" style="text-align: center; font-size: 16px;  font-weight: normal;  line-height: normal; color: #f93f00;"><?= get_star_string($qna_add['iq_name']) ?></td>
        <td class="lt-col-4" style="text-align: center; font-size: 12px; font-weight: 500; color: #565656;"><?= $qna_add['iq_time'] ?></td>
        <td class="lt-col-5" style="text-align: center; font-size: 18px; color: #f93f00;"><?= $qna_add['iq_answer'] ? "답변완료" : "답변대기중" ?></td>
    </tr>
    <tr class="product-detail-qna-content on-big">
        <td></td>
        <td colspan=4>
            <div style="font-size: 16px;  font-weight: normal;  color: #333333;">Q. <?= trim($qna_add['iq_question']) ?></div>
            <div style="font-size: 16px;  font-weight: normal;  color: #f93f00;">A. <?= trim($qna_add['iq_answer']) ?></div>
        </td>
    </tr>
<? endfor ?>