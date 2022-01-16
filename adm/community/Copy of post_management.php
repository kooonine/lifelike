<?php
$sub_menu = "";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '게시물 관리';
include_once ('../admin.head.php');

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
                            	<input type='text' class="form-control" id="start_date" />
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
                      <select id="bo_table" name="bo_table">
                          <option value="" selected>전체</option>
                          <?php
                          $sql = " select bo_table, bo_subject from {$g5['board_table']} order by bo_me_code asc";
                          $result = sql_query($sql);
                          for ($i=0; $row=sql_fetch_array($result); $i++) {
                              echo '<option value="'.$row["bo_table"].'">'.$row["bo_subject"].'</option>';
                          }
                          ?>
                      </select>
                    </td>
                </tr>
                <tr>
                  <th scope="row">게시글 찾기</th>
                  <td colspan="2">
                  <label for="sfl" class="sound_only">검색조건</label>
            	    <select name="sfl" id="sfl">
            	        <option value="wr_subject||wr_content"<?php echo get_selected($_GET['sfl'], "wr_subject||wr_content") ?>>제목+내용</option>
            	        <option value="wr_subject"<?php echo get_selected($_GET['sfl'], "wr_subject") ?>>제목</option>
            	        <option value="wr_content"<?php echo get_selected($_GET['sfl'], "wr_content") ?>>내용</option>
            	        <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id") ?>>회원아이디</option>
            	        <option value="wr_name"<?php echo get_selected($_GET['sfl'], "wr_name") ?>>이름</option>
            	    </select>
            
                	<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
                    <div class="sch_ipt">
                    	<input type="text" name="stx" id="stx" value="<?php echo $text_stx ?>" class="frm_input" required  maxlength="20">
            		</div>
            		
                  </td>
                </tr>
                <tr>
                  <th scope="row">스팸글 관리</th>
                  <td colspan="2">
                    <select id="category3" name="category3">
                        <option value="all" selected>전체</option>
                        <option value="nospam">스팸글 제외</option>
                        <option value="onlyspam">스팸글만 보기</option>
                    </select>
                  </td>
                </tr>
                <tr>
                    <th scope="row">답변상태</th>
                    <td colspan="2">
                      <input type="radio" checked="" value="1" id="rdo_reply_status1" name="rdo_reply_status"> 전체보기  &nbsp;
                      <input type="radio" value="2" id="rdo_reply_status2" name="rdo_reply_status"> 답변 전  &nbsp;
                      <input type="radio" value="3" id="rdo_reply_status3" name="rdo_reply_status"> 답변 완료
                    </td>
                </tr>
                <tr>
                    <th scope="row">댓글여부</th>
                    <td colspan="2">
                      <input type="radio" checked="" value="1" id="rdo_comment_YN1" name="rdo_comment_YN"> 전체보기  &nbsp;
                      <input type="radio" value="2" id="rdo_comment_YN2" name="rdo_comment_YN"> 있음  &nbsp;
                      <input type="radio" value="3" id="rdo_comment_YN3" name="rdo_comment_YN"> 없음
                    </td>
                </tr>
                <tr>
                    <th scope="row">첨부파일 여부</th>
                    <td colspan="2">
                      <input type="radio" checked="" value="1" id="rdo_attach_YN1" name="rdo_attach_YN"> 전체보기  &nbsp;
                      <input type="radio" value="2" id="rdo_attach_YN2" name="rdo_attach_YN"> 있음  &nbsp;
                      <input type="radio" value="3" id="rdo_attach_YN3" name="rdo_attach_YN"> 없음
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
			

          <!-- 댓글 관리 탭 -->
  				<div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="comment-manage-tab">
            <div class="tbl_frm01 tbl_wrap">
                <table>
                <caption>댓글 관리</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                    <col class="grid_3">
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">작성일</th>
                    <td colspan="2">
                      <div class='input-group date' id='start_date2' style="float: left; margin-right: 10px;">
                          <input type='text' class="form-control" id="txt_start_date2" />
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                      </div>
                      <div class='input-group date' id='end_date2' style="float: left; margin-right: 10px;">
                          <input type='text' class="form-control" id="txt_end_date2" />
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                      </div>
                      <div class="btn-group" data-toggle="buttons-radio">
                        <button type="button" class="btn btn_02" name="dateBtn" data="today">오늘</button>
                        <button type="button" class="btn btn_02" name="dateBtn" data="3d">3일</button>
                        <button type="button" class="btn btn_02 btn_03" name="dateBtn" data="1w">1주</button>
                        <button type="button" class="btn btn_02" name="dateBtn" data="1m">1개월</button>
                        <button type="button" class="btn btn_02" name="dateBtn" data="3m">3개월</button>
                      </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">게시판 선택</th>
                    <td colspan="2">
                      <select id="category4" name="category4">
                          <option value="all" selected>전체</option>
                          <option value="tester">체험단</option>
                          <option value="review">리뷰</option>
                          <option value="notice">공지사항</option>
                          <option value="livingKnowhow">리빙노하우</option>
                      </select>
                    </td>
                </tr>
                <tr>
                  <th scope="row">게시글 찾기</th>
                  <td colspan="2">
                    <select id="category5" name="category5">
                        <option value="all" selected>전체</option>
                        <option value="title">제목</option>
                        <option value="content">내용</option>
                        <option value="writer">작성자</option>
                        <option value="itemname">상품명</option>
                        <option value="id">아이디</option>
                    </select> &nbsp;
                    <input type="text" name="txt_comment_search" value="" id="txt_comment_search" required class="required frm_input" size="50">
                  </td>
                </tr>
                <tr>
                  <th scope="row">스팸글 관리</th>
                  <td colspan="2">
                    <select id="category6" name="category6">
                        <option value="all" selected>전체</option>
                        <option value="nospam">???</option>
                    </select>
                  </td>
                </tr>
                </tbody>
                </table>
            </div>
            <div class="form-group">
        			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
                <button class="btn btn_02" type="button" id="btn_clear2">초기화</button>
        			  <input type="submit" class="btn btn-success" value="검색" id="btn_search2"></input>
        			</div>
      		  </div>
          </div>
			  </div>
			</div>
	  </div>
  <!-- </form> -->

<div class="hidden" id="tab1_list_table">
	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 게시물 목록 <small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	  <div class="x_content">
      <label>[오늘 등록된 새글 0건]</label>&nbsp;<label> 검색결과 0건</label>
      <div style="float: right;">
        <select id="category7" name="category7">
            <option value="default">기본정렬</option>
            <option value="viewcount">조회수 많은 수</option>
        </select>
        <select id="category8" name="category8">
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
              <label for="chkall" class="sound_only">게시글 전체</label>
              <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
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
        <tr>
          <td class="td_chk">
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_01">
          </td>
          <td class="td_category2">체험단</td>
          <td class="td_left">
            <a href="" data-toggle="modal" data-target="#modal_post_member">texttexttexttexttexttexttexttext</a>
          </td>
          <td class="td_auth">답변완료</td>
          <td class="td_id">김빛나리(asdfasdf1234)</td>
          <td class="td_datetime">YYYY-MM-DD<br />HH:MM:SS</td>
          <td class="td_etc">000</td>
          <td class="td_etc">000</td>
          <td class="td_id">
            <button class="btn btn_02" type="button" id="btn_reply" data-toggle="modal" data-target="#modal_post_admin">답변하기</button>
          </td>
        </tr>
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
    if (f.stx.value.length < 2) {
        alert("검색어는 두글자 이상 입력하십시오.");
        f.stx.select();
        f.stx.focus();
        return false;
    }

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

//날짜 버튼
$("button[name='dateBtn']").click(function(){

  $('button[name="dateBtn"]').removeClass('btn_03');
  $(this).addClass('btn_03');

});

//초기화 버튼
$("#btn_clear, #btn_clear2").click(function(){

  $("#txt_start_date, #txt_end_date, #txt_start_date2, #txt_end_date2").val("");
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

	
	var startD = moment().subtract(6, 'days');
	var endD = moment();
	
	$('#start_date').daterangepicker({
		"autoApply": true,
		"startDate": startD,
		"endDate": endD,
		"opens": "right",
		locale: {
	        "format": "YYYY-MM-DD",
	        "separator": " - ",
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

	
  /*$('#start_date, #start_date2').datetimepicker();
  $('#end_date, #end_date2').datetimepicker({
      useCurrent: false //Important! See issue #1075
  });*/
  $("#start_date, #start_date2").on("dp.change", function (e) {
      $('#end_date').data("DateTimePicker").minDate(e.date);
      $('#end_date2').data("DateTimePicker").minDate(e.date);
  });
  $("#end_date, #end_date2").on("dp.change", function (e) {
      $('#start_date').data("DateTimePicker").maxDate(e.date);
      $('#start_date2').data("DateTimePicker").maxDate(e.date);
  });
});

</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>