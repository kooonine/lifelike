<?
if (!defined('_GNUBOARD_')) exit;

$begin_time = get_microtime();

$files = glob(G5_ADMIN_PATH.'/css/admin_extend_*');
if (is_array($files)) {
	foreach ((array) $files as $k=>$css_file) {
		$fileinfo = pathinfo($css_file);
		$ext = $fileinfo['extension'];
		if( $ext !== 'css' ) continue;
		$css_file = str_replace(G5_ADMIN_PATH, G5_ADMIN_URL, $css_file);
		add_stylesheet('<link rel="stylesheet" href="'.$css_file.'">', $k);
	}
}

include_once(G5_PATH.'/adm/admin.head.sub.php');

function print_menu1($key, $no=''){
	global $menu;
	$str = print_menu2($key, $no);
	return $str;
}

function print_menu2($key, $no=''){
	global $menu, $auth_menu, $is_admin, $auth, $g5, $sub_menu;

	$str .= "<ul class=\"nav child_menu\">";
	for($i=1; $i<count($menu[$key]); $i++){
		if ($is_admin != 'super' && (!array_key_exists(substr($menu[$key][$i][0],0,2), $auth) || !strstr($auth[substr($menu[$key][$i][0],0,2)], 'r')))
			continue;

		if ($menu[$key][$i][4] != null && $menu[$key][$i][4] != 1){
			$str .= '<li><a>'.$menu[$key][$i][1].' <span class="fa fa-chevron-down"></span></a>';
			$str .= '<ul class="nav child_menu">';

			foreach($menu[$key][$i][4] as $key2=>$value) {
				$str .= '<li><a href="'.$menu[$key][$i][4][$key2][2].'">'.$menu[$key][$i][4][$key2][1].'</a></li>';
				$auth_menu[$menu[$key][$i][4][$key2][0]] = $menu[$key][$i][4][$key2][1];
			}

			$str .= '</ul></li>';
		} else {
			if($menu[$key][$i][1] == '사방넷 상품재고전송'){
				if($is_admin == 'super'){
					$str .= '<li><a href="'.$menu[$key][$i][2].'" >'.$menu[$key][$i][1].'</a></li>';
				}

			}
			// else if($menu[$key][$i][1] == '사방넷 주문 세트상품(공동기획)'){
			// 	if($is_admin == 'super' && $member['mb_id']='sbs608'){
			// 		$str .= '<li><a href="'.$menu[$key][$i][2].'" >'.$menu[$key][$i][1].'</a></li>';
			// 	}

			// }
			else{
				$str .= '<li><a href="'.$menu[$key][$i][2].'" >'.$menu[$key][$i][1].'</a></li>';
			}
		}

		$auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
	}
	$str .= "</ul>";

	return $str;
}

$adm_menu_cookie = array(
	'container' => '',
	'gnb'       => '',
	'btn_gnb'   => '',
);

if( ! empty($_COOKIE['g5_admin_btn_gnb']) ){
	$adm_menu_cookie['container'] = 'container-small';
	$adm_menu_cookie['gnb'] = 'gnb_small';
	$adm_menu_cookie['btn_gnb'] = 'btn_gnb_open';
}
?>

<script>
	var tempX = 0;
	var tempY = 0;

	function imageview(id, w, h)
	{

		menu(id);

		var el_id = document.getElementById(id);

		//submenu = eval(name+".style");
		submenu = el_id.style;
		submenu.left = tempX - ( w + 11 );
		submenu.top  = tempY - ( h / 2 );

		selectBoxVisible();

		if (el_id.style.display != 'none')
			selectBoxHidden(id);
	}
</script>

<div class="container body">
	<div class="main_container">
		<div class="col-md-3 left_col">
			<div class="left_col scroll-view">
				<div class="navbar nav_title" style="border: 0;">
					<a href="<?=G5_ADMIN_URL ?>" class="site_title">
						<?
						if($is_admin == 'super') echo '리탠다드 통합관리자';
						else if($is_admin == 'admin') echo '리탠다드 통합관리자';
						else if($is_admin == 'brand') echo '입점몰 관리자';
						?>
					</a>
				</div>
				<div class="clearfix"></div>
				<br />
				<!-- sidebar menu -->
				<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
					<div class="menu_section">
						<ul class="nav side-menu">
							<?
							$jj = 1;
							foreach($amenu as $key=>$value) {
								//echo print_r2($menu['menu'.$key][0]);
								if ($is_admin != 'super' && (!array_key_exists(substr($menu['menu'.$key][0][0],0,2), $auth) || !strstr($auth[substr($menu['menu'.$key][0][0],0,2)], 'r')))
									continue;
								$button_title = $menu['menu'.$key][0][1];
								?>
								<li><a><?=$button_title;?> <span class="fa fa-chevron-down"></span></a><?=print_menu1('menu'.$key, 1); ?></li>
								<?
								$jj++;
							}
							?>
						</ul>
					</div>
				</div>
				<!-- /sidebar menu -->
					<!-- /menu footer buttons
					<div class="sidebar-footer hidden-small">
						<a data-toggle="tooltip" data-placement="top" title="Settings">
							<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
						</a>
						<a data-toggle="tooltip" data-placement="top" title="FullScreen">
							<span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
						</a>
						<a data-toggle="tooltip" data-placement="top" title="Lock">
							<span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
						</a>
						<a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
							<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
						</a>
					</div>
				-->
				<!-- /menu footer buttons -->
			</div>
		</div>

		<!-- top navigation -->
		<div class="top_nav">
			<div class="nav_menu">
				<nav>
					<div class="nav toggle">
						<a id="menu_toggle"><i class="fa fa-bars"></i></a>
					</div>
					<ul class="nav navbar-nav navbar-right">
						<li class="" id="tnb_logout"><a href="<?=G5_ADMIN_URL ?>/logout.php">로그아웃</a></li>
						<li class=""><a href="<?=G5_URL ?>/index.php" class="tnb_shop" target="_blank" title="쇼핑몰 바로가기">쇼핑몰 이동</a></li>
						<li class="" onClick="window.open('<?=G5_ADMIN_URL ?>/configform_pwd_popup.php', 'win_admininfo_form', 'width=900, height=620,scrollbars=0,resizable=0');"><a href="#">관리자정보</a></li>
						<li class=""><a href="javascript:;"><?=$member['mb_name'] ?> 님</a></li>
					</ul>
				</nav>
			</div>
		</div>
		<!-- /top navigation -->
		<script>
			jQuery(function($){
				var menu_cookie_key = 'g5_admin_btn_gnb';
				$(".tnb_mb_btn").click(function(){
					$(".tnb_mb_area").toggle();
				});
				$("#btn_gnb").click(function(){
					var $this = $(this);
					try {
						if( ! $this.hasClass("btn_gnb_open") ){
							set_cookie(menu_cookie_key, 1, 60*60*24*365);
						} else {
							delete_cookie(menu_cookie_key);
						}
					}
					catch(err) {
					}
					$("#container").toggleClass("container-small");
					$("#gnb").toggleClass("gnb_small");
					$this.toggleClass("btn_gnb_open");
				});
				$(".gnb_ul li .btn_op" ).click(function() {
					$(this).parent().addClass("on").siblings().removeClass("on");
				});
			});
		</script>

		<!-- page content -->
		<div class="right_col" role="main">
			<div class="">
				<div class="page-title">
					<div class="title_left">
						<h3><?=$g5['title'] ?></h3>
					</div>
					<div class="title_right">
						<div class="col-md-12 col-sm-12 col-xs-12 text-right pull-right">
							<h5> 메인 > <?=$g5['title'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
