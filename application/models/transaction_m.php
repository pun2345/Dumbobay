<?php
Class transaction_m extends CI_Model {
	function newTransaction($status,$price,$seller_id,$buyer_id,$product_id){
		$data = array(
			'Status' => $status,
			'Price' => $price,
			'Seller_ID' => $seller_id,
			'Buyer_ID' => $buyer_id,
			'Product_ID' => $product_id,
			// 'End_Date' => 'curdate()'

		);
		$this->db->trans_start();
		$this->db->set('End_Date', 'NOW() + INTERVAL 3 DAY', FALSE);
		$this->db->insert('transaction', $data);
		$insert_id = $this->db->insert_id();
		$this->db->trans_complete();
		return  $insert_id;
	}
	function getTransactionDetail($transaction_id){
		return $this->db->get_where('transaction',array('Transaction_ID'=>$transaction_id))->row();
	}
	function getCustTransaction($buyer_id){
		return $this->db->get_where('transaction',array('Buyer_ID'=>$buyer_id));
	}
	function getCustTransactionNum($buyer_id){
		$query = $this->transaction_m->getCustTransaction($buyer_id);
		return $query->num_rows();
	}
	function getEndPayment(){
		$query = $this->db->query("Select * from transaction where End_Date < curdate()");
		return $query;
	}
	function saveFeedbackBuyer($transaction_id,$score,$feedback){
		$data = array(
			'Seller_Score' => $score,
			'Seller_Feedback' => $feedback
		);
		$this->db->trans_start();
		$this->db->where('Transaction_ID', $transaction_id);
		$this->db->update('transaction',$data);
		$complete = $this->db->affected_rows();
		$row = $this->transaction_m->getTransactionDetail($transaction_id);
		$user_id  = $row->Seller_ID;
		echo $user_id;
		$this->member_m->updateFeedbackScore($user_id,$score);
		$this->db->trans_complete();
		if ($complete>0) {
			return "true";
		}else return "false";
	}
	function saveFeedbackSeller($transaction_id,$score,$feedback){
		$data = array(
			'Buyer_Score' => $score,
			'Buyer_Feedback' => $feedback
		);
		$this->db->trans_start();
		$this->db->where('Transaction_ID', $transaction_id);
		$this->db->update('transaction',$data);
		$complete = $this->db->affected_rows();
		$row = $this->transaction_m->getTransactionDetail($transaction_id);
		$user_id  = $row->Buyer_ID;
		echo $user_id;
		$this->member_m->updateFeedbackScore($user_id,$score);
		$this->db->trans_complete();
		if ($complete>0) {
			return "true";
		}else return "false";
	}
	function updateStatus($transaction_id,$status,$status_detail){
		$data = array(
			'Status' => $status,
			'Status_detail' => $status_detail
		);
		$this->db->trans_start();
		$this->db->where('Transaction_ID', $transaction_id);
		$this->db->update('transaction',$data);
		$complete = $this->db->affected_rows();
		$this->db->trans_complete();
		if ($complete>0) {
			return "true";
		}else return "false";
	}
	
}
?>