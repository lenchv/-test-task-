<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItemModel extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	function getGoods() {
		$query = $this->db->get("goods");
		$data = array();
		$i = 0;
		foreach ($query->result() as $row) {
			$data[$i]['id'] = $row->goods_id;
			$data[$i]['name'] = $row->name;
			$data[$i]['price'] = $row->price;
			$data[$i]['path'] = IMAGE_DIR.$row->path;
			$i++;
		}

		return $data;
	}

	function getGoodsById($id) {
		$query = $this->db->query("SELECT * FROM goods WHERE goods_id = ".$id);
		$query = $query->result_array();
		if(empty($query))
		{
			return false;
		} else 
			return $query[0];
	}

	function getCityById($id) {
		$query = $this->db->query("SELECT * FROM delivery_cities WHERE delivery_id = ".$id);
		$query = $query->result_array();
		return $query[0]['delivery_city'];
	}

	function getPaymentById($id) {
		$query = $this->db->query("SELECT * FROM payments WHERE payments_id = ".$id);
		$query = $query->result_array();
		return $query[0]['payment'];
	}

	function validOrderForm() {
		$this->form_validation->set_rules("user_name", "имя пользователя", "trim|required|min_length[3]|max_length[20]|xss_clean");
		$this->form_validation->set_rules("user_email", "электронная почта", "trim|required|valid_email");
		$this->form_validation->set_rules("user_phone", "номер телефона", "trim|required|integer");

		$this->form_validation->set_message('required', 'Требуется указать %s');
		$this->form_validation->set_message('min_length', 'Короткое %s');
		$this->form_validation->set_message('max_length', 'Слишком длиное %s');
		$this->form_validation->set_message('valid_email', 'Неверно указана %s');
		$this->form_validation->set_message('integer', 'Неверно указан %s');
	}

	function validRegisterForm() {
		$this->form_validation->set_rules("registration_user_name", "имя пользователя", "trim|required|min_length[3]|max_length[20]|xss_clean");
		$this->form_validation->set_rules("registration_user_email", "электронная почта", "trim|required|valid_email|is_unique[users.email]");
		$this->form_validation->set_rules("registration_user_password", "пароль", "trim|required|min_length[6]| matches[registration_user_password_repeat]|md5");
		$this->form_validation->set_rules("registration_user_password_repeat", "повторный пароль", "trim|required");
		$this->form_validation->set_rules("registration_user_phone", "номер телефона", "trim|required|integer");


		$this->form_validation->set_message('required', 'Требуется указать %s');
		$this->form_validation->set_message('min_length', 'Короткое %s');
		$this->form_validation->set_message('max_length', 'Слишком длиное %s');
		$this->form_validation->set_message('valid_email', 'Неверно указана %s');
		$this->form_validation->set_message('integer', 'Неверно указан %s');
		$this->form_validation->set_message('is_unique', 'Пользователь с такой почтой уже существует');
	}

	function validAuthorizationForm() {
		$this->form_validation->set_rules("authorization_user_email", "электронная почта", "trim|required|callback_checkEmail");
		$this->form_validation->set_rules("authorization_user_password", "пароль", "trim|required|callback_checkPassword");
		$this->form_validation->set_message('required', 'Требуется указать %s');
	}

	function validCost() {
		$total_cost = 0;
		$pay = $this->session->userdata('pay');
		$delivery = $this->session->userdata('delivery_city');
		$pickup = $this->session->userdata('pickup');
		$items = $this->session->userdata('items');

		$cities = $this->getCities();
		$payments = $this->getPayments();

		$payment_cost = ($payments[$pay][1])? PAYMENT_COST: 0;
		$delivery_cost = ($cities[$delivery][1])? DELIVERY_COST: 0;
		$pickup_cost = (!strcmp($pickup,"delivery_address"))? PICKUP_COST: 0;
		
		foreach($items as $item) {
			$total_cost += ((int)$item['price'] + $payment_cost) * $item['quantity'];
		}		
		$total_cost += $delivery_cost + $pickup_cost;

		return $total_cost;
	}

	function getItems() {
	
		if ($_POST['id_items'] != '') {
			$items =  explode(' ', $_POST['id_items']);
			$quantityItems = explode(' ', $_POST['quantity_items']);
			$rows = array();
			$i = 0;
			foreach($items as $id) {
				$rows[$i] = $this->getGoodsById((int)$id);
				$rows[$i]['total_price'] = $rows[$i]['price'] * (int)$quantityItems[$i];
				$rows[$i]['quantity'] = (int)$quantityItems[$i];
				$i++;
			}
		
			return $rows;
		} else return false;
	}

	function getCities() {
		$cities = $this->db->get('delivery_cities');
		$arr = array();
		$i = 0;
		foreach( $cities->result() as $row) {
			$arr[$i][0] = $row->delivery_city;
			$arr[$i][1] = $row->tax;
			$i++;
		}

		return $arr;
	}

	function getPayments() {
		$payments = $this->db->get('payments');
		$arr = array();
		$i = 0;
		foreach( $payments->result() as $row) {
			$arr[$i][0] = $row->payment;
			$arr[$i][1] = $row->tax;
			$i++;
		}

		return $arr;
	}

	function setPurchace() {
		$items = $this->session->userdata('items');
		$goods_id = array();
		$quantity = array();
		for ($i = 0; $i < count($items); $i++) {
			$goods_id[$i] = $items[$i]['goods_id'];
			$quantity[$i] = $items[$i]['quantity'];
		}

		$goods_id = serialize($goods_id);
		$quantity = serialize($quantity);
		$total_price = $this->validCost();
		$delivery_id = (int)$this->session->userdata('delivery_city');
		$payment_id = (int)$this->session->userdata('pay');
		$pickup = $this->session->userdata('pickup');
		$pickup = (!strcmp($pickup,"delivery_address"))? 1: 0;

		$email = $_POST['user_email'];
		$name = $_POST['user_name'];
		$phone = $_POST['user_phone'];

		$data = array(
			'goods_ids' => $goods_id,
			'quantity' => $quantity,
			'total_price' => $total_price,
			'delivery_id' => $delivery_id,
			'payment_id' => $payment_id,
			'pickup' => $pickup,
			'email' => $email,
			'name' => $name,
			'phone' => $phone
		);

		$str = $this->db->insert_string('info_about_purchases', $data);
		
		if ($this->db->simple_query($str) == false) {
			echo "Error";
		} else {
			$this->sendMail(
				$items,
				$total_price,
				$delivery_id,
				$payment_id,
				$pickup,
				$email,
				$name);
		}
	}

	function getPurchace() {
		$query = $this->db->query("SELECT   info_about_purchases.purchase_id, 
											info_about_purchases.goods_ids,
											info_about_purchases.quantity, 
											info_about_purchases.total_price, 
											info_about_purchases.pickup, 
											info_about_purchases.email, 
											info_about_purchases.name, 
											info_about_purchases.phone, 
											info_about_purchases.status, 
											delivery_cities.delivery_city, 
											delivery_cities.tax AS delivery_tax, 
											payments.payment, 
											payments.tax AS payment_tax
									FROM payments INNER JOIN (
											info_about_purchases INNER JOIN delivery_cities 
											ON info_about_purchases.delivery_id = delivery_cities.delivery_id
										) 
									ON payments.payments_id = info_about_purchases.payment_id");

		$result = array();
		$i = 0;
		foreach($query->result_array() as $row) {
			$result[$i]['id'] = $row['purchase_id'];
			$result[$i]['total_price'] = $row['total_price'];
			$result[$i]['pickup'] = $row['pickup'];
			$result[$i]['city'] = $row['delivery_city'];
			$result[$i]['delivery_tax'] = $row['delivery_tax'];
			$result[$i]['payment'] = $row['payment'];
			$result[$i]['payment_tax'] = $row['payment_tax'];
			$result[$i]['name'] = $row['name'];
			$result[$i]['email'] = $row['email'];
			$result[$i]['phone'] = $row['phone'];
			$result[$i]['status'] = $row['status'];
			$result[$i]['goods'] = array();

			$goods_id = unserialize($row['goods_ids']);
			$quantity = unserialize($row['quantity']);

			for($j = 0; $j < count($goods_id); $j++) {
				$result[$i]['goods'][$j] = $this->getGoodsById((int)$goods_id[$j]);
				$result[$i]['goods'][$j]['quantity'] = $quantity[$j];
			}
			$i++;
		}
		return $result;
	}


	function setPurchaceStatus($id, $status) {
		if($status)
			$data = array('status' => 1);
		else
			$data = array('status' => 0);

		$where = "purchase_id = ".$id; 

		$str = $this->db->update_string('info_about_purchases', $data, $where);
		
		if ($this->db->simple_query($str) == false) {
			echo "Error";
		}
	}

	function sendMail($items, $total_price, $delivery_id, $payment_id, $pickup, $email, $name) {
		$this->email->from('lenchvov@rambler.ru', 'Интернет магазин');
		$this->email->to($email);  

		$this->email->subject('Ваш заказ');

		$style_td = "style='border: solid 1px #DDDDDD; padding: 3px'";
		$begin_message = "<div>Уважаемый, ".$name."! Ваш заказ:</div> <div><table style='border-collapse:collapse; border: 2px white solid;'><tbody>";

		$body_message = "<tr><td ".$style_td.">Имя товара</td><td ".$style_td.">Цена</td><td ".$style_td.">Количество</td></tr>";

		foreach($items as $item) {
			$body_message .= "<tr>";
			$body_message .= "<td ".$style_td.">".$item['name']."</td>";
			$body_message .= "<td ".$style_td.">".$item['price']."</td>";
			$body_message .= "<td ".$style_td.">".$item['quantity']."</td>";
			$body_message .= "</tr>";
		}

		$end_message = "<tbody></table></div>";
		$end_message .= "<div>Доставка в город: ".$this->getCityById($delivery_id)."</div>";
		$end_message .= "<div>Способ оплаты: ".$this->getPaymentById($payment_id)."</div>";
		$end_message .= "<div>Способ доставки: ".(($pickup)? 'Доставка по адресу':'самовывоз')."</div>";
		$end_message .= "<div>Общая сумма: ".$total_price." грн.</div>";


		$this->email->message($begin_message.$body_message.$end_message);	

		$this->email->send();
	}

	function setUser($email, $name, $phone, $password) {
		$data = array(
			'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'password' => $password,
			'admin' => 0
		);
		$str = $this->db->insert_string('users', $data);
		if ($this->db->simple_query($str) == false) {
			return false;
		} else {
			return $this->getUserId($email);
		}
	}

	function getUserId($email) {
		$query = $this->db->query("SELECT user_id FROM users WHERE email = ".$this->db->escape($email));
		$query = $query->result_array();
		
		if(!empty($query))
			return (int)$query[0]['user_id'];
		else 
			return false;
	}

	function getUserName($id) {
		$query = $this->db->query("SELECT name FROM users WHERE user_id = ".(int)$this->db->escape($id));
		$query = $query->result_array();
		if(!empty($query))
			return $query[0]['name'];
		else 
			return false;
	}

	function getUserPassword($id) {
		$query = $this->db->query("SELECT password FROM users WHERE user_id = ".(int)$this->db->escape($id));
		$query = $query->result_array();
		if(!empty($query))
			return $query[0]['password'];
		else 
			return false;
	}

	function isAdmin($id) {
		$query = $this->db->query("SELECT admin FROM users WHERE user_id = ".(int)$this->db->escape($id));
		$query = $query->result_array();
		if(!empty($query))
			return !!$query[0]['admin'];
		else 
			return false;
	}

	function getUserInfo($id) {
		$query = $this->db->query("SELECT * FROM users WHERE user_id = ".(int)$this->db->escape($id));
		$query = $query->result_array();
		if(!empty($query))
			return $query[0];
		else 
			return false; 
	}
}