<?php
//$sub_menu = '100310';
$sub_menu = '800831';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$tv_id = preg_replace('/[^0-9]/', '', $tv_id);

$html_title = "라이프라이크TV";
if ($w == "u") {
    $html_title .= " 수정";
    $sql = " select * from lt_lifeliketv where tv_id = '$tv_id' ";
    $tv = sql_fetch($sql);

} else {
    $html_title .= " 등록";
}

$g5['title'] = $html_title;

include_once(G5_ADMIN_PATH . '/admin.head.php');
?>
<!-- @START@ 내용부분 시작 -->

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">


            <form name="frmnewwin" action="./tv.update.php" onsubmit="return submit_check(this);" method="post" enctype="multipart/form-data">
                <input type="hidden" name="w" value="<?php echo $w; ?>">
                <input type="hidden" name="tv_id" value="<?php echo $tv_id; ?>">
                

                <div class="x_content">
                    <div class="tbl_frm01 tbl_wrap">
                        <table>
                            <caption><?php echo $g5['title']; ?></caption>
                            <colgroup>
                                <col class="grid_4">
                                <col>
                            </colgroup>
                            <tbody>

                                <tr>
                                    <th scope="row">URL</th>
                                    <td>
                                        <input type="text" id="tv_link" name="tv_url" value="<?php echo $tv['tv_url']; ?>" class="frm_input" size="100">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">사용여부</label></th>
                                    <td>
                                        <div class="radio">
                                            <label><input type="radio" name="tv_use" value=0 <?php echo get_checked($tv['tv_use'], 0) ?>>미사용</label>&nbsp;&nbsp;
                                            <label><input type="radio" name="tv_use" value=1 <?php echo get_checked($tv['tv_use'], 1) ?>>사용</label>&nbsp;&nbsp;
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="x_content">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                            <!-- <a href="./tv.update.php?w=d&amp;ba_id=<?php echo $ba_id; ?>" onclick="return delete_confirm(this);" class="btn btn-danger"><span class="sound_only"><?php echo $ba['ba_subject']; ?> </span>삭제</a> -->
                            <a href="./tv.list.php" class=" btn btn_02">목록</a>
                            <input type="submit" value="저장" class="btn btn-success">
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
    function submit_check(f) {
        if(confirm("저장하시겠습니까?")) {
            let tv_link = $("#tv_link").val()
            if (tv_link == '') {
                alert("URL을 입력해주세요");
                return false;
            }  
            return true;
        } else {
            return false;
        }
    }
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>