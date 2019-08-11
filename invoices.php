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
	
    $tpl = new MVC("theme/$thema/invoices.html"); 
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
	
	$customer = $logado['id'];

    $inv = mysql_query("SELECT * FROM invoices WHERE customer = '$customer' order by id DESC");
    while($invoice = mysql_fetch_array($inv)){

    if($invoice['status'] == 'open') { $tpl->STATUS_INVOICE = 'warning'; } 
    if($invoice['status'] == 'canceled') { $tpl->STATUS_INVOICE = 'danger'; } 
    if($invoice['status'] == 'close') { $tpl->STATUS_INVOICE = 'danger'; } 
    if($invoice['status'] == 'payable') { $tpl->STATUS_INVOICE = 'warning'; } 
    if($invoice['status'] == 'billed') { $tpl->STATUS_INVOICE = 'info'; } 
    if($invoice['status'] == 'confirmed') { $tpl->STATUS_INVOICE = 'success'; } 
    if($invoice['status'] == 'open') { $tpl->STATUS = 'OPEN'; } 
    if($invoice['status'] == 'canceled') { $tpl->STATUS = 'CANCELED'; } 
    if($invoice['status'] == 'close') { $tpl->STATUS = 'CLOSE'; } 
    if($invoice['status'] == 'payable') { $tpl->STATUS = 'PAYABLE'; } 
    if($invoice['status'] == 'billed') { $tpl->STATUS = 'BILLED'; } 
    if($invoice['status'] == 'confirmed') { $tpl->STATUS = 'CONFIRMED'; } 
    
    $tpl->INVOICE_ID = base64_encode($invoice['id']);
    $tpl->INVOICE_CODE = $invoice['invoice'];
    $tpl->INVOICE_TOTAL = number_format($invoice['total'],2,'.','');
    $tpl->INVOICE_DATE = data_en($invoice['date']);
    
	$datedue =  $invoice['payment_date'];
	$tpl->INVOICE_DUE_DATE = data_en($datedue);
    $tpl->block("BLOCK_INVOICES");
	
    }
    
    
    
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->CURRENCY = $config['moeda'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
