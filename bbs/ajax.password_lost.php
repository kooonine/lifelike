<?php
include_once('./_common.php');
require_once(G5_LIB_PATH.'/Unirest.php');
require_once(G5_LIB_PATH.'/ppurioSMS.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
$name = $_POST['name'];
$auth_type = $_POST['auth_type'];
$auth_text = $_POST['auth_text'];
$mb_id = $_POST['id'];

header('Content-Type: application/json');
if($name == '' || $auth_type  == '' || $auth_text  == '' || $mb_id == ''){
    $view_text = '<script>';
    $view_text .='alert("모든 정보를 입력해주세요")';
    $view_text .='</script>';
    $result = array("result" => "F", "view_text" => $view_text);
    $output =  json_encode($result);
    
    // 출력
    echo  urldecode($output);
    
    
}else {
    if($auth_type == 'phone'){
        $auth_text = hyphen_hp_number($auth_text);
        $sql = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from {$g5['member_table']} where mb_name = '$name' and mb_hp = '$auth_text' and mb_id = '$mb_id'";
    }else {
        $sql = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from {$g5['member_table']} where mb_name = '$name' and mb_email = '$auth_text' and mb_id = '$mb_id' ";
    }
    $mb = sql_fetch($sql);
    
    if (!$mb['mb_id']){
        $view_text = '<script>';
        $view_text .= 'alert("일치하는 회원정보가 없습니다")';
        $view_text .= '</script>';
        $result = array("result" => "F", "view_text" => $view_text);
        $output =  json_encode($result);
        
        // 출력
        echo  urldecode($output);
    }else {
        // 임시비밀번호 발급
        $change_password = rand(100000, 999999);
        // 어떠한 회원정보도 포함되지 않은 일회용 난수를 생성하여 인증에 사용
        $mb_nonce = md5(pack('V*', rand(), rand(), rand(), rand()));
        
        // 임시비밀번호와 난수를 mb_lost_certify 필드에 저장
        $sql = " update {$g5['member_table']} set mb_lost_certify = '$mb_nonce $change_password' where mb_id = '{$mb['mb_id']}' ";
        sql_query($sql);
        $view_text = '';
        $view_text .= '<p class="sm">';
        $view_text .= '입력하신 <span>[입력값]</span> 으로 인증번호가 발송되었습니다. 인증 완료 시 비밀번호가 재 발급 됩니다.';
        $view_text .='</p>';
        
        $dest_name = $mb['mb_name'];
        $dest_phone = $auth_text;
        $dest_phone = preg_replace("/[^0-9]/", "", $dest_phone);
        $arr_change_data = array();
        $arr_change_data['NAME'] = $mb['mb_name'];
        $arr_change_data['사원명'] = $mb['mb_name'];
        $arr_change_data['고객명'] = $mb['mb_name'];
        $arr_change_data['아이디'] = $mb['mb_id'];
        $arr_change_data['인증번호'] = $change_password;
        $arr_change_data['입력값'] = $auth_text;
        
        $result = "F";
        if($auth_type == 'phone'){
            $body = sms_autosend('회원', '본인확인 인증번호 발송', '', $dest_name, $dest_phone, $arr_change_data);
            if($body['result_code'] == '200')
            {
                $result = "S";
                $view_text .= '<script>';
                $view_text .= 'alert("입력하신 '.$auth_text.' 으로 인증번호가 발송되며, 인증 완료 후 비밀번호를 재설정해주세요)")';
                $view_text .= '</script>';
            }
            else
            {
                $view_text .= '<script>';
                $view_text .= 'alert("인증번호 발송을 실패하였습니다.")';
                $view_text .= '</script>';
            }
        }else {
            $result = mailer_autosend('회원-본인확인 인증번호 발송', $auth_text, $arr_change_data);
            if($result){
                $result = "S";
                $view_text .= '<script>';
                $view_text .= 'alert("입력하신 '.$auth_text.' 으로 인증번호가 발송되며, 인증 완료 후 비밀번호를 재설정해주세요)")';
                $view_text .= '</script>';
            } else {
                $view_text .= '<script>';
                $view_text .= 'alert("인증번호 발송을 실패하였습니다.")';
                $view_text .= '</script>';
            }
        }
        $result = array("result" => $result, "mb_no" => $mb['mb_no'], "mb_nonce" => $mb_nonce, "view_text" => $view_text);
        $output =  json_encode($result);
        // 출력
        echo  urldecode($output);
    }
}?>