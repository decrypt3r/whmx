<?php
	#################################################################
	##  WHMX Billing system for cPanel / WHM					   ##
	##-------------------------------------------------------------##
	##  Version: 1.00 - ENVATO MARKET                              ##
	##-------------------------------------------------------------##
	##  Author: Gianck Luiz obviosistemas@gmail.com         	   ##
	##-------------------------------------------------------------##
	##  Copyright ©2016 . All rights reserved.	                   ##
	##-------------------------------------------------------------##
	#################################################################
	
    session_start();
    ob_start();
    require_once("adm/config/conexao.class.php");
    require_once("adm/config/crud.class.php");
    require_once("adm/config/mega.class.php");
    include_once("adm/config/common.php"); // Language
    
    $con = new conexao(); 
    $con->connect(); 
    
    $dbconfig = mysql_query("SELECT * FROM config WHERE id = '1'");
    $config = mysql_fetch_array($dbconfig);
    $thema = $config['thema'];

	use megaphp\view\MVC;
	
    $tpl = new MVC("theme/$thema/reset.html"); 
    $tpl->addFile("NAVBAR", "theme/$thema/inc/navbar.html"); 
    $tpl->addFile("DEMO", "theme/$thema/inc/demo.html"); 
    $tpl->addFile("FOOTER", "theme/$thema/inc/footer.html"); 
    
    // SESSION 
	//LOGADO
	if ( !isset($_SESSION['id']) ){
	
	$tpl->block("BLOCK_NAO_LOGADO");
	} else {
    $idbase = $_SESSION['id'];
	$cslogin = mysql_query("SELECT * FROM customers WHERE id = + $idbase");
	$logado = mysql_fetch_array($cslogin);	
	$tpl->USER_NAME = $logado['name'];
	$tpl->block("CHAT_LOGIN");	
	$tpl->block("BLOCK_LOGADO");	
	}
    
    if (@$_POST['opr'] == 'reset') { 
	$email = anti_injection($_POST['email']);
	if( $_SESSION['captcha'] == $_POST['captcha']){
    
    $email = $_POST['email'];
	$consultas = mysql_query("SELECT * FROM customers WHERE email = '$email'");
	$campo = mysql_fetch_array($consultas);
	$contagem = mysql_num_rows($consultas); 

	if (@$contagem == 1) {
    
    $gerasenhanova = geraSenha(6);
	$pwdsr = md5($gerasenhanova);
	$salt = base64_encode($gerasenhanova);
	
	$iduser = $campo['id'];
	$name = $campo['name'];
	$data_email = date('d/m/Y H:i:s');
	$dataen_email = date('m/d/Y H:i:s');
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$crud = new crud('customers'); 
	$crud->atualizar("pass='$pwdsr',salt='$salt'", "id='$iduser'");
    
    // SEND MAIL 
	require_once("adm/config/mail/class.phpmailer.php");
	$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '15'");
    $template_mail = mysql_fetch_array($query_tpl);
	
	$mail = new PHPMailer();
	$mail->IsSMTP();		
	$mail->SMTPDebug = 0;		
	$mail->SMTPAuth = true;		
	$mail->IsHTML(true);
	$mail->Host = $config['smtp_host'];	
	$mail->Port = $config['smtp_port'];		
	$mail->Username = $config['smtp_user'];	
	$mail->Password = $config['smtp_pass'];	

	$mail->From = $template_mail['from_mail'];  
	$mail->FromName = $template_mail['name_from']; 

	$mail->AddAddress($email);
	$mail->IsHTML(true); 
	$mail->CharSet = 'iso-8859-1'; 
	$mail->Subject  = $template_mail['subject']; 
	
	$corpoDoEmail = $template_mail['msg']; 
	$signature = $config['signature'];
	$logo = $config['logo'];
	$company = $config['company'];
	
	// MAIL Variables
    $corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMAIL%', $email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SENHA%',  $gerasenhanova, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATABR%', $data_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATAEN%', $dataen_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%IP%', $ip, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
    
    // END Variables
	$mail->Body = $corpoDoEmail;
	$SendOK = $mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	
	$tpl->RETURN_PWD = '<div class="alert alert-success">Success - Congratulations a new password has been sent to your email! </div>';

	} else { 
    $tpl->RETURN_PWD = '<div class="alert alert-danger">Error - Sorry no records located in our records ! </div>';
    }
    
    }else{
    $tpl->CAPTCHA = '<div class="alert alert-danger">Error - Captcha Invalid Code </div>';
    }
	
	}
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
