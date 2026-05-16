<?php
session_start();
require_once dirname(__DIR__).'/includes/ip_helper.php';
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".$password."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function login2(){
		extract($_POST);
		$identifier = $this->db->real_escape_string($email);
		$pass = $this->db->real_escape_string($password);

		$admin_qry = $this->db->query("SELECT * FROM users where username = '".$identifier."' and password = '".$pass."' ");
		if($admin_qry && $admin_qry->num_rows > 0){
			foreach ($admin_qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			return 4;
		}

		$qry = $this->db->query("SELECT * FROM user_info where email = '".$identifier."' and password = '".md5($password)."' ");
		if($qry && $qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			$ip = $this->db->real_escape_string(get_client_ip());
			$this->db->query("UPDATE cart set user_id = '".$_SESSION['login_user_id']."', client_ip = NULL where client_ip = '".$ip."' ");
			return 1;
		}
		return 3;
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php?page=home");
		exit;
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '$password' ";
		$data .= ", type = '$type' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function signup(){
		extract($_POST);
		$data = " first_name = '$first_name' ";
		$data .= ", last_name = '$last_name' ";
		$data .= ", mobile = '$mobile' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM user_info where email = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO user_info set ".$data);
		if($save){
			$login = $this->login2();
			return 1;
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if(!empty($_FILES['img']['tmp_name'])){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		if(!empty($_FILES['logo']['tmp_name'])){
						$fname = strtotime(date('y-m-d H:i')).'_logo_'.$_FILES['logo']['name'];
						$move = move_uploaded_file($_FILES['logo']['tmp_name'],'../assets/img/'. $fname);
					$data .= ", logo_img = '$fname' ";

		}
		if(!empty($_FILES['logo_dark']['tmp_name'])){
						$fname = strtotime(date('y-m-d H:i')).'_logo_dark_'.$_FILES['logo_dark']['name'];
						$move = move_uploaded_file($_FILES['logo_dark']['tmp_name'],'../assets/img/'. $fname);
					$data .= ", logo_img_dark = '$fname' ";

		}

		$settings_row = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		$gallery = array();
		if(!empty($settings_row['school_gallery'])){
			$decoded = json_decode($settings_row['school_gallery'], true);
			if(is_array($decoded)){
				$gallery = $decoded;
			}
		}
		if(!empty($_POST['remove_gallery']) && is_array($_POST['remove_gallery'])){
			$gallery = array_values(array_diff($gallery, $_POST['remove_gallery']));
		}
		if(!empty($_FILES['school_gallery']['name'][0])){
			$file_count = count($_FILES['school_gallery']['name']);
			for($i = 0; $i < $file_count; $i++){
				if(empty($_FILES['school_gallery']['tmp_name'][$i])){
					continue;
				}
				$safe_name = preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['school_gallery']['name'][$i]);
				$fname = strtotime(date('y-m-d H:i')).'_school_'.$i.'_'.$safe_name;
				if(move_uploaded_file($_FILES['school_gallery']['tmp_name'][$i], '../assets/img/'.$fname)){
					$gallery[] = $fname;
				}
			}
		}
		$gallery_json = $this->db->real_escape_string(json_encode(array_values($gallery)));
		$data .= ", school_gallery = '$gallery_json' ";
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data." where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['setting_'.$key] = $value;
		}

			return 1;
				}
	}

	
	function save_category(){
		extract($_POST);
		$data = " name = '$name' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO category_list set ".$data);
		}else{
			$save = $this->db->query("UPDATE category_list set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM category_list where id = ".$id);
		if($delete)
			return 1;
	}
	function save_menu(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", price = '$price' ";
		$data .= ", category_id = '$category_id' ";
		$data .= ", description = '$description' ";
		if(isset($status) && $status  == 'on')
		$data .= ", status = 1 ";
		else
		$data .= ", status = 0 ";

		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'. $fname);
					$data .= ", img_path = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO product_list set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE product_list set ".$data." where id=".$id);
			if($save)
				return 2;
		}
	}

	function get_menu(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$qry = $this->db->query("SELECT * FROM product_list where id = ".$id);
		if($qry && $qry->num_rows > 0){
			header('Content-Type: application/json');
			echo json_encode($qry->fetch_assoc());
		}
	}

	function delete_menu(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM product_list where id = ".$id);
		if($delete)
			return 1;
	}

	function add_to_cart(){
		extract($_POST);
		$pid = (int)$pid;
		$qty = isset($qty) ? max(1, (int)$qty) : 1;

		if(isset($_SESSION['login_user_id'])){
			$user_id = (int)$_SESSION['login_user_id'];
			$where = " where user_id = '".$user_id."' ";
		}else{
			$ip = $this->db->real_escape_string(get_client_ip());
			$where = " where client_ip = '".$ip."' ";
		}

		$chk = $this->db->query("SELECT id, qty FROM cart ".$where." and product_id = ".$pid." limit 1");
		if($chk && $chk->num_rows > 0){
			$row = $chk->fetch_assoc();
			$new_qty = (int)$row['qty'] + $qty;
			$save = $this->db->query("UPDATE cart set qty = ".$new_qty." where id = ".(int)$row['id']);
		}else{
			$data = " product_id = ".$pid.", qty = ".$qty;
			if(isset($_SESSION['login_user_id'])){
				$data .= ", user_id = ".(int)$_SESSION['login_user_id'].", client_ip = NULL";
			}else{
				$ip = $this->db->real_escape_string(get_client_ip());
				$data .= ", client_ip = '".$ip."', user_id = NULL";
			}
			$save = $this->db->query("INSERT INTO cart set ".$data);
		}
		if($save)
			return 1;
	}
	function get_cart_count(){
		if(isset($_SESSION['login_user_id'])){
			$where =" where user_id = '".(int)$_SESSION['login_user_id']."' ";
		}else{
			$ip = $this->db->real_escape_string(get_client_ip());
			$where =" where client_ip = '".$ip."' ";
		}
		$get = $this->db->query("SELECT COALESCE(SUM(qty), 0) as cart FROM cart ".$where);
		if($get && $get->num_rows > 0){
			return (int)$get->fetch_array()['cart'];
		}
		return 0;
	}

	function update_cart_qty(){
		extract($_POST);
		$data = " qty = $qty ";
		$save = $this->db->query("UPDATE cart set ".$data." where id = ".$id);
		if($save)
		return 1;	
	}

	function delete_cart(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM cart where id = ".$id);
		if($delete)
			return 1;
	}

	function save_order(){
		extract($_POST);
		$data = " name = '".$first_name." ".$last_name."' ";
		$data .= ", address = '$address' ";
		$data .= ", mobile = '$mobile' ";
		$data .= ", email = '$email' ";
		$save = $this->db->query("INSERT INTO orders set ".$data);
		if($save){
			$id = $this->db->insert_id;
			$qry = $this->db->query("SELECT * FROM cart where user_id =".$_SESSION['login_user_id']);
			while($row= $qry->fetch_assoc()){

					$data = " order_id = '$id' ";
					$data .= ", product_id = '".$row['product_id']."' ";
					$data .= ", qty = '".$row['qty']."' ";
					$save2=$this->db->query("INSERT INTO order_list set ".$data);
					if($save2){
						$this->db->query("DELETE FROM cart where id= ".$row['id']);
					}
			}
			return 1;
		}
	}
function confirm_order(){
	extract($_POST);
		$save = $this->db->query("UPDATE orders set status = 1 where id= ".$id);
		if($save)
			return 1;
	}

	function get_pending_orders_count(){
		$qry = $this->db->query("SELECT COUNT(*) as c FROM orders WHERE status = 0 AND archived = 0");
		if($qry && $qry->num_rows > 0){
			return (int)$qry->fetch_assoc()['c'];
		}
		return 0;
	}

	function delete_order(){
		extract($_POST);
		$id = (int)$id;
		$this->db->query("DELETE FROM order_list WHERE order_id = ".$id);
		$delete = $this->db->query("DELETE FROM orders WHERE id = ".$id);
		if($delete)
			return 1;
	}

	function archive_orders(){
		$ids = isset($_POST['ids']) ? $_POST['ids'] : [];
		if(!is_array($ids))
			$ids = [$ids];
		$ids = array_filter(array_map('intval', $ids));
		if(empty($ids))
			return 0;
		$id_list = implode(',', $ids);
		$save = $this->db->query("UPDATE orders SET archived = 1 WHERE id IN (".$id_list.")");
		if($save)
			return 1;
	}

	function unarchive_order(){
		extract($_POST);
		$id = (int)$id;
		$save = $this->db->query("UPDATE orders SET archived = 0 WHERE id = ".$id);
		if($save)
			return 1;
	}

}