<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class member_c extends CI_Controller {

		function __construct(){
  		    parent::__construct();
   		    $this->load->helper('html');
   		    $this->load->helper('form');
		    $this->load->database();
			$this->load->model('member_m');
		}

		function index(){

		}

		function createMember(){
			$this->load->library('form_validation');
	        $this->form_validation->set_rules('username', 'username', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('password', 'password', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('firstname', 'firstname', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('lastname', 'lastname', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('address', 'address', 'require|css_clean|max_length[70]');
	        $this->form_validation->set_rules('telephone', 'telephone', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('email', 'email', 'require|css_clean|max_length[25]');
	        $this->form_validation->set_rules('country', 'country', 'require|css_clean|max_length[30]');
	        $this->form_validation->set_rules('type','type','require');
			if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		    {
		        $this->load->view('signup.html');
		    }
      	    else // passed validation proceed to post success logic
	        {
		        $form_data = array( 'username' => $this->input->post('username'),
		        					'password' => $this->input->post('password'),
		                            'firstname' => $this->input->post('firstname'),
		                            'lastname' => $this->input->post('lastname'),
		                            'address' => $this->input->post('address'),
		                            'telephone' => $this->input->post('telephone'),
		                            'password' => $this->input->post('password'),
		                            'country' => $this->input->post('country'),
		                            'email' => $this->input->post('email'),
		                            'type' => $this->input->post('type'));
		        $form_data['address'] = $form_data['address'] + " " + $form_data['country'];
			    if ($this->member_m->checkMember($form_data['username'],$form_data['email']) == 'false') // the information has therefore been successfully saved in the db
			    {             
			    	$this->member_m->createMember($form_data['username'],$form_data['password'],$form_data['firstname'],$form_data['lastname'],$form_data['type'],$form_data['address'],$form_data['telephone'],$form_data['email']);
			        $this->session->set_flashdata("message","Registration Completed");
			        redirect('home_c');   // or whatever logic needs to occur
			    }
			    else
			    {
			        $this->session->set_flashdata("message","Registration Failed, Try Again");
			    	redirect('member_c/createMember');
			    }	
			}
		}

		function confirmMember($user_id){
			$this->member_m->activateMember($user_id);
			redirect('home_c');
		}

		function editProfile($user_id){
			$data['user'] = $this->member_m->getUserDetail($user_id);
			$user = $data['user'];
    		$this->load->library('form_validation');
	        $this->form_validation->set_rules('password', 'password', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('firstname', 'firstname', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('lastname', 'lastname', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('address', 'address', 'require|css_clean|max_length[100]');
	        $this->form_validation->set_rules('telephone', 'telephone', 'require|css_clean|max_length[20]');
	        $this->form_validation->set_rules('email', 'email', 'require|css_clean|max_length[25]');
			if ($this->form_validation->run() == FALSE) // validation hasn't been passed
		    {
		        $this->load->view('edit_profile_form.html');
		    }
      	    else // passed validation proceed to post success logic
	        {
		        $form_data = array( 'password' => $this->input->post('password'),
		                            'firstname' => $this->input->post('firstname'),
		                            'lastname' => $this->input->post('lastname'),
		                            'address' => $this->input->post('address'),
		                            'telephone' => $this->input->post('telephone'),
		                            'password' => $this->input->post('password'),
		                            'email' => $this->input->post('email'));
			    $member['password'] = $form_data['password'];
			    $member['firstname'] = $form_data['$firstname'];
			    $member['lastname'] = $form_data['$lastname'];
			    $member['address'] = $form_data['$address'];
			    $member['telephone'] = $form_data['$telephone'];
			    $member['password'] = $form_data['$password'];
			    $member['email'] = $form_data['$email'];

			}
		    if ($this->member_m->editUserDetail($user) == 'true') // the information has therefore been successfully saved in the db
		    {             
		        $this->session->set_flashdata("message","Profile edited");
		        redirect('home_c');   // or whatever logic needs to occur
		    }
		    else
		    {
		        $this->session->set_flashdata("message","Error to edit profile");
		    }
		}

		function memberDetail($user_id){
			$data['member'] = $this->member_m->getMemberDetail($user_id);
			$this->load->view('member_detail.html',$data);
		}

	}
?>