<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta property="og:image" content="<?= G5_URL ?>/img/re/logo_og_image.png" />
    <!-- 0817 nexdi -->
    <meta name="facebook-domain-verification" content="nioyf1sthrsw62q4epezd3sl271oza" />
    <title><?php echo $g5_head_title ?></title>
    <?php echo $meta ?>
    <?php echo $style ?>
    <?php echo $script ?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-153591131-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-153591131-1');
    </script>
   
    <script>
        // 자바스크립트에서 사용하는 전역변수 선언
        var g5_url = "<?= G5_URL ?>";
        var g5_bbs_url = "<?= G5_BBS_URL ?>";
        var g5_is_member = "<?= isset($is_member) ? $is_member : ''; ?>";
        var g5_is_admin = "<?= isset($is_admin) ? $is_admin : ''; ?>";
        var g5_is_mobile = "<?= G5_IS_MOBILE ?>";
        var g5_bo_table = "<?= isset($bo_table) ? $bo_table : ''; ?>";
        var g5_sca = "<?= isset($sca) ? $sca : ''; ?>";
        var g5_editor = "<?= ($config['cf_editor'] && $board['bo_use_dhtml_editor']) ? $config['cf_editor'] : ''; ?>";
        var g5_cookie_domain = "<?= G5_COOKIE_DOMAIN ?>";
        <? if (defined('G5_IS_ADMIN')) { ?>
            var g5_admin_url = "<?= G5_ADMIN_URL; ?>";
        <? } ?>
    </script>
    <script type="text/javascript" src="/re/js/underscore-min.js"></script>
    <script src="<?= G5_JS_URL ?>/modernizr.custom.70111.js"></script>
    <script src="/js/tiny-slider/tiny-slider.js"></script>
    <script type="text/javascript" src="/re/datepicker/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="/re/datepicker/locales/bootstrap-datepicker.kr.js"></script>

    <link rel="stylesheet" href="/js/tiny-slider/tiny-slider.css">
    <link rel="stylesheet" href="/js/swiper/swiper.min.css?ver=190801">
    <link rel="shortcut icon" href="/favicon.png" />
    <link rel="stylesheet" href="/re/datepicker/bootstrap-datepicker3.min.css">


    <? if (defined('_SHOP_')) : ?>
        <? if (!G5_IS_MOBILE) : ?>
            <script src="<?= G5_JS_URL ?>/jquery.shop.menu.js?ver=<?= G5_JS_VER; ?>"></script>
        <? else : ?>
            <script src="<?= G5_JS_URL ?>/jquery.menu.js?ver=<?= G5_JS_VER; ?>"></script>
        <? endif ?>
    <? endif ?>

    <script src="<?= G5_JS_URL ?>/common.js?ver=<?= G5_JS_VER; ?>"></script>
    <!-- <script src="<?= G5_JS_URL ?>/wrest.js?ver=<?= G5_JS_VER; ?>"></script> -->

    <!-- <link rel="stylesheet" href="<?= G5_JS_URL ?>/font-awesome/css/font-awesome.min.css"> -->
    <?
    if (!defined('G5_IS_ADMIN')) echo $config['cf_add_script'];
    echo $config['cf_add_html_head_pc'];
    ?>
</head>