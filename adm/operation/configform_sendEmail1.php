<?php
$sub_menu = "200110";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = 'Email 발송';
include_once ('../admin.head.php');


if (!isset($email_reciver_type)) $email_reciver_type = 'customer';
if (!isset($mb_mailling)) $mb_mailling = 1;

?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
    <form name="frm" method="post" action="./configform_sendEmail_send1.php" enctype="multipart/form-data">
    	<table>
    		<tr>
    			<td>발신자</td>
    			<td><input type="text" name="sender" /></td>
    		</tr>
    		<tr>
    			<td>발신자 메일</td>
    			<td><input type="text" name='email' /></td>
    		</tr>
    		<tr>
    			<td>수신자</td>
    			<td><textarea name="receiverlist" rows="5" cols="40"></textarea></td>
    		</tr>
    		<tr>
    			<td>수신자 URL</td>
    			<td><input type="text" name='receiverlistUrl' /></td>
    		</tr>
    		<tr>
    			<td>수신거부자 발송</td>
    			<td><input type="radio" name='rejectType ' value="2" checked /> 포함 
    			<input type="radio" name='rejectType ' value="3"/> 포함</td>
    		</tr>
    		<tr>
    			<td>제목</td>
    			<td><input type="text" name='subject' /></td>
    		</tr>
    		<tr>
    			<td>내용</td>
    			<td><textarea name="content" rows="15" cols="60"></textarea></td>
    		</tr>
    		<tr>
    			<td>파일첨부</td>
    			<td><input type="file" name='addfile' />(2MB 미만)</td>
    		</tr>
    		<tr>
    			<td>예약발송</td>
    			<td><input type="checkbox" name='sendType' value="1"/> 사용
    				<input type="text" name="sendDate" />(년-월-일 시:분:초)
    			</td>
    		</tr>
    		<tr>
    			<td>수신거부기능</td>
    			<td><input type="checkbox" name='useRejectMemo' value="1"/> 사용 <a href="http://help.cafe24.com/notice/notice_view.php?idx=1914" target=_blank><b><font color="red"> ※주의하세요</font></b> </a></td>
    		</tr>
                  <tr>
                        <td>메일 중복발송</td>
                        <td><input type="radio" name='overlapType' value="2" checked/> 중복발송허용 <input type="radio" name='overlapType' value="1" /> 중복제외 </td>
                  </tr>		
    		<tr>
    			<td>요청테스트</td>
    			<td><input type="checkbox" name='testFlag' value="1"/> 사용</td>
    		</tr>
    	</table>
    <input type="submit" value="발송요청" />
    </form>

	</div>
  </div>
</div>

<script>
$(function(){

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
	     }
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
						$option =$('<option>', {value:responseJSON[i]['mb_email'], text: responseJSON[i]['mb_name']+"("+responseJSON[i]['mb_email']+")"})

						$option.attr("mb_name", responseJSON[i]['mb_name']);
						$option.attr("data", responseJSON[i]['mb_id']);
						$targetSel.append($option);
	   				}


	            }
	        );
	});

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
	           alert('목록에 이미 같은 메일주소가 있습니다.');
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

function fconfigform_submit(f)
{
    f.action = "./configform_sendEmail_send.php";
    return true;
}
</script>




<!-- @END@ 내용부분 끝 -->

<?php
include_once ('../admin.tail.php');
?>

