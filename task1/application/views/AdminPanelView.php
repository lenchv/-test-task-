<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv=Content-Type content="text/html">
	<title>Админ панель</title>
	<link href='/task1/src/css/admin_panel.css' type='text/css' rel='stylesheet'>
</head>
<body>
	<table>
	</tbody>
	<?php 
		if($this->session->userdata('authorized')) {
			$purchace = 0;
			$id = $this->session->userdata('user_id');
			if($this->ItemModel->isAdmin($id)) {
				$purchace = $this->ItemModel->getPurchace();
				echo "<tr>";
				echo "<td rowspan='2'>id</td>";
				echo "<td colspan='5'>Товар</td>";
				echo "<td rowspan='2'>Общая сумма</td>";
				echo "<td rowspan='2'>Доставка</td>";
				echo "<td rowspan='2'>Город</td>";
				echo "<td rowspan='2'>Стоимость доставки (грн.)</td>";
				echo "<td rowspan='2'>Оплата</td>";
				echo "<td rowspan='2'>Налог (грн.)</td>";
				echo "<td rowspan='2'>Имя заказчика</td>";
				echo "<td rowspan='2'>Почта заказчика</td>";
				echo "<td rowspan='2'>Телефон заказчика</td>";
				echo "<td rowspan='2'>Статус</td>";
				echo "</tr>";
				echo "<tr>
						<td width='63px'>Код товара</td>
						<td>Изображени</td>
						<td>Название</td>
						<td>Сумма</td>
						<td>Количество</td>
					 </tr>";


				for($i = 0; $i < count($purchace); $i++) {
					$check = (!!$purchace[$i]['status'])? 'check':'uncheck';
					echo "<tr class='".$check."' >";
					echo "<td>".$purchace[$i]['id']." <input type='hidden' value=".$purchace[$i]['id']." name='id_item".$i."' form='form-save'></td>";

					echo "<td colspan='5'>";
					echo "<div style='overflow:auto; height: 60px; width: 400px'>";
					foreach( $purchace[$i]['goods'] as $item) {
						echo "<div style='width:400px'>";
							echo "<div class='cell-item cell-short'>".$item['goods_id']."</div>";
							echo "<div class='cell-item'>
									<img src='".IMAGE_DIR.$item['path']."' alt='".$item['name']."' width='50' height='50'/>
								</div>";
							echo "<div class='cell-item cell-name'>".$item['name']."</div>";
							echo "<div class='cell-item cell-short'>".$item['price']."</div>";
							echo "<div class='cell-item last-cell cell-short'>".$item['quantity']."</div>";
						echo "</div>";
					}
					echo "</div>";
					echo "</td>";
					echo "<td>".$purchace[$i]['total_price']."</td>";
					echo "<td>".((!!$purchace[$i]['pickup'])? 'доставка по адресу(25 грн.)': 'самовывоз')."</td>";
					echo "<td>".$purchace[$i]['city']."</td>";
					echo "<td>".((!!$purchace[$i]['delivery_tax'])? DELIVERY_COST : 0)."</td>";
					echo "<td>".$purchace[$i]['payment']."</td>";
					echo "<td>".((!!$purchace[$i]['payment_tax'])? PAYMENT_COST : 0)."</td>";
					echo "<td>".$purchace[$i]['name']."</td>";
					echo "<td>".$purchace[$i]['email']."</td>";
					echo "<td>".$purchace[$i]['phone']."</td>";
					$status = (!!$purchace[$i]['status'])? 'checked':'';
					echo "<td><input type='checkbox' name='status".$i."' form='form-save'".$status."></td>";
					echo "</tr>";
				}
				echo "</tbody></table>";
				echo "<form action='/task1/index.php' method='POST' id='form-save'>";
				
				echo "<input type='hidden' name='count_items' value='".count($purchace)."'>";
		
				echo "<input type='submit' name='admin_save' value='Сохранить'>";
				echo "</form>";
			}else {
			echo '<div>У Вас недостаточно прав для просмотра этой страницы.</div>';
		}
		} 
	?>
	
	
	<a href='/task1/index.php'>На главную</a>
	<script type='text/javascript' src="/task1/src/js/jquery-2.1.1.js" charset='utf-8'></script>
	<script type='text/javascript' src="/task1/src/js/admin_panel.js" charset='utf-8'></script>
</body>
</html>