var counter;
$(document).ready(function () {

	updateValueItems();
	/***********************************************/
	/*Появление корзины (клик по "купить")*/
	$('.item-buy').click(function() {
		$('.basket-item').show(500);
		fillBasket($(this).parent());
	});
	/***********************************************/
	
	/***********************************************/
	/*Когда курсор уводится с корзины, она пропадает*/
	$(".basket-item").mouseleave(function() {
		setTimeout(function() {
			$(".basket-item").slideUp('slow');
		}, 1000);
	});
	/***********************************************/
	/**************************************/
	/***Модальное окно авторизации**/
	$('#authorization').click( function() {
		$('#window-authorization').dialog({
			title: "Авторизация",
			modal: true
		});
	});

	$('#registration-link').click(function() {
		$('#window-authorization').dialog('close');
		$('#window-registration').dialog({
			title: "Регистрация",
			modal: true
		});
	});
});

/***********************************************************/
/*Добавление товара в корзину*/
var fillBasket = function(item) {
	var path = $(item).find('img').attr('src');
	var name = $(item).find('a.item-name').text();
	var price = $(item).find('div.price-item span').text();
	var id = $(item).find('#id-item').text();
	var allId = $('.basket #id-item-basket');
	var flag = true;

	for (var i = 0; i < allId.length; i++) {
		if ($(allId[i]).text() === id) {
			flag = false;
			var old = $(allId[i]).parent().parent().find('.quantity-items').val();
			$(allId[i]).parent().parent().find('.quantity-items').val(++old);
			updateValueItems();
		}
	}
	if(flag)
	{
		$('.basket table tbody').append(" <tr>\
								<td>\
									<span id='id-item-basket' style='display: none'>"+id+"</span>\
									<img src=' " + path + "' \
										 width='100'\
										 height='100'\
										 alt='"+name+"'\
									>\
								</td>\
								<td><span style='width:300px; display: block'>"+name+"</span></td>\
								<td><span>"+price+"</span> \
								грн.</td>\
								<td>\
								<div class='items-count'>\
									<input type='text' \
										   class='quantity-items'\
										   value='1'\
										   name='quantity_items'\
										>\
									<div class='item-inc'>/\\</div>\
									<div class='item-dec'>\\/</div>\
								</div></td>\
								<td><div class='delete-item'>X</div></td> \
								</tr>");
		updateValueItems();
	}
}
/***********************************************************/