<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper('html');
    $this->load->database();
    $this->load->helper('form');
    $this->load->model('transaction_m');

    $this->load->model('member_m');
  }

  function index()
  {
    // $session_data = $this->session->userdata('logged_in');
    // $data['user_id'] = $session_data['user_id'];
    // $data['username'] = $session_data['username'];
    $data['products'] = $this->transaction_m->getTopTenBestProduct();
    $data['sellers'] = $this->transaction_m->getTopTenBestSeller();
    $data['blacklistUsers'] = $this->member_m->getBlacklist();
    $this->load->view("report.html",$data);
  }

  // function isLogin(){
  //   if($this->session->userdata('logged_in')){
  //     return true;
  //   } else{
  //     $this->session->set_flashdata("message","Please Login!");
  //     redirect('home');
  //   }
  // }

}
?>