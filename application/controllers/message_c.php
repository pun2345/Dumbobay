<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class message_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('message_m');
    $this->load->model('member_m');
    $this->load->library('form_validation');
    $this->load->helper('html');
  }

  function index()
  {

  }

  function sendMessage()
  {
      $session_data = $this->session->userdata('logged_in');
      $senderID=$session_data['user_id'];
      $data['type'] = $session_data['type'];
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $this->form_validation->set_rules('msgSubject', 'msgSubject', 'require|css_clean|max_length[30]');
      $this->form_validation->set_rules('msgReceiver', 'msgReceiver', 'require|css_clean');
      $this->form_validation->set_rules('msgText', 'msgText', 'max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view('newMessage.html',$data);
          
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array( 'msgSubject' => $this->input->post('msgSubject'),
                              'msgText' => $this->input->post('msgText'),
                              'msgReceiver' => $this->input->post('msgReceiver'));      // run insert model to write data to db
          if ($this->message_m->createMessage($senderID,$form_data['msgSubject'],$form_data['msgText'],$form_data['msgReceiver']) == 'true') // the information has therefore been successfully saved in the db
          {             
              $this->session->set_flashdata("message","Message sent!");
              redirect('message_c/manageMessageBox');   // or whatever logic needs to occur
          }
          else
          {
              $this->session->set_flashdata("message","Sending error");
              redirect('message_c/manageMessageBox');
          }
      }
  }

  function sendMessageTo($receiver_id)
  {
      $session_data = $this->session->userdata('logged_in');
      $senderID=$session_data['user_id'];
      $data['type'] = $session_data['type'];
      $this->form_validation->set_rules('msgSubject', 'msgSubject', 'require|css_clean|max_length[30]');
      $this->form_validation->set_rules('msgText', 'msgText', 'max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view('newMessage.html',$data);
          
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array( 'msgSubject' => $this->input->post('msgSubject'),
                              'msgText' => $this->input->post('msgText'));
          // run insert model to write data to db
          if ($this->message_m->createMessage($senderID,$form_data['msgSubject'],$form_data['msgText'],$receiver_id) == 'true') // the information has therefore been successfully saved in the db
          {             
              $this->session->set_flashdata("message","Message sent!");
              redirect('message_c/manageMessageBox');   // or whatever logic needs to occur
          }
          else
          {
              $this->session->set_flashdata("message","Sending error");
              redirect(current_url());
          }
      }
  }

  function reportProblem($problem)
  {
      $adminID = 0;
      redirect('message_c/sendMessageTo',$adminID);
  }

  function manageMessageBox()
  {
      $session_data = $this->session->userdata('logged_in');
      $data['user_id']=$session_data['user_id'];
<<<<<<< HEAD
      $data['user_id'] = 6;
      $data['type'] = $session_data['type'];
      $data['type'] = 2;
=======
      $data['type'] = $session_data['type'];
>>>>>>> 427f5d8c29d07e39f47c7c5979300318320e1415
      $data['username'] = $session_data['username'];
      $data['messages'] = $this->message_m->getUserMessage($data['user_id']);
      foreach ($data['messages']->result() as $msg){
        $x = $this->member_m->getMemberDetail($msg->Sender_ID);
        $msg->Sender_Name = $x->Username;
      }
      $this->load->view('messageBox.html',$data);
      
  }

  function messageDetail($message_id)
  {
      $session_data = $this->session->userdata('logged_in');
      $data['user_id']=$session_data['user_id'];
      $data['type'] = $session_data['type'];
      $data['username'] = $session_data['username'];
      $data['message'] = $this->message_m->getMessage($message_id);
      $msg = $data['message']->result();
      $x = $this->member_m->getMemberDetail($msg->Sender_ID);
      $msg->Sender_Name = $x->Username;
      $this->load->view('viewMessage.html',$data);
      
  }

  function reply($message_id)
  {
      $session_data = $this->session->userdata('logged_in');
      $data['user_id']=$session_data['user_id'];
      $data['type'] = $session_data['type'];
      $data['username'] = $session_data['username'];
      $data['receiver_id'] = $this->message_m->getSender($message_id);
      $data['subject'] = $this->message_m->getSubject($message_id);
      if(strlen($data['subject'])>26) $data['subject'] = substr($data['subject'],0,23) . "..";
      $this->load->view('replyMessage.html',$data);
      
      $this->form_validation->set_rules('msgText', 'msgText', 'max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view('replyMessage.html',$data);
          
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array('msgText' => $this->input->post('msgText'));
              // run insert model to write data to db
          if ($this->message_m->createMessage($user_id,$data['subject'],$form_data['msgText'],$data['receiver_id']) == 'true') // the information has therefore been successfully saved in the db
          {
              $this->session->set_flashdata("message","Message sent!");
              redirect('message_c/manageMessageBox');   // or whatever logic needs to occur
          }
          else
          {
              $this->session->set_flashdata("message","Sending error");
              redirect(current_url());
          }
      }
  }

  function delete($message_id)
  {
      $session_data = $this->session->userdata('logged_in');
      $this->message_m->deleteMessage($message_id);
      redirect('message_c/manageMessageBox');
      // refresh view duay na 
  }

}
?>