<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class problem_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
			$this->load->database();
			$this->load->model('message_m');
		}

		function index(){
		    $session_data = $this->session->userdata('logged_in');
		    $senderID = $session_data['user_id'];
			$this->load->library('form_validation');
      		$data['type'] = $session_data['type'];
      		$data['user_id'] = $session_data['user_id'];
      		$data['username'] = $session_data['username'];
	        $this->form_validation->set_rules('msgSubject', 'msgSubject', 'require|css_clean|max_length[30]');
	        $this->form_validation->set_rules('msgText', 'msgText', 'max_length[200]');
	        if ($this->form_validation->run() == FALSE) // validation hasn't been passed
	        {
	            $this->load->view('problem_report_form.html',$data);
		        $this->load->view('footer.html');
	        }
	        else // passed validation proceed to post success logic
	        {
	            $form_data = array( 'msgSubject' => $this->post->input('msgSubject'),
	                              'msgText' => $this->post->input('msgText'));
		        // run insert model to write data to db
		        if ($this->message_m->createMessage($senderID,$form_data['msgSubject'],$form_data['msgText'],0) == 'true') // the information has therefore been successfully saved in the db
		        {             
		            $this->session->set_flashdata("message","Problem Reported!");
		            redirect('problem_c/index');   // or whatever logic needs to occur
		        }
		        else
		        {
		            $this->session->set_flashdata("message","Error to report problem");
		            redirect('problem_c/index');
		        }
	        }

		}

	}
?>