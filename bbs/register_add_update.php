<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$mb_id = $_POST['mb_id'];

if (!exist_mb_id($mb_id)) alert("올바른 방법으로 이용 해 주세요");

$mb_nick        = trim($_POST['mb_nick']);
$mb_sex         = trim($_POST['mb_sex'])      ;   
$mb_birth       = trim($_POST['mb_birth'])   ;    
$mb_recommend   = trim($_POST['mb_recommend'])   ;
$mb_zip1        = substr(trim($_POST['mb_zip']), 0, 3);
$mb_zip2        = substr(trim($_POST['mb_zip']), 3);
$mb_addr1       = trim($_POST['mb_addr1'])      ; 
$mb_addr2       = trim($_POST['mb_addr2'])       ;
$mb_addr3       = trim($_POST['mb_addr3'])      ;
$mb_addr_jibeon = trim($_POST['mb_addr_jibeon']) ;
$mb_1           = trim($_POST['mb_1'])        ;  
$mb_2           = trim($_POST['mb_2'])   ;        

$mb_nick        = clean_xss_tags($mb_nick);
$tmp_mb_nick = iconv('UTF-8', 'UTF-8//IGNORE', $mb_nick);
if($tmp_mb_nick != $mb_nick) {
    alert('닉네임을 올바르게 입력해 주십시오.');
}

$mb_zip1        = preg_replace('/[^0-9]/', '', $mb_zip1);
$mb_zip2        = preg_replace('/[^0-9]/', '', $mb_zip2);
$mb_addr1       = clean_xss_tags($mb_addr1);
$mb_addr2       = clean_xss_tags($mb_addr2);
$mb_addr3       = clean_xss_tags($mb_addr3);
$mb_addr_jibeon = preg_match("/^(N|R)$/", $mb_addr_jibeon) ? $mb_addr_jibeon : '';

if ($config['cf_use_recommend'] && $mb_recommend) {
    if (!exist_mb_id($mb_recommend))
        alert("추천하신 아이디는 없는 아이디 입니다.");
}

if (strtolower($mb_id) == strtolower($mb_recommend)) {
    alert('본인을 추천할 수 없습니다.');
}

$mb_img = '';
$mb_icon_img = $mb_id.'.gif';
$image_regex = "/(\.(gif|jpe?g|png))$/i";
// 회원 프로필 이미지 업로드
if (isset($_FILES['mb_img']) && is_uploaded_file($_FILES['mb_img']['tmp_name'])) {
    $mb_tmp_dir = G5_DATA_PATH.'/member_image/';
    $mb_dir = $mb_tmp_dir.substr($mb_id,0,2);
    if( !is_dir($mb_tmp_dir) ){
        @mkdir($mb_tmp_dir, G5_DIR_PERMISSION);
        @chmod($mb_tmp_dir, G5_DIR_PERMISSION);
    }
    
    if (preg_match($image_regex, $_FILES['mb_img']['name'])) {
        // 아이콘 용량이 설정값보다 이하만 업로드 가능
        if ($_FILES['mb_img']['size'] <= $config['cf_member_img_size']) {
            @mkdir($mb_dir, G5_DIR_PERMISSION);
            @chmod($mb_dir, G5_DIR_PERMISSION);
            $dest_path = $mb_dir.'/'.$mb_icon_img;
            move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if (!($size[2] === 1 || $size[2] === 2 || $size[2] === 3)) { // gif jpg png 파일이 아니면 올라간 이미지를 삭제한다.
                    @unlink($dest_path);
                } else if ($size[0] > $config['cf_member_img_width'] || $size[1] > $config['cf_member_img_height']) {
                    $thumb = null;
                    if($size[2] === 2 || $size[2] === 3) {
                        //jpg 또는 png 파일 적용
                        $thumb = thumbnail($mb_icon_img, $mb_dir, $mb_dir, $config['cf_member_img_width'], $config['cf_member_img_height'], true, true);
                        if($thumb) {
                            @unlink($dest_path);
                            rename($mb_dir.'/'.$thumb, $dest_path);
                        }
                    }
                    if( !$thumb ){
                        // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                        @unlink($dest_path);
                    }
                }
                //=================================================================\
            }
        } else {
            alert('회원이미지을 '.number_format($config['cf_member_img_size']).'바이트 이하로 업로드 해주십시오.');
        }
        
    } else {
        alert($_FILES['mb_img']['name'].'은(는) gif/jpg 파일이 아닙니다.');
    }
}
    


if (isset($_FILES['profile_img']) && is_uploaded_file($_FILES['profile_img']['tmp_name']))
{
    if (preg_match($image_regex, $_FILES['company_file']['name'])) {
        
        $member_dir = G5_DATA_PATH.'/member_image/'.substr($mb_id, 2);
        @mkdir($member_dir, G5_DIR_PERMISSION);
        @chmod($member_dir, G5_DIR_PERMISSION);
        
        $dest_path = $member_dir.'/'.$mb_id.'.gif';
        
        move_uploaded_file($_FILES['company_file']['tmp_name'], $dest_path);
        chmod($dest_path, G5_FILE_PERMISSION);
        
    }
}

$sql = " update lt_member
                set 
                 	mb_nick = '{$mb_nick}',
                 	mb_sex = '{$mb_sex}',
                 	mb_birth = '{$mb_birth}',
                 	mb_recommend = '{$mb_recommend}',
                    mb_zip1 = '{$mb_zip1}',
                 	mb_zip2 = '{$mb_zip2}',
                 	mb_addr1 = '{$mb_addr1}',
                 	mb_addr2 = '{$mb_addr2}',
	         	    mb_addr3 = '{$mb_addr3}',
		            mb_addr_jibeon = '{$mb_addr_jibeon}',
		            mb_1 = '{$mb_1}',
		            mb_2 = '{$mb_2}'
where mb_id = '{$mb_id}'
                     ";
sql_query($sql);

$sql = " update {$g5['g5_shop_order_address_table']}
                set
                 	ad_zip1 = '{$mb_zip1}',
                 	ad_zip2 = '{$mb_zip2}',
                 	ad_addr1 = '{$mb_addr1}',
                 	ad_addr2 = '{$mb_addr2}',
	         	    ad_addr3 = '{$mb_addr3}',
		            ad_jibeon = '{$mb_addr_jibeon}'
where mb_id = '{$mb_id}' and ad_default = '1' ";
sql_query($sql);

set_session("ss_mb_reg", $mb_id);



// 추천인에게 포인트 부여
if ($config['cf_use_recommend'] && $mb_recommend){
    //추천 받은 사람
    insert_point($mb_recommend, $config['cf_recommend_point'], $mb_id.'의 추천인', '@member', $mb_recommend, $mb_id.' 추천');
    
    //추천한 본인
    insert_point($mb_id, $config['cf_recommend_point'], '회원가입시 '.$mb_recommend.' 추천', '@member', $mb_id, $mb_recommend.' 추천등록');
}











goto_url(G5_BBS_URL.'/register_result.php?mb_name='.$mb_nick);
?>