<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<script>
    // 현금영수증 MAIN FUNC
    function  jsf__pay_cash( form )
    {
        jsf__show_progress(true);

        if ( jsf__chk_cash( form ) == false )
        {
            jsf__show_progress(false);
            return;
        }

        form.submit();
    }

    // 진행 바
    function  jsf__show_progress( show )
    {
        if ( show == true )
        {
            window.show_pay_btn.style.display  = "none";
            window.show_progress.style.display = "inline";
        }
        else
        {
            window.show_pay_btn.style.display  = "inline";
            window.show_progress.style.display = "none";
        }
    }

    // 포맷 체크
    function  jsf__chk_cash( form )
    {
        if (  form.tr_code[0].checked )
        {
            if ( form.id_info.value.length != 10 &&
                 form.id_info.value.length != 11 &&
                 form.id_info.value.length != 13 )
            {
                alert("주민번호 또는 휴대전화번호를 정확히 입력해 주시기 바랍니다.");
                form.id_info.select();
                form.id_info.focus();
                return false;
            }
        }
        else if (  form.tr_code[1].checked )
        {
            if ( form.id_info.value.length != 10 )
            {
                alert("사업자번호를 정확히 입력해 주시기 바랍니다.");
                form.id_info.select();
                form.id_info.focus();
                return false;
            }
        }
        return true;
    }

    function  jsf__chk_tr_code( form )
    {
        var span_tr_code_0 = document.getElementById( "span_tr_code_0" );
        var span_tr_code_1 = document.getElementById( "span_tr_code_1" );

        if ( form.tr_code[0].checked )
        {
            span_tr_code_0.style.display = "block";
            span_tr_code_1.style.display = "none";
        }
        else if (form.tr_code[1].checked )
        {
            span_tr_code_0.style.display = "none";
            span_tr_code_1.style.display = "block";
        }
    }

</script>
<br/>
<div class="layer">
	<div class="inner_layer">
		<div class="content comm sub">
    		<div class="grid" style="width:90%">
    			<div class="title_bar">
    				<h1 class="g_title_01"><?php echo $g5['title']; ?></h1>
    			</div>

				<div class="title_bar none">
					<h1 class="g_title_02">주문정보</h1>
				</div>

				<div class="order_list gray_box">
					<ul>
						<li>
							<span class="item">주문 번호</span>
							<strong class="result"><?php echo $od_id; ?></strong>
						</li>
						<li>
							<span class="item">상품 정보</span>
							<strong class="result"><?php echo $goods_name; ?></strong>
						</li>
						<li>
							<span class="item">주문자 이름</span>
							<strong class="result"><?php echo $od_name; ?></strong>
						</li>
						<li>
							<span class="item">주문자 E-Mail</span>
							<strong class="result"><?php echo $od_email; ?></strong>
						</li>
						<li>
							<span class="item">주문자 전화번호</span>
							<strong class="result"><?php echo $od_tel; ?></strong>
						</li>
					</ul>
				</div>

				<div class="title_bar none">
					<h1 class="g_title_02">현금영수증 발급 정보</h1>
				</div>

				<div class="order_list gray_box">
                    <form method="post" id="LGD_PAYINFO" action="<?php echo G5_SHOP_URL; ?>/lg/taxsave_result.php">
                    <input type="hidden" name="tx"        value="<?php echo $tx; ?>">
                    <input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
                    <ul>
                        <li>
                            <span class="item">거래 시각</span>
                            <strong class="result"><?php echo $trad_time; ?></strong>
                        </li>
                        <li>
                            <span class="item">발행 용도</span>
                            <strong class="result">
                                <input type="radio" name="tr_code" value="1" id="tr_code1" onClick="jsf__chk_tr_code( this.form )" checked>
                                <label for="tr_code1">소득공제용</label>
                                <input type="radio" name="tr_code" value="2" id="tr_code2" onClick="jsf__chk_tr_code( this.form )">
                                <label for="tr_code2">지출증빙용</label>
                            </strong>
                        </li>
                        <li>
                            <span class="item">
                                <label for="id_info">
                                    <span id="span_tr_code_0" style="display:inline">주민(휴대전화)번호</span>
                                    <span id="span_tr_code_1" style="display:none">사업자번호</span>
                                </label>
                            </span>
                            <strong class="result">
                                <input type="text" name="id_info" id="id_info" class="frm_input" size="16" maxlength="13"> ("-" 생략)
                            </strong>
                        </li>
                        <li>
                            <span class="item">거래금액 총합</span>
                            <strong class="result"><?php echo number_format($amt_tot); ?>원</strong>
                        </li>
                        <li>
                            <span class="item">공급가액</span>
                            <strong class="result"><?php echo number_format($amt_sup); ?>원<!-- ((거래금액 총합 * 10) / 11) --></strong>
                        </li>
                        <li>
                            <span class="item">봉사료</span>
                            <strong class="result"><?php echo number_format($amt_svc); ?>원</strong>
                        </li>
                        <li>
                            <span class="item">부가가치세</span>
                            <strong class="result"><?php echo number_format($amt_tax); ?>원<!-- 거래금액 총합 - 공급가액 - 봉사료 --></strong>
                        </li>
                    </ul>

            		<div class="btn_group"  id="show_pay_btn">
                        <button type="button" class="btn big green" onclick="jsf__pay_cash( this.form )"><span>등록요청</span></button>
					</div>
                    <span id="show_progress" style="display:none">
                        <b>등록 진행중입니다. 잠시만 기다려주십시오</b>
                    </span>

                    </form>
                </div>
				<br/>

			</div>
		</div>
	</div>
</div>
