<?php
class Timer extends CI_Controller {
	 function __construct()
  	{
	    parent::__construct();
		$this->load->model('bidding_m');
		$this->load->model('transaction_m');
		$this->load->model('product_m');
		$this->load->model('member_m');
		$this->load->helper('email_sender');
	}
	public function endBid()
	{
		$query = $this->biddingproducts_m->getEndBiddingProduct();
		foreach $query->result() as $row{
			//Manage End Bid
			$this->bidding_m->setBiddingProductStatus($row->Product_ID,'Bid Closed')
			$query1 = $this->bidding_m->getJoinBidding($row->Product_ID);
			foreach $query1->result() as $row1
			{
				$this->bidding_m->setJoinBiddingStatus($row1->User_ID,$row->Product_id,2);//set end bid
			}
			$winner_ID = $this->bidding_m->getCurrentWinCust($row->Product_ID);
			$this->bidding_m->setJoinBiddingType($winner_ID,$row->Product_ID,3);//win
			$Product_Detail = $this->product_m->getDetail($row->$product_ID)->Seller_ID;
			$seller_ID = $Product_Detail->Seller_ID;
			$enddate = date("Y-m-d", strtotime("+3 days"));
			$this->transaction_m->create_transaction('unpaid',$row->Current_Bid,'address',$Seller_ID,$row->Winner_ID,$row->Product_ID,-1,'seller feeback',-1,'buyer_feedback',$end_date);
			$email= $this->member_m->getEmail($row->$winner_ID);
			$product_name = $Product_Detail->Name;
			win_Bid($email,$product_name);
		}
	}
	public function endPayment()
	{
		$query = $this->transaction_m->getEndPaymeny();
		foreach $query->result() as $row{
			// Manage BL
			$this->transaction_m->updateStatus($row->Trandsaction_ID,'Exceed Paymeny Due Date');
			$bscore=$this->member_m->increaseBlacklistScore($row->Buyer_ID);
			//$email= $this->member_m->getMemberDetail($row->$Buyer_ID)->'E-mail';
			BlackList($row->Buyer_ID,$row->$Transaction_ID);
			if($bscore >=3){
				$this->member_m->disableMember($row->$Buyer_ID);
			}
		}
	}
}
?>