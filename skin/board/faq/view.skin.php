<?
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>
<script src="<?=G5_JS_URL; ?>/viewimageresize.js"></script>
<!-- <div id="bo_v_table"><?=($board['bo_mobile_subject'] ? $board['bo_mobile_subject'] : $board['bo_subject']); ?></div> -->
<div class="btn_top top">
	<? if ($reply_href) { ?><a href="<?=$reply_href ?>" class="btn_b01"><i class="fa fa-reply" aria-hidden="true"></i> 답변</a><? } ?>
	<? if ($write_href) { ?><a href="<?=$write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 글쓰기</a><? } ?>
</div>
<article id="bo_v" style="width:<?=$width; ?>">
	<header>
		<h2 id="bo_v_title">
			<? if ($category_name) { ?>
				<span class="bo_v_cate"><?=$view['ca_name']; // 분류 출력 끝 ?></span>
			<? } ?>
			<span class="bo_v_tit">
				<?=cut_str(get_text($view['wr_subject']), 70);?></span>
		</h2>
		<p><span class="sound_only">작성일</span><i class="fa fa-clock-o" aria-hidden="true"></i> <?=date("y-m-d H:i", strtotime($view['wr_datetime'])) ?></p>
	</header>

	<section id="bo_v_info">
		<h2>페이지 정보</h2>
		<span class="sound_only">작성자</span><?=$view['name'] ?><span class="ip"><? if ($is_ip_view) { echo "&nbsp;($ip)"; } ?></span>
		<span class="sound_only">조회</span><strong><i class="fa fa-eye" aria-hidden="true"></i> <?=number_format($view['wr_hit']) ?>회</strong>
		<span class="sound_only">댓글</span><strong><i class="fa fa-commenting-o" aria-hidden="true"></i> <?=number_format($view['wr_comment']) ?>건</strong>
	</section>

	<div id="bo_v_top">
		<?
		ob_start();
		?>
		<ul class="bo_v_left">
			<? if ($update_href) { ?><li><a href="<?=$update_href ?>" class="btn_b01 btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</a></li><? } ?>
			<? if ($delete_href) { ?><li><a href="<?=$delete_href ?>" class="btn_b01 btn" onclick="del(this.href); return false;"><i class="fa fa-trash-o" aria-hidden="true"></i> 삭제</a></li><? } ?>
			<? if ($copy_href) { ?><li><a href="<?=$copy_href ?>" class="btn_admin btn" onclick="board_move(this.href); return false;"><i class="fa fa-files-o" aria-hidden="true"></i> 복사</a></li><? } ?>
			<? if ($move_href) { ?><li><a href="<?=$move_href ?>" class="btn_admin btn" onclick="board_move(this.href); return false;"><i class="fa fa-arrows" aria-hidden="true"></i> 이동</a></li><? } ?>
			<? if ($search_href) { ?><li><a href="<?=$search_href ?>" class="btn_b01 btn">검색</a></li><? } ?>

		</ul>

		<?
		$link_buttons = ob_get_contents();
		ob_end_flush();
		?>
	</div>

	<section id="bo_v_atc">
		<h2 id="bo_v_atc_title">본문</h2>

		<?
		// 파일 출력
		$v_img_count = count($view['file']);
		if($v_img_count) {
			echo "<div id=\"bo_v_img\">\n";

			for ($i=0; $i<=count($view['file']); $i++) {
				if ($view['file'][$i]['view']) {
					//echo $view['file'][$i]['view'];
					echo get_view_thumbnail($view['file'][$i]['view']);
				}
			}

			echo "</div>\n";
		}
		?>

		<div id="bo_v_con"><?=get_view_thumbnail($view['content']); ?></div>
		<? //echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우 ?>

		<? if ($is_signature) { ?><p><?=$signature ?></p><? } ?>

		<? if ( $good_href || $nogood_href) { ?>
			<div id="bo_v_act">
				<? if ($good_href) { ?>
					<span class="bo_v_act_gng">
						<a href="<?=$good_href.'&amp;'.$qstr ?>" id="good_button"  class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><br><span class="sound_only">추천</span><strong><?=number_format($view['wr_good']) ?></strong></a>
						<b id="bo_v_act_good">이 글을 추천하셨습니다</b>
					</span>
				<? } ?>
				<? if ($nogood_href) { ?>
					<span class="bo_v_act_gng">
						<a href="<?=$nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><br><span class="sound_only">비추천</span><strong><?=number_format($view['wr_nogood']) ?></strong></a>
						<b id="bo_v_act_nogood"></b>
					</span>
				<? } ?>
			</div>
		<? } else {
			if($board['bo_use_good'] || $board['bo_use_nogood']) {
				?>
				<div id="bo_v_act">
					<? if($board['bo_use_good']) { ?><span class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><br><span class="sound_only">추천</span><strong><?=number_format($view['wr_good']) ?></strong></span><? } ?>
					<? if($board['bo_use_nogood']) { ?><span class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><br><span class="sound_only">비추천</span> <strong><?=number_format($view['wr_nogood']) ?></strong></span><? } ?>
				</div>
				<?
			}
		}
		?>

		<div id="bo_v_share">
			<? if ($scrap_href) { ?><a href="<?=$scrap_href;  ?>" target="_blank" class=" btn_scrap" onclick="win_scrap(this.href); return false;"><i class="fa fa-thumb-tack" aria-hidden="true"></i> 스크랩</a><? } ?>

			<?
			include_once(G5_SNS_PATH."/view.sns.skin.php");
			?>
		</div>
	</section>



	<?
	$cnt = 0;
	if ($view['file']['count']) {
		for ($i=0; $i<count($view['file']); $i++) {
			if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
				$cnt++;
		}
	}
	?>

	<? if($cnt) { ?>
		<section id="bo_v_file">
			<h2>첨부파일</h2>
			<ul>
				<?
		// 가변 파일
				for ($i=0; $i<count($view['file']); $i++) {
					if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
						?>
						<li>
							<a href="<?=$view['file'][$i]['href'];  ?>" class="view_file_download">
								<i class="fa fa-download" aria-hidden="true"></i>
								<strong><?=$view['file'][$i]['source'] ?></strong>
								<?=$view['file'][$i]['content'] ?> (<?=$view['file'][$i]['size'] ?>)
							</a>
							<span class="bo_v_file_cnt"><?=$view['file'][$i]['download'] ?>회 다운로드</span> |
							<span>DATE : <?=$view['file'][$i]['datetime'] ?></span>
						</li>
						<?
					}
				}
				?>
			</ul>
		</section>
	<? } ?>

	<? if(array_filter($view['link'])) { ?>
		<!-- 관련링크 시작 { -->
		<section id="bo_v_link">
			<h2>관련링크</h2>
			<ul>
				<?
		// 링크
				$cnt = 0;
				for ($i=1; $i<=count($view['link']); $i++) {
					if ($view['link'][$i]) {
						$cnt++;
						$link = cut_str($view['link'][$i], 70);
						?>
						<li>
							<a href="<?=$view['link_href'][$i] ?>" target="_blank">
								<i class="fa fa-link" aria-hidden="true"></i>
								<strong><?=$link ?></strong>
							</a>
							<span class="bo_v_link_cnt"><?=$view['link_hit'][$i] ?>회 연결</span>
						</li>
						<?
					}
				}
				?>
			</ul>
		</section>
		<!-- } 관련링크 끝 -->
	<? } ?>

	<? if ($prev_href || $next_href) { ?>
		<ul class="bo_v_nb">
			<? if ($prev_href) { ?><li class="bo_v_prev"><a href="<?=$prev_href ?>"><i class="fa fa-caret-left" aria-hidden="true"></i> 이전글</a></li><? } ?>
			<? if ($next_href) { ?><li class="bo_v_next"><a href="<?=$next_href ?>">다음글 <i class="fa fa-caret-right" aria-hidden="true"></i></a></li><? } ?>
			<li><a href="<?=$list_href ?>" class="btn_list"><i class="fa fa-list" aria-hidden="true"></i> 목록</a></li>

		</ul>
	<? } ?>
	<?
	// 코멘트 입출력
	include_once(G5_BBS_PATH.'/view_comment.php');
	?>

</article>

<script>
	<? if ($board['bo_download_point'] < 0) { ?>
		$(function() {
			$("a.view_file_download").click(function() {
				if(!g5_is_member) {
					alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
					return false;
				}

				var msg = "파일을 다운로드 하시면 포인트가 차감(<?=number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

				if(confirm(msg)) {
					var href = $(this).attr("href")+"&js=on";
					$(this).attr("href", href);

					return true;
				} else {
					return false;
				}
			});
		});
	<? } ?>

	function board_move(href)
	{
		window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
	}
</script>

<!-- 게시글 보기 끝 -->

<script>
	$(function() {
		$("a.view_image").click(function() {
			window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
			return false;
		});

	// 추천, 비추천
	$("#good_button, #nogood_button").click(function() {
		var $tx;
		if(this.id == "good_button")
			$tx = $("#bo_v_act_good");
		else
			$tx = $("#bo_v_act_nogood");

		excute_good(this.href, $(this), $tx);
		return false;
	});

	// 이미지 리사이즈
	$("#bo_v_atc").viewimageresize();
});

	function excute_good(href, $el, $tx)
	{
		$.post(
			href,
			{ js: "on" },
			function(data) {
				if(data.error) {
					alert(data.error);
					return false;
				}

				if(data.count) {
					$el.find("strong").text(number_format(String(data.count)));
					if($tx.attr("id").search("nogood") > -1) {
						$tx.text("이 글을 비추천하셨습니다.");
						$tx.fadeIn(200).delay(2500).fadeOut(200);
					} else {
						$tx.text("이 글을 추천하셨습니다.");
						$tx.fadeIn(200).delay(2500).fadeOut(200);
					}
				}
			}, "json"
			);
	}
</script>
