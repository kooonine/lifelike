<?php
// if (!defined('_GNUBOARD_')) exit;
?>
<!DOCTYPE html>

<head>
	<meta name="robots" content="NOINDEX, NOFOLLOW">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=yes">
	<title>소셜 로그인 - <?php echo $provider; ?></title>
</head>
<style>
	.loader,
	.loader:after {
		border-radius: 50%;
		width: 60px;
		height: 60px;
	}

	.loader {
		margin: 0 auto;
		margin-top: calc(50vh - 30px);
		font-size: 10px;
		position: relative;
		text-indent: -9999em;
		border-top: 4px solid rgba(0, 0, 0, 0.2);
		border-right: 4px solid rgba(0, 0, 0, 0.2);
		border-bottom: 4px solid rgba(0, 0, 0, 0.2);
		border-left: 4px solid #000;
		-webkit-transform: translateZ(0);
		-ms-transform: translateZ(0);
		transform: translateZ(0);
		-webkit-animation: load8 1s infinite linear;
		animation: load8 1s infinite linear;
	}

	@-webkit-keyframes load8 {
		0% {
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}

		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}

	@keyframes load8 {
		0% {
			-webkit-transform: rotate(0deg);
			transform: rotate(0deg);
		}

		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
</style>

<body>
	<div class="loader">Loading...</div>
	<?php if ((defined('G5_SOCIAL_IS_LOADING') && G5_SOCIAL_IS_LOADING) || (G5_SOCIAL_USE_POPUP && empty($login_action_url))) : ?>
		<script>
			window.location.href = window.location.href + "&redirect_to_idp=1";
		</script>
	<?php else : ?>
		<form name="loginform" method="post" action="<?php echo $login_action_url; ?>">
			<input type="hidden" id="url" name="url" value="<?php echo $url ?>">
			<input type="hidden" id="provider" name="provider" value="<?php echo $provider ?>">
			<input type="hidden" id="mb_id" name="mb_id" value="<?php echo $mb_id ?>">
			<input type="hidden" id="mb_password" name="mb_password" value="<?php echo $mb_password ?>">
		</form>
		<script>
			function init() {
				<?php if ($use_popup == 1 || !$use_popup) : ?>
					if (window.opener) {
						window.opener.name = "social_login";
						document.loginform.target = window.opener.name;
						document.loginform.submit();
						window.close();
					} else {
						document.loginform.submit();
					}
				<?php elseif ($use_popup == 2) : ?>
					document.loginform.submit();
				<?php endif ?>
			}
			init();
		</script>
	<?php endif ?>
</body>

</html>
<?php
die();
?>