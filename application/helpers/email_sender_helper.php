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
	    $config['mailtype']     = 'html';
	    $config['validation']   = TRUE;

		$CI->email->initialize($config);

		$CI->email->from('pun_Taweekiat@hotmail.com'); 
		$CI->email->to('pun_Taweekiat@hotmail.com'); 
		$CI->email->subject('test123');
		$CI->email->message("Follow the link: <a href=\"localhost/Dumbobay/index.php/home_c\">Localhost/Dumbobay/index.php/home_c</a>");
		if($CI->email->send())
			return true;
		return false;
	//if($CI->email->print_debugger()){
	//	return true;
	//}
	//return false
}
function activate_User($id,$username,$mail){
	echo $id;
	echo $username;
	echo $mail;
	$CI =& get_instance();
	//$CI->load->database();
    $CI->load->library('email');               

    $config['protocol']     = 'smtp';
    $config['smtp_host']    = 'ssl://smtp.gmail.com';
    $config['smtp_port']    = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user']    = 'pun2345@gmail.com';
    $config['smtp_pass']    = '2345pun2345pun';
    $config['charset']      = 'utf-8';
    $config['newline']      = "\r\n";
    $config['mailtype']     = 'html';
    $config['validation']   = TRUE;

    //echo $id;

	//$user_data = $CI->member_m->getUserDetail($id);
	//rint_r($user_data);
	$CI->email->initialize($config);
	$CI->email->from('Dumbobay@Dumbobay.com');
	$CI->email->to($mail); 
	$subject = "Sign up: ".$username." for Dumbobay";
	$CI->email->subject($subject);
	

	$text = "To ".$username."<br> To activate your id, follow the link: <a href=\"localhost/Dumbobay/index.php/member_c/confirmMember/".$id."\">localhost/Dumbobay/index.php/member_c/confirmMember/".$id."</a>.<br>From Dumbobay.";
	$CI->email->message($text);
	

	if($CI->email->send())
		return true;
	return false;
	//if($CI->email->print_debugger()){
	//	return true;
	//}
	//return false
}


function Feedback($t_id){
	$CI =& get_instance();
	$CI->load->model('member_m');
	$CI->load->model('product_m');
	$CI->load->model('transaction_m');
    $CI->load->library('email');               

    $config['protocol']     = 'smtp';
    $config['smtp_host']    = 'ssl://smtp.gmail.com';
    $config['smtp_port']    = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user']    = 'pun2345@gmail.com';
    $config['smtp_pass']    = '2345pun2345pun';
    $config['charset']      = 'utf-8';
    $config['newline']      = "\r\n";
    $config['mailtype']     = 'html';
    $config['validation']   = TRUE;

    $tran_data = $CI->transaction_m->getTransactionDetail($t_id);
    //print_r($tran_data->Product_ID);

    $product_data = $CI->product_m->getProductDetail($tran_data->Product_ID);
    //print_r($product_data);
    //echo $tran_data->Buyer_ID;
    $buyer_data = $CI->member_m->getMemberDetail($tran_data->Buyer_ID);
    $seller_data = $CI->member_m->getMemberDetail($tran_data->Seller_ID);

	$CI->email->initialize($config);	
	$CI->email->from('Dumbobay@Dumbobay.com');
	//echo $buyer_data->Email;
	//print_r($buyer_data);
// //To Buyer
	$CI->email->to($buyer_data->Email); 
	
	$subject = "Feedback: ".$product_data->Name;
	$CI->email->subject($subject);
	
	$text = "To ".$buyer_data->Username."<br>
	Please give feed back to the ".$product_data->Name." transaction and ".$seller_data->Username.".<br>
	Follow the link: <a href=\"Localhost/Dumbobay/index.php/transaction_c/Feedback/".$t_id."\">"."Localhost/Dumbobay/index.php/transaction_c/Feedback/".$t_id."</a><br>From Dumbobay";
	$CI->email->message($text);
	if(!$CI->email->send()){
		return false;
	}
// //To Seller
	$CI->email->to($seller_data->Email); 
	
	$subject = "Feedback: ".$product_data->Name;
	$CI->email->subject($subject);
	
	$text = "To ".$seller_data->Username."<br>
	Please give feed back to the ".$product_data->Name." transaction and ".$buyer_data->Username.".<br>
	Follow the link: <a href=\"Localhost/Dumbobay/index.php/transaction_c/Feedback/".$t_id."\">"."Localhost/Dumbobay/index.php/transaction_c/Feedback/".$t_id."</a><br>From Dumbobay";
	$CI->email->message($text);
	if(!$CI->email->send()){
		return false;
	}
	return true;
}

function losing_Bid($id,$product_id){
	$CI =& get_instance();
	$CI->load->model('member_m');
	$CI->load->model('product_m');
    $CI->load->library('email');               

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

    //echo "losing";
    $CI->email->initialize($config);	
	$CI->email->from('Dumbobay@Dumbobay.com');
	
	$user_data = $CI->member_m->getMemberDetail($id);
	$product_data = $CI->product_m->getProductDetail($product_id);

	$CI->email->to($user_data->Email); 
	
	$subject = "Losing: ".$product_data->Name;
	$CI->email->subject($subject);
	
	$text = "To ".$user_data->Username."\r\n Your bid was beaten on ".$product_data->Name.".\r\nFrom Dumbobay";
	$CI->email->message($text);
	
	if($CI->email->send())
		return true;
	return false;
	//if($CI->email->print_debugger()){
	//	return true;
	//}
	//return false
}

function win_Bid($id,$product_id,$t_id){
	$CI =& get_instance();
	$CI->load->model('member_m');
	$CI->load->model('product_m');
    $CI->load->library('email');               

    $config['protocol']     = 'smtp';
    $config['smtp_host']    = 'ssl://smtp.gmail.com';
    $config['smtp_port']    = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user']    = 'pun2345@gmail.com';
    $config['smtp_pass']    = '2345pun2345pun';
    $config['charset']      = 'utf-8';
    $config['newline']      = "\r\n";
    $config['mailtype']     = 'html';
    $config['validation']   = TRUE;

	$user_data = $CI->member_m->getMemberDetail($id);
	$product_data = $CI->product_m->getProductDetail($product_id);

	$CI->email->initialize($config);	
	$CI->email->from('Dumbobay@Dumbobay.com');
	
	$CI->email->to($user_data->Email); 
	
	$subject = "Win Bid: ".$product_data->Name;
	$CI->email->subject($subject);
	
	$text = "To ".$user_data->Username."\r\n You win the auction of ".$product_data->Name.".\r\nPlease proceed the payment.".
	"Follow the link: <a href=\"Localhost/Dumbobay/index.php/payment_c/payBid/".$t_id."\">"."Localhost/Dumbobay/index.php/payment_c/payBid/".$t_id.".</a>."
	."\r\nFrom Dumbobay";
	$CI->email->message($text);
	
	if($CI->email->send())
		return true;
	return false;
}
function blackList($id,$t_id,$p_id){
	$CI =& get_instance();
	$CI->load->model('member_m');
	$CI->load->model('product_m');
    $CI->load->library('email');               

    $config['protocol']     = 'smtp';
    $config['smtp_host']    = 'ssl://smtp.gmail.com';
    $config['smtp_port']    = '465';
    $config['smtp_timeout'] = '7';
    $config['smtp_user']    = 'pun2345@gmail.com';
    $config['smtp_pass']    = '2345pun2345pun';
    $config['charset']      = 'utf-8';
    $config['newline']      = "\r\n";
    $config['mailtype']     = 'html';
    $config['validation']   = TRUE;

	$user_data = $CI->member_m->getMemberDetail($id);
	$product_data = $CI->product_m->getProductDetail($p_id);

	$CI->email->initialize($config);	
	$CI->email->from('Dumbobay@Dumbobay.com');
	
	$CI->email->to($user_data->Email); 
	
	$subject = "Black list: ".$product_data->Name;
	$CI->email->subject($subject);
	
	$text = "To ".$user_data->Username."<br> Your name is on the blacklist.<br>".
	"You exceed the payment period on ".$product_data->Name
	.".<br>From Dumbobay";
	$CI->email->message($text);
	
	if($CI->email->send())
		return true;
	return false;
}

?>