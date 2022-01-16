<?php
$sub_menu = "900110";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = '게시물 관리';

if($wr_type == "") $wr_type = "1";
?>
<!-- @START@ 내용부분 시작 -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
  			<div class="x_content">
  				<div class="" role="tabpanel" data-example-id="togglable-tabs">
            	  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            		<li role="presentation" class="<?php echo ($wr_type=="1"?"active":"") ?>"><a href="./member_form.php?w=&mb_id=<?php echo $mb_id?>&mode=4&wr_type=1" >게시물 관리</a></li>
            		<li role="presentation" class="<?php echo ($wr_type=="2"?"active":"") ?>"><a href="./member_form.php?w=&mb_id=<?php echo $mb_id?>&mode=4&wr_type=2" >댓글 관리</a></li>
		          </ul>
  			  	  <div class="clearfix"></div>
				</div>
			</div>
			<?php 
			if($wr_type=="1"){
			    include_once ('./member_form_4_1.php');
			} elseif($wr_type=="2"){
			    include_once ('./member_form_4_2.php');
			} ?>


			
		</div>
	</div>
</div>
<!-- @END@ 내용부분 끝 -->
