<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->database();
    $this->load->helper('form');
    $this->load->model('transaction_model');
    $this->load->model('product_model');
  }

  function index()
  {
    //This method will have the credentials validation
    
  }

  function history($user_id){
    $this->isLogin();
    $data['transactions'] = $this->transaction_model->getTransaction($user_id);
    $this->load->view('history',$data);
  }
  function feedback($transactionID){
    //$this->isLogin();
    $data['transaction'] = $this->transaction_model->getTransactionDetail($transaction_id);
    $session_data = $this->session->userdata('logged_in');
    $data['user_id']= $session_data['user_id'];
    $this->load->library('form_validation');
    $this->form_validation->set_rules('score', 'score', 'required|xss_clean');      
    $this->form_validation->set_rules('feedback', 'feedback', 'trim|max_length[200]');

    $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');
  
    if($this->form_validation->run() == FALSE)
    {

      $data['transactionID']= $transactionID;
      $this->load->view('feedback_form',$data);
    }
    else
    {
      $score = set_value('score');
      $feedback = set_value('feedback');
      // run insert model to write data to db
      if ($this->transaction_model->saveFeedback($transactionID,$user_id,$score,$feedback) == TRUE) // the information has therefore been successfully saved in the db
      {
        $this->session->set_flashdata("message","Feedback saved!");
        redirect('transaction/history/'.$user_id); 
      }
      else
      {
        $this->session->set_flashdata("message","Feedback Failed! Try Again.");
        redirect('transaction/history/'.$user_id);
      }
    }
  }
  function updateStatus($transaction_id){
    $transaction = $this->transaction_model->getTransactionDetail($transaction_id);
    $product = $this->product_model->getDetail($transaction->product_id);
    $data['productName']=$product->name;
    $this->load->library('form_validation');
    $this->form_validation->set_rules('status', 'Status', 'required|max_length[30]|xss_clean');      
    $this->form_validation->set_rules('status_detail', 'Status_detail', 'trim');

    $this->form_validation->set_error_delimiters('<br /><span class="error">', '</span>');

    if($this->form_validation->run() == FALSE)
    {
      $this->load->view('update_status_form',$data);
    }
    else
    {
      // $points = set_value('points');
      // $comment = set_value('comment');
      // run insert model to write data to db
      $temp=$this->transaction_model->updateStatus($transaction_id,$status,$status_detail);
      if ($temp == true) // the information has therefore been successfully saved in the db
      {
        $this->session->set_flashdata("message","Status updated!");
        redirect('transaction/viewTransactionDetail/'.$transaction_id); 
      }
      else
      {
        $this->session->set_flashdata("message","Updating Status Failed! Try Again.");
        redirect(current_url());
      }
    }
  }
  function viewTransactionDetail($transaction_id){
    $session_data = $this->session->userdata('logged_in');
    $data['transaction'] = $this->transaction_model->getTransactionDetail($transaction_id);
    $data['product'] = $this->product_model->getDetail($data['transaction']->product_id);
    $this->load->view('transaction_detail',$data);
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