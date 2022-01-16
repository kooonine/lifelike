<?php
$sub_menu = "900110";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = '문의정보';

$qaconfig = get_qa_config();

$sql_common = " from {$g5['qa_content_table']} ";
$sql_search = " where qa_type = '0' ";
$sql_search .= " and mb_id = '{$mb_id}' ";

if ($search_date != "") {
    $search_dates = explode("~", $search_date);
    $fr_date = trim($search_dates[0]);
    $to_date = trim($search_dates[1]);
}

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';
if ($fr_date && $to_date) {
    $sql_search .= " and qa_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}


if($sca) {
    if (preg_match("/[a-zA-Z]/", $sca))
        $sql_search .= " and INSTR(LOWER(qa_category), LOWER('$sca')) > 0 ";
        else
            $sql_search .= " and INSTR(qa_category, '$sca') > 0 ";
}

$stx = trim($stx);
if($stx) {
    if (preg_match("/[a-zA-Z]/", $stx))
        $sql_search .= " and ( INSTR(LOWER(qa_subject), LOWER('$stx')) > 0 or INSTR(LOWER(qa_content), LOWER('$stx')) > 0 or INSTR(LOWER(od_id), LOWER('$stx')) > 0 or INSTR(LOWER(it_id), LOWER('$stx')) > 0 )";
        else
            $sql_search .= " and ( INSTR(qa_subject, '$stx') > 0 or INSTR(qa_content, '$stx') > 0 or INSTR(od_id, '$stx') > 0 or INSTR(it_id, '$stx') > 0 ) ";
}
if($qa_status)
{
    $sql_search .= " and qa_status = '$qa_status' ";
}

$sql_order = " order by qa_num ";

$sql = " select count(*) as cnt
        $sql_common
        $sql_search ";
        $row = sql_fetch($sql);
        $total_count = $row['cnt'];
        
        $page_rows = $qaconfig['qa_page_rows'];
        $total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
        if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
        $from_record = ($page - 1) * $page_rows; // 시작 열을 구함
        
        $stx = get_text(stripslashes($stx));
        
        $qstr = "stx=".urlencode($stx)."&amp;search_date=".urlencode($search_date)."&amp;qa_status=".urlencode($qa_status)."&amp;sca=".urlencode($sca);
        $qstr .='&amp;w='.$w.'&amp;mb_id='.$mb_id.'&amp;mode='.$mode;
        
        $sql = " select *
        $sql_common
        $sql_search
        $sql_order
        limit $from_record, $page_rows ";
        $result = sql_query($sql);
        
        $list = array();
        $num = $total_count - ($page - 1) * $page_rows;
        $subject_len = $qaconfig['qa_subject_len'];
        
        for($i=0; $row=sql_fetch_array($result); $i++) {
            $list[$i] = $row;
            
            $list[$i]['category'] = get_text($row['qa_category']);
            $list[$i]['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
            if ($stx) {
                $list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
            }
            
            $list[$i]['view_href'] = G5_ADMIN_URL.'/community/help_detail.php?qa_id='.$row['qa_id'].$qstr;
            
            $list[$i]['icon_file'] = '';
            if(trim($row['qa_file1']) || trim($row['qa_file2']))
                $list[$i]['icon_file'] = '<img src="'.$qa_skin_url.'/img/icon_file.gif">';
                
                $list[$i]['name'] = get_text($row['qa_name']);
                // 사이드뷰 적용시
                //$list[$i]['name'] = get_sideview($row['mb_id'], $row['qa_name']);
                $list[$i]['date'] = substr($row['qa_datetime'], 2, 8);
                
                $list[$i]['num'] = $num - $i;
        }
        
        $list_pages = get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].$qstr.'&amp;page=');
        
        ?>


<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 고객문의 관리<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

    <div class="x_content">
    <form name="flist" id="flist" class="local_sch01 local_sch">
	<input type="hidden" name="page" value="<?php echo $page; ?>">
	<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
    
    <input type="hidden" name="mb_id" value="<?php echo $mb_id ?>">
    <input type="hidden" name="mode" value="<?php echo $mode ?>">
	
      <div class="tbl_frm01 tbl_wrap">
          <table>
          <caption>게시물 관리</caption>
          <colgroup>
              <col class="grid_4">
              <col>
              <col class="grid_3">
          </colgroup>
          <tbody>
          <tr>
              <th scope="row">기간설정</th>
              <td colspan="2">
            	<div class="row">
                	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    	<input type='text' class="form-control" id="search_date" name="search_date" value="" />
                    	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
                	</div>
                	<div class="btn-group col-lg-8 col-md-6 col-sm-12 col-xs-12">
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
              <th scope="row">처리상태</th>
              <td colspan="2">
              <?php if(!isset($qa_status)) $qa_status=""; ?>
                <label><input type="radio" value="" id="rdo_reply_status1" name="qa_status" <?php echo get_checked($qa_status, "")?>> 전체</label>  &nbsp;
                <label><input type="radio" value="1" id="rdo_reply_status2" name="qa_status" <?php echo get_checked($qa_status, "1")?>> 답변완료</label>  &nbsp;
                <label><input type="radio" value="0" id="rdo_reply_status3" name="qa_status" <?php echo get_checked($qa_status, "0")?>> 답변대기</label>
              </td>
          </tr>
          <tr>
              <th scope="row">문의유형</th>
              <td colspan="2">
                <select id="sca" name="sca">
                <?php 
                $category_option = '';
                $category_option .= '<option value="" '.(($sca=='')?'selected="selected"':'').'>전체</option>';
                $categories = explode('|', $qaconfig['qa_category']); // 구분자가 | 로 되어 있음
                for ($i=0; $i<count($categories); $i++) {
                    $category = trim($categories[$i]);
                    if ($category=='') continue;
                    $category_option .= '<option value="'.$category.'" '.(($sca==$category)?'selected="selected"':'').'>'.$category.'</option>';
                }
                
                echo $category_option;
                ?>
                </select>
              </td>
          </tr>
          <tr>
            <th scope="row">검색어</th>
            <td colspan="2">
              <input type="text" name="stx" value="<?php echo $stx?>" id="stx" class="required frm_input" size="50">
            </td>
          </tr>
          </tbody>
          </table>
      </div>
      <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
          <button class="btn btn_02" type="reset" id="btn_clear">초기화</button>
          <input type="submit" class="btn btn-success" value="검색" id="btn_search"></input>
        </div>
      </div>
      </form>
    </div>

    <div class="x_content" style="margin-top: 20px;" id="tab_list_table">
      <label> 검색결과 <?php echo $total_count; ?>건</label>
      <div style="float: right;">
          <select name="page_rows" onchange="$('#flist').submit();">
            <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
            <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
            <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
          </select>
      </div><br /><br />
      <div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
        <table>
        <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">문의유형</th>
          <th scope="col">주문번호</th>
          <th scope="col">상품번호</th>
          <th scope="col">문의제목</th>
          <th scope="col">처리상태</th>
          <th scope="col">처리자</th>
          <th scope="col">처리일</th>
          <th scope="col">문의자</th>
          <th scope="col">문의일</th>
        </tr>
        </thead>

        <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
            if($list[$i]['qa_status'])
            {
                $sql = " select *
                        from {$g5['qa_content_table']}
                        where qa_type = '1'
                          and qa_parent = '{$list[$i]['qa_id']}' ";
                $answer = sql_fetch($sql);
            }
        ?>
        <tr>
          <td class="td_chk"><?php echo $list[$i]['num']?></td>
          <td class="td_category2"><?php echo $list[$i]['category']; ?></td>
          <td class="td_category2"><?php echo $list[$i]['od_id']?></td>
          <td class="td_category2"><?php echo $list[$i]['it_id']?></td>
          <td class="td_left">
                <a href="<?php echo $list[$i]['view_href']; ?>" class="ellipsis" target="_blank">
                <?php echo $list[$i]['subject']; ?>
                </a>
          </td>
          <?php echo ($list[$i]['qa_status'] ? '<td class="td_auth">답변완료</td>' : '<td class="td_auth">답변대기</td>'); ?>
          <td class="td_id"><?php 
          if($answer)
          {
              echo $answer['qa_name'].'('.$answer['mb_id'].')';
          }?></td>
          <td class="td_datetime"><?php echo substr($answer['qa_datetime'],0,16); ?></td>
          <td class="td_etc"><?php echo $list[$i]['name']; ?>(<?php echo $list[$i]['mb_id']; ?>)</td>
          <td class="td_datetime"><?php echo substr($list[$i]['qa_datetime'],0,16); ?></td>
        </tr>
        <?php } ?>
        <?php if ($i == 0) { echo '<tr><td colspan="10" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
		<?php echo $list_pages;  ?>

      </div>
		</div>



	<!-- </form> -->

	</div>
  </div>
</div>


<script>
$(function() {
    //날짜 버튼

	$('#search_date').daterangepicker({
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
	   if($fr_date !='') echo "$('#search_date').val('".$fr_date." ~ ".$to_date."');";
	   else if($sc_od_time !='') echo "$('#search_date').val('".$search_date."');";
	   else echo "$('#search_date').val('');";
		?>

		//날짜 버튼
		$("button[name='dateBtn']").click(function(){
			
			var d = $(this).attr("data");
			if(d == "all") {
				$('#search_date').val("");
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
    			} else if(d == "today") {
    				startD = moment();
    				endD = moment();
    			}
    
    			$('#search_date').data('daterangepicker').setStartDate(startD);
    			$('#search_date').data('daterangepicker').setEndDate(endD);
			}
		
		});
});

function fhelplist_submit(f)
{

}
</script>




<!-- @END@ 내용부분 끝 -->

