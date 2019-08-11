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
	
    $tpl = new MVC("theme/$thema/ticket-open.html"); 
    $tpl->addFile("NAVBAR", "theme/$thema/inc/navbar.html"); 
    $tpl->addFile("DEMO", "theme/$thema/inc/demo.html"); 
    $tpl->addFile("FOOTER", "theme/$thema/inc/footer.html");  
    
    // SESSION FOR ACCOUNTS 
	//LOGADO
	if ( !isset($_SESSION['id']) ){
	echo "<script>
   	window.location = 'login.php';
    </script>";
	$tpl->block("BLOCK_NAO_LOGADO");
	} else {
    $idbase = $_SESSION['id'];
	$cslogin = mysql_query("SELECT * FROM customers WHERE id = + $idbase");
	$logado = mysql_fetch_array($cslogin);	
	$tpl->USER_NAME = $logado['name'];
	$tpl->block("CHAT_LOGIN");	
	$tpl->block("BLOCK_LOGADO");	
	}
	
	$querydpt = mysql_query("SELECT * FROM departments order by id ASC");
    while($dpt = mysql_fetch_array($querydpt)){
    
    $tpl->ID_DEPARTMENT = $dpt['id'];
    $tpl->NAME_DEPARTMENT = $dpt['name_department'];

    $tpl->block("BLOCK_SELECT_DEP");
	
    } 
	
	
    if (@$_POST['opr'] == 'new') { 
	
	if( $_SESSION['captcha'] == $_POST['captcha']){
		
	$tpk = rand(111111,999999);
	$customer = anti_injection($_POST['customer']);
	$dpu = anti_injection($_POST['department']);
	
	$query_us = mysql_query("SELECT * FROM users WHERE suporte = '$dpu'");
    $users = mysql_fetch_array($query_us);
	$user = $users['id'];
	
	$date = date('Y-m-d');
	$data_email = date('d/m/Y');
	$dataen_email = date('m/d/Y');
	$hour = date('H:i:s');
	$department = $_POST['department'];
	$priority = $_POST['priority'];
	$subject = $_POST['subject'];
	$msg = $_POST['msg'];
	
	$crud = new crud('tickets');  // table
    $crud->inserir("customer,user,date,hour,department,priority,subject,msg,tpk,status", "'$customer','$user','$date','$hour','$department','$priority','$subject','$msg','$tpk','1'");
    
	$query_cc = mysql_query("SELECT * FROM customers WHERE id = '$customer'");
    $customer = mysql_fetch_array($query_cc);
    $customer_email = $customer['email'];
    $customer_name = $customer['name'];
    
    // SEND MAIL 
	require_once("adm/config/mail/class.phpmailer.php");
	$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '4'");
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

	$mail->AddAddress($customer_email);
	$mail->IsHTML(true); 
	$mail->CharSet = 'iso-8859-1'; 
	$mail->Subject  = $template_mail['subject']; 
	
	$corpoDoEmail = $template_mail['msg']; 
	$signature = $config['signature'];
	$logo = $config['logo'];
	
	// MAIL Variables
    $corpoDoEmail = str_replace( '%NOME%', $customer_name, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%TICKET%', $tpk, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATABR%', $data_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATAEN%', $dataen_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%ASSUNTO%', $subject, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%MENSAGEM%', $msg, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMAIL%',  $customer_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
    
    // END Variables
	$mail->Body = $corpoDoEmail;
	$SendOK = $mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	if ($SendOK) {
	header("Location: tickets.php?a=new"); // redirect send mail ok
    } else {
	echo "<script>
        alert ('Error sending email!');
        document.location.href = ('tickets.php?a=new');
	</script>";
    }
	// END SEND 
		
    }else{
    $tpl->CAPTCHA = '<div class="alert alert-danger">Error - Captcha Invalid Code </div>';
	}
	
	} // End
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->CUSTOMER_NAME = $logado['name'];
	$tpl->CUSTOMER_EMAIL = $logado['email'];
	$tpl->CUSTOMER_ID = $logado['id'];
	
	
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
