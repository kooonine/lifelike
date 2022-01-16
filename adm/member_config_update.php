<?php
$sub_menu = "700110";
include_once('./_common.php');


auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

check_admin_token();

$cf_social_servicelist = !empty($_POST['cf_social_servicelist']) ? implode(',', $_POST['cf_social_servicelist']) : '';

$sql = " update {$g5['config_table']}
            set cf_1 = '{$_POST['cf_1']}',
                cf_2 = '{$_POST['cf_2']}',

                cf_social_login_use = '{$_POST['cf_social_login_use']}',
                cf_googl_shorturl_apikey = '{$_POST['cf_googl_shorturl_apikey']}',
                cf_kakao_js_apikey = '{$_POST['cf_kakao_js_apikey']}',
                cf_facebook_appid = '{$_POST['cf_facebook_appid']}',
                cf_facebook_secret = '{$_POST['cf_facebook_secret']}',
                cf_naver_clientid = '{$_POST['cf_naver_clientid']}',
                cf_naver_secret = '{$_POST['cf_naver_secret']}',
                cf_google_clientid = '{$_POST['cf_google_clientid']}',
                cf_google_secret = '{$_POST['cf_google_secret']}',
                cf_kakao_rest_key = '{$_POST['cf_kakao_rest_key']}',
                cf_kakao_client_secret = '{$_POST['cf_kakao_client_secret']}',
                cf_social_servicelist   =   '{$cf_social_servicelist}'
                ";
sql_query($sql);

//sql_query(" OPTIMIZE TABLE `$g5[config_table]` ");

goto_url('./member_config.php', false);
?>