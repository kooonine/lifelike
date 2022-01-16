<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($board['bo_use_userform'] == "1"){
	//신청하기는 신청하기 양식으로 연결.
	include_once ($board_skin_path.'/write2.skin.php');

} else {
	?>
	<!-- container -->
	<div id="container" class="no_title">
		<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>

		<div class="content community type3 sub">
			<!-- 컨텐츠 시작 -->
			<form name="fviewcomment" id="fviewcomment" action="<?=$action_url  ?>" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off" class="bo_vc_w" enctype='multipart/form-data'>
				<input type="hidden" name="w" value="<?=$w ?>" id="w">
				<input type="hidden" name="bo_table" value="<?=$bo_table ?>">
				<input type="hidden" name="wr_id" value="<?=$wr_id ?>">
				<input type="hidden" name="wr_6" id="wr_6" value="<?=$wr_6 ?>">


				<div class="grid write-grid" id="gridContent">
					<div class="type-wrap">
						<div class="type-list">
							<ul>
								<? 
								$bo_select = '';
								for ($i=1; $i<10; $i++) {
									if($board['bo_'.$i.'_subj'] != ''){
										?>
										<li><button type="button"  class="bo_option" name="<?='btn_bo_subj'?>" targetID="bo_option_<?=$i?>" SEQ="<?=$i-1;?>"><?=$board['bo_'.$i.'_subj'];?></button></li>
										<?

										if($board['bo_'.$i] != '') {
											$bo_option_list = explode(',', $board['bo_'.$i]);
											$option_count = count($bo_option_list);
											$bo_select = '<select id="bo_option_'.$i.'" name="sel_bo_option[]" hidden >'.PHP_EOL;
											$bo_select .= '<option value="">선택</option>'.PHP_EOL;
											for($j=0; $j<$option_count; $j++) {
												$bo_select .= '<option value="'.$bo_option_list[$j].'">'.$bo_option_list[$j].'</option>'.PHP_EOL;
											}
											$bo_select .= '</select>'.PHP_EOL;

											echo $bo_select.PHP_EOL;
										}

									}
								}?>
							</ul>
						</div>

						<? if($board['bo_use_grade'] == "1"){ ?>
							<div class="star-point" >
								<p class="text">별점을 선택 해 주세요.</p>
								<div class="star">
									<input type="hidden" name="wr_8" id="wr_8" value="<?=$wr_8?>">
									<ul class="select_star">
										<li class="<?=($is_score>=2)?'on':'';?>"><button type="button" onclick="$('#wr_8').val('2');"><span class="blind">1점</span></button></li>
										<li class="<?=($is_score>=4)?'on':'';?>"><button type="button" onclick="$('#wr_8').val('4');"><span class="blind">2점</span></button></li>
										<li class="<?=($is_score>=6)?'on':'';?>"><button type="button" onclick="$('#wr_8').val('6');"><span class="blind">3점</span></button></li>
										<li class="<?=($is_score>=8)?'on':'';?>"><button type="button" onclick="$('#wr_8').val('8');"><span class="blind">4점</span></button></li>
										<li class="<?=($is_score>=10)?'on':'';?>"><button type="button" onclick="$('#wr_8').val('10');"><span class="blind">5점</span></button></li>
									</ul>
								</div>
							</div>
						<? } ?>

						<!-- The Modal -->
						<div id="optionModal" class="modal" style="display: none;">
							<!-- Modal content -->
							<div class="content sub">
								<div style="float: right;">
									<a href="#" class="close"><span class="blind">닫기</span></a>
								</div>
								<div class="grid cont" style="border-top-width: 0px;">
									<div class="title_bar" style="overflow:visible;">
										<h3 class="g_title_01" id="optionModalTitle">선택한 :<span class="none"></span></h3>
									</div>
									<div class="list">
										<ul class="type1 pad"  id="optionModalList">
										</ul>
									</div>

								</div>
							</div>
						</div>
						<!--End Modal-->
						<!-- 컨텐츠 분류 : 태그 -->
						<div class="type-tag">
							<ul id="bo_selected_option">

							</ul>
						</div>
					</div>
					<div class="inp_wrap">
						<label class="blind">제목</label>
						<div class="inp_ele">
							<div class="input"><input type="text" placeholder="제목" id="wr_subject" name = "wr_subject" value="<?=$subject?>" required="required">
							</div>
						</div>
					</div>
					<div class="inp_wrap">
						<label class="blind">내용</label>
						<div class="inp_ele">
							<?=$editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
						</div>
					</div>
					<div class="inp_wrap">
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
					<div class="file_list text">
						<ul id="file_list">
							<? if($w == 'u' && $file[0]['file']) { ?>
								<li id="file_data">
									<span><?=$file[0]['source'].'('.$file[0]['size'].')';  ?></span>
				<!-- <button type="button" class="btn_delete" id="file_delete" target_ID='bf_file_del0'>
				<span class="blind">삭제</span>
			</button> -->
		</li>

	<? } ?>
</ul>
<? if($w == 'u' && $file[0]['file']) { ?>
	<input type="checkbox" id="bf_file_del0" name="bf_file_del[0]" value="1" hidden>
<? }?>
</div>
<div class="write-bottom">
	<div class="row">
		<span>태그달기</span>
	</div>
	<input class="input-tag" type="text" placeholder="태그(#)는 쉼표로 구분하며, 10개까지 입력할 수 있습니다." name="wr_2" id="wr_2" value="<?= $wr_2?>">
</div>
<div class="write-bottom">
	<div class="row">
		<span>라이프라이크 구매 제품을 추가 해 주세요.</span>
		<button type="button" class="btn-round" id="btn_item_add">제품추가</button>
	</div>
	<input  type="hidden" name="wr_5" id="wr_5" value="<?=$wr_5?>">
	<div class="pdt_rolling pdt1" id="itemView">
	</div>
</div>


<div class="btn_group two">
	<button type="button" class="btn big border" onclick="history.back();"><span>취소</span></button>
	<button type="submit" class="btn big green" ><span><? if($w != 'u'){?>등록<? } else { echo '수정';}?></span></button>
</div>
</div>
<!-- 컨텐츠 종료 -->
</form>
</div>

<div id="popup"></div>
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
		extName = fileObj.substring(pathMiddle+1, pathEnd).toLowerCase();
		allFilename = fileName+"."+extName;
		if(extName != 'jpg' && extName != 'gif' && extName != 'png' && extName != 'pdf'){
			alert(" jpg, gif, png, pdf 파일만 첨부 가능합니다.");
			return false;
		}
		var html = '';
		html += '<li id="file_data">';
		html += '<span>'+allFilename+'</span>';
		html += '<button type="button" class="btn_delete" id="file_delete" >';
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

	$(document).on("click", ".btnSearch", function() {
		var wr_5 = $("#wr_5").val();
		var ca_id = $("#ca_id").val();
		if(ca_id == ""){
			alert("카테고리를 선택해주세요.");
			return false;
		}
		$.post(
			"<?=G5_SHOP_URL?>/ajax.itemlist.php",
			{ not_it_id_list : wr_5, ca_id:ca_id },
			function(data) {
				$("#popup").empty().html(data);
			}
			);
	});

	$(document).on("click", ".btnAddItem", function() {
		var wr_5 = sep = "";
		var $chks = $("input[name='chk[]']:checked");
		if($chks.size() > 0){
			$chks.each(function() {
				var chkid = $(this).val();

				var it_id = $("input[name='it_id["+chkid+"]']").val();
				wr_5 += sep + it_id;
				sep = ",";
			});
		}

		$("#wr_5").val(wr_5);
		$.bo_selected_item_view();
		$('#btn_item_add').trigger("click");
	});

	$(document).on("click", ".btnDelItem", function() {
		var wr_5 = sep = "";
		var $chks = $("input[name='chk2[]']:not(:checked)");
		if($chks.size() > 0){
			$chks.each(function() {
				var chkid = $(this).val();

				var it_id = $("input[name='it_id2["+chkid+"]']").val();
				wr_5 += sep + it_id;
				sep = ",";
			});
		}
		$("#wr_5").val(wr_5);
		$.bo_selected_item_view();
		$('#btn_item_add').trigger("click");
	});
	$('#btn_item_add').click(function(){

		var wr_5 = $("#wr_5").val();
		var ca_id = $("#ca_id").val();
		$.post(
			"<?=G5_SHOP_URL?>/ajax.itemlist.php",
			{ not_it_id_list : wr_5, ca_id:ca_id },
			function(data) {
				$("#popup").empty().html(data);
			}
			);
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

	$.bo_selected_option_create = function(){
		$('#bo_selected_option').html('');
		var wr_6 = $('#wr_6').val();
		var html = '';

		if(wr_6 != ''){
			var wr_6Split = wr_6.split(',');
			for ( var i in wr_6Split ) {
				if(wr_6Split[i] != '')
					html += '<li><span>'+wr_6Split[i]+'</span><a onclick="bo_selected_option_delete(\''+wr_6Split[i]+'\');" >닫기</a></li>';
			}
			$('#bo_selected_option').html(html);
		}
	};

	$.bo_selected_item_view = function(){
		var wr_5 = $("#wr_5").val();
		$("#itemView").empty();
		$.post(
				"<?=G5_SHOP_URL?>/ajax.itemview.php",
				{ it_id_lists : wr_5},
				function(data) {
					//alert(data);
					$("#itemView").empty().html(data);
				}
		);
	};

	$(".close").click(function() {
		$(".modal").css("display","none");
	});


	$.bo_selected_option_create();
	$.bo_selected_item_view();
});
function fviewcomment_submit(f)
{
	<?php if($bo_select != '') { ?>
	var wr_6 = $('#wr_6').val();
	if(wr_6 == '' || wr_6 == ',,,,,,,,'){
		alert("분류를 선택해주세요.");
		return false;
	}
	<?php } ?>
	
	<? if($w == '') { ?>
		if($('#bf_file').val() == ""){
			alert("썸네일이미지가 등록되지 않았습니다");
			return false;
		}
	<? }?>

	<?=$editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

	return true;
}

</script>


<? } ?>
