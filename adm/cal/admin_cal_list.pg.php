<?php

$sql_common = " from lt_shop_pg_cal ";

$sst = "pc_datetime";
$sod = "desc";
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql = " select *
          {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$token = get_admin_token();

$qstr = "cal_type=1&amp;page_rows=".urlencode($page_rows);
?>
<div class="x_content">
	<div class="form-group">
		<div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:8px;">
 			<a href="http://pgweb.uplus.co.kr" target="_blank"><input type="button" class="btn btn_01" value="LG 유플러스 상점관리자"></input></a>
 			<br/><br/>		 
			<label class="red">| PG사에서 다운로드 받은 정산내역 파일을 업로드 합니다.</label>
		</div>
	</div>
</div>

<form name="fwrite" id="fwrite" action="./admin_cal_update.php" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return fwrite_submit(this)">
<input type="hidden" name="cal_type"  value="<?php echo $cal_type; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token?>">
<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    </colgroup>
    <tr>
        <th scope="row">파일명</th>
		<td>
    		<input type="text" name="pc_name" value="" id="pc_name"  required class="frm_input full_input required" size="100" maxlength="100" placeholder="파일명 100자까지 입력가능" style="width: 100%"> 
		</td>
	</tr>
    <tr>
        <th scope="row">정산내역 업로드</th>
		<td>
           	<input type="file" name="pc_file" id="pc_file" required title="" class="frm_file " accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
		</td>
	</tr>
	</table>
</div>
<div class="form-group">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
    	<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>파일업로드</button>
    </div>
</div>
</form>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" onsubmit="$('#page').val('1');">
<input type="hidden" name="page"  id="page" value="<?php echo $page; ?>">
<input type="hidden" name="cal_type"  value="<?php echo $cal_type; ?>">
<div class="tbl_head01 tbl_wrap">
    <div class="pull-right">

    <select name="page_rows" onchange="$('#fsearch').submit();">
        <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
        <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
        <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
    </select>
	</div>
</div>
</form>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" >업로드일</th>
        <th scope="col" >파일명</th>
        <th scope="col" >업로드ID</th>
        <th scope="col" >관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_name" class="td_mbname"><?php echo $row['pc_datetime']; ?></td>
        <td headers="mb_list_name" class="td_mbname"><?php echo $row['pc_name']; ?></td>
        <td headers="mb_list_name" class="td_mbname"><?php echo $row['mb_name'].'('.$row['mb_id'].')'; ?></td>
        <td headers="mb_list_mng" class="td_mng td_mng_s">
        	<input type="button" class="btn btn_02 btn_del" value="삭제" pc_id="<?php echo $row['pc_id'] ?>">
        	<a href="<?php echo G5_DATA_URL.'/cal/'.$row['pc_source'] ?>" download='<?php echo $row['pc_file'] ?>'><input type="button" class="btn btn_02 btn_download" value="다운로드"></a>
        </td>
    </tr>
    <?php
    }
    
    if ($i == 0)
        echo "<tr><td colspan=\"4\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<form name="fdel" id="fdel" action="./admin_cal_list_update.php" method="post" autocomplete="off" >
<input type="hidden" name="cal_type"  value="<?php echo $cal_type; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token?>">
<input type="hidden" name="act" value="delete">
<input type="hidden" name="pc_id" value="">
</form>
<script>
$(function(){
    $(".btn_del").click(function(){
        var pc_id = $(this).attr("pc_id");
        if(confirm("파일 삭제시 정산예정금액 및 정산내역에서 모두 내역이 삭제됩니다. 진행하시겠습니까")){

			$("input[name='pc_id']").val(pc_id);
        	$("#fdel").submit();
        }
        
    });
});

function fmemberlist_btn_click(btnStatus)
{
    if (!is_checked("chk[]")) {
        alert(btnStatus+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

	switch (btnStatus)
    {
        case "불량회원" :
        	$("#modal_intercept").modal("show");
        	break;
        case "MEMO" :
        	$("#modal_intercept").modal("show");
        	break;
        case "EMAIL" :
        	document.pressed="개별메일전송";
        	$("#fmemberlist").submit();
        	break;
    }
}

function fwrite_submit(f)
{
    

    return true;
}


</script>
