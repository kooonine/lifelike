<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

if($w == '')
	$w = 'c';

$wr_is_comment = sql_fetch(" select count(wr_id) cnt from {$write_table} where   wr_parent='{$wr_id}' and wr_is_comment = '1' and     mb_id = '{$member['mb_id']}'");
if($wr_is_comment['cnt'] != 0){
	alert("이미 고객님께서는 신청한 이력이 있습니다. 감사합니다.");
}
?>
<!-- container -->
<div id="container">
	<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>

	<div class="content community type1 sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid cont">
			<div class="title_bar ">
				<h2 class="g_title_01">신청자 정보</h2>
			</div>
			<p class="ico_import red point_red">정보가 올바르지 않은 경우 MY페이지 &gt; 나의정보에서 수정이 가능합니다.</p>
		</div>
		<form name="fviewcomment" id="fviewcomment" action="<?=https_url(G5_BBS_DIR)."/write_comment_update.php"; ?>" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off" class="bo_vc_w">
			<input type="hidden" name="w" value="<?=$w ?>" id="w">
			<input type="hidden" name="bo_table" value="<?=$bo_table ?>">
			<input type="hidden" name="wr_id" value="<?=$wr_id ?>">
			<input type="hidden" name="comment_id" value="<?=$c_id ?>" id="comment_id">
			<input type="hidden" name="sca" value="<?=$sca ?>">
			<input type="hidden" name="sfl" value="<?=$sfl ?>">
			<input type="hidden" name="stx" value="<?=$stx ?>">
			<input type="hidden" name="spt" value="<?=$spt ?>">
			<input type="hidden" name="page" value="<?=$page ?>">
			<input type="hidden" name="is_good" value="">
			<input type="hidden" id="wr_content" name="wr_content"  value="">
			<div class="grid type2" id="gridContent">

				<?
				$sql_board = " select * from {$write_table} where wr_id='$wr_id'";
				$result = sql_query($sql_board);

				while ($row=sql_fetch_array($result)) {

					$experience_view_data = json_decode(str_replace('\\','',$row['wr_4']), true);


					if($experience_view_data['name'] == 1) {
						?>
						<div class="inp_wrap">
							<div class="title count3"><label for="mb_name">신청자 명</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="text" placeholder="" id="mb_name" value="<?=$member['mb_name']?>"></div>
							</div>
						</div>
						<?
					}
					if($experience_view_data['sex'] == 1) {

						?>
						<div class="inp_wrap">
							<div class="title count3"><label for="mb_email">이메일</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="text" placeholder="" id="mb_email" value="<?=$member['mb_email']?>"></div>
							</div>
						</div>
						<?
					}
					if($experience_view_data['phone'] == 1){

						?>
						<div class="inp_wrap">
							<div class="title count3"><label for="mb_hp">휴대전화 번호</label></div>
							<div class="inp_ele count6">
								<div class="input"><input type="tel" placeholder="" id="mb_hp" value="<?=$member['mb_hp']?>"></div>
							</div>
						</div>
						<?
					}
					if($experience_view_data['age'] == 1) {

						?>
						<div class="inp_wrap">
							<div class="title count3"><label for="join2">생년월일<span class="txt_essential"><em>필수입력</em></span></label></div>
							<div class="inp_ele count2">
								<span class="sel_box">
									<select name="year" id= "year" title="목록" target1="month" target2="day">
										<option value="">선택</option>
										<?
										$mb_birth_explode = explode('-',$member['mb_birth']);
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
						<script>
							$(function(){
								$('#year').change(function (){
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

								$('#month').change(function ()
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
							});
						</script>
						<?
					}
					if($experience_view_data['sex'] == 1) {

						?>
						<div class="inp_wrap">
							<div class="title count3"><label for="join3">성별</label></div>
							<div class="inp_ele count3">
								<span class="chk radio">

									<input type="radio" id="mb_sex_1" name="mb_sex" value="M" <? if($member['mb_sex'] == 'M') echo "checked='checked'";?>>
									<label for="r_01_1">남성</label>
								</span>
							</div>
							<div class="inp_ele count3">
								<span class="chk radio">
									<input type="radio" id="mb_sex_2" name="mb_sex" value="F" <? if($member['mb_sex'] == 'F') echo "checked='checked'";?>>
									<label for="r_01_2">여성</label>
								</span>
							</div>
						</div>

						<?
					}
					if($experience_view_data['address'] == 1){

						?>
						<div class="title_bar none">
							<h3 class="g_title_04">주소(기본배송지)</h3>
							<a href="#" class="arrow_r btn">배송지 변경</a>
						</div>
						<div class="inp_wrap">
							<div class="inp_ele count3">
								<button type="button" class="btn small gray_line" onclick="win_zip('fviewcomment', 'mb_zip', 'mb_addr1', 'mb_addr2','mb_addr3', 'mb_addr_jibeon');">우편번호</button>
							</div>
							<div class="inp_ele count6">
								<label for="mb_zip" class="blind" >우편번호</label>
								<div class="input"><input type="tel" placeholder="" id="mb_zip"  name= "mb_zip" title="우편번호" value="<?=$member['mb_zip1'].$member['mb_zip2']?>" disabled></div>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="inp_ele">
								<div class="input"><input type="text" placeholder="" id="mb_addr1" name= "mb_addr1" title="상세주소" value="<?=$member['mb_addr1']?>" ></div>
							</div>
						</div>
						<div class="inp_wrap">
							<div class="inp_ele">
								<div class="input"><input type="text" placeholder="" id="mb_addr2" name= "mb_addr2" title="상세주소" value="<?=$member['mb_addr2']?>" ></div>
							</div>
						</div>
        				<input type="hidden" id="mb_addr3" name = "mb_addr3" value="<?php echo $member['mb_addr3']?>"  >
						<input type="hidden" id = "mb_addr_jibeon"name="mb_addr_jibeon" value="<?=$member['mb_addr_jibeon']; ?>">
						<?
					}?>
					<div id="itembox"></div>
					<? if($experience_view_data['additem'] != "[]"){

						?>

						<script>
							var wr_4 = '<?=$row["wr_4"]; ?>';
							if(wr_4 != "")
							{
								var wr_4 = JSON.parse(wr_4.replace("&#034;","\""));

								for(var i=0;i<wr_4.addItem.length;i++)
								{
									addItem(wr_4.addItem[i]);
								}
							}


							function addItem(addItem)
							{

								var addHtml = '<div class="title_bar none">';
								addHtml += '	<h3 class="g_title_04">'+addItem+'</h3>';
								addHtml += '</div>';
								addHtml += '<div class="inp_wrap">';
								addHtml += '	<div class="inp_ele">';
								addHtml += '		<div class="input"><input type="text" placeholder="" id="'+addItem+'" name="addItem"></div>';
								addHtml += '	</div>';
								addHtml += '</div>';

								$("#itembox").append(addHtml);
							}

						</script>
						<?
					}
				}
				?>
			</div>

			<div class="grid type2">
				<div class="title_bar">
					<h3 class="g_title_01">이용자동의</h3>
				</div>
				<div class="list">
					<ul class="type1 terms">
						<li>
							<p class="chk_title">
								<span class="fix">개인정보 수집 · 이용 동의<span>(필수)</span></span>
								<button type="button" class="btn small border" id="btnUser_privacy">전문보기</button>
							</p>
							<div class="floatR">
								<span class="chk radio">
									<input type="radio" id="chk_01" name="rr21">
									<label for="chk_01">동의</label>
								</span>
								<span class="chk radio">
									<input type="radio" id="r_012" name="rr21">
									<label for="r_012">미동의</label>
								</span>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<hr class="full_line">
			<div class="grid type2">
				<div class="info_box ">
					<p class="ico_import red point_red">주의사항</p>
					<div class="list">
						<ul class="hyphen">
							<li>체험단 신청 본인이 아닌 경우 신청이 불가합니다.</li>
							<li>개인정보 수집 · 이용동의에 동의하지 않을 경우 당첨문자 안내 및 상품 배송이 불가하여 이벤트 참여가 불가합니다.</li>
							<li>만 19세 미만의 미성년자는 참여가 불가합니다.</li>
						</ul>
					</div>
				</div>
				<div class="btn_group"><button type="submit" id="btn_submit" class="btn big green"><span>신청하기</span></button></div>
			</div>
		</form>
		<!-- 컨텐츠 종료 -->
	</div>
	<script>
		$(document).ready(function(){
			$("#btnUser_privacy").click(function(){
				var url = '<?=G5_MOBILE_URL?>/common/terms_agreement.php?id=chk_01&type=user_privacy';
				window.open(url, "user_privacy", "left=100,top=100,width=800,height=600,scrollbars=1");
			});
		});
		
	function fviewcomment_submit(f)
	{
		if($('#mb_name').val() == ''){
			alert('이름을 입력 해 주세요');
			return false;
		} else if($('#mb_email').val() == ''){
			alert('이메일을 입력 해 주세요');
			return false;
		} else if($('#mb_hp').val() == ''){
			alert('전화번호를 입력 해 주세요');
			return false;
		} else if($('#mb_zip').val() == ''){
			alert('주소를 입력 해 주세요');
			return false;
		} else if($('#mb_age').val() == ''){
			alert('생년월일을 입력 해 주세요');
			return false;
		} else if($('#mb_sex').val() == ''){
			alert('성별을 선텍 해 주세요');
			return false;
		}
		
		if($('#chk_01:checked').length == 0){
			alert('개인정보 수집 · 이용 동의를 해주세요');
			return false;
		}

	f.is_good.value = 0;

	var subject = "";
	var content = "";

	var contentJson = new Object();
	contentJson.id = "<?php echo $member['mb_id'] ?>";
	
	if($('#mb_name').val() != '') contentJson.name = $('#mb_name').val();
	if($('#mb_email').val() != '') contentJson.email = $('#mb_email').val();
	if($('#mb_hp').val() != '') contentJson.phone = $('#mb_hp').val();
	if($('#year').val() != '') contentJson.age = $('#year').val()+'-'+$('#month').val()+'-'+$('#day').val();
	if($('#mb_sex').val() != '') contentJson.sex = $('#mb_sex').val();

	if($('#mb_zip').val() != ''){
    	var addressJson = new Object();
    	addressJson.zip= $('#mb_zip').val();
    	addressJson.adrr1= $('#mb_addr1').val();
    	addressJson.adrr2= $('#mb_addr2').val();
    	addressJson.addr3= $('#mb_addr3').val();
    	addressJson.addr_jibeon = $('#mb_addr_jibeon').val();
    	contentJson.address = addressJson;
	}

	if($('input[name="addItem"]').length > 0) {
        var addItemJson = {};
        var keyname = '';
    
    	$('input[name="addItem"]').each(function (i){
    		 var addItemValue = $("input[name='addItem']").eq(i).attr("value");
    		 var addItemId = $("input[name='addItem']").eq(i).attr("id");
    		 addItemJson[keyname + addItemId] = addItemValue;
    	});
    
    	contentJson.addItem = addItemJson;
	}

	$.ajax({
		url: g5_bbs_url+"/ajax.filter.php",
		type: "POST",
		data: {
			"subject": "",
			"content": contentJson
		},
		dataType: "json",
		async: false,
		cache: false,
		success: function(data, textStatus) {
			subject = data.subject;
			content = data.content;
		}
	});


	document.getElementById('wr_content').value = JSON.stringify(contentJson);




	set_comment_token(f);

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

</script>
