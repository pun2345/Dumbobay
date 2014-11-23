<?php
Class bidding_m extends CI_Model
{
		function setCurrentPrice($product_id,$price){
			$data = array(
				'Current_Price' => $price
			);
			$this->db->trans_start();
			$this->db->where('Product_ID', $product_id);
			$this->db->update('product_bid', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				return "true";
			}
		}
		function getCurrentPrice($product_id){
			$this->db->where('Product_ID', $product_id);
			$query=$this->db->get('product_bid');
			if($query->num_rows() == 1){
				foreach ($query->result() as $row)
				{
				   // print_r($row);
				   return $row->Current_Price;
				}
			}
		}
		function setCurrentMaxBid($product_id,$maxbid){
			$data = array(
				'Current_Max_Bid' => $maxbid
			);
			$this->db->trans_start();
			$this->db->where('Product_ID', $product_id);
			$this->db->update('product_bid', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				return "true";
			}
		}
		function getCurrentMaxBid($product_id){
			$this->db->where('Product_ID', $product_id);
			$query=$this->db->get('product_bid');
			if($query->num_rows() == 1){
				foreach ($query->result() as $row)
				{
				   // print_r($row);
				   return $row->Current_Max_Bid;
				}
			}
		}
		function getBidIncrement($product_id){
			$this->db->select('Bid_Increment');
			$this->db->where('Product_ID', $product_id);
			$query=$this->db->get('product_bid');
			if($query->num_rows() == 1){
				foreach ($query->result() as $row)
				{
				   // print_r($row);
				   return $row->Bid_Increment;
				}
			}
		}
		function setJoinBidding($user_id,$product_id,$price,$type){
			if($this->bidding_m->checkExistBidding($user_id,$product_id)){
				echo "exist <br>";
				return $this->bidding_m->updateBidding($user_id,$product_id,$price,$type);
			}else{
				$data = array(
					'User_ID' => $user_id,
					'Product_ID' => $product_id,
					'Bid_Price' => $price,
					'Bid_Type' => $type
				);
				$this->db->trans_start();
				$this->db->insert('join_bidding', $data);
				$complete = $this->db->affected_rows();
				$this->db->trans_complete();
				if ($complete>0) {
					return "true";
				}
			}
		}
		function checkExistBidding($user_id,$product_id){
			$this->db->select('Status');
			$query = $this->db->get_where('join_bidding',array('User_ID'=>$user_id,'Product_ID'=>$product_id));
			if($query->num_rows() == 1) return true;
			else return false;
		}
		function updateBidding($user_id,$product_id,$price,$type){
			echo "updateBidding <br>";
			$data = array(
					'Bid_Price' => $price,
					'Bid_Type' => $type
				);
				$this->db->trans_start();
				$this->db->where('User_ID', $user_id);
				$this->db->update('join_bidding', $data);
				$complete = $this->db->affected_rows();
				$this->db->trans_complete();
				if ($complete>0) {
					return "true";
				}
		}
		function setBidProductStatus($product_id,$status){
			$data = array(
				'Status' => $status
			);
			$this->db->trans_start();
			$this->db->where('Product_ID', $product_id);
			$this->db->update('product_bid', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				return "true";
			}
		}
		function setJoinBiddingStatus($user_id,$product_id,$status){
			$data = array(
				'Status' => $status
			);
			$this->db->trans_start();
			$this->db->where('User_ID', $user_id);
			$this->db->where('Product_ID', $product_id);
			$this->db->update('join_bidding', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				return "true";
			}
		}
		function setJoinBiddingType($user_id,$product_id,$type){
			$data = array(
				'Bid_Type' => $type
			);
			$this->db->trans_start();
			$this->db->where('User_ID', $user_id);
			$this->db->where('Product_ID', $product_id);
			$this->db->update('join_bidding', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				return "true";
			}
		}
		function setCurrentWinCust($product_id,$user_id){
			$data = array(
				'Current_Winner' => $user_id
			);
			$this->db->trans_start();
			$this->db->where('Product_ID', $product_id);
			$this->db->update('product_bid', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				return "true";
			}
		}
		function getCurrentWinCust($product_id){
			$this->db->select('Current_Winner');
			$this->db->where('Product_ID', $product_id);
			$query=$this->db->get('product_bid');
			if($query->num_rows() == 1){
				foreach ($query->result() as $row)
				{
				   // print_r($row);
				   return $row->Current_Winner;
				}
			}
		}
		function getJoinBidding($product_id){
			$query = $this->db->query("Select user_id from join_bidding where product_id = $product_id");
			return $query;
		}
		function getJoinBiddingUser($user_id,$product_id){
			$query = $this->db->query("Select user_id from join_bidding where product_id = $product_id and user_id = $user_id");
			return $query;
		}
		
}
?>