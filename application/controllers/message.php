<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Message extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('message');
  }

  function index()
  {

  }

  function sendMessage()
  {

  }

  function sendMessageTo($senderID)
  {

  }

  function reportProblem($problem)
  {

  }

  function manageMessageBox()
  {

  }

  function messageDetail($messageID)
  {

  }

  function reply($messageID)
  {

  }

  function delete($messageID)
  {

  }

}
?>