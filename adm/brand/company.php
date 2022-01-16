<?php
$sub_menu = "920101";
include_once('./_common.php');

$mb_id = $member['mb_id'];
    
$mb = get_member($mb_id);
if (!$mb['mb_id'])
    alert('존재하지 않는 회원자료입니다.');

if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'] && $mb['mb_id'] != $member['mb_id'])
    alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

$g5['title'] = '판매자 정보관리';

include_once (G5_ADMIN_PATH.'/admin.head.php');

$sql = "select * from lt_member_company where mb_id = '{$mb_id}' ";
$cp = sql_fetch($sql);
if(!$cp) $w = "c";

$token = get_admin_token(); 
?>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	
		<?php 
		if($cp['company_code'] != ""){
		?>
    	<div class="x_content">
    		<div class="" role="tabpanel" data-example-id="togglable-tabs">
        	  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        		<li role="presentation" class="<?php echo ($w==""||$w=="u"?"active":"") ?>"><a href="./company.php?w=" >판매자정보</a></li>
        		<li role="presentation" class="<?php echo ($w=="l"?"active":"") ?>"><a href="./company.php?w=l" >탈퇴신청</a></li>
              </ul>
    	  	  <div class="clearfix"></div>
    		</div>
    	</div>
		<?php 
		}
		
		if($w==""){
		    include_once ('./company_view.php');
		} elseif($w=="u" || $w=="c"){
		    include_once ('./company_form.php');
		} elseif($w=="l"){
		    include_once ('./company_leave.php');
		}?>
	</div>
  </div>
</div>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>