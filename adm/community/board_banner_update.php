<?php
$sub_menu = '900110';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

//check_admin_token();

$result = '';
$alertMsg = '';

$banner_json = array();

$image_regex = "/(\.(gif|jpe?g|png))$/i";

$count = $_POST['rdo_banner_count'];

$imgfile = array();

$banner_json['bannerCount'] = $count;

//print_r($_FILES);
$bo_table = $_POST['bo_table'];
$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

for ($i=0; $i<$count; $i++)
{
    $imgfile[$i] = array();
    $imgfile[$i]['imgOrder'] = $_POST['imgOrder'][$i];
    
    $imgfile[$i]['linkURL'] = $_POST['linkURL'][$i];
    $imgfile[$i]['muse'] = $_POST['mimg_use'.($i+1)];
    
    $orgimgFile = $_POST['orgimgFile'][$i];
    
    if (isset($_FILES['imgFile']) && is_uploaded_file($_FILES['imgFile']['tmp_name'][$i]))
    {
        if (!preg_match($image_regex, $_FILES['imgFile']['name'][$i])) {
            
            $result = 'F';
            $alertMsg = $_FILES['imgFile']['name'][$i] . '은(는) 이미지 파일이 아닙니다.';
            break;
        }
        
        // 기존 이미지가 있는 경우 삭제
        if ($orgimgFile) {
            @unlink(G5_DATA_PATH.'/banner/'.$bo_table.'/'.$orgimgFile);
        }
        
        if (preg_match($image_regex, $_FILES['imgFile']['name'][$i])) {
            
            $design_dir = G5_DATA_PATH.'/banner/'.$bo_table;
            @mkdir($design_dir, G5_DIR_PERMISSION);
            @chmod($design_dir, G5_DIR_PERMISSION);
            
            shuffle($chars_array);
            $shuffle = implode('', $chars_array);
            $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($_FILES['imgFile']['name'][$i]);
            
            $dest_path = $design_dir.'/'.$dest_file;
            
            move_uploaded_file($_FILES['imgFile']['tmp_name'][$i], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);
            
            $imgfile[$i]['imgFile'] = $dest_file;
        }
        
    } else if($_POST['orgimgFile'][$i] && $_POST['orgimgFile'][$i] != "") {
        
        $imgfile[$i]['imgFile'] = $_POST['orgimgFile'][$i];
        
    } else {
        unset($imgfile[$i]);
    }
    
    $orgmimgFile = $_POST['orgmimgFile'][$i];
    
    if($_POST['mimg_use'.($i+1)] == '1')
    {
        if (isset($_FILES['mimgFile']) && is_uploaded_file($_FILES['mimgFile']['tmp_name'][$i]))
        {
            if (!preg_match($image_regex, $_FILES['mimgFile']['name'][$i])) {
                
                $result = 'F';
                $alertMsg = $_FILES['mimgFile']['name'][$i] . '은(는) 이미지 파일이 아닙니다.';
                break;
            }
            
            // 기존 이미지가 있는 경우 삭제
            if ($orgmimgFile) {
                @unlink(G5_DATA_PATH.'/banner/'.$bo_table.'/'.$orgmimgFile);
            }
            
            if (preg_match($image_regex, $_FILES['mimgFile']['name'][$i])) {
                
                $design_dir = G5_DATA_PATH.'/banner/'.$bo_table;
                @mkdir($design_dir, G5_DIR_PERMISSION);
                @chmod($design_dir, G5_DIR_PERMISSION);
                
                shuffle($chars_array);
                $shuffle = implode('', $chars_array);
                $dest_file = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($_FILES['mimgFile']['name'][$i]);
                
                $dest_path = $design_dir.'/'.$dest_file;
                
                move_uploaded_file($_FILES['mimgFile']['tmp_name'][$i], $dest_path);
                chmod($dest_path, G5_FILE_PERMISSION);
                
                $imgfile[$i]['mimgFile'] = $dest_file;
            }
            
        } else if($_POST['orgmimgFile'][$i] && $_POST['orgmimgFile'][$i] != "") {
            $imgfile[$i]['mimgFile'] = $_POST['orgmimgFile'][$i];
        }
        
    } else if($orgmimgFile) {
        // 기존 이미지가 있는 경우 삭제
        @unlink(G5_DATA_PATH.'/banner/'.$bo_table.'/'.$orgmimgFile);
    }
    
}

if($result != "F")
{
    //print_r($imgfile);
    //$imgOrder  = array_column($imgfile, 'imgOrder');
    //array_multisort($imgOrder, SORT_ASC, $imgfile);
    
    $banner_json['imgFile'] = $imgfile;  
    
    $bo_banner = json_encode_raw($banner_json, JSON_UNESCAPED_UNICODE);
    
    $sql = " update lt_board
                set bo_banner = '{$bo_banner}'
              where bo_table = '{$bo_table}' ";
    
    if(false)
    {
        //Test시 사용
        echo $sql;
        
    } else {
        sql_query($sql);
        echo '{"result":"S"}';
    }
} else {
    echo '{"result":"'.$result.'", "alertMsg":"'.$alertMsg.'" }';
}
?>
