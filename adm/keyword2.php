<?php
$sub_menu = '800800';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');


$g5['title'] = '시그니처 관리';
include_once('./admin.head.php');

?>

<div class="row">
<div class="x_panel">

    <div class="x_title">
    	<h4><span class="fa fa-check-square"></span> 시그니처 관리<small></small></h4>
    	<div class="clearfix"></div>
    </div>

    <form name="keywordlist" id="keywordlist" action="./keyword_update.php" method="post">
        <div class="tbl_head01 tbl_wrap">
            <table>
                <colgroup>
                    <col style="width:90%;">
                    <col style="width:10%;">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">?_?</th>
                        <th scope="col">?_?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" size ="70" name ="keyWord" value = "<?php echo $config['cf_keyword']; ?>">
                        </td>
                        <td>
                            <input type = "submit" value ="수정">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
</div>

<script>
$(function() {
    $('#keywordlist').submit(function() {
        if(confirm("인기키워드를 수정하시겠습니까?")) {
            return true;
        } else {
            return false;
        }
    });
});
</script>

<?php
include_once ('./admin.tail.php');
?>