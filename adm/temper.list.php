<?php
$sub_menu = '800820';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '템퍼 관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');

$sql_common = " from lt_temper ";

$sql = "select * $sql_common WHERE tp_type = 0 order by tp_num ASC ";
$sql1 = "select * $sql_common WHERE tp_type = 1 order by tp_num ASC ";

$itemResult = sql_fetch($sql);
$pcResult = sql_query($sql1);

?>

<!-- @START@ 내용부분 시작 -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> 배너<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                        <a onclick="return numSave(false)" class="btn btn-success">저장</a>
                        <a href="./temper.update.form.php?w=pc" class="btn btn-success">배너등록</a>
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
                        <caption>템버 목록</caption>
                        <thead>
                            <tr>
                                <th scope="col">순서</th>
                                <th scope="col">정렬</th>
                                <th scope="col">이미지</th>
                                <th scope="col">상태</th>
                                <th scope="col">관리</th>
                            </tr>
                        </thead>
                        <tbody id="tvlist">
                            <input type="hidden" name ="tp_id_list" id ="tp_id_list" value = "">
                            <?php
                            for ($i = 0; $row = sql_fetch_array($pcResult); $i++) {
                                $status = $row['tp_use'] ? "사용" : "미사용";
                            ?>
                                <tr>
                                    <td class="td_chk sort_td">
                                       
                                        <input type="hidden" name ="sort[<?php echo $i; ?>]" value = "<?php echo $i + 1; ?>">
                                        <?php echo ($i + 1); ?>
                                    </td>
                                    <td class="td_chk tp_id_sort" data-value="<?php echo $row['tp_id'] ?>">
                                        <span class="glyphicon glyphicon-chevron-up" onclick="tvSort(this, 'up')"></span>
                                        <span class="glyphicon glyphicon-chevron-down" onclick="tvSort(this, 'down')"></span>
                                    </td>
                                    <td>
                                        <img src="<?php echo $row['tp_img']; ?>" class="img-thumbnail" id="imgimgFile" style="width: 150px; height: 150px;">
                                    </td>
                                    <td><?php echo $status; ?></td>
                                    <td class="td_mng td_mng_m">
                                        <a href="./temper.update.form.php?w=u&amp;tp_id=<?php echo $row['tp_id']; ?>" class="btn btn-success">수정</a>
                                        <a href="./temper.update.php?w=d&amp;tp_id=<?php echo $row['tp_id']; ?>" onclick="return delete_tv(this);" class="btn btn-danger">삭제</a>
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

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> 상품 목록<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                        <a onclick="return itemSave()" class="btn btn-success">상품저장</a>
                    </div>
                </div>

                <tr>
                    <th scope="row">템퍼 상품 </th>
                    <td scope="row" colspen=2>
                        선택 상품&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_item[1]" id="cp_item_set_item_1" value="<?= $itemResult['tp_item'] ?>" readonly><button type="button" class="btn frm_input" target-data="coupon_product_modal" data-item-idx=1 onclick=openCpItemPopup(this)>상품선택</button><br>
                        <!-- 선택 분류&nbsp;&nbsp;<input type="text" style="width: 60%;" name="cp_item_set_category[1]" id="cp_item_set_category_1" value="" readonly><button type="button" class="btn frm_input" target-data="coupon_category_modal" data-item-idx=1 onclick=openCpItemPopup(this)>분류선택</button> -->
                    </td>
                </tr>


            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="coupon_product_modal" tabindex="-1" role="dialog" aria-labelledby="coupon_product_modal">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">상품선택</h4>
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
                                        <?
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
                                <th scope="row"><label>상품번호/상품명/sap코드</label></th>
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
                        <? include_once(G5_ADMIN_URL . '/design/design_component_itemsearch.php'); ?>
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
    function numSave(e) {
        if(confirm("저장하시겠습니까?")) {
            let tpNum = $("#tp_id_list").val();
            if (e) {
                tpNum = $("#tp_id_list2").val();
            }
            if (tpNum == '') {
                location.reload();
                return false;
            }  
            $.ajax({
                url: "./temper.update.php",
                method: "POST",
                data: {
                    'w' : 's',
                    'tpNum' : tpNum
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
    function itemSave() {
        if(confirm("저장하시겠습니까?")) {
            let tpItem = $("#cp_item_set_item_1").val();

            $.ajax({
                url: "./temper.update.php",
                method: "POST",
                data: {
                    'w' : 'is',
                    'tpItem' : tpItem
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
        const $rows = $("#tvlist>tr>td.tp_id_sort");
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
            $("#tvlist>tr>td.tp_id_sort").each(function(idx, elem) {
                values.push($(elem).data("value"));
            });
            $("#tp_id_list").val(values.join(','));
        }
    }

    function openCpItemPopup(elem) {
        const id = $(elem).attr("target-data");
        CpItemIndex = $(elem).data("item-idx");
        tblProductFormBind();
        $('#coupon_ul_category').html("");
        $('#' + id).modal('show');
    }
    function tblProductFormBind() {
        var $table = $("#tblProductForm");
        $.post(
            "<?= G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
                w: "u",
                it_id_list: $("#cp_item_set_item_" + CpItemIndex).val()
            },
            function(data) {
                $table.empty().html(data);
            }
        );
    }
    $("#btnSearch").click(function(event) {
        var $table = $("#tblProduct");
        $.post(
            "<?= G5_ADMIN_URL ?>/design/design_component_itemsearch.php", {
                ca_id: $("#ca_id").val(),
                stx: $("#stx").val(),
                not_it_id_list: $("#cp_item_set_item_" + CpItemIndex).val(),
                search: 'searchTemper'
            },
            function(data) {
                $table.empty().html(data);
            }
        );
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
        var it_ids = $it_id.join(",");
        if ($("#cp_item_set_item_" + CpItemIndex).val() != "") it_ids += "," + $("#cp_item_set_item_" + CpItemIndex).val();
        $("#cp_item_set_item_" + CpItemIndex).val(it_ids);
        tblProductFormBind();
        $("#btnSearch").click();
    });
    $("#btnProductDel").click(function(event) {
        if (!is_checked("chk2[]") && !is_checked("n_chk2[]")) {
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
            var $nchk = $("input[name='n_chk2[]']");
			for (var j = 0; j < $nchk.size(); j++) {
				if (!$($nchk[j]).is(':checked')) {
					var k = $($nchk[j]).val();
					$it_id.push($("input[name='n_it_id2[" + k + "]']").val());
				}
			}
            $("#cp_item_set_item_" + CpItemIndex).val($it_id.join(","));
            tblProductFormBind();
        }
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>