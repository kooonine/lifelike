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

$sqltk = "SELECT token, mb_id FROM lt_app_users WHERE mb_id = '" . $member['mb_id'] . "'";
$resulttk = sql_query($sqltk);
$rowtk = sql_fetch_array($resulttk);

$sql = "select * from lt_sms_sendhistory where sf_type = 'push' and dest_phone like '%" . $rowtk['token'] . "%' order by sh_datetime desc ";
$result = sql_query($sql);
$push_count = sql_num_rows($result);
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
	<div class="type2 grid">
		<ul>
			<?
			for ($i = 0; $row = sql_fetch_array($result); $i++) {
				$sqlin = "UPDATE `lt_push_count_history` SET `nc`='Y' WHERE  `token`='" . $rowtk['token'] . "' AND `nc`='N';";
				sql_query($sqlin);

				$msg = explode('URL:', $row['msg_body']);
			?>
				<li>
					<div class="order_title">
						<span class="item"><?= $row['msg_title'] ?></span>
					</div>
					<div class="order_cont mt0">
						<div class="body2">
							<div class="text">
								<?= mb_substr($msg[0], 0, -2, 'UTF-8') ?>
							</div>
							<div class="btn_comm alignR" style="height:28px;">
								<span class="date floatL"><?= $row['sh_datetime'] ?></span>
								<? if ($msg[1]) { ?>
									<button type="button" class="btn gray_line small" onclick="location.href='<?= substr($msg[1], 5) ?>'"><span>링크 바로가기</span></button>
								<? } ?>
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>
				</li>
			<? } ?>
		</ul>
	</div>
	<!-- 컨텐츠 종료 -->
</div>
<? include_once(G5_MSHOP_PATH . '/_tail.php'); ?>