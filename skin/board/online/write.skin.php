<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

    ?>
<!-- container -->
<div id="container">
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>온라인 집들이 작성</span></h1>
		<a href="#" class="btn_back"><span class="blind">뒤로가기</span></a>
	</div>
<div class="content community type3 sub">
	<!-- 컨텐츠 시작 -->
	<form name="fviewcomment" id="fviewcomment" action="<?php echo $action_url  ?>" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off" class="bo_vc_w" enctype='multipart/form-data'>
        <input type="hidden" name="w" value="<?php echo $w ?>" id="w">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
		<input type="hidden" name="wr_6" id="wr_6" value="<?php echo $wr_6 ?>">
	
	
	<div class="grid" id="gridContent">
		
		<div class="grid">
        	<?php
        	$wr_6s = explode(",", $wr_6);
        	for ($i=1; $i<10; $i++) {
        	    if($board['bo_'.$i.'_subj'] != ''){
        	?>
			<div class="inp_wrap">
				<div class="title count3"><label for="f1"><?php echo $board['bo_'.$i.'_subj'];?></label></div>
				<div class="inp_ele count6">
					<span class="sel_box">
					<?php 
                            if($board['bo_'.$i] != '') {
                                $bo_option_list = explode(',', $board['bo_'.$i]);
                                $option_count = count($bo_option_list);
                                $bo_select = '<select id="bo_option_'.$i.'" name="sel_bo_option[]" required>'.PHP_EOL;
                                $bo_select .= '<option value="">선택</option>'.PHP_EOL;
                                
                                for($j=0; $j<$option_count; $j++) {
                                    $selected = "";
                                    
                                    if(count($wr_6s) > 0 && $wr_6s[$i-1] && $bo_option_list[$j] == $wr_6s[$i-1]) $selected = "selected";
                                    $bo_select .= '<option value="'.$bo_option_list[$j].'" '.$selected.'>'.$bo_option_list[$j].'</option>'.PHP_EOL;
                                }
                                $bo_select .= '</select>'.PHP_EOL;
                                echo $bo_select.PHP_EOL;
                            }
                    ?>
					</span>
				</div>
			</div>
			<?php }
            } ?>
		</div>
		
        <div class="grid">
            <div class="inp_wrap">
                <label for="f4" class="blind">제목</label>
                <div class="inp_ele">
                    <div class="input"><input type="text" placeholder="제목" id="wr_subject" name = "wr_subject" value="<?php echo $subject?>" required="required">
                    </div>
                </div>
            </div>
            <div class="inp_wrap">
                <label for="f5" class="blind">내용</label>
                <div class="inp_ele">
                 <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                    <!-- <div class="input"><textarea rows="6" cols="20" id="wr_content" name = "wr_content" ><?php echo $content?></textarea></div> -->
                </div>
            </div>
            <div class="inp_wrap">
                <span class="byte">0/2000</span>
            </div>

            <div class="write-bottom">
                <div class="row">
                    <span>썸네일이미지</span>
                    <span class="btn_file" style="float: right;">
                        <button type="button" class="btn-round">파일첨부</button>
                        <input type="file" title="파일 업로드" onchange="getCmaFileInfo(this)" id="bf_file" name = "bf_file[]" >
                       
                    </span>
                    
                     
                </div>
                <ul class="write-info">
                    <li>- 리스트 화면에서  PC/Mobile 동일  썸네일 이미지로 사용됩니다.</li>
                    <li>- 권장 사이즈 300px * 300px 이상 (정사각형)</li>
                </ul>
            </div>
            <div class="file_list">
                <ul id="file_list">
                    <?php if($w == 'u' && $file[0]['file']) { ?>
                    <li>
            		<span  class="name"><?php echo $file[0]['source'].'('.$file[0]['size'].')';  ?></span>
            		<button type="button" class="btn_delete gray" id="file_delete" target_ID='bf_file_del0'>
            		<span class="blind">삭제</span>
            		</button>
            		</li>
                    <?php } ?>
                </ul>
                <?php if($w == 'u' && $file[0]['file']) { ?>
                <input type="checkbox" id="bf_file_del0" name="bf_file_del[0]" value="1" hidden> 
                <?php }?>
            </div>
            <div class="write-bottom">
                <div class="row">
                    <span>태그달기</span>
                </div>
                <input class="input-tag" type="text" placeholder="태그(#)는 쉼표로 구분하며, 10개까지 입력할 수 있습니다." name="wr_2" id="wr_2" value="<?php echo  $wr_2?>">
            </div>
		</div>
		<div class="grid gray_box border_box">
			<div class="info_box mt0">
				<span class="cmt">라이프라이크 구매 제품을 추가 하고싶으시면 제품 추가 버튼을 선택 해 주세요</span>
				<span class="btn_r">
					<button type="button" class="btn gray_line small">제품추가</button>
                	<input  type="hidden" name="wr_5" id="wr_5">
				</span>
			</div>
		</div>
    	
    	<div class="grid">
            <div class="btn_group two">
                <button type="button" class="btn big border" onclick="history.back();"><span>취소</span></button>
                <button type="submit" class="btn big green" ><span><?php if($w != 'u'){?>등록<?php } else { echo '수정';}?></span></button>
            </div>
    	</div>
	<!-- 컨텐츠 종료 -->
	</form>	
</div>
<script>
function fileCheck( file )
{
        // 사이즈체크
        var maxSize  = 2 * 1024 * 1024    
        var fileSize = 0;

	// 브라우저 확인
	var browser=navigator.appName;
	
	// 익스플로러일 경우
	if (browser=="Microsoft Internet Explorer")
	{
		var oas = new ActiveXObject("Scripting.FileSystemObject");
		fileSize = oas.getFile( file.value ).size;
	}
	// 익스플로러가 아닐경우
	else
	{
		fileSize = file.files[0].size;
	}



    if(fileSize > maxSize)
    {
        alert("첨부파일 사이즈는 2MB 이내로 등록 가능합니다.    ");
        return false;
    }
	return true;

}
function getCmaFileInfo(obj) {
    var fileObj, pathHeader , pathMiddle, pathEnd, allFilename, fileName, extName;
    if(obj == "[object HTMLInputElement]") {
        fileObj = obj.value
        if(!fileCheck(obj)){
			return false;
        }
    } else {
        fileObj = document.getElementById(obj).value;
        if(!fileCheck(document.getElementById(obj))){
			return false;
        }
    }
    
    if (fileObj != "") {
        pathHeader = fileObj.lastIndexOf("\\");
        pathMiddle = fileObj.lastIndexOf(".");
        pathEnd = fileObj.length;
        fileName = fileObj.substring(pathHeader+1, pathMiddle);
        extName = fileObj.substring(pathMiddle+1, pathEnd);
        allFilename = fileName+"."+extName;
		if(extName != 'jpg' && extName != 'gif' && extName != 'png' && extName != 'pdf'){
			alert(" jpg, gif, png, pdf 파일만 첨부 가능합니다.");
			return false;
		}
		var html = '';
		html += '<li>';
		html += '<span class="name">'+allFilename+'</span>';
		html += '<button type="button" class="btn_delete gray" id="file_delete" >';
		html += '<span class="blind">삭제</span>';
		html += '</button>';
		html += '</li>';
		$('#file_list').html(html);

    } else {
            alert("파일을 선택해주세요");
            return false;
    }
    // getCmaFileView(this,'name');
    // getCmaFileView('upFile','all');
 }

function bo_option_select(optionval,seq){
		
		var wr_6 = $('#wr_6').val();
		var new_wr_6 = '';
		var wr_6Split = '';
	if(wr_6 == ''){
		wr_6 = ',,,,,,,,';
	}
	wr_6Split = wr_6.split(',');
  	for ( var i in wr_6Split ) {
    	 if(i == seq){
       	 	wr_6Split[i] = optionval;
    	 }
  	}
	for ( var i in wr_6Split ) {
    	 if(i == 0){
    		 new_wr_6 += wr_6Split[i];
    	 }else {
    		 new_wr_6 += ','+wr_6Split[i];
       	 }
  	}
		$('#wr_6').val(new_wr_6);
		
		$(".modal").css("display","none");
		
		$.bo_selected_option_create();
	};


	function bo_selected_option_delete(seq){
		var wr_6 = $('#wr_6').val();
		var new_wr_6 = '';
	if(wr_6 != ''){
		var wr_6Split = wr_6.split(',');
		
      	for ( var i in wr_6Split ) {
        	 if(i == seq) {
        		 wr_6Split[i] = '';
        	 }
      	}
	}
	for ( var i in wr_6Split ) {
   	 if(i == 0){
   		 new_wr_6 += wr_6Split[i];
   	 }else {
   		 new_wr_6 += ','+wr_6Split[i];
       	 }
 	}
		$('#wr_6').val(new_wr_6);
		$.bo_selected_option_create();
	};

$(document).ready(function(){
	$(document).on("click", "#file_delete", function() {
    	var result = confirm('첨부 파일을 삭제 하시겠습니까?');

    	if(result){
			$("#bf_file").replaceWith($("#bf_file").val('').clone(true));
			var target_id = $(this).attr('target_ID');
			$("#"+target_id).prop('checked',true);
			$('#file_list').html('');
    	}
	});
	$(".bo_option").click(function() {
		var target_id = $(this).attr("targetID");
		var seq = $(this).attr("SEQ");
		var optionName = $(this).text();
		$('#optionModalTitle').html(optionName);

		var optionList = "";
		var $option = $('#'+target_id+' option');

		$option.each(function() {
			bo_value = $(this).val();
			

	   	if(bo_value != ""){
	   		optionList += "<li>";
	   		optionList += "<a onclick='bo_option_select(\""+bo_value+"\", \""+seq+"\");'>";
	   		optionList += "<span class=\"bold\">"+bo_value+"</span>";
				optionList += "</a></li>";
	       	
	   	}
	   });
		
		$('#optionModalList').html(optionList);

		$("#optionModal").css("display","block");
	});

});
function fviewcomment_submit(f)
{
	<?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

	var wr_6 = "";
	for (var i = 1; i <= 10; i++) {
		if(i != 1) wr_6 += "," 
		if($("#bo_option_"+i).val() != undefined){
			wr_6 += $("#bo_option_"+i).val(); 			
		}
	}
	$("#wr_6").val(wr_6);

    return true;
}

</script>

