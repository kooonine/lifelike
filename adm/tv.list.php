<?php
$sub_menu = '800830';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '라이프라이크TV 관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');

$sql_common = " from lt_lifeliketv ";

$sql = "select * $sql_common order by tv_num ASC ";
$result = sql_query($sql);
?>

<!-- @START@ 내용부분 시작 -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> 라이프라이크TV 목록<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                        <a onclick="return numSave()" class="btn btn-success">저장</a>
                        <a href="./tv.update.form.php" class="btn btn-success">등록</a>
                    </div>
                </div>
                <div class="tbl_head01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col style="width:10%;">
                            <col style="width:10%;">
                            <col style="width:40%;">
                            <col style="width:20%;">
                            <col style="width:20%;">
                        </colgroup>
                        <caption>라이프라이크TV 목록</caption>
                        <thead>
                            <tr>
                                <th scope="col">순서</th>
                                <th scope="col">정렬</th>
                                <th scope="col">url</th>
                                <th scope="col">상태</th>
                                <th scope="col">관리</th>
                            </tr>
                        </thead>
                        <tbody id="tvlist">
                            <input type="hidden" name ="tv_id_list" id ="tv_id_list" value = "">
                            <?php
                            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                $status = $row['tv_use'] ? "사용" : "미사용";
                            ?>
                                <tr>
                                    <td class="td_chk sort_td">
                                       
                                        <input type="hidden" name ="sort[<?php echo $i; ?>]" value = "<?php echo $i + 1; ?>">
                                        <?php echo ($i + 1); ?>
                                    </td>
                                    <td class="td_chk tv_id_sort" data-value="<?php echo $row['tv_id'] ?>">
                                        <span class="glyphicon glyphicon-chevron-up" onclick="tvSort(this, 'up')"></span>
                                        <span class="glyphicon glyphicon-chevron-down" onclick="tvSort(this, 'down')"></span>
                                    </td>
                                    
                                    <!-- <td> -->
                                        <!-- <?php echo $row['tv_num']; ?> -->
                                        <!-- 여기에 넣어야함 ㅋㅋㅋ -->
                                    <!-- </td> -->

                                    <td><?php echo $row['tv_url']; ?></td>
                                    <td><?php echo $status; ?></td>
                                    <td class="td_mng td_mng_m">
                                        <a href="./tv.update.form.php?w=u&amp;tv_id=<?php echo $row['tv_id']; ?>" class="btn btn-success">수정</a>
                                        <a href="./tv.update.php?w=d&amp;tv_id=<?php echo $row['tv_id']; ?>" onclick="return delete_tv(this);" class="btn btn-danger">삭제</a>
                                    </td>
                                </tr>
                            <?php
                            }

                            if ($i == 0) {
                                echo '<tr><td colspan="5" class="empty_table">등록된 내용이 없습니다</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="clearfix"></div><br />
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function numSave() {
        if(confirm("저장하시겠습니까?")) {

            let tvNum = $("#tv_id_list").val()
            if (tvNum == '') {
                location.reload();
                return false;
            }  
            $.ajax({
                url: "./tv.update.php",
                method: "POST",
                data: {
                    'w' : 's',
                    'tvNum' : tvNum,
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    if(result =='success') {
                        location.reload();
                    } else {
                    }
                }
            });
            return true;
        } else {
            return false;
        }
    }
    function delete_tv() {

        if(confirm("삭제하시겠습니까?")) {
            return true;
        } else {
            return false;
        }
    }

    function tvSort(elem, action) {

        const value = $(elem).parent().data("value");
        const $rows = $("#tvlist>tr>td.tv_id_sort");
        const $sort_val =Number($(elem).closest('tr').children('.sort_td').children('input').val());
        let $current, targetIdx , changeIdx;

        $rows.each(function(idx, elem) {
            if ($(elem).data("value") == value) {
                targetIdx = action == 'up' ? idx - 1 : idx + 1;
                $current = $(elem).parent();
            }
        });

        if (targetIdx >= 0 && targetIdx < $rows.length) {

            $rows.each(function(idx, elem) {
                if (idx == targetIdx) {
                    if (action == 'up') {
                        changeIdx = $sort_val - 1;
                        $(elem).parent().before($current);
                        $(elem).closest('tr').children('.sort_td').children('input').val($sort_val);
                        $(elem).closest('tr').children('.sort_td').children('input').attr("name","sort["+($sort_val-1)+"]");

                        $(elem).closest('tr').prev().children('.sort_td').children('input').val(changeIdx);
                        $(elem).closest('tr').prev().children('.sort_td').children('input').attr("name","sort["+(changeIdx-1)+"]");
                    } else {
                        changeIdx = $sort_val + 1;
                        $(elem).parent().after($current);
                        $(elem).closest('tr').children('.sort_td').children('input').val($sort_val);
                        $(elem).closest('tr').children('.sort_td').children('input').attr("name","sort["+($sort_val-1)+"]");

                        $(elem).closest('tr').next().children('.sort_td').children('input').val(changeIdx);
                        $(elem).closest('tr').next().children('.sort_td').children('input').attr("name","sort["+(changeIdx-1)+"]");

                    }
                }
            });

            let values = [];
            $("#tvlist>tr>td.tv_id_sort").each(function(idx, elem) {
                values.push($(elem).data("value"));
            });
            $("#tv_id_list").val(values.join(','));
        }
    }  
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>