<?php

class View_test extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->helper('form');
    $this->load->helper('html');
    $this->load->helper('url');
  }

  function index()
  {
    $this->load->view('signup.html');
  }
}
?>