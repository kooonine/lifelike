<?php
$sub_menu = "";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '';
include_once ('../admin.head.php');

?>


<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	<!-- <form name="fpartnershiplist" id="fpartnershiplist" method="post" onsubmit="return fpartnershiplist_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
	<!-- <input type="hidden" name="token" value="" id="token"> -->

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 제휴문의<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

    <div class="x_content">
      <div class="tbl_frm01 tbl_wrap">
          <table>
          <caption>제휴문의</caption>
          <colgroup>
              <col class="grid_4">
              <col>
              <col class="grid_3">
          </colgroup>
          <tbody>
          <tr>
              <th scope="row">기간설정</th>
              <td colspan="2">
                <div class='input-group date' id='start_date' style="float: left; margin-right: 10px;">
                    <input type='text' class="form-control" id="txt_start_date" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <div class='input-group date' id='end_date' style="float: left; margin-right: 10px;">
                    <input type='text' class="form-control" id="txt_end_date" />
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
              <th scope="row">처리상태</th>
              <td colspan="2">
                <input type="radio" checked="" value="1" id="rdo_reply_status1" name="rdo_reply_status"> 전체  &nbsp;
                <input type="radio" value="2" id="rdo_reply_status2" name="rdo_reply_status"> 답변완료  &nbsp;
                <input type="radio" value="3" id="rdo_reply_status3" name="rdo_reply_status"> 미답변
              </td>
          </tr>
          <tr>
            <th scope="row">상세검색</th>
            <td colspan="2">
              <input type="text" name="txt_detail_search" value="" id="txt_detail_search" required class="required frm_input" size="50">
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
    </div>

    <div class="x_content hidden" style="margin-top: 20px;" id="tab_list_table">
      <label> 검색결과 0건</label>
      <div style="float: right;">
        <select id="category3" name="category3">
            <option value="10">10개씩 보기</option>
            <option value="30">30개씩 보기</option>
            <option value="50">50개씩 보기</option>
        </select>
      </div><br /><br />
      <div class="tbl_head01 tbl_wrap" style="margin-bottom: 50px">
        <table>
        <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">회사명</th>
          <th scope="col">담당자</th>
          <th scope="col">연락처</th>
          <th scope="col">이메일 주소</th>
          <th scope="col">처리상태</th>
          <th scope="col">처리자</th>
          <th scope="col">처리일</th>
          <th scope="col">문의일</th>
        </tr>
        </thead>

        <tbody>
        <tr>
          <td class="td_chk">00</td>
          <td class="td_category2"><a href="partnership_detail.html">TEXT TEXT TEXT</a></td>
          <td class="td_category2">TEXT</td>
          <td class="td_category2">02-123-4567</td>
          <td class="td_category2">abc@naver.com</td>
          <td class="td_auth">답변완료</td>
          <td class="td_id">ID(관리자)</td>
          <td class="td_datetime">YYYY-MM-DD</td>
          <td class="td_datetime">YYYY-MM-DD</td>
        </tr>
        </tbody>
        </table>

        <nav style="text-align: center;">
	        <ul class="pagination">
	        <li><a href="#"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
	        <li><a href="#">1</a></li>
	        <li><a href="#">2</a></li>
	        <li><a href="#">3</a></li>
	        <li><a href="#">4</a></li>
	        <li><a href="#">5</a></li>
	        <li><a href="#"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
	        </ul>
	      </nav>

      </div>
		</div>



	<!-- </form> -->

	</div>
  </div>
</div>


<style>
.ui-datepicker{ width: 320px; }
.ui-datepicker select.ui-datepicker-month{ width:30%; }
.ui-datepicker select.ui-datepicker-year{ width:30%; }
</style>


<script>

  //날짜 버튼
  $("button[name='dateBtn']").click(function(){

    $('button[name="dateBtn"]').removeClass('btn_03');
    $(this).addClass('btn_03');

  });

  //초기화 버튼
  $("#btn_clear").click(function(){

    $("#txt_start_date, #txt_end_date").val("");
    $('button[name="dateBtn"]').removeClass('btn_03').addClass('btn_02');
    $("#rdo_reply_status1").prop("checked", true);
    $("#txt_detail_search").val("");

  });

  //게시물 검색 버튼
  $("#btn_search").click(function(){

    $("#tab_list_table").removeClass("hidden");

  });

  $(function() {

    //$('#start_date').datetimepicker();
    //$('#end_date').datetimepicker({
    //    useCurrent: false //Important! See issue #1075
    //});
    $("#start_date").on("dp.change", function (e) {
        $('#end_date').data("DateTimePicker").minDate(e.date);
    });
    $("#end_date").on("dp.change", function (e) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);
    });
  });

function fpartnershiplist_submit(f)
{

}
</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>