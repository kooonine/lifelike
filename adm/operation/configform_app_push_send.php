<?php
$sub_menu = "20";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = 'PUSH 메시지 발송';
include_once ('../admin.head.php');

$token = get_admin_token();

$sql = 'SELECT * FROM (SELECT au.mb_id AS mb_id, au.token AS token, au.device AS device, au.push_check AS push_check, mb.mb_name AS mb_name, au.regdate AS regdate, au.updatedate AS updatedate FROM lt_app_users AS au LEFT JOIN lt_member AS mb on au.mb_id = mb.mb_id  WHERE (au.mb_id IS NOT NULL AND au.mb_id != "") AND mb.mb_id IS NOT NULL order BY updatedate DESC) AS groupTable GROUP BY mb_id ORDER BY regdate DESC';
$result = sql_query($sql);

// $testSql = "SELECT pr_push_date as pd from lt_app_push_reservation WHERE (pr_status = 0 OR pr_status IS NULL) AND (pr_push_date >= NOW() AND pr_push_date <= DATE_ADD(now(), INTERVAL 11 MINUTE)) limit 1";
// $testresult = sql_fetch($testSql);
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
  <input type="hidden" name="fcmId" value="" id="fcmId">
  <input type="hidden" name="deviceType" value="" id="deviceType">
  <input type="hidden" name="min10" value=0 id="min10">
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
        <!-- <tr>
          <th scope="col">플랫폼</th>
          <td >
              <div class="col-md-12 col-sm-12 col-xs-12">
              	<label><input type="radio" name="sendType" value="" id="sendType0" checked="checked" > 전체</label>
              	<label><input type="radio" name="sendType" value="android" id="sendType1"> Android </label>
                <label><input type="radio" name="sendType" value="ios" id="sendType2" > iOS</label>
              </div>
          </td>
        </tr> -->
        <tr>
          <th scope="col">URL</th>
          <td >
            <div class="container col-md-12 col-sm-12 col-xs-12">
          		<input type="text" name="msg_url"  value="" id="msg_url" class="form-control" >
          	</div>
          </td>
        </tr>
        <tr>
          <th scope="col">시간설정</th>
          <td>
              <input type="radio" name="pushType" value="0" id="pushType0" checked="checked"> <label for="pushType0">일반발송</label>
              <input type="radio" name="pushType" value="1" id="pushType1"> <label for="pushType1">예약발송</label>
              <div name='pushDateTime' id="pushDateTime" style="display: none;">
                <input name='pushDateTimeIn' id="pushDateTimeIn" type="datetime-local" />
              </div>
              <!-- <div class='input-group date hidden' id='senddatepicker'>
                  <input type='text' class="form-control" id="sendDate" />
                  <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div> -->
            <!-- <div class="container col-md-12 col-sm-12 col-xs-12"> -->
          		<!-- <input type="text" name="timerFcm"  value="" id="timerFcm" class="form-control" > -->
          	<!-- </div> -->
          </td>
        </tr>
        <tr>
        <td colspan="2">
        	  <div class="x_content">
        		  <div class="form-group">
        			<div class="col-md-12 col-sm-12 col-xs-12 text-right">
                <input type="button" class="btn btn-success" value="선택 메시지 발송" id="btnSendSingie"></input>
        			  <input type="button" class="btn btn-success" value="전체 메시지 발송" id="btnSendAll"></input>
                <!-- <input type="button" class="btn btn-success" value="test001 발송" onclick="test001('<?= $testresult['pd'] ?>')"></input> -->
        			</div>
        		  </div>
        	  </div>
        </td>
        </tr>



      </table>

		  <!-- <div class="ln_solid"></div> -->
	  </div>
  <div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk">
            <label for="chkall" class="sound_only">회원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="checkFcm_all(this.form)">
        </th>
        <th scope="col" id="mb_list_auth">이름</th>
        <th scope="col" id="mb_list_auth">아이디</th>
        <th scope="col" id="mb_list_join">디바이스</th>
        <th scope="col" id="mb_list_join">수신여부</th>

    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
    ?>
    <tr>
    <th> <input type="checkbox" name="chkFcm[]" value="<?= $row['mb_id']?>"  data-value ="<?= $row['device']?>"  id="chkFcm_<?php echo $i ?>"></th>
    <th><?= $row['mb_name']?></th>
    <th><?= $row['mb_id']?></th>
    <th><?= $row['device']?></th>
    <th><?php echo ($row['push_check'] == 1) ? 'Y':'N' ?></th>
    </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<!-- /////////// -->
	</form>

	</div>
  </div>
</div>

<div id="num_book"></div>
<script>
  function checkFcm_all(e) {
    var chkFcm = document.getElementsByName("chkFcm[]");
    for (i=0; i<chkFcm.length; i++) {
      chkFcm[i].checked = e.chkall.checked;
    }

  }
  $('#msg_body').keyup(function (e){

      var content = $(this).val();
      var counter_id = $(this).attr('id')+'_counter';

      $('#'+counter_id).html('<font color="red">'+content.length + '</font>/300');
  });
  
  $("#pushType1").click(function(){
    $("#pushDateTime").show();
  })
  $("#pushType0").click(function(){
    $("#pushDateTime").hide();
  })

  $("#btnSendAll").click(function(){ 
    let msg_title = document.getElementById("msg_title").value;
    let msg_body = document.getElementById("msg_body").value;
    let msg_url = document.getElementById("msg_url").value;
    if (!msg_title || msg_title.length < 1) {
        alert("제목을 확인해주세요.");
        return false;
    }
    if (!msg_body || msg_body.length < 1) {
        alert("내용을 확인해주세요.");
        return false;
    }
    if (!msg_url || msg_url.length < 1) {
        alert("URL을 확인해주세요.");
        return false;
    }
    $("#fconfigform").attr("action", "ajax.configform_app_push_send_all.php").submit();
    return true;
  })

  $("#btnSendSingie").click(function(){
    if (!is_checked("chkFcm[]")) {
        alert("하나 이상 선택하세요.");
        return false;
    }
    let msg_title = document.getElementById("msg_title").value;
    let msg_body = document.getElementById("msg_body").value;
    let msg_url = document.getElementById("msg_url").value;
    if (!msg_title || msg_title.length < 1) {
        alert("제목을 확인해주세요.");
        return false;
    }
    if (!msg_body || msg_body.length < 1) {
        alert("내용을 확인해주세요.");
        return false;
    }
    if (!msg_url || msg_url.length < 1) {
        alert("URL을 확인해주세요.");
        return false;
    }
    //라디오 체크 여부 !!1
    let radioCheck = $("input:radio[id='pushType1']").is(":checked");
    if (radioCheck) {
      let timestamp = new Date().getTime();
      let selectTime = document.getElementById("pushDateTimeIn").value;
      let selectTimestamp = new Date(selectTime).getTime();
      if (!selectTime || selectTime =='' || timestamp >=selectTimestamp) {
        alert("예약발송 시간을 확인해주세요.");
        return false;
      }
      $('#min10').val(0);
      if(selectTimestamp - timestamp <= 1000*60*10) {
          $('#min10').val(1);
          alert("예약발송 시간을 확인해주세요.");
          return false;
      }
    }
    var chkFcm = document.getElementsByName("chkFcm[]");
    var pushId = false;
    let idFcm = [];
    $('#deviceType').val('');
    for (i=0; i<chkFcm.length; i++)
    {
        if (chkFcm[i].checked) {
          let deviceInfo = document.querySelector('#chkFcm_'+i).dataset.value;
          if($('#deviceType').val() =='전체') {
          }else if ($('#deviceType').val()=='') {
            $('#deviceType').val(deviceInfo);
          } else {
            if (deviceInfo != $('#deviceType').val()) {
                $('#deviceType').val('전체') 
            }
          }
          
          idFcm.push(chkFcm[i].value);
        }
    }
    $('#fcmId').val(idFcm);
    $("#fconfigform").attr("action", "ajax.configform_app_push_send.php").submit();
    return true;

  })
</script>






<?php
include_once ('../admin.tail.php');
?>
