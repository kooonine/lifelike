<?php
$test = false;

$sub_menu = "900110";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[substr($sub_menu,0,2)], 'w');

$g5['title'] = '게시물 관리';
include_once ('../admin.head.php');

if($wr_type == "") $wr_type = "1";
?>
<!-- @START@ 내용부분 시작 -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        
            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> <?php echo $g5['title']; ?><small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>

  			<div class="x_content">
  				<div class="" role="tabpanel" data-example-id="togglable-tabs">
            	  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            		<li role="presentation" class="<?php echo ($wr_type=="1"?"active":"") ?>"><a href="./post_management.php?wr_type=1" >게시물 관리</a></li>
            		<li role="presentation" class="<?php echo ($wr_type=="2"?"active":"") ?>"><a href="./post_management.php?wr_type=2" >댓글 관리</a></li>
		          </ul>
  			  	  <div class="clearfix"></div>
				</div>
			</div>
			<?php 
			if($wr_type=="1"){
			    include_once (G5_ADMIN_PATH.'/community/post_management_1.php');
			} elseif($wr_type=="2"){
			    include_once (G5_ADMIN_PATH.'/community/post_management_2.php');
			} ?>
			
		</div>
	</div>
</div>
<!-- @END@ 내용부분 끝 -->
<script>
	$(function(){ 
		window.addEventListener("keydown", (e) => {
        	if (e.keyCode == 13) {
        	    document.getElementById('fsearch').submit();
        	}
    	})
	})
</script>
<?php
include_once ('../admin.tail.php');
?>