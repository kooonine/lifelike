<?php
include_once('./_common.php');

for ($i=0; $i<count($_POST['chk']); $i++) {
    
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];
    $bo_table = $_POST['board_table'][$k];
    $write_table = $g5['write_prefix'] . $bo_table;
    $wr_id = $_POST['wr_id'][$k];
    
    $write = sql_fetch(" select * from {$write_table} where wr_id = '{$wr_id}' ");
    
    $po_point = $_POST['po_point'];
    $po_rel_action = $_POST['po_rel_action'];
    
    insert_point($write['mb_id'], $po_point, $po_rel_action, $bo_table, $wr_id, '쓰기');
    //$mb_id, $point, $content='', $rel_table='', $rel_id='', $rel_action='', $expire=0
    
    //echo $write['mb_id'].','.$po_point.','.$po_rel_action.','.$bo_table.','.$wr_id.',쓰기 <br/>';
    
    $sql = " update $write_table set wr_point = wr_point + {$po_point} where wr_id = '{$wr_id}' ";
    sql_query($sql);
    //echo $sql;
}

$msg = '적립금이 적용되었습니다.';

alert($msg, $rtnURL.'?wr_type=1&amp;page='.$page.$qstr, false);
?>
