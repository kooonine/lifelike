<?php
$sub_menu = "800500";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super')
	alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '서랍 메뉴관리';
include_once ('../admin.head.php');

$token = get_admin_token();
?>

<div class="row">
<?php

$sql = " select * from lt_design_side where side_id = '1' ";
$view = sql_fetch($sql);

$side_id = $view['side_id'];
$main_type2 = $view['main_type2'];
$main_type2_maxcount = 2;
$main_view_data = json_decode(str_replace('\\','',$view['main_view_data']), true);

?>
		<div class="x_panel">
			<form name="frm" id="frm" method="post" onsubmit="return frm_submit(this);" enctype="multipart/form-data" >
			<input type="hidden" name="token" value="<?php echo $token ?>">
			<input type="hidden" name="side_id" value="<?php echo $side_id ?>">
			<input type="hidden" name="main_type1" value="<?php echo $view['main_type1'] ?>">

			<div class="x_title">
				<h4><span class="fa fa-check-square"></span> 서랍 메뉴관리<small></small></h4>
				<div class="clearfix"></div>
			</div>

			<table class="table table-bordered">
			<tbody>
			<tr>
				<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">노출채널 결정 여부</th>
				<td>
					<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="radio">
					<label id="menu_use1"><input type="radio" name="mobile_onoff" value="Y" <?php echo get_checked($view['mobile_onoff'], 'Y') ?> > 사용(모바일 서랍메뉴)</label>&nbsp;&nbsp;&nbsp;
					<label id="menu_use0"><input type="radio" name="mobile_onoff" value="N" <?php echo get_checked($view['mobile_onoff'], 'N') ?>> 미사용</label>&nbsp;&nbsp;&nbsp;
					</div></div>
				</td>
			</tr>

			<tr>
				<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">수량설정</th>
				<td>
					<div class="col-md-10 col-lg-10 col-sm-10">
					<div class="radio">
						<?php for ($i = 1; $i <= $main_type2_maxcount; $i++) {
							echo '<label id="main_type2_'.$i.'"><input type="radio" name="main_type2" value="'.$i.'" '.get_checked($main_type2, $i).' >'.$i.'개</label>&nbsp;&nbsp;&nbsp;';
						}?>
					</div>
					</div>
					<div class="text-right col-md-2 col-lg-2 col-sm-2">
						<input type="button" class="btn btn-secondary" id="btnModifyMain_type" value="수정"></input>
					</div>
				</td>
			</tr>

			</tbody>
			</table>

			<?php

			for ($i = 1; $i <= $main_type2_maxcount; $i++) {
			?>
			<table class="table table-bordered <?php if($main_type2 < $i) echo 'hidden'; ?>" id="tblImage<?php echo $i ?>">
			<thead>
			<tr>
				<th scope="col" class="text-center active" colspan="2">이미지 <?php echo $i ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">전시순서</th>
				<td>
				<select name="imgOrder[]" id="imgOrder<?php echo $i ?>" class="form-control">
					<?php for ($j = 1; $j <= $main_type2_maxcount; $j++) {
						echo '<option value="'.$j.'" '.get_selected($i, $j).'>순서 '.$j.'</option>';
					}?>
				</select>
				</td>
			</tr>

			<tr>
				<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">이미지 등록</th>
				<td>
					<div class="col-md-3 col-lg-3 col-sm-3">
					<?php
					$img_data = $main_view_data['imgFile'][$i-1];

					$img_file = G5_DATA_PATH.'/sidemenu/'.$side_id.'/'.$img_data['imgFile'];
					if ($img_data['imgFile'] && file_exists($img_file)) {
					   $img_url = G5_DATA_URL.'/sidemenu/'.$side_id.'/'.$img_data['imgFile'];
					   echo '<img src="'.$img_url.'" class="img-thumbnail" id="imgimgFile'.$i.'" style="width: 100%; height: 30%;">';
				   } else {
					   echo '<img src="../img/theme_img.jpg" class="img-thumbnail" id="imgimgFile'.$i.'" style="width: 100%; height: 30%;">';
				   }
					?>
					</div>

					<div class="col-md-6 col-lg-6 col-sm-6">
						<div class="input-group">
							<span class="">
								<div class="btn btn-info">
								<span><?php if($img_data) echo '이미지 수정'; else echo '이미지 등록'; ?></span>
									<input type="file" id="imgFile<?php echo $i ?>" name="imgFile[]" class="hiddenFile" delBtnID="btnDelimgFile<?php echo $i ?>" imgID="imgimgFile<?php echo $i ?>" style="width:100px" accept=".jpg, .png">
								</div>
							</span>
							<button class="btn btn-danger <?php if(!$img_data['imgFile']) echo 'hidden'; ?>" type="button" id="btnDelimgFile<?php echo $i ?>" fileBtnID="imgFile<?php echo $i ?>" >삭제</button>

							<input type="hidden" id="orgimgFile<?php echo $i ?>" name="orgimgFile[]" value="<?php echo $img_data['imgFile']; ?>" >

						</div>
					</div>

					<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="clearfix"></div><br />
						<span class="red">* 업로드 이미지 사이즈 (<?=$view['main_width']?>px * <?=$view['main_height']?>px) <br />
						* 최대 15MB / 확장자 jpg, png만 가능</span>
					</div>

				</td>
			</tr>

			<tr>
				<th scope="col" class="text-center success" width="30%" style="vertical-align: middle;">링크연결</th>
				<td>
					<div class="radio col-md-12 col-sm-12 col-xs-12">
						<label><input type="radio" class="imgLinkYN" name="imgLinkYN<?php echo $i ?>" data="<?php echo $i ?>" value="N" <?php echo get_checked($img_data['imgLinkYN'], 'N') ?> <?php echo get_checked($img_data['imgLinkYN'], null) ?>>링크없음</label>&nbsp;&nbsp;
						<label><input type="radio" class="imgLinkYN" name="imgLinkYN<?php echo $i ?>" data="<?php echo $i ?>" value="Y" <?php echo get_checked($img_data['imgLinkYN'], 'Y') ?>>URL</label>
					</div>
					<input type="text" class="form-control <?php echo get_hidden($img_data['imgLinkYN'], 'N') ?> <?php echo get_hidden($img_data['imgLinkYN'], null) ?>" id="linkURL<?php echo $i ?>" name="linkURL[]" value="<?php echo $img_data['linkURL']; ?>">
				</td>
			</tr>

			</tbody>
			</table>

			<div class="clearfix"></div>
			<?php } ?>

			<div class="x_content">
				  <div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12 text-right">
						<a href="./design_sidemenu_preview.php" id="preview"><input type="button" class="btn btn-secondary" value="미리보기"></input></a>
						<input type="submit" class="btn btn-success" value="적용하기" id="btnSubmit"></input>
					</div>
				  </div>
			</div>
			</form>
		</div>
	</div>

</div>


<script>
$(function(){
	$(document).ready(function($) {


		$("#preview").on("click", function() {
			var url = this.href;
			window.open(url, "preview", "left=100,top=100,width=800,height=600,scrollbars=1");
			return false;
		});

		$("#btnModifyMain_type").click(function(event) {
			//alert($("input:radio[name=main_type2]:checked").val());
			var main_type2 = $("input:radio[name=main_type2]:checked").val();
			for(i=1;i<=5;i++){
				if(i <= main_type2) {
					$("#tblImage"+i).removeClass('hidden');
				} else {
					$("#tblImage"+i).removeClass('hidden').addClass('hidden');
				}
			}
		});


		$("input.imgTextYN").change(function(event) {
			var imgTextYN = $(this).val();
			var i =$(this).attr("data");


			if(imgTextYN == "Y") {
				$("#trMainText"+i).removeClass('hidden');
				$("#trSubText"+i).removeClass('hidden');
			} else {
				$("#trMainText"+i).removeClass('hidden').addClass('hidden');
				$("#trSubText"+i).removeClass('hidden').addClass('hidden');
				$("#txtMainText"+i).val("");
				$("#txtSubText"+i).val("");

			}
		});

		$("input.imgLinkYN").change(function(event) {
			var imgLinkYN = $(this).val();
			var i =$(this).attr("data");

			if(imgLinkYN == "Y") {
				$("#linkURL"+i).removeClass('hidden');
			} else {
				$("#linkURL"+i).removeClass('hidden').addClass('hidden');
				$("#linkURL"+i).val("");
			}
		});

		$("#main_type1").change(function(event) {
			//alert($(this).val());
			location.href = './design_component_web.php?main_id=<?php echo $view['main_id'] ?>&chType1=' + $(this).val();
		});


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

		$.setImgFileUpload('imgFile1');
		$.setImgFileUpload('imgFile2');

	});
});

function frm_submit(f)
{

	if(confirm("적용하시겠습니까?"))
	{
		f.action = "./design_sidemenu_update.php";
		return true;
	}
	return false;
}

</script>

<?php
include_once ('../admin.tail.php');
?>
