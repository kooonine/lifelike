<?php
$sub_menu = "100290";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

check_admin_token();

$count = count($_POST['code']);

for ($i=0; $i<$count; $i++)
{
    $_POST = array_map_deep('trim', $_POST);
    
    $me_id    = $_POST['me_id'][$i];
    $me_code    = $_POST['code'][$i];
    $me_name = $_POST['me_name'][$i];
    $depth = $_POST['depth'];
    $me_link = preg_match('/^javascript/i', $_POST['me_link'][$i]) ? G5_URL : strip_tags($_POST['me_link'][$i]);
    
    if(!$me_name || !$me_link || !$depth)
    {
        continue;
    }
    
    if(!$me_id)
    {
        //insert $me_code 생성 필요.
        $p_code = $_POST['p_code'];
        
        $sql = "select IFNULL(MAX(SUBSTRING(me_code,((me_depth-1) * 2 + 1) ,2)),'00') as max_me_code
                from    {$g5['menu_table']}
                where   me_code like '".$p_code."%'
                and     me_depth = '".$depth."'";
        
        $row = sql_fetch($sql);
        
        $sub_code = base_convert($row['max_me_code'], 36, 10);
        $sub_code += 36;
        $sub_code = base_convert($sub_code, 10, 36);
        
        $me_code = $p_code.$sub_code;
    }
    
    // 메뉴 등록
    $sql = "    set me_code         = '$me_code',
                    me_name         = '$me_name',
                    me_link         = '$me_link',
                    me_target       = '{$_POST['me_target'][$i]}',
                    me_order        = '{$_POST['me_order'][$i]}',
                    me_use          = '{$_POST['me_use'][$i]}',
                    me_mobile_use   = '{$_POST['me_use'][$i]}',
                    me_depth        = '$depth' ";
    if(!$me_id)
    {
        //insert
        $sql = " insert into {$g5['menu_table']} " . $sql;
        
    } else {
        //update
        $sql = " update {$g5['menu_table']} " . $sql . " where me_id = '$me_id' ";
    }
    
    if(false)
    {
        //Test시 사용
        echo $sql;
        
    } else {
        sql_query($sql);
    }
}
?>
<script>
alert("변경되었습니다.");
window.onunload = refreshParent;
function refreshParent() {
    window.opener.location.reload();
}
self.close();
</script>


