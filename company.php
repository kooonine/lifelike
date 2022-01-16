<?
include_once('./_common.php');

if (G5_IS_MOBILE) {
	include_once(G5_MOBILE_PATH.'/company.php');
	return;
}

$type = $_GET['type'];
$title = $_GET['title'];

if($type == "stipulation") $title = "이용약관";
else if($type == "privacy") $title = "개인정보처리방침";
else $title = "회사소개";

include_once(G5_PATH.'/head.php');
?>

<!-- container -->
<div id="container">
	<div id="lnb" class="header_bar">
		<h1 class="title"><span><?=$title?></span></h1>
	</div>
	<div class="content comm sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">

			<div class="detail_wrap detail_add">
				<?
				if($type == "stipulation") echo conv_content($config['cf_stipulation'], $config['cf_editor']);
				else if($type == "privacy") echo conv_content($config['cf_privacy'], $config['cf_editor']);
				else echo conv_content($default['de_user_reg_info'], $config['cf_editor']);
				?>
			</div>
		</div>
	</div>
</div>
<!-- //container -->
<?
include_once(G5_PATH.'/tail.php');
?>
