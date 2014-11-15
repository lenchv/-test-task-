<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
  	<meta http-equiv=Content-Type content="text/html">
  	<link href="/task1/src/css/item.css" rel="stylesheet" type="text/css"/>
  	<link href="/task1/src/css/basket.css" rel="stylesheet" type="text/css"/>
  	<link href="/task1/src/css/basket.css" rel="stylesheet" type="text/css"/>
  	<link href="/task1/src/css/cupertino/jquery-ui-1.9.2.custom.css" rel="stylesheet" type="text/css" />
 	<title>Test</title>
</head>
<body>
	<div id='window-authorization' style="display: none">
		<form action="/task1/index.php" method="POST">
			<span class="basket-warning">
					<?php echo form_error('authorization_user_email','<span>','</span>');?>
				</span><br/>
			<label for='authorization_user_email'>����������� �����:</label><br/><input type='text' name='authorization_user_email'><br/>
			<label for='authorization_user_password'>������:</label></br>
				<span class="basket-warning">
					<?php echo form_error('authorization_user_password','<span>','</span>');?>
				</span><br/>
			<input type='password' name='authorization_user_password'><br/><br/>
			<input type='submit' name='authorization_user_enter' value='�����'>
			<a href='#' id='registration-link'>�����������</a>
		</form>
	</div>
	<div id='window-registration' style="display: none">
		<form action="/task1/index.php" method="POST">
			<label for='registration_user_name'>��� ������������:</label>
				<span class="basket-warning" id="wrong-name">
					<?php echo form_error('registration_user_name','<span>','</span>');?>
				</span><br/>
				<input type='text' name='registration_user_name' value=<?php echo set_value('registration_user_name');?>><br/><br/>
			<label for='registration_user_email'>����������� �����:</label>
				<span class="basket-warning" id="wrong-email">
					<?php echo form_error('registration_user_email','<span>','</span>');?>
				</span><br/>
				<input type='text' name='registration_user_email' value=<?php echo set_value('registration_user_email');?>><br/><br/>

			<label for='registration_user_phone'>�������:</label>
				<span class="basket-warning" id="wrong-phone">
					<?php echo form_error('registration_user_phone','<span>','</span>');?>
				</span><br/>
				<input type='text' name='registration_user_phone' value=<?php echo set_value('registration_user_phone');?>><br/><br/>

			<label for='registration_user_password'>������:</label>
				<span class="basket-warning" id="wrong-password">
					<?php echo form_error('registration_user_password','<span>','</span>');?>
				</span><br/>
				<input type='password' name='registration_user_password'><br/></br>
			<label for='registration_user_password_repeat'>��������� ������:</label>
				<span class="basket-warning" id="wrong-password-repeat">
					<?php echo form_error('registration_user_password_repeat','<span>','</span>');?>
				</span><br/>
				<input type='password' name='registration_user_password_repeat'><br/><br/>
			<input type='submit' name='registration_user_enter' value='������������������'>
		</form>
	</div>
	<?php
		if($this->session->userdata('authorized')) {
			echo "<span>".$this->ItemModel->getUserName($this->session->userdata('user_id'))."</span>";
			echo "<a href='/task1/index.php/ItemController/logOut' >�����</a>";
			echo "<a href='/task1/index.php/ItemController/adminPanel' >����� ������</a>";
		}
		else {
			echo "<a href='#' id='authorization'>�����������</a>";
		}
	?>
	<?php
		foreach ($query as $row) {
			echo "<div class='item'>\n";
			echo "<p style='text-align: center; margin: 0; padding-top: 10px'>\n";
			echo "<img src='".$row['path']."' width='200' height='200' alt='".$row['name']."'/>\n";
			echo "</p>\n";
			echo "<a class='item-name' href='#'>".$row['name']."</a>\n";
			echo "<div class='price-item'><span>".$row['price']."</span> ���.</div>\n";
			echo "<a class='item-buy' href='#'>������</a>\n";
			echo "<span style='display:none' id='id-item'>".$row['id']."</span>";
			echo "</div>\n";
		}
	?>
	<div class="basket">
	<div class="basket-item">
		<h2>�������</h2>
		<div class="scroll-basket">
		<table>
			<tbody>
				<?php 
					if (isset($itemsInBasket))
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
							echo "<td><span>".$itemsInBasket[$i]['price']."</span> ���.</td>";
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
		</div>
		
		<div class='item-total'>
			<form action="/task1/index.php" method="POST">
				<span class='info-item'>�����: <span id='quantity-all'>0</span> ������ �� <span id='quantity-money'>0</span> ���. </span>
				<input type='hidden' value='' name='quantity_items'>
				<input type='hidden' value='' name='id_items'>
				<input type='submit' name='save' value="���������" id='form-button'>
				<input class='button-item' type='submit' name='send' value="�������� �����" id='form-button'>

			</form>
		</div>
	</div>
	</div>

	<script type='text/javascript' src="/task1/src/js/jquery-2.1.1.js" charset='utf-8'></script>
	<script type="text/javascript" src="/task1/src/js/jquery-ui-1.9.2.custom.js"></script>
  	<script type='text/javascript' src="/task1/src/js/script.js" charset='utf-8'></script>
  	<script type='text/javascript' src="/task1/src/js/basket.js" charset='utf-8'></script>
</body>
</html>