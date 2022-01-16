<?php
$sub_menu = '900140';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "w");

$fm_id = 1;
$sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
$fm = sql_fetch($sql);
$fa_category1_arr = array();
$fa_category1_arr = explode("|", $fm['fm_subject']);

if (!$fa_category1){
    $fa_category1 = '주문/결제';
}

$sql_common = " from {$g5['faq_table']} where fm_id = '$fm_id' and fa_category1 = '$fa_category1' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "select * $sql_common order by fa_category2, fa_order , fa_id ";
$result = sql_query($sql);



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
        
        <div class="local_ov01 local_ov">
           <span class="btn_ov01"><span class="ov_txt"> 등록된 FAQ 상세내용</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
        </div>

        <div class="local_desc01 local_desc">
            <ol>
                <li>FAQ는 무제한으로 등록할 수 있습니다</li>
                <li><strong>FAQ 상세내용 추가</strong>를 눌러 자주하는 질문과 답변을 입력합니다.</li>
            </ol>
        </div>

        <div id="myTabContent" class="tab-content">
    	
            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
    			<a href="./faqform.php?fa_category1=<?php echo $fa_category1; ?>" class="btn btn_02">FAQ 상세내용 추가</a>
            </div>
            
            <div class="tbl_head01 tbl_wrap">
                <table>
                <caption><?php echo $g5['title']; ?> 목록</caption>
                <thead>
                <tr>
                    <th scope="col" width="5%">번호</th>
                    <th scope="col" width="10%">카테고리</th>
                    <th scope="col" width="30%">질문</th>
                    <th scope="col" width="40%">답변</th>
                    <th scope="col" width="5%">순서</th>
                    <th scope="col" width="10%">관리</th>
                </tr>
                </thead>
                <tbody>
                <?php
                
                
                
                for ($i=0; $row=sql_fetch_array($result); $i++)
                {
                    $row1 = sql_fetch(" select COUNT(*) as cnt from {$g5['faq_table']} where fm_id = '{$row['fm_id']}' ");
                    $cnt = $row1['cnt'];
            
                    $s_mod = icon("수정", "");
                    $s_del = icon("삭제", "");
            
                    $num = $i + 1;
            
                    $bg = 'bg'.($i%2);
                ?>
            
                <tr class="<?php echo $bg; ?>">
                    <td class="td_num"><?php echo $num; ?></td>
                    <td class="td_left"><?php echo stripslashes($row['fa_category2']); ?></td>
                    <td class="td_left"><?php echo stripslashes($row['fa_subject']); ?></td>
                    <td class="td_left"><?php echo stripslashes($row['fa_content']); ?></td>
                    <td class="td_num"><?php echo $row['fa_order']; ?></td>
                    <td class="td_mng td_mng_m">
                        <a href="./faqform.php?w=u&amp;fm_id=<?php echo $row['fm_id']; ?>&amp;fa_id=<?php echo $row['fa_id']; ?>&amp;fa_category1=<?php echo $row['fa_category1']; ?>" class="btn btn_03"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span>수정</a>
                        <a href="./faqformupdate.php?w=d&amp;fm_id=<?php echo $row['fm_id']; ?>&amp;fa_id=<?php echo $row['fa_id']; ?>" onclick="return delete_confirm(this);" class="btn btn_02"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span>삭제</a>
                    </td>
                </tr>
            
                <?php
                }
            
                if ($i == 0) {
                    echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
                }
                ?>
                </tbody>
                </table>
            
            </div>
        	
    	</div>
    </div>
</div>
</div></div></div>


<!-- @END@ 내용부분 끝 -->

<?php
include_once ('../admin.tail.php');
?>