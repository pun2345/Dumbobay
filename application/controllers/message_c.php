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
      $this->form_validation->set_rules('msgText', 'msgText', 'require|max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view('newMessage.html',$data);
          
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array( 'msgSubject' => $this->input->post('msgSubject'),
                              'msgText' => $this->input->post('msgText'),
                              'msgReceiver' => $this->input->post('msgReceiver'));      // run insert model to write data to db
          $receiverID = $this->member_m->getUserID($form_data['msgReceiver']);
          if($receiverID == null){
              $this->session->set_flashdata("message","The user is not available!");
              redirect('message_c/sendMessage'); 
          }
          else if ($this->message_m->createMessage($senderID,$form_data['msgSubject'],$form_data['msgText'],$receiverID) == 'true') // the information has therefore been successfully saved in the db
          {             
              $this->session->set_flashdata("message","Message sent!");
              redirect('message_c/manageMessageBox');   // or whatever logic needs to occur
          }
          else
          {
              $this->session->set_flashdata("message","All Information need to be completed");
              redirect('message_c/manageMessageBox');
          }
      }
  }

  function sendMessageTo($receiver_id)
  {
      $session_data = $this->session->userdata('logged_in');
      $senderID=$session_data['user_id'];
      $data['type'] = $session_data['type'];
      $data['user_id'] = $session_data['user_id'];
      $data['username'] = $session_data['username'];
      $this->form_validation->set_rules('msgSubject', 'msgSubject', 'require|css_clean|max_length[30]');
      $this->form_validation->set_rules('msgText', 'msgText', 'require|max_length[200]');
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
              $this->session->set_flashdata("message","All Information need to be completed");
              redirect(current_url());
          }
      }
  }

  function manageMessageBox()
  {
      $session_data = $this->session->userdata('logged_in');
      $data['user_id']=$session_data['user_id'];
      $data['type'] = $session_data['type'];
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
      $msg = $data['message'];
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
      $tmp = $this->member_m->getMemberDetail($data['receiver_id']);
      $data['receiverName'] = $tmp->Username;
      $data['message_id'] = $message_id;
      $data['subject'] = $this->message_m->getSubject($message_id);
      if(strlen($data['subject'])>24) $data['subject'] = "RE: " . substr($data['subject'],0,22) . "..";
      else $data['subject'] = "RE: " . $data['subject'];

      $this->form_validation->set_rules('msgText', 'msgText', 'max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view('replyMessage.html',$data);
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array('msgText' => $this->input->post('msgText'));
              // run insert model to write data to db
          if ($this->message_m->createMessage($data['user_id'],$data['subject'],$form_data['msgText'],$data['receiver_id']) == 'true') // the information has therefore been successfully saved in the db
          {
              $this->session->set_flashdata("message","Message sent!");
              redirect('message_c/manageMessageBox');   // or whatever logic needs to occur
          }
          else
          {
              echo $message_id;
              $this->session->set_flashdata("message","Sending error");
              redirect('message_c/reply/',$message_id);
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