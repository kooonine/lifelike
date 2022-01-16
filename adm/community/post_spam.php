<?php
include_once('./_common.php');

$spam_ip_block = substr($_POST['btn_submit'], 4, 1);
$spam_delete = substr($_POST['btn_submit'], 5, 1);
$spam_blacklist = substr($_POST['btn_submit'], 6, 1);

for ($i=0; $i<count($_POST['chk']); $i++) {
    
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];
    $bo_table = $_POST['board_table'][$k];
    $write_table = $g5['write_prefix'] . $bo_table;
    $wr_id = $_POST['wr_id'][$k];
    $write = sql_fetch(" select * from {$write_table} where wr_id = '{$wr_id}' ");
    
    sql_query(" update {$write_table} set wr_8='스팸' where wr_id = '{$wr_id}' ");
    
    //IP차단 등록
    if($spam_ip_block == '1') {
        
        $ipblock = sql_fetch(" select count(*) cnt from lt_ipblock where ib_bo_table = '{$bo_table}' and ib_wr_id = '{$write['wr_id']}' ");
        if($ipblock['cnt'] <= 0)
        {
            $sql = " insert lt_ipblock
                    set ib_datetime = '".G5_TIME_YMDHIS."'
                        ,ib_intercept_ip = '{$write['wr_ip']}'
                        ,ib_admin_id = '{$member['mb_id']}'
                        ,ib_mb_id = '{$write['mb_id']}'
                        ,ib_bo_table = '{$bo_table}'
                        ,ib_wr_id = '{$write['wr_id']}'
                        ";
            sql_query($sql);
            
            //접속 차단 config 반영
            sql_query("update lt_config set cf_intercept_ip = (select  group_concat(DISTINCT ib_intercept_ip SEPARATOR  '\n') from lt_ipblock)");
        }
    }
    
    //삭제하기
    if($spam_delete == '1') {
        
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
    }
    
    //블랙리스트 등록
    if($spam_blacklist == '1') {
        
    }
}


$msg = '해당 게시물을 스팸신고 적용하였습니다. \n삭제처리한 게시물은 90일 이후에는 완전삭제되어 복원하실 수 없습니다.';

alert($msg, $rtnURL.'?wr_type=1&amp;page='.$page.$qstr, false);
?>
