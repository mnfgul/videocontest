$(function () {
	
	// Preload images
	$.preloadCssImages();
	
	
	if($.fn.visualize)
	{
		drawGraph();
	}
	
	//video gallery
	$("a[rel^='videos']").prettyPhoto({ie6_fallback: true, social_tools:false, slideshow: false});
	
	/*Change Status*/
	 $(".cb-enable").click(function(){
        var parent = $(this).parents('.switch');
        $('.cb-disable',parent).removeClass('selected');
        $(this).addClass('selected');
        $('.changeStatus',parent).attr('checked', true).trigger("change");
	  });
    $(".cb-disable").click(function(){
        var parent = $(this).parents('.switch');
        $('.cb-enable',parent).removeClass('selected');
        $(this).addClass('selected');
        $('.changeStatus',parent).attr('checked', false).trigger("change");
    });
	
    $(".changeStatus").change(function(){
    	newstatus = 0;
		if($(this).is(":checked")){newstatus = 1} else {newstatus = -1}
		var vid = $(this).val();
		var topParent = $(this).parent().parent();
		var parent = $(this).parent();
		topParent.children(".feedback").remove();
		parent.after('<div class="progress feedback">Saving Changes</div>');
		
		$.post("ajax-interface.php", { id: vid, status: newstatus, action: "change-status"},
				function(data) {						
					topParent.children(".feedback").remove();
					if(data.result == -1)
					{
						if($(this).is(":checked")){
							$(this).attr('checked', false);
						}
						else{
							$(this).attr('checked', true);
						}
						msg = "'"+data.msg+"'";
						parent.after('<div class="error feedback">Error Occured <br/> <a href="javascript:onClick=alert('+msg+');" title="View Error Details">View Details</a></div>');
					}
					else
					{
						var icoId = "#newIco-"+vid;
						$(icoId).fadeOut();
						parent.after('<div class="success feedback">Changes Done!</div>');
					}
					window.setTimeout('$(".success").fadeOut();', 2000);
			      }
			   );
    	
    });
    
    
	/*User table*/
	$('.detailsLink').click(function(){
		$('.detailRow:visible').hide();
		var name=$(this).attr('id');
		name='#detailRow-'+name;
		$(name).fadeIn();
		return false;
	});
	
	$(".userDeleteLink").click(function(){
		if(!confirm("Do you want to DELETE this participant ?"))
		{
			return false; 	
		}
	});
	
	$(".printLink").click(function(){
		var link = $(this).attr('href');
		window.open(link,'mywin','left=20,top=20,width=510,height=500,toolbar=0,resizable=0, scrollbars=1');
		return false;
	});
	// Check / uncheck all checkboxes
	$('.check_all').click(function() {
		$(this).parents('form').find('input:checkbox').attr('checked', $(this).is(':checked'));   
	});
	
	
	// Modal boxes - to all links with rel="facebox"
	//$('a[rel*=facebox]').facebox()
	
	
	// Messages
	$('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
	$('.block .message .close').hover(
		function() { $(this).addClass('hover'); },
		function() { $(this).removeClass('hover'); }
	);
		
	$('.block .message .close').click(function() {
		$(this).parent().fadeOut('slow', function() { $(this).remove(); });
	});
	
	
	// Tabs
	$(".tab_content").hide();
	$("ul.tabs li:first-child").addClass("active").show();
	$(".block").find(".tab_content:first").show();

	$("ul.tabs li").click(function() {
		$(this).parent().find('li').removeClass("active");
		$(this).addClass("active");
		$(this).parents('.block').find(".tab_content").hide();

		var activeTab = $(this).find("a").attr("href");
		$(activeTab).show();
		return false;
	});
		
	
	// Date picker
	//$('input.date_picker').date_input();
	
		
	// CSS tweaks
	$('#header #nav li:last').css('background', 'none');
	$('.block_head ul').each(function() { $('li:first', this).css('background', 'none'); });
	$('.block table.list tr').hover(function(){$(this).toggleClass('hoverRow');});
	$('.block table tr:odd').css('background-color', '#fbfbfb');
	$('.block form input[type=file]').addClass('file');
	
	
	// IE6 PNG fix
	//$(document).pngFix();
		
});


function getConfirm(txt)
{
	if(!confirm(txt))
	{
		return false;
	}
}

function pageTaks(page)
{
	switch(page)
	{
		//dashboard tasks
		case '0':{
			
		}
	}
}

function drawGraph()
{
	// Participant stats	
	$('table.stats').hide().visualize({
		type: 'area',	// 'bar', 'area', 'pie', 'line'
		width: '880px',
		height: '240px',
		colors: ['#6fb9e8', '#ec8526', '#9dc453', '#ddd74c']
	});
}