<?php
$sub_menu = '800500';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

$sql = " select * from lt_design_side where side_id = '1' ";
$view = sql_fetch($sql);
$main_view_data = json_decode(str_replace('\\','',$view['main_view_data']), true);


$sql_common = " ";

if (is_checked('main_type2')) $sql_common .= " , main_type2 = '{$_POST['main_type2']}' ";

$main_view_json = array();

$image_regex = "/(\.(gif|jpe?g|png))$/i";

$count = count($_POST['imgOrder']);
//echo $count.'<br/>';

$imgfile = array();

//print_r($_FILES);

for ($i=0; $i<$count; $i++)
{
    $imgfile[$i] = array();
    $imgfile[$i]['imgOrder'] = $_POST['imgOrder'][$i];
    
    $imgfile[$i]['imgLinkYN'] = $_POST['imgLinkYN'.($i+1)];
    $imgfile[$i]['linkURL'] = $_POST['linkURL'][$i];
    
    if (isset($_FILES['imgFile']) && is_uploaded_file($_FILES['imgFile']['tmp_name'][$i]))
    {
        // 기존 동영상 이미지가 있는 경우 삭제
        if ($main_view_data['imgFile'][$i]['imgFile'])
            @unlink(G5_DATA_PATH.'/sidemenu/'.$side_id.'/'.$main_view_data['imgFile'][$i]['imgFile']);
            
            if (!preg_match($image_regex, $_FILES['imgFile']['name'][$i])) {
                alert($_FILES['imgFile']['name'][$i] . '은(는) 이미지 파일이 아닙니다.');
            }
            
            if (preg_match($image_regex, $_FILES['imgFile']['name'][$i])) {
                
                $design_dir = G5_DATA_PATH.'/sidemenu/'.$side_id;
                @mkdir($design_dir, G5_DIR_PERMISSION);
                @chmod($design_dir, G5_DIR_PERMISSION);
                
                $dest_path = $design_dir.'/'.$_FILES['imgFile']['name'][$i];
                
                move_uploaded_file($_FILES['imgFile']['tmp_name'][$i], $dest_path);
                chmod($dest_path, G5_FILE_PERMISSION);
                
                $imgfile[$i]['imgFile'] = $_FILES['imgFile']['name'][$i];
            }
    } else if($_POST['orgimgFile'][$i] && $_POST['orgimgFile'][$i] != "") {
        
        $imgfile[$i]['imgFile'] = $_POST['orgimgFile'][$i];
        
    } else {
        unset($imgfile[$i]);
    }
}
//print_r($imgfile);
//$imgOrder  = array_column($imgfile, 'imgOrder');
//array_multisort($imgOrder, SORT_ASC, $imgfile);

$main_view_json['imgFile'] = $imgfile;  
    
if(count($main_view_json) > 0)
{
    $main_view_data = json_encode_raw($main_view_json, JSON_UNESCAPED_UNICODE);
    
    $sql_common .= " , main_view_data = '{$main_view_data}' ";
}

$sql = " update lt_design_side
            set main_datetime = now()
                ,mobile_onoff = '{$mobile_onoff}'
                {$sql_common}
          where side_id = '{$side_id}' ";

if(false)
{
    //Test시 사용
    echo $sql;
    
} else {
    sql_query($sql);
    alert('적용되었습니다.', './design_sidemenu.php', false);
}

?>
