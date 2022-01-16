<?
define('G5_IS_ADMIN', true);

include_once ('../common.php');
$g5['title'] = '로그인';
include_once('./admin.head.sub.php');

$url = strip_tags($_GET['url']);

// url 체크
check_url_host($url);

// 이미 로그인 중이라면
if ($is_member) {
	if ($url){
		goto_url($url);
	} else {
		goto_url(G5_URL);
	}
}

if(!$url){
	$url = G5_ADMIN_URL;
}
$login_url			= login_url($url);
$login_action_url	= G5_HTTPS_BBS_URL."/login_check.php";
?>

<div id="mb_login" class="mbskin" style="padding-top: 150px;">
	<div class="col-md-12">
		<div class="col-md-4"></div>
		<div class="col-md-4">
			<div class="x_panel">
				<div class="x_title" style="text-align: center;">
					<h1>LIFELIKE ADMIN</h1>
					<div class="clearfix"></div>
				</div>
				<div class="x_content" style="text-align: center;">
					<form class="form-horizontal form-label-left" name="flogin" action="<?=$login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
						<input type="hidden" name="url" value="<?=$login_url ?>">
						<div class="form-group text-center">
							<div class="col-md-2 col-sm-1 col-xs-1"></div>

							<div class="col-md-6 col-sm-6 col-xs-6 text-right">
								<input type="text" name="mb_id" id="login_id" required class="form-control required col-md-7 col-xs-12" maxLength="20" placeholder=" 아이디">
								<div class="clearfix" style="padding: 10px;"></div>
								<input type="password" name="mb_password" id="login_pw" required class="form-control required col-md-7 col-xs-12" maxLength="20" placeholder="비밀번호">
							</div>
							<div class="col-md-3 col-sm-4 col-xs-4" style="height:100%; vertical-align: middle;">
								<input type="submit" value="로그인" class="btn btn-primary" style="width:100%;height: 80px;">
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1"></div>
						</div>

						<div class="clearfix"></div>
						<div class="separator">
							<h4>
								<a href="./id_lost.php" target="_blank" id="login_password_lost">아이디 찾기</a> |
								<a href="./password_lost.php" target="_blank" id="login_password_lost">비밀번호 찾기</a>
							</h4>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-4"></div>
	</div>
</div>
<script>
	function flogin_submit(f){
		return true;
	}
</script>
<!-- } 로그인 끝 -->

<?
include_once(G5_PATH.'/adm/admin.tail.sub.php');
?>
