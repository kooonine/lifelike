<?
$sub_menu = "800110";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super')
	alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '레이아웃관리 (WEB)';
include_once ('../admin.head.php');

$sql_common = " from lt_design_main ";
$sql_where = " where (1) ";


$sql = " select COUNT(*) as cnt {$sql_common} {$sql_where} ";
$row = sql_fetch($sql);
$cnt = $row['cnt'];

$token = get_admin_token();
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<table class="table table-bordered" style="height: 100%">
				<thead>
					<tr>
						<th scope="col" class="text-center active" width="50%">레이아웃</th>
						<th scope="col" class="text-center active" width="20%">영역명(타이틀명)</th>
						<th scope="col" class="text-center active" width="10%">노출 설정</th>
						<th scope="col" class="text-center active" width="10%">관리</th>
					</tr>
				</thead>
				<tbody>
					<?
					$sql = " select main_id, main_name, main_fixed, main_order, main_onoff, main_type1, main_type2, main_view_data, main_datetime $sql_common $sql_where order by main_order ";
					$result = sql_query($sql);
					$i=0;

					while ($row=sql_fetch_array($result)) {
						$i++;

						$bg = 'bg'.($i%2);

						$bgType = '';

						$mainType1 = "";
						switch( $row['main_type1'] ){
							case "gnb" :
							{
								$mainType1 = $row['main_name'];
							} break;
							case "rolling" :
							case "image" :
							case "imagetext" :
							case "banner" :
							case "motion" :
							{
								$mainType1 = "이미지 영역 - ".$row['main_name'];
							} break;
							case "subproduct" :
							{
								$mainType1 = "서브상품영역 - ".$row['main_name'];
							} break;
							case "movie" :
							{
								$mainType1 = "영상영역 - ".$row['main_name'];
							} break;
							case "sns" :
							{
								$mainType1 = $row['main_name'];
							} break;
							default :
							{
								$mainType1 = $row['main_name'];
							}
						}

						if($row['main_fixed'] == "Y"){
							$bgType = 'bg-success';
						} else {
							$bgType = '';
						}
						?>

						<tr class="<?=$bg; ?>">

							<? if($row['main_id'] == "3") { ?>
								<td scope="col" class="text-center" rowspan="4"><table class="table table-bordered" style="margin-bottom: 0px;" style="height: 100px;">
									<tr class="bg-success">
										<td style="cursor:pointer;vertical-align: middle;"<? if($main_id == "3") echo 'class="bg-primary"';?> onclick="location.href='./design_component_web.php?main_id=3';"><br/>메인 배너<br/>영역 1<br/><br/></td>
										<td rowspan="2" style="cursor:pointer;vertical-align: middle;" <? if($main_id == "5") echo 'class="bg-primary"';?> onclick="location.href='./design_component_web.php?main_id=5';">상품<br/>영역</td>
										<td rowspan="2" style="cursor:pointer;vertical-align: middle;" <? if($main_id == "6") echo 'class="bg-primary"';?> onclick="location.href='./design_component_web.php?main_id=6';">서비스<br/>소개<br/>영역</td>
									</tr>
									<tr class="bg-success">
										<td style="cursor:pointer;vertical-align: middle;" <? if($main_id == "4") echo 'class="bg-primary"';?> onclick="location.href='./design_component_web.php?main_id=4';"><br/>메인 배너<br/>영역 2<br/><br/></td>
									</tr>
								</table></td>
							<? } else if($row['main_id'] == "4" || $row['main_id'] == "5" || $row['main_id'] == "6") {?>
							<? } else { ?>
								<td scope="col" class="text-center">
									<table class="table table-bordered" style="margin-bottom: 0px;">
										<tr>
											<td class="<?=$bgType; ?>"  style="cursor:pointer;vertical-align: middle;" onclick="location.href='./design_component_web.php?main_id=<?=$row['main_id'] ?>';">
												<?=$mainType1 ?>
											</td>
										</tr>
									</table>
								<? } ?>
							</td>

							<td class="text-center"  style="vertical-align: middle;"><?=$row['main_name'] ?></td>
							<td class="text-center"  style="vertical-align: middle;">
								<?
								if($row['main_fixed'] == "Y"){
									echo '고정노출';
								} else if($row['main_onoff'] == "Y"){
									echo '<input type="button" id="btnMain'.$row['main_id'].'" main_id="'.$row['main_id'].'" class="btn btn-dark" value="ON" data="N" onclick="mainOnOffChange(this, \''.$row['main_id'].'\');" />';
								} else {
									echo '<input type="button" id="btnMain'.$row['main_id'].'" main_id="'.$row['main_id'].'" class="btn btn-secondary" value="OFF" data="Y" onclick="mainOnOffChange(this, \''.$row['main_id'].'\');" />';
								}
								?>
							</td>
							<td class="text-center">
								<input type="button" class="btn btn-secondary" value="관리하기"  onclick="location.href='./design_component_web.php?main_id=<?=$row['main_id'] ?>';" />
							</td>
						</tr>
					<? } ?>
				</tbody>
			</table>
			<div class="x_content">
				<div class="form-group">
					<div class="col-md-12 col-sm-12 col-xs-12 text-right">
						<form name="frm" id="frm" method="post" >
							<input type="hidden" name="token" value="<?=$token ?>">
							<input type="hidden" name="onofflist" value="" id="onofflist">
							<input type="hidden" name="previewonofflist" value="" id="previewonofflist">
							<input type="button" class="btn btn-secondary" value="영역생성하기" onclick="newModal();" />
							<input type="button" class="btn btn-secondary" value="미리보기" id="btnPreview" />
							<input type="button" class="btn btn-success" value="적용하기" id="btnSubmit" />
						</form>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<div class="row">
					<div class="col-md-10 col-sm-10 col-xs-10 text-left"><h5 class="modal-title" id="bankModalLabel">영역생성하기</h5></div>
					<div class="col-md-2 col-sm-2 col-xs-2 text-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
				</div>
			</div>

			<form name="frmNew" id="frmNew" method="post" onsubmit="return frmNew_submit(this);">
				<input type="hidden" name="token" value="<?=$token ?>" >
				<div class="modal-body">
					<h4><span class="fa fa-check-square"></span> 정보입력 <small></small></h4>
					<div class="tbl_frm01 tbl_wrap">
						<table>
							<tbody>
								<tr>
									<th scope="row">영역명</th>
									<td><input type="text" name=main_name  value="" id="main_name" class="form-control" required></td>
								</tr>

								<tr>
									<th scope="row" rowspan="2">영역 템플릿 설정</th>
									<td>
										<select name="main_type1" id="main_type1" class="form-control" required>

											<option value="image" >이미지타입-일반이미지</option>
											<option value="imagetext" >이미지타입-이미지+텍스트</option>
											<option value="rolling" >이미지타입-롤링이미지</option>
											<option value="banner" >이미지타입-띠배너</option>
											<option value="motion">이미지타입-모션이미지</option>

											<option value="movie">동영상타입</option>

											<option value="subproduct" >서브 상품타입</option>

										</select>

									</td>
								</tr>

								<tr>
									<td>
										<div class="radio">
											<label id="main_type2_1"><input type="radio" name="main_type2" value="1" >1단 구성</label>
											<label id="main_type2_2"><input type="radio" name="main_type2" value="2" >2단 구성</label>
											<label id="main_type2_3"><input type="radio" name="main_type2" value="3" >3단 구성</label>
											<label id="main_type2_4"><input type="radio" name="main_type2" value="4" checked="checked" >4단 구성</label>
										</div>
										<select name="selMain_type2" id="selMain_type2" class="form-control hidden">
											<option value="" >이미지수량</option>
											<? for ($i = 1; $i <= 10; $i++) {
												echo '<option value="'.$i.'" >'.$i.'</option>';
											}?>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
					<button type="submit" class="btn btn-success" id="btnConfirm">생성하기</button>
				</div>
			</form>

		</div>
	</div>
</div>

<script>
	$(function(){

		$("#main_type1").change(function(event) {

			var main_type1 = $(this).val();

			$("#main_type2_1").removeClass('hidden').addClass('hidden');
			$("#main_type2_2").removeClass('hidden').addClass('hidden');
			$("#main_type2_3").removeClass('hidden').addClass('hidden');
			$("#main_type2_4").removeClass('hidden').addClass('hidden');
			$("#selMain_type2").removeClass('hidden').addClass('hidden');
			$('input:radio[name=main_type2]:input[value=4]').prop("checked", true);

			if(main_type1 == "image" || main_type1 == "imagetext" || main_type1 == "motion"){
				$("#main_type2_1").removeClass('hidden');
				$("#main_type2_2").removeClass('hidden');
				$("#main_type2_3").removeClass('hidden');
				$("#main_type2_4").removeClass('hidden');

			} else if(main_type1 == "rolling"){
				$("#main_type2_1").removeClass('hidden').addClass('hidden');
				$("#main_type2_2").removeClass('hidden').addClass('hidden');
				$("#main_type2_3").removeClass('hidden').addClass('hidden');
				$("#main_type2_4").removeClass('hidden').addClass('hidden');

				$("#selMain_type2").removeClass('hidden');
				$('input:radio[name=main_type2]:input[value=1]').prop("checked", true);

			} else if(main_type1 == "banner"){
				$("#main_type2_1").removeClass('hidden').addClass('hidden');
				$("#main_type2_2").removeClass('hidden').addClass('hidden');
				$("#main_type2_3").removeClass('hidden').addClass('hidden');
				$("#main_type2_4").removeClass('hidden').addClass('hidden');

				$('input:radio[name=main_type2]:input[value=1]').prop("checked", true);

			} else if(main_type1 == "movie"){
				$("#main_type2_1").removeClass('hidden').addClass('hidden');
				$("#main_type2_2").removeClass('hidden').addClass('hidden');
				$("#main_type2_3").removeClass('hidden').addClass('hidden');
				$("#main_type2_4").removeClass('hidden').addClass('hidden');

				$('input:radio[name=main_type2]:input[value=1]').prop("checked", true);

			} else if(main_type1 == "subproduct"){
				$("#main_type2_1").removeClass('hidden').addClass('hidden');
				$("#main_type2_2").removeClass('hidden');
				$("#main_type2_3").removeClass('hidden');
				$("#main_type2_4").removeClass('hidden');
			}

		//alert($(this).val());
	});


		$("#btnSubmit").click(function(){
			frm_submit($("#frm"));
		});

		$("#btnPreview").click(function(){

			var previewonofflist = new Object();
			var btns = $("input:button[id^='btnMain']");
			var i=0;
			btns.each(function (){
				var main_onoff = $(this).attr("data");
				var main_id = $(this).attr("main_id");

			//previewonofflist.push({"main_id":main_id,"main_onoff":(main_onoff=="Y")?"N":"Y"});
			previewonofflist[main_id] = (main_onoff=="Y")?"N":"Y";
		});
		//alert(JSON.stringify(previewonofflist));
		//return;

		$("#previewonofflist").val(JSON.stringify(previewonofflist));
		$("#frm").attr("action","/index.php?device=pc");
		$("#frm").attr("target","_blank");
		$("#frm").submit();
		return true;
	});

	});

	var onofflist = new Array();

	function mainOnOffChange(ctl, main_id)
	{
	//alert(main_id+","+main_onoff);
	var main_onoff = $("#"+ctl.id).attr("data");

	if(main_onoff == "Y")
	{
		$("#"+ctl.id).removeClass('btn-secondary').addClass('btn-dark');
		$("#"+ctl.id).attr("data","N");
		$("#"+ctl.id).val("ON");
	} else {
		$("#"+ctl.id).removeClass('btn-dark').addClass('btn-secondary');
		$("#"+ctl.id).attr("data","Y");
		$("#"+ctl.id).val("OFF");
	}
	var add = true;
	for(var i=0;i<onofflist.length;i++) {
		if(onofflist[i]["main_id"] == main_id)
		{
			onofflist[i] = {"main_id":main_id
			,"main_onoff":main_onoff};
			add = false;
		}
	}

	if(add)
	{
		onofflist.push(
			{"main_id":main_id
			,"main_onoff":main_onoff});
	}
}

function newModal()
{
	$('#main_name').select();
	$('#newModal').modal('show');
}

function frm_submit(f)
{
	if(onofflist.length == 0)
	{
		alert("변경된 내역이 없습니다.");
		return false;
	}

	if(confirm("적용하시겠습니까?"))
	{
		$("#onofflist").val(JSON.stringify(onofflist));

		$("#frm").attr("action","design_layout_web_update.php");
		$("#frm").attr("target","");
		$("#frm").submit();
		return false;
	}
	return false;

}

function frmNew_submit(f)
{
	if($('#main_name').val().trim() == "")
	{
		alert("영역명을 입력해주세요.");
		return false;
	}

	if($('#main_type1').val() == "rolling" && $('#selMain_type2').val() == "")
	{
		alert("이미지 수량을 입력해주세요.");
		return false;
	}

	if(confirm("저장하시겠습니까?"))
	{
		f.action = "./design_layout_web_insert.php";
		return true;
	}
	return false;
}
</script>

<?
include_once ('../admin.tail.php');
?>
