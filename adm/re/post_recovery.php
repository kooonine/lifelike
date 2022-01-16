<?php
include_once('./_common.php');

for ($i=0; $i<count($_POST['chk2']); $i++) {
    
    // 실제 번호를 넘김
    $k = $_POST['chk2'][$i];
    $write_table = $g5['write_prefix'] . $_POST['board_table'][$k];
    $wr_id = $_POST['wr_id'][$k];
    
    if($_POST['btn_submit'] == '선택복원'){
        //삭제 대상 테이블에서 원래 테이블로 복원
        $sql = " insert into $write_table
                    (wr_id, wr_num, wr_reply, wr_parent, wr_is_comment, wr_comment, wr_comment_reply, ca_name, wr_option, wr_subject, wr_content, wr_content_mobile, wr_link1, wr_link2, wr_link1_hit, wr_link2_hit, wr_hit, wr_good, wr_nogood, mb_id, wr_password, wr_name, wr_email, wr_homepage, wr_datetime, wr_file, wr_last, wr_ip, wr_facebook_user, wr_twitter_user, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_point )
                select wr_id, wr_num, wr_reply, wr_parent, wr_is_comment, wr_comment, wr_comment_reply, ca_name, wr_option, wr_subject, wr_content, wr_content_mobile, wr_link1, wr_link2, wr_link1_hit, wr_link2_hit, wr_hit, wr_good, wr_nogood, mb_id, wr_password, wr_name, wr_email, wr_homepage, wr_datetime, wr_file, wr_last, wr_ip, wr_facebook_user, wr_twitter_user, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_point 
                from lt_write_delete where bo_table = '{$_POST['board_table'][$k]}' and wr_parent = '{$wr_id}'; ";
        //echo $sql;
        sql_query($sql);
        
        // 삭제대상 테이블로 복사된 원글 게시글 삭제
        sql_query(" delete from lt_write_delete where bo_table = '{$_POST['board_table'][$k]}' and wr_parent = '{$wr_id}'; ");
        $wr_type = '1';
    } elseif($_POST['btn_submit'] == '댓글선택복원'){
        
        //삭제 대상 테이블에서 원래 테이블로 복원
        $sql = " insert into $write_table
                    (wr_id, wr_num, wr_reply, wr_parent, wr_is_comment, wr_comment, wr_comment_reply, ca_name, wr_option, wr_subject, wr_content, wr_content_mobile, wr_link1, wr_link2, wr_link1_hit, wr_link2_hit, wr_hit, wr_good, wr_nogood, mb_id, wr_password, wr_name, wr_email, wr_homepage, wr_datetime, wr_file, wr_last, wr_ip, wr_facebook_user, wr_twitter_user, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_point )
                select wr_id, wr_num, wr_reply, wr_parent, wr_is_comment, wr_comment, wr_comment_reply, ca_name, wr_option, wr_subject, wr_content, wr_content_mobile, wr_link1, wr_link2, wr_link1_hit, wr_link2_hit, wr_hit, wr_good, wr_nogood, mb_id, wr_password, wr_name, wr_email, wr_homepage, wr_datetime, wr_file, wr_last, wr_ip, wr_facebook_user, wr_twitter_user, wr_1, wr_2, wr_3, wr_4, wr_5, wr_6, wr_7, wr_8, wr_9, wr_10, wr_point
                from lt_write_delete where bo_table = '{$_POST['board_table'][$k]}' and wr_id = '{$wr_id}'; ";
        //echo $sql;
        sql_query($sql);
        
        // 삭제대상 테이블로 복사된 원글 게시글 삭제
        sql_query(" delete from lt_write_delete where bo_table = '{$_POST['board_table'][$k]}' and wr_id = '{$wr_id}'; ");
        $wr_type = '2';
    }
}
$msg = '해당 게시물을 복원 하였습니다.';

alert($msg, $rtnURL.'?wr_type='.$wr_type.'&amp;page='.$page.$qstr, false);
?>
