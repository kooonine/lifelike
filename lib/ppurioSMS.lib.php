<?php
if (!defined('_GNUBOARD_')) exit;

/*************************************************************************
 **
 ** apistore SMS API 연동
 **  대용량 SMS API 에 사용할 함수 모음
 **
 *************************************************************************/


if (!function_exists('array_overlap')) {
    function array_overlap($arr, $val)
    {
        for ($i = 0, $m = count($arr); $i < $m; $i++) {
            if ($arr[$i] == $val)
                return true;
        }
        return false;
    }
}
if (!function_exists('get_hp')) {
    function get_hp($hp, $hyphen = 1)
    {
        global $g5;

        if (!is_hp($hp)) return '';

        if ($hyphen) $preg = "$1-$2-$3";
        else $preg = "$1$2$3";

        $hp = str_replace('-', '', trim($hp));
        $hp = preg_replace("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $preg, $hp);

        return $hp;
    }
}
if (!function_exists('is_hp')) {
    function is_hp($hp)
    {
        $hp = str_replace('-', '', trim($hp));
        if (preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $hp))
            return true;
        else
            return false;
    }
}
if (!function_exists('alert_just')) {
    // 경고메세지를 경고창으로
    function alert_just($msg = '', $url = '')
    {
        global $g5;

        if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

        //header("Content-Type: text/html; charset=$g5[charset]");
        echo "<meta charset=\"utf-8\">";
        echo "<script language='javascript'>alert('$msg');";
        echo "</script>";
        exit;
    }
}

if (!function_exists('utf2euc')) {
    function utf2euc($str)
    {
        return iconv("UTF-8", "cp949//IGNORE", $str);
    }
}
if (!function_exists('is_ie')) {
    function is_ie()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false);
    }
}

/**
 * SMS 발송을 관장하는 메인 클래스이다.
 *
 * 접속, 발송, URL발송, 결과등의 실질적으로 쓰이는 모든 부분이 포함되어 있다.
 */
require_once(G5_LIB_PATH . '/Unirest.php');

if (!function_exists('get_sendnumber_list')) {
    function get_sendnumber_list()
    {
        $response = Unirest::get(
            "http://api.apistore.co.kr/ppurio/1/sendnumber/list/" . API_STORE_ID,
            array(
                "x-waple-authorization" => API_STORE_KEY
            )
        );
        return get_object_vars($response->body);
    }
}

if (!function_exists('save_sendnumber')) {
    function save_sendnumber($sendnumber, $comment, $pintype = "VMS", $pincode = "")
    {
        global $g5;
        $pintype = "VMS";
        $param = array(
            sendnumber => $sendnumber,
            comment => $comment,
            pintype => $pintype
        );

        if ($pincode != "") $param['pincode'] = $pincode;

        $response = Unirest::post(
            "http://api.apistore.co.kr/ppurio/2/sendnumber/save/" . API_STORE_ID,
            array(
                "x-waple-authorization" => API_STORE_KEY
            ),
            $param
        );

        return get_object_vars($response->body);
    }
}



if (!function_exists('sms_send')) {
    function sms_send($message_type, $send_time, $send_phone, $dest_phone, $send_name, $dest_name, $subject = "", $msg_body, $smsExcel = null)
    {
        global $g5;

        $param = array(
            send_time => $send_time, send_phone => $send_phone, dest_phone => $dest_phone, send_name => $send_name, dest_name => $dest_name, msg_body => $msg_body, smsExcel => $smsExcel
        );

        if ($subject != "") $param['subject'] = $subject;

        $response = Unirest::post(
            "http://api.apistore.co.kr/ppurio/1/message/" . $message_type . "/" . API_STORE_ID,
            array(
                "x-waple-authorization" => API_STORE_KEY
            ),
            $param
        );

        return get_object_vars($response->body);
    }
}

if (!function_exists('sms_autosend')) {
    function sms_autosend($sf_cate, $sf_title, $send_time, $dest_name, $dest_phone, $arr_change_data)
    {
        global $g5, $config, $default;

        $sf_title_org = $sf_title;
        $dest_name = get_text($dest_name);
        $dest_phone = preg_replace("/[^0-9]/", "", $dest_phone);

        $kakao_result = false;
        $sql  = " select * from lt_sms_form where sf_cate = '" . $sf_cate . "' and sf_title = '" . $sf_title . "' and sf_user_use = 1 and sf_type = 'kakao' ";
        $kakao_msg = sql_fetch($sql);
        if ($kakao_msg) {
            //카카오 발송

            //템플릿코드로 등록된 템플릿 불러오기
            $template_code = $kakao_msg['sf_user_template_code'];
            $param = array('template_code' => $template_code, 'status' => null);
            $response = Unirest::post(
                "http://api.apistore.co.kr/kko/1/template/list/" . API_STORE_ID,
                array(
                    "x-waple-authorization" => API_STORE_KEY
                ),
                $param
            );
            $body = get_object_vars($response->body);
            $template = $body['templateList'][0];
            $status = $template->status;
            $sf_title = $template->template_name;
            $msg = $template->template_msg;

            if ($sf_title == '재입고알림') {
                $msg = "[라이프라이크] 재입고알림
안녕하세요 고객님, 재입고 시 알림을 요청하신 [#{브랜드}] #{상품명}이(가) 재입고 되었습니다.

※ 인기 상품의 경우 구매시점에 따라 조기품절될 수 있습니다.";
            }

            if (is_array($arr_change_data) && count($arr_change_data) > 0) {
                foreach ($arr_change_data as $key => $val) {
                    $msg = str_replace("#{" . $key . "}", $val, $msg);
                    $msg = str_replace("{" . $key . "}", $val, $msg);
                }
            }

            //$send_name = $default['de_admin_company_saupja_no'];
            $send_name = $config['cf_admin_email_name'];
            $send_phone = preg_replace("/[^0-9]/", "", $default['de_sms_hp']);
            $sms_recive = 0;

            $msg_title = "[" . $config['cf_title'] . " " . $sf_title . "]";
            $msg_body = $msg;

            $message_type = 'sms';

            if ($status == "승인") {
                $param = array(
                    'reqdate' => $send_time, 'phone' => $dest_phone, 'callback' => $send_phone, 'msg' => $msg_body, 'template_code' => $template_code, 'failed_type' => "N", 'failed_subject' => null, 'failed_msg' => null
                );

                if (isset($arr_change_data['button'])) {
                    $param['BTN_TYPES'] = $arr_change_data['button']['type'];
                    $param['BTN_TXTS'] = $arr_change_data['button']['txt'];
                    $param['BTN_URLS1'] = $arr_change_data['button']['link'];
                }

                $response = Unirest::post(
                    "http://api.apistore.co.kr/kko/1/msg/" . API_STORE_ID,
                    array(
                        "x-waple-authorization" => API_STORE_KEY
                    ),
                    $param
                );

                $body = get_object_vars($response->body);

                // var_dump($param);
                // dd($body);

                $result_code = $body['result_code'];
                $result_msg = $body['result_msg'];
                $cmid = $body['cmid'];

                if ($body && $body['result_code'] == "200") {
                    $kakao_result = true;
                }
            } else {
                $result_code = "700";
                $result_msg = "템플릿코드 사전 승인제에 의한 미승인차단";
                $cmid = "";
            }

            $msg_title = addslashes($msg_title);
            $msg_body = addslashes($msg_body);

            $sql = " insert into lt_sms_sendhistory
                     set sh_sendtype = '자동발송',
                        sf_type = 'kakao',
                        msg_type = '{$message_type}',
                        send_time = '{$send_time}',
                        send_phone = '{$send_phone}',
                        dest_phone = '{$dest_phone}',
                        donotcall_use = '{$sms_recive}',
                        send_name = '',
                        dest_name = '',
                        msg_title = '{$msg_title}',
                        msg_body = '{$msg_body}',
                        smsExcel = '',
                        result_code = '{$body['result_code']}',
                        result_msg = '{$body['result_msg']}',
                        cmid = '{$body['cmid']}',
                        sh_datetime = '" . G5_TIME_YMDHIS . "'
                    ";
            sql_query($sql);
            //return $body;
        }

        //카카오전송 실패시
        if (!$kakao_result) {
            $sql  = " select  * from lt_sms_form where sf_cate = '" . $sf_cate . "' and sf_title = '" . $sf_title_org . "' and sf_user_use = 1 and sf_type = 'sms' ";
            $sms_msg = sql_fetch($sql);
            if ($sms_msg) {
                //SMS발송
                $msg = $sms_msg['sf_user_msg'];
                if (is_array($arr_change_data) && count($arr_change_data) > 0) {
                    foreach ($arr_change_data as $key => $val) {
                        $msg = str_replace("{" . $key . "}", $val, $msg);
                        //$msg = str_replace("[".$key."]",$val,$msg);
                    }
                }

                //$send_name = $default['de_admin_company_saupja_no'];
                $send_name = $config['cf_admin_email_name'];
                $send_phone = preg_replace("/[^0-9]/", "", $default['de_sms_hp']);
                $sms_recive = 0;

                $msg_title = "[" . $config['cf_title'] . " " . $sf_title_org . "]";
                $msg_body = $msg;

                $message_type = 'sms';
                $param = array(
                    'send_time' => $send_time, 'dest_phone' => $dest_phone, 'dest_name' => $dest_name, 'send_phone' => $send_phone, 'send_name' => $send_name, 'subject' => $msg_title, 'msg_body' => $msg_body
                );

                $response = Unirest::post(
                    "http://api.apistore.co.kr/ppurio/1/message/sms/" . API_STORE_ID,
                    array(
                        "x-waple-authorization" => API_STORE_KEY
                    ),
                    $param
                );

                $body = get_object_vars($response->body);

                $msg_title = addslashes($msg_title);
                $msg_body = addslashes($msg_body);

                $sql = " insert into lt_sms_sendhistory
                         set sh_sendtype = '자동발송',
                            sf_type = 'sms',
                            msg_type = '{$message_type}',
                            send_time = '{$send_time}',
                            send_phone = '{$send_phone}',
                            dest_phone = '{$dest_phone}',
                            donotcall_use = '{$sms_recive}',
                            send_name = '',
                            dest_name = '',
                            msg_title = '{$msg_title}',
                            msg_body = '{$msg_body}',
                            smsExcel = '',
                            result_code = '{$body['result_code']}',
                            result_msg = '{$body['result_msg']}',
                            cmid = '{$body['cmid']}',
                            sh_datetime = '" . G5_TIME_YMDHIS . "'
                        ";
                sql_query($sql);

                return $body;
            }
        }
    }
}

function send_notification($tokens, $title, $sendData, $message)
{

    $notification = array(
        "title"     => $title,
        "body"   => $sendData
    );

    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
        'registration_ids' => $tokens,
        'data' => $message,
        'notification' => $notification
    );

    $headers = array(
        'Authorization:key =' . FCM_PUSH_API_KEY,
        'Content-Type: application/json;charset=UTF-8'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    curl_close($ch);
    return $result;
}
