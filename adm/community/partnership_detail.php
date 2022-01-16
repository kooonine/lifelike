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

	<!-- <form name="fpartnershipdetail" id="fpartnershipdetail" method="post" onsubmit="return fpartnershipdetail_submit(this);" data-parsley-validate class="form-horizontal form-label-left"> -->
	<!-- <input type="hidden" name="token" value="" id="token"> -->

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> 제휴내역<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

    <div class="x_content">
      <div class="tbl_frm01 tbl_wrap">
          <table>
          <caption>제휴내역</caption>
          <colgroup>
              <col class="grid_4">
              <col>
              <col class="grid_3">
          </colgroup>
          <tbody>
            <tr>
                <th scope="row">회사명</th>
                <td colspan="3">TEXT TEXT TEXT TEXT</td>
            </tr>
            <tr>
                <th scope="row">담당자</th>
                <td colspan="3">TEXT</td>
            </tr>
            <tr>
                <th scope="row">연락처</th>
                <td colspan="3">010-1234-5678</td>
            </tr>
            <tr>
                <th scope="row">이메일</th>
                <td colspan="3">abc@naver.com</td>
            </tr>
            <tr>
                <th scope="row">문의내용</th>
                <td colspan="3">
                  <textarea class="form-control" rows="5" id="help_content"></textarea>
                </td>
            </tr>
          </tbody>
          </table>
      </div>
      <div class="form-group">
        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
          <button class="btn btn_02" type="button" id="btn_clear">목록이동</button>
        </div>
      </div>
    </div>

	<!-- </form> -->

	</div>
  </div>
</div>



<script>

  //목록이동 버튼
  $("#btn_clear").click(function(){

    if ( confirm("목록으로 이동하면 입력한 정보는 저장되지 않습니다.") ) {
      location.href="partnership_management.html";
    }
    
  });


function fpartnershipdetail_submit(f)
{

}
</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>