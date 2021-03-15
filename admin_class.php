<?php
session_start();
ini_set('display_errors', 1);
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
		$qry = $this->db->query("SELECT * FROM user_info where email = '".$email."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
			$this->db->query("UPDATE cart set user_id = '".$_SESSION['login_user_id']."' where client_ip ='$ip' ");
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
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
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
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
	function save_supplier(){
		extract($_POST);
		if (isset($supply))	array_push($supply, $supply1);
		else $supply = array($supply1);
		$supply2 = array();
		foreach(array_unique($supply) as $k=>$v)
			array_push($supply2, $v);
		$supplya = json_encode($supply2);
		$data = " supplier_name = '$name' ";
		$data .= ", supply = '$supplya' ";
		$data .= ", contact = '$contact' ";
		$data .= ", qemail = '$qemail' ";
		$data .= ", address = '$address' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO supplier_list set ".$data);
		}else{
			$save = $this->db->query("UPDATE supplier_list set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_supplier(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM supplier_list where id = ".$id);
		if($delete)
			return 1;
	}
	function save_product(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", sku = '$sku' ";
		$data .= ", category_id = '$category_id' ";
		$data .= ", description = '$description' ";
		$data .= ", price = '$price' ";  
		
		if(isset($_FILES["uploadfile"]["name"])) {
			$filename = $_FILES["uploadfile"]["name"];
			$tempname = $_FILES["uploadfile"]["tmp_name"]; 
			$folder = "assets/product_images/".$filename; 
			$data .= ", photo = '$filename' ";
			
			if (move_uploaded_file($tempname, $folder)) 
				$dd2 = false; 
			else 
				$dd2 = true; 
		}
		if (!empty($uploadfile2) && $dd2)
			$data .= ", photo = '$uploadfile2' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO product_list set ".$data);
		}else{
			$save = $this->db->query("UPDATE product_list set ".$data." where id=".$id);
		}
		if($save && $dd2)
			return 3;
		else if ($save)
			return 1;
	}

	function delete_product(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM product_list where id = ".$id);
		if($delete)
			return 1;
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}

	function save_receiving(){
		extract($_POST);
		$data = " supplier_id = '$supplier_id' ";
		$data .= ", total_amount = '$tamount' ";
		
		if(empty($id)){
			$ref_no = sprintf("%'.08d\n", $ref_no);
			$i = 1;

			while($i == 1){
				$chk = $this->db->query("SELECT * FROM receiving_list where ref_no ='$ref_no'")->num_rows;
				if($chk > 0){
					$ref_no = mt_rand(1,99999999);
					$ref_no = sprintf("%'.08d\n", $ref_no);
				}else{
					$i=0;
				}
			}
			$data .= ", ref_no = '$ref_no' ";
			$save = $this->db->query("INSERT INTO receiving_list set ".$data);
			$id =$this->db->insert_id;
			foreach($product_id as $k => $v){
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '1' ";
				$data .= ", stock_from = 'receiving' ";
				$details = json_encode(array('price'=>$price[$k],'qty'=>$qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock from Receiving-".$ref_no."' ";

				$save2[]= $this->db->query("INSERT INTO inventory set ".$data);
			}
			if(isset($save2)){
				return 1;
			}
		}else{
			$save = $this->db->query("UPDATE receiving_list set ".$data." where id =".$id);
			$ids = implode(",",$inv_id);
			$this->db->query("DELETE FROM inventory where type = 1 and form_id ='$id' and id NOT IN (".$ids.") ");
			foreach($product_id as $k => $v){
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '1' ";
				$data .= ", stock_from = 'receiving' ";
				$details = json_encode(array('price'=>$price[$k],'qty'=>$qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock from Receiving-".$ref_no."' ";
				if(!empty($inv_id[$k])){
									$save2[]= $this->db->query("UPDATE inventory set ".$data." where id=".$inv_id[$k]);
				}else{
					$save2[]= $this->db->query("INSERT INTO inventory set ".$data);
				}
			}
			if(isset($save2)){
				
				return 1;
			}

		}
	}

	function delete_receiving(){
		extract($_POST);
		$del1 = $this->db->query("DELETE FROM receiving_list where id = $id ");
		$del2 = $this->db->query("DELETE FROM inventory where type = 1 and form_id = $id ");
		if($del1 && $del2)
			return 1;
	}
	function save_customer(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO customer_list set ".$data);
		}else{
			$save = $this->db->query("UPDATE customer_list set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_customer(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM customer_list where id = ".$id);
		if($delete)
			return 1;
	}

	function chk_prod_availability(){
		extract($_POST);
		$price = $this->db->query("SELECT * FROM product_list where id = ".$id)->fetch_assoc()['price'];
		$inn = $this->db->query("SELECT sum(qty) as inn FROM inventory where type = 1 and product_id = ".$id);
		$inn = $inn && $inn->num_rows > 0 ? $inn->fetch_array()['inn'] : 0;
		$out = $this->db->query("SELECT sum(qty) as `out` FROM inventory where type = 2 and product_id = ".$id);
		$out = $out && $out->num_rows > 0 ? $out->fetch_array()['out'] : 0;
		$available = $inn - $out;
		return json_encode(array('available'=>$available,'price'=>$price));

	}
	function save_sales(){
		extract($_POST);
		$data = " customer_id = '$customer_id' ";
		$data .= ", sellers_name = '$sellers_name' ";
		$data .= ", total_amount = '$tamount' ";
		$data .= ", amount_tendered = '$amount_tendered' ";
		$data .= ", amount_change = '$change' ";
		//$data .= ", user_id = '$user_id' ";
		
		if(empty($id)){
			$ref_no = sprintf("%'.08d\n", $ref_no);
			$i = 1;

			while($i == 1){
				$chk = $this->db->query("SELECT * FROM sales_list where ref_no ='$ref_no'")->num_rows;
				if($chk > 0){
					$ref_no = mt_rand(1,99999999);
					$ref_no = sprintf("%'.08d\n", $ref_no);
				}else{
					$i=0;
				}
			}
			$data .= ", ref_no = '$ref_no' ";
			$save = $this->db->query("INSERT INTO sales_list set ".$data);
			$id =$this->db->insert_id;
			foreach($product_id as $k => $v){
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '2' ";
				$data .= ", stock_from = 'Sales' ";
				$details = json_encode(array('price'=>$price[$k],'qty'=>$qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock out from Sales-".$ref_no."' ";

				$save2[]= $this->db->query("INSERT INTO inventory set ".$data);
			}
			if(isset($save2)){
				return $id;
			}
		}else{
			$save = $this->db->query("UPDATE sales_list set ".$data." where id=".$id);
			$ids = implode(",",$inv_id);
			$this->db->query("DELETE FROM inventory where type = 1 and form_id ='$id' and id NOT IN (".$ids.") ");
			foreach($product_id as $k => $v){
				$data = " form_id = '$id' ";
				$data .= ", product_id = '$product_id[$k]' ";
				$data .= ", qty = '$qty[$k]' ";
				$data .= ", type = '2' ";
				$data .= ", stock_from = 'Sales' ";
				$details = json_encode(array('price'=>$price[$k],'qty'=>$qty[$k]));
				$data .= ", other_details = '$details' ";
				$data .= ", remarks = 'Stock out from Sales-".$ref_no."' ";

				if(!empty($inv_id[$k])){
					$save2[]= $this->db->query("UPDATE inventory set ".$data." where id=".$inv_id[$k]);
				}else{
					$save2[]= $this->db->query("INSERT INTO inventory set ".$data);
				}
			}
			if(isset($save2)){
				return $id;
			}
		}
	}
	function delete_sales(){
		extract($_POST);
		$del1 = $this->db->query("DELETE FROM sales_list where id = $id ");
		$del2 = $this->db->query("DELETE FROM inventory where type = 2 and form_id = $id ");
		if($del1 && $del2)
			return 1;
	}
	function get_selected_date(){
		extract($_POST);
		if (!empty($e_date))
			$n_date = strftime("%Y-%m-%d", strtotime("$e_date +1 day"));
		if (empty($e_date) && empty($s_date))
			return "Inseart date first.";
		else if (empty($e_date) || $s_date == $e_date) {
			$t_sum = $this->db->query("SELECT SUM(total_amount) AS amount FROM sales_list 
				WHERE date(date_updated) = '$s_date'")->fetch_array(MYSQLI_ASSOC);
			$t_exp = $this->db->query("SELECT SUM(total_amount) AS amount FROM receiving_list 
				WHERE date(date_added) = '$s_date'")->fetch_array(MYSQLI_ASSOC);
		}
		else if ($s_date>$e_date) return "Starting date can never be greater than the Ending date!";
		else {
			$t_sum = $this->db->query("SELECT SUM(total_amount) AS amount FROM sales_list 
				WHERE date_updated BETWEEN '$s_date' AND '$n_date'")->fetch_array(MYSQLI_ASSOC);
			$t_exp = $this->db->query("SELECT SUM(total_amount) AS amount FROM receiving_list 
				WHERE date_added BETWEEN '$s_date' AND '$n_date'")->fetch_array(MYSQLI_ASSOC);
		}
		if (empty($t_sum["amount"]))
			$r = "No sales on that/those day(s)!";
		else
			$r = "Total Sale: ".$t_sum["amount"]." BDT";
		if (empty($t_exp["amount"]))
			$r .= "<br />No expense on that/those day(s)!";
		else
			$r .= "<br />Total Expense: ".$t_exp["amount"]." BDT";
		return $r;
	}

}