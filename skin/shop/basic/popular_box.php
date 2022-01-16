
<div class="content search_container" id="popular_box">
    <div class="grid">
    	<div class="tab_cont_wrap">
    		<div class="tab">
    			<ul class="type4 onoff tab_btn center">
    				<li class="on"><a href="#"><span>인기</span></a></li>
    				<li class=""><a href="#"><span>최근</span></a></li>
    			</ul>
    		</div>
        	<div class="tab_cont">
        		<!-- tab1 -->
        		<div class="tab_inner">
        			<div class="list">
        				<ul class="type1 none">
        					<?php echo popular();?>
        				</ul>
        			</div>
        		</div>
        		<!-- tab2 -->
        		<div class="tab_inner">
        			<div class="list">
        				<ul class="type3 btn_r" id="history_box">
    						<div class="no_data">
    							<p>검색결과가 존재하지 않습니다.</p>
    						</div>
        				</ul>
        			</div>
        		</div>
        	</div>
        </div>
	</div>
</div>