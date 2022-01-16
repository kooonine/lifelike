<? include_once('../../common.php');

$chk_id = $_GET['id'];
$type = $_GET['type'];
$type2 = $_GET['type2'];
$agree = $_GET['agree'];
$title = $_GET['title'];
if(!$title) $title = "서비스 이용 약관";
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<title><?=$title ?></title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">

	<!-- 스타일 -->
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/css/m_common.css" />
	<link rel="stylesheet" type="text/css" href="<?=G5_MOBILE_URL; ?>/css/m_ui.css" />

	<!-- 스크립트 -->
	<script src="<?=G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="<?=G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?=G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
	<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
</head>
<body>
	<!-- popup -->
	<section class="popup_container layer">
		<div class="inner_layer">
			<div id="lnb" class="header_bar">
				<h1 class="title"><span><?=$title; ?></span></h1>
				<a href="#" class="btn_closed" onclick="javascript : var win = window.open('', '_self'); win.close();return false;"><span class="blind">닫기</span></a>
			</div>
			<div class="content comm sub">
				<!-- 컨텐츠 시작 -->
				<div class="grid terms_wrap">
					<div class="terms_box">
						<?

						if($type) echo $config['cf_'.$type] ;
						if($type2) echo $default['de_'.$type2] ;

						?>
					</div>					
				</div>
				<? if(!isset($agree)) { ?>
					<div class="btn_group none"><button type="button" class="btn big green" id="agree"><span>동의합니다</span></button></div>
				<? } ?>
				<!-- 컨텐츠 종료 -->
			</div>
		</div>
	</section>
	<!-- //popup -->
	<script>
		$(document).ready(function(){
			var chk_id = '<?=$chk_id;?>';
			$('#agree').click(function() {
				$(opener.document).find('#'+chk_id).prop('checked', true);
				window.open('', '_self', '');
				window.close();
			});
		});
		
	    // 이미지 리사이즈
	    $(".terms_box").viewimageresize();
	</script>
</body>
</html>
