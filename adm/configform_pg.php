<?php
$sub_menu = "100100";
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], 'r');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');
    
    $g5['title'] = 'PG이용현황';
    include_once ('./admin.head.php');
    
    get_admin_token();
    ?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

            <div class="x_title">
              <h4><span class="fa fa-check-square"></span> <?php echo $g5['title'] ?><small></small></h4>
              <label class="nav navbar-right"></label>
              <div class="clearfix"></div>
            </div>
            
            <div class="x_content">
            <div class="tbl_frm01 tbl_wrap">
            
            </div>
            </div>

		</div>
	</div>
</div>
<?php
include_once ('./admin.tail.php');
?>
