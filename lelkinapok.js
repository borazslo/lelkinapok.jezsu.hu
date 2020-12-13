
$(function(){
           
            $("h1,h2,h3,h4").append('<i class="fa float-right fa-arrow-circle-up" aria-hidden="true"></i>');
            
            $(".fa-arrow-circle-up").click(function() {            	
            	$(this).parent().next().toggle();
            	$(this).toggleClass("fa-rotate-180");
            });

			 $("body").css({'padding-top': $('nav.navbar').outerHeight()});
            
        });