<?php
include_once('./_common.php');

$count_write = 0;
$count_comment = 0;
$wr_type = '1';
$tmp_array = $_POST['chk2'];

$chk_count = count($tmp_array);
// 거꾸로 읽는 이유는 답변글부터 삭제가 되어야 하기 때문임
for ($i=$chk_count-1; $i>=0; $i--)
{
    $k = $tmp_array[$i];
    $bo_table = $_POST['board_table'][$k];
    $wr_id = $_POST['wr_id'][$k];
    
    $board = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table' ");
    
    if($_POST['btn_submit'] == '완전삭제'){
            
        $write = sql_fetch(" select * from lt_write_delete where bo_table = '$bo_table' and wr_id = '$wr_id' ");
        $sql = " select wr_id, mb_id, wr_is_comment, wr_content from lt_write_delete where bo_table = '$bo_table' and wr_parent = '{$write['wr_id']}' order by wr_id ";
        $result = sql_query($sql);
        while ($row = sql_fetch_array($result))
        {
            // 원글이라면
            if (!$row['wr_is_comment'])
            {
                $sql2 = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ";
                $result2 = sql_query($sql2);
                while ($row2 = sql_fetch_array($result2)) {
                    // 파일삭제
                    @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.str_replace('../', '',$row2['bf_file']));
                    
                    // 썸네일삭제
                    if(preg_match("/\.({$config['cf_image_extension']})$/i", $row2['bf_file'])) {
                        delete_board_thumbnail($bo_table, $row2['bf_file']);
                    }
                }
                
                // 에디터 썸네일 삭제
                delete_editor_thumbnail($row['wr_content']);
                
                // 파일테이블 행 삭제
                sql_query(" delete from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ");
                
                $count_write++;
            }
        }
        
        // 게시글 삭제
        sql_query(" delete from lt_write_delete where bo_table = '$bo_table' and wr_parent = '{$write['wr_id']}' ");
        
        // 최근게시물 삭제
        sql_query(" delete from {$g5['board_new_table']} where bo_table = '$bo_table' and wr_parent = '{$write['wr_id']}' ");
        
        // 스크랩 삭제
        sql_query(" delete from {$g5['scrap_table']} where bo_table = '$bo_table' and wr_id = '{$write['wr_id']}' ");
        
        $bo_notice = board_notice($board['bo_notice'], $write['wr_id']);
        sql_query(" update {$g5['board_table']} set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");
        $wr_type = '1';
    } else if($_POST['btn_submit'] == '댓글완전삭제'){
        
        // 댓글 삭제
        sql_query(" delete from lt_write_delete where bo_table = '$bo_table' and wr_id = '{$wr_id}' ");
        $wr_type = '2';
    }
}

delete_cache_latest($bo_table);
$msg = '해당 게시물을 완전 삭제하였습니다.';
alert($msg, $rtnURL.'?wr_type='.$wr_type.'&amp;page='.$page.$qstr, false);
?>
