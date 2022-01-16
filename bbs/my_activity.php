<?
include_once('./_common.php');

if(!$is_member)
	alert_close('회원이시라면 회원로그인 후 이용해 주십시오.');

$skin_path = G5_SKIN_PATH.'/mypage/basic';

include_once('_head.php');

$type = $_GET['type'];
if(!$type){
	$type = 'item';
}

if(G5_IS_MOBILE) {
	$skin_path = G5_MOBILE_PATH.'/skin/mypage/basic';

	$header_html = '';
	$header_html .= '<div id="lnb" class="header_bar">';
	$header_html .= '<h1 class="title"><span>나의 활동 전체</span></h1>';
	$header_html .= '<a class="btn_back" onclick="history.back();"><span class="blind">뒤로가기</span></a>';
	$header_html .= '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span><button>';
	$header_html .= '</div>';

	echo "<script>$('#header').html('".$header_html."');</script>";
?>
<? } else { ?>
	<!-- container -->
	<div id="container">
		<!-- lnb -->
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>나의 활동 전체</span></h1>
		</div>
	<? } ?>
	<!-- //lnb -->
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="step-nav">
				<ul>
					<li <? if($type == 'item') echo 'class="on"';?>><a href="<?=G5_BBS_URL?>/my_activity.php?type=item">제품평</a></li>
					<li <? if($type == 'review') echo 'class="on"';?>><a href="<?=G5_BBS_URL?>/my_activity.php?type=review">체험단리뷰</a></li>
					<li <? if($type == 'online') echo 'class="on"';?>><a href="<?=G5_BBS_URL?>/my_activity.php?type=online">온라인집들이</a></li>
					<li <? if($type == 'event') echo 'class="on"';?>><a href="<?=G5_BBS_URL?>/my_activity.php?type=event">이벤트</a></li>
				</ul>
			</div>

			<?
			if($type == 'item'){
				include_once($skin_path.'/item.skin.php');
			} else if($type == 'review'){
				include_once($skin_path.'/review.skin.php');
			} else if($type == 'online'){
				include_once($skin_path.'/online.skin.php');
			} else if($type == 'event'){
				include_once($skin_path.'/event.skin.php');
			}
			?>

		</div>
		<!-- 컨텐츠 종료 -->
	</div>
</div>
<!-- //container -->
<?
include_once('_tail.php');
?>
