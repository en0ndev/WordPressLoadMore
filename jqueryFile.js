(function($){
	"use strict";

	var addNum     = 0;
	var getChoose  = 5; // one click to load 5 post 
	var clicked    = false;
	var readyCount = false;

	$(".load__more__button").click(function() {
		if (!clicked) {
			$('.load__more__button').text('Loading...');
			if (readyCount == true) {
				addNum = addNum + getChoose;
			}
			readyCount = true;
			$.post(my__params.ajaxurl,
			{
            	'action': 'my__load__more', // function name in functionFile.php
            	'addNum': addNum,
            	'getChoose': getChoose,
            	'varcat': my__params.varcat,
            	'varaut': my__params.varaut,
            	'vartag': my__params.vartag,
            	'varyear': my__params.varyear,
            	'varmon': my__params.varmon,
            	'varday': my__params.varday,
            },
            function(response) {
            	var posts = JSON.parse(response);

            	for( var i = 0; i < posts.length; i++ )
            	{
						if( posts[i] == false ) // "0" can cause some problems
							$("#load_more").fadeOut();
						else
							$("#load_more").before(posts[i]);
						$('#load_more').text('Load More');

					}
				});
			$(document).ajaxStop(function () {
				clicked = false;
			});
			clicked = true;
		}
	});

}(jQuery));
