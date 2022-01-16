<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>회원가입 완료</span></h1>';
header += '<button type="button" class="btn_closed" onclick="location.href=\'/\'"><span class="blind">닫기</span></button>';
header += '</div>';
$('#header').html(header);
</script>
<div class="content comm sub">
	<!-- 컨텐츠 시작 -->
	<div class="grid">
		<div class="guide_box ico ico_chk">
			<p>안녕하세요, <span class="point"><?php echo $mb_name?></span> 님<br>라이프라이크 회원이 되어주셔서 감사합니다. <br>일상을 더 선명하게 즐겁게 할 라이프라이크의 특별한 서비스, 지금 바로 만나보세요.</p>
		</div>
		<div class="btn_group"><a href="<?php echo G5_URL?>/index.php" class="btn big green"><span>메인으로</span></a></div>
	</div>
	
	<!-- 컨텐츠 종료 -->
</div>
		
</body>
</html>
