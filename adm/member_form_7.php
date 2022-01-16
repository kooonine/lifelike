<?php

$sql_common = " from lt_login_history as a";
$sql_search = " where mb_id = '{$mb_id}' ";


if (!$sst) {
    $sst = "lh_id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.* {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$token = get_admin_token();

$qstr = "mb_id=".urlencode($mb_id)."&amp;mode=".urlencode($mode)."&amp;page_rows=".urlencode($page_rows);
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
    	
    <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" onsubmit="$('#page').val('1');">
    <input type="hidden" name="page"  id="page" value="<?php echo $page; ?>">
    
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="mode"  value="<?php echo $mode ?>">
    <input type="hidden" name="mb_id"  value="<?php echo $mb_id ?>">
        
    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 로그인 로그 <small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
    </div>

    <div style="float: right;">
        <select name="page_rows" onchange="fsearch.submit();">
            <option value="10" <?php echo get_selected($_GET['page_rows'], "10") ?>>10개씩 보기</option>
            <option value="30" <?php echo get_selected($_GET['page_rows'], "30") ?>>30개씩 보기</option>
            <option value="50" <?php echo get_selected($_GET['page_rows'], "50") ?>>50개씩 보기</option>
        </select>
    </div>
    
	</form>
	
	<div class="tbl_head01 tbl_wrap">
        <table>
        <thead>
        <tr>
            <th scope="col" id="mb_list_chk">번호</th>
            <th scope="col" id="mb_list_name">IP</a></th>
            <th scope="col" id="mb_list_sex">일시</th>
        </tr>
        </thead>
        <tbody>
        
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
        ?>
        <tr>
            <td class="grid_1"><?php echo ($i+1+$from_record) ?></td>
            <td><?php echo $row['lh_ip'] ?></td>
            <td><?php echo $row['lh_date_time'] ?></td>
        </tr>
        <?php } 
        if ($i == 0)
            echo "<tr><td colspan=\"3\" class=\"empty_table\">자료가 없습니다.</td></tr>";
        ?>
        </tbody>
        </table>
	</div>
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>
</div>
</div>
</div>        

<script>
$(function(){
	
});
</script>