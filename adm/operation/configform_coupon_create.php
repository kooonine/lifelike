<?php
$sub_menu = "200220";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super' && $is_admin != 'admin')
  alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '쿠폰생성';
include_once('../admin.head.php');

//$token = get_admin_token();
?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">

      <form name="fwrite" id="fwrite" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
        <input type="hidden" name="token" value="" id="token">
        <input type="hidden" name="w" value="<?php echo $w; ?>" id="w">
        <input type="hidden" name="cm_no" value="<?php echo $cm_no; ?>" id="cm_no">
        <input type="hidden" name="cm_status" value="발급중" id="cm_status">

        <div class="x_title">
          <h4><span class="fa fa-check-square"></span> 발급정보<small></small></h4>
          <label class="nav navbar-right"></label>
          <div class="clearfix"></div>
        </div>

        <div class="tbl_frm01 tbl_wrap">
          <table>

            <tr scope='row'>
              <th>쿠폰명</th>
              <td colspan="3">
                <input type="text" class="form-control" id="cm_subject" name="cm_subject" required="required" maxlength="255" />
              </td>
            </tr>
            <tr scope='row'>
              <th>쿠폰설명</th>
              <td colspan="3">
                <input type="text" class="form-control" id="cm_summary" name="cm_summary" maxlength="255" />
              </td>
            </tr>
            <tr scope='row'>
              <th>혜택구분</th>
              <td colspan="3">
                <div class="pull-left">
                  <select name="cm_type" id="cm_type">
                    <option value="0">할인금액</option>
                    <option value="1">할인율</option>
                  </select>
                </div>
                <div class="pull-left">
                  &nbsp;<input type="text" class="frm_input" id="cm_price" name="cm_price" required="required" />
                  &nbsp;<label id="coupon_benefit_won_label">원</label>
                </div>
                <div class="pull-left hidden" id="coupon_benefit_per">
                  &nbsp;&nbsp;절사단위
                  <select name="cm_trunc" id="cm_trunc">
                    <option value="1">일원단위</option>
                    <option value="10">십원단위</option>
                    <option value="100">백원단위</option>
                    <option value="1000">천원단위</option>
                  </select>
                  최대금액
                  <input type="text" class="frm_input" id="cm_maximum" name="cm_maximum" /> 원
                </div>
              </td>
            </tr>
            <tr scope='row'>
              <th>발급구분</th>
              <td colspan="3">
                <div class="pull-left">
                  <select name="cm_target_type" id="cm_target_type">
                    <option value="0" selected="selected">대상자 지정발급</option>
                    <option value="1">조건부 자동발급</option>
                  </select>
                </div>
                <div class="pull-left hidden" id="coupon_div_condition_type">
                  <select name="cm_target_type2" id="cm_target_type2">
                    <option value="회원가입" selected="selected">회원가입(가입회원)</option>
                    <option value="생일">생일</option>
                  </select>
                </div>
              </td>
            </tr>
            <tr scope='row' class="" id="inssuance_div_point">
              <th>발급시점</th>

              <td colspan="3">
                <div class="pull-left" id="inssuance_div_point_default">
                  <label><input type="radio" name="cm_create_time" id="cm_create_time0" value="0" checked="checked" /> 즉시발급</label>
                  <label><input type="radio" name="cm_create_time" id="cm_create_time1" value="1" /> 지정한 시점에 발급</label>
                </div>
                <div class="pull-left col-sm-6 col-sm-6 col-xs-6 hidden" id="inssuance_div_point_tail">
                  <div class='col-sm-6 col-sm-6 col-xs-6'>

                    <div class='input-group date' id='cmstartpicker'>
                      <input type='text' class="form-control" id="cm_start" name="cm_start" />
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                    </div>

                  </div>
                </div>
                <div class="pull-left hidden" id="inssuance_div_birthday_point">
                  <label><input type="radio" name="cm_create_time1" value="0" /> 생일 당일 발급</label>
                  <label><input type="radio" name="cm_create_time1" value="1" checked="checked" />
                    <input type="text" class="frm_input" id="cm_create_time2" name="cm_create_time2" value="7" style="width:50px;"> 일전 미리 발행</label>
                </div>
              </td>



            </tr>

          </table>
        </div>

    </div>

    <div class="x_panel">
      <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 사용정보<small></small></h4>

        <div class="clearfix"></div>
      </div>

      <div class="tbl_frm01 tbl_wrap">
        <table>
          <tbody>
            <tr>
              <th scope="row">사용기간</th>
              <td colspan="3">
                <div class='col-sm-12 col-sm-12 col-xs-12'>
                  <label><input type="radio" name="cm_end_time" value="1" checked="checked" id ="cmEndId" /> 발급일 기준으로
                    <input type="text" class="frm_input" id="cm_end_time1" name="cm_end_time1" value="7" style="width:50px;"> 일간 사용가능</label>
                </div>
                <div class='col-sm-12 col-sm-12 col-xs-12' id="inssuance_div_birthday_point_del">
                  <label><input type="radio" name="cm_end_time" value="0" /> 기간 제한 없음</label>
                </div>
              </td>
            </tr>
            <tr>
              <th scope="row">사용기한</th>
              <td colspan="3">
                <?php echo help("쿠폰 다운로드 후 사용할 수 있는 요일을 설정합니다."); ?>
                <div>
                  <!-- <input type="checkbox" name="cm_weekday[]" id="cm_weekday_all"><label for="cm_weekday_all">전체</label> -->
                  <input type="checkbox" name="cm_weekday[]" value=1 id="cm_weekday_1" <?= in_array(1, $cp_weekday) ? "checked" : "" ?>><label for="cm_weekday_1">월</label>
                  <input type="checkbox" name="cm_weekday[]" value=2 id="cm_weekday_2" <?= in_array(2, $cp_weekday) ? "checked" : "" ?>><label for="cm_weekday_2">화</label>
                  <input type="checkbox" name="cm_weekday[]" value=3 id="cm_weekday_3" <?= in_array(3, $cp_weekday) ? "checked" : "" ?>><label for="cm_weekday_3">수</label>
                  <input type="checkbox" name="cm_weekday[]" value=4 id="cm_weekday_4" <?= in_array(4, $cp_weekday) ? "checked" : "" ?>><label for="cm_weekday_4">목</label>
                  <input type="checkbox" name="cm_weekday[]" value=5 id="cm_weekday_5" <?= in_array(5, $cp_weekday) ? "checked" : "" ?>><label for="cm_weekday_5">금</label>
                  <input type="checkbox" name="cm_weekday[]" value=6 id="cm_weekday_6" <?= in_array(6, $cp_weekday) ? "checked" : "" ?>><label for="cm_weekday_6">토</label>
                  <input type="checkbox" name="cm_weekday[]" value=7 id="cm_weekday_7" <?= in_array(7, $cp_weekday) ? "checked" : "" ?>><label for="cm_weekday_7">일</label>
                </div>
                <?php echo help("쿠폰 다운로드 후 사용할 수 있는 주차를 설정합니다."); ?>
                <div>
                  <!-- <input type="checkbox" name="cm_week[]" id="cm_week_all"><label for="cm_week_all">전체</label> -->
                  <input type="checkbox" name="cm_week[]" value="1" id="cm_week_1" <?= in_array(1, $cp_week) ? "checked" : "" ?>><label for="cm_week_1">첫째주</label>
                  <input type="checkbox" name="cm_week[]" value="2" id="cm_week_2" <?= in_array(2, $cp_week) ? "checked" : "" ?>><label for="cm_week_2">둘째주</label>
                  <input type="checkbox" name="cm_week[]" value="3" id="cm_week_3" <?= in_array(3, $cp_week) ? "checked" : "" ?>><label for="cm_week_3">셋째주</label>
                  <input type="checkbox" name="cm_week[]" value="4" id="cm_week_4" <?= in_array(4, $cp_week) ? "checked" : "" ?>><label for="cm_week_4">넷째주</label>
                </div>
              </td>
            </tr>
            <tr scope='row' col-group='non-target-member'>
              <th>사용범위</th>
              <td colspan="3">
                <label><input type="checkbox" name="cm_use_device[]" value="pc" checked="checked" /> PC</label>&nbsp;&nbsp;&nbsp;
                <label><input type="checkbox" name="cm_use_device[]" value="mobile" checked="checked" /> Mobile</label>&nbsp;&nbsp;&nbsp;
                <label><input type="checkbox" name="cm_use_device[]" value="app" checked="checked" /> APP</label>&nbsp;&nbsp;&nbsp;
              </td>
            </tr>
            <tr scope='row' col-group='non-target-member'>
              <th>적용범위</th>
              <td colspan="3">
                <select name="cm_method" id="cm_method">
                  <option value="2">주문서쿠폰</option>
                  <option value="3">배송비쿠폰</option>
                  <!-- option value="0" >상품쿠폰</option -->
                </select>
              </td>
            </tr>
            <tr scope='row' col-group='non-target-member'>
              <th>쿠폰적용 상품선택</th>
              <td colspan="3">
                <select name="cm_item_type" id="cm_item_type">
                  <option value="0">모두 적용</option>
                  <!-- <option value="1">선택한 상품 적용</option> -->
                  <!-- <option value="2">선택한 상품 제외하고 적용</option> -->
                </select>
                <input type="hidden" value="" name="cm_item_it_id_list" id="cm_item_it_id_list" />

                <button type="button" class="btn btn-default frm_input hidden" id="coupon_btn_product_type" target-data="coupon_product_modal">상품선택</button>
              </td>
            </tr>
            <tr scope='row' col-group='non-target-member'>
              <th>쿠폰적용 분류선택</th>
              <td colspan="3">
                <select name="cm_category_type" id="cm_category_type">
                  <option value="0">모두 적용</option>
                  <!-- <option value="1">선택한 분류 적용</option> -->
                  <!-- <option value="2">선택한 분류 제외하고 적용</option> -->
                </select>
                <input type="hidden" value="" name="cm_item_ca_id_list" id="cm_item_ca_id_list" />
                <button type="button" class="btn btn-default frm_input hidden" id="coupon_btn_category_type" target-data="coupon_category_modal">분류선택</button>
              </td>
            </tr>
            <tr scope='row' col-group='non-target-member'>
              <th>사용가능 기준금액</th>
              <td colspan="3">
                <div class="pull-left hidden">
                  <select name="cm_use_type" id="cm_use_type">
                    <option value="0">제한없음</option>
                    <option value="1">모든 상품의 주문금액</option>
                    <option value="2">쿠폰적용 상품의 주문금액</option>
                    <option value="3">상품금액기준</option>
                  </select>
                </div>
                <div class="pull-left" id="coupon_div_use_criteria_tail">
                  &nbsp;<input type="text" class="frm_input text-right" id="cm_minimum" name="cm_minimum" value=""> 원 이상 구매시
                </div>

              </td>
            </tr>
            <tr scope='row' col-group='non-target-member'>
              <th>적용계산 기준</th>
              <td colspan="3">
                <select name="cm_use_price_type" id="cm_use_price_type">
                  <!-- <option value="0">할인(쿠폰제외) 적용 전 결제금액</option> -->
                  <!-- <option value="1" selected="selected">할인(쿠폰제외) 적용 후 결제금액</option> -->
                  <option value="1" selected="selected">상품쿠폰 적용 후 결제금액</option>
                </select>
              </td>
            </tr>
            <tr scope='row' col-group='non-target-member'>
              <th>동일쿠폰사용 설정</th>
              <td colspan="3">
                주문서당 <input type="text" class="frm_input" id="cm_duple_item_use" name="cm_duple_item_use" value="1" style="width:50px;" /> 개 까지 사용가능
              </td>
            </tr>
            <tr scope='row' hidden>
              <th>로그인 시 쿠폰발급 알림</th>
              <td colspan="3">
                <label><input type="radio" name="cm_login_send" value="1" checked="checked" /> 사용함</label>
                <label><input type="radio" name="cm_login_send" value="0" /> 사용안함</label>
              </td>
            </tr>
            <tr scope='row'>
              <th>쿠폰발급 SMS 발급</th>
              <td colspan="3">
                <label><input type="radio" name="cm_sms_send" value="1" checked="checked" /> 발송함</label>
                <label><input type="radio" name="cm_sms_send" value="0" /> 발송안함</label>
              </td>
            </tr>
          </tbody>
          <tr>
            <td colspan="4" style="text-align:right;">
              <input type="submit" class="btn btn-primary" id="coupon_btn_update" value="생성"></input>
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

    $('#cmstartpicker').datetimepicker({
      ignoreReadonly: true,
      allowInputToggle: true,
      format: 'YYYY-MM-DD HH',
      locale: 'ko'
    });

    $('#coupon_checkbox_all').click(function() {
      var check = $(this).is(":checked");

      if (check) {
        $("input[name=coupon_checkbox]").prop('checked', true);
      } else {
        $("input[name=coupon_checkbox]").prop('checked', false);
      }
    });

    //전체선택
    $('#coupon_checkbox_all_selected').click(function() {
      var check = $(this).is(":checked");

      if (check) {
        $("input[name=coupon_checkbox_selected]").prop('checked', true);
      } else {
        $("input[name=coupon_checkbox_selected]").prop('checked', false);
      }
    });

    $("#coupon_btn_product_type, #coupon_btn_category_type").click(function() {
      var id = $(this).attr("target-data");
      $('#' + id).modal('show');
    });


    $('#coupon_btn_category_add').click(function() {

      var ca_id = $('#coupon_sel_product_main').val();
      if (ca_id != "") {
        var ca_name = $('#coupon_sel_product_main :selected').text();

        var stop = false;
        $('#coupon_ul_category li').each(function() {
          if ($(this).attr("data") == ca_id) {
            alert("등록된 상품분류입니다.");
            stop = true;
            return;
          }
        });
        if (stop) return;

        var li_script = '<li data="' + ca_id + '">' + ca_name +
          '<div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>' +
          '</li>';

        $('#coupon_ul_category').append(li_script);
        $("button[name='coupon_btn_category_delete']").parent().css("height", "22px");
        $("button[name='coupon_btn_category_delete']").css("height", "100%");
      }
    });

    $("button[name='coupon_btn_category_delete']").parent().css("height", "22px");
    $("button[name='coupon_btn_category_delete']").css("height", "100%");

    $("input:radio").click(function() {
      var target = $(this).attr('target-div');
      var show_yn = $(this).attr('show_yn');
      if (show_yn == 'Y') {
        $('#' + target).removeClass('hidden');
      } else {
        $('#' + target).removeClass('hidden').addClass('hidden');
      }
    });
    $("#cm_category_type").change(function() {
      var value = $(this).val();
      if (value != '0') {
        $('#coupon_btn_category_type').removeClass('hidden');
      } else {
        $('#coupon_btn_category_type').removeClass('hidden').addClass('hidden');
      }
    });
    $("#cm_item_type").change(function() {
      var value = $(this).val();
      if (value != '0') {
        $('#coupon_btn_product_type').removeClass('hidden');
      } else {
        $('#coupon_btn_product_type').removeClass('hidden').addClass('hidden');
      }
    });

    $("#cm_use_type").change(function() {
      var select_val = $(this).val();
      if (select_val != '0') {
        $('#coupon_div_use_criteria_tail').removeClass('hidden');
      } else {
        $('#coupon_div_use_criteria_tail').removeClass('hidden').addClass('hidden');
      }
    });
    $("#cm_target_type2").change(function() {
      var select_val = $(this).val();
      if (select_val != '회원가입') {
        $('#inssuance_div_point').removeClass('hidden');
        $('#inssuance_div_birthday_point').removeClass('hidden');
        $('#inssuance_div_birthday_point_del').removeClass('hidden').addClass('hidden');
        $("input:radio[id='cmEndId']").prop("checked", true); 
      } else {
        $('#inssuance_div_point').removeClass('hidden').addClass('hidden');
        $('#inssuance_div_birthday_point').removeClass('hidden').addClass('hidden');
        $('#inssuance_div_birthday_point_del').removeClass('hidden');
      }
    });

    $("input[name='cm_end_time']").click(function() {
      var select_val = $(this).val();

      if (select_val == '0') {
        $('#cm_end_time1').attr('disabled', true);
      } else if (select_val == '1') {
        $('#cm_end_time1').removeAttr('disabled');
      }
    });

    $("input[name='cm_create_time']").click(function() {
      var value = $(this).val();
      if (value == '0') {
        $('#inssuance_div_point_tail').removeClass('hidden').addClass('hidden');
      } else {
        $('#inssuance_div_point_tail').removeClass('hidden');
      }
    });

    $("input[name='cm_create_time1']").click(function() {
      var value = $(this).val();
      if (value == '0') {
        $('#cm_create_time2').attr('disabled', true);
      } else {
        $('#cm_create_time2').removeAttr('disabled');
      }
    });

    $("#cm_target_type").change(function() {
      var select_val = $(this).val();
      $('#coupon_div_condition_type').removeClass('hidden').addClass('hidden');
      $('#inssuance_div_birthday_point').removeClass('hidden').addClass('hidden');
      $('#inssuance_div_birthday_point_del').removeClass('hidden');

      if (select_val == '1') {
        $('#coupon_div_condition_type').removeClass('hidden');
        $('#inssuance_div_point').removeClass('hidden').addClass('hidden');
        $('#inssuance_div_point_default').removeClass('hidden').addClass('hidden');
        $('#inssuance_div_point_tail').removeClass('hidden').addClass('hidden');

        $('#cm_target_type2').val('회원가입');
      } else {
        $('#inssuance_div_point').removeClass('hidden');
        $('#inssuance_div_point_default').removeClass('hidden');
        $('#cm_create_time0').click();

      }
    });

    $("#cm_type").change(function() {
      var select_val = $(this).val();

      if (select_val == '0') {
        $('#coupon_benefit_won_label').text('원');
        $('#coupon_benefit_per').removeClass('hidden').addClass('hidden');
      } else if (select_val == '1') {
        $('#coupon_benefit_won_label').text('%');
        $('#coupon_benefit_per').removeClass('hidden');
      }

    });
  });

  $(document).on("click", "button[name='coupon_btn_category_delete']", function() {
    $(this).parent().parent().remove();
  });

  function fconfigform_submit(f) {
    //할인율일시 할인최대금액 필수
    if ($("#cm_type").val() == "1" && $("#cm_maximum").val() == "") {
      $("#cm_maximum").focus();
      alert("할인 최대금액을 입력하세요.");
      return false;
    }

    if ($("#cm_item_type").val() == "0") {
      $("#cm_item_it_id_list").val("");
    }
    if ($("#cm_category_type").val() != "0") {
      var ca_id_list = new Array();
      $('#coupon_ul_category li').each(function() {
        ca_id_list.push($(this).attr("data"));
      });
      $('#cm_item_ca_id_list').val(ca_id_list.join(","));
    }

    var token = get_ajax_token();

    if (!token) {
      alert("토큰 정보가 올바르지 않습니다.");
      return false;
    }

    var $f = $(f);

    if (typeof f.token === "undefined")
      $f.prepend('<input type="hidden" name="token" value="">');

    $f.find("input[name=token]").val(token);

    f.action = "./configform_coupon_create_update.php";
    return true;
  }
</script>




<!-- @END@ 내용부분 끝 -->

<div class="modal fade" id="coupon_product_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_product_modal">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Popup - 상품선택</h4>
      </div>

      <div class="modal-body">

        <div class="tbl_frm01 tbl_wrap">
          <table>
            <colgroup>
              <col class="grid_4">
              <col>
            </colgroup>
            <tbody>
              <tr>
                <th scope="row"><label>제품분류</label></th>
                <td>
                  <select id="ca_id">
                    <option value=''>분류별 상품</option>
                    <?php
                    $sql = " select * from {$g5['g5_shop_category_table']} ";
                    if ($is_admin != 'super')
                      $sql .= " where ca_mb_id = '{$member['mb_id']}' ";
                    $sql .= " order by ca_order, ca_id ";
                    $result = sql_query($sql);
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                      $len = strlen($row['ca_id']) / 2 - 1;

                      $nbsp = "";
                      for ($i = 0; $i < $len; $i++)
                        $nbsp .= "&nbsp;&nbsp;&nbsp;";

                      echo "<option value=\"{$row['ca_id']}\">$nbsp{$row['ca_name']}</option>\n";
                    }
                    ?>
                  </select>
                </td>
              </tr>
              <tr>
                <th scope="row"><label>상품번호/상품명</label></th>
                <td>
                  <input type="text" name="stx" id="stx" value="" class="form-control">
                </td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: right;">
                  <button type="button" class="btn btn-success" id="btnSearch">검색</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <form name="procForm" id="procForm" method="post">
          <div class="tbl_frm01 tbl_wrap" id="tblProduct">
            <?php include_once(G5_ADMIN_URL . '/design/design_component_itemsearch.php'); ?>
          </div>
        </form>

        <div style="text-align: right;">
          <button type="button" class="btn btn-success" id="btnProductSubmit">추가</button>
        </div>

        <div class="x_title">
          <h5><span class="fa fa-check-square"></span> 선택된 지정상품</h5>
          <div style="text-align: right;">
            <input type="button" class="btn btn-danger" value="삭제" id="btnProductDel" />
          </div>
        </div>

        <form name="procForm1" id="procForm1" method="post">
          <div class="tbl_frm01 tbl_wrap" id="tblProductForm">

          </div>
        </form>

      </div>

      <div class="modal-footer">
        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>
<script>
  $(function() {
    function check_all(f) {
      var chk = document.getElementsByName("chk[]");

      for (i = 0; i < chk.length; i++)
        chk[i].checked = f.chkall.checked;
    }

    function check_all2(f) {
      var chk = document.getElementsByName("chk2[]");

      for (i = 0; i < chk.length; i++)
        chk[i].checked = f.chkall.checked;
    }

    function tblProductFormBind() {

      var $table = $("#tblProductForm");
      $.post(
        "<?php echo G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
          w: "u",
          it_id_list: $("#cm_item_it_id_list").val()
        },
        function(data) {
          $table.empty().html(data);
        }
      );
    };

    tblProductFormBind();

    $("#btnSearch").click(function(event) {
      var $table = $("#tblProduct");
      $.post(
        "<?php echo G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
          ca_id: $("#ca_id").val(),
          stx: $("#stx").val(),
          not_it_id_list: $("#cm_item_it_id_list").val()
        },
        function(data) {
          $table.empty().html(data);
        }
      );
    });

    $("#btnProductDel").click(function(event) {
      if (!is_checked("chk2[]")) {
        alert("삭제 하실 항목을 하나 이상 선택하세요.");
        return false;
      }

      if (confirm("삭제하시겠습니까?")) {

        var $chk = $("input[name='chk2[]']");
        var $it_id = new Array();

        for (var i = 0; i < $chk.size(); i++) {
          if (!$($chk[i]).is(':checked')) {
            var k = $($chk[i]).val();
            $it_id.push($("input[name='it_id2[" + k + "]']").val());
          }
        }

        $("#cm_item_it_id_list").val($it_id.join(","));
        tblProductFormBind();
      }
    });


    $("#btnProductSubmit").click(function(event) {

      if (!is_checked("chk[]")) {
        alert("등록 하실 항목을 하나 이상 선택하세요.");
        return false;
      }

      var $chk = $("input[name='chk[]']:checked");
      var $it_id = new Array();

      for (var i = 0; i < $chk.size(); i++) {
        var k = $($chk[i]).val();
        $it_id.push($("input[name='it_id[" + k + "]']").val());
      }
      var cm_item_it_id_list = $it_id.join(",");

      if ($("#cm_item_it_id_list").val() != "") cm_item_it_id_list += "," + $("#cm_item_it_id_list").val();

      $("#cm_item_it_id_list").val(cm_item_it_id_list);

      tblProductFormBind();
      $("#btnSearch").click();

      //$("#modal_product").modal('hide');
    });


    $("#btnProductSearch").click(function(event) {
      $("#stx").val("");
      var $table = $("#tblProduct");
      $table.empty();
      $("#modal_product").modal('show');
    });
  });
</script>
<div class="modal fade" id="coupon_category_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_category_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Popup - 카테고리 선택</h4>

      </div>
      <div class="modal-body">
        <div class="row">
          <div class="tbl_frm01 tbl_wrap">
            <table>
              <thead>
                <tr>
                  <th colspan="4" style="text-align:center;">
                    <label>상품분류 선택</label>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th rowspan="2">상품분류</th>
                  <td>
                    <select name="coupon_sel_product_main" id="coupon_sel_product_main">
                      <option value=''>분류별 상품</option>
                      <?php
                      $sql = " select  a.ca_id, a.ca_name
                                        ,b.ca_id as ca_id1, b.ca_name as ca_name1
                                        ,c.ca_id as ca_id2, c.ca_name as ca_name2
                                from    {$g5['g5_shop_category_table']} as a
                                        left outer join {$g5['g5_shop_category_table']} as b
                                          on left(a.ca_id,2) = b.ca_id
                                        left outer join {$g5['g5_shop_category_table']} as c
                                          on left(a.ca_id,4) = c.ca_id
                                order by a.ca_order, a.ca_id; ";

                      $result = sql_query($sql);
                      for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        $ca_name = $row['ca_name'];
                        if ($row['ca_name'] != $row['ca_name2']) {
                          $ca_name = $row['ca_name2'] . '>' . $ca_name;
                        }
                        if ($row['ca_name'] != $row['ca_name1']) {
                          $ca_name = $row['ca_name1'] . '>' . $ca_name;
                        }

                        /*
                            $len = strlen($row['ca_id']) / 2 - 1;

                            $nbsp = "";
                            for ($i=0; $i<$len; $i++)
                                $nbsp .= "&nbsp;&nbsp;&nbsp;";
                            */

                        echo "<option value=\"{$row['ca_id']}\">$nbsp{$ca_name}</option>\n";
                      }
                      ?>
                    </select>
                    <button type="button" class="btn btn-default" id="coupon_btn_category_add">추가</button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <ul data-role="listview" id="coupon_ul_category"></ul>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <br><br><br>
          <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">저장</button>
        </div>
      </div>
    </div>
  </div>


  <?php
  include_once('../admin.tail.php');
  ?>