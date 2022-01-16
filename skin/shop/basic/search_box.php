

<div class="grid">
    <div class="search_bar">
    	<input type="text" class="search_anchor" title="검색어입력" id="search_text" name="search_text" onkeyup = "$.search('recommend','');"/>
		<button type="button" class="sch_btn" onclick="$.search('search','')"><span class="blind">검색</span></button>
    	<a href="#" class="btn_del" onclick="$('#search_text').val('');$.clear();"><span class="blind">닫기</span></a>
    </div>
    <!-- //검색 -->
    <!-- 검색어 목록 -->
    <div class="search_list">
    	<div class="list">
    		<ul class="type1 none" id="search_list">
    		</ul>
    	</div>
    </div>
</div>
