<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class bidding_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
			$this->load->database();
			$this->load->model('bidding_m');
		}

		function index(){
			
		}

	}
?>