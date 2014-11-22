<<<<<<< HEAD
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Home_c extends CI_Controller {

    public $message=null;
  function __construct()
  {
    parent::__construct();
    $this->load->helper("html");
    $this->load->helper('html');
    // $this->load->database();
    $this->load->model('member_m');
  }

  function index()
  {
    if($this->session->userdata('logged_in'))
    {
      $session_data = $this->session->userdata('logged_in');
      $data['username'] = $session_data['username'];
      $user_type = $this->member_m->checkUserType($session_data['user_id']);
      if($user_type == 1){
        $this->load->view('admin_home.html',$data);
        $this->load->view('footer.html');
      }elseif($user_type == 2){
        $this->load->view('buyer_home.html',$data);
        $this->load->view('footer.html');
      }elseif($user_type == 3){
        $this->load->view('seller_home.html',$data);
        $this->load->view('footer.html');
      }
    }
    else
    {
      //If no session, redirect to login page
      $this->load->view('index.html');
      $this->load->view('footer.html');
    }
  }
  function product(){
    redirect('product_c');
  }
  function cart(){
    redirect('cart_c');
  }
  function search($searchWord)
  {
    redirect('product_c/search/'.$searchWord);
  }
  function report()
  {
    $this->isLogin();
    redirect('report_c');
  }
  function register()
  {
    redirect('member_c/createMember');
  }
  function problem_report(){
    $this->isLogin();
    redirect('problem_report_c');
  }
  function history(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $user_id = $session_data['user_id'];
    redirect('transaction_c/history/'.$user_id);
  }
  function watchlist(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $user_id = $session_data['user_id'];
    redirect('watchlist_c');
  }
  function memberDetail(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $user_id = $session_data['user_id'];
    redirect('member_c/memberDetail/'.$user_id);
  }
  function sendMessage(){
    $this->isLogin();
    redirect('message_c/sendMessage');
    
  }
  function message(){
    $this->isLogin();
    redirect('message_c/manageMessageBox');
  }
  
  function logout()
  {
    $this->session->unset_userdata('logged_in');
    session_destroy();
    redirect('home_c', 'refresh');
  }
  function isLogin(){
    if($this->session->userdata('logged_in')){
      return true;
    } else{
      $this->session->set_flashdata("message","Please Login!");
      redirect('home_c');
    }
  }

  function login()
  {
    //This method will have the credentials validation
    redirect('login_c');
    
  }
  
  

}

=======
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Home_c extends CI_Controller {

    public $message=null;
  function __construct()
  {
    parent::__construct();
    $this->load->helper("html");
    $this->load->helper('html');
    $this->load->database();
    $this->load->model('member_m');
  }

  function index()
  {
    if($this->session->userdata('logged_in'))
    {
      $session_data = $this->session->userdata('logged_in');
      $data['username'] = $session_data['username'];
      $user_type = $session_data['type'];

      if($user_type == 1){
        $this->load->view('admin_home.html',$data);
        $this->load->view('footer.html');
      }elseif($user_type == 2){
        $this->load->view('buyer_home.html',$data);
        $this->load->view('footer.html');
      }elseif($user_type == 3){
        $this->load->view('seller_home.html',$data);
        $this->load->view('footer.html');
      }
    }
    else
    {
      //If no session, redirect to login page
      $this->load->view('index.html');
      $this->load->view('footer.html');
    }
  }
  function product(){
    redirect('product_c');
  }
  function cart(){
    redirect('cart_c');
  }
  function search($searchWord)
  {
    redirect('product_c/search/'.$searchWord);
  }
  function report()
  {
    $this->isLogin();
    redirect('report_c');
  }
  function register()
  {
    redirect('member_c/createMember');
  }
  function problem_report(){
    $this->isLogin();
    redirect('problem_report_c');
  }
  function history(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $user_id = $session_data['user_id'];
    redirect('transaction_c/history/'.$user_id);
  }
  function watchlist(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $user_id = $session_data['user_id'];
    redirect('watchlist_c');
  }
  function memberDetail(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $user_id = $session_data['user_id'];
    redirect('member_c/memberDetail/'.$user_id);
  }
  function sendMessage(){
    $this->isLogin();
    redirect('message_c/sendMessage');
    
  }
  function message(){
    $this->isLogin();
    redirect('message_c/manageMessageBox');
  }
  
  function logout()
  {
    $this->session->unset_userdata('logged_in');
    session_destroy();
    redirect('home_c', 'refresh');
  }
  function isLogin(){
    if($this->session->userdata('logged_in')){
      return true;
    } else{
      $this->session->set_flashdata("message","Please Login!");
      redirect('home_c');
    }
  }

  function login()
  {
    //This method will have the credentials validation
    redirect('login_c');
    
  }
  
  

}

>>>>>>> 4ce7334c68ec18bae9d7a5582761f7090b558ac4
?>