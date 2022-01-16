<?php
include_once('./_common.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$od_id = isset($od_id) ? preg_replace('/[^A-Za-z0-9\-_]/', '', strip_tags($od_id)) : 0;

// 불법접속을 할 수 없도록 세션에 아무값이나 저장하여 hidden 으로 넘겨서 다음 페이지에서 비교함
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$sql = "select * from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";
if($is_member && !$is_admin)
    $sql .= " and mb_id = '{$member['mb_id']}' ";
$od = sql_fetch($sql);
if (!$od['od_id'] || (!$is_member && md5($od['od_id'].$od['od_time'].$od['od_ip']) != get_session('ss_orderview_uid'))) {
    alert("조회하실 주문서가 없습니다.", G5_SHOP_URL);
}

// 결제방법
$settle_case = $od['od_settle_case'];

$title = '해지요청';
$subtitle = '해지';
$od_type_name = '리스';

$rt_month = $od['rt_month'];
$rt_rental_enddate = date_create($od['rt_rental_startdate']);
date_add($rt_rental_enddate, date_interval_create_from_date_string($rt_month.' months'));
$rt_rental_enddate = date_format($rt_rental_enddate,"Y-m-d");

$penalty = rental_contractout_calc($od);

$action_url = G5_SHOP_URL."/orderinquirycontractout.php";

$od_status = $od['od_status'];

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderinquiry.contractoutform.php');
    return;
}

$g5['title'] = $title.'|주문 상세';
include_once('./_head.php');

?>
<script src="<?php echo G5_JS_URL; ?>/shop.js"></script>

<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar type2">
		<h1 class="title"><span>리스 해지 계약서 작성</span></h1>
	</div>
    <!-- //lnb -->
    <div class="content mypage sub">
    	<form method="post" name="forderform" action="<?php echo $action_url; ?>" autocomplete="off">
        <input type="hidden" name="od_id"  value="<?php echo $od['od_id']; ?>">
        <input type="hidden" name="token"  value="<?php echo $token; ?>">
        
        <input type="hidden" name="od_contractout"  value="<?php echo $od_contractout; ?>">
        <input type="hidden" name="od_penalty"  value="<?php echo $penalty; ?>">
        <input type="hidden" name="od_send_cost2" id="od_send_cost2" value="<?php echo $default['de_return_costs']; ?>">
        <input type="hidden" name="od_receipt_price" id="od_receipt_price" value="<?php echo $od['od_receipt_price']; ?>">
        
    	<!-- 주문하시는 분 입력 시작 { -->
		<div class="grid bg_none type2">
			<div class="title_bar">
				<h2 class="g_title_01">계약자 정보</h2>
			</div>
			<div class="border_box none order_list">	
                <div class="inp_wrap">
                    <div class="title count3"><label for="f_51">생년월일</label></div>
                    <div class="inp_ele count6">
                        <span class="value"><?php echo get_text($member['mb_birth']); ?></span>
                    </div>
                </div>
				<div class="inp_wrap">
					<div class="title count3"><label>이름</label></div>
					<div class="inp_ele count6">
                    	<span class="value"><?php echo get_text($member['mb_name']); ?></span>
					</div>
                    <input type="hidden" name="od_email" value="<?php echo get_text($member['od_email']); ?>" id="od_email" >
                    <input type="hidden" name="od_tel" value="<?php echo get_text($member['mb_tel']); ?>" id="od_tel" >
                    <input type="hidden" name="od_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="od_zip" >
                    <input type="hidden" name="od_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="od_addr1" >
                    <input type="hidden" name="od_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="od_addr2" >
    				<input type="hidden" name="od_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="od_addr3" >
                    <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
				</div>
                <div class="inp_wrap">
                    <div class="title count3"><label for="f_53">연락처</label></div>
                    <div class="inp_ele count6">
                        <span class="value"><?php echo get_text($member['mb_tel']); ?></span>
                    </div>
                </div>
                <div class="inp_wrap">
                    <div class="title count3"><label for="f_54">휴대전화 번호</label></div>
                    <div class="inp_ele count6">
                        <span class="value"><?php echo get_text($member['mb_hp']); ?></span>
                    </div>
                </div>
                <div class="inp_wrap">
                    <div class="title count3">
                        <label for="join7">주소</label>
                    </div>
                    <div class="inp_ele count6">
                        <span class="value"><?php echo $member['mb_zip1'].$member['mb_zip2']; ?></span>
                    </div>
                </div>
                <div class="inp_wrap">
					<div class="title count3">
						<label for="join77_3" class="blind">주소 상세</label>
					</div>
					<div class="inp_ele count6">
                        <span class="value"><?php echo get_text($member['mb_addr1']); ?></span>
                    </div>
                </div>
                <div class="inp_wrap">
					<div class="title count3">
						<label for="join77_3" class="blind">주소 상세</label>
					</div>
					<div class="inp_ele count6">
                        <span class="value"><?php echo get_text($member['mb_addr2']); ?></span>
                    </div>
                </div>
			</div>
		</div>
		<!-- } 주문하시는 분 입력 끝 -->

	   <!-- 받으시는 분 입력 시작 { -->
		<div class="grid bg_none  type2">
			<div class="title_bar">
				<h2 class="g_title_01">해지 요청 수거지 정보</h2>
				<span class="chk radio">
                	<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">
                	<label for="ad_sel_addr_same">계약자와 동일</label>
				</span>
        	</div>
        	
            <div class="border_box none order_list">
    			<div class="inp_wrap">
    				<div class="title count3"><label>수거 일자 선택</label></div>
    				<div class="inp_ele count6">
                        <div class="input calendar"><input type="date" name="od_hope_date" id="od_hope_date" required class="frm_input required" maxlength="10" value="<?php echo G5_TIME_YMD ?>"></div>
    				</div>
    			</div>
    			<div class="inp_wrap">
    				<div class="title count3"><label>이름</label></div>
    				<div class="inp_ele count6">
                        <div class="input"><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required" maxlength="20" placeholder="이름 입력"></div>
    				</div>
    				<input type="hidden" name="ad_subject" id="ad_subject" value="">
    			</div>
    			<div class="inp_wrap">
    				<div class="title count3"><label>연락처</label></div>
    				<div class="inp_ele count6">
                        <div class="input"><input type="text" name="od_b_tel" id="od_b_tel" class="frm_input required" maxlength="20" placeholder="연락처 입력"></div>
    				</div>
    			</div>
    			<div class="inp_wrap">
    				<div class="title count3"><label>휴대전화 번호</label></div>
    				<div class="inp_ele count6">
                        <div class="input"><input type="text" name="od_b_hp" id="od_b_hp" required class="frm_input" maxlength="20" placeholder="휴대전화 번호 입력"></div>
    				</div>
    			</div>
                <div class="inp_wrap">
    				<div class="title count3"><label>주소</label></div>
                    <div class="inp_ele count6 r_btn_100">
                        <div class="input"  onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">
                        	<input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required disabled readonly" size="5" maxlength="6" readonly="readonly">
                        </div>
                        <button type="button" class="btn small green" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소검색</button>
                    </div>
                </div>
                <div class="inp_wrap">
					<div class="title count3">
						<label for="join77_2" class="blind">주소 상세</label>
					</div>
                    <div class="inp_ele count6 " >
                        <div class="input"><input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required disabled readonly" readonly="readonly"></div>
                    </div>
                </div>
                <div class="inp_wrap">
					<div class="title count3">
						<label for="join77_2" class="blind">주소 상세</label>
					</div>
                    <div class="inp_ele count6">
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
		</div>			
        <!-- } 받으시는 분 입력 끝 -->
    
        <!-- 주문상품 확인 시작 { -->
		<div class="grid bg_none type2">
			<div class="title_bar">
				<h2 class="g_title_01">계약 내용</h2>
			</div>
            <?php
            
            $sql = " select it_id, it_name, cp_price, ct_send_cost, it_sc_type
                            ,ct_id, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price, ct_rental_price, ct_item_rental_month, ct_keep_month, ct_receipt_price
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '$od_id'
                        order by ct_id ";
            $result = sql_query($sql);
            
            for($i=0; $row=sql_fetch_array($result); $i++) {
                
                $opt_rental_price = $row['ct_rental_price'] + $row['io_price'];
                $sell_rental_price = $opt_rental_price * $row['ct_qty'];
                
            ?>
            <div class="border_box order_list">
                <ul>
                    <li>
                        <span class="item">계약 제품</span>
                        <strong class="result">
                            <em class="bold"><?php echo stripslashes($row['it_name']); ?></em>
                        </strong>
                    </li>
                    <li>
                        <span class="item">옵션</span>
                        <strong class="result"><?php echo get_text($row['ct_option']); ?></strong>
                    </li>   
                    <li>
                        <span class="item">수량</span>
                        <strong class="result"><?php echo $row['ct_qty']; ?>개</strong>
                    </li>                                                  
                    <li>
                        <span class="item">월 이용료</span>
                        <strong class="result"><?php echo number_format($sell_rental_price); ?> 원</strong>
                    </li>
                    <li>
                        <span class="item">총 계약기간</span>
                        <strong class="result">
                            <em class="bold point_red"><?php echo $row['ct_item_rental_month']?></em>개월
                        </strong>
                    </li>
                </ul>
            </div>
            <?php } ?>
		</div>
	
    	<div class="grid bg_none  type2">
    		<div class="title_bar">
    			<h3 class="g_title_01">위약금 정보</h3>
    		</div>
    		<div class="border_box order_list">
        		<ul>
    				<li>
    					<span class="item">계약 금액</span>
    					<strong class="result">
    						<em class="point bold"><?php echo number_format($od['rt_rental_price'] * $od['rt_month']); ?> 원</em>
    					</strong>
    				</li>
    				<li>
    					<span class="item">리스료</span>
    					<strong class="result">
                                <em class="point bold"><?php echo number_format($od['rt_rental_price']); ?> 원</em>
    					</strong>
    				</li>
    				<li>
    					<span class="item">수납 방법</span>
    					<strong class="result">
    						카드 자동 이체
    					</strong>
    				</li>
    				<li>
    					<span class="item">카드사</span>
    					<strong class="result"><?php echo $od['od_bank_account']; ?></strong>
    				</li>
    				<li>
    					<span class="item">수납일</span>
    					<strong class="result"><?php echo $od['rt_billday']; ?>일</strong>
    				</li>
    				<li>
    					<span class="item">수납 횟수</span>
    					<strong class="result"><?php echo $od['rt_payment_count']; ?> 회</strong>
    				</li>
    				<li>
    					<span class="item">수납일 시작일</span>
    					<strong class="result"><?php echo $od['rt_rental_startdate']; ?></strong>
    				</li>
    				<li>
    					<span class="item">수납일 종료일</span>
    					<strong class="result"><?php echo $rt_rental_enddate; ?></strong>
    				</li>
    				<li>
    					<span class="item">예상 위약금 금액</span>
    					<strong class="result">
    						<em class="bold big point_red"><?php echo number_format($penalty) ?> 원</em>
    					</strong>
    				</li>
    			</ul>
    		</div>
    		
    		<div class="grid bg_none  type2">
    			<div class="title_bar">
    				<h3 class="g_title_01">판매자 정보</h3>
    			</div>
    			<div class="border_box order_list result_right mt20">
    				<ul>
    					<li>
    						<span class="item">판매 일자</span>
    						<strong class="result">
    							<?php
                                echo substr($od['od_time'],0,10);
                                ?>
    						</strong>
    					</li>
    					<li>
    						<span class="item">판매자 상호</span>
    						<strong class="result">
    							<?php echo $default['de_admin_company_name']?>
    						</strong>
    					</li>
    					<li>
    						<span class="item">판매자 연락처</span>
    						<strong class="result">
                                   <?php echo $default['de_admin_company_tel']?>
    						</strong>
    					</li>
    				</ul>
    			</div>
    		</div>
    
    
    		<div class="grid bg_none  type2">
    			<div class="title_bar">
    				<h3 class="g_title_01">고객 확인사항</h3>
    			</div>
    			<div class="info_box gray_box">
    				<ol class="text_g">
    					<li>1. 제품 정보(제품명/수량/월이용료) 및 기능, 색상, 디자인에 대해 이상 없이 확인합니다.</li>
                        <li>2. 총 계약기간은 36개월(3년)이고, 의무사용기간은 36개월(3년) 입니다.</li>
                        <li>3. 월요금은 인도일 익월부터 정기 출금/승인일에 청구 됩니다.</li>
                        <li>4. 계약은 제품 인도/설치 후 성립되며, 계약 후 고객의 임의 해지시 해약금(위약금+운송비+사은품비용+미납/연체비용 등)이 부과됩니다.</li>
                        <li>5. 의무사용기간 내 고객요청에 의한 임의 해지 시 위약금이 부과됩니다. 위약금 산정 시, 월요금은 할인되기 전의 기본요금을 기준으로 합니다.
                            <ul class="hyphen">
                                <li>1년 이내 해지시: (월 리스료÷30일) X (의무사용일수-실사용일수) X 30%</li>
                                <li>1년 이상~2년 이내 해지시: (월 이용료÷30일) X (의무사용일수-실사용일수) X 20%</li>
                                <li>2년 이상~3년 이내 해지시: (월 이용료÷30일) X (의무사용일수-실사용일수) X 10%</li>
                                <li>운송비: 총 계약기간(3년) 내 고객 요청에 의한 임의 해지시 운송비가 부과됩니다.</li>
                                <li>미납/연체비용: 해지 접수 시점에 미납/연체비용이 있을 경우 해약금에 함께 청구 됩니다.</li>
                            </ul>
                        </li>
    				</ol>
    			</div>
    		</div>
    		
    		<div class="grid bg_none  type2">
                <div class="title_bar">
                   <h3 class="g_title_01">약관 안내/동의</h3>
                   <span class="chk check" style="display: none;">
                        <input type="checkbox" id="chk_all_stipulation">
                        <label for="chk_all_stipulation">전체동의</label>
                    </span>
                </div>
                
    			<div class="list">
    				<ul class="type1 terms">
    					<li>
    						<p class="chk_title">
    							<span class="fix">계약서 확인 및 동의(필수)</span>
    							<button type="button" class="btn small border" id="btn_auto_billing" >전문보기</button>
    						</p>
    						<div class="floatR">
    							<span class="chk radio">
                                    <input type="radio" id="chk_auto_billing" name="rdo_auto_billing" value="1">
                                    <label for="chk_auto_billing">동의</label>
    							</span>
    							<span class="chk radio">
                                    <input type="radio" id="chk_auto_billing2" name="rdo_auto_billing" value="0">
                                    <label for="chk_auto_billing2">미동의</label>
    							</span>
    						</div>
    					</li>
    					<li>
    						<p class="chk_title">
    							<span class="fix">마케팅 활용 동의(선택)</span>
    							<button type="button" class="btn small border" id="btn_user_thirdparty_privacy" >전문보기</button>
    						</p>
    						<div class="floatR">
    							<span class="chk radio">
                                    <input type="radio" id="chk_user_thirdparty_privacy" name="rdo_user_thirdparty_privacy" value="1">
                                    <label for="chk_user_thirdparty_privacy">동의</label>
    							</span>
    							<span class="chk radio">
                                    <input type="radio" id="chk_user_thirdparty_privacy2"  name="rdo_user_thirdparty_privacy" value="0">
                                    <label for="chk_user_thirdparty_privacy2">미동의</label>
    							</span>
    						</div>
    					</li>
    				</ul>
    			</div>
    			
    			<div class="info_box gray_box">
    				<strong class="g_title_06">고지 안내사항</strong>
    				<p class="text_g">회사는 본 제품의 계약 이행 및 원활한 서비스 제공을 위해 개인정보 취급을 위탁하고 있으며, 위탁업체 및 업무내용에 관한 상세내용은 뒷면의 이용약관 또는 리탠다드 홈페이지(http://www.lifelike.co.kr) 內 개인정보취급방츰을 참조하여 주시기 바랍니다.</p>
    				<p class="text_g">※고객님의 정보조회/수정 또는 동의 철회를 위해서는 본 제품 계약 매장 또는 개인정보관리책임자에게 연락하여 주십시오.</p>
    			</div>
    		</div>
    
    		<div class="grid bg_none type2">
    			<div class="title_bar">
    				<h3 class="g_title_01">계약 서명</h3>
    			</div>
    			<p class="ico_import red point_red">계약자는 제품내용, 고객확인사항 및 이용약관에 대하여 충분한 설명을 듣고 이를 확인하였으며, 계약은 설치 완료 후 성립됨에 동의하는 의미로 아래와 같이 전자서명을 합니다.</p>
    			<div class="border_box order_list mt20">
    				<div class="inp_wrap">
    					<div class="title count6"><label for="f2">판매 일자/계약 일자</label></div>
    					<div class="inp_ele count3  alignR">
    						<?php echo $makedate; ?>
    					</div>
    				</div>
                    <div class="inp_wrap">
                        <div class="title count3"><label for="f2">이름</label></div>
                        <div class="inp_ele count6">
                            <div class="input"><input type="text" name="od_name" value="<?php echo get_text($member['mb_name']); ?>" id="od_name" readonly="readonly" ></div>
                        </div>
                    </div>
                    <div class="inp_wrap">
                        <div class="title count3"><label for="f2">휴대전화 번호</label></div>
                        <div class="inp_ele count6">
                            <div class="input"><input type="text" name="od_hp" value="<?php echo get_text($member['mb_hp']); ?>" id="od_hp" readonly="readonly" ></div>
                        </div>
                    </div>
    				<div class="inp_wrap">
    					<div class="title count6"><label for="f3">제품 및 계약내용 및 유지관리 서비스 문의</label></div>
    					<div class="inp_ele count3 alignR"><?php echo $default['de_admin_company_tel']?></div>
    				</div>
    				<p class="ico_import red point_red">본인인증 후 전자서명이 가능합니다.</p>
    				<div class="inp_wrap">
    					<div class="title count9"><label for="join1">본인인증</label><button type="button" class="btn floatR green small auto" id="btn_send_auth_key">인증하기</button></div>
    				</div>
                    <div class="inp_wrap" style="display: none;" id="div_auth">
                        <div class="title count9"><label for="join7">휴대전화 번호로 전송된 숫자를 입력해 주세요.</label></div>
                        <div class="inp_ele count9 r_btn">
                            <div class="input r_txt bg">
                                <input type="tel" placeholder="인증번호 입력" id="auth_key" name="auth_key" maxlength="6">
                                <span class="time" id="timer">02:59</span>
                            </div>
                            <button type="button" class="btn small green" id="btn_auth">인증</button>
                            <input type="hidden" id="auth_yn">
                        </div>
                        <div id="div_alert" style="display: none"></div>
                    </div>
    				<div class="signature_box" id="canvasSimpleDiv"><span class="signature_txt">전자서명란</span></div>
    				<div class="inp_wrap">
    					<div class="title count9">
    					<input type="hidden" id="cust_file" name="cust_file">
    					<input type="hidden" id="orgCanvasSimple">
    					<input type="button" value="다시작성" id="clearCanvasSimple" class="btn floatR gray_line small auto" >  
    					</div>
    				</div>
    			</div>
    		</div>
        </div>
    		
                <div class="grid foot bg_none" id="display_pay_button">
    				<div class="btn_group two">
                    	<a href="javascript:history.go(-1);" class="btn01"><button type="button" class="btn big border"><span>취소</span></button></a>
                    	<input type="button" value="다음" class="btn big green" onclick="forderform_check(this.form);"/>
                	</div>
                </div>
                
	</form>
	</div>	
</div>

<!-- popup -->
<section class="popup_container layer" id="popup_container" style="display: none">
	<div class="inner_layer" style="top:10%">
		<div class="content comm sub">
			<!-- 컨텐츠 시작 -->
			<div class="grid cont">
				<div class="title_bar">
					<h1 class="g_title_01" id='popuptitle'><?php echo $title; ?></h1>
				</div>
			</div>
			<div class="grid terms_wrap">
				<div class="terms_box" id='popupbody1'><?php echo $config['cf_contract_cancel'] ?></div>
				<div class="terms_box" id='popupbody2' style="display: none;"><?php echo $config['cf_collection_privacy'] ?></div>
			</div>
			<div class="btn_group bottom none"><button type="button" class="btn big green" id="agree"><span>동의합니다</span></button></div>
			<!-- 컨텐츠 종료 -->
		</div>
		<a class="btn_closed" onclick="$('#popup_container').css('display','none')"><span class="blind">닫기</span></a>
	</div>
</section>
<!-- //popup -->

<script>
var zipcode = "";
var canvasDiv;
var context;
var canvasWidth = 556;
var canvasHeight = 159;

var clickX_simple = new Array();
var clickY_simple = new Array();
var clickDrag_simple = new Array();
var paint_simple;
var canvas_simple;
var context_simple;
$(function() {

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
        }
    });

    // 배송지목록
    $("#order_address").on("click", function() {
        var url = this.href;
        window.open(url, "win_address", "left=100,top=100,width=800,height=600,scrollbars=1");
        return false;
    });

    //전체 동의
    $("#chk_all_stipulation").on("click", function() {
        var chk = $("#chk_all_stipulation").is(":checked");
        if(chk){
        	$("#chk_auto_billing").prop("checked",chk);
        	$("#chk_user_thirdparty_privacy").prop("checked",chk);
        }
    });

    var agreeBtnID = "";

    $('#btn_auto_billing').click(function () {
        $("#popuptitle").text("계약서 확인 및 동의(필수)");
        $("#popupbody1").css("display","");
        $("#popupbody2").css("display","none");

        agreeBtnID = "chk_auto_billing";

        $("#popup_container").css("display","");
    });

    $('#btn_user_thirdparty_privacy').click(function () {
        $("#popuptitle").text("마케팅 활용 동의");
        $("#popupbody1").css("display","none");
        $("#popupbody2").css("display","");

        agreeBtnID = "chk_user_thirdparty_privacy";

        $("#popup_container").css("display","");
    });

    $('#agree').click(function () {
		$('#'+agreeBtnID).click();
		agreeBtnID = "";
        $("#popup_container").css("display","none");
    });


    var timer = 180;
    $("#btn_send_auth_key").on("click", function() {

    	if($('#od_hp').val() ==  ''){
			alert("등록된 휴대전화번호가 없습니다. 회원정보에서 휴대전화 번호를 입력바랍니다.");
			return false;
		}
		
    	<?php if ($default['de_card_test']) {   // 테스트 결제시 ?>

        if(!confirm("결제 테스트 모드 : 인증 테스트하시겠습니까?\n취소시 인증완료로 처리됩니다.")){
        	$("#auth_yn").val('Y');
        	$('#auth_key').prop("disabled", true);
        	$('#btn_auth').prop("disabled", true);
        	$('#btn_send_auth_key').prop("disabled", true);
        	$('#btn_send_auth_key').text("인증완료");

            return false;
        }

       <?php } ?>

    	var dest_phone = $('#od_hp').val().replace(/[\-]/g, "");

    	$.post(
                "./orderform.sub.rental.sms_sender_auth.php",
                { name: encodeURIComponent($('#od_name').val()),  auth_phoneNumber: dest_phone},
                function(data) {
                    if(data.result =='S'){
                    	$("#div_alert").html(data.view_text);
                        $('#div_auth').css('display','block');
                        timer = 180;
                        var interval = setInterval(function(){
                            minutes = parseInt(timer / 60, 10);
                            seconds = parseInt(timer % 60, 10);
                    		
                            minutes = minutes < 10 ? "0" + minutes : minutes;
                            seconds = seconds < 10 ? "0" + seconds : seconds;
                    		
                            
                            $('#timer').text(minutes + ':'+seconds);

                            if (--timer < 0) {
                                timer = 0;
                                clearInterval(interval);
                                if($('#auth_yn').val() != 'Y'){
                                    alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
                                }
                            }
                        }, 1000);
                    }else {
                    	$("#div_alert").html(data.view_text);
                    }
                }
            );
    });
    
    $('#btn_auth').click(function () {
    	if(timer == 0){
    		alert('인증시간이 만료되었습니다.\n다시 인증해주세요');
    		return;
        }
        
    	var dest_phone = $('#od_hp').val().replace(/[\-]/g, "");
    	$.post(
                "<?php echo G5_BBS_URL.'/ajax.register_authkey_certify.php'; ?>",
                { auth_key: $('#auth_key').val(),  auth_phoneNumber: dest_phone},
                function(data) {
                    
                    if(data.result =='S'){
                    	$("#div_alert").html(data.view_text);
                    	$("#auth_yn").val('Y');
                    	timer = 0;

                    	$('#auth_key').prop("disabled", true);
                    	$('#btn_auth').prop("disabled", true);
                    	$('#btn_send_auth_key').prop("disabled", true);
                    	$('#btn_send_auth_key').text("인증완료");
                        
                    }else {
                    	$("#div_alert").html(data.view_text);
                    }
                }
            );
	});

    function prepareSimpleCanvas()
    {
        //alert('test');
    	// Create the canvas (Neccessary for IE because it doesn't know what a canvas element is)
    	var canvasDiv = document.getElementById('canvasSimpleDiv');
        var canvasTxt = document.querySelector('.signature_txt');
    	canvas_simple = document.createElement('canvas');
    	canvas_simple.setAttribute('width', canvasWidth);
    	canvas_simple.setAttribute('height', canvasHeight);
    	canvas_simple.setAttribute('id', 'canvasSimple');
    	canvasDiv.appendChild(canvas_simple);
    	if(typeof G_vmlCanvasManager != 'undefined') {
    		canvas_simple = G_vmlCanvasManager.initElement(canvas_simple);
    	}
    	context_simple = canvas_simple.getContext("2d");

    	document.getElementById('orgCanvasSimple').value = document.getElementById("canvasSimple").toDataURL();
    	
    	// Add mouse events
    	// ----------------
    	$('#canvasSimple').mousedown(function(e)
    	{
			canvasDiv.style.position = 'inherit';
            canvasTxt.style.display = 'none';
    		// Mouse down location
    		var mouseX = e.pageX - this.offsetLeft;
    		var mouseY = e.pageY - this.offsetTop;
    		
    		paint_simple = true;
    		addClickSimple(mouseX, mouseY, false);
    		redrawSimple();
    	});
    	
    	$('#canvasSimple').mousemove(function(e){
    		if(paint_simple){
    			addClickSimple(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
    			redrawSimple();
    		}
    	});
    	
    	$('#canvasSimple').mouseup(function(e){
    		paint_simple = false;
    	  	redrawSimple();
    	});
    	
    	$('#canvasSimple').mouseleave(function(e){
    		paint_simple = false;
    	});
    	
    	$('#clearCanvasSimple').mousedown(function(e)
    	{
    		clickX_simple = new Array();
    		clickY_simple = new Array();
    		clickDrag_simple = new Array();
    		clearCanvas_simple(); 
			canvasDiv.style.position = 'relative';
            canvasTxt.style.display = 'block';
    	});
    	
    	// Add touch event listeners to canvas element
    	canvas_simple.addEventListener("touchstart", function(e)
    	{
			canvasDiv.style.position = 'inherit';
            canvasTxt.style.display = 'none';
    		// Mouse down location
    		var mouseX = (e.changedTouches ? e.changedTouches[0].pageX : e.pageX) - this.offsetLeft,
    			mouseY = (e.changedTouches ? e.changedTouches[0].pageY : e.pageY) - this.offsetTop;
    		
    		paint_simple = true;
    		addClickSimple(mouseX, mouseY, false);
    		redrawSimple();
    	}, false);
    	canvas_simple.addEventListener("touchmove", function(e){
    		canvasDiv.style.position = 'inherit';
            canvasTxt.style.display = 'none';
    		var mouseX = (e.changedTouches ? e.changedTouches[0].pageX : e.pageX) - this.offsetLeft,
    			mouseY = (e.changedTouches ? e.changedTouches[0].pageY : e.pageY) - this.offsetTop;
    					
    		if(paint_simple){
    			addClickSimple(mouseX, mouseY, true);
    			redrawSimple();
    		}
    		e.preventDefault()
    	}, false);
    	canvas_simple.addEventListener("touchend", function(e){
    		paint_simple = false;
    	  	redrawSimple();
    	}, false);
    	canvas_simple.addEventListener("touchcancel", function(e){
    		paint_simple = false;
    	}, false);
    }

    function addClickSimple(x, y, dragging)
    {
    	clickX_simple.push(x);
    	clickY_simple.push(y);
    	clickDrag_simple.push(dragging);
    }

    function clearCanvas_simple()
    {
    	context_simple.clearRect(0, 0, canvasWidth, canvasHeight);
    }

    function redrawSimple()
    {
    	clearCanvas_simple();
    	
    	var radius = 3;
    	context_simple.strokeStyle = "#000000";
    	context_simple.lineJoin = "round";
    	context_simple.lineWidth = radius;
    			
    	for(var i=0; i < clickX_simple.length; i++)
    	{		
    		context_simple.beginPath();
    		if(clickDrag_simple[i] && i){
    			context_simple.moveTo(clickX_simple[i-1], clickY_simple[i-1]);
    		}else{
    			context_simple.moveTo(clickX_simple[i]-1, clickY_simple[i]);
    		}
    		context_simple.lineTo(clickX_simple[i], clickY_simple[i]);
    		context_simple.closePath();
    		context_simple.stroke();
    	}
    }

    prepareSimpleCanvas();
});

function forderform_check(f)
{

	if(f.rdo_auto_billing.value != "1")
	{
		alert("계약서 확인 및 동의에 동의해 주십시오. 동의하셔야 계약 진행이 가능합니다.");
		f.chk_all_stipulation.focus();
        return false;
	}
	if(f.auth_yn.value != "Y")
	{
		alert("본인 인증이 완료되어야 계약이 가능합니다.");
		f.od_hp.focus();
        return false;
	}

	var canvasSimpleData = document.getElementById("canvasSimple").toDataURL();
	f.cust_file.value = canvasSimpleData;
	
	if($("#orgCanvasSimple").val() == $("#cust_file").val())
	{
		alert("계약서 서명란에 사인을 입력하셔야 계약이 가능합니다.");
		return false;
	}

	alert("감사합니다. \n전문상담사가 해지신청 내역 확인 후 빠르게 연락 드리도록 하겠습니다.\n 확인 버튼 선택 시 상품 반환 주소 및 희망 방문 수거일자(신청일로 이틀 이후)\n지정 이 화면으로 이동 됩니다.");

    f.submit();
}

//구매자 정보와 동일합니다.
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

    //calculate_sendcost(String(f.od_b_zip.value));
}
</script>

<?php
include_once('./_tail.php');
?>