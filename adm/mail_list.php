<?php
$sub_menu = '800300';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

$sql_common = " from {$g5['mail_table']} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select COUNT(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$page = 1;

$sql = " select * {$sql_common} order by ma_id desc ";
$result = sql_query($sql);

$g5['title'] = '메일폼 관리';
include_once('./admin.head.php');

$colspan = 7;
?>

<div class="row">
<div class="x_panel">

    <div class="x_title">
    	<h4><span class="fa fa-check-square"></span> 메일폼 관리<small></small></h4>
    	<div class="clearfix"></div>
    </div>

    <div class="local_desc01 local_desc">
        <p>
            <b>테스트</b>는 등록된 최고관리자의 이메일로 테스트 메일을 발송합니다.<br>
            현재 등록된 메일은 총 <?php echo $total_count ?>건입니다.<br>
        </p>
    </div>
    
    
    <form name="fmaillist" id="fmaillist" action="./mail_list_update.php" method="post">
    
    <div class="local_cmd01 local_cmd">
    	<div style="float: right">
    	<input type="submit" value="수정" class="btn btn_02" >
    	</div>
    </div>
    
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
        <tr>
             <th scope="col">번호</th>
            <th scope="col">메일항목</th>
            <th scope="col">제목</th>
            <th scope="col">작성일시</th>
            <th scope="col">
            	<label><input type="checkbox" name="chkall" value="1" id="chkall" title="전체선택" onclick="check_all(this.form)"> 사용유무</label>
            </th>
            <th scope="col">테스트</th>
            <!-- th scope="col">보내기</th -->
            <th scope="col">수정 및 관리</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            $s_vie = '<a href="./mail_form.php?w=u&amp;ma_id='.$row['ma_id'].'" class="btn btn_02">수정</a>';
            $s_vie .= '<a href="./mail_preview.php?ma_id='.$row['ma_id'].'" target="_blank" class="btn btn_03">미리보기</a>';
    
            $num = number_format($total_count - ($page - 1) * $config['cf_page_rows'] - $i);
    
            $bg = 'bg'.($i%2);
        ?>
    
        <tr class="<?php echo $bg; ?>">
            <td class="td_num_c"><?php echo $num ?></td>
            <td class="td_left">
            	<strong>
            	<?php 
            	    if($row['ma_type'] == '0') echo '[고객]'; 
                    elseif($row['ma_type'] == '1') echo '[관리자]';
                    elseif($row['ma_type'] == '2') echo '[브랜드]';
                ?>
                </strong>
            	<?php echo $row['ma_name'] ?>
            </td>
            <td class="td_left"><a href="./mail_form.php?w=u&amp;ma_id=<?php echo $row['ma_id'] ?>"><?php echo $row['ma_subject'] ?></a></td>
            <td class="td_datetime"><?php echo $row['ma_time'] ?></td>
            <td class="td_chk" style="width:100px;">
                <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['ma_subject']; ?> 메일</label>
                <input type="checkbox" id="chk_<?php echo $i ?>" name="chk[<?php echo $i ?>]" value="1" <?php echo get_checked($row['ma_use'], '1') ; ?> >
                <input type="hidden" id="ma_id_<?php echo $i ?>" name="ma_id[<?php echo $i ?>]" value="<?php echo $row['ma_id'] ?>">
            </td>
            <td class="td_mng"><a href="./mail_test.php?ma_id=<?php echo $row['ma_id'] ?>" class="btn btn_02">테스트</a></td>
            <!-- td class="td_send"><a href="./mail_select_form.php?ma_id=<?php echo $row['ma_id'] ?>">보내기</a></td -->
            <td class="td_mng" style="width:150px;"><?php echo $s_vie ?></td>
        </tr>
    
        <?php
        }
        if (!$i)
            echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
        ?>
        </tbody>
        </table>
    </div>
    </form>
</div>
</div>

<script>
$(function() {
    $('#fmaillist').submit(function() {
        if(confirm("전체 목록의 사용여부를 수정 하시겠습니까?")) {
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