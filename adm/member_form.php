<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

$mb = get_member($mb_id);

if (!$mb['mb_id'])
    alert('존재하지 않는 회원자료입니다.');

if ($mb['mb_level'] < $member['mb_level'])
	alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

// if ($mb['mb_level'] < $member['mb_level'])
//     alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.5');

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

$g5['title'] .= '회원 상세정보';

include_once ('./admin.head.sub.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>
<div class="container body">
<div class="main_container">
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  <table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col" class="text-right active">[<?php echo $mb['mb_id'] ?>]/[<?php echo $mb['mb_name'] ?>] 회원
        </th>
    </tr>
    </thead>
  </table>
  </div>
</div>

<div class="row">
    <div class="col-md-3 col-sm-3 col-xs-12">
        <div class="x_panel">
        
            <div class="tbl_frm01 tbl_wrap">
                <table>
                <colgroup>
                    <col class="grid_2">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                	<td><?php
                    $mb_dir = substr($mb['mb_id'],0,2);
                    $icon_file = G5_DATA_PATH.'/member_image/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
                    if (file_exists($icon_file)) {
                        $icon_url = G5_DATA_URL.'/member_image/'.$mb_dir.'/'.$mb['mb_id'].'.gif';
                        echo '<img src="'.$icon_url.'" alt="">';
                    }
                    ?>
                	</td>
                	<td>
                		<?php echo $mb['mb_name'] ?><br/><?php echo $mb['mb_id'] ?>
                	</td>
                </tr>
                <tr>
                	<td>닉네임</td>
                	<td><?php echo $mb['mb_nick'] ?></td>
               	</tr>
                <!-- tr>
                	<td>등급</td>
                	<td><?php echo $mb['mb_level'] ?></td>
               	</tr -->
                <tr>
                	<td>가입일</td>
                	<td><?php echo $mb['mb_datetime'] ?></td>
               	</tr>
                <tr>
                	<td>최종접속일</td>
                	<td><?php echo $mb['mb_today_login'] ?></td>
               	</tr>
                <tr>
                	<td>문의내역</td>
                	<td></td>
               	</tr>
                <tr>
                	<td>
                    	<form action="<?php echo G5_ADMIN_URL?>/operation/configform_sendEmail.php" target="_blank" method="post">
                    	<input type="hidden" name="act_button" value="EMAIL">
                    	<input type="submit" value="Email보내기" class="btn btn_02" >
            			<input type="hidden" name="mb_id[0]" value="<?php echo $mb['mb_id'] ?>">
            			<input type="hidden" name="chk[]" value="0">
                    	</form>
                   	</td>
                   	<td>
                    	<form action="<?php echo G5_ADMIN_URL?>/operation/configform_sms_send.php" target="_blank" method="post">
                    	<input type="hidden" name="act_button" value="SMS">
                    	<input type="submit" value="SMS보내기" class="btn btn_02" >
            			<input type="hidden" name="mb_id[0]" value="<?php echo $mb['mb_id'] ?>">
            			<input type="hidden" name="chk[]" value="0">
                    	</form>
                	</td>
               	</tr>
                <tr>
                	<td colspan="2">
                		<a href="./member_form.php?w=u&mb_id=<?php echo $mb['mb_id']?>" class="<?php echo (!isset($mode) && !$mode)?"h2_frm":""?>">■ 회원상세정보</a>
                	</td>
               	</tr>
                <tr>
                	<td colspan="2">
                		<a href="./member_form.php?w=&mb_id=<?php echo $mb['mb_id']?>&mode=1" class="<?php echo (isset($mode) && $mode && $mode=="1")?"h2_frm":""?>">■ 주문정보</a>
                	</td>
               	</tr>
                <tr>
                	<td colspan="2">
                		<a href="./member_form.php?w=&mb_id=<?php echo $mb['mb_id']?>&mode=2" class="<?php echo (isset($mode) && $mode && $mode=="2")?"h2_frm":""?>">■ 리스/케어서비스정보</a>
                	</td>
               	</tr>
                <tr>
                	<td colspan="2">
                		<a href="./member_form.php?w=&mb_id=<?php echo $mb['mb_id']?>&mode=3" class="<?php echo (isset($mode) && $mode && $mode=="3")?"h2_frm":""?>">■ 회원메모</a>
                	</td>
               	</tr>
                <tr>
                	<td colspan="2">
                		<a href="./member_form.php?w=&mb_id=<?php echo $mb['mb_id']?>&mode=4" class="<?php echo (isset($mode) && $mode && $mode=="4")?"h2_frm":""?>">■ 게시글 정보</a>
                	</td>
               	</tr>
                <tr>
                	<td colspan="2">
                		<a href="./member_form.php?w=&mb_id=<?php echo $mb['mb_id']?>&mode=5" class="<?php echo (isset($mode) && $mode && $mode=="5")?"h2_frm":""?>">■ 문의정보</a>
                	</td>
               	</tr>
                <tr>
                	<td colspan="2">
                		<a href="./member_form.php?w=&mb_id=<?php echo $mb['mb_id']?>&mode=6" class="<?php echo (isset($mode) && $mode && $mode=="6")?"h2_frm":""?>">■ 적립금/쿠폰</a>
                	</td>
               	</tr>
                <tr>
                	<td colspan="2">
                		<a href="./member_form.php?w=&mb_id=<?php echo $mb['mb_id']?>&mode=7" class="<?php echo (isset($mode) && $mode && $mode=="7")?"h2_frm":""?>">■ 로그인 IP정보</a>
                	</td>
               	</tr>
                </tbody>
				</table>
			</div>
        </div>
    </div>
    
    <div class="col-md-9 col-sm-9 col-xs-12">
        <div class="x_panel">
        <?php 
        if(isset($mode) && $mode) {
            if($mode == "1") include_once ('./member_form_1.php');
            else if($mode == "2") include_once ('./member_form_2.php');
            else if($mode == "3") include_once ('./member_form_3.php');
            else if($mode == "4") include_once ('./member_form_4.php');
            else if($mode == "5") include_once ('./member_form_5.php');
            else if($mode == "6") include_once ('./member_form_6.php');
            else if($mode == "7") include_once ('./member_form_7.php');
        } else {
            //기본 회원정보 수정
            include_once ('./member_form_mb.php');
        }
        ?>
        </div>
    </div>
</div>


</div>
</div>

<?php

include_once ('./admin.tail.sub.php');
?>