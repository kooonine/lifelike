<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_PHPMAILER_PATH.'/PHPMailerAutoload.php');

function mailer_autosend($ma_name, $to, $arr_change_data)
{
    global $g5,$config,$default;
    
    $sql  = " select  * from lt_mail where ma_name = '".$ma_name."' and ma_use = 1";
    $ma = sql_fetch($sql);
    if(!$ma) return;
    
    $fname = $config['cf_admin_email_name'];
    $fmail = $default['de_admin_call_email'];
    $subject = $ma['ma_subject'];
    $content = $ma['ma_content'];
    
    if(is_array($arr_change_data) && count($arr_change_data) > 0) {
        foreach ($arr_change_data as $key => $val) {
            $subject = str_replace("{".$key."}",$val,$subject);
            //$subject = str_replace("[".$key."]",$val,$subject);
        }
    }
    
    if(is_array($arr_change_data) && count($arr_change_data) > 0) {
        foreach ($arr_change_data as $key => $val) {
            $content = str_replace("{".$key."}",$val,$content);
            //$content = str_replace("[".$key."]",$val,$content);
        }
    }
    
    return mailer($fname, $fmail, $to, $subject, $content, 1, "", "", "", $ma['ma_id']);
    
}

// 메일 보내기 (파일 여러개 첨부 가능)
// type : text=0, html=1, text+html=2
function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="", $ma_id="")
{
    global $config;
    global $g5;

    // 메일발송 사용을 하지 않는다면
    if (!$config['cf_email_use']) return;

    if ($type != 1)
        $content = nl2br($content);

    $mail = new PHPMailer(); // defaults to using php "mail()"
    if (defined('G5_SMTP') && G5_SMTP) {
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = G5_SMTP; // SMTP server
        if(defined('G5_SMTP_PORT') && G5_SMTP_PORT)
            $mail->Port = G5_SMTP_PORT;
    }
    $mail->CharSet = 'UTF-8';
    $mail->From = $fmail;
    $mail->FromName = $fname;
    $mail->Subject = $subject;
    $mail->AltBody = ""; // optional, comment out and test
    $mail->msgHTML($content);
    $mail->addAddress($to);
    if ($cc)
        $mail->addCC($cc);
    if ($bcc)
        $mail->addBCC($bcc);
    //print_r2($file); exit;
    if ($file != "") {
        foreach ($file as $f) {
            $mail->addAttachment($f['path'], $f['name']);
        }
    }
    $result = $mail->send();

    $msgCode = ($result)?"S":"F";
    $msg = ($result)?"메일 발송에 성공했습니다.":"메일 발송에 실패했습니다."; 
    
    
    $subject = addslashes($subject);
    $content = addslashes($content);
    
    $sql = " insert into lt_mail_sendhistory
                 set sh_type = 'sendmail'
                  ,ma_id = '{$ma_id}'
                  ,sender_name = '{$fname}'
                  ,sender_email = '{$fmail}'
                  ,receiver = '{$to}'
                  ,sh_subject = '{$subject}'
                  ,sh_content = '{$content}'
                  ,rejectType = '2'
                  ,sendtype =  '0'
                  ,sendDate = ''
                  ,useRejectMemo = '0'
                  ,overlapType = '2'
                  ,testFlag = '0'
                  ,result_code = '{$msgCode}'
                  ,result_msg = '{$msg}'
                  ,sh_datetime = '".G5_TIME_YMDHIS."'
                ";
    sql_query($sql, false);
    
    return $result;
}

// 파일을 첨부함
function attach_file($filename, $tmp_name)
{
    // 서버에 업로드 되는 파일은 확장자를 주지 않는다. (보안 취약점)
    $dest_file = G5_DATA_PATH.'/tmp/'.str_replace('/', '_', $tmp_name);
    move_uploaded_file($tmp_name, $dest_file);
    $tmpfile = array("name" => $filename, "path" => $dest_file);
    return $tmpfile;
}
?>