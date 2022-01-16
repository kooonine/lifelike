<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>
<!-- container -->
<div id="container">
    <!-- lnb -->
    <div id="lnb" class="header_bar">
        <h1 class="title"><span>1:1 문의하기</span></h1>
        <a href="#" class="btn_back"><span class="blind">뒤로가기</span></a>
    </div>
    <!-- //lnb -->
    <div class="content mypage sub">
        <!-- 게시물 작성/수정 시작 { -->
        <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="qa_id" value="<?php echo $qa_id ?>">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <?php
        $option = '';
        $option_hidden = '';
        $option = '';
        if ($is_dhtml_editor) {
            $option_hidden .= '<input type="hidden" name="qa_html" value="1">';
        } else {
            $option .= "\n".'<input type="checkbox" id="qa_html" name="qa_html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'."\n".'<label for="qa_html">html</label>';
        }
        echo $option_hidden;
        ?>

        <div class="grid border_box divide_inp">
            <div class="inp_wrap">
                <div class="title count3"><label for="f1">문의 유형</label></div>
                <div class="inp_ele count6">
                    <span class="sel_box">
                        <select name="qa_category" id="qa_category" required >
                            <option value="">선택</option>
                            <?php echo $category_option ?>
                        </select>
                    </span>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="title count3"><label for="f2">이름 또는 주문자명</label></div>
                <div class="inp_ele count6">
                    <div class="input"><input type="text" id="f4" placeholder="" value="<?php echo ($write['qa_name'])?$write['qa_name']:$member['mb_name']?>" readonly="readonly" class="readonly"></div>
                </div>
            </div>

            <div class="inp_wrap">
                <div class="title count3"><label for="f3">주문번호</label></div>
                <div class="inp_ele r_btn count6">
                    <div class="input"><input type="text" id="od_id" name="od_id" placeholder="주문번호 입력" value="<?php echo $write['od_id']?>"></div>
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
            <div class="inp_wrap"></div>

            <div class="inp_wrap">
                <div class="title count3"><label for="f4">작성자(ID)</label></div>
                <div class="inp_ele count6">
                    <div class="input"><input type="text" id="f4" placeholder="" value="<?php echo ($write['mb_id'])?$write['mb_id']:$member['mb_id']?>" readonly="readonly" class="readonly"></div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="title count3"><label for="f5">작성자(email)</label></div>
                <div class="inp_ele count6">
                    <div class="input">
                    	<input type="text" name="qa_email" value="<?php echo get_text($write['qa_email']); ?>" id="qa_email" <?php echo $req_email; ?> class="<?php echo $req_email.' '; ?>frm_input full_input email" size="50" maxlength="100" placeholder="이메일">
                    </div>
                    <input type="checkbox" name="qa_email_recv" id="qa_email_recv" value="1" <?php if($write['qa_email_recv']) echo 'checked="checked"'; ?>>
                	<label for="qa_email_recv" class="frm_info">답변받기</label>
                </div>
            </div>
            <?php if ($is_hp) { ?>
            <li class="bo_w_hp">
                <label for="qa_hp" class="sound_only">휴대전화</label>
                <input type="text" name="qa_hp" value="<?php echo get_text($write['qa_hp']); ?>" id="qa_hp" <?php echo $req_hp; ?> class="<?php echo $req_hp.' '; ?>frm_input full_input" size="30" placeholder="휴대전화">
                <?php if($qaconfig['qa_use_sms']) { ?>
                <input type="checkbox" name="qa_sms_recv" id="qa_sms_recv" value="1" <?php if($write['qa_sms_recv']) echo 'checked="checked"'; ?>> <label for="qa_sms_recv" class="frm_info">답변등록 SMS알림 수신</label>
                <?php } ?>
            </li>
            <?php } ?>
        </div>

        <div class="grid">
            <div class="inp_wrap">
                <label for="f4" class="blind">제목</label>
                <div class="inp_ele">
                    <div class="input"><input type="text" name="qa_subject" value="<?php echo get_text($write['qa_subject']); ?>" id="qa_subject" required class="frm_input full_input required" size="50" maxlength="255" placeholder="제목"></div>
                </div>
            </div>
            <div class="inp_wrap">
                <label for="f6" class="blind">내용</label>
                <div class="inp_ele">
                    <div class="input" <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                    </div>
                </div>
            </div>

            <?php if ($option) { ?>
            <div class="inp_wrap">
                <?php echo $option; ?>
            </div>
            <?php } ?>

            <!--
            <div class="write-bottom">
                <div class="row">
                    <span>파일첨부#1</span>
                    <input type="file" name="bf_file[1]" id="bf_file_1" title="파일첨부 1 :  용량 <?php echo $upload_max_filesize; ?> 이하만 업로드 가능" class="btn-round">
                    <?php if($w == 'u' && $write['qa_file1']) { ?>
                    <input type="checkbox" id="bf_file_del1" name="bf_file_del[1]" value="1"> <label for="bf_file_del1"><?php echo $write['qa_source1']; ?> 파일 삭제</label>
                    <?php } ?>
                </div>
                <div class="row">
                    <span>파일첨부#2</span>
                    <input type="file" name="bf_file[2]" id="bf_file_2" title="파일첨부 2 :  용량 <?php echo $upload_max_filesize; ?> 이하만 업로드 가능" class="btn-round">
                    <?php if($w == 'u' && $write['qa_file2']) { ?>
                    <input type="checkbox" id="bf_file_del2" name="bf_file_del[2]" value="1"> <label for="bf_file_del2"><?php echo $write['qa_source2']; ?> 파일 삭제</label>
                    <?php } ?>
                </div>
                <ul class="write-info">
                    <li>- 리스트 화면에서 PC/Mobile 동일 썸네일 이미지로 사용됩니다.</li>
                    <li>- 권장 사이즈 300px * 300px 이상 (정사각형)</li>
                </ul>
            </div>
            <p class="ico_import red point_red">최대 사진 NN매, 동영상 NNmb 이하의 파일만 첨부 가능합니다.</p>
             -->

            <div class="btn_group two">
                <a href="<?php echo $list_href; ?>" class="btn big border"><span>취소</span></a>
                <button type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn big green"><span>등록</span></button>
            </div>
		</div>
    	</form>
        <!-- 컨텐츠 끝 -->
    </div>
</div>
<!-- //container -->

<div id="popup"></div>

<script>

	$('#qa_email_recv').click(function(){

		$('#qa_email_recv').attr('checked',$(this).is(":checked"));
		//alert($(this).is(":checked"));
	});

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



    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "2";
            else
                obj.value = "1";
        }
        else
            obj.value = "";
    }

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

        <?php if ($is_hp) { ?>
        var hp = f.qa_hp.value.replace(/[0-9\-]/g, "");
        if(hp.length > 0) {
            alert("휴대전화번호는 숫자, - 으로만 입력해 주십시오.");
            return false;
        }
        <?php } ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
</script>
<!-- } 게시물 작성/수정 끝 -->
