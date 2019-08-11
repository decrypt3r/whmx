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
	
    $tpl = new MVC("theme/$thema/tickets.html"); 
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
    
    $querydtk = mysql_query("SELECT p.*, c.name AS name, c.email AS email, d.name_department AS name_department FROM tickets AS p INNER JOIN customers AS c ON p.customer = c.id INNER JOIN departments AS d ON p.department = d.id WHERE p.customer = '$id_customer' order by p.id DESC LIMIT 30");
    while($tkm = mysql_fetch_array($querydtk)){
    
    $tpl->TICKET_ID = base64_encode($tkm['id']);
    $tpl->TICKET_TPK = $tkm['tpk'];
    $tpl->TICKET_SUBJECT = limitText($tkm['subject'],70);
    $tpl->TICKET_DEPARTMENT = $tkm['name_department'];
    $tpl->TICKET_DATE =  data_en($tkm['date']);

	if($tkm['status'] == '1') { $tpl->STATUS = '<span class="label label-success">OPEN</span>'; } 
	if($tkm['status'] == '2') { $tpl->STATUS = '<span class="label label-warning">ANSWERED</span>'; } 
	if($tkm['status'] == '3') { $tpl->STATUS = '<span class="label label-primary">PROCESS</span>'; } 
	if($tkm['status'] == '4') { $tpl->STATUS = '<span class="label label-danger">CLOSED</span>'; } 
 
    $tpl->block("BLOCK_TICKETS");
	
    } 
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
