<?php
include_once('./_common.php');

$wr_type = '1';

for ($i=0; $i<count($_POST['chk']); $i++) {
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];
    $bo_table = $_POST['board_table'][$k];
    $write_table = $g5['write_prefix'] . $bo_table;
    $wr_id = $_POST['wr_id'][$k];
    
    if($_POST['btn_submit'] == '선택삭제'){
        //삭제 대상 테이블에 복사
        $sql = " insert into lt_write_delete
                    (bo_table, wr_del_datetime, wr_del_mb_id, wr_del_mb_name
                    , wr_id, wr_num, wr_reply, wr_parent, wr_is_comment, wr_comment, wr_comment_reply, ca_name, wr_option, wr_subject, wr_content, wr_content_mobile, wr_link1, wr_link2, wr_link1_hit, wr_link2_hit, wr_hit, wr_good, wr_nogood, mb_id, wr_password, wr_name, wr_email, wr_homepage, wr_datetime, wr_file, wr_last, wr_ip, wr_facebook_user, wr_twitter_user, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_point )
                select '{$bo_table}' as bo_table, now() as wr_del_datetime, '{$member['mb_id']}' as wr_del_mb_id, '{$member['mb_name']}' as wr_del_mb_name
                    ,wr_id, wr_num, wr_reply, wr_parent, wr_is_comment, wr_comment, wr_comment_reply, ca_name, wr_option, wr_subject, wr_content, wr_content_mobile, wr_link1, wr_link2, wr_link1_hit, wr_link2_hit, wr_hit, wr_good, wr_nogood, mb_id, wr_password, wr_name, wr_email, wr_homepage, wr_datetime, wr_file, wr_last, wr_ip, wr_facebook_user, wr_twitter_user, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_point 
                from $write_table where wr_parent = '{$wr_id}'; ";
        //echo $sql;
        sql_query($sql);
        
        // 삭제대상 테이블로 복사된 원글 게시글 삭제
        sql_query(" delete from $write_table where wr_parent = '{$wr_id}' ");
        
        $count_write = sql_fetch("select count(*) cnt from lt_write_delete where bo_table='{$bo_table}' and wr_parent = '{$wr_id}' and wr_is_comment = '0'; ");
        $count_comment = sql_fetch("select count(*) cnt from lt_write_delete where bo_table='{$bo_table}' and wr_parent = '{$wr_id}' and wr_is_comment != '0'; ");
        
        // 글숫자 감소
        if ($count_write['cnt'] > 0 || $count_comment['cnt'] > 0)
            sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write - '{$count_write['cnt']}', bo_count_comment = bo_count_comment - '{$count_comment['cnt']}' where bo_table = '$bo_table' ");
            
        $wr_type = '1';
    } elseif($_POST['btn_submit'] == '댓글선택삭제'){
        
        $wr = sql_fetch("select wr_parent from $write_table where wr_id = '{$wr_id}' ");
        //삭제 대상 테이블에 복사
        $sql = " insert into lt_write_delete
                    (bo_table, wr_del_datetime, wr_del_mb_id, wr_del_mb_name
                    , wr_id, wr_num, wr_reply, wr_parent, wr_is_comment, wr_comment, wr_comment_reply, ca_name, wr_option, wr_subject, wr_content, wr_content_mobile, wr_link1, wr_link2, wr_link1_hit, wr_link2_hit, wr_hit, wr_good, wr_nogood, mb_id, wr_password, wr_name, wr_email, wr_homepage, wr_datetime, wr_file, wr_last, wr_ip, wr_facebook_user, wr_twitter_user, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_point )
                select '{$bo_table}' as bo_table, now() as wr_del_datetime, '{$member['mb_id']}' as wr_del_mb_id, '{$member['mb_name']}' as wr_del_mb_name
                    ,wr_id, wr_num, wr_reply, wr_parent, wr_is_comment, wr_comment, wr_comment_reply, ca_name, wr_option, wr_subject, wr_content, wr_content_mobile, wr_link1, wr_link2, wr_link1_hit, wr_link2_hit, wr_hit, wr_good, wr_nogood, mb_id, wr_password, wr_name, wr_email, wr_homepage, wr_datetime, wr_file, wr_last, wr_ip, wr_facebook_user, wr_twitter_user, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_point
                from $write_table where wr_id = '{$wr_id}'; ";
        //echo $sql;
        sql_query($sql);
        
        // 삭제대상 테이블로 복사된 원글 게시글 삭제
        sql_query(" delete from $write_table where wr_id = '{$wr_id}' ");
        
        $wr_parent = $wr['wr_parent'];
        
        $count_write = sql_fetch("select count(*) cnt from lt_write_delete where bo_table='{$bo_table}' and wr_parent = '{$wr_parent}' and wr_is_comment = '0'; ");
        $count_comment = sql_fetch("select count(*) cnt from lt_write_delete where bo_table='{$bo_table}' and wr_parent = '{$wr_parent}' and wr_is_comment != '0'; ");
        
        // 글숫자 감소
        if ($count_write['cnt'] > 0 || $count_comment['cnt'] > 0){
            sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write - '{$count_write['cnt']}', bo_count_comment = bo_count_comment - '{$count_comment['cnt']}' where bo_table = '$bo_table' ");
        }
        
        $wr_type = '2';
    }
}

$msg = '해당 게시물을 삭제 하였습니다. \n90일 이후에는 완전삭제되어 복원하실 수 없습니다.';

alert($msg, $rtnURL.'?wr_type='.$wr_type.'&amp;page='.$page.$qstr, false);
?>
