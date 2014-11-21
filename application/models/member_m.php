<?php
Class member_m extends CI_Model
{
	function enableMember($ID){
		$data = array(
			'Activated' => 1
		);
		$this->db->where('User_ID', $ID);
		$this->db->update('member',$data);
	}

	function checkLogin($username, $password){
		$this->db->select('user_id, username, password');
		$this->db->from('user');
		$this->db->where('username = ' . "'" . $username . "'"); 
		$this->db->where('password = ' . "'" . MD5($password) . "'"); 
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			return false;
		}

	}

	function createMember($username, $password, $firstname, $Lastname, $type, $address, $telephone, $email){
		$data1 = array(
		   	'Username' => $username,
	   		'Password' => $password,
	   		'Firstname' => $firstname,
	   		'Lastname' => $lastname,
	   		'Type' => $type
		);
		 $this->db->trans_start();
   		 $this->db->insert('user',$data1);
   		 $insert_id = $this->db->insert_id();
   		 
   		 $data2 = array(
		   	'User_ID' => $insert_id,
		   	'Address' => $address,
	   		'Telephone' => $telephone,
	   		'E-mail' => $email	   		
		);
		$this->db->insert('member',$data2);
   	  	$this->db->trans_complete();
	}
	
}
?>