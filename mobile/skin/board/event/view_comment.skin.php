<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
if($comment_order == ''){
    $comment_order = "wr_datetime, ";
}
?>

<script>
// 글자수 제한
var char_min = parseInt(<?php echo $comment_min ?>); // 최소
var char_max = parseInt(<?php echo $comment_max ?>); // 최대
</script>
<div class="grid">
	<?php 
        if($w == '')
            $w = 'c';
    ?>
	<h3 class="blind">댓글 등록</h3>
	<div id="bo_vc_w">
    	<form name="fviewcomment" id="fviewcomment" action="<?php echo $comment_action_url; ?>" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off" class="bo_vc_w">
    	<input type="hidden" name="w" value="<?php echo $w ?>" id="w">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
        <input type="hidden" name="comment_id" value="<?php echo $c_id ?>" id="comment_id">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="is_good" value="">
    	
    	<div class="inp_ele r_btn comment_write" <?php if(!$is_member){ echo 'hidden';}?>>
    		<div class="input">
    			<label for="comment_write" class="blind">댓글</label>
    			
                    
    				<input type="text" id="wr_content" name="wr_content" placeholder="댓글을 입력해주세요." value="">
    			
    		</div>
    		<button type="button" class="btn green" id = "btn_submit"onClick="javascript:$('#fviewcomment').submit()">등록</button>
    		
    	</div>
    	
    	</form>
	</div>
    
	<div class="tab_cont_wrap">
		<span class="comment_total">댓글<?php echo count($list);?></span>
        <div class="tab">
            <ul class="type2 tab_btn">
                <li <?php if($comment_order == "wr_datetime, ") {?>class="on"<?php }?> ><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=event&wr_id=<?php echo $wr_id?>&comment_order=wr_datetime"><span>등록순</span></a></li>
                <li <?php if($comment_order == "wr_good desc, ") {?>class="on"<?php }?> ><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=event&wr_id=<?php echo $wr_id?>&comment_order=wr_good desc"><span>추천순</span></a></li>
            </ul>
        </div>
		<div class="tab_cont" >
			<div class="comment_wrap tab_inner">
				<ul class = "comment_list">
        		<?php
                    for ($i=0; $i<count($list); $i++) {
                        $comment_id = $list[$i]['wr_id'];
                        $cmt_depth = ""; // 댓글단계
                        $cmt_depth = strlen($list[$i]['wr_comment_reply']) * 20;
                        $str = $list[$i]['content'];
                        if (strstr($list[$i]['wr_option'], "secret"))
                            $str = $str;
                        $str = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp|mms)\:\/\/([^[:space:]]+)\.(mp3|wma|wmv|asf|asx|mpg|mpeg)\".*\<\/a\>\]/i", "<script>doc_write(obj_movie('$1://$2.$3'));</script>", $str);
                        if (!$cmt_depth) { 
                            $top_id = $comment_id;
                        ?>
        		
        					<li id="c_<?php echo $comment_id ?>">
        						<div class="comment_box">
        							<div class="user_bar">
        								<span class="photo"><?php echo get_member_profile_img($list[$i]['mb_id'], 40, 40); ?></span>
        								<span class="name"><?php echo $list[$i]['name'] ?></span>
        							</div>
        							<div class="text_area">
        								<?php if (strstr($list[$i]['wr_option'], "secret")) echo "<img src=\"".$board_skin_url."/img/icon_secret.gif\" alt=\"비밀글\">"; ?>
                        				<?php echo $str ?>
        							</div>
        							<div class="btn_comm">                                
                                        <span class="date"><?php echo $list[$i]['datetime'] ?></span>
                                        <button type="button" onclick="onPopup();" >신고</button>
                                        <?php if ($list[$i]['is_del'])  { ?><a href="<?php echo $list[$i]['del_link']; ?>" onclick="return comment_delete();"><button type="button">삭제</button></a><?php } ?>
                                        <div class="btn_comm_row">
                                        	<?php if($list[$i]['is_reply'] || $list[$i]['is_edit'] || $list[$i]['is_del']) {
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
                                            ?>
                                                <?php if ($list[$i]['is_reply']) { ?><a href="<?php echo $c_reply_href; ?>" onclick="comment_box('<?php echo $comment_id ?>', 'c'); return false;"><button type="button" class="btn small">답글</button></a><?php } ?>
                                                
                                            <?php } ?>
                                            <!-- 찜 눌르면 class="on" 추가 -->
                                            <?php 
                							$sql2 = " select bg_flag from {$g5['board_good_table']}
                                                    where bo_table = '{$bo_table}'
                                                    and wr_id = '{$list[$i]['wr_id']}'
                                                    and mb_id = '{$member['mb_id']}'
                                                    and bg_flag in ('good', 'nogood') ";
                							$pickYN2 = sql_fetch($sql2);
                							?>
                                            <button type="button" class="pick ico <?php if ($pickYN2['bg_flag']) echo 'on';?>" id = "<?php echo $list[$i]['wr_id']?>" name="btn_pick2" href="./good.php?bo_table=event&wr_id=<?php echo $list[$i]['wr_id']?>&good=good&comment=1"><span class="blind">찜</span><?php echo $list[$i]['wr_good']?></button>
                                            
                                        </div>
                                    </div>
                                    
            						
        						</div>
        					</li>
            			<?php }else{ ?>
            		    	<li id="c_<?php echo $comment_id ?>">
            		    		<div class="reply_wrap" id="reply_<?php echo $top_id ?>" ">
            		    		
                                    <ul class="reply_list">
                                        <li>
                                            <div class="reply_box">
                                                <div class="user_bar">
                                                    <span class="name"><?php echo $list[$i]['name'] ?></span>
                                                </div>
                                                <div class="text_area">
                                                    <?php if (strstr($list[$i]['wr_option'], "secret")) echo "<img src=\"".$board_skin_url."/img/icon_secret.gif\" alt=\"비밀글\">"; ?>
                        							<?php echo $str ?>
                                                </div>
                                                <div class="btn_comm">
                                                    <span class="date line"><?php echo $list[$i]['datetime'] ?></span>
                                                    <button type="button" onclick="onPopup();">신고</button>
                                                    <?php if ($list[$i]['is_del'])  { ?><a href="<?php echo $list[$i]['del_link']; ?>" onclick="return comment_delete();"><button type="button">삭제</button></a><?php } ?>
                                                    <div class="btn_comm_row">
                                                    	<?php if($list[$i]['is_reply'] || $list[$i]['is_edit'] || $list[$i]['is_del']) {
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
                                                		
                                                		<?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
            		    	</li>
            		    <?php }?>
                    <?php }?>
				</ul>
			</div>
		</div>
	</div>
	<?php if($total_count > $limit_count){?>
	<div class="btn_group"><a href="<?php echo G5_BBS_URL?>/board.php?bo_table=event&wr_id=<?php echo $wr_id?>&limit_count=<?php echo $limit_count+$board['bo_reply_rows'];?>" class="btn big border"><span>더보기</span></a></div>
	<?php }?>
</div>

    <script>

    function onPopup(){
        window.open("<?php echo G5_MOBILE_URL; ?>/common/declaration.php");
    }

    var save_before = '';
    var save_html = document.getElementById('bo_vc_w').innerHTML;

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
        form_el = 'fviewcomment',
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

            // 댓글 수정
            if (work == 'cu')
            {
                document.getElementById('wr_content').value = document.getElementById('save_comment_' + comment_id).value;
                if (typeof char_count != 'undefined')
                    check_byte('wr_content', 'char_count');
                if (document.getElementById('secret_comment_'+comment_id).value)
                    document.getElementById('wr_secret').checked = true;
                else
                    document.getElementById('wr_secret').checked = false;
            }

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

    comment_box('', 'c'); // 댓글 입력폼이 보이도록 처리하기위해서 추가 (root님)

    <?php if($board['bo_use_sns'] && ($config['cf_facebook_appid'] || $config['cf_twitter_key'])) { ?>
    $(function() {
    // sns 등록
        $("#bo_vc_send_sns").load(
            "<?php echo G5_SNS_URL; ?>/view_comment_write.sns.skin.php?bo_table=<?php echo $bo_table; ?>",
            function() {
                save_html = document.getElementById('bo_vc_w').innerHTML;
            }
        );


           
    });
    <?php } ?>

    $(function() {            
        //댓글열기
       
        $(".cmt_btn").click(function(){
            $(this).toggleClass("cmt_btn_op");
            $("#bo_vc").toggle();
        });
        $("button[name=btn_pick2]").click(function() {
    		var href = $(this).attr('href');
    		var id = $(this).attr('id');
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
    							$('#'+id).removeClass('on').addClass('on');
    						}else {
    							$('#'+id).removeClass('on');
    						}
    					}
    		            if(data.count) {
    		            	$('#'+id).text('');
    		            	$('#'+id).append('<span class="blind">찜</span> '+data.count);
    		            }
    		        }, "json"
    		    );
    	 });
    });
    </script>
    
