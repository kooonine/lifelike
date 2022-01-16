<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');
?>

<!-- container -->
<div id="container">
    <!-- lnb -->
    <div id="lnb" class="header_bar">
        <h1 class="title"><span>펭귄 테스트</span></h1>
    </div>
    <!-- //lnb -->
    <div class="content mypage sub">
        <!-- 컨텐츠 시작 -->
        <div class="grid border_box divide_inp">
        	<form id="frm" name="frm">
            <div class="inp_wrap">
                <div class="title count3"><label for="f2">RFID</label></div>
                <div class="inp_ele count6">
                    <div class="input"><input type="text" id="RFID" name="RFID" value=""></div>
                </div>
            </div>
            
            <div class="inp_wrap">
                <div class="title count3"><label for="f3">상태값</label></div>
                <div class="inp_ele r_btn count6">
                <div class="input">
                    <select id="LAUNDRY_STEP" name="LAUNDRY_STEP">
                        <option value="04">04 공장입고 : 상태값 변경 [수거완료] -> [세탁중]</option>
                        <option value="05">05 공장출고 : (이력만 저장) </option>
                        <option value="06">06 장기보관중 : 상태값 변경(보관만) [세탁중] -> [보관중]</option>
                        <option value="07">07 고객출고 : 운송장번호 입력 & 상태값 변경 [세탁중] 또는 [보관중] -> [배송중]</option>
                        <option value="11">11 반려</option>
                    </select>
                    </div>
                </div>
            </div>
            
            <div class="inp_wrap">
                <div class="title count3"><label for="f3">상태별 일자</label></div>
                <div class="inp_ele r_btn count6">
                    <div class="input"><input type="text" id="LAUNDRY_DATE" name="LAUNDRY_DATE" value="<?php echo G5_TIME_YMD?>" ></div>
                </div>
            </div>
            
            <div class="inp_wrap">
                <div class="title count3"><label for="f3">오염추가비용</label></div>
                <div class="inp_ele r_btn count6">
                    <div class="input"><input type="number" id="PLT_PRICE" name="PLT_PRICE" ></div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="title count3"><label for="f3">오염항목</label></div>
                <div class="inp_ele r_btn count6">
                    <div class="input">
                    <select id="PLT_CODE" name="PLT_CODE">
                        <option value="">선택없음</option>
                        <option value="01">부분오염</option>
                        <option value="02">음식물유착</option>
                        <option value="03">황변제거</option>
                        <option value="04">매직/잉크</option>
                        <option value="05">부분토사</option>
                        <option value="06">토사</option>
                        <option value="07">혈흔</option>
                        <option value="08">분비물</option>
                        <option value="09">전체오염</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="inp_wrap">
                <div class="title count3"><label for="f3">반려사유</label></div>
                <div class="inp_ele r_btn count6">
                    <div class="input">
                    <select id="REJCT_CODE" name="REJCT_CODE">
                        <option value="">선택없음</option>
                        <option value="01">고객변심</option>
                        <option value="02">세탁거부</option>
                    </select>
                    </div>
                </div>
            </div>
            
            <div class="inp_wrap">
                <div class="title count3"><label for="f3">송장번호</label></div>
                <div class="inp_ele r_btn count6">
                    <div class="input"><input type="text" id="INVOICE_NO" name="INVOICE_NO" ></div>
                </div>
            </div>
            
            <div class="btn_group two">
                <button type="button" class="btn big green" onclick="laundryorder();"><span>세탁신청 정보 요청</span></button>
                <button type="button" class="btn big green" onclick="laundryorderSend();"><span>세탁신청 정보 전달</span></button>
                <button type="button" class="btn big green" onclick="laundryorderproc();"><span>세탁신청 처리</span></button>
            </div>
            
            <div class="inp_wrap">
                <div class="title count3"><label for="f5">결과값</label></div>
                <div class="inp_ele count6" id="result">
                </div>
            </div>
            </form>
        </div>
        <!-- 컨텐츠 끝 -->
    </div>
</div>
<!-- //container -->

<script>
$(function() {
});
function laundryorder()
{
	
    $.ajax({
            url: "./laundryorder.php",
            type: "POST",
            data: {
                "TR_NAME": "LAUNDRY_ORDER_POST"
                ,"RFID" : $("#RFID").val()
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data) {
                $("#result").html(JSON.stringify(data));
            }
        });
}
function laundryorderSend()
{
	
    $.ajax({
            url: "./laundryorderSend.php",
            type: "POST",
            data: {
                "TR_NAME": "LAUNDRY_ORDER_POST"
                ,"RFID" : $("#RFID").val()
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data) {
                $("#result").html(JSON.stringify(data));
            }
        });
}

function laundryorderproc()
{
    $.ajax({
        url: "./laundryorderproc.php",
        type: "POST",
        data: {
            "TR_NAME": "STEP_POST"
            ,"RFID" : $("#RFID").val()
            ,"LAUNDRY_STEP" : $("#LAUNDRY_STEP").val()
            ,"LAUNDRY_DATE" : $("#LAUNDRY_DATE").val()
            ,"PLT_PRICE" : $("#PLT_PRICE").val()
            ,"PLT_CODE" : $("#PLT_CODE").val()
            ,"REJCT_CODE" : $("#REJCT_CODE").val()
            ,"INVOICE_NO" : $("#INVOICE_NO").val()
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            $("#result").html(JSON.stringify(data));
        }
    });
}
</script>