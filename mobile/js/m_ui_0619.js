var w = $(window);
var wH = $(window).height();

$(function(){



function wHeight(){
	var headerH = $("#header").innerHeight();
	var footerH = $("#footer").innerHeight();
	
	$("body").css("padding-top", headerH)
	$("#container .content.sub").css("min-height", wH - headerH - footerH - 45 + "px");
	$("#container").css("min-height", wH - headerH - footerH );
	$(".popup_container").css("height", wH + "px");

} wHeight();

 //상단베너
 function topBanner(){
	 $("#header .top_group .top_close").on("mouseenter", function(e){
		$(this).parent(".top_group").hide(); 
	 });
 } topBanner();

 //aside
 $(document).on("click", "#header .btn_menu_all", function(e){
	$("#aside").animate({left:"0%"}, 300);
 });
  $(document).on("click", "#lnb .btn_menu_all", function(e){
	$("#aside").animate({left:"0%"}, 300);
 });
 $(document).on("click", "#aside .btn_closed", function(e){
	$("#aside").animate({left:"100%"}, 300);
 });

  //search
  $(document).on("click", "#header .btn_search", function(e){
	
 });

 //floating_wrap
	 $(document).on("click", ".floating_wrap .control_btn button", function(e){
	  $(this).parents(".floating_wrap").toggleClass("opened");
 });

$(window).scroll(function(){
	$(".menu-gate").hide();
})

 //nav
 function globalNavi(){
  var navWrap = $("#header .nav_group .menu")
  var navMenu = navWrap.find(">ul");
  var navM_li = navMenu.find(">li");
		
		

	  navM_li.find(" > a").on("mouseenter focus", function(e){
		 var btA = $(this);
		 var target = $(btA).parent("li")
		
	
		 var navbarHeight = target.find(">.dep2").outerHeight();
		 $("#header .nav_group .menu > ul > li").each(function(){
			 $(this).removeClass("on");
			 $(this).find(".dep2").hide();

		 });
	     //$("#header .nav_group .menu > ul > li").removeClass("on");
	     target.addClass("on");
	    // navWrap.stop().animate({'height': 45 + navbarHeight + "px"}, "swing");
	     target.find(">.dep2").show();
		
		//menu-gate
		if(target.find("on")){
			$(".menu-gate").hide();
			$(this).siblings(".menu-gate").show();
		}else{
			$(".menu-gate").hide();
		}
	 });
		
	  $("#header .nav_group .dep2 > ul > li > a").on("mouseenter focus", function(e){
			
			// menu-gate
			$(".menu-gate").hide();

		   $("#header .nav_group .dep2 > ul > li").each(function(){
				 $(this).removeClass("on");
			 });
			 $("#header .nav_group .dep2 > ul > li .dep3").each(function(){
				 $(this).hide();
			 });

		 	if ($(this).parent("li").hasClass("on"))
			{
				$(this).parent("li").removeClass("on");
				$(this).next(".dep3").hide();
			} else{
				$(this).parent("li").addClass("on");
				$(this).next(".dep3").show();
			}

			
	 });

	  $("#header .nav_group .dep3 > ul > li > a").on("mouseenter focus", function(e){
		   $("#header .nav_group .dep3 > ul > li").each(function(){
			 $(this).removeClass("on");
		 });
		   $(this).parent("li").addClass("on");
	 });

	 $("#header").on("mouseleave", function(){
		 navM_li.removeClass("on");
		 $("#header .nav_group .dep2").each(function(){ $(this).hide();});
		 $("#header .nav_group .dep2 > ul > li").removeClass("on"); 
		 $("#header .nav_group .dep2 > ul > li .dep3").each(function(){$(this).hide();});

		 // menu-gate
		$(".menu-gate").hide();
	 });

 } globalNavi();


 
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

	//tab
	function tabType2(){
		var tabBtn = $(".tab .tab_btn li");
		
		$(".tab_cont > .tab_inner:nth-child(1)").show();
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
		$(".add_service").slideToggle();
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

	//selectStar
	function selectStar(){
		$(".select_star").find("button").on("click", function(){
			$(".select_star").find("li").each(function(){
				$(this).removeClass("on");
			});
			$(this).parent("li").addClass("on").prevAll("li").addClass("on");
		});
	} selectStar();

}); 


 //sticky_tab
 function stickyTab(){
	var stickyT = $(".sticky_tab");
	var stickyOffset = 0;
	//var stickyOffset = $(".sticky_tab").offset().top;

	$(window).scroll(function(){
		if ($(window).scrollTop() + 40 > stickyOffset)
		{
			stickyT.addClass("fixed");
		} else{
			stickyT.removeClass("fixed");
		}

		 // menu-gate
		//$(".menu-gate").hide();
	});
 } stickyTab();


