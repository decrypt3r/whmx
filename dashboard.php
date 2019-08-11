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
	
    $tpl = new MVC("theme/$thema/dashboard.html"); 
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
	
	$tkp = mysql_query("SELECT * FROM tickets WHERE status = '1' AND customer = '$customer' order by id DESC");
    while($ticket = mysql_fetch_array($tkp)){
    $department = $ticket['department'];
    $dmp = mysql_query("SELECT * FROM departments WHERE id = '$department'");
    $dep = mysql_fetch_array($dmp);
    
    if($ticket['priority'] == 'A') { $tpl->TR = 'danger'; } 
    if($ticket['priority'] == 'M') { $tpl->TR = 'warning'; } 
    if($ticket['priority'] == 'B') { $tpl->TR = 'default'; } 
    
    $tpl->TICKET_ID = base64_encode($ticket['id']);
    $tpl->TICKET_TK = $ticket['tpk'];
    $tpl->TICKET_DEPARTMENT = $dep['name_department'];
    $tpl->TICKET_HOUR = $ticket['hour'];
    $tpl->TICKET_DATE = data_br($ticket['date']);
	$tpl->TICKET_SUBJECT = limitText($ticket['subject'],80);

    $tpl->block("BLOCK_TICKETS");
	
    }
	
	$inv = mysql_query("SELECT * FROM invoices WHERE customer = '$customer' AND status = 'open' order by id DESC limit 15");
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
    $tpl->INVOICE_DATE = data_en($ticket['date']);
    
	$datedue =  date('Y-m-d', strtotime('+30 days', strtotime($invoice['payment_date'])));
	$tpl->INVOICE_DUE_DATE = data_en($datedue);
    $tpl->block("BLOCK_INVOICES");
	
    }
	
    $cm = mysql_query("SELECT * FROM tickets WHERE customer = '$customer'");
    $qtd_tickets = mysql_num_rows($cm);
    $dm = mysql_query("SELECT * FROM domains WHERE customer = '$customer'");
    $qtd_domains = mysql_num_rows($dm);
    $rm = mysql_query("SELECT * FROM requests WHERE customer = '$customer'");
    $qtd_services = mysql_num_rows($rm);
    $cmd1 = mysql_query("SELECT * FROM tickets WHERE customer = '$customer' AND status = '1'");
	$ticketsopen = mysql_num_rows($cmd1);
	$innv = mysql_query("SELECT * FROM invoices WHERE customer = '$customer' AND status = 'open'");
	$qtd_invoices = mysql_num_rows($innv);
	
	$tpl->TICKETS = $qtd_tickets;
	$tpl->DOMAINS = $qtd_domains;
	$tpl->SERVICES = $qtd_services;
	$tpl->TICKETS_OPEN = $ticketsopen;
	$tpl->INVOICES_T = $qtd_invoices;
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->EMAIL = $config['email'];
	$tpl->CURRENCY = $config['moeda'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
