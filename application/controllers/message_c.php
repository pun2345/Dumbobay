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
      $this->form_validation->set_rules('subject', 'subject', 'require|css_clean|max_length[30]');
      $this->form_validation->set_rules('receiver', 'receiver', 'require|css_clean');
      $this->form_validation->set_rules('text', 'text', 'max_length[200]');
      if ($this->form_validation->run() == FALSE) // validation hasn't been passed
      {
          $this->load->view(-------);
      }
      else // passed validation proceed to post success logic
      {
          $form_data = array( 'subject' => set_value('subject'),
                              'text' => set_value('text'),
                             'receiver' => set_value('receiver'));
      // run insert model to write data to db
      if ($this->message_m->createMessage($senderID,$form_data['text'],$receiverID) == TRUE) // the information has therefore been successfully saved in the db
      {
          redirect('message_c/success');   // or whatever logic needs to occur
      }
      else
      {
          $this->session->set_flashdata("message","Sending error");
          redirect('home_c');
      }
  }

  function sendMessageTo($receiverID,$subject)
  {
      $session_data = $this->session->userdata(logged_in);
      $senderID=$session_data['userID'];
      if(!isset($subject)){
          $this->form_validation->set_rules('text', 'text', 'max_length[200]');
          $this->form_validation->set_rules('subject', 'subject', 'require|css_clean|max_length[30]');
          if ($this->form_validation->run() == FALSE) // validation hasn't been passed
          {
              $this->load->view(-------);
          }
          else // passed validation proceed to post success logic
          {
              $form_data = array('text' => set_value('text'),
                                'subject' => set_value('subject'));
          }
              // run insert model to write data to db
          if(strlen($subject)>26) $subject = substr($subject,0,25);
          if ($this->message_m->createMessage($senderID,$form_data['subject'],$form_data['text'],$receiverID) == TRUE) // the information has therefore been successfully saved in the db
          {
              redirect('message_c/success');   // or whatever logic needs to occur
          }
          else
          {
              $this->session->set_flashdata("message","Sending error");
              redirect('home_c');
          }

      }
      else{
          $this->form_validation->set_rules('text', 'text', 'max_length[200]');
          if ($this->form_validation->run() == FALSE) // validation hasn't been passed
          {
              $this->load->view(-------);
          }
          else // passed validation proceed to post success logic
          {
              $form_data = array('text' => set_value('text'));
          }
          if(strlen($subject)>26) $subject = substr($subject,0,23) . '..';
          if ($this->message_m->createMessage($senderID,'RE: '.$subject,$form_data['text'],$receiverID) == TRUE) // the information has therefore been successfully saved in the db
          {
              redirect('message_c/success');   // or whatever logic needs to occur
          }
          else
          {
              $this->session->set_flashdata("message","Sending error");
              redirect('home_c');
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
      $this->load->view('messageDetail.html/',$data);
  }

  function reply($messageID)
  {
      $session_data = $this->session->userdata(logged_in);
      $data['userID']=$session_data['userID'];
      $senderID = $this->message_m->getSender($messageID);
      $subject = $this->message_m->getSubject($messageID);
      redirect('message_c/sendMessageTo/'.$senderID.'/'.$subject);
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