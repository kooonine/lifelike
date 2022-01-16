<?php
$sub_menu = "800600";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super' && $is_admin != 'admin')
	alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '필터관리';

include_once('../admin.head.php');

$sql_common = " from lt_shop_finditem ";
$sql_where = " where (1) ";

$token = get_admin_token();
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

			<div class="x_title">
				<div class="col-md-6 col-sm-6 col-xs-6 text-left">
					<h4><span class="fa fa-check-square"></span>필터관리<small></small></h4>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
					<input type="button" value="필터추가" class="btn btn-success" id="btnAdd" />
				</div>
				<div class="clearfix"></div>
			</div>

			<form class="form-horizontal form-label-left" name="frm" id="frm" method="post" onsubmit="return frm_submit(this);">
				<input type="hidden" name="w" value="<?php echo $w; ?>">
				<input type="hidden" name="token" value="<?php echo $token ?>">

				<div class="x_content" id="divFindItem">


					<div class="divider-dashed"></div>

					<?php
					$sql = " select * $sql_common $sql_where ";
					$result = sql_query($sql);
					$i = 0;

					while ($row = sql_fetch_array($result)) {
						$i++;
					?>

						<div id="findItem<?php echo $i; ?>">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">필터 <?php echo $i; ?></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input type="text" class="form-control" name="fi_subject[]" placeholder="" required="required" value="<?php echo $row['fi_subject']; ?>">
									<input type="hidden" name="fi_id[]" value="<?php echo $row['fi_id']; ?>">
								</div>
								<div class="clearfix"></div><br />

								<label class="control-label col-md-3 col-sm-3 col-xs-12">사용여부 <?php echo $i; ?></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<select name="fi_status[]" class="">
										<option value="N" <?php echo get_selected($row['fi_status'], 'N') ?>>사용안함</option>
										<option value="B" <?php echo get_selected($row['fi_status'], 'B') ?>>브랜드</option>
										<option value="Y" <?php echo get_selected($row['fi_status'], 'Y') ?>>사용함</option>
									</select>
								</div>

								<div class="clearfix"></div><br />

								<label class="control-label col-md-3 col-sm-3 col-xs-12 ">목록 <?php echo $i; ?></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input type="text" class="form-control" name="fi_contents[]" data-role="tagsinput" style="width:100%" value="<?php echo $row['fi_contents']; ?>" />
								</div>
							</div>

							<div class="divider-dashed"></div>

						</div>

					<?php } ?>



				</div>

				<div class="x_content">
					<div class="form-group">
						<div class="col-md-12 col-sm-12 col-xs-12 text-right">
							<input type="button" value="저장" class="btn btn-success" id="btnSubmit">
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>
</div>

<script src="../vendors/bootstrap-tagsinput-latest/src/bootstrap-tagsinput.js"></script>


<script>
	$(function() {
		$(document).ready(function($) {
			var addItemCnt = <?php echo $i ?>;

			$("#btnSubmit").click(function(event) {
				$("#frm").submit();
			});

			$("#btnAdd").click(function(event) {
				addItemCnt++;

				var addHtml = '';
				addHtml += '<div id="findItem' + addItemCnt + '">';
				addHtml += '<div class="form-group">';
				addHtml += '	<label class="control-label col-md-3 col-sm-3 col-xs-12">질문(추가)</label>';
				addHtml += '	<div class="col-md-8 col-sm-8 col-xs-10">';
				addHtml += '		<input type="text" class="form-control" name="fi_subject[]" placeholder="" required="required">';
				addHtml += '	</div>';
				addHtml += '	<div class="col-md-1 col-sm-1 col-xs-2">';
				addHtml += '		<input type="button" value="삭제" class="btn btn-danger" id="btnDel' + addItemCnt + '" onclick="delItem(\'findItem' + addItemCnt + '\');" />';
				addHtml += '	</div>';
				addHtml += '	<div class="clearfix"></div><br/>';
				addHtml += '	<label class="control-label col-md-3 col-sm-3 col-xs-12">사용여부(추가)</label>';
				addHtml += '	<div class="col-md-9 col-sm-9 col-xs-12">';

				addHtml += '	<select name="fi_status[]" class="">';
				addHtml += '		<option value="N" selected>사용안함</option>';
				addHtml += '		<option value="Y" >사용함</option>';
				addHtml += '	</select>';

				addHtml += '	</div>';
				addHtml += '	<div class="clearfix"></div><br/>';
				addHtml += '	<label class="control-label col-md-3 col-sm-3 col-xs-12 ">답변(추가)</label>';
				addHtml += '	<div class="col-md-9 col-sm-9 col-xs-12">';
				addHtml += '		<input type="text" class="form-control" id="fi_contents' + addItemCnt + '" name="fi_contents[]" value="" data-role="tagsinput" style="width:100%"  />';
				addHtml += '    </div>';
				addHtml += '</div>';
				addHtml += '<div class="divider-dashed"></div>';
				addHtml += '</div>';

				$("#divFindItem").append(addHtml);

				$("#fi_contents" + addItemCnt).tagsinput();
			});

		});
	});

	function frm_submit(f) {

		if (confirm("적용하시겠습니까?")) {
			f.action = "./design_finditem_update.php";
			return true;
		}
		return false;
	}

	function delItem(divid) {
		if (confirm("질문을 삭제 하시겠습니까?")) {
			$("#" + divid).html("");
		}
		return false;
	}
</script>


<?php
include_once('../admin.tail.php');
?>