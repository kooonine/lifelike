<?php
$sub_menu = '800030';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '시그니처 관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');

$sql_common = " from lt_signature ";

$sql = "select * $sql_common order by si_id desc ";
$result = sql_query($sql);
?>

<!-- @START@ 내용부분 시작 -->
<!-- ㅇdzㅇ -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> 시그니처 목록<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="form-group">
                    <!-- <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                        <div class="local_ov01 local_ov"><span class="btn_ov01"><span class="ov_txt">전체 </span><span class="ov_num"> <?php echo $total_count; ?>건</span></span></div>
                    </div> -->
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                        <a href="./signature.update.form.php" class="btn btn-success">등록</a>
                    </div>
                </div>
                <div class="tbl_head01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col style="width:60%;">
                            <col style="width:20%;">
                            <col style="width:20%;">
                        </colgroup>
                        <caption>시그니처 목록</caption>
                        <thead>
                            <tr>
                                <!-- <th scope="col">번호</th> -->
                                <!-- <th scope="col">제목</th> -->
                                <th scope="col">종류</th>
                                <th scope="col">상태</th>
                                <!-- <th scope="col">시작일시</th> -->
                                <!-- <th scope="col">종료일시</th> -->
                                <!-- <th scope="col">등록일</th> -->
                                <th scope="col">관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                $bg = 'bg' . ($i % 2);
                                $status = $row['si_use'] ? "사용" : "미사용";
                            ?>
                                <tr class="<?php echo $bg; ?>">
                                    <!-- <td class="td_num"><?php echo $row['cp_id']; ?></td> -->
                                    <!-- <td class="td_left">
                                        <a href="./special.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>"><?php echo $row['cp_subject']; ?></a>
                                    </td> -->
                                    <td><?php echo $row['si_type']; ?>시그니처</td>
                                    <td class="td_device"><?php echo $status; ?></td>
                                    <!-- <td class="td_datetime"><?php echo substr($row['cp_start_date'], 0, 16); ?></td> -->
                                    <!-- <td class="td_datetime"><?php echo substr($row['cp_end_date'], 0, 16); ?></td> -->
                                    <!-- <td class="td_datetime"><?php echo substr($row['cp_create_date'], 0, 10); ?></td> -->
                                    <td class="td_mng td_mng_m">
                                        <a href="./signature.update.form.php?w=u&amp;si_id=<?php echo $row['si_id']; ?>" class="btn btn-success"><span class="sound_only"><?php echo $row['cp_subject']; ?> </span>수정</a>
                                        <a href="./signature.update.php?w=d&amp;si_id=<?php echo $row['si_id']; ?>" onclick="return delete_confirm(this);" class="btn btn-danger"><span class="sound_only"><?php echo $row['cp_subject']; ?> </span>삭제</a>
                                    </td>
                                </tr>
                            <?php
                            }

                            if ($i == 0) {
                                echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
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

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>