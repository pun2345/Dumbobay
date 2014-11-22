<?php
Class member_m extends CI_Model
{

	function checkLogin($username, $password){
		$this->db->select('user_id, username, password');
		$this->db->from('user');
		$this->db->where('username = ' . "'" . $username . "'"); 
		$this->db->where('password = ' . "'" . $password . "'"); 
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{	
			foreach ($query->result() as $row)
				{	
					// print_r($row);
					$id = $row->user_id; 
					$userData = array(
							'User_ID' => $id,
							'username' => $row->username
					);
				}
			$this->db->select('Activated');
			$query2 = $this->db->get_where('Member',array('User_ID' => $id));
			if($query->num_rows() == 1){
				foreach ($query2->result() as $row)
				{
					// print_r($row);
					if( $row->Activated== 1) 
						// print_r($userData);
						return $userData;
				}
			}	
		}
		$userData = array(
			'User_ID' => 0,
			'username' => "null"
		);
		return $userData;

	}
	function checkUserType($user_id){
		$query = $this->db->query("select Type from User where User_ID=$user_id");
		if($query->num_rows() == 1)
		{
			foreach ($query->result() as $row)
			{
				return $row->Type;
			}
		}
		return null;

	}
	function createMember($username, $password, $firstname, $lastname, $type, $address, $telephone, $email){
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
	function enableMember($ID){
		$data = array(
			'Activated' => 1
		);
		$this->db->trans_start();
		$this->db->where('User_ID', $ID);
		$this->db->update('member',$data);
		$this->db->trans_complete();
	}
	function getBlacklist(){
		return $this->db->query("select * from Member where Blacklist_score >=3");
	}
	function checkMember($username,$email){
		$query1=$this->db->get_where('User',array('Username'=>$username));
		if($query1 -> num_rows()> 0) return "true";
		$query2= $this->db->get_where('Member',array('E-mail' => $email));
		if($query2 -> num_rows()> 0) return "true";
		return "false";
	}
	function editMemberDetail($user_id,$username, $password, $firstname, $lastname, $type, $address, $telephone, $email){
		$data1 = array(
		   	'Username' => $username,
	   		'Password' => $password,
	   		'Firstname' => $firstname,
	   		'Lastname' => $lastname,
	   		'Type' => $type
		);
		 $this->db->trans_start();
		 $this->db->where('User_ID',$user_id);
   		 $this->db->update('user',$data1);
   		 if ($this->db->affected_rows() <= 0) return "false";
         
   		 $data2 = array(
		   	'User_ID' => $user_id,
		   	'Address' => $address,
	   		'Telephone' => $telephone,
	   		'E-mail' => $email	   		
		);
		$this->db->where('User_ID',$user_id);
   		$this->db->update('member',$data2);
   		echo $this->db->affected_rows() ."<br>";
   		if ($this->db->affected_rows() <= 0) return "false";
   	  	$this->db->trans_complete();
   	  	return "true";
	}
	function getMemberDetail($user_id){
		return $this->db->query("select * from Member where User_ID=$user_id");
	}
}
?>