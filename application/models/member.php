<?php
Class Member extends CI_Model
{
	function checkLogin($username, $password)
	{
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
}
?>