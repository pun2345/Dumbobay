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
    $this->load->view("report.html");
  }
  function topTenProduct(){
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $data['products'] = $this->transaction_m->getTopTenProduct();
    $this->load->view("topTenProduct.html",$data);
  }
  function topTenSeller(){
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $sellers = $this->transaction_m->getTopTenSeller();
    // foreach($sellers as $seller->$row){
    //   print_r($row);
    //   // print_r($row['seller_id']);
    // }
    $this->load->view("topTenSeller.html",$data);
  }
  function blacklist(){
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $data['users'] = $this->transaction_m->getBlacklist();
    $this->load->view("blacklist.html",$data);
  }
  function isLogin(){
    if($this->session->userdata('logged_in')){
      return true;
    } else{
      $this->session->set_flashdata("message","Please Login!");
      redirect('home');
    }
  }

}
?>