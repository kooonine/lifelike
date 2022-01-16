<?php
include_once('./_common.php');
//if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderaddress_edit.php');
    return;
//}
if($w == 'u'){
    $g5['title'] = '배송지 수정';
}else {
    $g5['title'] = '배송지 등록';
}
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
<?php echo G5_POSTCODE_JS ?>

</head>
<body>

<!-- popup -->
	<section class="popup_container layer">
			
			<div class="inner_layer">

				<!-- lnb -->
				<div id="lnb" class="header_bar">
					<h1 class="title"><span>배송지 등록</span></h1>
					<a onclick="history.back();" class="btn_back" style="cursor: pointer;"><span class="blind">뒤로가기</span></a>
					<a href="#" class="btn_closed" onclick="javascript:self.close();"><span class="blind">닫기</span></a>
				</div>
				<!-- //lnb -->
				<div class="content comm sub">
					<!-- 컨텐츠 시작 -->
					<form name="forderaddress" method="post" action="<?php echo G5_SHOP_URL; ?>/orderaddress_edit_update.php" autocomplete="off">
					<input type="hidden" id="w" name="w" value="<?php echo $w?>">
					<input type="hidden" id="ad_id" name="ad_id" value="<?php echo $ad_id?>">
					<div class="grid ">
						<div class="inp_wrap">
							<div class="title count3"><label for="f1">주소 별칭</label></div>
							<div class="inp_ele count6 ">
								<div class="input "><input type="text" placeholder="주소 별칭 입력" id="ad_subject" name="ad_subject" value="<?php if($w == 'u') echo $addr_result['ad_subject'];?>"></div>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="title count3"><label for="f2">이름</label></div>
							<div class="inp_ele count6 ">
								<div class="input "><input type="text" placeholder="이름 입력" id="ad_name" name ="ad_name" value="<?php if($w == 'u') echo $addr_result['ad_name'];?>"></div>
							</div>
                        </div>
                        <div class="inp_wrap">
							<div class="title count3"><label for="f3">휴대전화 번호</label></div>
							<div class="inp_ele count6 ">
								<div class="input "><input type="tel" placeholder="휴대전화 번호 입력" id="ad_hp" name ="ad_hp" value="<?php if($w == 'u') echo $addr_result['ad_hp'];?>"></div>
							</div>
                        </div>
						<div class="inp_wrap">
							<div class="title count3"><label for="f4">연락처</label></div>
							<div class="inp_ele count6 ">
								<div class="input "><input type="tel" placeholder="연락처 입력" id="ad_tel" name ="ad_tel" value="<?php if($w == 'u') echo $addr_result['ad_tel'];?>"></div>
							</div>
						</div>
						<div class="inp_wrap">
                            <div class="title count3">
                                <label for="join7">주소</label>
                            </div>
                            <div class="inp_ele count6 r_btn_100">
                                <div class="input"><input type="text" placeholder="" id="ad_zip" name ="ad_zip" value="<?php if($w == 'u') echo $addr_result['ad_zip1'].$addr_result['ad_zip2'];?>" title="우편번호" readonly></div>
                                <button type="button" class="btn small green" onclick="win_zip('forderaddress', 'ad_zip', 'ad_addr1', 'ad_addr2','ad_addr3');">우편번호</button>
                            </div>
                        </div>
                        <div class="inp_wrap">
                            <div class="inp_ele count6 col_r">
                                <div class="input"><input type="text" placeholder="" id="ad_addr1" name ="ad_addr1" value="<?php if($w == 'u') echo $addr_result['ad_addr1'];?>" readonly></div>
                            </div>
                        </div>
                        <div class="inp_wrap">
                            <div class="inp_ele count6 col_r">
                                <div class="input"><input type="text" placeholder="" id="ad_addr2" name ="ad_addr2" value="<?php if($w == 'u') echo $addr_result['ad_addr2'];?>" ></div>
                            </div>
                        </div>
                		<input type="hidden" id="ad_addr3" name ="ad_addr3" value="<?php if($w == 'u') echo $addr_result['ad_addr3'];?>">
                        
						<div class="btn_group two">
							<button type="button" class="btn big border" onclick="goBack()"><span>취소</span></button>
							<button type="submit" class="btn big green"><span><?php if($w == 'u'){ echo '수정';}else{echo '등록';} ?></span></button>
						</div>
					</div>
					</form>
					<!-- 컨텐츠 종료 -->
				</div>

			</div>
			
		</section>
    


<script>
$(function() {
    

});
function goBack() {
	window.history.back();
}
</script>

</body>
</html>
