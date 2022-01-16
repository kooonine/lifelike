<?php
include_once('./_common.php');

check_admin_token();

if($_POST['btn_submit'] == '일괄답글') {
    
    $txt_selected_review_num = $_POST['txt_selected_review_num'];
    $review_reply = $_POST['review_reply'];
    
    $selected_review_nums = explode(",", $txt_selected_review_num);
    
    $count = count($selected_review_nums);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    
    for ($i=0; $i<count($selected_review_nums); $i++) {
        
        $is_id = $selected_review_nums[$i];
        
        //$is_id
        //$review_reply
        
        //삭제 대상 테이블에서 원래 테이블로 복원
        $sql = " update lt_shop_item_use
                set is_reply_content = '{$review_reply}'
                    ,ls_reply_mb_id = '".$member['mb_id']."'
                    ,is_reply_name = '".$member['mb_name']."'
                where  is_id = '$is_id'";
        //echo $sql;
        sql_query($sql);
        
    }
    $msg = '해당 게시물에 일괄 답글 하였습니다.';
    
    alert($msg, './review_management.php?page='.$page.$qstr, false);
    
} else if($_POST['btn_submit'] == '답글') {
    
    $is_confirm = $_POST['is_confirm'];
    $is_spam = $_POST['is_spam'];
    $review_reply = $_POST['is_reply_content'];
    $is_id = $_POST['is_id'];
    
    //$is_id
    //$review_reply
    
    $sql = " update lt_shop_item_use
            set is_reply_content = '{$review_reply}'
                ,ls_reply_mb_id = '".$member['mb_id']."'
                ,is_reply_name = '".$member['mb_name']."'
                ,is_spam = '".$is_spam."'
                ,is_confirm = '".$is_confirm."'
            where  is_id = '$is_id'";
    //echo $sql;
    sql_query($sql);
        
    $msg = '해당 게시물에 답글을 저장하였습니다.';
    
    alert($msg, './review_management.php?page='.$page.$qstr, false);
    
} else if($_POST['btn_submit'] == '베스트') {
    
    $txt_selected_review_num = $_POST['txt_review_num'];
    
    if($_POST['rdo_best_pointYN']) $point_num = $_POST['txt_best_point_num'];
    else $point_num = '';
    
    $is_best = $_POST['rdo_best_pointYN'];
    
    $selected_review_nums = explode(",", $txt_selected_review_num);
    
    $count = count($selected_review_nums);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    
    for ($i=0; $i<count($selected_review_nums); $i++) {
        
        $is_id = $selected_review_nums[$i];
        
        $is = sql_fetch("select * from lt_shop_item_use where is_id = '$is_id' ");
        //$is_id
        //$review_reply
        
        $sql = " update lt_shop_item_use
                set is_best = '".$is_best."'
                    ,is_point = '".$point_num."'
                where  is_id = '$is_id'";
        //echo $sql;
        sql_query($sql);
        
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
        
    }
    $msg = '해당 게시물에 베스트선정 처리 하였습니다.';
    
    alert($msg, './review_management.php?page='.$page.$qstr, false);
    
}elseif($_POST['btn_submit'] == '전시') {
    
    $count = count($_POST['chk']);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    
    for ($i=0; $i<count($_POST['chk']); $i++) {
        
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $is_id = $_POST['is_id'][$k];

        //전시
        $sql = " update lt_shop_item_use
                set is_confirm = '1'
                where  is_id = '$is_id'";
        //echo $sql.'<br>';
        sql_query($sql);
        
        $sql = "select  b.od_type, b.od_id, b.ct_id, b.ct_price, c.it_point_type, c.it_point, a.mb_id
                                , a.is_subject
                        from    lt_shop_item_use as a
                                left join lt_shop_cart as b on a.ct_id = b.ct_id
                                left join lt_shop_item as c on b.it_id = c.it_id
                            where   a.is_id = '$is_id' ";
        $is = sql_fetch($sql);
        //echo $sql.'<br>';
        //echo print_r2($is);
        
        if($is['od_type'] == 'O' && $is['it_point_type'] != '9'){
            $point_num = 0;
            if($is['it_point_type'] == "0"){
                //적립금액 (원)
                $point_num = (int)$is['it_point'];
            }elseif($is['it_point_type'] == "3"){
                //적립율(%) - 고정
                $point_num = ceil((int)$is['ct_price'] / 100 * (int)$default['de_point_percent']);
            }elseif($is['it_point_type'] == "2"){
                //적립율(%) - 지정
                $point_num = ceil((int)$is['ct_price'] / 100 * (int)$is['it_point']);
            }
            insert_point($is['mb_id'], $point_num, "주문번호 {$is['od_id']} {$is['is_subject']} 리뷰작성", 'item_use', $is['od_id'], $is['ct_id'].'리뷰작성');
            
            $op = sql_fetch("select ifnull(sum(po_point),0) as po_point from lt_point a where a.po_rel_table = 'item_use' and a.po_rel_id = '{$is['od_id']}' ");
           // echo print_r2($op);
            //주문정보에 적립금 update
            $sql = " update lt_shop_order
                set od_point = '{$op['po_point']}'
                where  od_id = '{$is['od_id']}'";
            
            sql_query($sql);
            //echo $sql.'<br>';
            
            $sql = " update lt_shop_item_use
                set is_point = '".$point_num."'
                where  is_id = '$is_id'";
            //echo $sql.'<br>';
            sql_query($sql);
        }
        
    }
    $msg = '해당 게시물에 일괄 '.$_POST['btn_submit'].' 하였습니다.';
    
    alert($msg, './review_management.php?page='.$page.$qstr, false);
    
} elseif($_POST['btn_submit'] == '전시해제') {
    
    $count = count($_POST['chk']);
    
    if(!$count) {
        alert($_POST['btn_submit'].' 하실 항목을 하나 이상 선택하세요.');
    }
    
    for ($i=0; $i<count($_POST['chk']); $i++) {
        
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
    $msg = '해당 게시물에 일괄 '.$_POST['btn_submit'].' 하였습니다.';
    
    alert($msg, './review_management.php?page='.$page.$qstr, false);
    
} else {
    alert('올바른 방법으로 이용해 주세요');
}
?>