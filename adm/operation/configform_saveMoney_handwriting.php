<?php
$sub_menu = "200330";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');

$sql_common = " from {$g5['point_table']} ";

$sql_search = " where po_request_id != '' ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($po_datetime != "") {
    $po_datetimes = explode("~", $po_datetime);
    $fr_date = trim($po_datetimes[0]);
    $to_date = trim($po_datetimes[1]);

    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

    if ($fr_date && $to_date) {
        $sql_search .= " and po_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
    }
}

if ($po_rel_action != "") {
    $sql_search .= " and po_rel_action = '{$po_rel_action}' ";
}

if (!$sst) {
    $sst  = "po_id";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
    {$sql_common}
    {$sql_search}
    {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
    {$sql_common}
    {$sql_search}
    {$sql_order}
    limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$po_expire_term = '';
if($config['cf_point_term'] > 0) {
    $po_expire_term = $config['cf_point_term'];
}

$colspan = 9;

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";

$excel_sql = $sql;
if(substr_count($sql, "limit")){
    $sqls = explode('limit', $sql);
    $excel_sql = $sqls[0];
}
$headers = array('NO', '일자', '아이디','성명', '적립금 내역', '처리상태', '요청자', '요청자명','처리자','처리자명','내용');
$bodys = array('NO', 'po_datetime', 'mb_id','mb_name', 'po_point', 'po_rel_action', 'po_request_id', 'po_request_name', 'po_approve_id','po_approve_name','po_content');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));
    
$g5['title'] = '수기지급 관리';
include_once ('../admin.head.php');

?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  	<div class="x_panel">


  	<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">

      <div class="x_title">
      <h4><span class="fa fa-check-square"></span> 조회<small></small></h4>
  		<label class="nav navbar-right"></label>
  		<div class="clearfix"></div>
  	  </div>

  	  <div class="tbl_frm01 tbl_wrap">
        <table>

          <tr>
            <th scope="col">
              검색대상
            </th>
            <td>
            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                	<select name="sfl" id="sfl">
                        <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
                        <option value="po_content"<?php echo get_selected($_GET['sfl'], "po_content"); ?>>내용</option>
                    </select>
                    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
                    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
				</div>
            </td>
          </tr>
          <tr>
            <th scope="col">검색기간</th>
            <td colspan="3">
            	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                	<input type='text' class="form-control" id="po_datetime" name="po_datetime" value=""/>
                	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
            	</div>
            	<div class="btn-group col-lg-8 col-md-6 col-sm-12 col-xs-12" >
                    <button type="button" class="btn btn-default" name="dateBtn" data="today">오늘</button>
                    <button type="button" class="btn btn-default" name="dateBtn" data="3d">3일</button>
                    <button type="button" class="btn btn-default" name="dateBtn" data="1w">1주</button>
                    <button type="button" class="btn btn-default" name="dateBtn" data="1m">1개월</button>
                    <button type="button" class="btn btn-default" name="dateBtn" data="3m">3개월</button>
                    <button type="button" class="btn btn-default" name="dateBtn" data="all">전체</button>
                 </div>
            </td>
            </td>
          </tr>
          <tr>
            <th scope="col">
              처리상태
            </th>
            <td>
            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label><input type="radio" name="po_rel_action" <?php echo get_checked($po_rel_action, "") ?> value="" /> 전체 </label>&nbsp;&nbsp;&nbsp;
                      <label><input type="radio" name="po_rel_action" <?php echo get_checked($po_rel_action, "처리완료") ?> value="처리완료" /> 처리완료 </label>&nbsp;&nbsp;&nbsp;
                      <label><input type="radio" name="po_rel_action" <?php echo get_checked($po_rel_action, "요청완료") ?> value="요청완료" /> 요청완료 </label>&nbsp;&nbsp;&nbsp;
                      <label><input type="radio" name="po_rel_action" <?php echo get_checked($po_rel_action, "반려") ?> value="반려" /> 반려 </label>
				</div>
            </td>
          </tr>
          <tr>
          <tr>
            <td class="col-md-12 col-sm-12 col-xs-12 text-right" colspan="4" style="text-align:right;">
              <input type="submit" class="btn btn-primary" value="검색"></input>
            </td>
          </tr>
        </table>
  	  </div>
  	</div>

    <div class="x_panel">
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
          <caption>목록</caption>
          <thead>
            <tr>
              <th colspan="8">
                <div class="pull-right">
                  <button type="button" class="btn btn-sm btn-default" id="handwriting_btn_apply_1">수기지급 요청하기</button>
                  <button type="button" class="btn btn-sm btn-default" id="excel_download1">Excel 다운로드</button>
                </div>
              </th>
            </tr>
          <tr>
            <th rowspan="2" colspan="1">일자</th>
            <th rowspan="2" colspan="1">아이디</th>
            <th rowspan="2" colspan="1">적립금 내역</th>
            <th rowspan="2" colspan="1">처리상태</th>
            <th colspan="2" rowspan="1">지급 처리자</th>
            <th rowspan="2" colspan="1">내용</th>
            <th rowspan="2" colspan="1">상세보기</th>
          </tr>
          <tr>
            <th >요청자</th>
            <th >처리자</th>
          </tr>
          </thead>
          <tbody>
            <?php
            for ($i=0; $row=sql_fetch_array($result); $i++) {
                if ($i==0 || ($row2['mb_id'] != $row['mb_id'])) {
                    $sql2 = " select mb_id, mb_name, mb_nick, mb_email, mb_homepage, mb_point from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
                    $row2 = sql_fetch($sql2);
                }

                $mb_nick = get_sideview($row['mb_id'], $row2['mb_nick'], $row2['mb_email'], $row2['mb_homepage']);

                $link1 = $link2 = '';
                if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table']) {
                    $link1 = '<a href="'.G5_BBS_URL.'/board.php?bo_table='.$row['po_rel_table'].'&amp;wr_id='.$row['po_rel_id'].'" target="_blank">';
                    $link2 = '</a>';
                } elseif($row['po_rel_table'] == "@order") {
                    $link1 = '<a href="'.G5_ADMIN_URL.'/shop_admin/orderform.php?od_id='.$row['po_rel_id'].'" target="_blank">';
                    $link2 = '</a>';
                }

                $expr = '';
                if($row['po_expired'] == 1)
                    $expr = ' txt_expired';

                $bg = 'bg'.($i%2);
            ?>
            <tr>
        	  <td class="td_datetime"><?php echo $row['po_datetime'] ?></td>
              <td class="td_left"><a href="?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
        	  <td class="td_num td_pt"><?php echo number_format($row['po_point']) ?></td>
              <td><?php echo $row['po_rel_action'] ?></td>
              <td><?php echo $row['po_request_id'] ?></td>
              <td><?php echo $row['po_approve_id'] ?></td>
        	  <td class="td_left"><?php echo $link1 ?><?php echo $row['po_content'] ?><?php echo $link2 ?></td>
              <td><button type="button" class="btn btn-default" name="btn_datail_view" value="" po_id='<?php echo $row['po_id'] ?>'>상세보기</button></td>
            </tr>
            <?php
            }

            if ($i == 0)
                echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
            ?>
          </tbody>
          <thead>
            <tr>
              <th colspan="9">
                <div class="pull-right">
                  <button type="button" class="btn btn-sm btn-default" id="handwriting_btn_apply_2">수기지급 요청하기</button>
                  <button type="button" class="btn btn-sm btn-default" id="excel_download2">Excel 다운로드</button>
                </div>
              </th>
            </tr>
          </thead>
          </table>
      </div>
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
	
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

  $('#handwriting_btn_apply_1, #handwriting_btn_apply_2').click(function(){
    $('#handwriting_modal_apply').modal('toggle');
  });


  $('#saveMoney_approve1').click(function(){
	  $('#po_rel_action1').prop("checked",false);
	  $('#po_rel_action1').closest("label").prop("hidden",true);
	  $('#po_rel_action2').prop("checked",true);
	  $('#po_rel_action2').closest("label").prop("hidden",false); 
    $('#detail_approve_reason').prop("hidden",false);
  });

  $('#saveMoney_approve2').click(function(){
	  $('#po_rel_action1').prop("checked",true);
	  $('#po_rel_action1').closest("label").prop("hidden",false);
	  $('#po_rel_action2').prop("checked",false); 
	  $('#po_rel_action2').closest("label").prop("hidden",true); 
    $('#detail_approve_reason').prop("hidden",false);
  });

  $('#po_datetime').daterangepicker({
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
     if($fr_date !='') echo "$('#po_datetime').val('".$fr_date." ~ ".$to_date."');";
     else if($po_datetime !='') echo "$('#po_datetime').val('".$po_datetime."');";
     else echo "$('#po_datetime').val('');";
  ?>

  //날짜 버튼
  $("button[name='dateBtn']").click(function(){

  	var d = $(this).attr("data");
  	if(d == "all") {
  		$('#po_datetime').val("");
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

      	$('#po_datetime').data('daterangepicker').setStartDate(startD);
      	$('#po_datetime').data('daterangepicker').setEndDate(endD);
  	}

  });
  window.addEventListener("keydown", (e) => {
    if (e.keyCode == 13) {
      	document.getElementById('fsearch').submit();
    }
  })

});

$(document).on( 'click', 'input[name="saveMoney_list"]', function () {
var checked_val = $(this).val();
$('[col-group*=saveMoney_config]').each(function(){
  if($(this).attr("col-group") == 'saveMoney_config_'+checked_val){
    $(this).removeAttr('disabled');
  }else{
    $(this).attr('disabled','true');
  }
});
});
$(document).on( 'click', 'button[name="btn_datail_view"]', function () {
	var po_id = $(this).attr("po_id");

	$.ajax({
 		 type:"POST",
 		 url:"./configform_saveMoney_handwriting_update.php",
         type: "POST",
         data: {
             "w": "s",
             "po_id": po_id
         },
         dataType: "json",
         async: false,
         cache: false,
 		 success : function(data) {
   			if(data.po_id){
   	   			$("#view_po_request_id").html(data.po_request_id);
   	   			$("#view_po_datetime").html(data.po_datetime);
   	   			$("#view_po_rel_action").html(data.po_rel_action);
   	   			$("#view_mb_id").html(data.mb_id);
   	   			$("#view_mb_name").html(data.mb_name);
   	   			$("#view_mb_hp").html(data.mb_hp);
   	   			$("#view_po_mb_point").html(number_format(data.po_point));
   	   			if(data.po_point < 0){
   	   	   			$("#view_po_mb_point_type").html("(-)적립금차감");
   	   			}else if(data.po_point > 0){
   	   	   			$("#view_po_mb_point_type").html("(+)적립금증액");
   	   			} else {
   	   				$("#view_po_mb_point_type").html("");
   	   			}
   	   			$("#view_po_content").html(data.po_content);

   	   			$("#po_id").val(data.po_id);
   	   			$("#po_content_approve").val("");


   	   			$("#approve_w").val("a");

   	   			if(data.po_rel_action == "처리완료" || data.po_rel_action == "반려"){

   	   				$("#saveMoney_delete").removeClass("hidden").addClass("hidden");
   	   				$("#saveMoney_approve1").removeClass("hidden").addClass("hidden");
   	   				$("#saveMoney_approve2").removeClass("hidden").addClass("hidden");

   	   			} else {

   	   				$("#saveMoney_delete").removeClass("hidden");
   	   				$("#saveMoney_approve1").removeClass("hidden");
   	   				$("#saveMoney_approve2").removeClass("hidden");
   	   			}

   	   			$('#detail_approve_reason').prop("hidden",true);
   			}
	 		//var responseJSON = JSON.parse(res);
			return true;
 		 },
 		 error : function(request,status,error){
 			return false;
 		 }
 	 });


  	$('#detail_modal').modal('show');
});

$(function(){

	 $("#saveMoney_delete").click(function(){

		if(!confirm("삭제하시겠습니까?")) return false;
		$("#approve_w").val("d");

		var form = $('#approve_form')[0];
	    var data = new FormData(form);

		$("#saveMoney_delete").prop("disabled", true);
		$.ajax({
	 		 type:"POST",
	 		 url:"./configform_saveMoney_handwriting_update.php",
	 		 data : data,
             processData: false,
             contentType: false,
             cache: false,
             timeout: 600000,
	 		 success : function(res) {
				 	console.log(res);
	  			$("#saveMoney_delete").prop("disabled", false);

	 			var responseJSON = JSON.parse(res);

				if(responseJSON.result == "S") {
					alert(responseJSON.alertMsg);

	 			} else {
	 				alert(responseJSON.alertMsg);
	 			}

				$("#detail_modal").modal('hide');
				$("#fsearch").submit();
				return true;
	 		 },
	 		 error : function(request,status,error){
	 			$("#saveMoney_delete").removeAttr("disabled");
	 			alert('');
	 			return false;
	 		 }
	 	 });
	 });

	 $("#saveMoney_approve_save").click(function(){

		if(!confirm("수기지급요청을 처리하시겠습니까?")) return false;
		$("#approve_w").val("a");

		var form = $('#approve_form')[0];
	    var data = new FormData(form);

		$("#saveMoney_approve_save").prop("disabled", true);
		$.ajax({
	 		 type:"POST",
	 		 url:"./configform_saveMoney_handwriting_update.php",
	 		 data : data,
             processData: false,
             contentType: false,
             cache: false,
             timeout: 600000,
	 		 success : function(res) {
				 	console.log(res);
	  			$("#saveMoney_approve_save").prop("disabled", false);

	 			var responseJSON = JSON.parse(res);

				if(responseJSON.result == "S") {
					alert(responseJSON.alertMsg);

	 			} else {
	 				alert(responseJSON.alertMsg);
	 			}

				$("#detail_modal").modal('hide');
				$("#fsearch").submit();
				return true;
	 		 },
	 		 error : function(request,status,error){
	 			$("#saveMoney_approve_save").removeAttr("disabled");
	 			alert('');
	 			return false;
	 		 }
	 	 });
	 });
	/*
		approve_w
		po_id
		po_rel_action
		po_content_approve

	*/
	//saveMoney_delete
	//saveMoney_approve_save

});

function fconfigform_submit(f)
{
    f.action = "./configform_delivery_update.php";
    return true;
}
</script>
<!-- @END@ 내용부분 끝 -->

<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">팝업 - 수기지급 요청 상세보기</h4>

      </div>
      <div class="modal-body" >
        <div class="row">
          <div class="x_title">
            <h4><span class="fa fa-check-square"></span> 요청 상세정보<small></small></h4>
        		<label class="nav navbar-right"></label>
        		<div class="clearfix"></div>
      	  </div>
          <div class="tbl_frm01 tbl_wrap">
            <table>
              <tbody>
                <tr>
                  <th>요청자</th>
                  <td id="view_po_request_id"></td>
                </tr>
                <tr>
                  <th>요청일자</th>
                  <td id="view_po_datetime"></td>
                </tr>
                <tr>
                  <th>처리상태</th>
                  <td id="view_po_rel_action"></td>
                </tr>
                <tr>
                  <th>아이디</th>
                  <td id="view_mb_id"></td>
                </tr>
                <tr>
                  <th>성명</th>
                  <td id="view_mb_name"></td>
                </tr>
                <tr>
                  <th>휴대전화번호</th>
                  <td id="view_mb_hp"></td>
                </tr>
                <tr>
                  <th>증감여부</th>
                  <td id="view_po_mb_point_type"></td>
                </tr>
                <tr>
                  <th>적립금금액</th>
                  <td id="view_po_mb_point">원</td>
                </tr>
                <tr>
                  <th>내용</th>
                  <td id="view_po_content"></td>
                </tr>
              <tr >
                <td colspan="2">
                  <div  class="pull-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
                    <button type="button" class="btn btn-primary" id="saveMoney_delete" >삭제</button>
                    <button type="button" class="btn btn-primary" id="saveMoney_approve1" >반려하기</button>
                    <button type="button" class="btn btn-primary" id="saveMoney_approve2" >승인하기</button>
                  </div>
                </td>
              </tr>
              </tbody>
            </table>
      	  </div>
        </div>
      </div>

      <div class="modal-footer" id="detail_approve_reason" hidden>
      <form name="approve_form" method="post" id="approve_form" >
        <input type="hidden" name="w" id="approve_w" value="a" >
        <input type="hidden" name="po_id" id="po_id" value="" >
        <div class="tbl_frm01 tbl_wrap">
          <table>
            <tr>
              <th>처리승인</th>
              <td>
                <label><input type="radio" name ="po_rel_action" id="po_rel_action1" value="처리완료" > 처리</label>
                <label><input type="radio" name ="po_rel_action" id="po_rel_action2" value="반려"> 반려</label>
              </td>
            </tr>
            <tr>
              <th>사유</th>
              <td>
                <input type="text" class="full_input" name="po_content_approve" size="100" />
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div class="pull-right">
                  <button type="button" class="btn btn-primary" id="saveMoney_approve_save" >저장</button>
                </div>
              </td>
            </tr>
          </table>
        </div>
        </form>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="handwriting_modal_apply" tabindex="-1" role="dialog" aria-labelledby="handwriting_modal_apply">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">수기지급 요청하기</h4>

      </div>
      <div class="modal-body" >
        <div class="row">
          <div class="x_title">
            <h4><span class="fa fa-check-square"></span> 조회<small></small></h4>
        		<label class="nav navbar-right"></label>
        		<div class="clearfix"></div>
      	  </div>
          <div class="tbl_frm01 tbl_wrap">
            <table>

              <tr>
                <th>
                  회원명 및 ID
                </th>
                <td>
                  <select name="coupon_sel_product_type" id="coupon_sel_product_type" >
                      <option value="mb_id" >아이디</option>
                      <option value="mb_name" >이름</option>
                  </select>
                  <input type="text" name="searchValue"  value="" id="searchValue" class="frm_input" >
                </td>
              </tr>
              <tr>
                <td colspan="4" style="text-align:right;">
                  <input type="button" class="btn btn-primary" value="검색" id="btnSearch" />
                </td>
              </tr>
            </table>
      	  </div>
        </div>
        <form name="upload_form" method="post" id="upload_form" action="./configform_saveMoney_handwriting_update.php" >
        <input type="hidden" name="w" value="" >
        <div class="row">

          <div class="tbl_head01 tbl_wrap">
            <table id="test2">
            <caption>목록</caption>
            <thead>
            <tr>
              <th scope="col">
              	<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
              </th>
              <th scope="col">아이디</th>
              <th scope="col">성명</th>
              <th scope="col">휴대전화번호</th>
            </tr>
            </thead>
            <tbody id="mb_point_list">
            </tbody>
            </table>
          </div>
      </div>

      <div class="modal-footer">
      	<div class="tbl_head01 tbl_wrap">
            <table id="test2">
            <tbody>
              <tr>
                <th rowspan="2">적립금 설정</th>
                <th>증감여부</th>
                <th>적립금 금액</th>
                <th>사용유효기간</th>
                <th>내용</th>
              </tr>
              <tr>
                <td>
                  <select class="frm_input" name="point_type" >
                    <option value="p" selected="selected">(+)적립금증액</option>
                    <option value="m">(-)적립금차감</option>
                  </select>
                </td>
                <td><input type="text" class="frm_input" name="po_point" id="po_point" required="required" /></td>
                <td>발행일로부터 <input type="number" max="365" class="frm_input" style="width:50px;" name="po_expire_date" id="po_expire_date" required="required"/> 일까지</td>
                <td><input type="text" class="frm_input" name="po_content" id="po_content" required="required" /></td>
              </tr>
            </tbody>
            </table>
        </div>

        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">취소</button>
        <button type="button" class="btn btn-primary" id="saveMoney_apply" >요청하기</button>
      </div>
      </form>

    </div>
  </div>
</div>


<script type="text/html" id="saveMoney-template">
<tr>
    <td>
        <input type="hidden" name="mb_id[%%i%%]" value="%%mb_id%%">
        <input type="checkbox" id="chk_%%i%%" name="chk[]" value="%%i%%" title="내역선택">
    </td>
    <td>%%mb_id%%</td>
    <td>%%mb_name%%</td>
    <td>%%mb_hp%%</td>
</tr>
</script>

<script type="text/javascript">
$(function(){

	$("#searchValue").keyup(function(e){if(e.keyCode == 13) $("#btnSearch").click(); });

	$("#btnSearch").click(function(){
	    if($("#searchValue").val() == "") {
	        alert("검색어를 입력하세요.");
	    	$("#searchValue").focus();
	    	return;
	    }

		$targetSel = $("#mb_point_list");

	    $.post(
	            "ajax.configform_sms_sender_search.php",
	            { sfl: $("#coupon_sel_product_type").val(), stx: $("#searchValue").val() },
	            function(data) {
	                //alert(data);
	            	var responseJSON = JSON.parse(data);
	            	var count = responseJSON.length;
	            	$targetSel.empty();

	            	if(count == 0) {
	            		$targetSel.append($('<tr><class="empty_table">자료가 없습니다.</td></tr>'));
	                	return;
	            	}

					for(i=0; i<count; i++) {

						var mb_id = responseJSON[i]['mb_id'];
						var mb_hp = responseJSON[i]['mb_hp'];
						var mb_name = responseJSON[i]['mb_name'];
						var template = $('#saveMoney-template').text();

      					  template = template.replace('%%i%%', i);
    					  template = template.replace('%%i%%', i);
    					  template = template.replace('%%i%%', i);
    					  template = template.replace('%%mb_id%%', mb_id);
    					  template = template.replace('%%mb_id%%', mb_id);
    					  template = template.replace('%%mb_name%%', mb_name);
    					  template = template.replace('%%mb_hp%%', mb_hp);

    					  template = $(template);
    					  template.prop('id', 'tr' + mb_id);

    					  $targetSel.prepend(template);

						//$option =$('<option>', {value:responseJSON[i]['mb_hp'], text: responseJSON[i]['mb_name']+"("+responseJSON[i]['mb_hp']+")"})
						//$option.attr("mb_name", responseJSON[i]['mb_name']);
						//$option.attr("data", responseJSON[i]['mb_id']);
						//$targetSel.append($option);
	   				}


	            }
	        );

	});

	$("#saveMoney_apply").click(function(){

		if(document.getElementById('po_point').value == ''){
			alert("적급금액과 내용을 확인해주세요.");
			return false;
		}

		if(document.getElementById('po_expire_date').value == '' && document.getElementById('po_expire_date').value > 365){
			alert("사용유효기간을 확인해주세요.");
			return false;
		}

		if(document.getElementById('po_content').value == ''){
			alert("적급금액과 내용을 확인해주세요.");
			return false;
		}

	    if (!is_checked("chk[]")) {
	        alert("수기지급 요청하실 항목을 하나 이상 선택하세요.");
	        return false;
	    }

		var form = $('#upload_form')[0];
	    var data = new FormData(form);

		$("#saveMoney_apply").prop("disabled", true);
		$.ajax({
	 		 type:"POST",
	 		 url:"./configform_saveMoney_handwriting_update.php",
	 		 data : data,
             processData: false,
             contentType: false,
             cache: false,
             timeout: 600000,
	 		 success : function(res) {
			 	console.log(res);
	  			$("#saveMoney_apply").prop("disabled", false);

	 			var responseJSON = JSON.parse(res);

				if(responseJSON.result == "S") {
					alert(responseJSON.alertMsg);

	 			} else {
	 				alert(responseJSON.alertMsg);
	 			}

				$("#handwriting_modal_apply").modal('hide');
				$("#fsearch").submit();
				return true;
	 		 },
	 		 error : function(request,status,error){
	 			$("#saveMoney_apply").removeAttr("disabled");
	 			alert('');
	 			return false;
	 		 }
	 	 });

	});


});



</script>


<?php
include_once ('../admin.tail.php');
?>
