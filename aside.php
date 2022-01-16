<!-- 메뉴 개편전 임시적용 -->
<style>
	aside.aside>.menu_area {
		margin-top: 28px;
	}

	aside.aside>.menu_area li {
		position: relative;
		height: 100px;
		text-align: center;
		background: url(../img/mb/common/aside_menu_01.png) no-repeat 50% 0;
		background-size: 40px;
	}

	aside.aside>.menu_area li+li {
		background: url(../img/mb/common/aside_menu_02.png) no-repeat 50% 0;
		background-size: 40px;
	}

	aside.aside>.menu_area li+li+li {
		background: url(../img/mb/common/aside_menu_03.png) no-repeat 50% 0;
		background-size: 40px;
	}

	aside.aside>.menu_area li+li+li+li {
		background: url(../img/mb/common/aside_menu_04.png) no-repeat 50% 0;
		background-size: 40px;
	}

	aside.aside>.menu_area li+li+li+li+li {
		background: url(../img/mb/common/aside_menu_05.png) no-repeat 50% 0;
		background-size: 35px;
	}

	aside.aside>.menu_area li+li+li+li+li+li {
		background: url(../img/mb/common/aside_menu_06.png) no-repeat 50% 0;
		background-size: 40px;
	}

	aside.aside>.menu_area li a {
		display: block;
		padding-top: 50px;
		font-size: 16px;
		font-weight: bold;
		letter-spacing: -1px;
	}
</style>
<aside class="aside">
	<div class="head">
		<div class="user_info">
			<div class="inner">
				<!-- 로그인 후 -->
				<? if ($is_member) { ?>
					<?
					$mb_dir = substr($member['mb_id'], 0, 2);
					$icon_file = G5_DATA_PATH . '/member_image/' . $mb_dir . '/' . $member['mb_id'] . '.gif';
					if (file_exists($icon_file)) {
						$icon_url = G5_DATA_URL . '/member_image/' . $mb_dir . '/' . $member['mb_id'] . '.gif';
					?>
						<p class="photo"><img src="<?= $icon_url; ?>" alt="" /></p>
					<? } else { ?>
						<p class="photo"><img src="/img/default.jpg" alt="" /></p>
					<? } ?>
					<div class="edit_cont">
						<p class="name" onclick="location.href='<?= G5_SHOP_URL; ?>/mypage.php';" style="cursor: pointer;">안녕하세요. <strong class="bold"><?= $member['mb_name'] ?></strong>님 ></p>
						<button type="button" class="btn_link" onclick="location.href='<?= G5_SHOP_URL ?>/viewlist.php'"><span class="arrow_r_gray">최근 본 상품</span></button>
					</div>
					<!-- //로그인 후 -->
				<? } else { ?>
					<!-- 로그인 전 -->
					<div class="edit_cont off">
						<p class="name"><a href="<?= G5_BBS_URL; ?>/login.php?url=<?= $urlencode; ?>"><strong class="bold">로그인</strong></a>해주세요</p>
						<button type="button" class="btn_link" onclick="location.href='<?= G5_SHOP_URL ?>/viewlist.php'"><span class="arrow_r_gray">최근 본 상품</span></button>
					</div>
					<!-- //로그인 전 -->
				<? } ?>
			</div>
		</div>
	</div>
	<div class="menu_area">
		<ul class="count3">
			<?
																																					/*
			$sql = " select *
			from {$g5['menu_table']}
			where me_use = '1'
			and length(me_code) = '2'
			order by me_order, me_id ";
			$result = sql_query($sql, false);
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$ico = "";
				if($row['me_code'] == "10") $ico = "ico3";
				if($row['me_code'] == "20") $ico = "ico4";
				if($row['me_code'] == "30") $ico = "ico5";
				if($row['me_code'] == "40") $ico = "ico2";
				?>
				<li>
					<a href="<?=$row['me_link']; ?>" target="_<?=$row['me_target']; ?>" class="dep1 <?=$ico?>"><?=$row['me_name'] ?></a>
					<?
					$sql2 = " select *
					from {$g5['menu_table']}
					where me_use = '1'
					and length(me_code) = '4'
					and substring(me_code, 1, 2) = '{$row['me_code']}'
					order by me_order, me_id ";
					$result2 = sql_query($sql2);
					$result2Cnt = -1;
					for ($k=0; $row2=sql_fetch_array($result2); $k++) {
						$result2Cnt = $k;
						if($k == 0){
							?>
							<ul class="dep2">
							<? } ?>
							<li><a href="<?=$row2['me_link']; ?>" target="_<?=$row2['me_target']; ?>"><?=$row2['me_name'] ?></a></li>
						<? }
						if($result2Cnt > -1) {
							?>
						</ul>
					<? } ?>
				</li>
			<? }*/ ?>
			<li><a href="<?= G5_SHOP_URL ?>/list.php?ca_id=102010">리스</a></li>
			<li><a href="<?= G5_SHOP_URL ?>/list.php?ca_id=1010">제품</a></li>
			<li><a href="<?= G5_SHOP_URL ?>/caremain.php">케어</a></li>
			<!-- <li><a href="<?= G5_URL ?>/community.php">커뮤니티</a></li>
			<li><a href="<?= G5_URL ?>/magazine.php">매거진</a></li> -->
		</ul>
	</div>
	<div class="banner">
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
				<a href="<?= $link_url ?>"><img src="<?= $img_url ?>" alt="" width="480" /></a>
			<? } else { ?>
				<a href="<?= $link_url ?>"><img src="<?= G5_MOBILE_URL; ?>/img/theme_img.jpg" width="480" alt="" /></a>
		<? }
																																					} ?>
	</div>
	<div class="link_area">
		<ul>
			<li class="link_1"><a href="<?= G5_URL ?>/company.php">회사소개</a></li>
			<li class="link_2"><a href="<?= G5_BBS_URL ?>/faq.php">고객센터</a></li>
		</ul>
	</div>
	<a href="#" class="btn_closed"><span class="blind">닫기</span></a>
</aside>