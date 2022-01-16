<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$faq_skin_url.'/style.css">', 0);
?>
<!-- container -->
<div id="container">
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="tab">
				<ul class="type1 black">
					<li class="on"><a href="<?= G5_BBS_URL ?>/faq.php"><span>FAQ</span></a></li>
					<li><a href="<?= G5_BBS_URL ?>/qalist.php"><span>1:1 문의하기</span></a></li>
				</ul>
			</div>

			<div class="tab_cont_wrap">
				<div class="tab button_group">
					<ul class="type4 center black tab_btn">
						<li class="<?= ($fa_category1 == "") ? "on" : "" ?>"><a href="<?= $category_href ?>"><span>전체</span></a></li>
						<? for ($i = 0; $i < count($fa_category1_arr); $i++) {
							echo '<li onclick="location.href=\'' . $category_href . '?fa_category1=' . $fa_category1_arr[$i] . '\'" class="' . (($fa_category1_arr[$i] == $fa_category1) ? 'on' : '') . '">';
							echo '<a href="#">' . $fa_category1_arr[$i] . '</a></li>';
						} ?>
					</ul>
				</div>

				<? if ($admin_href) { ?>
					<div class="title_bar none">
						<a href="<?= $admin_href ?>"><button type="button" class="btn small gray floatR"><span>FAQ 수정</span></button></a>
					</div>
				<? } ?>

				<!-- //title_bar  -->
				<div class="tab_cont">
					<? // FAQ 내용
					if (count($faq_list)) {
					?>
						<!-- tab1 -->
						<div class="tab_inner">
							<div class="toggle line_top type2">
								<?
								foreach ($faq_list as $key => $v) {
									if (empty($v))
										continue;
								?>
									<div class="toggle_group">
										<div class="title">
											<span class="category "><?= $v['fa_category2'] ?></span>
											<a href="#" class="toggle_anchor">
												<h3 class="tit ellipsis"><?= conv_content($v['fa_subject'], 1); ?></h3>
											</a>
										</div>
										<div class="cont">
											<?= conv_content($v['fa_content'], 1); ?>
										</div>
									</div>
								<?
								}
								?>
							</div>
						</div>
						<!-- //tab1 -->
					<?
					} else {
					?>
						<!-- tab1 -->
						<div class="tab_inner">
							<div class="toggle line_top type2">
								<div class="toggle_group">
									<div class="title">
										<?
										if ($stx) {
											echo '검색된 게시물이 없습니다.';
										} else {
											echo '등록된 FAQ가 없습니다.';
										}
										?>
									</div>
								</div>
							</div>
						</div>

					<? } ?>
				</div>
				<?= $list_pages ?>
			</div>
			<!-- } FAQ 끝 -->
		</div>
	</div>
</div>
<script src="<?= G5_JS_URL; ?>/viewimageresize.js"></script>
<script>
	$(function() {
		$(".closer_btn").on("click", function() {
			$(this).closest(".con_inner").slideToggle();
		});
	});

	function faq_open(el) {
		var $con = $(el).closest("li").find(".con_inner");

		if ($con.is(":visible")) {
			$con.slideUp();

		} else {
			$("#faq_con .con_inner:visible").css("display", "none");

			$con.slideDown(
				function() {
					// 이미지 리사이즈
					$con.viewimageresize2();
				}
			);
		}

		return false;
	}
</script>
<?
// include_once(G5_PATH.'/tail.php');
?>