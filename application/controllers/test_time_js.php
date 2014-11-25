<?php

class Test_time_js extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->helper('form');
    $this->load->helper('html');
    $this->load->helper('url');
    $this->load->database();
    $this->load->model('product_m');
  }

  function index()
  {
    $query = $this->product_m->getProductDetail(9);
    //print_r($query->End_Date);
    $data['Date'] = $query->End_Date;
    $data['username'] = 'use12';
    $this->load->view('test_time.html',$data);
    $this->load->view('footer.html',$data);
  }
}
?>