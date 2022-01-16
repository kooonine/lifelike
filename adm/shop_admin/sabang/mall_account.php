<?php
$sub_menu = '95';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '제휴몰계정 관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');

$sql_common = " from sabang_mall_code ";

$sql = "select * $sql_common order by smc_code ASC ";
$result = sql_query($sql);
?>

<!-- @START@ 내용부분 시작 -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> 제휴몰계정 목록<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="tbl_head01 tbl_wrap">
                    <table>
                        <colgroup>
                            <col style="width:20%;">
                            <col style="width:35%;">
                            <col style="width:35%;">
                            <col style="width:10%;">
                        </colgroup>
                        <caption>라이프라이크TV 목록</caption>
                        <thead>
                            <tr>
                                <th scope="col">mall</th>
                                <th scope="col">account</th>
                                <th scope="col">password</th>
                                <th scope="col">수정</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                if ($row['smc_sb_name'] !='자사몰' && $row['smc_sb_name'] !='카카오메이커스' && $row['smc_sb_name'] !='카카오톡스토어' && $row['smc_sb_name'] !='카카오선물하기') {
                            ?>
                                <tr>
                                    <input type="hidden" name="name_<?= $i?>" value="<?php echo $row['smc_sb_name']; ?>">
                                    <td><?php echo $row['smc_sb_name']; ?></td>

                                    <td><input type="text" value = "<?php echo $row['smc_account']; ?>" name ="acc_<?= $i?>"> 
                                    <? if ($row['smc_sb_name'] =='CJ온스타일') {?>
                                        <input type="text" value = "<?php echo $row['smc_account2']; ?>" name ="acc2_<?= $i?>">
                                    <? } ?>
                                    </td>
                                    <td><input type="text" value = "<?php echo $row['smc_password']; ?>" name ="pass1_<?= $i?>"> 
                                    <? if ($row['smc_sb_name'] =='텐바이텐') {?>
                                        <input type="text" value = "<?php echo $row['smc_password2']; ?>" name ="pass2_<?= $i?>">
                                    <? } ?>
                                    </td>
                                    <td class="td_mng td_mng_m">
                                        <a onclick="passModi(<?=$i?>)" class="btn btn-success"><span class="sound_only"></span>수정</a>
                                        <!-- <a href="./tv.update.php?w=d&amp;si_id=<?php echo $row['si_id']; ?>" onclick="return delete_confirm(this);" class="btn btn-danger"><span class="sound_only"><?php echo $row['cp_subject']; ?> </span>삭제</a> -->
                                    </td>
                                </tr>
                            <?php
                                }
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
<script>
    function passModi(e) {
        if(confirm("수정하시겠습니까?")) {
            let mallName = '';
            let id = '';
            let id2 = null;
            let pass1 = null;
            let pass2 = null;
            mallName = $("input[name='name_"+e+"']").val();
            id = $("input[name='acc_"+e+"']").val();
            id2 = $("input[name='acc2_"+e+"']").val();
            pass1 = $("input[name='pass1_"+e+"']").val();
            pass2 = $("input[name='pass2_"+e+"']").val();
            if (!id || id =='') return alert ('ID를 확인해주세요');
            if (!pass1 || pass1 =='') return alert ('비밀번호를 확인해주세요');

            $.ajax({
                url: "./mall_account_update.php",
                method: "POST",
                data: {
                    'mallName' : mallName,
                    'id' : id,
                    'pass1' : pass1,
                    'pass2' : pass2,
                    'id2' : id2
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function(result) {
                    if(result =='success') {
                        location.reload();
                    } else {
                        alert(result);  
                    }
                }
            });
            return true;
        } else {
            return false;
        }
    }
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>