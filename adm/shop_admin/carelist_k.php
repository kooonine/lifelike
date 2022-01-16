<?php
$where = array();

$doc = strip_tags($doc);
$sort1 = in_array($sort1, array('od_id', 'od_cart_price', 'od_receipt_price', 'od_cancel_price', 'od_misu', 'od_cash')) ? $sort1 : '';
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_field = get_search_string($sel_field);
if( !in_array($sel_field, array('od_id', 'mb_id', 'od_name', 'od_tel', 'od_hp', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_deposit_name', 'od_invoice')) ){   //검색할 필드 대상이 아니면 값을 제거
    $sel_field = '';
}
$od_status = get_search_string($od_status);
$search = get_search_string($search);

$sql_search = "";
if ($search != "") {
    if ($sel_field != "") {
        $where[] = " a.$sel_field like '%$search%' ";
    }

    if ($save_search != $search) {
        $page = 1;
    }
}

if ($sc_od_time != "") {
    $sc_od_times = explode("~", $sc_od_time);
    $fr_date = trim($sc_od_times[0]);
    $to_date = trim($sc_od_times[1]);
}

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

$where[] = " a.od_type = '$od_type' ";
$where[] = " a.od_status in ('보관중','보관완료') ";

if ($od_status) {
    $where[] = " a.od_status = '$od_status' ";
}
if ($fr_date && $to_date) {
    $where[] = " a.od_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}


$keep_compl_day=date_create(G5_TIME_YMD);
date_add($keep_compl_day, date_interval_create_from_date_string('7 days'));
$keep_compl_day = date_format($keep_compl_day,"Y-m-d");

if($keep_compl){
    $where[] = " a.od_id in (select od_id from lt_shop_cart where od_type = '$od_type' and ct_keep_end <= '{$keep_compl}') ";
}

if ($ct_keep_month != "") {
    $ct_keep_months = explode("~", $ct_keep_month);
    $fr_keep_months = trim($ct_keep_months[0]);
    $to_keep_months = trim($ct_keep_months[1]);
    if($to_keep_months == '') $to_keep_months = '999';
    
    $where[] = " a.od_id in (select od_id from lt_shop_cart where od_type = '$od_type' and ct_keep_month between '{$fr_keep_months}' and '{$to_keep_months}') ";
}


if ($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_id";
if ($sort2 == "") $sort2 = "desc";

$sql_common = " from {$g5['g5_shop_order_table']} as a $sql_search ";

$sql = " select count(od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *,
            (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
           $sql_common
           order by $sort1 $sort2
           limit $from_record, $rows ";
$result = sql_query($sql);


$excel_sql = "select a.*, b.it_id, b.it_name, b.ct_option, b.ct_qty, (b.ct_price + b.io_price)  as opt_price, b.ct_status, b.ct_status_claim, b.rf_serial, b.its_order_no
                     ,concat(a.od_type,'-',left(a.od_id,8),'-',right(a.od_id,6)) as disp_od_id
                    ,if(b.ct_free_laundry_use = '1','무료','유료') ct_free_laundry
                    ,b.ct_keep_month,b.ct_keep_start,b.ct_keep_end
                from lt_shop_order as a
                     left join lt_shop_cart as b
                        on a.od_id = b.od_id and b.io_type = '0'
                $sql_search
                order by a.$sort1 $sort2, b.io_type asc, b.ct_id asc";
                
if(substr_count($sql, "limit")){
    $sqls = explode('limit', $excel_sql);
    $excel_sql = $sqls[0];
}

$headers = array('NO', '주문일시','주문번호', '주문상태','세탁구분','주문자아이디','주문자명', '상품코드', '상품명', '옵션항목', 'ORDERNO', 'RFID','보관기간','보관시작일','보관만료일');
$bodys = array('NO', 'od_time','disp_od_id', 'od_status','ct_free_laundry','mb_id','od_name', 'it_id', 'it_name', 'ct_option', 'its_order_no', 'rf_serial','ct_keep_month','ct_keep_start','ct_keep_end');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));

$qstr1 = "od_status=".urlencode($od_status)."&amp;od_settle_case=".urlencode($od_settle_case)."&amp;od_misu=$od_misu&amp;od_cancel_price=$od_cancel_price&amp;od_refund_price=$od_refund_price&amp;od_receipt_point=$od_receipt_point&amp;od_coupon=$od_coupon&amp;fr_date=$fr_date&amp;to_date=$to_date&amp;sel_field=$sel_field&amp;search=$search&amp;save_search=$search";
if($default['de_escrow_use'])
    $qstr1 .= "&amp;od_escrow=$od_escrow";
$qstr = "$qstr1&amp;sort1=$sort1&amp;sort2=$sort2&amp;page=$page";


$token = get_admin_token();
?>
	
<form name="frmorderlist" class="local_sch01 local_sch" id ="frmorderlist">
<input type="hidden" name="doc" value="<?php echo $doc; ?>">
<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="save_search" value="<?php echo $search; ?>">
<input type="hidden" name="od_type" value="<?php echo $od_type; ?>">
	<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
    </colgroup>
    
    <tr>
        <th scope="row">기간일자</th>
		<td colspan="2">
	
        	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            	<input type='text' class="form-control" id="sc_od_time" name="sc_od_time" value=""/>
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
        <th scope="row">상품명</th>
		<td colspan="2">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="text" name="sc_it_name" value="<?php echo $sc_it_name; ?>" id="sc_it_name" class="frm_input" size="60" autocomplete="off">
       		</div>
        </td>
    </tr>
    <tr>
        <th scope="row">검색항목</th>
		<td colspan="2">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<select name="sel_field" id="sel_field">
                <option value="od_id" <?php echo get_selected($sel_field, 'od_id'); ?>>주문번호</option>
                <option value="mb_id" <?php echo get_selected($sel_field, 'mb_id'); ?>>회원 ID</option>
                <option value="od_name" <?php echo get_selected($sel_field, 'od_name'); ?>>주문자</option>
                <option value="od_tel" <?php echo get_selected($sel_field, 'od_tel'); ?>>주문자전화</option>
                <option value="od_hp" <?php echo get_selected($sel_field, 'od_hp'); ?>>주문자핸드폰</option>
                <option value="od_b_name" <?php echo get_selected($sel_field, 'od_b_name'); ?>>받는분</option>
                <option value="od_b_tel" <?php echo get_selected($sel_field, 'od_b_tel'); ?>>받는분전화</option>
                <option value="od_b_hp" <?php echo get_selected($sel_field, 'od_b_hp'); ?>>받는분핸드폰</option>
                <option value="od_deposit_name" <?php echo get_selected($sel_field, 'od_deposit_name'); ?>>입금자</option>
                <option value="od_invoice" <?php echo get_selected($sel_field, 'od_invoice'); ?>>운송장번호</option>
            </select>
            
            <label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="search" value="<?php echo $search; ?>" id="search" class="frm_input" autocomplete="off">
       		</div>
        </td>
    </tr>
	
    <tr>
        <th scope="row">보관기간</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
            <input type="radio" name="ct_keep_month" value="" id="ct_keep_month1"  <?php echo get_checked($ct_keep_month, ''); ?>>
            <label for="ct_keep_month1">전체</label>
            <input type="radio" name="ct_keep_month" value="1~5" id="ct_keep_month2"  <?php echo get_checked($ct_keep_month, '1~5'); ?>>
            <label for="ct_keep_month2">6개월 미만</label>
            <input type="radio" name="ct_keep_month" value="6~11" id="ct_keep_month3"  <?php echo get_checked($ct_keep_month, '6~11'); ?>>
            <label for="ct_keep_month3">6개월 이상 ~ 12개월 미만</label>
            <input type="radio" name="ct_keep_month" value="12~" id="ct_keep_month4"  <?php echo get_checked($ct_keep_month, '12~'); ?>>
            <label for="ct_keep_month4">12개월 이상</label>
		</div>
		</td>
	</tr>
	
    <tr>
        <th scope="row">보관상태</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
            <input type="radio" name="od_status" value="" id="od_status"    <?php echo get_checked($od_status, ''); ?>>
            <label for="od_status">전체</label>
            <input type="radio" name="od_status" value="보관중" id="od_status1"    <?php echo get_checked($od_status, '보관중'); ?>>
            <label for="od_status1">보관중</label>
            <input type="radio" name="od_status" value="보관완료" id="od_status2"    <?php echo get_checked($od_status, '보관완료'); ?>>
            <label for="od_status2">보관완료</label>
		</div>
		</td>
	</tr>
	
    <tr>
        <th scope="row">만료임박</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
            <input type="checkbox" name="keep_compl" value="만료임박" id="keep_compl"    <?php echo get_checked($keep_compl, '1'); ?>>
            <label for="keep_compl">만료임박상품</label>
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
</form>


<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    <span class="btn_ov01">[ 검색결과 <?php echo number_format($total_count); ?>건 ]</span>
</div>

<form name="forderlist" id="forderlist" method="post" autocomplete="off">
<input type="hidden" name="search_od_status" value="<?php echo $od_status; ?>">
<input type="hidden" name="od_status" id="post_od_status">
<input type="hidden" name="token" value="<?php echo $token?>">

<div class="local_cmd01 local_cmd">
	<div style="float: right">
	<input type="button" value="엑셀다운로드" class="btn btn_02" id="excel_download1">
	</div>
</div>

<div class="tbl_head01 tbl_wrap">
    <table id="sodr_list">
    <caption>세탁 내역 목록</caption>
    <thead>
    <tr>
    	<th scope="col" rowspan="2">No</th>
    	<th scope="col" rowspan="2">보관만료<br/>임박주문</th>
    	<th scope="col" rowspan="2">주문일시</th>
    	<th scope="col" rowspan="2">주문자명(ID)</th>
    	<th scope="col" rowspan="2"><a href="<?php echo title_sort("od_id", 1)."&amp;$qstr1"; ?>">주문번호</a></th>
    	<th scope="col" rowspan="2">상품주문번호</th>
    	<th scope="col" rowspan="2">상품코드</th>
    	<th scope="col" rowspan="2">상품명</th>
    	
    	<th scope="col" colspan="4">보관정보</th>
    	    	
	</tr>
	<tr>
    	<th scope="col" >보관기간</th>
    	<th scope="col" >보관시작일</th>
    	<th scope="col" >보관만료일</th>
	</tr>
	</thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $mb_nick = get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');
        $disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'<br/>-'.substr($row['od_id'],8,6);
        
        $odsql = " select *
                   from {$g5['g5_shop_cart_table']}
                   where od_id = '{$row['od_id']}'
                   ";
        $opt = sql_fetch($odsql);
            
    ?>
    <tr>
        <td class="td_chk" rowspan="<?php echo $rowspan ?>">
            <?php echo $i+1 ?>
        </td>
        <td rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">보관만료임박주문</label>
        	<?php
        	if($keep_compl_day >= $opt['ct_keep_end']) echo "만료임박";
        	?>
        </td>
        <td rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문일시</label>
        	<?php echo substr($row['od_time'],2,8) ?><br/>
        	<?php echo substr($row['od_time'],11,8) ?>
        </td>
        <td headers="th_odrer" class="td_name" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문자명</label>
        	<?php echo $mb_nick; ?>(<?php echo $row['mb_id']; ?>)        	
        	<br/><?php echo get_text($row['od_tel']); ?>
        </td>
        <td headers="th_ordnum" class="td_odrnum2" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문번호</label>
        	<a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>"><?php echo $disp_od_id; ?></a>
        	
        	<label class="sound_only">주문통합상태</label>
            <input type="hidden" name="current_status[<?php echo $i ?>]" value="<?php echo $row['od_status'] ?>">
        </td>
        
        <td headers="td_odrnum2" class="td_odrnum2">
        	<label class="sound_only">상품주문번호</label>
        	<?php echo $disp_od_id."-".$opt['od_sub_id'] ?>
        </td>
        <td headers="td_odrnum2" class="td_odrnum2">
        	<label class="sound_only">상품코드</label>
        	<?php echo $opt['it_id'] ?>
        	<?php echo ($opt['its_order_no'])?"<br/>(".$opt['its_order_no'].")":"" ?>
        	<?php echo ($opt['rf_serial'])?"<br/>(".$opt['rf_serial'].")":"" ?>
        
        </td>
        <td class="td_itopt_tl">
        	<label class="sound_only">상품명</label>
        	<a href="./itemform.php?w=u&amp;it_id=<?php echo $opt['it_id']; ?>"> <?php echo stripslashes($opt['it_name']); ?></a><br/>
        	<?php echo $opt['ct_option'] ?>
        </td>
        
        <td rowspan="<?php echo $rowspan ?>">
            <?php echo $opt['ct_keep_month'] ?>개월
        </td>
        <td rowspan="<?php echo $rowspan ?>">
            <?php echo $opt['ct_keep_start'] ?>
        </td>
        <td rowspan="<?php echo $rowspan ?>">
            <?php echo $opt['ct_keep_end'] ?>
        </td>
    </tr>    
    <?php
    }
    sql_free_result($result);
    if ($i == 0)
        echo '<tr><td colspan="19" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>
<div class="local_cmd01 local_cmd">
	<div style="float: right">
	<input type="button" value="엑셀다운로드" class="btn btn_02" id="excel_download2">
	</div>
</div>


</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
$(function(){
    //$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
    
	$("#excel_download1, #excel_download2").click(function(){
		var $form = $('<form></form>');     
		$form.attr('action', '<?=G5_ADMIN_URL?>/ajax.excel_download.php');
	    $form.attr('method', 'post');
	    $form.appendTo('body');
	     
	    var exceldata = $('<input type="hidden" value="<?=$excel_sql?>" name="exceldata">');
	    var headerdata = $('<input type="hidden" value="<?=$headers?>" name="headerdata">');
	    var bodydata = $('<input type="hidden" value="<?=$bodys?>" name="bodydata">');
	    $form.append(exceldata).append(headerdata).append(bodydata);
	    $form.submit();
	});
    
	$('#sc_od_time').daterangepicker({
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
    	/*,ranges: {
	           '오늘': [moment(), moment()],
	           '3일': [moment().subtract(2, 'days'), moment()],
	           '1주': [moment().subtract(6, 'days'), moment()],
	           '1개월': [moment().subtract(1, 'month'), moment()],
	           '3개월': [moment().subtract(3, 'month'), moment()],
	           '이번달': [moment().startOf('month'), moment().endOf('month')],
	           '마지막달': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	        }*/
	});
	//alert($("button[name='dateBtn'].btn_03").attr("data"));
	<?php 
	   if($fr_date !='') echo "$('#sc_od_time').val('".$fr_date." ~ ".$to_date."');";
	   else if($sc_od_time !='') echo "$('#sc_od_time').val('".$sc_od_time."');";
	   else echo "$('#sc_od_time').val('');";
	?>
	
	//날짜 버튼
	$("button[name='dateBtn']").click(function(){
		
		var d = $(this).attr("data");
		if(d == "all") {
			$('#sc_od_time').val("");
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
    
    		$('#sc_od_time').data('daterangepicker').setStartDate(startD);
    		$('#sc_od_time').data('daterangepicker').setEndDate(endD);
		}
	
	});
	
    // 주문상품보기
    $(".orderitem").on("click", function() {
        var $this = $(this);
        var od_id = $this.text().replace(/[^0-9]/g, "");

        if($this.next("#orderitemlist").size())
            return false;

        $("#orderitemlist").remove();

        $.post(
            "./ajax.orderitem.php",
            { od_id: od_id },
            function(data) {
                $this.after("<div id=\"orderitemlist\"><div class=\"itemlist\"></div></div>");
                $("#orderitemlist .itemlist")
                    .html(data)
                    .append("<div id=\"orderitemlist_close\"><button type=\"button\" id=\"orderitemlist-x\" class=\"btn_frmline\">닫기</button></div>");
            }
        );

        return false;
    });

    // 상품리스트 닫기
    $(".orderitemlist-x").on("click", function() {
        $("#orderitemlist").remove();
    });

    $("body").on("click", function() {
        $("#orderitemlist").remove();
    });
    window.addEventListener("keydown", (e) => {
    	if (e.keyCode == 13) {
    	    document.getElementById('frmorderlist').submit();
    	}
   	})

});


</script>
