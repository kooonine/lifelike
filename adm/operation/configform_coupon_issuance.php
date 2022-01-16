<?php
$sub_menu = "200210";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

if(!$cm_no)
    alert('잘못된 접근입니다.');

$sql = "select * from lt_shop_coupon_mng where cm_no={$cm_no} ";
$row = sql_fetch($sql);
        
$g5['title'] = '쿠폰발급하기';
include_once ('../admin.head.php');

$token = get_admin_token();
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  	<div class="x_panel">

  	<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
  	<input type="hidden" name="token" value="<?php echo $token ?>" id="token">
  	<input type="hidden" name="cm_no" value="<?php echo $row['cm_no'] ?>" id="cm_no">
  	  <div class="x_title">
  		<h4><span class="fa fa-check-square"></span> 발급쿠폰정보<small></small></h4>
  		<label class="nav navbar-right"></label>
  		<div class="clearfix"></div>
  	  </div>

  	  <div class="tbl_frm01 tbl_wrap">
        <table>

          <tr scope = 'row'>
            <th>쿠폰명</th>
            <td colspan="3">
              <?php echo $row['cm_subject']?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>쿠폰설명</th>
            <td colspan="3">
              <?php echo $row['cm_summary']?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>혜택구분</th>
            <td colspan="3">
              <?php 
                switch($row['cm_type']) {
                    case '0':
                        echo '할인금액 : '.number_format($row['cm_price']).'원';
                        break;
                    case '1':
                        echo '할인율 : '.$row['cm_price'].'%';
                        echo ', 절사단위 : '.number_format($row['cm_trunc']).'원단위';
                        echo ', 최대금액 : '.number_format($row['cm_maximum']).'원';
                        break;
                }
                ?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>발급구분</th>
            <td colspan="3">
              <?php echo ($row['cm_target_type'] == '0')?"대상자지정":"조건부 자동" ?>
              <?php echo ($row['cm_target_type2'] != '')?"(".$row['cm_target_type2'].")":"" ?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>적용계산 기준</th>
            <td colspan="3">
            	<?php echo ($row['cm_use_price_type'] =='0')?"할인(쿠폰 제외) 적용 전 결제금액":"할인(쿠폰제외) 적용 후 결제금액"; ?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>사용기간</th>
            <td colspan="3">
               <?php 
                    if ($row['cm_end_time'] == 0) echo "기간 제한 없음";
                    else {
                        echo "발급일로부터 ".$row['cm_end_time']."일 이내";
                        
                        $cp_start=date_create(G5_TIME_YMDHIS);
                        $cp_end = date_create(G5_TIME_YMDHIS);
                        date_add($cp_end, date_interval_create_from_date_string($row['cm_end_time'].' days'));
                        
                        echo " ( ".date_format($cp_start,"Y-m-d H:i")." ~ ";
                        echo date_format($cp_end,"Y-m-d H:i")." )";
                        
                    }
                ?>
              	<input type="hidden" name="cp_start" value="<?php echo date_format($cp_start,"Y-m-d H:i").":00" ?>" id="cp_start">
              	<input type="hidden" name="cp_end" value="<?php echo date_format($cp_end,"Y-m-d H:i").":00" ?>" id="cp_end">
            </td>
          </tr>
          <tr scope = 'row'>
            <th>적용범위</th>
            <td colspan="3">
              <?php echo ($row['cm_method'] =='0')?"상품쿠폰":"주문서쿠폰"; ?>
            </td>
          </tr>
          <tr scope = 'row'>
            <th>사용가능 기준금액</th>
            <td colspan="3">
             <?php 
                switch($row['cm_use_type']) {
                    case '0': echo '제한없음'; break;
                    case '1': echo '모든 상품의 주문금액'; break;
                    case '2': echo '쿠폰적용 상품의 주문금액'; break;
                    case '3': echo '상품금액기준'; break;
                }
                ?>
            </td>
          </tr>
        </table>
  	  </div>

      <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 발급할 회원/조건 선택<small></small></h4>

        <div class="clearfix"></div>
      </div>

      <div class="tbl_frm01 tbl_wrap">
        <table>
          <tbody>
            <tr scope = 'row'>
              <th>SMS 발송 설정</th>
              <td colspan="3">
                <label><input type="radio" name="cp_sms_send" value="1" /> 발급함</label>
                <label><input type="radio" name="cp_sms_send" value="0" checked="checked" /> 발급안함</label>
                
                <!-- label for="cp_email_send">이메일발송</label>
                <input type="checkbox" name="cp_email_send" value="1" id="cp_email_send"  checked -->
              </td>
            </tr>
            <tr scope = 'row'>
              <th>중복 발급 설정</th>
              <td colspan="3">
                <label><input type="radio" name="coupon_inssuance_overlap_radio" value="yes" /> 발급함</label>
                <label><input type="radio" name="coupon_inssuance_overlap_radio" value="no" checked="checked" /> 발급안함</label>
              </td>
            </tr>
            <tr scope = 'row'>
              <th>1회 발급 수량</th>
              <td colspan="3">
                <label><input type="radio" name="coupon_inssuance_radio_cnt" col-group="inssuance_cnt" disabled="true" checked="checked" value="1장씩 발급" /> 1장씩 발급</label>
                <label><input type="radio" name="coupon_inssuance_radio_cnt" col-group="inssuance_cnt" disabled="true" value="설정된 수량만큼 발급"/> 설정된 수량만큼 발급</label>
                <select name="coupon_sel_inssuance_cnt" id="coupon_sel_inssuance_cnt" col-group="inssuance_cnt" disabled="true">
                    <option value="2" >2매</option>
                    <option value="3" >3매</option>
                    <option value="4" >4매</option>
                    <option value="5" >5매</option>
                    <option value="6" >6매</option>
                    <option value="7" >7매</option>
                    <option value="8" >8매</option>
                    <option value="9" >9매</option>
                    <option value="10" >10매</option>
                </select>
              </td>
            </tr>
            <tr scope = 'row'>
              <th>회원/조건 선택</th>
              <td colspan="3">
                <select name="coupon_sel_member" id="coupon_sel_member" >
                    <option value="all" >전체회원 발급</option>
                    <!-- option value="level" >회원등급선택</option -->
                    <option value="member" >특정회원 선택</option>
                    <option value="excel" >엑셀등급발급</option>
                </select>
                
                <span id="tdlevel" hidden><?php echo get_member_level_select('mb_level', 1, $member['mb_level'], $mb['mb_level']) ?></span>
                
                <span id="tdmember" hidden>
    				<button type="button" class="btn btn-default" id="coupon_btn_member">회원선택</button>
    				<input type="hidden" name="mb_id_list" value="" id="mb_id_list">
    			</span>
    			</td>
            </tr>
		</table>
	  </div>
	  </form>
	  
	  <form name="upload_form" method="post" enctype="multipart/form-data" id="fileup_frm">
      <div class="tbl_frm01 tbl_wrap">
        <table>
            <tr id="tdexcel1" hidden>
              <th></th>
              <td>
                <div class="pull-left" id="upload_button">
                    <input type="file" name="csv" id="csv" onchange="$('#mb_id_list').val();$('#dvSelectedMember').empty();" required="required" accept=".csv,.xls">
                </div>
                <div class="pull-left">
                    <input type="button" value="등록" class="btn_submit btn" id="csvupload">
                    <a href="./point_excel_bundle_sample.csv" ><button type="button" name="searchValue"  value="" id="searchValue" class="btn btn-default frm_input" >양식 다운로드</button></a>
				</div>
				<br/><br/>
				<div class="pull-left"> 
                    <span id="uploading" style="display: none">
                        파일을 업로드 중입니다. 잠시만 기다려주세요.
                    </span>
    				<div id="upload_info" style="display: none"></div>
                </div>
              </td>
            </tr>
            <tr id="tdexcel2" hidden>
              <th></th>
              <td>이곳에 엑셀 파일을 등록해 주십시오. (확장자 : CSV 형태)</td>
            </tr>
            <tr id="tdexcel3" hidden>
              <th></th>
              <td>데이터가 500건이 넘는 엑셀 파일을 등록하면 오류가 발생할 수 있습니다. 500건 단위로 분할하여 올려주세요.</td>
            </tr>
            <tr id="tdexcel4" hidden>
              <th></th>
              <td>
                <div class="tbl_head01 tbl_wrap" id="dvSelectedMember">
                
                </div>
              </td>
            </tr>
            
          </tbody>
            <tr>
              <td colspan="4" style="text-align:right;">
                <a href="./configform_coupon_list.php?<?php echo $qstr; ?>" class="btn btn_02">목록</a>
                <input type="button" class="btn btn-primary" id="coupon_btn_update" value="발급"></input>
              </td>
            </tr>
        </table>
  	  </div>

  	</form>
  	
    </div>
  </div>
</div>

<script>
$(document).ready(function() {

	$("#csvupload").click(function(){

		if(document.getElementById('csv').value == ''){
			alert("파일을 선택해주세요.");
			return false;
		}
	    var f = document.upload_form;
	    
        document.getElementById('upload_button').style.display = 'none';
        document.getElementById('upload_info').style.display = 'none';
        
	    f.action = 'configform_coupon_issuance_upload.php';
	    
	    (function($){
	        if(!document.getElementById("fileupload_fr")){
	            var i = document.createElement('iframe');
	            i.setAttribute('id', 'fileupload_fr');
	            i.setAttribute('name', 'fileupload_fr');
	            i.style.display = 'none';
	            document.body.appendChild(i);
	        }
	        f.target = 'fileupload_fr';
	        f.submit();
	    })(jQuery);
	});

      $("#coupon_sel_member").change(function(){
        var select_val = $(this).val();

        $("#mb_id_list").val("")
        $("#dvSelectedMember").empty();

		$("#tdlevel").prop("hidden",true);
		$("#tdlevel").prop("hidden",true);
		$("#tdmember").prop("hidden",true);	
		$("#tdexcel1").prop("hidden",true);
		$("#tdexcel2").prop("hidden",true);
		$("#tdexcel3").prop("hidden",true);
		$("#tdexcel4").prop("hidden",true);
		
        switch (select_val) {
		case 'all':
			break;
		case 'level':
			$("#tdlevel").prop("hidden",false);
			break;
		case 'member':	
			$("#tdmember").prop("hidden",false);
			$("#tdexcel4").prop("hidden",false);
			break;
		case 'excel':			
			$("#tdexcel1").prop("hidden",false);
			$("#tdexcel2").prop("hidden",false);
			$("#tdexcel3").prop("hidden",false);
			$("#tdexcel4").prop("hidden",false);
			break;
		default:
			break;
		}

      });

      $("#coupon_btn_member").click(function(){
        $('#coupon_member_modal').modal('show');
      });


  $('input[name="coupon_inssuance_overlap_radio"]').click(function(){
    if($(this).val() == 'yes'){
      $('[col-group="inssuance_cnt"]').removeAttr('disabled');
    }else{
      $('[col-group="inssuance_cnt"]').attr('disabled',true);
    }
  });


  //목록
  $('input[name="coupon_btn_list"]').click(function(){

  });
  //제품선택
  $('#coupon_btn_product').click(function(){
    $('#coupon_product_modal').modal('toggle');
    $("#product_table").css("width","100%");
  });

  $("#stx").keyup(function(){
	  if (window.event.keyCode == 13) {
		  $("#btnSearch").click();
	  }
  });

  $("#btnSearch").click(function(){
	  
	  $.post(
          "ajax.configform_coupon_issuance_search.php",
          { sfl:$("#sfl").val(), stx:$("#stx").val() },
          function(data) {
              $("#dvMember").empty().html(data);
          }
      );
  });

  
  $("#btnMemberSave").click(function(){

      var mb_id_array = new Array();
      
	  var $el = $("input[name='chk[]']:checked");
      if($el.size() < 1) {
          alert("저장할 회원을 한명 이상 선택해 주십시오.");
          return false;
      }
      $el.each(function(index) {
          i = $.trim($(this).val());
    	  var mb_id = $("input[name='mb_id["+i+"]']").val();
    	  mb_id_array.push(mb_id);
      });

      $("#mb_id_list").val(mb_id_array.join(",")+","+$("#mb_id_list").val());

	  $.post(
          "ajax.configform_coupon_issuance_search.php",
          { mb_id_list:$("#mb_id_list").val() },
          function(data) {
              $("#dvSelectedMember").empty().html(data);
              /*
              var $mb_id2 = $("input[name='mb_id2[]']");

              var mb_id2_array = new Array();
              mb_id2.each(function(index) {
            	  mb_id2_array.push($(this).val());
              });
              $("#mb_id_list").val(mb_id2_array.join(","));
              */
          }
      );

      $('#coupon_member_modal').modal('hide');
  });

  $("#coupon_btn_update").click(function(){

	  $("#fconfigform").submit();
	  
  });

});



function fconfigform_submit(f)
{
	var confirmstr = "다음 조건으로 쿠폰을 발급하시겠습니까?";
	confirmstr += "\n -중복발급설정 : "+(($("input[name='coupon_inssuance_overlap_radio']:checked").val() == "yes")?"발급함":"발급안함");
	confirmstr += "\n -1회 발급수량: "+($("input[name='coupon_inssuance_radio_cnt']:checked").val());
	if($("input[name='coupon_inssuance_radio_cnt']:checked").val() == "설정된 수량만큼 발급") confirmstr += $("#coupon_sel_inssuance_cnt").val()+"매";
	confirmstr += "\n -회원/조건 선택 : "+($("#coupon_sel_member option:selected").text());
	
	if(!confirm(confirmstr)) return false;
	
    f.action = "./couponformupdate.php";
    return true;
}

function loadmember(){
	if($("#mb_id_list").val() != "")
	{
	  $.post(
        "ajax.configform_coupon_issuance_search.php",
        { mb_id_list:$("#mb_id_list").val() },
        function(data) {
            $("#dvSelectedMember").empty().html(data);
        }
        );
	}
}
</script>
<!-- @END@ 내용부분 끝 -->


<div class="modal fade" id="coupon_member_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_member_modal">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Popup - 회원선택</h4>
      </div>
      <form id="flst" name="flist">
      <div class="modal-body" >
        <div class="row">
          <div class="tbl_frm01 tbl_wrap">
            <table>
              <tbody>
                <tr>
                  <th>검색</th>
                  <td colspan="3">
                    <select name="sfl" id="sfl" >
                        <option value="mb_id" >아이디</option>
                        <option value="mb_name" >고객명</option>
                    </select>
                    <input type="text" value="" class="frm_input" id="stx" name="stx" >
                    <div class="pull-right">
                    <button type="button" class="btn btn-primary" id="btnSearch">검색</button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="tbl_head01 tbl_wrap" id="dvMember">
				<?php include_once('./ajax.configform_coupon_issuance_search.php');?>
          </div>
        </div>
      </div>
      </form>
      <div class="modal-footer">
        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        <button type="button" class="btn btn-default" id="btnMemberSave">저장</button>
      </div>
    </div>
  </div>
</div>

<?php
include_once ('../admin.tail.php');
?>