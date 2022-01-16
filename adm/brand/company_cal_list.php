<?php
$sub_menu = "50";
include_once('./_common.php');

$g5['title'] = '입점몰 정산관리';
include_once ('../admin.head.php');

$mb_id = $member['mb_id'];
$sql = "select * from lt_member_company where mb_id = '{$mb_id}' ";
$cp = sql_fetch($sql);

$where = array();
$where[] = " (a.company_code = '{$cp['company_code']}') ";

if ($sc_od_s_y && $sc_od_s_m && $sc_od_e_y && $sc_od_e_m) {
    
    $fr_month = date_format(date_create($sc_od_s_y.'-'.$sc_od_s_m.'-01'),"Y-m");
    $to_month = date_format(date_create($sc_od_e_y.'-'.$sc_od_e_m.'-01'),"Y-m");
    
} else {
    $fr_date = date_create(G5_TIME_YMD);
    date_add($fr_date, date_interval_create_from_date_string('-3 months'));
    $fr_month = date_format($fr_date,"Y-m");
    
    $to_month = substr(G5_TIME_YMD,0,7);
}

$where[] = " (yymm between '".$fr_month."' and '".$to_month."' ) ";

if ($where) {
    $sql_search = ' and '.implode(' and ', $where);
}

if ($sort1 == "") $sort1 = "cc_id";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from lt_shop_company_cal as a, lt_member_company as b where a.company_code = b.company_code $sql_search ";

$sql = " select count(cc_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *
           $sql_common
           order by $sort1 $sort2
           limit $from_record, $rows ";

$result = sql_query($sql);

$qstr = "cal_type=2&amp;page_rows=".urlencode($page_rows);
?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" onsubmit="$('#page').val('1');">
<input type="hidden" name="page"  id="page" value="<?php echo $page; ?>">
<input type="hidden" name="cal_type"  value="<?php echo $cal_type; ?>">
<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    </colgroup>
    <tr>
        <th scope="row">검색기간</th>
        <td>
        	<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        		<?php echo month_select($fr_month, 'sc_od_s')?>
        		~ <?php echo month_select($to_month, 'sc_od_e')?>
        	</div>
        	<div class="btn-group col-lg-6 col-md-6 col-sm-12 col-xs-12" >
                <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="6m">6개월</button>
                <button type="button" class="btn btn_02" name="dateBtn" data="12m">12개월</button>
            </div>
        </td>
	</tr>
    <tr>
        <th scope="row">지급상태</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
			<?php if(!isset($cc_status)) $cc_status = ""?>
            <input type="radio" name="cc_status" value="" id="cc_status01"        <?php echo get_checked($cc_status, '');          ?>>
            <label for="cc_status01">전체</label>
            <input type="radio" name="cc_status" value="지급예정" id="cc_status03" <?php echo get_checked($cc_status, '지급예정');  ?>>
            <label for="cc_status03">지급예정</label>
            <input type="radio" name="cc_status" value="지급보류" id="cc_status04" <?php echo get_checked($cc_status, '지급보류');  ?>>
            <label for="cc_status04">지급보류</label>
            <input type="radio" name="cc_status" value="지급완료" id="cc_status05"   <?php echo get_checked($cc_status, '지급완료');    ?>>
            <label for="cc_status05">지급완료</label>
		</div>
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
<div class="clearfix"></div>

<div class="tbl_head01 tbl_wrap">
    <div class="pull-left">
    <span class="btn_ov01">[ 검색결과 <?php echo number_format($total_count); ?>건 ]</span>
	</div>
    <div class="pull-right">
		<input type="button" value="엑셀다운로드" class="btn btn_02" >
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
    <thead>
    <tr>
        <th scope="col" rowspan="2" >기간</th>
        <th scope="col" rowspan="2" >사용자<br/>결제금액</th>
        <th scope="col" rowspan="2" >매출<br/>건수</th>
        <th scope="col" rowspan="2">적립금<br/>지급</th>
        
        <th scope="col" colspan="2">할인</th>
        <th scope="col" rowspan="2" >부가세</th>
        
        <th scope="col" rowspan="2" >입점사<br/>수수료</th>
        
        <th scope="col" rowspan="2" >정산금액</th>
        <th scope="col" rowspan="2" >지급<br/>(예정)일</th>
        <th scope="col" rowspan="2" >지급<br/>상태</th>
        <th scope="col" rowspan="2" >비고<br/>(지급보류사유)</th>
    </tr>
    <tr>
        <th scope="col" >쿠폰할인</th>
        <th scope="col" >적립금사용</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
    ?>
    <tr class="<?php echo $bg; ?>">
    	<td><?php echo $row['yymm'] ?></td>
        <td><?php echo number_format($row['od_receipt_price']) ?></td>
        <td><?php echo number_format($row['od_count']) ?></td>
        <td><?php echo number_format($row['od_point']) ?></td>
        
        <td><?php echo number_format($row['od_coupon']) ?></td>
        <td><?php echo number_format($row['od_receipt_point']) ?></td>
        <td><?php echo number_format($row['od_vat_mny']) ?></td>
        
    	<td><?php echo $row['cp_commission_mny'] ?></td>
    	
    	<td><?php echo $row['cp_cal_price'] ?></td>
    	<td><?php echo $row['cp_payment_date'] ?></td>
    	<td><?php echo $row['cc_status'] ?></td>
    	<td><?php echo $row['cc_reason'] ?></td>
    </tr>
    <?php
    }
    
    if ($i == 0)
        echo "<tr><td colspan=\"20\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>


<script>
$(function(){

	//날짜 버튼
	$("button[name='dateBtn']").click(function(){
		
		var d = $(this).attr("data");
		if(d == "all") {
			$('#sc_od_time').val("");
		} else {
    		var startD = moment();
    		var endD = moment();
            
    		if(d == "3m") {
    			startD = moment().subtract(3, 'month');
    			endD = moment();
    		} else if(d == "6m") {
    			startD = moment().subtract(6, 'month');
    			endD = moment();
    		} else if(d == "12m") {
    			startD = moment().subtract(12, 'month');
    			endD = moment();
    		}

    		$("select[name='sc_od_s_y']").val(startD.format("YYYY"));
    		$("select[name='sc_od_s_m']").val(startD.format("M"));

    		$("select[name='sc_od_e_y']").val(endD.format("YYYY"));
    		$("select[name='sc_od_e_m']").val(endD.format("M"));
		}
	
	});
});

function fwrite_submit(f)
{

    return true;
}


</script>

	</div>
	</div>
</div>


<?php
include_once ('../admin.tail.php');
?>