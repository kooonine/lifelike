<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// $s_cart_id 로 현재 장바구니 자료 쿼리
$sql = "select  b.ct_id, a.it_id, a.it_name, a.ct_option, a.rf_serial, a.od_type
                , a.ct_price, a.io_price, a.ct_time
                , a.ct_rental_price, a.ct_item_rental_month
                , a.ct_free_laundry_use, a.ct_free_laundry, a.ct_free_laundry_delivery_price
                , a.ct_laundry_use, a.ct_laundry_price, a.ct_laundry_delivery_price
                , a.ct_laundrykeep_use, a.ct_laundrykeep_lprice, a.ct_laundrykeep_kprice, a.ct_laundrykeep_delivery_price
                , a.ct_repair_use, a.ct_repair_price, a.ct_repair_delivery_price
                , b.buy_ct_id, b.buy_od_sub_id, a.ct_status
        from    lt_shop_order_item a
                inner join lt_shop_cart b
                  on a.ct_id = b.buy_ct_id and a.od_sub_id = b.buy_od_sub_id
        where   b.od_id = '$s_cart_id'
          and   b.ct_select = '1' ";

$row = sql_fetch($sql);

if(($row['ct_status'] != "구매완료" && $row['ct_status'] != "리스중" && $row['ct_status'] != "") || $row['ct_repair_use'] != "1")
    alert('케어서비스를 이용하실 수 없습니다.');

$image_width = 100;
$image_height = 100;
$image = get_it_image($row['it_id'], $image_width, $image_height);
$it_name = stripslashes($row['it_name']);

$send_cost = 0; //배송비
$goods = $goods_it_id = "";

$laundry_price = 0; //유료세탁비
//$send_cost =  $row['ct_repair_delivery_price']; //배송비
$title = "수선 신청";
$sell_price = $laundry_price;

$tot_sell_price = $sell_price;
$tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비

$goods = preg_replace("/\?|\'|\"|\||\,|\&|\;/", "", $row['it_name']).' '.$title;
$goods_it_id = $row['it_id'];
?>
<script>
var header = '<div id="lnb" class="header_bar">';
header += '<h1 class="title"><span class="">세탁 서비스 신청</span></h1>';
header += '<a onclick="history.back();" class="btn_back"><span class="blind">뒤로가기</span></a>';
header += '<button type="button" class="btn_menu_all"><span class="blind">메뉴</span></button>';
header += '</div>';
$('#header').html(header);
</script>

<div class="content shop sub">
	<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off" enctype="multipart/form-data">

	<!-- 컨텐츠 시작 -->
	<div class="grid">
		<div class="title_bar ">
			<h3 class="g_title_01">수선 서비스 신청 안내</span></h3>
		</div>
		<div class="gray_box pad15 mt20">
			<p>*수선 서비스는 지퍼 교체부터 다운 충전까지 다양한 수선이 가능하기 때문에 전문 상담사와 ‘선 상담 후 결제’로 운영됩니다.</p>
			<p>수선 신청을 해주시면 전문 상담사가 연락드리며, 통화를 통해 수선 가능 여부 및 비용에 대해 안내드립니다.</p>
		</div>
		
		<div class="title_bar ">
			<h3 class="g_title_01">제품 정보</span></h3>
			<button type="button" class="btn green_line round floatR" onclick="$('#pricetable').css('display','');"><span class="point">수선단가표</span></button>
		</div>
		
        <div class="order_cont">
            <div class="body">
                <div class="cont">
                    <div class="photo"><?php echo $image; ?></div>
                    <div class="info">
                        <strong><?php echo $it_name; ?></strong>
                        <p>옵션 : <?php echo $row['ct_option']; ?></p>
                        <p>구매일 : <?php echo substr($row['ct_time'], 0, 10)?></p>
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
        <input type="hidden" name="od_send_cost" value="<?php echo $send_cost; ?>">
        <input type="hidden" name="od_send_cost2" value="0">
        <input type="hidden" name="item_coupon" value="0">
        <input type="hidden" name="od_coupon" value="0">
        <input type="hidden" name="od_send_coupon" value="0">
        <input type="hidden" name="od_goods_name" value="<?php echo $goods; ?>">
        <input type="hidden" name="od_type" value="<?php echo $od_type?>" />
    </div>

	<div class="grid">
    	<div class="title_bar ">
    		<h3 class="g_title_01">신청 제품 확인</span></h3>
    	</div>
    	<div class="border_box order_list mt20 reverse">
    		<ul>
    			<li>
    				<span class="item">* <?php echo $row['ct_option']; ?> * 1개 수선 </span>
    				<strong class="result"><?php echo ($laundry_price)?number_format($laundry_price)." 원":"후불"?></strong>
    			</li>
    			<!-- <li>
    				<span class="item">* 택배비</span>
    				<strong class="result"><?php echo number_format($send_cost)?> 원</strong>
    			</li> 
    			<li>
    				<span class="item blind"></span>
    				<strong class="result bold point" id="ct_tot_price"><?php echo number_format($laundry_price + ($keep_price * $keep_month) + $send_cost)?> 원</strong>
    			</li>-->
    		</ul>
    	</div>
	</div>
	
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
    		<div class="title count9"><label for="f_01">수선 접수 사항</label></div>
    		<div class="inp_ele count9">
    			<div class="input"><textarea name="cust_memo" id="cust_memo" rows="6" cols="20" maxlength="200" placeholder="접수할 수선물에 대한 상세한 정보 입력"></textarea></div>
    		</div>
    	</div>
    	<div class="inp_wrap">
    		<p class="ico_import red point_red floatL">수선 접수 사항은 수선 해야 할 부분을 자세히 입력해 주셔야 합니다.</p>
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
        // 배송지 이력
        $sep = chr(30);

        // 기본배송지
        $sql = " select *
                    from {$g5['g5_shop_order_address_table']}
                    where mb_id = '{$member['mb_id']}'
                      and ad_default = '1' ";
        $row = sql_fetch($sql);
        
        if($row['ad_id']) {
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
                        <?php echo '['.$row['ad_zip1'].$row['ad_zip2'].']'.$row['ad_addr1'].' '.$row['ad_addr2'] ?>
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

    </div>

	<div class="grid">
		<div class="btn_group two">
			<button type="button" class="btn big border gray" onClick="location.href='<?php echo G5_SHOP_URL ?>/care.php';"><span>취소</span></button>
			<button type="button" class="btn big green"  onClick="forderform_check(this.form);" ><span>등록</span></button>
		</div>
	</div>
	</form>
</div>

<!-- popup -->
<section class="popup_container layer" style="display: none" id="pricetable">
	<div class="inner_layer" style="top:0px">
	
	<!-- lnb -->
	<div id="lnb" class="header_bar">
		<h1 class="title"><span>수선 단가표</span></h1>
		<a href="#" class="btn_closed" onclick="$('#pricetable').css('display','none');"><span class="blind">닫기</span></a>
	</div>
	<!-- // lnb -->

	<div class="content shop sub">
		<!-- 컨텐츠 시작 -->
		<div class="grid">
                <div class="gray_box pad15">
                    <p>*아래 요금표는 안내를 위한 것으로, 전문 상담사와 통화 후 정확한 요금 안내가 이루어집니다.</p>
                    <p>*수선 서비스는 라이프라이크 제품에 한하여 이용이 가능합니다.</p>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">구스다운 충전 (100g당)</span>
                    <strong class="result"><em class="point">72,000원~</em></strong>
                </div>
                <div class="border_box order_list ">
                    <ul>
                        <li>
                            <span class="item">폴란드산 구스다운 90%</span>
                            <strong class="result">119,000원</strong>
                        </li>
                        <li>
                            <span class="item">헝가리산 구스다운 90%</span>
                            <strong class="result">72,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">사이즈 수선(줄임)</span>
                    <strong class="result"><em class="point">7,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">이불 커버</span>
                            <strong class="result">15,000원</strong>
                        </li>
                        <li>
                            <span class="item">누비 이불 커버</span>
                            <strong class="result">20,000원</strong>
                        </li>
                        <li>
                            <span class="item">매트리스 커버</span>
                            <strong class="result">10,000원</strong>
                        </li>
                        <li>
                            <span class="item">패드</span>
                            <strong class="result">10,000원</strong>
                        </li>
                        <li>
                            <span class="item">차렵 이불</span>
                            <strong class="result">15,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">솜 샤시(누비)</span>
                    <strong class="result"><em class="point">10,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">홑겹 이불 커버 → 누비 이불 커버</span>
                            <strong class="result">S → 40,000원</strong>
                        </li>
                        <li>
                            <span class="item">홑겹 매트리스 커버 → 누비 매트리스 커버</span>
                            <strong class="result">Q → 50,000원</strong>
                        </li>
                        <li>
                            <span class="item">홑겹 베개 커버 → 누비 베개 커버</span>
                            <strong class="result">50,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">밴드 교체</span>
                    <strong class="result"><em class="point">8,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">패드(모서리 4면)</span>
                            <strong class="result">8,000원</strong>
                        </li>
                        <li>
                            <span class="item">매트리스 커버(양 쪽 2개)</span>
                            <strong class="result">12,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">지퍼 수선</span>
                    <strong class="result"><em class="point">5,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">대품(이불, 이불커버)</span>
                            <strong class="result">8,000원</strong>
                        </li>
                        <li>
                            <span class="item">베개</span>
                            <strong class="result">5,000원</strong>
                        </li>
                    </ul>
                </div>

                <div class="order_title reverse type2">
                    <span class="item">매트리스 커버 수선</span>
                    <strong class="result"><em class="point">27,000원~</em></strong>
                </div>
                <div class="border_box order_list reverse ">
                    <ul>
                        <li>
                            <span class="item">프릴단 전체 교체 ~10cm, 20cm 길이 수선</span>
                            <strong class="result">27,000원 ~ 32,000원</strong>
                        </li>
                    </ul>
                </div>

            </div>
            
            <div class="grid bg_none">
                <div class="toggle inquiry_toggle">
                    <div class="toggle_group type2 opened">
                        <div class="title">
                            <h3 class="tit ellipsis">참조사항</h3>
                        </div>
                        <div class="cont">
                            <div class="white_box info_box">
                                <p class="tit">* 베이직라인 이불속통 (중량추가주입)</p>
                                <ul class="hyphen">
                                    <li>원산지별 100g 당 가격</li>
                                    <li>속통류는 베이직라인만 가능</li>
                                    <li>원단 덧댐 수선은 별도문의</li>
                                </ul>
                                <p class="tit">* 사이즈 수선 (줄임)</p>
                                <ul class="hyphen">
                                    <li>지퍼수선이 필요할경우 추가</li>
                                </ul>
                                <p class="tit">* 사이즈 수선 (늘임)</p>
                                <ul class="hyphen">
                                    <li>정상 런제품 가능하며,<br>원자재 보유제품이어야 함</li>
                                </ul>
                                <p class="tit">* 매커프릴수선 교체 / 높이연폭</p>
                                <ul class="hyphen">
                                    <li>프릴단 교체(완성 35cm)</li>
                                    <li>기존끝에서 추가(35+20)</li>
                                    <li>기존끝에서 추가(35+10)</li>
                                </ul>
                                <p class="tit">* 랍바수선 (면)</p>
                                <ul class="hyphen">
                                    <li>정상제품 가능하며, 원자재 보유제품이어야 함</li>
                                </ul>
                                <p class="tit">* 앞, 뒤 판갈이</p>
                                <ul class="hyphen">
                                    <li>패드 판갈이 불가능(짜집기, 덧뎀)</li>
                                </ul>
                                <p class="tit">* 손샤시</p>
                                <ul class="hyphen">
                                    <li>기존 사용솜 이외의 신청은 불가</li>
                                </ul>
                                <p class="tit">* 밴드교체</p>
                                <ul class="hyphen">
                                    <li>해당없음</li>
                                </ul>
                                <p class="tit">* 지퍼수선</p>
                                <ul class="hyphen">
                                    <li>옥매트 ㄷ자형 : 메모리폼 커버</li>
                                </ul>
                                <p class="tit">* 삥줄(3면)</p>
                                <ul class="hyphen">
                                    <li>해당없음</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		<!-- 컨텐츠 종료 -->
	</div>
</section>
<!-- //popup -->
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

function ad_subject_change()
{
    $("#addr").text("["+$("#od_b_zip").val()+"]"+$("#od_b_addr1").val()+" "+$("#od_b_addr2").val());
    $("#spn_od_b_name").text($("#od_b_name").val());
    $("#spn_od_b_tel").text($("#od_b_tel").val());
    $("#spn_od_b_hp").text($("#od_b_hp").val());
    $("#spn_ad_subject").text($("#od_b_name").val());
}

function forderform_check(f)
{
    errmsg = "";
    errfld = "";
    var deffld = "";

    check_field(f.cust_memo, "요청사항을 입력하십시오.");
    check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
    if (typeof(f.od_pwd) != 'undefined')
    {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
    }
    check_field(f.od_hp, "주문하시는 분 휴대전화 번호를 입력하십시오.");
    //check_field(f.od_tel, "주문하시는 분 전화번호를 입력하십시오.");
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

	f.submit();
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
    
    //calculate_sendcost(String(f.od_b_zip.value));

    ad_subject_change();
}
</script>