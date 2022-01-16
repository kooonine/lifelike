<?php
$sub_menu = "20";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');


// 테이블의 전체 레코드수만 얻음
// $sql = " select count(*) as cnt " . $sql_common;
$sql = "SELECT COUNT(*) AS cnt FROM lt_sms_soldout GROUP BY ss_op_id";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if ($page_rows) $rows = $page_rows;
else $rows = 100;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
  $page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = "SELECT * FROM (SELECT * FROM lt_sms_soldout ORDER BY ss_numbers DESC) AS a GROUP BY ss_op_id ORDER BY ss_op_id DESC LIMIT $from_record, $rows ";

$result = sql_query($sql);
$qstr  = $qstr . '&amp;sca=' . $sca . '&amp;page=' . $page . '&amp;save_stx=' . $stx;

$token = get_admin_token();

$g5['title'] = '품절 SMS 대량 발송';
include_once('../admin.head.php');

?>


<!-- @START@ 내용부분 시작 -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          <h4><span class="fa fa-check-square"></span> 품절 SMS EXCEL 업로드<small></small></h4>
          <div class="clearfix"></div>
      </div>
    
      <form id='tset' name="fitem" method="post" action="/adm/shop_admin/smsPushExcel.php" enctype="multipart/form-data">
	        <p><input type="file" name="excelfile" id="excelfile" required="required" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"></p>
	        <input id='tset' type="submit" name="act_button" value="업로드"  class="btn btn_02">
      </form>
      <br>

      <div class="x_title">
        <h4><span class="fa fa-check-square"></span> 품절 SMS 대량 발송 조회<small></small></h4>
        <div class="clearfix"></div>
      </div>

      <form name="flist" id="flistSearch" class="local_sch01 local_sch">
      <input type="hidden" name="save_stx" value="<?php echo $stx; ?>">

      <div class="tbl_head01 tbl_wrap">
        <!-- <div class="pull-left">
          <span class="btn_ov01"><span class="ov_txt">검색결과</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
        </div> -->
        <div class="pull-right">
          <select name="page_rows" onchange="$('#flistSearch').submit();">
            <option value="100" <?php echo get_selected($page_rows, '100'); ?>>100개씩 보기</option>
            <option value="500" <?php echo get_selected($page_rows, '500'); ?>>500개씩 보기</option>
          </select>
        </div>
      </div>
      </form>


      <!-- <form name="fitemlistupdate" id="fitemlistupdate" method="post" action="./itemlistupdate.php" onsubmit="return fitemlist_submit(this);" autocomplete="off"> -->
      <form name="fitemlistupdate" id="fitemlistupdate" autocomplete="off">
        <input type="hidden" name="sca" value="<?php echo $sca; ?>">
        <input type="hidden" name="sst" value="<?php echo $sst; ?>">
        <input type="hidden" name="sod" value="<?php echo $sod; ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
        <input type="hidden" name="stx" value="<?php echo $stx; ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">

        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">

        <div class="tbl_head01 tbl_wrap">
          <table id="test">
            <caption>목록</caption>
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">전송일시</th>
                <th scope="col">발송 총 건수</th>
                <th scope="col">상세</th>
              </tr>
            </thead>
            <tbody>
              <?php
              for ($i = 0; $row = sql_fetch_array($result); $i++) {
                ?>

                <tr>
                  <td>
                    <?php echo $row['ss_op_id']; ?>
                  </td>
                  <td>
                    <?php echo $row['ss_regdatetime']; ?>
                  </td>
                  <td>
                    <?php echo $row['ss_numbers']; ?>
                  </td>
                  <td>
                    <label  onclick="showDetail(<?php echo $row['ss_op_id']; ?>)" style="margin-top: 5px; cursor: pointer;">상세보기</label>
                  </td>
                </tr>
              <?php
              }
              if ($i == 0)
                echo '<tr><td colspan="12" class="empty_table">자료가 한건도 없습니다.</td></tr>';
              ?>
            </tbody>
          </table>
        </div>
      </form>

      <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
    </div>
  </div>
</div>


<script>
  $(function() {

    $('#sh_datetime').val("<?php echo $sh_datetime ?>");

    //날짜 버튼
  });

  function showDetail(sh_no) {
    $.post(
      "configform_sms_send_soldout_detail.php", {
        sh_no: sh_no
      },
      function(data) {
        $("#dvDetail").empty().html(data);
      }
    );

    $('#detail_modal').modal('show');
  }
</script>

<!-- 상세보기 !!!!!!!!!!! -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">발송결과 상세 팝업</h4>
      </div>
      <div class="modal-body">
        <div class="tbl_frm01 tbl_wrap" id="dvDetail">
        </div>
        <!-- 몸체를 만들자 -->
        <!-- <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="all-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content2" aria-labelledby="stay-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content3" aria-labelledby="success-tab">
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content4" aria-labelledby="fail-tab">
            </div>
          </div>
          <div class="tbl_frm01 tbl_wrap" id="dvDetail">
          </div>
        </div> -->
      </div>
      <div class="modal-footer">
        <br><br><br>
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
      </div>
    </div>
  </div>
</div>

<!-- @END@ 내용부분 끝 -->

<?php
include_once('../admin.tail.php');
?>