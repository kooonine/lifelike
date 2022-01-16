<?php
$sub_menu = "900230";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = '리뷰관리';
include_once ('../admin.head.php');

$sql_common = "  from {$g5['g5_shop_item_use_table']} a
                        left join {$g5['g5_shop_item_table']} b on (a.it_id = b.it_id)
                        left join {$g5['member_table']} c on (a.mb_id = c.mb_id) ";

$sql_search = " where (1)";

if ($sca != "") {
    $sql_search .= " and ca_id like '$sca%' ";
}

if (!$sst) {
    $sst = "is_id";
    $sod = "desc";
}

if (!$search_date) {
    $search_date_s = date_create(G5_TIME_YMDHIS);
    $search_date_e = date_create(G5_TIME_YMDHIS);
    date_add($search_date_s, date_interval_create_from_date_string('-6 days'));
    
    $search_date = date_format($search_date_s,"Y-m-d").' ~ '.date_format($search_date_e,"Y-m-d");
}
$search_dates = explode("~", $search_date);
$sql_search .= " and (is_time between '".trim($search_dates[0])." 00:00:00' and '".trim($search_dates[1])." 23:59:59') ";

$sql_common .= $sql_search;

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = " select *
      $sql_common
      order by $sst $sod, is_id desc
      limit $from_record, $rows ";
      $result = sql_query($sql);
      
//$qstr = 'page='.$page.'&amp;sst='.$sst.'&amp;sod='.$sod.'&amp;stx='.$stx;
$qstr .= ($qstr ? '&amp;' : '').'sca='.$sca.'&amp;save_stx='.$stx;
      
//echo $sql;
?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<!-- <form name="freviewlist" id="freviewlist" method="post" onsubmit="return freviewlist_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
	<!-- <input type="hidden" name="token" value="" id="token"> -->

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 구매평 관리<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

    <div class="x_content">
    <form name="flist" class="local_sch01 local_sch">
	<input type="hidden" name="page" value="<?php echo $page; ?>">
	<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">

      <div class="tbl_frm01 tbl_wrap">
          <table>
          <caption>구매평 관리</caption>
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
              <th scope="row">카테고리</th>
              <td colspan="2">
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
              </td>
          </tr>
          <tr>
              <th scope="row">구분</th>
              <td colspan="2">
                <input type="radio" checked="" value="1" id="rdo_review_type1" name="rdo_review_type"> 전체  &nbsp;
                <input type="radio" value="2" id="rdo_review_type2" name="rdo_review_type"> 일반 구매평  &nbsp;
                <input type="radio" value="3" id="rdo_review_type3" name="rdo_review_type"> 프리미엄 구매평
              </td>
          </tr>
          <tr>
              <th scope="row">평점</th>
              <td colspan="2">
                <input type="radio" checked="" value="" id="rdo_item_grade1" name="is_score"> 전체  &nbsp;
                <input type="radio" value="5" id="rdo_item_grade2" name="is_score"> 5점  &nbsp;
                <input type="radio" value="4" id="rdo_item_grade3" name="is_score"> 4점  &nbsp;
                <input type="radio" value="3" id="rdo_item_grade4" name="is_score"> 3점  &nbsp;
                <input type="radio" value="2" id="rdo_item_grade5" name="is_score"> 2점  &nbsp;
                <input type="radio" value="1" id="rdo_item_grade6" name="is_score"> 1점  &nbsp;
              </td>
          </tr>
          <tr>
            <th scope="row">상세검색</th>
            <td colspan="2">
              <input type="text" name="stx" id="stx" value="<?php echo $stx; ?>" class="frm_input" size="50">
            </td>
          </tr>
          </tbody>
          </table>
      </div>
      <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
          <button class="btn btn_02" type="button" id="btn_clear">초기화</button>
          <input type="submit" class="btn btn-success" value="검색" id="btn_search"></input>
        </div>
      </div>
      
	</form>
    </div>

    <div id="tab_list_table">
    	  <div class="x_content">
          <label> 검색결과 <?php echo $total_count; ?>건</label>
          <div style="float: right;">
            <select id="category3" name="category3">
                <option value="1">리뷰등록일순</option>
                <option value="2">상품명순</option>
                <option value="3">평점높은순</option>
            </select>
            <select id="category4" name="category4">
                <option value="10">10개씩 보기</option>
                <option value="30">30개씩 보기</option>
                <option value="50">50개씩 보기</option>
            </select>
          </div><br /><br />
          
          
          <div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
            <table>
            <thead>
            <tr>
              <th colspan="14" style="text-align: right;">
                <button class="btn btn_03" type="button" id="btn_list_reply_all" data-toggle="modal" data-target="#modal_replyall">일괄답글</button>
                <button class="btn btn_03" type="button" id="btn_list_best_select" data-toggle="modal" data-target="#modal_bestselect">베스트 리뷰선정</button>
              </th>
            </tr>
            <tr>
              <th scope="col">
                  <label for="chkall" class="sound_only">구매평 전체</label>
            	  <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
              </th>
              <th scope="col">구분</th>
              <th scope="col">상품번호</th>
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
            </tr>
            </thead>

            <tbody>
            <?php
            for ($i=0; $row=sql_fetch_array($result); $i++) {
                $href = G5_SHOP_URL.'/item.php?it_id='.$row['it_id'];
                $name = get_sideview($row['mb_id'], get_text($row['is_name']), $row['mb_email'], $row['mb_homepage']);
                $is_content = get_view_thumbnail(conv_content($row['is_content'], 1), 300);
        
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
              <td class="td_category3"><?php echo $row['it_id']; ?></td>
              <td class="td_left">
                <a href="<?php echo $href; ?>" target="_blank"><?php echo $row['it_name']; ?></a> <!-- 상품 화면 새 창 열림 -->
              </td>
              <td class="td_category3"><?php echo str_repeat('★', (int)$row['is_score']) ?></td>
              <td class="td_id"><?php echo get_it_image($row['it_id'], 50, 50); ?></td>
              <td class="td_auth">
                <a href="" data-toggle="modal" data-target="#modal_review_detail"><?php echo get_text($row['is_subject']) ?></a>
              </td>
              <td class="td_category3"><?php echo $name; ?></td>
              <td class="td_datetime">YYYY-MM-DD<br />HH:MM:SS</td>
              <td class="td_postal"><?php echo ($row['is_confirm'] ? 'Y' : 'N'); ?></td>
              <td class="td_postal"><?php echo ($row['is_reply_subject'] != '' ? 'Y' : 'N'); ?></td>
              <td class="td_stat"><?php echo ($row['is_best'] ? 'Y' : 'N'); ?></td>
              <td class="td_postal">-</td>
              <td class="td_postal">-</td>
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
                <th colspan="14" style="text-align: right;">
                  <button class="btn btn_03" type="button" id="btn_list_reply_all" data-toggle="modal" data-target="#modal_replyall">일괄답글</button>
                  <button class="btn btn_03" type="button" id="btn_list_best_select" data-toggle="modal" data-target="#modal_bestselect">베스트 리뷰선정</button>
                </th>
              </tr>
            </tfoot>
            </table>
          </div>
    		</div>
        </div>
	<!-- </form> -->

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
          <input type="text" name="txt_selected_review_num" value="" id="txt_selected_review_num" required class="required frm_input" size="80"> <!-- 선택된 리뷰글 번호 -->
        </td>
      </tr>
      <tr>
        <th scope="row">베스트 리뷰 여부</th>
        <td colspan="2">
          <input type="radio" checked="" value="Y" id="rdo_best_selectY" name="rdo_best_selectYN"> 선정  &nbsp;
          <input type="radio" value="N" id="rdo_best_selectN" name="rdo_best_selectYN"> 선정 안함
        </td>
      </tr>
      <tr>
        <th rowspan="3">혜택</th>
      </tr>
      <tr>
        <td colspan="3">
          <input type="radio" checked="" value="Y" id="rdo_best_pointY" name="rdo_best_pointYN"> 적립금  &nbsp;
          <input type="radio" value="N" id="rdo_best_pointN" name="rdo_best_pointYN"> 혜택 지급하지 않음
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <input type = "number" min = "1" name="txt_best_point_num" value="" id="txt_best_point_num" required class="required frm_input"> 원 적립
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
          <input type="text" name="txt_selected_review_num" value="" id="txt_selected_review_num" required class="required frm_input" size="80"> <!-- 선택된 리뷰글 번호 --> <!-- 선택된 리뷰글 번호 -->
        </td>
      </tr>
      <tr>
        <th scope="row">판매자 답글</th>
        <td colspan="2">
          <textarea class="form-control" rows="5" id="review_reply" onkeyup="len_chk()"></textarea>
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
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 일괄 답글 -->


<!-- Modal : 리뷰내용 상세보기 -->
<!-- <form name="fmodalreviewdetail" id="fmodalreviewdetail" method="post" onsubmit="return fmodalreviewdetail_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_review_detail" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">리뷰내용 상세보기 레이어 팝업</h4>
  </div>
  <div class="modal-body">
    <div style="width: 850px; background: #eaeaea; padding: 10px; margin: 10px;">
        <p>
            ※상품을 구매한 회원이 작성한 리뷰입니다.<br>
            ※욕설, 허위사실, 도배/중복등록 등의 리뷰는 [신고하기]를 통해 삭제 요청할 수 있습니다.
        </p>
    </div>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>리뷰내용 상세보기</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr rowspan="3">
          <th scope="row">문의자</th>
          <td>이름(ID)</td>
          <th scope="row">문의일</th>
          <td>YYYY-MM-DD HH:MM</td>
        </tr>
        <tr rowspan="3">
          <th scope="row">상품주문번호</th>
          <td>0000000000</td>
          <th scope="row">상품명</th>
          <td>TEXT TEXT TEXT</td>
        </tr>
        <tr>
            <th scope="row">평점</th>
            <td colspan="3">★★★★★</td>
        </tr>
        <tr>
          <th rowspan="3">내용</th>
        </tr>
        <tr>
          <td colspan="3">
            TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT TEXT
          </td>
        </tr>
        <tr>
          <td colspan="3">
            
          </td>
        </tr>
        </tbody>
      </table>

      <table>
      <caption>리뷰내용 상세보기 정보</caption>
      <colgroup>
          <col class="grid_4">
          <col>
          <col class="grid_3">
      </colgroup>
      <tbody>
      <tr rowspan="3">
        <th scope="row">구분</th>
        <td>프리미엄 구매평</td>
        <th scope="row">전시상태</th>
        <td>Y</td>
      </tr>
      <tr rowspan="3">
        <th scope="row">답글여부</th>
        <td>N</td>
        <th scope="row">베스트리뷰</th>
        <td>선정상태 : N</td>
      </tr>
      </tbody>
    </table>

    <table>
    <caption>판매자 답글</caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_3">
    </colgroup>
    <tbody>
      <tr>
        <th scope="row">판매자 답글</th>
        <td colspan="2">
          <textarea class="form-control" rows="5" id="review_reply"></textarea>
        </td>
      </tr>
    </tbody>
  </table>

    </div>
  </div>
  <div class="modal-footer">
    <div style="float: left;">
      <button type="button" class="btn btn-success" data-dismiss="modal" data-toggle="modal" data-target="#modal_reviewspam">리뷰신고</button>
    </div>
    <input type="submit" class="btn btn-success" id="btn_review_reply" value="답글 등록"></input><br /><br /><br />
      <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 리뷰내용 상세보기 -->



<!-- Modal : 리뷰글 신고하기 -->
<!-- <form name="fmodalreviewspam" id="fmodalreviewspam" method="post" onsubmit="return fmodalreviewspam_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
<div id="modal_reviewspam" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">리뷰글 신고하기 레이어 팝업</h4>
  </div>
  <div class="modal-body">
    <div style="width: 550px; background: #eaeaea; padding: 10px; margin: 10px;">
        <p>
            ※신고로 비노출의 경우, 고객에게 적립된 포인트는 회수되지 않습니다.<br>
        </p>
    </div>
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>리뷰글 신고하기</caption>
        <colgroup>
            <col class="grid_4">
            <col>
            <col class="grid_3">
        </colgroup>
        <tbody>
        <tr>
          <td>
            <input type="radio" checked="" value="1" id="rdo_review_spam_type1" name="rdo_review_spam_type"> 욕설/비방  &nbsp; &nbsp;
            <input type="radio" value="2" id="rdo_review_spam_type2" name="rdo_review_spam_type"> 광고/홍보글  &nbsp; &nbsp;
            <input type="radio" value="3" id="rdo_review_spam_type3" name="rdo_review_spam_type"> 개인정보유출  &nbsp; &nbsp;
            <input type="radio" value="4" id="rdo_review_spam_type4" name="rdo_review_spam_type"> 게시글도배
          </td>
        </tr>
        <tr>
          <td>
            <input type="radio" checked="" value="5" id="rdo_review_spam_type5" name="rdo_review_spam_type"> 음란/선정성  &nbsp; &nbsp;
            <input type="radio" value="6" id="rdo_review_spam_type6" name="rdo_review_spam_type"> 저작권침해  &nbsp; &nbsp;
            <input type="radio" value="7" id="rdo_review_spam_type7" name="rdo_review_spam_type"> 기타
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
    <input type="submit" class="btn btn-success" id="btn_review_spam" value="신고하기"></input>
  </div>
</div>
<!-- Modal content-->
</div>
</div>
<!-- </form> -->
<!-- Modal : 리뷰글 신고하기 -->

<script>

  //초기화 버튼
  $("#btn_clear").click(function(){

    $("#txt_start_date, #txt_end_date").val("");
    $('button[name="dateBtn"]').removeClass('btn_03').addClass('btn_02');
    $("#category1, #category2").val("all").prop("selected", true);
    $("#rdo_review_type1, #rdo_item_grade1").prop("checked", true);
    $("#txt_detail_search").val("");

  });

  //게시물 검색 버튼
  $("#btn_search").click(function(){

    $("#tab_list_table").removeClass("hidden");

  });

  //일괄 답글 팝업 등록 버튼
  $("#btn_reply_save").click(function(){

    alert("답변이 등록되었습니다.");
    $("#modal_replyall").modal("hide");

  });

    //베스트 리뷰 선정 팝업 적용 버튼
    $("#btn_best_save").click(function(){

      alert("베스트 리뷰 적용되었습니다.");
      $("#modal_bestselect").modal("hide");

    });


    $('input[type="radio"][name="rdo_best_pointYN"]').click(function(){

      if( $("#rdo_best_pointN").prop('checked') ) {
        $("#txt_best_point_num").prop('disabled', true);
      }
      else {
        $("#txt_best_point_num").prop('disabled', false);
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

  //글자수 제한 체크 -> 정확한 글자수는 협의 필요
  function len_chk(){
    var frm = document.insertFrm.test;

    if(frm.value.length > 400){
         alert("글자수는 영문400, 한글200자로 제한됩니다.!");
         frm.value = frm.value.substring(0,400);
         frm.focus();
    }

  }

function freviewlist_submit(f)
{

}
</script>




<!-- @END@ 내용부분 끝 -->

<?php
include_once ('../admin.tail.php');
?>