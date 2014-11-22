<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper('html');
    // $this->load->database();
    $this->load->helper('form');
    // $this->load->model('transaction_m');
  }

  function index($price)
  {
    //This method will have the credentials validation
    $data['price']=$price;
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $transaction_ids = $this->session->get_flashdata("cart");
    $this->session->keep_flashdata("cart");
    //
    $data['products'] = $this->session->get_flashdata("cart2");
    $this->session->keep_flashdata("cart2");
    $this->load->payment_view('paymentForm.html',$data);
  }

  function pay($price)
  {
    //This method will have the credentials validation
    $data['price']=$price;
    $transaction_ids = $this->session->flashdata("cart");
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $data['products'] = $this->session->flashdata("cart2");

    $this->load->view('paymentForm.html',$data);
    $this->load->library('form_validation');

    $this->form_validation->set_rules('visano', 'visano', 'trim|required|xss_clean');
    $this->form_validation->set_rules('visapw', 'visapw', 'trim|required|xss_clean');
    $this->form_validation->set_rules('sendaddress', 'sendaddress', 'trim|required|xss_clean');  

    if($this->form_validation->run() == FALSE)
    {
      //Field validation failed.  User redirected to login page
      $this->load->view('paymentForm.html',$data);
    }
    else
    {
      //Go to private area
      foreach ($transaction_ids as $transaction_id) {
        $this->transaction_m->updateStatus($transaction_id,"already Paid","");
      }
      $this->session->set_flashdata('cart2',$data['products']);
      redirect('cart/afterPaid');
    }
    //$this->load->view('payment_view',$data);

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