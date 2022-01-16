<?php

if ( $member['mb_id'] == 'azaxac123') {
    $menu['menu960'] = array (
        array('960000', 'B2B특판관리', G5_ADMIN_URL.'/', 'shop_admin/b2border'),
        array('960300', '특판발주서', G5_ADMIN_URL.'/shop_admin/b2border/b2b_order_form.php')        
    );
    
} else {
    $menu['menu960'] = array (
        array('960000', 'B2B특판관리', G5_ADMIN_URL.'/', 'shop_admin/b2border'),
        array('960100', '업체관리', G5_ADMIN_URL.'/shop_admin/b2border/b2b_company.php'),
        array('960200', '특판주문서', G5_ADMIN_URL.'/shop_admin/b2border/b2b_order.php'),
        array('960300', '특판발주서', G5_ADMIN_URL.'/shop_admin/b2border/b2b_order_form.php'),
        array('960400', '상품등록', G5_ADMIN_URL.'/shop_admin/b2border/b2b_sale_item_list.php')
        
    );
}
?>