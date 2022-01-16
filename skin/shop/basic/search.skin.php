<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<!-- container -->
<div id="container">
	<!-- lnb -->
	<div id="lnb" class="header_bar alignC blind">
		<h1 class="title"><span>제품 검색</span></h1>
	</div>
	<!-- //lnb -->
	<div class="content comm search_container">
		<?php include_once(G5_SHOP_SKIN_PATH . '/search_box.php'); ?>
		<?php include_once(G5_SHOP_SKIN_PATH . '/popular_box.php'); ?>
		<?php include_once(G5_SHOP_SKIN_PATH . '/search_complete_box.php'); ?>
	</div>
</div>
<!-- //container -->

<script>
	$(function() {
		var cookieList = function(cookieName) {
			var cookie = $.cookie(cookieName);
			var items = cookie ? cookie.split(/,/) : new Array();
			return {
				"add": function(val) {
					if (cookie == undefined || cookie.indexOf(val) == -1) {
						items.push(val);
						$.cookie(cookieName, items.join(','));
					}
				},
				"clear": function() {
					items = null;
					$.cookie(cookieName, null);
				},
				"items": function() {
					return items;
				}
			}
		}

		$(document).ready(function() {

			$.search = function(type, text) {
				if (text) {
					$("#search_text").val(text);
				}

				if (window.event.keyCode == 13) {
					type = "search";
				}

				if (type == "search" && $("#search_text").val().length <= 0) {
					alert("검색어를 입력하세요.");
					return;
				}

				$.post(
					"<?php echo $ajax_url; ?>", {
						type: type,
						search_text: encodeURIComponent($("#search_text").val())
					},
					function(data) {
						if (type == 'search') {
							$("#search_name").html($("#search_text").val());
							$("#item_box").html(data.view_text);
							$('#popular_box').removeAttr('hidden').attr('hidden', true);
							$('#complete_box').removeAttr('hidden');
							cookieList("cookieList").add($("#search_text").val());

							if (data.view_text == '') {
								$('#nodata').removeAttr('hidden').attr('hidden', false);
							} else {
								$('#nodata').removeAttr('hidden').attr('hidden', true);
							}


							$.search_history_create();
						} else if (type == 'recommend') {
							$("#search_list").html(data.view_text);
						}
					}
				);

			};

			$.clear = function() {
				$("#search_text").val('');
				$("#search_list").html('');
				$('#complete_box').removeAttr('hidden').attr('hidden', true);
				$('#popular_box').removeAttr('hidden');
			};

			$.search_history_create = function() {

				var list = new cookieList("cookieList").items();
				var html = "";
				for (var i = 0; i < list.length; i++) {
					if (list[i] == 'null') continue;
					html += '<li>';
					html += '	<a href="#" onclick="$.search(\'search\', \'' + list[i] + '\')">';
					html += '		<span class="">' + list[i] + '</span>';
					html += '	</a>';
					html += '	<a href="#" class="btn_delete" onclick="$.search_history_delete(\'' + list[i] + '\')"><span class="blind">삭제</span></a>';
					html += '</li>';
				}
				$('#history_box').html(html);

			};

			$.search_history_delete = function(cookieName) {
				var list = new cookieList("cookieList").items();
				cookieList("cookieList").clear();
				for (var i = 0; i < list.length; i++) {
					if (list[i] != cookieName) {
						cookieList("cookieList").add(list[i]);
					}
				}
				$.search_history_create();

			};

			$.search_history_create();
		});

	});
</script>
<?php
						include_once(G5_PATH . '/tail.php');
?>