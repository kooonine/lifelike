<?php
include_once('./../common.php');
$sql_common = " from `{$g5['g5_shop_item_qa_table']}`";



$page = $_POST['page'];

// $perpage = 5;
// if ($page > 1) $fr = ($page - 1) * $perpage . ",";


// $sql_order = $sql_common . "WHERE mb_id='{$member['mb_id']}'  AND it_id !='' ORDER BY iq_time DESC LIMIT {$fr}{$perpage}";

// $sql_qna = " select *
//           $sql_common
//           $sql_search
//           WHERE mb_id='{$member['mb_id']}'  AND it_id !='' ORDER BY iq_time DESC limit $fr $perpage ";
// $result = sql_query($sql_qna);

// $sql_cnt = " select count(*) as cnt
//          $sql_common
//          $sql_search
//          ";
// $row = sql_fetch($sql_cnt);
// $total_count = $row['cnt'];


$perpage = 5;
if ($page > 1) $fr = ($page - 1) * $perpage . ",";
$sql_cnt_qna = "SELECT COUNT(*) AS CNT FROM lt_qa_content WHERE mb_id='{$member['mb_id']}' AND qa_type=0 AND it_id=''";
$cnt_qna = sql_fetch($sql_cnt_qna);


$sql_qna = "SELECT q.*, (SELECT qa_content FROM lt_qa_content AS a WHERE a.qa_parent=q.qa_id AND a.qa_type=1) AS qa_answer FROM lt_qa_content AS q WHERE mb_id='{$member['mb_id']}' AND qa_type=0 AND it_id='' ORDER BY qa_datetime DESC LIMIT {$fr}{$perpage}";
$db_qna = sql_query($sql_qna);



$total_count = $cnt_qna['CNT'];



//$rows = $config['cf_page_rows'];
$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

 // ah i dlrk djqtdjtj rmfjsrk ?

// $qstr = "it_id=".$it_id;
// $paging = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page=' , '#product-desc-qna-wrapper');

?>
<? for ($oi = 0; $qna = sql_fetch_array($db_qna); $oi++) : ?>
    <tr height="116px" class="on-small" style="border-bottom : 1px solid #e0e0e0;">
                            <td style="font-size: 14px; font-weight: normal; border:0px" onclick="openAnswer_cus(this)">
                                <!-- 화살표 ㅋㅋ -->

                                <!-- ///  -->
                                <div style="text-align: left; font-size: 16px;  font-weight: 500;  line-height: normal; color: #333333;"><?= $qna['qa_category'] ?></div>
                                <div style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden; width:100px; display:block;font-size: 12px;  font-weight: normal; color: #3a3a3a;"><?= $qna['qa_subject'] ?>
                                    <? if (!empty($qna['qa_answer'])) : ?>
                                        <img src="/img/mobile/gnb_bg2.png">
                                    <? endif ?>
                                </div>
                                <div style="text-align: left;font-size: 12px;  font-weight: normal;  color: #959595"> <span class="lt-col-4"><?= date("Y.m.d", strtotime($qna['qa_datetime'])) ?></span></div>
                                <div style="text-align: left; font-size: 12px;  font-weight: normal;  color: #f93f00;"><?= $qna['qa_answer'] ? "답변완료" : "답변대기" ?></div>

                            </td>
                            <td style="border:0px;font-size: 14px; font-weight: normal; color: #f54600;border:0px;">
                            <? if (!empty($qna['qa_answer'])) : ?>
                                <div style="margin-left:5px; margin-bottom:-20px;">답변완료</div>
                                <br>
                            <? else : ?>
                                <br>
                            <? endif ?>
                                <div class="mo_delete_1_btn" style="margin-top:7px" onclick="cusDelete('<?= $qna['qa_id'] ?>')" >삭제</div>


                                <!-- <div class="mo_modify_btn" onclick="qaUpdate('<?= $qna['iq_id'] ?>')">수정</div> -->
                                <!-- <div class="mo_delete_btn" style="margin-top:7px" onclick="qaDelete('<?= $qna['iq_id'] ?>')">삭제</div> -->
                                <!-- <div class="mo_delete_btn" style="margin-bottom:-35px" >삭제</div> -->
                                <!-- 삭제버튼 ㅋㅋㅋㅋㅋㅋㅋㅋㅋ
                                     -->
                                <!-- <button type="button" class="btn btn-black btn-list" onclick="qaDelete('<?= $qna['iq_id'] ?>')">삭제</button> -->
                            </td>
                        </tr>


                        <? if (!empty($qna['qa_answer'])) : ?>
                        <!-- 이건 답변 같음  -->
                        <tr class="qna-content">
                            <td class="on-big"></td>
                            <td colspan=4 id="qna-content-answer">
                            
                                <div class="qna-answer" style="margin-bottom: 15px;">
                                    <span style ="font-size: 12px; color:#3a3a3a font-weight: normal;";> 답변 : <?= $qna['qa_answer'] ?> </span>
                                </div>
                                <? if ($qna['qa_file1']) : ?>
                                        <div><img src="/data/qa/<?= $qna['qa_file1'] ?>" class="review-thumbnail-answer" id="imgQAFile1"></div>
                                        <br>
                                <? endif ?>
                                <? if ($qna['qa_file2']) : ?>
                                        <div><img src="/data/qa/<?= $qna['qa_file2'] ?>" class="review-thumbnail-answer" id="imgQAFile2"></div>
                                <? endif ?>
                            </td>
                        </tr>
                        <? endif ?>
<? endfor ?>


