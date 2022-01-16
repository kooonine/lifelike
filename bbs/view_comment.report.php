<section class="popup_container layer" id="declaration_popup" style="display: none;">
	<div class="inner_layer">
		<div id="lnb" class="header_bar">
			<h1 class="title"><span>신고</span></h1>
		</div>
		<div class="grid">
			<div class="border_box alignC none">
			<p class="sm tb_cell">신고 사유를 선택해주세요. <br>신고 내용은 이용약관 및 정책에 의해 처리됩니다.</p>
			</div>
			<ul class="declaration-list">    
				<li><label><input type="radio" name="bg_comment" id="bg_comment1" value="욕설/비방"> 욕설/비방</label></li>
				<li><label><input type="radio" name="bg_comment" id="bg_comment2" value="광고/홍보글"> 광고/홍보글</label></li>
				<li><label><input type="radio" name="bg_comment" id="bg_comment3" value="개인정보유출"> 개인정보유출</label></li>
				<li><label><input type="radio" name="bg_comment" id="bg_comment4" value="게시글도배"> 게시글도배</label></li>
				<li><label><input type="radio" name="bg_comment" id="bg_comment5" value="음란/선정성"> 음란/선정성</label></li>
				<li><label><input type="radio" name="bg_comment" id="bg_comment6" value="저작권침해"> 저작권침해</label></li>
				<li><label><input type="radio" name="bg_comment" id="bg_comment7" value="기타"> 기타</label></li>
            </ul>
			<div class="btn_group two">
				<button type="button" class="btn big border" onclick="$('#declaration_popup').css('display','none');"><span>취소</span></button>
				<button type="submit" class="btn big black" onclick="nogood_write();"><span>신고하기</span></button>
			</div>
			<a class="btn_closed" onclick="$('#declaration_popup').css('display','none');"><span class="blind">닫기</span></a>
		</div>
	</div>
</section>