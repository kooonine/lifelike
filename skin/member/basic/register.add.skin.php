<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
if($mb)
{
    $mb_dir = substr($mb['mb_id'],0,2);
    $icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
    if (file_exists($icon_file)) {
        $icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
    }
    
    $mb_birth_explode = explode('-',$mb['mb_birth']);
}

include_once(G5_PATH.'/head.php');
?>

        <!-- lnb -->
        <div id="lnb" class="header_bar">
            <h1 class="title"><span>추가정보 입력</span></h1>
        </div>

        <div class="content comm sub">
				<!-- 컨텐츠 시작 -->
				<div class="grid">
					<div class="title_bar">
						<h2 class="g_title_02">프로필 등록<span class="txt_essential"><em>필수 입력 사항</em></span></h2>
					</div>
					<form id = "frm_member" name ="frm_member" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off" enctype="multipart/form-data">
					<input type="hidden" name="mb_id" value="<?php echo $mb_id?>">
					<input type="hidden" name="mb_birth" id="mb_birth" value="">
					<input type="hidden" name="mb_2" id="mb_2" value="">
				<div class="profile_area_wrap">
					<div class="profile_area">
						<div class="profile_photo">
							<p class="photo"><img  src="<?php echo $icon_url ?>" alt="" id="img"/></p>
							<input type="file" id="mb_img" name="mb_img" style="display: none">
							<button type="button" class="register" id="btn_profile_img"><span class="blind">프로필 등록</span></button>
						</div>
					</div>
					<div class="right_cont">
    					<div class="inp_wrap">
    						<div class="title count3"><label for="join1">닉네임 입력<span class="txt_essential"><em>필수 입력 사항</em></span></label></div>
    						<div class="inp_ele count6 r_btn_120">
    							<div class="input"><input type="text" placeholder="한글, 영문, 숫자, 포함 2~10자" id="mb_nick" name="mb_nick" value="<?php echo isset($mb['mb_nick'])?get_text($mb['mb_nick']):''; ?>"></div>
    							<button type="button" class="btn small green" id="btn_id_check">중복확인</button>
    							<input type="hidden" id="nickYN" >
    						</div>
    					</div>
    					<div class="inp_wrap">
    						<div class="title count3"><label for="join2">생년월일<span class="txt_essential"><em>필수 입력 사항</em></span></label></div>
    						<div class="inp_ele count2">
    							<span class="sel_box">
    								<select name="year" id= "year" title="목록" target1="month" target2="day">
    									<option value="">선택</option>
    									<?
    									//1960~현재년도까지
    									foreach(range(date('Y'), 1960) as $val){
    									    if($mb_birth_explode[0] == $val) $selected = 'selected'; else $selected = '';
    									    echo '<option value="'.$val.'" '.$selected.' >'.$val.'</option>';
    									}
                                        ?>
    
    								</select>
    							</span>
    						</div>
    						<div class="inp_ele count2">
    							<span class="sel_box">
    								<select name="month"  id ="month" title="목록" target1="year" target2="day">
    									<option value="">선택</option>
    									<?
                
                                        //1월부터 12월까지
                						foreach(range(1, 12) as $val) {
                						    if($mb_birth_explode[1] == $val) $selected = 'selected'; else $selected = '';
                						    echo '<option value="'.sprintf('%0d' , $val).'" '.$selected.' >'.sprintf('%d월' , $val).'</option>';
                						}
                                        ?>
    								</select>
    							</span>
    						</div>
    						<div class="inp_ele count2">
    							<span class="sel_box">
    								<select name="day"  id ="day"  title="목록">
    									<option value="">선택</option>
    									<?
                                        //1월부터 12월까지
                						foreach(range(1, 31) as $val){
                						    if($mb_birth_explode[2] == $val) $selected = 'selected'; else $selected = '';
                						    echo '<option value="'.sprintf('%0d' , $val).'" '.$selected.' >'.sprintf('%d일' , $val).'</option>';
                						}
                                        ?>
    								</select>
    							</span>
    						</div>
    					</div>
    					<div class="inp_wrap">
    						<div class="title count3"><label for="join3">성별<span class="txt_essential"><em>필수 입력 사항</em></span></label></div>
    						<div class="inp_ele count3">
    							<span class="chk radio">
    								<input type="radio" id="r_01_1" name="mb_sex" value="M" <?php if($mb['mb_sex'] == 'M') echo "checked='checked'";?>>
    								<label for="r_01_1">남성</label>
    							</span>
    						</div>
    						<div class="inp_ele count3">
    							<span class="chk radio">
    								<input type="radio" id="r_01_2" name="mb_sex" value="F" <?php if($mb['mb_sex'] == 'F') echo "checked='checked'";?>>
    								<label for="r_01_2">여성</label>
    							</span>
    						</div>
    					</div>
    					<div class="inp_wrap">
    						<div class="title count3"><label for="join4">결혼 유무</label></div>
    						<div class="inp_ele count3">
    							<span class="chk radio">
    								<input type="radio" id="r_02_1" name="mb_1" value="0">
    								<label for="r_02_1">미혼</label>
    							</span>
    						</div>
    						<div class="inp_ele count3">
    							<span class="chk radio">
    								<input type="radio" id="r_02_2" name="mb_1" value="1">
    								<label for="r_02_2">기혼</label>
    							</span>
    						</div>
    					</div>
    					<div class="inp_wrap">
    						<div class="title count3"><label for="join5">결혼 기념일</label></div>
    						<div class="inp_ele count2">
    							<span class="sel_box">
    								<select name="year2" id= "year2" title="목록" target1="month2" target2="day2">
    									<option value="">선택</option>
    									<?
    
                                        //1960~현재년도까지
    									
                                        foreach(range(date('Y'), 1960) as $val) echo '<option value="'.$val.'">'.$val.'</option>';
                                        
                                        
                                        ?>
    
    								</select>
    							</span>
    						</div>
    						<div class="inp_ele count2">
    							<span class="sel_box">
    								<select name="month2"  id ="month2" title="목록" target1="year2" target2="day2">
    									<option value="">선택</option>
    									<?
    
                                        //1월부터 12월까지
                                        foreach(range(1, 12) as $val) echo '<option value="'.sprintf('%d' , $val).'">'.sprintf('%d월' , $val).'</option>';
                                        
                                        ?>
    								</select>
    							</span>
    						</div>
    						<div class="inp_ele count2">
    							<span class="sel_box">
    								<select name="day2"  id ="day2"  title="목록">
    									<option value="">선택</option>
    									<?
    
                                        //1월부터 12월까지
                                        foreach(range(1, 31) as $val) echo '<option value="'.sprintf('%d' , $val).'">'.sprintf('%d일' , $val).'</option>';
                                        
                                        ?>
    								</select>
    							</span>
    						</div>
    					</div>
    				</div>
    			</div>

                <div class="grid">
                    <div class="inp_wrap">
                        <?php if($type == 'company'){
                                $cp = sql_fetch("select * from lt_member_company where mb_id= '{$mb_id}'");
                                ?>
                        <span class="chk radio floatR">
                            <input type="checkbox" id="chk_cop_addr" name="chk_cop_addr" value="1">
                            <label for="chk_cop_addr">회사 주소지와 동일합니다</label>
                        </span>
                        <script>
                        $(function($){
                            $("#chk_cop_addr").click(function(){

                            	if($("#chk_cop_addr").is(":checked")){
                            		$("#mb_zip").val('<?php echo $cp['company_zip1'].$cp['company_zip2'] ?>');
                            		$("#mb_addr1").val('<?php echo $cp['company_addr1'] ?>');
                            		$("#mb_addr2").val('<?php echo $cp['company_addr2'] ?>');
                            		$("#mb_addr3").val('<?php echo $cp['company_addr3'] ?>');
                            		$("#mb_addr_jibeon").val('<?php echo $cp['company_addr_jibeon'] ?>');
                            	}
                            });
                        });
                        </script>
                        <?php }?>
                    </div>			
                    <div class="inp_wrap">
                        <div class="title count3">
                            <label for="join6">주소(기본 배송지)<span class="txt_essential"><em>필수 입력 사항</em></span></label>
                        </div>
                        <div class="inp_ele count6 r_btn_120 address">
                            <div class="input">
                            	<input type="text" placeholder="" id="mb_zip" name="mb_zip" title="우편번호" value="<?php echo $mb['mb_zip1'].$mb['mb_zip2'] ?>" readonly >
                            </div>
                            <button type="button" class="btn small green" onclick="win_zip('frm_member','mb_zip' , 'mb_addr1', 'mb_addr2', 'mb_addr3','mb_addr_jibeon');">우편번호</button>
                            <div class="input"><input type="text" placeholder="" id="mb_addr1"  name = "mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" readonly ></div>
                            <div class="input"><input type="text" placeholder="" id="mb_addr2"  name = "mb_addr2" value="<?php echo $mb['mb_addr2'] ?>"></div>
                        </div>
                		<input type="hidden" id = "mb_addr_jibeon" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon'] ?>">
                		<input type="hidden" id = "mb_addr3" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>">
					</div>
					<!-- 간격 여백 -->
				<div class="grid">
					<div class="inp_wrap">
						<div class="title count3"><label for="join7">추천인 아이디</label></div>
						<div class="inp_ele count6">
							<div class="input">
								<input type="text" placeholder="추천인 아이디 입력" id="mb_recommend" name="mb_recommend">
                            </div>
                            <ul class="hyphen mt20">
								<li class="lh15">추천인 아이디 입력 시 추천인과 본인에게 적립금 <span class="strong"><?php echo $config['cf_recommend_point'];?>원씩</span> 드립니다.<span class="span_block"> (추천인의 아이디를 정확히 입력해 주세요.)</li>
							</ul>
                        </div>
					</div>
				</div>
				</div>
				<div class="grid">
					<div class="btn_group"><button type="submit" class="btn big green"><span>완료</span></button></div>
				</div>
					</form>
				</div>
				<!-- 컨텐츠 종료 -->
			</div>
		</div>
		<!-- //container -->
		
		<!-- footer -->

		<!-- //footer -->
	</div>
<!-- </body> -->
<!-- </html> -->

    <script>
    function fregister_submit(f)
    {
        if (f.mb_sex.value == '' || f.mb_nick == '' || f.year.value == '' || f.month.value == '' || f.day.value == '' || f.mb_addr1.value == ''|| f.mb_addr2.value == ''|| f.mb_zip.value == '') {
            alert("닉네임등록, 생년월일, 성별, 주소는 필수 입력값 입니다.");
            return false;
        }

        if($('#nickYN').val() != 'Y'){
			alert("모든 인증이 완료되어야 가입이 가능합니다.");
            
            return false;
		}
		var mb_birth = f.year.value +'-'+ f.month.value +'-'+  f.day.value
        $('#mb_birth').val(mb_birth);
		var mb_2 = f.year2.value +'-'+ f.month2.value +'-'+  f.day2.value
        $('#mb_2').val(mb_2);
        return true;
    }

    function handleImgFileSelect(e) {
        var files = e.target.files;
        var filesArr = Array.prototype.slice.call(files);

        filesArr.forEach(function(f) {
            if(!f.type.match("image.*")) {
                alert("확장자는 이미지 확장자만 가능합니다.");
                return;
            }

            sel_file = f;

            var reader = new FileReader();
            reader.onload = function(e) {
                $("#img").attr("src", e.target.result);
            }
            reader.readAsDataURL(f);
        });
    }
	
    jQuery(function($){
        // 모두선택
        
        
        $("#mb_img").on("change", handleImgFileSelect);
        var timer = 180;
        $('#btn_profile_img').click(function () {
        	$("#mb_img").click();
    	});
        $('#btn_id_check').click(function () {
        	$.ajax({
                type: "POST",
                url: "<?php echo G5_BBS_URL.'/ajax.mb_nick.php'; ?>",
                data: {
                    "reg_mb_id": "<?php echo $mb_id;?>"
                    ,"reg_mb_nick": $("#mb_nick").val()
                },
                cache: false,
                async: false,
                success: function(data) {
                	var msg = data;
                    if (msg) {
                        alert(msg);
                        $("#mb_nick").focus();
                        return false;
                    }else {
                    	$('#nickYN').val('Y');
                    	alert('사용 가능합니다.');
                    }
                }
            });
    	});
        
        $('#year, #year2').change(function ()
        {	
            var id = $(this).attr('id');
            var target1 = $(this).attr('target1');
            var target2 = $(this).attr('target2');
            var year = $('#'+id+' option:selected').val();
            var month = $('#'+target1+' option:selected').val();
            if(year != '' & month != '')
            //month 는 0 부터 시작해서..
            var day = 32 - new Date(year, month-1, 32).getDate();
            $.fn_append_day(day,target2);
        });

        $('#month, #month2').change(function ()
        {
        	var id = $(this).attr('id');
            var target1 = $(this).attr('target1');
            var target2 = $(this).attr('target2');
            var year = $('#'+target1+' option:selected').val();
            var month = $('#'+id+' option:selected').val();
            
            //month 는 0 부터 시작해서..
            var day = 32 - new Date(year, month-1, 32).getDate();
            $.fn_append_day(day,target2);
        });

        $.fn_append_day = function(day,target){
        	$('#'+target).html('');
        	var html = '';
            for(var i = 1 ; i < day+1 ; i++){
                html = '<option value="'+i+'">'+i+'일</option>';
            	$('#'+target).append(html);    
            }
        }
        
        $("input[name=chk_all]").click(function() {
			$("input[name^=agree]").click();
        });
    });

    
    </script>
<!-- </body> -->

<!-- </html> -->
<?php
include_once(G5_PATH.'/tail.php');
?>
