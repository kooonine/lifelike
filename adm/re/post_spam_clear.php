<?php
include_once('./_common.php');

for ($i=0; $i<count($_POST['chk']); $i++) {
    
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];
    $bo_table = $_POST['board_table'][$k];
    $write_table = $g5['write_prefix'] . $bo_table;
    $wr_id = $_POST['wr_id'][$k];
    
    sql_query(" update {$write_table} set wr_8='' where wr_id = '{$wr_id}' ");
}


$msg = '해당 게시물을 스팸해제 적용하였습니다.';

alert($msg, $rtnURL.'?wr_type=1&amp;page='.$page.$qstr, false);
?>
