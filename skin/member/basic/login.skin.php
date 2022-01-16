<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_PATH . '/head.php');

?>

<!-- container -->
<div id="container">
	<div class="content login">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="title_bar none">
				<h2 class="g_title_01 alignC">LOGIN</h2>
			</div>
			<form name="flogin" action="<?= $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post" id="flogin">
				<input type="hidden" name="url" value="<?= $login_url ?>">
				<div class="login_container">
					<div class="inp_wrap">
						<div class="inp_ele">
							<div class="input"><input type="text" placeholder="아이디 입력" name="mb_id" id="login_id"></div>
						</div>
					</div>
					<div class="inp_wrap">
						<div class="inp_ele">
							<div class="input"><input type="password" placeholder="비밀번호 입력" name="mb_password" id="login_pw"></div>
						</div>
					</div>
					<div class="inp_wrap">
						<span class="chk check">
							<input type="checkbox" id="login_auto_login" name="auto_login" value="1">
							<label for="login_auto_login">로그인 상태를 유지합니다.</label>
						</span>
					</div>
					<div class="btn_group"><button type="submit" class="btn big green"><span>로그인</span></button></div>
					<ul class="member_link">
						<li><a href="<?= G5_URL ?>/common/register_select.php">회원가입</a></li>
						<li><a href="#" onclick="window.open('<?= G5_BBS_URL ?>/id_lost.php', 'mywin','left=100,top=100,width=1200,height=800,toolbar=1,resizable=1'); return false;">아이디찾기</a></li>
						<li><a href="#" onclick="window.open('<?= G5_BBS_URL ?>/password_lost.php', 'mywin','left=100,top=100,width=1200,height=800,toolbar=1,resizable=1'); return false;">비밀번호찾기</a></li>
					</ul>
				</div>
				<div class="login_container">
					<div class="title_bar none">
						<h2 class="g_title_01 alignC">간편가입</h2>
					</div>

					<?
					// 소셜로그인 사용시 소셜로그인 버튼
					include_once(get_social_skin_path() . '/social_outlogin.skin.1.php');
					?>

					<div class="btn_group">
						<!--
						<a href="<?= G5_URL ?>/common/nonmemberorder.php " class="btn big border bold">비회원 주문조회</a>
						<br/><br/>
						-->
						<button type="button" class="btn big border" id="btn_findorder"><span>비회원 주문조회</span></button>

					</div>

				</div>
			</form>
		</div>
		<!-- 컨텐츠 종료 -->
	</div>
</div>

<!-- popup -->
<section class="popup_container layer" id="popup_findorder" style="display: none;">
	<div class="inner_layer" style="top:10%;">
		<div class="content login">
			<!-- 컨텐츠 시작 -->
			<div class="grid cont">
				<div class="title_bar">
					<h1 class="g_title_01">비회원 주문/배송조회</h1>
				</div>
				<div class="title_bar none">
					<h2 class="g_title_03">비회원으로 제품을 구매하신 경우에만 주문/배송조회가 가능합니다.</h2>
				</div>
			</div>
			<div class="grid">
				<form name="forderinquiry" method="post" action="<?= G5_SHOP_URL . '/orderinquiry.php'; ?>" autocomplete="off">
					<div class="inp_wrap">
						<div class="title count3"><label for="join1">이름</label></div>
						<div class="inp_ele count6">
							<div class="input"><input type="text" placeholder="이름 입력" name="od_name" id="od_name" required="required"></div>
						</div>
					</div>
					<div class="inp_wrap">
						<div class="title count3"><label for="join2">휴대전화 번호</label></div>
						<div class="inp_ele count6">
							<div class="input"><input type="tel" placeholder="휴대전화 번호 입력" name="od_tel" id="od_tel" required="required"></div>
						</div>
					</div>
					<div class="inp_wrap">
						<div class="title count3"><label for="join3">주문번호</label></div>
						<div class="inp_ele count6">
							<div class="input"><input type="text" placeholder="주문번호 입력" name="od_id" id="od_id" required="required"></div>
						</div>
					</div>

					<!-- 간격 여백 -->
					<hr class="full_line">

					<div class="info_box">
						<p class="ico_import red point_red">주의해주세요.</p>
						<div class="list">
							<ul class="hyphen">
								<li>주문자명/휴대전화 번호/주문번호를 모두 입력하셔야 정상 조회가 가능합니다.</li>
								<li>주문번호가 기억나지 않은 경우 고객센터(<?= $default['de_admin_call_tel'] ?>)를 통해 문의해 주세요.</li>
							</ul>
						</div>
					</div>

					<div class="btn_group bottom none">
						<button type="submit" class="btn big green"><span>주문/배송조회</span></button>
					</div>
				</form>
			</div>
			<!-- 컨텐츠 종료 -->
		</div>
		<a style="cursor: pointer;" class="btn_closed" onclick="$('#popup_findorder').css('display','none')"><span class="blind">닫기</span></a>
	</div>
</section>
<!-- //popup -->

<script>
	$(function() {
		$("#login_auto_login").click(function() {
			if (this.checked) {
				this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
			}
		});
	});

	function flogin_submit(f) {
		return true;
	}

	$('#btn_findorder').click(function() {
		$("#popup_findorder").css("display", "");
	});
</script>
<?
include_once(G5_PATH . '/tail.php');
?>