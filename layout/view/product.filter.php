<?php
ob_start();
$fidx = $sfidx = 0;
$lidx = 0;
?>

<form name="form_filter" method="GET">
    <input type="hidden" name="ca_id" value="<?= $ca_id ?>">
    <input type="hidden" name="sort" value="<?= $sort ?>">
    <input type="hidden" name="sortodr" value="<?= $sortodr ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">
    <input type="hidden" name="numberView" value="<?= $numberView ?>">
    <input type="hidden" name="mix">
</form>

<div class="product-list-action" id ="product-list-action-mo">
    <span class="item_count on-big">총 <?=$total_count?> 개 상품</span>
    <? if (!empty($filters)) : ?>
        <span class="btn-product-list-filter btn-filter-add on-small" id="btn-modal-filter" onclick="filter_modal()"><img src="/img/re/filter.png" srcset="/img/re/filter@2x.png 2x,/img/re/filter@3x.png 3x">필터</span>
    <? endif ?>
    
    <select class="btn-product-list-filter btn-sort" style="float: right; text-align :left">
        <option value="new" <?php if ($sort == "new") echo "selected" ?>>신상품순</option>
        <option value="best" <?php if ($sort == "best") echo "selected" ?>>인기순 </option>
        <option value="disc" <?php if ($sort == "disc") echo "selected" ?>>할인율순</option>
    </select>
    <? if (!empty($filters)) : ?>
        <span class="btn-product-list-filter btn-filter-add on-big" id="btn-toggle-filter"><img src="/img/re/filter.png" srcset="/img/re/filter@2x.png 2x,/img/re/filter@3x.png 3x">필터</span>
    <? endif ?>
</div>
<!-- 
<div class="on-small" id ="product-list-action-mo" style="margin:8px 14px;">
    <? if (!empty($filters)) : ?>
        <span class="btn-product-list-filter btn-filter-add on-small" id="btn-modal-filter" onclick="filter_modal()"><img src="/img/re/filter.png" srcset="/img/re/filter@2x.png 2x,/img/re/filter@3x.png 3x">필터</span>
    <? endif ?>
    <select class="btn-product-list-filter btn-sort" style="float: right;">
        <option value="new" <?php if ($sort == "new") echo "selected" ?>>신상품</option>
        <option value="best" <?php if ($sort == "best") echo "selected" ?>>인기순</option>
        <option value="disc" <?php if ($sort == "disc") echo "selected" ?>>할인율</option>
    </select>
</div> -->

<? if (!empty($filters)) : ?>
    <div id="filter-wrapper">
        <div id="filter-list-wrapper" style="word-break: break-word;">
            <? foreach ($filters as $fi => $fc) : ?>
                <?php if (!isset($fc['SUBJECT'])) : ?>
                <?php
                echo $fidx > 0 ? "</div>" : "";
                $fidx++;
                $lidx = 0;
                ?>
                    <div class="filter-row">
                    <div class="filter-row-title"><?= $fi ?></div>
                    <ul class="scrollbar-inner">
                <? else : $lidx++; ?>
                    <!-- <?php
                    echo ($lidx % 5) ==0 ? "</ul><ul>" : "";
                    ?> -->
                    <li class="filter-item custom-checkbox"><input type="checkbox" id="filter-item-<?= $fi ?>" class="custom-control-input cbg-sf-<?= $fidx ?>" data-checkgroup="cbg-sf-<?= $fidx ?>" <?php if (!isset($fc['SUBJECT'])) : ?> data-checkall="cbg-sf-<?= $fidx ?>" <?php else : ?> value="<?= $fi ?>" <?php endif ?>><label for="filter-item-<?= $fi ?>" class="custom-control-label"><?= $fi ?><?php echo $fc['COUNT'] > 0 ? "({$fc['COUNT']})" : "" ?></label></li>   

                <? endif ?>
            <? endforeach ?>

            </div>
            <div class="product-list-filter">
               
            </div>
        </div>
        <div id="filter-list-wrapper-btn" style="text-align: center;">
            <button type="button" class="C1KOWHM" style="width: 111px; height: 44px; border-radius: 2px; font-size: 16px;font-weight: 500;color: #3a3a3a; border: solid 1px #333333; background-color: #ffffff" onclick="formFilterSubmit(true)">초기화</button>
            <button type="button" class="C1KOWHM" style="width: 111px; height: 44px; border: unset; background-color: #333333;" onclick="formFilterSubmit()">필터적용</button>
        </div>
    </div>
    <div class="product-list-filter">
        <div id="product-list-filter-wrapper">
            <?php if (!empty($filter)) : ?>
                <?php foreach (explode(',', $filter) as $f) : ?>
                    <span class="product-list-filter-selected" onclick="removeFilter('<?= $f ?>')"><?= $f ?></span>
                <? endforeach ?>
            <?php endif ?>
        </div>
    </div>
    <div class="on-big">
    <select class="btn_numberView on-big" style="float: right; margin-top: -30px;">
            <option value="20" <?php if ($numberView == "20") echo "selected" ?>>20개씩 보기</option>
    	    <option value="50" <?php if ($numberView == "50") echo "selected" ?> >50개씩 보기</option>
		    <option value="100" <?php if ($numberView == "100") echo "selected" ?> >100개씩 보기</option>
    </select><br>
    </div>
<? else : ?>
    <div class="on-big">
    <select class="btn_numberView on-big" style="float: right; margin-top: -7px;">
        <option value="20" <?php if ($numberView == "20") echo "selected" ?>>20개씩 보기</option>
    	<option value="50" <?php if ($numberView == "50") echo "selected" ?> >50개씩 보기</option>
		<option value="100" <?php if ($numberView == "100") echo "selected" ?> >100개씩 보기</option>
    </select><br><br>
    </div>
<? endif ?>

<!-- 필터 모달 -->

<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="btn-modal-filter" aria-hidden="true" style="max-width: unset; min-width: unset;">
    <div class="modal-dialog" role="document">
        <div id="modal-filter-content" class="modal-content" style="height: calc(100vh - 150px);">
            <div class="modal_header">필터
                <img  src="/img/re/cancle.png" srcset="/img/re/cancle@2x.png 2x,/img/re/cancle@3x.png 3x" data-dismiss="modal">
            </div>
            <div class="modal_body">
                <div>
                    <? foreach ($filters as $fi => $fc) : ?>
                        <?php if (!isset($fc['SUBJECT'])) : ?>
                            <?php $sfidx++ ?>
                </div>
                <div class="filter-row">
                    <div class="filter-item custom-title"><?= $fi ?></div>
                <?php else: ?>
                    <div class="filter-item custom-checkbox"><input type="checkbox" id="filter-modal-item-<?= $fi ?>" class="custom-control-input cbg-fm-<?= $sfidx ?>" data-checkgroup="cbg-fm-<?= $sfidx ?>" <?php if (!isset($fc['SUBJECT'])) : ?> data-checkall="cbg-fm-<?= $sfidx ?>" <?php else : ?> value="<?= $fi ?>" <?php endif ?>><label for="filter-modal-item-<?= $fi ?>" class="custom-control-label"><?= $fi ?><?php echo $fc['COUNT'] > 0 ? "({$fc['COUNT']})" : "" ?></label></div>
                <?php endif ?>
                <?= !isset($fc['SUBJECT']) ? "</div><div class='filter-row filter-row-sub'>" : "" ?>
            <? endforeach ?>
                </div>
                <div class="filter-btn-group">
                    <button type="button" class="C1KOWHM" style="color: #333333; background-color: #ffffff;" onclick="formFilterSubmit(true)">초기화</button>
                    <button type="button" class="C1KOWHM" style="color: #ffffff; background-color: #333333;" onclick="formFilterSubmit()">필터적용</button>
                </div>
                </div>
        </div>
    </div>
</div>

<style>
    #modal-filter {
        overflow-y : hidden;
    }
    #modal-filter .modal-dialog{
        width : 100%;
        margin : 0;
        padding : 0;
        position: absolute;
        bottom: 0;
    }
    #modal-filter-content{
        width : 100%;
        border-radius: 20px 20px 0 0 ;
        margin : 0;
        padding : 0 !important;
        position: fixed;
        bottom: 0px;
    }
    #modal-filter-content .modal_header{height:50px; line-height: 50px; text-align: center;font-size: 18px; font-weight: 500; color: #090909; position:relative;}
    #modal-filter-content .modal_header img{position:absolute; top : 50%; right : 7px; transform: translate(-50%,-50%);}
    #modal-filter-content .modal_body{ display:grid; height:calc(100vh - 98px); overflow-x : scroll;}
    #modal-filter-content .filter-row {border-top: 1px solid #e0e0e0;}
    #modal-filter-content .filter-row .custom-title {margin : 20px 0 0 14px;}
    #modal-filter-content .filter-row.filter-row-sub{ margin-left : 2px; margin-top : 8px; border: none; padding-bottom: 6px;}
    #modal-filter-content .filter-btn-group{margin : 0 14px; display:flex; justify-content: space-around; margin-bottom : 40px;}
    #modal-filter-content .filter-btn-group button { width:calc((100vw - 42px) / 2); height: 44px;  text-align:center;  font-size: 14px;  font-weight: 500;  border-radius: 2px;  border: solid 1px #333333;  line-height: 40px;  letter-spacing: normal;}

    #modal-filter-content .filter-row.filter-row-sub .custom-checkbox{width : 50%; float: left;height: 20px;font-size: 14px;font-weight: normal;font-style: normal;line-height: 20px;color: #777777; margin-bottom : 18px;}
    #modal-filter-content .filter-row .custom-title{height: 20px;font-size: 14px;font-weight: 500;font-stretch: normal;line-height: 20px;color: #333333;}
</style>

<script>
    const formFilter = document.form_filter;
    let filter = formFilter.filter.value;

    function formFilterSubmit(reset) {
        if (!reset) reset = false;
        let filterSet = $(".filter-item>input[type=checkbox]:checked");

        if (reset) {
            formFilter.filter.value = "";
            $(filterSet).prop("checked", false);
            $(".product-list-filter-selected").remove();
            return false;
        }

        return formFilter.submit();
    }

    function calcFilter() {
        let filterChecked = [];
        let filterIconSet = $(".product-list-filter-selected");
        let filterSet = $(".filter-item>input[type=checkbox]:checked");

        filterSet.each(function(fsi, fse) {
            if (!$(fse).data("checkall")) {
                if (filterChecked.indexOf($(fse).val()) < 0) filterChecked.push($(fse).val());
            }
        });

        $("#product-list-filter-wrapper").html("");
        $(filterChecked).each(function(fci) {
            $("#product-list-filter-wrapper").append("<span class='product-list-filter-selected' onclick=removeFilter('"+ filterChecked[fci] +"')>" + filterChecked[fci] + "</span>&nbsp;");
        });

        formFilter.filter.value = filterChecked.join(',');
    }

    $(document).ready(function() {
        const filterChecked = filter.split(',');
        
        let filterSet = $(".filter-item>input[type=checkbox]");
        $(filterSet).each(function(fi) {
            //alert(filterChecked.indexOf(filterSet[fi].value));
            if (filterChecked.indexOf(filterSet[fi].value) >= 0) {
                filterSet[fi].checked = "checked";
                //controllCheckboxGroup(filterSet[fi]);
            }
        })
    });

    $(".filter-item>input[type=checkbox]").on("change", function() {
        const filterElem = $(this);
        const filterText = $(this).val();

        if (filterText != "on") {
            let filterSet = $(".filter-item>input[type=checkbox]");
            $(filterSet).each(function(fi) {
                if (filterSet[fi].value == filterText) {
                    filterSet[fi].checked = filterElem.prop("checked") ? "checked" : "";
                }
            })
            return calcFilter();
        } else {
            const filterId = filterElem.prop("id").split("-");
            controllCheckboxGroup(document.getElementById("filter-item-" + filterId[filterId.length - 1]));
            controllCheckboxGroup(document.getElementById("filter-modal-item-" + filterId[filterId.length - 1]));
            return calcFilter();
        }

    });

    function removeFilter(text_filter){
        let filterSet = $(".filter-item>input[type=checkbox]");
        $(filterSet).each(function(fi) {
            if (filterSet[fi].value == text_filter) {
                filterSet[fi].checked = "";
            }
        })

        return calcFilter();
    }

    // $("span.product-list-filter-selected").on("click", function() {
    //     const filterText = $(this).text();
        
    //     let filterSet = $(".filter-item>input[type=checkbox]");
    //     $(filterSet).each(function(fi) {
    //         if (filterSet[fi].value == filterText) {
    //             filterSet[fi].checked = "";
    //         }
    //     })

    //     return calcFilter();
    // });

    $(".btn-filter-mixit").on("click", function() {
        formFilter.mix.value = true;
        return formFilter.submit();
    });

    $(".btn-sort").on("change", function() {
        const sort = $(".btn-sort > option:checked");
        formFilter.sort.value = sort.val();
        return formFilter.submit();
    });

    $(".btn_numberView").on("change", function() {
        const numberView = $(".btn_numberView > option:checked");
        formFilter.numberView.value = numberView.val();
        return formFilter.submit();
    });


</script>

<?php
$filter_view = ob_get_contents();
ob_end_clean();
return $filter_view;
?>