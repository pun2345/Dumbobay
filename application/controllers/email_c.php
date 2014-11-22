<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class email_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
  		    $this->load->model('member_m');
			$this->load->model('product_m');
			//$this->load->model('transaction_m')
		
			//$this->load->database();
			//$this->load->model('email_m');
		

			//$this->email->from('test@localhost.com', 'Hello');
			//$this->email->to('pun_taweekiat@hotmail.com'); 

			//$this->email->subject('Email Test1');
			//$this->email->message('Testing the email class.');	

			//$this->email->send();

			//echo $this->email->print_debugger();

		}
		function index(){
			$this->load->helper('email_sender');
			test('try try try');
		}
		function winning_bid($id,$product_id){
			

		}
		function lose_bid(){}
		function win_bid(){}
		function endpayment(){}
		function feedback(){}
		/*function index(){
			$this->load->library('email');

			//$config['protocol'] = 'sendmail';
		

		    $config['protocol']    = 'smtp';
		    $config['smtp_host']    = 'ssl://smtp.gmail.com';
		    $config['smtp_port']    = '465';
		    $config['smtp_timeout'] = '7';
		    $config['smtp_user']    = 'pun2345@gmail.com';
		    $config['smtp_pass']    = '2345pun2345pun';
		    $config['charset']    = 'utf-8';
		    $config['newline']    = "\r\n";
		    $config['mailtype'] = 'text';
		    $config['validation'] = TRUE;

			$this->email->initialize($config);

			$this->email->from('test@localhost.com', 'Hello');
			$this->email->to('pun_taweekiat@hotmail.com'); 

			$this->email->subject('Email Test1');
			$this->email->message('Testing the email class.');	

			$this->email->send();

			echo $this->email->print_debugger();

		}*/




	}
?>