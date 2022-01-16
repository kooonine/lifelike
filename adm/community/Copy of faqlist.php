<?php
$sub_menu = '900140';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$fm_id = 1;

$fa_category1_arr = array();
$fa_category1_arr[] = "회원관리";
$fa_category1_arr[] = "리스/결제/비용영수증";
$fa_category1_arr[] = "상품/배송";
$fa_category1_arr[] = "취소/반품/환불";
$fa_category1_arr[] = "혜택";
$fa_category1_arr[] = "케어서비스";

if (!$fa_category1){
    $fa_category1 = '회원관리';
}
$sql = "select fa_category2 from {$g5['faq_table']} where fa_category1 = '$fa_category1' group by fa_category2 ";
$fa_category2_row = sql_query($sql);



$g5['title'] = "FAQ 관리";
include_once ('../admin.head.php');
?>
<div class="row"><div class="col-md-12 col-sm-12 col-xs-12"><div class="x_panel">
<div class="x_content">

    <div class="" role="tabpanel" data-example-id="togglable-tabs">
        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <?php for ($i = 0; $i < count($fa_category1_arr); $i++) {
            echo '<li role="presentation" onclick="location.href=\'faqlist.php?fa_category1='.$fa_category1_arr[$i].'\'" class="'.(($fa_category1_arr[$i]==$fa_category1)?'active':'').'">';
            echo '<a href="#" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">'.$fa_category1_arr[$i].'</a></li>';
        }?>
        
        </ul>
        <div class="clearfix"></div>
        
        <div id="myTabContent" class="tab-content">
    	
            <div class="tbl_frm01 tbl_wrap">
                <table>
                <tbody>
                <tr>
                    <th scope="row"><label for="bo_table">카테고리 선택</label></th>
                    <td colspan="2">
                        <select name="selfa_category2" id="selfa_category2" class="frm_input" >
                        	<?php for ($i=0; $row=sql_fetch_array($fa_category2_row); $i++) {
                        	    
                        	    if (!$fa_category2 && $i==0){
                        	        $fa_category2 = $row['fa_category2'];
                        	    }
                        	    
                        	    echo '<option value="'.$row['fa_category2'].'" '.get_selected($row['fa_category2'], $fa_category2).'>'.$row['fa_category2'].'</option>';
                        	    
                        	} ?>
                        </select>
                    	<input type="text" class="frm_input" size="50" id="fa_category2" name="fa_category2" value="<?php echo $fa_category2; ?>">
                    	<button type="button" class="btn btn-success" id="btnAddfa_category2">카테고리수정</button>
                    </td>
                </tr>
                </tbody>
                </table>
        	</div>
        
            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
            	<button type="button" class="btn btn_02" id="btnAdd">항목추가</button>
            </div>

            <div class="tbl_frm01 tbl_wrap">
                <table>
                <colgroup>
                	<col width="5%">
                	<col width="30%">
                	<col width="55%">
                	<col width="10%">
                </colgroup>
                <thead>
            	<tr>
                	<th scope="col" style="text-align: center;">번호</th>
                	<th scope="col" style="text-align: center;">질문</th>
                	<th scope="col" style="text-align: center;">답변</th>
                	<th scope="col" style="text-align: center;">관리</th>
            	</tr>
                </thead>
                <tbody>
                <?php
                
                $sql_common = " from {$g5['faq_table']} where fm_id = '$fm_id' and fa_category1 = '$fa_category1' and fa_category2 = '$fa_category2' ";
                
                // 테이블의 전체 레코드수만 얻음
                $sql = " select count(*) as cnt " . $sql_common;
                $row = sql_fetch($sql);
                $total_count = $row['cnt'];
                
                $sql = "select * $sql_common order by fa_order , fa_id ";
                $result = sql_query($sql);
                
                for ($i=0; $row=sql_fetch_array($result); $i++)
                {
                    $num = $i + 1;
            
                    $bg = 'bg'.($i%2);
                ?>
            
                <tr class="<?php echo $bg; ?>">
                    <td class="td_num"><?php echo $num; ?></td>
                    <td class="td_left"><textarea class="resizable_textarea form-control" name="fa_subject[]" rows="3"><?=$row['fa_subject']?></textarea></td>
                    <td class="td_left"><textarea class="resizable_textarea form-control" name="fa_content[]" rows="3"><?=$row['fa_content']?></textarea></td>
                    <td class="td_center">
                        <a href="./faqformupdate.php?w=d&amp;fm_id=<?php echo $row['fm_id']; ?>&amp;fa_id=<?php echo $row['fa_id']; ?>" onclick="return delete_confirm(this);" class="btn btn-danger"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span>삭제</a>
                    </td>
                </tr>
            
                <?php
                }
            
                if ($i == 0) {
                    echo '<tr><td colspan="4" class="empty_table" style="text-align: center;">자료가 없습니다.</td></tr>';
                }
                ?>
                
                </tbody>
                </table>
        	</div>
        	
        
            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
            	<button type="button" class="btn btn-success" id="btnAdd">수정</button>
            </div>
        	
    	</div>
    </div>
</div>
</div></div></div>


<!-- @END@ 내용부분 끝 -->

<?php
include_once ('../admin.tail.php');
?>