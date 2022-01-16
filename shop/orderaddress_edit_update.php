<?php
include_once('./_common.php');

if($is_guest)
    die('회원 로그인 후 이용해 주십시오.');


$ad_subject = $_POST['ad_subject'];
$ad_name = $_POST['ad_name'];
$ad_tel = $_POST['ad_tel'];
$ad_hp = $_POST['ad_hp'];
$ad_zip = $_POST['ad_zip'];
$ad_addr1 = $_POST['ad_addr1'];
$ad_addr2 = $_POST['ad_addr2'];
$ad_addr3 = $_POST['ad_addr3'];
$ad_id = $_POST['ad_id'];
$w = $_POST['w'];

$ad_zip1 = substr( $ad_zip, 0, 3 );
$ad_zip2 = substr( $ad_zip, 3, 2 );



if ($is_member) {
        // 실제 번호를 넘김
    if($w == 'u'){
        $sql = " update {$g5['g5_shop_order_address_table']}
                    set ad_subject = '{$ad_subject}'
                        , ad_tel = '{$ad_tel}'
                        , ad_hp = '{$ad_hp}'
                        , ad_name = '{$ad_name}'
                        , ad_zip1 = '{$ad_zip1}'
                        , ad_zip2 = '{$ad_zip2}'
                        , ad_addr1 = '{$ad_addr1}'
                        , ad_addr2 = '{$ad_addr2}'
                        , ad_addr3 = '{$ad_addr3}'";

        

        $sql .= " where ad_id = '{$ad_id}'
                    and mb_id = '{$member['mb_id']}' ";

        sql_query($sql);
    }else {
        $sql = " insert into {$g5['g5_shop_order_address_table']}
                    set ad_subject = '{$ad_subject}'
                        , mb_id = '{$member['mb_id']}'
                        , ad_default = 0
                        , ad_name = '{$ad_name}'
                        , ad_tel = '{$ad_tel}'
                        , ad_hp = '{$ad_hp}'
                        , ad_zip1 = '{$ad_zip1}'
                        , ad_zip2 = '{$ad_zip2}'
                        , ad_addr1 = '{$ad_addr1}'
                        , ad_addr2 = '{$ad_addr2}'
                        , ad_addr3 = '{$ad_addr3}'
                        , ad_jibeon = ''";
        sql_query($sql);
    }
}

goto_url(G5_SHOP_URL.'/orderaddress.php');
?>