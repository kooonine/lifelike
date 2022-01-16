<?php

$sql_common = " from lt_member_memo as a";
$sql_search = " where mb_id = '{$mb_id}' ";
if ($stx) {
    $sql_search .= " (mm_memo like '{$stx}%') ";
}

if ($mm_time != "") {
    $mb_datetimes = explode("~", $mm_time);
    $fr_mb_datetime = trim($mb_datetimes[0]);
    $to_mb_datetime = trim($mb_datetimes[1]);
    
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_datetime) ) $fr_mb_datetime = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_datetime) ) $to_mb_datetime = '';
    
    if ($fr_mb_datetime && $to_mb_datetime) {
        $sql_search .= " and mm_time between '$fr_mb_datetime 00:00:00' and '$to_mb_datetime 23:59:59' ";
    }
}

if (!$sst) {
    $sst = "mm_no";
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

$qstr = "mb_id=".urlencode($mb_id)."&amp;mode=".urlencode($mode)."&amp;mm_time=".urlencode($mm_time)."&amp;stx=".urlencode($stx);
?>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
    	
    <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" onsubmit="$('#page').val('1');">
    <input type="hidden" name="page"  id="page" value="<?php echo $page; ?>">
    
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="mode"  value="<?php echo $mode ?>">
    <input type="hidden" name="mb_id"  value="<?php echo $mb_id ?>">
    <input type="hidden" name="mm_no"  value="<?php echo $mm['mm_no'] ?>">
        
    <div class="tbl_frm01 tbl_wrap">
        <table>
    	<colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_3">
        </colgroup>
        
        <tr>
            <th scope="row">가입일</th>
    		<td colspan="2">
            	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                	<input type='text' class="form-control" id="mm_time" name="mm_time" value=""/>
                	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
            	</div>
            	<div class="btn-group col-lg-8 col-md-6 col-sm-12 col-xs-12" >
                    <button type="button" class="btn btn_02" name="dateBtn" data="all">전체</button>
                    <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                    <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                    <button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                    <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                    <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                 </div>
    		</td>
    	</tr>
        
        <tr>
            <th scope="row">메모</th>
    		<td colspan="2">
    		<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class="frm_input" style="width:100%">
    		</td>
    	</tr>
    	</table>
    </div>
    <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        	<button class="btn btn_02" type="reset" id="btn_clear">초기화</button>
        	<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>검색</button>
        </div>
    </div>
	</form>
	
	<div class="tbl_head01 tbl_wrap">
        <table>
        <caption>목록</caption>
        <thead>
        <tr>
            <th scope="col" id="mb_list_chk">번호</th>
            <th scope="col" id="mb_list_join"><?php echo subject_sort_link('mm_time', '', 'desc') ?>작성일</a></th>
            <th scope="col" id="mb_list_name">작성자</a></th>
            <th scope="col" id="mb_list_sex">내용</th>
            <th scope="col" id="mb_list_mng">관리</th>
        </tr>
        </thead>
        <tbody>
        
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
        ?>
        <tr>
            <td><?php echo ($i+1+$from_record) ?></td>
            <td><?php echo $row['mm_time'] ?></td>
            <td><?php echo $row['mm_mb_name'] ?>(<?php echo $row['mm_mb_id'] ?>)</td>
            <td>
            <?php 
                if($row['is_important']) echo '<span class="label label-warning">중요메모</span>&nbsp;'; 
            ?>
            <?php echo get_text($row['mm_memo']); ?></td>
            <td>
    	    	<input type="button" value="수정" class="btn btn_01 btn_mod" mm_no='<?php echo $row['mm_no']?>'>
	        	<input type="button" value="삭제" class="btn btn_02 btn_del" mm_no='<?php echo $row['mm_no']?>'>
            </td>
        </tr>
        <?php } 
        if ($i == 0)
            echo "<tr><td colspan=\"5\" class=\"empty_table\">자료가 없습니다.</td></tr>";
        ?>
        </tbody>
        </table>
	</div>
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>
	
	
	<?php 
	if(isset($mm_no) && $mm_no != "" && $w == 'u')
	{
	    $sql = " select * from  lt_member_memo where mm_no = '{$mm_no}' ";
	    $mm = sql_fetch($sql);
	}
	?>
	<form name="fwrite" id="fwrite" action="./member_form_memo_update.php" onsubmit="return fmember_submit(this);" method="post" >
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">
    <input type="hidden" name="mode"  value="<?php echo $mode ?>">
    <input type="hidden" name="mb_id"  value="<?php echo $mb_id ?>">
    <input type="hidden" name="mm_no"  value="<?php echo $mm['mm_no'] ?>">
    
    <div class="tbl_frm01 tbl_wrap">
    <table>
    <tbody>
    <tr>
        <th scope="row" rowspan="2"><label for="mm_memo">메모</label></th>
        <td><textarea name="mm_memo" id="mm_memo" required="required" style="width: 100%"><?php echo $mm['mm_memo'] ?></textarea></td>
    </tr>
    <tr>
        <td>
	        <label><input type="checkbox" name="is_important" value="1" id="is_important" <?php echo get_checked($mm['is_important'], "1"); ?> > 중요메모</label>
        </td>
    </tr>
	</tbody>
	</table>
	</div>
    <div class="pull-right">
    	<?php if($w=="u") echo '<input type="button" value="신규 메모" class="btn" id="btn_new" >'; ?>
        <input type="submit" value="메모 저장" class="btn_submit btn" accesskey='s'>
    </div>
	</form>
	
	</div>
	</div>
</div>
        

<script>
$(function(){
	
	$('#btn_new').click(function(){
		fsearch.w.value = '';
		fsearch.mm_no.value = ""; 
		fsearch.submit();
	});

	$('.btn_mod').click(function(){
		var mm_no = $(this).attr("mm_no");

		fsearch.w.value = 'u';
		fsearch.mm_no.value = mm_no; 
		fsearch.submit();

	});

	$('.btn_del').click(function(){
		if(confirm("삭제 하시겠습니까?")){
    		var mm_no = $(this).attr("mm_no");
    
    		fwrite.w.value = 'd';
    		fwrite.mm_no.value = mm_no; 
    		fwrite.submit();
		}
	});
	
    $('#mm_time').daterangepicker({
    	"autoApply": true,
    	"opens": "right",
    	locale: {
            "format": "YYYY-MM-DD",
            "separator": " ~ ",
            "applyLabel": "선택",
            "cancelLabel": "취소",
            "fromLabel": "시작일자",
            "toLabel": "종료일자",
            "customRangeLabel": "직접선택",
            "weekLabel": "W",
            "daysOfWeek": ["일","월","화","수","목","금","토"],
            "monthNames": ["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],
            "firstDay": 1
        }
    });
    <?php 
        if($mm_time !='') echo "$('#mm_time').val('".$mm_time."');";
       else echo "$('#mm_time').val('');";
    ?>
    
    //날짜 버튼
    $("button[name='dateBtn']").click(function(){
    	
    	var d = $(this).attr("data");
    	if(d == "all") {
    		$('#mm_time').val("");
    	} else {
    		var startD = moment();
    		var endD = moment();
            
    		if(d == "3d") {
    			startD = moment().subtract(2, 'days');
    			endD = moment();
    			
    		} else if(d == "1w") {
    			startD = moment().subtract(6, 'days');
    			endD = moment();
    			
    		} else if(d == "1m") {
    			startD = moment().subtract(1, 'month');
    			endD = moment();
    			
    		} else if(d == "3m") {
    			startD = moment().subtract(3, 'month');
    			endD = moment();
    		}
    
    		$('#mm_time').data('daterangepicker').setStartDate(startD);
    		$('#mm_time').data('daterangepicker').setEndDate(endD);
    	}
    });
});

function fmember_submit(f)
{
	
	return true;
}
</script>