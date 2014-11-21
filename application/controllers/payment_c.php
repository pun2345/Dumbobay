<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->database();
    $this->load->helper('form');
    $this->load->model('transaction_m');
  }

  function index($price)
  {
    //This method will have the credentials validation
    $data['amount']=$price;
    $this->load->library('form_validation');

    $this->form_validation->set_rules('credit', 'credit', 'trim|required|xss_clean');
    $this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');
  
    if($this->form_validation->run() == FALSE)
    {
      //Field validation failed.  User redirected to login page
      $this->session->keep_flashdata("cart"); /////check again
      $this->load->view('payment_view',$data);
    }
    else
    {
      //Go to private area
      $transaction_ids = $this->get_flashdata("cart");
      foreach ($transaction_ids as $transaction_id) {
        $this->transaction_m->updateStatus($transaction_id,"already Paid","");
      }
      redirect('cart/deleteAll');
    }
    $this->load->view('payment_view',$data);

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