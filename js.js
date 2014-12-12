jQuery(function($) {
    $('.voteRatings a img').hover(function() {
        $(this).css("opacity", "1");
    }, function() {
        $(this).css("opacity", "0.5");
    });

    $('.voteRatings a').click(function(e) {
        e.preventDefault();
        $(".voteRatings").animate({opacity:0},"fast");
        $(".ratingsContainer").animate({opacity:0},"fast");
        var data = {
		'action': 'saveRating',               
		'ratingId': $(this).find("img").attr("ratingid"),
                'blogId': ajax_object.blogId
	   };
	
        jQuery.post(ajax_object.ajax_url, data, function(response) {		            
            $('.ratingsContainer').html(response);            
            $('.ratingsContainer').fadeTo("fast",1);  
        });
    })
});