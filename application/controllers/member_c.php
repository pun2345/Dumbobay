<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class memeber_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
    $this->load->helper('html');
			$this->load->database();
			$this->load->model('member_m');
		}

		function index(){

		}

		function createMember(){
<<<<<<< HEAD
=======
			$this->load->view('registration_form.html');
>>>>>>> 0be5bbb20db951d89b7d55050cb28004a984fb9c
			//$this->load->view('registration_form.html');
			$this->load->library('form_validation');
	        $this->form_validation->set_rules('username', 'username', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('password', 'password', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('firstname', 'firstname', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('lastname', 'lastname', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('address', 'address', 'require|css_clean|max_length[100]');
	        $this->form_validation->set_rules('telephone', 'telephone', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('email', 'email', 'require|css_clean|max_length[25]');
	        $this->form_validation->set_rules('type','type','require');
			if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		    {
		        $this->load->view('edit_profile_form.html/',$data);
		    }
      	    else // passed validation proceed to post success logic
	        {
<<<<<<< HEAD
=======
		        $form_data = array( 'username' => set_value('username'),
		        					'password' => set_value('password'),
		                            'firstname' => set_value('firstname'),
		                            'lastname' => set_value('lastname'),
		                            'address' => set_value('address'),
		                            'telephone' => set_value('telephone'),
		                            'password' => set_value('password'),
		                            'email' => set_value('email'),
		                            'type' => set_value('type'));
=======
>>>>>>> 0be5bbb20db951d89b7d55050cb28004a984fb9c
		        $form_data = array( 'username' => $this->input->post('username'),
		        					'password' => $this->input->post('password'),
		                            'firstname' => $this->input->post('firstname'),
		                            'lastname' => $this->input->post('lastname'),
		                            'address' => $this->input->post('address'),
		                            'telephone' => $this->input->post('telephone'),
		                            'password' => $this->input->post('password'),
		                            'email' => $this->input->post('email'),
		                            'type' => $this->input->post('type'));
<<<<<<< HEAD
=======
>>>>>>> 4f243f98d6343d8e6b082ade48d6457f29cc0270
>>>>>>> 0be5bbb20db951d89b7d55050cb28004a984fb9c
			}
		    if ($this->member_m->checkMember($form_data['username'],$form_data['email'])) == TRUE) // the information has therefore been successfully saved in the db
		    {             
		    	$this->member_m->createMember($formdata);
		        $this->session->set_flashdata("message","Registration Completed");
		        redirect('member_c/createMember');   // or whatever logic needs to occur
		    }
		    else
		    {
		        $this->session->set_flashdata("message","Registration Failed");
		        redirect('member_c/createMember');
		    }
		}

		function confirmMember($user_id){

		}

		function editProfile($user_id){
			$data['member'] = $this->member_m->getMemberDetail($user_id);
			$member = $data['member']
    		$this->load->library('form_validation');
			$this->load->view('edit_profile_form.html/',$data);
	        $this->form_validation->set_rules('password', 'password', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('firstname', 'firstname', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('lastname', 'lastname', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('address', 'address', 'require|css_clean|max_length[100]');
	        $this->form_validation->set_rules('telephone', 'telephone', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('email', 'email', 'require|css_clean|max_length[25]');
			if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		    {
		        $this->load->view('edit_profile_form.html/',$data);
		    }
      	    else // passed validation proceed to post success logic
	        {
<<<<<<< HEAD
		        $form_data = array( 'password' => $this->input->post('password'),
		                            'firstname' => $this->input->post('firstname'),
		                            'lastname' => $this->input->post('lastname'),
		                            'address' => $this->input->post('address'),
		                            'telephone' => $this->input->post('telephone'),
		                            'password' => $this->input->post('password'),
		                            'email' => $this->input->post('email'));
=======
		        $form_data = array( 'password' => set_value('password'),
		                            'firstname' => set_value('firstname'),
		                            'lastname' => set_value('lastname'),
		                            'address' => set_value('address'),
		                            'telephone' => set_value('telephone'),
		                            'password' => set_value('password'),
		                            'email' => set_value('email'));
>>>>>>> 0be5bbb20db951d89b7d55050cb28004a984fb9c
			    $member['password'] = $form_data['password'];
			    $member['firstname'] = $form_data['$firstname'];
			    $member['lastname'] = $form_data['$lastname'];
			    $member['address'] = $form_data['$address'];
			    $member['telephone'] = $form_data['$telephone'];
			    $member['password'] = $form_data['$password'];
			    $member['email'] = $form_data['$email'];

			}
		    if ($this->member_m->editMemberDetail($member)) == TRUE) // the information has therefore been successfully saved in the db
		    {             
		        $this->session->set_flashdata("message","Profile edited");
		        redirect('member_c/editProfile');   // or whatever logic needs to occur
		    }
		    else
		    {
		        $this->session->set_flashdata("message","Error to edit profile");
		        redirect('member_c/editProfile');
		    }
		}

		function memberDetail($user_id){
			$data['member'] = $this->member_m->getMemberDetail($user_id);
			$this->load->view('member_detail.html',$data);
		}

	}
?>