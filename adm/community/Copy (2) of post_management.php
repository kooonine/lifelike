<?php
$test = true;

$sub_menu = "";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = '게시물 관리';
include_once ('../admin.head.php');

$search_table = Array();
$table_index = 0;
$write_pages = "";
$text_stx = "";
$srows = 0;

$stx = strip_tags($stx);
$stx = get_search_string($stx); // 특수문자 제거

//if ($stx) 
{
    $stx = preg_replace('/\//', '\/', trim($stx));
    $sop = strtolower($sop);
    
    if (!$sop || !($sop == 'and' || $sop == 'or')) $sop = 'and'; // 연산자 and , or
    $srows = isset($_GET['srows']) ? (int)preg_replace('#[^0-9]#', '', $_GET['srows']) : 10;
    if (!$srows) $srows = 10; // 한페이지에 출력하는 검색 행수

    $g5_search['tables'] = Array();
    $g5_search['read_level'] = Array();
    $sql = " select gr_id, bo_table, bo_read_level from {$g5['board_table']} where bo_use_search = 1 and bo_list_level <= '{$member['mb_level']}' ";
    if ($gr_id)
        $sql .= " and gr_id = '{$gr_id}' ";
    
    $onetable = isset($onetable) ? $onetable : "";
    if ($onetable) // 하나의 게시판만 검색한다면
        $sql .= " and bo_table = '{$onetable}' ";
    $sql .= " order by bo_order, gr_id, bo_table ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $g5_search['tables'][] = $row['bo_table'];
        $g5_search['read_level'][] = $row['bo_read_level'];
    }
    
    $op1 = '';
    
    // 검색어를 구분자로 나눈다. 여기서는 공백
    $s = explode(' ', strip_tags($stx));
    
    if( count($s) > 1 ){
        $s = array_slice($s, 0, 2);
        $stx = implode(' ', $s);
    }
    
    $text_stx = get_text(stripslashes($stx));
    
    $search_query = 'sfl='.urlencode($sfl).'&amp;stx='.urlencode($stx).'&amp;sop='.$sop.'&amp;search_date='.$search_date;
    
    // 검색필드를 구분자로 나눈다. 여기서는 +
    $field = explode('||', trim($sfl));
    
    if (!$search_date) {
        $search_date_s = date_create(G5_TIME_YMDHIS);
        $search_date_e = date_create(G5_TIME_YMDHIS);
        date_add($search_date_s, date_interval_create_from_date_string('-6 days'));
        
        $search_date = date_format($search_date_s,"Y-m-d").' ~ '.date_format($search_date_e,"Y-m-d");
    }
    $search_dates = explode("~", $search_date);
    $str = " (wr_datetime between '".trim($search_dates[0])." 00:00:00' and '".trim($search_dates[1])." 23:59:59') ";

    //스팸상태
    if (isset($wr_8)) {
        $search_query .='&amp;wr_8='.$wr_8;
        if ($wr_8 == '1') $str .= " and ( wr_8 = '스팸' ) ";
        elseif ($wr_8 == '0') $str .= " and ( wr_8 != '스팸' ) ";
    }
    //답변상태
    if (isset($wr_9) && $wr_9 != "") {
        $search_query .='&amp;wr_9='.$wr_9;
        $str .= " and ( wr_9 = '{$wr_9}' ) ";
    }
    //댓글여부
    if (isset($wr_comment)) {
        $search_query .='&amp;wr_comment='.$wr_comment;
        if ($wr_comment == '0') $str .= " and ( wr_comment = '0' ) ";
        elseif ($wr_comment == '1') $str .= " and ( wr_comment >= '1' ) ";
    }
    //첨부파일 여부	
    if (isset($wr_file)) {
        $search_query .='&amp;wr_file='.$wr_file;
        if ($wr_file == '0') $str .= " and ( wr_file = '0' ) ";
        elseif ($wr_file == '1') $str .= " and ( wr_file >= '1' ) ";
    }
    
    if ($stx) $str .= ' and (';
    for ($i=0; $i<count($s); $i++) {
        if (trim($s[$i]) == '') continue;
        
        $search_str = $s[$i];
        
        // 인기검색어
        //insert_popular($field, $search_str);
        
        $str .= $op1;
        $str .= "(";
        
        $op2 = '';
        // 필드의 수만큼 다중 필드 검색 가능 (필드1+필드2...)
        for ($k=0; $k<count($field); $k++) {
            $str .= $op2;
            switch ($field[$k]) {
                case 'mb_id' :
                case 'wr_name' :
                    $str .= "$field[$k] = '$s[$i]'";
                    break;
                case 'wr_subject' :
                case 'wr_content' :
                    if (preg_match("/[a-zA-Z]/", $search_str))
                        $str .= "INSTR(LOWER({$field[$k]}), LOWER('{$search_str}'))";
                        else
                            $str .= "INSTR({$field[$k]}, '{$search_str}')";
                            break;
                default :
                    $str .= "1=0"; // 항상 거짓
                    break;
            }
            $op2 = " or ";
        }
        $str .= ")";
        
        $op1 = " {$sop} ";
    }
    if ($stx) $str .= ")";
    
    $sql_search = $str;
    
    $str_board_list = "";
    $board_count = 0;
    
    $time1 = get_microtime();
    
    $total_count = 0;
    for ($i=0; $i<count($g5_search['tables']); $i++) {
        $tmp_write_table   = $g5['write_prefix'] . $g5_search['tables'][$i];
        
        $sql = " select wr_id from {$tmp_write_table} where {$sql_search} ";
        if($test) echo $sql.'<br/>';
        
        $result = sql_query($sql, false);
        $row['cnt'] = @sql_num_rows($result);
        
        $total_count += $row['cnt'];
        if ($row['cnt']) {
            $board_count++;
            $search_table[] = $g5_search['tables'][$i];
            $read_level[]   = $g5_search['read_level'][$i];
            $search_table_count[] = $total_count;
            
            $sql2 = " select bo_subject, bo_mobile_subject from {$g5['board_table']} where bo_table = '{$g5_search['tables'][$i]}' ";
            $row2 = sql_fetch($sql2);
            $sch_class = "";
            $sch_all = "";
            if ($onetable == $g5_search['tables'][$i]) $sch_class = " selected ";
            else $sch_all = " selected ";
            $str_board_list .= '<option value="'.$_SERVER['SCRIPT_NAME'].'?'.$search_query.'&amp;gr_id='.$gr_id.'&amp;onetable='.$g5_search['tables'][$i].'" '.$sch_class.'>'.((G5_IS_MOBILE && $row2['bo_mobile_subject']) ? $row2['bo_mobile_subject'] : $row2['bo_subject']).'('.$row['cnt'].')</option>';
        }
    }
    
    $rows = $srows;
    $total_page = ceil($total_count / $rows);  // 전체 페이지 계산
    if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $rows; // 시작 열을 구함
    
    for ($i=0; $i<count($search_table); $i++) {
        if ($from_record < $search_table_count[$i]) {
            $table_index = $i;
            $from_record = $from_record - $search_table_count[$i-1];
            break;
        }
    }
    
    $bo_subject = array();
    $list = array();
    
    $k=0;
    for ($idx=$table_index; $idx<count($search_table); $idx++) {
        $sql = " select bo_subject, bo_mobile_subject from {$g5['board_table']} where bo_table = '{$search_table[$idx]}' ";
        $row = sql_fetch($sql);
        $bo_subject[$idx] = ((G5_IS_MOBILE && $row['bo_mobile_subject']) ? $row['bo_mobile_subject'] : $row['bo_subject']);
        
        $tmp_write_table = $g5['write_prefix'] . $search_table[$idx];
        
        if (!$sst) {
            $sst  = "wr_num, wr_reply";
            $sod = "asc";
        } else if ($sst == "wr_num,wr_reply") {
            $sod = "asc";
        }  else if ($sst == "wr_hit") {
            $sod = "desc";
        }
        $sql_order = " order by $sst $sod";
        
        $sql = " select * from {$tmp_write_table} where {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
        if($test) echo $sql.'<br/>';
        
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            // 검색어까지 링크되면 게시판 부하가 일어남
            $list[$idx][$i] = $row;
            $list[$idx][$i]['bo_subject'] = $bo_subject[$idx];
            
            $list[$idx][$i]['href'] = './board.php?bo_table='.$search_table[$idx].'&amp;wr_id='.$row['wr_parent'];
            
            if ($row['wr_is_comment'])
            {
                $sql2 = " select wr_subject, wr_option from {$tmp_write_table} where wr_id = '{$row['wr_parent']}' ";
                $row2 = sql_fetch($sql2);
                //$row['wr_subject'] = $row2['wr_subject'];
                $row['wr_subject'] = get_text($row2['wr_subject']);
            }
            
            // 비밀글은 검색 불가
            //if (strstr($row['wr_option'].$row2['wr_option'], 'secret'))
            //    $row['wr_content'] = '[비밀글 입니다.]';
                
            $subject = get_text($row['wr_subject']);
            if (strstr($sfl, 'wr_subject'))
                $subject = search_font($stx, $subject);
            $list[$idx][$i]['subject'] = $subject;
            
            /*    
            if ($read_level[$idx] <= $member['mb_level'])
            {
                //$content = cut_str(get_text(strip_tags($row['wr_content'])), 300, "…");
                $content = strip_tags($row['wr_content']);
                $content = get_text($content, 1);
                $content = strip_tags($content);
                $content = str_replace('&nbsp;', '', $content);
                $content = cut_str($content, 300, "…");
                
                if (strstr($sfl, 'wr_content'))
                    $content = search_font($stx, $content);
            }
            else
                $content = '';            
            
            $list[$idx][$i]['content'] = $content;
            */    
                    
            $list[$idx][$i]['name'] = get_sideview($row['mb_id'], get_text(cut_str($row['wr_name'], $config['cf_cut_name'])), $row['wr_email'], $row['wr_homepage']);
            $list[$idx][$i]['wr_hit'] = $row['wr_hit'];
            $list[$idx][$i]['wr_9'] = $row['wr_9']; //답변상태(답변전, 답변완료)
            $list[$idx][$i]['wr_comment'] = $row['wr_comment'];
            $list[$idx][$i]['wr_reply'] = $row['wr_reply'];
            $list[$idx][$i]['wr_point'] = $row['wr_point'];
            
            $k++;
            if ($k >= $rows)
                break;
        }
        sql_free_result($result);
        
        if ($k >= $rows)
            break;
            
            $from_record = 0;
    }
    
    $write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$search_query.'&amp;gr_id='.$gr_id.'&amp;srows='.$srows.'&amp;onetable='.$onetable.'&amp;page=');
}

if (!$sfl) $sfl = 'wr_subject||wr_content';
if (!$sop) $sop = 'or';

?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
    
	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 게시물 관리<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">
      <div class="" role="tabpanel" data-example-id="togglable-tabs">
			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
				  <li role="presentation" class="active"><a href="#tab_content1" id="post-manage-tab" role="tab" data-toggle="tab" aria-expanded="true">게시물 관리</a></li>
				  <li role="presentation" class=""><a href="#tab_content2" role="tab" id="comment-manage-tab" data-toggle="tab" aria-expanded="false">댓글 관리</a></li>
			  </ul>
			  <div class="clearfix"></div>
			  <div id="myTabContent" class="tab-content">

			<form name="fsearch" onsubmit="return fsearch_submit(this);" method="get">
    		<input type="hidden" name="srows" value="<?php echo $srows ?>">
    		<input type="hidden" name="sop" value="<?php echo $sop ?>">
    		<input type="hidden" name="sst" value="<?php echo $sst ?>">
    		
            <!-- 게시물 관리 탭 -->
			<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="post-manage-tab">
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
                    <th scope="row">작성일</th>
                    <td colspan="2">
                    	<div class="row">
                        	<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                            	<input type='text' class="form-control" id="search_date" name="search_date" required="required" value="<?php echo $search_date?>" />
                            	<i class="glyphicon glyphicon-calendar fa fa-calendar" style="position: absolute;bottom: 10px;right: 24px;top: auto;cursor: pointer;"></i>
                        	</div>
                        	<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                            	<div class="btn-group" data-toggle="buttons-radio">
                                    <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                                    <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                                    <button type="button" class="btn btn_02 btn_03" name="dateBtn" data="1w">1주</button>
                                    <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                                    <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                                 </div>
                             </div>
                    	</div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">게시판 선택</th>
                    <td colspan="2">
                      <select id="bo_table" onchange="location.href=this.value;">
                          <option value="<?php echo $_SERVER['SCRIPT_NAME'].'?'.$search_query ?>&amp;gr_id=<?php echo $gr_id ?>" <?php echo $sch_all ?>>전체</option>
                          <?php echo $str_board_list; ?>
                      </select>
                      <input type="hidden" name="onetable" value="<?php echo $onetable?>">
                    </td>
                </tr>
                <tr>
                  <th scope="row">게시글 찾기</th>
                  <td colspan="2">
                  	
            	    <select name="sfl" id="sfl">
            	        <option value="wr_subject||wr_content"<?php echo get_selected($_GET['sfl'], "wr_subject||wr_content") ?>>제목+내용</option>
            	        <option value="wr_subject"<?php echo get_selected($_GET['sfl'], "wr_subject") ?>>제목</option>
            	        <option value="wr_content"<?php echo get_selected($_GET['sfl'], "wr_content") ?>>내용</option>
            	        <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id") ?>>회원아이디</option>
            	        <option value="wr_name"<?php echo get_selected($_GET['sfl'], "wr_name") ?>>이름</option>
            	    </select>
                   	<input type="text" name="stx" id="stx" value="<?php echo $text_stx ?>" class="frm_input" maxlength="20">
            		
                  </td>
                </tr>
                <tr>
                  <th scope="row">스팸글 관리</th>
                  <td colspan="2">
                    <select id="wr_8" name="wr_8">
                        <option value="" selected <?php echo get_selected($_GET['wr_8'], "") ?>>전체</option>
                        <option value="0"<?php echo get_selected($_GET['wr_8'], "0") ?>>스팸글 제외</option>
                        <option value="1"<?php echo get_selected($_GET['wr_8'], "1") ?>>스팸글만 보기</option>
                    </select>
                  </td>
                </tr>
                <tr>
                    <th scope="row">답변상태</th>
                    <td colspan="2">
                      <label><input type="radio" checked value="" name="wr_9"<?php echo get_checked($_GET['wr_9'], "") ?>> 전체보기</label>  &nbsp;
                      <label><input type="radio" value="답변전" name="wr_9"<?php echo get_checked($_GET['wr_9'], "답변전") ?>> 답변전</label>  &nbsp;
                      <label><input type="radio" value="답변완료" name="wr_9"<?php echo get_checked($_GET['wr_9'], "답변완료") ?>> 답변완료</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">댓글여부</th>
                    <td colspan="2">
                      <label><input type="radio" checked value="" id="rdo_comment_YN1" name="wr_comment"<?php echo get_checked($_GET['wr_comment'], "") ?>> 전체보기</label>  &nbsp;
                      <label><input type="radio" value="1" id="rdo_comment_YN2" name="wr_comment"<?php echo get_checked($_GET['wr_comment'], "1") ?>> 있음</label>  &nbsp;
                      <label><input type="radio" value="0" id="rdo_comment_YN3" name="wr_comment"<?php echo get_checked($_GET['wr_comment'], "0") ?>> 없음</label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">첨부파일 여부</th>
                    <td colspan="2">
                      <label><input type="radio" checked value="" id="rdo_attach_YN1" name="wr_file"<?php echo get_checked($_GET['wr_file'], "") ?>> 전체보기</label>  &nbsp;
                      <label><input type="radio" value="1" id="rdo_attach_YN2" name="wr_file"<?php echo get_checked($_GET['wr_file'], "1") ?>> 있음</label>  &nbsp;
                      <label><input type="radio" value="0" id="rdo_attach_YN3" name="wr_file"<?php echo get_checked($_GET['wr_file'], "0") ?>> 없음</label>
                    </td>
                </tr>
                </tbody>
                </table>
            </div>
            <div class="form-group">
        			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
                		<button class="btn btn_02" type="button" id="btn_clear">초기화</button>
                    	<button type="submit" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
        			</div>
      		  </div>
  			</div>
			</form>
			
	  	</div>
		</div>
	</div>



<div id="tab1_list_table">
	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 게시물 목록 <small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">
	  <span class="btn_ov01"><span class="ov_txt"> 검색결과</span><span class="ov_num"> <?php echo number_format($total_count) ?>건</span></span>
	  
      <div style="float: right;">
        <select id="category7" name="category7" onchange="fsearch.sst.value=this.value;fsearch.submit();">
            <option value="wr_num,wr_reply" <?php echo get_selected($_GET['sst'], "wr_num,wr_reply") ?>>기본정렬</option>
            <option value="wr_hit" <?php echo get_selected($_GET['sst'], "wr_hit") ?>>조회수 많은 수</option>
        </select>
        <select id="category8" name="category8" onchange="fsearch.srows.value=this.value;fsearch.submit();">
            <option value="10" <?php echo get_selected($_GET['srows'], "10") ?>>10개씩 보기</option>
            <option value="30" <?php echo get_selected($_GET['srows'], "30") ?>>30개씩 보기</option>
            <option value="50" <?php echo get_selected($_GET['srows'], "50") ?>>50개씩 보기</option>
        </select>
      </div>
      <br /><br />
      
      <div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
        <table>
        <thead>
        <tr>
          <th colspan="9" style="text-align: right;">
            <div style="float: left;">
              <button class="btn btn_03" type="button" id="btn_view_deleted" data-toggle="modal" data-target="#modal_viewdeleted">삭제된 글 보기</button>
            </div>
            <button class="btn btn_03" type="button" id="btn_list_delete">삭제</button>
            <button class="btn btn_03" type="button" id="btn_list_point" data-toggle="modal" data-target="#modal_point">적립금 일괄적용</button>
            <button class="btn btn_03" type="button" id="btn_list_spam_report" data-toggle="modal" data-target="#modal_spamreport">스팸신고</button>
            <button class="btn btn_03" type="button" id="btn_list_spam_clear" data-toggle="modal" data-target="#modal_spamclear">스팸해제</button>
          </th>
        </tr>
        <tr>
          <th scope="col">
			<label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
			<input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
          </th>
          <th scope="col">카테고리 분류</th>
          <th scope="col">제목</th>
          <th scope="col">답변상태</th>
          <th scope="col">작성자</th>
          <th scope="col">작성일</th>
          <th scope="col">조회</th>
          <th scope="col">적립금</th>
          <th scope="col">관리</th>
        </tr>
        </thead>

        <tbody>
        <?php
        $k=0;
        for ($idx=$table_index, $k=0; $idx<count($search_table) && $k<$rows; $idx++) {
    	?>
    	<?php
            for ($i=0; $i<count($list[$idx]) && $k<$rows; $i++, $k++) {
        ?>
        
        <tr>
          <td class="td_chk">
                <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
          </td>
          <td class="td_category2"><?php echo $list[$idx][$i]['bo_subject'] ?></td>
          <td class="td_left" style="text-align:left; padding-left:<?php echo $list[$idx][$i]['reply'] ? (strlen($list[$idx][$i]['wr_reply'])*10) : '0'; ?>px">
          	<?php echo ($list[$idx][$i]['wr_reply'] != "")?"->[답변]":"";?>
            &nbsp;<a href="" data-toggle="modal" data-target="#modal_post_member"><?php echo $list[$idx][$i]['subject'] ?></a>
          </td>
          <td class="td_auth"><?php echo $list[$idx][$i]['wr_9'] ?></td>
          <td class="td_id"><?php echo $list[$idx][$i]['name'] ?></td>
          <td class="td_datetime"><?php echo $list[$idx][$i]['wr_datetime'] ?></td>
          <td class="td_etc"><?php echo $list[$idx][$i]['wr_hit'] ?></td>
          <td class="td_etc"><?php echo $list[$idx][$i]['wr_point'] ?></td>
          <td class="td_id">
            <?php if($list[$idx][$i]['wr_9'] == '답변전') echo '<button class="btn btn_02" type="button" id="btn_reply" data-toggle="modal" data-target="#modal_post_admin">답변하기</button>'; ?>
          </td>
        </tr>
        <?php } ?>
    	<?php } ?>
        </tbody>

        <tfoot>
          <tr>
            <th colspan="9" style="text-align: right;">
              <div style="float: left;">
                <button class="btn btn_03" type="button" id="btn_view_deleted" data-toggle="modal" data-target="#modal_viewdeleted">삭제된 글 보기</button>
              </div>
              <button class="btn btn_03" type="button" id="btn_list_delete">삭제</button>
              <button class="btn btn_03" type="button" id="btn_list_point" data-toggle="modal" data-target="#modal_point">적립금 일괄적용</button>
              <button class="btn btn_03" type="button" id="btn_list_spam_report" data-toggle="modal" data-target="#modal_spamreport">스팸신고</button>
              <button class="btn btn_03" type="button" id="btn_list_spam_clear" data-toggle="modal" data-target="#modal_spamclear">스팸해제</button>
            </th>
          </tr>
        </tfoot>
        </table>
        
        <?php echo $write_pages ?>
        
      </div>
		</div>
    </div>

    <div class="hidden" id="tab2_list_table">
    	  <div class="x_title">
    		<h4><span class="fa fa-check-square"></span> 댓글 목록 <small></small></h4>
    		<label class="nav navbar-right"></label>
    		<div class="clearfix"></div>
    	  </div>

    	  <div class="x_content">
          <label>[오늘 등록된 새글 0건]</label>&nbsp;<label> 검색결과 0건</label>
          <div style="float: right;">
            <select id="category9" name="category9">
                <option value="all">전체보기</option>
                <option value="onlycomment">댓글만 보기</option>
            </select>
            <select id="category10" name="category10">
                <option value="10">10개씩 보기</option>
                <option value="30">30개씩 보기</option>
                <option value="50">50개씩 보기</option>
            </select>
          </div><br /><br />
          <div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
            <table>
            <thead>
            <tr>
              <th colspan="9" style="text-align: right;">
                <button class="btn btn_03" type="button" id="btn_list_delete2">삭제</button>
                <button class="btn btn_03" type="button" id="btn_list_point" data-toggle="modal" data-target="#modal_point">적립금 일괄적용</button>
                <button class="btn btn_03" type="button" id="btn_list_spam_report" data-toggle="modal" data-target="#modal_spamreport">스팸신고</button>
                <button class="btn btn_03" type="button" id="btn_list_spam_clear" data-toggle="modal" data-target="#modal_spamclear">스팸해제</button>
              </th>
            </tr>
            <tr>
              <th scope="col">
                  <label for="chkall" class="sound_only">게시글 전체</label>
                  <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
              </th>
              <th scope="col">카테고리 분류</th>
              <th scope="col">제목</th>
              <th scope="col">작성자</th>
              <th scope="col">작성일</th>
            </tr>
            </thead>

            <tbody>
            <tr>
              <td class="td_chk">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_01">
              </td>
              <td class="td_category2">체험단</td>
              <td class="td_left">
                <a href="" data-toggle="modal" data-target="#modal_comment">texttexttexttexttexttexttexttext</a>
              </td>
              <td class="td_id">김빛나리(asdfasdf1234)</td>
              <td class="td_datetime">YYYY-MM-DD<br />HH:MM:SS</td>
            </tr>
            </tbody>

            <tfoot>
              <tr>
                <th colspan="9" style="text-align: right;">
                  <button class="btn btn_03" type="button" id="btn_list_delete2">삭제</button>
                  <button class="btn btn_03" type="button" id="btn_list_point" data-toggle="modal" data-target="#modal_point">적립금 일괄적용</button>
                  <button class="btn btn_03" type="button" id="btn_list_spam_report" data-toggle="modal" data-target="#modal_spamreport">스팸신고</button>
                  <button class="btn btn_03" type="button" id="btn_list_spam_clear" data-toggle="modal" data-target="#modal_spamclear">스팸해제</button>
                </th>
              </tr>
            </tfoot>
            </table>
          </div>
    		</div>
        </div>

	</div>
  </div>
</div>


<!-- Modal : 삭제된 글 보기 -->
<!-- <form name="fmodalviewdeleted" id="fmodalviewdeleted" method="post" onsubmit="return fmodalviewdeleted_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_viewdeleted" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">삭제된 게시물 관리 팝업</h4>
  </div>
  <div class="modal-body">
		<h4><span class="fa fa-check-square"></span> 삭제된 게시물 관리<small></small></h4>
    <div style="width: 850px; background: #eaeaea; padding: 10px; margin: 10px;">
        <p>
            ※게시판에서 삭제된 글을 복원할 수 있는 기능입니다.<br>
            ※삭제된 글은 삭제일로부터 00일만 저장되고, 경과한 후에는 영구 삭제됩니다.<br>
        </p>
    </div>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>삭제된 게시물 관리</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <thead>
        <tr>
          <th>
              <label for="chkall" class="sound_only">게시글 전체</label>
              <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
          </th>
          <th>카테고리 분류</th>
          <th>제목</th>
          <th>작성일</th>
          <th>작성자</th>
          <th>삭제자</th>
        </tr>
        </thead>
        <tbody>
          <tr>
            <td class="td_chk">
              <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_01">
            </td>
            <td class="td_category2">체험단</td>
            <td class="td_left">texttexttexttexttexttexttexttext</td>
            <td class="td_datetime">YYYY-MM-DD</td>
            <td class="td_id">이름(ID)</td>
            <td class="td_etc">ID(관리자)</td>
          </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <input type="submit" class="btn btn-success" id="btn_restore" value="복원"></input>
    <input type="submit" class="btn btn-success" id="btn_delete_completely" value="완전삭제"></input>
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 삭제된 글 보기 -->


<!-- Modal : 적립금 일괄적용 -->
<!-- <form name="fmodalpoint" id="fmodalpoint" method="post" onsubmit="return fmodalpoint_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_point" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">적립금 일괄 적용 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>적립금 일괄 적용</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">적용대상</th>
            <td colspan="2">
              <input type="checkbox" name="chk_point_to" value="wirter" id="chk_point_to_wirter">
              <label for="chk_point_to_wirter">게시물 작성자</label> &nbsp;
              <input type="checkbox" name="chk_point_to" value="comment" id="chk_point_to_comment">
              <label for="chk_point_to_comment">댓글 작성자</label> &nbsp;
            </td>
        </tr>
        <tr>
            <th scope="row">증감여부</th>
            <td colspan="2">
              <input type="radio" checked="" value="increase" id="rdo_point_increase"> (+)적립금 증액
            </td>
        </tr>
        <tr>
          <th scope="row">적립금</th>
          <td colspan="2">
            <input type = "number" name="txt_point_num" value="" id="txt_point_num" required class="required frm_input">
          </td>
        </tr>
        <tr>
          <th scope="row">내용</th>
          <td colspan="2">
            <input type="text" name="txt_content" value="" id="txt_content" required class="required frm_input" size="50" maxlength="120">
          </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" class="btn btn-success" id="btn_point_apply" value="일괄적용"></input>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 적립금 일괄적용 -->


<!-- Modal : 게시글 내용 (회원) -->
<!-- <form name="fmodalpostmember" id="fmodalpostmember" method="post" onsubmit="return fmodalpostmember_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_post_member" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">게시글 내용 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시글 내용</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">카테고리 분류</th>
            <td colspan="2">TEXT</td>
        </tr>
        <tr>
            <th scope="row">제목</th>
            <td colspan="2">
              TEXTTEXT
              <div style="float: right;">
                <button class="btn btn_02" type="button" id="btn_url_copy">URL 복사</button>
              </div>
            </td>
        </tr>
        <tr>
          <th scope="row">작성자</th>
          <td colspan="2">이름(ID)</td>
        </tr>
        <tr>
          <th scope="row">답변상태</th>
          <td colspan="2">
            [답변 전]
            <div style="float: right;">
              <button class="btn btn_02" type="button" id="btn_memeber_post_reply" data-toggle="modal" data-target="#modal_post_reply">답변하기</button>
            </div>
          </td>
        </tr>
        <tr>
          <th scope="row">작성글</th>
          <td colspan="2">
            <textarea class="form-control" rows="5" id="post_content"></textarea>
          </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
  <input type="submit" class="btn btn-success" id="btn_member_post_spam" value="스팸신고" data-toggle="modal" data-target="#modal_spamreport"></input>
  <input type="submit" class="btn btn-success" id="btn_member_post_delete" value="삭제하기"></input>
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 게시글 내용 (회원) -->


<!-- Modal : 게시글 내용 (회원) 답변하기 -->
<!-- <form name="fmodalpostreply" id="fmodalpostreply" method="post" onsubmit="return fmodalpostreply_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_post_reply" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">게시글 내용 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시글 내용</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">카테고리 분류</th>
            <td colspan="2">TEXT</td>
        </tr>
        <tr>
            <th scope="row">제목</th>
            <td colspan="2">TEXTTEXT</td>
        </tr>
        <tr>
          <th scope="row">작성자</th>
          <td colspan="2">이름(ID)</td>
        </tr>
        <tr>
          <th scope="row">답변상태</th>
          <td colspan="2">[답변 완료]</td>
        </tr>
        <tr>
          <th scope="row">답변글</th>
          <td colspan="2">
            <textarea class="form-control" rows="5" id="reply_content"></textarea>
          </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" class="btn btn-success" id="btn_post_reply_save" value="저장"></input>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 게시글 내용 (회원) 답변하기 -->


<!-- Modal : 게시글 내용 (관리자) -->
<!-- <form name="fmodalpostadmin" id="fmodalpostadmin" method="post" onsubmit="return fmodalpostadmin_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_post_admin" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">게시글 내용 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>게시글 내용</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">카테고리 분류</th>
            <td colspan="2">TEXT</td>
        </tr>
        <tr>
            <th scope="row">제목</th>
            <td colspan="2">TEXTTEXT</td>
        </tr>
        <tr>
          <th scope="row">작성자</th>
          <td colspan="2">ID(관리자)</td>
        </tr>
        <tr>
          <th scope="row">작성글</th>
          <td colspan="2">
            <img src="./img/theme_img.jpg" class="img-thumbnail">
          </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
  <input type="submit" class="btn btn-success" id="btn_post_delete" value="삭제"></input>
  <input type="submit" class="btn btn-success" id="btn_post_update" value="수정"></input>
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 게시글 내용 (관리자) -->

<!-- Modal : 댓글 내용 -->
<!-- <form name="fmodalcomment" id="fmodalcomment" method="post" onsubmit="return fmodalcomment_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_comment" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">댓글 내용 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>댓글 내용</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
          <th scope="row">작성자</th>
          <td colspan="2">이름(ID)</td>
        </tr>
        <tr>
          <th scope="row">댓글</th>
          <td colspan="2">
            <textarea class="form-control" rows="5" id="comment_content"></textarea>
          </td>
        </tr>
        <tr>
          <th scope="row">게시글</th>
          <td colspan="2">
            <textarea class="form-control" rows="5" id="comment_post_content"></textarea>
          </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <input type="submit" class="btn btn-success" id="btn_member_comment_save" value="댓글입력"></input>
    <input type="submit" class="btn btn-success" id="btn_member_comment_spam" value="스팸신고" data-toggle="modal" data-target="#modal_spamreport"></input>
    <input type="submit" class="btn btn-success" id="btn_member_comment_delete" value="삭제하기"></input>
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 댓글 내용 -->


<!-- Modal : 스팸신고 -->
<!-- <form name="fmodalspamreport" id="fmodalspamreport" method="post" onsubmit="return fmodalspamreport_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_spamreport" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">스팸게시물 신고하기 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>스팸게시물 신고하기</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th rowspan='3' style="text-align: center;">신고하기</th>
            <td colspan="2">
              <input type="checkbox" name="chk_spam" value="ip" id="chk_spam_ip_block">
              <label for="chk_spam_ip_block">게시물 작성자 IP 차단하기</label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <input type="checkbox" name="chk_spam" value="delete" id="chk_spam_delete">
            <label for="chk_spam_delete">삭제하기</label>
            </td>
        </tr>
        <tr>
          <td colspan="2">
          <input type="checkbox" name="chk_spam" value="blacklist" id="chk_spam_blacklist">
          <label for="chk_spam_blacklist">블랙리스트</label>
          </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" class="btn btn-success" id="btn_spam_report" value="신고하기"></input>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 스팸신고 -->


<!-- Modal : 스팸해제 -->
<!-- <form name="fmodalspamclear" id="fmodalspamclear" method="post" onsubmit="return fmodalspamclear_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_spamclear" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">스팸게시물 해제하기 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>스팸게시물 해제하기</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
            <th rowspan='3' style="text-align: center;">신고하기</th>
            <td colspan="2">
              <input type="checkbox" checked="" name="chk_spam" value="ip" id="chk_spam_ip_block">
              <label for="chk_spam_ip_block">000.000.000.000</label>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" class="btn btn-success" id="btn_spam_clear" value="해제하기"></input>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 스팸해제 -->

<script>

function fsearch_submit(f)
{
    /*if (f.stx.value.length < 2) {
        alert("검색어는 두글자 이상 입력하십시오.");
        f.stx.select();
        f.stx.focus();
        return false;
    }*/

    // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
    var cnt = 0;
    for (var i=0; i<f.stx.value.length; i++) {
        if (f.stx.value.charAt(i) == ' ')
            cnt++;
    }

    if (cnt > 1) {
        alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
        f.stx.select();
        f.stx.focus();
        return false;
    }

    f.action = "";
    return true;
}
<!-- 버튼 이벤트 -->

$("#post-manage-tab").click(function(){

  $("#tab2_list_table").addClass("hidden");

});

$("#comment-manage-tab").click(function(){

  $("#tab1_list_table").addClass("hidden");

});



//초기화 버튼
$("#btn_clear, #btn_clear2").click(function(){

  $("#search_date").val("");
  $('button[name="dateBtn"]').removeClass('btn_03').addClass('btn_02');
  $("#category1, #category2, #category3, #category4, #category5, #category6").val("all").prop("selected", true);
  $("#txt_post_search, #txt_comment_search").val("");
  $("#rdo_reply_status1").prop("checked", true);
  $("#rdo_comment_YN1").prop("checked", true);
  $("#rdo_attach_YN1").prop("checked", true);

});

//게시물 검색 버튼
$("#btn_search").click(function(){

  $("#tab1_list_table").removeClass("hidden");

});

//댓글 검색 버튼
$("#btn_search2").click(function(){

  $("#tab2_list_table").removeClass("hidden");

});

//삭제된 글 보기 팝업 복원 버튼
$("#btn_restore").click(function(){

  //

});

//삭제된 글 보기 팝업 완전삭제 버튼
$("#btn_delete_completely").click(function(){

  //

});

//게시물 목록 삭제 버튼
$("#btn_list_delete, #btn_list_delete2").click(function(){

  if ( confirm("게시글을 삭제합니다.") ) {
    //선택한 게시판 삭제
  }

});

// 적립금 일괄적용 팝업 일괄적용 버튼
$("#btn_point_apply").click(function(){

  if ( (!$("#chk_point_to_wirter").prop('checked') && !$("#chk_point_to_comment").prop('checked'))
      || ( isNull($("#txt_point_num").val()) )
      || ( isNull($("#txt_content").val()) ) ) {

    alert("항목을 확인해주세요.");
  }
  else {

    if(confirm("일괄적용 합니다.")) {
      alert("적립금 적용됩니다.");
      //데이터 전달. form?
      $("#modal_point").on('hidden.bs.modal', function () {
        $(this).find("input").val('').end();
      });
      $("#modal_point").modal("hide");
    }
  }

});

//널 값 체크
function isNull(elm) {

        var elm;
        return (elm == null || elm == "" || elm == "undefined" || elm == " " || elm == "0") ? true : false
}

// 스팸신고 팝업 신고하기 버튼
$("#btn_spam_report").click(function(){

  if ( !$("#chk_spam_ip_block").prop('checked') &&
  !$("#chk_spam_delete").prop('checked') &&
  !$("#chk_spam_blacklist").prop('checked') ) {

    alert("항목을 선택해주세요.");

  } else {

    alert("적용되었습니다.");
    $("#modal_spamreport").modal("hide");
  }
});

// 스팸해제 팝업 해제하기 버튼
$("#btn_spam_clear").click(function(){

  alert("처리되었습니다.");
  $("#modal_spamclear").modal("hide");

});

// 게시글 내용 팝업 삭제하기 버튼
$("#btn_post_delete").click(function(){

  if ( confirm("게시글을 삭제하시겠습니까?") ) {
    alert("삭제되었습니다.");
    $("#modal_post_admin").modal("hide");
  }

});

// 게시글 내용 팝업 (관리자) 수정하기 버튼
$("#btn_post_update").click(function(){

  //수정 내용 적용
  $("#modal_post_admin").modal("hide");

});

// 게시글 내용 팝업 (회원) 삭제하기 버튼
$("#btn_member_post_delete, #btn_member_comment_delete").click(function(){

  if ( confirm("게시글을 삭제하시겠습니까?") ) {
    alert("삭제되었습니다.");
    $("#modal_post_member").modal("hide");
    $("#modal_comment").modal("hide");
  }

});

// 게시글 내용 팝업 (회원) URL 복사 버튼
$("#btn_url_copy").click(function(){



});

// 게시글 내용 팝업 (회원) 답변하기 저장 버튼
$("#btn_post_reply_save").click(function(){



});


$(function() {
	
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

	//날짜 버튼
	$("button[name='dateBtn']").click(function(){
		$('button[name="dateBtn"]').removeClass('btn_03');
		$(this).addClass('btn_03');
		
		var d = $(this).attr("data");
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

		$('#search_date').data('daterangepicker').setStartDate(startD);
		$('#search_date').data('daterangepicker').setEndDate(endD);
	
	});

});

</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>