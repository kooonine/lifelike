<?php
$sub_menu = '400100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

//
// 영카트 default
//
$cf_social_servicelist_arr = array();
if(!empty($_POST['check_social_facebook'])) $cf_social_servicelist_arr[] = $_POST['check_social_facebook'];
if(!empty($_POST['check_social_kakao'])) $cf_social_servicelist_arr[] = $_POST['check_social_kakao'];
if(!empty($_POST['check_social_naver'])) $cf_social_servicelist_arr[] = $_POST['check_social_naver'];
if(!empty($_POST['check_social_google'])) $cf_social_servicelist_arr[] = $_POST['check_social_google'];

$cf_social_servicelist = !empty($cf_social_servicelist_arr) ? implode(',', $cf_social_servicelist_arr) : '';

$sql = " update {$g5['config_table']}
            set 
                cf_social_servicelist   =   '{$cf_social_servicelist}',
                cf_googl_shorturl_apikey = '{$_POST['cf_googl_shorturl_apikey']}',
                cf_kakao_js_apikey = '{$_POST['cf_kakao_js_apikey']}',
                cf_facebook_appid = '{$_POST['cf_facebook_appid']}',
                cf_facebook_secret = '{$_POST['cf_facebook_secret']}',
                cf_naver_clientid = '{$_POST['cf_naver_clientid']}',
                cf_naver_secret = '{$_POST['cf_naver_secret']}',
                cf_google_clientid = '{$_POST['cf_google_clientid']}',
                cf_google_secret = '{$_POST['cf_google_secret']}',
                cf_kakao_rest_key = '{$_POST['cf_kakao_rest_key']}',
                cf_kakao_client_secret = '{$_POST['cf_kakao_client_secret']}'
                ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {

sql_query($sql);
goto_url("./configform_sns.php");
}
?>
