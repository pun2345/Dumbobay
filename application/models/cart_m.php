<?php
Class cart_m extends CI_Model
{
	function saveToCart($user_id,$product_id,$amount){;
		if($this->cart_m->checkProductInCart($user_id,$product_id)== 0) {	
			$data = array(
				'User_ID' => $user_id,
				'Product_ID' => $product_id,
				'Quantity' => $amount
			);
			$this->db->trans_start();
			$this->db->insert('cart', $data);
			$this->db->trans_complete();
			return "true";
		}
		else return "false";
	}
	function getProductInCart($user_id){
			 return $this->db->query("select * from cart join Product using (product_id) where User_ID = $user_id");
	}
	function checkProductIncart($user_id,$product_id){
		$this->db->where('Product_ID', $product_id);
			$this->db->where('User_ID', $user_id);
			$query=$this->db->get('Cart');
			if($query->num_rows() == 1) return 1;
			else return 0;
	}
	function deleteFromCart($user_id, $product_id){
		$this->db->trans_start();
		$this->db->where('Product_ID', $product_id);
		$this->db->where('User_ID', $user_id);
		$this->db->delete('cart');
		$this->db->trans_complete();
		$query=$this->db->get_where('Cart',array('User_ID' => $user_id,
											'Product_ID' =>$product_id));
		if($query->num_rows() == 0) return "true";
		else return "false";
	}
	function editAmount($user_id,$product_id,$amount){
		if($this->cart_m->checkProductIncart($user_id,$product_id)){
			$data = array(
				'Quantity' => $amount
			);
			$this->db->trans_start();
			$this->db->where('User_ID', $user_id);
			$this->db->where('Product_ID', $product_id);
			$this->db->update('cart', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				return "true";
			}
		}
		return "false";

		
	}
}
?>