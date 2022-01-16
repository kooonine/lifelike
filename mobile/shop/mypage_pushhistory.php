<?php
include_once('./_common.php');

$g5['title'] = '나의 알림';
include_once(G5_MSHOP_PATH.'/_head.php');


$push_cnt = 0;
$where = "";
$sql = "select token from lt_app_users where mb_id = '{$member['mb_id']}' ";
$res = sql_query($sql);
$tk_cnt = sql_num_rows($res);
if($tk_cnt) {
    $where = "and ( false";
    for($k=0; $tk=sql_fetch_array($res); $k++) {
        $where .= " or dest_phone like concat('%', '{$tk['token']}', '%') ";
    }
    $where .= ")";
    
    $sql = "select * from lt_sms_sendhistory where sf_type = 'push' ".$where." order by sh_datetime desc";
    $res = sql_query($sql);
    $push_cnt = sql_num_rows($res);
}
?>

<script>
	var header = '<div id="lnb" class="header_bar">';
	header += '<h1 class="title"><span>나의 알림</span></h1>';
	header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
	header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
	header += '</div>';
	$('#header').html(header);
</script>

<div class="content mypage sub">
	<!-- 컨텐츠 시작 -->
	<?php if($push_cnt) {
	
    for($i=0; $sh = sql_fetch_array($res); $i++) {
        
        $msg_body = $sh['msg_body'];
        $msg_body =str_replace(", URL:", "<br/>URL : ", $msg_body);
        $msg_body = url_auto_link($msg_body);
	    ?>
	
	<div class="grid cont">
		<div class="order_cont">
			<div class="body" style="min-height:0px;">
				<strong class="g_title_06">[<?php echo $sh['msg_title'] ?>]</strong>
				<div class="text">
					<?php echo $msg_body?>
				</div>
				<div class="btn_comm alignR">
					<span class="date floatL"><?php echo $sh['sh_datetime'] ?></span>
				</div>
			</div>
		</div>
	</div>
	
	
	<?php
        }
    } else { ?>
    <!-- 알림 없을 경우 -->
    <div class="grid cont">
    	<div class="guide_box">
    		<p>알림 내역이 없습니다.</p>
    	</div>
    </div>
    <!-- //알림 없을 경우 -->
	<?php } ?>
</div>
<script>
$(function() {
	
});

</script>

<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
?>