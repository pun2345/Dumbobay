<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Timer extends CI_Controller {
	 function __construct()
  	{
	    parent::__construct();
		$this->load->model('bidding_m');
		$this->load->model('transaction_m');
		$this->load->model('product_m');
		$this->load->model('member_m');
		$this->load->database();
		$this->load->helper('email_sender');
	}
	public function endBid()
	{
		//echo "!23";
		$query = $this->product_m->getEndBiddingProduct();
		foreach ($query->result() as $row){
			//echo $row->product_id;
			$query1 = $this->bidding_m->getJoinBidding($row->product_id);
			$this->bidding_m->setBidProductStatus($row->product_id,1);//auction closed
			//echo "===============";
			foreach ($query1->result() as $row1)
			{
			 	$this->bidding_m->setJoinBiddingStatus($row1->user_id,$row->product_id,2);//set end bid
			}
			 $winner_ID = $this->bidding_m->getCurrentWinCust($row->product_id);
			 $this->bidding_m->setJoinBiddingStatus($winner_ID,$row->product_id,3);//win
			
			$status ='Waiting for payment';
		    $product = $this->product_m->getProductDetail($row->product_id);
		    $price = $product->Current_Price;		 
		    $quantity = 1;
		    $transaction_id = $this->transaction_m->newTransaction($status,$price,$quantity,$product->Seller_ID,$winner_ID,$row->product_id);
		    
			win_Bid($winner_ID,$row->product_id,$transaction_id);
		}
	}
	public function endPayment()
	{
		$query = $this->transaction_m->getEndPayment();
		foreach ($query->result() as $row){
			//print_r($row);
			echo $row->Buyer_ID;
			// Manage BL
			$this->transaction_m->updateStatus($row->Transaction_ID,'Exceed Paymeny Due Date','Exceed Paymeny Due Date. BlackList score +1');
			$bscore=$this->member_m->incBlacklistScore($row->Buyer_ID);
			echo ":".$bscore."<br>";
			//$email= $this->member_m->getMemberDetail($row->$Buyer_ID)->Email;
			blackList($row->Buyer_ID,$row->Transaction_ID,$row->Product_ID);
			if($bscore >=3){
				$this->member_m->deactivateMember($row->Buyer_ID);
			}
		}
	}
	public function test($x = ''){
		echo "test ".$x;
	}
}
?>