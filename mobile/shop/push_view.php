<?
include_once('./_common.php');

// 테마에 mypage.php 있으면 include
if (defined('G5_THEME_SHOP_PATH')) {
	$theme_mypage_file = G5_THEME_MSHOP_PATH . '/mypage.php';
	if (is_file($theme_mypage_file)) {
		include_once($theme_mypage_file);
		return;
		unset($theme_mypage_file);
	}
}

$g5['title'] = '마이페이지';
include_once(G5_MSHOP_PATH . '/_head.php');

// $s_push_id 로 현재 장바구니 자료 쿼리
$sql = "select * from lt_sms_sendhistory where sf_type = 'push' and sh_no = '" . $_REQUEST['sq'] . "' order by sh_datetime desc ";
$result = sql_query($sql);
$row = sql_fetch_array($result);
?>
<script>
	var header = '<div id="lnb" class="header_bar">';
	header += '<h1 class="title"><span>알림내역</span></h1>';
	header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
	header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
	header += '</div>';
	$('#header').html(header);
</script>
<div class="content mypage sub">
	<!-- 컨텐츠 시작 -->
	<div class="grid type2">
		<div class="order_title">
			<span class="item"><?= $row['msg_title'] ?></span>
		</div>
		<div class="order_cont mt0">
			<div class="body">
				<div class="text">
					<?= $row['msg_body'] ?>
				</div>
				<div class="btn_comm alignR">
					<span class="date floatL"><?= $row['sh_datetime'] ?></span>
					<button type="button" class="btn gray_line small" onclick="location.href='/mobile/shop/push_list.php'"><span>리스트</span></button>
				</div>
			</div>
		</div>
	</div>
	<!-- 컨텐츠 종료 -->
</div>
<? include_once(G5_MSHOP_PATH . '/_tail.php'); ?>