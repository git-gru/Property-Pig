$.fn.amazing_select = function(){
    var optionsofselect = this;
    optionsofselect.children('option').each(function(){
		
		
		
		//$(this).attr('val', $(this).html());
	});
	var noofoption = optionsofselect.children('option').length;
	var targetid = optionsofselect.attr('id') + "-ul";
	var selectli = "";
	for(i=1; i <= noofoption; i++)
	{
		var valueofoption = optionsofselect.children('option:nth-child('+ i +')').val();
		selectli += "<li val='"+ valueofoption +"'>"+ valueofoption +"</li>";
	}
	
	var selectul = "<div class='amazing-select-holder "+ optionsofselect.attr('id') +"-holder'><label id="+ targetid +"-label class='amazing-select-label'> " + optionsofselect.children('option:nth-child('+ 1 +')').val() + " </label><ul id="+ targetid  +" class='amazing-select-ul'>"+ selectli +"</ul></div>";

	optionsofselect.after(selectul);
	optionsofselect.hide();
	
	var labelid = "#" + targetid + "-label";
	var ulid = "#" + targetid;
	
	$(labelid).click(function(e) {
        $(this).siblings('ul').slideToggle('fast');
		$(this).toggleClass('active');
    });
	
	$(ulid).children('li').click(function(e) {
        $(labelid).html( $(this).html() );
		optionsofselect.val($(this).html());
		$(ulid).slideUp('fast');
		$(labelid).removeClass('active');
    });
	
	$(document).mouseup(function (e)
	{
		var container = $(ulid).parent('div');
	
		if (!container.is(e.target) 
			&& container.has(e.target).length === 0)
		{
		   $(ulid).slideUp('fast');
		   $(labelid).removeClass('active');
		}
	});
	
}