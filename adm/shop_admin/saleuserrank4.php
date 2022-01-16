<?php
$sub_menu = '500100';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '성별분석';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if ($sort1 == "") $sort1 = "od_addr";

$sort1 = strip_tags($sort1);

$sql_common  = " from 	lt_shop_order a
                where   a.od_status not in ('쇼핑') ";

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


$sql_common .= "group by substring(od_addr1,1,2) ";


$sql = " select max(a.od_time) as od_time
            	,substring(od_addr1,1,2) as od_addr
              ,count(if(a.od_status in ('결제완료','상품준비중','배송완료','구매완료','계약등록','리스중') and a.od_receipt_price > 0 ,1,null)) cnt1
              ,sum(if(a.od_status in ('결제완료','상품준비중','배송완료','구매완료','계약등록','리스중') and a.od_receipt_price > 0 ,od_receipt_price,0)) cnt2     
              ,sum(if(a.od_status in ('결제완료','상품준비중','배송완료','구매완료','계약등록','리스중') and a.od_receipt_price > 0 , (select sum(ct_qty) from lt_shop_cart b where a.od_id = b.od_id), 0)) cnt3  
              ,ifnull(sum(if(a.od_refund_price > 0 ,(select sum(ct_qty) from lt_shop_cart b where a.od_id = b.od_id),null)),0) refund_cnt1
              ,ifnull(sum(if(a.od_refund_price > 0 ,od_refund_price,null)),0) refund_cnt2
           $sql_common
           order by $sort1 asc";

$result = sql_query($sql);

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
    	<h4><span class="fa fa-check-square"></span> 배송지역별 분석내역<small></small></h4>
    	<div class="clearfix"></div>
	</div>

	<div class="tbl_head01 tbl_wrap">
        <div class="pull-left">
        [업데이트 : <?php echo G5_TIME_YMDHIS?> ]
        </div>
        <div class="pull-right">
        
          <input type="button" class="btn btn_02" id="excel_download" value="엑셀다운로드"></input>
        </div>
	</div>


    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
        <tr>
            <th scope="col">지역구분</th>
            <th scope="col">결재완료 주문수</th>
            <th scope="col">결재완료 상품수</th>
            <th scope="col">결제상품 판매금액</th>
            <th scope="col">환불완료 상품수</th>
            <th scope="col">환불상품 판매금액</th>
        </tr>
        </thead>
        <tbody>
        <?php
        
        $excel_sql = $sql;
        
        $headers = array('NO','지역구분','결재완료 주문수','결재완료 상품수','결제상품 판매금액', '환불완료 상품수', '환불상품 판매금액');
        $bodys = array('NO','od_addr', 'cnt1','cnt3','cnt2', 'refund_cnt1', 'refund_cnt2');
        $enc = new str_encrypt();
        $excel_sql = $enc->encrypt($excel_sql);
        $headers = $enc->encrypt(json_encode_raw($headers));
        $bodys = $enc->encrypt(json_encode_raw($bodys));
        
        $sum_mb_cnt = 0;
        $sum_cnt1 = 0;
        $sum_cnt2 = 0;
        $sum_cnt3 = 0;
        $sum_refund_cnt1 = 0;
        $sum_refund_cnt2 = 0;
        
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $num = $from_record + $i + 1;
            $bg = 'bg'.($i%2);
            $sum_cnt1 += $row['cnt1'];
            $sum_cnt2 += $row['cnt2'];
            $sum_cnt3 += $row['cnt3'];
            $sum_refund_cnt1 += $row['refund_cnt1'];
            $sum_refund_cnt2 += $row['refund_cnt2'];
            
            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="td_mbid"><?php echo $row['od_addr']; ?></td>
                <td class="td_odrnum"><?php echo $row['cnt1']; ?></td>
                <td class="td_odrnum"><?php echo number_format($row['cnt3']); ?></td>
                <td class="td_price"><?php echo number_format($row['cnt2']); ?></td>
                
                <td class="td_odrnum"><?php echo number_format($row['refund_cnt1']); ?></td>
                <td class="td_price"><?php echo number_format($row['refund_cnt2']); ?></td>
            </tr>
            <?php
        }
    
        if ($i == 0) {
            echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
        } else {
        ?>
            <tr class="<?php echo $bg; ?>">
                <td class="td_mbid"><strong>전체</strong></td>
                <td class="td_odrnum b"><strong><?php echo $sum_cnt1?></strong></td>
                <td class="td_odrnum b"><strong><?php echo number_format($sum_cnt3) ?></strong></td>
                <td class="td_price b"><strong><?php echo number_format($sum_cnt2) ?></strong></td>
                
                <td class="td_odrnum b"><strong><?php echo number_format($sum_refund_cnt1); ?></strong></td>
                <td class="td_price b"><strong><?php echo number_format($sum_refund_cnt2); ?></strong></td>
            </tr>
        <?php } ?>
        </tbody>
        </table>
        
    </div>
    
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
