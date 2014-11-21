<?php
Class Product extends CI_Model
{
	function search($data){
		$query = $this->db->get_where('Product',array('name'=>$data),40);
		return $query;
	}
	function allProduct(){
		$this->db->limit(10);
		$query = $this->db->get('Product');
		return $query;
	}
	function getDetail(&id){
		$query = $this->db->get_where('Product',array('id'=>$id));
		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}		
	}
	function editProduct($id, $name, $image,$brand, $model, $price,$additional_info,$capacity,$size,$property,$defect,$quality,$payment,$return_product,$return_fee,$packaging,$delivery_fee,$delevery_confirmation,$tax,$seller_id,$datetime){
		$data = array(
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
			'Delevery_Confirmation' => $delevery_confirmation,
			'Tax' => $tax,
			'Seller' => $seller_id,
			'Datetime' => $datetime	
			);
			$this->db->where('Product_ID', $id);
			$this->db->update('product', $data); 
			return true;
	}
	function deleteProduct(){
		$this->db->where('Product_ID', $id);
		$this->db->delete('product');
		return true;
	}
	function newDirectProduct();
	
	function newBiddingProduct();

}
?>