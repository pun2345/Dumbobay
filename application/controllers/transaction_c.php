<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->database();
    $this->load->helper('html');
    $this->load->helper('form');
    $this->load->model('transaction_m');
    $this->load->model('product_m');
    $this->load->model('member_m');
  }

  function index()
  {
    //This method will have the credentials validation
    
  }

  function history(){
    $this->isLogin();
    $session_data = $this->session->userdata('logged_in');
    $data['user_id'] = $session_data['user_id'];
    $data['username'] = $session_data['username'];
    $data['type'] = $session_data['type'];
    $data['transactions'] = $this->transaction_m->getHistoryCustTransaction($data['user_id']);
    //print_r($data['transactions']->result());
    $this->load->view('history.html',$data);
        // $this->load->view('footer.html');
  }
  function feedback($transaction_id){
    $this->loginBeforeFeedback();
    $data['transaction'] = $this->transaction_m->getTransactionDetail($transaction_id);

    $session_data = $this->session->userdata('logged_in');
    $data['user_id'] = $session_data['user_id'];
    $data['username'] = $session_data['username'];
    $data['type'] = $session_data['type'];
    $seller = $this->member_m->getMemberDetail($data['transaction']->Seller_ID);
    $buyer = $this->member_m->getMemberDetail($data['transaction']->Buyer_ID);
    $this->transaction_m->saveFeedbackBuyer($transaction_id,$this->input->post('rate'),$this->input->post('comment'));
    redirect('transaction_c/history/'.$user_id);
    //$this->input->post('rate');
    //echo $this->input->post('comment');
    // if($data['user_id']==$seller){
    //   $data['other'] = $seller->Username;
    // }else{
    //   $data['other'] = $buyer->Username;
    // }
    // $this->load->library('form_validation');
    // $this->form_validation->set_rules('score', 'score', 'required|xss_clean');      
    // $this->form_validation->set_rules('feedback', 'feedback', 'trim|max_length[200]');

    // $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
  
    // if($this->form_validation->run() == FALSE)
    // {

    //   // $data['transactionID']= $transactionID;
    //   $this->load->view('feedback_form.html',$data);
    //     // $this->load->view('footer.html');
    // }
    // else
    // {
    //   $score = $this->input->post('score');
    //   $feedback = $this->input->post('feedback');
    //   // run insert model to write data to db
    //   if($data['user_id']==$seller){
    //     if ($this->transaction_m->saveFeedbackBuyer($transactionID,$score,$feedback) == TRUE) // the information has therefore been successfully saved in the db
    //     {
    //       $this->session->set_flashdata("message","Feedback saved!");
    //       redirect('transaction_c/history/'.$user_id); 
    //     }
    //     else
    //     {
    //       $this->session->set_flashdata("message","Feedback Failed! Try Again.");
    //       redirect('transaction_c/history/'.$user_id);
    //     }
    //   }else{
    //     if ($this->transaction_m->saveFeedbackSeller($transactionID,$score,$feedback) == TRUE) // the information has therefore been successfully saved in the db
    //     {
    //       $this->session->set_flashdata("message","Feedback saved!");
    //       redirect('transaction_c/history/'.$user_id); 
    //     }
    //     else
    //     {
    //       $this->session->set_flashdata("message","Feedback Failed! Try Again.");
    //       redirect('transaction_c/history/'.$user_id);
    //     }
    //   }
      
    // }
  }
  function loginBeforeFeedback(){
    if($this->session->userdata('logged_in')){
      return true;
    } else{
      $this->session->set_flashdata("message","Please Login!");
      $this->session->set_flashdata("beforePay",current_url());
      redirect('login_c');
    }
  }
  function updateStatus($transaction_id){
    $session_data = $this->session->userdata('logged_in');
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $data['type'] = $session_data['type'];
    $transaction = $this->transaction_m->getTransactionDetail($transaction_id);
    $product = $this->product_m->getDetail($transaction->product_id);
    $data['productName']=$product->name;
    $this->load->library('form_validation');
    $this->form_validation->set_rules('status', 'Status', 'required|max_length[30]|xss_clean');      
    $this->form_validation->set_rules('status_detail', 'Status_detail', 'trim');

    $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');

    if($this->form_validation->run() == FALSE)
    {
      $this->load->view('update_status_form',$data);
        // $this->load->view('footer.html');
    }
    else
    {
      
      // $points = $this->input->post('points');
      // $comment = $this->input->post('comment');
      // run insert model to write data to db
      $temp=$this->transaction_m->updateStatus($transaction_id,$status,$status_detail);
      if ($temp == "true") // the information has therefore been successfully saved in the db
      {
        $this->session->set_flashdata("message","Status updated!");
        redirect('transaction_c/viewTransactionDetail/'.$transaction_id); 
        // $this->load->view('footer.html');
      }
      else
      {
        $this->session->set_flashdata("message","Updating Status Failed! Try Again.");
        redirect("transacion_c/viewTransactionDetail/".$transaction_id);
      }
    }
  }
  // function viewTransactionDetail($transaction_id){
  //   $session_data = $this->session->userdata('logged_in');
  //     $data['user_id'] = $session_data['user_id'];
  //     $data['username'] = $session_data['username'];
  //     $data['type'] = $session_data['type'];
  //   $data['transaction'] = $this->transaction_m->getTransactionDetail($transaction_id);
  //   $data['product'] = $this->product_m->getDetail($data['transaction']->product_id);
  //   $this->load->view('transaction_detail',$data);
  //       // $this->load->view('footer.html');
  // }
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