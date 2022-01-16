<?php
$sub_menu = "200320";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super' && $is_admin != 'admin')
    alert('최고관리자만 접근 가능합니다.');

$sql_common = " from {$g5['point_table']} a
                    inner join {$g5['member_table']} as b  on a.mb_id = b.mb_id
                    left outer join {$g5['member_table']} as c  on a.po_request_id = c.mb_id
                    left outer join {$g5['member_table']} as d  on a.po_approve_id = d.mb_id";

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " (a.mb_id = '{$stx}') ";
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

$sql = " select a.*, b.mb_name, c.mb_name as po_request_name, d.mb_name as po_approve_name
                , if(po_point >= 0,po_point,'') po_point_p
                , if(po_point < 0,po_point,'') po_point_m
        {$sql_common}
        {$sql_search}
        {$sql_order}
        limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '적립금 관리';
include_once ('../admin.head.php');

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
$headers = array('NO', '일자', '아이디','성명', '증가', '차감', '잔액', '요청자', '요청자명','처리자','처리자명','내용');
$bodys = array('NO', 'po_datetime', 'mb_id','mb_name', 'po_point_p', 'po_point_m', 'po_mb_point', 'po_request_id', 'po_request_name', 'po_approve_id','po_approve_name','po_content');
        	  
$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  
  	<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
  	<div class="x_panel">

      <div class="x_title">
      <h4><span class="fa fa-check-square"></span> 적립금 조회<small></small></h4>
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
          </tr>

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
    	<caption><?php echo $g5['title']; ?> 목록</caption>
          <thead>
            <tr>
              <th colspan="9">
                <div class="pull-right">
                  <button type="button" class="btn btn-sm btn-default" id="management_btn_apply_1">적립금 일괄조정</button>
                  <button type="button" class="btn btn-sm btn-default" id="excel_download1">Excel 다운로드</button>
                </div>
              </th>
            </tr>
          <tr>
            <th rowspan="2">일자</th>
            <th rowspan="2">아이디</th>
            <th colspan="3">적립금 내역</th>
            <th colspan="2">지급 처리자</th>
            <th rowspan="2">내용</th>
          </tr>
          <tr>
            <th >증가</th>
            <th >차감</th>
            <th >잔액</th>
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
              <td class="td_left"><a href="?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_name'].'('.$row['mb_id'].')'  ?></a></td>              
        	  <td class="td_num td_pt"><?php echo ($row['po_point'] >= 0)?number_format($row['po_point']):"" ?></td>           
        	  <td class="td_num td_pt"><?php echo ($row['po_point'] < 0)?number_format($row['po_point']):"" ?></td>
              <td class="td_num td_pt"><?php echo number_format($row['po_mb_point']) ?></td>
              <td><?php echo ($row['po_request_id'] != '')?$row['po_request_name'].'('.$row['po_request_id'].')':'' ?></td>
              <td><?php echo ($row['po_approve_id'] != '')?$row['po_approve_name'].'('.$row['po_approve_id'].')':'' ?></td>
        	  <td class="td_left"><?php echo $link1 ?><?php echo $row['po_content'] ?><?php echo $link2 ?></td>
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
                  <button type="button" class="btn btn-sm btn-default" id="management_btn_apply_1">적립금 일괄조정</button>
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
	
    $('#management_btn_apply_1, #management_btn_apply_2').click(function(){
    	$('#management_modal_apply').modal('toggle');
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
  
$(function(){


});

function fconfigform_submit(f)
{
    f.action = "./configform_delivery_update.php";
    return true;
}
</script>


<!-- @END@ 내용부분 끝 -->
<div class="modal fade" id="management_modal_apply" tabindex="-1" role="dialog" aria-labelledby="management_modal_apply" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Popup - 적립금 일괄 조정</h4>
      </div>
      
	  <form name="upload_form" method="post" enctype="multipart/form-data" id="upload_form" action="./configform_saveMoney_upload.php" >
      <div class="modal-body" >
        <div class="row">
          <div class="x_title">
            <h4><span class="fa fa-check-square"></span> 양식 다운로드<small></small></h4>
        		<label class="nav navbar-right"></label>
        		<div class="clearfix"></div>
      	  </div>      	  
          <div class="tbl_frm01 tbl_wrap">
            <table>

              <tr>
                <th>
                  엑셀 양식 다운로드
                </th>
                <td>
                	<a href="./point_excel_bundle_sample.csv" ><button type="button" name="searchValue"  value="" id="searchValue" class="btn btn-default frm_input" >양식 다운로드</button></a>
                </td>
              </tr>
            </table>
      	  </div>
        </div>
        <div class="row">
          <div class="x_title">
            <h4><span class="fa fa-check-square"></span> 양식 업로드<small></small></h4>
        		<label class="nav navbar-right"></label>
        		<div class="clearfix"></div>
      	  </div>
          <div class="tbl_head01 tbl_wrap">
            <table id="test2">
            <caption>목록</caption>
            <thead>
              <tr>
                <th>엑셀파일 등록</th>
                <th colspan="5">
                  <input type="file" name="csv" id="csv" onchange="" required="required" accept=".csv,.xls">
                </th>
              </tr>
            </thead>
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
      </div>

      <div class="modal-footer">
        <br><br><br>
        <button type="button" class="btn btn-default" id="btn_upload" >엑셀업로드</button>
        <button type="button" class="btn btn-primary"  data-dismiss="modal">취소</button>
      </div>
    </div>
    </form>
  </div>
</div>
<script>

$(function(){
	$("#btn_upload").click(function(){

		if(document.getElementById('csv').value == ''){
			alert("업로드 엑셀파일을 확인해주세요.");
			return false;
		}

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
	    var f = document.upload_form;

	    f.action = 'configform_saveMoney_upload.php';
	    
	    (function($){
	        if(!document.getElementById("fileupload_fr")){
	            var i = document.createElement('iframe');
	            i.setAttribute('id', 'fileupload_fr');
	            i.setAttribute('name', 'fileupload_fr');
	            i.style.display = 'none';
	            document.body.appendChild(i);
	        }
	        f.target = 'fileupload_fr';
	        f.submit();
	    })(jQuery);
	    
	    return false;
	});
});

function management_modal_apply_close(){
	$("#management_modal_apply").modal('hide');
}
</script>
<?php
include_once ('../admin.tail.php');
?>
