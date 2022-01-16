<?php
//$sub_menu = '100310';
$sub_menu = '10';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$nw_id = preg_replace('/[^0-9]/', '', $nw_id);

$html_title = "팝업";

// 팝업레이어 테이블에 쇼핑몰, 커뮤니티 인지 구분하는 여부 필드 추가
$sql = " ALTER TABLE `{$g5['new_win_table']}` ADD `nw_division` VARCHAR(10) NOT NULL DEFAULT 'both' ";
sql_query($sql, false);

if ($w == "u")
{
    $html_title .= " 수정";
    $sql = " select * from {$g5['new_win_table']} where nw_id = '$nw_id' ";
    $nw = sql_fetch($sql);
    if (!$nw['nw_id']) alert("등록된 자료가 없습니다.");
}
else
{
    $html_title .= " 입력";
    $nw['nw_device'] = 'both';
    $nw['nw_status'] = 'W';
    $nw['nw_disable_hours'] = 24;
    $nw['nw_left']   = 10;
    $nw['nw_top']    = 10;
    $nw['nw_width']  = 450;
    $nw['nw_height'] = 500;
    $nw['nw_content_html'] = 2;
}

$g5['title'] = "팝업 관리";
include_once (G5_ADMIN_PATH.'/admin.head.php');
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">


<form name="frmnewwin" action="./newwinformupdate.php" onsubmit="return frmnewwin_check(this);" method="post" enctype="multipart/form-data" >
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="nw_id" value="<?php echo $nw_id; ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="nw_division" value="<?php echo $nw['nw_division']; ?>">


<div class="x_content">


<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="nw_division">진행여부</label></th>
        <td>
            <div class="radio" >
            <label><input type="radio" name="nw_status" value="W" <?php echo get_checked($nw['nw_status'], 'W') ?>>대기</label>&nbsp;&nbsp;
			<label><input type="radio" name="nw_status" value="Y" <?php echo get_checked($nw['nw_status'], 'Y') ?>>진행중</label>&nbsp;&nbsp;
			<label><input type="radio" name="nw_status" value="N" <?php echo get_checked($nw['nw_status'], 'N') ?>>종료</label>
            </div>

        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_device">접속기기</label></th>
        <td>
            <?php echo help("팝업레이어가 표시될 접속기기를 설정합니다."); ?>
            <select name="nw_device" id="nw_device">
                <option value="both"<?php echo get_selected($nw['nw_device'], 'both', true); ?>>PC와 모바일</option>
                <option value="pc"<?php echo get_selected($nw['nw_device'], 'pc'); ?>>PC</option>
                <option value="mobile"<?php echo get_selected($nw['nw_device'], 'mobile'); ?>>모바일</option>
            </select>
        </td>
    </tr>
    <tr class="hidden">
        <th scope="row"><label for="nw_disable_hours">시간<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <?php echo help("고객이 다시 보지 않음을 선택할 시 몇 시간동안 팝업레이어를 보여주지 않을지 설정합니다."); ?>
            <input type="text" name="nw_disable_hours" value="<?php echo $nw['nw_disable_hours']; ?>" id="nw_disable_hours" required class="frm_input required" size="5"> 시간
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_begin_time">시작일시<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_begin_time" value="<?php echo $nw['nw_begin_time']; ?>" id="nw_begin_time" required class="frm_input required" size="21" maxlength="19">
            <input type="checkbox" name="nw_begin_chk" value="<?php echo date("Y-m-d 00:00:00", G5_SERVER_TIME); ?>" id="nw_begin_chk" onclick="if (this.checked == true) this.form.nw_begin_time.value=this.form.nw_begin_chk.value; else this.form.nw_begin_time.value = this.form.nw_begin_time.defaultValue;">
            <label for="nw_begin_chk">시작일시를 오늘로</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_end_time">종료일시<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_end_time" value="<?php echo $nw['nw_end_time']; ?>" id="nw_end_time" required class="frm_input required" size="21" maxlength="19">
            <input type="checkbox" name="nw_end_chk" value="<?php echo date("Y-m-d 23:59:59", G5_SERVER_TIME+(60*60*24*7)); ?>" id="nw_end_chk" onclick="if (this.checked == true) this.form.nw_end_time.value=this.form.nw_end_chk.value; else this.form.nw_end_time.value = this.form.nw_end_time.defaultValue;">
            <label for="nw_end_chk">종료일시를 오늘로부터 7일 후로</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_left">팝업 좌측 위치<strong class="sound_only"> 필수</strong></label></th>
        <td>
           <input type="text" name="nw_left" value="<?php echo $nw['nw_left']; ?>" id="nw_left" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_top">팝업 상단 위치<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_top" value="<?php echo $nw['nw_top']; ?>" id="nw_top" required class="frm_input required"  size="5"> px
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_width">팝업 넓이<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_width" value="<?php echo $nw['nw_width'] ?>" id="nw_width" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_height">팝업 높이<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_height" value="<?php echo $nw['nw_height'] ?>" id="nw_height" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_subject">팝업 제목<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_subject" value="<?php echo get_sanitize_input($nw['nw_subject']); ?>" id="nw_subject" required class="frm_input required" size="80">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_subject">팝업 이미지등록<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <div class="col-md-3 col-lg-3 col-sm-3">
            <?php

            $img_file = G5_DATA_PATH.'/popup/'.$nw['nw_imgfile'];
            if ($nw['nw_imgfile'] && file_exists($img_file)) {
    	       $img_url = G5_DATA_URL.'/popup/'.$nw['nw_imgfile'];
    	       echo '<img src="'.$img_url.'" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
    	   } else {
    	       echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile" style="width: 100%; height: 30%;">';
    	   }
        	?>
            </div>

            <div class="col-md-6 col-lg-6 col-sm-6">
                <div class="input-group">
    		        <span class="">
    		        	<div class="btn btn-info">
		        			<span><?php if($w == "u") echo '이미지 수정'; else echo '이미지 등록'; ?></span>
    		        		<input type="file" id="imgFile" name="nw_imgfile" class="hiddenFile" delBtnID="btnDelimgFile" imgID="imgimgFile" style="width:100px" accept=".jpg, .png">
    		        	</div>
					</span>
            		<button class="btn btn-danger <?php if(!$nw['nw_imgfile']) echo 'hidden'; ?>" type="button" id="btnDelimgFile" fileBtnID="imgFile" >삭제</button>
					<input type="hidden" id="orgimgFile" name="orgnw_imgfile" value="<?php echo $nw['nw_imgfile']; ?>" >

    		    </div>

    		    <div class="col-md-12 col-lg-12 col-sm-12">
                	<span class="red">
                        * 최대 15MB / 확장자 jpg, png만 가능 <br>
                        * 팝업 이미지 등록 시, 팝업 넓이와 높이는 이미지 비율에 맞추어 설정
                    </span>
                </div>
    		</div>

        </td>
    </tr>
    <tr>
        <th scope="row" >링크</th>
        <td>
			<input type="text" id="nw_link" name="nw_link" value="<?php echo $nw['nw_link']; ?>" class="frm_input" size="80">
		</td>
    </tr>
    </tbody>
    </table>
</div>

</div>

<div class="x_content">
  <div class="form-group">
	<div class="col-md-12 col-sm-12 col-xs-12 text-right">
		<a href="#" class="btn btn_02">미리보기</a>

		<a href="./newwinformupdate.php?w=d&amp;nw_id=<?php echo $nw_id; ?>" onclick="return delete_confirm(this);" class="btn btn-danger"><span class="sound_only"><?php echo $nw['nw_subject']; ?> </span>삭제</a>
    	<a href="./design_popup.php" class=" btn btn_02">목록</a>
    	<input type="submit" value="저장" class="btn btn-success" accesskey="s">
	</div>
  </div>
</div>

</form>

</div></div></div>

<script>
$(function(){
	$(document).ready(function($) {

		$.delBtnFileUpload = function(event) {
			var fileBt = $("#"+$(this).attr("fileBtnID"));

			var fileBtnID = fileBt.attr("id");
			var labalID = fileBt.attr("labalID");
			var delBtnID = fileBt.attr("delBtnID");
			var imgID = fileBt.attr("imgID");

			$("#"+fileBtnID).val("");
			$("#org"+fileBtnID).val("");
			if(labalID != "") $("#"+labalID).val("");
			if(imgID != "")
			{
				$("#"+imgID).attr("src", "../img/theme_img.jpg");
				//$("#"+imgID).removeClass('hidden').addClass('hidden');
			}

			$(this).removeClass('hidden').addClass('hidden');
		}

		$.imgFileUploadChange = function(event) {

			var fileBtnID = $(this).attr("id");
			var labalID = $(this).attr("labalID");
			var delBtnID = $(this).attr("delBtnID");
			var imgID = $(this).attr("imgID");

			var fileName = "";
			if(window.FileReader){
				fileName = $(this)[0].files[0].name;
			} else {
				fileName = $(this)[0].val().split('/').pop().split('\\').pop();
			}

			if(fileName != "" && imgID != "") {
				var reader = new FileReader();
				reader.onload = function (e) {
					$("#"+imgID).attr("src", e.target.result);
				}
				reader.readAsDataURL($(this)[0].files[0]);

				$("#"+imgID).removeClass('hidden');
			}

			//$("#btnDelMainImgFile").removeClass('d-none').addClass('d-none');
			$("#"+delBtnID).removeClass('hidden');
			if(labalID != "") $("#"+labalID).val(fileName);
		}

		$.setImgFileUpload = function(fileInputId) {

			$("#"+fileInputId).on('change', $.imgFileUploadChange);
			var delBtnID = $("#"+fileInputId).attr("delBtnID");
			$("#"+delBtnID).click($.delBtnFileUpload);
		}

		$.setImgFileUpload('imgFile');
	});
});

function frmnewwin_check(f)
{
    errmsg = "";
    errfld = "";


    check_field(f.nw_subject, "제목을 입력하세요.");

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    return true;
}
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
