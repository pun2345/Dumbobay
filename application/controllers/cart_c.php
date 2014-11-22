<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->database();
    $this->load->helper('html');
    $this->load->helper('form');
    $this->load->model('cart_m');
    $this->load->model('transaction_m');
    $this->load->model('product_m');
  }

  function index()
  {
    // This method will have the credentials validation
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $data['products'] = $this->cart_m->getProductInCart($session_data['user_id']);
    $this->load->view('cart.html',$data);
    $this->load->view('footer.html');
  }
  function addToCart($product_id,$amount){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $temp = $this->cart_m->saveToCart($session_data['user_id'],$product_id,$amount);
    if($temp=="true"){
      $this->session->set_flashdata("message","Product was added");
    }else{
    $this->session->set_flashdata("message","Added fail!");
    }
    redirect("product_c");
    // redirect(current_url());
  } 
  function deleteProduct($product_id){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $temp = $this->cart_m->deleteFormCart($session_data['user_id'],$product_id);
    if($temp=="true"){
      $this->session->set_flashdata("message","Product was deleted");
    }else{
    $this->session->set_flashdata("message","Deleted fail!");
    }
    redirect("cart_c");
  }
  function editAmount($product_id,$amount){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $temp = $this->cart_m->editAmount($session_data['user_id'],$product_id,$amount);
    if($temp=="true"){
      $this->session->set_flashdata("message","Amount was changed");
    }else{
    $this->session->set_flashdata("message","Changing Amount fail!");
    }
    redirect("cart_c");
  }
  function checkOut(){
    $this->load->helper('date');
    $session_data = $this->session->userdata('logged_in');
    $products = $this->cart_m->getProductInCart($session_data['user_id']);
    $sumamount = 0;
    foreach ($products->result() as $row) {
      $amount = $row->Quantity;
      $product_id= $row->Product_ID;
      $price = $row->Price;
      $sumamount = $sumamount+($amount*$price);
      $transaction = array( 'datetime' => date('Y-m-d H:i:s'),
                            'status' => 'waiting for payment',
                            'status_detail' => '',
                            'price'=>$price,
                            'quantity'=>$amount,
                            'seller_score' => null,
                            'seller_feedback' => null,
                            'buyer_score' => null,
                            'buyer_feedback' => null,
                            // 'buyer_id' => 1,
                            'buyer_id' => $session_data['user_id'],
                            'product_id'=>$product_id);

      $transaction_ids[] = $this->transaction_m->newTransaction($transaction);
    }
    $this->session->set_flashdata("cart",$transaction_ids);
    $this->session->set_flashdata("cart2",$products);
    redirect('payment_c/index/'.$sumamount);
  }
  function afterPaid(){
    $session_data = $this->session->userdata('logged_in');
    $products = $this->cart_m->getProductInCart($session_data['user_id']);
    $products= $this->session->get_flashdata('cart');
    foreach ($products as $product=>$row) {
      $temp = $this->cart_m->deleteFormCart($session_data['user_id'],$row->Product_ID);
    }
    $this->session->set_flashdata("message","Checkout Sucessfuly!");
    redirect('home_c','refresh');
  }
  function deleteAll(){
    $session_data = $this->session->userdata('logged_in');
    $products = $this->cart_m->getProductInCart($session_data['user_id']);
    
    foreach ($products as $product) {
      $temp = $this->cart_m->deleteFormCart($session_data['user_id'],$product->product_id);
    }
    $this->session->set_flashdata("message","All product in cart was Deleted");
    redirect('home_c','refresh');
  }
  function isLogin(){
    if($this->session->userdata('logged_in')){
      return true;
    } else{
      $this->session->set_flashdata("message","Please Login!");
      redirect('home_c');
    }
  }

}
?>