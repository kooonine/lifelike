<?php
$sub_menu = '95';
include_once('./_common.php');

auth_check($auth[substr($sub_menu,0,2)], "r");

$g5['title'] = '제휴몰 상세 HTML 제작';
include_once(G5_ADMIN_PATH . '/admin.head.php');



if(!empty($goods_lists)){

    preg_match_all("/[^() ||  \/\,]+/", preg_replace('/\r\n/',",",$goods_lists),$list);
    $in_list = empty($list[0])?'NULL':"'".join("','", $list[0])."'";
    
    $sql = "select * from lt_prod_info where pi_model_no in ({$in_list}) group by pi_model_no ORDER BY FIELD(pi_model_no , {$in_list} )";
    $sql_res = sql_query($sql);
    

    $sql_cnt = "select count(prod.pi_model_no) as CNT from  (select pi_model_no from  lt_prod_info where pi_model_no in ({$in_list})  group by pi_model_no ) AS prod ";
    $res_cnt = sql_fetch($sql_cnt);

    $list_cnt = $res_cnt['CNT'] * 1 ;

    $line = round($list_cnt / 2);
    
    
    $item_list = array();

    $templet = array();
    for($z = 1 ; $data_list = sql_fetch_array($sql_res); $z++ ){
        $item_list[$z] = $data_list;
        //상세페이지 생성 로직
        $pi_images = array();
        if (!empty($data_list['pi_img'])) {
            $pi_images = json_decode($data_list['pi_img'], true);
        }

        $GOODS_REMARKS = "<html>/n<div align="."center"." style="."display: grid;"."> /n";

        foreach ($pi_images as $pii => $pi_image){
            if(!empty($pi_image['img'])){
                $url = str_replace(" ","",$pi_image['img']);
                if(file($url)){
                    if($data_list['pi_brand'] == '템퍼'){
                        if(strpos($pi_image['img'] , "main_1") === false ){
                            $GOODS_REMARKS .= "<img src=".$pi_image['img']."> /n";
                        }else{
                            $GOODS_REMARKS .= '<video controls width="860"><source src="https://lifelikecdn.co.kr/video/tempur/HOME BY TEMPUR_final.mp4" type="video/mp4"></video> /n';
                            $GOODS_REMARKS .= "<img src=".$pi_image['img']."> /n";
                        }
                    }else{
                        $GOODS_REMARKS .= "<img src=".$pi_image['img']."> /n";
                    }
                }else{
                    
                }
            }
        }
        $GOODS_REMARKS .= "</div> /n </html>";

        $templet[$z] = $GOODS_REMARKS;

        $myfile = fopen("newfile_".$z.".html", "w") or die("Unable to open file!");
        $txt = $templet[$z];
        fwrite('/xml_sabang/'.$myfile, $txt);
        
        fclose($myfile);
    }

    



}




?>

<style>
#html_pop_area {display : block;}
#html_souce_area {display : none;}
</style>

<!-- @START@ 내용부분 시작 -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">

            <div class="x_title">
                <h4><span class="fa fa-check-square"></span>제휴몰 상세 HTML 제작<small></small></h4>
                <label class="nav navbar-right"></label>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" style="display : flex;">
                <form>
                    <div style="margin-bottom : 10px;">
                        상품코드<textarea style="min-height : 350px;" type = "text" name = "goods_lists" value = "<?=$goods_lists?>"> <?=$goods_lists?></textarea>
                    </div>
                    <div style="margin-bottom : 10px;">
                        가로폭<br><input type = "text" name = "it_width" value = "<?=$it_width?>">
                    </div>
                    <input type = "submit" value = "제작" > 
                    <br>
                    <br>
                    <input type = "button" onclick="source_view()" value = "소스보기" > <br>
                    <br>
                    <input type = "button" onclick="html_pop()" value = "html 추출" > 
                </form>
                
                <div id ="html_pop_area" class="view"  style="width : 100%;">
                    <table  width="100%" border="1" bordercolor="#cccccc" cellspacing="0" cellpadding="0">
                        <tbody>
                        <?for ($i = 0 ; $i < $line; $i++) : ?>
                        <?
                            $row1 = ($i * 2) +1;
                            $row2 = ($i * 2) +2;
                            if($item_list[$row1]['pi_barnd'] == '탬퍼' || $item_list[$row2]['pi_barnd'] == '탬퍼'){
                                $file_path = 'tempur';
                            }else if ($item_list[$row1]['pi_barnd'] == '쉐르단' || $item_list[$row2]['pi_barnd'] == '쉐르단'){
                                $file_path = 'sheridan';
                            }else {
                                $file_path = 'sofraum';
                            }
                        ?>
                            <tr>
                                <td align="center">
                                    <a href="http://lifelikecdn.co.kr/promotion/<?=$item_list[$row1]['pi_model_no']?>.html" target="_blank">	  
                                    <img src="http://lifelikecdn.co.kr/sabang/<?=$file_path?>/<?=$item_list[$row1]['pi_model_no']?>_THUM_1.jpg" width="<?=$it_width?>" alt="<?=$item_list[$row1]['pi_it_sub_name']?>">
                                    </a><br>
                                    <strong><?=$item_list[$row1]['pi_it_sub_name']?></strong><br><?=$item_list[$row2]['pi_item_soje']?><br><br></td>
                                </td>
                                <td align="center">
                                    <a href="http://lifelikecdn.co.kr/promotion/<?=$item_list[$row2]['pi_model_no']?>.html" target="_blank">	  
                                    <img src="http://lifelikecdn.co.kr/sabang/<?=$file_path?>/<?=$item_list[$row2]['pi_model_no']?>_THUM_1.jpg" width="<?=$it_width?>" alt="<?=$item_list[$row2]['pi_it_sub_name']?>">
                                    </a><br>
                                    <strong><?=$item_list[$row2]['pi_it_sub_name']?></strong><br><?=$item_list[$row2]['pi_item_soje']?><br><br></td>
                                </td>
                            </tr>
                        <?endfor?>
                        </tbody>
                    </table>
                </div>
                
                <xmp id ="html_souce_area">

                </xmp>
            </div>
        </div>
    </div>
</div>
<script>

    function html_pop(){
        
        var fileName = "상품 옵션리스트 HTML.html";
        var content = $("#html_pop_area").html();
        
        var blob = new Blob([content], { type: 'text/plain' });
        objURL = window.URL.createObjectURL(blob);
                
        // 이전에 생성된 메모리 해제
        if (window.__Xr_objURL_forCreatingFile__) {
            window.URL.revokeObjectURL(window.__Xr_objURL_forCreatingFile__);
        }
        window.__Xr_objURL_forCreatingFile__ = objURL;
        var a = document.createElement('a');
        a.download = fileName;
        a.href = objURL;
        a.click();

        
    }
    function source_view(){

        if($("#html_pop_area").hasClass('view')){
            $("#html_pop_area").css("display","none");
            $("#html_souce_area").css("display","block");
            $("#html_souce_area").html($("#html_pop_area").html());
            $("#html_pop_area").removeClass('view');
            $("#html_pop_area").addClass('unview');
        }else{
            $("#html_pop_area").removeClass('unview');
            $("#html_pop_area").addClass('view');
            $("#html_pop_area").css("display","block");
            $("#html_souce_area").empty();
            $("#html_souce_area").css("display","none");
        }
        
    }
    
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>