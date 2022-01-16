<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 테마 head.sub.php 파일
if (!defined('G5_IS_ADMIN') && defined('G5_THEME_PATH') && is_file(G5_THEME_PATH . '/head.sub.php')) {
    require_once(G5_THEME_PATH . '/head.sub.php');
    return;
}

$begin_time = get_microtime();

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
} else {
    $g5_head_title = $g5['title']; // 상태바에 표시될 제목
    $g5_head_title .= " | " . $config['cf_title'];
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$g5['lo_location'] = addslashes($g5['title']);
if (!$g5['lo_location'])
    $g5['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$g5['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($g5['lo_url'], '/' . G5_ADMIN_DIR . '/') || $is_admin == 'super') $g5['lo_url'] = '';

/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <?php
    if (G5_IS_MOBILE) {
        echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">' . PHP_EOL;
        echo '<meta name="HandheldFriendly" content="true">' . PHP_EOL;
        echo '<meta name="format-detection" content="telephone=no">' . PHP_EOL;
    } else {
        echo '<meta http-equiv="imagetoolbar" content="no">' . PHP_EOL;
        echo '<meta http-equiv="X-UA-Compatible" content="IE=Edge">' . PHP_EOL;
    }

    if ($config['cf_add_meta'])
        echo $config['cf_add_meta'] . PHP_EOL;
    ?>
    <title><?php echo $g5_head_title; ?></title>
    <?php

    if (defined('G5_IS_ADMIN')) {
        if (!defined('_THEME_PREVIEW_'))
            echo '<link rel="stylesheet" href="' . G5_ADMIN_URL . '/css/admin.css">' . PHP_EOL;
    } else {
        $shop_css = '';
        if (defined('_SHOP_')) $shop_css = '_shop';
        echo '<link rel="stylesheet" href="' . G5_CSS_URL . '/' . (G5_IS_MOBILE ? 'mobile' : 'default') . $shop_css . '.css?ver=' . G5_CSS_VER . '">' . PHP_EOL;
    }

    ?>

    <!--[if lte IE 8]>
<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
<![endif]-->
    <script>
        // 자바스크립트에서 사용하는 전역변수 선언
        var g5_url = "<?php echo G5_URL ?>";
        var g5_bbs_url = "<?php echo G5_BBS_URL ?>";
        var g5_is_member = "<?php echo isset($is_member) ? $is_member : ''; ?>";
        var g5_is_admin = "<?php echo isset($is_admin) ? $is_admin : ''; ?>";
        var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
        var g5_bo_table = "<?php echo isset($bo_table) ? $bo_table : ''; ?>";
        var g5_sca = "<?php echo isset($sca) ? $sca : ''; ?>";
        var g5_editor = "<?php echo ($config['cf_editor'] && $board['bo_use_dhtml_editor']) ? $config['cf_editor'] : ''; ?>";
        var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
        <?php if (defined('G5_IS_ADMIN')) { ?>
            var g5_admin_url = "<?php echo G5_ADMIN_URL; ?>";
        <?php } ?>
    </script>


    <!-- Bootstrap -->
    <link href="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo G5_ADMIN_URL ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo G5_ADMIN_URL ?>/vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo G5_ADMIN_URL ?>/css/custom.css" rel="stylesheet">
    
    <!-- new.goods Theme Style -->
    <link href="<?php echo G5_ADMIN_URL ?>/css/new.goods.css" rel="stylesheet">

    <!-- good_db Theme Style -->
    <link href="<?php echo G5_ADMIN_URL ?>/css/good_db.css" rel="stylesheet">

    <!-- iCheck -->
    <link href="<?php echo G5_ADMIN_URL ?>/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <link href="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap-tagsinput-latest/src/bootstrap-tagsinput.css" rel="stylesheet">

    <!-- bootstrap-daterangepicker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- link href="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" -->
    <!-- bootstrap-datetimepicker -->
    <link href="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">


    <!-- jQuery -->
    <script src="<?php echo G5_ADMIN_URL ?>/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo G5_ADMIN_URL ?>/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo G5_ADMIN_URL ?>/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="<?php echo G5_ADMIN_URL ?>/vendors/iCheck/icheck.min.js"></script>

    <script src="<?php echo G5_ADMIN_URL ?>/vendors/moment/min/moment-with-locales.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- script src="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap-daterangepicker/daterangepicker.js"></script -->
    <!-- bootstrap-datetimepicker -->
    <script src="<?php echo G5_ADMIN_URL ?>/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <!-- jQuery-autoNumeric -->
    <script src="<?php echo G5_ADMIN_URL ?>/vendors/jquery-autoNumeric/autoNumeric.min.js"></script>
    <!-- <script src="<?php echo G5_ADMIN_URL ?>/vendors/jquery-autoNumeric/autoNumeric.js"></script> -->

    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />



    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" ></script>


    <script src="<?php echo G5_JS_URL ?>/common.js?ver=<?php echo G5_JS_VER; ?>"></script>

    <?php
    if (G5_IS_MOBILE) {
        echo '<script src="' . G5_JS_URL . '/modernizr.custom.70111.js"></script>' . PHP_EOL; // overflow scroll 감지
    }
    if (!defined('G5_IS_ADMIN'))
        echo $config['cf_add_script'];
    ?>
</head>

<body class="nav-md" <?php echo isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
    <?php
    if ($is_member) { // 회원이라면 로그인 중이라는 메세지를 출력해준다.
        $sr_admin_msg = '';
        if ($is_admin == 'super') $sr_admin_msg = "최고관리자 ";
        else if ($is_admin == 'group') $sr_admin_msg = "그룹관리자 ";
        else if ($is_admin == 'admin') $sr_admin_msg = "일반관리자 ";
        else if ($is_admin == 'brand') $sr_admin_msg = "입점몰관리자 ";
    }

    ?>