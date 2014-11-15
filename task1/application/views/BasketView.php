<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
  		<meta http-equiv=Content-Type content="text/html">
  		<link href="/task1/src/css/item.css" rel="stylesheet" type="text/css"/>
  		<link href="/task1/src/css/basket.css" rel="stylesheet" type="text/css"/>
		<title>Корзина</title>
	</head>
	<body>
		<div class="basket" style="display:block">
		<h2>Корзина</h2>
		<table>
			<tbody>
				<?php 
					if ($itemsInBasket != '')
					{
						for($i = 0; $i < count($itemsInBasket); $i++)
						{
							echo "<tr> ";
							echo "<td> ";
							echo "<span id='id-item-basket' style='display: none'>".$itemsInBasket[$i]['goods_id']."</span>";
							echo "<img "; 
							echo "src=' ".IMAGE_DIR.$itemsInBasket[$i]['path']." '"; 
							echo "width='100' height='100'"; 
							echo "alt='".$itemsInBasket[$i]['name']."'";  
							echo "> ";
							echo "</td>";
							echo "<td><span style='width:300px; display: block'>".$itemsInBasket[$i]['name']."</span></td>";
							
							$paymentCost = ($payments[$pay][1])? PAYMENT_COST : 0;
							echo "<td><span id='price'>".((int)$itemsInBasket[$i]['price'] + $paymentCost)."</span> грн.</td>";
							

							echo "<td> <div class='items-count'>";
							echo "<input type='text' class='quantity-items' value='".$itemsInBasket[$i]['quantity']."' name='quantity_items'>";
							echo "<div class='item-inc'>/\\</div>";
							echo "<div class='item-dec'>\\/</div>";
							echo "</div></td>";
							echo "<td><div class='delete-item'>X</div></td>"; 
							echo "</tr>";
						}
					}
					
				?>
			</tbody>
		</table>
		
		<div id="delivery">
			<h2>Доставка</h2>
			<select name='city' form="save-form">
				
				<?php 
					for($i = 0; $i < count($cities); $i++) {
						echo "<option value='".$i."'";
						if((int)$delivery_city == $i) {
							echo " selected ";
						}
						if($cities[$i][1]) {
							echo " class='tax' ";
						}

						echo ">".$cities[$i][0]."</option>";
					}
				?>
			</select>
			<div style="clear: right">
				<input type="radio" name="pickup" value="delivery_address" form="save-form" 
				<?php $a = (!strcmp($pickup,"delivery_address"))? "checked": ""; echo $a;?> >
				
				<label for="pickup">Доставка по адресу</label><br/>
   				<input type="radio" name="pickup" value="pickup" form="save-form" 
   				<?php $a = (!strcmp($pickup,"pickup"))? "checked": ""; echo $a;?> >
   				
   				<label for="pickup">Самовывоз</label><br/>
			</div>
			<div>Стоимость доставки:<span id='cost-delivery'>
				<?php 
					$delivery = (!strcmp($pickup,"delivery_address"))? PICKUP_COST: 0;
					$city = ($cities[(int)$delivery_city][1])? DELIVERY_COST: 0;
					echo ($delivery + $city);
				?>
			</span></div>
		</div>
		<div id="payment">
			<h2>Оплата</h2>
			<select name='pay' form="save-form">
				<?php 
					for($i = 0; $i < count($payments); $i++) {
						echo "<option value='".$i."'";
						if((int)$pay == $i) {
							echo " selected ";
						}
						if($payments[$i][1]) {
							echo " class='tax' ";
						}

						echo ">".$payments[$i][0]."</option>";
					}
				?>
			</select>
			<div>Сумма:<span id='cost-payment'>0</span></div>
		</div>

		<div class='item-total'>
			<form action="/task1/index.php" method="POST" id="save-form">
				<span class='info-item'>Всего: <span id='quantity-all'>0</span> товары на <span id='quantity-money'>0</span> грн. </span>
				<input type='hidden' value='' name='quantity_items'>
				<input type='hidden' value='' name='id_items'>
				<input class='button-item' type='submit' name='send' value="Сохранить" id='form-button'>				
			</form>
		</div>

		<form action="/task1/index.php" method="POST" style="clear: both" id="about-user">
			<label for='user_name'>Имя:</label>
				<span class="basket-warning" id="wrong-name"><?php echo form_error('user_name','<span>','</span>');?></span>
			<input type="text" size="40" name="user_name" value=
									<?php 
										$id = $this->session->userdata('user_id');
										$user = $this->ItemModel->getUserInfo($id);
										echo ($this->session->userdata('authorized'))? $user['name']:set_value('user_name');
									?>>
			<label for='user_email'>Электронная почта:</label>
				<span class="basket-warning" id="wrong-email"><?php echo form_error('user_email','<span>','</span>');?></span>
			<input type="text" size="40" name="user_email" value=
										<?php 
											echo ($this->session->userdata('authorized'))? $user['email']:set_value('user_email');
										?>>
			
		
			<label for='user_phone'>Телефон:</label>
				<span class="basket-warning" id="wrong-phone"><?php echo form_error('user_phone','<span>','</span>');?></span>
			<input type="text" size="40" name="user_phone" value=
									<?php 
										echo ($this->session->userdata('authorized'))? $user['phone']:set_value('user_phone');
									?>>
			
			<input type="submit" name="user_send" value="Заказать" disabled>
		</form>

		</div>
		<script type='text/javascript' src="/task1/src/js/jquery-2.1.1.js" charset='utf-8'></script>
  		<script type='text/javascript' src="/task1/src/js/basket.js" charset='utf-8'></script>
  		<script type='text/javascript' src="/task1/src/js/basket_order.js" charset='utf-8'></script>
	</body>
</html>