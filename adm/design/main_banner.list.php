<?php
//$sub_menu = '100310';
$sub_menu = '10';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '메인페이지 배너관리 / ' . $cp_category;
include_once(G5_ADMIN_PATH . '/admin.head.php');

$sql_common = " from lt_banner_new ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common . "where cp_category =  '$cp_category' " ;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// $sql = "select * ". $sql_common . "where cp_category =  '$cp_category'  order by cp_id asc ";
$sql = "select * ". $sql_common . "where cp_category =  '$cp_category'  order by ba_sequence ASC, cp_id asc ";

$result = sql_query($sql);
$result1 = sql_query($sql);
$result2 = sql_query($sql);
$result3 = sql_query($sql);
$result4 = sql_query($sql);
$result5 = sql_query($sql);
$result6 = sql_query($sql);
$result7 = sql_query($sql);
$data = sql_fetch($sql);

$sql = "select * from lt_shop_category where main_yn ='Y' order by ca_id asc ";
$title_cat = sql_query($sql);
$cate_data = sql_fetch($sql);
?>



<!-- @START@ 내용부분 시작 -->


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span> 등록 된 배너<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>


            <div class="x_content">
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                        <div class="local_ov01 local_ov"><span class="btn_ov01"><span class="ov_txt">전체 </span><span class="ov_num"> <?php echo $total_count; ?>건</span></span></div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                        <a href="./main_banner.php" class="btn btn-success">목록</a>
                        <a onclick="deleteCp_all()" class="btn btn-danger">선택 삭제</a>
                        <!-- <a onclick="reset()" class="btn btn-danger">초기화</a> -->
                    </div>
                </div>



                
                
                <div class="tbl_head01 tbl_wrap">
                    
                    <div class="banner_div">
                        <div class="aera-list">
                            <?php if ($cp_category == 'GNB_IN') : ?>
                            <div>이불</div>
                            <!-- GNB 내부 카테고리별 상품 등록 -->
                            <ul id="sortable" class="banner_ul">
                                <?php
                                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                        $bg = 'bg' . ($i % 2);
                                        $status = $row['ba_use'] ? "사용" : "미사용";
                                    ?>
                                        
                                    <?php 
                                    if($row['ca_name'] == '이블' ): ?>
                                    <li id="<?= $row['cp_id']?>">
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=이블">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ?>
                                <?php
                                }
                                if ($i == 0  ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }

                                ?>
                            </ul>
                        
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a onclick="seqSave('<?= $cp_category?>',null)" class="btn btn-success">순서저장</a>
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=이블" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                        <div class="aera-list">
                            <div>베개/패드/토퍼</div>
                            <!-- GNB 내부 카테고리별 상품 등록 -->
                            <ul id="sortable1" class="banner_ul">
                                <?php
                                    for ($i = 0; $row = sql_fetch_array($result1); $i++) {
                                        $bg = 'bg' . ($i % 2);
                                        $status = $row['ba_use'] ? "사용" : "미사용";
                                    ?>
                                        
                                    <?php 
                                    
                                    if($row['ca_name'] == '베개/패드/토퍼' ): ?>
                                    <li id="<?= $row['cp_id']?>">
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=베개/패드/토퍼">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ?>
                                <?php
                                }
                                if ($i == 0  ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                            
                                ?>
                            </ul>
                        
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a onclick="seqSave('<?= $cp_category?>',1)" class="btn btn-success">순서저장</a>
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=베개/패드/토퍼" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                        <div class="aera-list">
                            <div>침구커버</div>
                            <!-- GNB 내부 카테고리별 상품 등록 -->
                            <ul id="sortable3" class="banner_ul">
                                <?php
                                    for ($i = 0; $row = sql_fetch_array($result2); $i++) {
                                        $bg = 'bg' . ($i % 2);
                                        $status = $row['ba_use'] ? "사용" : "미사용";
                                    ?>
                                        
                                    <?php 
                                    if($row['ca_name'] == '침구커버' ): ?>
                                    <li id="<?= $row['cp_id']?>">
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=침구커버">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ?>
                                <?php
                                }
                                if ($i == 0  ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                            
                                ?>
                            </ul>
                        
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a onclick="seqSave('<?= $cp_category?>',3)" class="btn btn-success">순서저장</a>
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=침구커버" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                        <div class="aera-list">
                            <div>홈데코</div>
                            <!-- GNB 내부 카테고리별 상품 등록 -->
                            <ul id="sortable4" class="banner_ul">
                                <?php
                                    for ($i = 0; $row = sql_fetch_array($result3); $i++) {
                                        $bg = 'bg' . ($i % 2);
                                        $status = $row['ba_use'] ? "사용" : "미사용";
                                    ?>
                                    <?php if($row['ca_name'] == '홈데코' ): ?>
                                    <li id="<?= $row['cp_id']?>">
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=홈데코">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                            
                                ?>
                            </ul>
                        
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a onclick="seqSave('<?= $cp_category?>',4)" class="btn btn-success">순서저장</a>
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=홈데코" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                        <div class="aera-list">
                        <?php   elseif( $cp_category == 'MD' || $cp_category == 'BRAND' || $cp_category == 'SEASON' || $cp_category == 'GOOS'|| $cp_category == 'EVENT') : ?> 
                            <div>1단</div>
                            <!-- brand 내부 카테고리별 상품 등록 -->
                            <ul id="sortable1" class="banner_ul">
                                <?php
                                    for ($i = 0; $row = sql_fetch_array($result4); $i++) {
                                        $bg = 'bg' . ($i % 2);
                                        $status = $row['ba_use'] ? "사용" : "미사용";
                                    ?>
                                    <?php if($row['ca_name'] == '1단' ): ?>
                                    <li id="<?= $row['cp_id']?>">
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=1단">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                            
                                ?>
                            </ul>
                        
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a onclick="seqSave('<?= $cp_category?>',1)" class="btn btn-success">순서저장</a>
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=1단" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                        <div class="aera-list">    
                            <div>2단</div>
                            <!-- GNB 내부 카테고리별 상품 등록 -->
                            <ul id="sortable2" class="banner_ul">
                                <?php
                                    for ($i = 0; $row = sql_fetch_array($result5); $i++) {
                                        $bg = 'bg' . ($i % 2);
                                        $status = $row['ba_use'] ? "사용" : "미사용";
                                    ?>
                                    <?php if($row['ca_name'] == '2단' ): ?>
                                    <li id="<?= $row['cp_id']?>">
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=2단">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                            
                                ?>
                            </ul>
                        
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a onclick="seqSave('<?= $cp_category?>',2)" class="btn btn-success">순서저장</a>
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=2단" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                        <div class="aera-list">
                            <div>3단</div>
                            <!-- GNB 내부 카테고리별 상품 등록 -->
                            <ul id="sortable3" class="banner_ul">
                                <?php
                                    for ($i = 0; $row = sql_fetch_array($result6); $i++) {
                                        $bg = 'bg' . ($i % 2);
                                        $status = $row['ba_use'] ? "사용" : "미사용";
                                    ?>
                                    <?php 
                                    if($row['ca_name'] == '3단' ): ?>
                                    <li>
                                        <input type="checkbox" name="cbBanner" value="" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=3단">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ?>
                                <?php
                                }
                                if ($i == 0 ) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                            
                                ?>
                            </ul>
                        
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a onclick="seqSave('<?= $cp_category?>',3)" class="btn btn-success">순서저장</a>
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=3단" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                            <?php   if( $cp_category == 'BRAND') : ?> 
                            <div class="aera-list">
                                <div>4단</div>
                                <!-- GNB 내부 카테고리별 상품 등록 -->
                                <ul id="sortable4" class="banner_ul">
                                    <?php
                                        for ($i = 0; $row = sql_fetch_array($result7); $i++) {
                                            $bg = 'bg' . ($i % 2);
                                            $status = $row['ba_use'] ? "사용" : "미사용";
                                        ?>
                                        <?php 
                                        if($row['ca_name'] == '4단' ): ?>
                                        <li>
                                            <input type="checkbox" name="cbBanner" value="" class="cb" />
                                            <div class="mover"  style = "float : right;">
                                                <div><strong>MOVE</strong></div>
                                            </div>
                                            <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                            
                                            <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                            </div>
                                                
                                            <div class="caption">
                                                <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                                <div>
                                                    <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>&amp;cate_name=4단">수정</a></button></span>
                                                    
                                                    <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                    <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                                </div>
                                            </div>
                                        </li>
                                        <?php endif ?>
                                    <?php
                                    }
                                    if ($i == 0 ) {
                                        echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                    }
                                
                                    ?>
                                </ul>
                            
                                <div class="clearfix"></div><br />
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                        <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                        <a onclick="seqSave('<?= $cp_category?>',4)" class="btn btn-success">순서저장</a>
                                        <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>&amp;cate_name=4단" class="btn btn-success">배너등록</a>
                                    </div>
                                </div>
                            </div>
                            <?endif?>
                        <div class="aera-list">
                            <?php   else : ?>
                                <ul id="sortable" class="banner_ul">
                                <?php
                                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                    $bg = 'bg' . ($i % 2);
                                    $status = $row['ba_use'] ? "사용" : "미사용";
                                ?>
                                    
                                    <li id="<?= $row['cp_id']?>">
                                        <input type="checkbox" name="cbBanner" value="<?=$row['cp_id']?>" class="cb" />
                                        <div class="mover"  style = "float : right;">
                                            <div><strong>MOVE</strong></div>
                                        </div>
                                        <!-- 기간설정이 지난 배너는 기간종료 기간에 들어가지못한 배너는 대기로 표시 -->
                                        
                                        <div class="contents_priview" style = "background : url('<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>') no-repeat center">
                                        </div>
                                            
                                        <div class="caption">
                                            <p class="ellipsis"  style="width:60%;"><?php echo $row['cp_subject']; ?></p>
                                            <div>
                                                <span class=""><button type="button"><a href="./main_banner.update.form.php?w=u&amp;cp_id=<?php echo $row['cp_id']; ?>&amp;cp_category=<?php echo $cp_category ?>">수정</a></button></span>
                                                
                                                <span class=""><button type="button" onclick=preview_Img("<?= G5_DATA_URL . '/banner/' . $row['cp_image_1'] ?>")>원본</button></span>
                                                <span><button class="btn-danger" type="button" onclick="deleteCp()">삭제</button></span>
                                            </div>
                                        </div>
                                    </li>
                                <?php
                                }
                                if ($i == 0) {
                                    echo '<tr><td colspan="11" class="empty_table">등록된 내용이 없습니다</td></tr>';
                                }
                            
                                ?>
                            </ul>
                        
                            <div class="clearfix"></div><br />
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-right pull-right">
                                    <!-- <a  id = "insertBanner" onclick=insertBanner() class="btn btn-success">배너등록</a> -->
                                    <a onclick="seqSave('<?= $cp_category?>',null)" class="btn btn-success">순서저장</a>
                                    <!-- <a href="./main_banner.list.php?cp_category=<?php echo $cp_category ?>&amp;seq=save" class="btn btn-success">순서저장</a> -->
                                    <a href="./main_banner.update.form.php?cp_category=<?php echo $cp_category ?>" class="btn btn-success">배너등록</a>
                                </div>
                            </div>
                        </div>
                        
                        <?php endif ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal123 fade"  tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>


<script>

    var select_cp_id = 0;

    $(function() {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        $("#sortable1").sortable();
        $("#sortable1").disableSelection();
        $("#sortable2").sortable();
        $("#sortable2").disableSelection();
        $("#sortable3").sortable();
        $("#sortable3").disableSelection();
        $("#sortable4").sortable();
        $("#sortable4").disableSelection();

        
    });
    function seqSave(e,num){
        if (!num) num ='';
        
        var seqArr =  $(`#sortable${num}`).sortable('toArray');
        resArr = [];
        for(var i in seqArr) { 
            cpId = seqArr[i];
            resArr.push({i,cpId })
        }

        $.ajax({
            url: "./main_banner.ajax.php",
            type: "POST",
            data: {
                contents:resArr
            }, 
            // dataType: "json",
            success: function(data) {
                alert('저장완료');
            }
        });
    }

    function deleteCp_all(){
        alert("다음기회의 하나씩");
    }

    function deleteCp(){
        var select_cp_id = $("input:checkbox[name=cbBanner]:checked").val();
        if(!select_cp_id){
            alert("배너를 선택해주세요.");
            return;
        }
        var result = confirm("선택한 배너를 삭제하기겠습니까?");
        if(result){
            alert("삭제되었습니다.");
            location.href =  "./main_banner.update.php?w=d&cp_id="+select_cp_id+"&cp_category=<?php echo $cp_category ?>";
        }else{
            
        }
        
    }
    function reset(){
        $("input:checkbox[name=cbBanner]").each(function () {
            $(this).attr('checked', false);
        });
    }

    function preview_Img(imgPath){
        $("#imgPath").attr('src' , imgPath);
        $("#imgStr").html(imgPath);

        $("#modal_preview_img").modal('show');
    }

</script>


<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>