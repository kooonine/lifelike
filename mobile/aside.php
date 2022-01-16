<!-- aside -->
<nav id="aside" class="">
	<div class="inner">
		<div class="user_area">
			<!-- 로그인 후 -->
			<? if ($is_member) { ?>
				<div class="user_photo">
					<?
					$mb_dir = substr($member['mb_id'], 0, 2);
					$icon_file = G5_DATA_PATH . '/member_image/' . $mb_dir . '/' . $member['mb_id'] . '.gif';
					if (file_exists($icon_file)) {
						$icon_url = G5_DATA_URL . '/member_image/' . $mb_dir . '/' . $member['mb_id'] . '.gif';
					?>
						<span class="photo"><img src="<?= $icon_url; ?>" alt="" /></span>
					<? } else { ?>
						<span class="photo"><img src="/img/default.jpg" alt="" /></span>
					<? } ?>
					<a href="<?= G5_BBS_URL; ?>/member_confirm.php?url=register_form.php"><button type="button" class="modify">p</button></a>
				</div>
				<div class="user_text">
					<span class="txt1">안녕하세요</span>
					<p class="txt2"><a href="<?= G5_SHOP_URL; ?>/mypage.php"><strong><?= $member['mb_name'] ?></strong>님</a></p>

					<p class="logout_text p10 pl85"><a href="<?= G5_SHOP_URL ?>/viewlist.php">최근 본 상품</a></p>
				</div>

				<!-- //로그인 후 -->
			<? } else { ?>
				<!-- 로그인 전 -->
				<p class="logout_text"><a href="<?= G5_BBS_URL; ?>/login.php?url=<?= $urlencode; ?>"><span>로그인</span>해주세요</a></p>

				<p class="logout_text p10"><a href="<?= G5_SHOP_URL ?>/viewlist.php">최근 본 상품</a></p>
				<!-- //로그인 전 -->
			<? } ?>
			<a href="#" class="btn_closed"><span class="blind">닫기</span></a>
		</div>
		<div class="comm_area">
			<ul class="count2">
				<li class="home"><a href="<?= G5_URL; ?>/index.php?device=mobile"><span>HOME</span></a></li>
				<li class="set"><a href="<?= G5_MOBILE_URL; ?>/common/setting.php"><span>설정</span></a></li>
			</ul>
		</div>
		<div class="menu_area">
			<ul class="count3">
				<li><a href="<?= G5_SHOP_URL ?>/list.php?ca_id=102010">리스</a></li>
				<li><a href="<?= G5_SHOP_URL ?>/list.php?ca_id=1010">제품</a></li>
				<li><a href="<?= G5_SHOP_URL ?>/caremain.php">케어</a></li>
				<!-- <li><a href="<?= G5_URL ?>/community.php">커뮤니티</a></li> -->
				<!-- <li><a href="<?= G5_URL ?>/magazine.php">매거진</a></li> -->
				<? if ($is_member) { ?>
					<!-- <li><a href="<?= G5_SHOP_URL ?>/fileitem.php">내게 맞는 제품 찾기</a></li> -->
				<? } ?>
			</ul>
		</div>
		<div class="link_area">
			<ul class="count2">
				<li><a href="<?= G5_URL ?>/company.php">회사소개</a></li>
				<li><a href="<?= G5_BBS_URL ?>/board.php?bo_table=event">이벤트</a></li>
				<li><a href="<?= G5_BBS_URL ?>/board.php?bo_table=notice">공지사항</a></li>
				<li><a href="<?= G5_BBS_URL ?>/faq.php">고객센터</a></li>
			</ul>
		</div>
		<div class="banner_area">
			<?

																					$side_sql = " select * from lt_design_side where side_id = '1' and mobile_onoff='Y'";
																					$side_view = sql_fetch($side_sql);

																					$side_id = $side_view['side_id'];
																					$side_type2 = $side_view['main_type2'];
																					$side_view_data = json_decode(str_replace('\\', '', $side_view['main_view_data']), true);

																					for ($i = 1; $i <= $side_type2; $i++) {
																						$img_data = $side_view_data['imgFile'][$i - 1];
																						$link_url = $img_data['linkURL'];
																						$img_file = G5_DATA_PATH . '/sidemenu/' . $side_id . '/' . $img_data['imgFile'];
																						if ($img_data['imgFile'] && file_exists($img_file)) {
																							$img_url = G5_DATA_URL . '/sidemenu/' . $side_id . '/' . $img_data['imgFile'];
			?>
					<a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" /></a>
				<? } else { ?>
					<a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" alt="" /></a>
			<? }
																					} ?>
		</div>
		<p class="foot_text">Copyright &copy; LIFELIKE All right reserved</p>
	</div>
</nav>