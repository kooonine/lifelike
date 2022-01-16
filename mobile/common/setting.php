<?
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('../../common.php');

if(!$is_member){
	goto_url(G5_BBS_URL."/login.php?url=/mobile/common/setting.php");
}

$mb = get_member($member['mb_id']);

$autologinid = get_cookie('ck_mb_id');
$autologinkey = get_cookie('ck_auto');

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
	$theme_mypage_file = G5_THEME_MSHOP_PATH.'/setting.php';
	if(is_file($theme_mypage_file)) {
		include_once($theme_mypage_file);
		return;
		unset($theme_mypage_file);
	}
}

$g5['title'] = '마이페이지';
include_once(G5_MSHOP_PATH.'/_head.php');
?>
<script>
	$('#header').html('');
</script>
<div class="wrap_all">
	<p id="skipNavi"><a href="#container">본문 바로가기</a></p>

	<!-- aside -->
	<? include_once('../aside.php'); ?>

	<!-- container -->
	<div id="container">
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>설정</span></h1>
			<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>
			<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>
		</div>

		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid set">
				<div class="title_bar">
					<h1 class="g_title_01">설정</h1>
				</div>
				<div class="title_bar">
					<h2 class="g_title_01">로그인 설정</h2>
				</div>
				<div class="set_cont">
					<div class="bar">
						<h3 class="tit">자동 로그인</h3>
						<span class="switch_group">

							<? if ($autologinid != '' && $autologinkey != '') {?>
								<button type="button" class="switch on" id="auto_login"><span></span></button>
							<? } else { ?>
								<button type="button" class="switch off" id="auto_login"><span></span></button>
							<? } ?>
						</span>
					</div>
				</div>

				<?
				//소셜계정이 있다면
				if(function_exists('social_login_link_account') && $mb['mb_id'] ){
					if( $my_social_accounts = social_login_link_account($mb['mb_id'], false, 'get_data') ){ ?>
						<div class="title_bar">
							<h2 class="g_title_01">SNS 연동</h2>
						</div>
						<div class="set_cont">
						<? foreach($my_social_accounts as $account){     //반복문
							if( empty($account) ) continue;

							$provider = strtolower($account['provider']);
							$provider_name = social_get_provider_service_name($provider);
							?>
							<div class="bar">
								<h3 class="tit">
									<?
									if($provider == 'naver') echo '<img src="../../img/mb/ico/ico_sns_naver.png" alt="네이버"><span>네이버</span>';
									else if($provider == 'kakao') echo '<img src="../../img/mb/ico/ico_sns_talk.png" alt="카카오톡"><span>카카오톡</span>';
									else if($provider == 'facebook') echo '<img src="../../img/mb/ico/ico_sns_facebook.png" alt="페이스북"><span>페이스북</span>';
									?>
								</h3>
								<span class="sns_right_txt">
									<?=$account['displayname']; ?>
								</span>
							</div>
						<? } ?>
					</div>
				<? }
			} ?>

			<div class="title_bar">
				<h2 class="g_title_01">알람 수신 설정</h2>
			</div>
			<div class="set_cont">
				<div class="bar">
					<h3 class="tit">광고성 정보(PUSH) 수신동의</h3>
					<span class="switch_group">
						<button type="button" class="switch off"><span></span></button>
					</span>
				</div>
				<p class="txt">본 설정은 해당 기기에서만 유효하며, 수신 동의하시면 쿠폰, 할인, 상품 정보 및 주문 입고알림, 등도 받으실 수 있습니다.</p>
			</div>
			<div class="set_cont">
				<div class="bar">
					<h3 class="tit">알림 설정</h3>
					<span class="switch_group">
						<button type="button" class="switch off"><span></span></button>
					</span>
				</div>
				<p class="txt">제품 발송, 리스, 케어 관련 (광고성 메시지 포함) 알림을 받으실 수 있습니다.</p>
			</div>

		</div>
		<div class="grid bg_none">
					<!-- div class="set_cont">
						<div class="bar">
							<h3 class="tit">앱 버전</h3>
							<span class="right_txt">
								<span>1.31</span><button type="button" class="btn small green_line round"><span>앱 업데이트</span></button>
							</span>
						</div>
					</div -->
					<? if ($is_member) { ?>
						<div class="btn_group"><a href="<?=G5_BBS_URL; ?>/logout.php"><button type="button" class="btn big border"><span>로그아웃</span></button></a></div>
					<? } ?>
				</div>
				<!-- 컨텐츠 종료 -->
			</div>

		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#auto_login').click(function(){
				var chk = ($(this).hasClass('on') == false)?1:0;

				$.ajax({
					type: "POST",
					url: "<?=G5_BBS_URL.'/login_check2.php'; ?>",
					data: {
						"mb_id": '<?=$member['mb_id']?>'
						,"auto_login":chk
					},
					cache: false,
					async: false,
					success: function(data) {
					}
				});

			});
		});
	</script>
<? include_once(G5_MSHOP_PATH.'/_tail.php'); ?>
