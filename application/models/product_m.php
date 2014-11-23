<?php
Class product_m extends CI_Model
{
	function search($searchWord){
		// $query = $this->db->get_where('Product',array('name'=>$searchWord),40);
		$query = $this->db->query("Select * from product where Name like '%$searchWord%'");
		return $query;
	}
	function allBidProduct($page_num){
		$min = ($page_num-1)*10+1;
		
		$query = $this->db->query("Select * from product_bid join product where product_bid.product_id = product.product_id limit 10");

		return $query;
	}
	function allDirectProduct($page_num){
		$min = ($page_num-1)*10+1;
		$query = $this->db->query("Select * from product_direct join product where product_direct.product_id = product.product_id limit 10");
		
		return $query;
	}
	function getProductDetail($product_id){
		$this->db->select('Type');
		$query= $this->db->get_where('product',array('Product_ID'=>$product_id));
		$row = $query->row();
		if($row->Type == 1){ 
			//Direct product
			$query2 = $this->db->query("Select * from product join product_direct using (product_id) where product_id = $product_id");
		}
		else{
			//Bid product
			$query2 = $this->db->query("Select * from product join product_bid using (product_id) where product_id = $product_id");
		}
		return $query2->row();

	}
	function newDirectProduct($name,$image,$brand,$model,$price,$additional_info,$capacity,
							$size,$property,$defect,$quality,$payment,$return_product,$return_fee,
							$packaging,$delivery_fee,$delivery_confirmation,$tax,$quantity,$user_id){
		$data1 = array(
			'Type' => 1,
			'Name' => $name, 
			'Image'=> $image,
			'Brand' => $brand, 
			'Model' => $model, 
			'Price' => $price,
			'Additional_Info'=> $additional_info,
			'Capacity'=> $capacity,
			'Size'=> $size,
			'Property' => $property,
			'Defect' => $defect,
			'Quality' => $quality,
			'Payment' => $payment,
			'Return_Product' => $return_product,
			'Return_Fee' => $return_fee,
			'Packaging' => $packaging,
			'Delivery_Fee' => $delivery_fee,
			'Delivery_Confirmation' => $delivery_confirmation,
			'Tax' => $tax,
			'Seller_ID' => $user_id
			);
			$this->db->trans_start();
			$this->db->insert('product', $data1);
			$insert_id = $this->db->insert_id();
		$data2 = array(
			'Product_ID' => $insert_id,
			'Quantity' => $quantity
		); 
			$this->db->insert('product_direct', $data2);
			$this->db->trans_complete();
			return $insert_id;
	}
	
	function newBiddingProduct($name,$image,$brand,$model,$price,$additional_info,$capacity,
		$size,$property,$defect,$quality,$payment,$return_product,$return_fee,$packaging,$delivery_fee,
		$delivery_confirmation,$tax,$end_datetime,
		$status,$current_price,$current_max_bid,$current_win_cust_id,$bid_increment,$user_id){

		$data1 = array(
			'Type' => 2,
			'Name' => $name, 
			'Image'=> $image,
			'Brand' => $brand, 
			'Model' => $model, 
			'Price' => $price,
			'Additional_Info'=> $additional_info,
			'Capacity'=> $capacity,
			'Size'=> $size,
			'Property' => $property,
			'Defect' => $defect,
			'Quality' => $quality,
			'Payment' => $payment,
			'Return_Product' => $return_product,
			'Return_Fee' => $return_fee,
			'Packaging' => $packaging,
			'Delivery_Fee' => $delivery_fee,
			'Delivery_Confirmation' => $delivery_confirmation,
			'Tax' => $tax,
			'Seller_ID' => $user_id
			);
		$this->db->trans_start();
		$this->db->insert('product', $data1);
		$insert_id = $this->db->insert_id();

		$data2 = array(
			'Product_ID' => $insert_id,
			'Status' => $status,
			'Current_Price' => $current_price,
			'Current_Max_Bid' => $current_max_bid, 
			'Bid_Increment' => $bid_increment,
			'End_Date' => $end_datetime
			);
		$this->db->insert('product_bid', $data2);
		$this->db->trans_complete();
		return $insert_id;

	}

	function editBiddingProduct($id, $name, $image,$brand, $model, $price,$additional_info,
								$capacity,$size,$property,$defect,$quality,$payment,$return_product,$return_fee,
								$packaging,$delivery_fee,$delevery_confirmation,$tax,$seller_id,
								$status,$current_price,$current_max_bid, $bid_increment,$end_date){
		$data1 = array(
			//'Product_ID' => $id, 
			'Name' => $name, 
			'Image'=> $image,
			'Brand' => $brand, 
			'Model' => $model, 
			'Price' => $price,
			'Additional_Info'=> $additional_info,
			'Capacity'=> $capacity,
			'Size'=> $size,
			'Property' => $property,
			'Defect' => $defect,
			'Quality' => $quality,
			'Payment' => $payment,
			'Return_Product' => $return_product,
			'Return_Fee' => $return_fee,
			'Packaging' => $packaging,
			'Delivery_Fee' => $delivery_fee,
			'Delivery_Confirmation' => $delevery_confirmation,
			'Tax' => $tax,
			'Seller_ID' => $seller_id
			);
			$this->db->trans_start();
			$this->db->where('Product_ID', $id);
			$this->db->update('product', $data1);
			$product_r = ($this->db->affected_rows() > 0);
			// echo "product ".$product_r."<br>";
			// if ($this->db->affected_rows() <= 0) return "false";

		$data2 = array(
			'Status' =>$status,
			'Current_Price' => $current_price,
			'Current_Max_Bid' => $current_max_bid, 
			'Bid_Increment' => $bid_increment,
			'End_Date' => $end_date
			);
			$this->db->where('Product_ID', $id);
			$this->db->update('product_bid', $data2) ."<br>";
			$product_bid_r = ($this->db->affected_rows() > 0);
			// echo "<br> product_bid " .$product_bid_r. "<br>";
			$this->db->trans_complete();
			if ($product_bid_r or $product_r) return "true";
			else return "false";
	}
	function editDirectProduct($id, $name, $image,$brand, $model, $price,$additional_info,
								$capacity,$size,$property,$defect,$quality,$payment,$return_product,$return_fee,
								$packaging,$delivery_fee,$delivery_confirmation,$tax,$seller_id,
								$quantity){
		$data1 = array(
			//'Product_ID' => $id, 
			'Name' => $name, 
			'Image'=> $image,
			'Brand' => $brand, 
			'Model' => $model, 
			'Price' => $price,
			'Additional_Info'=> $additional_info,
			'Capacity'=> $capacity,
			'Size'=> $size,
			'Property' => $property,
			'Defect' => $defect,
			'Quality' => $quality,
			'Payment' => $payment,
			'Return_Product' => $return_product,
			'Return_Fee' => $return_fee,
			'Packaging' => $packaging,
			'Delivery_Fee' => $delivery_fee,
			'Delivery_Confirmation' => $delivery_confirmation,
			'Tax' => $tax,
			'Seller_ID' => $seller_id,
			);
			$this->db->trans_start();
			$this->db->where('Product_ID', $id);
			$this->db->update('product', $data1);
		$data2 = array(
			'Quantity' => $quantity
			); 
			$this->db->where('Product_ID', $id);
			$this->db->update('product_direct', $data2);
			$product_direct_r = ($this->db->affected_rows() > 0);
			$this->db->trans_complete();
			if ($product_direct_r or $product_r) return "true";
			else return "false";
	}
	function deleteProduct($product_id){
		$this->db->trans_start();
		$this->db->where('Product_ID', $product_id);
		$this->db->delete('product');
		$result = ($this->db->affected_rows() > 0);

		$this->db->where('Product_ID', $product_id);
		$this->db->delete('product_direct');
		$result2 = ($this->db->affected_rows() > 0);
		
		$this->db->where('Product_ID', $product_id);
		$this->db->delete('product_bid');
		$result2 = $result2 or ($this->db->affected_rows() > 0);

		$this->db->trans_complete();

		if ($this->product_m->checkExistProduct($product_id) == "false") return "true";
		else return "false";
	}
	function checkExistProduct($product_id){
		$query = $this->db->get_where('Product',array('product_id'=>$product_id));
		if($query->num_rows() >0){ 
			$checkExBidProduct = $this->product_m->checkExistProductBid($product_id);
			$checkExDirectProduct = $this->product_m->checkExistProductDirect($product_id);
			if($checkExDirectProduct and $checkExBidProduct) return "true";
			else return "false";
		}
		else return "false";
	}
	function checkExistProductBid($product_id){
		$query = $this->db->get_where('Product_bid',array('product_id'=>$product_id));
		if($query->num_rows() >0) return true;
		else return false;
	}
	function checkExistProductDirect($product_id){
		$query = $this->db->get_where('Product_direct',array('product_id'=>$product_id));
		if($query->num_rows() >0) return true;
		else return false;
	}
}
?>