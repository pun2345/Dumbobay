<?php
class Timer extends CI_Controller {
	 function __construct()
  	{
	    parent::__construct();
		$this->load->model('biddingproducts_m');
		$this->load->model('transaction_m');
		$this->load->model('product_m');
		$this->load->model('member_m');
	}
	public function endBid()
	{
		$query = $this->biddingproducts_m->searchEndBid();
		foreach $query->result() as $row{
			//Manage End Bid
			$query1 = $this->biddingproducts_m->searchBidder($row->$product_ID);
			foreach $query1->result() as $row1{
				$this->biddingproducts_m->setEndbidder($row1->User_ID);
			}
			$this->biddingproducts_m->setWinnerTransaction($row->$winner_ID);
			$seller_ID = $this->product_m->findSellerID($row->$product_ID);
			$enddate = date("Y-m-d", strtotime("+3 days"));
			$this->transaction_m->create_transaction('unpaid',$row->current_bid,'address',$seller_ID,$row->winner_ID,$row->product_ID,'seller_Socre','seller feeback','buyer_score','buyer_feedback',$end_date);
			$email= $this->member_m->getEmail($row->$winner_ID);
			$product_name = $this->product_m->product_name($row->product_ID);
			$this->send_Email->send_win_bid($email,$product_name);
		}
	}
	public function endPayment()
	{
		$query = $this->transaction_m->searchEndPayment();
		foreach $query->result() as $row{
			// Manage BL
			$this->transaction_m->setTransactionNoPayment($row->$transaction_ID);
			$this->member_m->increaseBlacklistScore($row->buyer_ID);
			$email= $this->member_m->getEmail($row->$buyer_ID);
			$this->send_Email->send_no_pay($email,$row->$transaction_ID);
		}
	}
}
?>