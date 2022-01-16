<?
include_once('_common.php');
require_once(G5_LIB_PATH . '/Unirest.php');

$invc_no = $_GET['invc_no'];
//$invc_no = "353981783072";

function curl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$g = curl_exec($ch);
	curl_close($ch);
	return $g;
}

$now_status = "";
if (isset($_GET['invc_no']) && $invc_no != "") {
	$response = curl(CJ_TRACKING_URL . $invc_no);

	if ($response !== false) {
		$response = trim($response);
		$response = json_decode($response, true);
		$tracking = $response['tracking'];

		if (is_array($tracking)) {
			$now_status = $response['tracking'][0]['gbnm'];
		}
	}
} else {
	$tracking = "운송장 번호가 없습니다.";
}

if ($is_mobile || $view_popup) {
	?>
	<!DOCTYPE html>
	<html lang="ko">
	<title>배송 조회</title>

	<head>

		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">

		<!-- 스타일 -->
		<link rel="stylesheet" type="text/css" href="<?= G5_URL; ?>/js/swiper/swiper.min.css">
		<link rel="stylesheet" type="text/css" href="<?= G5_URL; ?>/css/m_common.css" />
		<link rel="stylesheet" type="text/css" href="<?= G5_URL; ?>/css/m_ui.css" />

		<!-- 스크립트 -->
		<script src="<?= G5_URL; ?>/js/jquery-1.8.3.min.js" type="text/javascript"></script>
		<script src="<?= G5_URL; ?>/js/swiper/swiper.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?= G5_URL; ?>/js/m_ui.js" type="text/javascript"></script>

	</head>

	<body>

		<!-- popup -->
		<section class="popup_container layer">
			<div class="inner_layer">
				<!-- lnb -->
				<div id="lnb" class="header_bar">
					<h1 class="title"><span>배송 조회</span></h1>
					<a href="#" class="btn_closed" onclick="var win = window.open('', '_self'); win.close();self.close();return false;"><span class="blind">닫기</span></a>
				</div>
				<!-- //lnb -->

				<div class="content">
					<div class="grid">

						<div class="order_title">
							<span class="item bold">배송 상황</span>
							<? if (is_array($tracking)) { ?>
								<strong class="result alignR">
									<span class="category round_none"><?= $now_status ?></span>
								</strong>
							<? } ?>
						</div>
						<div class="order_list border_box">
							<ul>
								<li>
									<span class="item">택배사</span>
									<strong class="result">
										CJ대한통운 (CJGLS)
									</strong>
								</li>
								<li>
									<span class="item">운송장 번호</span>
									<strong class="result">
										<?= $invc_no ?>
									</strong>
								</li>
							</ul>
						</div>
						<div class="delivery_step">
							<ul>
								<?
									if (is_array($tracking)) {
										for ($i = 0; $i < count($tracking); $i++) {
											$di = $tracking[$i];
											if ($di) {
												echo '<li class="step' . $i . ' ' . (($i == 0) ? "ok" : "") . '">';
												echo '<strong>' . $di['gbnm'] . ' (' . $di['brannm'] . ')</strong>';
												echo '<span>' . $di['scandt'] . ' ' . $di['scanhr'] . '</span>';

												echo '</li>';
											}
										}
									} else {
										echo $tracking;
									}
									?>
							</ul>
						</div>
					</div>

				</div>
			</div>
		</section>
		<!-- //popup -->

	</body>

	</html>
<? } else { ?>
	<section class="popup_container layer" id="od_delivery_view">
		<div class="inner_layer" style="top:10%;">

			<div class="content">
				<div class="grid">

					<div class="title_bar">
						<h2 class="g_title_01">배송 조회</h2>
					</div>

					<div class="order_title none">
						<span class="item bold">배송 상황</span>
						<? if (is_array($tracking)) { ?>
							<strong class="result alignL">
								<span class="category round_none"><?= $now_status ?></span>
							</strong>
						<? } ?>
					</div>
					<div class="order_list border_box gray_box">
						<ul>
							<li>
								<span class="item">택배사</span>
								<strong class="result">
									CJ대한통운 (CJGLS)
								</strong>
							</li>
							<li>
								<span class="item">운송장 번호</span>
								<strong class="result">
									<?= $invc_no ?>
								</strong>
							</li>
						</ul>
					</div>
					<div class="delivery_step">
						<ul>
							<?
								if (is_array($tracking)) {
									for ($i = 0; $i < count($tracking); $i++) {
										$di = $tracking[$i];
										if ($di) {
											echo '<li class="step' . $i . ' ' . (($i == 0) ? "ok" : "") . '">';
											echo '<strong>' . $di['gbnm'] . ' (' . $di['brannm'] . ')</strong>';
											echo '<span>' . $di['scandt'] . ' ' . $di['scanhr'] . '</span>';

											echo '</li>';
										}
									}
								} else {
									echo $tracking;
								}
								?>
						</ul>
					</div>
				</div>
			</div>
			<a class="btn_closed" onclick="$('#od_delivery_view').remove();" style="cursor: pointer;"><span class="blind">닫기</span></a>
		</div>
	</section>
	<!-- //popup -->

<? } ?>