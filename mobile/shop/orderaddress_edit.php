<?php
include_once('./_common.php');

if($w == 'u'){
    $g5['title'] = '배송지 수정';
}else {
    $g5['title'] = '배송지 등록';
}
include_once(G5_MSHOP_PATH.'/_head.php');

?>
<script>
var header = '';
$('#header').html(header);
</script>

<!-- popup -->
	<section class="popup_container layer">
			
			<div class="inner_layer">

				<!-- lnb -->
				<div id="lnb" class="header_bar">
					<h1 class="title"><span>배송지 등록 <?php echo G5_POSTCODE_JS ?></span></h1>
					<a onclick="history.back();" class="btn_back" style="cursor: pointer;"><span class="blind">뒤로가기</span></a>
					<a class="btn_closed" onclick="javascript : var win = window.open('', '_self'); win.close();return false;"><span class="blind">닫기</span></a>
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
                            <div class="inp_ele count9">
                                <div class="input"><input type="text" placeholder="" id="ad_addr1" name ="ad_addr1" value="<?php if($w == 'u') echo $addr_result['ad_addr1'];?>" readonly></div>
                            </div>
                        </div>
                        <div class="inp_wrap">
                            <div class="inp_ele count9">
                                <div class="input"><input type="text" placeholder="" id="ad_addr2" name ="ad_addr2" value="<?php if($w == 'u') echo $addr_result['ad_addr2'];?>" ></div>
                            </div>
                        </div>
                		<input type="hidden" id="ad_addr3" name ="ad_addr3" value="<?php if($w == 'u') echo $addr_result['ad_addr3'];?>">
                        
						<div class="btn_group two">
							<button type="button" class="btn big border" onclick="history.back();" ><span>취소</span></button>
							<button type="submit" class="btn big green"><span><?php if($w == 'u'){ echo '등록';}else{echo '등록';} ?></span></button>
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
</script>

</body>
</html>
