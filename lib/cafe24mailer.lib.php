<?php
if (!defined('_GNUBOARD_')) exit;

function cafe24mailer($sender, $email, $receiverlist, $subject, $content, $type = 0, $file = "", $cc = "", $bcc = "")
{
    /******************** 인증정보 ********************/
    // 대량메일 인증 관련
    $sendmail_url = "https://lifelike1.sendmail.cafe24.com/sendmail_api.php"; // 전송요청 URL
    // $secureKey = "393104"; // 인증키(아마도 로그인 인증키를 적어둔듯...)
    $secureKey = "df6ee7c538b503fe07fa32711229bf80"; // 인증키
    $userId = "lifelike1"; // 발송자ID

    /******************** 요청변수 처리 ********************/
    // 메일발송 관련
    $sender = ($_POST['sender']) ? $_POST['sender'] : $sender; // 발송자 이름
    $email = ($_POST['email']) ? $_POST['email'] : $email; // 발송자 이메일
    $receiverlist = ($_POST['receiverlist']) ? $_POST['receiverlist'] : $receiverlist; // 수신자 리스트
    //$receiverlistUrl= ($_POST['receiverlistUrl']) ? $_POST['receiverlistUrl'] : ''; // 수신자 리스트 URL

    // 메일내용 관련
    $subject = ($_POST['subject']) ? $_POST['subject'] : $subject; // 메일 제목
    $content = ($_POST['content']) ? $_POST['content'] : $content; // 메일 내용

    if ($type != 1)
        $content = nl2br($content);

    // 수신자 처리 관련
    $rejectType = ($_POST['rejectType']) ? $_POST['rejectType'] : 2; // 수신거부자 발송여부(2: 제외발송, 3:포함발송)
    $overlapType = 2;

    // 예약발송 관련
    $sendType = ($_POST['sendType']) ? $_POST['sendType'] : 0; // 예약발송 여부(0:즉시발송, 1:예약발송)
    $sendDate = ($_POST['sendDate']) ? $_POST['sendDate'] : ''; // 예약발송 시간(년-월-일 시:분:초)

    // 수신거부 기능 관련
    $useRejectMemo = ($_POST['useRejectMemo']) ? $_POST['useRejectMemo'] : 0; // 수신거부 사용여부(0: 사용안함, 1: 사용)

    // 메일주소 중복발송 관련
    $overlapType = $_POST['overlapType'] == '1' ? '1' : '2';

    // 요청 테스트
    $testFlag = ($_POST['testFlag']) ? $_POST['testFlag'] : 1; // 요청 테스트 사용여부(0: 사용안함, 1: 사용)

    /******************** 요청변수 처리 ********************/
    $mail['secureKey'] = $secureKey;
    $mail['userId'] = $userId;
    $mail['sender'] = base64_encode($sender);
    $mail['email'] = base64_encode($email);
    $mail['receiverlist'] = base64_encode($receiverlist);
    $mail['receiverlistUrl'] = base64_encode($receiverlistUrl);
    $mail['subject'] = base64_encode($subject);
    $mail['content'] = base64_encode($content);
    $mail['rejectType'] = $rejectType;
    $mail['overlapType'] = $overlapType;
    $mail['sendType'] = $sendType;
    $mail['sendDate'] = $sendDate;
    $mail['useRejectMemo'] = $useRejectMemo;
    $mail['testFlag'] = $testFlag;

    $host_info = explode("/", $sendmail_url);
    $host = $host_info[2];
    $path = $host_info[3] . "/" . $host_info[4];

    srand((float) microtime() * 1000000);
    $boundary = "---------------------" . substr(md5(rand(0, 32000)), 0, 10);

    // 헤더 생성
    $header = "POST /" . $path . " HTTP/1.0\r\n";
    $header .= "Host: " . $host . "\r\n";
    $header .= "Content-type: multipart/form-data, boundary=" . $boundary . "\r\n";

    // 본문 생성
    foreach ($mail as $index => $value) {
        $data .= "--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"" . $index . "\"\r\n";
        $data .= "\r\n" . $value . "\r\n";
        $data .= "--$boundary\r\n";
    }

    // 첨부파일
    if (is_uploaded_file($_FILES['addfile']['tmp_name'])) {

        // 파일첨부 관련
        $file_name = ($_FILES['addfile']) ? $_FILES['addfile']['name'] : null;
        $tmp_name = ($_FILES['addfile']) ? $_FILES['addfile']['tmp_name'] : null;
        $content_type = ($_FILES['addfile']) ? $_FILES['addfile']['type'] : null;

        $data .= "--$boundary\r\n";
        $content_file = join("", file($tmp_name));
        $data .= "Content-Disposition: form-data; name=\"addfile\"; filename=\"" . $file_name . "\"\r\n";
        $data .= "Content-Type: $content_type\r\n\r\n";
        $data .= "" . $content_file . "\r\n";
        $data .= "--$boundary--\r\n";
    }
    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

    $fp = fsockopen($host, 80);

    if ($fp) {
        fputs($fp, $header . $data);

        $rsp = '';
        while (!feof($fp)) {
            $rsp .= fgets($fp, 8192);
        }

        fclose($fp);

        $msg = explode("\r\n\r\n", trim($rsp));
        return $msg[1];
    } else {
        return "Connection Failed";
    }
}


function cafe24mailerpost($receiverlist = '')
{
    /******************** 인증정보 ********************/
    // 대량메일 인증 관련
    //https://.sendmail.cafe24.com/sendmail_api.php
    $sendmail_url = "https://lifelike1.sendmail.cafe24.com/sendmail_api.php"; // 전송요청 URL
    // $secureKey = "393104"; // 인증키(아마도 로그인 인증키를 적어둔듯...)
    $secureKey = "df6ee7c538b503fe07fa32711229bf80"; // 인증키
    $userId = "lifelike1"; // 발송자ID
    /******************** 요청변수 처리 ********************/
    // 메일발송 관련
    $sender = ($_POST['sender']) ? $_POST['sender'] : ''; // 발송자 이름
    $email = ($_POST['email']) ? $_POST['email'] : ''; // 발송자 이메일
    $receiverlist = ($_POST['receiverlist']) ? $_POST['receiverlist'] : $receiverlist; // 수신자 리스트
    $receiverlistUrl = ($_POST['receiverlistUrl']) ? $_POST['receiverlistUrl'] : ''; // 수신자 리스트 URL

    // 메일내용 관련
    $subject = ($_POST['subject']) ? $_POST['subject'] : ''; // 메일 제목
    $content = ($_POST['content']) ? $_POST['content'] : ''; // 메일 내용

    // 수신자 처리 관련
    $rejectType = ($_POST['rejectType']) ? $_POST['rejectType'] : 2; // 수신거부자 발송여부(2: 제외발송, 3:포함발송)
    $overlapType = 2;

    // 예약발송 관련
    $sendType = ($_POST['sendType']) ? $_POST['sendType'] : 0; // 예약발송 여부(0:즉시발송, 1:예약발송)
    $sendDate = ($_POST['sendDate']) ? $_POST['sendDate'] : ''; // 예약발송 시간(년-월-일 시:분:초)

    // 파일첨부 관련
    $file_name = $_FILES['addfile']['name'];
    $tmp_name = $_FILES['addfile']['tmp_name'];
    $content_type = $_FILES['addfile']['type'];

    // 수신거부 기능 관련
    $useRejectMemo = ($_POST['useRejectMemo']) ? $_POST['useRejectMemo'] : 0; // 수신거부 사용여부(0: 사용안함, 1: 사용)

    // 메일주소 중복발송 관련
    $overlapType = $_POST['overlapType'] == '1' ? '1' : '2';

    // 요청 테스트
    $testFlag = ($_POST['testFlag']) ? $_POST['testFlag'] : 0; // 요청 테스트 사용여부(0: 사용안함, 1: 사용)

    /******************** 요청변수 처리 ********************/
    $mail['secureKey'] = $secureKey;
    $mail['userId'] = $userId;
    $mail['sender'] = base64_encode($sender);
    $mail['email'] = base64_encode($email);
    $mail['receiverlist'] = base64_encode($receiverlist);
    $mail['receiverlistUrl'] = base64_encode($receiverlistUrl);
    $mail['subject'] = base64_encode($subject);
    $mail['content'] = base64_encode($content);
    $mail['rejectType'] = $rejectType;
    $mail['overlapType'] = $overlapType;
    $mail['sendType'] = $sendType;
    $mail['sendDate'] = $sendDate;
    $mail['useRejectMemo'] = $useRejectMemo;
    $mail['testFlag'] = $testFlag;

    $host_info = explode("/", $sendmail_url);
    $host = $host_info[2];
    $path = $host_info[3] . "/" . $host_info[4];

    srand((float) microtime() * 1000000);
    $boundary = "---------------------" . substr(md5(rand(0, 32000)), 0, 10);

    // 헤더 생성
    $header = "POST /" . $path . " HTTP/1.0\r\n";
    $header .= "Host: " . $host . "\r\n";
    $header .= "Content-type: multipart/form-data, boundary=" . $boundary . "\r\n";

    // 본문 생성
    foreach ($mail as $index => $value) {
        $data .= "--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"" . $index . "\"\r\n";
        $data .= "\r\n" . $value . "\r\n";
        $data .= "--$boundary\r\n";
    }

    // 첨부파일
    if (is_uploaded_file($_FILES['addfile']['tmp_name'])) {
        $data .= "--$boundary\r\n";
        $content_file = join("", file($tmp_name));
        $data .= "Content-Disposition: form-data; name=\"addfile\"; filename=\"" . $file_name . "\"\r\n";
        $data .= "Content-Type: $content_type\r\n\r\n";
        $data .= "" . $content_file . "\r\n";
        $data .= "--$boundary--\r\n";
    }
    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

    $fp = fsockopen($host, 80);

    if ($fp) {
        fputs($fp, $header . $data);

        $rsp = '';
        while (!feof($fp)) {
            $rsp .= fgets($fp, 8192);
        }

        fclose($fp);

        $msgCode = explode("\r\n\r\n", trim($rsp));
        $msg = "";
        switch ($msgCode[1]) {
            case 'SUCCESS':
                $msg = '발송 성공';
                break;
            case 'TEST SUCCESS':
                $msg = '테스트 요청 성공';
                break;
            case '101':
                $msg = '인증키 공백 오류';
                break;
            case '102':
                $msg = '발송자 ID 공백 오류';
                break;
            case '103':
                $msg = '발송자 이름 공백 오류';
                break;
            case '104':
                $msg = '발송자 이메일 공백 오류';
                break;
            case '105':
                $msg = '수신자 리스트 공백 오류 or 수신자 리스트 URL 공백 오류';
                break;
            case '106':
                $msg = '메일 제목 공백 오류';
                break;
            case '107':
                $msg = '메일 내용 공백 오류';
                break;
            case '108':
                $msg = '예약발송 시간 오류(발송요청 시간이 현재보다 과거이거나 7일 이상인 경우)';
                break;
            case '201':
                $msg = '사용자 인증 실패 오류';
                break;
            case '202':
                $msg = '첨부파일 용량이 2MB 를 넘었을때 발생하는 오류';
                break;
            case '203':
                $msg = '첨부파일이 첨부할 수 없는 파일 확장자일때 발생하는 오류';
                break;
            case '301':
                $msg = '수신자 리스트 발송통수가 3만통이 넘을때 발생하는 오류';
                break;
            case '302':
                $msg = '잔여건수가 메일 발송수보다 적을때 발생하는 오류';
                break;
            case '303':
                $msg = '수신자 리스트 URL 발송통수가 10만통이 넘었을때 발생하는 오류';
                break;
            case '304':
                $msg = '동일 IP 로 10건 이상 API 요청 시 5분 이내 동일 IP 로 API 요청 시 오류';
                break;
            case '305':
                $msg = '대량발송 API 사용 때 10통 미만 요청 시 발생하는 오류';
                break;
            case '306':
                $msg = '수신자 리스트 필수값 누락 시 발생하는 오류 (필수 : 이름, 메일주소)';
                break;
            case '307':
                $msg = '수신자 리스트 이메일 형식 오류';
                break;
            default:
                $msg = trim($rsp);
                break;
        }

        $sql = " insert into lt_mail_sendhistory
                 set sh_type = 'cafe24'
                  ,ma_id = '{$ma_id}'
                  ,sender_name = '{$sender}'
                  ,sender_email = '{$email}'
                  ,receiver = '{$receiverlist}'
                  ,sh_subject = '{$subject}'
                  ,sh_content = '{$content}'
                  ,rejectType = '{$rejectType}'
                  ,sendtype =  '{$sendtype}'
                  ,sendDate = '{$sendDate}'
                  ,useRejectMemo = '{$useRejectMemo}'
                  ,overlapType = '{$overlapType}'
                  ,testFlag = '{$testFlag}'
                  ,result_code = '{$msgCode[1]}'
                  ,result_msg = '{$msg}'
                  ,sh_datetime = '" . G5_TIME_YMDHIS . "'
                ";
        sql_query($sql, false);

        return $msg;
    } else {
        return "Connection Failed";
    }
}
