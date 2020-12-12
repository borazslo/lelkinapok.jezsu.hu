
$(function(){


    	if(window.location.hash) {
      		var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
      		
      		if(hash == 'haromtortenet') {
      			$('.stories .card-body').show();
      		}
      	// hash found
  		} 
    	

  
            $(".organizerTip").addClass("alert alert-warning");
            $(".leaderTip").addClass("alert alert-info");
            $(".leaderTip").after("<div class='buttonTips alert alert-info float-right'>!</div>");
         
            $(".source").addClass("alert alert-success");
            
            
            $("h1,h2,h3,h4").append('<i class="fa float-right fa-arrow-circle-up" aria-hidden="true"></i>');
            
            //Alapértelmezetten eltakartak se legenek most eltakartak ezért ki kommentálva
            //$("h1.nextHidden,h2.nextHidden,h3.nextHidden").children(".fa").addClass("fa-rotate-180");
            //$("h1.nextHidden,h2.nextHidden,h3.nextHidden").next().hide();

            $(".buttonTips").hide();
            $(".organizer").hide();	
            //$(".organizerTip").hide();
            $(".time").hide();
            //$(".source").hide();


            $(".fa-arrow-circle-up").click(function() {            	
            	$(this).parent().next().toggle();
            	$(this).toggleClass("fa-rotate-180");
            });

            $("#buttonOrganizer").click(function () {
            	if ($(this).hasClass('disabled')) {
					$("#buttonOrganizer").removeClass("disabled");

            	} else {
            		$("#buttonOrganizer").addClass("disabled");
            	}            	
            	$(".organizerTip").toggle();	
            });

            $("#buttonOpenAll").click(function () {
            	$(".fa-arrow-circle-up").removeClass("fa-rotate-180");
            	$(".fa-arrow-circle-up").removeClass("fa-arrow-circle-down");
            	$(".fa-arrow-circle-up").addClass("fa-arrow-circle-up");
            	$(".fa-arrow-circle-up").parent().next().show();
            });



            $("#buttonLeader").click(function () {
            	if ($(this).hasClass('disabled')) {
					$("#buttonLeader").removeClass("disabled");
            	} else {
            		$("#buttonLeader").addClass("disabled");
            	}            	
            	$(".leader").toggle();	
            });

			$("#buttonTips").click(function () {
            	if ($("#buttonTips").hasClass('disabled')) {
            		$("#buttonTips").not(".leader").removeClass("disabled");	
            		$(".leaderTip").show();	            	
            		$(".buttonTips").hide();	            	
            	} else {
            		$("#buttonTips").addClass("disabled");
            		$(".leaderTip").hide();            		
            		$(".buttonTips").show();	            	
            	}            	            	
            });

			$(".leaderTip").click(function () { 
				$(this).next().toggle();
				$(this).toggle();
			});
			$(".buttonTips").click(function () { 
				$(this).prev().toggle();
				$(this).toggle();
			});


			 $("body").css({'padding-top': $('nav.navbar').outerHeight()});
			 /* $("h1, h2, h3, h4").css({'padding-top': $('nav.navbar').outerHeight()}); */
            
        });