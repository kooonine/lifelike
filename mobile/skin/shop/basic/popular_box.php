<div class="tab_cont_wrap type2" id="popular_box" >
	<div class="tab">
		<ul class="type4 onoff tab_btn">
			<li class="on"><a href="#"><span>인기</span></a></li>
			<li class=""><a href="#"><span>최근</span></a></li>
		</ul>
	</div>
	<div class="tab_cont">
		<!-- tab1 -->
		<div class="tab_inner">
			<div class="list">
				<ul class="type1">
					<?php echo popular();?>
				</ul>
			</div>
		</div>
		<!-- tab2 -->
		<div class="tab_inner">
			<div class="list">
				<ul class="type3 btn_r" id="history_box">
					
				</ul>
			</div>
		</div>
	</div>
</div>
<script>
var cookieList = function(cookieName){
	 var cookie = $.cookie(cookieName);
	 var items = cookie ? cookie.split(/,/) : new Array();
	 return {
	  "add" : function(val){
		  if(!cookie.contains(val)){
    	   items.push(val);
    	   $.cookie(cookieName,items.join(','));
		  }
	  },
	  "clear" : function(){
	   items = null;
	   $.cookie(cookieName, null);
	  },
	  "items" : function(){
	   return items; 
	  }
	 }
	}


$(document).ready(function(){
	
	$.search_history_create = function (){
		
		var list = new cookieList("cookieList").items();
		var html = "";
		for(var i = 0;i<list.length;i++){
			if(list[i] == 'null' ) break;
			html += '<li>';
			html += '	<a href="#" onclick="$.search(\'search\', \''+list[i]+'\')">';
			html += '		<span class="text">'+list[i]+'</span>';
			html += '	</a>';
			html += '	<a href="#" class="btn_delete" onclick="$.search_history_delete(\''+list[i]+'\')"><span class="blind">삭제</span></a>';
			html += '</li>';
		}
		$('#history_box').html(html);
		
	};

	$.search_history_delete = function (cookieName){
		var list = new cookieList("cookieList").items();
		cookieList("cookieList").clear();
		for(var i = 0;i<list.length;i++){
			if(list[i] != cookieName)
				cookieList("cookieList").add(cookieName);
		}
		$.search_history_create();
		
	};

	$.search_history_create();
});

</script>