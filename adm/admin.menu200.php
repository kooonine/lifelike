<?php
$menu['menu200'] = array(
    array('200000', '운영관리', G5_ADMIN_URL . '/', 'member')

    //,array('200100', '메시지발송관리', G5_ADMIN_URL.'/', '', array (
    , array('200110', 'Email 발송', G5_ADMIN_URL . '/operation/configform_sendEmail.php', 'configform_sendEmail'), array('200120', 'Email 발송 내역', G5_ADMIN_URL . '/operation/configform_sendEmail_history.php', 'configform_sendEmail_history')
    // , array('200130', 'SMS 자동발송 설정', G5_ADMIN_URL . '/operation/configform_sms_autoSend_config.php', 'configform_sms_autoSend_config'), array('200140', 'SMS 자동발송 기간 설정', G5_ADMIN_URL . '/operation/configform_sms_autoSend_time_config.php', 'configform_sms_autoSend_time_config'), array('200150', 'SMS 발송', G5_ADMIN_URL . '/operation/configform_sms_send.php', 'configform_sms_send'), array('200160', 'SMS 발송내역 조회', G5_ADMIN_URL . '/operation/configform_sms_send_history.php', 'configform_sms_send_history')
    , array('200160', '품절 SMS 대량 발송', G5_ADMIN_URL . '/operation/configform_sms_send_soldout.php', 'configform_sms_send_soldout')

    , array('200171', 'PUSH 메시지 작성', G5_ADMIN_URL . '/operation/configform_app_push_send.php', 'configform_app_push_send')
    , array('200170', 'PUSH 예약내역 조회', G5_ADMIN_URL . '/operation/configform_app_push_reservation.php', 'configform_app_push_reservation_history')
    // , array('200170', 'PUSH 메시지 작성', G5_ADMIN_URL . '/operation/configform_app_push.php', 'configform_app_push')
    , array('200180', 'PUSH 발송내역 조회', G5_ADMIN_URL . '/operation/configform_app_push_history.php', 'configform_app_push_history')
    //))

    , array('200210', '쿠폰내역 조회', G5_ADMIN_URL . '/operation/configform_coupon_list.php', 'configform_coupon_list'), array('200220', '자동발급쿠폰', G5_ADMIN_URL . '/operation/configform_coupon_create.php', 'configform_coupon_create'), array('200230', '다운로드쿠폰', G5_ADMIN_URL . '/shop_admin/couponzonelist.php', 'scf_coupon_zone'), array('200310', '적립금설정', G5_ADMIN_URL . '/operation/configform_saveMoney_config.php', 'configform_saveMoney_config'), array('200320', '적립금관리', G5_ADMIN_URL . '/operation/configform_saveMoney_management.php', 'configform_saveMoney_management'), array('200330', '수기지급적립금관리', G5_ADMIN_URL . '/operation/configform_saveMoney_handwriting.php', 'configform_saveMoney_handwriting'), array('200340', ' CNPLUS 변환기', G5_ADMIN_URL . '/convert.php',   'cnplusconvert'), array('200210', '재입고 SMS', G5_ADMIN_URL . '/shop_admin/itemstocksms.php', 'itemstocksms'), array('200220', '사용자 우회접속', G5_ADMIN_URL . '/into_inception.php', 'into_inception')
    /*
    ,array('700100', '회원설정', G5_ADMIN_URL.'/', '', array (
        array('700110', '회원가입항목설정', G5_ADMIN_URL.'/member_config.php', 'member_config')
        ))
        
        ,array('700200', '회원정보', G5_ADMIN_URL.'/', '', array (
            array('700210', '회원정보조회', G5_ADMIN_URL.'/member_list.php', 'mb_list')
            ))
            */
);
