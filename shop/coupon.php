<?
include_once('./_common.php');

if (G5_IS_MOBILE) {
	include_once(G5_MSHOP_PATH.'/coupon.php');
	return;
}

// 테마에 coupon.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
	$theme_coupon_file = G5_THEME_SHOP_PATH.'/coupon.php';
	if(is_file($theme_coupon_file)) {
		include_once($theme_coupon_file);
		return;
		unset($theme_coupon_file);
	}
}

if ($is_guest){
	alert('회원만 조회하실 수 있습니다.');
}

$g5['title'] = $member['mb_nick'].' 님의 쿠폰 내역';
include_once(G5_PATH.'/head.sub.php');
include_once(G5_PATH.'/head.php');

$sql = " select a.*, b.cm_item_type, b.cm_category_type, b.cm_item_it_id_list, b.cm_item_ca_id_list, b.cm_summary
from lt_shop_coupon as a
inner join lt_shop_coupon_mng as b
on a.cm_no = b.cm_no
where a.mb_id IN ( '{$member['mb_id']}', '전체회원' )
and ((a.cp_start <= '".G5_TIME_YMD."'
and a.cp_end >= '".G5_TIME_YMD."')
or a.cp_end is null)
order by a.cp_no ";
$result = sql_query($sql);

$title = "보유 쿠폰 목록";
?>
<? require_once $_SERVER['DOCUMENT_ROOT']."/lib/navigation.php" ?>
<div id="container">
	<div class="content mypage sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
			<div class="coupon_list2">
				<ul>
					<?
					$cp_count = 0;
					for($i=0; $row=sql_fetch_array($result); $i++) {
						if(is_used_coupon($member['mb_id'], $row['cp_id'])){
							continue;
						}
						$dc = 0;
						if($row['cp_type']) {
							$dc = floor(($price * ($row['cp_price'] / 100)) / $row['cp_trunc']) * $row['cp_trunc'];
							$ount_price = '<span class="per"><span>'.$row['cp_price'].'</span>%</span>';
						} else {
							$dc = $row['cp_price'];
							$ount_price = '<span class="won"><span>'.number_format($row['cp_price']).'</span> 원</span>';
						}

						if($row['cp_maximum'] && $dc > $row['cp_maximum']) {
							$dc = $row['cp_maximum'];
							$ount_price = '<span class="won"><span>'.number_format($row['cp_maximum']).'</span> 원</span>';
						}
						$cp_count++;
						?>
						<li>
							<div class="couponBox">
								<div class="couponPay"><?=$ount_price; ?></div>
								<div class="couponInfo">
									<? if($row['cm_item_type'] == "1" || $row['cm_category_type'] == "1") {?>
										<button type="button" class="btn floatR arrow_r" id="btn_coupon_item1" cm_no="<?=$row['cm_no'] ?>" cm_type="1" style="font-size:12px; padding-right:10px;">적용 제품보기</button>
									<? }

									if($row['cm_item_type'] == "2" || $row['cm_category_type'] == "2") {?>
										<button type="button" class="btn floatR arrow_r" id="btn_coupon_item2" cm_no="<?=$row['cm_no'] ?>" cm_type="2" style="font-size:12px; padding-right:10px;"><span>적용 제외 제품보기</span></button>
									<? }?>
									<?if ($row['cp_end'] != 0){?>
										<span class="category">D-<?=ceil((strtotime($row['cp_end']) - strtotime(G5_TIME_YMDHIS)) /(60*60*24))?></span>
									<? } ?>
									<div class="subject"><?=get_text($row['cp_subject']); ?></div>
									<ul class="disc">
										<li>
											<? if($row['cp_end'] != '0000-00-00') { ?>
												<span class="date"><?=substr($row['cp_start'], 0, 10); ?> ~ <?=substr($row['cp_end'], 0, 10); ?></span>
											<? } else { ?>
												<span class="date">기간 제한 없음</span>
											<? } ?>
										</li>
										<?
										if($row['cm_summary']){
											echo '<li>'.$row['cm_summary'].'</li>';
										}
										if($row['cp_minimum']){
											echo '<li>결제 시 '.number_format($row['cp_minimum']).'원 이상 구매 시 사용</li>';
										}
										if($row['cp_maximum']){
											echo '<li>최대 할인 금액 '.number_format($row['cp_maximum']).' 원</li>';
										}
										?>
									</ul>

								</div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</li>
					<? } ?>
					<?
					if(!$cp_count){
						echo '<li><p>보유하신<br>쿠폰이 없습니다.</p></li>';
					}
					?>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<div id="popup"></div>
<script>
	$(document).ready(function() {
		$('#btn_coupon_item1,#btn_coupon_item2').click(function(){
			var cm_no = $(this).attr("cm_no");
			var cm_type = $(this).attr("cm_type");
			var $table = $("#popup");
			$.post("./coupon_item.php", { cm_no : cm_no, cm_type : cm_type }, function(data){
				$table.empty().html(data);
			});
		});
	});
</script>
<?
include_once(G5_PATH.'/tail.php');
include_once(G5_PATH.'/tail.sub.php');
?>
