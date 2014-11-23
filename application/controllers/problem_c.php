<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class problem_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
			$this->load->database();
			$this->load->model('message_m');
			$this->load->helper('html');
		}

		function index(){
		    $session_data = $this->session->userdata('logged_in');
		    $senderID = $session_data['user_id'];
			$this->load->library('form_validation');
      		$data['type'] = $session_data['type'];
      		$data['user_id'] = $session_data['user_id'];
      		$data['username'] = $session_data['username'];
	        $this->form_validation->set_rules('msgSubject', 'msgSubject', 'require|css_clean|max_length[30]');
	        $this->form_validation->set_rules('msgText', 'msgText', 'require|max_length[200]');
	        if ($this->form_validation->run() == FALSE) // validation hasn't been passed
	        {
	            $this->load->view('problemForm.html',$data);
	        }
	        else // passed validation proceed to post success logic
	        {
				$form_data = array( 'msgSubject' => $this->input->post('msgSubject'),
                              		'msgText' => $this->input->post('msgText'));
		        // run insert model to write data to db
		        if ($this->message_m->createMessage($senderID,$form_data['msgSubject'],$form_data['msgText'],0) == 'true') // the information has therefore been successfully saved in the db
		        {             
		            $this->session->set_flashdata("message","Problem Reported!");
		            redirect('home_c');   // or whatever logic needs to occur
		        }
		        else
		        {
		            $this->session->set_flashdata("message","Unable to report problem");
		            redirect('problem_c');
		        }
	        }

		}

	}
?>