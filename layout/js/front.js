$(function () {
	'use strict';

	// switch login & signup
	$('.login-page h1 span').click(function () {
		$(this).addClass('selected').siblings().removeClass('selected');
		$('.login-page form').hide();
		$('.' + $(this).data('class')).fadeIn(100);
	});

	// trigger selectboxit
	$("select").selectBoxIt({
		autoWidth: false
	});

	// hide placeholder on form focus event
	$('[placeholder]').focus(function () {
		$(this).attr('data-text', $(this).attr('placeholder'));
		$(this).attr('placeholder', '');
	}).blur(function () {
		$(this).attr('placeholder', $(this).attr('data-text'));

	});

	// add asterisk on required field
	$('input').each(function () {
		if ($(this).attr('required') === 'required') {
			$(this).after('<span class="asterisk">*</span>');
		}
	});

	// confirmation message on click
	$('.confirm').click(function () {
		return confirm('Are You Sure?');
	});

	$('.live').keyup(function () {
		$($(this).data('class')).text($(this).val());
	});

	// card.php

	$("input[value='Update']").click(function(){
			let total = 0;
			$("span#product-price").each(function(){
					total += +$(this).text();
					$("#total-price").text(total);
			})
	})
	$("input[name=qan]").change(function(){
		let qan = $(this).val()
		const index = $("a.target-btn").prop("href").lastIndexOf("=")+1
		let link = $("a.target-btn").prop("href")
		let newLink = link.slice(0,index)
		$("a.target-btn").prop("href", newLink + String(qan))

	})

	

});

