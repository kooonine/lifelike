<?php
$sub_menu = '400400';
include_once('./_common.php');

$cart_title3 = '주문번호';
$cart_title4 = '배송완료';

auth_check($auth[substr($sub_menu,0,2)], "w");

$g5['title'] = "주문상세정보";
include_once(G5_ADMIN_PATH.'/admin.head.php');


// 완료된 주문에 포인트를 적립한다.
//save_order_point("구매완료");

//------------------------------------------------------------------------------
// 주문서 정보
//------------------------------------------------------------------------------
$sql = " select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
$od = sql_fetch($sql);
if (!$od['od_id']) {
    alert("해당 주문번호로 주문서가 존재하지 않습니다.");
}

//------------------------------------------------------------------------------
$qstr1 = "od_status=".urlencode($od_status)."&amp;od_settle_case=".urlencode($od_settle_case)."&amp;od_misu=$od_misu&amp;od_cancel_price=$od_cancel_price&amp;od_refund_price=$od_refund_price&amp;od_receipt_point=$od_receipt_point&amp;od_coupon=$od_coupon&amp;fr_date=$fr_date&amp;to_date=$to_date&amp;sel_field=$sel_field&amp;search=$search&amp;save_search=$search";
if($default['de_escrow_use'])
    $qstr1 .= "&amp;od_escrow=$od_escrow";

$qstr = "$qstr1&amp;sort1=$sort1&amp;sort2=$sort2&amp;page=$page&amp;od_type=".$od['od_type'];

// LG 현금영수증 JS
if($default['de_card_test']) {
echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
} else {
    echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
}

$disp_od_id = $od['od_type'].'-'.substr($od['od_id'],0,8).'-'.substr($od['od_id'],8);

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 상품주문정보 조회<small></small></h4>
        <div class="clearfix"></div>
    </div>

    <div class="local_desc02 local_desc">
        <p>
        	<label class="control-label col-md-3" >주문경로/유입경로 :  <strong><?php echo ($od['od_mobile'])?"MOBILE":"PC" ?></strong> </label>
        	<label class="control-label col-md-9" >주문번호 :  <strong><?php echo $disp_od_id?></strong> </label>
        	<div class="clearfix"></div>

        	<label class="control-label col-md-3" >현재 주문상태 :  <strong><?php echo $od['od_status'] ?></strong> </label>
        	<label class="control-label col-md-9" >주문일자 :  <strong><?php echo substr($od['od_time'],0,16); ?> (<?php echo get_yoil($od['od_time']); ?>)</strong> </label>
        	<br/>
    </div>


    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 주문 상품 목록<small></small></h4>
        <div class="clearfix"></div>
    </div>

	<div class="x_content">

    <div class="tbl_head01 tbl_wrap">
        <table>
        <thead>
            <tr>
                <th scope="col">세탁구분</th>
                <th scope="col">상품개별번호</th>
                <th scope="col">RFID</th>
                <th scope="col">상품명</th>
                <th scope="col">옵션항목</th>
                <?php if($od['od_type'] == "K"){?>
                <th scope="col">보관기간</th>
                <?php }?>
                <th scope="col">주문금액</th>
            </tr>
        </thead>
        	<?php
        	// 상품목록
        	$sql = " select *
                   from {$g5['g5_shop_cart_table']}
                  where od_id = '$od_id'
                  order by ct_id ";
        	$row = sql_fetch($sql);
        	// 상품이미지
        	$image = get_it_image($row['it_id'], 50, 50);
        	?>
            <tr>
                <td class="td_num"><?php echo ($row['ct_free_laundry_use'])?"무료":"유료"; ?></td>
                <td class="td_num"><?php echo $row['od_sub_id']; ?></td>
                <td class="td_num"><?php echo $row['rf_serial']; ?></td>
                <td class="td_itname" >
                    <a href="./itemform.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>" target="_blank"><?php echo $image; ?> <?php echo stripslashes($row['it_name']); ?></a>
                </td>
                <td class="td_itname">
                    <?php echo $row['ct_option']; ?>
                </td>
                <?php if($od['od_type'] == "K"){?>
                <td> <?php echo $row['ct_keep_month']?>개월</td>
                <?php }?>
                <td class="td_num"><?php echo number_format($od['od_receipt_price']); ?> 원</td>
            </tr>
            </tbody>
        </table>
    </div>

	</div>


    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 요청사항 <small></small></h4>
        <div class="clearfix"></div>
    </div>

	<div class="x_content">

        <div class="tbl_frm01 tbl_wrap">
            <table>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="">수거 요청일자</label></th>
                <td> <?php echo $od['od_hope_date']?></td>
            </tr>
            <tr>
                <th scope="row"><label for="">요청사항</label></th>
                <td> <?php echo nl2br($od['cust_memo']) ?></td>
            </tr>
            <tr>
                <td colspan="2">
                	<table>
                	<tr>
                    <?php
                    $cust_file = json_decode($od['cust_file'], true);
                    for ($i = 0; $i < count($cust_file); $i++) {

                        $imgL = G5_DATA_URL.'/file/order/'.$od['od_id'].'/'.$cust_file[$i]['file'];

                        if ( preg_match("/\.(mp4|mov|avi)$/i", $cust_file[$i]['file'])){
                            echo "<td><video controls width='250' height='250' style='vertical-align:top;' >
                                  	<source src='$imgL' type='video/mp4' width='250' height='250' >
                                    </video></td>";
                        } else {
                            echo '<td><img src="'.$imgL.'" width="250px"></td>';
                        }
                    }
                    ?>
                    </tr>
                    </table>
                </td>
            </tr>
          	</tbody>
          	</table>
    	</div>
	</div>


    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 주문상세정보 <small></small></h4>
        <div class="clearfix"></div>
    </div>

    <?php
    // 주문금액 = 상품구입금액 + 배송비 + 추가배송비
    $amount['order'] = $od['od_cart_price'] + $od['od_send_cost'] + $od['od_send_cost2'];

    // 입금액 = 결제금액 + 포인트
    $amount['receipt'] = $od['od_receipt_price'] + $od['od_receipt_point'];

    // 쿠폰금액
    $amount['coupon'] = $od['od_cart_coupon'] + $od['od_coupon'] + $od['od_send_coupon'];

    // 취소금액
    $amount['cancel'] = $od['od_cancel_price'];

    // 미수금 = 주문금액 - 취소금액 - 입금금액 - 쿠폰금액
    //$amount['미수'] = $amount['order'] - $amount['receipt'] - $amount['coupon'];

    // 결제방법
    $s_receipt_way = $od['od_settle_case'];

    if($od['od_settle_case'] == '간편결제') {
        switch($od['od_pg']) {
            case 'lg':
                $s_receipt_way = 'PAYNOW';
                break;
            case 'inicis':
                $s_receipt_way = 'KPAY';
                break;
            case 'kcp':
                $s_receipt_way = 'PAYCO';
                break;
            default:
                $s_receipt_way = $row['od_settle_case'];
                break;
        }
    }

    if ($od['od_receipt_point'] > 0)
        $s_receipt_way .= "+포인트";
    ?>

	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <tr>
            <th scope="row"><label>상품주문번호</label></th>
            <td colspan="3"><?php echo $disp_od_id; ?></td>
		</tr>
        <tr>
            <th scope="row"><label>주문상태</label></th>
            <td><strong><?php echo $od['od_status'] ?></strong></td>
            <th scope="row"><label>클레임상태</label></th>
            <td><strong><?php echo $od['od_status_claim'] ?></strong></td>
		</tr>
        <tr>
            <th scope="row"><label>주문자명(ID)</label></th>
            <td colspan="3"><?php echo get_sideview($od['mb_id'], get_text($od['od_name']), $od['od_email'], ''); ?>(<?php echo $od['mb_id'];?>)</td>
		</tr>
        <tr>
            <th scope="row"><label>주문금액</label></th>
            <td><?php echo display_price($amount['order']); ?></td>
            <th scope="row"><label>배송비</label></th>
            <td><?php echo display_price($od['od_send_cost'] + $od['od_send_cost2']); ?></td>
		</tr>
        <tr>
            <th scope="row"><label>결제금액</label></th>
            <td colspan="3"><?php echo number_format($amount['receipt']); ?>원</td>
		</tr>
		</tbody>
		</table>
	</div>
	</div>

    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 결제상세정보 <small></small></h4>
        <div class="clearfix"></div>
    </div>


	<div class="x_content">
            <div class="tbl_frm01 tbl_wrap">
                <table>
                <caption>결제상세정보</caption>
                <colgroup>
                    <col class="grid_3">
                    <col>
                </colgroup>
                <tbody>
                <?php if ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체') { ?>
                <?php if ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '가상계좌') { ?>
                <tr>
                    <th scope="row">계좌번호</th>
                    <td><?php echo get_text($od['od_bank_account']); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="row"><?php echo $od['od_settle_case']; ?> 입금액</th>
                    <td><?php echo display_price($od['od_receipt_price']); ?></td>
                </tr>
                <tr>
                    <th scope="row">입금자</th>
                    <td><?php echo get_text($od['od_deposit_name']); ?></td>
                </tr>
                <tr>
                    <th scope="row">입금확인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == 0) { ?>입금 확인일시를 체크해 주세요.
                        <?php } else { ?><?php echo $od['od_receipt_time']; ?> (<?php echo get_yoil($od['od_receipt_time']); ?>)
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '휴대전화') { ?>
                <tr>
                    <th scope="row">휴대전화번호</th>
                    <td><?php echo get_text($od['od_bank_account']); ?></td>
                    </tr>
                <tr>
                    <th scope="row"><?php echo $od['od_settle_case']; ?> 결제액</th>
                    <td><?php echo display_price($od['od_receipt_price']); ?></td>
                </tr>
                <tr>
                    <th scope="row">결제 확인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == 0) { ?>결제 확인일시를 체크해 주세요.
                        <?php } else { ?><?php echo $od['od_receipt_time']; ?> (<?php echo get_yoil($od['od_receipt_time']); ?>)
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '신용카드') { ?>
                <tr>
                    <th scope="row" class="sodr_sppay">신용카드 결제금액</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>0원
                        <?php } else { ?><?php echo display_price($od['od_receipt_price']); ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="sodr_sppay">카드 승인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>신용카드 결제 일시 정보가 없습니다.
                        <?php } else { ?><?php echo substr($od['od_receipt_time'], 0, 20); ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == 'KAKAOPAY') { ?>
                <tr>
                    <th scope="row" class="sodr_sppay">KAKOPAY 결제금액</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>0원
                        <?php } else { ?><?php echo display_price($od['od_receipt_price']); ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="sodr_sppay">KAKAOPAY 승인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>신용카드 결제 일시 정보가 없습니다.
                        <?php } else { ?><?php echo substr($od['od_receipt_time'], 0, 20); ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] == '간편결제' || ($od['od_pg'] == 'inicis' && is_inicis_order_pay($od['od_settle_case']) ) ) { ?>
                <tr>
                    <th scope="row" class="sodr_sppay"><?php echo $s_receipt_way; ?> 결제금액</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") {?>0원
                        <?php } else { ?><?php echo display_price($od['od_receipt_price']); ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="sodr_sppay"><?php echo $s_receipt_way; ?> 승인일시</th>
                    <td>
                        <?php if ($od['od_receipt_time'] == "0000-00-00 00:00:00") { echo $s_receipt_way; ?> 결제 일시 정보가 없습니다.
                        <?php } else { ?><?php echo substr($od['od_receipt_time'], 0, 20); ?>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if ($od['od_settle_case'] != '무통장') { ?>
                <tr>
                    <th scope="row">결제대행사 링크</th>
                    <td>
                        <?php
                        if ($od['od_settle_case'] != '무통장') {
                            switch($od['od_pg']) {
                                case 'lg':
                                    $pg_url  = 'http://pgweb.uplus.co.kr';
                                    $pg_test = 'LG유플러스';
                                    if ($default['de_card_test']) {
                                        $pg_url = 'http://pgweb.uplus.co.kr/tmert';
                                        $pg_test .= ' 테스트 ';
                                    }
                                    break;
                                case 'inicis':
                                    $pg_url  = 'https://iniweb.inicis.com/';
                                    $pg_test = 'KG이니시스';
                                    break;
                                case 'KAKAOPAY':
                                    $pg_url  = 'https://mms.cnspay.co.kr';
                                    $pg_test = 'KAKAOPAY';
                                    break;
                                default:
                                    $pg_url  = 'http://admin8.kcp.co.kr';
                                    $pg_test = 'KCP';
                                    if ($default['de_card_test']) {
                                        // 로그인 아이디 / 비번
                                        // 일반 : test1234 / test12345
                                        // 에스크로 : escrow / escrow913
                                        $pg_url = 'http://testadmin8.kcp.co.kr';
                                        $pg_test .= ' 테스트 ';
                                    }

                                }
                            echo "<a href=\"{$pg_url}\" target=\"_blank\">{$pg_test}바로가기</a><br>";
                        }
                        //------------------------------------------------------------------------------
                        ?>
                    </td>
                </tr>
                <?php } ?>

                <?php if($od['od_tax_flag']) { ?>
                <tr>
                    <th scope="row">과세공급가액</th>
                    <td><?php echo display_price($od['od_tax_mny']); ?></td>
                </tr>
                <tr>
                    <th scope="row">과세부가세액</th>
                    <td><?php echo display_price($od['od_vat_mny']); ?></td>
                </tr>
                <tr>
                    <th scope="row">비과세공급가액</th>
                    <td><?php echo display_price($od['od_free_mny']); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th scope="row">주문금액할인</th>
                    <td><?php echo display_price($od['od_coupon']); ?></td>
                </tr>
                <tr>
                    <th scope="row">포인트</th>
                    <td><?php echo display_point($od['od_receipt_point']); ?></td>
                </tr>
                <tr>
                    <th scope="row">결제취소/환불액</th>
                    <td><?php echo display_price($od['od_refund_price']); ?></td>
                </tr>
                <?php
                if ($od['od_misu'] == 0 && $od['od_receipt_price'] && ($od['od_settle_case'] == '무통장' || $od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체')) {
                ?>
                <tr>
                    <th scope="row">현금영수증</th>
                    <td>
                    <?php
                    if ($od['od_cash']) {
                        require G5_SHOP_PATH.'/settle_lg.inc.php';

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
                        <a href="javascript:;" onclick="<?php echo $cash_receipt_script; ?>" class="btn btn_02">현금영수증 확인</a>
                    <?php } else { ?>
                        <a href="javascript:;" onclick="window.open('<?php echo G5_SHOP_URL; ?>/taxsave.php?od_id=<?php echo $od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');" class="btn btn_02">현금영수증 발급</a>
                    <?php } ?>
                    </td>
                </tr>
                <?php
                }
                ?>
                </tbody>
                </table>
            </div>
		</div>
	<div class="clearfix"></div>

	<?php if($od['od_invoice']) { ?>
    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 배송 정보 <small></small></h4>
        <div class="clearfix"></div>
    </div>

	<div class="x_content">
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <caption>결제상세정보</caption>
            <colgroup>
                <col class="grid_3">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">택배정보</th>
                <td>
                	<?php echo $od['od_delivery_company']; ?> <?php echo get_delivery_inquiry($od['od_delivery_company'], $od['od_invoice'], 'btn btn_01'); ?>
                	<?php echo $od['od_invoice']; ?>
                </td>
            </tr>
		    <tr>
                <th scope="row">발송완료일</th>
                <td><?php echo is_null_time($od['od_invoice_time']) ? "" : $od['od_invoice_time']; ?></td>
            </tr>

            </tbody>
            </table>
        </div>
	</div>
	<div class="clearfix"></div>
	<?php } ?>

    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 주문자/배송지 정보 <small></small></h4>
        <div class="clearfix"></div>
    </div>


    <form name="frmorderform3" action="./orderformupdate.php" method="post">
    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    <input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
    <input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
    <input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
    <input type="hidden" name="search" value="<?php echo $search; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="mod_type" value="info">

    <div class="compare_wrap">

        <section id="anc_sodr_orderer" class="compare_left">
            <h5><span class="fa fa-check-square"></span> 주문하신 분 <small></small></h5>

            <div class="tbl_frm01">
                <table>
                <caption>주문자/배송지 정보</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label for="od_name"><span class="sound_only">주문하신 분 </span>이름</label></th>
                    <td><input type="text" name="od_name" value="<?php echo get_text($od['od_name']); ?>" id="od_name" required class="frm_input required"></td>
                </tr>
                <tr hidden>
                    <th scope="row"><label for="od_tel"><span class="sound_only">주문하신 분 </span>연락처</label></th>
                    <td><input type="text" name="od_tel" value="<?php echo get_text($od['od_tel']); ?>" id="od_tel" required class="frm_input required"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_hp"><span class="sound_only">주문하신 분 </span>핸드폰</label></th>
                    <td><input type="text" name="od_hp" value="<?php echo get_text($od['od_hp']); ?>" id="od_hp" class="frm_input"></td>
                </tr>
                <tr>
                    <th scope="row"><span class="sound_only">주문하시는 분 </span>주소</th>
                    <td>
                        <label for="od_zip" class="sound_only">우편번호</label>
                        <input type="text" name="od_zip" value="<?php echo $od['od_zip1'].$od['od_zip2']; ?>" id="od_zip" required class="frm_input required" size="5">
                        <button type="button" class="btn_frmline" onclick="win_zip('frmorderform3', 'od_zip', 'od_addr1', 'od_addr2', 'od_addr3', 'od_addr_jibeon');">주소 검색</button><br>
                        <span id="od_win_zip" style="display:block"></span>
                        <input type="text" name="od_addr1" value="<?php echo get_text($od['od_addr1']); ?>" id="od_addr1" required class="frm_input required" size="35">
                        <label for="od_addr1">기본주소</label><br>
                        <input type="text" name="od_addr2" value="<?php echo get_text($od['od_addr2']); ?>" id="od_addr2" class="frm_input" size="35">
                        <label for="od_addr2">상세주소</label>
                        <br>
                        <input type="text" name="od_addr3" value="<?php echo get_text($od['od_addr3']); ?>" id="od_addr3" class="frm_input" size="35">
                        <label for="od_addr3">참고항목</label>
                        <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($od['od_addr_jibeon']); ?>"><br>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_email"><span class="sound_only">주문하신 분 </span>E-mail</label></th>
                    <td><input type="text" name="od_email" value="<?php echo $od['od_email']; ?>" id="od_email" required class="frm_input email required" size="30"></td>
                </tr>
                <tr>
                    <th scope="row"><span class="sound_only">주문하신 분 </span>IP Address</th>
                    <td><?php echo $od['od_ip']; ?></td>
                </tr>
                </tbody>
                </table>
            </div>
        </section>

        <section id="anc_sodr_taker" class="compare_right">
            <h5><span class="fa fa-check-square"></span> 받으시는 분 <small></small></h5>

            <div class="tbl_frm01">
                <table>
                <caption>받으시는 분 정보</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row"><label for="od_b_name"><span class="sound_only">받으시는 분 </span>이름</label></th>
                    <td><input type="text" name="od_b_name" value="<?php echo get_text($od['od_b_name']); ?>" id="od_b_name" required class="frm_input required"></td>
                </tr>
                <tr hidden>
                    <th scope="row"><label for="od_b_tel"><span class="sound_only">받으시는 분 </span>연락처</label></th>
                    <td><input type="text" name="od_b_tel" value="<?php echo get_text($od['od_b_tel']); ?>" id="od_b_tel" required class="frm_input required"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="od_b_hp"><span class="sound_only">받으시는 분 </span>핸드폰</label></th>
                    <td><input type="text" name="od_b_hp" value="<?php echo get_text($od['od_b_hp']); ?>" id="od_b_hp" class="frm_input required"></td>
                </tr>
                <tr>
                    <th scope="row"><span class="sound_only">받으시는 분 </span>주소</th>
                    <td>
                        <label for="od_b_zip" class="sound_only">우편번호</label>
                        <input type="text" name="od_b_zip" value="<?php echo $od['od_b_zip1'].$od['od_b_zip2']; ?>" id="od_b_zip" required class="frm_input required" size="5">
                        <button type="button" class="btn_frmline" onclick="win_zip('frmorderform3', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소 검색</button><br>
                        <input type="text" name="od_b_addr1" value="<?php echo get_text($od['od_b_addr1']); ?>" id="od_b_addr1" required class="frm_input required" size="35">
                        <label for="od_b_addr1">기본주소</label>
                        <input type="text" name="od_b_addr2" value="<?php echo get_text($od['od_b_addr2']); ?>" id="od_b_addr2" class="frm_input" size="35">
                        <label for="od_b_addr2">상세주소</label>
                        <input type="text" name="od_b_addr3" value="<?php echo get_text($od['od_b_addr3']); ?>" id="od_b_addr3" class="frm_input" size="35">
                        <label for="od_b_addr3">참고항목</label>
                        <input type="hidden" name="od_b_addr_jibeon" value="<?php echo get_text($od['od_b_addr_jibeon']); ?>"><br>
                    </td>
                </tr>

                <?php if ($default['de_hope_date_use']) { ?>
                <tr>
                    <th scope="row"><label for="od_hope_date">희망배송일</label></th>
                    <td>
                        <input type="text" name="od_hope_date" value="<?php echo $od['od_hope_date']; ?>" id="od_hopedate" required class="frm_input required" maxlength="10" minlength="10"> (<?php echo get_yoil($od['od_hope_date']); ?>)
                    </td>
                </tr>
                <?php } ?>

                <tr>
                    <th scope="row">전달 메세지</th>
                    <td><?php if ($od['od_memo']) echo get_text($od['od_memo'], 1);else echo "없음";?></td>
                </tr>
                </tbody>
                </table>
            </div>
        </section>

    </div>

    <div class="btn_confirm01 btn_confirm text-right">
        <input type="submit" value="주문자/배송지 정보 수정" class="btn_submit btn ">
        <a href="./orderlist.php?<?php echo $qstr; ?>" class="btn">목록</a>
    </div>

    </form>
</section>

<div class="x_title">
    <h4><span class="fa fa-check-square"></span> 관리자 메모 <small></small></h4>
    <div class="clearfix"></div>
</div>

<section id="anc_sodr_memo">

    <form name="frmorderform2" action="./orderformupdate.php" method="post" id="sh_memo_form">
    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
    <input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
    <input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
    <input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
    <input type="hidden" name="search" value="<?php echo $search; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="sh_id" value="">
    <input type="hidden" name="mod_type" value="sh_memo_new">

    <div class="tbl_head01 tbl_wrap">
        <table>
        <thead>
        <tr>
            <th scope="col">작성일</th>
            <th scope="col">작성자</th>
            <th scope="col">중요</th>
            <th scope="col">상품명</th>
            <th scope="col">내용</th>
            <th scope="col">관리</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = " select *
                   from lt_shop_order_history
                  where od_id = '{$od['od_id']}'
                  order by sh_id ";

        $result = sql_query($sql);
        for($i=0; $row=sql_fetch_array($result); $i++) {

        ?>
        <tr>
        	<td><?php echo $row['sh_time'] ?></td>
        	<td><?php echo $row['sh_mb_name'].'('.$row['sh_mb_id'].')' ?></td>
        	<td class="is_important"><?php echo ($row['is_important'])?"중요":"-" ?></td>
        	<td class="it_name"><?php echo ($row['it_name'])?$row['it_name']:"-" ?></td>
        	<td class="td_itname"><?php
        	if($row['cancel_select'] != '') echo '['.$row['cancel_select'].'] ';

        	echo nl2br($row['sh_memo']);

        	if($row['sh_add_price'] != '') echo '<br/>추가비용 : '.number_format($row['sh_add_price']).' 원 ';
        	?></td>
        	<td><?php if($row['cancel_select'] == '') {?>
        		<input type="button" value="수정" class="btn" sh_id="<?php echo $row['sh_id']; ?>">
        		<input type="button" value="삭제" class="btn" sh_id="<?php echo $row['sh_id']; ?>">
        		<?php } ?>
        	</td>
        </tr>
<?php } ?>
        </tbody>
        </table>
     </div>

	<div class="x_content">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <tbody>
        <tr>
            <th scope="row"><label>중요표시</label></th>
            <td ><label><input type="checkbox" name="is_important" value="1" /> 중요</label></td>
		</tr>
        <tr>
            <th scope="row"><label>상품명</label></th>
            <td ><input type="text" name="it_name" value="" class="frm_input" size="35"></td>
		</tr>
        <tr>
            <th scope="row"><label>메모내용</label></th>
            <td ><textarea name="sh_memo" id="sh_memo" rows="8"></textarea></td>
		</tr>
    	</tbody>
    	</table>
	</div>
	</div>

    <div class="btn_confirm01 btn_confirm text-right">
        <input type="button" value="신규메모" class="btn" id="btnMemoNew" style="display: none">
        <input type="submit" value="메모 등록" class="btn_submit btn" id="btnMemoSubmit">
        <a href="./orderlist.php?<?php echo $qstr; ?>" class="btn">목록</a>
    </div>

    </form>
</section>


</div></div></div>

<script>
$(function() {
	$("#btnMemoNew").click(function() {

		$("input[name=sh_id]").val("");
		$("input[name=mod_type]").val("sh_memo_new");

		$("input[name=is_important]").prop("checked",false);
		$("input[name=it_name]").val("");
		$("#sh_memo").val("");

		$("#btnMemoNew").css("display","none");
	});


	$("#sh_memo_form .btn").click(function() {
		if($(this).val() == "수정"){
			$("input[name=sh_id]").val($(this).attr("sh_id"));
			$("input[name=mod_type]").val("sh_memo_modify");

			is_important = $(this).closest("tr").find(".is_important").text();
			it_name = $(this).closest("tr").find(".it_name").text();
			sh_memo = $(this).closest("tr").find(".td_itname").text();

			if(is_important == "중요") $("input[name=is_important]").prop("checked",true);
			$("input[name=it_name]").val(it_name);
			$("#sh_memo").val(sh_memo);

			$("#btnMemoNew").css("display","");

		} else if($(this).val() == "삭제" && confirm("삭제하시겠습니까?")){
			$("input[name=sh_id]").val($(this).attr("sh_id"));
			$("input[name=mod_type]").val("sh_memo_del");

			$("#btnMemoSubmit").click();
		}
	});

    // 전체 옵션선택
    $("#sit_select_all").click(function() {
        if($(this).is(":checked")) {
            $("input[name='it_sel[]']").attr("checked", true);
            $("input[name^=ct_chk]").attr("checked", true);
        } else {
            $("input[name='it_sel[]']").attr("checked", false);
            $("input[name^=ct_chk]").attr("checked", false);
        }
    });

    // 상품의 옵션선택
    $("input[name='it_sel[]']").click(function() {
        var cls = $(this).attr("id").replace("sit_", "sct_");
        var $chk = $("input[name^=ct_chk]."+cls);
        if($(this).is(":checked"))
            $chk.attr("checked", true);
        else
            $chk.attr("checked", false);
    });

    // 개인결제추가
    $("#personalpay_add").on("click", function() {
        var href = this.href;
        window.open(href, "personalpaywin", "left=100, top=100, width=700, height=560, scrollbars=yes");
        return false;
    });

    // 부분취소창
    $("#orderpartcancel").on("click", function() {
        var href = this.href;
        window.open(href, "partcancelwin", "left=100, top=100, width=600, height=350, scrollbars=yes");
        return false;
    });
});

function form_submit(f)
{
    var check = false;
    var status = document.pressed;

    for (i=0; i<f.chk_cnt.value; i++) {
        if (document.getElementById('ct_chk_'+i).checked == true)
            check = true;
    }

    if (check == false) {
        alert("처리할 자료를 하나 이상 선택해 주십시오.");
        return false;
    }

    var msg = "";

    <?php if($od['od_settle_case'] == '신용카드' || $od['od_settle_case'] == 'KAKAOPAY' || $od['od_settle_case'] == '간편결제' || ($od['od_pg'] == 'inicis' && is_inicis_order_pay($od['od_settle_case']) )) { ?>
    if(status == "취소" || status == "반품" || status == "품절") {
        var $ct_chk = $("input[name^=ct_chk]");
        var chk_cnt = $ct_chk.size();
        var chked_cnt = $ct_chk.filter(":checked").size();
        <?php if($od['od_pg'] == 'KAKAOPAY') { ?>
        var cancel_pg = "카카오페이";
        <?php } else { ?>
        var cancel_pg = "PG사의 <?php echo $od['od_settle_case']; ?>";
        <?php } ?>

        if(chk_cnt == chked_cnt) {
            if(confirm(cancel_pg+" 결제를 함께 취소하시겠습니까?\n\n한번 취소한 결제는 다시 복구할 수 없습니다.")) {
                f.pg_cancel.value = 1;
                msg = cancel_pg+" 결제 취소와 함께 ";
            } else {
                f.pg_cancel.value = 0;
                msg = "";
            }
        }
    }
    <?php } ?>

    if (confirm(msg+"\'" + status + "\' 상태를 선택하셨습니다.\n\n선택하신대로 처리하시겠습니까?")) {
        return true;
    } else {
        return false;
    }
}

function del_confirm()
{
    if(confirm("주문서를 삭제하시겠습니까?")) {
        return true;
    } else {
        return false;
    }
}

// 기본 배송회사로 설정
function chk_delivery_company()
{
    var chk = document.getElementById("od_delivery_chk");
    var company = document.getElementById("od_delivery_company");
    company.value = chk.checked ? chk.value : company.defaultValue;
}

// 현재 시간으로 배송일시 설정
function chk_invoice_time()
{
    var chk = document.getElementById("od_invoice_chk");
    var time = document.getElementById("od_invoice_time");
    time.value = chk.checked ? chk.value : time.defaultValue;
}

// 결제금액 수동 설정
function chk_receipt_price()
{
    var chk = document.getElementById("od_receipt_chk");
    var price = document.getElementById("od_receipt_price");
    price.value = chk.checked ? (parseInt(chk.value) + parseInt(price.defaultValue)) : price.defaultValue;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
