<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 3;

$is_checkbox = false;
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
?>
<!-- container -->
<div id="container">
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="tab">
				<ul class="type1 black">
					<li><a href="<?=G5_BBS_URL?>/faq.php"><span>FAQ</span></a></li>
					<li class="on"><a href="<?=G5_BBS_URL?>/qalist.php"><span>1:1 문의하기</span></a></li>
				</ul>
			</div>
			<div class="tab_cont_wrap">
				<div class="tab button_group ">
					<ul class="type4 black tab_btn center">
						<? if ($category_option) { ?>
							<?=$category_option ?>
						<? } ?>
					</ul>
				</div>
				<div class="title_bar none">
					<? if ($admin_href || $write_href) { ?>
						<? if ($admin_href) { ?><a href="<?=$admin_href ?>"><button type="button" class="btn small gray floatR"><span>관리자</span></button></a><? } ?>
						<? if ($write_href) { ?><a href="<?=$write_href ?>"><button type="button" class="btn small green floatR"><span>문의하기</span></button></a><? } ?>
					<? } ?>
				</div>
				<!-- } 게시판 페이지 정보 및 버튼 끝 -->

				<div class="tab_cont">
					<!-- tab1 -->
					<div class="tab_inner">

						<form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);" method="post">
							<input type="hidden" name="stx" value="<?=$stx; ?>">
							<input type="hidden" name="sca" value="<?=$sca; ?>">
							<input type="hidden" name="page" value="<?=$page; ?>">

							<div class="tbl_list">
								<table>
									<colgroup>
										<col style="width:15%;">
										<col style="width:70%;">
										<col style="width:15%;">
									</colgroup>
									<tbody>
										<? for ($i=0; $i<count($list); $i++) { ?>
											<tr onclick="location.href='<?=$list[$i]['view_href']; ?>';">
												<?=($list[$i]['qa_status'] ? '<td class="point alignL">답변완료</td>' : '<td class="alignL">답변대기</td>'); ?>
												<td class="alignL">
													<a href="<?=$list[$i]['view_href']; ?>" class="ellipsis">
														[<?=$list[$i]['category']; ?>] <?=$list[$i]['subject']; ?>
														<?=$list[$i]['icon_file']?"<i class=\"fa fa-download\" aria-hidden=\"true\"></i>":""; ?>
													</a>
												</td>
												<td class="date"><?=substr($list[$i]['qa_datetime'],0,16); ?></td>
											</tr>
											<? } ?>

										<? if ($i == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
									</tbody>
								</table>
							</div>
						<!-- div class="bo_fx">
							<ul class="btn_bo_user">
								<? if ($is_checkbox) { ?>
								<li><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_admin"><i class="fa fa-trash-o" aria-hidden="true"></i> 선택삭제</button></li>
								<? } ?>
								<? if ($list_href) { ?><li><a href="<?=$list_href ?>" class="btn_b01 btn"><i class="fa fa-list" aria-hidden="true"></i> 목록</a></li><? } ?>
								<? if ($write_href) { ?><li><a href="<?=$write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 문의등록</a></li><? } ?>
							</ul>
						</div -->
					</form>
				</div>

				<? if($is_checkbox) { ?>
					<noscript>
						<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
					</noscript>
				<? } ?>
				<div class="paging">
					<!-- 페이지 -->
					<?=$list_pages;  ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<? if ($is_checkbox) { ?>
	<script>
		function all_checked(sw) {
			var f = document.fqalist;

			for (var i=0; i<f.length; i++) {
				if (f.elements[i].name == "chk_qa_id[]")
					f.elements[i].checked = sw;
			}
		}

		function fqalist_submit(f) {
			var chk_count = 0;

			for (var i=0; i<f.length; i++) {
				if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
					chk_count++;
			}

			if (!chk_count) {
				alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
				return false;
			}

			if(document.pressed == "선택삭제") {
				if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
					return false;
			}

			return true;
		}
	</script>
<? } ?>
<!-- } 게시판 목록 끝 -->
