<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_MSHOP_SKIN_URL.'/style.css">', 0);
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span>1:1 문의하기</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>
			<form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
				<input type="hidden" name="w" value="<?php echo $w ?>">
				<input type="hidden" name="qa_id" value="<?php echo $qa_id ?>">
                <input type="hidden" name="sca" value="<?php echo $sca ?>">
                <input type="hidden" name="stx" value="<?php echo $stx ?>">
                <input type="hidden" name="page" value="<?php echo $page ?>">
                <input type="hidden" name="qa_html" value="1">
				<div class="content mypage sub">
				<!-- 컨텐츠 시작 -->
					<div class="grid">
						<div class="inp_wrap">
							<div class="title count3"><label for="f1">문의 유형</label></div>
							<div class="inp_ele count6">
								<span class="sel_box">
									<select name="qa_category" id="qa_category" required >
                                        <option value="">분류를 선택하세요</option>
                                        <?php echo $category_option ?>
                                    </select>
								</span>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="title count3"><label for="f2">이름 (또는 주문자명)</label></div>
							<div class="inp_ele count6">
                                <div class="input"><input type="text" id="qa_name" name="qa_name" value="<?php echo ($write['qa_name'])?$write['qa_name']:$member['mb_name']?>" readonly="readonly" class="readonly"></div>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="title count3"><label for="f3">주문번호</label></div>
							<div class="inp_ele r_btn count6">
								<div class="input"><input type="text" id="od_id" name="od_id" value="<?php echo $$write['od_id']?>"></div>
								<button type="button" class="btn green" id="btn_order">검색</button>
							</div>
						</div>
						<div class="inp_wrap">
							<p class="ico_import red point_red">주문번호 기입 시, 더욱 정확한 답변 및 처리가 가능합니다.</p>
						</div>
						
                        <div class="inp_wrap">
                            <div class="title count3"><label for="f3">상품번호</label></div>
                            <div class="inp_ele count6">
                                <div class="input"><input type="text" id="it_id" name="it_id" readonly="readonly" value="<?php echo ($write['it_id'])?$write['it_id']:$it_id?>" class="readonly"></div>
                            </div>
                        </div>
                        
						<div class="inp_wrap">
							<div class="title count3"><label for="f4">작성자(ID)</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="text" id="mb_id" name="mb_id" placeholder="" value="<?php echo ($write['mb_id'])?$write['mb_id']:$member['mb_id']?>" readonly="readonly" class="readonly"></div>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="title count3"><label for="f5">작성자(email)</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="text" name="qa_email" value="<?php echo get_text($write['qa_email']); ?>" id="qa_email" <?php echo $req_email; ?> class="<?php echo $req_email.' '; ?>frm_input full_input email" size="50" maxlength="100" placeholder="이메일"></div>
							</div>
						</div>
					</div>
					<div class="grid">
						<div class="inp_wrap">
							<label for="f6" class="blind">제목</label>
							<div class="inp_ele">
								<div class="input"><input type="text" name="qa_subject" value="<?php echo get_text($write['qa_subject']); ?>" id="qa_subject" required class="frm_input full_input required" size="50" maxlength="255" placeholder="제목">
								</div>
							</div>
						</div>
						<div class="inp_wrap">
							<label for="f7" class="blind">내용</label>
							<div class="inp_ele">
								<div class="input"><?php echo $editor_html;?></div>
							</div>
						</div>
                  
						<div class="btn_group two">
							<button type="button" class="btn big border gray" onclick="history.back();"><span>취소</span></button>
							<button type="submit" class="btn big green"><span><?php if($w=='u') echo '수정'; else echo '등록';?></span></button>
						</div>
					</div>
				<!-- 컨텐츠 끝 -->
				</div>
			</form>
		</div>
		<!-- //container -->
		<div id="popup"></div>
        <script>
			$(function(){

    			$('#btn_order').click(function(){
    				var od_id = $("#od_id").val();

    		        $.post(
    		        		"<?php echo G5_SHOP_URL?>/ajax.orderlist.php",
    		                { od_id : od_id },
    		                function(data) {
    		                	$("#popup").empty().html(data);
    		                }
    		            );
    			});

    		    $(document).on("click", ".btn_order_submit", function() {
    				var od_id = '';
    				var it_id = '';
    				$("input[name='chk_od']:checked").each(function(index){
    					od_id = $(this).val();
    					it_id = $(this).attr('it_id');
    				});
    				$('#od_id').val(od_id);
    				$('#it_id').val(it_id);
    				$("#popup").empty();
    			});
    		});

            function fwrite_submit(f)
            {
                <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>
        
                var subject = "";
                var content = "";
                $.ajax({
                    url: g5_bbs_url+"/ajax.filter.php",
                    type: "POST",
                    data: {
                        "subject": f.qa_subject.value,
                        "content": f.qa_content.value
                    },
                    dataType: "json",
                    async: false,
                    cache: false,
                    success: function(data, textStatus) {
                        subject = data.subject;
                        content = data.content;
                    }
                });
        
                if (subject) {
                    alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
                    f.qa_subject.focus();
                    return false;
                }
        
                if (content) {
                    alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
                    if (typeof(ed_qa_content) != "undefined")
                        ed_qa_content.returnFalse();
                    else
                        f.qa_content.focus();
                    return false;
                }
        
        
                document.getElementById("btn_submit").disabled = "disabled";
        
                return true;
            }
            </script>
		<!-- footer -->
		
		<!-- //footer -->
	</div>
</body>
</html>