<?php
$sub_menu = '92';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '취소/반품 순위 내역';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if ($sort1 == "") $sort1 = "ct_status_2";

//if (!in_array($sort1, array('ct_status_1', 'ct_status_2', 'ct_status_3', 'ct_status_4', 'ct_status_5', 'ct_status_6', 'ct_status_7', 'ct_status_8', 'ct_status_9', 'ct_price_sum'))) $sort1 = "ct_price_sum";
if ($sort2 == "" || $sort2 != "asc") $sort2 = "desc";

$doc = strip_tags($doc);
$sort1 = strip_tags($sort1);

$sql  = "select a.it_id, b.it_name, b.it_price, b.it_stock_qty
            	,SUM((ct_price + io_price) * ct_qty) as ct_price
            	,SUM(ct_qty) as ct_qty_sum 
            	,SUM((ct_price + io_price) * ct_qty) as ct_price_sum  
            	,SUM(IF(od_status in ('상품준비중','배송완료','구매완료','리스중'),ct_qty, 0)) as ct_status_1
            	,SUM(IF(od_status = '주문취소' or od_status_claim = '주문취소',ct_qty, 0)) as ct_status_2
            	,SUM(IF(od_status_claim in ('반품','철회','해지'),ct_qty, 0)) as ct_status_3
            from 	lt_shop_cart a, lt_shop_item b, lt_shop_order c
            where 	a.it_id = b.it_id 
            and		a.od_id = c.od_id
            and 	a.od_type in ('O','R','B')
            and     a.company_code = (select company_code from lt_member_company where mb_id = '{$member['mb_id']}' )";

if (!isset($sc_ct_time)) {
    $fr_date = date_create(G5_TIME_YMD);
    date_add($fr_date, date_interval_create_from_date_string('-6 days'));
    $fr_date = date_format($fr_date,"Y-m-d");
    
    $to_date = G5_TIME_YMD;
    
    $sc_ct_time = $fr_date.' ~ '.$to_date;
}

if ($sc_ct_time != "") {
    $it_times = explode("~", $sc_ct_time);
    $sql .= " and ct_time between '".trim($it_times[0])." 00:00:00' and '".trim($it_times[1])." 23:59:59' ";
}

if ($sca)
{
    $sql .= " and b.ca_id like '$sca%' ";
}
if ($od_mobile)
{
    $sql .= " and c.od_mobile = '$od_mobile' ";
}
if ($s_it_price)
{
    $sql .= " and b.it_price >= '$s_it_price' ";
}
if ($e_it_price)
{
    $sql .= " and b.it_price <= '$e_it_price' ";
}

if ($stx != "") {
    $sql .= " and (b.it_name like '%$stx%' or b.it_id like '%$stx%' )";
    if ($save_stx != $stx)
        $page = 1;
}


$sql .= " group by a.it_id ";

$result = sql_query($sql."order by $sort1 desc limit 10 ");

$result2 = sql_query($sql."order by $sort1 asc limit 10 ");


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
        <th scope="row">검색분류</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<label for="sca" class="sound_only">상품카테고리 선택</label>
                <select name="sca" id="sca">
                    <option value="">상품 전체분류</option>
                    <?php
                    $sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
                    $result1 = sql_query($sql1);
                    for ($i=0; $row1=sql_fetch_array($result1); $i++) {
                        $len = strlen($row1['ca_id']) / 2 - 1;
                        $nbsp = '';
                        for ($i=0; $i<$len; $i++) $nbsp .= '&nbsp;&nbsp;&nbsp;';
                        echo '<option value="'.$row1['ca_id'].'" '.get_selected($sca, $row1['ca_id']).'>'.$nbsp.$row1['ca_name'].'</option>'.PHP_EOL;
                    }
                    ?>
                </select>
                
            	<label for="sfl" class="sound_only">상품</label>
                <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input" placeholder="상품코드,상품명">
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
        <th scope="row">주문경로</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
			<?php if(!isset($od_mobile)) $od_mobile = ""?>
            <input type="radio" name="od_mobile" value="" id="od_mobile_all" <?php echo get_checked($od_mobile, '');          ?>>
            <label for="od_mobile_all">전체</label>
            <input type="radio" name="od_mobile" value="0" id="od_mobile0" <?php echo get_checked($od_mobile, '0');  ?>>
            <label for="od_mobile0">PC</label>
            <input type="radio" name="od_mobile" value="1" id="od_mobile1" <?php echo get_checked($od_mobile, '1');  ?>>
            <label for="od_mobile1">Mobile</label>
            <input type="radio" name="od_mobile" value="2" id="od_mobile2"   <?php echo get_checked($od_mobile, '2');    ?>>
            <label for="od_mobile2">APP</label>
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
    	<h4>통계그래프</h4>
    	<div class="clearfix"></div>
    </div>
    <div class="x_content">
    	<div class="col-md-6 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_content"  style="text-align:center;">
    	
    			<canvas id="saledateChart1"></canvas>
    		  </div>
    		</div>
    	</div>
    	
    	<div class="col-md-6 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_content"  style="text-align:center">
    			<canvas id="saledateChart2"></canvas>
    		  </div>
    		</div>
    	</div>
    </div>
</div>


<div class="x_panel">
    <div class="x_title">
    	<h4><span class="fa fa-check-square"></span> 취소/반품순위내역<small></small></h4>
    	<div class="clearfix"></div>
	</div>

	<div class="tbl_head01 tbl_wrap">
        <div class="pull-left">
        
        </div>
        <div class="pull-right">
        
            <input type="button" class="btn btn_02" id="excel_download" value="엑셀다운로드"></input>
            <select id="sc_sort1" onchange="sstsod_change(this);">
                <option value="ct_status_2" <?php echo get_selected($sort1, 'ct_status_2') ; ?>>취소수량순</option>
            	<option value="ct_status_3" <?php echo get_selected($sort1, 'ct_status_3') ; ?>>반품수량순</option>
            </select>
        
        <script>
        function sstsod_change(ctl)
        {
        	$("#sort1").val($("#"+ctl.id).val());
         	$('#flist').submit();
         	return true;
        }
        </script>
        </div>
	</div>


    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
        <tr>
            <th scope="col">순위</th>
            <th scope="col">상품코드</th>
            <th scope="col">상품명</th>
            
            <th scope="col">판매가</th>
            <th scope="col">재고</th>
            
            <th scope="col">결제수량</th>
            
            <th scope="col">취소수량</th>
            <th scope="col">반품수량</th>
           
            <th scope="col">판매합계</th>
        </tr>
        </thead>
        <tbody>
        <?php
        
        $excel_sql = $sql."order by $sort1 desc";
        $headers = array('순위','상품코드','상품명', '판매가','재고','결제수량', '취소수량', '반품수량', '판매합계');
        $bodys = array('NO','it_id','it_name', 'it_price','it_stock_qty','ct_status_1', 'ct_status_2', 'ct_status_3', 'ct_price_sum');
        $enc = new str_encrypt();
        $excel_sql = $enc->encrypt($excel_sql);
        $headers = $enc->encrypt(json_encode_raw($headers));
        $bodys = $enc->encrypt(json_encode_raw($bodys));
        
        $it_names = array();
        $tot0 = array();
        $tot1 = array();
        
        $total_qty = 0;
        $total_price = 0;
    
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $href = G5_SHOP_URL."/item.php?it_id={$row['it_id']}";
    
            $num = $rank + $i + 1;
    
            $bg = 'bg'.($i%2);
            $it_names[] = $row['it_name'];
            $tot0[] = $row['ct_status_2'];
            $total_qty += (int)$row['ct_status_2'];
            
            $tot1[] = $row['ct_status_3'];
            $total_price += (int)$row['ct_status_3'];
            
            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="td_num"><?php echo $num; ?></td>
                <td class="td_left"><?php echo $row['it_id']; ?></a></td>
                <td class="td_left"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['it_id'], 50, 50); ?> <?php echo cut_str($row['it_name'],30); ?></a></td>
                
                <td class="td_num"><?php echo number_format($row['it_price']); ?></td>
                <td class="td_num"><?php echo number_format($row['it_stock_qty']); ?></td>
                
                <td class="td_num"><?php echo number_format($row['ct_status_1']); ?></td>
                
                <td class="td_num"><?php echo number_format($row['ct_status_2']); ?></td>
                <td class="td_num"><?php echo number_format($row['ct_status_3']); ?></td>
                
                <td class="td_num"><?php echo number_format($row['ct_price_sum']); ?></td>
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
</div>
</div>
</div>


<script src="../vendors/Chart.js/dist/Chart.min.js"></script>
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

	  var ctx1 = document.getElementById("saledateChart1");
	  var data = {
    		labels: <?php echo json_encode($it_names); ?>,
    		datasets: [{
    		  data: <?php echo json_encode($tot0); ?>,
    		  backgroundColor: [
          		"#7F82B3","#FFCBB5","#CFD2FF","#A4CC91","#95B388",        		
      			"#455C73","#9B59B6","#BDC3C7","#26B99A","#3498DB",
      			'#26B99A','#34495E','#8abb6f','#759c6a'
    		  ]
    		}]
    	  };
    
        var canvasDoughnut = new Chart(ctx1, {
            type: 'doughnut',
            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
            data: data,
            options: {  
                tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                
                                var label = data.labels[tooltipItem.index] || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index])+"개";

                                label += " ("+(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] * 100 / <?php echo $total_qty?>).toFixed(1)+"%)";
                                return label;
                             }
                       }
                  }
            }
        });
    	  

	  var ctx2 = document.getElementById("saledateChart2");

	  var data2 = {
    		labels: <?php echo json_encode($it_names); ?>,
    		datasets: [{
    		  data: <?php echo json_encode($tot1); ?>,
    		  backgroundColor: [
        		"#7F82B3","#FFCBB5","#CFD2FF","#A4CC91","#95B388",        		
    			"#455C73","#9B59B6","#BDC3C7","#26B99A","#3498DB",
    			'#26B99A','#34495E','#8abb6f','#759c6a'
    		  ]
    
    		}]
    	  };
    
        var canvasDoughnut = new Chart(ctx2, {
            type: 'doughnut',
            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
            data: data2,
            options: { 
            	/*legend: false,*/ 
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            
                            var label = data.labels[tooltipItem.index] || '';
                            if (label) {
                                label += ': ';
                            }
                            //label += Math.round(tooltipItem.yLabel * 100) / 100;
                            label += number_format(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index])+"개";

                            label += " ("+(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] * 100 / <?php echo $total_price?>).toFixed(1)+"%)";
                            return label;
                         }
                   }
              }
            }
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


	
});
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
