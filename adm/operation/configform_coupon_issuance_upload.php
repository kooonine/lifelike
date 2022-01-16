<?php
$sub_menu = "900900";
include_once("./_common.php");

auth_check($auth[substr($sub_menu,0,2)], "w");

if (!$_FILES['csv']['size'])
    alert_after('파일을 선택해주세요.');
    
    $file = $_FILES['csv']['tmp_name'];
    $filename = $_FILES['csv']['name'];
    
    $pos = strrpos($filename, '.');
    $ext = strtolower(substr($filename, $pos, strlen($filename)));
    
    switch ($ext) {
        case '.csv' :
            $data = file($file);
            $num_rows = count($data) + 1;
            $csv = array();
            foreach ($data as $item)
            {
                array_push($csv, $item);
            }
            break;
        case '.xls' :
            include_once(G5_LIB_PATH.'/Excel/reader.php');
            $data = new Spreadsheet_Excel_Reader();
            
            // Set output Encoding.
            $data->setOutputEncoding('UTF-8');
            $data->read($file);
            $num_rows = $data->sheets[0]['numRows'];
            break;
        default :
            alert_after('xls파일과 csv파일만 허용합니다.');
    }
    
    $counter = 0;
    $success = 0;
    $failure = 0;
    $inner_overlap = 0;
    $overlap = 0;
    $arr_mb_id = array();
    $encode = array('ASCII','UTF-8','EUC-KR');
    
    for ($i = 1; $i <= $num_rows; $i++) {
        $counter++;
        $j = 1;
        
        switch ($ext) {
            case '.csv' :
                $mb_id = $csv[$i];
                $str_encode = @mb_detect_encoding($mb_id, $encode);
                if( $str_encode == "EUC-KR" ){
                    $mb_id = iconv_utf8( $mb_id );
                }                
                $mb_id = addslashes($mb_id);
                break;
            case '.xls' :
                $mb_id = addslashes($data->sheets[0]['cells'][$i][$j++]);
                $str_encode = @mb_detect_encoding($mb_id, $encode);
                if( $str_encode == "EUC-KR" ){
                    $mb_id = iconv_utf8( $mb_id );
                }
                break;
        }
        $mb_id = preg_replace('/\r\n|\r|\n/','',$mb_id);
        
        if (!(strlen($mb_id)))
        {
            $failure++;
            
        }
        else if ($mb_id == "아이디")
        {
            $counter--;
            //$failure++;
            
        } else {
            if (in_array($mb_id, $arr_mb_id))
            {
                $inner_overlap++;
            } else {
                array_push($arr_mb_id, $mb_id);
                
                $res = sql_fetch("select * from {$g5['member_table']} where mb_id='$mb_id'");
                if ($res)
                {
                    $overlap++;
                }
            }
        }
        if ($inner_overlap > 0) $overlap += $inner_overlap;
    }
    
    unlink($_FILES['csv']['tmp_name']);
    
    $result = $counter - $failure - $overlap;
    
    echo "<script>
var info = parent.document.getElementById('upload_info');
var html = '';
html += \"총 건수 : ".number_format($counter)." 건 / \";";
    
if (count($arr_mb_id) > 0)
{
    echo "html += \"등록가능 ".number_format(count($arr_mb_id))." 건\";";
} else {
    echo "html += \"등록할 수 없습니다.\";";
}
    
echo "
parent.document.getElementById('upload_button').style.display = 'inline';
parent.document.getElementById('uploading').style.display = 'none';
            
info.style.display = 'block';
info.innerHTML = html;
parent.document.getElementById('mb_id_list').value = '".implode(",", $arr_mb_id)."';
parent.loadmember();
";
        
echo "</script>";    
    
function alert_after($str) {
        echo "<script>
parent.document.getElementById('upload_button').style.display = 'inline';
parent.document.getElementById('uploading').style.display = 'none';
parent.document.getElementById('upload_info').style.display = 'none';
</script>";
        alert_just($str);
    }
    ?>