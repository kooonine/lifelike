<?
include_once('./_common.php');

$g5['title'] = '주문 상세 보기';
include_once(G5_MSHOP_PATH.'/_head.php');

// LG 현금영수증 JS
if($od['od_pg'] == 'lg') {
	if($default['de_card_test'] && $od['od_type'] != 'R') {
		echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
	} else {
		echo '<script language="JavaScript" src="https://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
	}
}
?>
<script>
	var header = '<div id="lnb" class="header_bar">';
	header += '<h1 class="title"><span>주문 상세 보기</span></h1>';
	header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
	header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
	header += '</div>';
	$('#header').html(header);
</script>

<!-- //lnb -->
<div class="content mypage sub">
	<!-- 컨텐츠 시작 -->
	<div class="grid cont">
		<div class="order_cont">
			<div class="head">
				<span class="category round_green"><?=$od_type_name; ?></span>
				<span class="category round_none"><?=$od_status; ?></span>
				<?
				if($od['od_status_claim'] == '반품') echo "<span class='category round_black black'>반품</span>";
				if($od['od_status_claim'] == '교환') echo "<span class='category round_black black'>교환</span>";
				if($od['od_status_claim'] == '철회') echo "<span class='category round_black black'>철회</span>";
				if($od['od_status_claim'] == '주문취소') echo "<span class='category round_black black'>취소</span>";

				if($od['od_status'] == '리스중') echo "<span class='category round_black black'>리스중</span>";
				if($od['od_status'] == '리스종료') echo "<span class='category round_black black'>리스종료</span>";
				if($od['od_status_claim'] == '해지') echo "<span class='category round_black black'>해지</span>";
				?>
			</div>
			<div class="body">
				<div class="order_num">
					<span class="tit">주문번호 : <?=$od_id; ?></span>
				</div>
				<?
				$st_count1 = $st_count2 = 0;
				$custom_cancel = false;

				$sql = " select it_id, it_name, cp_price, ct_send_cost, it_sc_type
				,ct_id, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price, ct_rental_price, ct_item_rental_month, ct_keep_month, ct_receipt_price
                , if(ct_status IN ( '결제완료', '상품준비중', '배송중', '배송완료', '구매완료' ), 0, 1) as ct_status_order
				from {$g5['g5_shop_cart_table']}
				where od_id = '$od_id'
                order by it_id, it_sc_type, ct_status_order ";
				$result = sql_query($sql);
				
				$tot_rows = sql_num_rows($result);
				$rowspan = 0;
				$rowspanCnt = 0;
				$total_send_cost = (int)$od['od_send_cost'];

				for($i=0; $row=sql_fetch_array($result); $i++) {
					$image = get_it_image($row['it_id'], 80, 80, '', '', $row['it_name']);

					$opt_price = $row['ct_price'] + $row['io_price'];
					$sell_price = $opt_price * $row['ct_qty'];
					$point = $row['ct_point'] * $row['ct_qty'];
					$tot_point += $point;

					$opt_rental_price = $row['ct_rental_price'] + $row['io_price'];
					$sell_rental_price = $opt_rental_price * $row['ct_qty'];
					$btn_act = '';
					
					$ct_send_cost_str = "-";
					if($od['od_type'] == "O") {
    				//제품
					    $sql_sc = " select  SUM((b.its_final_price + a.io_price) * a.ct_qty) as price,
        									                    SUM((b.its_price + a.io_price) * a.ct_qty) as before_price,
        									                    SUM(a.ct_qty) as qty,
                                                                count(distinct a.ct_id) as ct_cnt
                        									from lt_shop_cart as a
                        									inner join lt_shop_item_sub as b on a.it_id = b.it_id and a.its_no = b.its_no
                        									inner join lt_shop_item as c on a.it_id = c.it_id
                        									where  a.od_id = '$od_id'
                                                            and    a.it_id = '{$row['it_id']}' ";
					    $sc = sql_fetch($sql_sc);
					    if($sc){
					        if($row['it_sc_type'] == '2' && $de_individual_costs_use == '1'){
					            
					            //선택설정(상품별 개별배송비 사용 필수) : 상품별로 배송비 부과
					            $ct_send_cost = (int)get_item_sendcost($row['it_id'], $sc['price'], $sc['qty'], $od_id, $sc['before_price']);
					            $rowspan = $sc['ct_cnt'];
					            
					            if($ct_send_cost > 0) $total_send_cost = (int)$total_send_cost - (int)$ct_send_cost;
					        } else {
					            $rowspan = $tot_rows;
					            //$rowspan = $sc['ct_cnt'];
					            $ct_send_cost = $total_send_cost;
					        }
					    }
					    
					    if($ct_send_cost == 0) $ct_send_cost_str = "무료배송";
					    else $ct_send_cost_str = number_format($ct_send_cost)." 원";
					    
					   $tot_rows--;
						?>
						<div class="cont">
							<div class="photo"><a href="./item.php?it_id=<?=$row['it_id']; ?>" ><?=$image; ?></a></div>
							<div class="info">
								<a href="./item.php?it_id=<?=$row['it_id']; ?>"><strong><?=$row['it_name']; ?></strong></a>
								<p>옵션 : <?=get_text($row['ct_option']); ?></p>

								<p>제품 금액 : <?=number_format($opt_price); ?></p>
								<p>수량 : <?=number_format($row['ct_qty']);?></p>
								<p>배송비 : <?=$ct_send_cost_str;?></p>
								<p class="price"><span>주문 금액 : </span>  <?=number_format($sell_price); ?> 원</p>
							</div>
							<div class="clear"></div>
							<div class="btn_comm count3" id="orderinquiry_btn">
								<? if($row['ct_status'] == "구매완료") {
									$review = sql_fetch("select count(*) cnt from lt_shop_item_use where it_id = '{$row['it_id']}' and mb_id = '{$member['mb_id']}' and ct_id = '{$row['ct_id']}' ");
									?>
									<? if($review['cnt'] <= 0){ ?><button class="btn gray_line small" it_id="<?=$row['it_id']?>" ct_id="<?=$row['ct_id']?>"><span>리뷰작성</span></button>
									<? } else { ?><button class="btn gray_line small" it_id="<?=$row['it_id']?>" ><span>리뷰보기</span></button>
								<? } ?>
							<? } ?>
							<?=get_btn_act($od, $row['ct_status'], $uid); ?>
						</div>
					</div>
					<?
				} elseif($od['od_type'] == "R") {
					//리스
					?>
					<div class="cont">
						<div class="photo"><a href="./item.php?it_id=<?=$row['it_id']; ?>" ><?=$image; ?></a></div>
						<div class="info">
							<a href="./item.php?it_id=<?=$row['it_id']; ?>"><strong><?=$row['it_name']; ?></strong></a>
							<p>옵션 : <?=get_text($row['ct_option']); ?></p>

							<p>수량 : <?=number_format($row['ct_qty']);?></p>
							<p>계약기간 : <?=number_format($row['ct_item_rental_month']);?>개월</p>
							<p class="price"><span>리스 금액 : </span>  <?=number_format($sell_rental_price); ?> 원</p>
						</div>
						<div class="clear"></div>
						<div class="btn_comm count3" id="orderinquiry_btn">
							<? if($row['ct_status'] == "리스중" || $row['ct_status'] == "리스완료") {
								$review = sql_fetch("select count(*) cnt from lt_shop_item_use where it_id = '{$row['it_id']}' and mb_id = '{$member['mb_id']}' ");
								?>
								<? if($review['cnt'] <= 0) {?><button class="btn gray_line small" it_id="<?=$row['it_id']?>" ct_id="<?=$row['ct_id']?>"><span>리뷰작성</span></button>
								<? } else {?><button class="btn gray_line small" it_id="<?=$row['it_id']?>" ><span>리뷰보기</span></button>
							<? } ?>
						<? } ?>
						<?=get_btn_act($od, $row['ct_status'], $uid); ?>
					</div>
				</div>
			<? }  elseif($od['od_type'] == "L") {
					//세탁
				?>
				<div class="cont">
					<div class="photo"><a href="./item.php?it_id=<?=$row['it_id']; ?>" ><?=$image; ?></a></div>
					<div class="info">
						<a href="./item.php?it_id=<?=$row['it_id']; ?>"><strong><?=$row['it_name']; ?></strong></a>
						<p>옵션 : <?=get_text($row['ct_option']); ?></p>
						<p class="price"><span>주문 금액 : </span>  <?=display_price($row['ct_receipt_price']); ?> 원</p>
					</div>
					<div class="clear"></div>
					<div class="btn_comm count3" id="orderinquiry_btn">
						<?=get_btn_act($od, $row['ct_status'], $uid); ?>
					</div>
				</div>
				<?
			} elseif($od['od_type'] == "K") {
					//보관
				?>
				<div class="cont">
					<div class="photo"><a href="./item.php?it_id=<?=$row['it_id']; ?>" ><?=$image; ?></a></div>
					<div class="info">
						<a href="./item.php?it_id=<?=$row['it_id']; ?>"><strong><?=$row['it_name']; ?></strong></a>
						<p>옵션 : <?=get_text($row['ct_option']); ?></p>
						<p>보관 기간 : <?=$row['ct_keep_month']; ?>개월</p>

						<p class="price"><span>주문 금액 : </span>  <?=display_price($row['ct_receipt_price']); ?> 원</p>
					</div>
					<div class="clear"></div>
					<div class="btn_comm count3" id="orderinquiry_btn">
						<?=get_btn_act($od, $row['ct_status'], $uid); ?>
					</div>
				</div>
				<?
			} elseif($od['od_type'] == "S") {
					//수선
				$tot_price = (int)$od['od_cart_price'] + (int)$od['od_send_cost'] + (int)$od['od_send_cost2'];
				?>
				<div class="cont">
					<div class="photo"><a href="./item.php?it_id=<?=$row['it_id']; ?>" ><?=$image; ?></a></div>
					<div class="info">
						<a href="./item.php?it_id=<?=$row['it_id']; ?>"><strong><?=$row['it_name']; ?></strong></a>
						<p>옵션 : <?=get_text($row['ct_option']); ?></p>
						<p class="price"><span>주문 금액 : </span> <?=($od['od_cart_price'])?display_price($tot_price)." 원":"후불"; ?></p>
					</div>
					<div class="clear"></div>
					<div class="btn_comm count3" id="orderinquiry_btn">
						<?=get_btn_act($od, $row['ct_status'], $uid); ?>
					</div>
				</div>
				<?
			}
			?>

			<?
		}
		?>

		<? if($od['od_type'] == "R") { ?>
			<div class="order_list bottom_cont">
				<ul>
					<li>
						<span class="item">계약일</span>
						<strong class="result"><?=substr($od['od_time'],0,10) ?></strong>
					</li>
					<li>
						<span class="item">리스료</span>
						<strong class="result">월 <?=number_format($od['rt_rental_price']); ?> 원</strong>
					</li>
					<li>
						<span class="item">횟수정보</span>
						<strong class="result"><span class="point"><?=number_format($od['rt_payment_count']); ?></span>회 / <?=number_format($od['rt_month']); ?>회 (현재 횟수/전체 횟수)</strong>
					</li>
				</ul>
			</div>
		<? } ?>
	</div>
</div>
</div>

<? if($od['od_type'] == "L" || $od['od_type'] == "K" || $od['od_type'] == "S") { ?>
	<div class="grid">
		<div class="title_bar">
			<h3 class="g_title_01"><?=$od_type_name?> 요청서</h3>
		</div>
		<div class="border_box order_list mt20 reverse">
			<ul>
				<!-- li>
					<span class="item">수거일자 선택</span>
					<strong class="result">
					   <?=$od['od_hope_date']?>
					</strong>
				</li -->
				<li>
					<span class="item">요청사항</span>
					<strong class="result">
						<?=nl2br($od['cust_memo']) ?>
					</strong>
				</li>
			</ul>
			<div class="photo">
				<ul class="list">
					<?
					$cust_file = json_decode($od['cust_file'], true);
					for ($i = 0; $i < count($cust_file); $i++) {

						$imgL = G5_DATA_URL.'/file/order/'.$od['od_id'].'/'.$cust_file[$i]['file'];

						if ( preg_match("/\.(mp4|mov|avi)$/i", $cust_file[$i]['file'])){
							echo "<li><video controls width='150' height='150' style='vertical-align:top;' >
							<source src='$imgL' type='video/mp4' width='150' height='150' >
							</video></li>";
						} else {
							echo '<li><img src="'.$imgL.'" width="150px"></li>';
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
<? } ?>

<?
	// 총계 = 주문상품금액합계 + 배송비 - 제품 할인 - 결제할인 - 배송비할인
$tot_price = $od['od_cart_price'] + $od['od_send_cost'] - $od['od_cart_coupon'] - $od['od_coupon'] - $od['od_send_coupon'] - $od['od_cancel_price'];
$sale_price = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'] + $od['od_receipt_point'];
?>
<?
$receipt_price = $od['od_receipt_price']
+ $od['od_receipt_point'];
$cancel_price = $od['od_cancel_price'];

$misu = true;
$misu_price = $tot_price - $receipt_price - $cancel_price;

if ($misu_price == 0 && ($od['od_cart_price'] > $od['od_cancel_price'])) {
	$wanbul = " (완불)";
		$misu = false; // 미수금 없음
	}
	else
	{
		$wanbul = display_price($receipt_price);
	}

	// 결제정보처리
	if($od['od_receipt_price'] > 0)
		$od_receipt_price = display_price($od['od_receipt_price']);
	else
		$od_receipt_price = '아직 입금되지 않았거나 입금정보를 입력하지 못하였습니다.';

	$app_no_subj = '';
	$disp_bank = false;
	$disp_receipt = false;
	$easy_pay_name = '';
	if($od['od_settle_case'] == '신용카드' || $od['od_settle_case'] == 'KAKAOPAY' || is_inicis_order_pay($od['od_settle_case']) ) {
		$app_no_subj = '승인번호';
		$app_no = $od['od_app_no'];
		$disp_bank = false;
		$disp_receipt = true;
	} else if($od['od_settle_case'] == '간편결제') {
		$app_no_subj = '승인번호';
		$app_no = $od['od_app_no'];
		$disp_bank = false;
		switch($od['od_pg']) {
			case 'lg':
			$easy_pay_name = 'PAYNOW';
			break;
			case 'inicis':
			$easy_pay_name = 'KPAY';
			break;
			case 'kcp':
			$easy_pay_name = 'PAYCO';
			break;
			default:
			break;
		}
	} else if($od['od_settle_case'] == '휴대전화') {
		$app_no_subj = '휴대전화번호';
		$app_no = $od['od_bank_account'];
		$disp_bank = false;
		$disp_receipt = true;
	} else if($od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체') {
		$app_no_subj = '거래번호';
		$app_no = $od['od_tno'];
	}
	?>

	<div class="grid">
		<? if($od['od_type'] == "R") { ?>
			<div class="title_bar">
				<h3 class="g_title_01">납부 정보</h3>
			</div>
			<div class="order_list border_box">
				<ul>
				<!-- li>
					<span class="item">계약 금액</span>
					<strong class="result">
						<em class="bold"><?=number_format($od['rt_rental_price'] * $od['rt_month']); ?> 원</em>
					</strong>
				</li -->
				<li>
					<span class="item">월 이용료</span>
					<strong class="result">
						<em class="bold"><?=number_format($od['rt_rental_price']); ?> 원</em>
					</strong>
				</li>
				<li>
					<span class="item">납부 방법</span>
					<strong class="result">카드자동이체</strong>
				</li>
				<li>
					<span class="item">카드사</span>
					<strong class="result"><?=$od['od_bank_account']; ?></strong>
				</li>
				<? if($od['rt_billday']) { ?>
					<li>
						<span class="item">납부일</span>
						<strong class="result"><?=$od['rt_billday']; ?>일</strong>
					</li>
				<? } ?>
				<li>
					<span class="item">납부 횟수</span>
					<strong class="result">
						<span class="sel_box" style="width:45%;">
							<select id="rt_payment_count" name="rt_payment_count">
								<?
								$sql = "select * from lt_shop_order_add_receipt where od_id = '$od_id' and od_receipt_type = 'rental' order by od_receipt_rental_month desc";
								$result = sql_query($sql);
								if(sql_num_rows($result)){
									for ($i=0; $row=sql_fetch_array($result); $i++){
										$LGD_TID = $row['od_tno'];
										$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

										echo '<option value="'.$LGD_HASHDATA.'" od_tno="'.$LGD_TID.'" >'.$row['od_receipt_rental_month'].' 회</option>';
									}
								} else {
									$LGD_TID = $od['od_tno'];
									$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

									echo '<option value="'.$LGD_HASHDATA.'" od_tno="'.$LGD_TID.'" >1 회</option>';
								}
								?>
							</select>
						</span>
						<button class="btn gray_line small" id="btnshowReceipt" ><span>영수증 출력</span></button>
					</strong>
				</li>
				<? if($od['rt_rental_startdate']) {?>
					<li>
						<span class="item">납부 시작일</span>
						<strong class="result"><?=$od['rt_rental_startdate']; ?></strong>
					</li>
				<? } ?>

				<? if ($od['od_refund_price'] > 0) { ?>
					<li>
						<span class="item">환불 금액</span>
						<strong class="result"><?=display_price($od['od_refund_price']); ?></strong>
					</li>
				<? } ?>
			</ul>
		</div>
	<? } else { ?>
		<div class="title_bar">
			<h3 class="g_title_01">결제 상세 정보</h3>
		</div>
		<div class="order_list border_box">
			<ul>
				<li>
					<span class="item">주문일시</span>
					<strong class="result"><?=$od['od_time']; ?></strong>
				</li>
				<li>
					<span class="item">결제 방식</span>
					<strong class="result">
						<?=($easy_pay_name ? $easy_pay_name.'('.$od['od_settle_case'].')' : check_pay_name_replace($od['od_settle_case'])); ?>
						<?
						if($od['od_settle_case'] == '휴대전화'){
							$LGD_TID      = $od['od_tno'];
							//$LGD_MERTKEY  = $config['cf_lg_mert_key'];
							$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

							$hp_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
							?>
							<button class="btn gray_line small" onclick="<?=$hp_receipt_script; ?>"><span>영수증 출력</span></button>
							<?
						}

						if($od['od_settle_case'] == '신용카드' || is_inicis_order_pay($od['od_settle_case'])){
							$LGD_TID      = $od['od_tno'];
						//$LGD_MERTKEY  = $config['cf_lg_mert_key'];
							$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

							$card_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
							?>
							<button class="btn gray_line small" onclick="<?=$card_receipt_script; ?>"><span>영수증 출력</span></button>
							<?
						}

						if($od['od_settle_case'] == 'KAKAOPAY')
						{
							$card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$od['od_tno'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
							?>
							<button class="btn gray_line small" onclick="<?=$card_receipt_script; ?>"><span>영수증 출력</span></button>
							<?
						}
						?>
					</strong>
				</li>
				<!--
				<?if($app_no_subj){?>
					<li>
						<span class="item"><?=$app_no_subj; ?></span>
						<strong class="result"><?=$app_no; ?></strong>
					</li>
				<? } ?>
				-->
				<? if($disp_bank){?>
					<li>
						<span class="item">입금자명</span>
						<strong class="result"><?=get_text($od['od_deposit_name']); ?></strong>
					</li>
					<li>
						<span class="item">입금계좌</span>
						<strong class="result"><?=get_text($od['od_bank_account']); ?></strong>
					</li>
				<? } ?>

				<?
				// 현금영수증 발급을 사용하는 경우에만
				if ($default['de_taxsave_use']) {
					// 미수금이 없고 현금일 경우에만 현금영수증을 발급 할 수 있습니다.
					if ($misu_price == 0 && $od['od_receipt_price'] && ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '계좌이체' || $od['od_settle_case'] == '가상계좌')) {
						?>
						<li>
							<span class="item">현금영수증</span>
							<strong class="result">
								<?
								if ($od['od_cash']){
									switch($od['od_settle_case']) {
										case '계좌이체':
										$trade_type = 'BANK';
										break;
										case '가상계좌':
										$trade_type = 'CAS';
										break;
										default:
										$trade_type = 'CR';
										break;
									}
									$cash_receipt_script = 'javascript:showCashReceipts(\''.$LGD_MID.'\',\''.$od['od_id'].'\',\''.$od['od_casseqno'].'\',\''.$trade_type.'\',\''.$CST_PLATFORM.'\');';
									?>
									<a href="javascript:;" onclick="<?=$cash_receipt_script; ?>" class="btn gray_line small">현금영수증 확인하기</a>
									<?
								} else {
									?>
									<a href="javascript:;" onclick="window.open('<?=G5_SHOP_URL; ?>/taxsave.php?od_id=<?=$od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');" class="btn gray_line small">현금영수증을 발급하시려면 클릭하십시오.</a>
								<? } ?>
							</strong>
						</li>
						<?
					}
				}
				?>
			</ul>
		</div>
	<? } ?>
	<div class="order_title">
		<span class="item">결제 금액</span>
		<strong class="result">
			<em class="bold"><?=$od_receipt_price ?> 원</em>
		</strong>
	</div>
	<div class="order_title">
		<span class="item">주문 금액</span>
		<strong class="result">
			<em class="bold"><?=number_format($od['od_cart_price']+$od['od_send_cost']); ?> 원</em>
		</strong>
	</div>
	<div class="order_list border_box">
		<ul>
			<li>
				<span class="item">제품 금액</span>
				<strong class="result"><?=number_format($od['od_cart_price']); ?> 원</strong>
			</li>
				<li>
					<span class="item">배송비</span>
					<strong class="result"><?=number_format($od['od_send_cost']); ?> 원</strong>
				</li>
			<!--
			<? if ($tot_point > 0) { ?>
				<li>
					<span class="item">적립금</span>
					<strong class="result"><?=number_format($tot_point); ?> 원</strong>
				</li>
			<? } ?>
			-->
		</ul>
	</div>
	<? if($sale_price > 0) { ?>
		<div class="order_title">
			<span class="item">할인 금액</span>
			<strong class="result">
				<em class="bold">- <?=number_format($sale_price); ?> 원</em>
			</strong>
		</div>
		<div class="order_list border_box">
			<ul>
				<? if($od['od_cart_coupon'] > 0) { ?>
					<li>
						<span class="item">제품 할인</span>
						<span class="result">- <?=number_format($od['od_cart_coupon']); ?> 원</span>
					</li>
				<? } ?>
				<? if($od['od_coupon'] > 0) { ?>
					<li>
						<span class="item">쿠폰 할인</span>
						<strong class="result">- <?=number_format($od['od_coupon']); ?> 원</strong>
					</li>
				<? } ?>

				<? if($od['od_send_coupon'] > 0) { ?>
					<li>
						<span class="item">배송비 할인</span>
						<strong class="result">- <?=number_format($od['od_send_coupon']); ?> 원</strong>
					</li>
				<? } ?>
				<? if ($od['od_receipt_point'] > 0){ ?>
					<li>
						<span class="item">적립금 할인</span>
						<strong class="result">- <?=display_point($od['od_receipt_point']); ?></strong>
					</li>
				<? } ?>
			</ul>
		</div>
	<? } ?>
	<? if($od['od_cancel_price']+$od['od_refund_price'] > 0) { ?>
		<div class="order_title">
			<span class="item">취소(반품) 금액</span>
			<strong class="result">
				<em class="bold"><?=number_format($od['od_cancel_price']+$od['od_refund_price']); ?> 원</em>
			</strong>
		</div>
		<div class="order_list border_box">
			<ul>
				<li>
					<span class="item">결제 금액</span>
					<span class="result"><?=$od_receipt_price ?> 원</span>
				</li>
				<? if ($od['od_send_cost2'] > 0) { ?>
					<li>
						<span class="item">반품 배송비</span>
						<strong class="result">- <?=number_format($od['od_send_cost2']); ?> 원</strong>
					</li>
				<? } ?>
				<? if ($od['od_cancel_price'] > 0) { ?>
					<li>
						<span class="item">취소금액</span>
						<strong class="result"><?=number_format($od['od_cancel_price']); ?> 원</strong>
					</li>
				<? } ?>
				<? if ($od['od_refund_price'] > 0){?>
					<li>
						<span class="item">환불 금액</span>
						<strong class="result"><?=display_price($od['od_refund_price']); ?> 원</strong>
					</li>
				<? } ?>

			</ul>
		</div>
	<? } ?>
</div>

<div class="grid">
	<div class="title_bar">
		<h3 class="g_title_01">주문 정보</h3>
	</div>
	<div class="order_list border_box">
		<ul>
			<li>
				<span class="item">이 름</span>
				<strong class="result"><?=get_text($od['od_name']); ?></strong>
			</li>
			<li>
				<span class="item">연락처</span>
				<strong class="result"><?=get_text($od['od_tel']); ?></strong>
			</li>
			<li>
				<span class="item">휴대전화 번호</span>
				<strong class="result"><?=get_text($od['od_hp']); ?></strong>
			</li>
			<li>
				<span class="item">주 소</span>
				<strong class="result">
					<span class="addr"><?=get_text(sprintf("(%s%s)", $od['od_zip1'], $od['od_zip2']).' '.print_address($od['od_addr1'], $od['od_addr2'], $od['od_addr3'], $od['od_addr_jibeon'])); ?></strong>
					</strong>
				</li>
				<li>
					<span class="item">E-mail</span>
					<strong class="result"><?=get_text($od['od_email']); ?></strong>
				</li>
			</ul>
		</div>
	</div>

	<div class="grid">
		<div class="title_bar">
			<h3 class="g_title_01">배송 정보</h3>
		</div>
		<div class="order_list border_box">
			<ul>
				<li>
					<span class="item">이 름</span>
					<strong class="result"><?=get_text($od['od_b_name']); ?></strong>
				</li>
				<?if($od['od_b_tel']){?>
				<li>
					<span class="item">연락처</span>
					<strong class="result"><?=get_text($od['od_b_tel']); ?></strong>
				</li>
				<? } ?>
				<li>
					<span class="item">휴대전화 번호</span>
					<strong class="result"><?=get_text($od['od_b_hp']); ?></strong>
				</li>
				<li>
					<span class="item">주 소</span>
					<strong class="result">
						<span class="addr"><?=get_text(sprintf("(%s%s)", $od['od_b_zip1'], $od['od_b_zip2']).' '.print_address($od['od_b_addr1'], $od['od_b_addr2'], $od['od_b_addr3'], $od['od_b_addr_jibeon'])); ?></span>
					</strong>
				</li>
				<?
				// 희망배송일을 사용한다면
				if ($default['de_hope_date_use'])
				{
					?>
					<li>
						<span class="item">희망배송일</span>
						<strong class="result"><?=substr($od['od_hope_date'],0,10).' ('.get_yoil($od['od_hope_date']).')' ;?></strong>
					</li>
				<? }
				if ($od['od_memo'])
				{
					?>
					<li>
						<span class="item">전하실 말씀</span>
						<strong class="result"><?=conv_content($od['od_memo'], 0); ?></strong>
					</li>
				<? } ?>

				<?
				if ($od['od_invoice'] && $od['od_delivery_company'])
				{
					?>
					<li>
						<span class="item">배송회사</span>
						<strong class="result"><?=$od['od_delivery_company']; ?><?=get_delivery_inquiry($od['od_delivery_company'], $od['od_invoice'], 'btn gray_line small'); ?></strong>
					</li>
					<li>
						<span class="item">운송장번호</span>
						<strong class="result"><?=$od['od_invoice']; ?></strong>
					</li>
					<li>
						<span class="item">배송일시</span>
						<strong class="result"><?=$od['od_invoice_time']; ?></td>
						</li>
						<?
					}
					else
					{
						?>
						<li>
							<td class="empty_table" colspan="2">아직 배송하지 않았거나 배송정보를 입력하지 못하였습니다.</td>
						</li>
						<?
					}
					?>
				</ul>
			</div>
		</div>

		<div class="grid">
			<!--
			<div class="title_bar">
				<h3 class="g_title_01">결제합계</h3>
			</div>
			<div class="order_list border_box">
				<ul>
					<li>
						<span class="item">총 구매액</span>
						<strong class="result"><?=display_price($tot_price); ?></strong>
					</li>
					<?
					if ($misu_price > 0) {
						echo '<li>';
						echo '<span class="item">미결제액</span>'.PHP_EOL;
						echo '<strong class="result">'.display_price($misu_price).'</strong>';
						echo '</li>';
					}
					?>
					<li id="alrdy">
						<span class="item">결제액</span>
						<strong class="result"><?=$wanbul; ?></strong>
					</li>
					<? if( $od['od_receipt_point'] ){    //포인트로 결제한 내용이 있으면 ?>
						<li><span class="item">포인트 결제</span><strong class="result"><?=number_format($od['od_receipt_point']); ?> 원</strong></li>
						<li><span class="item">실결제</span><strong class="result"><?=number_format($od['od_receipt_price']); ?> 원</strong></li>
					<? } ?>
				</ul>
			</div>
			-->
			<div class="btn_group">
				<a href="<?=G5_SHOP_URL; ?>/orderinquiry.php"><button type="button" class="btn big border"><span>목록 이동</span></button></a>
			</div>
		</div>

	</div>

	<section class="popup_container layer" id="od_review_select" hidden it_id="" ct_id="">
		<div class="inner_layer" style="top:10%">
			<!-- lnb -->
			<div id="lnb" class="header_bar">
				<h1 class="title"><span>리뷰 작성 유형 선택</span></h1>
			</div>
			<!-- //lnb -->
			<div class="content sub">
				<div class="grid cont">
					<div class="list">
						<ul class="type1 pad">
							<li><a href="#" onclick="location.href='<?=G5_SHOP_URL?>/itemuseform.php?mode=txt&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">일반 리뷰</span></a></li>
							<li><a href="#" onclick="location.href='<?=G5_SHOP_URL?>/itemuseform.php?mode=img&it_id='+$('#od_review_select').attr('it_id')+'&ct_id='+$('#od_review_select').attr('ct_id');"><span class="bold point_black">프리미엄 리뷰</span></a></li>
						</ul>
					</div>
				</div>
			</div>
			<a href="#" class="btn_closed btn_close" onclick="$('#od_review_select').prop('hidden', true);"><span class="blind">닫기</span></a>
		</div>
	</section>

	<form method="post" action="./orderinquirychange.php" id="orderinquirychange_form" name="orderinquirychange_form">
		<input type="hidden" name="act" value="">
		<input type="hidden" name="od_id"  value="">
		<input type="hidden" name="token"  value="">
		<input type="hidden" name="uid"  value="">
	</form>
	<script>
		$(function() {
			$("#btnshowReceipt").click(function(){
				$opt = $("#rt_payment_count option:selected");

				showReceiptByTID('<?=$LGD_MID?>', $opt.attr("od_tno"), $opt.val());
			});

			$(document).on("click", "#orderinquiry_btn button", function() {
				var mode = $(this).text();
				var od_id = $(this).attr("od_id");
				var uid=$(this).attr("uid");

				switch(mode) {
					case "주문취소":
					var uid=$(this).attr("uid");
					location.href="<?=G5_SHOP_URL;?>/orderinquirycancelform.php?od_id="+od_id+"&uid="+uid;
					break;
					case "수거지변경":
					case "배송지변경":
					$.post(
						"./orderinquiry.deliverychangeform.php",
						{ od_id: od_id, uid:uid },
						function(data) {
							$("#dvOrderinquiryPopup").html(data);
						}
						);
					break;
					case "배송조회":
				/*
				var href=$(this).closest("a").attr("href");
				if(href.indexOf("<?=G5_URL?>") >= 0){
					$.post(href, { od_id: od_id },
						function(data) {
							$("#dvOrderinquiryPopup").html(data);
						}
					);
					return false;
				}*/
				break;
				case "교환요청":
				if(confirm("교환을 요청 하시겠습니까? 교환 시 사유에 따라 배송료가 발생 될 수 있습니다."))
				{
					var uid=$(this).attr("uid");
					location.href="<?=G5_SHOP_URL;?>/orderinquirychangeform.php?od_id="+od_id+"&act=change&uid="+uid;
				}
				break;
				case "교환철회":
				if(confirm("교환을 철회 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("교환철회");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "반품요청":
				if(confirm("반품을 요청 하시겠습니까? 반품 시 사유에 따라 배송료가 발생 될 수 있습니다."))
				{
					var uid=$(this).attr("uid");
					location.href="<?=G5_SHOP_URL;?>/orderinquiryreturnform.php?od_id="+od_id+"&act=return&uid="+uid;
				}
				break;
				case "반품철회":
				if(confirm("반품을 철회 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("반품철회");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "철회요청":
				if(confirm("철회를 요청 하시겠습니까? 철회 시 사유에 따라 배송료가 발생 될 수 있습니다."))
				{
					var uid=$(this).attr("uid");
					location.href="<?=G5_SHOP_URL;?>/orderinquiryreturnform.php?od_id="+od_id+"&act=return&uid="+uid;
				}
				break;
				case "철회취소":
				if(confirm("철회요청을 취소 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("철회취소");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "해지신청":
				$.post(
					"./orderinquiry.contractout.php",
					{ od_id: od_id, uid:uid },
					function(data) {
						$("#dvOrderinquiryPopup").html(data);
					}
					);
				break;
				case "해지취소":
				if(confirm("해지신청을 취소 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("해지취소");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "위약금납부":
				var uid=$(this).attr("uid");
				location.href="<?=G5_SHOP_URL;?>/orderform.out.php?od_id="+od_id+"&uid="+uid;
				break;
				case "위약금영수증":
				var uid=$(this).attr("uid");
				location.href="<?=G5_SHOP_URL;?>/orderform.out2.php?od_id="+od_id+"&uid="+uid;
				break;
				case "구매확정":
				if(confirm("구매확정 시 반품 및 교환이 불가합니다. 확정 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("구매확정");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "리뷰작성":
				var it_id = $(this).attr("it_id");
				var ct_id = $(this).attr("ct_id");
				$('#od_review_select').attr("it_id",it_id);
				$('#od_review_select').attr("ct_id",ct_id);
				$('#od_review_select').prop('hidden', false);

				//location.href="<?=G5_SHOP_URL;?>/itemuseform.php?it_id="+it_id;
				break;
				case "리뷰보기":
				var it_id = $(this).attr("it_id");
				location.href="<?=G5_SHOP_URL;?>/item.php?it_id="+it_id+"#review";
				break;
				case "계약취소":
				var uid=$(this).attr("uid");
				location.href="<?=G5_SHOP_URL;?>/orderinquirycancelform.php?od_id="+od_id+"&uid="+uid;
				break;
				case "리스시작하기":
				if(confirm("리스를 시작 하시겠습니까?"))
				{
					var uid=$(this).attr("uid");

					$("form[name=orderinquirychange_form] input[name=act]").val("리스시작하기");
					$("form[name=orderinquirychange_form] input[name=od_id]").val(od_id);
					$("form[name=orderinquirychange_form] input[name=uid]").val(uid);
					$("form[name=orderinquirychange_form]").submit();
				}
				break;
				case "수선비용결제":
				var uid=$(this).attr("uid");
				location.href="<?=G5_SHOP_URL;?>/orderform2.php?od_id="+od_id;
				break;
				case "계약서다운로드":
				url = "<?=G5_SHOP_URL;?>/orderinquiryview.rental.php?od_id="+od_id;
				window.open(url, "rentalpdf", "left=100,top=100,width=800,height=600,scrollbars=0");
				break;

			}
		});
});
</script>

<div id="dvOrderinquiryPopup"></div>
<?
include_once(G5_MSHOP_PATH.'/_tail.php');
?>
