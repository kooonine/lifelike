<?php
include_once('_common.php');

$chk_id = $_GET['id'];
$type = $_GET['type'];
$type2 = $_GET['type2'];
$agree = $_GET['agree'];
$title = $_GET['title'];
if(!$title) $title = "서비스 이용 약관";
?>
<!DOCTYPE html>
<html lang="ko">
<title><?php echo $title ?></title>
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">

<!-- 스타일 -->
  <link rel="stylesheet" type="text/css" href="<?php echo G5_URL; ?>/js/swiper/swiper.min.css">
 <link rel="stylesheet" type="text/css" href="<?php echo G5_URL; ?>/css/pc_common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo G5_URL; ?>/css/pc_ui.css" />

<!-- 스크립트 -->
<script src="<?php echo G5_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo G5_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo G5_URL;?>/js/pc_ui.js" type="text/javascript"></script>

</head>
<body>
<!-- popup -->
<section class="popup_container layer">
	<div class="inner_layer">
		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid cont">
				<div class="title_bar">
					<h1 class="g_title_01"><?php echo $title; ?></h1>
				</div>
			</div>
			<div class="grid terms_wrap">
				<div class="terms_box">
				<?php 
				
				if($type) echo conv_content($config['cf_'.$type], $config['cf_editor']);
				if($type2) echo conv_content($default['de_'.$type2], $config['cf_editor']);
				
				?>
				</div>				
			</div>
			<?php if(!isset($agree)) { ?>
			<div class="btn_group bottom none"><button type="button" class="btn big green" id="agree"><span>동의합니다</span></button></div>
			<?php } ?>
			<!-- 컨텐츠 종료 -->
		</div>
		<a href="#" class="btn_closed" onclick="javascript : var win = window.open('', '_self'); win.close();return false;"><span class="blind">닫기</span></a>
	</div>
</section>
<!-- //popup -->
<script>
	$(document).ready(function(){
		var chk_id = '<?php echo $chk_id;?>';
		$('#agree').click(function() {
			$(opener.document).find('#'+chk_id).click();
			window.open('', '_self', '');

			window.close();


		});
	});
</script>	
</body>
</html>
