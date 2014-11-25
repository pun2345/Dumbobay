<?php
Class transaction_m extends CI_Model {
	function newTransaction($status,$price,$quantity,$seller_id,$buyer_id,$product_id){
		$data = array(
			'Status' => $status,
			'Price' => $price,
			'Quantity' => $quantity,
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
	function getHistoryCustTransaction($buyer_id){
		return  $this->db->query("Select transaction_id, product_id, product.name as product_name, status, transaction.price,  transaction.quantity, user.firstname as seller_name 
			from transaction 
			join product using (product_id) 
			join user on (transaction.seller_id = user.user_id) 
			where Buyer_ID = $buyer_id limit 30");

	}
	function getCustTransaction($buyer_id){
		return $this->db->get_where('transaction',array('Buyer_ID'=>$buyer_id));
	}
	function getCustTransactionNum($buyer_id){
		$query = $this->transaction_m->getCustTransaction($buyer_id);
		return $query->num_rows();
	}
	function getEndPayment(){
		$query = $this->db->query("Select * from transaction where End_Date < curdate() and Status = 'Waiting for payment'");
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
	function getWatchList($user_id){
		$this->db->select('*, join_bidding.Status AS Bidding_Status, product_bid.status AS Product_Status, join_bidding.Datetime AS Bidding_Datetime, product.Datetime AS Product_Datetime');
		$this->db->from('join_bidding');
		$this->db->where('join_bidding.User_ID', $user_id);
		$this->db->join('product', 'product.product_id = join_bidding.product_id');
		$this->db->join('product_bid', 'product.product_id = product_bid.product_id');
		$query = $this->db->get(); 
		return $query;
	}
	function getTopTenBestSeller(){
		return $this->db->query("
			Select seller_id,username,Firstname,Lastname,SumPrice from 
				(Select seller_id,count(seller_id),sum(price) as SumPrice 
					from transaction where Month(Datetime) = Month(curdate()) 
					group by seller_id limit 10
				) as newtable 
			inner join user on (user.user_id = newtable.seller_id)
			order by Sumprice desc
		");
	}
	function getTopTenBestProduct(){
		return $this->db->query("
			Select product.product_id,name,type,SumQuantity from 
				(Select product_id,sum(quantity) as SumQuantity 
					from transaction where Month(Datetime) = Month(curdate()) 
					group by product_id limit 10
				) as newtable 
			inner join product on (product.product_id = newtable.product_id)
			order by SumQuantity desc
		");
	}
}
?>
