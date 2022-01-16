<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$meta = "";
$style = "";
$script = "";

if ($config['cf_add_meta']) {
    $meta .= $config['cf_add_meta'] . PHP_EOL;
}

if ($config['cf_add_seo_use'] == "1") {
    if (strstr(addslashes(clean_xss_tags($_SERVER['REQUEST_URI'])), '/' . G5_BBS_DIR . '/')) {
        if ($config['cf_add_meta_bbs_title']) $meta .= '<meta name="title" content="' . $config['cf_add_meta_bbs_title'] . '">' . PHP_EOL;
        if ($config['cf_add_meta_bbs_author']) $meta .= '<meta name="author" content="' . $config['cf_add_meta_bbs_author'] . '">' . PHP_EOL;
        if ($config['cf_add_meta_bbs_description']) $meta .= '<meta name="description" content="' . $config['cf_add_meta_bbs_description'] . '">' . PHP_EOL;
        if ($config['cf_add_meta_bbs_keywords']) $meta .= '<meta name="keywords" content="' . $config['cf_add_meta_bbs_keywords'] . '">' . PHP_EOL;
    } else {
        if ($config['cf_add_meta_common_title']) $meta .= '<meta name="title" content="' . $config['cf_add_meta_common_title'] . '">' . PHP_EOL;
        if ($config['cf_add_meta_common_author']) $meta .= '<meta name="author" content="' . $config['cf_add_meta_common_author'] . '">' . PHP_EOL;
        if ($config['cf_add_meta_common_description']) $meta .= '<meta name="description" content="' . $config['cf_add_meta_common_description'] . '">' . PHP_EOL;
        if ($config['cf_add_meta_common_keywords']) $meta .= '<meta name="keywords" content="' . $config['cf_add_meta_common_keywords'] . '">' . PHP_EOL;
    }
}


if (defined('G5_IS_ADMIN')) {
    if (!defined('_THEME_PREVIEW_'))
        $style .= '<link rel="stylesheet" href="' . G5_ADMIN_URL . '/css/admin.css">' . PHP_EOL;
}

// jQuery, Bootstrap
$script .= '<script type="text/javascript" src="' . G5_URL . '/re/js/jquery-3.4.1.min.js"></script>';
$script .= '<script type="text/javascript" src="' . G5_URL . '/re/js/bootstrap.min.js"></script>';
$script .= '<script type="text/javascript" src="' . G5_URL . '/re/js/bootstrap.bundle.min.js"></script>';
$script .= '<script type="text/javascript" src="' . G5_URL . '/re/js/jquery.scrollbar.min.js"></script>';

$script .= '<script type="text/javascript" src="' . G5_URL . '/js/swiper/swiper.min.js"></script>';


$style .= '<link rel="stylesheet" href="' . G5_URL . '/re/css/bootstrap.min.css" />' . PHP_EOL;
// $style .= '<link rel="stylesheet" href="' . G5_URL . '/re/css/bootstrap-grid.min.css" />' . PHP_EOL;
// $style .= '<link rel="stylesheet" href="' . G5_URL . '/re/css/bootstrap-reboot.min.css" />' . PHP_EOL;
$style .= '<link rel="stylesheet" href="' . G5_URL . '/re/css/common.css?ver=' . date('Ymdhis') . '" />' . PHP_EOL;
$style .= '<link rel="stylesheet" href="' . G5_URL . '/re/css/layout.css?ver=' . date('Ymdhis') . '" />' . PHP_EOL;
$style .= '<link rel="stylesheet" href="' . G5_URL . '/re/css/jquery.scrollbar.css" />' . PHP_EOL;

if ($is_member) { // 회원이라면 로그인 중이라는 메세지를 출력해준다.
    $sr_admin_msg = '';
    if ($is_admin == 'super') $sr_admin_msg = "최고관리자 ";
    else if ($is_admin == 'group') $sr_admin_msg = "그룹관리자 ";
    else if ($is_admin == 'board') $sr_admin_msg = "게시판관리자 ";
}

// 소셜로그인 관련처리
$social_pop_once = false;
$sns_login_uri = "/auth/login.php";

// 장바구니 카운트
$tmp_cart_id = get_session('ss_cart_id');
$cart_count = get_cart_count($tmp_cart_id);

// history
$history_mb_id = $is_member ? $member['mb_id'] : session_id();
$g5_user_history = user_history("list", array("mb_id" => $history_mb_id));

// 새창을 사용한다면
if (G5_SOCIAL_USE_POPUP) {
    $sns_login_uri = G5_SOCIAL_LOGIN_URL . '/popup.php';
}
include_once G5_LAYOUT_PATH . "/view/head.php";
