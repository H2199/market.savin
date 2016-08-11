<?php
require 'privat_info.php';
mysql_con();
$adm_pwd;
$adm_login;
session_start ();
$session_pwd   =  isset($_SESSION['paswd'])	? $_SESSION['paswd'] : '';
$session_login =  isset($_SESSION['login'])	? $_SESSION['login'] : '';
$auth = ($session_pwd==$adm_pwd && $session_login==$adm_login)? true : false;

require 'functions.php';

switch ($_GET['action']){
	case '1':
		$expected_values = array('name', 'email', 'link', 'message');
		foreach($expected_values as $key => $value){
			if (isset($_POST[$value]) && !empty($_POST[$value])){
				$$value = clean_var($_POST[$value]);
			}
		}
		if(empty($name)||empty($email)||(empty($link) && empty($message))){
			header('Location: index.php?error=1&text=Try to fill necessary fields.');
		}
		
		$ip = $_SERVER['REMOTE_ADDR'];
		$ins = "INSERT 
		INTO `order`(`name` ,`email` ,`link` ,`message` ,`ip`) 
		VALUES		('$name','$email','$link','$message','$ip')";
		mysql_query($ins)or die(mysql_error());

		$to = "stanislav.savin@gmail.com";
		$charset = "utf-8";
		$subject = "MARKET.SAVIN.FI ORDER";
		$mess = "Website: market.savin.fi\n Name: $name \n Email: $email \n Link: $link \n Message: $message";
		$from = "order@market.savin.fi";
		$headers  = "MIME-Version: 1.0";
		$headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
		$headers .= "From: $from";
		$send = mail ($to,$subject,$mess,$headers);
		if ($send == 'true')
		{
			header('Location: index.php?sent=1');
		}
		 else{
				header('Location: index.php?error=1&text=Error occured. Try to resend message later on.');
			}
	break;
}

?>