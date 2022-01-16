<?php
$qaconfig = get_qa_config();

if (!isset($it) && !get_session("ss_tv_idx")) {
    if (!headers_sent()) {  //헤더를 보내기 전이면 검색엔진에서 제외합니다.
        echo '<meta name="robots" content="noindex, nofollow">';
    }
}

$sql_common = " FROM {$g5['qa_content_table']} AS qa WHERE qa_type=0 AND it_id='{$it_id}'";
$sql_order = " ORDER BY qa_num LIMIT 5";

// 테이블의 전체 레코드수만 얻음

$sql_count = "SELECT count(*) as cnt $sql_common";
$db_count = sql_fetch($sql_count);
$total_count = $db_count['cnt'];

$sql_qna = "SELECT *, (SELECT qc.qa_content FROM {$g5['qa_content_table']} AS qc WHERE qc.qa_type=1 AND qc.qa_parent=qa.qa_id) AS qa_answer $sql_common $sql_order";
$result = sql_query($sql_qna);

$list = array();

for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $list[$i] = $row;
    $list[$i]['category'] = get_text($row['qa_category']);
    $list[$i]['icon_file'] = '';
    $list[$i]['content'] = nl2br(strip_tags($row['qa_content']));
    $list[$i]['answer'] = nl2br(strip_tags($row['qa_answer']));
    $list[$i]['name'] = get_text($row['qa_name']);
    $list[$i]['date'] = date("Y.m.d", strtotime($row['qa_datetime']));
}

include_once(G5_VIEW_PATH . "/product.detail.qna.php");
