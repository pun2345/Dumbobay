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
      $senderID=$session_data['userID'];
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

  function sendMessageTo($receiverID)
  {
      $session_data = $this->session->userdata(logged_in);
      $senderID=$session_data['userID'];
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
      if ($this->message_m->createMessage($senderID,$form_data['msgSubject'],$form_data['msgText'],$receiverID) == TRUE) // the information has therefore been successfully saved in the db
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
      $data['userID']=$session_data['userID'];
      $data['messages'] = $this->message_m->getUserMessage($data['userID']);
      $this->load->view('messageBox.html/',$data);
  }

  function messageDetail($messageID)
  {
      $session_data = $this->session->userdata(logged_in);
      $data['userID']=$session_data['userID'];
      $data['message'] = $this->message_m->getMessage($messageID);
      $this->load->view('viewMessage.html/',$data);
  }

  function reply($messageID)
  {
      $session_data = $this->session->userdata(logged_in);
      $data['userID']=$session_data['userID'];
      $data['receiverID'] = $this->message_m->getSender($messageID);
      $data['subject'] = $this->message_m->getSubject($messageID);
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
      if ($this->message_m->createMessage($userID,$data['subject'],$form_data['msgText'],$data['receiverID']) == TRUE) // the information has therefore been successfully saved in the db
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

  function delete($messageID)
  {
      $session_data = $this->session->userdata(logged_in);
      $this->message_m->deleteMessage($messageID);
      redirect('message_c/manageMessageBox/');
      // refresh view duay na 
  }

}
?>