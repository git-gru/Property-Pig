$.fn.amzing_slide = function(){
	var object = $(this);	
	var count = object.children('.question-single').length;
	var controler = this.siblings('.cotrollsofqus');
	var lastbutton = this.siblings('.last-button-holder');
	var next = controler.children('.next-question');
	var prev = controler.children('.back-question');
	var firstindex = 1;
	var lastindex = count;
	var currentindex = 1;
	
	$(document).ready(function(e) {
        prev.hide();
		object.children('.question-single').each(function(){
			$(this).hide();
		});
		object.children('.question-single:first-child').show();
    });
	currentshow();
	function currentshow()
	{
		controler.children('.question-pagination').html( currentindex +" of "+ lastindex);
	}
	
	next.click(function(){
		
		if(currentindex != lastindex)
		{
			object.children('.question-single').each(function(){
				$(this).hide();
			});
			object.children('.question-single:nth-child('+ (currentindex + 1) +')').show();
			currentindex += 1;
			currentshow();
			if(currentindex == lastindex)
			{
				next.hide();
				lastbutton.children('button').css('display','table');
			}
		}
		prev.show();
	});
	
	
	prev.click(function(){
		
		if(currentindex != firstindex)
		{
			object.children('.question-single').each(function(){
				$(this).hide();
			});
			object.children('.question-single:nth-child('+ (currentindex - 1) +')').show();
			currentindex -= 1;
			currentshow();
			if(currentindex == firstindex)
			{
				prev.hide();
			}
		}
		
		next.show();
		lastbutton.children('button').css('display','none');
	});
	
	
}