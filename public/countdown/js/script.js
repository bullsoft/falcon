
$(document).ready(function(){

	$('header h1').hover(
		function(){ $('header h1').animate({ top: 5 }, 'fast'); },
		function(){ $('header h1').animate({ top: 0 }, 'fast'); }
	);

	/* ---- Countdown timer ---- */
	$('#counter').countdown({
		// timestamp : (new Date()).getTime() + 10*24*60*60*1000
		timestamp : (new Date("2014/04/15 00:00:00")).getTime()
	});


	$('.email').focus(function() {
		if($(this).val() == 'enter your email...') {
			$(this).val('');
		}
	});

	$('.email').blur(function() {
		if($(this).val() == '') {
			$(this).val('enter your email...');
		}
	});



	$('#follow_us a').hover(
		function(){ $(this).animate({ top: 5 }, 'fast'); },
		function(){ $(this).animate({ top: 0 }, 'fast'); }
	);

	$('.footer-orange').hover(
		function(){ $('.footer-orange').animate({ top: 5 }, 'fast'); },
		function(){ $('.footer-orange').animate({ top: 0 }, 'fast'); }
	);


});

