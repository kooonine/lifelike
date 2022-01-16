<?
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
if($comment_order == ''){
	$comment_order = "wr_comment desc, ";
}
?>
<script>
// 글자수 제한
var char_min = parseInt(<?=$comment_min ?>); // 최소
var char_max = parseInt(<?=$comment_max ?>); // 최대
</script>
<div class="grid">
	<?
	if($w == ''){
		$w = 'c';
	}
	?>
	<h3 class="blind"><a name="comment">댓글 등록하기</a></h3>
	<div class="inp_ele comment_write" id="bo_vc_w">
		<? if($is_comment_write) {?>
			<form name="fviewcomment" id="fviewcomment" action="<?=$comment_action_url; ?>" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off" class="bo_vc_w">
				<input type="hidden" name="w" value="<?=$w ?>" id="w">
				<input type="hidden" name="bo_table" value="<?=$bo_table ?>">
				<input type="hidden" name="wr_id" value="<?=$wr_id ?>">
				<input type="hidden" name="comment_id" value="<?=$c_id ?>" id="comment_id">
				<input type="hidden" name="sca" value="<?=$sca ?>">
				<input type="hidden" name="sfl" value="<?=$sfl ?>">
				<input type="hidden" name="stx" value="<?=$stx ?>">
				<input type="hidden" name="spt" value="<?=$spt ?>">
				<input type="hidden" name="page" value="<?=$page ?>">
				<input type="hidden" name="is_good" value="">
				<div class="input">
					<textarea id="wr_content" name="wr_content" style="height:100px;" placeholder="댓글을 입력해주세요."></textarea>
				</div>
				<div class="btnarea">
					<button type="button" class="btn_common" id="btn_submit" onClick="javascript:$('#fviewcomment').submit()">등록</button>
				</div>
			</form>
		<? } else {?>
			<div class="input">
				<textarea id="wr_content" name="wr_content" style="height:100px;" placeholder="로그인 후 작성하실 수 있습니다." disabled></textarea>
			</div>
			<div class="btnarea">
				<button type="button" class="btn_common" disabled="disabled">등록</button>
			</div>			
		<? } ?>
	</div>

	<div class="tab_cont_wrap">
		<span class="comment_total">댓글<?=count($list);?></span>
		<div class="tab">
			<ul class="type2">
				<li <? if($comment_order == "wr_comment desc, ") {?>class="on"<? }?> ><a href="<?=G5_BBS_URL?>/board.php?bo_table=<?=$board['bo_table']?>&wr_id=<?=$wr_id?>&comment_order=wr_comment desc#comment"><span>등록순</span></a></li>
				<li <? if($comment_order == "wr_good desc, ") {?>class="on"<? }?> ><a href="<?=G5_BBS_URL?>/board.php?bo_table=<?=$board['bo_table']?>&wr_id=<?=$wr_id?>&comment_order=wr_good desc#comment"><span>추천순</span></a></li>
			</ul>
		</div>
		<div class="tab_cont" >
			<div class="comment_wrap tab_inner">
				<ul class = "comment_list">
					<?
					for ($i=0; $i<count($list); $i++) {
						$comment_id = $list[$i]['wr_id'];
						$cmt_depth = ""; // 댓글단계
						$cmt_depth = strlen($list[$i]['wr_comment_reply']) * 20;
						$str = $list[$i]['content'];
						if (strstr($list[$i]['wr_option'], "secret")){
							$str = $str;
						}
						$str = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp|mms)\:\/\/([^[:space:]]+)\.(mp3|wma|wmv|asf|asx|mpg|mpeg)\".*\<\/a\>\]/i", "<script>doc_write(obj_movie('$1://$2.$3'));</script>", $str);
						if (!$cmt_depth) {
							?>
							<li id="c_<?=$comment_id ?>">
								<div class="comment_box">
									<div class="user_bar">
										<span class="photo"><?=get_member_profile_img($list[$i]['mb_id'], 40, 40); ?></span>
										<span class="name"><?=$list[$i]['name'] ?></span>
									</div>
									<div class="text_area" id="save_comment_<?=$comment_id ?>">
										<? if (strstr($list[$i]['wr_option'], "secret")) echo "<img src=\"".$board_skin_url."/img/icon_secret.gif\" alt=\"비밀글\">"; ?>
										<?=$str ?>
									</div>
									<?
									if($list[$i]['is_reply'] || $list[$i]['is_edit'] || $list[$i]['is_del']) {
										$query_string = clean_query_string($_SERVER['QUERY_STRING']);

										if($w == 'cu') {
											$sql = " select wr_id, wr_content, mb_id, wr_good from $write_table where wr_id = '$c_id' and wr_is_comment = '1' ";
											$cmt = sql_fetch($sql);
											if (!($is_admin || ($member['mb_id'] == $cmt['mb_id'] && $cmt['mb_id'])))
												$cmt['wr_content'] = '';
											$c_wr_content = $cmt['wr_content'];
										}

										$c_reply_href = './board.php?'.$query_string.'&amp;c_id='.$comment_id.'&amp;w=c#bo_vc_w';
										$c_edit_href = './board.php?'.$query_string.'&amp;c_id='.$comment_id.'&amp;w=cu#bo_vc_w';
									}
									?>

									<div class="btn_comm">
										<span class="date"><?=$list[$i]['datetime'] ?></span>
										<?
											$sql = " select bg_flag from {$g5['board_good_table']}
											where bo_table = '{$bo_table}'
											and wr_id = '{$comment_id}'
											and mb_id = '{$member['mb_id']}'
											and bg_flag = 'nogood' ";
											$pickYN = sql_fetch($sql);
											if (!$pickYN['bg_flag']) {
										?>
										<button type="button" onclick="nogood_popup('<?=$comment_id ?>');" id="nogood<?=$comment_id?>">신고</button>
										<?php } ?>
										<? if ($list[$i]['is_del'])  { ?><a href="<?=$list[$i]['del_link']; ?>" onclick="return comment_delete();"><button type="button">삭제</button></a><? } ?>
										<div class="btn_comm_row">
											<? if ($list[$i]['is_reply']) { ?><a href="<?=$c_reply_href; ?>" onclick="comment_box('<?=$comment_id ?>', 'c'); return false;"><button type="button" class="btn small">답글</button></a><? } ?>
											<!-- 찜 눌르면 class="on" 추가 -->
											<?
											$sql = " select bg_flag from {$g5['board_good_table']}
											where bo_table = '{$bo_table}'
											and wr_id = '{$comment_id}'
											and mb_id = '{$member['mb_id']}'
											and bg_flag = 'good' ";
											$pickYN = sql_fetch($sql);

											$good_href = './good.php?bo_table='.$bo_table.'&amp;wr_id='.$comment_id.'&amp;good=good&amp;comment=1';
											?>
											<button type="button" class="pick ico <? if ($pickYN['bg_flag']) echo 'on';?>" href="<?=$good_href.'&amp;'.$qstr ?>">
												<span class="blind">찜</span><?=$list[$i]['wr_good']?></button>
											</div>
										</div>
										<div id="reply_<?=$comment_id ?>" class="reply_wrap"></div><!-- 답변 -->
									</div>
								</li>
							<? }else{ ?>
								<li id="c_<?=$comment_id ?>">
									<div class="reply_wrap">
										<ul class="reply_list">
											<li>
												<div class="reply_box">
													<div class="user_bar">
														<span class="name"><?=$list[$i]['name'] ?></span>
													</div>
													<div class="text_area" id="save_comment_<?=$comment_id ?>">
														<? if (strstr($list[$i]['wr_option'], "secret")) echo "<img src=\"".$board_skin_url."/img/icon_secret.gif\" alt=\"비밀글\">"; ?>
														<?=$str ?>
													</div>
													<div class="btn_comm">
														<span class="date line"><?=$list[$i]['datetime'] ?></span>
                										<?
                											$sql = " select bg_flag from {$g5['board_good_table']}
                											where bo_table = '{$bo_table}'
                											and wr_id = '{$comment_id}'
                											and mb_id = '{$member['mb_id']}'
                											and bg_flag = 'nogood' ";
                											$pickYN = sql_fetch($sql);
                											if (!$pickYN['bg_flag']) {
                										?>
                										<button type="button" onclick="nogood_popup('<?=$comment_id ?>');" id="nogood<?=$comment_id?>">신고</button>
                										<?php } ?>
														<? if ($list[$i]['is_del'])  { ?><a href="<?=$list[$i]['del_link']; ?>" onclick="return comment_delete();"><button type="button">삭제</button></a><? } ?>
														<div class="btn_comm_row">
															<? if($list[$i]['is_reply'] || $list[$i]['is_edit'] || $list[$i]['is_del']) {
																$query_string = clean_query_string($_SERVER['QUERY_STRING']);

																if($w == 'cu') {
																	$sql = " select wr_id, wr_content, mb_id from $write_table where wr_id = '$c_id' and wr_is_comment = '1' ";
																	$cmt = sql_fetch($sql);
																	if (!($is_admin || ($member['mb_id'] == $cmt['mb_id'] && $cmt['mb_id'])))
																		$cmt['wr_content'] = '';
																	$c_wr_content = $cmt['wr_content'];
																}

																$c_edit_href = './board.php?'.$query_string.'&amp;c_id='.$comment_id.'&amp;w=cu#bo_vc_w';
																?>
															<? } ?>
														</div>
													</div>
												</div>
											</li>
										</ul>
										<div id="edit_<?=$comment_id ?>" class="btn small"></div><!-- 수정 -->
									</div>
								</li>
							<? }?>
						<? }?>
					</ul>
				</div>
			</div>
		</div>
		<? if($total_count > $limit_count){?>
			<div class="btn_group"><a href="<?=G5_BBS_URL?>/board.php?bo_table=<?=$board['bo_table']?>&wr_id=<?=$wr_id?>&limit_count=<?=$limit_count+$board['bo_reply_rows'];?>" class="btn big border"><span>더보기</span></a></div>
		<? }?>
	</div>
	<!-- popup -->
	<section class="popup_container layer" id="declaration_popup" style="display: none;">
		<div class="inner_layer" style="top:100px;">
			<div class="grid">
				<div class="title_bar">
					<h2 class="g_title_01">신고</h2>
				</div>
				<div class="border_box alignC none">
					<p class="sm tb_cell">신고 사유를 선택 해 주세요.</p>
				</div>
				<ul class="declaration-list">
					<li><label><input type="radio" name="bg_comment" id="bg_comment1" value="욕설/비방"> 욕설/비방</label></li>
					<li><label><input type="radio" name="bg_comment" id="bg_comment2" value="광고/홍보글"> 광고/홍보글</label></li>
					<li><label><input type="radio" name="bg_comment" id="bg_comment3" value="개인정보유출"> 개인정보유출</label></li>
					<li><label><input type="radio" name="bg_comment" id="bg_comment4" value="게시글도배"> 게시글도배</label></li>
					<li><label><input type="radio" name="bg_comment" id="bg_comment5" value="음란/선정성"> 음란/선정성</label></li>
					<li><label><input type="radio" name="bg_comment" id="bg_comment6" value="저작권침해"> 저작권침해</label></li>
					<li><label><input type="radio" name="bg_comment" id="bg_comment7" value="기타"> 기타</label></li>
				</ul>
				<div class="order_list button_choice black">
					<ul class="onoff">
						<li class=""  onclick="$('#declaration_popup').css('display','none');"><a href="javascript:">취소</a></li>
						<li class="on" onclick="nogood_write();"><a href="javascript:">신고하기</a></li>
					</ul>
				</div>
				<a href="#" class="btn_closed" onclick="$('#declaration_popup').css('display','none');"><span class="blind">닫기</span></a>
			</div>
		</div>
	</section>
	<!-- //popup -->
	<script>
		var save_before = '';
		var save_html = document.getElementById('bo_vc_w').innerHTML;

		var nogood_comment_id = ''
		function nogood_popup(comment_id)
		{
			nogood_comment_id = comment_id;
			$('#declaration_popup').css('display','');
		}
		function nogood_write()
		{
			if($("input[name='bg_comment']:checked").length == 0){
				alert("신고 사유를 선택 해 주세요.");
				return;
			}
			var bg_comment = $("input[name='bg_comment']:checked").val();			
			var href = './good.php?bo_table=<?=$bo_table?>&wr_id='+nogood_comment_id+'&good=nogood&comment=1&bg_comment='+bg_comment+'&<?=$qstr?>';

			//alert(href);
			
			$.post(
				href,
				{ js: "on" },
				function(data) {
					//alert(data);
					
					if(data.error) {
						alert(data.error);
						return false;
					}
					alert("이 글을 신고 하셨습니다.");
					$('#nogood'+nogood_comment_id).css('display','none');
					$('#declaration_popup').css('display','none');
					$("input[name='bg_comment']").removeAttr("checked");
					nogood_comment_id = '';
					
				}, "json"
				);
		}

		
		function good_and_write()
		{
			var f = document.fviewcomment;
			if (fviewcomment_submit(f)) {
				f.is_good.value = 1;
				f.submit();
			} else {
				f.is_good.value = 0;
			}
		}

		function fviewcomment_submit(f)
		{
		var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자

		f.is_good.value = 0;

		/*
		var s;
		if (s = word_filter_check(document.getElementById('wr_content').value))
		{
			alert("내용에 금지단어('"+s+"')가 포함되어있습니다");
			document.getElementById('wr_content').focus();
			return false;
		}
		*/

		var subject = "";
		var content = "";
		$.ajax({
			url: g5_bbs_url+"/ajax.filter.php",
			type: "POST",
			data: {
				"subject": "",
				"content": f.wr_content.value
			},
			dataType: "json",
			async: false,
			cache: false,
			success: function(data, textStatus) {
				subject = data.subject;
				content = data.content;
			}
		});

		if (content) {
			alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
			f.wr_content.focus();
			return false;
		}

		// 양쪽 공백 없애기
		var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
		document.getElementById('wr_content').value = document.getElementById('wr_content').value.replace(pattern, "");
		if (char_min > 0 || char_max > 0)
		{
			check_byte('wr_content', 'char_count');
			var cnt = parseInt(document.getElementById('char_count').innerHTML);
			if (char_min > 0 && char_min > cnt)
			{
				alert("댓글은 "+char_min+"글자 이상 쓰셔야 합니다.");
				return false;
			} else if (char_max > 0 && char_max < cnt)
			{
				alert("댓글은 "+char_max+"글자 이하로 쓰셔야 합니다.");
				return false;
			}
		}
		else if (!document.getElementById('wr_content').value)
		{
			alert("댓글을 입력하여 주십시오.");
			return false;
		}

		if (typeof(f.wr_name) != 'undefined')
		{
			f.wr_name.value = f.wr_name.value.replace(pattern, "");
			if (f.wr_name.value == '')
			{
				alert('이름이 입력되지 않았습니다.');
				f.wr_name.focus();
				return false;
			}
		}

		if (typeof(f.wr_password) != 'undefined')
		{
			f.wr_password.value = f.wr_password.value.replace(pattern, "");
			if (f.wr_password.value == '')
			{
				alert('비밀번호가 입력되지 않았습니다.');
				f.wr_password.focus();
				return false;
			}
		}


		set_comment_token(f);

		document.getElementById("btn_submit").disabled = "disabled";

		return true;
	}

	function comment_box(comment_id, work)
	{
		var el_id,
		form_el = 'bo_vc_w',
		respond = document.getElementById(form_el);

		// 댓글 아이디가 넘어오면 답변, 수정
		if (comment_id)
		{
			if (work == 'c')
				el_id = 'reply_' + comment_id;
			else
				el_id = 'edit_' + comment_id;
		}
		else
			el_id = 'bo_vc_w';

		if (save_before != el_id)
		{
			if (save_before)
			{
				document.getElementById(save_before).style.display = 'none';
			}
			if(document.getElementById(el_id) == null){
				el_id = 'c_'+comment_id;

				reply_div = document.createElement('div');
				reply_div.id = 'reply_' + comment_id;
				reply_div.className='reply_wrap';
				document.getElementById(el_id).appendChild(reply_div);
				el_id = 'reply_' + comment_id;
			}
			document.getElementById(el_id).style.display = '';
			document.getElementById(el_id).appendChild(respond);
			//입력값 초기화
			document.getElementById('wr_content').value = '';

			document.getElementById('comment_id').value = comment_id;
			document.getElementById('w').value = work;

			if(save_before)
				$("#captcha_reload").trigger("click");

			save_before = el_id;
		}
	}

	function comment_delete()
	{
		return confirm("이 댓글을 삭제하시겠습니까?");
	}

	//comment_box('', 'c'); // 댓글 입력폼이 보이도록 처리하기위해서 추가 (root님)

	<? if($board['bo_use_sns'] && ($config['cf_facebook_appid'] || $config['cf_twitter_key'])) { ?>
		$(function() {
	// sns 등록
	$("#bo_vc_send_sns").load(
		"<?=G5_SNS_URL; ?>/view_comment_write.sns.skin.php?bo_table=<?=$bo_table; ?>",
		function() {
			save_html = document.getElementById('bo_vc_w').innerHTML;
		}
		);



});
	<? } ?>

	$(function() {
		//댓글열기

		$(".cmt_btn").click(function(){
			$(this).toggleClass("cmt_btn_op");
			$("#bo_vc").toggle();
		});

		$(".pick").click(function() {
			var href = $(this).attr('href');
			$pick = $(this);

			$.post(
				href,
				{ js: "on" },
				function(data) {
					if(data.error) {
						alert(data.error);
						return false;
					}
					if(data.flag) {
						if(data.flag == 'ON'){
							$pick.removeClass('on').addClass('on');
						}else {
							$pick.removeClass('on');
						}
					}
					if(data.count) {
						$pick.text('');
						$pick.append('<span class="blind">찜</span>'+data.count);
					}
				}, "json"
				);
		});

	});
</script>
