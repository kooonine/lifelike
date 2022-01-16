<?php
if ($is_admin == 'brand') {

    $sql = "select * from lt_member_company where mb_id = '{$member['mb_id']}' ";
    $cp = sql_fetch($sql);
    if (!$cp || $cp['cp_status'] == "승인요청" || $cp['cp_status'] == "승인반려") {
        $menu['menu920'] = array(
            array('920000', '브랜드', G5_ADMIN_URL . '/', 'brand'), array('920110', '판매자 정보관리', G5_ADMIN_URL . '/brand/company.php', '', 1), array('920120', '공지사항', G5_ADMIN_URL . '/brand/company_notice.php', '', 1)
        );
    } else {
        $menu['menu920'] = array(
            array('920000', '브랜드', G5_ADMIN_URL . '/', 'brand'), array('920110', '판매자 정보관리', G5_ADMIN_URL . '/brand/company.php', '', 1), array('920120', '공지사항', G5_ADMIN_URL . '/brand/company_notice.php', '', 1), array('920210', '고객문의관리', G5_ADMIN_URL . '/community/help_management.php', 'help_detail'), array('920230', '리뷰관리', G5_ADMIN_URL . '/community/review_management.php', 'review_management'), array('920310', '제품관리', G5_ADMIN_URL . '/shop_admin/itemlist.brand.php', 'scf_item'), array('920320', '제품등록', G5_ADMIN_URL . '/shop_admin/itemform.brand.php', 'itemform'), array('920410', '주문내역 현황', G5_ADMIN_URL . '/shop_admin/orderlist.brand.php', 'scf_order', 1), array('920430', '취소/반품 현황', G5_ADMIN_URL . '/shop_admin/claimlist.brand.php', 'scf_claim', 1), array('920510', '배송/반품설정',    G5_ADMIN_URL . '/brand/company_delivery.php',   'configformdelivery')
            //,array('920520', '배송업체관리',     G5_ADMIN_URL.'/configform_deliverycompany.php',   'configformdeliverycompany')

            , array('920610', '일별매출', G5_ADMIN_URL . '/brand/saledate.php', '', 1), array('920620', '주별매출', G5_ADMIN_URL . '/brand/saleweek.php', '', 1), array('920630', '월간매출', G5_ADMIN_URL . '/brand/salemonth.php', '', 1), array('920640', '연간매출', G5_ADMIN_URL . '/brand/saleyear.php', '', 1), array('920650', '결제수단별매출', G5_ADMIN_URL . '/brand/salecase.php', '', 1), array('920660', '판매상품순위', G5_ADMIN_URL . '/brand/saleitemrank1.php', 1)
            //,array('920670', '판매분류순위', G5_ADMIN_URL.'/brand/saleitemrank2.php', 1)
            , array('920680', '취소/반품순위', G5_ADMIN_URL . '/brand/saleitemrank3.php', 1), array('920690', '장바구니분석', G5_ADMIN_URL . '/brand/saleitemrank4.php', 1), array('920710', '정산관리', G5_ADMIN_URL . '/brand/company_cal_list.php', 1)
        );
    }
} else {
    $menu['menu920'] = array(
        array('920000', '입점사', G5_ADMIN_URL . '/', 'brand'), array('921000', '브랜드관리', G5_ADMIN_URL . '/brand/brand.list.php', '', 1), array('921000', '입점사관리', G5_ADMIN_URL . '/brand/company_list.php', '', 1), array('922000', '공지사항관리', G5_ADMIN_URL . '/brand/admin_notice.php', '', 1), array('923000', '제품승인관리', G5_ADMIN_URL . '/shop_admin/itemlist.brand.approve.php', '', 1)
    );
}
