<?php
if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

    if ($mb['mb_level'] < $member['mb_level'])
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    $required_mb_id = 'readonly';
    $required_mb_password = '';
    $html_title = '수정';

    $mb['mb_name'] = get_text($mb['mb_name']);
    $mb['mb_nick'] = get_text($mb['mb_nick']);
    $mb['mb_email'] = get_text($mb['mb_email']);
    $mb['mb_homepage'] = get_text($mb['mb_homepage']);
    $mb['mb_birth'] = get_text($mb['mb_birth']);
    $mb['mb_tel'] = get_text($mb['mb_tel']);
    $mb['mb_hp'] = get_text($mb['mb_hp']);
    $mb['mb_addr1'] = get_text($mb['mb_addr1']);
    $mb['mb_addr2'] = get_text($mb['mb_addr2']);
    $mb['mb_addr3'] = get_text($mb['mb_addr3']);
    $mb['mb_signature'] = get_text($mb['mb_signature']);
    $mb['mb_recommend'] = get_text($mb['mb_recommend']);
    $mb['mb_profile'] = get_text($mb['mb_profile']);
    $mb['mb_1'] = get_text($mb['mb_1']);
    $mb['mb_2'] = get_text($mb['mb_2']);
    $mb['mb_3'] = get_text($mb['mb_3']);
    $mb['mb_4'] = get_text($mb['mb_4']);
    $mb['mb_5'] = get_text($mb['mb_5']);
    $mb['mb_6'] = get_text($mb['mb_6']);
    $mb['mb_7'] = get_text($mb['mb_7']);
    $mb['mb_8'] = get_text($mb['mb_8']);
    $mb['mb_9'] = get_text($mb['mb_9']);
    $mb['mb_10'] = get_text($mb['mb_10']);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 본인확인방법
switch($mb['mb_certify']) {
    case 'hp':
        $mb_certify_case = '휴대전화';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';

if($mb['mb_block_login'] || $mb['mb_block_shop'] || $mb['mb_block_write'])
{
    $mb_intercept = '1';
} else {
    $mb_intercept = '0';
}

if ($mb['mb_intercept_date']) $g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '회원 '.$html_title;

include_once ('./admin.head.sub.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

$token = get_admin_token();
?>

<form name="fmember" id="fmember" action="./member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="mb_id">아이디<?php echo $sound_only ?></label></th>
        <td colspan="3">
            <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15"  maxlength="20">
        </td>
	</tr>
	<tr>
        <th scope="row"><label for="mb_password">회원유형</label></th>
        <td><?php if($row['mb_10'] == '1') echo '사업자';
                else echo '일반';?></td>
        <th scope="row"><label for="mb_password">비밀번호<?php echo $sound_only ?></label></th>
        <td>
        	<input type="button" value="임시 비밀번호 생성" class="btn" id="btn_new_password_modal">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_name">성명<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required frm_input" size="15"  maxlength="20"></td>
        <th scope="row"><label for="mb_nick">닉네임<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick" required class="required frm_input" size="15"  maxlength="20"></td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_email">E-mail<strong class="sound_only">필수</strong></label></th>
        <td><input type="text" name="mb_email" value="<?php echo $mb['mb_email'] ?>" id="mb_email" maxlength="100" required class="required frm_input email" size="30"></td>
        <th scope="row">E-mail 수신</th>
        <td>
            <input type="radio" name="mb_mailling" value="1" id="mb_mailling_yes" <?php echo $mb_mailling_yes; ?>>
            <label for="mb_mailling_yes">수신</label>
            <input type="radio" name="mb_mailling" value="0" id="mb_mailling_no" <?php echo $mb_mailling_no; ?>>
            <label for="mb_mailling_no">수신거부</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_hp">휴대전화번호</label></th>
        <td><input type="text" name="mb_hp" value="<?php echo $mb['mb_hp'] ?>" id="mb_hp" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="mb_sms_yes">SMS 수신</label></th>
        <td>
            <input type="radio" name="mb_sms" value="1" id="mb_sms_yes" <?php echo $mb_sms_yes; ?>>
            <label for="mb_sms_yes">수신</label>
            <input type="radio" name="mb_sms" value="0" id="mb_sms_no" <?php echo $mb_sms_no; ?>>
            <label for="mb_sms_no">수신거부</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="">생년월일</label></th>
        <td>
            <div class='input-group date datepicker' style="width: 150px;">
        	<input type="text" name="mb_birth" value="<?php echo $mb['mb_birth'] ?>" id="mb_birth" class="form-control" >
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
       	</td>
        <th scope="row"><label for="">성별</label></th>
        <td>
            <input type="radio" id="mb_sex_1" name="mb_sex" value="M" <?php if($mb['mb_sex'] == 'M') echo "checked='checked'";?>>
            <label for="mb_sex_1">남성</label>

            <input type="radio" id="mb_sex_2" name="mb_sex" value="F" <?php if($mb['mb_sex'] == 'F') echo "checked='checked'";?>>
            <label for="mb_sex_2">여성</label>
		</td>
    </tr>
    <tr>
        <th scope="row"><label for="">결혼유무</label></th>
        <td>
            <input type="radio" id="mb_1_" name="mb_1" value="" <?php if($mb['mb_1'] == '') echo "checked='checked'";?>>
            <label for="mb_1_">선택안함</label>

            <input type="radio" id="mb_1_0" name="mb_1" value="0" <?php if($mb['mb_1'] == '0') echo "checked='checked'";?>>
            <label for="mb_1_0">미혼</label>

            <input type="radio" id="mb_1_1" name="mb_1" value="1" <?php if($mb['mb_1'] == '1') echo "checked='checked'";?>>
            <label for="mb_1_1">기혼</label>
        </td>
        <th scope="row"><label for="">결혼기념일</label></th>
        <td>
            <div class='input-group date datepicker' style="width: 150px;">
        	<input type="text" name="mb_2" value="<?php echo $mb['mb_2'] ?>" id="mb_2" class="form-control" >
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
       </td>
    </tr>
    <tr>
        <th scope="row"><label for="">연락처</label></th>
        <td><input type="text" name="mb_tel" value="<?php echo $mb['mb_tel'] ?>" id="mb_tel" class="frm_input" size="15" maxlength="20"></td>
        <th scope="row"><label for="">가입경로</label></th>
        <td><?php
            if($mb['mb_9']=="1") echo "Mobile";
            else if($mb['mb_9']=="2") echo "APP";
            else echo "PC";
        ?></td>
    </tr>
    <tr>
        <th scope="row">주소</th>
        <td colspan="3" class="td_addr_line">
            <label for="mb_zip" class="sound_only">우편번호</label>
            <input type="text" name="mb_zip" value="<?php echo $mb['mb_zip1'].$mb['mb_zip2']; ?>" id="mb_zip" class="frm_input readonly" size="5" maxlength="6">
            <button type="button" class="btn_frmline" onclick="win_zip('fmember', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
            <input type="text" name="mb_addr1" value="<?php echo $mb['mb_addr1'] ?>" id="mb_addr1" class="frm_input readonly" size="60">
            <br>
            <input type="text" name="mb_addr2" value="<?php echo $mb['mb_addr2'] ?>" id="mb_addr2" class="frm_input" size="60">
            <br>
            <input type="text" name="mb_addr3" value="<?php echo $mb['mb_addr3'] ?>" id="mb_addr3" class="frm_input" size="60">
            <input type="hidden" name="mb_addr_jibeon" value="<?php echo $mb['mb_addr_jibeon']; ?>"><br>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="mb_img">회원이미지</label></th>
        <td>
            <?php echo help('이미지 크기는 <strong>넓이 '.$config['cf_member_img_width'].'픽셀 높이 '.$config['cf_member_img_height'].'픽셀</strong>로 해주세요.') ?>
            <input type="file" name="mb_img" id="mb_img">
		</td>
		<td colspan="2">
            <?php
            $mb_dir = substr($mb['mb_id'],0,2);
            $icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
            if (file_exists($icon_file)) {
                $icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
                echo '<img src="'.$icon_url.'" alt="">';
                echo '&nbsp;&nbsp;&nbsp;<label><input type="checkbox" id="del_mb_img" name="del_mb_img" value="1">삭제</label>';
            }
            ?>
        </td>
    </tr>
    <tr>
        <!-- th scope="row"><label for="mb_level">등급</label></th>
        <td><?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?></td -->
        <th scope="row">적립금</th>
        <td><a href="./operation/configform_saveMoney_management.php?sfl=mb_id&amp;stx=<?php echo $mb['mb_id'] ?>" target="_blank"><?php echo number_format($mb['mb_point']) ?></a> 원</td>
        <th scope="row">총 결제금액</th>
        <td><?php
        $od = sql_fetch("select sum(od_receipt_price) as od_receipt_price from lt_shop_order where mb_id = '".$mb['mb_id']."' and od_status in ('구매완료','리스중','보관중','세탁완료','수선완료');");
        echo number_format($od['od_receipt_price'])." 원";
        ?></td>
    </tr>

    <tr>
        <th scope="row"><label for="">불량회원 설정</label></th>
        <td colspan="3">
        <label><input type="radio" name="mb_intercept" value="0" id="mb_intercept0" onclick="$('.mb_block').prop('checked',false);" <?php echo get_checked($mb_intercept, "0")?>>설정안함</label>
        <label><input type="radio" name="mb_intercept" value="1" id="mb_intercept1" onclick="$('#mb_block_login').prop('checked',true);" <?php echo get_checked($mb_intercept, "1")?> >설정</label>
        (
            <label><input type="checkbox" class="mb_block" name="mb_block_login" value="1" id="mb_block_login" <?php echo get_checked($mb['mb_block_login'], "1")?> >로그인 차단</label>
            <label><input type="checkbox" class="mb_block" name="mb_block_shop" value="1" id="mb_block_shop" <?php echo get_checked($mb['mb_block_shop'], "1")?>>구매 차단</label>
            <label><input type="checkbox" class="mb_block" name="mb_block_write" value="1" id="mb_block_write" <?php echo get_checked($mb['mb_block_write'], "1")?>>글쓰기 차단</label>
        )
        <br/>
        <textarea name="mb_7" id="mb_7" style="width: 100%" placeholder="불량회원 설정 사유를 기입해주세요."><?php echo $mb['mb_7'] ?></textarea>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="mb_memo">메모</label></th>
        <td colspan="3"><textarea name="mb_memo" id="mb_memo" style="width: 100%"><?php echo $mb['mb_memo'] ?></textarea></td>
    </tr>

    <?php if ($config['cf_use_recommend']) { // 추천인 사용 ?>
    <tr>
        <th scope="row">추천인</th>
        <td ><?php echo ($mb['mb_recommend'] ? get_text($mb['mb_recommend']) : '없음'); // 081022 : CSRF 보안 결함으로 인한 코드 수정 ?></td>
        <th scope="row">추천 가입 횟수</th>
        <td >
        <?php
        $mb_recommender = sql_fetch("select count(*) as cnt from lt_member where mb_recommend = '".$mb['mb_id']."' and mb_leave_date = '' ");
        echo $mb_recommender['cnt'].' 회';
        ?>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <th scope="row">회원가입일</th>
        <td><?php echo $mb['mb_datetime'] ?></td>
        <th scope="row">최근접속일</th>
        <td><?php echo $mb['mb_today_login'] ?></td>
    </tr>

    </tbody>
    </table>
</div>
<div class="pull-right">
    <input type="submit" value="회원정보 변경" class="btn_submit btn" accesskey='s'>
</div>
</form>


<div id="modal_newpassword" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">임시 비밀번호 생성</h4>
  </div>
  <div class="modal-body">
  	<span> - 회원에게 임시 비밀번호를 발급한 후에는 발송된 번호로 비밀번호가 변경됩니다.</span>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th>발송방식</th>
            <td>
            <label><input type="checkbox" name="send_sms" value="1" id="send_sms"> 휴대전화으로 메시지 발송</label>
            <label><input type="checkbox" name="send_email" value="1" id="send_email"> 이메일 발송</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <input type="button"  value="확인" class="btn btn-success" id="btn_new_password">
    <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
  </div>
</div>
<!-- Modal content-->
</div>
</div>

<script>
$(function(){
	$('.datepicker').datetimepicker({
	    ignoreReadonly: true,
	    allowInputToggle: true,
	    format: 'YYYY-MM-DD',
	    locale : 'ko'
	});

	$("#btn_new_password_modal").click(function(){
		$("#modal_newpassword").modal("show");
	});

	$("#btn_new_password").click(function(){
		var send_sms = $("#send_sms").is(":checked");
		var send_email = $("#send_email").is(":checked");

		if(!send_sms && !send_email){
			alert("발송 방식을 선택해주세요.");
		} else {
			$("#btn_new_password").prop("disabled",true);

			$.post(
	                "member_form_update.php",
	                {	w : "p"
		                , token : $("input[name='token']").val()
	                    , mb_id:  '<?php echo $mb['mb_id']?>'
	                    , send_sms:  (send_sms)?1:0
	                    , send_email: (send_email)?1:0
	                    },
	                function(data) {
	                    $("#btn_new_password").prop("disabled",false);
		    	        //alert(data);
	    	            if(data != ""){

    	                	var responseJSON = JSON.parse(data);
    	                	if(responseJSON.result == "S"){
        	                	var msg = "임시비밀번호가 발송되었습니다.";
        	                	if(responseJSON.alert != "") msg += "\n"+responseJSON.alert;
        	                	//if(responseJSON.ch != "") msg += "\n"+responseJSON.ch;
    	                		alert(msg);
    	                		$("#modal_newpassword").modal("hide");
    	                    }else {
    	                    	alert(responseJSON.alert);
    	                        return false;
    	                	}
	    	            }
	                }
	            );

		}
	});

});

function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
        alert('아이콘은 이미지 파일만 가능합니다.');
        return false;
    }

    if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
        alert('회원이미지는 이미지 파일만 가능합니다.');
        return false;
    }

    return true;
}
</script>
