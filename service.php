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
    require_once("adm/whm.class.php");
    
    $con = new conexao(); 
    $con->connect(); 
    
    $dbconfig = mysql_query("SELECT * FROM config WHERE id = '1'");
    $config = mysql_fetch_array($dbconfig);
    $thema = $config['thema'];

	use megaphp\view\MVC;
	
    $tpl = new MVC("theme/$thema/service.html"); 
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
	
	if($_POST['update'] == 'cancel') { 
		if( $_SESSION['captcha'] == $_POST['captcha']){
		$msg = anti_injection($_POST['msg']);
		$idservice = anti_injection($_POST['idservice']);
		$idrequest = anti_injection($_POST['idrequest']);
		$type = anti_injection($_POST['type']);
		$customer = $logado['id'];
		$date = date('Y-m-d H:i:s');
		
		$crud = new crud('cancellations');  // table
		$crud->inserir("customer,request,request_tb,msg,date,type,status", "'$customer','$idrequest','$idservice','$msg','$date','$type','N'");
	    $tpl->RESULTCANCEL = '<div class="alert alert-success">Cancellation request submitted successfully</div>';	
	    } else {
		$tpl->CAPTCHA = '<div class="alert alert-danger">Error - Captcha Invalid Code </div>';	
		}
    }
    
    
	
	if($_POST['update'] == 'uppwd') { 
	if($_POST['psw'] == 'HIGH') { 
	$accUser = anti_injection($_POST['cpuser']);
	$pass = anti_injection($_POST['pwd']);
	$cpdomain = anti_injection($_POST['cpdomain']);
	$server = anti_injection($_POST['server']);
	$query = mysql_query("SELECT * FROM servers WHERE id = '$server'");
	$rq = mysql_fetch_array($query);

	$whmdomain = $rq['domain'];
	$whmuser = $rq['user'];
	$whmpwd = $rq['pwd'];
	
	$WHM = new WHM( false, $whmdomain, $whmuser, $whmpwd);

	$result = $WHM->change_password_account($accUser,$pass);
	$crud = new crud('accounts'); // Table
	$crud->atualizar("acctpass='$pass'", "acctuser='$accUser'");
	$tpl->PWRESULT = '<div class="alert alert-success">Password changed successfully!</div>';
	
	// SEND MAIL
	$emailcustomer = $logado['email'];
	
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

	$mail->AddAddress($emailcustomer);
	$mail->IsHTML(true); 
	$mail->CharSet = 'iso-8859-1'; 
	$mail->Subject  = $template_mail['subject']; 
	
	$corpoDoEmail = $template_mail['msg']; 
	$signature = $config['signature'];
	$logo = $config['logo'];
	$company = $config['company'];
	$namecustomer = $logado['name'];
	
	// MAIL Variables
    $corpoDoEmail = str_replace( '%NOME%', $namecustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMAIL%', $emailcustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGIN%', $accUser, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DOMINIO%', $cpdomain, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SENHA%',  $pass, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
    
    // END Variables
	$mail->Body = $corpoDoEmail;
	$SendOK = $mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	
	} else { 
	$tpl->PWRESULT = '<div class="alert alert-danger">Error - Password not allowed , please provide a high password </div>';	
	}
	// END
	}
	// END
	
	function geraGrafico($largura, $altura, $valores, $referencias, $tipo = "p2"){
           $valores = implode(',', $valores);
           $referencias = implode('|', $referencias);
 
           return "http://chart.apis.google.com/chart?chs=". $largura ."x". $altura . "&amp;chd=t:" . $valores . "&amp;cht=p3&amp;chl=" . $referencias;
     }
	 
	$id_customer = $logado['id'];
	$idsrc = base64_decode(anti_injection($_GET['sr']));
	$srvc = mysql_query("SELECT * FROM requests_tbl WHERE id = '$idsrc'");
	$service = mysql_fetch_array($srvc);
	$idacc = $service['id'];
	$accts = mysql_query("SELECT * FROM accounts WHERE request = '$idacc' AND customer = '$id_customer'");
	$acc = mysql_fetch_array($accts);
	$idsrv = $acc['server'];
	$srvs = mysql_query("SELECT * FROM servers WHERE id = '$idsrv'");
	$srv = mysql_fetch_array($srvs);
	
	$whmdomain = $srv['domain'];
	$whmuser = $srv['user'];
	$whmpwd = $srv['pwd'];
	$accUser = $acc['acctuser'];
	
	$WHM = new WHM( false, $whmdomain, $whmuser, $whmpwd);

	$accounts = $WHM->search_account_by_user($accUser);
    foreach ($accounts as $ac){
	$totalkb = rrmb($ac['disklimit']);	
	$usedkb = rrmb($ac['diskused']);
	$disklimit = $ac['disklimit'];
	$diskused = $ac['diskused'];
	
	$tpl->GRAFICO = geraGrafico(500, 200, array($totalkb, $usedkb), array("Disk Total: $disklimit", "Disk Usage: $diskused"));
		
	}
   
    $tpl->SERVICE_ID = $service['id'];
    $tpl->SERVICE_REQUEST = $service['request'];
    $tpl->SERVICE_DOMAIN = $service['domain'];
    $tpl->SERVICE_TLD = $service['tld'];
    $tpl->SERVICE_HOSTNAME = $srv['hostname'];
    $tpl->SERVICE_SERVER = $srv['id'];
    $tpl->SERVICE_CP_USER = $acc['acctuser'];
    $tpl->SERVICE_CP_PWD = $acc['acctpass'];
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->GETID = $_GET['sr'];
	$tpl->ERRO_PWD = $lang['VALIDATE_PASS'];
	$tpl->ERRO_MIN_PWD = $lang['VALIDATE_PASS_MIN'];
	$tpl->ERRO_CONFIRM_PWD = $lang['VALIDATE_PASS_CONFIRM'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
