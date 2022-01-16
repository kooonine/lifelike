<?php
include_once('./_common.php');

if($is_guest)
    die('회원 로그인 후 이용해 주십시오.');

$count = count($_POST['ad_default']);

if (!$count) {
    alert('수정하실 항목을 선택하세요.');
}

if ($is_member) {
    //실제 번호를 넘김

    $sql = " update {$g5['g5_shop_order_address_table']}
            set ";

    if(!empty($_POST['ad_default'])) {
        sql_query(" update {$g5['g5_shop_order_address_table']} set ad_default = '0' where mb_id = '{$member['mb_id']}' ");

        $sql .= "ad_default = '1' ";
    }

    $sql .= " where ad_id = '".$_POST['ad_default']."'
                    and mb_id = '{$member['mb_id']}' ";
    sql_query($sql);
        
    if(!empty($_POST['ad_default'])) {
        $ad = sql_fetch("select * from {$g5['g5_shop_order_address_table']} where ad_id = '".$_POST['ad_default']."' and mb_id = '{$member['mb_id']}' ");
        
        $sql = " update {$g5['member_table']}
                    set mb_zip1 = '{$ad['ad_zip1']}',
                        mb_zip2 = '{$ad['ad_zip2']}',
                        mb_addr1 = '{$ad['ad_addr1']}',
                        mb_addr2 = '{$ad['ad_addr2']}',
                        mb_addr3 = '{$ad['ad_addr3']}',
                        mb_addr_jibeon = '{$ad['ad_addr_jibeon']}'
                  where mb_id = '{$member['mb_id']}' ";
        sql_query($sql);
    }

}

goto_url(G5_SHOP_URL.'/orderaddress.php');
?>