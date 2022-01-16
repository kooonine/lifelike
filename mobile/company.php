<?php
include_once('./_common.php');

$g5['title'] = '회사소개';
include_once(G5_MSHOP_PATH.'/_head.php');
?>

<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>회사소개</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<!-- //lnb -->
<div class="content sub community type3">
	<!-- 컨텐츠 시작 -->
	<div class="grid head">
		<div class="detail_wrap">
			<?php echo $default['de_user_reg_info']?>		
		</div>
	</div>
	<!-- 컨텐츠 종료 -->
</div>
<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
?>