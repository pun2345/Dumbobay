<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class bidding_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
			$this->load->database();
			$this->load->model('bidding_m');
			$this->load->model('product_m');
			$this->load->helper('email_sender');
			$this->load->helper('html');	
			$this->load->library('form_validation');
			$this->load->helper('form');

		}

		function index(){
			
		}

		function bidding($user_id, $product_id, $price, $bidding_type) { 
			$product = $this->product_m->getProductDetail($product_id);
			$current_maxbid = $product->Current_Max_Bid;
			$current_price = $product->Current_Price;
			$bid_increment = $product->Bid_Increment;

			$newprice= $price;  // Normalize Price
			if($bidding_type == 'auto'){
				$turns = ($current_maxbid - $current_price) / $bid_increment;
				$turn = ($current_maxbid - $current_price) % $bid_increment;
				if($turn != 0){
					$newprice = $current_price + ($bid_increment * $turns);
					$this->session->set_flashdata("message","Your Bid is Invalid. Your new Maxbid =".$newprice);
				}
			}
			// Calculate Winner
			if($newprice<=$current_maxbid)
			{
				//echo "Loser";
				//$newprice = $price;
				if($bidding_type == 'auto')
				{
					
					$this->bidding_m->setJoinBidding($user_id,$product_id,$newprice,'auto',0);
				}
				else{//$bidding_type == manunal
					$this->bidding_m->setJoinBidding($user_id,$product_id,$newprice,'maunal',0);
				}

				$oldWinner = $this->bidding_m->getCurrentWinCust($product_id);
				if($newprice+$bid_increment<=$current_maxbid)
				{
					$this->bidding_m->setCurrentPrice($product_id,$price+$bid_increment);
					$this->bidding_m->setJoinBiddingPrice($oldWinner,$product_id,$price+$bid_increment);
				
				}
				else{//		$price+$bid_increment>Current Maxbid{
					$this->bidding_m->setCurrentPrice($product_id,$current_maxbid);
					$this->bidding_m->setJoinBiddingPrice($oldWinner,$product_id,$current_maxbid);
				

				}
			}

			else//$newprice>$current_maxbid
			{
				//echo "Winer";
				if($bidding_type == 'auto')
				{
					//echo "1";
					$this->bidding_m->setCurrentPrice($product_id,$current_maxbid+$bid_increment);
					$this->bidding_m->setCurrentMaxBid($product_id,$newprice);	
					$this->bidding_m->setJoinBidding($user_id,$product_id,$current_price+$bid_increment,'auto',1);
				}
				else// manual
				{
					//echo "2";
					$this->bidding_m->setCurrentPrice($product_id,$newprice);
					$this->bidding_m->setCurrentMaxBid($product_id,$newprice);
					$this->bidding_m->setJoinBidding($user_id,$product_id,$newprice,'manual',1);
				}

				$oldWinner = $this->bidding_m->getCurrentWinCust($product_id);
				if($oldWinner != null){
					//$this->bidding_m->setJoinBiddingType($oldWinner,$product_id,0);
					$this->bidding_m->setJoinBiddingPriceStatus($oldWinner,$product_id,$current_maxbid,0);
					//$this->bidding_m->setJoinBiddingStatus($oldWinner,$product_id,0);
					$this->notifyBidLosingEmail($oldWinner,$product_id);
				}
				$this->bidding_m->setCurrentWinCust($product_id,$user_id);
				
			}




// 			if($bidding_type == 'auto'){
				
// 				
// 				$maxbid = $newprice;
// z
// 				if($maxbid <= $current_price || $maxbid < $current_price + $bid_increment){
// 					$this->bidding_m->setJoinBidding($user_id,$product_id,$maxbid,'auto');
// 					return FALSE;
// 				}
// 				else if($current_maxbid==0){
// 					if($maxbid >= $current_price + $bid_increment){
// 						$this->bidding_m->setCurrentPrice($product_id,$current_price+$bid_increment);
// 						$this->bidding_m->setCurrentMaxBid($product_id,$maxbid);
// 						$oldWinner = $this->bidding_m->getCurrentWinCust($product_id);
// 						if($oldWinner != null){
// 							//$this->bidding_m->setJoinBiddingType($oldWinner,$product_id,0);
// 							$this->bidding_m->setJoinBiddingType($oldWinner,$product_id,$current_price,'manual',0);
// 							//$this->bidding_m->setJoinBiddingStatus($oldWinner,$product_id,0);
// 							$this->notifyBidLosingEmail($oldWinner,$product_id);
// 						}
// 						$this->bidding_m->setCurrentWinCust($product_id,$user_id);
// 						$this->bidding_m->setJoinBidding($user_id,$product_id,$current_price+$bid_increment,'auto',1);
// 					}
// 					// else if($maxbid > $current_price){
// 					// 	$this->bidding_m->setJoinBidding($user_id,$product_id,$current_price,'manual',0);
						
// 					// 	$this->notifyBidLosingEmail($user_id,$product_id);
// 					// }/////////Unuse
// 				}

// 				else if($maxbid > $current_maxbid){///win
// 					$this->bidding_m->setCurrentPrice($product_id,$maxbid);
// 					$this->bidding_m->setCurrentMaxBid($product_id,$maxbid);
// 					$oldWinner = $this->bidding_m->getCurrentWinCust($product_id);
// 					if($oldWinner != null){
// 						//$this->bidding_m->setJoinBiddingType($oldWinner,$product_id,'manual');
// 						$this->bidding_m->setJoinBidding($oldWinner,$product_id,$current_maxbid,'manual',0);
// 						//$this->bidding_m->setJoinBiddingStatus($oldWinner,$product_id,0);
// 						$this->notifyBidLosingEmail($oldWinner,$product_id);
// 					}
// 					$this->bidding_m->setCurrentWinCust($product_id,$user_id);
// 					$this->bidding_m->setJoinBidding($user_id,$product_id,$maxbid,'auto',1);

// 				}
// 				else if($maxbid <= $current_maxbid){
// 					$this->bidding_m->setCurrentPrice($product_id,$maxbid);
// 					$this->bidding_m->setJoinBidding($user_id,$product_id,$maxbid,'manual',0);
// 					$this->notifyBidLosingEmail($user_id,$product_id);
// 				}
// 				// else if($maxbid == $current_maxbid){
// 				// 	$this->bidding_m->setCurrentPrice($product_id,$maxbid);
// 				// 	$this->bidding_m->setJoinBidding($user_id,$product_id,$maxbid,'manual',0);
// 				// 	$this->notifyBidLosingEmail($user_id,$product_id);
// 				// }

// 			}
// 			else if($bidding_type == 'manual'){
// 				if($price <= $current_maxbid){
// 					if($current_maxbid >= $price + $bid_increment){
// 						$newprice = $price + $bid_increment;
// 					}
// 					else{
// 						$newprice = $price;
// 					}
// 					$this->bidding_m->setCurrentPrice($product_id,$newprice);
// 				}
// 				else if{// price > maxbid
// 					$this->bidding_m->setCurrentPrice($product_id,$price);
// 					$this->bidding_m->setCurrentMaxBid($product_id,0);///////////////////??
// 					$oldWinner = $this->bidding_m->getCurrentWinCust($product_id);
// 					if($oldWinner != null){
// 						//$this->bidding_m->setJoinBiddingType($oldWinner,$product_id,'manual');
// 						$this->bidding_m->updateJoinBidding($oldWinner,$product_id,$current_maxbid,'manual',0){
// 						//$this->bidding_m->setJoinBiddingStatus($oldWinner,$product_id,0);
// 						$this->notifyBidLosingEmail($oldWinner,$product_id);
// 					}
// 					$this->bidding_m->setJoinBidding($user_id,$product_id,$price,'manual',1);
// 					$this->bidding_m->setCurrentWinCust($product_id,$user_id);
// 				}
// 			}
			return TRUE;
		}

		function maxBidding($product_id){
			$session_data = $this->session->userdata('logged_in');
      		$data['type'] = $session_data['type'];	
      		$data['user_id'] = $session_data['user_id'];
      		$data['username'] = $session_data['username'];
   	        $this->form_validation->set_rules('maxbid', 'maxbid', 'require|css_clean');
			if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		    {
		        $this->load->view('watchlist.html',$data);
		    }
      	    else // passed validation proceed to post success logic
	        {
		        $form_data = array( 'maxbid' => $this->input->post('maxbid'));
			    $maxbid = $form_data['maxbid'];
			    if ($this->bidding($session_data['user_id'],$product_id,$maxbid,'auto') == TRUE) // the information has therefore been successfully saved in the db
			    {             
			        $this->session->set_flashdata("message","Bidding completed");
			    }
			    else
			    {
			        $this->session->set_flashdata("message","Your max bid is lower than minimum bid incremental, please try again");
			    }
			    redirect('watchlist_c');
			}
		}

		function stepBidding($product_id){
			$session_data = $this->session->userdata('logged_in');
      		$data['type'] = $session_data['type'];
      		$data['user_id'] = $session_data['user_id'];
      		$data['username'] = $session_data['username'];
			$current_price = $this->bidding_m->getCurrentPrice($product_id);
			$bid_increment = $this->bidding_m->getBidIncrement($product_id);
			$newprice = $current_price+$bid_increment;
			$this->bidding($session_data['user_id'],$product_id,$newprice,'manual');
			$data['current_price'] = $current_price;
		    $this->session->set_flashdata("message","Bidding completed !");
		    redirect('watchlist_c');
		}

		function getBiddingParticipants($product_id){
			$joinbids = $this->bidding_m->getJoinBidding($product_id);
			$winner_id = $this->bidding_m->getCurrentWinCust($product_id);
			foreach($joinbids as $joinbid){
				$list[] = $joinbid->user_id;
			}
			return $list;
		}

		function updateBiddingStatus($product_id){
			$this->bidding_m->setProductStatus($product_id,1);
			$this->bidding_m->setJoinBiddingStatus($this->bidding_m->getCurrentWinCust($product_id),$product_id,3);
			$participants = $getBiddingParticipants($product_id);
			foreach($participants as $participant){
				$this->bidding_m->setJoinBiddingStatus($participant,$product_id,2);
			}
		}

		function notifyBidLosingEmail($user_id,$product_id){
			losing_Bid($user_id,$product_id);
		}

		function initializeMaxBidding($product_id,$maxbid){
			$session_data = $this->session->userdata('logged_in');
			$this->bidding($session_data['user_id'],$product_id,$maxbid,'auto');
			redirect('watchlist_c');
		}

		function initializeStepBidding($product_id){
			$session_data = $this->session->userdata('logged_in');
			$isBid = $this->bidding_m->isJoinBidding($session_data['user_id'],$product_id);
			if(!$isBid)
			{
				$current_price = $this->bidding_m->getCurrentPrice($product_id);
				$bid_increment = $this->bidding_m->getBidIncrement($product_id);
				$this->bidding($session_data['user_id'],$product_id,$current_price+$bid_increment,'manual');
			}
			redirect('watchlist_c');
		}

	}
?>