<?php
//$sub_menu = '100310';
$sub_menu = '10';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '메인페이지 관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');

$sql_common = " from lt_banner_type where use_yn = 'Y' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "select * ". $sql_common. " order by bt_id asc ";
$result = sql_query($sql);
$data = sql_fetch($sql);
?>



<!-- @START@ 내용부분 시작 -->




<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> 배너 설정 및 목록<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>


            <div class="x_content">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                        <div class="local_ov01 local_ov"><span class="btn_ov01"><span class="ov_txt">전체 </span><span class="ov_num"> <?php echo $total_count; ?>건</span></span></div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                        <a href="./main_banner.update.form.php" class="btn btn-success">배너등록</a>
                    </div>
                </div>


                <div class="tbl_head01 tbl_wrap">
                    <table>
                        <caption><?php echo $g5['title']; ?> 목록</caption>
                        <thead>
                            <tr>
                                <th scope="col" colspan="2">영역명</th>
                                <th scope="col">노출상태</th>
                                <th scope="col">관리</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 1; $row = sql_fetch_array($result); $i++) {
                                $bg = 'bg' . ($i % 2);
                                $status = $row['use_yn'] == 'Y' ? "Y" : "Y";
                            ?>
                                <tr class="<?php echo $bg; ?>">
                                    
                                    <td class="td_left">
                                    <a href="./main_banner.list.php?w=&amp;cp_category=<?php echo $row['bt_type']; ?>"><?php echo $row['bt_name']; ?></a>
                                    </td>
                                    <td>이미지</td>
                                    <td>
                                        <label><input type="radio" name="use_yn_<?= $i ?>" value=Y <?php echo get_checked($status, 'Y') ?>>노출</label>&nbsp;&nbsp;
                                        <label><input type="radio" name="use_yn_<?= $i ?>" value=N <?php echo get_checked($status, 'N') ?>>숨김</label>&nbsp;&nbsp;
                                    </td>
                                    <td><a href="./main_banner.list.php?w=&amp;cp_category=<?php echo $row['bt_type']; ?>">관리</a></td>
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
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                        <a href="./main_banner.update.form.php" class="btn btn-success">배너등록</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>