
<link href="../../css/a4.print.css" rel="stylesheet">
<script src="../../vendors/jquery/dist/jquery.min.js"></script>
<?
include_once('./_common.php');
    // $jo_id = $_GET['jo_id'];
    $ps_id = $_GET['ps_id'];

    $qty = $_GET['qty'];
    $main_yd = $_GET['main'];
    $codi_yd = $_GET['codi'];
    $codi1_yd = $_GET['codi1'];

    $sql = "SELECT * FROM lt_job_order WHERE ps_id = '{$ps_id}' ORDER BY jo_id ASC limit 1 " ;
    $result = sql_fetch($sql);

    $ps_sql = "SELECT * FROM lt_prod_schedule WHERE ps_id = '{$ps_id}' " ;
    $ps_result = sql_fetch($ps_sql);
    $bal_sql = "SELECT * FROM lt_prod_schedule_balju_print WHERE ps_id = '{$ps_id}' " ;
    $balju_result = sql_fetch($bal_sql);
    
    $jo_mater_info = array();
    $meg_list = array();
    if(!empty($balju_result['pno'])){
        $jo_mater_info = json_decode($balju_result['mater_info'], true);
        $meg_list = json_decode($balju_result['meg_text'], true);
    }else{
        if (!empty($result['jo_mater_info'])) {
            $jo_mater_info = json_decode($result['jo_mater_info'], true);
        }
    }

    $jo_mater_name = array();
    if (!empty($result['jo_mater_name'])) {
        $jo_mater_name = json_decode($result['jo_mater_name'], true);
    }

    $jo_main_img = array();
    if (!empty($result['jo_main_img'])) {
        $jo_main_img = json_decode($result['jo_main_img'], true);
    }
    $jo_codi_img = array();
    if (!empty($result['jo_codi_img'])) {
        $jo_codi_img = json_decode($result['jo_codi_img'], true);
    }
    $jo_sub_img = array();
    if (!empty($result['jo_sub_img'])) {
        $jo_sub_img = json_decode($result['jo_sub_img'], true);
    }

    
    function color_table($text){
        if(preg_match("/[a-zA-Z]/",$text)){
            switch($text){
                case 'AA' : $color_nm = "기타"; break;
                case 'BE' : $color_nm = "베이지"; break;
                case 'BK' : $color_nm = "블랙"; break;
                case 'BL' : $color_nm = "블루"; break;
                case 'BR' : $color_nm = "브라운"; break;
                case 'CR' : $color_nm = "크림"; break;
                case 'DB' : $color_nm = "진블루"; break;
                case 'DP' : $color_nm = "진핑크"; break;
                case 'FC' : $color_nm = "푸시아"; break;
                case 'GD' : $color_nm = "골드"; break;
                case 'GN' : $color_nm = "그린"; break;
                case 'GR' : $color_nm = "그레이"; break;
                case 'IV' : $color_nm = "아이보리"; break;
                case 'KA' : $color_nm = "카키"; break;
                case 'LB' : $color_nm = "연블루"; break;
                case 'LG' : $color_nm = "연그레이"; break;
                case 'LP' : $color_nm = "연핑크"; break;
                case 'LV' : $color_nm = "라벤다"; break;
                case 'MT' : $color_nm = "민트"; break;
                case 'MU' : $color_nm = "멀티"; break;
                case 'MV' : $color_nm = "모브"; break;
                case 'MX' : $color_nm = "혼합"; break;
                case 'NC' : $color_nm = "내츄럴"; break;
                case 'NV' : $color_nm = "네이비"; break;
                case 'OR' : $color_nm = "오렌지"; break;
                case 'PC' : $color_nm = "청록"; break;
                case 'PK' : $color_nm = "핑크"; break;
                case 'PU' : $color_nm = "퍼플"; break;
                case 'RD' : $color_nm = "레드"; break;
                case 'WH' : $color_nm = "화이트"; break;
                case 'YE' : $color_nm = "노랑"; break;
            }
        }else{
            $color_nm = $text;
        }
        return $color_nm;
    }    
?>
<div id="pring3_a4">
    
    <h2>(원단) 발주서</h2>
    
    <form name="fwrite" id="fwrite"  method="post" >
    <input type="hidden" name = "pno" value ="<?=$balju_result['pno']?>">
    <input type="hidden" name = "ps_id" value ="<?=$ps_id?>">
    <input type="hidden" name = "item_name" value ="<?=$ps_result['ps_it_name']?>">
    <input type="hidden" name = "brand" value ="<?=$result['jo_brand']?>">
    <input type="hidden" name = "prod_user" value ="<?=$result['jo_user']?>">
    <input type="hidden" name = "ps_year" value ="<?=substr($result['jo_prod_year'],2,2)?>">
    <input type="hidden" name = "ps_season" value ="<?=$result['jo_season']?>">
    <input type="hidden" name = "ps_job_gubun" value ="<?=$result['jo_gubun']?>">

    <input type="hidden" name = "o_main_yd" value ="<?=$main_yd?>">
    <input type="hidden" name = "o_codi_yd" value ="<?=$codi_yd?>">
    <input type="hidden" name = "o_codi1_yd" value ="<?=$codi1_yd?>">

    <table id="pring3_table">
        <colgroup>
            <col width="13%"/>
            <col width="17%"/>
            <col width="8%"/>
            <col width="7%"/>
            <col width="8%"/>
            <col width="11%"/>
            <col width="10%"/>
            <col width="13%"/>
            <col width="12%"/>
        </colgroup>
        <tbody class="main_body">
        <tr class="h50">
            <th colspan=9 class="txt_left">다음과 같이 발주하오니 조건 엄수 납품하여 주시기 바랍니다.</th>
        </tr>
        <tr>
            <th rowspan=2>거래처</th>
            <td colspan=4 class="txt_center"><?=$jo_mater_name[1]['mater']?></td>
            <th>브랜드</th>
            <th colspan=3 class="txt_center"><?=$result['jo_brand']?></th>
        </tr>
        <tr>
            <?
            // $phone =  $jo_mater_name[1]['tel'];
            // $length = strlen($phone);
            // switch($length){
            //     case 9 :
            //         $tel = preg_replace("/([0-9]{2})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            //         break;
            //     case 10:
            //         $tel = preg_replace("/([0-9]{2})([0-9]{2})([0-9]{4})/", "$1-$2-$3", $phone);
            //         break;
            //     default :
            //         $tel = $phone;
            //         break;
            // }
            $tel = $jo_mater_name[1]['tel'];
            ?>
            <td colspan=4 class="font11 txt_center"><?=$tel?></td>
            <th>발주명</th>
            <?
            $balju_name = substr($result['jo_prod_year'],2,2).$result['jo_season'].$result['jo_gubun'].'_'.$result['jo_it_name'].' 발주';
            ?>
            <th colspan=3 class="txt_center"><?=$balju_name?></th>
        </tr>
        <tr>
            <th>발주일</th>
            <td colspan=4 class="txt_center"><?=date('Y년 m월 d일', strtotime($ps_result['ps_balju']))?></td>
            <input type="hidden" name = "balju_date"  value = "<?=$ps_result['ps_balju']?>">
            <th>발주담당</th>
            <td colspan=3 class="txt_center" id="comf_user">
                <?if(!empty($balju_result['pno'])){
                    switch($balju_result['comform_user']){
                        case "001" :
                            echo '리탠다드 상품소싱팀 유숙현님';
                        break;
                        case "002" :
                            echo '리탠다드 상품소싱팀 강동윤님';
                        break;
                        case "003" :
                            echo '리탠다드 상품소싱팀 염종권님';
                        break;
                        case "004" :
                            echo '리탠다드 상품소싱팀 박동언님';
                        break;
                        case "005" :
                            echo '리탠다드 상품소싱팀 신상철님';
                        break;
                        case "006" :
                            echo '리탠다드 상품소싱팀 정윤경님';
                        break;
                        case "007" :
                            echo '리탠다드 상품소싱팀 김소현님';
                        break;
                        case "008" :
                            echo '리탠다드 상품소싱팀 임태경님';
                        break;
                    }
                }else{
                    echo '리탠다드 상품소싱팀 유숙현님';     
                }
                
                ?>
                
            </td>
        </tr>
        <tr>
            <th>납기일</th>
            <td colspan=4 class="txt_center"><?=date('Y년 m월 d일', strtotime($ps_result['ps_expected_limit_date']))?></td>
            <input type="hidden" name = "balju_limit_date"  value = "<?=$ps_result['ps_expected_limit_date']?>">
            <th>납기장소</th>
            <td colspan=3 class="txt_center">서울특별시 구로구 구로동 1127-35</td>
        </tr>
        <tr>
            <th colspan=2>품명</th>
            <th>컬러</th>
            <th>단위</th>
            <th>규격</th>
            <th>발주량</th>
            <th>단가(￦)</th>
            <th>금액(￦)</th>
            <th>비  고</th>
        </tr>

        <?if (!empty($jo_main_img)) {
            $mater_count  = 1;
        } 
        if (!empty($jo_codi_img)) {
            $mater_count  = 2;
        }
        if (!empty($jo_sub_img)) {
            $mater_count  = 3;  
        }

        ?>
        <?php foreach ($jo_mater_info as $jm => $mater_info) : ?>
        <?if (!empty($mater_info['main']) && $mater_info['main'] == '메인') : 
            // $mater_count++;
            if(!empty($balju_result['pno'])){

                $main_yd = $mater_info['yd'];
                $total_yd += $main_yd  ;
            }else{
                $total_yd += $main_yd  ;
            }
            $total_price += $mater_info['danga'] * ($main_yd );
        ?>
        <tr>
            <td colspan=2 class="font9 txt_center"><?=$result['jo_it_name']?><?=stripslashes($jo_main_img[1]['title'])?></td>
            <th class="font10 txt_center"><?=color_table($result['jo_color'])?></th>
            <th class="font10 txt_center">YD</th>
            <input type="hidden" name="mater_info[<?=$jm?>]">
            <input type="hidden" name="mater_info_soje[<?=$jm?>]" value = "<?=stripslashes($jo_main_img[1]['title'])?>">
            <input type="hidden" name="mater_info_color[<?=$jm?>]" value="<?=color_table($result['jo_color'])?>">
            <input type="hidden" name="mater_info_main[<?=$jm?>]" value="<?=$mater_info['main']?>">
            <input type="hidden" name="mater_info_origin_yd[<?=$jm?>]" value="<?=$_GET['main']?>">
            <input type="hidden" name="mater_info_mater_name[<?=$jm?>]" value="<?$cat1 = explode('_',$jo_mater_name[1]['mater']); echo $cat1[0]; ?>">
            <td class="font11 txt_center"><input name="mater_info_size[<?=$jm?>]" class="font11 txt_center noborder" value="<?=$mater_info['size']?>"></td>
            <td class="font11 txt_right"><input name="mater_info_yd[<?=$jm?>]" class="font11 txt_right noborder balju_yd yd_id_<?=$jm?>" data-balju-idx="<?=$jm?>" onblur="balju_p(this)" value = "<?=number_format(($main_yd ),1)?>" ></td>
            <td class="font11 txt_center"><input name="mater_info_danga[<?=$jm?>]" class="font11 txt_center noborder bal_danga_<?=$jm?>" data-balju-idx="<?=$jm?>" onblur="balju_p(this)" value ="<?=$mater_info['danga'] ? number_format($mater_info['danga']): ''?>" ></td>
            <td class="font11 txt_right"><input name="mater_info_price[<?=$jm?>]" class="font11 txt_right noborder balju_p bal_price_<?=$jm?>" value ="<?=$mater_info['danga'] ? number_format(($main_yd )*$mater_info['danga']) : ''?>" readonly></td>
            <td class="font10 txt_left"><input name="mater_info_etc[<?=$jm?>]" class="input_red" type="text"  value="<?=$mater_info['etc']?>"></td>
        </tr>
        <? break;
        endif?>
        <?php endforeach ?>
        <?php foreach ($jo_mater_info as $jm => $mater_info) : ?>
        <?if (!empty($mater_info['main']) && $mater_info['main'] == '코디') : 
            // $mater_count++;
            if(!empty($balju_result['pno'])){
                $codi_yd = $mater_info['yd'];
                $total_yd += $codi_yd  ;
            }else{
                $total_yd += $codi_yd  ;
            }
            $total_price += $mater_info['danga'] * ($codi_yd );
        ?>
        <tr>
            <td colspan=2 class="font9 txt_center"><?=$result['jo_it_name']?><?=stripslashes($jo_codi_img[1]['title'])?></td>
            <th class="font10 txt_center"><?=color_table($result['jo_color'])?></th>
            <th class="font10 txt_center">YD</th>
            <input type="hidden" name="mater_info[<?=$jm?>]">
            <input type="hidden" name="mater_info_soje[<?=$jm?>]" value = "<?=stripslashes($jo_codi_img[1]['title'])?>">
            <input type="hidden" name="mater_info_color[<?=$jm?>]" value="<?=color_table($result['jo_color'])?>">
            <input type="hidden" name="mater_info_main[<?=$jm?>]" value="<?=$mater_info['main']?>">
            <input type="hidden" name="mater_info_origin_yd[<?=$jm?>]" value="<?=$_GET['codi']?>">
            <input type="hidden" name="mater_info_mater_name[<?=$jm?>]" value="<?$cat2 = explode('_',$jo_mater_name[2]['mater']); echo $cat2[0]; ?>">
            <td class="font11 txt_center"><input name="mater_info_size[<?=$jm?>]"  class="font11 txt_center noborder" value="<?=$mater_info['size']?>"></td>
            <td class="font11 txt_right"><input name="mater_info_yd[<?=$jm?>]"  class="font11 txt_right noborder balju_yd yd_id_<?=$jm?>" data-balju-idx="<?=$jm?>" onblur="balju_p(this)" value = "<?=number_format(($codi_yd),1)?>" ></td>
            <td class="font11 txt_center"><input name="mater_info_danga[<?=$jm?>]" class="font11 txt_center noborder bal_danga_<?=$jm?>" data-balju-idx="<?=$jm?>" onblur="balju_p(this)" value ="<?=$mater_info['danga'] ? number_format($mater_info['danga']): ''?>" ></td>
            <td class="font11 txt_right"><input name="mater_info_price[<?=$jm?>]" class="font11 txt_right noborder balju_p bal_price_<?=$jm?>" value ="<?=$mater_info['danga'] ? number_format(($codi_yd)*$mater_info['danga']) : ''?>" readonly></td>
            <td class="font10 txt_left"><input name="mater_info_etc[<?=$jm?>]"  class="input_red" type="text"  value="<?=$mater_info['etc']?>"></td>
        </tr>
        <? break;
        endif?>
        <?php endforeach ?>
        <?php foreach ($jo_mater_info as $jm => $mater_info) : ?>
        <?if (!empty($mater_info['main']) && $mater_info['main'] == '코디1') : 
            // $mater_count++;
            if(!empty($balju_result['pno'])){
                $codi1_yd = $mater_info['yd'];
                $total_yd += $codi1_yd  ;
            }else{
                $total_yd += $codi1_yd  ;
            }
            $total_price += $mater_info['danga'] * ($codi1_yd );
        ?>
        <tr>
            <td colspan=2 class="font9 txt_center"><?=$result['jo_it_name']?><?=stripslashes($jo_codi1_img[1]['title'])?></td>
            <th class="font10 txt_center"><?=color_table($result['jo_color'])?></th>
            <th class="font10 txt_center">YD</th>
            <input type="hidden" name="mater_info[<?=$jm?>]">
            <input type="hidden" name="mater_info_soje[<?=$jm?>]" value = "<?=stripslashes($jo_codi1_img[1]['title'])?>">
            <input type="hidden" name="mater_info_color[<?=$jm?>]" value="<?=color_table($result['jo_color'])?>">
            <input type="hidden" name="mater_info_main[<?=$jm?>]" value="<?=$mater_info['main']?>">
            <input type="hidden" name="mater_info_origin_yd[<?=$jm?>]" value="<?=$_GET['codi1']?>">
            <input type="hidden" name="mater_info_mater_name[<?=$jm?>]" value="<?$cat3 = explode('_',$jo_mater_name[3]['mater']); echo $cat3[0]; ?>">
            <td class="font11 txt_center"><input name="mater_info_size[<?=$jm?>]"  class="font11 txt_center noborder" value="<?=$mater_info['size']?>"></td>
            <td class="font11 txt_right"><input name="mater_info_yd[<?=$jm?>]"  class="font11 txt_right noborder balju_yd yd_id_<?=$jm?>" data-balju-idx="<?=$jm?>" onblur="balju_p(this)" value = "<?=number_format(($codi1_yd),1)?>" ></td>
            <td class="font11 txt_center"><input name="mater_info_danga[<?=$jm?>]" class="font11 txt_center noborder  bal_danga_<?=$jm?>" onblur="balju_p(this)" value ="<?=$mater_info['danga'] ? number_format($mater_info['danga']): ''?>" ></td>
            <td class="font11 txt_right"><input name="mater_info_price[<?=$jm?>]" class="font11 txt_right noborder balju_p bal_price_<?=$jm?>" value ="<?=$mater_info['danga'] ? number_format(($codi1_yd)*$mater_info['danga']) : ''?>" readonly></td>
            <td class="font10 txt_left"><input name="mater_info_etc[<?=$jm?>]"  class="input_red" type="text" value="<?=$mater_info['etc']?>"></td>
        </tr>
        <? break;
        endif?>
        <?php endforeach ?>

      
        <?if($mater_count < 6):?>
            <tr>
                <td colspan=2 class="font10 txt_center">이하여백</td>
                <th></th>
                <th></th>
                <th></th>
                <td></td>
                <th></th>
                <td class="font11  txt_right">0</td>
                <td></td>
            </tr>
            <?
            $cnt_mater = $mater_count+2;
            for($maidx = $cnt_mater; $maidx < 7 ; $maidx++) : ?>
                <tr>
                    <td colspan=2></td>
                    <th></th>
                    <th></th>
                    <th></th>
                    <td></td>
                    <th></th>
                    <td class="font11  txt_right">0</td>
                    <td></td>
                </tr>
            <?endfor?>

        <?endif?>
        
        <tr>
            <th colspan=5 class="font11 txt_center">소   계</th>
            <td class="font11 txt_center"><input class="font11 txt_center total_yd" value = "<?=number_format($total_yd,1)?>" disabled ></td>
            <th class="font11 txt_center"></th>
            <td class="font11 txt_right"><input class="font11 txt_right total_price" value = "<?=number_format($total_price)?>" disabled ></td>
            <td></td>
        </tr>
        <tr>
            <th colspan=5 class="font11 txt_center">부 가 세</th>
            <th id="LRnob" class="font11 txt_center"></th>
            <th class="font11 txt_center"></th>
            <td class="font11 txt_right"><input class="font11 txt_right buga_price" value = "<?=number_format($total_price * 0.1)?>" disabled ></td>
            <td></td>
        </tr>
        <tr>
            <th colspan=5 class="font11 txt_center">합   계</th>
            <th id="LRnob" class="font11 txt_center"></th>
            <th class="font11 txt_center"></th>
            <td class="font11 txt_right"><input class="font11 txt_right total_real_price" value = "<?=number_format($total_price  * 1.1)?>" disabled ></td>
            <td></td>
        </tr>
        
        <tr>
            <td colspan=9 class="txt_left"> <span style="font-weight : bold;"> 문의 사항 </span> <br>
            <select class="balju_select" name="comform_user" onchange="categoryChange(this)">
                <option value="001" <?=$balju_result['comform_user'] == '001' ? 'selected' : ''?>>리탠다드(주) 상품MD팀 유숙현님 TEL 02-3494-7622 / HP 010-8628-0919 / FAX 02-830-7503</option>
                <option value="002" <?=$balju_result['comform_user'] == '002' ? 'selected' : ''?>>리탠다드(주) 상품MD팀 강동윤님 TEL 02-3494-7621 / HP 010-2293-7019 / FAX 02-830-7503</option>
                <option value="003" <?=$balju_result['comform_user'] == '003' ? 'selected' : ''?>>리탠다드(주) 상품MD팀 염종권님 TEL 02-3494-7621 / HP 010-2293-7019 / FAX 02-830-7503</option>
                <option value="004" <?=$balju_result['comform_user'] == '004' ? 'selected' : ''?>>리탠다드(주) 상품MD팀 박동언님 TEL 02-3494-7621 / HP 010-2293-7019 / FAX 02-830-7503</option>
                <option value="005" <?=$balju_result['comform_user'] == '005' ? 'selected' : ''?>>리탠다드(주) 상품MD팀 신상철님 TEL 02-3494-7621 / HP 010-2293-7019 / FAX 02-830-7503</option>
                <option value="006" <?=$balju_result['comform_user'] == '006' ? 'selected' : ''?>>리탠다드(주) 상품MD팀 정윤경님 TEL 02-3494-7621 / HP 010-2293-7019 / FAX 02-830-7503</option>
                <option value="007" <?=$balju_result['comform_user'] == '007' ? 'selected' : ''?>>리탠다드(주) 상품MD팀 김소현님 TEL 02-3494-7621 / HP 010-2293-7019 / FAX 02-830-7503</option>
                <option value="008" <?=$balju_result['comform_user'] == '008' ? 'selected' : ''?>>리탠다드(주) 상품MD팀 임태경님 TEL 02-3494-7621 / HP 010-2293-7019 / FAX 02-830-7503</option>
            </select>
            
            </td>
        </tr>
        <tr>
            <th colspan=9 class="txt_left">
            <span style="font-weight : bold; line-height:20px;">
                특기사항/ 유의사항 <br>
                1. 결제조건 : 납품 당월 말일 계산서 발행 60일 이후 현금결제<br>
                2. 운송방법 : 직택배(본사로) / 발주서 확인 후 예상납기일 공유바랍니다.<br>
            </span>
                <span style="font-weight : 500; line-height:20px;">
                3. 품질 기준 미달 및 납기 지연으로 인한 제 손실은 전액 공급자가 부담하여야 합니다. (당일입고분 포함)<br>
                4. 거래명세표에는 품명, 규격, 색상, SIZE별 수량을 반드시 기재하여 주십시오.<br>
            </span>
            </th>
        </tr>
        <tr>
            <td colspan=9 class="txt_left"><input class="input_nor" type="text" name="meg_item[1]"  value="<?=$meg_list[1]?>"></td>
        </tr>
        <tr>
            <td colspan=9 class="txt_left"><input class="input_nor" type="text" name="meg_item[2]"  value="<?=$meg_list[2]?>"></td>
        </tr>
        <tr>
            <td colspan=9 class="txt_left"><input class="input_nor" type="text" name="meg_item[3]"  value="<?=$meg_list[3]?>" ></td>
        </tr>
        </tbody>
        <tr class="noborder">
        <th>리탠다드(주)</th>
        <td colspan=5></td>
        <th colspan=3 class="txt_right"><?=$result['jo_brand']?></th>
        </tr>
    </table>

    </form>

    <div>
        <table id="comfirm_table">
            <tr>
                <td class="txt_center" rowspan = 2 >결<br>재</td>
                <td class="txt_center">담  당</td>
                <td class="txt_center">선  임</td>
                <td class="txt_center">실  장</td>
                <td class="txt_center">부서장</td>
                <td class="txt_center">사  장</td>
            </tr>
            <tr class="hei_50">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>

</div>

<div class="noprint">
<button class="noprint btn btn_02" onclick="printPage_3(event);" type="button btn-success">출력</button>

<button class="noprint btn btn_02" onclick="save(event);" type="button btn-success">저장</button>
</div>




<script>
    function balju_p(elem){
        let id = $(elem).data("balju-idx");

        let yd = $('.yd_id_'+id).val().replace(/,/gi,'');
        let danga = $('.bal_danga_'+id).val().replace(/,/gi,'');

        let price = (yd * danga);
        
        $('.bal_price_'+id).val(comma(price+""));

        var fileValue = $(".balju_p").length;
        var fileData = new Array(fileValue);
        var fileData2 = new Array(fileValue);
        let total_price = 0;
        let total_yd = 0;
        for(var i=0; i<fileValue; i++){                          
            fileData[i] = $(".balju_p")[i].value.replace(/,/gi,'');
            total_price += (fileData[i]*1);
            
            fileData2[i] = $(".balju_yd")[i].value.replace(/,/gi,'');
            total_yd += (fileData2[i]*1);
            
        }

        $('.yd_id_'+id).val(comma(yd+""));
        $('.bal_danga_'+id).val(comma(danga+""));
        //소계
        $('.total_yd').val(comma(total_yd+""));
        $('.total_price').val(comma(total_price+""));
        //부가세
        $('.buga_price').val(comma( Math.floor(total_price * 0.1)+"" ));
        //합계
        $('.total_real_price').val(comma( Math.floor(total_price * 1.1) +""));


    }
    function comma(obj){
        
        var regx = new RegExp(/(-?\d+)(\d{3})/);
        var bExists = obj.indexOf(".", 0);//0번째부터 .을 찾는다.
        var strArr = obj.split('.');
        while (regx.test(strArr[0])) {//문자열에 정규식 특수문자가 포함되어 있는지 체크
            //정수 부분에만 콤마 달기 
            strArr[0] = strArr[0].replace(regx, "$1,$2");//콤마추가하기
        }
        if (bExists > -1) {
            //. 소수점 문자열이 발견되지 않을 경우 -1 반환
            obj = strArr[0] + "." + strArr[1];
        } else { //정수만 있을경우 //소수점 문자열 존재하면 양수 반환 
            obj = strArr[0];
        }
        return obj;//문자열 반환     
    }
    function printPage_3(){
        save();
        // var el = document.querySelectorAll(".mater_select");
        // el.forEach(function(select) {
        //     if(select.value){

        //     }else{
        //         select.style.display = "none";
        //     }
        // });
        var initBody;
        window.onbeforeprint = function(){
            initBody = document.body.innerHTML;
            document.body.innerHTML =  document.getElementById('print3').innerHTML;
        };
        window.onafterprint = function(){
            document.body.innerHTML = initBody;
            location.reload();
            
        };
        window.print();
        location.reload();
        return false;
    }

    function save(){
        // alert("asdf");
        
        var formData = $("#fwrite").serialize();

        $.ajax({
            cache : false,
            url : "./update_balju_print.php", // 요기에
            type : 'POST', 
            data : formData, 
            success : function(data) {
                // var jsonObj = JSON.parse(data);
                console.log(data);
            }, // success 

            error : function(xhr, status) {
                alert(xhr + " : " + status);
            }
        }); 
        

    }

    function categoryChange(e){
        switch(e.value){
            case "001" :
                document.getElementById('comf_user').innerText = '리탠다드 상품소싱팀 유숙현님';
            break;
            case "002" :
                document.getElementById('comf_user').innerText = '리탠다드 상품소싱팀 강동윤님';
            break;
            case "003" :
                document.getElementById('comf_user').innerText = '리탠다드 상품소싱팀 염종권님';
            break;
            case "004" :
                document.getElementById('comf_user').innerText = '리탠다드 상품소싱팀 박동언님';
            break;
            case "005" :
                document.getElementById('comf_user').innerText = '리탠다드 상품소싱팀 신상철님';
            break;
            case "006" :
                document.getElementById('comf_user').innerText = '리탠다드 상품소싱팀 정윤경님';
            break;
            case "007" :
                document.getElementById('comf_user').innerText = '리탠다드 상품소싱팀 김소현님';
            break;
            case "008" :
                document.getElementById('comf_user').innerText = '리탠다드 상품소싱팀 임태경님';
            break;
        }

    }
    

</script>