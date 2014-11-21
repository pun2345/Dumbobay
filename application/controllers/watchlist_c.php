<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class watchlist_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
			$this->load->database();
			$this->load->model('watchlist_m');
		}

		function index(){

		}

	}
?>