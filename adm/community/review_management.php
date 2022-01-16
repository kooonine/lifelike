<?php
$sub_menu = "900210";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

//auth_check($auth[substr($sub_menu,0,2)], 'r');
    
$g5['title'] = '리뷰관리';
include_once ('../admin.head.php');

$sql_common = "  from {$g5['g5_shop_item_use_table']} a
                        left join {$g5['g5_shop_item_table']} b on (a.it_id = b.it_id)
                        left join {$g5['member_table']} c on (a.mb_id = c.mb_id)
                        left outer join lt_shop_cart d on a.ct_id = d.ct_id 
";
$sql_search = " where (1)";

if ($is_admin == 'brand')
{
    $sql = "select * from lt_member_company where mb_id = '{$member['mb_id']}' ";
    $cp = sql_fetch($sql);
    if($cp['cp_status'] == "승인요청" || $cp['cp_status'] == "승인반려") {
        alert("이 메뉴에는 접근 권한이 없습니다. 승인완료 후 접근 가능합니다.");
    }
    $sql_search .= "and a.it_id in (select it_id from lt_shop_item where ca_id3 != '' and ca_id3 = '{$cp['company_code']}')";
}

if ($search_date != "") {
    $search_dates = explode("~", $search_date);
    $fr_date = trim($search_dates[0]);
    $to_date = trim($search_dates[1]);
}

if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';
if ($fr_date && $to_date) {
    $sql_search .= " and is_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if($sca) {
    $sql_search .= " and ca_id like '$sca%' ";
}

$stx = strip_tags($stx);
$stx = get_search_string($stx); // 특수문자 제거

if($stx) {
    // 검색필드를 구분자로 나눈다. 여기서는 +
    $field = explode('||', trim($sfl));
    $sql_search .= ' and (';
    
    $op2 = '';
    // 필드의 수만큼 다중 필드 검색 가능 (필드1+필드2...)
    for ($k=0; $k<count($field); $k++) {
        $sql_search .= $op2;
        switch ($field[$k]) {
            case 'mb_id' :
            case 'is_name' :
                $sql_search .= "a.$field[$k] = '$stx'";
                break;
            case 'od_id' :
                $stx = preg_replace("/[^0-9]/", "", $stx);
                $sql_search .= "$field[$k] = '$stx%'";
                break;
            case 'it_name' :
                if (preg_match("/[a-zA-Z]/", $stx))
                    $sql_search .= "INSTR(LOWER(b.{$field[$k]}), LOWER('{$stx}'))";
                else
                    $sql_search .= "INSTR(b.{$field[$k]}, '{$stx}')";
                break;
            case 'is_subject' :
            case 'is_content' :
                if (preg_match("/[a-zA-Z]/", $stx))
                    $sql_search .= "INSTR(LOWER(a.{$field[$k]}), LOWER('{$stx}'))";
                else
                    $sql_search .= "INSTR(a.{$field[$k]}, '{$stx}')";
                break;
            default :
                $sql_search .= "1=0"; // 항상 거짓
                break;
        }
        $op2 = " or ";
    }
    
    $sql_search .= ")";
} 

if($is_type) $sql_search .= " and is_type = '$is_type' ";
if($is_score) $sql_search .= " and is_score = '$is_score' ";


if (!$sst) {
    $sst  = "is_id";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";

$sql = " select count(*) as cnt
        $sql_common
        $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($page_rows) $rows = $page_rows;
else $rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$stx = get_text(stripslashes($stx));

$qstr = "sfl=".urlencode($sfl)."&amp;stx=".urlencode($stx)."&amp;search_date=".urlencode($search_date)."&amp;qa_status=".urlencode($qa_status)."&amp;sca=".urlencode($sca)."&amp;page=".$page;

$sql = " select *
        $sql_common
        $sql_search
        $sql_order
        limit $from_record, $rows ";
$result = sql_query($sql);

$qstr .= ($qstr ? '&amp;' : '').'sca='.$sca.'&amp;save_stx='.$stx;

$token = get_admin_token();
?>


<!-- @START@ 내용부분 시작 -->
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 리뷰 관리<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>
	  
    <form name="flistSearch" id="flistSearch" class="local_sch01 local_sch">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
    	
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
          <tr hidden>
              <th scope="row">카테고리</th>
              <td colspan="2">
              	<div class="col-md-12 col-sm-12 col-xs-12">
                <select name="sca" id="sca">
                    <option value="">상품카테고리 전체분류</option>
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
                </div>
              </td>
          </tr>
          <tr>
              <th scope="row">구분</th>
              <td colspan="2">
              <div class="col-md-12 col-sm-12 col-xs-12">
              <?php if(!isset($is_type)) $is_type = ""; ?>
                <label><input type="radio" value="" id="rdo_review_type1" name="is_type" <?php echo get_checked($is_type, ""); ?> > 전체  </label>&nbsp;
                <label><input type="radio" value="0" id="rdo_review_type2" name="is_type" <?php echo get_checked($is_type, "0"); ?> > 일반 구매평  </label>&nbsp;
                <label><input type="radio" value="1" id="rdo_review_type3" name="is_type" <?php echo get_checked($is_type, "1"); ?> > 프리미엄 구매평</label>
              </div>
              </td>
          </tr>
          <tr>
              <th scope="row">평점</th>
              <td colspan="2">
              <div class="col-md-12 col-sm-12 col-xs-12">
              <?php if(!isset($is_score)) $is_score = ""; ?>
                <label><input type="radio" value="" id="rdo_item_grade1" name="is_score" <?php echo get_checked($is_score, "")?>> 전체 </label>&nbsp;
                <label><input type="radio" value="5" id="rdo_item_grade2" name="is_score" <?php echo get_checked($is_score, "5")?>> 5점  </label>&nbsp;
                <label><input type="radio" value="4" id="rdo_item_grade3" name="is_score" <?php echo get_checked($is_score, "4")?>> 4점  </label>&nbsp;
                <label><input type="radio" value="3" id="rdo_item_grade4" name="is_score" <?php echo get_checked($is_score, "3")?>> 3점  </label>&nbsp;
                <label><input type="radio" value="2" id="rdo_item_grade5" name="is_score" <?php echo get_checked($is_score, "2")?>> 2점  </label>&nbsp;
                <label><input type="radio" value="1" id="rdo_item_grade6" name="is_score" <?php echo get_checked($is_score, "1")?>> 1점  </label>&nbsp;
                </div>
              </td>
          </tr>
          <tr>
            <th scope="row">검색항목</th>
            <td colspan="2">
            <div class="col-md-12 col-sm-12 col-xs-12">
          	
        	    <select name="sfl" id="sfl">
    				<option value="od_id" <?=get_selected($_GET['sfl'], 'od_id'); ?>>주문번호</option>
        	        <option value="mb_id||is_name" <?php echo get_selected($_GET['sfl'], "mb_id||is_name") ?>>등록자</option>
        	        <option value="it_name" <?php echo get_selected($_GET['sfl'], "it_name") ?>>상품명</option>
        	        <option value="is_content" <?php echo get_selected($_GET['sfl'], "is_content") ?>>내용</option>
        	    </select>
    
    			<label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    			
              <input type="text" name="stx" value="<?php echo $stx?>" id="stx" class="required frm_input" size="50">
            </div>
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
        
        <div class="x_title">
            <h4><span class="fa fa-check-square"></span> 게시물 목록 <small></small></h4>
            <label class="nav navbar-right"></label>
            <div class="clearfix"></div>
        </div>
        
        <div class="x_content">
          <label> 검색결과 <?php echo $total_count; ?>건</label>
            <div class="pull-right">
        		<input type="hidden" name="sst" id="sst" value="<?php echo $sst; ?>">
        		<input type="hidden" name="sod" id="sod"  value="<?php echo $sod; ?>">
        		
                <select id="sstsod" onchange="sstsod_change(this);">
                <option value="is_time,desc" <?php echo get_selected($sst.','.$sod, 'is_time,desc') ; ?>>리뷰등록일순</option>
                <option value="b.it_name,asc" <?php echo get_selected($sst.','.$sod, 'b.it_name,asc') ; ?>>상품명순</option>
                <option value="is_score,desc" <?php echo get_selected($sst.','.$sod, 'is_score,desc') ; ?>>평점높은순</option>
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
          	</div>
		</div>
	</form>
	
	<form name="flist" id="flist" action="./review_update.php" method="post">
    <input type="hidden" name="btn_submit" id="btn_submit_list" value="">
    <input type="hidden" name="token" value="<?php echo $token?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sop" value="<?php echo $sop ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">
	<input type="hidden" name="search_date" value="<?php echo $search_date ?>">
	
	<div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
            <table>
            <thead>
            <tr>
              <th colspan="15" style="text-align: right;">
                <button class="btn btn_03" type="button" id="btn_list_view_all" >전시</button>
                <button class="btn btn_03" type="button" id="btn_list_noview_all" >전시해제</button>
                <button class="btn btn_03" type="button" id="btn_list_reply_all" >일괄답글</button>
                <?php if ($is_admin == 'super') {?>
                <button class="btn btn_03" type="button" id="btn_list_best_select">베스트 리뷰선정</button>
                <?php } ?>
                <button class="btn btn_03" type="button" id="btn_list_rank">순위선정</button>
              </th>
            </tr>
            <tr>
              <th scope="col">
                  <label for="chkall" class="sound_only">구매평 전체</label>
            	  <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
              </th>
              <th scope="col">구분</th>
              <th scope="col">주문번호</th>
              <th scope="col">상품명</th>
              <th scope="col">평점</th>
              <th scope="col">포토/영상</th>
              <th scope="col">내용</th>
              <th scope="col">등록자</th>
              <th scope="col">등록일</th>
              <th scope="col">전시상태</th>
              <th scope="col">답글여부</th>
              <th scope="col">베스트 리뷰</th>
              <th scope="col">혜택지급</th>
              <th scope="col">신고여부</th>
              <th scope="col">리뷰순위</th>
            </tr>
            </thead>

            <tbody>
            <?php
            for ($i=0; $row=sql_fetch_array($result); $i++) {
                $href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
                $name = get_sideview($row['mb_id'], get_text($row['is_name']), $row['mb_email'], $row['mb_homepage']);
                $is_content = get_view_thumbnail(conv_content($row['is_content'], 1), 300);
                
                $disp_od_id = $row['od_type'].'-'.substr($row['od_id'],0,8).'-'.substr($row['od_id'],8,6);
                
                $bg = 'bg'.($i%2);
            ?>
            <tr>
              <td class="td_chk">
                <label for="chk_<?php echo $i; ?>" class="sound_only"></label>
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i; ?>">
                <input type="hidden" name="is_id[<?php echo $i; ?>]" value="<?php echo $row['is_id']; ?>">
                <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
              </td>
              <td class="td_numsmall"><?php echo ($row['is_type']=='1')?"프리미엄":"일반"?></td>
              <td class="td_category3">
                <a href="<?php echo G5_ADMIN_URL."/shop_admin/orderform.php?od_id=".$row['od_id']; ?>" target="_blank"><?php echo $disp_od_id ?></a></td>
              <td class="td_left">
                <a href="<?php echo $href; ?>" target="_blank">
                <?php echo get_it_image($row['it_id'], 50, 50); ?>
                <?php echo $row['it_name']; ?></a> <!-- 상품 화면 새 창 열림 -->
              </td>
              <td class="td_category3"><?php echo str_repeat('★', (int)$row['is_score']) ?></td>
              <td class="td_id">
              <div style="width: 100px;height: 100px;overflow: hidden">
              <?php 
                $fi_sql = " select * from lt_shop_item_use_file where is_id = '".$row['is_id']."' order by bf_no limit 1 ";
                $fi_result = sql_fetch($fi_sql);
                if($fi_result && $fi_result['bf_file']){
                    $filepath = G5_DATA_PATH.'/file/itemuse';
                    $src = G5_DATA_URL.'/file/itemuse/'.$fi_result['bf_file'];
                    
                    if($fi_result['bf_type'] == '0'){
                        echo '<video controls width="100px"><source src="'.$src.'" type="video/mp4" width="100px">Your browser does not support the video tag.</video>';
                    } else {
                        echo '<img src="'.$src.'" width="100px" >';
                    }
                }
              ?>
              </div>
              </td>
              <td class="td_left is_content" style="cursor: pointer" is_id="<?php echo $row['is_id']?>"><?php echo $is_content ?></td>
              <td class="td_category3"><?php echo $name; ?></td>
              <td class="td_datetime"><?php echo substr($row['is_time'],2,8); ?><br /><?php echo substr($row['is_time'],11,10); ?></td>
              <td class="td_postal"><?php echo ($row['is_confirm'] ? 'Y' : 'N'); ?></td>
              <td class="td_postal"><?php echo ($row['is_reply_content'] != '' ? 'Y' : 'N'); ?></td>
              <td class="td_stat"><?php echo ($row['is_best'] ? 'Y' : 'N'); ?></td>
              <td class="td_postal"><?php echo ($row['is_point'] ? 'Y' : 'N'); ?></td>
              <td class="td_postal"><?php echo ($row['is_spam'] ? 'Y' : 'N'); ?></td>
              <td class="td_postal"><input type="text" id="reviewRank_<?php echo $i; ?>" style="width:26px; text-align: center;" value="<?= $row['is_rank']?>" placeholder='-'></td>
            </tr>
            <?php
            }
        
            if ($i == 0) {
                echo '<tr><td colspan="14" class="empty_table">자료가 없습니다.</td></tr>';
            }
            ?>
            </tbody>

            <tfoot>
              <tr>
                <th colspan="15" style="text-align: right;">
                  <button class="btn btn_03" type="button" id="btn_list_view_all2" >전시</button>
                  <button class="btn btn_03" type="button" id="btn_list_noview_all2" >전시해제</button>
                  <button class="btn btn_03" type="button" id="btn_list_reply_all2">일괄답글</button>
                  <?php if ($is_admin == 'super') {?>
                  <button class="btn btn_03" type="button" id="btn_list_best_select2">베스트 리뷰선정</button>
                  <button class="btn btn_03" type="button" id="btn_list_rank2">순위선정</button>
                  <?php } ?>
                </th>
              </tr>
            </tfoot>
            </table>
          </div>
	</form>
    
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

	</div>
  </div>
</div>


<!-- Modal : 베스트 리뷰 선정 -->
<!-- <form name="fmodalbestselect" id="fmodalbestselect" method="post" onsubmit="return fmodalbestselect_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_bestselect" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">베스트 리뷰 선정 레이어 팝업</h4>
  </div>
<form name="fbest" id="fbest" action="./review_update.php" onsubmit="return fbest_submit(this)" method="post">
<input type="hidden" name="btn_submit" id="btn_submit" value="베스트">
<input type="hidden" name="token" value="<?php echo $token?>">
  <div class="modal-body">
    <div style="width: 850px; background: #eaeaea; padding: 10px; margin: 10px;">
        <p>
            ※선택한 리뷰에 대해 베스트리뷰를 선정할 수 있습니다.
        </p>
    </div>
    <div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>베스트 리뷰 선정</caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_3">
    </colgroup>
    <tbody>
      <tr>
        <th scope="row">리뷰글 번호</th>
        <td colspan="2">
          <input type="text" name="txt_review_num" value="" id="txt_review_num" required readonly="readonly" class="required frm_input" size="80"> <!-- 선택된 리뷰글 번호 -->
        </td>
      </tr>
      <tr>
        <th scope="row">베스트 리뷰 여부</th>
        <td colspan="2">
          <label><input type="radio" checked="" value="1" id="rdo_best_selectY" name="rdo_best_selectYN"> 선정</label>  &nbsp;
          <label><input type="radio" value="0" id="rdo_best_selectN" name="rdo_best_selectYN"> 선정 안함</label>
        </td>
      </tr>
      <tr>
        <th rowspan="3">혜택</th>
      </tr>
      <tr>
        <td colspan="3">
          <label><input type="radio" checked="" value="1" id="rdo_best_pointY" name="rdo_best_pointYN" onclick="$('#txt_best_point_num').prop('readonly',false);"> 적립금</label>&nbsp;
          <label><input type="radio" value="0" id="rdo_best_pointN" name="rdo_best_pointYN" onclick="$('#txt_best_point_num').prop('readonly',true);$('#txt_best_point_num').val('');"> 혜택 지급하지 않음</label>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <input type="number" min="1" name="txt_best_point_num" value="" id="txt_best_point_num" class="frm_input" > 원 적립
        </td>
      </tr>
    </tbody>
  </table>

    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" class="btn btn-success" id="btn_best_save" value="적용"></input>
  </div>
  </form>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 베스트 리뷰 선정 -->


<!-- Modal : 일괄 답글 -->
<!-- <form name="fmodalreplyall" id="fmodalreplyall" method="post" onsubmit="return fmodalreplyall_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_replyall" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">
<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">일괄 답글 레이어 팝업</h4>
  </div>
<form name="freply" id="freply" action="./review_update.php" onsubmit="return freply_submit(this)" method="post">
<input type="hidden" name="btn_submit" id="btn_submit" value="일괄답글">
<input type="hidden" name="token" value="<?php echo $token?>">

  <div class="modal-body">
    <div style="width: 850px; background: #eaeaea; padding: 10px; margin: 10px;">
        <p>
            ※고객의 리뷰를 일괄로 작성할 수 있습니다.
        </p>
    </div>
    <div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>판매자 답글</caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_3">
    </colgroup>
    <tbody>
      <tr>
        <th scope="row">리뷰글 번호</th>
        <td colspan="2">
          <input type="text" name="txt_selected_review_num" value="" id="txt_selected_review_num" required readonly class="required frm_input" size="80"> <!-- 선택된 리뷰글 번호 --> <!-- 선택된 리뷰글 번호 -->
        </td>
      </tr>
      <tr>
        <th scope="row">판매자 답글</th>
        <td colspan="2">
          <textarea class="form-control" rows="5" id="review_reply" name="review_reply" onkeyup="len_chk()"></textarea>
        </td>
      </tr>
    </tbody>
  </table>

    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" class="btn btn-success" id="btn_reply_save" value="등록"></input>
  </div>
</form>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 일괄 답글 -->

<div id="popup"></div>
<script>
$(function() {

	$(".is_content").click(function(){
		var is_id = $(this).attr("is_id");

        $.post(
        		"./review_detail.php",
                { is_id : is_id, token: '<?php echo $token?>' },
                function(data) {
                	$("#popup").empty().html(data);

                	$(".modal_review_detail").modal("show");
                }
            );
	});

	$("#btn_list_view_all,#btn_list_view_all2").click(function(){

		if (!is_checked("chk[]")) {
	        alert("전시 하실 항목을 하나 이상 선택하세요.");
	        return false;
	    }

		$("#btn_submit_list").val("전시");
		$("#flist").submit();
	});

	$("#btn_list_noview_all,#btn_list_noview_all2").click(function(){

		if (!is_checked("chk[]")) {
	        alert("전시해제 하실 항목을 하나 이상 선택하세요.");
	        return false;
	    }

		$("#btn_submit_list").val("전시해제");
		$("#flist").submit();
	});
	

	$("#btn_list_reply_all,#btn_list_reply_all2").click(function(){

		if (!is_checked("chk[]")) {
	        alert("일괄 답글 하실 항목을 하나 이상 선택하세요.");
	        $("#txt_selected_review_num").val("");
	        return false;
	    }
		var $chk = $("input[name='chk[]']:checked");

		var sep = "";
		var txt_selected_review_num = "";
    	for (var i=0; i<$chk.size(); i++)
		{
    		 var k = $($chk[i]).val();
             var is_id = $("input[name='is_id["+k+"]']").val();

             txt_selected_review_num += sep+is_id;
             sep = ",";
		}

		$("#txt_selected_review_num").val(txt_selected_review_num);
		$("#modal_replyall").modal("show");
        
	});

	$("#btn_list_best_select,#btn_list_best_select2").click(function(){

		if (!is_checked("chk[]")) {
	        alert("베스트 리뷰 선정 하실 항목을 하나 이상 선택하세요.");
	        $("#txt_review_num").val("");
	        return false;
	    }
		var $chk = $("input[name='chk[]']:checked");

		var sep = "";
		var txt_selected_review_num = "";
    	for (var i=0; i<$chk.size(); i++)
		{
    		 var k = $($chk[i]).val();
             var is_id = $("input[name='is_id["+k+"]']").val();

             txt_selected_review_num += sep+is_id;
             sep = ",";
		}

		$("#txt_review_num").val(txt_selected_review_num);
		$("#modal_bestselect").modal("show");
        
	});
	
  $("#btn_list_rank,#btn_list_rank2").click(function(){
    if (!is_checked("chk[]")) {
	    alert("순위선정 하실 항목을 하나 이상 선택하세요.");
	    $("#txt_review_num").val("");
	    return false;
	  }
    var $chk = $("input[name='chk[]']:checked");
    var rankInfo = {};
    $("#btn_submit_list").val("순위선정");
    for (let i=0; i<$chk.size(); i++) {
      let k = $($chk[i]).val();
      let is_id = $("input[name='is_id["+k+"]']").val();
      let rank = $("#reviewRank_"+k).val();
      rankInfo[is_id] = rank;
    }
    $.ajax({
      url: "./review_update.php",
      method: "POST",
      data: {
          "token": '<?php echo $token?>',
          "btn_submit_list": '순위선정',
          'reviewRank' : rankInfo,
      },
      dataType: "json",
      async: false,
      cache: false,
      success: function(result) {
        if(result =='success') {
          alert('완료되었습니다.');
          location.reload();
        } else {
          alert(result);
        }
      }
    });
  });

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
    window.addEventListener("keydown", (e) => {
      if (e.keyCode == 13) {
          document.getElementById('flistSearch').submit();
      }
    })
});

function fhelplist_submit(f)
{

}

function len_chk(){
    var frm = document.freply.review_reply;
    
    if(frm.value.length > 400){
         alert("글자수는 영문400, 한글200자로 제한됩니다.!");
         frm.value = frm.value.substring(0,400);
         frm.focus();
    }

}

function freply_submit(f)
{
	if(f.review_reply.value.length <= 0){
        alert("판매자 답글을 입력하세요.");
        f.review_reply.focus();
        return false;
	}

	f.btn_submit.value = "일괄답글";

	return true;
}

function fbest_submit(f)
{

	f.btn_submit.value = "베스트";

	return true;
}


</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>