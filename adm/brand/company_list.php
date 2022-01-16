<?php
$sub_menu = "92";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

$g5['title'] = '입점사관리';
include_once('../admin.head.php');

$sql_common = " from lt_member_company as a, lt_member as b";

$sql_search = " where a.mb_id = b.mb_id and cp_status != '' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}


if ($mb_datetime != "") {
    $mb_datetimes = explode("~", $mb_datetime);
    $fr_mb_datetime = trim($mb_datetimes[0]);
    $to_mb_datetime = trim($mb_datetimes[1]);
    
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_mb_datetime) ) $fr_mb_datetime = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_mb_datetime) ) $to_mb_datetime = '';
    
    if ($fr_mb_datetime && $to_mb_datetime) {
        $sql_search .= " and register_date between '$fr_mb_datetime 00:00:00' and '$to_mb_datetime 23:59:59' ";
    }
}

if(isset($cp_status) && $cp_status != ""){
    $sql_search .= " and cp_status = '{$cp_status}' ";
}

if (!$sst) {
    $sst = "approve_date";
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


$sql = " select a.*, b.mb_name
          {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$colspan = 16;
$token = get_admin_token();


$qstr = "sort1=".urlencode($sort1)."&amp;sort2=".urlencode($sort2)."&amp;sfl=".urlencode($sfl)."&amp;stx=".urlencode($stx)."&amp;mb_datetime=".urlencode($mb_datetime)."&amp;cp_status=".urlencode($cp_status);
$qstr .="&amp;page_rows=".urlencode($page_rows);
?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" onsubmit="$('#page').val('1');">
<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
<input type="hidden" name="page"  id="page" value="<?php echo $page; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
	<colgroup>
    <col class="grid_4">
    <col>
    <col class="grid_3">
    </colgroup>
    
    <tr>
        <th scope="row" style="width:15%;">가입상태구분</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
			<?php if(!isset($cp_status)) $cp_status = ''; ?>
            <input type="radio" name="cp_status" value="" id="cp_status" <?php echo get_checked($cp_status, '');?>>
            <label for="cp_status">전체</label>
            <input type="radio" name="cp_status" value="승인요청" id="cp_status1" <?php echo get_checked($cp_status, '승인요청');  ?>>
            <label for="cp_status1">승인요청</label>
            <input type="radio" name="cp_status" value="승인반려" id="cp_status2" <?php echo get_checked($cp_status, '승인반려');  ?>>
            <label for="cp_status2">승인반려</label>
            <input type="radio" name="cp_status" value="승인완료" id="cp_status3" <?php echo get_checked($cp_status, '승인완료');  ?>>
            <label for="cp_status3">승인완료</label>
            <input type="radio" name="cp_status" value="정보변경신청" id="cp_status4" <?php echo get_checked($cp_status, '정보변경신청');  ?>>
            <label for="cp_status4">정보변경신청</label>
            <input type="radio" name="cp_status" value="정보변경반려" id="cp_status5" <?php echo get_checked($cp_status, '정보변경반려');  ?>>
            <label for="cp_status5">정보변경반려</label>
            <input type="radio" name="cp_status" value="정보변경승인" id="cp_status6" <?php echo get_checked($cp_status, '정보변경승인');  ?>>
            <label for="cp_status6">정보변경승인</label>
            
            <input type="radio" name="cp_status" value="탈퇴신청" id="cp_status7" <?php echo get_checked($cp_status, '탈퇴신청');  ?>>
            <label for="cp_status7">탈퇴신청</label>
            <input type="radio" name="cp_status" value="탈퇴완료" id="cp_status8" <?php echo get_checked($cp_status, '탈퇴완료');  ?>>
            <label for="cp_status8">탈퇴완료</label>
		</div>
		</td>
	</tr>
    <tr>
        <th scope="row">기간설정</th>
		<td colspan="2">
        	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            	<input type='text' class="form-control" id="mb_datetime" name="mb_datetime" value=""/>
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
        <th scope="row">검색</th>
		<td colspan="2">
		<div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
        	<select name="sfl" id="sfl">
                <option value="company_name"<?php echo get_selected($_GET['sfl'], "company_name"); ?>>입점몰명</option>
                <option value="company_code"<?php echo get_selected($_GET['sfl'], "company_code"); ?>>입점몰코드<option>
            </select>
    		<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class=" frm_input">
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

<div class="tbl_head01 tbl_wrap">
    <div class="pull-left">
    </div>
    <div class="pull-right">
    <input type="hidden" name="sst" id="sst" value="<?php echo $sst; ?>">
    <input type="hidden" name="sod" id="sod"  value="<?php echo $sod; ?>">

      <select id="sstsod" onchange="sstsod_change(this);">
        <option value="approve_date,desc" <?php echo get_selected($sst.','.$sod, 'approve_date,desc') ; ?>>최신순</option>
        <option value="company_name,asc" <?php echo get_selected($sst.','.$sod, 'company_name,asc') ; ?>>가나다순(오름차순)</option>
        <option value="company_name,desc" <?php echo get_selected($sst.','.$sod, 'company_name,desc') ; ?>>가나다순(내림차순)</option>
      </select>
      <script>
      function sstsod_change(ctl)
      {
      	var sstsod = $("#"+ctl.id).val().split(',');
      	$("#sst").val(sstsod[0]);
      	$("#sod").val(sstsod[1]);

      	$('#fsearch').submit();
          return true;
      }
      </script>

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
        <th scope="col" rowspan="2">No</th>
        <th scope="col" rowspan="2">입점몰명</th>
        <th scope="col" rowspan="2">입점몰코드</th>
        <th scope="col" rowspan="2">수수료</th>
        <th scope="col" rowspan="2">정산일</th>
        <th scope="col" rowspan="2">입점몰<br/>담당자</th>
        <th scope="col" colspan="4">가입정보</th>
        <th scope="col" rowspan="2">수수료관리</th>
    </tr>
    <tr>
        <th scope="col" >승인담당자</th>
        <th scope="col" >가입상태</th>
        <th scope="col" >가입일자</th>
        <th scope="col" >승인(반려)일자</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_join" class="td_num"><?php echo $i+1+$from_record; ?></td>
        <td headers="mb_list_name" class="td_mbname" onclick="view('<?php echo $row['mb_id'] ?>');" style="cursor: pointer;">
        	<strong><?php echo get_text($row['company_name']); ?></strong>
        </td>
        <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['company_code']); ?></td>
        <td headers="mb_list_name" class="td_mbname" onclick="view_commission('<?php echo $row['mb_id'] ?>','<?php echo $row['company_name'] ?>','<?php echo $row['company_code'] ?>','<?php echo $row['mb_name'] ?>','<?php echo $row['cp_commission'] ?>','<?php echo $row['approve_date'] ?>','<?php echo $row['approve_mb_name'] ?>','<?php echo $row['approve_mb_id'] ?>');" style="cursor: pointer;">
        	<strong><?php echo ($row['cp_commission'])?$row['cp_commission']."%":""; ?></strong>
        </td>
        <td headers="mb_list_name" class="td_mbname"  onclick="view_calculate('<?php echo $row['mb_id'] ?>','<?php echo $row['company_name'] ?>','<?php echo $row['company_code'] ?>','<?php echo $row['mb_name'] ?>','<?php echo $row['cp_calculate_date'] ?>','<?php echo $row['cp_calculate_date1'] ?>','<?php echo $row['cp_calculate_date2'] ?>','<?php echo $row['approve_date'] ?>','<?php echo $row['approve_mb_name'] ?>','<?php echo $row['approve_mb_id'] ?>');" style="cursor: pointer;">
        	<?php echo ($row['cp_calculate_date'])?"매달".$row['cp_calculate_date']."일":""; ?>
        </td>
        <td headers="mb_list_name" class="td_mbname"><?php echo $row['mb_name'].'('.$row['mb_id'].')'; ?></td>
        <td headers="mb_list_name" class="td_mbname"><?php echo ($row['approve_mb_id'])?$row['approve_mb_name'].'('.$row['approve_mb_id'].')':""; ?></td>
        <td headers="mb_list_name" class="td_mbname" onclick="view('<?php echo $row['mb_id'] ?>');" style="cursor: pointer;">
        	<strong><?php echo $row['cp_status']; ?></strong>
        </td>
        <td headers="mb_list_name" class="td_mbname"><?php echo $row['register_date']; ?></td>
        <td headers="mb_list_name" class="td_mbname"><?php echo $row['approve_date']; ?></td>
        
        <td headers="mb_list_mng" class="td_mng td_mng_l">
        	<?php if($row['company_code']) {?>
        	<a href="./company_item_excel.php?company_code=<?php echo $row['company_code'] ?>" download><input type="button" class="btn btn_02 btn_download" value="다운로드" mb_id="<?php echo $row['mb_id'] ?>"></a>
        	
        	<a onclick="view_commission2('<?php echo $row['mb_id'] ?>','<?php echo $row['company_name'] ?>','<?php echo $row['company_code'] ?>','<?php echo $row['mb_name'] ?>','<?php echo $row['cp_commission'] ?>','<?php echo $row['approve_date'] ?>','<?php echo $row['approve_mb_name'] ?>','<?php echo $row['approve_mb_id'] ?>');" style="cursor: pointer;">
        	<input type="button" class="btn btn_02 " value="업로드" ></a>
        	<?php } ?>
        </td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"10\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div id="modal_commission" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">수수료 변경 팝업</h4>
  </div>
  <form name="fmemberlist" id="fmemberlist"  method="post" action="./company_form_update.php" >
	<input type="hidden" name="token" value="<?php echo $token?>">
	<input type="hidden" name="w" value="commission">
	<input type="hidden" name="mb_id" value="">
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_2">
            <col class="grid_2">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th>입점몰명</th>
            <td colspan="2" id="commission_td1"></td>
        </tr>
        <tr>
            <th>입점몰코드</th>
            <td colspan="2" id="commission_td2"></td>
        </tr>
        <tr>
            <th>입점몰담당자명</th>
            <td colspan="2" id="commission_td3"></td>
        </tr>
        <tr>
            <th rowspan="2">입점몰담당자명</th>
            <th>기본수수료</th>
            <td id="commission_td4"></td>
        </tr>
        <tr>
            <th>변경수수료</th>
            <td><input type="text" placeholder="" id="cp_commission" name ="cp_commission" required="required" class="frm_input" size="5" value="">%</td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" name="act_button" value="수정" onclick="document.pressed=this.value" class="btn btn-success">
  </div>
  </form>
</div>
<!-- Modal content-->
</div>
</div>

<div id="modal_calculate" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">정산일 변경 팝업</h4>
  </div>
  <form name="fmemberlist" id="fmemberlist"  method="post" action="./company_form_update.php" >
	<input type="hidden" name="token" value="<?php echo $token?>">
	<input type="hidden" name="w" value="calculate">
	<input type="hidden" name="mb_id" value="">
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_2">
            <col class="grid_2">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th>입점몰명</th>
            <td colspan="2" id="calculate_td1"></td>
        </tr>
        <tr>
            <th>입점몰코드</th>
            <td colspan="2" id="calculate_td2"></td>
        </tr>
        <tr>
            <th>입점몰담당자명</th>
            <td colspan="2" id="calculate_td3"></td>
        </tr>
        <tr>
            <th rowspan="4">입점몰담당자명</th>
            <th>기존 매출기간</th>
            <td id="calculate_td4"></td>
        </tr>
        <tr>
            <th>기존 정산일</th>
            <td id="calculate_td5"></td>
        </tr>
        <tr>
            <th>변경 매출기간</th>
            <td>전월 <input type="text" placeholder="" id="cp_calculate_date1" name ="cp_calculate_date1" class="frm_input" size="5" value="">
            	~ 당월 <input type="text" placeholder="" id="cp_calculate_date2" name ="cp_calculate_date2" class="frm_input" size="5" value=""></td>
        </tr>
        <tr>
            <th>변경 정산일</th>
            <td>익월 <input type="text" placeholder="" id="cp_calculate_date" name ="cp_calculate_date" required="required" class="frm_input" size="5" value="">일</td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" name="act_button" value="수정" onclick="document.pressed=this.value" class="btn btn-success">
  </div>
  </form>
</div>
<!-- Modal content-->
</div>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>
	
	</div>
	</div>
</div>

<div id="modal_commission2" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">수수료 일괄변경 팝업</h4>
  </div>
  <form name="fitem" method="post" action="./company_item_update.php" enctype="multipart/form-data">
	<input type="hidden" name="token" value="<?php echo $token?>">
	<input type="hidden" name="w" value="commission2">
	<input type="hidden" name="mb_id" value="">
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_2">
            <col class="grid_2">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th>입점몰명</th>
            <td colspan="2" id="commission2_td1"></td>
        </tr>
        <tr>
            <th>입점몰코드</th>
            <td colspan="2" id="commission2_td2"></td>
        </tr>
        <tr>
            <th>입점몰담당자명</th>
            <td colspan="2" id="commission2_td3"></td>
        </tr>
        <tr>
            <th rowspan="2">입점몰담당자명</th>
            <th>기본수수료</th>
            <td id="commission2_td4"></td>
        </tr>
        <tr>
            <th>파일업로드</th>
            <td><input type="file" name="excelfile" id="excelfile" required="required" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"></td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" name="act_button" value="수정" onclick="document.pressed=this.value" class="btn btn-success">
  </div>
  </form>
</div>
<!-- Modal content-->
</div>
</div>

<div id="popup"></div>
<script>
$(function(){

	$(".btn_upload").click(function(){
        var url = this.href;
        window.open(url, "upload_form", "left=100,top=100,width=800,height=600,scrollbars=0");
        return false;
	});
	
    $('#mb_datetime,#mb_today_login,#od_time').daterangepicker({
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
       if($mb_datetime !='') echo "$('#mb_datetime').val('".$mb_datetime."');";
       else echo "$('#mb_datetime').val('');";
       
       if($od_time !='') echo "$('#od_time').val('".$od_time."');";
       else echo "$('#od_time').val('');";
       
       if($mb_today_login !='') echo "$('#mb_today_login').val('".$mb_today_login."');";
       else echo "$('#mb_today_login').val('');";
    ?>

    $(".mb_10").change(function(){

    	var mb_10 = "";
        var sep = "";
        $("input.mb_10:checked").each(function(){
        	//alert($(this).val());    
			if(mb_10 != "") mb_10 += ",";
        	mb_10 += $(this).val();
        	
        });

        $("#mb_10").val(mb_10)
        
    });
    
    //날짜 버튼
    $("button[name='dateBtn']").click(function(){
    	
    	var d = $(this).attr("data");
    	if(d == "all") {
    		$('#mb_datetime').val("");
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
    
    		$('#mb_datetime').data('daterangepicker').setStartDate(startD);
    		$('#mb_datetime').data('daterangepicker').setEndDate(endD);
    	}
    });

    $(".member_form").on("click", function() {
        var url = this.href;
        window.open(url, "member_form", "left=100,top=100,width=1500,height=800,scrollbars=1");
        return false;
    });
    
    $(".mb_memo").on("click", function() {
        var $this = $(this);
        var mb_id = $this.attr("mb_id");

        $("#modal_intercept").modal('show');
        
        return false;
    });

    $("#btn_mb_memo").on("click", function() {
        var mm_memo = $("#mm_memo").val();
        var is_important = $("#is_important").val();
        var mb_id = $("#mb_memo_id").text();

		$.post(
                "ajax.member_list_update.php",
                {	act_button : "메모"
                    , mb_id:  mb_id
                    , mm_memo:  mm_memo
                    , is_important: is_important
                    },
                function(data) {
                	var responseJSON = JSON.parse(data);
                	if(responseJSON.result == "S"){
                		$("#fsearch").submit();
                    }else {
                    	alert("오류가 발생했습니다. 다시 시도해주시기 바랍니다.");
                        return false;
                	}
                }
            );

        
    });
    window.addEventListener("keydown", (e) => {
        if (e.keyCode == 13) {
            document.getElementById('fsearch').submit();
        }
    })
    
});

function view_commission(mb_id,company_name,company_code,mb_name,cp_commission,approve_date,approve_mb_name,approve_mb_id)
{
	$("input[name='mb_id']").val(mb_id);
	$("#commission_td1").html(company_name);
	$("#commission_td2").html(company_code);
	$("#commission_td3").html(mb_name);
	$("#commission_td4").html(cp_commission+"% ("+approve_date.substring(0,10)+" / "+approve_mb_name+"("+approve_mb_id+"))");

	$("#cp_commission").val(cp_commission);
	
	$("#modal_commission").modal("show");
}

function view_commission2(mb_id,company_name,company_code,mb_name,cp_commission,approve_date,approve_mb_name,approve_mb_id)
{
	$("input[name='mb_id']").val(mb_id);
	$("#commission2_td1").html(company_name);
	$("#commission2_td2").html(company_code);
	$("#commission2_td3").html(mb_name);
	$("#commission2_td4").html(cp_commission+"% ("+approve_date.substring(0,10)+" / "+approve_mb_name+"("+approve_mb_id+"))");

	$("#cp_commission2").val(cp_commission);
	
	$("#modal_commission2").modal("show");
}

function view_calculate(mb_id,company_name,company_code,mb_name,cp_calculate,cp_calculate1,cp_calculate2,approve_date,approve_mb_name,approve_mb_id)
{
	$("input[name='mb_id']").val(mb_id);
	$("#calculate_td1").html(company_name);
	$("#calculate_td2").html(company_code);
	$("#calculate_td3").html(mb_name);
	$("#calculate_td4").html("전월 "+cp_calculate1+"일 ~ 당월"+cp_calculate2+"일 ("+approve_date.substring(0,10)+" / "+approve_mb_name+"("+approve_mb_id+"))");
	$("#calculate_td5").html("익월 "+cp_calculate+"일 ("+approve_date.substring(0,10)+" / "+approve_mb_name+"("+approve_mb_id+"))");

	$("#cp_calculate_date").val(cp_calculate);
	$("#cp_calculate_date1").val(cp_calculate1);
	$("#cp_calculate_date2").val(cp_calculate2);
	
	$("#modal_calculate").modal("show");
}

function view(mb_id)
{
	$.post(
            "ajax.company_view.php",
            {mb_id:  mb_id },
            function(data) {
            	$("#popup").empty().html(data);
            	$(".modal_company_view_detail").modal("show");
            }
        );
}

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

function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "삭제") {
        if(!confirm("선택한 회원을 탈퇴/삭제 처리 하시겠습니까? \n탈퇴/삭제된 아이디는 복구가 불가능 합니다.")) {
            return false;
        }
    } 
    else if(document.pressed == "불량회원설정") {
		if($("#mb_7").val() == ""){
			alert("설정 사유를 입력해 주세요");
			$("#mb_7").focus();
			return false;
		}
        
    } 
    else if(document.pressed == "개별메일전송") {
    	$("#fmemberlist").attr("action","<?php echo G5_ADMIN_URL?>/operation/configform_sendEmail.php");
    	$("#fmemberlist").attr("target","_blank");
        
    } else {
    	return false;    
    }

    return true;
}


</script>

<?php
include_once ('../admin.tail.php');
?>
