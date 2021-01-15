	$(document).ready(function ()  {

		var addNum = 0;
		var getChoose = 5; /* one click to load 5 post */
		var is_clicked = false;
		var clicked = false;

		$("#load_more").click(function() {

			if (!clicked) {

				$('#load_more').text('Loading...');

				if ( is_clicked == true ) {

					addNum = addNum + getChoose;

				}

				is_clicked = true;

				$.post(ajaxurl,
				{

					'action': 'your_load_more', /* function name in functionFile.php */
					'addNum': addNum,
					'getChoose': getChoose,
					
				},

				function(response)

				{

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

			}});
	});