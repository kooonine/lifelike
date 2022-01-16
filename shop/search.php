<?php
include_once('./_common.php');

if( function_exists('social_check_login_before') ){
    $social_login_html = social_check_login_before();
}


//<script src="echo G5_MOBILE_URL /js/jquery.cookie.js" type="text/javascript"></script>

add_javascript("<script src='".G5_MOBILE_URL."/js/jquery.cookie.js' type='text/javascript'></script>", 1);

$g5['title'] = '검색';
include_once('./_head.php');
$ajax_url = G5_SHOP_URL.'/ajax.search.php';
if($is_mobile){
    $search_skin = G5_MSHOP_SKIN_PATH.'/search.skin.php';
}else {
    $search_skin = G5_SHOP_SKIN_PATH.'/search.skin.php';
}


include_once($search_skin);



  
?>
