<?php
$sub_menu = "200150";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');


$g5['title'] = 'SMS 발송';
include_once ('../admin.head.php');

$token = get_admin_token();
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 메시지 발송<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	<form name="fconfigform" id="fconfigform" method="post" data-parsley-validate class="form-horizontal form-label-left">
	<input type="hidden" name="token" value="<?php echo $token ?>" id="token">
	<input type="hidden" name="dest_phone" value="" id="dest_phone">

	  <div class="tbl_frm01 tbl_wrap">
      <table>
        <colgroup>
        <col class="grid_4">
        <col>
        <col>
        <col>
        </colgroup>
        <tr>
          <th scope="col">발신번호 선택</th>
          <td colspan="2" id="tdSender">

          		<div class="col-sm-12">
            		<div class="col-sm-2">
            		<label>기존번호</label>&nbsp;&nbsp;&nbsp;
            		</div>
              		<div class="col-sm-10" id="dvsend_phone">
                      <?php
                      $authType = "l";
                      include_once('configform_sms_sender_auth.php');
                      ?>
                    </div>
            	</div>

                <div id="dvSendnumber" class="col-sm-12" style="padding-top: 10px;">
            		<div class="col-sm-2">
                		<label>신규 발신번호 등록</label>&nbsp;&nbsp;&nbsp;
                	</div>
                	<div class="col-sm-10">
                        <input type="number" value="010" id="sendnumber" class="frm_input" maxlength="12">
                        명칭:
                        <input type="text" value="" id="comment" class="frm_input" maxlength="20">
                        <button class="btn btn-default" type="button" id="btnNewSendnumber">신규 발신번호 인증요청</button>
                    </div>
                </div>

                <div id="dvSendnumberAuth" class="col-md-12 col-sm-12 hidden">
                	<label style="width: 150px;">인증번호 확인</label>
                    <input type="text" value="" id="pincode" class="frm_input" maxlength="6">
                    <button  class="btn btn-default" type="button" id="btnNewSendnumberCheck">인증확인</button>
                    <span id="spnSendnumberAuth" class="red"></span>
                </div>

                <script>
                var authTime = 180;
                var setAuthTimer = null;
                var authsendnumber = "";
                var authcomment = "";

                function authTimeChk() {
                    var authTimeTxt = "";

                    authTimeTxt = Math.floor(authTime/60) + ":" + (authTime%60);
                    $('#spnSendnumberAuth').text(authTimeTxt);
                    authTime = authTime-1;

                    clearTimeout(setAuthTimer);
                    if(authTime > 0) {
                        setAuthTimer = setTimeout(authTimeChk, 1000);
                    } else {
                    	alert("인증시간이 만료되었습니다. 다시 인증요청바랍니다.");
                    	authTimeChkClose();
                    }
                }

                function authTimeChkClose() {
                	$('#pincode').val("");
                	$('#dvSendnumberAuth').removeClass("hidden").addClass("hidden");
                	$("#sendnumber").prop("disabled", false);
                	$("#comment").prop("disabled", false);
                	setAuthTimer = null;
                	authTime = 180;
                	authsendnumber = "";
                	authcomment = "";
                }

                $('#btnNewSendnumber').click(function(){
                    if($("#sendnumber").val() == "" || $("#sendnumber").val() == "010")
                    {
                        alert("신규 발신번호를 입력바랍니다.");
                        return;
                    }

                    authsendnumber = $("#sendnumber").val();
                    authcomment = $("#comment").val();

                	$.post(
                            "configform_sms_sender_auth.php",
                            { sendnumber: $("#sendnumber").val(), comment: $("#comment").val(), authType:'s' },
                            function(data) {
                                //alert(data);
        	                	var responseJSON = JSON.parse(data);
                                if(responseJSON['result_code'] == "200")
                                {
                                	$('#dvSendnumberAuth').removeClass("hidden");
                                    alert("요청하신 발신번호로 인증번호를 전송하였습니다. 3분이내로 인증번호를 입력바랍니다.");
                                    $('#pincode').val("");
                                    $('#pincode').focus();

                                    authTime = 180;
                                    authTimeChk();

                                	$("#sendnumber").prop("disabled", true);
                                	$("#comment").prop("disabled", true);

                                } else if(responseJSON['result_code'] == "500") {
                                	alert("이미 등록된 번호입니다.");
                                } else {
                                    alert("오류가 발생했습니다. 재시도 바랍니다.");
                                }
                            }
                        );
                });


                $('#btnNewSendnumberCheck').click(function(){
                    if(authTime <= 0) {
                        alert("인증 시간이 만료되었습니다.");
                        authTimeChkClose();
                        return;
                    }
                	if($("#pincode").val() == "")
                    {
                        alert("인증번호를 입력바랍니다.");
                        return;
                    }

                	$.post(
                            "configform_sms_sender_auth.php",
                            { sendnumber: authsendnumber, comment: authcomment, pincode: $("#pincode").val(), authType:'a' },
                            function(data) {
                                //$option_table.empty().html(data);
        	                	var responseJSON = JSON.parse(data);
                                if(responseJSON['result_code'] == "200")
                                {
                                	alert("새로운 발신번호가 등록되었습니다.");
                                    authTimeChkClose();

                                    $.post(
                                            "configform_sms_sender_auth.php",
                                            { authType:'l' },
                                            function(data) {
                                                $("#dvsend_phone").empty().html(data);
                                            }
                                        );

                                } else if(responseJSON['result_code'] == "600") {
                                	alert("인증번호가 일치하지 않습니다.");
                                } else if(responseJSON['result_code'] == "700") {
                                	alert("인증 시간이 만료되었습니다. 재시도 바랍니다.");
                                    authTimeChkClose();
                                } else {
                                    alert("오류가 발생했습니다. 재시도 바랍니다.");
                                }
                            }
                        );
                });

                </script>
          </td>
        </tr>
        <tr>
          <th scope="col">수신거부자</th>
          <td colspan="2">
            <div class="col-sm-2">
            <label><input type="radio" name="sms_recive"  value="except" checked="checked" /> 제외</label>&nbsp;&nbsp;&nbsp;
            <label><input type="radio" name="sms_recive"  value="include" /> 포함</label>
            </div>
          </td>
        </tr>
        <tr>
          <th scope="col">발송타입
          </th>
          <td colspan="2">
            <div class="col-sm-2">
              	<input type="radio" name="sendType" value="0" id="sendType0" checked="checked" > <label for="sendType0"> 즉시</label>&nbsp;&nbsp;&nbsp;
                <input type="radio" name="sendType" value="1" id="sendType1" > <label for="sendType1"> 예약</label>
            </div>

            <div class='input-group date hidden' id='senddatepicker'>
                <input type='text' class="form-control" id="send_time" name="send_time" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>

          </td>

        </tr>
        <tr>
            <th scope="col" rowspan="4">
                발송대상 선택
            </th>
            <td colspan="2">
              <div class="btn-group col-sm-12" >
                <label><input type="radio" name="sms_reciver_type" value = "customer" checked="checked"/> 고객</label>&nbsp;&nbsp;&nbsp;
                <label hidden><input type="radio" name="sms_reciver_type" value = "excel"/> 엑셀 </label>
                <span hidden>(필드구성 : 발송시간|수신자번호|수신자이름|발신자번호|발신자이름|제목|내용)</span>
              </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">

            <label for="sfl" class="sound_only">검색대상</label>
            <select name="sfl" id="sfl">
                <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
                <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option>
                <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
                <option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
                <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>연락처</option>
                <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대전화번호</option>
            </select>
            <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
            <input type="button" class="btn btn-default" value="검색" id="btnSearch">

            </td>
        </tr>
        <tr>
            <th style="text-align:center;">검색대상</th>
            <th style="text-align:center;">발송대상</th>
        </tr>

        <tr>
            <td>
            	<div class="col-md-6 col-sm-12 text-left">
            		<h4>회원 목록</h4>
            	</div>
            	<div class="col-md-6 col-sm-12 text-right">
            		<button type="button" class="btn btn-default" onclick="hp_list_add()">선택추가</button>
            	</div>

            	<div class="col-md-12 col-sm-12">
            		<select name="mb_list" id="mb_list" class="select2_multiple form-control" multiple="multiple" size="5"></select>
            	</div>
            </td>
            <td style="text-align:center;">

            	<div class="col-md-6 col-sm-12 text-left">

            	</div>
            	<div class="col-md-6 col-sm-12 text-right">
            		<button type="button" class="btn btn-default" onclick="hp_list_del()">선택삭제</button>
            	</div>

            	<div class="col-md-12 col-sm-12">
            		<select name="hp_list" id="hp_list" class="select2_multiple form-control" multiple="multiple" size="5">
            		</select>
            	</div>
            </td>
        </tr>

        <tr>
          <th scope="col">메시지 구분</th>
          <td colspan="2">
            <div class="col-sm-12">
                <label><input type="radio" name="msg_type" value="sms" checked="checked" /> 단문</label>&nbsp;&nbsp;&nbsp;
                <label><input type="radio" name="msg_type" value="lms" /> 장문</label>
			</div>
          </td>
        </tr>
        <tr>
          <th scope="col">메시지 제목</th>
          <td colspan="2">
            <div class="container col-md-12 col-sm-12 col-xs-12">
          		<input type="text" name="msg_title"  value="" id="msg_title" class="form-control" >
          	</div>
          </td>
        </tr>
        <tr>
          <th>내용입력</th>
          <td colspan="2">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <textarea class="resizable_textarea form-control" id="msg_body" name="msg_body"></textarea>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                <span id="msg_body_counter"><font color='red'>0</font>/300</span>
              </div>
          </td>
        </tr>
        <tr>
        <td colspan="3">

        	  <div class="x_content">
        		  <div class="form-group">
        			<div class="col-md-12 col-sm-12 col-xs-12 text-right">

        			  <input type="button" class="btn btn-success" value="메시지 발송" id="btnSend"></input>

        			</div>
        		  </div>
        	  </div>

        </td>
        </tr>

      </table>

		  <div class="ln_solid"></div>
	  </div>


	</form>

	</div>
  </div>
</div>

<div id="num_book"></div>
<script>

$(function(){


});

$('#senddatepicker').datetimepicker({
    ignoreReadonly: true,
    allowInputToggle: true,
    format: 'YYYY-MM-DD HH:mm',
    locale : 'ko'
});

$('input[name="sendType"]').click(function(){

     if($(this).val() == '1'){
    	 $('#senddatepicker').removeClass("hidden");
     }else{
    	 $('#senddatepicker').removeClass("hidden").addClass("hidden");
    	 $('#send_time').val("");
     }
});

$('#msg_body').keyup(function (e){

    var content = $(this).val();
    var counter_id = $(this).attr('id')+'_counter';


  //  $(this).height(((content.split('\n').length + 1) * 1.5) + 'em');
    $('#'+counter_id).html('<font color="red">'+content.length + '</font>/300');
});

$("#stx").keyup(function(e){if(e.keyCode == 13) $("#btnSearch").click(); });

$("#btnSearch").click(function(){
    if($("#stx").val() == "") {
        alert("검색어를 입력하세요.");
    	$("#stx").focus();
    	return;
    }

	$targetSel = $("#mb_list");

    $.post(
            "ajax.configform_sms_sender_search.php",
            { stx: $("#stx").val(), sfl: $("#sfl").val() },
            function(data) {
                //alert(data);

            	var responseJSON = JSON.parse(data);
            	var count = responseJSON.length;
            	$targetSel.empty();

            	if(count == 0) {
            		$targetSel.append($('<option>', {value:"", text: "검색 결과가 없습니다."}));
                	return;
            	}

				for(i=0; i<count; i++) {
					$option =$('<option>', {value:responseJSON[i]['mb_hp'], text: responseJSON[i]['mb_name']+"("+responseJSON[i]['mb_hp']+")"})

					$option.attr("mb_name", responseJSON[i]['mb_name']);
					$option.attr("data", responseJSON[i]['mb_id']);
					$targetSel.append($option);
   				}


            }
        );
});

$("#btnSend").click(function(){

	var dest_phone = sms_obj.phone_number.join(",").replace(/[\-]/g, "");
	$("#dest_phone").val(dest_phone);

	$("#fconfigform").attr("action", "ajax.configform_sms_send.php").submit();
    return true;
});

function hp_list_add()
{
	$mb_list =  $('#mb_list :selected');

    if ($mb_list.length < 0) {
        alert('추가할 목록을 선택해주세요.');
        return;
    }

    $mb_list.each(function(i, sel){
        sms_obj.person_add($(sel).attr("data"), $(sel).attr("mb_name"), $(sel).val());
    	//alert($(sel).attr("data")+","+$(sel).text()+","+$(sel).val());
    });

}

function hp_list_del()
{
    var hp_list = document.getElementById('hp_list');

    if (hp_list.selectedIndex < 0) {
        alert('삭제할 목록을 선택해주세요.');
        return;
    }

    var regExp = /(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?[0-9]{3,4}-?[0-9]{4}/,
        hp_number_option = hp_list.options[hp_list.selectedIndex],
        result = (hp_number_option.outerHTML.match(regExp));
    if( result !== null ){
        sms_obj.phone_number = sms_obj.array_remove( sms_obj.phone_number, result[0] );
    }
    hp_list.options[hp_list.selectedIndex] = null;
}

var sms_obj={
	    phone_number : [],
	    el_box : "#num_book",
	    person_is_search : false,
	    array_remove : function(arr, item){
	        for(var i = arr.length; i--;) {
	          if(arr[i] === item) {
	              arr.splice(i, 1);
	          }
	        }
	        return arr;
	    },
	    book_all_checked : function(chk){
	        var bk_no = document.getElementsByName('bk_no');

	        if (chk) {
	            for (var i=0; i<bk_no.length; i++) {
	                bk_no[i].checked = true;
	            }
	        } else {
	            for (var i=0; i<bk_no.length; i++) {
	                bk_no[i].checked = false;
	            }
	        }
	    },
	    person_add : function(bk_no, bk_name, bk_hp){
	        var hp_list = document.getElementById('hp_list');
	        var item    = bk_name + " (" + bk_hp + ")";
	        var value   = 'p,' + bk_no;

	        for (i=0; i<hp_list.length; i++) {
	            if (hp_list[i].value == value) {
	                alert('이미 같은 목록이 있습니다.');
	                return;
	            }
	        }
	        if( jQuery.inArray( bk_hp , this.phone_number ) > -1 ){
	           alert('목록에 이미 같은 휴대전화 번호가 있습니다.');
	           return;
	        } else {
	            this.phone_number.push( bk_hp );
	        }
	        hp_list.options[hp_list.length] = new Option(item, value);
	    },
	    person_multi_add : function(){
	        var bk_no = document.getElementsByName('bk_no');
	        var ck_no = '';
	        var count = 0;

	        for (i=0; i<bk_no.length; i++) {
	            if (bk_no[i].checked==true) {
	                count++;
	                ck_no += bk_no[i].value + ',';
	            }
	        }

	        if (!count) {
	            alert('하나이상 선택해주세요.');
	            return;
	        }

	        var hp_list = document.getElementById('hp_list');
	        var item    = "개인 (" + count + " 명)";
	        var value   = 'p,' + ck_no;

	        for (i=0; i<hp_list.length; i++) {
	            if (hp_list[i].value == value) {
	                alert('이미 같은 목록이 있습니다.');
	                return;
	            }
	        }

	        hp_list.options[hp_list.length] = new Option(item, value);
	    }
}


<?php
if ($_POST['act_button'] == "SMS") {
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $mb = get_member($_POST['mb_id'][$k]);
        if (!$mb['mb_id']) {
            continue;
        }

        if(is_hp($mb['mb_hp'])) {
            echo 'sms_obj.person_add("'.$mb['mb_id'].'", "'.$mb['mb_name'].'", "'.$mb['mb_hp'].'");'.PHP_EOL;
        }
    }
}
?>
</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>
