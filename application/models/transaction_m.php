<?php
Class Transaction_m extends CI_Model
{
	function getTopTenSeller(){
		$this->db->select("'seller_id',SUM('quantity') AS 'total'");
		// $this->db->select("SUM('quantity') AS 'total'");
		$this->db->from('transaction');
		$this->db->group_by('seller_id');
		$this->db->order_by('total','desc');
		$this->db->limit(10);
		$query = $this->db->get();
		print_r($query);
		foreach($query as $q=>$row){
		      print_r($row['seller_id']);
		      print_r("</br>");
		      // print_r($row['seller_id']);
		    }
		return $query;
	}
	
}
?>