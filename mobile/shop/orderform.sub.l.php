<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_MSHOP_PATH.'/settle_lg3.inc.php');

$tablet_size = "1.0"; // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0)

// 개인결제번호제거
set_session('ss_personalpay_id', '');
set_session('ss_personalpay_hash', '');
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span class="blind">세탁 서비스 신청</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<?php
// $s_cart_id 로 현재 장바구니 자료 쿼리
$sql = "select  b.ct_id, a.it_id, a.it_name, a.ct_option, a.rf_serial, a.od_type
                , a.ct_price, a.io_price, a.ct_time
                , a.ct_rental_price, a.ct_item_rental_month
                , a.ct_free_laundry_use, a.ct_free_laundry, a.ct_free_laundry_delivery_price
                , a.ct_laundry_use, a.ct_laundry_price, a.ct_laundry_delivery_price
                , a.ct_laundrykeep_use, a.ct_laundrykeep_lprice, a.ct_laundrykeep_kprice, a.ct_laundrykeep_delivery_price
                , a.ct_repair_use, a.ct_repair_price, a.ct_repair_delivery_price
                , b.buy_ct_id, b.buy_od_sub_id
        from    lt_shop_order_item a
                inner join lt_shop_cart b
                  on a.ct_id = b.buy_ct_id and a.od_sub_id = b.buy_od_sub_id
        where   b.od_id = '$s_cart_id'
          and   b.ct_select = '1' ";

$row = sql_fetch($sql);
$good_info = '';
$comm_tax_mny = 0; // 과세금액
$comm_vat_mny = 0; // 부가세
$comm_free_mny = 0; // 면세금액
$tot_tax_mny = 0;

$image_width = 100;
$image_height = 100;
$image = get_it_image($row['it_id'], $image_width, $image_height);
$it_name = stripslashes($row['it_name']);

$laundry_price = 0; //세탁비
$send_cost = 0; //배송비
$keep_price = 0; //1개월 보관비
$keep_month = 6; // 최소 보관 개월 수
$goods = $goods_it_id = "";

$ct_free_laundry_YN = 0; //무료세탁여부

$ct_free_laundry = $row['ct_free_laundry']; //무료세탁횟수
$ct_free_laundry_use = $row['ct_free_laundry_use']; //무료세탁 사용횟수
$ct_free_laundry_delivery_price = $row['ct_free_laundry_delivery_price']; //무료세탁일 경우 배송비
$ct_free_laundry_count = $ct_free_laundry-$ct_free_laundry_use; //무료세탁 남은 횟수 (무료세탁 먼저 사용)

$sell_price = 0;
if($od_type == "L")
{
    //세탁
    $laundry_price = $row['ct_laundry_price'];  //유료세탁비
    $org_send_cost =  $row['ct_laundry_delivery_price']; //유료세탁배송비
    $send_cost =  $row['ct_laundry_delivery_price']; //유료세탁배송비
    $title = "세탁신청";

    $sell_price = $laundry_price;
    if($ct_free_laundry_count > 0) {
        $ct_free_laundry_YN = 1; //무료세탁여부
        $sell_price = 0; //무료세탁일 경우 세탁비 없음.
        $send_cost = 0; //무료세탁일 경우 배송비도 없음.
        //$send_cost = $ct_free_laundry_delivery_price; //무료세탁 배송비로 설정
        
    }

} elseif($od_type == "K") {
    //세탁보관
    $laundry_price = $row['ct_laundrykeep_lprice']; //유료세탁비
    $org_send_cost =  $row['ct_laundrykeep_delivery_price']; //유료세탁배송비
    $send_cost =  $row['ct_laundrykeep_delivery_price']; //배송비

    $title = "세탁 보관 신청";
    $sell_price = $laundry_price;
    if($ct_free_laundry_count > 0) {
        $ct_free_laundry_YN = 1; //무료세탁여부
        $sell_price = 0; //무료세탁일 경우 세탁비 없음.
        $send_cost = 0; //무료세탁일 경우 배송비도 없음.
        //$send_cost = $ct_free_laundry_delivery_price; //무료세탁보관일 경우 세탁보관의 배송비로 설정
    }

    $keep_price = $row['ct_laundrykeep_kprice']; //보관비
    $sell_price = $sell_price + ($keep_price * $keep_month);
}


//배송비 제외
$send_cost = 0;

$tot_sell_price = $sell_price;
$tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비

$goods = preg_replace("/\?|\'|\"|\||\,|\&|\;/", "", $row['it_name']).' '.$title;
$goods_it_id = $row['it_id'];
?>

<?php
// 결제대행사별 코드 include (결제등록 필드)
require_once(G5_MSHOP_PATH.'/lg3/orderform.1.php');
?>
<div class="content shop sub">
	<form name="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">

	<!-- 컨텐츠 시작 -->
	<div class="grid">
		<div class="title_bar ">
			<h3 class="g_title_01">제품 정보</span></h3>
			<a href="<?php echo G5_SHOP_URL ?>/care.php"><button type="button" class="btn green_line round floatR"><span class="point">제품 변경</span></button></a>
		</div>
        <div class="order_cont">
            <div class="body">
                <div class="cont">
                    <div class="photo"><?php echo $image; ?></div>
                    <div class="info">
                        <strong><?php echo $it_name; ?></strong>
                        <p>옵션 : <?php echo $row['ct_option']; ?></p>
                        <p>구매일 : <?php echo substr($row['ct_time'], 0, 10)?></p>

            			<?php if($od_type == "K") { ?>
						<p>보관기간 :
							<span class="sel_box inline">
								<select name="ct_keep_month" id="ct_keep_month">
								<?php for ($i = 6; $i <= 36; $i++) {
								    echo '<option value="'.$i.'">'.$i.'개월</option>';
								} ?>
								</select>
							</span>
						</p>
						<?php } else { ?>
    						<input type="hidden" name="ct_keep_month" value="0">
						<?php } ?>

                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="it_id[]"    value="<?php echo $row['it_id']; ?>">
        <input type="hidden" name="it_name[]"  value="<?php echo get_text($row['it_name']); ?>">
        <input type="hidden" name="it_price[]" value="<?php echo $sell_price; ?>">
        <input type="hidden" name="cp_id[]" value="">
        <input type="hidden" name="cp_price[]" value="0">

        <input type="hidden" name="od_price"    value="<?php echo $tot_sell_price; ?>">
        <input type="hidden" name="org_od_price"    value="<?php echo $tot_sell_price; ?>">
        <input type="hidden" name="od_send_cost" value="<?php echo $send_cost; ?>">
        <input type="hidden" name="od_send_cost2" value="0">
        <input type="hidden" name="item_coupon" value="0">
        <input type="hidden" name="od_coupon" value="0">
        <input type="hidden" name="od_send_coupon" value="0">
        <input type="hidden" name="od_goods_name" value="<?php echo $goods; ?>">
        <input type="hidden" name="od_type" value="<?php echo $od_type?>" />
        <input type="hidden" name="laundry_price" value="<?php echo $laundry_price?>" />
        <input type="hidden" name="keep_price" value="<?php echo $keep_price?>" />
        <input type="hidden" name="ct_free_laundry_YN" value="<?php echo $ct_free_laundry_YN?>">
        <input type="hidden" name="ct_free_laundry_delivery_price" value="<?php echo $ct_free_laundry_delivery_price?>">
    </div>

	<div class="grid">
		<div class="title_bar ">
			<h3 class="g_title_01">신청 제품 확인</h3>
		</div>
		<div class="border_box order_list mt20 reverse">
			<ul>
				<li>
					<span class="item">* <?php echo $row['ct_option']; ?> * 1개 세탁</span>
					<strong class="result"><?php echo number_format($laundry_price)?> 원</strong>
				</li>
                <?php if($od_type == "K") { ?>
                <li>
                    <span class="item">* <?php echo $row['ct_option']; ?> * <em id="keep_month1"><?php echo $keep_month ?></em>개월 보관</span>
                    <strong class="result"><?php echo number_format($keep_price)?> 원 X <em id="keep_month2"><?php echo $keep_month ?></em>개월</strong>
                </li>
                <?php }?>
				<li>
					<span class="item blind"></span>
					<strong class="result bold point" id="ct_tot_price"><?php echo number_format($tot_sell_price)?> 원</strong>
				</li>
			</ul>
		</div>
	</div>
	<?php if($ct_free_laundry_count > 0) { ?>
	<!-- 무료 세탁권 있을경우 -->
	<div class="grid">
		<div class="title_bar ">
			<h3 class="g_title_01">무료 세탁권(잔여 <?php echo $ct_free_laundry_count?>회)</h3>
			<span class="chk radio floatR">
				<input type="checkbox" id="chkFree" name="chkFree" value="1" checked="checked" readonly="readonly" disabled="disabled">
				<label for="chkFree">사용함</label>
			</span>
		</div>
		<div class="order_title reverse">
			<span class="item">무료 세탁 1회 (*)</span>
			<strong class="result">
				<span class="sm">만료일 : <?php
				$enddate = date_add(date_create($row['ct_time']), date_interval_create_from_date_string("1 years"));
				echo date_format($enddate,"Y-m-d")?></span>
			</strong>
		</div>
		<div class="border_box order_list reverse">
			<ul>
				<li>
					<span class="item">*무료 세탁 (*)</span>
					<strong class="result">-<?php echo number_format($laundry_price)?> 원</strong>
				</li>
				<li>
					<span class="item blind"></span>
					<strong class="result bold point"><?php echo number_format($free_laundry_price)?> 원</strong>
				</li>
			</ul>
		</div>
	</div>
	<!-- //무료 세탁권 있을경우 -->
	<?php } ?>

	<div class="grid">
    	<div class="title_bar">
    		<h3 class="g_title_01">신청서</h3>
    	</div>
    	<!-- <div class="inp_wrap">
    		<div class="title count3"><label for="od_hope_date">수거 일자 선택</label></div>
    		<div class="inp_ele count6">
    			<div class="input calendar">
    				<input type="date" placeholder="" id="od_hope_date" name="od_hope_date">
    			</div>
    		</div>
    	</div> -->
    	<div class="inp_wrap">
    		<div class="title count9"><label for="f_01">세탁 접수 사항</label></div>
    		<div class="inp_ele count9">
    			<div class="input"><textarea name="cust_memo" id="cust_memo" rows="6" cols="20" maxlength="200" placeholder="접수할 세탁물에 대한 상세한 정보 입력"></textarea></div>
    		</div>
    	</div>
    	<div class="inp_wrap">
    		<p class="ico_import red point_red floatL">세탁 접수 사항은 오염 위치와 주의해야 할 부분을 자세히 입력해 주셔야 합니다.</p>
    		<span class="byte"><span id="byte">0</span>/200</span>
    	</div>

    	<div class="btn_comm review">
    		<h4 class="blind">첨부하기</h4>
    		<ul class="count3 alignC">
    			<li>
    				<span class="btn_file">
    					<button type="button" class="photo" id="btnFile1" accept="image/*">사진촬영</button>
    				</span>
    			</li>
    			<li>
    				<span class="btn_file">
    					<button type="button" class="album" id="btnFile2" accept="image/*">앨범선택</button>
    				</span>
    			</li>
    			<li>
    				<span class="btn_file">
    					<button type="button" class="video" id="btnFile3" accept="video/*">동영상</button>
    				</span>
    			</li>
    		</ul>
    	</div>
    	<p class="ico_import red point_red">첨부파일 최대 5개, 동영상의 경우 20mb 이하의 파일만 첨부 가능합니다.</p>

    	<div class="file_list">
    		<div class="swiper-container">
    			<ul class="swiper-wrapper" id="file_list">
    			</ul>
    		</div>
			<?php for ($i=0; $i<5; $i++) { ?>
            <input type="file" id="bf_file<?php echo $i+1 ?>" name="bf_file[]" hidden idx="<?php echo $i+1 ?>" act="">
            <?php } ?>
    		<script>
    			var swiperColumn_three = new Swiper('.file_list .swiper-container', {
    			  slidesPerView: 'auto',
    			  spaceBetween: 10,
    			 //loop: true,
    			});
    	  </script>
    	</div>
        <div class="file_list text">
            <ul id="file_list2">
            </ul>
        </div>

    	<div class="info_box">
    		<p class="ico_import red point_red">안내사항</p>
            <div class="list">
                <ul class="hyphen">
                    <li>침구에 묻은 선명한 오염 외에도 투명해서 잘 보이지 않는 오염 모두 접수증에 작성해 주세요.</li>
                    <li>오염의 형태와 고착화 정도에 따라 오염 제거에 맞는 세탁의 방식이 달라질 수 있습니다.</li>
                    <li>오염 부분을 제거하기 위해 직접 약품 처리를 하신 경우에는 세탁 진행이 거부될 수 있습니다.</li>
                </ul>
            </div>
    	</div>
    </div>

     <div class="grid bg_none">
        <div class="title_bar">
            <h3 class="g_title_01">고객 정보</h3>
        </div>

        <div class="order_list border_box">
            <ul>
                <li>
                    <span class="item">이름</span>
                    <strong class="result"><div class="input">
                    <input type="text" name="od_name" value="<?php echo get_text($member['mb_name']); ?>" id="od_name" required class="frm_input required" maxlength="20">
                    </div></strong>

                    <input type="hidden" name="od_tel" value="<?php echo get_text($member['mb_tel']); ?>" id="od_tel" >
                    <input type="hidden" name="od_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" >
                    <input type="hidden" name="od_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="od_addr1" >
                    <input type="hidden" name="od_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="od_addr2" >
					<input type="hidden" name="od_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="od_addr3" >
                    <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
                </li>

                <li>
                    <span class="item">휴대전화 번호</span>
                    <strong class="result"><div class="input">
                    <input type="text" name="od_hp" value="<?php echo get_text($member['mb_hp']); ?>" id="od_hp" required class="frm_input" maxlength="20" placeholder="휴대전화 번호 입력">
                    </div></strong>
                </li>
				<li>
                    <span class="item">E-mail</span>
					<strong class="result"><div class="input">
                    <input type="email" name="od_email" value="<?php echo $member['mb_email']; ?>" id="od_email" required class="frm_input required" maxlength="100">
                    </div></strong>
                </li>
            </ul>
        </div>
    </div>

	<div class="grid bg_none">
        <div class="title_bar">
            <h3 class="g_title_01">수거지 정보</h3>
			<span class="chk radio floatR">
            	<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">
            	<label for="ad_sel_addr_same">주문자 정보와 동일</label>
            </span>
        </div>

        <?php
        if($is_member) {
            // 배송지 이력
            $sep = chr(30);

            // 기본배송지
            $sql = " select *
                        from {$g5['g5_shop_order_address_table']}
                        where mb_id = '{$member['mb_id']}'
                          and ad_default = '1' ";
            $row = sql_fetch($sql);
            /*if($row['ad_id']) {
                $val1 = $row['ad_name'].$sep.$row['ad_tel'].$sep.$row['ad_hp'].$sep.$row['ad_zip1'].$sep.$row['ad_zip2'].$sep.$row['ad_addr1'].$sep.$row['ad_addr2'].$sep.$row['ad_addr3'].$sep.$row['ad_jibeon'].$sep.$row['ad_subject'];

                $addr_list .= '<span class="chk radio"><input type="radio" name="ad_sel_addr" value="'.get_text($val1).'" id="ad_sel_addr_def">'.PHP_EOL;
                $addr_list .= '<label for="ad_sel_addr_def">기본배송지</label></span>'.PHP_EOL;
            }*/

            //$addr_list .='<a href="'.G5_SHOP_URL.'/orderaddress.php" id="order_address">배송지목록</a>';

            if(!$row['ad_id']) {
		      $od_b_name = get_text($member['mb_name']);
		      $od_b_hp = get_text($member['mb_hp']);
		      $od_b_tel = get_text($member['mb_tel']);
		      $od_b_zip = $member['mb_zip1'].$member['mb_zip2'];
		      $od_b_addr1 = get_text($member['mb_addr1']);
		      $od_b_addr2 = get_text($member['mb_addr2']);
		      $od_b_addr3 = get_text($member['mb_addr3']);
		      $od_b_addr_jibeon = get_text($member['mb_addr_jibeon']);

            } else {
                $od_b_name = get_text($row['ad_name']);
                $od_b_hp = $row['ad_hp'];
                $od_b_tel = $row['ad_tel'];
                $od_b_zip = $row['ad_zip1'].$row['ad_zip2'];
                $od_b_addr1 = $row['ad_addr1'];
                $od_b_addr2 = $row['ad_addr2'];
                $od_b_addr3 = $row['ad_addr3'];
                $od_b_addr_jibeon = $row['ad_jibeon'];
            }
        ?>

        <div class="order_list">
            <ul>
                <li>
					<span class="item">제품 수거지 주소</span>
                    <strong class="result">
                        <span id="spn_ad_subject"><?php echo get_text($row['ad_subject']); ?></span>
                        <a href="<?php echo G5_SHOP_URL ?>/orderaddress.php" id="order_address"><button class="btn gray_line small"><span>배송지 관리</span></button></a>
                        <span class="addr" id="addr">
                        <?php echo '['.$od_b_zip.']'.$od_b_addr1.' '.$od_b_addr2 ?>
                        </span>
	                    <input type="hidden" name="ad_subject" id="ad_subject" value="<?php echo get_text($row['ad_subject']) ?>">
	                    <input type="hidden" name="od_b_name" id="od_b_name" value="<?php echo $od_b_name?>">
	                    <input type="hidden" name="od_b_hp" id="od_b_hp" value="<?php echo $od_b_hp?>">
	                    <input type="hidden" name="od_b_tel" id="od_b_tel" value="<?php echo $od_b_tel?>">
	                    <input type="hidden" name="od_b_zip" id="od_b_zip" value="<?php echo $od_b_zip?>">
	                    <input type="hidden" name="od_b_addr1" id="od_b_addr1" value="<?php echo $od_b_addr1?>">
	                    <input type="hidden" name="od_b_addr2" id="od_b_addr2" value="<?php echo $od_b_addr2?>">
	                    <input type="hidden" name="od_b_addr3" id="od_b_addr3" value="<?php echo $od_b_addr3?>">
	                    <input type="hidden" name="od_b_addr_jibeon" id="od_b_addr_jibeon" value="<?php echo $od_b_addr_jibeon?>">
	              </strong>
                </li>
                <li>
					<span class="item">받는분</span>
                    <strong class="result" id="spn_od_b_name"><?php echo $od_b_name; ?></strong>
                </li>
                <li>
                    <span class="item">연락처</span>
                    <strong class="result" id="spn_od_b_tel"><?php echo $od_b_tel; ?></strong>
                </li>
                <li>
                    <span class="item">휴대전화 번호</span>
                    <strong class="result" id="spn_od_b_hp"><?php echo $od_b_hp; ?></strong>
                </li>
                <li>
                    <span class="item">배송 메시지</span>
                    <strong class="result">
                        <div class="input mt10"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="" maxlength="20"></div>
                    </strong>
                </li>
            </ul>
        </div>
        <?php
        } else {
        ?>
		<div class="border_box">
			<div class="inp_wrap">
				<div class="title count3"><label>받는분</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20"></div>
				</div>
                    <input type="hidden" name="ad_subject" id="ad_subject" value="">
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label>휴대전화 번호</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input" maxlength="20"></div>
				</div>
			</div>
			<div class="inp_wrap">
				<div class="title count3"><label>연락처</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input required" maxlength="20"></div>
				</div>
			</div>
            <div class="inp_wrap">
				<div class="title count3"><label>주소</label></div>
                <div class="inp_ele count6 r_btn_100">
                    <div class="input"><input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required" size="5" maxlength="6"></div>
                    <button type="button" class="btn small green" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소검색</button>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="inp_ele count6 col_r">
                    <div class="input"><input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required"></div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="inp_ele count6 col_r">
                    <div class="input"><input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address">
                    <input type="hidden" name="od_b_addr3" id="od_b_addr3" >
                    <input type="hidden" name="od_b_addr_jibeon" value=""></div>
                </div>
            </div>
			<div class="inp_wrap">
				<div class="title count3"><label>배송 메시지</label></div>
				<div class="inp_ele count6">
                    <div class="input"><input type="text" placeholder="한글 20자 이내 입력" name="od_memo" id="od_memo" title="배송요청 내용" value="" maxlength="20"></div>
				</div>
			</div>
        </div>
		<?php } ?>

    </div>

    <?php
    $oc_cnt = $sc_cnt = 0;
    if($tot_price > 0) {
    ?>

	<div class="grid bg_none">
        <div class="title_bar">
            <h3 class="g_title_01">결제 수단 선택</h3>
        </div>
        <?php
        if (!$default['de_card_point'])
            echo '<p id="sod_frm_pt_alert"><strong>무통장입금</strong> 이외의 결제 수단으로 결제하시는 경우 적립금를 적립해드리지 않습니다.</p>';

        $multi_settle = 0;
        $checked = '';

        $escrow_title = "";
        if ($default['de_escrow_use']) {
            $escrow_title = "에스크로 ";
        }

        if ($is_kakaopay_use || $default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay()) {
            echo '<div class="order_list button_choice"><ul class="onoff">';
        }

        // 신용카드 사용
        if ($default['de_card_use']) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_card").click();\'>신용카드</a></li><input type="radio" id="od_settle_card" name="od_settle_case" hidden value="신용카드" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // 카카오페이
        if($is_kakaopay_use) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_kakaopay").click();\'>카카오페이</a></li><input type="radio" id="od_settle_kakaopay" name="od_settle_case" hidden value="KAKAOPAY" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // 가상계좌 사용
        if ($default['de_vbank_use']) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_vbank").click();\'>'.$escrow_title.'가상계좌</a></li><input type="radio" id="od_settle_vbank" name="od_settle_case" hidden value="가상계좌" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // 계좌이체 사용
        if ($default['de_iche_use']) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_iche").click();\'>'.$escrow_title.'계좌이체</a></li><input type="radio" id="od_settle_iche" name="od_settle_case" hidden value="계좌이체" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // 휴대전화 사용
        if ($default['de_hp_use']) {
            $multi_settle++;
            echo '<li><a onclick=\'$("#od_settle_hp").click();\'>'.$escrow_title.'휴대전화</a></li><input type="radio" id="od_settle_hp" name="od_settle_case" hidden value="휴대전화" '.$checked.'>'.PHP_EOL;
            $checked = '';
        }

        // PG 간편결제
        if($default['de_easy_pay_use']) {
            switch($default['de_pg_service']) {
                case 'lg':
                    $pg_easy_pay_name = 'PAYNOW';
                    break;
                case 'inicis':
                    $pg_easy_pay_name = 'KPAY';
                    break;
                default:
                    $pg_easy_pay_name = 'PAYCO';
                    break;
            }

            $multi_settle++;
            echo '<li><input type="radio" id="od_settle_easy_pay" name="od_settle_case" value="간편결제" '.$checked.'> <label for="od_settle_easy_pay" class="'.$pg_easy_pay_name.' lb_icon">'.$pg_easy_pay_name.'</label></li>'.PHP_EOL;
            $checked = '';
        }

        //이니시스 삼성페이
        if($default['de_samsung_pay_use']) {
            echo '<li><input type="radio" id="od_settle_samsungpay" data-case="samsungpay" name="od_settle_case" value="삼성페이" '.$checked.'> <label for="od_settle_samsungpay" class="samsung_pay lb_icon">삼성페이</label></li>'.PHP_EOL;
            $checked = '';
        }

        //이니시스 Lpay
        if($default['de_inicis_lpay_use']) {
            echo '<li><input type="radio" id="od_settle_inicislpay" data-case="lpay" name="od_settle_case" value="lpay" '.$checked.'> <label for="od_settle_inicislpay" class="inicis_lpay">L.pay</label></li>'.PHP_EOL;
            $checked = '';
        }

        echo '</ul>';


        if ($default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || is_inicis_simple_pay() ) {
            echo '</div>';
        }

        if ($multi_settle == 0)
            echo '<p>결제할 방법이 없습니다.<br>운영자에게 알려주시면 감사하겠습니다.</p>';
    ?>

        <div class="order_list">
            <div class="inp_wrap">
                <span class="chk check">
                    <input type="checkbox" id="chk_user_privacy" required="required">
                    <label for="chk_user_privacy">개인정보 수집 · 이용 동의<span>(필수)</span></label>
                </span>
                <a href="<?php echo G5_MOBILE_URL?>/common/terms_agreement.php?id=chk_user_privacy&type=user_privacy&title=<?php echo urlencode("개인정보 수집 • 이용 동의")?>" class="btn floatR arrow_r_green point" target="_blank">전문보기</a>
            </div>
        </div>
    <?php } ?>
    </div>

	<div class="grid">

		<div class="order_list">

			<div class="line_box tbl">
				<span class="item cell">총 결제 금액<br>(배송료 포함)</span>
				<strong class="result cell"><em id="od_tot_price" class="point"><?php echo number_format($tot_price); ?></em> 원</strong>
			</div>

        </div>

        <?php
        // 결제대행사별 코드 include (결제대행사 정보 필드 및 주분버튼)
        require_once(G5_MSHOP_PATH.'/lg3/orderform.2.php');
        ?>
	</div>

    <div id="show_progress" style="display:none;">
        <img src="<?php echo G5_MOBILE_URL; ?>/shop/img/loading.gif" alt="">
        <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
    </div>
    </form>

    <?php
    if ($default['de_escrow_use']) {
        // 결제대행사별 코드 include (에스크로 안내)
        require_once(G5_MSHOP_PATH.'/lg3/orderform.3.php');
    }
    ?>

</div>

<script src="<?php echo G5_JS_URL?>/shop.order.js"></script>
<script>
var zipcode = "";

$(function() {
	$("textarea[name='cust_memo']").keyup(function() {
		var str = $(this).val();
		var mx = parseInt($(this).attr("maxlength"));
		document.getElementById('byte').innerHTML = str.length;
		
		if (str.length > mx) {
			$(this).val(str.substr(0, mx));
			return false;
		}
	});

	var fileCount = <?php echo $i?>;

    $("#btnFile1,#btnFile2,#btnFile3").click(function() {
		var accept = $(this).attr("accept");

    	var nextID = "";
    	var notadd = true;

    	for (var i = 1; i <= 5; i++) {
			var $bf_file = $("#bf_file"+i);
			var act = $bf_file.attr("act");

            if($bf_file.val() == "" && act != "in") {
            	$bf_file.attr("accept", accept);
            	$bf_file.click();
            	notadd = false;
            	break;
            }
		}
		if(notadd) alert("최대 사진5매, 동영상 20mb 이하의 파일만 첨부 가능합니다.");
    });

    $("input[name='bf_file[]']").change(function() {

        var idx = $(this).attr("idx");
		var isVideo = ($(this).attr("accept") == "video/*");


    	var fileName = "";
    	if(window.FileReader){
    		fileName = $(this)[0].files[0].name;
    	} else {
    		fileName = $(this)[0].val().split('/').pop().split('\\').pop();
    	}
    	
        if (fileName != "") {
            if(isVideo) {
        		var html = '';
        		html += '<li>';
        		html += '<span>'+fileName+'</span>';
        		html += '<button type="button" class="btn_delete" id="file_delete" idx="'+idx+'">';
        		html += '<span class="blind">삭제</span>';
        		html += '</button>';
        		html += '</li>';
        		
                if($("#file_list2 li").size() > 0) {
        			$('#file_list2 li:last').after(html);
                } else {
                	$('#file_list2').html(html);
                }
                fileCount++;
                $(this).attr("act", "in");
            } else {

        		imgID = "fileImg"+idx;
        		
        		var html = '';
        		html += '<li class="swiper-slide" style="margin-right: 10px;">';
        		html += '<button type="button" class="btn_delete" id="file_delete" idx="'+idx+'">';
        		html += '<span class="blind">삭제</span>';
        		html += '</button>';
        		html += '<img src="" id="'+imgID+'">';
        		html += '</li>';
        		
                if($("#file_list li").size() > 0) {
        			$('#file_list li:last').after(html);
                } else {
                	$('#file_list ').html(html);
                }
        		
        		if(fileName != "") {
        			var reader = new FileReader();
        			reader.onload = function (e) {
        				$("#"+imgID).attr("src", e.target.result);
        			}
        			reader.readAsDataURL($(this)[0].files[0]);
        		}
        		fileCount++;
        		$(this).attr("act", "in");
            }

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
	
    var $cp_btn_el;
    var $cp_row_el;

    $("#od_b_addr2").focus(function() {
        var zip = $("#od_b_zip").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);

        if(zipcode == code)
            return false;

        zipcode = code;
        calculate_sendcost(code);
    });

    $("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay,#od_settle_samsungpay").bind("click", function() {
        $("#settle_bank").hide();
        $("#show_req_btn").css("display", "block");
        $("#show_pay_btn").css("display", "none");

        $("input[name='submitChecked']").val($("#od_tot_price").text()+"원 결제");
    });

    // 배송지선택
    $("input[name=ad_sel_addr]").on("click", function() {
        var addr = $(this).val().split(String.fromCharCode(30));

        if (addr[0] == "same") {
            gumae2baesong();
        } else {
            if(addr[0] == "new") {
                for(i=0; i<10; i++) {
                    addr[i] = "";
                }
            }

            var f = document.forderform;
            f.od_b_name.value        = addr[0];
            f.od_b_tel.value         = addr[1];
            f.od_b_hp.value          = addr[2];
            f.od_b_zip.value         = addr[3] + addr[4];
            f.od_b_addr1.value       = addr[5];
            f.od_b_addr2.value       = addr[6];
            f.od_b_addr3.value       = addr[7];
            f.od_b_addr_jibeon.value = addr[8];
            f.ad_subject.value       = addr[9];

            var zip1 = addr[3].replace(/[^0-9]/g, "");
            var zip2 = addr[4].replace(/[^0-9]/g, "");

            var code = String(zip1) + String(zip2);

            if(zipcode != code) {
                calculate_sendcost(code);
            }

            ad_subject_change();
        }
    });

    // 배송지목록
    $("#order_address,#order_address1").on("click", function() {
        var url = this.href;
        window.open(url, "win_address", "left=100,top=100,width=650,height=500,scrollbars=1");
        return false;
    });

    $("#ct_keep_month").on("change", function() {
    	calculate_total_price();
    });

});

function fileCheck( file )
{
        // 사이즈체크
        var maxSize  = 20 * 1024 * 1024    
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
        alert("첨부파일 사이즈는 20MB 이내로 등록 가능합니다.    ");
        return false;
    }
	return true;
}

function ad_subject_change()
{
    $("#addr").text("["+$("#od_b_zip").val()+"]"+$("#od_b_addr1").val()+" "+$("#od_b_addr2").val());
    $("#spn_od_b_name").text($("#od_b_name").val());
    $("#spn_od_b_tel").text($("#od_b_tel").val());
    $("#spn_od_b_hp").text($("#od_b_hp").val());
    $("#spn_ad_subject").text($("#od_b_name").val());
}

function calculate_total_price()
{
	var keep_month = $("#ct_keep_month").val();
	$("#keep_month1").text(keep_month);
	$("#keep_month2").text(keep_month);

    var laundry_price = parseInt($("input[name=laundry_price]").val());
    var keep_price = parseInt($("input[name=keep_price]").val());
    var send_cost = parseInt($("input[name=od_send_cost]").val());
    var ct_free_laundry_YN = parseInt($("input[name=ct_free_laundry_YN]").val());
    var ct_free_laundry_delivery_price = parseInt($("input[name=ct_free_laundry_delivery_price]").val());

    //tot_sell_price = laundry_price + (keep_price * keep_month) + send_cost;

    od_price = 0;
    if(!ct_free_laundry_YN) od_price = laundry_price;
    od_price = od_price + (keep_price * keep_month);
    $("input[name=od_price]").val(od_price);

    var tot_price = od_price + send_cost;
    $("#od_tot_price").text(number_format(String(tot_price)));
    $("#ct_tot_price").text(number_format(String(tot_price))+" 원");

    $("input[name=good_mny]").val(tot_price);
}

function calculate_order_price()
{
    var sell_price = parseInt($("input[name=od_price]").val());
    var send_cost = parseInt($("input[name=od_send_cost]").val());
    var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
    var send_coupon = parseInt($("input[name=od_send_coupon]").val());
    var tot_price = sell_price + send_cost + send_cost2 - send_coupon - temp_point;

    $("form[name=sm_form] input[name=good_mny]").val(tot_price);
    $("#od_tot_price").text(number_format(String(tot_price)));
    <?php if($temp_point > 0 && $is_member) { ?>
    calculate_temp_point();
    <?php } ?>

    $("#od_coupon_cost").text(number_format(String(parseInt($("input[name=od_coupon]").val()))));
    $("#od_point_cost").text(number_format(String(temp_point)));
    $("#od_tot_price").text(number_format(String(tot_price)));
    $("input[name='submitChecked']").val(number_format(String(tot_price))+"원 결제");
    //$("input[name='submitChecked']").val(number_format(String($("input[name=od_price]").val()))+"원 결제");
}

function calculate_temp_point()
{
    var sell_price = parseInt($("input[name=od_price]").val());
    var mb_point = parseInt(<?php echo $member['mb_point']; ?>);
    var max_point = parseInt(<?php echo $default['de_settle_max_point']; ?>);
    var point_unit = parseInt(<?php echo $default['de_settle_point_unit']; ?>);
    var temp_point = max_point;

    if(temp_point > sell_price)
        temp_point = sell_price;

    if(temp_point > mb_point)
        temp_point = mb_point;

    temp_point = parseInt(temp_point / point_unit) * point_unit;

    $("#use_max_point").text(number_format(String(temp_point))+"원");
    $("input[name=max_temp_point]").val(temp_point);
}

function calculate_sendcost(code)
{
    $.post(
        "./ordersendcost.php",
        { zipcode: code },
        function(data) {
            $("input[name=od_send_cost2]").val(data);
            $("#od_send_cost2").text(number_format(String(data)));

            zipcode = code;

            calculate_order_price();
        }
    );
}

function calculate_tax()
{
    var $it_prc = $("input[name^=it_price]");
    var $cp_prc = $("input[name^=cp_price]");
    var sell_price = tot_cp_price = 0;
    var it_price, cp_price, it_notax;
    var tot_mny = comm_free_mny = tax_mny = vat_mny = 0;
    var send_cost = parseInt($("input[name=od_send_cost]").val());
    var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
    var od_coupon = parseInt($("input[name=od_coupon]").val());
    var send_coupon = parseInt($("input[name=od_send_coupon]").val());
    var temp_point = 0;

    $it_prc.each(function(index) {
        it_price = parseInt($(this).val());
        cp_price = parseInt($cp_prc.eq(index).val());
        sell_price += it_price;
        tot_cp_price += cp_price;
        it_notax = $("input[name^=it_notax]").eq(index).val();
        if(it_notax == "1") {
            comm_free_mny += (it_price - cp_price);
        } else {
            tot_mny += (it_price - cp_price);
        }
    });

    if($("input[name=od_temp_point]").size())
        temp_point = parseInt($("input[name=od_temp_point]").val());

    tot_mny += (send_cost + send_cost2 - od_coupon - send_coupon - temp_point);
    if(tot_mny < 0) {
        comm_free_mny = comm_free_mny + tot_mny;
        tot_mny = 0;
    }

    tax_mny = Math.round(tot_mny / 1.1);
    vat_mny = tot_mny - tax_mny;
    $("input[name=comm_tax_mny]").val(tax_mny);
    $("input[name=comm_vat_mny]").val(vat_mny);
    $("input[name=comm_free_mny]").val(comm_free_mny);
}

/* 결제방법에 따른 처리 후 결제등록요청 실행 */
var settle_method = "";
var temp_point = 0;

function pay_approval()
{
    var f = document.sm_form;
    var pf = document.forderform;

    // 필드체크
    if(!orderfield_check(pf))
        return false;

    // 금액체크
    if(!payment_check(pf))
        return false;

    // pg 결제 금액에서 적립금 금액 차감
    if(settle_method != "무통장") {
        var od_price = parseInt(pf.od_price.value);
        var send_cost = parseInt(pf.od_send_cost.value);
        var send_cost2 = parseInt(pf.od_send_cost2.value);
        var send_coupon = parseInt(pf.od_send_coupon.value);
        f.good_mny.value = od_price + send_cost + send_cost2 - send_coupon - temp_point;
    }

    // 카카오페이 지불
    if(settle_method == "KAKAOPAY") {
        <?php if($default['de_tax_flag_use']) { ?>
        pf.SupplyAmt.value = parseInt(pf.comm_tax_mny.value) + parseInt(pf.comm_free_mny.value);
        pf.GoodsVat.value  = parseInt(pf.comm_vat_mny.value);
        <?php } ?>
        pf.good_mny.value = f.good_mny.value;
        getTxnId(pf);
        return false;
    }

    var form_order_method = '';

    if( settle_method == "삼성페이" || settle_method == "lpay" ){
        form_order_method = 'samsungpay';
    }

    if( jQuery(pf).triggerHandler("form_sumbit_order_"+form_order_method) !== false ) {
        <?php if($default['de_pg_service'] == 'kcp') { ?>
        f.buyr_name.value = pf.od_name.value;
        f.buyr_mail.value = pf.od_email.value;
        f.buyr_tel1.value = pf.od_tel.value;
        f.buyr_tel2.value = pf.od_hp.value;
        f.rcvr_name.value = pf.od_b_name.value;
        f.rcvr_tel1.value = pf.od_b_tel.value;
        f.rcvr_tel2.value = pf.od_b_hp.value;
        f.rcvr_mail.value = pf.od_email.value;
        f.rcvr_zipx.value = pf.od_b_zip.value;
        f.rcvr_add1.value = pf.od_b_addr1.value;
        f.rcvr_add2.value = pf.od_b_addr2.value;
        f.settle_method.value = settle_method;
        if(settle_method == "간편결제")
            f.payco_direct.value = "Y";
        else
            f.payco_direct.value = "";
        <?php } else if($default['de_pg_service'] == 'lg') { ?>
        var pay_method = "";
        var easy_pay = "";
        switch(settle_method) {
            case "계좌이체":
                pay_method = "SC0030";
                break;
            case "가상계좌":
                pay_method = "SC0040";
                break;
            case "휴대전화":
                pay_method = "SC0060";
                break;
            case "신용카드":
                pay_method = "SC0010";
                break;
            case "간편결제":
                easy_pay = "PAYNOW";
                break;
        }
        f.LGD_CUSTOM_FIRSTPAY.value = pay_method;
        f.LGD_BUYER.value = pf.od_name.value;
        f.LGD_BUYEREMAIL.value = pf.od_email.value;
        f.LGD_BUYERPHONE.value = pf.od_hp.value;
        f.LGD_AMOUNT.value = f.good_mny.value;
        f.LGD_EASYPAY_ONLY.value = easy_pay;
        <?php if($default['de_tax_flag_use']) { ?>
        f.LGD_TAXFREEAMOUNT.value = pf.comm_free_mny.value;
        <?php } ?>
        <?php } else if($default['de_pg_service'] == 'inicis') { ?>
        var paymethod = "";
        var width = 330;
        var height = 480;
        var xpos = (screen.width - width) / 2;
        var ypos = (screen.width - height) / 2;
        var position = "top=" + ypos + ",left=" + xpos;
        var features = position + ", width=320, height=440";
        var p_reserved = f.DEF_RESERVED.value;
        f.P_RESERVED.value = p_reserved;
        switch(settle_method) {
            case "계좌이체":
                paymethod = "bank";
                break;
            case "가상계좌":
                paymethod = "vbank";
                break;
            case "휴대전화":
                paymethod = "mobile";
                break;
            case "신용카드":
                paymethod = "wcard";
                f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "");
                break;
            case "간편결제":
                paymethod = "wcard";
                f.P_RESERVED.value = p_reserved+"&d_kpay=Y&d_kpay_app=Y";
                break;
            case "삼성페이":
                paymethod = "wcard";
                f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "")+"&d_samsungpay=Y";
                //f.DEF_RESERVED.value = f.DEF_RESERVED.value.replace("&useescrow=Y", "");
                f.P_SKIP_TERMS.value = "Y"; //약관을 skip 해야 제대로 실행됨
                break;
            case "lpay":
                paymethod = "wcard";
                f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "")+"&d_lpay=Y";
                //f.DEF_RESERVED.value = f.DEF_RESERVED.value.replace("&useescrow=Y", "");
                f.P_SKIP_TERMS.value = "Y"; //약관을 skip 해야 제대로 실행됨
                break;
        }
        f.P_AMT.value = f.good_mny.value;
        f.P_UNAME.value = pf.od_name.value;
        f.P_MOBILE.value = pf.od_hp.value;
        f.P_EMAIL.value = pf.od_email.value;
        <?php if($default['de_tax_flag_use']) { ?>
        f.P_TAX.value = pf.comm_vat_mny.value;
        f.P_TAXFREE = pf.comm_free_mny.value;
        <?php } ?>
        f.P_RETURN_URL.value = "<?php echo $return_url.$od_id; ?>";
        f.action = "https://mobile.inicis.com/smart/" + paymethod + "/";
        <?php } ?>

        // 주문 정보 임시저장
        var order_data = $(pf).serialize();
        var save_result = "";
        $.ajax({
            type: "POST",
            data: order_data,
            url: g5_url+"/shop/ajax.orderdatasave.php",
            cache: false,
            async: false,
            success: function(data) {
                save_result = data;
            }
        });

        if(save_result) {
            alert(save_result);
            return false;
        }

        f.submit();
    }

    return false;
}

function forderform_check()
{
    var f = document.forderform;

    // 필드체크
    if(!orderfield_check(f))
        return false;

    // 금액체크
    if(!payment_check(f))
        return false;

    if(settle_method != "무통장" && f.res_cd.value != "0000") {
        alert("결제등록요청 후 주문해 주십시오.");
        return false;
    }

    document.getElementById("display_pay_button").style.display = "none";
    document.getElementById("show_progress").style.display = "block";

    setTimeout(function() {
        f.submit();
    }, 300);
}

// 주문폼 필드체크
function orderfield_check(f)
{
    errmsg = "";
    errfld = "";
    var deffld = "";

    check_field(f.cust_memo, "요청사항을 입력하십시오.");
    check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
    check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
    //check_field(f.od_addr1, "주소검색을 이용하여 주문하시는 분 주소를 입력하십시오.");
    //check_field(f.od_addr2, " 주문하시는 분의 상세주소를 입력하십시오.");
    //check_field(f.od_zip, "");

    clear_field(f.od_email);
    if(f.od_email.value=='' || f.od_email.value.search(/(\S+)@(\S+)\.(\S+)/) == -1)
        error_field(f.od_email, "E-mail을 바르게 입력해 주십시오.");

    if (typeof(f.od_hope_date) != "undefined")
    {
        clear_field(f.od_hope_date);
        if (!f.od_hope_date.value)
            error_field(f.od_hope_date, "수거 일자를 선택하여 주십시오.");
    }

    check_field(f.od_b_name, "받으시는 분 이름을 입력하십시오.");
    check_field(f.od_b_hp, "받으시는 분 휴대전화 번호를 입력하십시오.");
    check_field(f.od_b_addr1, "주소검색을 이용하여 받으시는 분 주소를 입력하십시오.");
    //check_field(f.od_b_addr2, "받으시는 분의 상세주소를 입력하십시오.");
    check_field(f.od_b_zip, "");

    // 배송비를 받지 않거나 더 받는 경우 아래식에 + 또는 - 로 대입
    f.od_send_cost.value = parseInt(f.od_send_cost.value);

    if (errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

    var od_price = parseInt(f.od_price.value);
    var send_cost = parseInt(f.od_send_cost.value);
    var tot_price = od_price + send_cost;
    
	if(tot_price == 0) {
        f.enctype = "multipart/form-data";
		f.submit();
        return false;
	}

    var settle_case = document.getElementsByName("od_settle_case");
    var settle_check = false;
    for (i=0; i<settle_case.length; i++)
    {
        if (settle_case[i].checked)
        {
            settle_check = true;
            settle_method = settle_case[i].value;
            break;
        }
    }
    if (!settle_check)
    {
        alert("결제방식을 선택하십시오.");
        return false;
    }

	if (!$("#chk_user_privacy").is(":checked"))
	{
        alert("개인정보 수집 · 이용 동의에 동의하셔야 결제 하실 수 있습니다.");
		return false;
	}

    return true;
}

// 결제체크
function payment_check(f)
{
    var od_price = parseInt(f.od_price.value);
    var send_cost = parseInt(f.od_send_cost.value);
    var tot_price = od_price + send_cost;
    
    if (document.getElementById("od_settle_iche")) {
        if (document.getElementById("od_settle_iche").checked) {
            if (tot_price < 150) {
                alert("계좌이체는 150원 이상 결제가 가능합니다.");
                return false;
            }
        }
    }

    if (document.getElementById("od_settle_card")) {
        if (document.getElementById("od_settle_card").checked) {
            if (tot_price < 1000) {
                alert("신용카드는 1000원 이상 결제가 가능합니다.");
                return false;
            }
        }
    }

    if (document.getElementById("od_settle_hp")) {
        if (document.getElementById("od_settle_hp").checked) {
            if (tot_price < 350) {
                alert("휴대전화은 350원 이상 결제가 가능합니다.");
                return false;
            }
        }
    }

    <?php if($default['de_tax_flag_use']) { ?>
    calculate_tax();
    <?php } ?>

    return true;
}

// 구매자 정보와 동일합니다.
function gumae2baesong() {
    var f = document.forderform;

    f.od_b_name.value = f.od_name.value;
    f.od_b_tel.value  = f.od_tel.value;
    f.od_b_hp.value   = f.od_hp.value;
    f.od_b_zip.value  = f.od_zip.value;
    f.od_b_addr1.value = f.od_addr1.value;
    f.od_b_addr2.value = f.od_addr2.value;
    f.od_b_addr3.value = f.od_addr3.value;
    f.od_b_addr_jibeon.value = f.od_addr_jibeon.value;
    f.ad_subject.value = f.od_name.value;

    calculate_sendcost(String(f.od_b_zip.value));

    ad_subject_change();
}
</script>
