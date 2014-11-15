var counter;
var costDelivery; // сумма доставки
var costPayment; // сумма c оплатой
$(document).ready(function () {

	updateValueItems();
	/***********************************************/
	/*Инкремент товаров*/
	$('.basket').on('click', '.item-inc', function() {
		var count = $(this).prev().val();
		$(this).prev().val(++count);
		updateValueItems();
	});
	/***********************************************/

	/***********************************************/	
	/*Декремент товаров*/	
	$('.basket').on('click', '.item-dec', function() {
		var count = $(this).prev().prev().val();
		if(count > 1) {
			$(this).prev().prev().val(--count);
			updateValueItems();
		}
	});
	/***********************************************/

	/***********************************************/
	/*Сохранить кол-во товаров при наведении фокуса на инпут*/
	$(".basket").on('focus',"input[type='text'].quantity-items" , function() {
		counter = $(this).val();
	});
	/***********************************************/
	/***********************************************/
	/*если введена в поле не цифра, то вернуть предыдущее значение*/
	$('.basket').on('change',"input[type='text'].quantity-items" , function() {
		if(!$.isNumeric($(this).val())) {
			$(this).val(counter);
		} else {
			updateValueItems();
		}
	});
	/********************************************************/
	/********************************************************/
	/*Удаление элементов с корзины*/
	$(".basket").on('click',".delete-item" , function() {
		var quantity = +$(this).parent().prev().find('input').val();
		var price = +$(this).parent().prev().prev().find('span').text();
		
		var totalQuantity = +$(".basket #quantity-all").text();
		var totalPrice = +$(".basket #quantity-money").text();
		$(".basket #quantity-all").text(totalQuantity - quantity);
		$(".basket #quantity-money").text(totalPrice - (price*quantity));
		$(this).parent().parent().fadeOut('fast', function() { 
			$(this).remove();
			if($(".basket tr").length < 1) {
				$(".basket table tbody").append("<tr><td>Корзина пуста</td></tr>");
			}  
		});
	});
	/********************************************************/
	
	/********************************************************/
	/*При оформлении заказа формируютсяid выбранного товара и количество такого товара*/

	$('.item-total form').submit(function() {
		var itemsId = $(this).children()[2];
		var quantityItems = $(this).children()[1];
		var items = $('.basket tr');
		var sumId = '';
		var sumQuant = '';
		for(var i = 0; i < items.length; i++) {
			sumId += $(items[i]).find('#id-item-basket').text() + ' ';
			sumQuant += $(items[i]).find(".items-count input[type='text']").val() + ' ';			
		}

		$(itemsId).val($.trim(sumId));
		$(quantityItems).val($.trim(sumQuant));
	});

});

/**********************************************************************/
/*Расчет кол-ва товаров в корзине и общей цены*/

var updateValueItems = function() {
	var itemsInBasket = $(".basket div.items-count :first-child");
	var quantity = 0;
	var price = 0;

	costDelivery = +$('#delivery #cost-delivery').text();

	for(var i = 0; i < itemsInBasket.length; i++) {
		quantity += +$(itemsInBasket[i]).val();
		price += +$(itemsInBasket[i]).parent().parent().prev().find('span').text() * $(itemsInBasket[i]).val();
	}
	$(".basket #quantity-all").text(quantity);
	$(".basket #quantity-money").text(price + costDelivery);
}
/************************************************************/
