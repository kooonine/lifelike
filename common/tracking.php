<?
include_once('_common.php');
require_once(G5_LIB_PATH . '/Unirest.php');

$request = new Unirest;
$invc_no = urldecode($_GET['invc_no']);
$invc_co = urldecode($_GET['invc_co']);
$level = array(1 => "배송준비중", 2 => "집화완료", 3 => "배송중", 4 => "지점도착", 5 => "배송출발", 6 => "배송완료");
$company_code = $now_status = "";
$tracking = "조회된 배송정보가 없습니다";

$response = $request->get($default['de_tracking_api_company'] . '?t_key=' . $default['de_tracking_api_key']);

if ($response->code === 200) {
	$company = $response->body->Company;
	foreach ($company as $com) {
		if ($com->Name == $invc_co) $company_code = $com->Code;
	}
}


if (!empty($invc_no) && !empty($company_code)) {
	$tracking_url = sprintf("%s?t_key=%s&t_code=%s&t_invoice=%s", $default['de_tracking_api'], $default['de_tracking_api_key'], $company_code, $invc_no);
	$tracking_response = $request->get($tracking_url);

	if ($tracking_response->code === 200 && $tracking_response->body->result == 'Y') {
		$tracking = $tracking_response->body;
		$now_status = $level[$tracking->level];
	}
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
							<? if (is_object($tracking)) { ?>
								<strong class="result alignR">
									<span class="category round_none"><?= $now_status ?></span>
								</strong>
							<? } ?>
						</div>
						<div class="order_list border_box">
							<ul>
								<li>
									<span class="item">택배사</span>
									<strong class="result"><?= $invc_co ?></strong>
								</li>
								<li>
									<span class="item">운송장 번호</span>
									<strong class="result"><?= $invc_no ?></strong>
								</li>
							</ul>
						</div>
						<div class="delivery_step">
							<ul>
								<?
								if (is_object($tracking)) {
									$last_step = count($tracking->trackingDetails) - 1;
									for ($i = $last_step; $i >= 0; $i--) {
										$di = $tracking->trackingDetails[$i];
										if ($di) {
											echo '<li class="step' . $i . ' ' . (($i == $last_step) ? "ok" : "") . '">';
											echo '<strong>' . $di->kind . ' (' . $di->where . ')</strong>';
											echo '<span>' . $di->timeString . '</span>';
											echo '</li>';
										}
									}
								} else {
									echo '<li class="step ok"><strong>' . $tracking . '</strong></li>';
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
						<? if (is_object($tracking)) { ?>
							<strong class="result alignL">
								<span class="category round_none"><?= $now_status ?></span>
							</strong>
						<? } ?>
					</div>
					<div class="order_list border_box gray_box">
						<ul>
							<li>
								<span class="item">택배사</span>
								<strong class="result"><?= $invc_co ?></strong>
							</li>
							<li>
								<span class="item">운송장 번호</span>
								<strong class="result"><?= $invc_no ?></strong>
							</li>
						</ul>
					</div>
					<div class="delivery_step">
						<ul>
							<?
							if (is_object($tracking)) {
								$last_step = count($tracking->trackingDetails) - 1;
								for ($i = $last_step; $i >= 0; $i--) {
									$di = $tracking->trackingDetails[$i];
									if ($di) {
										echo '<li class="step' . $i . ' ' . (($i == $last_step) ? "ok" : "") . '">';
										echo '<strong>' . $di->kind . ' (' . $di->where . ')</strong>';
										echo '<span>' . $di->timeString . '</span>';
										echo '</li>';
									}
								}
							} else {
								echo '<li class="step ok"><strong>' . $tracking . '</strong></li>';
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