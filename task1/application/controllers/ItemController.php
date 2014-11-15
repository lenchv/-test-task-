<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItemController extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model("ItemModel");
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('email');
	}

	public function index()
	{

		if(isset($_POST['authorization_user_enter'])) {
			$this->ItemModel->validAuthorizationForm();
			
			$this->form_validation->run();
			if($this->form_validation->run()){
				$this->session->set_userdata('authorized', true);
				$user_id = $this->ItemModel->getUserId($_POST['authorization_user_email']);
				$this->session->set_userdata('user_id', $user_id);
			}
		}
		if(isset($_POST['registration_user_enter'])) {
			$this->ItemModel->validRegisterForm();
			if($this->form_validation->run()){
				$this->session->set_userdata('authorized', true);
				$user_id = $this->ItemModel->setUser( $_POST['registration_user_email'],
                                                      $_POST['registration_user_name'],
                                                      $_POST['registration_user_phone'],
                                                      $_POST['registration_user_password']
													);
				$this->session->set_userdata('user_id', $user_id);
			}
		} 

		//Сохранение товара корзины в сессии
		if(isset($_POST['save'])) {
			$items = $this->ItemModel->getItems();
			if ($items) {
				$session_data = array(
					'items' => $items
				);
				$this->session->set_userdata($session_data);
			} else {
				$this->session->unset_userdata('items');
			}
		} 
		
		if(isset($_POST['admin_save'])) {		
			$count_items = $_POST['count_items'];

			for($i = 0; $i < $count_items; $i++) {
				if(isset($_POST['status'.$i])) {
					$this->ItemModel->setPurchaceStatus($_POST['id_item'.$i], true);
				} else {
					$this->ItemModel->setPurchaceStatus($_POST['id_item'.$i], false);
				}
			}
			header('Location: /task1/index.php/ItemController/adminPanel');
		} else 
		/*Если нажата кнопка "Заказать"*/
		if(isset($_POST['user_send'])) {
			$this->ItemModel->validOrderForm();
				
			if ($this->form_validation->run()) {
				$this->ItemModel->setPurchace();
				$this->session->unset_userdata('items');
				$this->session->unset_userdata('delivery_city');
				$this->session->unset_userdata('pay');
				$this->session->unset_userdata('pickup');

				if($this->session->userdata('items')) {
					$data['itemsInBasket'] = $this->session->userdata('items');
				}

				$data['query'] = $this->ItemModel->getGoods();
				$this->load->view('ItemView', $data);

			} else {
				$this->basket();
			}
			
		} else 
		/*если нажата кнопка "оформить заказ", то вызывается отображение корзины*/ 
		if(isset($_POST['send'])) {
			$this->basket();
		} else {

			if($this->session->userdata('items')) {
				$data['itemsInBasket'] = $this->session->userdata('items');
			}

			$data['query'] = $this->ItemModel->getGoods();
			$this->load->view('ItemView', $data);
		}
	}


	public function basket() {
		$session_data = '';
		$data['itemsInBasket'] = array();
		if (isset($_POST['id_items']))
		{
			$data['itemsInBasket'] = $this->ItemModel->getItems();
			if ($data['itemsInBasket']) {
				$session_data = array(
					'items' => $data['itemsInBasket']
				);
			} else {
				$this->session->unset_userdata('items');
			}
		} else {
			if($this->session->userdata('items')) {
				$data['itemsInBasket'] = $this->session->userdata('items');
			}
		}

		if(isset($_POST['city'])) {
			$session_data['delivery_city'] = $_POST['city'];
			$data['delivery_city'] = $_POST['city'];
		} else {
			if($this->session->userdata('delivery_city')) {
				$data['delivery_city'] = $this->session->userdata('delivery_city');
			} else {
				$data['delivery_city'] = 0;
			}
		}

		if(isset($_POST['pay'])) {
			$session_data['pay'] = $_POST['pay'];
			$data['pay'] = $_POST['pay'];
		} else {
			if($this->session->userdata('pay')) {
				$data['pay'] = $this->session->userdata('pay');
			} else {
				$data['pay'] = 0;
			}
		}

		if(isset($_POST['pickup'])) {
			$session_data['pickup'] = $_POST['pickup'];
			$data['pickup'] = $_POST['pickup'];
		} else {
			if($this->session->userdata('pickup')) {
				$data['pickup'] = $this->session->userdata('pickup');
			} else {
				$data['pickup'] = 'pickup';
			}
		}
		$this->session->set_userdata($session_data);


		$data['cities'] = $this->ItemModel->getCities();

		$data['payments'] = $this->ItemModel->getPayments();

		$this->load->view('BasketView', $data);
	}

	public function logOut() {
		$this->session->unset_userdata('authorized');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('items');
		$this->session->unset_userdata('delivery_city');
		$this->session->unset_userdata('pay');
		$this->session->unset_userdata('pickup');

		if($this->session->userdata('items')) {
			$data['itemsInBasket'] = $this->session->userdata('items');
		}

		$data['query'] = $this->ItemModel->getGoods();
		$this->load->view('ItemView', $data);
	}

	public function checkEmail() {
		if($this->ItemModel->getUserId($_POST['authorization_user_email'])) {
			return true;
		} else {
			$this->form_validation->set_message('checkEmail', 'Такая почта не зарегестрирована');
			return false;
		}
	}

	public function checkPassword() {
		$pass = md5($_POST['authorization_user_password']);
		$id = $this->ItemModel->getUserId($_POST['authorization_user_email']);
		if(!strcmp($pass, $this->ItemModel->getUserPassword($id))) {
			return true;
		} else {
			$this->form_validation->set_message('checkPassword', 'Неверный пароль');
			return false;
		}
	}

	public function adminPanel() {

		$this->load->view('AdminPanelView');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

