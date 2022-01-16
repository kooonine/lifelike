<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
</div>


<!-- container End -->
<script type="text/javascript">
$(document).ready(function(){
	$('input:read-only').css('backgroundColor','#e4e4e4');
});
var today = new Date();
window.onbeforeunload = function (event) {
	var edate = new Date();
	var stayTime = edate.getTime() - today.getTime();
	$.post( "<?php echo G5_BBS_URL?>/visit_insert.page.inc.php", { vi_stay: stayTime, vi_page : "<?php echo $_SERVER['PHP_SELF'] ?>", full_page : "<?php echo $_SERVER['PHP_SELF'].(($_SERVER['QUERY_STRING'])?"?".$_SERVER['QUERY_STRING']:"")?>" }, function(data ) {
		//console.log( data );
	} );

    window.onbeforeunload = null;
	try{(e || window.event).returnValue = null;}catch(e){};
	//return null;
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