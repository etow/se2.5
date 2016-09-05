$(document).ready(function(){
		$('.wItem--radioImage').click(function(){
				$(this).addClass("is-active");
				$(this).siblings().removeClass("is-active");
		});
		$('.wSection').each(function(i,v){
			var scope = v;
			$(v).find('input[type="radio"]').each(function(i,v){
					if($(v).prop('checked')){
						var elemento = $(scope).find('.wItem--radioImage')[i];
						$(elemento).addClass('is-active');
					}
			});
		});
		$('.wSwitch').click(function() {
				$(this).toggleClass('is-on');
		});
		$('input[type="checkbox"]:checked').each(function(){
			var id = $(this).attr("id");
			$('label[for="'+id+'"]').parent().addClass('is-on');
		});
		$('.wTabs-link').click(function(){
			$(this).siblings().removeClass('is-active');
			$(this).addClass('is-active');
			var target = $(this).attr('data-wTabs-page');
			$('.wTabs-page').hide();
			$('#wTabs-page-'+target).fadeIn();
		});
			

	});