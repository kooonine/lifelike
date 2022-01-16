<?php
include_once('./../common.php');
$sql_common = " from `{$g5['g5_shop_item_qa_table']}`";
// if ($pick == "true") {
//     $sql_event_picked = "SELECT GROUP_CONCAT(it_id) AS picked FROM lt_shop_wish WHERE mb_id='{$member['mb_id']}' AND wi_type='event' GROUP BY mb_id";
//     $picked = sql_fetch($sql_event_picked);
//     $sql_common .= " AND cp_id IN ({$picked['picked']})";
// }
$page = $_POST['page'];
$type = $_POST['type'];
$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";

if(!empty($type)){
    $sql_search .= "and iq_category = '{$type}'";
}
$sql_order = $sql_common . "WHERE mb_id='{$member['mb_id']}'  AND it_id !='' ORDER BY iq_time DESC LIMIT {$fr}{$perpage}";

$sql_qna = " select *
          $sql_common
          $sql_search
          WHERE mb_id='{$member['mb_id']}'  AND it_id !='' ORDER BY iq_time DESC limit $fr $perpage ";
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

 // ah i dlrk djqtdjtj rmfjsrk ?

// $qstr = "it_id=".$it_id;
// $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=' , '#product-desc-qna-wrapper');

?>


<? for ($ci = 0; $qna_add = sql_fetch_array($result); $ci++) : ?>
    <tr height="116px" class="on-small" style="border-bottom : 1px solid #e0e0e0 ;">
        <td style="font-size: 14px; font-weight: normal; border:0px" onclick="openAnswerMo(this)">
        <div style="text-align: left; font-size: 16px;  font-weight: 500;  line-height: normal; color: #333333;"><?= $qna_add['iq_category'] ?></div>
        <div style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden; width:100px; display:block;font-size: 12px;  font-weight: normal; color: #3a3a3a;"><?= $qna_add['iq_subject'] ?>
        <? if (!empty($qna_add['iq_answer'])) : ?>
            <img src="/img/mobile/gnb_bg2.png">
        <? endif ?>
        </div>
        <div style="text-align: left;font-size: 12px;  font-weight: normal;  color: #959595"> <span class="lt-col-4" ><?= date("Y.m.d", strtotime($qna_add['iq_time'])) ?></span></div>
        <div style="text-align: left; font-size: 12px;  font-weight: normal;  color: #f93f00;"><?= $qna_add['iq_answer'] ? "답변완료" : "답변대기" ?></div>

        </td>
        <td style="border:0px;font-size: 14px; font-weight: normal; color: #f54600;border:0px;">

        <? if (!empty($qna_add['iq_answer'])) : ?>
            <div style="margin-left:5px; margin-bottom:-20px;">답변완료</div>
            <br>
        <? else : ?>
        <div class="mo_modify_btn" onclick="qaUpdate('<?= $qna_add['iq_id'] ?>')">수정</div>
        <? endif ?>
        <div class="mo_delete_btn" style="margin-top:7px" onclick="qaDelete('<?= $qna_add['iq_id'] ?>')" >삭제</div>
        
            <!-- <button type="button" class="btn btn-black btn-list" onclick="qaDelete('<?= $qna['iq_id'] ?>')">삭제</button> -->
        </td>
    </tr>
    <? if (!empty($qna_add['iq_answer'])) : ?>
        <tr height = "79px" class="qna-content on-small">
            <td colspan=3 style="font-size: 12px !important; font-weight: normal !important; border:0px;">
                <div style="float:left; margin-right:3px; font-size: 12px; color: #3a3a3a;"> 답변 : </div>
                <div style="font-size: 12px; color: #3a3a3a;"> <?= $qna_add['iq_answer'] ?></div>
                <!-- <br> -->
                <!-- <div style= "margin-top:-39px; font-size: 12px; color: #959595;"><?= date("Y.m.d", strtotime($qna_add['iq_time'])) ?></div> -->
            </td>
        </tr>
    <? endif ?>
<? endfor ?>
