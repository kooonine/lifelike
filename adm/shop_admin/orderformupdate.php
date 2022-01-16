<?php
$sub_menu = '400400';
include_once('./_common.php');

check_admin_token();

if($_POST['mod_type'] == 'info') {
    $od_zip1   = substr($_POST['od_zip'], 0, 3);
    $od_zip2   = substr($_POST['od_zip'], 3);
    $od_b_zip1 = substr($_POST['od_b_zip'], 0, 3);
    $od_b_zip2 = substr($_POST['od_b_zip'], 3);
    
    $sql = " update {$g5['g5_shop_order_table']}
                set od_name = '$od_name',
                    od_tel = '$od_tel',
                    od_hp = '$od_hp',
                    od_zip1 = '$od_zip1',
                    od_zip2 = '$od_zip2',
                    od_addr1 = '$od_addr1',
                    od_addr2 = '$od_addr2',
                    od_addr3 = '$od_addr3',
                    od_addr_jibeon = '$od_addr_jibeon',
                    od_email = '$od_email',
                    od_b_name = '$od_b_name',
                    od_b_tel = '$od_b_tel',
                    od_b_hp = '$od_b_hp',
                    od_b_zip1 = '$od_b_zip1',
                    od_b_zip2 = '$od_b_zip2',
                    od_b_addr1 = '$od_b_addr1',
                    od_b_addr2 = '$od_b_addr2',
                    od_b_addr3 = '$od_b_addr3',
                    od_b_addr_jibeon = '$od_b_addr_jibeon' ";
    if ($default['de_hope_date_use'])
        $sql .= " , od_hope_date = '$od_hope_date' ";
        
        $sql .= " where od_id = '$od_id' ";
        sql_query($sql);
} elseif($_POST['mod_type'] == 'rfid') {
    //rfid 정보 업데이트
    
    for ($i=0; $i<count($_POST['od_sub_id']); $i++)
    {
        $rf_serial = $_POST['rf_serial'][$i];
        $od_sub_id = $_POST['od_sub_id'][$i];
        if($od_sub_id){
            $sql = "update lt_shop_order_item
                    set rf_serial = '$rf_serial' ";
            $sql .= " where od_id = '$od_id' ";
            $sql .= " and   od_sub_id = '$od_sub_id' ";
            
            sql_query($sql);
        }
    }
    
} else if($_POST['mod_type'] == 'sh_memo_new') {
    
    $is_important = 0;
    if(isset($_POST['is_important']) && $_POST['is_important']) $is_important = 1;
    $sh_memo = $_POST['sh_memo'];
    $it_name = $_POST['it_name'];
    
    
    $sql = " insert into lt_shop_order_history
                    (od_id, is_important, it_name, sh_memo, sh_time, sh_ip, sh_mb_name, sh_mb_id)
                 values
                    ('$od_id', '$is_important', '$it_name', '$sh_memo', '".G5_TIME_YMDHIS."', '$REMOTE_ADDR', '{$member['mb_name']}', '{$member['mb_id']}'); ";
    sql_query($sql);
    
}  else if($_POST['mod_type'] == 'sh_memo_modify') {
    
    $is_important = 0;
    if(isset($_POST['is_important']) && $_POST['is_important']) $is_important = 1;
    $sh_memo = $_POST['sh_memo'];
    $it_name = $_POST['it_name'];
    $sh_id = $_POST['sh_id'];
    
    $sql = " update lt_shop_order_history
              set   is_important = '$is_important'
                    ,sh_memo = '$sh_memo'
                    ,it_name = '$it_name'";
    $sql .= " where sh_id = '$sh_id' ";
    sql_query($sql);
    
    
} else if($_POST['mod_type'] == 'sh_memo_del') {
    
    $sh_id = $_POST['sh_id'];
    
    $sql = "delete from lt_shop_order_history ";
    $sql .= " where sh_id = '$sh_id' ";
    sql_query($sql);
    
    
} else if($_POST['mod_type'] == 'memo') {
    $sql = "update {$g5['g5_shop_order_table']}
                set od_shop_memo = '$od_shop_memo' ";
    $sql .= " where od_id = '$od_id' ";
    sql_query($sql);
}

$qstr = "sort1=$sort1&amp;sort2=$sort2&amp;sel_field=$sel_field&amp;search=$search&amp;page=$page";

goto_url("./orderform.php?od_id=$od_id&amp;$qstr");
?>
