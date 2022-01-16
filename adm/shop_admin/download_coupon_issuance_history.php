<?php
$sub_menu = "200180";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

if(!$cz_id)
    alert('잘못된 접근입니다.');

$sql = "select * from lt_shop_coupon_zone where cz_id={$cz_id} ";
$cmg = sql_fetch($sql);
    
$sql_common = " from  lt_shop_coupon as a
                  left outer join lt_shop_coupon_log as c
                    on a.cp_id = c.cp_id
                  left outer join lt_member as b
                    on b.mb_id = (case when c.cl_id is not null then c.mb_id else a.mb_id end) ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " (b.mb_id = '{$stx}') ";
            break;
        case 'mb_name' :
            $sql_search .= " (b.mb_name = '{$stx}') ";
            break;
        case 'admin_id' :
            $sql_search .= " (admin_id = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}
if (isset($coupon_issuance_type) && $coupon_issuance_type == '0') {
    $sql_search .= 'and c.cl_id is null ';
} else if (isset($coupon_issuance_type) && $coupon_issuance_type == '1') {
    $sql_search .= 'and c.cl_id is not null ';
}

if ($po_datetime != "") {
    $po_datetimes = explode("~", $po_datetime);
    $fr_date = trim($po_datetimes[0]);
    $to_date = trim($po_datetimes[1]);
    
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
    if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';
    
    if ($fr_date && $to_date) {
        $sql_search .= " and cp_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
    }
}


$sql_search .= " and cz_id={$cz_id} ";

if (!$sst) {
    $sst  = "cz_id";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.cp_method, a.cp_id, c.cl_id, c.od_id, c.cl_datetime,a.cp_datetime,b.mb_id,b.mb_name,cp_start,cp_end,if(c.cl_id is null,'미사용','사용') as str_cl_id
                ,concat(' ',c.od_id) as str_od_id
        {$sql_common}
        {$sql_search}
        {$sql_order}
        limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$excel_sql = $sql;
if(substr_count($sql, "limit")){
    $sqls = explode('limit', $sql);
    $excel_sql = $sqls[0];
}
$headers = array('NO', '회원아이디', '고객명','발급일자', '사용가능 시작일', '사용가능 종료일', '사용일자', '주문번호', '사용여부');
$bodys = array('NO', 'mb_id', 'mb_name','cp_datetime', 'cp_start', 'cp_end', 'cl_datetime', 'str_od_id', 'str_cl_id');

$enc = new str_encrypt();

$excel_sql = $enc->encrypt($excel_sql);
$headers = $enc->encrypt(json_encode_raw($headers));
$bodys = $enc->encrypt(json_encode_raw($bodys));


$g5['title'] = '쿠폰발급 내역관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$colspan = 9;

$qstr = $qstr."&amp;cz_id=".$cz_id."&amp;coupon_issuance_type=".$coupon_issuance_type."&amp;po_datetime=".$po_datetime;
?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  	<div class="x_panel">

  	  <div class="x_title">
  		<h4><span class="fa fa-check-square"></span> 쿠폰정보<small></small></h4>
  		<label class="nav navbar-right"></label>
  		<div class="clearfix"></div>
  	  </div>

  	  <div class="tbl_frm01 tbl_wrap">
        <table>

         <tr scope = 'row'>
            <th>쿠폰명</th>
            <td colspan="3">
              <?php echo $cmg['cz_subject']?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>쿠폰설명</th>
            <td colspan="3">
              <?php echo $cmg['cz_desc']?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>혜택구분</th>
            <td colspan="3">
              <?php 
                if ($cmg['cp_point_coupon_check']) {
                  echo '포인트 : '.number_format($cmg['cp_point_coupon_amount']).'포인트';
                } else if ($cmg['cp_type'] = 0){
                  echo '할인금액 : '.number_format($cmg['cp_price']).'원';
                } else if ($cmg['cp_type'] = 1){ 
                  echo '할인율 : '.$cmg['cp_price'].'%';
                  echo ', 절사단위 : '.number_format($cmg['cp_trunc']).'원단위';
                  echo ', 최소주문금액 : '.number_format($cmg['cp_minimum']).'원';
                  echo ', 최대금액 : '.number_format($cmg['cp_maximum']).'원';
                }
                ?>
            </td>
          </tr>

          <tr scope = 'row'>
            <th>사용기간</th>
            <td colspan="3">
               <?php 
                    if ($cmg['cz_period'] == 0) echo "기간 제한 없음";
                    else {
                        echo "발급일로부터 ".$cmg['cz_period']."일 이내";
                        /*
                        $date=date_create(G5_TIME_YMDHIS);
                        
                        echo " ( ".date_format($date,"Y-m-d H:i")." ~ ";
                        
                        date_add($date, date_interval_create_from_date_string($cmg['cm_end_time'].' days'));
                        echo date_format($date,"Y-m-d H:i")." )";
                        */
                    }
                ?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>적용대상</th>
            <td colspan="3">
              <?php 
              if ($cmg['cp_method'] == '0') echo "상품쿠폰";
              else if ($cmg['cp_method'] == '31') echo "포인트쿠폰";
              else echo "주문서쿠폰";
              ?>
            </td>
          </tr>



        </table>
  	  </div>
  	</div>

    <div class="x_panel">
      <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 쿠폰 관리<small></small></h4>

        <div class="clearfix"></div>
      </div>

	  <form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
	  <input type="hidden" name="cz_id" value="<?php echo $cz_id;?>">
	  
      <div class="tbl_frm01 tbl_wrap">
        <table>
          <tbody>
            <tr>
              <th scope="col">사용여부</th>
              <td >
                <select name="coupon_issuance_type" id="coupon_issuance_type" >
                    <option value="" >전체</option>
                    <option value="1" <?php echo get_selected($coupon_issuance_type, '1')?> >사용</option>
                    <option value="0" <?php echo get_selected($coupon_issuance_type, '0')?>>미사용</option>
                </select>
              </td>
              <th scope="col">검색대상</th>
              <td>
                <select name="sfl" title="검색대상">
                    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
                    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>회원이름</option>
                    <option value="admin_id"<?php echo get_selected($_GET['sfl'], "admin_id"); ?>>발급자ID</option>
                </select>
                <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class=" frm_input">

              </td>
            </tr>
            <tr>
              <th scope="row">검색기간</th>
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
          </tbody>
            <tr>
              <td colspan="4" style="text-align:right;">
                <input type="submit" class="btn btn-primary" id="coupon_btn_update" value="검색"></input>
              </td>
            </tr>
            <tr>
              <td colspan="4" style="text-align:left;">
              회원검색 후 “삭제”버튼을 클릭하는 경우, 고객에게 발급된 쿠폰이 삭제되며, 이미 사용된 쿠폰은 삭제되지 않습니다.
              </td>
            </tr>
        </table>
  	  </div>
	</form>
    


<form name="fcouponlist" id="fcouponlist" method="post" action="./couponlist_delete.php" onsubmit="return fcouponlist_submit(this);">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="cz_id" value="<?php echo $cz_id; ?>">
<input type="hidden" name="po_datetimes" value="<?php echo $po_datetimes; ?>">
<input type="hidden" name="token" value="">   
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $g5['title']; ?></caption>
        <thead>
        <tr>
          <th colspan="11">
            <div class="pull-right">
            	<input type="button" class="btn btn_02" id="excel_download1" value="엑셀다운로드"></input>
              	<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn_02">
            </div>
          </th>
        </tr>
        <tr>
            <th scope="col">
                <label for="chkall" class="sound_only">쿠폰 전체</label>
                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
            </th>
            <th scope="col"><?php echo subject_sort_link('mb_id') ?>회원아이디</a></th>
            <th scope="col">고객명</th>
            <th scope="col">발급일자</th>
            <th scope="col">사용가능기간</th>            
            <th scope="col">사용일자</th>
            <th scope="col">주문번호</th>
            <th scope="col">사용여부</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
    
            $link1 = '<a href="'.G5_ADMIN_URL.'/shop_admin/orderform.php?od_id='.$row['od_id'].'">';
            $link2 = '</a>';
    
            $bg = 'bg'.($i%2);
        ?>
    
        <tr class="<?php echo $bg; ?>">
            <td class="td_chk">
                <input type="hidden" id="cp_id_<?php echo $i; ?>" name="cp_id[<?php echo $i; ?>]" value="<?php echo $row['cp_id']; ?>">
                <input type="checkbox" id="chk_<?php echo $i; ?>" name="chk[]" value="<?php echo $i; ?>" title="내역선택" <?php echo ($row['cl_id'])?'disabled':''; ?>>
            </td>
            <td class="td_name sv_use"><div><?php echo $row['mb_id']; ?></div></td>
            <td class="td_name sv_use"><div><?php echo $row['mb_name']; ?></div></td>
            <td class="td_name sv_use"><div><?php echo $row['cp_datetime']; ?></div></td>
            
            <td class="td_name sv_use"><div>
            <?php 
              if ($row['cp_method'] == 31) echo "";
              else {
                echo $row['cp_start'].'~'.$row['cp_end']; 
              }
            ?></div></td>
            
            <td class="td_name sv_use"><div><?php echo $row['cl_datetime']; ?></div></td>
            <td class="td_name sv_use"><div>
              <?php 
                if ($row['cp_method'] == 31) echo "";
                else {
                  echo $link1.$row['od_id'].$link2;
                }
              ?>
            </div></td>
            
            <td class="td_cntsmall"><?php echo ($row['cl_id'])?'사용':'미사용'; ?></td>
        </tr>
    
        <?php
        }
    
        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        <thead>
          <tr>
            <th colspan="11">
              <div class="pull-right">
              	<input type="button" class="btn btn_02" id="excel_download2" value="엑셀다운로드"></input>
              	<input type="submit" name="act_button" value="삭제" onclick="document.pressed=this.value" class="btn btn_02">
              </div>
            </th>
          </tr>
        </thead>
        </table>
    </div>
              <div class="pull-right">
       <a href="./configform_coupon_list.php" class="btn btn_02">목록</a>
    </div>
    
    </form>
    
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
	
// $('#coupon_btn_delete_1, #coupon_btn_delete_2').click(function(){
//   var check_cnt = 0;
//   $('input[name=coupon_checkbox]:checked').each(function() {
//       check_cnt +=1;
//   });

//   if(check_cnt > 0){
//     var result = confirm('선택한 쿠폰을 삭제하시겠습니까?');
//     if(result) {
//       alert('발급된 쿠폰이 삭제되었습니다.');
//     }
//   }else {
//     alert('선택된 상품이 없습니다.');
//   }
// });


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

  $('#coupon_checkbox_all').click(function(){
    var check = $(this).is(":checked");

    if(check){
      $("input[name=coupon_checkbox]").prop('checked',true);
    }else {
      $("input[name=coupon_checkbox]").prop('checked',false);
    }
  });
});

function fcouponlist_submit(f)
{
	if(!confirm('발급된 쿠폰을 삭제 하시겠습니까?')) return false;
	
    return true;
}
</script>




<!-- @END@ 내용부분 끝 -->

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>