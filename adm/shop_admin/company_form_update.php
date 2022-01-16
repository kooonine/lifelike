<?php
$sub_menu = "920101";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_LIB_PATH.'/ppurioSMS.lib.php');

$mb_id = trim($_POST['mb_id']);
$w = $_POST['w'];

check_admin_token();


$company_zip1 = substr($_POST['company_zip'], 0, 3);
$company_zip2 = substr($_POST['company_zip'], 3);

$sql_common = " company_no = '{$_POST['company_no']}',
                company_type = '{$_POST['company_type']}',
                company_name = '{$_POST['company_name']}',
                company_leader = '{$_POST['company_leader']}',
                company_category = '{$_POST['company_category']}',
                company_hp = '{$_POST['company_hp']}',
                company_zip1 = '{$company_zip1}',
                company_zip2 = '{$company_zip2}',
                company_addr1 = '{$_POST['company_addr1']}',
                company_addr2 = '{$_POST['company_addr2']}',
                company_addr3 = '{$_POST['company_addr3']}',
                company_addr_jibeon = '{$_POST['company_addr_jibeon']}',
                cp_fax = '{$_POST['cp_fax']}',
                cp_tongsin_no = '{$_POST['cp_tongsin_no']}',
                company_type2 = '{$_POST['company_type2']}',

                jeongsan_mb_name = '{$_POST['jeongsan_mb_name']}',
                jeongsan_mb_email = '{$_POST['jeongsan_mb_email']}',
                jeongsan_mb_tel = '{$_POST['jeongsan_mb_tel']}',
                jeongsan_mb_hp = '{$_POST['jeongsan_mb_hp']}',
                
                delivery_mb_name = '{$_POST['delivery_mb_name']}',
                delivery_mb_email = '{$_POST['delivery_mb_email']}',
                delivery_mb_tel = '{$_POST['delivery_mb_tel']}',
                delivery_mb_hp = '{$_POST['delivery_mb_hp']}',
                
                cs_mb_name = '{$_POST['cs_mb_name']}',
                cs_mb_email = '{$_POST['cs_mb_email']}',
                cs_mb_tel = '{$_POST['cs_mb_tel']}',
                cs_mb_hp = '{$_POST['cs_mb_hp']}',
                
                cp_bank_name = '{$_POST['cp_bank_name']}',
                cp_bank_account = '{$_POST['cp_bank_account']}',
                cp_bank_account_no = '{$_POST['cp_bank_account_no']}',
                
                cp_out_zip = '{$_POST['cp_out_zip']}',
                cp_out_address1 = '{$_POST['cp_out_address1']}',
                cp_out_address2 = '{$_POST['cp_out_address2']}',
                
                cp_return_zip = '{$_POST['cp_return_zip']}',
                cp_return_address1 = '{$_POST['cp_return_address1']}',
                cp_return_address2 = '{$_POST['cp_return_address2']}',
                cp_ps_open_use = '{$_POST['cp_ps_open_use']}',
                cp_ps_mb_use = '{$_POST['cp_ps_mb_use']}',
                cp_ps_mb_name = '{$_POST['cp_ps_mb_name']}',
                cp_ps_mb_email = '{$_POST['cp_ps_mb_email']}'
                ";

$sql_common2 = " A.company_no = B.company_no,
                A.company_type = B.company_type,
                A.company_name = B.company_name,
                A.company_leader = B.company_leader,
                A.company_category = B.company_category,
                A.company_hp = B.company_hp,
                A.company_file = B.company_file,
                A.company_zip1 = B.company_zip1,
                A.company_zip2 = B.company_zip2,
                A.company_addr1 = B.company_addr1,
                A.company_addr2 = B.company_addr2,
                A.company_addr3 = B.company_addr3,
                A.company_addr_jibeon = B.company_addr_jibeon,
                A.cp_fax = B.cp_fax,
                A.cp_tongsin_no = B.cp_tongsin_no,
                A.company_type2 = B.company_type2,
                A.jeongsan_mb_name = B.jeongsan_mb_name,
                A.jeongsan_mb_email = B.jeongsan_mb_email,
                A.jeongsan_mb_tel = B.jeongsan_mb_tel,
                A.jeongsan_mb_hp = B.jeongsan_mb_hp,
                A.delivery_mb_name = B.delivery_mb_name,
                A.delivery_mb_email = B.delivery_mb_email,
                A.delivery_mb_tel = B.delivery_mb_tel,
                A.delivery_mb_hp = B.delivery_mb_hp,
                A.cs_mb_name = B.cs_mb_name,
                A.cs_mb_email = B.cs_mb_email,
                A.cs_mb_tel = B.cs_mb_tel,
                A.cs_mb_hp = B.cs_mb_hp,
                A.cp_bank_name = B.cp_bank_name,
                A.cp_bank_account = B.cp_bank_account,
                A.cp_bank_account_no = B.cp_bank_account_no,
                A.cp_out_zip = B.cp_out_zip,
                A.cp_out_address1 = B.cp_out_address1,
                A.cp_out_address2 = B.cp_out_address2,
                A.cp_return_zip = B.cp_return_zip,
                A.cp_return_address1 = B.cp_return_address1,
                A.cp_return_address2 = B.cp_return_address2,
                A.cp_ps_open_use = B.cp_ps_open_use,
                A.cp_ps_mb_use = B.cp_ps_mb_use,
                A.cp_ps_mb_name = B.cp_ps_mb_name,
                A.cp_ps_mb_email = B.cp_ps_mb_email,
                A.company_img = B.company_img,
                A.company_file1 = B.company_file1,
                A.company_file2 = B.company_file2,
                A.company_file3 = B.company_file3,
                A.company_file4 = B.company_file4
                ";

if ($w == 'c' || $w == 'u')
{
    $sql = "select * from lt_member_company where mb_id = '{$mb_id}' ";
    $cp = sql_fetch($sql);
    
    //파일업로드
    $mb_dir = G5_DATA_PATH.'/company/';
    if( !is_dir($mb_dir) ){
        @mkdir($mb_dir, G5_DIR_PERMISSION);
        @chmod($mb_dir, G5_DIR_PERMISSION);
    }
    
    $mb_dir .= $mb_id;
    if( !is_dir($mb_dir) ){
        @mkdir($mb_dir, G5_DIR_PERMISSION);
        @chmod($mb_dir, G5_DIR_PERMISSION);
    }
    
    // 입점몰 이미지 업로드
    if (isset($_FILES['company_img']) && is_uploaded_file($_FILES['company_img']['tmp_name'])) {
        $company_img = $mb_id.'_company_img.gif';
        $dest_path = $mb_dir.'/'.$company_img;
        
        move_uploaded_file($_FILES['company_img']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);
        
        $sql_common .= ", company_img = '{$company_img}' ";
    } elseif($cp['company_img']){
        $sql_common .= ", company_img = '{$cp['company_img']}' ";
    }
    
    $company_file_add = false;
    if (isset($_POST['del_company_file']) && $_POST['del_company_file']) {
        @unlink($mb_dir.'/'.$cp['company_file']);
        if (!isset($_FILES['company_file']) || !is_uploaded_file($_FILES['company_file']['tmp_name']))
        {
            $sql_common .= ", company_file = '' ";
            $company_file_add = true;
        }
    }
    //사업자등록증 업로드
    if (isset($_FILES['company_file']) && is_uploaded_file($_FILES['company_file']['tmp_name']))
    {
        $dest_path = $mb_dir.'/'.$_FILES['company_file']['name'];
        
        move_uploaded_file($_FILES['company_file']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);
        
        $sql_common .= ", company_file = '{$_FILES['company_file']['name']}' ";
        $company_file_add = true;
    }
    if($cp['company_file'] && !$company_file_add){
        $company_file_add = true;
        $sql_common .= ", company_file = '{$cp['company_file']}' ";
    }
    
    for ($i = 1; $i <= 4; $i++) {
        $company_file_add = false;
        
        if (isset($_POST['del_company_file'.$i]) && $_POST['del_company_file'.$i]) {
            @unlink($mb_dir.'/'.$cp['company_file'.$i]);
            if (!isset($_FILES['company_file'.$i]) || !is_uploaded_file($_FILES['company_file'.$i]['tmp_name']))
            {
                $sql_common .= ", company_file".$i." = '' ";
                $company_file_add = true;
            }
        }
        
        //추가 서류 제출 업로드
        if (isset($_FILES['company_file'.$i]) && is_uploaded_file($_FILES['company_file'.$i]['tmp_name']))
        {
            $dest_path = $mb_dir.'/'.$_FILES['company_file'.$i]['name'];
            
            move_uploaded_file($_FILES['company_file'.$i]['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            
            $sql_common .= ", company_file".$i." = '{$_FILES['company_file'.$i]['name']}' ";
            $company_file_add = true;
        }
        
        if($cp['company_file'.$i] && !$company_file_add){
            $sql_common .= ", company_file".$i." = '{$cp['company_file'.$i]}' ";
        }
    }
}

if ($w == 'c')
{
    $company_no = str_replace("-", "", $_POST['company_no']);
    //사업자번호 중복 체크
    $row = sql_fetch("select  count(mb_id) cnt from lt_member_company where   replace(company_no,'-','') = '{$company_no}' and mb_id != '{$mb_id}' ");
    if ($row['cnt'])
        alert('중복된 사업자 번호입니다.');
    
    if (!$cp['mb_id']) {
        sql_query(" insert into lt_member_company set mb_id = '{$mb_id}', register_date = '".G5_TIME_YMDHIS."', cp_status = '승인요청', {$sql_common} ");
    } else {
        sql_query(" update lt_member_company set register_date = '".G5_TIME_YMDHIS."', cp_status = '승인요청', {$sql_common} where mb_id = '{$mb_id}' ");
    }
    
    sql_query(" update lt_member set mb_tel = '".$_POST['mb_tel']."' where mb_id = '{$mb_id}' ");
    
    goto_url('./company.php', false);
}
else if ($w == 'u')
{
    $mb = get_member($mb_id);
    if (!$mb['mb_id'])
        alert('존재하지 않는 입점몰 자료입니다.');
    
    if (!$cp['mb_id'])
        alert('존재하지 않는 입점몰 자료입니다.');
        
    sql_query(" insert into lt_member_company_approve set mb_id = '{$mb_id}', register_date = '".G5_TIME_YMDHIS."', cp_status = '정보변경신청', {$sql_common} ");
    
    $sql = " update lt_member_company
                set  cp_status = '정보변경신청'
                    , modify_date = '".G5_TIME_YMDHIS."'
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
    
    sql_query(" update lt_member set mb_tel = '".$_POST['mb_tel']."' where mb_id = '{$mb_id}' ");
    
    goto_url('./company.php', false);
}
else if ($w == 'approve')
{
    //입점사 코드 생성 P000000
    $row = sql_fetch("select (max(substr(company_code,-6)) + 1) cnt from lt_member_company where company_code is not null and company_code != '' ");
    $company_code = 'P'.substr('000000'.$row['cnt'], -6);
    
    //(가입) 승인요청, 승인반려, 승인완료 / (정보변경) 정보변경신청,정보변경반려,정보변경승인  
    //승인
    $sql = " update lt_member_company
                set  cp_status = '승인완료'
                    , approve_mb_id = '{$member['mb_id']}'
                    , approve_mb_name = '{$member['mb_name']}'
                    , approve_date = '".G5_TIME_YMDHIS."'
                    , cp_reason = '{$_POST['cp_reason']}'
                    , company_code = '{$company_code}'
                    , cp_commission = '{$_POST['cp_commission']}'
                    , cp_calculate_date = '{$_POST['cp_calculate_date']}'
                    ,cp_calculate_date1 = '{$_POST['cp_calculate_date1']}'
                    ,cp_calculate_date2 = '{$_POST['cp_calculate_date2']}'
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
    
    alert('승인되었습니다.');
}
else if ($w == 'modifyapprove')
{
    
    $sql = " update lt_member_company_approve
                set  cp_status = '정보변경승인'
                    , approve_mb_id = '{$member['mb_id']}'
                    , approve_mb_name = '{$member['mb_name']}'
                    , approve_date = '".G5_TIME_YMDHIS."'
                    , cp_reason = '{$_POST['cp_reason']}'
                where cp_no = '{$_POST['cp_no']}' ";
    sql_query($sql);
    
    //승인
    $sql = " UPDATE lt_member_company A INNER JOIN lt_member_company_approve B
                    ON A.mb_id = B.mb_id and B.cp_no = '{$_POST['cp_no']}'
                SET A.cp_status = '정보변경승인'
                    , A.approve_mb_id = '{$member['mb_id']}'
                    , A.approve_mb_name = '{$member['mb_name']}'
                    , A.approve_date = '".G5_TIME_YMDHIS."'
                    , A.cp_reason = '{$_POST['cp_reason']}'
                    , A.cp_commission = '{$_POST['cp_commission']}'
                    , A.cp_calculate_date = '{$_POST['cp_calculate_date']}'
                    , A.cp_calculate_date1 = '{$_POST['cp_calculate_date1']}'
                    , A.cp_calculate_date2 = '{$_POST['cp_calculate_date2']}'
                    , {$sql_common2}
                where A.mb_id = '{$mb_id}' ";
    
    sql_query($sql);
    
    alert('승인되었습니다.');
}
else if ($w == 'return')
{
    //반려    
    $sql = " update lt_member_company
                set  cp_status = '승인반려'
                    , approve_mb_id = '{$member['mb_id']}'
                    , approve_mb_name = '{$member['mb_name']}'
                    , approve_date = '".G5_TIME_YMDHIS."'
                    , cp_reason = '{$_POST['cp_reason']}'
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
    
    alert('반려사유가 등록되었습니다.');
}
else if ($w == 'modifyreturn')
{
    //반려
    $sql = " update lt_member_company_approve
                set  cp_status = '정보변경반려'
                    , approve_mb_id = '{$member['mb_id']}'
                    , approve_mb_name = '{$member['mb_name']}'
                    , approve_date = '".G5_TIME_YMDHIS."'
                    , cp_reason = '{$_POST['cp_reason']}'
                where cp_no = '{$_POST['cp_no']}' ";
    sql_query($sql);
    
    
    $sql = " update lt_member_company
                set  cp_status = '정보변경반려'
                    , approve_mb_id = '{$member['mb_id']}'
                    , approve_mb_name = '{$member['mb_name']}'
                    , approve_date = '".G5_TIME_YMDHIS."'
                    , cp_reason = '{$_POST['cp_reason']}'
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
    
    alert('반려사유가 등록되었습니다.');
}
else if ($w == 'commission')
{
    $sql = " update lt_member_company
                set   approve_mb_id = '{$member['mb_id']}'
                    , approve_mb_name = '{$member['mb_name']}'
                    , cp_commission = '{$_POST['cp_commission']}'
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
    
    alert('수수료가 변경되었습니다.');
}
else if ($w == 'calculate')
{
    $sql = " update lt_member_company
                set   approve_mb_id = '{$member['mb_id']}'
                    , approve_mb_name = '{$member['mb_name']}'
                    , cp_calculate_date = '{$_POST['cp_calculate_date']}'
                    , cp_calculate_date1 = '{$_POST['cp_calculate_date1']}'
                    , cp_calculate_date2 = '{$_POST['cp_calculate_date2']}'
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
    
    alert('정산일이 변경되었습니다.');
}
else if ($w == 'addr1')
{
    $company_zip1 = substr($_POST['company_zip'], 0, 3);
    $company_zip2 = substr($_POST['company_zip'], 3);
    
    $sql = " update lt_member_company
                set     company_zip1 = '{$company_zip1}',
                        company_zip2 = '{$company_zip2}',
                        company_addr1 = '{$_POST['company_addr1']}',
                        company_addr2 = '{$_POST['company_addr2']}',
                        company_addr3 = '{$_POST['company_addr3']}',
                        company_addr_jibeon = '{$_POST['company_addr_jibeon']}'
             where mb_id = '{$mb_id}' ";
    sql_query($sql);
    goto_url('./company.php', false);
}
else if ($w == 'addr2')
{
    $sql = " update lt_member_company
                set     cp_out_zip = '{$_POST['cp_out_zip']}',
                        cp_out_address1 = '{$_POST['cp_out_address1']}',
                        cp_out_address2 = '{$_POST['cp_out_address2']}'
             where mb_id = '{$mb_id}' ";
    sql_query($sql);
    goto_url('./company.php', false);
}
else if ($w == 'addr3')
{
    $sql = " update lt_member_company
                set     cp_return_zip = '{$_POST['cp_return_zip']}',
                        cp_return_address1 = '{$_POST['cp_return_address1']}',
                        cp_return_address2 = '{$_POST['cp_return_address2']}'
             where mb_id = '{$mb_id}' ";
    sql_query($sql);
    goto_url('./company.php', false);
}
else if ($w == 'leave')
{
    //탈퇴완료
    $sql = " update lt_member_company
                set  cp_status = '탈퇴완료'
                    , approve_mb_id = '{$member['mb_id']}'
                    , approve_mb_name = '{$member['mb_name']}'
                    , approve_date = '".G5_TIME_YMDHIS."'
                    , cp_reason = '{$_POST['cp_reason']}'
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
    
    alert('탈퇴완료 처리하였습니다.');
}
else
{
    alert('제대로 된 값이 넘어오지 않았습니다.');
}
?>