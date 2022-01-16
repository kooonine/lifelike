<?php
$sub_menu = "200310";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

if ($is_admin != 'super' && $is_admin != 'admin')
  alert('최고관리자만 접근 가능합니다.');

$g5['title'] = '적립금설정';
include_once('../admin.head.php');
?>

<!-- @START@ 내용부분 시작 -->

<div class="row">
  <form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);" data-parsley-validate class="form-horizontal form-label-left">
    <input type="hidden" name="token" value="" id="token">

    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h4><span class="fa fa-check-square"></span> 기본설정<small></small></h4>
          <label class="nav navbar-right"></label>
          <div class="clearfix"></div>
        </div>

        <div class="tbl_frm01 tbl_wrap">
          <table>

            <tr scope='row'>
              <th>자동구매확정기간</th>
              <td colspan="3">
                자동구매확정기간 <input type="text" class="frm_input text-center" value="<?php echo $default['de_point_days']; ?>" id="de_point_days" name="de_point_days" required="required" /> 일으로 설정
              </td>
            </tr>
            <tr scope='row'>
              <th>적립금명칭</th>
              <td colspan="3">
                <input type="text" class="frm_input text-center" value="<?php echo $default['de_point_name']; ?>" id="de_point_name" name="de_point_name" required="required" readonly="readonly" />
              </td>
            </tr>
            <tr scope='row'>
              <th>적립금 표시방식</th>
              <td colspan="3">
                <input type="text" class="frm_input text-center" value="<?php echo $default['de_point_unit']; ?>" id="de_point_unit" name="de_point_unit" required="required" readonly="readonly" />
              </td>
            </tr>
            <tr scope='row'>
              <th>적립금 절사</th>
              <td colspan="3">
                <div class="pull-left">
                  적립금을 %단위로 입력하는 경우에
                  <select name="de_settle_point_unit" id="de_settle_point_unit">
                    <option value="0" <?php echo get_selected($default['de_settle_point_unit'], '0'); ?>>절사안함</option>
                    <option value="1" <?php echo get_selected($default['de_settle_point_unit'], '1'); ?>>일원단위</option>
                    <option value="10" <?php echo get_selected($default['de_settle_point_unit'], '10'); ?>>십원단위</option>
                    <option value="100" <?php echo get_selected($default['de_settle_point_unit'], '100'); ?>>백원단위</option>
                    <option value="1000" <?php echo get_selected($default['de_settle_point_unit'], '1000'); ?>>천원단위</option>
                  </select>
                </div>
                <div class="pull-left hidden" id="dv_de_settle_point_unit_type">
                  로
                  <select name="de_settle_point_unit_type" id="de_settle_point_unit_type">
                    <option value="roundup" <?php echo get_selected($default['de_settle_point_unit_type'], 'roundup'); ?>>올림</option>
                    <option value="rounddown" <?php echo get_selected($default['de_settle_point_unit_type'], 'rounddown'); ?>>내림</option>
                    <option value="round" <?php echo get_selected($default['de_settle_point_unit_type'], 'round'); ?>>반올림</option>
                  </select>
                </div>
                <script>
                  $("#de_settle_point_unit").change(function() {
                    var value = $(this).val();
                    if (value != '0') {
                      $('#dv_de_settle_point_unit_type').removeClass('hidden');
                    } else {
                      $('#dv_de_settle_point_unit_type').removeClass('hidden').addClass('hidden');
                    }
                  });
                  $("#de_settle_point_unit").change();
                </script>
              </td>
            </tr>
            <tr scope='row'>
              <th>적립금 항목 노출 설정</th>
              <td colspan="3">
                <select name="de_point_display_type" id="de_point_display_type">
                  <option value="%" <?php echo get_selected($default['de_point_display_type'], '%'); ?>>정율(%) 단일표시(1%)</option>
                  <option value="원" <?php echo get_selected($default['de_point_display_type'], '원'); ?>>정액(원) 단일표기(100원)</option>
                </select>
              </td>
            </tr>
            <tr scope='row'>
              <th>사용 유효기간</th>
              <td colspan="3">
                발행일로부터 <input type="text" class="frm_input text-center" value="<?php echo $config['cf_point_term']; ?>" id="cf_point_term" name="cf_point_term" required="required" /> 일까지
                <!-- <div class="pull-right"><b>※유효기간은 최대 180일까지 설정 가능합니다.</b></div> -->
              </td>
            </tr>

          </table>
        </div>


      </div>

      <div class="x_panel">
        <div class="x_title">
          <h4><span class="fa fa-check-square"></span> 지급설정<small></small></h4>

          <div class="clearfix"></div>
        </div>

        <div class="tbl_frm01 tbl_wrap">
          <table>
            <tbody>
              <tr>
                <th scope="row">상품구매금액 기준설정</th>
                <td colspan="3">
                  상품구매금액을
                  <select name="de_prd_price_for_save_type" id="de_prd_price_for_save_type">
                    <option value="판매가" <?php echo get_selected($default['de_prd_price_for_save_type'], '판매가'); ?>>판매가</option>
                    <option value="할인판매가" <?php echo get_selected($default['de_prd_price_for_save_type'], '할인판매가'); ?>>할인판매가</option>
                  </select>
                  을(를)기준으로 설정합니다.
                </td>
              </tr>
              <tr scope='row'>
                <th>상품구매시 적립금</th>
                <td colspan="3">
                  상품값의 <input type="text" class="frm_input text-center" value="<?php echo $default['de_point_percent']; ?>" id="de_point_percent" name="de_point_percent" required="required" /> %
                </td>
              </tr>
              <tr scope='row'>
                <th rowspan="3" id="saveMoney_th_config_1">적립금으로 구매시<br>적립기준 설정</th>
                <td colspan="3">
                  <div class="border border-dark border-bottom-10">
                    <label><input type="radio" name="de_point_use_standard" value="1" show_yn="N" target-div="saveMoney_div_config_1" <?php echo get_checked($default['de_point_use_standard'], '1'); ?> /> 적립금 그대로 적립(적립금 사용으로 구매시에도 원래의 상품적립금 그대로 적립)</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="border border-dark border-bottom-10">
                    <label><input type="radio" name="de_point_use_standard" value="0" show_yn="N" target-div="saveMoney_div_config_1" <?php echo get_checked($default['de_point_use_standard'], '0'); ?> /> 적립안함(적립금 사용 구매시 구매건에 대한 적립금 적립불가)</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="border border-dark border-bottom-10">
                    <label><input type="radio" name="de_point_use_standard" value="2" show_yn="Y" target-div="saveMoney_div_config_1" <?php echo get_checked($default['de_point_use_standard'], '2'); ?> /> 금액대비 차등 적립 (적립금 사용 구매로 인하여 할인받은 할인율을 해당 구매건의 적립률에서 차감)</label>
                  </div>

                  <div id="saveMoney_div_config_1" class="hidden">
                    <div class="pull-left">
                      적립금 계산 시
                      <select name="de_point_use_standard_unit" id="de_point_use_standard_unit">
                        <option value="0" <?php echo get_selected($default['de_point_use_standard_unit'], '0'); ?>>절사안함</option>
                        <option value="1" <?php echo get_selected($default['de_point_use_standard_unit'], '1'); ?>>일원단위</option>
                        <option value="10" <?php echo get_selected($default['de_point_use_standard_unit'], '10'); ?>>십원단위</option>
                        <option value="100" <?php echo get_selected($default['de_point_use_standard_unit'], '100'); ?>>백원단위</option>
                        <option value="1000" <?php echo get_selected($default['de_point_use_standard_unit'], '1000'); ?>>천원단위</option>
                      </select>
                    </div>
                    <div class="pull-left hidden" id="dv_de_point_use_standard_unit_type">
                      로
                      <select name="de_point_use_standard_unit_type" id="de_point_use_standard_unit_type">
                        <option value="roundup" <?php echo get_selected($default['de_point_use_standard_unit_type'], 'roundup'); ?>>올림</option>
                        <option value="rounddown" <?php echo get_selected($default['de_point_use_standard_unit_type'], 'rounddown'); ?>>내림</option>
                        <option value="round" <?php echo get_selected($default['de_point_use_standard_unit_type'], 'round'); ?>>반올림</option>
                      </select>
                    </div>
                    <script>
                      $("input[name='de_point_use_standard']").change(function() {
                        var value = $(this).val();
                        if (value == '2') {
                          $('#saveMoney_div_config_1').removeClass('hidden');
                        } else {
                          $('#saveMoney_div_config_1').removeClass('hidden').addClass('hidden');
                        }
                      });

                      $("#de_point_use_standard_unit").change(function() {
                        var value = $(this).val();
                        if (value != '0') {
                          $('#dv_de_point_use_standard_unit_type').removeClass('hidden');
                        } else {
                          $('#dv_de_point_use_standard_unit_type').removeClass('hidden').addClass('hidden');
                        }
                      });

                      $("#dv_de_point_use_standard_unit_type").change();
                    </script>
                  </div>

                </td>
              </tr>

              <tr scope='row'>
                <th>회원가입 적립금</th>
                <td colspan="3">
                  신규회원 가입 시 <input type="text" class="frm_input text-center" value="<?php echo $config['cf_register_point']; ?>" id="cf_register_point" name="cf_register_point" required="required" /> 원 적립
                </td>
              </tr>
              <tr>
                <th scope="row"><label for="cf_use_recommend">생일축하 적립금</label></th>
                <td colspan="3">
                  <input type="text" name="cf_extra_point_1" value="<?php echo $config['cf_extra_point_1'] ?>" id="cf_extra_point_1" class="frm_input text-center"> 원 적립
                </td>
              </tr>
              <tr scope='row'>
                <th rowspan=3>리뷰작성 적립금</th>
                <td colspan="3">
                  <span style="width: 90px; display: inline-block;">리뷰 작성</span>
                  <input type="text" class="frm_input text-center" value="<?php echo $config['cf_review_write_point']; ?>" id="cf_review_write_point" name="cf_review_write_point" required="required" /> 원 적립
                </td>
              </tr>
              <tr scope='row'>
                <td colspan="3">
                  <span style="width: 90px; display: inline-block;">포토 리뷰 작성</span>
                  <input type="text" class="frm_input text-center" value="<?php echo $config['cf_review_photo_point']; ?>" id="cf_review_photo_point" name="cf_review_photo_point" required="required" /> 원 적립
                </td>
              </tr>
              <tr scope='row'>
                <td colspan="3">
                  <span style="width: 90px; display: inline-block;">최초 리뷰 작성</span>
                  <input type="text" class="frm_input text-center" value="<?php echo $config['cf_review_first_point']; ?>" id="cf_review_first_point" name="cf_review_first_point" required="required" /> 원 적립
                </td>
              </tr>
              <tr>
                <th scope="row"><label for="cf_use_recommend">추천인 적립금</label></th>
                <td colspan="3">
                  <label><input type="checkbox" name="cf_use_recommend" value="1" id="cf_use_recommend" <?php echo $config['cf_use_recommend'] ? 'checked' : ''; ?>> 사용</label>
                  <input type="text" name="cf_recommend_point" value="<?php echo $config['cf_recommend_point'] ?>" id="cf_recommend_point" class="frm_input text-center"> 원 적립
                </td>
              </tr>
              <tr>
                <th scope="row"><label for="cf_app_install">APP 설치 적립금</label></th>
                <td colspan="3">
                  <input type="text" name="cf_install_point" value="<?php echo $config['cf_install_point'] ?>" id="cf_install_point" class="frm_input text-center"> 원 적립
                </td>
              </tr>

            </tbody>


          </table>
        </div>

      </div>

      <div class="x_panel">
        <div class="x_title">
          <h4><span class="fa fa-check-square"></span> 사용/제한 설정<small></small></h4>

          <div class="clearfix"></div>
        </div>

        <div class="tbl_frm01 tbl_wrap">
          <table>
            <tbody>
              <tr scope='row' hidden>
                <th>
                  적립금 사용가능<br>
                  최소 상품구매 합계액 설정
                </th>
                <td colspan="5">
                  상품 구매 합계액 최소 <input type="text" class="frm_input text-center" value="<?php echo $default['de_use_product_max_point']; ?>" id="de_use_product_max_point" name="de_use_product_max_point" required="required" /> 원 이상일 때 적립금 사용가능
                </td>
              </tr>
              <tr scope='row'>
                <th>
                  적립금 사용가능<br>
                  최소 누적 적립금액 설정
                </th>
                <td colspan="5">
                  누적 적립금액 최소 <input type="text" class="frm_input text-center" value="<?php echo $default['de_settle_min_point']; ?>" id="de_settle_min_point" name="de_settle_min_point" required="required" /> 원 이상일 때 적립금 사용가능
                </td>
              </tr>
              <tr scope='row'>
                <th rowspan="4">
                  1회 사용 적립금<br>
                  최대 사용한도 설정
                </th>
                <td colspan="5">
                  <div class="border border-dark border-bottom-10">
                    <label><input type="radio" name="de_use_max_point_type" value="0" <?php echo get_checked($default['de_use_max_point_type'], '0'); ?> /> 한도제한없음</label>
                  </div>
                  <input type="hidden" value="<?php echo $default['de_settle_max_point']; ?>" id="de_settle_max_point" name="de_settle_max_point" />
                </td>
              </tr>
              <tr>
                <td colspan="5">
                  <div class="border border-dark border-bottom-10">
                    <label><input type="radio" name="de_use_max_point_type" value="1" <?php echo get_checked($default['de_use_max_point_type'], '1'); ?> />
                      1회 최대 <input type="text" class="frm_input text-center" value="<?php echo ($default['de_use_max_point_type'] == '1') ? $default['de_settle_max_point'] : "" ?>" id="de_settle_max_point1" name="de_settle_max_point1" /> 원 까지만 사용가능</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="5">
                  <div class="border border-dark border-bottom-10">
                    <label><input type="radio" name="de_use_max_point_type" value="2" <?php echo get_checked($default['de_use_max_point_type'], '2'); ?> />
                      상품구매 합계액의 <input type="text" class="frm_input text-center" value="<?php echo ($default['de_use_max_point_type'] == '2') ? $default['de_settle_max_point'] : "" ?>" id="de_settle_max_point2" name="de_settle_max_point2" /> % 까지만 사용가능</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="5">
                  <div class="border border-dark border-bottom-10">
                    <label><input type="radio" name="de_use_max_point_type" value="3" <?php echo get_checked($default['de_use_max_point_type'], '3'); ?> /> 총 결제예정금액에 따른 적립금 사용한도 설정</label>
                  </div>

                  <div class="tbl_frm01 tbl_wrap hidden" id="tbl_de_use_max_point_type">
                    <table>
                      <tbody>
                        <tr>
                          <th colspan="2" class="" style="text-align:center;">총 결제예정금액</th>
                          <th colspan="3" class="frm_input " style="text-align:center;">적립금 사용한도</th>
                        </tr>
                        <tr>
                          <td colspan="2">
                            <div class="border border-dark border-bottom-10">
                              <label><input type="text" class="frm_input text-center" value="<?php echo $default['de_use_point_min_price']; ?>" id="de_use_point_min_price" name="de_use_point_min_price" /> 원 이상
                                <input type="text" class="frm_input text-center" value="<?php echo $default['de_use_point_max_price']; ?>" id="de_use_point_max_price" name="de_use_point_max_price" /> 원 미만 일 경우</label>
                            </div>
                          </td>
                          <td colspan="2">
                            <div class="border border-dark border-bottom-10">
                              <label> <input type="text" class="frm_input text-center" value="<?php echo $default['de_settle_max_point']; ?>" id="de_settle_max_point3" name="de_settle_max_point3" /> 원 까지만 사용가능</label>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <script>
                    $("input[name='de_use_max_point_type']").change(function() {
                      var value = $(this).val();

                      $('#de_settle_max_point1').prop("disabled", true);
                      $('#de_settle_max_point1').val("");
                      $('#de_settle_max_point2').prop("disabled", true);
                      $('#de_settle_max_point2').val("");
                      $('#tbl_de_use_max_point_type').removeClass('hidden').addClass('hidden');

                      if (value == '0') {
                        $('#tbl_de_use_max_point_type').removeClass('hidden').addClass('hidden');
                      } else if (value == '1') {

                        $('#de_settle_max_point1').val($('#de_settle_max_point').val());
                        $('#de_settle_max_point1').prop("disabled", false);
                        $('#tbl_de_use_max_point_type').removeClass('hidden').addClass('hidden');
                      } else if (value == '2') {
                        $('#de_settle_max_point2').val($('#de_settle_max_point').val());
                        $('#de_settle_max_point2').prop("disabled", false);
                        $('#tbl_de_use_max_point_type').removeClass('hidden').addClass('hidden');

                      } else if (value == '3') {
                        $('#de_settle_max_point3').val($('#de_settle_max_point').val());
                        $('#tbl_de_use_max_point_type').removeClass('hidden');
                      }
                    });

                    //alert($("input[name='de_use_max_point_type']:checked").val());
                    $("input[name='de_use_max_point_type']:checked").change();
                  </script>

                </td>
              </tr>
              <tr scope='row'>
                <th rowspan="2">적립금 사용 예외 설정</th>
                <td colspan="5">
                  <div class="border border-dark border-bottom-10">
                    <label><input type="checkbox" name="de_use_except_item_limit_use" id="de_use_except_item_limit_use" value="1" <?php echo get_checked($default['de_use_except_item_limit_use'], "1") ?> /> 지정된 분류에 포함된 상품적립금 사용제한</label>
                    <input type="hidden" value="<?php echo $default['de_use_except_it_id_list'] ?>" name="de_use_except_it_id_list" id="de_use_except_it_id_list" />
                    <button type="button" class="btn btn-default frm_input" id="coupon_btn_product_type" target-data="coupon_category_modal">상품분류 선택</button>
                  </div>
                </td>
              </tr>
              <tr>
                <td colspan="5">
                  <div class="border border-dark border-bottom-10">
                    <label><input type="checkbox" name="de_use_except_ca_limit_use" id="de_use_except_ca_limit_use" value="1" <?php echo get_checked($default['de_use_except_ca_limit_use'], "1") ?> /> 지정된 상품 적립금 사용제한</label>
                    <input type="hidden" value="<?php echo $default['de_use_except_ca_id_list'] ?>" name="de_use_except_ca_id_list" id="de_use_except_ca_id_list" />
                    <button type="button" class="btn btn-default frm_input" id="coupon_btn_category_type" target-data="coupon_product_modal">상품선택</button>
                  </div>
                </td>
              </tr>
            </tbody>
            <tr>
              <td colspan="5" style="text-align:right;">
                <input type="submit" class="btn btn-primary" id="coupon_btn_update" value="저장"></input>
              </td>
            </tr>

          </table>
        </div>

      </div>

    </div>

  </form>
</div>

<script>
  $(document).ready(function() {
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
  });

  $(document).on("click", "button[name='coupon_btn_category_delete']", function() {
    $(this).parent().parent().remove();
  });

  function fconfigform_submit(f) {
    if ($("#de_use_except_item_limit_use").is(":checked") && $("#de_use_except_it_id_list").val() == "") {
      alert("적립금 사용제한 상품을 선택해주십시오.");
      return false;
    }
    if ($("#de_use_except_ca_limit_use").is(":checked")) {
      var ca_id_list = new Array();
      $('#coupon_ul_category li').each(function() {
        ca_id_list.push($(this).attr("data"));
      });
      $('#de_use_except_ca_id_list').val(ca_id_list.join(","));

      if ($("#de_use_except_ca_id_list").val() == "") {
        alert("상품적립금 사용제한 분류를 선택해주십시오.");
        return false;
      }
    }

    f.action = "./configform_saveMoney_config_update.php";
    return true;
  }
</script>

<!-- @END@ 내용부분 끝 -->

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
                    for ($i = 0; $ca_row = sql_fetch_array($result); $i++) {
                      $len = strlen($ca_row['ca_id']) / 2 - 1;

                      $nbsp = "";
                      for ($i = 0; $i < $len; $i++)
                        $nbsp .= "&nbsp;&nbsp;&nbsp;";

                      echo "<option value=\"{$ca_row['ca_id']}\">$nbsp{$ca_row['ca_name']}</option>\n";
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
          it_id_list: $("#de_use_except_it_id_list").val()
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
          not_it_id_list: $("#de_use_except_it_id_list").val()
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

        $("#de_use_except_it_id_list").val($it_id.join(","));
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
      var de_use_except_it_id_list = $it_id.join(",");

      if ($("#de_use_except_it_id_list").val() != "") de_use_except_it_id_list += "," + $("#de_use_except_it_id_list").val();

      $("#de_use_except_it_id_list").val(de_use_except_it_id_list);

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
                      for ($i = 0; $ca_row = sql_fetch_array($result); $i++) {
                        $ca_name = $ca_row['ca_name'];
                        if ($ca_row['ca_name'] != $ca_row['ca_name2']) {
                          $ca_name = $ca_row['ca_name2'] . '>' . $ca_name;
                        }
                        if ($ca_row['ca_name'] != $ca_row['ca_name1']) {
                          $ca_name = $ca_row['ca_name1'] . '>' . $ca_name;
                        }

                        /*
                            $len = strlen($row['ca_id']) / 2 - 1;

                            $nbsp = "";
                            for ($i=0; $i<$len; $i++)
                                $nbsp .= "&nbsp;&nbsp;&nbsp;";
                            */

                        echo "<option value=\"{$ca_row['ca_id']}\">$nbsp{$ca_name}</option>\n";
                      }
                      ?>
                    </select>
                    <button type="button" class="btn btn-default" id="coupon_btn_category_add">추가</button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <ul data-role="listview" id="coupon_ul_category">
                      <?php
                      if ($default['de_use_except_ca_id_list'] != '') {
                        $cm_item_ca_id_list = implode("','", explode(',', $default['de_use_except_ca_id_list']));

                        $sql = " select  a.ca_id, a.ca_name
                                            ,b.ca_id as ca_id1, b.ca_name as ca_name1
                                            ,c.ca_id as ca_id2, c.ca_name as ca_name2
                                    from    {$g5['g5_shop_category_table']} as a
                                            left outer join {$g5['g5_shop_category_table']} as b
                                              on left(a.ca_id,2) = b.ca_id
                                            left outer join {$g5['g5_shop_category_table']} as c
                                              on left(a.ca_id,4) = c.ca_id
                                    where   a.ca_id in ('{$cm_item_ca_id_list}')
                                    order by a.ca_order, a.ca_id; ";

                        $result = sql_query($sql);
                        for ($i = 0; $ca_row = sql_fetch_array($result); $i++) {
                          $ca_name = $ca_row['ca_name'];
                          if ($ca_row['ca_name'] != $ca_row['ca_name2']) {
                            $ca_name = $ca_row['ca_name2'] . '>' . $ca_name;
                          }
                          if ($ca_row['ca_name'] != $ca_row['ca_name1']) {
                            $ca_name = $ca_row['ca_name1'] . '>' . $ca_name;
                          }
                      ?>
                          <li data="<?php echo $ca_row['ca_id'] ?>"><?php echo $ca_name ?>
                            <div class="pull-right"><button type="button" class="btn btn-sm btn-default" name="coupon_btn_category_delete">X</button></div>
                          </li>
                      <?php
                        }
                      }
                      ?>
                    </ul>
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