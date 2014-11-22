<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');    
    $this->load->helper('html');
    $this->load->database();
    $this->load->helper('form');
    $this->load->model('product_m');
  }

  function index()
  {
    //This method will have the credentials validation
    $page_num=1;
    $data['products'] = $this->product_m->allProduct($page_num);
    $data['type'] = 0;
    if($this->session->userdata('logged_in'))
    {
      $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];

        $this->load->view('product.html',$data);
        $this->load->view('footer.html');
    }
    else
    {
      //If no session, redirect to login page
      $this->load->view('product.html',$data);
      $this->load->view('footer.html');
    }
  }
  function nextpage($page_num){
     $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $data['products'] = $this->product_m->allProduct($page_num);
    $this->load->view('product.html',$data);
    $this->load->view('footer.html');
  }
  function register()
  {
    redirect('member_c/createMember');
  }
  function search($searchWord){

     $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $data['products'] = $this->product_m->search($searchWord);
    $this->load->view('product.html',$data);
    $this->load->view('footer.html');
  }
  function viewProductDetail($product_id){

     $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $data['product'] = $this->product_m->getDetail($product_id);
    $this->load->view('productDesc.html',$data);
    $this->load->view('footer.html');
  }
  function newProduct(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $data['user_id'] = $session_data['user_id'];
    $data['username'] = $session_data['username'];
    $data['type'] = $session_data['type'];
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
    $this->load->view('footer.html');
    }
    else
    {
      $type = $this->input->post('type');
      $name = $this->input->post('name');      
      $image = $this->input->post('image');
      $brand = $this->input->post('brand');      
      $model = $this->input->post('model');
      $price = $this->input->post('price');      
      $additional_info = $this->input->post('additional_info');
      $capacity = $this->input->post('capacity');      
      $size = $this->input->post('size');
      $property = $this->input->post('property');      
      $defect = $this->input->post('defect');
      $quality = $this->input->post('quality');      
      $payment = $this->input->post('payment');
      $return_product = $this->input->post('return_product');
      $return_fee = $this->input->post('return_fee');      
      $packaging = $this->input->post('packaging');
      $delevery_fee = $this->input->post('delivery_fee');      
      $delivry_confirmation = $this->input->post('delivry_confirmation');
      $tax = $this->input->post('tax');
      if($type == 1){ // direct product
        $quantity = $this->input->post('quantity');
      }
      if($type == 2){ // bidding
        $end_datetime = $this->input->post('end_datetime');
        $status = "auction start";
        $current_price = $price;
        $current_max_bid = 0;
        $current_win_cust_id = null;
        $bit_increment = 1;
      }

      // run insert model to write data to db
      if($type == 1){ // direct product
        $product_id=$this->product_m->newDirectProduct($name,$image,$brand,
          $model,$price,$additional_info,$capacity,$size,$property,
          $defect,$quality,$payment,$return_product,$return_fee,
          $packaging,$delevery_fee,$delivry_confirmation,$tax,$quantity,$data['user_id']);
      }
      if($type == 2){ // bidding
        $product_id=$this->product_m->newBiddingProduct($name,$image,$brand,
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
    $data['user_id'] = $session_data['user_id'];
    $data['username'] = $session_data['username'];
    $data['type'] = $session_data['type'];
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
    $data['product'] = $this->product_m->getDetail($product_id);
    if($this->form_validation->run() == FALSE)
    {
      $this->load->view('editDirectProduct_form.html',$data);
    $this->load->view('footer.html');
    }
    else
    {
      
      $newProduct = array(
        'type' => $this->input->post('type'),
        'name' => $this->input->post('name'),      
        'image' => $this->input->post('image'),
        'brand' => $this->input->post('brand'),      
        'model' => $this->input->post('model'),
        'price' => $this->input->post('price'),      
        'additional_info' => $this->input->post('additional_info'),
        'capacity' => $this->input->post('capacity'),      
        'size' => $this->input->post('size'),
        'property' => $this->input->post('property'),      
        'defect' => $this->input->post('defect'),
        'quality' => $this->input->post('quality'),      
        'payment' => $this->input->post('payment'),
        'return_product' => $this->input->post('return_product'),
        'return_fee' => $this->input->post('return_fee'),      
        'packaging' => $this->input->post('packaging'),
        'delevery_fee' => $this->input->post('delivery_fee'),      
        'delivry_confirmation' => $this->input->post('delivry_confirmation'),
        'tax' => $this->input->post('tax'),
        'quantity' => $this->input->post('quantity')
      );

      // run insert model to write data to db
        $result=$this->product_m->editDirectProduct($newProduct);
      
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
    $data['user_id'] = $session_data['user_id'];
    $data['username'] = $session_data['username'];
    $data['type'] = $session_data['type'];
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
    $data['product'] = $this->product_m->getDetail($product_id);

    if($this->form_validation->run() == FALSE)
    {
      $this->load->view('editProduct_form.html',$data);
    $this->load->view('footer.html');
    }
    else
    {
      $newProduct = array(
        'type' => $this->input->post('type'),
        'name' => $this->input->post('name'),      
        'image' => $this->input->post('image'),
        'brand' => $this->input->post('brand'),      
        'model' => $this->input->post('model'),
        'price' => $this->input->post('price'),      
        'additional_info' => $this->input->post('additional_info'),
        'capacity' => $this->input->post('capacity'),      
        'size' => $this->input->post('size'),
        'property' => $this->input->post('property'),      
        'defect' => $this->input->post('defect'),
        'quality' => $this->input->post('quality'),      
        'payment' => $this->input->post('payment'),
        'return_product' => $this->input->post('return_product'),
        'return_fee' => $this->input->post('return_fee'),      
        'packaging' => $this->input->post('packaging'),
        'delevery_fee' => $this->input->post('delivery_fee'),      
        'delivry_confirmation' => $this->input->post('delivry_confirmation'),
        'tax' => $this->input->post('tax'),
        'end_datetime' => $this->input->post($data['product']->end_datetime),
        'status' => $this->input->post($data['product']->status),
        'current_price' => $this->input->post($data['product']->current_price),
        'current_max_bid' => $this->input->post($data['product']->current_max_bid),
        'current_win_cust_id' => $this->input->post($data['product']->current_win_cust_id),
        'bit_increment' => $this->input->post($data['product']->bit_increment)
      );
      

      // run insert model to write data to db
        $product_id=$this->product_m->newBiddingProduct($newProduct);
      
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
    $temp = $this->product_m->deleteProduct($product_id);
    if($temp="true"){
      $this->session->set_flashdata("message","Product was deleted");
    }else{
      $this->session->set_flashdata("message","Deleted fail!");
    }
    redirect('home_c');
  }
  function maxBiddingForm(){
    $this->load->library('form_validation');
    $this->form_validation->set_rules('maxbid', 'maxbid', 'trim|required');

  }

  function joinMaxBidding($product_id){

  } 
  function joinStepBidding($product_id){
    redirect("bidding_c/initializeStepBidding/".$product_id);
  }
  
  // redirect("bidding_c/initializeMaxBidding/"$product_id)
  
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