<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class bidding_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
			$this->load->database();
			$this->load->model('bidding_m');

		}

		function index(){
			
		}


		function bidding($user_id, $product_id, $price, $bidding_type) { 
			$current_price = $this->bidding_m->getCurrentPrice($product_id);
			$current_maxbid = $this->bidding_m->getCurrentMaxBid($product_id);
			$bid_increment = $this->bidding_m->getBidIncrement($product_id);
			if($bidding_type == 'auto'){
				$maxbid = $price;
				if($current_maxbid==0){
					if($maxbid > $current_price + $bid_increment){
						$this->bidding_m->setCurrentPrice($user_id,$product_id,$current_price+$bit_increment);
						$this->bidding_m->setCurrentMaxBid($product_id,$maxbid);
						$oldWinner = $this->bidding_m->getCurrentWinCust($product_id);
						$this->bidding_m->setJoinBiddingType($oldWinner,$product_id,'manual');
						$this->bidding_m->setJoinBiddingStatus($oldWinner,$product_id,0);
						$this->notifyBidLosingEmail($oldWinner,$product_id);
						$this->bidding_m->setCurrentWinCust($product_id,$user_id);
						$this->bidding_m->setJoinBiddingType($this->bidding_m->getCurrentWinCust($product_id),$product_id,'auto');
						$this->bidding_m->setJoinBiddingStatus($user_id,$product_id,1);
					}
				}
				else if($maxbid <= $current_price){
					return FALSE;
				}
				else if($maxbid > $current_maxbid){
					if($maxbid > $current_maxbid + $bit_increment){
						$newprice = $current_maxbid + $bit_increment;
					}
					else{
						$newprice = $maxbid;
					}
					$this->bidding_m->setCurrentPrice($user_id,$product_id,$newprice);
					$this->bidding_m->setCurrentMaxBid($product_id,$maxbid);
					$oldWinner = $this->bidding_m->getCurrentWinCust($product_id);
					$this->bidding_m->setJoinBiddingType($oldWinner,$product_id,'manual');
					$this->bidding_m->setJoinBiddingStatus($oldWinner,$product_id,0);
					$this->notifyBidLosingEmail($oldWinner,$product_id);
					$this->bidding_m->setCurrentWinCust($product_id,$user_id);
					$this->bidding_m->setJoinBiddingType($this->bidding_m->getCurrentWinCust($product_id),$product_id,'auto');
					$this->bidding_m->setJoinBiddingStatus($user_id,$product_id,1);
				}
				else if($maxbid <= $current_maxbid){
					if($current_maxbid > $maxbid + $bit_increment){
						$newprice = $maxbid + $bit_increment;
					}
					else{
						$newprice = $current_maxbid;
					}
					$this->bidding_m->setCurrentPrice($user_id,$product_id,$newprice);
					$this->bidding_m->setJoinBiddingStatus($user_id,$product_id,0);
					$this->notifyBidLosingEmail($user_id,$product_id);
				}
			}

			else if($bidding_type == 'manual'){
				$current_maxbid = $this->bidding_m->getCurrentMaxBid($product_id);
				$current_price = $this->bidding_m->getCurrentPrice($product_id);
				$bit_increment = $this->bidding_m->getBidIncrement($product_id);
				if($price < $current_maxbid){
					if($current_maxbid > $price + $bit_increment){
						$newprice = $price + $bit_increment;
					}
					else{
						$newprice = $current_price
					}
					$this->bidding_m->setCurrentPrice($user_id,$product_id,$newprice);
				}
				else{
					$this->bidding_m->setCurrentPrice($user_id,$product_id,$price);
					$this->bidding_m->setCurrentMaxBid($product_id,0);
					$oldWinner = $this->bidding_m->getCurrentWinCust($product_id);
					$this->bidding_m->setJoinBiddingType($oldWinner,$product_id,'manual');
					$this->bidding_m->setJoinBiddingStatus($oldWinner,$product_id,0);
					$this->notifyBidLosingEmail($oldWinner,$product_id);
					$this->bidding_m->setJoinBiddingType($user_id,$product_id,'manual');
					$this->bidding_m->setJoinBiddingStatus($user_id,$product_id,1);
					$this->bidding_m->setCurrentWinCust($product_id,$user_id);
				}
			}
			return TRUE;
		}

		function maxBidding($product_id){
			$session_data = $this->session->userdata(logged_in);			
			$this->load->library('form_validation');
	        $this->form_validation->set_rules('maxbid', 'maxbid', 'require|css_clean');
			if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		    {
		        $this->load->view(' .html/',$data);
		    }
      	    else // passed validation proceed to post success logic
	        {
		        $form_data = array( 'maxbid' => $this->input->post('maxbid'));
			    $maxbid = $form_data['$maxbid'];
			}
		    if ($this->bidding($session_data['user_id'],$product_id,$maxbid,'auto') == TRUE) // the information has therefore been successfully saved in the db
		    {             
		        $this->session->set_flashdata("message","Bidding completed");
		        redirect(current_url());   // or whatever logic needs to occur
		    }
		    else
		    {
		        $this->session->set_flashdata("message","Unable to bid");
		        redirect(current_url());
		    }
		}

		function stepBidding($product_id){
			$session_data = $this->session->userdata(logged_in);
			$current_price = $this->bidding_m->getCurrentPrice($product_id);
			$bit_increment = $this->bidding_m->getBitIncrement($product_id);
			$this->bidding($session_data['user_id'],$product_id,$current_price+$bit_increment,'manual');
			$data['current_price'] = $this->bidding_m->getCurrentPrice($product_id);
			$this->load->view('watchlist.html/',$data);
		    $this->session->set_flashdata("message","Bidding completed !");
		}

		function getBiddingParticipants($product_id){
			$joinbids = $this->bidding_m->getJoinBidding($user_id,$product_id);
			$winner_id = $this->bidding_m->getCurrentWinCust($product_id);
			foreach($joinbid in $joinbids){
				$list[] = $joinbid->user_id;
			}
			return $list;
		}

		function updateBiddingStatus($product_id){
			$this->bidding_m->setProductStatus($product_id,1);
			$this->bidding_m->setJoinBiddingStatus($this->bidding_m->getCurrentWinCust($product_id),$product_id,3);
			$participants = $getBiddingParticipants($product_id);
			foreach($participant in $participants){
				$this->bidding_m->setJoinBiddingStatus($participant,$product_id,2);
			}
		}

		function notifyBidLosingEmail($user_id,$product_id){

		}

	}
?>