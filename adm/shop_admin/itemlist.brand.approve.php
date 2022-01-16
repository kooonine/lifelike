<?php
$sub_menu = "92";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

if ($is_admin == 'brand')
    alert('관리자만 접근 가능합니다.');

$g5['title'] = '상품승인';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$where = " and ";
$sql_search = "";
if ($stx != "") {
    if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx != $stx)
        $page = 1;
}

if ($sel_ca_name1 != "") {
    $sql_search .= " and i.ca_name1 = '{$sel_ca_name1}' ";
}
if ($sel_ca_name2 != "") {
    $sql_search .= " and i.ca_name2 = '{$sel_ca_name2}' ";
}
if ($sel_ca_name3 != "") {
    $sql_search .= " and i.ca_name3 = '{$sel_ca_name3}' ";
}
if ($sel_ca_name4 != "") {
    $sql_search .= " and i.ca_name4 = '{$sel_ca_name4}' ";
}

if ($sc_it_use != "") {
    $sql_search .= " and it_use = '{$sc_it_use}' ";
}
if ($sc_it_status != "") {
    $sql_search .= " and it_status = '{$sc_it_status}' ";
}


if ($sc_it_time != "") {
    $it_times = explode("~", $sc_it_time);
    $sql_search .= " and it_time between '".trim($sh_datetimes[0])." 00:00:00' and '".trim($sh_datetimes[1])." 23:59:59' ";
}


if ($sfl == "")  $sfl = "it_name";

$sql_common = " from {$g5['g5_shop_item_table']} a 
                      left outer join lt_shop_info as i
                        on a.it_info_gubun = i.if_id
                      inner join lt_member_company as c
                        on a.ca_id3 = c.company_code
               where a.ca_id3 != ''
                    ";

$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];


if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sst) {
    $sst  = "it_time";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";


$sql  = " select i.ca_name1, i.ca_name2, i.ca_name3, i.ca_name4, a.*, c.company_name, c.company_code
           $sql_common
           $sql_order
           limit $from_record, $rows ";
$result = sql_query($sql);

//$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page;
$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;save_stx='.$stx;

$token = get_admin_token();
?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
  	
<form name="flist" id="flistSearch" class="local_sch01 local_sch">
<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
	<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
    </colgroup>
    
    <tr>
        <th scope="row" style="width:15%;">검색분류</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<label for="sfl" class="sound_only">검색대상</label>
                <select name="sfl" id="sfl">
                    <option value="it_name" <?php echo get_selected($sfl, 'it_name'); ?>>상품명</option>
                    <option value="it_id" <?php echo get_selected($sfl, 'it_id'); ?>>상품코드</option>
                </select>
                <label for="stx" class="sound_only">검색어</label>
                <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">상품카테고리</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    			<label for="sca" class="sound_only">상품카테고리 선택</label>
                <select name="sel_ca_name1" id="sel_ca_name1" class="" target="ca_name1" next="sel_ca_name2">
                	<option value="">----선택-----</option>
                	<?php 
        			$ca_sql = "select ca_name1 from lt_shop_info group by ca_name1";
        			$ca_result = sql_query($ca_sql);
        			for ($i=0; $row=sql_fetch_array($ca_result); $i++)
        			{
        			    echo '<option value="'.$row['ca_name1'].'" '.get_selected($row['ca_name1'], $sel_ca_name1).'>'.$row['ca_name1'].'</option>';
        			}
                	?>
                </select>
                <select name="sel_ca_name2" id="sel_ca_name2" class="" target="ca_name2" next="sel_ca_name3">
                	<option value="">----선택-----</option>
                </select>
                <select name="sel_ca_name3" id="sel_ca_name3" class="" target="ca_name3" next="sel_ca_name4">
                	<option value="">----선택-----</option>
                </select>
                <select name="sel_ca_name4" id="sel_ca_name4" class="" target="ca_name4">
                	<option value="">----선택-----</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">상품등록일</th>
		<td colspan="2">
            	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                	<input type='text' class="form-control" id="it_time" name="sc_it_time" value=""/>
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
        <th scope="row">진열상태</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="radio">
                    <label><input type="radio" value="" id="it_use" name="sc_it_use" <?php echo ($sc_it_use == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="1" id="it_use1" name="sc_it_use" <?php echo ($sc_it_use == '1')?'checked':''; ?>> 진열함</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="0" id="it_use0" name="sc_it_use" <?php echo ($sc_it_use == '0')?'checked':''; ?>> 진열안함 </label>&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th scope="row">승인상태</th>
        <td colspan="2">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            	<div class="radio">
                    <label><input type="radio" value="" id="it_status" name="sc_it_status" <?php echo ($sc_it_status == '')?'checked':''; ?>> 전체 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="승인대기" id="it_status1" name="sc_it_status" <?php echo ($sc_it_status == '승인대기')?'checked':''; ?>> 승인대기</label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="승인" id="it_status2" name="sc_it_status" <?php echo ($sc_it_status == '승인')?'checked':''; ?>> 승인 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="반려" id="it_status3" name="sc_it_status" <?php echo ($sc_it_status == '반려')?'checked':''; ?>> 반려 </label>&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" value="수정요청철회" id="it_status4" name="sc_it_status" <?php echo ($sc_it_status == '수정요청철회')?'checked':''; ?>> 수정요청철회 </label>&nbsp;&nbsp;&nbsp;
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
  	</div>

    <div class="x_panel">
      <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 상품 목록<small></small></h4>
        <div class="clearfix"></div>
      </div>

      <div class="tbl_head01 tbl_wrap">
        <div class="pull-left">
        	<span class="btn_ov01"><span class="ov_txt">검색결과</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
        </div>
        <div class="pull-right">
		<input type="hidden" name="sst" id="sst" value="<?php echo $sst; ?>">
		<input type="hidden" name="sod" id="sod"  value="<?php echo $sod; ?>">

          <select id="sstsod" onchange="sstsod_change(this);">
            <option value="it_time,asc" <?php echo get_selected($sst.','.$sod, 'it_time,asc') ; ?>>등록일순</option>
            <option value="it_time,desc" <?php echo get_selected($sst.','.$sod, 'it_time,desc') ; ?>>최근등록일순</option>
            <option value="it_update_time,asc" <?php echo get_selected($sst.','.$sod, 'it_update_time,asc') ; ?>>수정일</option>
            <option value="it_update_time,desc" <?php echo get_selected($sst.','.$sod, 'it_update_time,desc') ; ?>>최근수정일</option>
          </select>
          <script>
          function sstsod_change(ctl)
          {
          	var sstsod = $("#"+ctl.id).val().split(',');
          	$("#sst").val(sstsod[0]);
          	$("#sod").val(sstsod[1]);

          	$('#flistSearch').submit();
              return true;
          }
          </script>
          <select name="page_rows" onchange="$('#flistSearch').submit();">
            <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
            <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
            <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
          </select>
          <br/><br/>
        </div>
      </div>
</form>

<?php $colspan = 13; ?>
<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
        <th scope="col" rowspan="2">No</th>
        <th scope="col" rowspan="2">승인구분</th>
        <th scope="col" rowspan="2">입점몰명</th>
        <th scope="col" rowspan="2">입점몰코드</th>
        <th scope="col" rowspan="2"><?php echo subject_sort_link('it_id', 'sca='.$sca); ?>상품코드</a></th>
        <th scope="col" rowspan="2"><?php echo subject_sort_link('it_time', 'sca='.$sca); ?>등록일</a></th>
        <th scope="col" rowspan="2" colspan="2" id="th_pc_title"><?php echo subject_sort_link('it_name', 'sca='.$sca); ?>상품명</a></th>
        <th scope="col" rowspan="2" id="th_amt"><?php echo subject_sort_link('it_price', 'sca='.$sca); ?>판매가</a></th>
        <th scope="col" colspan="4">승인정보</th>
        <th scope="col" rowspan="2">관리</th>
	</tr>
	<tr>
        <th scope="col" >승인담당자</th>
        <th scope="col" >승인상태</th>
        <th scope="col" >요청일자</th>
        <th scope="col" >승인(반려)일자</th>
	</tr>
	</thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?php echo $bg ?>">
        <td class="td_chk">
        	<?php echo $i+1+$from_record ?>
        </td>
        <td class="td_sort grid_2">
            <label for="it_time_<?php echo $i; ?>" class="sound_only">구분</label>
            <?php
            if($row['it_status'] != "승인") {
                echo (!$row['it_modify_date'] || $row['it_modify_date'] == '0000-00-00 00:00:00')?"신규":"수정";
            }?>
        </td>
        <td class="td_sort grid_2">
            <label for="it_time_<?php echo $i; ?>" class="sound_only">입점몰명</label>
            <?php echo $row['company_name'] ?>
        </td>
        <td class="td_sort grid_2">
            <label for="it_time_<?php echo $i; ?>" class="sound_only">입점몰코드</label>
            <?php echo $row['company_code'] ?>
        </td>
        <td class="td_num grid_2">
            <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
            <?php echo $row['it_id']; ?>
        </td>
        <td class="td_sort grid_2">
            <label for="it_time_<?php echo $i; ?>" class="sound_only">등록일</label>
            <?php echo $row['it_time']; ?>
        </td>
        <td class="td_img grid_2"><?php echo get_it_image($row['it_id'], 50, 50); ?></td>
        <td headers="th_pc_title" class="td_input " style="text-align: left;cursor: pointer;">
            <label for="name_<?php echo $i; ?>" class="sound_only">상품명</label>
            <?php echo htmlspecialchars2(cut_str($row['it_name'],250, "")); ?>
        </td>
        <td headers="th_amt" class="td_numbig td_input grid_4">
            <label for="price_<?php echo $i; ?>" class="sound_only">판매가</label>
            <?php echo number_format($row['it_price']); ?>
        </td>
        <td class="td_input grid_2">
            <label for="use_<?php echo $i; ?>" class="sound_only">승인담당자</label>
            <?php echo ($row['it_approve_mb_name'])?$row['it_approve_mb_name'].'('.$row['it_approve_mb_id'].')':'' ?>
        </td>
        <td class="td_input grid_2">
            <label for="use_<?php echo $i; ?>" class="sound_only">승인상태</label>
            <?php echo $row['it_status'] ?>
        </td>
        <td class="td_input grid_2">
            <label for="use_<?php echo $i; ?>" class="sound_only">요청일자</label>
            <?php
            echo (!$row['it_modify_date'] || $row['it_modify_date'] == '0000-00-00 00:00:00')?$row['it_time']:$row['it_modify_date'];
            ?>
        </td>
        <td class="td_input grid_2">
            <label for="use_<?php echo $i; ?>" class="sound_only">승인반려일자</label>
            <?php
            echo ($row['it_approve_date'] && $row['it_approve_date'] != '0000-00-00 00:00:00')?$row['it_approve_date']:'';
            ?>
        </td>
        <td class="td_mng td_mng_s">
        	<a href="./itemform.brand.php?w=u&amp;it_id=<?php echo $row['it_id']; ?>&amp;<?php echo $qstr; ?>" class="btn btn_02 itemview">상세보기</a>
        </td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 한건도 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>

</form>


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

</div></div></div>

<script>

function fitemlist_submit(f)
{
    return true;
}


$(function() {
	$.get_ca_name = function(ca_name1,ca_name2,ca_name3, targetuid, targetSel){

		$targetSel = $("#"+targetSel);
		$.post(
                "<?php echo G5_ADMIN_URL; ?>/design/design_iteminfo_get.php",
                { ca_name1: ca_name1, ca_name2: ca_name2, ca_name3: ca_name3 },
                function(data) {
                	var responseJSON = JSON.parse(data);
                	var count = responseJSON.length;
	   				for(i=0; i<count; i++) {
	   					if(responseJSON[i][targetuid] != "") {
	   						$targetSel.append($('<option>', {value:responseJSON[i][targetuid], text: responseJSON[i][targetuid]}));
	   					}
	   				}
                    
                }
            );
	};
	$("#sel_ca_name1").change(function(e){

		$("#ca_name1").val($(this).val());
		$("#ca_name2").val("");
		$("#ca_name3").val("");
		$("#ca_name4").val("");

		$("#sel_ca_name2").empty().append($('<option>', {value:'', text: '----선택-----'}));
		$("#sel_ca_name3").empty().append($('<option>', {value:'', text: '----선택-----'}));
		$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
		if($(this).val() != "")
		{
			$.get_ca_name($(this).val(), '', '', 'ca_name2', 'sel_ca_name2');
		} else {
			
		}
	});

	$("#sel_ca_name2").change(function(){
		$("#ca_name2").val($(this).val());
		$("#ca_name3").val("");
		$("#ca_name4").val("");

		$("#sel_ca_name3").empty().append($('<option>', {value:'', text: '----선택-----'}));
		$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
		if($(this).val() != "")
		{
			$.get_ca_name($("#sel_ca_name1").val(), $(this).val(), '', 'ca_name3', 'sel_ca_name3');
		}
	});

	$("#sel_ca_name3").change(function(){
		$("#ca_name3").val($(this).val());
		$("#ca_name4").val("");

		$("#sel_ca_name4").empty().append($('<option>', {value:'', text: '----선택-----'}));
		if($(this).val() != "")
		{
			$.get_ca_name($("#sel_ca_name1").val(), $("#sel_ca_name2").val(), $(this).val(),  'ca_name4', 'sel_ca_name4');
		}
	});

	$("#sel_ca_name4").change(function(){
		$("#ca_name4").val($(this).val());
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
	$('#it_time').val("<?php echo $sc_it_time ?>");
	
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

	
    $(".itemview").click(function() {
        var href = $(this).attr("href");
        window.open(href, "itemview", "left=100, top=100, width=1000, height=800, scrollbars=0");
        return false;
    });
    window.addEventListener("keydown", (e) => {
        if (e.keyCode == 13) {
            document.getElementById('flistSearch').submit();
        }
    })
});
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
