<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper('array');
    $this->load->helper('html');
    $this->load->database();
    $this->load->helper('email_sender');
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->model('transaction_m');
    $this->load->model('product_m');
  }

  function index($price)
  {
    //This method will have the credentials validation
    $data['price']=$price;
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $transaction_ids = $this->session->flashdata("cart");
    $this->session->keep_flashdata("cart");
    //
    $data['products'] = $this->session->flashdata("cart2");
    //print_r($data['products']);
    $this->session->keep_flashdata("cart2");
    $this->load->view('paymentForm.html',$data);
  }

  function pay($price)
  {
    //This method will have the credentials validation
    $data['price']=$price;
    $transaction_ids = $this->session->flashdata("cart");
    $this->session->keep_flashdata("cart");
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $products = $this->session->flashdata("cart2");
    $this->session->keep_flashdata("cart2");
    $data['products'] = $products;

    //$this->load->view('paymentForm.html',$data);
    $this->load->library('form_validation');

    $this->form_validation->set_rules('visano', 'visano', 'trim|required|xss_clean');
    $this->form_validation->set_rules('ccv', 'ccv', 'trim|required|xss_clean');
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
        $this->transaction_m->updateStatus($transaction_id,"Paid","");
        Feedback($transaction_id);
      }
      // $this->session->set_flashdata("cart2",$products);
    $this->session->keep_flashdata("cart");
    $this->session->keep_flashdata("cart2");
      redirect('cart_c/afterPaid');
    }
    //$this->load->view('payment_view',$data);

  }
  
  function payBid($transaction_id)
  {
    $this->loginBeforePay();
    //This method will have the credentials validation
    $session_data = $this->session->userdata('logged_in');
    $data['user_id'] = $session_data['user_id'];
    $data['username'] = $session_data['username'];
    $data['type'] = $session_data['type'];
    $transaction = $this->transaction_m->getTransactionDetail($transaction_id);
    $data['transaction_id']=$transaction_id;
    $data['price']=$transaction->Price;
    $product_id = $transaction->Product_ID;
    $data['products'] =$this->product_m->getProductDetail($product_id);

    //$this->load->view('paymentForm.html',$data);
    $this->load->library('form_validation');

    $this->form_validation->set_rules('visano', 'visano', 'trim|required|xss_clean');
    $this->form_validation->set_rules('visapw', 'visapw', 'trim|required|xss_clean');
    $this->form_validation->set_rules('sendaddress', 'sendaddress', 'trim|required|xss_clean');  

    if($this->form_validation->run() == FALSE)
    {
      //Field validation failed.  User redirected to login page
      $this->load->view('paymentForm2.html',$data);
    }
    else
    {
      //Go to private area
        $this->transaction_m->updateStatus($transaction_id,"Paid","");
        Feedback($transaction_id);
        redirect("transaction_c/history");

    }
    //$this->load->view('payment_view',$data);

  }

  function isLogin(){
    if($this->session->userdata('logged_in')){
      return true;
    } else{
      $this->session->set_flashdata("message","Please Login!");
      redirect('home_c');
    }
  }
  function loginBeforePay(){
    if($this->session->userdata('logged_in')){
      return true;
    } else{
      $this->session->set_flashdata("message","Please Login!");
      $this->session->set_flashdata("beforePay",current_url());
      redirect('login_c');
    }
  }
}
?>