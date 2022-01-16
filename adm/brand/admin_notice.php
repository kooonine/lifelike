<?php
$_REQUEST['bo_table'] = "mallnotice";
$sub_menu = "92";

include_once('./_common.php');
include_once(G5_EDITOR_LIB);

$g5['title'] = '공지사항';
include_once ('../admin.head.php');

$colspan = 15;

if (!$board['bo_table']) {
    alert('존재하지 않는 게시판입니다.', './');
}

$sop = strtolower($sop);
if ($sop != 'and' && $sop != 'or')
    $sop = 'and';
    
// 분류 선택 또는 검색어가 있다면
$stx = trim($stx);
//검색인지 아닌지 구분하는 변수 초기화
$is_search_bbs = false;
    
if ($sca || $stx || $stx === '0') {     //검색이면
    $is_search_bbs = true;      //검색구분변수 true 지정
    $sql_search = get_sql_search($sca, $sfl, $stx, $sop);
    
    // 가장 작은 번호를 얻어서 변수에 저장 (하단의 페이징에서 사용)
    $sql = " select MIN(wr_num) as min_wr_num from {$write_table} ";
    $row = sql_fetch($sql);
    $min_spt = (int)$row['min_wr_num'];
    
    if (!$spt) $spt = $min_spt;
    
    $sql_search .= " and (wr_num between {$spt} and ({$spt} + {$config['cf_search_part']})) ";
    
    // 원글만 얻는다. (코멘트의 내용도 검색하기 위함)
    // 라엘님 제안 코드로 대체 http://sir.kr/g5_bug/2922
    $sql = " SELECT COUNT(DISTINCT `wr_parent`) AS `cnt` FROM {$write_table} WHERE {$sql_search} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
    /*
     $sql = " select distinct wr_parent from {$write_table} where {$sql_search} ";
     $result = sql_query($sql);
     $total_count = sql_num_rows($result);
     */
} else {
    $sql_search = "";
    
    $total_count = $board['bo_count_write'];
}

if ($search_date != "") {
    $search_dates = explode("~", $search_date);
    $fr_date = trim($search_dates[0]);
    $to_date = trim($search_dates[1]);
}

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';
if ($fr_date && $to_date) {
    $sql_search .= " and wr_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

$page_rows = $board['bo_page_rows'];
$list_page_rows = $board['bo_page_rows'];

if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)


// 년도 2자리
$today2 = G5_TIME_YMD;

$list = array();
$i = 0;

$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

// 관리자라면 CheckBox 보임
$is_checkbox = false;
//if ($is_member && ($is_admin == 'super' || $group['gr_admin'] == $member['mb_id'] || $board['bo_admin'] == $member['mb_id']))
//    $is_checkbox = true;
    
// 정렬에 사용하는 QUERY_STRING
$qstr2 = 'bo_table='.$bo_table.'&amp;sop='.$sop;

// 0 으로 나눌시 오류를 방지하기 위하여 값이 없으면 1 로 설정
$bo_gallery_cols = $board['bo_gallery_cols'] ? $board['bo_gallery_cols'] : 1;
$td_width = (int)(100 / $bo_gallery_cols);

// 정렬
// 인덱스 필드가 아니면 정렬에 사용하지 않음
//if (!$sst || ($sst && !(strstr($sst, 'wr_id') || strstr($sst, "wr_datetime")))) {
if (!$sst) {
    if ($board['bo_sort_field']) {
        $sst = $board['bo_sort_field'];
    } else {
        $sst  = "wr_num, wr_reply";
        $sod = "";
    }
} else {
    // 게시물 리스트의 정렬 대상 필드가 아니라면 공백으로 (nasca 님 09.06.16)
    // 리스트에서 다른 필드로 정렬을 하려면 아래의 코드에 해당 필드를 추가하세요.
    // $sst = preg_match("/^(wr_subject|wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
    $sst = preg_match("/^(wr_datetime|wr_hit|wr_good|wr_nogood)$/i", $sst) ? $sst : "";
}

if(!$sst)
    $sst  = "wr_num, wr_reply";

if ($sst) {
    $sql_order = " order by {$sst} {$sod} ";
}

if ($is_search_bbs) {
    $sql = " select distinct wr_parent from {$write_table} where {$sql_search} {$sql_order} limit {$from_record}, $page_rows ";
} else {
    $sql = " select * from {$write_table} where wr_is_comment = 0 ";
    if(!empty($notice_array))
        $sql .= " and wr_id not in (".implode(', ', $notice_array).") ";
        $sql .= " {$sql_order} limit {$from_record}, $page_rows ";
}

// 페이지의 공지개수가 목록수 보다 작을 때만 실행
if($page_rows > 0) {
    $result = sql_query($sql);
    
    $k = 0;
    
    while ($row = sql_fetch_array($result))
    {
        // 검색일 경우 wr_id만 얻었으므로 다시 한행을 얻는다
        if ($is_search_bbs)
            $row = sql_fetch(" select * from {$write_table} where wr_id = '{$row['wr_parent']}' ");
            
            $list[$i] = get_list($row, $board, $board_skin_url, 200);
            if (strstr($sfl, 'subject')) {
                $list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
            }
            $list[$i]['is_notice'] = false;
            $list_num = $total_count - ($page - 1) * $list_page_rows - $notice_count;
            $list[$i]['num'] = $list_num - $k;
            
            $i++;
            $k++;
    }
}

$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './admin_notice.php?bo_table='.$bo_table.$qstr.'&amp;page=');

$list_href = '';
$prev_part_href = '';
$next_part_href = '';
if ($is_search_bbs) {
    $list_href = './admin_notice.php?bo_table='.$bo_table;
    
    $patterns = array('#&amp;page=[0-9]*#', '#&amp;spt=[0-9\-]*#');
    
    //if ($prev_spt >= $min_spt)
    $prev_spt = $spt - $config['cf_search_part'];
    if (isset($min_spt) && $prev_spt >= $min_spt) {
        $qstr1 = preg_replace($patterns, '', $qstr);
        $prev_part_href = './admin_notice.php?bo_table='.$bo_table.$qstr1.'&amp;spt='.$prev_spt.'&amp;page=1';
        $write_pages = page_insertbefore($write_pages, '<a href="'.$prev_part_href.'" class="pg_page pg_prev">이전검색</a>');
    }
    
    $next_spt = $spt + $config['cf_search_part'];
    if ($next_spt < 0) {
        $qstr1 = preg_replace($patterns, '', $qstr);
        $next_part_href = './admin_notice.php?bo_table='.$bo_table.$qstr1.'&amp;spt='.$next_spt.'&amp;page=1';
        $write_pages = page_insertafter($write_pages, '<a href="'.$next_part_href.'" class="pg_page pg_end">다음검색</a>');
    }
}


$write_href = '';
if ($member['mb_level'] >= $board['bo_write_level']) {
    $write_href = './board_create.php?bo_table='.$bo_table;
}

$nobr_begin = $nobr_end = "";
if (preg_match("/gecko|firefox/i", $_SERVER['HTTP_USER_AGENT'])) {
    $nobr_begin = '<nobr>';
    $nobr_end   = '</nobr>';
}
$stx = get_text(stripslashes($stx));

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 7;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

?>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> <?php echo $board['bo_subject'] ?> <small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>
	  
    <div class="x_content">
    
    <form name="flist" id="flist" class="local_sch">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
    
	  <div class="tbl_frm01 tbl_wrap">
          <table>
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
            <th scope="row">검색어</th>
            <td colspan="2">
            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              	<input type="text" name="stx" value="<?php echo $stx?>" id="stx" class="required frm_input" size="50">
              	</div>
            </td>
          </tr>
          </tbody>
          </table>
      </div>
      <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
          <a href="./admin_notice_post_create.php?bo_table=mallnotice"><button class="btn btn_02" type="button" id="btn_clear">공지사항 등록</button></a>
          <input type="submit" class="btn btn-success" value="검색" id="btn_search"></input>
        </div>
      </div>
      
	  <label> 검색결과 <?php echo number_format($total_count) ?>건 <?php echo $page ?> 페이지</label>
	  
      <div style="float: right;">
          <select name="page_rows" onchange="$('#flist').submit();">
            <option value="10" <?php echo get_selected($page_rows, '10') ; ?> >10개씩 보기</option>
            <option value="20" <?php echo get_selected($page_rows, '20') ; ?> >20개씩 보기</option>
            <option value="30" <?php echo get_selected($page_rows, '30') ; ?> >30개씩 보기</option>
          </select>
      </div><br /><br />
      
      </form>
	  
	  <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $board['bo_subject'] ?> 목록</caption>
        <thead>
        <tr>
            <?php if ($is_checkbox) { ?>
            <th scope="col">
                <label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            </th>
            <?php } ?>
            <th scope="col">번호</th>
            <th scope="col">구분</th>
            <th scope="col">제목</th>
            <th scope="col">작성자</th>
            <th scope="col">조회</th>
            <?php if ($is_good) { ?><th scope="col"><?php echo subject_sort_link('wr_good', $qstr2, 1) ?>추천 <i class="fa fa-sort" aria-hidden="true"></i></a></th><?php } ?>
            <?php if ($is_nogood) { ?><th scope="col"><?php echo subject_sort_link('wr_nogood', $qstr2, 1) ?>비추천 <i class="fa fa-sort" aria-hidden="true"></i></a></th><?php } ?>
            <th scope="col">작성일</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
         ?>
        <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?>">
            <?php if ($is_checkbox) { ?>
            <td class="td_chk">
                <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            </td>
            <?php } ?>
            <td class="td_num">
            <?php
            if ($list[$i]['is_notice']) // 공지사항
                echo '<strong class="notice_icon"><i class="fa fa-bullhorn" aria-hidden="true"></i><span class="sound_only">공지</span></strong>';
            else if ($wr_id == $list[$i]['wr_id'])
                echo "<span class=\"bo_current\">열람중</span>";
            else
                echo $list[$i]['num'];
             ?>
            </td>
            <td class="td_num">
            <?php
                if ($list[$i]['wr_1'] == '0') echo '비공개';
                else echo '공개';
            ?>
            </td>

            <td class="td_subject" style="text-align:left; padding-left:<?php echo $list[$i]['reply'] ? (strlen($list[$i]['wr_reply'])*10) : '0'; ?>px">
                <?php
                if ($is_category && $list[$i]['ca_name']) {
                 ?>
                <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a>
                <?php } ?>
                <div class="bo_tit">
                    
                    <a href="<?php echo './admin_notice_post_create.php?w=u&bo_table='.$bo_table.'&wr_id='.$list[$i]['wr_id'] ?>">
                        <?php echo $list[$i]['icon_reply'] ?>
                        <?php
                            if (isset($list[$i]['icon_secret'])) echo rtrim($list[$i]['icon_secret']);
                         ?>
                        <?php echo $list[$i]['subject'] ?>
                       
                    </a>
                    <?php
                    // if ($list[$i]['file']['count']) { echo '<'.$list[$i]['file']['count'].'>'; }
                    if (isset($list[$i]['icon_file'])) echo rtrim($list[$i]['icon_file']);
                    if (isset($list[$i]['icon_link'])) echo rtrim($list[$i]['icon_link']);
                    if (isset($list[$i]['icon_new'])) echo rtrim($list[$i]['icon_new']);
                    if (isset($list[$i]['icon_hot'])) echo rtrim($list[$i]['icon_hot']);
                    ?>
                    <?php if ($list[$i]['comment_cnt']) { ?><span class="sound_only">댓글</span><span class="cnt_cmt">+ <?php echo $list[$i]['wr_comment']; ?></span><span class="sound_only">개</span><?php } ?>
                </div>

            </td>
            <td class="td_name sv_use"><?php echo $list[$i]['name'].'('.$list[$i]['mb_id'].')' ?></td>
            <td class="td_num"><?php echo $list[$i]['wr_hit'] ?></td>
            <?php if ($is_good) { ?><td class="td_num"><?php echo $list[$i]['wr_good'] ?></td><?php } ?>
            <?php if ($is_nogood) { ?><td class="td_num"><?php echo $list[$i]['wr_nogood'] ?></td><?php } ?>
            <td class="td_datetime"><?php echo $list[$i]['datetime2'] ?></td>
            
        </tr>
        <?php } ?>
        <?php if (count($list) == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>
	  

	</div>

  </div>
 </div>  
</div>

<script>

$(function(){

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
        window.addEventListener("keydown", (e) => {
            if (e.keyCode == 13) {
                document.getElementById('flist').submit();
            }
        })
});



</script>




<!-- @END@ 내용부분 끝 -->

<?php
include_once ('../admin.tail.php');
?>