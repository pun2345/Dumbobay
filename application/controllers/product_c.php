<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->database();
    $this->load->helper('form');
    $this->load->model('product_m');
  }

  function index()
  {
    //This method will have the credentials validation
    $data['products'] = $this->product_model->allProduct();
    $this->load->view('product.html',$data);
  }
  function register()
  {
    redirect('member_c/createMember');
  }
  function search($searchWord){
    $data['products'] = $this->product_model->search($searchWord);
    $this->load->view('product.html',$data);
  }
  function viewProductDetail($product_id){
    $data['product'] = $this->product_model->viewProductDetail($product_id);
    $this->load->view('product_detail.html',$data);
  }
  function newProduct(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $data['user_id']= $session_data['user_id'];
    $this->load->library('form_validation');
    $this->form_validation->set_rules('type', 'type', 'required');
    $this->form_validation->set_rules('name', 'name', 'trim|required|max_length[30]');      
    $this->form_validation->set_rules('image', 'image', 'trim|required|max_length[70]');
    $this->form_validation->set_rules('brand', 'brand', 'rtrim|required|max_length[250]');      
    $this->form_validation->set_rules('model', 'model', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('price', 'price', 'trim|required');      
    $this->form_validation->set_rules('additional_info', 'additional_info', 'trim|required|text');
    $this->form_validation->set_rules('capacity', 'capacity', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('size', 'size', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('property', 'property', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('defect', 'defect', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('quality', 'quality', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('payment', 'payment', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('return_product', 'return_product', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('return_fee', 'return_fee', 'trim');      
    $this->form_validation->set_rules('packaging', 'packaging', 'trim|max_length[250]');
    $this->form_validation->set_rules('delevery_fee', 'delivery_fee', 'trim|max_length[250]');      
    $this->form_validation->set_rules('delivry_confirmation', 'delivry_confirmation', 'trim|max_length[250]');
    $this->form_validation->set_rules('tax', 'tax', 'trim|max_length[250]');  
    $this->form_validation->set_rules('quantity', 'quantity', 'trim'); 
    $this->form_validation->set_rules('end_datetime', 'end_datetime', 'trim'); 

    $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');

    if($this->form_validation->run() == FALSE)
    {
      $this->load->view('newProduct_form.html',$data);
    }
    else
    {
      $type = set_value('type');
      $name = set_value('name');      
      $image = set_value('image');
      $brand = set_value('brand');      
      $model = set_value('model');
      $price = set_value('price');      
      $additional_info = set_value('additional_info');
      $capacity = set_value('capacity');      
      $size = set_value('size');
      $property = set_value('property');      
      $defect = set_value('defect');
      $quality = set_value('quality');      
      $payment = set_value('payment');
      $return_product = set_value('return_product');
      $return_fee = set_value('return_fee');      
      $packaging = set_value('packaging');
      $delevery_fee = set_value('delivery_fee');      
      $delivry_confirmation = set_value('delivry_confirmation');
      $tax = set_value('tax');
      if($type == 1){ // direct product
        $quantity = set_value('quantity');
      }
      if($type == 2){ // bidding
        $end_datetime = set_value('end_datetime');
        $status = "auction start";
        $current_price = $price;
        $current_max_bid = 0;
        $current_win_cust_id = null;
        $bit_increment = 1;
      }

      // run insert model to write data to db
      if($type == 1){ // direct product
        $product_id=$this->product_model->newDirectProduct($name,$image,$brand,
          $model,$price,$additional_info,$capacity,$size,$property,
          $defect,$quality,$payment,$return_product,$return_fee,
          $packaging,$delevery_fee,$delivry_confirmation,$tax,$quantity,$data['user_id']);
      }
      if($type == 2){ // bidding
        $product_id=$this->product_model->newBiddingProduct($name,$image,$brand,
          $model,$price,$additional_info,$capacity,$size,$property,
          $defect,$quality,$payment,$return_product,$return_fee,
          $packaging,$delevery_fee,$delivry_confirmation,$tax,$end_datetime,
          $status,$current_price,$current_max_bid,$current_win_cust_id,
          $bit_increment,$data['user_id']);
      }
      
      if ($product_id!= null) // the information has therefore been successfully saved in the db
      {

        $this->session->set_flashdata("message","Product created!");
        redirect('product_c/viewProductDetail/'.$product_id); 
        
      }
      else
      {
        $this->session->set_flashdata("message","Creating Product Failed! Try Again.");
        redirect(current_url());
      }
    }
  }
  function editDirectProduct($product_id){
    $session_data = $this->session->userdata('logged_in');
    $data['user_id']= $session_data['user_id'];
    $this->load->library('form_validation');
    $this->form_validation->set_rules('name', 'name', 'trim|required|max_length[30]');      
    $this->form_validation->set_rules('image', 'image', 'trim|required|max_length[70]');
    $this->form_validation->set_rules('brand', 'brand', 'rtrim|required|max_length[250]');      
    $this->form_validation->set_rules('model', 'model', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('price', 'price', 'trim|required');      
    $this->form_validation->set_rules('additional_info', 'additional_info', 'trim|required|text');
    $this->form_validation->set_rules('capacity', 'capacity', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('size', 'size', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('property', 'property', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('defect', 'defect', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('quality', 'quality', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('payment', 'payment', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('return_product', 'return_product', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('return_fee', 'return_fee', 'trim');      
    $this->form_validation->set_rules('packaging', 'packaging', 'trim|max_length[250]');
    $this->form_validation->set_rules('delevery_fee', 'delivery_fee', 'trim|max_length[250]');      
    $this->form_validation->set_rules('delivry_confirmation', 'delivry_confirmation', 'trim|max_length[250]');
    $this->form_validation->set_rules('tax', 'tax', 'trim|max_length[250]');  
    $this->form_validation->set_rules('quantity', 'quantity', 'trim');

    $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
    $data['product'] = $this->product_model->getDetail($product_id);
    if($this->form_validation->run() == FALSE)
    {
      $this->load->view('editDirectProduct_form.html',$data);
    }
    else
    {
      
      $newProduct = array(
        'type' = set_value('type'),
        'name' = set_value('name'),      
        'image' = set_value('image'),
        'brand' = set_value('brand'),      
        'model' = set_value('model'),
        'price' = set_value('price'),      
        'additional_info' = set_value('additional_info'),
        'capacity' = set_value('capacity'),      
        'size' = set_value('size'),
        'property' = set_value('property'),      
        'defect' = set_value('defect'),
        'quality' = set_value('quality'),      
        'payment' = set_value('payment'),
        'return_product' = set_value('return_product'),
        'return_fee' = set_value('return_fee'),      
        'packaging' = set_value('packaging'),
        'delevery_fee' = set_value('delivery_fee'),      
        'delivry_confirmation' = set_value('delivry_confirmation'),
        'tax' = set_value('tax'),
        'quantity' = set_value('quantity')
      );

      // run insert model to write data to db
        $result=$this->product_model->editDirectProduct($newProduct);
      
      if ($result!= null) // the information has therefore been successfully saved in the db
      {

        $this->session->set_flashdata("message","Product edited!");
        redirect('product_c/viewProductDetail/'.$product_id); 
        
      }
      else
      {
        $this->session->set_flashdata("message","Editing Product Failed! Try Again.");
        redirect(current_url());
      }
    }

  }
  function editBiddingProduct($product_id){
    $session_data = $this->session->userdata('logged_in');
    $data['user_id']= $session_data['user_id'];
    $this->load->library('form_validation');
    $this->form_validation->set_rules('name', 'name', 'trim|required|max_length[30]');      
    $this->form_validation->set_rules('image', 'image', 'trim|required|max_length[70]');
    $this->form_validation->set_rules('brand', 'brand', 'rtrim|required|max_length[250]');      
    $this->form_validation->set_rules('model', 'model', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('price', 'price', 'trim|required');      
    $this->form_validation->set_rules('additional_info', 'additional_info', 'trim|required|text');
    $this->form_validation->set_rules('capacity', 'capacity', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('size', 'size', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('property', 'property', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('defect', 'defect', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('quality', 'quality', 'trim|required|max_length[250]');      
    $this->form_validation->set_rules('payment', 'payment', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('return_product', 'return_product', 'trim|required|max_length[250]');
    $this->form_validation->set_rules('return_fee', 'return_fee', 'trim');      
    $this->form_validation->set_rules('packaging', 'packaging', 'trim|max_length[250]');
    $this->form_validation->set_rules('delevery_fee', 'delivery_fee', 'trim|max_length[250]');      
    $this->form_validation->set_rules('delivry_confirmation', 'delivry_confirmation', 'trim|max_length[250]');
    $this->form_validation->set_rules('tax', 'tax', 'trim|max_length[250]');
    $this->form_validation->set_rules('end_datetime', 'end_datetime', 'trim');
    $this->form_validation->set_rules('status','status','');
    $this->form_validation->set_rules('current_price','current_price','');
    $this->form_validation->set_rules('current_max_bid','current_max_bid','');
    $this->form_validation->set_rules('current_win_cust_id','current_win_cust_id','');
    $this->form_validation->set_rules('bit_increment','bit_increment','');

    $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
    $data['product'] = $this->product_model->getDetail($product_id);

    if($this->form_validation->run() == FALSE)
    {
      $this->load->view('editProduct_form.html',$data);
    }
    else
    {
      $newProduct = array(
        'type' = set_value('type'),
        'name' = set_value('name'),      
        'image' = set_value('image'),
        'brand' = set_value('brand'),      
        'model' = set_value('model'),
        'price' = set_value('price'),      
        'additional_info' = set_value('additional_info'),
        'capacity' = set_value('capacity'),      
        'size' = set_value('size'),
        'property' = set_value('property'),      
        'defect' = set_value('defect'),
        'quality' = set_value('quality'),      
        'payment' = set_value('payment'),
        'return_product' = set_value('return_product'),
        'return_fee' = set_value('return_fee'),      
        'packaging' = set_value('packaging'),
        'delevery_fee' = set_value('delivery_fee'),      
        'delivry_confirmation' = set_value('delivry_confirmation'),
        'tax' = set_value('tax'),
        'end_datetime' = set_value($data['product']->end_datetime),
        'status' = set_value($data['product']->status),
        'current_price' = set_value($data['product']->current_price),
        'current_max_bid' = set_value($data['product']->current_max_bid),
        'current_win_cust_id' = set_value($data['product']->current_win_cust_id,
        'bit_increment' = set_value($data['product']->bit_increment)
      )
      

      // run insert model to write data to db
        $product_id=$this->product_model->newBiddingProduct($newProduct);
      
      if ($product_id!= null) // the information has therefore been successfully saved in the db
      {

        $this->session->set_flashdata("message","Product edited!");
        redirect('product_c/viewProductDetail/'.$product_id); 
        
      }
      else
      {
        $this->session->set_flashdata("message","Editing Product Failed! Try Again.");
        redirect(current_url());
      }
    }

  }
  function deleteProduct($product_id){
    $session_data = $this->session->userdata('logged_in');
    $temp = $this->product_model->deleteProduct($session_data['user_id'],$product_id);
    if($temp){
      $this->session->set_flashdata("message","Product was deleted");
    }else{
      $this->session->set_flashdata("message","Deleted fail!");
    }
    redirect('home_c');
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