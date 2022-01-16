<?php
if ($auth[substr('95',0,2)] || $is_admin == 'super') {
    $menu['menu940'] = array (
        array('940000', '기타관리', G5_ADMIN_URL.'/', 'shop_admin/sabang'),
        array('940100', '사방넷 상품재고전송', G5_ADMIN_URL.'/shop_admin/sabang/sabang_goods.send.php'),
        array('940110', '사방넷 요약수정(품절/공급중)', G5_ADMIN_URL.'/shop_admin/sabang/sabang_goods_simple_send.php'),
        array('940120', '제휴몰 가격 운영 관리', G5_ADMIN_URL.'/shop_admin/sabang/mall_price_stat.php'),
        array('940200', '세트상품설정', G5_ADMIN_URL.'/shop_admin/sabang/sabang_set_code_mapping.php'),
        array('940300', '제휴몰계정관리', G5_ADMIN_URL.'/shop_admin/sabang/mall_account.php'),
        array('940400', '제휴몰상세HTML제작', G5_ADMIN_URL.'/shop_admin/sabang/mall_detail_html_create.php')
        
        
    );
    
} else {
    $menu['menu940'] = array (
        array('940000', '기타관리', G5_ADMIN_URL.'/', 'shop_admin/sabang'),
        array('940100', '사방넷 상품재고전송', G5_ADMIN_URL.'/shop_admin/sabang/sabang_goods.send.php'),
        array('940110', '사방넷 요약수정(품절/공급중)', G5_ADMIN_URL.'/shop_admin/sabang/sabang_goods_simple_send.php'),
        array('940120', '제휴몰 가격 운영 관리', G5_ADMIN_URL.'/shop_admin/sabang/mall_price_stat.php'),
        array('940200', '세트상품설정', G5_ADMIN_URL.'/shop_admin/sabang/sabang_set_code_mapping.php')
        
        
    );
}

?>