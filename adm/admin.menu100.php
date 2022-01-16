<?php
$menu['menu100'] = array(
    array('100000', '환경설정관리',      G5_ADMIN_URL . '',   'config')

    //,array('100100', '기본환경관리',     G5_ADMIN_URL.'',   '', array (
    , array('100110', '사업자정보',       G5_ADMIN_URL . '/configform_biz.php',   'configformbiz'), array('100120', '비밀번호변경',     G5_ADMIN_URL . '/configform_pwd.php',   'configformpwd'), array('100130', '이용약관설정',     G5_ADMIN_URL . '/configform_stipulation.php',   'configformstipulation'), array('100140', '기타이용안내설정', G5_ADMIN_URL . '/configform_etc.php',   'configformetc'), array('100150', '개인정보제공설정', G5_ADMIN_URL . '/configform_privacy.php',   'configformprivacy')
    //))
    //,array('100200', '사이트환경관리',   G5_ADMIN_URL.'',   '', array (
    , array('100220', 'IP접속제한관리',   G5_ADMIN_URL . '/configform_ip.php',   'configformip'), array('100240', '검색엔진 최적화',   G5_ADMIN_URL . '/configform_search.php',   'configformsearch'), array('100290', '메뉴설정', G5_ADMIN_URL . '/menu_list.php',     'cf_menu', 1)
    //))
    //,array('100400', '결제정보관리',     G5_ADMIN_URL.'',   '', array (
    , array('100410', '결제방식설정',     G5_ADMIN_URL . '/configform_payment.php',   'configformpayment')
    //    ,array('100420', '무통장입금계좌설정',     G5_ADMIN_URL.'/configform_bankaccount.php',   'configformbankaccount')
    // , array('100430', 'PG 이용현황',     G5_ADMIN_URL . '/configform_pg.php',   'configformpg')
    //    ,array('100440', '현금영수증서비스', G5_ADMIN_URL.'/configform_cash.php',   'configformcash')
    // , array('100450', '세금계산서서비스', G5_ADMIN_URL . '/configform_tax.php',   'configformtax')
    //))
    //,array('100500', '배송관리',         G5_ADMIN_URL.'',   '', array (
    , array('100510', '배송/반품설정',    G5_ADMIN_URL . '/configform_delivery.php',   'configformdelivery')
    //    ,array('100520', '배송업체관리',     G5_ADMIN_URL.'/configform_deliverycompany.php',   'configformdeliverycompany')
    //))
    //,array('100600', '관리자권한관리',  G5_ADMIN_URL.'',   '', array (
    , array('100610', '관리자 계정관리',    G5_ADMIN_URL . '/admin_list.php',   'auth_list')
    //))
    // , array('100900', 'phpinfo()',        G5_ADMIN_URL . '/phpinfo.php',       'cf_phpinfo')
    , array('101000', '환경설정', G5_ADMIN_URL . '/config_form.php',   'config')
);

/*

$menu['menu101'] = array (
    array('101000', '환경설정관리', G5_ADMIN_URL.'/configform_biz.php',   'config'),
    array('101100', '기본환경설정', G5_ADMIN_URL.'/configform_biz.php',   'configform', array (
		array('101110', '사업자정보', G5_ADMIN_URL.'/configform_biz.php',   'configformbiz'),
		array('101120', '비밀번호변경', G5_ADMIN_URL.'/configform_pwd.php',   'configformpwd'),
		array('101130', '이용약관설정', G5_ADMIN_URL.'/configform_stipulation.php',   'configformstipulation'),
		array('101140', '기타이용안내설정', G5_ADMIN_URL.'/configform_etc.php',   'configformetc'),
		array('101150', '개인정보제공설정', G5_ADMIN_URL.'/configform_privacy.php',   'configformprivacy')
		)	
	),

    array('101200', '사이트환경관리', G5_ADMIN_URL.'/siteconfig_ip.php',   'siteconfig', array (
	    array('101210', 'IP접속제한관리', G5_ADMIN_URL.'/siteconfig_ip.php',   'siteconfigip'),
	    array('101210', '검색엔진최적화', G5_ADMIN_URL.'/siteconfig_search.php',   'siteconfigsearch')
		)
	)
);

$menu['menu100'] = array (
    array('100000', '환경설정', G5_ADMIN_URL.'/config_form.php',   'config'),
    array('100100', '기본환경설정', G5_ADMIN_URL.'/config_form.php',   'cf_basic'),
    array('100200', '관리권한설정', G5_ADMIN_URL.'/auth_list.php',     'cf_auth'),
    array('100280', '테마설정', G5_ADMIN_URL.'/theme.php',     'cf_theme', 1),
    array('100290', '메뉴설정', G5_ADMIN_URL.'/menu_list.php',     'cf_menu', 1),
    array('100300', '메일 테스트', G5_ADMIN_URL.'/sendmail_test.php', 'cf_mailtest'),
    array('100310', '팝업레이어관리', G5_ADMIN_URL.'/newwinlist.php', 'scf_poplayer'),
    array('100800', '세션파일 일괄삭제',G5_ADMIN_URL.'/session_file_delete.php', 'cf_session', 1),
    array('100900', '캐시파일 일괄삭제',G5_ADMIN_URL.'/cache_file_delete.php',   'cf_cache', 1),
    array('100910', '캡챠파일 일괄삭제',G5_ADMIN_URL.'/captcha_file_delete.php',   'cf_captcha', 1),
    array('100920', '썸네일파일 일괄삭제',G5_ADMIN_URL.'/thumbnail_file_delete.php',   'cf_thumbnail', 1),
    array('100500', 'phpinfo()',        G5_ADMIN_URL.'/phpinfo.php',       'cf_phpinfo')
);

if(version_compare(phpversion(), '5.3.0', '>=') && defined('G5_BROWSCAP_USE') && G5_BROWSCAP_USE) {
    $menu['menu100'][] = array('100510', 'Browscap 업데이트', G5_ADMIN_URL.'/browscap.php', 'cf_browscap');
    $menu['menu100'][] = array('100520', '접속로그 변환', G5_ADMIN_URL.'/browscap_convert.php', 'cf_visit_cnvrt');
}

$menu['menu100'][] = array('100410', 'DB업그레이드', G5_ADMIN_URL.'/dbupgrade.php', 'db_upgrade');
$menu['menu100'][] = array('100400', '부가서비스', G5_ADMIN_URL.'/service.php', 'cf_service');
*/
/*
환경설정관리

1.기본환경설정
-사업자정보		configform_biz
-보안인증-비밀번호변경 	configform_pwd
-이용약관설정		configform_stipulation
-기타이용안내설정	configform_etc
-개인정보제공설정	configform_privacy



2.사이트환경관리
-IP접속제한관리		siteconfig_ip
-검색엔진최적화		siteconfig_search
*/
