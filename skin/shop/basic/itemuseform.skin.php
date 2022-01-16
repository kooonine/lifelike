<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_PATH.'/head.php');
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>

<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>리뷰 작성</span></h1>
	</div>
	<!-- // lnb -->
	<div class="content mypage sub">
		<form name="fitemuse" method="post" action="./itemuseformupdate.php" onsubmit="return fitemuse_submit(this);" autocomplete="off" enctype='multipart/form-data'>
			<input type="hidden" name="w" value="<?=$w; ?>">
			<input type="hidden" name="it_id" value="<?=$it_id; ?>">
			<input type="hidden" name="is_id" value="<?=$is_id; ?>">
			<input type="hidden" name="ct_id" value="<?=$ct_id; ?>">
			<input type="hidden" name="is_mobile_shop" value="1">
			<input type="hidden" name="is_type" value="<?=($mode=='txt')?"0":"1"; ?>">
			<input type="hidden" name="is_subject" value="<?=stripslashes($ct['it_name']); ?> / 옵션 : <?=get_text($ct['ct_option']); ?>" id="is_subject" >
			<!-- 컨텐츠 시작 -->
			<div class="grid">
				<div class="order_cont">
					<div class="head">
						<span class="title">리뷰 제품 정보</span>
					</div>
					<div class="body">
						<div class="cont">
							<?
							$image_width = 80;
							$image_height = 80;
							$image = get_it_image($ct['it_id'], $image_width, $image_height);
							?>
							<div class="photo"><?=$image; ?></div>
							<div class="info">
								<strong><?=stripslashes($ct['it_name'])?></strong>
								<p>옵션 : <?=get_text($ct['ct_option']); ?></p>
								<p>주문일 : <?=substr($ct['ct_time'],0,10); ?></p>
							</div>
						</div>
					</div>
				</div>

				<div class="review_star select">
					<p class="title">별 점과 구매하신 분의<br>연령대를 선택해 주세요.</p>
					<div class="star big">
						<input type="hidden" name="is_score" id="is_score" value="<?=$is_score?>">
						<ul class="select_star">
							<li class="<?=($is_score>=1)?'on':'';?>"><button type="button" onclick="$('#is_score').val('1');"><span class="blind">1점</span></button></li>
							<li class="<?=($is_score>=2)?'on':'';?>"><button type="button" onclick="$('#is_score').val('2');"><span class="blind">2점</span></button></li>
							<li class="<?=($is_score>=3)?'on':'';?>"><button type="button" onclick="$('#is_score').val('3');"><span class="blind">3점</span></button></li>
							<li class="<?=($is_score>=4)?'on':'';?>"><button type="button" onclick="$('#is_score').val('4');"><span class="blind">4점</span></button></li>
							<li class="<?=($is_score>=5)?'on':'';?>"><button type="button" onclick="$('#is_score').val('5');"><span class="blind">5점</span></button></li>
						</ul>
					</div>
					<div class="select_group">
						<input type="hidden" name="is_age" id="is_age" value="<?=$is_age?>">
						<ul class="onoff">
							<li class="<?=($is_age==20)?'on':'';?>"><a href="#" onclick="$('#is_age').val('20');"><button type="button">20대</button></a></li>
							<li class="<?=($is_age==30)?'on':'';?>"><a href="#" onclick="$('#is_age').val('30');"><button type="button">30대</button></a></li>
							<li class="<?=($is_age==40)?'on':'';?>"><a href="#" onclick="$('#is_age').val('40');"><button type="button">40대</button></a></li>
							<li class="<?=($is_age==50)?'on':'';?>"><a href="#" onclick="$('#is_age').val('50');"><button type="button">50대 이상</button></a></li>
						</ul>
					</div>
				</div>

				<div class="border_top">
					<div class="inp_wrap">
						<label for="f5" class="blind">내용</label>
						<div class="inp_ele">
							<div class="input"><?=$editor_html; ?></div>
						</div>
					</div>
					<div class="inp_wrap">
						<span class="byte"><span id="byte">0</span>/200</span>
					</div>
				</div>

				<div class="border_box" <?=($mode=='txt')?"hidden":""; ?>>
					<p class="ico_import red point_red">첨부파일 최대 5개, 동영상의 경우 20mb 이하의 파일만 첨부 가능합니다.</p>
					<div class="inp_wrap">
						<div class="title count3"><label for="f_01">첨부파일</label></div>
						<div class="inp_ele count6 r_btn_120">
							<div class="input">
								<input type="text" placeholder="" id="join7_1" title="파일" disabled="" value="" >
							</div>
							<button type="button" class="inp_file btn" id="btnFile1" accept="image/*,video/*">파일찾기</button>
						</div>
						<? for ($i=0; $i<5; $i++) { ?>
							<input type="file" id="bf_file<?=$i+1 ?>" name="bf_file[]" hidden idx="<?=$i+1 ?>" act="<?=($w == 'u' && $file[$i])?"in":"" ?>">
							<input type="checkbox" id="bf_file_del<?=$i+1 ?>" name="bf_file_del[<?=$i?>]" value="1" hidden>
						<? } ?>
					</div>
					<div class="file_list">
						<ul id="file_list">
							<?
							for ($i=0; $i<5; $i++) { ?>
								<? if($w == 'u' && $file[$i]) { ?>
									<li>
										<span class="name"><?=$file[$i]['source'] ?> (<?=$file[$i]['size'] ?>)</span>
										<button type="button" class="btn_delete gray" id="file_delete" idx="<?=$i ?>"><span class="blind">삭제</span></button>
									</li>
									<?
								}
							}
							?>
						</ul>
					</div>
				</div>

				<div class="info_box">
					<p class="ico_import red point_red">리뷰 작성 안내</p>
					<div class="list">
						<ul class="hyphen">
							<li>상품에 대한 평가는 한글 기준 20자 이상 작성해 주세요.</li>
							<li>단순 문자 또는 기호의 나열/반복, 욕설 등록 시 적립금이 지급되지 않습니다.</li>
							<li>이메일, 휴대전화 번호 등 개인 정보가 포함된 글 또는 광고 게시글은 블라인드 처리 및 계정 블록 처리가 될 수 있습니다.</li>
							<li>작성된 후기는 라이프라이크 홍보용 컨텐츠로 사용될 수 있습니다.</li>
							<li>후기 도용 시 적립금 회수 및 계정 블록 처리됩니다.</li>
						</ul>
					</div>
				</div>

			</div>
			<div class="grid foot">
				<div class="btn_group two">
					<button type="button" onclick="history.back();" class="btn big border gray"><span>취소</span></button>
					<button type="submit" class="btn big green"><span>등록</span></button>
				</div>
			</div>
		</form>
		<!-- 컨텐츠 종료 -->
	</div>

	<script type="text/javascript">
		$(function(){

			if($("textarea[name='is_content']") != undefined){
				$("textarea[name='is_content']").attr("placeholder","상품에 대한 평가는 한글 기준 20자 이상 작성해 주세요.");
				check_byte('is_content', 'byte');
				
				$("textarea[name='is_content']").keyup(function() {
					check_byte('is_content', 'byte');
				});
			}
			var fileCount = <?=$i?>;

			$("#btnFile1,#btnFile2,#btnFile3").click(function() {
				var accept = $(this).attr("accept");

				var nextID = "";

				for (var i = 1; i <= 5; i++) {
					var $bf_file = $("#bf_file"+i);
					var act = $bf_file.attr("act");

					if($bf_file.val() == "" && act != "in") {
						$bf_file.attr("accept", accept);
						$bf_file.click();
						break;
					}
				}
			});

			$("input[name='bf_file[]']").change(function() {

				var idx = $(this).attr("idx");
		//if(!fileCheck($(this))){
		//	return false;
		//}

		var fileName = "";
		if(window.FileReader){
			fileName = $(this)[0].files[0].name;
		} else {
			fileName = $(this)[0].val().split('/').pop().split('\\').pop();
		}

		if (fileName != "") {
			var html = '';
			html += '<li>';
			html += '<span class="name">'+fileName+'</span>';
			html += '<button type="button" class="btn_delete gray" id="file_delete" idx="'+idx+'">';
			html += '<span class="blind">삭제</span>';
			html += '</button>';
			html += '</li>';

			if($("#file_list li").size() > 0) {
				$('#file_list li:last').after(html);
			} else {
				$('#file_list').html(html);
			}
			fileCount++;
			$(this).attr("act", "in");

		} else {
			alert("파일을 선택해주세요");
			return false;
		}
	});

			$(document).on("click", "#file_delete", function() {
				var result = confirm('첨부 파일을 삭제 하시겠습니까?');

				if(result){
					var idx = $(this).attr('idx');
					$("#bf_file_del"+idx).prop('checked',true);

					$("#bf_file"+idx).attr("act", "del");
					$("#bf_file"+idx).replaceWith($("#bf_file"+idx).val('').clone(true));
					$(this).closest("li").remove();
					fileCount--;
				}
			});
		});

		function fileCheck( file )
		{
		// 사이즈체크
		var maxSize  = 10 * 1024 * 1024
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
		alert("첨부파일 사이즈는 10MB 이내로 등록 가능합니다.    ");
		return false;
	}
	return true;
}

function fitemuse_submit(f)
{

	<? //echo $editor_js; ?>

	if($("#is_age").val() == "") {
		alert("연령대를 선택해주세요.");
		return false;
	}
	if($("textarea[name='is_content']") != undefined){
		var is_content = trim($("textarea[name='is_content']").val()).replace(/\s/g,'');
		var str_len = is_content.length;
	    var rbyte = 0;
	    var one_char = "";
	    for(var i=0; i<str_len; i++)
	    {
	        one_char = is_content.charAt(i);
	        if(escape(one_char).length > 4)
	        {
	            rbyte += 2;                                         //한글2Byte
	        }
	        else
	        {
	            rbyte++;                                            //영문 등 나머지 1Byte
	        }
	     }
		    
		if(rbyte < 40 || rbyte > 200){
			alert("상품에 대한 평가는 한글 기준 20자 이상, 200자 이하로 작성해 주세요.");
			return false;
		}
	}

	return true;
}
</script>
<!-- } 사용후기 쓰기 끝 -->
<?
include_once(G5_PATH.'/tail.php');
?>
