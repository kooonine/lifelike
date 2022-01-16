<?php
$sub_menu = "20";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = 'PUSH 메시지 발송';
include_once ('../admin.head.php');

$token = get_admin_token();
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">

	  <div class="x_title">
		<h4><span class="fa fa-check-square"></span> PUSH 메시지 발송<small></small></h4>
		<label class="nav navbar-right"></label>
		<div class="clearfix"></div>
	  </div>

	<form name="fconfigform" id="fconfigform" method="post" data-parsley-validate class="form-horizontal form-label-left">
	<input type="hidden" name="token" value="<?php echo $token ?>" id="token">

	  <div class="tbl_frm01 tbl_wrap">
      <table>
        <colgroup>
        <col class="grid_4">
        <col>
        </colgroup>
        <tr>
          <th scope="col">메시지 제목</th>
          <td >
            <div class="container col-md-12 col-sm-12 col-xs-12">
          		<input type="text" name="msg_title"  value="" id="msg_title" class="form-control" >
          	</div>
          </td>
        </tr>
        <tr>
          <th>내용입력</th>
          <td >
              <div class="col-md-12 col-sm-12 col-xs-12">
                <textarea class="resizable_textarea form-control" id="msg_body" name="msg_body"></textarea>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                <span id="msg_body_counter"><font color='red'>0</font>/300</span>
              </div>
          </td>
        </tr>
        <tr>
          <th scope="col">플랫폼</th>
          <td >
              <div class="col-md-12 col-sm-12 col-xs-12">
              	<label><input type="radio" name="sendType" value="" id="sendType0" checked="checked" > 전체</label>
              	<label><input type="radio" name="sendType" value="android" id="sendType1"> Android </label>
                <label><input type="radio" name="sendType" value="ios" id="sendType2" > iOS</label>
              </div>
          </td>
        </tr>
        <tr>
          <th scope="col">URL</th>
          <td >
            <div class="container col-md-12 col-sm-12 col-xs-12">
          		<input type="text" name="msg_url"  value="" id="msg_url" class="form-control" >
          	</div>
          </td>
        </tr>
        <tr>
        <td colspan="2">
        	  <div class="x_content">
        		  <div class="form-group">
        			<div class="col-md-12 col-sm-12 col-xs-12 text-right">

        			  <input type="button" class="btn btn-success" value="메시지 발송" id="btnSend"></input>

        			</div>
        		  </div>
        	  </div>
        </td>
        </tr>

      </table>

		  <div class="ln_solid"></div>
	  </div>


	</form>

	</div>
  </div>
</div>

<div id="num_book"></div>
<script>

$(function(){

});

$('#senddatepicker').datetimepicker({
    ignoreReadonly: true,
    allowInputToggle: true,
    format: 'YYYY-MM-DD HH:mm',
    locale : 'ko'
});

$('#msg_body').keyup(function (e){

    var content = $(this).val();
    var counter_id = $(this).attr('id')+'_counter';


  //  $(this).height(((content.split('\n').length + 1) * 1.5) + 'em');
    $('#'+counter_id).html('<font color="red">'+content.length + '</font>/300');
});

$("#btnSend").click(function(){

	$("#fconfigform").attr("action", "ajax.configform_push_send.php").submit();
    return true;
});

</script>




<!-- @END@ 내용부분 끝 -->


<?php
include_once ('../admin.tail.php');
?>
