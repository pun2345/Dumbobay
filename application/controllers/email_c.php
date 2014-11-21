<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class email_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
			$this->load->database();
			$this->load->model('email_m');
		}

		function index(){
			$this->load->library('email');

			$config['protocol'] = 'sendmail';
			$config['mailpath'] = '/usr/sbin/sendmail';
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;

			$this->email->initialize($config);

			$this->email->from('test@localhost.com', 'Hello');
			$this->email->to('bonedz@gmail.com'); 

			$this->email->subject('Email Test');
			$this->email->message('Testing the email class.');	

			$this->email->send();

			echo $this->email->print_debugger();
		}

	}
?>