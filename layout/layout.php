<!DOCTYPE html>
<html lang="ko" class="no-js">
<?php include_once G5_LAYOUT_PATH . "/header.php"; ?>

<body <?= $g5['body_script'] ?>>
    <div id="offset-nav-top" class ="offsetNavTop"></div>
    <div id="contents">
        <?php echo $contents; ?>
    </div>
    <?php include_once G5_LAYOUT_PATH . "/modal.php" ?>
    <?php include_once G5_LAYOUT_PATH . "/footer.php" ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5ZTXNNT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
<?php
if ($is_admin == 'super') {
    echo '<!-- <div>RUN TIME : ' . (get_microtime() - $begin_time) . '</div> -->';
}
echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다.
?>
<script>
    <?
    if(defined('_INDEX_')) { ?>
    openNoticePopup();
    <? } ?>
</script>