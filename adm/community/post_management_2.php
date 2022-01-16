<?php

include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = '댓글 관리';
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
    
    $search_query = 'sfl='.urlencode($sfl).'&amp;stx='.urlencode($stx).'&amp;sop='.$sop.'&amp;search_date='.$search_date.'&amp;wr_type='.$wr_type;
    
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
    $str .= " and wr_is_comment = '1' ";

    //스팸상태
    if (isset($wr_8)) {
        $search_query .='&amp;wr_8='.$wr_8;
        if ($wr_8 == '1') $str .= " and ( wr_8 = '스팸' ) ";
        elseif ($wr_8 == '0') $str .= " and ( wr_8 != '스팸' ) ";
    }
    //신고상태
    if (isset($nogood)) {
        $search_query .='&amp;nogood='.$nogood;
        if ($nogood == '1') $str .= " and ( wr_nogood > 0 ) ";
        elseif ($nogood == '0') $str .= " and ( wr_nogood = 0 ) ";
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
        //if($test) echo $sql.'<br/>';
        
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
        //if($test) echo $sql.'<br/>';
        
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            // 검색어까지 링크되면 게시판 부하가 일어남
            $list[$idx][$i] = $row;
            $list[$idx][$i]['bo_subject'] = $bo_subject[$idx];
            $list[$idx][$i]['bo_table'] = $search_table[$idx];
            
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
                    
            //$list[$idx][$i]['name'] = get_sideview($row['mb_id'], get_text(cut_str($row['wr_name'], $config['cf_cut_name'])), $row['wr_email'], $row['wr_homepage']);
            
            $list[$idx][$i]['name'] = get_text(cut_str($row['wr_name'], $config['cf_cut_name'])).'('.$row['mb_id'].')<br/>IP:'.$row['wr_ip'];
            $list[$idx][$i]['wr_hit'] = $row['wr_hit'];
            $list[$idx][$i]['wr_8'] = $row['wr_8']; //스팸상태
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

$token = get_admin_token();
?>
    <form name="fsearch" onsubmit="return fsearch_submit(this);" method="get" id="fsearch">
    <input type="hidden" name="srows" value="<?php echo $srows ?>">
    <input type="hidden" name="sop" value="<?php echo $sop ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="wr_type" value="<?php echo $wr_type ?>">
    
    <!-- 게시물 관리 탭 -->
    <div role="tabpanel" class="tab-pane fade active in">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>답글 관리</caption>
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
          <th scope="row">신고글 관리</th>
          <td colspan="2">
            <select id="nogood" name="nogood">
                <option value="" selected <?php echo get_selected($_GET['nogood'], "") ?>>전체</option>
                <option value="0"<?php echo get_selected($_GET['nogood'], "0") ?>>신고글 제외</option>
                <option value="1"<?php echo get_selected($_GET['nogood'], "1") ?>>신고글만 보기</option>
            </select>
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
			
    <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 게시물 목록 <small></small></h4>
        <label class="nav navbar-right"></label>
        <div class="clearfix"></div>
    </div>

	  <div class="x_content">
	  <span class="btn_ov01"><span class="ov_txt"> 검색결과</span><span class="ov_num"> <?php echo number_format($total_count) ?>건</span></span>
	  
      <div style="float: right;">
        <select id="category8" name="category8" onchange="fsearch.srows.value=this.value;fsearch.submit();">
            <option value="10" <?php echo get_selected($_GET['srows'], "10") ?>>10개씩 보기</option>
            <option value="30" <?php echo get_selected($_GET['srows'], "30") ?>>30개씩 보기</option>
            <option value="50" <?php echo get_selected($_GET['srows'], "50") ?>>50개씩 보기</option>
        </select>
      </div>
      <br /><br />
      
        <form name="fboardlist" id="fboardlist" action="./post_management_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
        <input type="hidden" name="btn_submit" id="btn_submit" value="">
        
        <input type="hidden" name="po_point" id="po_point" value="">
        <input type="hidden" name="po_rel_action" id="po_rel_action" value="">
        
        <input type="hidden" name="token" value="<?php echo $token?>">
        <input type="hidden" name="srows" value="<?php echo $srows ?>">
        <input type="hidden" name="wr_type" value="<?php echo $wr_type ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="sop" value="<?php echo $sop ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="onetable" value="<?php echo $onetable?>">
        
        <input type="hidden" name="search_date" value="<?php echo $search_date ?>">
        <input type="hidden" name="wr_8" value="<?php echo $wr_8 ?>">
      
      <div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
        <table>
        <thead>
        <tr>
          <th colspan="9" style="text-align: right;">
            <div style="float: left;">
              <button class="btn btn_03" type="button" id="btn_view_deleted1" >삭제된 댓글 보기</button>
            </div>
            <button class="btn btn_03" type="button" id="btn_list_delete1">삭제</button>
            <button class="btn btn_03" type="button" id="btn_list_point1">적립금 일괄적용</button>
            <button class="btn btn_03" type="button" id="btn_list_spam_report1" >스팸신고</button>
            <button class="btn btn_03" type="button" id="btn_list_spam_clear1" >스팸해제</button>
          </th>
        </tr>
        <tr>
          <th scope="col">
			<label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
          </th>
          <th scope="col">카테고리 분류</th>
          <th scope="col">제목</th>
          <th scope="col">댓글</th>
          <th scope="col">작성자</th>
          <th scope="col">작성일</th>
          <th scope="col">스팸여부</th>
          <th scope="col">신고여부</th>
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
                <label for="chk_<?php echo $k ?>" class="sound_only"><?php echo $list[$idx][$i]['subject'] ?></label>
            	<input type="checkbox" name="chk[]" value="<?php echo $k ?>" id="chk_<?php echo $k ?>">
            	
                <input type="hidden" name="wr_id[<?php echo $k ?>]" value="<?php echo $list[$idx][$i]['wr_id'] ?>" id="wr_id_<?php echo $k ?>">
                <input type="hidden" name="board_table[<?php echo $k ?>]" value="<?php echo $list[$idx][$i]['bo_table'] ?>" id="bo_table_<?php echo $k ?>">
                <input type="hidden" name="wr_8[<?php echo $k ?>]" value="<?php echo $list[$idx][$i]['wr_8'] ?>" id="wr_8_<?php echo $k ?>">
          </td>
          <td class="td_category2">
          	<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $list[$idx][$i]['bo_table'] ?>" target="_blank"><?php echo $list[$idx][$i]['bo_subject'] ?></a>
          </td>
          <td class="td_left" style="text-align:left; padding-left:<?php echo $list[$idx][$i]['reply'] ? (strlen($list[$idx][$i]['wr_reply'])*10) : '0'; ?>px">
          	<a href="#" onclick='fview("v","<?php echo $list[$idx][$i]['bo_table'] ?>","<?php echo $list[$idx][$i]['wr_id'] ?>","<?php echo $k ?>");'>
            &nbsp;<?php echo $list[$idx][$i]['subject'] ?>
            </a>
          </td>
          <td class="td_left">
          	<?php echo $list[$idx][$i]['wr_content']; ?>
          </td>
          <td class="td_id"><?php echo $list[$idx][$i]['name'] ?></td>
          <td class="td_datetime"><?php echo $list[$idx][$i]['wr_datetime'] ?></td>
          <td class="td_datetime"><?php echo ($list[$idx][$i]['wr_8'] == "스팸")?"<span class='label label-danger'>SPAM</span>":"";?></td>
          <td class="td_datetime"><?php echo ($list[$idx][$i]['wr_nogood'] == "0")?"-":$list[$idx][$i]['wr_nogood'] ?></td>
        </tr>
        <?php } ?>
    	<?php } ?>
        </tbody>

        <tfoot>
          <tr>
            <th colspan="9" style="text-align: right;">
              <div style="float: left;">
                <button class="btn btn_03" type="button" id="btn_view_deleted2" >삭제된 댓글 보기</button>
              </div>
              <button class="btn btn_03" type="button" id="btn_list_delete2">삭제</button>
              <button class="btn btn_03" type="button" id="btn_list_point2">적립금 일괄적용</button>
              <button class="btn btn_03" type="button" id="btn_list_spam_report2">스팸신고</button>
              <button class="btn btn_03" type="button" id="btn_list_spam_clear2">스팸해제</button>
            </th>
          </tr>
        </tfoot>
        </table>
        
        <?php echo $write_pages ?>
        
      </div>
      </form>
      
		</div>
    </div>

    

	</div>
  </div>
</div>

<!-- Modal : 삭제된 글 보기 -->
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
            ※ 게시판에서 삭제된 글을 복원할 수 있는 기능입니다. <br>
            ※ 삭제된 글은 삭제일로부터 90일만 저장되고, 경과한 후에는 영구 삭제됩니다.<br>
        </p>
    </div>
    <div class="tbl_frm01 tbl_wrap">
    
    <form name="fboardlist2" id="fboardlist2" action="./post_management_list_update.php" onsubmit="return fboardlist_submit2(this);" method="post">
        <input type="hidden" name="btn_submit" id="btn_submit2" value="">
        <input type="hidden" name="token" value="<?php echo $token?>">
        <input type="hidden" name="srows" value="<?php echo $srows ?>">
        <input type="hidden" name="wr_type" value="<?php echo $wr_type ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="sop" value="<?php echo $sop ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="onetable" value="<?php echo $onetable?>">
        
        <input type="hidden" name="search_date" value="<?php echo $search_date ?>">
        <input type="hidden" name="wr_8" value="<?php echo $wr_8 ?>">
        <input type="hidden" name="wr_9" value="<?php echo $wr_9 ?>">
        <input type="hidden" name="wr_comment" value="<?php echo $wr_comment ?>">
        <input type="hidden" name="wr_file" value="<?php echo $wr_file ?>">
        
        <table>
        <caption>삭제된 게시물 관리</caption>
        <thead>
        <tr>
          <th style="width:50px;">
              <input type="checkbox" name="chkall2" value="1" id="chkall2" onclick="check_all2(this.form)">
          </th>
          <th>카테고리 분류</th>
          <th>제목</th>
          <th>댓글</th>
          <th>작성일</th>
          <th>작성자</th>
          <th>삭제자</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        $sql = " select a.*, b.bo_subject from lt_write_delete as a, {$g5['board_table']} as b where a.bo_table = b.bo_table and wr_is_comment = '1' order by a.wr_del_datetime desc ";
        if($test) echo $sql.'<br/>';
        
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            
            $tmp_write_table = $g5['write_prefix'] . $row['bo_table'];
            
            $sql2 = " select wr_subject, wr_option from {$tmp_write_table} where wr_id = '{$row['wr_parent']}' ";
            $row2 = sql_fetch($sql2);
            //$row['wr_subject'] = $row2['wr_subject'];
            $row['wr_subject'] = get_text($row2['wr_subject']);
            
        ?>
          <tr>
            <td class="td_chk">
            	<input type="checkbox" name="chk2[]" value="<?php echo $i ?>" id="chk2_<?php echo $i ?>">
                <input type="hidden" name="wr_id[]" value="<?php echo $row['wr_id'] ?>">
                <input type="hidden" name="board_table[]" value="<?php echo $row['bo_table'] ?>">
            </td>
            <td class="td_category2"><?php echo $row['bo_subject'] ?></td>
            <td class="td_left"><?php echo $row['wr_subject'] ?></td>
            <td class="td_left"><?php echo $row['wr_content']; ?></td>
            <td class="td_datetime"><?php echo $row['wr_datetime'] ?></td>
            <td class="td_etc"><?php echo $row['wr_name'].'('.$row['mb_id'].')'?></td>
            <td class="td_etc"><?php echo $row['wr_del_mb_id'].'('.$row['wr_del_mb_name'].')'?></td>
          </tr>
		<?php } ?>
        </tbody>
        </table>
        </form>
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
              <label for="chk_point_to_wirter">
              <input type="checkbox" name="chk_point_to" value="wirter" id="chk_point_to_wirter" disabled="disabled">
              게시물 작성자</label> &nbsp;
              
              <label for="chk_point_to_comment">
              <input type="checkbox" name="chk_point_to" value="comment" id="chk_point_to_comment" checked="checked" disabled="disabled">
              댓글 작성자</label> &nbsp;
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


<!-- Modal : 게시글 내용 (회원) -->
<!-- <form name="fmodalpostmember" id="fmodalpostmember" method="post" onsubmit="return fmodalpostmember_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_post_view" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" style="width:100%">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">게시글 내용 팝업</h4>
  </div>
  <div class="modal-body">
    <div class="tbl_frm01 tbl_wrap" id="post_view">
    </div>
  </div>
  <div class="modal-footer">
  	<input type="button" class="btn btn-success" id="btn_list_spam_report3" value="스팸신고" ></input>
  	<input type="button" class="btn btn-success" id="btn_list_delete3" value="삭제하기"></input>
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 게시글 내용 (회원) -->


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

//초기화 버튼
$("#btn_clear").click(function(){

    $("#search_date").val("");
    $('button[name="dateBtn"]').removeClass('btn_03').addClass('btn_02');
    $("#category1, #category2, #category3, #category4, #category5, #category6").val("all").prop("selected", true);
    $("#txt_post_search, #txt_comment_search").val("");
    $("#rdo_reply_status1").prop("checked", true);
    $("#rdo_comment_YN1").prop("checked", true);
    $("#rdo_attach_YN1").prop("checked", true);

});


function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk[]" && f.elements[i].checked)
            chk_count++;
    }
    
    if (!chk_count) {
        alert("게시물을 하나 이상 선택하세요.");
        return false;
    }
    
    return true;
}

function fboardlist_submit2(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk2[]" && f.elements[i].checked)
            chk_count++;
    }
    
    if (!chk_count) {
        alert("게시물을 하나 이상 선택하세요.");
        return false;
    }
    
    return true;
}

function fview(w, bo_table, wr_id, k)
{
	$("input[name='chkall']").prop("checked", false);
	$("input[name='chk[]']").prop("checked", false);

	$("#chk_"+k).prop("checked", true);
	
	$.post(
        "./ajax.post_get.php",
        { w: w, bo_table: bo_table, wr_id:wr_id },
        function(data) {
            $("#post_view").html(data);
        	$("#modal_post_view").modal("show");
        }
    );
}

//삭제된 글 보기 팝업 
$("#btn_view_deleted1, #btn_view_deleted2").click(function(){
	$("#modal_viewdeleted").modal("show");
});

function check_all2(f)
{
    var chk = document.getElementsByName("chk2[]");

    for (i=0; i<chk.length; i++)
        chk[i].checked = f.chkall2.checked;
}

//삭제된 글 보기 팝업 복원 버튼
$("#btn_restore").click(function(){

    if ($("input[name='chk2[]']:checked").length <= 0) {
        alert("게시물을 하나 이상 선택하세요.");
        return false;
    }
    
    if ( confirm("선택한 게시물을 복원하시겠습니까?") ) {
      $("#btn_submit2").val("댓글선택복원");
      $("#fboardlist2").submit();
    }
});

//삭제된 글 보기 팝업 완전삭제 버튼
$("#btn_delete_completely").click(function(){

    if ($("input[name='chk2[]']:checked").length <= 0) {
        alert("게시물을 하나 이상 선택하세요.");
        return false;
    }
    
    if ( confirm("선택한 게시물을 완전 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다.") ) {
      $("#btn_submit2").val("댓글완전삭제");
      $("#fboardlist2").submit();
    }
});

//게시물 목록 삭제 버튼
$("#btn_list_delete1, #btn_list_delete2, #btn_list_delete3").click(function(){

	if ($("input[name='chk[]']:checked").length <= 0) {
        alert("게시물을 하나 이상 선택하세요.");
        return false;
    }

    if ( confirm("선택한 게시물을 정말 삭제하시겠습니까?") ) {
      $("#btn_submit").val("댓글선택삭제");
      $("#fboardlist").submit();
    //
    }
});



//적립금 일괄적용 팝업 버튼
$("#btn_list_point1, #btn_list_point2").click(function(){
  
  if ($("input[name='chk[]']:checked").length <= 0) {
      alert("게시물을 하나 이상 선택하세요.");
      return false;
  }
  $("#txt_point_num").val("");
  $("#txt_content").val("");
  $("#modal_point").modal("show");
  
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
    	var point_num = $("#txt_point_num").val();
    	var point_content = $("#txt_content").val();

    	$("#po_point").val(point_num);
    	$("#po_rel_action").val(point_content);

    	$("#btn_submit").val("적립금적용");
        $("#fboardlist").submit();
    }
  }

});

//널 값 체크
function isNull(elm) {

        var elm;
        return (elm == null || elm == "" || elm == "undefined" || elm == " " || elm == "0") ? true : false
}

$("#btn_list_spam_report1, #btn_list_spam_report2, #btn_list_spam_report3").click(function(){

	$("#modal_post_view").modal("hide");
	
  //스팸처리
  if ($("input[name='chk[]']:checked").length <= 0) {
      alert("게시물을 하나 이상 선택하세요.");
      return false;
  }

  $("#chk_spam_ip_block").prop("checked", false);
  $("#chk_spam_delete").prop("checked", false);
  $("#chk_spam_blacklist").prop("checked", false);
  
  $("#modal_spamreport").modal("show");
  
});

$("#btn_list_spam_clear1, #btn_list_spam_clear2").click(function(){
	  
    if ($("input[name='chk[]']:checked").length <= 0) {
        alert("게시물을 하나 이상 선택하세요.");
        return false;
    }

    var $chk = $("input[name='chk[]']:checked");
    for (var i=0; i<$chk.size(); i++)
    {
        var k = $($chk[i]).val();
        var wr_8 = $("input[name='wr_8["+k+"]']").val();
        
        if (wr_8 != "스팸") {
           alert("'스팸' 게시물만 선택하세요.");
           return false;
        }
    }
    
    if(confirm("스팸해제 합니다.\nIP 차단해제는 IP접속제한관리에서 가능합니다.\n블랙리스트 해제는 회원관리에서 가능합니다.")) {
    	$("#btn_submit").val("스팸해제");
        $("#fboardlist").submit();
    }
});


// 스팸신고 팝업 신고하기 버튼
$("#btn_spam_report").click(function(){
	
	var spam_ip_block = $("#chk_spam_ip_block").prop("checked");
	var spam_delete = $("#chk_spam_delete").prop("checked");
	var spam_blacklist = $("#chk_spam_blacklist").prop("checked");

    if(confirm("스팸신고 합니다.")) {

    	var btn_submit = "spam" + ((spam_ip_block)?"1":"0") + ((spam_delete)?"1":"0") + ((spam_blacklist)?"1":"0");
    	
    	$("#btn_submit").val(btn_submit);
        $("#fboardlist").submit();
    }
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