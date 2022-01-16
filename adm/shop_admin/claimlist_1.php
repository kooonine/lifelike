<?php
$where = array();

$doc = strip_tags($doc);
$sort1 = in_array($sort1, array('od_id', 'od_cart_price', 'od_receipt_price', 'od_cancel_price', 'od_misu', 'od_cash')) ? $sort1 : '';
$sort2 = in_array($sort2, array('desc', 'asc')) ? $sort2 : 'desc';
$sel_field = get_search_string($sel_field);
if( !in_array($sel_field, array('od_id', 'mb_id', 'od_name', 'od_tel', 'od_hp', 'od_b_name', 'od_b_tel', 'od_b_hp', 'od_deposit_name', 'od_invoice')) ){   //검색할 필드 대상이 아니면 값을 제거
    $sel_field = '';
}
$od_type = get_search_string($od_type);
$od_status = get_search_string($od_status);
$rt_payment_status = get_search_string($rt_payment_status);
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

$where[] = " a.od_status_claim in ( '취소','주문취소','계약취소' ) ";
if($is_admin == "brand") $where[] = " company_code = (select company_code from lt_member_company where mb_id = '{$member['mb_id']}' )";

if ($od_type) {
    $where[] = " a.od_type = '$od_type' ";
}
if ($od_status) {
    $where[] = " a.od_status = '$od_status' ";
}

if ($rt_payment_status) {
    $where[] = " a.rt_payment_status = '$rt_payment_status' ";
}

if ($sc_it_name) {
    $where[] = " a.od_id in (select od_id from {$g5['g5_shop_cart_table']} where it_name like '%$sc_it_name%') ";
}

if($sel_date == "") $sel_date = "od_time";
if ($fr_date && $to_date) {
    $where[] = " $sel_date between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if ($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

if ($sel_field == "")  $sel_field = "od_id";
if ($sort1 == "") $sort1 = "od_status_claim_date";
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
            ,if(a.od_type = 'O','제품','리스') as odtypename
           $sql_common
           order by $sort1 $sort2
           limit $from_record, $rows ";
$result = sql_query($sql);


$excel_sql = "select a.*, b.it_id, b.it_name, b.ct_option, b.ct_qty, (b.ct_price + b.io_price)  as opt_price, b.ct_status, b.ct_status_claim
                     ,concat(a.od_type,'-',left(a.od_id,8),'-',right(a.od_id,6)) as disp_od_id
                     ,concat(od_delivery_company,' ',od_invoice) as str_od_invoice
                     ,if(a.mb_id = '','비회원','회원') as ismember
                     ,if(a.od_type = 'O','제품','리스') as odtypename
                from lt_shop_order as a
                     left join lt_shop_cart as b
                        on a.od_id = b.od_id and b.io_type = '0' and b.ct_status_claim in ( '취소','주문취소','계약취소' )
                $sql_search
                order by a.$sort1 $sort2, b.io_type asc, b.ct_id asc";

if(substr_count($sql, "limit")){
    $sqls = explode('limit', $excel_sql);
    $excel_sql = $sqls[0];
}
$headers = array('NO', '회원구분','주문일시','주문번호', '주문구분','주문자아이디','주문자명', '상품코드', '상품명', '옵션항목', '수량','취소요청일');
$bodys = array('NO', 'ismember', 'od_time','disp_od_id', 'odtypename','mb_id','od_name', 'it_id', 'it_name', 'ct_option', 'ct_qty','od_status_claim_date');

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
	
<form name="frmorderlist" class="local_sch01 local_sch" onsubmit="$('#page').val('1');" method="get" id="frmorderlist">
<input type="hidden" name="doc" value="<?php echo $doc; ?>">
<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="save_search" value="<?php echo $search; ?>">
<input type="hidden" name="claimtype" value="<?php echo $claimtype; ?>">
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
			<div class="col-lg-1 col-md-3 col-sm-12 col-xs-12">
    		<select name="sel_date" id="sel_date" >
                <option value="od_time" <?php echo get_selected($sel_date, 'od_time'); ?>>&nbsp;&nbsp;주문일&nbsp;&nbsp;</option>
                <option value="od_receipt_time" <?php echo get_selected($sel_date, 'od_receipt_time'); ?>>&nbsp;&nbsp;결제일&nbsp;&nbsp;</option>
            </select>
            </div>
        	<div class="col-lg-5 col-md-3 col-sm-12 col-xs-12">
            	<input type='text' class="form-control" id="sc_od_time" name="sc_od_time" value=""/>
            	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
        	</div>
        	<div class="btn-group col-lg-6 col-md-6 col-sm-12 col-xs-12" >
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
        <th scope="row">주문 구분</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
            <input type="radio" name="od_type" value="" id="od_type"    <?php echo get_checked($od_type, '');     ?>>
            <label for="od_type">전체</label>
            <input type="radio" name="od_type" value="O" id="od_typeO" <?php echo get_checked($od_type, 'O'); ?>>
            <label for="od_typeO">제품</label>
            <input type="radio" name="od_type" value="R" id="od_typeR" <?php echo get_checked($od_type, 'R'); ?>>
            <label for="od_typeR">리스</label>
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
	<input type="button" value="인쇄" class="btn btn_02" id="pdf_download1">
	</div>
</div>

<div class="tbl_head01 tbl_wrap">
    <table id="sodr_list">
    <caption>상품 목록</caption>
    <thead>
    <tr>
    	<th scope="col" >No</th>
    	<th scope="col">회원구분</th>
    	<th scope="col">주문구분</th>
    	
    	<th scope="col" style="width: 70px;" >주문일시</th>
    	<th scope="col" style="width: 80px;"><a href="<?php echo title_sort("od_id", 1)."&amp;$qstr1"; ?>">주문번호</a></th>
    	<th scope="col">주문자명(ID)</th>
    	
    	<th scope="col">상품주문번호</th>
    	<th scope="col">상품코드</th>
    	<th scope="col">상품명</th>
    	<th scope="col">옵션항목</th>
    	
    	<th scope="col">수량</th>
    	
    	<th scope="col">취소요청일</th>
    	    	
	</tr>
	</thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $mb_nick = get_sideview($row['mb_id'], get_text($row['od_name']), $row['od_email'], '');
        
        // 주문번호에 - 추가
        $disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'<br/>-'.substr($row['od_id'],8,6);

        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

        $invoice_time = is_null_time($row['od_invoice_time']) ? G5_TIME_YMDHIS : $row['od_invoice_time'];
        $delivery_company = $row['od_delivery_company'] ? $row['od_delivery_company'] : $default['de_delivery_company'];

        $bg = 'bg'.($i%2);
        $td_color = 0;
        
        $odsql = " select it_id, it_name, its_sap_code, its_order_no,
                        ct_id, it_id, ct_price, ct_qty, ct_option, ct_status, cp_price, ct_send_cost, io_type, io_price
                        ,(select group_concat(od_sub_id) from lt_shop_order_item where lt_shop_order_item.ct_id = lt_shop_cart.ct_id ) as od_sub_id
            from {$g5['g5_shop_cart_table']} 
            where od_id = '".$row['od_id']."'
            and ct_status_claim in ( '취소','주문취소','계약취소' )
            order by io_type asc, ct_id asc
            ";
        
        $od = sql_query($odsql);
        $rowspan = sql_num_rows($od);
        
        for($k=0; $opt=sql_fetch_array($od); $k++) {
            
            // 상품이미지
            $image = get_it_image($opt['it_id'], 50, 50);
            
            
            if($opt['io_type'])
                $opt_price = $opt['io_price'];
            else
                $opt_price = $opt['ct_price'] + $opt['io_price'];
            
    ?>
    <tr>
		<?php if($k == 0) { ?>    	
        <td class="td_chk" rowspan="<?php echo $rowspan ?>">
            <?php echo $i+1 ?>
        </td>
        <td rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">회원구분</label>
        	<?php if ($row['mb_id']) { ?>
            회원
            <?php } else { ?>
            비회원
            <?php } ?>
        </td>
        <td headers="odrstat" class="odrstat" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문구분</label>
            <?php echo $row['odtypename']; ?>
        </td>
        <td rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문일시</label>
        	<?php echo substr($row['od_time'],2,8) ?><br/>
        	<?php echo substr($row['od_time'],11,8) ?>
        </td>
        <td headers="th_ordnum" class="td_odrnum2" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문번호</label>
        	<a href="./orderform<?php echo ($is_admin == "brand")?".brand":"" ?>.php?od_id=<?php echo $row['od_id']; ?>&amp;<?php echo $qstr; ?>"><?php echo $disp_od_id; ?></a>
        	
        	<label class="sound_only">주문통합상태</label>
            <input type="hidden" name="current_status[<?php echo $i ?>]" value="<?php echo $row['od_status'] ?>">
        </td>
        <td headers="th_odrer" class="td_name" rowspan="<?php echo $rowspan ?>">
        	<label class="sound_only">주문자명</label>
        	<?php echo $mb_nick; ?>(<?php echo $row['mb_id']; ?>)        	
        	<br/><?php echo get_text($row['od_tel']); ?>
        </td>
    	
        <?php } ?>
        
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
        	<a href="./itemform<?php echo ($is_admin == "brand")?".brand":"" ?>.php?w=u&amp;it_id=<?php echo $opt['it_id']; ?>"><?php echo $image; ?> <?php echo stripslashes($opt['it_name']); ?></a>
        </td>
        <td class="td_itopt_tl">
            <?php echo $opt['ct_option']; ?>
        </td>
        
        <td headers="odrstat" class="odrstat">
        	<label class="sound_only">수량</label>
        	<?php echo $opt['ct_qty']; ?>
        </td>
        
        <?php if($k == 0) { ?>   
        <td rowspan="<?php echo $rowspan ?>">
        	<?php echo substr($row['od_status_claim_date'],2,8) ?><br/>
        	<?php echo substr($row['od_status_claim_date'],11,8) ?>
        </td>
        <?php } ?>
        
    </tr>    
    <?php
        }
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
	<input type="button" value="인쇄" class="btn btn_02" id="pdf_download2">
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
	$("#pdf_download1, #pdf_download2").click(function(){
		var $form = $('<form></form>');     
		$form.attr('action', '<?=G5_ADMIN_URL?>/ajax.pdf_download.php');
	    $form.attr('method', 'post');
	    $form.attr('target', '_blank');
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

});

function forderlist_submit(change_status)
{
    if (!is_checked("chk[]")) {
        alert(change_status+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }
	var $f = $("#forderlist");
	var $chk = $("input[name='chk[]']:checked");

	$("#post_od_status").val("");

	switch (change_status)
    {
         case "계약취소" :
             if($chk.size() != 1){
                 alert("CS처리는 다중 선택할 수 없습니다.");
                 return false;
             }
             var k = $($chk[0]).val();
             var current_status = $("input[name='current_status["+k+"]']").val();

             if (current_status != "계약등록") {
                 alert("'계약등록' 상태의 주문만 '계약취소'가 가능합니다.");
                 return false;
             }

             var url = "./orderpartcancel.php?od_id="+$("input[name='od_id["+k+"]']").val();
             window.open(url, "orderpartcancel", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");
             
             break;
         case "교환요청" :
             if($chk.size() != 1){
                 alert("CS처리는 다중 선택할 수 없습니다.");
                 return false;
             }
             var k = $($chk[0]).val();
             var current_status = $("input[name='current_status["+k+"]']").val();

             if (current_status != "계약등록" && current_status != "배송완료") {
                 alert("'계약등록, 배송완료' 상태의 주문만 '교환요청'이 가능합니다.");
                 return false;
             }

             var url = "./orderpartchange.php?od_id="+$("input[name='od_id["+k+"]']").val();
             window.open(url, "orderpartchange", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");
             
             break;
         case "철회요청" :
             if($chk.size() != 1){
                 alert("CS처리는 다중 선택할 수 없습니다.");
                 return false;
             }
             var k = $($chk[0]).val();
             var current_status = $("input[name='current_status["+k+"]']").val();

             if (current_status != "계약등록" && current_status != "배송완료") {
                 alert("'계약등록, 배송완료' 상태의 주문만 '철회요청'이 가능합니다.");
                 return false;
             }

             var url = "./orderpartreturn.php?od_id="+$("input[name='od_id["+k+"]']").val();
             window.open(url, "orderpartreturn", "left=230,top=100,width=1000,height=650,scrollbars=yes,resizable=yes");
             
             break;
         case "계약확인" :

        	for (var i=0; i<$chk.size(); i++)
			{
        		 var k = $($chk[i]).val();
                 var current_status = $("input[name='current_status["+k+"]']").val();

                 if (current_status != "계약등록") {
                     alert("'계약등록' 상태의 주문만 '계약확인'이 가능합니다.");
                     return false;
                 }
			}

        	$("#post_od_status").val("상품준비중");
        	$("#forderlist").attr("action","./orderlistupdate.php").submit();
        	//f.action = "./orderlistupdate.php";
        	
            return false;
            break;
    }
}

</script>
