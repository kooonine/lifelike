<?php
$sub_menu = "900900";
include_once("./_common.php");

if ($w == "")
{
    //신청
    if (!count($_POST['chk'])) {
        
        $returnJson -> result = "F";
        $returnJson -> alertMsg = "지급대상자가 선택되지 않았습니다.확인해주세요.";
        echo json_encode_raw($returnJson,JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    //마이너스 처리
    if($point_type == "m") $po_point = (-1) * (int)$po_point;
    $po_expire_date = date('Y-m-d', strtotime('+'.($po_expire_date - 1).' days', G5_SERVER_TIME));
    
    $po_expired = 0;
    if($po_point < 0) {
        $po_expired = 1;
        $po_expire_date = G5_TIME_YMD;
    }
    
    $arr_sql = array();
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $mb_id = $_POST['mb_id'][$k];
        
        $mb_point = get_point_sum($mb_id);
        $po_mb_point = $mb_point;
        
        $sql = " insert into {$g5['point_table']}
                set mb_id = '$mb_id',
                    po_datetime = '".G5_TIME_YMDHIS."',
                    po_content = '".addslashes($po_content)."',
                    po_point = '$po_point',
                    po_use_point = '0',
                    po_mb_point = '$po_mb_point',
                    po_expired = '$po_expired',
                    po_expire_date = '$po_expire_date',
                    po_rel_table = '',
                    po_rel_id = '',
                    po_rel_action = '요청완료',
                    po_request_id = '".$member['mb_id']."' ";
        
        //array_push($arr_sql, $sql);
        sql_query($sql);        
    }
    
    //$returnJson -> sql = $arr_sql;
    //echo json_encode_raw($returnJson,JSON_UNESCAPED_UNICODE);
    
    $returnJson -> result = "S";
    $returnJson -> alertMsg = "지급요청이 정상적으로 등록되었습니다.";
    echo json_encode_raw($returnJson,JSON_UNESCAPED_UNICODE);
    exit;

} 
else if ($w == "s")
{
    $sql = " select a.*, b.mb_name, b.mb_hp from {$g5['point_table']} as a inner join {$g5['member_table']} as b on a.mb_id = b.mb_id";
    $sql .= " where a.po_id = '".$_POST['po_id']."' ";
    $row = sql_fetch($sql);
    
    echo json_encode_raw($row,JSON_UNESCAPED_UNICODE);
    exit;
}
else if ($w == "d")
{
    $sql = " delete from {$g5['point_table']} ";
    $sql .= " where po_id = '".$_POST['po_id']."' ";
    sql_query($sql);
    
    $returnJson -> result = "S";
    $returnJson -> alertMsg = "정상적으로 삭제되었습니다.";
    echo json_encode_raw($returnJson,JSON_UNESCAPED_UNICODE);
    exit;
}
else if ($w == "a")
{
    $sql = " select a.* from {$g5['point_table']} as a ";
    $sql .= " where a.po_id = '".$_POST['po_id']."' ";
    $po = sql_fetch($sql);
    
    $po_content = $po['po_content'];
    $point = $po['po_point'];
    $mb_id = $po['mb_id'];
    
    // 회원포인트
    $mb_point = get_point_sum($mb_id);
    $po_content = $po_content.' / 사유: '.$_POST['po_content_approve'];
    
    if($po_rel_action == '반려')
    {
        $po_content = $po_content.' / 신청적립금 : '.$point;
        
        $point = 0;
        $po_mb_point = $mb_point - $point;
    } else {
        $po_mb_point = $mb_point;
    }
    
    $sql = " update {$g5['point_table']}
             set     po_mb_point = '$po_mb_point'
                    , po_point = '$point'
                    , po_content = '".$po_content."'
                    , po_rel_action = '$po_rel_action'
                    , po_datetime = '".G5_TIME_YMDHIS."'
                    , po_approve_id = '".$member['mb_id']."'
             where po_id = '".$_POST['po_id']."' ";
    
    sql_query($sql);
    
    if($po_rel_action != '반려'){
        // 포인트를 사용한 경우 포인트 내역에 사용금액 기록
        if($point < 0) {
            insert_use_point($mb_id, $point);
        }
    }
    
    // 포인트 UPDATE
    $sql = " update {$g5['member_table']} set mb_point = '$po_mb_point' where mb_id = '$mb_id' ";
    sql_query($sql);
    
    
    $returnJson -> result = "S";
    $returnJson -> alertMsg = "정상적으로 ".$po_rel_action."되었습니다.";
    echo json_encode_raw($returnJson,JSON_UNESCAPED_UNICODE);
    exit;
}

?>