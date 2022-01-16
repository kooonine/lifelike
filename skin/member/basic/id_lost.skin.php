 
<!-- container -->
<div id="container">
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>아이디/비밀번호 찾기</span></h1>
	</div>
    <div class="content comm sub">
    				<!-- 컨텐츠 시작 -->
    				<div class="grid">
    					<div class="tab_cont_wrap">
    						<div class="tab">
    							<ul class="type3">
    								<li class="on" name="type"><a href="<?php echo G5_BBS_URL?>/id_lost.php"><span>아이디 찾기</span></a></li>
    								<li class="" name="type"><a href="<?php echo G5_BBS_URL?>/password_lost.php"><span>비밀번호 찾기</span></a></li>
    							</ul>
    						</div>
    						<div class="tab_cont">
    							<!-- tab1 -->
    							<div class="tab_inner">
    								<!-- 아이디 찾기 -->
    								<div class="inner_wrap" id ="div_search_form">
    									<div class="inp_wrap">
    										<div class="title count3"><label for="f_01">이름</label></div>
    										<div class="inp_ele count6">
    											<div class="input">
    												<input type="text" placeholder="이름 입력" id="name">
    											</div>
    										</div>
    									</div>
    									<div class="inp_wrap">
    										<div class="title count3"><label for="f_02">인증수단</label></div>
    										<div class="inp_ele count6">
    											<span class="chk radio">
    												<input type="radio" id="f_02_1" name="auth_type" value="phone">
    												<label for="f_02_1">휴대전화 번호로 찾기</label>
    											</span>
    											<span class="chk radio">
    												<input type="radio" id="f_02_2" name="auth_type" value="email">
    												<label for="f_02_2">이메일로 찾기</label>
    											</span>
    											<div class="input">
    												<input type="text" placeholder="" id="auth_text">
    											</div>
    										</div>
    									</div>
    
    									<!-- 간격/여백 -->
    									<hr class="full_line">
    
    									<div class="info _box">
    										<p class="ico_import red point_red">주의하세요.</p>
    										<div class="list">
    											<ul class="hyphen">
    												<li>가입 시 등록한 회원 정보로 입력하셔야 아이디/비밀번호를 찾으실 수 있습니다.</li>
    											</ul>
    										</div>
    									</div>
    								</div>
    								<!-- //아이디 찾기 -->
    
    								<!-- 아이디 찾기 완료 -->
    								<div class="find_form" style="display: none" id ="div_search_result">
    									<div class="title_bar">
    										<h3 class="g_title_01">아이디 찾기 결과</h3>
    									</div>
    									<div class="border_box alignC" id="div_search_id">
    										
    									</div>
    								</div>
    								<!-- //아이디 찾기 완료 -->
    
    							</div>
    
    						</div>
    					</div>
    					<div class="btn_group"><button type="button" class="btn big green" id="btn_search"><span id = "sp_search"></span></button></div>
    
    				</div>
    				<!-- 컨텐츠 종료 -->
    			</div>
    		</div>
    </div>
</div>
		
<script>
$(function(){
	$('#sp_search').html('아이디 찾기');
    $(document).on("click", "#btn_search", function() {
        if($('#sp_search').html()== '아이디 찾기'){
        
        	
            $.post(
                "<?php echo $action_url; ?>",
                { name: $('#name').val(), auth_type: $('input[type="radio"]:checked').val(),  auth_text:$('#auth_text').val()},
                function(data) {
                    $("#div_search_id").html(trim(data).substring(1));
                    if(trim(data).substring(0,1)=='S'){
                    	$('#sp_search').html('확인');
                        $('#div_search_result').css('display','block');
                        $('#div_search_form').css('display','none');
                    }
                }
            );
        }else {
        	//location.href = '<?php echo G5_BBS_URL?>/login.php';
        	window.close();
        }
    });

});
</script>
		
</body>
</html>