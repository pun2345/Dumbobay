<?php
Class message_m extends CI_Model
{
		function check()
		{

		}

		function createMessage($sender, $subject, $text, $receiver){
			$data = array(
		   	'Subject' => $subject,
	   		'Text' => $text,
	   		'Sender_ID' => $sender,
	   		'Receiver_ID' => $receiver
		);
		 $this->db->trans_start();
   		 $this->db->insert('message',$data);
   		 $this->db->trans_complete();
   		 return $this->db->insert_id();
		}

		function getUserMessage($user_id){
			return $this->db->query("select * from Message where Receiver_ID =$user_id order by Datetime desc");
		}
		function getMessage($message_id){
			return $this->db->query("select * from Message where Message_ID = $message_id")->row();
		}
		function deleteMessage($message_id){
			$this->db->trans_start();
			$this->db->delete('Message', array('Message_ID'=>$message_id));
			$this->db->trans_complete();
			// echo $this->db->affected_rows();
			if ($this->db->affected_rows() > 0){
            	return "true";
            }else{
        		return "false";
        	}
		}
		function getSender($message_id){
			$query = $this->db->query("select * from Message where Message_ID = $message_id");
			if($query -> num_rows()> 0){
				foreach ($query->result() as $row)
				   return $row->Sender_ID;
			}else{
				return null;
			} 
		}
		function getSubject($message_id){
			$query=$this->db->query("select * from Message where Message_ID = $message_id");
			if($query -> num_rows()> 0){
				foreach ($query->result() as $row)
				   return $row->Subject;
			}else{
				return null;
			} 
		}

}
?>