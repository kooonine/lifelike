<?php
include_once('./_common.php');

check_admin_token();

$sql_points =  "SELECT
                cf_review_write_point AS review_write,
                cf_review_photo_point AS review_photo,
                cf_review_first_point AS review_first
                FROM {$g5['config_table']}";
$cf_points = sql_fetch($sql_points);

if ($_POST['btn_submit'] == '일괄답글') {

    $txt_selected_review_num = $_POST['txt_selected_review_num'];
    $review_reply = $_POST['review_reply'];

    $selected_review_nums = explode(",", $txt_selected_review_num);

    $count = count($selected_review_nums);

    if (!$count) {
        alert($_POST['btn_submit'] . ' 하실 항목을 하나 이상 선택하세요.');
    }

    for ($i = 0; $i < count($selected_review_nums); $i++) {

        $is_id = $selected_review_nums[$i];

        // 포인트 지급
        $is = sql_fetch("SELECT * FROM lt_shop_item_use WHERE is_id = '$is_id'");
        $is_point = 0;

        if ($is['is_confirm'] == 1 && $is['is_point'] == 0) {
            if ($is['is_file'] > 0 && mb_strlen($is['is_content'], "UTF-8") >= 50) {
                $is_point += $cf_points['review_photo'];
                insert_point($is['mb_id'], $cf_points['review_photo'], "포토리뷰작성({$is['is_subject']})", 'item_use', $is_id, "포토리뷰작성");
            } else {
                $is_point += $cf_points['review_write'];
                insert_point($is['mb_id'], $cf_points['review_write'], "리뷰작성({$is['is_subject']})", 'item_use', $is_id, "리뷰작성");
            }

            $is_first = sql_fetch("SELECT is_id FROM lt_shop_item_use WHERE it_id = '" . $is['it_id'] . "' ORDER BY is_time ASC LIMIT 1");

            if ($is_first['is_id'] == $is_id) {
                $is_point += $cf_points['review_first'];
                insert_point($is['mb_id'], $cf_points['review_first'], "최초리뷰작성({$is['is_subject']})", 'item_use', $is_id, "최초리뷰작성");
            }
        }

        $sql = "UPDATE lt_shop_item_use
                SET is_reply_content = '{$review_reply}'
                    ,ls_reply_mb_id = '{$member['mb_id']}'
                    ,is_reply_name = '{$member['mb_name']}'
                    ,is_point = '{$is_point}'
                WHERE is_id = '$is_id'";
        sql_query($sql);
    }
    $msg = '일괄 답글 완료';

    alert($msg, './review_management.php?page=' . $page . $qstr, false);
} else if ($_POST['btn_submit'] == '답글') {
    $is_confirm = $_POST['is_confirm'];
    $is_spam = $_POST['is_spam'];
    $review_reply = $_POST['is_reply_content'];
    $is_id = $_POST['is_id'];

    // 포인트 지급
    $is = sql_fetch("SELECT * FROM lt_shop_item_use WHERE is_id = '$is_id'");
    $is_point = 0;

    if ($is_confirm == 1 && $is['is_point'] == 0) {
        if ($is['is_file'] > 0 && mb_strlen($is['is_content'], "UTF-8") >= 50) {
            $is_point += $cf_points['review_photo'];
            insert_point($is['mb_id'], $cf_points['review_photo'], "포토리뷰작성({$is['is_subject']})", 'item_use', $is_id, "포토리뷰작성");
        } else {
            $is_point += $cf_points['review_write'];
            insert_point($is['mb_id'], $cf_points['review_write'], "리뷰작성({$is['is_subject']})", 'item_use', $is_id, "리뷰작성");
        }

        $is_first = sql_fetch("SELECT is_id FROM lt_shop_item_use WHERE it_id = '" . $is['it_id'] . "' ORDER BY is_time ASC LIMIT 1");

        if ($is_first['is_id'] == $is_id) {
            $is_point += $cf_points['review_first'];
            insert_point($is['mb_id'], $cf_points['review_first'], "최초리뷰작성({$is['is_subject']})", 'item_use', $is_id, "최초리뷰작성");
        }
    }

    $sql = "UPDATE lt_shop_item_use
            SET is_reply_content = '{$review_reply}'
                ,ls_reply_mb_id = '{$member['mb_id']}'
                ,is_reply_name = '{$member['mb_name']}'
                ,is_spam = '{$is_spam}'
                ,is_confirm = '{$is_confirm}'
                ,is_point = '{$is_point}'
            WHERE is_id = '{$is_id}'";
    sql_query($sql);

    $msg = '저장 완료';

    alert($msg, './review_management.php?page=' . $page . $qstr, false);
} else if ($_POST['btn_submit'] == '베스트') {

    $txt_selected_review_num = $_POST['txt_review_num'];

    if ($_POST['rdo_best_pointYN']) $point_num = $_POST['txt_best_point_num'];
    else $point_num = '';

    $is_best = $_POST['rdo_best_selectYN'];

    $selected_review_nums = explode(",", $txt_selected_review_num);

    $count = count($selected_review_nums);

    if (!$count) {
        alert($_POST['btn_submit'] . ' 하실 항목을 하나 이상 선택하세요.');
    }

    for ($i = 0; $i < count($selected_review_nums); $i++) {
        $is_id = $selected_review_nums[$i];

        // 포인트 지급
        $is = sql_fetch("SELECT * FROM lt_shop_item_use WHERE is_id = '$is_id'");
        $is_point = 0;

        if ($is['is_confirm'] == 1 && $is['is_point'] == 0) {
            if ($is['is_file'] > 0 && mb_strlen($is['is_content'], "UTF-8") >= 50) {
                $is_point += $cf_points['review_photo'];
                insert_point($is['mb_id'], $cf_points['review_photo'], "포토리뷰작성({$is['is_subject']})", 'item_use', $is_id, "포토리뷰작성");
            } else {
                $is_point += $cf_points['review_write'];
                insert_point($is['mb_id'], $cf_points['review_write'], "리뷰작성({$is['is_subject']})", 'item_use', $is_id, "리뷰작성");
            }

            $is_first = sql_fetch("SELECT is_id FROM lt_shop_item_use WHERE it_id = '" . $is['it_id'] . "' ORDER BY is_time ASC LIMIT 1");

            if ($is_first['is_id'] == $is_id) {
                $is_point += $cf_points['review_first'];
                insert_point($is['mb_id'], $cf_points['review_first'], "최초리뷰작성({$is['is_subject']})", 'item_use', $is_id, "최초리뷰작성");
            }
        }

        // $sql = " update lt_shop_item_use
        //         set is_best = '" . $is_best . "'
        //             ,is_point = '" . $point_num . "'
        //         where  is_id = '$is_id'";
        $sql = "UPDATE lt_shop_item_use
                SET is_best = '{$is_best}'
                    ,is_point = '{$is_point}'
                WHERE  is_id = '{$is_id}'";
        sql_query($sql);

        // 베스트 여부 상관없이 포인트 적용 - 200526 balance@panpacific.co.kr
        /*
        //베스트값이 변경됨
        if($is_best != $is['is_best'])
        {
            if($is_best && $point_num != '' ) {
                //베스트 지정으로 포인트 지급
                insert_point($is['mb_id'], $point_num, "{$is['is_subject']} {$is_id} 베스트선정", 'item_use', $is_id, '베스트선정');
            } else {
                //베스트 지정해제로 포인트 삭제
                //$mb_id, $rel_table, $rel_id, $rel_action)
                delete_point($is['mb_id'], 'item_use', $is_id, '베스트선정');
            }
        }
        */
    }
    $msg = '베스트 처리 완료';

    alert($msg, './review_management.php?page=' . $page . $qstr, false);
} elseif ($_POST['btn_submit'] == '전시') {

    $count = count($_POST['chk']);

    if (!$count) {
        alert($_POST['btn_submit'] . ' 하실 항목을 하나 이상 선택하세요.');
    }

    for ($i = 0; $i < count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $is_id = $_POST['is_id'][$k];

        // 포인트 지급
        $is = sql_fetch("SELECT * FROM lt_shop_item_use WHERE is_id = '$is_id'");
        $is_point = 0;

        if ($is['is_point'] == 0) {
            if ($is['is_file'] > 0 && mb_strlen($is['is_content'], "UTF-8") >= 50) {
                $is_point += $cf_points['review_photo'];
                insert_point($is['mb_id'], $cf_points['review_photo'], "포토리뷰작성({$is['is_subject']})", 'item_use', $is_id, "포토리뷰작성");
            } else {
                $is_point += $cf_points['review_write'];
                insert_point($is['mb_id'], $cf_points['review_write'], "리뷰작성({$is['is_subject']})", 'item_use', $is_id, "리뷰작성");
            }

            $is_first = sql_fetch("SELECT is_id FROM lt_shop_item_use WHERE it_id = '" . $is['it_id'] . "' ORDER BY is_time ASC LIMIT 1");

            if ($is_first['is_id'] == $is_id) {
                $is_point += $cf_points['review_first'];
                insert_point($is['mb_id'], $cf_points['review_first'], "최초리뷰작성({$is['is_subject']})", 'item_use', $is_id, "최초리뷰작성");
            }
        }

        //전시
        $sql = "UPDATE lt_shop_item_use
                SET is_confirm = '1'
                ,is_point = '{$is_point}'
                WHERE is_id = '{$is_id}'";
        sql_query($sql);

        // 기존 포인트 적립 프로세스 무시 - 200526 balance@panpacific.co.kr
        /*
        $sql = "SELECT b.od_type, b.od_id, b.ct_id, b.ct_price, c.it_point_type, c.it_point, a.mb_id, a.is_subject
                FROM lt_shop_item_use AS a
                LEFT JOIN lt_shop_cart AS b ON a.ct_id = b.ct_id
                LEFT JOIN lt_shop_item AS c ON b.it_id = c.it_id
                WHERE a.is_id = '$is_id' ";
        $is = sql_fetch($sql);

        if ($is['od_type'] == 'O' && $is['it_point_type'] != '9') {
            $point_num = 0;
            if ($is['it_point_type'] == "0") {
                //적립금액 (원)
                $point_num = (int) $is['it_point'];
            } elseif ($is['it_point_type'] == "3") {
                //적립율(%) - 고정
                $point_num = ceil((int) $is['ct_price'] / 100 * (int) $default['de_point_percent']);
            } elseif ($is['it_point_type'] == "2") {
                //적립율(%) - 지정
                $point_num = ceil((int) $is['ct_price'] / 100 * (int) $is['it_point']);
            }
            insert_point($is['mb_id'], $point_num, "주문번호 {$is['od_id']} {$is['is_subject']} 리뷰작성", 'item_use', $is['od_id'], $is['ct_id'] . '리뷰작성');

            $op = sql_fetch("select ifnull(sum(po_point),0) as po_point from lt_point a where a.po_rel_table = 'item_use' and a.po_rel_id = '{$is['od_id']}' ");
            // echo print_r2($op);
            //주문정보에 적립금 update
            $sql = " update lt_shop_order
                set od_point = '{$op['po_point']}'
                where  od_id = '{$is['od_id']}'";

            sql_query($sql);
            //echo $sql.'<br>';

            $sql = " update lt_shop_item_use
                set is_point = '" . $point_num . "'
                where  is_id = '$is_id'";
            //echo $sql.'<br>';
            sql_query($sql);
        }
        */
    }
    $msg = '해당 게시물에 일괄 ' . $_POST['btn_submit'] . ' 하였습니다.';

    alert($msg, './review_management.php?page=' . $page . $qstr, false);
} elseif ($_POST['btn_submit'] == '전시해제') {

    $count = count($_POST['chk']);

    if (!$count) {
        alert($_POST['btn_submit'] . ' 하실 항목을 하나 이상 선택하세요.');
    }

    for ($i = 0; $i < count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $is_id = $_POST['is_id'][$k];

        $is = sql_fetch("select * from lt_shop_item_use where is_id = '$is_id' ");
        //전시
        $sql = " update lt_shop_item_use
                set is_confirm = '0'
                where  is_id = '$is_id'";
        //echo $sql;
        sql_query($sql);
    }
    $msg = '해당 게시물에 일괄 ' . $_POST['btn_submit'] . ' 하였습니다.';

    alert($msg, './review_management.php?page=' . $page . $qstr, false);
} elseif($btn_submit_list == '순위선정') {
    foreach($reviewRank as $key=>$value) {
        sql_query(" UPDATE lt_shop_item_use SET is_rank = null WHERE is_rank = $value ");
        sql_query(" UPDATE lt_shop_item_use SET is_rank = $value WHERE is_id = $key ");
    }
    $result = 'success';
    echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    return;
}  else {
    alert('올바른 방법으로 이용해 주세요');
}
