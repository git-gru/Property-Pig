// JavaScript Document

var holder = $('.popup-holder');
var single = $('.popup-single');

$.fn.callpopup = function(closetarget){
	var target = this;
	var close_elem = closetarget;
	holder.show();
	single.each(function(index, element) {
        if($(this).attr('id') != target.attr('id'))
		{
			$(this).hide();
		}
		else
		{
			$(this).show();
		}
    });
	
	$(closetarget).click(function(e) {
        holder.hide();
    });
	
};

// function call example $('#target-id').callpopup();