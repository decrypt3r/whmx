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
	
    $tpl = new MVC("theme/$thema/register.html"); 
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
    
    if (@$_POST['opr'] == 'register') { 
	
	if( $_SESSION['captcha'] == $_POST['captcha']){
		
    $name = anti_injection($_POST['name']);
    $company = anti_injection($_POST['company']);
    $email = anti_injection($_POST['email']);
    $cpf_cnpj = anti_injection($_POST['cpf_cnpj']);
    $passemail = anti_injection($_POST['password']);
    $pass = md5($_POST['password']);
    $salt = base64_encode($_POST['password']);
    $address = anti_injection($_POST['address']);
    $number = anti_injection($_POST['number']);
    $city = anti_injection($_POST['city']);
    $state = anti_injection($_POST['state']);
    $zipcode = anti_injection($_POST['zipcode']);
    $country = anti_injection($_POST['country']);
    $getOs = getOs();
    $ipcOs = $_SERVER['REMOTE_ADDR'];
    
    
    
    $cm = mysql_query("SELECT * FROM customers WHERE email = '$email'");
	$vrf = mysql_fetch_array($cm);	
    $verifica = mysql_num_rows($cm); 
    
    if($verifica == '1') { 
    $tpl->ERROR_REGISTER = '<div class="alert alert-danger">Error - Sorry email address already registered!</div>';
	} else { 
    
    $crud = new crud('customers');  // table
    $crud->inserir("name,ip,os,company,cpf_cnpj,email,pass,salt,address,number,city,state,zipcode,country,status", "'$name','$ip','$os','$company','$cpf_cnpj','$email','$pass','$salt','$address','$number','$city','$state','$zipcode','$country','S'");
	
	// SEND MAIL 
	require_once("adm/config/mail/class.phpmailer.php");
	$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '16'");
    $template_mail = mysql_fetch_array($query_tpl);
	
	$data_email = date('d/m/Y H:i:s');
	$dataen_email = date('m/d/Y H:i:s');
	$corpoDoEmail = $template_mail['msg']; 
	$signature = $config['signature'];
	$logo = $config['logo'];
	$companyc = $config['company'];
	
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
	
	// MAIL Variables
    $corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMPRESA%',  $companyc, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATABR%', $data_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATAEN%', $dataen_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
    
    // END Variables
	$mail->Body = $corpoDoEmail;
	$SendOK = $mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	if ($SendOK) {
	header("Location: login.php?register=new"); // redirect send mail ok
    } else {
	echo "<script>
        alert ('Error sending email!');
        document.location.href = ('login.php?register=new');
	</script>";
    }
	// END SEND
	
	} // END MAIL 
	
    
    }else{
    $tpl->CAPTCHA = '<div class="alert alert-danger">Error - Captcha Invalid Code </div>';
    $tpl->INPUT_NAME = $_POST['name'];
    $tpl->INPUT_COMPANY = $_POST['company'];
    $tpl->INPUT_EMAIL = $_POST['email'];
    $tpl->INPUT_TAX = $_POST['cpf_cnpj'];
    $tpl->INPUT_ADDRESS = $_POST['address'];
    $tpl->INPUT_NUMBER = $_POST['number'];
    $tpl->INPUT_CITY = $_POST['city'];
    $tpl->INPUT_STATE = $_POST['state'];
    $tpl->INPUT_ZIPCODE = $_POST['zipcode'];
    }
	
	
	}
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->ERRO_COMPANY = $lang['VALIDATE_COMPANY'];
	$tpl->ERRO_NAME = $lang['VALIDATE_NAME'];
	$tpl->ERRO_MIN_NAME = $lang['VALIDATE_NAME_MIN'];
	$tpl->ERRO_PWD = $lang['VALIDATE_PASS'];
	$tpl->ERRO_MIN_PWD = $lang['VALIDATE_PASS_MIN'];
	$tpl->ERRO_CONFIRM_PWD = $lang['VALIDATE_PASS_CONFIRM'];
	$tpl->ERROR_EMAIL = $lang['VALIDATE_EMAIL'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
