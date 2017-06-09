$(document).ready(function(){
	
	//restrict just to numbers for numbers input
	$("input.number").bind('keypress', function(e) { 
		return ( e.which!=8 && e.which!=0 && (e.which<40 || e.which>57)) ? false : true ;
	});
	
	
	//give feedback for required fields after blur
	$(".required").blur(function(){
		$(this).parent().children('.inputIcon').remove();
		if($(this).val()=="")
		{
			$(this).addClass("inputError").after('<span class="errorIcon inputIcon">Please fill this area!</span>');
		}
		else
		{
			$(this).removeClass("inputError").after('<span class="okIcon inputIcon"></span>');
		}
	});
	
	//feedback for email field
	$(".email").blur(function(){
		$(this).parent().children('.inputIcon').remove();
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!filter.test($(this).val()))
		{
			$(this).addClass("inputError").after('<span class="errorIcon inputIcon">Please enter a valid email address</span>');
		}
		else
		{
			$(this).removeClass("inputError").after('<span class="okIcon inputIcon"></span>');
		}
	});
	
	//feedback for password
	$(".pass1").blur(function(){
		$(this).parent().children('.inputIcon').remove();
		if($(this).val().length < 5)
		{
			$(this).addClass("inputError").after('<span class="errorIcon inputIcon">Password must be at least 5 character long.</span>');
		}
		else
		{
			$(this).removeClass("inputError").after('<span class="okIcon inputIcon"></span>');
		}
	});
	
	$(".pass2").blur(function(){
		$(this).parent().children('.inputIcon').remove();
		if(($(this).val() != $(".pass1").val())||($(this).val()==""))
		{
			$(this).addClass("inputError").after('<span class="errorIcon inputIcon">Password does not match!</span>');
		}
		else
		{
			$(this).removeClass("inputError").after('<span class="okIcon inputIcon"></span>');
		}
	});
	
	//feedback for zip code
	$(".requiredZip").blur(function(){
		$(this).parent().children('.inputIcon').remove();
		if($(this).val().length!=5)
		{
			$(this).addClass("inputError").after('<span class="errorIcon inputIcon">Please enter a valid zip code.</span>');
		}
		else
		{
			$(this).removeClass("inputError").after('<span class="okIcon inputIcon"></span>');
		}
	});
	
	//feedback for age
	$("#ageYear").blur(function(){
		checkAge();
	});

	//Form Upload
	$("#videoUpload").uploadify({
		'uploader'        	: 'uploadify/uploadify.swf',
		'script'          	: 'uploadify/uploadify.php',
		'cancelImg'       	: 'uploadify/cancel.png',
		'folder'          	: 'files',
		'fileExt'     	  	: '*.wmv;*.mpeg;*.mpeg4;*.mov;*.avi;*.flv',
		'fileDesc'     		: 'Video Files',
		'wmode'       		: 'transparent',
		'hideButton'  		: true,
		'width'       		: 375,
		'height'			: 100,
		'removeCompleted' 	: false,
		'multi'				: false,
		'auto'        		: true,
		'scriptData'		: { 'sid': $("#sid").val()},
		'onComplete'  		: function(event, ID, fileObj, response, data) {
			alert(response);
			var obj = $.parseJSON(response);
			$("#uploadifyWarning").remove();
			if(obj.result == 1)
			{
				$("#vname").val(obj.msg);
				$("#part2").show();
			}
			else
			{
				$("#videoUpload").uploadifyClearQueue();
				$("#vname").val('');
				alert(obj.msg);
			}
		},
		'onOpen'			: function(event,ID,fileObj){
			$(".percentage").after('<span id="uploadifyWarning"><br/>Please wait... processing the video</span>');
		}
	});
	
	//latest videos
	$("#slider").easySlider({
		auto		: true,
		continuous	: true,
		prevId		: 'prevBtn',
		prevText	: '',
		nextId		: 'nextBtn',
		nextText	: ''
	});
	
	//modal box and galeries
	$("a[rel^='rules']").prettyPhoto({ie6_fallback: true, social_tools:false, slideshow: false, show_title: true});
	$("a[rel^='videos']").prettyPhoto({ie6_fallback: true, social_tools:false, slideshow: false});
	
});

function validateForm()
{
	$("span.errorIcon").remove();
	var result1 = checkRequireds();
	var result2 = checkEmails();
	var result3 = checkAge();
	if(result1 && result2 && result3)
	{
		return true;
	}
	else
	{
		document.location="#errors";
		return false;
	}
}

function checkRequireds()
{
	requireds=$("input.required");
	var result=true;
	$.each(requireds, function(){
		if($(this).val()=="")	
		{
			$(this).addClass("inputError").after('<span class="errorIcon inputIcon">Please fill this area!</span>');
			$(".inputError").blur(function(){
				$(this).removeClass("inputError").parent().children(".errorIcon").remove();
			});
			result=false;
		}
	});
	return result;
}

function checkEmails()
{
	emails=$("input.email");
	var result=true;
	$.each(emails, function(){
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!filter.test($(this).val()))
		{
			$(this).addClass("inputError").after('<span class="errorIcon inputIcon">Please enter correct email!</span>');
			$(".inputError").blur(function(){
				$(this).removeClass("inputError").parent().children(".errorIcon").remove();
			});
			result=false;
		}
	});
	return result;
}

function checkAge()
{
	$("#ageYear").parent().children('.inputIcon').remove();
	var ageMonth = parseInt($("#ageMonth").val(), 10);
	var ageDay = parseInt($("#ageDay").val(), 10);
	var ageYear = parseInt($("#ageYear").val(), 10);
	
	if((ageDay < 1) || (ageDay > 31) || (ageMonth < 1) || (ageMonth > 12) || (ageYear < 1900) || (ageYear > 2011) || (isNaN(ageDay)) || (isNaN(ageMonth)) || (isNaN(ageYear)))
	{
		$("#ageYear").addClass("inputError").after('<span class="errorIcon inputIcon">Please enter a valid birth date.</span>');
		return false;
	}
	else
	{
		dateNow = new Date();
		birthDate = new Date(ageYear, ageMonth-1, ageDay);
		var diff = 0;
		diff = (dateNow.valueOf()-birthDate.valueOf())/1000/60/60/24;
		
		if(diff < 6574)
		{
			$("#ageYear").addClass("inputError").after('<span class="errorIcon inputIcon">Sorry, you are ineligible to enter.</span>');
			return false;
		}
		else
		{
			$("#ageYear").removeClass("inputError").after('<span class="okIcon inputIcon"></span>');
			return true;
		}
	}
}
