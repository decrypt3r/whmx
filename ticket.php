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
	
    $tpl = new MVC("theme/$thema/ticket.html"); 
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
	
    $id_customer = $logado['id'];
    $idptk = base64_decode($_GET['tk']);
    $tpl->TICKET_ID_FORM = $idptk;
    $tpl->IDCLOSETICKET = $_GET['tk'];
    
    $tkpc = mysql_query("SELECT * FROM tickets WHERE id = '$idptk'");
	$trtk = mysql_fetch_array($tkpc);
	$id_dep = $trtk['department'];
	$tkdp = mysql_query("SELECT * FROM departments WHERE id = '$id_dep'");
	$trdp = mysql_fetch_array($tkdp);
	$mtk = mysql_query("SELECT * FROM messages WHERE ticket = '$idptk' order by id DESC LIMIT 1");
	$smtk = mysql_fetch_array($mtk);
	
	$tpl->TICKET_REG_ID = $trtk['tpk']; 
	$tpl->TICKET_REG_SUBJECT = $trtk['subject']; 
	$tpl->TICKET_REG_DEPARTMENT = $trdp['name_department']; 
	$tpl->TICKET_REG_DATE = data_en($trtk['date']); 
	$tpl->TICKET_REG_HOUR = $trtk['hour'];
	if($trtk['priority'] == 'A') { $tpl->TICKET_REG_PRIORITY = 'High';  } 
	if($trtk['priority'] == 'M') { $tpl->TICKET_REG_PRIORITY = 'Medium';  } 
	if($trtk['priority'] == 'B') { $tpl->TICKET_REG_PRIORITY = 'Low';  } 
	$tpl->TICKET_UP_DATE = data_en($smtk['date']); 
	$tpl->TICKET_UP_HOUR = $smtk['hour'];
    
    $querydtk = mysql_query("SELECT p.*, c.name AS name, c.email AS email, d.name_department AS name_department, u.name_user AS name_user FROM tickets AS p INNER JOIN customers AS c ON p.customer = c.id INNER JOIN departments AS d ON p.department = d.id INNER JOIN users AS u ON p.user = u.id WHERE p.customer = '$id_customer' AND p.id = '$idptk'");
    while($tkm = mysql_fetch_array($querydtk)){
    
    $tpl->TICKET_NAME = $tkm['name'];
    $tpl->TICKET_MSG = $tkm['msg'];
    $tpl->TICKET_HOUR = $tkm['hour'];
    $tpl->TICKET_DATE =  data_en($tkm['date']);
	
	$getId = $tkm['id'];
	$query_msg = mysql_query("SELECT p.*, c.name AS name, c.email AS email, d.name_user AS name_user FROM messages AS p INNER JOIN customers AS c ON p.customer = c.id INNER JOIN users AS d ON p.user = d.id WHERE p.ticket = '$getId' order by p.id DESC");
 	$qtd_msg = mysql_num_rows($query_msg);
 	
 	while($msg = mysql_fetch_array($query_msg)){ 
	if($msg['type'] == '1') { $tpl->TRU = 'info'; $tpl->MSG_NAME = $msg['name']; } else {  $tpl->TRU = 'warning'; $tpl->MSG_NAME = $msg['name_user'];    }
	
	$tpl->MSG_DATE = data_en($msg['date']); 
	$tpl->MSG_HOUR = $msg['hour']; 
	$tpl->MSG_MSG = $msg['msg'];
	
	$tpl->block("BLOCK_MESSAGES");		
	}
    $tpl->block("BLOCK_TICKET");
    } 
    
    if (@$_POST['opr'] == 'resp') { 
	if( $_SESSION['captcha'] == $_POST['captcha']){
    
    $dips = mysql_query("SELECT * FROM tickets WHERE id = '$idptk'");
	$tkc = mysql_fetch_array($dips);	
    
	$id = $_POST['ticketid'];
	$customer = $tkc['customer'];
	$customer_name = $logado['name'];
	$customer_email = $logado['email'];
	$user = $tkc['user'];
	$type = '1';
	$date = date('Y-m-d');
	$data_email = date('d/m/Y');
	$dataen_email = date('m/d/Y');
	$hour = date('H:i:s');
	$subject = $_POST['subject'];
	$msg = $_POST['msg'];
	$idT = base64_encode($id);
	
	$crud = new crud('messages');  // table
    $crud->inserir("ticket,customer,user,date,hour,subject,msg,type,status", "'$id','$customer','$user','$date','$hour','$subject','$msg','$type','S'");
	
	$crud = new crud('tickets'); // Table
    $crud->atualizar("status='2'", "id='$id'");
	
	// SEND MAIL 
	require_once("adm/config/mail/class.phpmailer.php");
	$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '2'");
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
	header("Location: ticket.php?tk=$idT"); // redirect send mail ok
    } else {
	echo "<script>
        alert ('Error sending email!');
        document.location.href = ('ticket.php?tk=$idT');
	</script>";
    }
	// END SEND 
	
	
	}else{
    $tpl->CAPTCHA = '<div class="alert alert-danger">Error - Captcha Invalid Code </div>';
	}
	}
	
	// CLOSE TICKET 
	
	if ((isset($_GET["action"])) && ($_GET["action"] == "close")) {
    $id = base64_decode($_GET['idtk']); 
    
    $crud = new crud('tickets'); // Table
    $crud->atualizar("status='4'", "id='$id'");
    
    // SEND MAIL 
	require_once("adm/config/mail/class.phpmailer.php");
	$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '3'");
    $template_mail = mysql_fetch_array($query_tpl);
    $data_email = date('d/m/Y');
	$dataen_email = date('m/d/Y');
	$hour = date('H:i:s');
	$customer_name = $logado['name'];
	$customer_email = $logado['email'];
	
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
    $corpoDoEmail = str_replace( '%DATABR%', $data_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATAEN%', $dataen_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMAIL%',  $customer_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
    
    // END Variables
	$mail->Body = $corpoDoEmail;
	$SendOK = $mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	if ($SendOK) {
	header("Location: tickets.php"); // redirect send mail ok
    } else {
	echo "<script>
        alert ('Error sending email!');
        document.location.href = ('tickets.php');
	</script>";
    }
	// END SEND
	
    
    }
    // END 
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->GET_TK = $_GET['tk'];
	$tpl->CUSTOMER_NAME = $logado['name'];
	$tpl->CUSTOMER_EMAIL = $logado['email'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
