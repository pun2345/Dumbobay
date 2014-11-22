<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->helper('html');

    $this->load->database();

    $this->load->model('member_m');
  }

  function index()
  {
    //This method will have the credentials validation
    $this->load->library('form_validation');

    $this->form_validation->set_rules('username', 'username', 'trim|required|xss_clean');
    $this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean|callback_check_database');
	
    if($this->form_validation->run() == FALSE)
    {
      //Field validation failed.  User redirected to login page
      $this->load->view('signin.html');
    }
    else
    {
      //Go to private area
      redirect('home_c', 'refresh');
    }
    
  }
  
  function check_database($password)
  {
    //Field validation succeeded.  Validate against database
    $username = $this->input->post('username');
    
    $password = $this->input->post('password');
    //echo $username." ";
    //echo $password." ";
    //query the database
    $result = $this->member_m->checkLogin($username, $password);
    print_r($result);
    if($result)
    //print_r($result);
    if($result->user_id != 0)
    {
      if($result->activated=="true"){ 
        print_r("true");
        $sess_array = array(
            'user_id' => $result->user_id,
            'username' => $result->username,
          );
          $this->session->set_userdata('logged_in', $sess_array);
        
        return TRUE;
      //if($result->activated=="true"){ 
      print_r($result);
      echo $result->user_id;
      echo $result->username;
      $sess_array = array(
          'user_id' => $result->user_id,
          'username' => $result->username
        );
        $this->session->set_userdata('logged_in', $sess_array);
      
      return TRUE;
      //}else{
        //$this->form_validation->set_message('check_database', 'Please Activate your account first.');
        //return false;
      //}
      }
      else
      {
        // $this->session->set_flashdata("message",'Invalid username or password');
        $this->form_validation->set_message('check_database', 'Invalid username or password');
        return false;
      }
    }
    else
    {
      // $this->session->set_flashdata("message",'Invalid username or password');
      $this->form_validation->set_message('check_database', 'Invalid username or password');
      return false;
    }
    
  }
}
?>