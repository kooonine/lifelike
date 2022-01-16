$(function(){

	//nav
	function globalNavi(){
		var navAll = $("#header .nav_group");
		var navMenu = $("#header .nav_group .menu");
		var navM_li = navMenu.find(">ul").find(">li");
		var navDep2 = navM_li.find(".depth2 > ul > li");

		
		navM_li.find("a").on("mouseenter focus", function(e){
			var btA = $(this);
			var target = $(btA).parent("li");
			var navH = target.find(".depth2").height();
			
			target.addClass("on").siblings().removeClass("on");

		});


		navAll.on("mouseleave", function(e){
			$(this).find("li").removeClass("on");
		});
	}
	globalNavi();

	//aside
	function aside(){
		$("#header .logo_group .btn_menu").on("click", function(){
			$("#header .aside").animate({left:0}, 200);
		});
		$("#header .aside .btn_closed").on("click", function(){
			$("#header .aside").animate({left:-480}, 200);
		});
	} aside();

	//onoff
	function onOff(){
		var onoff_bt = $(".onoff > li");
		onoff_bt.on("click", function(e){
		e.preventDefault();
		$(this).parents(".onoff").find(">li").each(function(){
			$(this).removeClass("on");
		});
		$(this).addClass("on");
		});
	} onOff();

	//selectStar
	function selectStar(){
		$(".select_star").find("button").on("click", function(){
			$(".select_star").find("li").each(function(){
				$(this).removeClass("on");
			});
			$(this).parent("li").addClass("on").prevAll("li").addClass("on");
		});
	} selectStar();

	//tab
	function tabType2(){
		var tabBtn = $(".tab .tab_btn li");
		
		//$(".tab_cont").children(".tab_inner:nth-child(1)").show();
		tabBtn.on("click", function(index){
			btA = $(this);
			tabLI = $(this).index();
			tabWrap = $(this).parent("ul").parent(".tab").parent(".tab_cont_wrap");
			
			tabWrap.children(".tab_cont").children(".tab_inner").hide();
			tabWrap.children(".tab_cont").children(".tab_inner").eq(tabLI).show();

			//console.log(tabBox)
			
		});
	} tabType2();

 	//toggle
	$(document).on("click", ".toggle_anchor", function(e){
		e.preventDefault();
		if ($(this).hasClass("disable")){
			return;
		}
		var accLi = $(this).closest(".toggle_group");
		var accThis = $(this);
		var accEle = accThis.parent(".title").next();
		if (accLi.hasClass("opened")){
			accEle.slideUp(200, function(){
				accEle.removeClass("opened");
				accLi.removeClass("opened");
			});
		} else {
			if ($(this).parents(".toggle_group").hasClass("opened")){
				$(".toggle .toggle_group").removeClass("opened");
				$(".toggle .toggle_group .cont").slideUp(200);
			}
			accLi.addClass("opened");
			accEle.slideDown(200);
		}
	});
	$(".add_service").hide();
	$(".info_add").click(function(){
		$(".info_service").slideToggle();
		$(this).toggleClass('on');
	});
	function switch_group(){
		var onoff_bt = $(".switch_group > button");
		onoff_bt.on("click focus", function(e){
			e.preventDefault();
			
			if ($(this).hasClass("on"))
			{
				$(this).find("span").animate({left:"-1px"},200, function(){
					$(this).parent("button").removeClass("on").addClass("off");
				});
			} else{
				$(this).find("span").animate({left:"12px"},200, function(){
					$(this).parent("button").removeClass("off").addClass("on");
				});
			}
			

		});
	} switch_group();

	//table ttoggle
	$("table td").find("a").on("click", function(e){e.preventDefault();});
	function tableToggle(){
		$("table td .qna_btn").on("click", function(){
			
			if ($(this).hasClass("on"))
			{
				$(this).removeClass("on");
				$(this).parents("tr").next(".qna_reply").hide();
			} else{
				$(this).addClass("on");
				$(this).parents("tr").next(".qna_reply").show();
			}
		});
	} tableToggle();

	//popup
	function popWrap (){
		var popCont = $(".popup_container").find(".inner_layer");
		var popH = popCont.innerHeight();
		//console.log(popH)
		popCont.css("margin-top", - popH / 2 );
	} popWrap();

});