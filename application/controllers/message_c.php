<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class message_c extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('message_m');
    $this->load->library('form_validation');
  }

  function index()
  {

  }

  function sendMessage()
  {
      $session_data = $this->session->userdata(logged_in);
      $senderID=$session_data['user_id'];
      $this->load->view('newMessage.html');
      $this->form_validation->set_rules('msgSubject', 'msgSubject', 'require|css_clean|max_length[30]');
      $this->form_validation->set_rules('msgReceiver', 'msgReceiver', 'require|css_clean');
      $this->form_validation->set_rules('msgText', 'msgText', 'max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view('newMessage.html');
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array( 'msgSubject' => set_value('msgSubject'),
                              'msgText' => set_value('msgText'),
                             'msgReceiver' => set_value('msgReceiver'));
      }
      // run insert model to write data to db
      if ($this->message_m->createMessage($senderID,$form_data['msgSubject'],$form_data['msgText'],$form_data['msgReceiver']) == TRUE) // the information has therefore been successfully saved in the db
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

  function sendMessageTo($receiver_id)
  {
      $session_data = $this->session->userdata(logged_in);
      $senderID=$session_data['user_id'];
      $this->load->view('newMessage.html');
      $this->form_validation->set_rules('msgSubject', 'msgSubject', 'require|css_clean|max_length[30]');
      $this->form_validation->set_rules('msgText', 'msgText', 'max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view('newMessage.html');
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array( 'msgSubject' => set_value('msgSubject'),
                              'msgText' => set_value('msgText'));
      }
      // run insert model to write data to db
      if ($this->message_m->createMessage($senderID,$form_data['msgSubject'],$form_data['msgText'],$receiver_id) == TRUE) // the information has therefore been successfully saved in the db
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

  function reportProblem($problem)
  {
      $adminID = 0;
      redirect('message_c/sendMessageTo',$adminID);
  }

  function manageMessageBox()
  {
      $session_data = $this->session->userdata(logged_in);
      $data['user_id']=$session_data['user_id'];
      $data['messages'] = $this->message_m->getUserMessage($data['user_id
']);
      $this->load->view('messageBox.html/',$data);
  }

  function messageDetail($message_id)
  {
      $session_data = $this->session->userdata(logged_in);
      $data['user_id']=$session_data['user_id'];
      $data['message'] = $this->message_m->getMessage($message_id);
      $this->load->view('viewMessage.html/',$data);
  }

  function reply($message_id)
  {
      $session_data = $this->session->userdata(logged_in);
      $data['user_id']=$session_data['user_id'];
      $data['receiver_id'] = $this->message_m->getSender($message_id);
      $data['subject'] = $this->message_m->getSubject($message_id);
      if(strlen($data['subject'])>26) $data['subject'] = substr($data['subject'],0,23) . "..";
      $this->load->view('replyMessage.html/',$data);
      $this->form_validation->set_rules('msgText', 'msgText', 'max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view('replyMessage.html/',$data);
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array('msgText' => set_value('msgText'));
      }
              // run insert model to write data to db
      if ($this->message_m->createMessage($user_id,$data['subject'],$form_data['msgText'],$data['receiver_id']) == TRUE) // the information has therefore been successfully saved in the db
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

  function delete($message_id)
  {
      $session_data = $this->session->userdata(logged_in);
      $this->message_m->deleteMessage($message_id);
      redirect('message_c/manageMessageBox/');
      // refresh view duay na 
  }

}
?>