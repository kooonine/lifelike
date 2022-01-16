<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('_common.php');
$mb_id = $_GET['mb_id'];
?>
<html lang="ko">
<head>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">

<!-- 스타일 -->
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/js/swiper/swiper.min.css">
 <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo G5_MOBILE_URL; ?>/css/m_ui.css" />

<!-- 스크립트 -->
<script src="<?php echo G5_MOBILE_URL;?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo G5_MOBILE_URL;?>/js/m_ui.js" type="text/javascript"></script>
    

</head>
<body>
<!-- popup -->
		<section class="popup_container">
			<div class="grid">

				<div class="guide_box ico ico_cart">
					<p>선택하신 제품이<br>장바구니에 담겼습니다.</p>
				</div>

				<div class="btn_group two">
					<button type="button" class="btn big border gray" name="btn" data="ing"><span>쇼핑 계속</span></button>
					<button type="button" class="btn big green" name="btn" data = "stop"><span>장바구니 확인</span></button>
				</div>

			</div>
		</section>
		<!-- //popup -->
		<script>
			$(document).ready(function(){
				var url = "<?php echo G5_URL?>"
				$('button[name="btn"]').click(function() {
					if($(this).attr('data') == 'stop'){
						if(opener.closed) {   //부모창이 닫혔는지 여부 확인

						      // 부모창이 닫혔으니 새창으로 열기

						      window.open(url, "openWin");

						   } else {

						      opener.location.href = url;

						      opener.focus();

						   }
					}
					self.close();
				});
			});
		</script>
		
</body>
</html>
