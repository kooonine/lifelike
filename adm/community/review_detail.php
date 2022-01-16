<?php
include_once('./_common.php');

$sql = "select 	a.*, b.od_id, c.it_name
        from 	lt_shop_item_use as a
        		left outer join lt_shop_cart as b
        			on a.ct_id = b.ct_id
                left join {$g5['g5_shop_item_table']} c on (a.it_id = c.it_id)
        where	a.is_id = '{$is_id}' ";
$row  = sql_fetch($sql);


if ($row) {

  $thumbnail_width = 500;

  $is_star    = get_star($row['is_score']);
  $is_name    = get_text($row['is_name']);
  $is_subject = conv_subject($row['is_subject'], 50, "…");
  //$is_content = ($row['wr_content']);
  $is_content = get_view_thumbnail(conv_content($row['is_content'], 1), $thumbnail_width);

  $href = G5_SHOP_URL . '/item.php?it_id=' . $row['it_id'];
  $href2 = G5_ADMIN_URL . '/shop_admin/orderform.php?od_id=' . $row['od_id'];
?>

  <!-- Modal : 리뷰내용 상세보기 -->
  <!--  -->
  <div id="modal_review_detail" class="modal modal_review_detail" role="dialog">
    <div class="modal-dialog modal-lg">

      <form name="fmodalreviewdetail" id="fmodalreviewdetail" method="post" action="./review_update.php" onsubmit="return fmodalreviewdetail_submit(this);">
        <input type="hidden" name="btn_submit" id="btn_submit" value="답글">
        <input type="hidden" name="is_id" value="<?php echo $is_id ?>">
        <input type="hidden" name="token" value="<?php echo $token ?>">

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
                    <th scope="row">작성자</th>
                    <td><?php echo $row['mb_name'] . '(' . $row['mb_id'] . ')' ?></td>
                    <th scope="row">작성일</th>
                    <td><?php echo $row['is_time'] ?></td>
                  </tr>
                  <tr rowspan="3">
                    <th scope="row">상품주문번호</th>
                    <td><a href="<?php echo $href2; ?>" target="_blank"><?php echo $row['od_id'] ?></a></td>
                    <th scope="row">상품명</th>
                    <td>
                      <a href="<?php echo $href; ?>" target="_blank"><?php echo get_it_image($row['it_id'], 50, 50); ?>
                        <?php echo $row['it_name']; ?></a></td>
                  </tr>
                  <tr>
                    <th scope="row">평점</th>
                    <td colspan="3"><?php echo str_repeat('★', $is_star) ?></td>
                  </tr>
                  <tr>
                    <th rowspan="3">내용</th>
                  </tr>
                  <tr>
                    <td colspan="3">
                      <?php echo $is_content; // 사용후기 내용 
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3">
                      <?php
                      if ($row['is_file']) {
                        $fi_sql = " select * from lt_shop_item_use_file where is_id = '" . $row['is_id'] . "' order by bf_no ";
                        $fi_result = sql_query($fi_sql);
                        while ($fi_row = sql_fetch_array($fi_result)) {
                          $filepath = G5_DATA_PATH . '/file/itemuse';

                          $source_file = $filepath . '/' . $fi_row['bf_file'];

                          if (!is_file($source_file)) // 원본 파일이 없다면
                            continue;

                          if ($fi_row['bf_type'] == '0') {
                            //movie
                            $src = G5_DATA_URL . '/file/itemuse/' . $fi_row['bf_file'];

                            echo '<div class="view ico_video">';
                            echo '<video controls width="350px"><source src="' . $src . '" type="video/mp4" width="350px">Your browser does not support the video tag.</video>';
                            echo '</div>';
                          } else {
                            $thumb = thumbnail($fi_row['bf_file'], $filepath, $filepath, 228, 228, false, false, 'center', false, $um_value = '80/0.5/3');
                            $src = G5_DATA_URL . '/file/itemuse/' . $thumb;
                            echo '<img src="' . $src . '" >';
                          }
                        }
                      }
                      ?>
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
                    <td><?php echo ($row['is_type'] == '1') ? "프리미엄" : "일반" ?> 구매평</td>
                    <th scope="row">전시상태</th>
                    <td>
                      <label><input type="radio" value="1" id="is_confirm1" name="is_confirm" <?php echo get_checked($row['is_confirm'], '1') ?>> Y</label> &nbsp; &nbsp;
                      <label><input type="radio" value="0" id="is_confirm0" name="is_confirm" <?php echo get_checked($row['is_confirm'], '0') ?>> N </label> &nbsp; &nbsp;
                    </td>
                  </tr>
                  <tr rowspan="3">
                    <th scope="row">답글여부</th>
                    <td><?php echo ($row['is_reply_content'] != '' ? 'Y' : 'N'); ?></td>
                    <th scope="row">베스트리뷰</th>
                    <td>선정상태 : <?php echo ($row['is_best'] ? 'Y' : 'N'); ?></td>
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
                      <textarea class="form-control" rows="5" id="is_reply_content" name="is_reply_content"><?php echo $row['is_reply_content'] ?></textarea>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div style="width: 550px; background: #eaeaea; padding: 10px; margin: 10px;">
                <p>
                  ※신고로 비노출의 경우, 고객에게 적립된 포인트는 회수되지 않습니다.<br>
                </p>
              </div>
              <div class="tbl_frm01 tbl_wrap">
                <table>
                  <caption>리뷰글 신고</caption>
                  <colgroup>
                    <col class="grid_4">
                    <col>
                    <col class="grid_3">
                  </colgroup>
                  <tbody>
                    <tr>
                      <td>
                        <label><input type="radio" value="0" id="is_spam0" name="is_spam" <?php echo get_checked($row['is_spam'], '0') ?>> 신고안함</label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label><input type="radio" value="1" id="is_spam1" name="is_spam" <?php echo get_checked($row['is_spam'], '1') ?>> 욕설/비방</label> &nbsp; &nbsp;
                        <label><input type="radio" value="2" id="is_spam2" name="is_spam" <?php echo get_checked($row['is_spam'], '2') ?>> 광고/홍보글 </label> &nbsp; &nbsp;
                        <label><input type="radio" value="3" id="is_spam3" name="is_spam" <?php echo get_checked($row['is_spam'], '3') ?>> 개인정보유출</label> &nbsp; &nbsp;
                        <label><input type="radio" value="4" id="is_spam4" name="is_spam" <?php echo get_checked($row['is_spam'], '4') ?>> 게시글도배</label> &nbsp; &nbsp;
                        <label><input type="radio" value="5" id="is_spam5" name="is_spam" <?php echo get_checked($row['is_spam'], '5') ?>> 음란/선정성 </label> &nbsp; &nbsp;
                        <label><input type="radio" value="6" id="is_spam6" name="is_spam" <?php echo get_checked($row['is_spam'], '6') ?>> 저작권침해 </label> &nbsp; &nbsp;
                        <label><input type="radio" value="7" id="is_spam7" name="is_spam" <?php echo get_checked($row['is_spam'], '7') ?>> 기타</label>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>


            </div>
          </div>
          <div class="modal-footer">
            <div style="float: right;">
              <input type="submit" class="btn btn-success" id="btn_review_reply" value="리뷰저장"></input>
              <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
            </div>
          </div>
        </div>
      </form>
      <!-- Modal content-->
    </div>
  </div>
  <!-- Modal : 리뷰내용 상세보기 -->

  <script>
    $(function() {

      $("#btn_review_spam").click(function() {
        if ($(this).text() == "리뷰신고") {
          $("#dvSpam").css("display", "");
          $(this).text("리뷰 신고 저장");

        } else {
          //리뷰신고
          ///$(this).text()


        }
      });

    });

    function fmodalreviewdetail_submit() {

      return true;
    }
  </script>

<?php } ?>