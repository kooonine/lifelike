<?php
$sub_menu = '800140';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

check_admin_token();

try{

$sql = " select * from lt_design_main_mobile where main_id = '{$main_id}' ";
$view = sql_fetch($sql);
$main_view_data = json_decode(str_replace('\\','',$view['main_view_data']), true);


$_POST['main_name'] = strip_tags($_POST['main_name']);

$sql_common = " ";

if (is_checked('main_type2')) $sql_common .= " , main_type2 = '{$_POST['main_type2']}' ";

$main_view_json = array();
if (is_checked('title_name')) $main_view_json['title_name'] = $_POST['title_name'];

$image_regex = "/(\.(gif|jpe?g|png))$/i";
$movie_regex = "/(\.(mov|mp4|avi|mkv))$/i";

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

$design_dir = G5_DATA_PATH.'/design/'.$main_id;
@mkdir($design_dir, G5_DIR_PERMISSION);
@chmod($design_dir, G5_DIR_PERMISSION);

//echo $_POST['main_type1']."<br/>";
//movie
if ($_POST['main_type1'] == "movie")
{
    $main_view_json['imgLinkYN'] = $_POST['imgLinkYN0'];
    $main_view_json['linkURL'] = $_POST['linkURL'][0];
    
	//동영상 이미지 파일업로드
	if (isset($_FILES['movieimg']) && is_uploaded_file($_FILES['movieimg']['tmp_name']))
	{
		$tmp_file  = $_FILES['movieimg']['tmp_name'];
		$filesize  = $_FILES['movieimg']['size'];
		$filename  = $_FILES['movieimg']['name'];
		$filename  = get_safe_filename($filename);

		// 기존 동영상 이미지가 있는 경우 삭제
		if ($main_view_data['movieimg']) {
			@unlink($design_dir.'/'.$main_view_data['movieimg']);

			$fn = preg_replace("/\.[^\.]+$/i", "", basename($main_view_data['movieimg']));
			$delfiles = glob(G5_DATA_PATH.'/design/'.$main_id.'/thumb-m-'.$fn.'*');
			if (is_array($delfiles)) {
				foreach ($delfiles as $delfilename)
					@unlink($delfilename);
			}

		}

		if (!preg_match($image_regex, $filename)) {
			alert($filename . '은(는) 이미지 파일이 아닙니다.');

		} else {

			// 프로그램 원래 파일명
			$main_view_json['movieimgsource'] = $filename;
			$main_view_json['movieimgfilesize'] = $filesize;

			shuffle($chars_array);
			$shuffle = implode('', $chars_array);

			// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
			$main_view_json['movieimg'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

			$dest_file = $design_dir.'/'.$main_view_json['movieimg'];

			// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
			$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['movieimg']['error'][$i]);

			// 올라간 파일의 퍼미션을 변경합니다.
			chmod($dest_file, G5_FILE_PERMISSION);

			if (!get_magic_quotes_gpc()) {
				$main_view_json['movieimg'] = addslashes($main_view_json['movieimg']);
			}
		}
	} else {
		$main_view_json['movieimg'] = $main_view_data['movieimg'];
	}

	//동영상 파일업로드
	if (isset($_FILES['moviefile']) && is_uploaded_file($_FILES['moviefile']['tmp_name']))
	{
		$tmp_file  = $_FILES['moviefile']['tmp_name'];
		$filesize  = $_FILES['moviefile']['size'];
		$filename  = $_FILES['moviefile']['name'];
		$filename  = get_safe_filename($filename);

		// 기존 동영상 이미지가 있는 경우 삭제
		if ($main_view_data['moviefile']) {
			@unlink($design_dir.'/'.$main_view_data['moviefile']);
		}

		if (!preg_match($movie_regex, $filename)) {
			alert($filename . '은(는) 동영상 파일이 아닙니다.');
		} else {

			// 프로그램 원래 파일명
			$main_view_json['moviefilesource'] = $filename;
			$main_view_json['moviefilefilesize'] = $filesize;

			shuffle($chars_array);
			$shuffle = implode('', $chars_array);

			// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
			$main_view_json['moviefile'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

			$dest_file = $design_dir.'/'.$main_view_json['moviefile'];

			// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
			$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['moviefile']['error'][$i]);

			// 올라간 파일의 퍼미션을 변경합니다.
			chmod($dest_file, G5_FILE_PERMISSION);

			if (!get_magic_quotes_gpc()) {
				$main_view_json['moviefile'] = addslashes($main_view_json['moviefile']);
			}
		}
	} else if($_POST['orgmoviefile'] && $_POST['orgmoviefile'] != "") {
	    
	    $main_view_json['moviefile'] = $_POST['orgmoviefile'];
	} else {
	    // 기존 동영상 이미지가 있는 경우 삭제
	    if ($main_view_data['moviefile']) {
	        @unlink($design_dir.'/'.$main_view_data['moviefile']);
	    }
	    $main_view_json['moviefile'] = "";
	}
}
else if ($_POST['main_type1'] == "image" || $_POST['main_type1'] == "imagetext" || $_POST['main_type1'] == "rolling" || $_POST['main_type1'] == "motion" || $_POST['main_type1'] == "banner")
{
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

		if($_POST['imgTextYN'.($i+1)]) $imgfile[$i]['imgTextYN'] = $_POST['imgTextYN'.($i+1)];
		if($_POST['mainText'][$i]) $imgfile[$i]['mainText'] = $_POST['mainText'][$i];
		if($_POST['subText'][$i]) $imgfile[$i]['subText'] = $_POST['subText'][$i];

		if (isset($_FILES['imgFile']) && is_uploaded_file($_FILES['imgFile']['tmp_name'][$i]))
		{

			$tmp_file  = $_FILES['imgFile']['tmp_name'][$i];
			$filesize  = $_FILES['imgFile']['size'][$i];
			$filename  = $_FILES['imgFile']['name'][$i];
			$filename  = get_safe_filename($filename);

			// 기존 동영상 이미지가 있는 경우 삭제
			if ($main_view_data['imgFile'][$i]['imgFile']){
				@unlink(G5_DATA_PATH.'/design/'.$main_id.'/'.$main_view_data['imgFile'][$i]['imgFile']);

				$fn = preg_replace("/\.[^\.]+$/i", "", basename($main_view_data['imgFile'][$i]['imgFile']));
				$delfiles = glob(G5_DATA_PATH.'/design/'.$main_id.'/thumb-m-'.$fn.'*');
				if (is_array($delfiles)) {
					foreach ($delfiles as $delfilename)
						@unlink($delfilename);
				}
			}

			if (!preg_match($image_regex, $filename)) {
				alert($filename . '은(는) 이미지 파일이 아닙니다.');

			} else {

				// 프로그램 원래 파일명
				$imgfile[$i]['source'] = $filename;
				$imgfile[$i]['filesize'] = $filesize;

				shuffle($chars_array);
				$shuffle = implode('', $chars_array);

				// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
				$imgfile[$i]['imgFile'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

				$dest_file = $design_dir.'/'.$imgfile[$i]['imgFile'];

				// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
				$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['imgFile']['error'][$i]);

				// 올라간 파일의 퍼미션을 변경합니다.
				chmod($dest_file, G5_FILE_PERMISSION);

				if (!get_magic_quotes_gpc()) {
					$imgfile[$i]['imgFile'] = addslashes($imgfile[$i]['imgFile']);
				}


			}
		} else if($_POST['orgimgFile'][$i] && $_POST['orgimgFile'][$i] != "") {

			$imgfile[$i]['imgFile'] = $_POST['orgimgFile'][$i];

		} else {
			// 기존 동영상 이미지가 있는 경우 삭제
			if ($main_view_data['imgFile'][$i]['imgFile']){
				@unlink(G5_DATA_PATH.'/design/'.$main_id.'/'.$main_view_data['imgFile'][$i]['imgFile']);

				$fn = preg_replace("/\.[^\.]+$/i", "", basename($main_view_data['imgFile'][$i]['imgFile']));
				$delfiles = glob(G5_DATA_PATH.'/design/'.$main_id.'/thumb-m-'.$fn.'*');
				if (is_array($delfiles)) {
					foreach ($delfiles as $delfilename)
						@unlink($delfilename);
				}
			}

			unset($imgfile[$i]);
		}
	}
	//print_r($imgfile);
	//$imgOrder  = array_column($imgfile, 'imgOrder');
	//array_multisort($imgOrder, SORT_ASC, $imgfile);

	$main_view_json['imgFile'] = $imgfile;

} else if ($_POST['main_type1'] == "sns") {

	if (is_checked('hashtag')) $main_view_json['hashtag'] = $_POST['hashtag'];
	if (is_checked('imgOrder')) $main_view_json['imgOrder'] = $_POST['imgOrder'];
	if (is_checked('widget')) $main_view_json['widget'] = $_POST['widget'];
	if (is_checked('imgsize')) $main_view_json['imgsize'] = $_POST['imgsize'];

	if (is_checked('imgCol')) $main_view_json['imgCol'] = $_POST['imgCol'];
	if (is_checked('imgRow')) $main_view_json['imgRow'] = $_POST['imgRow'];
	if (is_checked('imgBorder')) $main_view_json['imgBorder'] = $_POST['imgBorder'];

	if (is_checked('imgDistance')) $main_view_json['imgDistance'] = $_POST['imgDistance'];

} else if ($_POST['main_type1'] == "product") {

	if (is_checked('it_id_list')) {
		$it_id_list = explode(",", $_POST['it_id_list']);
		$it_id = array();
		for ($i = 0; $i < count($it_id_list); $i++) {
			if($it_id_list[$i] != "") $it_id[] = $it_id_list[$i];
		}
		$main_view_json['it_id'] = $it_id;
	}

} else if ($_POST['main_type1'] == "subproduct") {
	if (is_checked('view_count')) $main_view_json['view_count'] = $_POST['view_count'];

	if (is_checked('it_id_list')) {
		$it_id_list = explode(",", $_POST['it_id_list']);
		$it_id = array();
		for ($i = 0; $i < count($it_id_list); $i++) {
			if($it_id_list[$i] != "") $it_id[] = $it_id_list[$i];
		}
		$main_view_json['it_id'] = $it_id;
	}
}

if(count($main_view_json) > 0)
{

		$main_view_data = json_encode_raw($main_view_json, JSON_UNESCAPED_UNICODE);
		$sql_common .= " , main_view_data = '{$main_view_data}' ";

}

$sql = " update lt_design_main_mobile
			set main_name = '{$_POST['main_name']}'
				,main_type1 = '{$_POST['main_type1']}'
				,main_datetime = now()
				{$sql_common}
		  where main_id = '{$main_id}' ";

if(false)
{
	//Test시 사용
	echo $sql;

} else {
	sql_query($sql);
	alert('적용되었습니다.', './design_layout_mobile.php', false);
}

}catch(Exception $e){
	echo print_r2($e);
}
?>
