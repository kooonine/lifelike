<?php
$sub_menu = "200100";
include_once('./_common.php');

check_demo();

if ($_POST['act_button'] == "불량회원") {
    
    if (!count($_POST['chk'])) {
        $result -> result = "F";
        $result -> alert = $_POST['act_button']." 하실 항목을 하나 이상 체크하세요.";
        echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
    }
    
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $mb = get_member($_POST['mb_id'][$k]);

        if (!$mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 회원자료가 존재하지 않습니다.\\n';
        } else if ($mb['mb_level'] < $member['mb_level']) {
            $msg .= $mb['mb_id'].' : 자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.\\n';
        } else if ($member['mb_id'] == $mb['mb_id']) {
            $msg .= $mb['mb_id'].' : 로그인 중인 관리자는 수정 할 수 없습니다.\\n';
        } else {
            if($_POST['mb_certify'][$k])
                $mb_adult = (int) $_POST['mb_adult'][$k];
            else
                $mb_adult = 0;

            $sql = " update {$g5['member_table']}
                        set mb_level = '".sql_real_escape_string($_POST['mb_level'][$k])."',
                            mb_intercept_date = '".sql_real_escape_string($_POST['mb_intercept_date'][$k])."',
                            mb_mailling = '".sql_real_escape_string($_POST['mb_mailling'][$k])."',
                            mb_sms = '".sql_real_escape_string($_POST['mb_sms'][$k])."',
                            mb_open = '".sql_real_escape_string($_POST['mb_open'][$k])."',
                            mb_certify = '".sql_real_escape_string($_POST['mb_certify'][$k])."',
                            mb_adult = '{$mb_adult}'
                        where mb_id = '".sql_real_escape_string($_POST['mb_id'][$k])."' ";
            sql_query($sql);
        }
    }
    $result -> result = "F";
    $result -> alert = $msg;
    echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
    
} else if ($_POST['act_button'] == "메모") {
    $mb_id = trim($_POST['mb_id']);
    
    $is_important           = isset($_POST['is_important'])             ? sql_real_escape_string(trim($_POST['is_important']))           : "0";
    
    $sql_common = "  mm_memo = '{$_POST['mm_memo']}',
                 is_important = '{$is_important}',
                 mm_mb_id = '{$member['mb_id']}',
                 mm_mb_name = '{$member['mb_name']}',
                 mm_time = '".G5_TIME_YMDHIS."' ";
    
    $sql = " update {$g5['member_table']}
                set {$sql_common}
                where mb_id = '{$mb_id}' ";
    
    sql_query(" insert into lt_member_memo set mb_id = '$mb_id', {$sql_common} ");
    
    
    $result -> result = "S";
    echo json_encode_raw($result,JSON_UNESCAPED_UNICODE);
}
?>
