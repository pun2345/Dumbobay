<?php
Class member_m extends CI_Model
{

	function checkLogin($username, $password){
		$this->db->select('user_id, username, password, type');
		$this->db->from('user');
		$this->db->where('username = ' . "'" . $username . "'"); 
		$this->db->where('password = ' . "'" . MD5($password) . "'"); 
		$this->db->limit(1);
		// echo MD5($password);
		$query = $this->db->get();
		$row = $query ->row();
		if($query->num_rows() == 1)
		{
			$userData = array(
				'User_ID' => $row->user_id,
				'Username' => $row->username,
				'Type' => $row->type
			);
		}else{
			$userData = array(
				'User_ID' => 0,
				'Username' => "",
				'Type' => 0
			);
		}
	
		return $userData;
	}
	function checkActivated($user_id){
		$this->db->select('Activated');
		$query = $this->db->get_where('Member',array('User_ID'=> $user_id));
		$row = $query->row();
		return $row->Activated;
	}
	function checkUserType($user_id){
		$query = $this->db->query("select Type from User where User_ID = $user_id");
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
	   		'Password' => MD5($password),
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
	   		'Email' => $email	   		
		);
		$this->db->insert('member',$data2);
   	  	$this->db->trans_complete();
   	  	return $insert_id;
	}
	function activateMember($ID){
		$data = array(
			'Activated' => 1
		);
		$this->db->trans_start();
		$this->db->where('User_ID', $ID);
		$this->db->update('member',$data);
		$this->db->trans_complete();
	}
	function deactivateMember($ID){
		$data = array(
			'Activated' => 0
		);
		$this->db->trans_start();
		$this->db->where('User_ID', $ID);
		$this->db->update('member',$data);
		$this->db->trans_complete();
	}
	function getBlacklist(){
		$maxScore = $this->member_m->getBlacklistMaxScore();
		return $this->db->query("
			select User_ID, Username, Email 
			from Member 
			join User using (User_ID) 
			where Blacklist_score >=$maxScore 
			order by user_id ");
	}
	function incBlacklist($user_id){
		$oldScore = $this->member_m->getBlacklist($user_id);
		$newScore = $oldScore+1;
		$data = array(
				'Blacklist_score' => $newScore
			);
			$this->db->trans_start();
			$this->db->where('User_ID', $user_id);
			$this->db->update('member', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				// echo "newCount " .$newCount ."<br>";
				return $newCount;
			}
	}
	function getBlacklistMaxScore(){
		$this->db->limit(1);
		$query = $this->db->get('Blacklist_Max_Score');
		return $query ->row()->MaxScore;
	}
	function checkMember($username,$email){
		$query1=$this->db->get_where('User',array('Username'=>$username));
		if($query1 -> num_rows()> 0) return "true";
		$query2= $this->db->get_where('Member',array('Email' => $email));
		if($query2 -> num_rows()> 0) return "true";
		return "false";
	}
	function editMemberDetail($user_id, $firstname, $lastname, $address, $telephone, $email){
		$data1 = array(
	   		'Firstname' => $firstname,
	   		'Lastname' => $lastname,
		);
		 $this->db->trans_start();
		 $this->db->where('User_ID',$user_id);
   		 $this->db->update('user',$data1);
   		 echo $this->db->affected_rows();
   		 $complete = ($this->db->affected_rows() > 0) ;
         
   		 $data2 = array(
		   	'Address' => $address,
	   		'Telephone' => $telephone,
	   		'Email' => $email	   		
		);
		$this->db->where('User_ID',$user_id);
   		$this->db->update('member',$data2);
   		$complete = $complete or ($this->db->affected_rows() > 0) ;
   	  	$this->db->trans_complete();
   	  	if ($complete) return "true";
   	  	else return "false";
	}
	function getMemberDetail($user_id){
		return $this->db->query("select * from User join member using (user_id) where user_id = $user_id")->row();
	}
	function getUserID($username){
		$this->db->select('User_ID');
		$query = $this->db->get_where('User',array('Username'=>$username));
		return $query->row()->User_ID;
	}
	function getFeedbackScore($user_id){
		$this->db->where('User_ID', $user_id);
		$query=$this->db->get('member');
		if($query->num_rows() == 1){
			foreach ($query->result() as $row)
			{
			   // echo "feedback score " .$row->Feedback_Score ."<br>";
			   return $row->Feedback_Score;
				}
		}
	}
	function updateFeedbackScore($user_id,$score){
		$count =$this->member_m->getFeedbackCount($user_id);
		$oldScore = $this->member_m->getFeedbackScore($user_id);
		$newScore = ($oldScore*$count+$score)/($count+1);
		// echo "newScore " .$newScore ."<br>";
		$data = array(
				'Feedback_Score' => $newScore
			);
			$this->db->trans_start();
			$this->db->where('User_ID', $user_id);
			$this->db->update('member', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			$this->member_m->incFeedbackCount($user_id);
			if ($complete>0) {	
				return "true";
			}
	}
	function getFeedbackCount($user_id){
		$this->db->where('User_ID', $user_id);
		$query=$this->db->get('member');
		if($query->num_rows() == 1){
			foreach ($query->result() as $row)
			{
			   // echo "feedback Count " .$row->Feedback_Count ."<br>";
			   return $row->Feedback_Count;
				}
		}
	}
	function incFeedbackCount($user_id){
		$oldCount = $this->member_m->getFeedbackCount($user_id);
		$newCount = $oldCount+1;
		$data = array(
				'Feedback_Count' => $newCount
			);
			$this->db->trans_start();
			$this->db->where('User_ID', $user_id);
			$this->db->update('member', $data);
			$complete = $this->db->affected_rows();
			$this->db->trans_complete();
			if ($complete>0) {
				// echo "newCount " .$newCount ."<br>";
				return $newCount;
			}
	}
}
?>