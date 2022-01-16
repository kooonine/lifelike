<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . "/mailer.lib.php");
include_once(G5_LIB_PATH . "/cafe24mailer.lib.php");

// 10건 미만 요청시 CAFE24 대량메일 API 사용불가 - 191213 balance@panpacific.co.kr
if ($_POST['email_reciver_type'] == 'all') {

    //대량 발송
    $_POST['receiverlistUrl'] = G5_ADMIN_URL . "/email/sendlist.php?type=all&reject=" . $_POST['rejectType'];
    $msg = cafe24mailerpost();
    alert($msg);
} else {
    $receiverlistcount = $_POST['receiverlistcount'];
    if ($receiverlistcount < 10) {
        //일반 메일 발송

        $sender = ($_POST['sender']) ? $_POST['sender'] : ''; // 발송자 이름
        $email = ($_POST['email']) ? $_POST['email'] : ''; // 발송자 이메일
        $receiverlist = ($_POST['receiverlist']) ? $_POST['receiverlist'] : $receiverlist; // 수신자 리스트

        // 메일내용 관련
        $subject = ($_POST['subject']) ? $_POST['subject'] : ''; // 메일 제목
        $content = ($_POST['content']) ? stripslashes($_POST['content']) : ''; // 메일 내용

        $r = mailer($sender, $email, $receiverlist, $subject, $content, 1);

        alert(($r) ? "메일 발송에 성공했습니다." : "메일 발송에 실패했습니다.");
    } else {
        //대량 발송
        $msg = cafe24mailerpost($_POST['receiverlist']);
        alert($msg);
    }
}
