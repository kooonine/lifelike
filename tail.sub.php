<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/tail.php');
    return;
}

if(!defined('G5_IS_ADMIN') && defined('G5_THEME_PATH') && is_file(G5_THEME_PATH.'/tail.sub.php')) {
    require_once(G5_THEME_PATH.'/tail.sub.php');
    return;
}
?>

<?php if ($is_admin == 'super') {  ?><!-- <div style='float:left; text-align:center;'>RUN TIME : <?php echo get_microtime()-$begin_time; ?><br></div> --><?php }  ?>

<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
$(function() {
    var $sv_use = $(".sv_use");
    var count = $sv_use.length;

    $sv_use.each(function() {
        $(this).css("z-index", count);
        $(this).css("position", "relative");
        count = count - 1;
    });
});
</script>
<![endif]-->
<script>

var today = new Date();
window.onbeforeunload = function (event) {
	var edate = new Date();
	var stayTime = edate.getTime() - today.getTime();
	$.post( "<?php echo G5_BBS_URL?>/visit_insert.page.inc.php", { vi_stay: stayTime, vi_page : "<?php echo $_SERVER['PHP_SELF'] ?>", full_page : "<?php echo $_SERVER['PHP_SELF'].(($_SERVER['QUERY_STRING'])?"?".$_SERVER['QUERY_STRING']:"")?>" }, function(data ) {
		//console.log( data );
	} );

    window.onbeforeunload = null;
	try{(e || window.event).returnValue = null;}catch(e){};
	//return false;
}
$(function () {
    $(".btn").click(function () {
        window.onbeforeunload = null;
    });
});
</script>
</body>
</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>