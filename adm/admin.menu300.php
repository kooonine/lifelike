<?php
$menu['menu300'] = array(
    array('300000', '제품관리', G5_ADMIN_URL . '/', 'shop'),
    array('300100', '분류관리', G5_ADMIN_URL . '/shop_admin/categorylist.php', 'scf_cate'),
    array('300101', '베스트관리', G5_ADMIN_URL . '/shop_admin/best.itemlist.php', 'scf_item'),

    array('300200', '제품관리', G5_ADMIN_URL . '/shop_admin/itemlist.php', 'scf_item'),

    array('300300', '제품등록', G5_ADMIN_URL . '/shop_admin/itemform0.php', 'itemform'),
    array('300400', '리스등록', G5_ADMIN_URL . '/shop_admin/itemform1.php', 'itemform'),
    array('300800', '일괄등록(사방넷)', G5_ADMIN_URL . '/upload.sabang.php', 'upload_sabang'),
    array('300900', '일괄수정(사방넷)', G5_ADMIN_URL . '/update.sabang.php', 'update_sabang'),
    array('800600', '필터관리', G5_ADMIN_URL . '/design/design_finditem.php', '')

    //array('300500', '쇼핑몰설정', G5_ADMIN_URL.'/shop_admin/configform.php', 'scf_config')
    /*
     ,array('700100', '회원설정', G5_ADMIN_URL.'/', '', array (
     array('700110', '회원가입항목설정', G5_ADMIN_URL.'/member_config.php', 'member_config')
     ))

     ,array('700200', '회원정보', G5_ADMIN_URL.'/', '', array (
     array('700210', '회원정보조회', G5_ADMIN_URL.'/member_list.php', 'mb_list')
     ))
     */
);
