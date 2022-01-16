<?php
$sub_menu = '500100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '회원별분석';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if ($sort1 == "") $sort1 = "od_receipt_price_sum";

//if (!in_array($sort1, array('ct_status_1', 'ct_status_2', 'ct_status_3', 'ct_status_4', 'ct_status_5', 'ct_status_6', 'ct_status_7', 'ct_status_8', 'ct_status_9', 'ct_price_sum'))) $sort1 = "ct_price_sum";
if ($sort2 == "" || $sort2 != "asc") $sort2 = "desc";

$sort1 = strip_tags($sort1);


$sql_common  = " from 	lt_shop_order a, lt_member as b
            where a.mb_id = b.mb_id
            and 	a.od_type in ('O','R','B')
            and   a.od_receipt_price > 0  ";

if (!isset($sc_ct_time)) {
    //$sc_ct_time =
    
    $fr_date = date_create(G5_TIME_YMD);
    date_add($fr_date, date_interval_create_from_date_string('-6 days'));
    $fr_date = date_format($fr_date,"Y-m-d");
    
    $to_date = G5_TIME_YMD;
    
    $sc_ct_time = $fr_date.' ~ '.$to_date;
}

    
if ($sc_ct_time != "") {
    $it_times = explode("~", $sc_ct_time);
    $sql_common .= " and od_time between '".trim($it_times[0])." 00:00:00' and '".trim($it_times[1])." 23:59:59' ";
}

if ($sca)
{
    $sql_common .= " and b.ca_id like '$sca%' ";
}
if ($od_mobile)
{
    $sql_common .= " and c.od_mobile = '$od_mobile' ";
}

if ($stx != "") {
    if ($sfl != "") {
        $sql_common .= " and $sfl like '%$stx%' ";
    }
    if ($save_stx != $stx)
        $page = 1;
}
$sql_common .= "group by b.mb_name,b.mb_id,a.od_settle_case ";

$havingwhere = " having ";

if ($s_it_price)
{
    $sql_common .= " $havingwhere sum(od_receipt_price) >= '$s_it_price' ";
    $havingwhere = " and ";
}
if ($e_it_price)
{
    $sql_common .= " $havingwhere sum(od_receipt_price) <= '$e_it_price' ";
    $havingwhere = " and ";
}
if ($s_od_cnt)
{
    $sql_common .= " $havingwhere count(od_id) >= '$s_od_cnt' ";
    $havingwhere = " and ";
}
if ($e_od_cnt)
{
    $sql_common .= " $havingwhere count(od_id) <= '$e_od_cnt' ";
    $havingwhere = " and ";
}


$sql = " select count(od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$disp_od_id = substr($row['od_id'],14,1).'-'.substr($row['od_id'],0,8).'-'.substr($row['od_id'],8,6);

$sql = " select max(a.od_time) as od_time
                 ,b.mb_name,b.mb_id
                 ,max(concat(a.od_id,a.od_type)) as od_id
                 ,sum(od_receipt_price) as od_receipt_price_sum
                 ,count(od_id) as od_cnt
                 ,od_settle_case 
                ,(select concat(od_type,'-',left(od_id,8),'-',right(od_id,6)) from lt_shop_order c where c.mb_id = a.mb_id order by c.od_time desc limit 1) as disp_od_id
           $sql_common
           order by $sort1 desc
           limit $from_record, $rows ";

$result = sql_query($sql);


//$qstr = 'page='.$page.'&amp;sort1='.$sort1.'&amp;sort2='.$sort2;
$qstr1 = $qstr.'&amp;fr_date='.$fr_date.'&amp;sc_ct_time='.$sc_ct_time.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;save_stx='.$stx;

?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
    <form name="flist" id="flist" class="local_sch01 local_sch">
    <input type="hidden" name="doc" value="<?php echo $doc; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="sort1" id="sort1" value="<?php echo $sort1; ?>">
    <input type="hidden" name="save_stx" value="<?php echo $stx; ?>">

	<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
    </colgroup>
    
    <tr>
        <th scope="row">검색</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                
            	<label for="sfl" class="sound_only">검색대상</label>
            	
                <select name="sfl" id="sfl">
                    <option value="mb_name" <?php echo get_selected($sfl, 'mb_name'); ?>>이름</option>
                    <option value="mb_id" <?php echo get_selected($sfl, 'mb_id'); ?>>아이디</option>
                    <option value="od_id" <?php echo get_selected($sfl, 'od_id'); ?>>주문번호</option>
                </select>
                <label for="stx" class="sound_only">검색어</label>
                <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">검색기간</th>
		<td colspan="2">
            	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                	<input type='text' class="form-control" id="it_time" name="sc_ct_time" value=""/>
                	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
            	</div>
            	<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                	<div class="btn-group" >
                        <button type="button" class="btn btn_02" name="dateBtn" data="all">전체</button>
                        <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                        <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                        <button type="button" class="btn btn_02" name="dateBtn" data="1w">1주</button>
                        <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                        <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                     </div>
                 </div>
        </td>
    </tr>
    <tr>
        <th scope="row">구매금액</th>
		<td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="text" name="s_it_price" value="<?php echo $s_it_price; ?>" id="s_it_price" class="frm_input">
            ~
            <input type="text" name="e_it_price" value="<?php echo $e_it_price; ?>" id="e_it_price" class="frm_input">
                 </div>
        </td>
    </tr>
    <tr>
        <th scope="row">구매건수</th>
		<td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="text" name="s_od_cnt" value="<?php echo $s_od_cnt; ?>" id="s_od_cnt" class="frm_input">
            ~
            <input type="text" name="e_od_cnt" value="<?php echo $e_od_cnt; ?>" id="e_od_cnt" class="frm_input">
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
</div>

<div class="x_panel">
    <div class="x_title">
    	<h4><span class="fa fa-check-square"></span> 회원별 분석내역<small></small></h4>
    	<div class="clearfix"></div>
	</div>

	<div class="tbl_head01 tbl_wrap">
        <div class="pull-left">
        [업데이트 : <?php echo G5_TIME_YMDHIS?> ]
        </div>
        <div class="pull-right">
        
          <input type="button" class="btn btn_02" id="excel_download" value="엑셀다운로드"></input>
          <select name="page_rows" onchange="$('flist').submit();">
            <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
            <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
            <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
          </select>
        </div>
	</div>


    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">최근주문일</th>
            <th scope="col">이름</th>
            <th scope="col">아이디</th>
            <th scope="col">최근주문번호</th>
            <th scope="col">총 주문금액</th>
            <th scope="col">총 주문건수</th>
            <th scope="col">결제수단</th>
        </tr>
        </thead>
        <tbody>
        <?php
        
        $excel_sql = $sql;
        
        $headers = array('NO','최근주문일','이름', '아이디','최근주문번호','총 주문금액', '총 주문건수', '결제수단');
        $bodys = array('NO','od_time','mb_name', 'mb_id','disp_od_id','od_receipt_price_sum', 'od_cnt', 'od_settle_case');
        $enc = new str_encrypt();
        $excel_sql = $enc->encrypt($excel_sql);
        $headers = $enc->encrypt(json_encode_raw($headers));
        $bodys = $enc->encrypt(json_encode_raw($bodys));
    
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $href = G5_SHOP_URL."/item.php?it_id={$row['it_id']}";
    
            $num = $from_record + $i + 1;
    
            $bg = 'bg'.($i%2);
            $disp_od_id = substr($row['od_id'],14,1).'-'.substr($row['od_id'],0,8).'-'.substr($row['od_id'],8,6);
            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="td_num"><?php echo $num; ?></td>
                <td class="td_datetime"><?php echo $row['od_time']; ?></td>
                <td class="td_mbid"><?php echo $row['mb_name']; ?></td>
                <td class="td_mbname"><?php echo $row['mb_id']; ?></td>
                <td class="td_odrnum"><?php echo $row['disp_od_id']; ?></td>
                
                <td class="td_price"><?php echo number_format($row['od_receipt_price_sum']); ?></td>
                <td class="td_odrnum"><?php echo number_format($row['od_cnt']); ?></td>
                <td class="td_payby"><?php echo $row['od_settle_case']; ?></td>
            </tr>
            <?php
        }
    
        if ($i == 0) {
            echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
        }
        ?>
        </tbody>
        </table>
        
    </div>
    
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div>
</div>
</div>


<script>
$(function() {
	
	$("#excel_download,#excel_download1, #excel_download2").click(function(){
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

	$('#it_time').daterangepicker({
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
	$('#it_time').val("<?php echo $sc_ct_time ?>");

	//날짜 버튼
	$("button[name='dateBtn']").click(function(){
		
		var d = $(this).attr("data");
		if(d == "all") {
			$('#it_time').val("");
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
    
    		$('#it_time').data('daterangepicker').setStartDate(startD);
    		$('#it_time').data('daterangepicker').setEndDate(endD);
		}
	
	});
    window.addEventListener("keydown", (e) => {
        if (e.keyCode == 13) {
            document.getElementById('flist').submit();
        }
    })
});
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
