var taxDelivery = 73;
var taxPayment;
var pickupDelivery = 25;

var isUserName = true;
var isUserEmail = true;
var isUserPhone = true;
$(document).ready(function() {
	
	$('#payment #cost-payment').text( $('.item-total #quantity-money').text() );
	if ($('#payment select option:selected').hasClass('tax')) {
		taxPayment = 0;
	} else {
		taxPayment = 100;
	}	


	$('#delivery select').change(function() {
		if($(this).find('option:selected').hasClass('tax')) {
			$('#delivery #cost-delivery').text( taxDelivery );
		} else {
			$('#delivery #cost-delivery').text(0);
		}
		var cost = +$('#delivery #cost-delivery').text();
		
		if(!!$("#delivery input[value='delivery_address']:checked").val()) {
			$('#delivery #cost-delivery').text(pickupDelivery + cost);
		}

		updateValueItems();
	});

	$("#delivery input[type='radio']").change(function() {
		var cost = +$('#delivery #cost-delivery').text();
		if($("#delivery input[type='radio']:checked").val() === 'delivery_address') {
			$('#delivery #cost-delivery').text( cost + pickupDelivery);
		} else {
			$('#delivery #cost-delivery').text( cost - pickupDelivery);
		}
		updateValueItems();
	});

	$('#payment select').change(function() {
		var priceItems = $(".basket #price");
		if($(this).find('option:selected').hasClass('tax')) {
			for (i = 0; i < priceItems.length; i++) {
				var currentPrice = +$(priceItems[i]).text(); 
				$(priceItems[i]).text(currentPrice + taxPayment);  
			}
			taxPayment = 0;
		} else {
			if(!taxPayment) {
				taxPayment = 100;
				for (i = 0; i < priceItems.length; i++) {
					var currentPrice = +$(priceItems[i]).text(); 
					$(priceItems[i]).text(currentPrice - taxPayment);  
				}
			}
		}

		updateValueItems();
		$('#payment #cost-payment').text( $('.item-total #quantity-money').text() );
		
	});

	$("form#about-user input[name='user_name']").keyup(function() {
		if($(this).val() !== '') {
			$("#wrong-name").text('поле имени не должно быть пустым');
			$("#wrong-name").fadeOut('fast');
			isUserName = true;
			enableOrderButton();
		} else {
			$("#wrong-name").fadeIn('fast');
			disableOrderButton();
			isUserName = false;
		}
	});
	
	$("form#about-user input[name='user_email']").keyup(function() {
		var reg =  /[a-z0-9!$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum|ua|com.ua|ru|com.ru)\b/;		
		if($(this).val() !== '') {
			if(!$(this).val().match(reg)) {
				$("#wrong-email").text("Введите корректную почту");
				$("#wrong-email").fadeIn('fast');
				disableOrderButton();
				isUserEmail = false;
			} else {
				$("#wrong-email").fadeOut('fast');
				isUserEmail = true;
				enableOrderButton();
			}

		} else {
			$("#wrong-email").text("поле почты не должно быть пустым");
			$("#wrong-email").fadeIn('fast');
			disableOrderButton();
			isUserEmail = false;
		} 
	});

	$("form#about-user input[name='user_phone']").keyup(function() {
		var reg = /^[0-9]*$/;
		if($(this).val() !== '') {
			if(!$(this).val().match(reg)) {
				$("#wrong-phone").text("Введите корректный номер телефона");
				$("#wrong-phone").fadeIn('fast');
				disableOrderButton();
				isUserPhone = false;
			} else {
				if($(this).val().length > 12) {
					$("#wrong-phone").text("Слишком длинный номер телефона");
					$("#wrong-phone").fadeIn('fast');
					disableOrderButton();
					isUserPhone = false;
				} else {
					$("#wrong-phone").fadeOut('fast');
					isUserPhone = true;
					enableOrderButton();
				}
			}
		} else {
			$("#wrong-phone").text("поле номера телефона не должно быть пустым");
			$("#wrong-phone").fadeIn('fast');
			disableOrderButton();
			isUserPhone = false;
		}
	});

	$("form#about-user input[name='user_phone']").change( function() {
		$(this).keyup();
	});

	$("form#about-user input[name='user_email']").change(function() {
		$(this).keyup();
	});
	
	$("form#about-user input[name='user_name']").change(function() {
		$(this).keyup();
	});

	$("form#about-user input[name='user_phone']").keyup();
	$("form#about-user input[name='user_email']").keyup();
	$("form#about-user input[name='user_name']").keyup();
});

var disableOrderButton = function() {
	$("form#about-user input[type='submit']").prop('disabled', true);
}

var enableOrderButton = function() {
	if (isUserPhone && isUserEmail && isUserName) {
		$("form#about-user input[type='submit']").prop('disabled', false);
	}
}