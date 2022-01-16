<div class="search_bar">
	<input type="text" class="search_anchor" title="검색어입력" id="search_text" name="search_text" onkeyup = "$.search('recommend','');"/>
	<div class="sch">
		<button type="button" class="sch_btn" onclick="$.search('search','')"><span class="blind">검색</span></button>
	</div>
	<a href="#" class="btn_del" onclick="$.clear()"><span class="blind">닫기</span></a>
</div>
<!-- //검색 -->
<!-- 검색어 목록 -->
<div class="search_list" id="search_list">
	
</div>
<script>

$(document).ready(function(){
	$.search = function(type, text){
		if(text){
			$("#search_text").val(text);
		}
		
		
		$.post(
            "<?php echo $ajax_url; ?>",
            { type: type, search_text:encodeURIComponent($("#search_text").val())},
            function(data) {
                if(type == 'search'){
                	$("#search_name").html($("#search_text").val());
                	$("#item_box").html(data.view_text);
                	$('#popular_box').removeAttr('hidden').attr('hidden',true);
            		$('#complete_box').removeAttr('hidden');
            		cookieList("cookieList").add($("#search_text").val());
            		$.search_history_create();
                }else if (type == 'recommend'){
                	$("#search_list").html(data.view_text);
                }
            }
        );
		
	};

	$.clear = function(){
		$("#search_text").val('');
		$("#search_list").html('');
		$('#complete_box').removeAttr('hidden').attr('hidden',true);
		$('#popular_box').removeAttr('hidden');
	};
});

</script>
