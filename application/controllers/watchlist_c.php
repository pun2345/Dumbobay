<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class watchlist_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
			$this->load->database();
			$this->load->helper('html');
			$this->load->model('watchlist_m');
			$this->load->model('product_m');
			$this->load->model('form');
			$this->load->model('transaction_m');
			$this->load->model('bidding_m');
		}

		function index(){
			$session_data = $this->session->userdata('logged_in');
			$data['user_id'] = $session_data['user_id'];
      		$data['username'] = $session_data['username'];
      		$data['type'] = $session_data['type'];
			$data['products'] = $this->transaction_m->getWatchlist($data['user_id']);
   	        $this->form_validation->set_rules('maxbid', 'maxbid', 'require|css_clean');
			if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		    {
		        $this->load->view('watchlist.html',$data);
		    }
      	    else // passed validation proceed to post success logic
	        {
			}
			//$this->load->view('watchlist.html',$data);	
		}

		function productDetail($product_id){
			$session_data = $this->session->userdata('logged_in');
			$data['user_id'] = $session_data['user_id'];
      		$data['type'] = $session_data['type'];
      		$data['username'] = $session_data['username'];
			$data['product'] = $this->product_m->getBidProductDetail($product_id);
			$this->load->view('watchlistdetail.html',$data);
		}

		function maxBidding($product_id){
			redirect('bidding_c/maxBidding/',$product_id);
		}

		function stepBidding($product_id){
			redirect('bidding_c/stepBidding/',$product_id);
		}

		function payment($product_id){
    		$this->load->helper('date');
			$product = $this->productDetail($product_id);
			$price = $product->Rrice;
			$transaction = array( 'datetime' => date('Y-m-d H:i:s'),
								  'status' => 'waiting for payment',
	                              'status_detail' => '',
	                              'price' => $price,
	                              'quantity' => $amount,
	                              'seller_score' => null,
	                              'seller_feedback' => null,
	                              'buyer_score' => null,
	                              'buyer_feedback' => null,
	                              'buyer_id' => $session_data['user_id'],
	                              'product_id' => $product->Product_ID );
			$transaction_ids[] = $this->transaction_m->newTransaction($transaction);
			$this->session->set_flashdata("cart",$transaction_ids);
			redirect('payment_c/'.$price);
		}

	}
?>