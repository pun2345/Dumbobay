<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function test($text ='test test test'){

		$CI =& get_instance();



        $CI->load->library('email');               
        //or autoload it from /application/config/autoload.php

	    $config['protocol']     = 'smtp';
	    $config['smtp_host']    = 'ssl://smtp.gmail.com';
	    $config['smtp_port']    = '465';
	    $config['smtp_timeout'] = '7';
	    $config['smtp_user']    = 'pun2345@gmail.com';
	    $config['smtp_pass']    = '2345pun2345pun';
	    $config['charset']      = 'utf-8';
	    $config['newline']      = "\r\n";
	    $config['mailtype']     = 'text';
	    $config['validation']   = TRUE;

		$CI->email->initialize($config);

		$CI->email->from('pun_Taweekiat@hotmail.com'); 
		$CI->email->to('pun_Taweekiat@hotmail.com'); 
		$CI->email->subject('test123');
		$CI->email->message($text);
		if($CI->email->send())
			return true;
		return false;
	//if($this->email->print_debugger()){
	//	return true;
	//}
	//return false
}
/*function highest_bid($id,$product_id){
	$user_data = $this->member_m->getMemberDetail($id);
	$product_data = $this->member_m->getDetail($product_id);
	$this->email->to($user_data['E-mail']); 
	$subject = "Current Highest Bid on ".$product_data['Name']
	$this->email->subject($subject);
	$text = "To ".$user_data['name']."\r\n you has the current hihgest bid on ".$product_data['name']."\r\nFrom Dumbobay";
	$this->email->message($text);
	if($this->email->send())
		return true;
	return false;
	//if($this->email->print_debugger()){
	//	return true;
	//}
	//return false
}*/
?>