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
	
    $tpl = new MVC("theme/$thema/services.html"); 
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
    $queryctm = mysql_query("SELECT * FROM requests WHERE customer = '$id_customer'");
    $request = mysql_fetch_array($queryctm);
    $id_request = $request['id'];
    $queryrqs = mysql_query("SELECT * FROM invoices WHERE request = '$id_request'");
    $invoice = mysql_fetch_array($queryrqs);
    
    $querysrs = mysql_query("SELECT * FROM requests_tbl WHERE customer = '$id_customer' AND product > '0' order by id DESC");
    while($services = mysql_fetch_array($querysrs)){
    $idp = $services['product']; 
    $idrt = $services['request']; 
    
    $queryctmm = mysql_query("SELECT * FROM requests WHERE id = '$idrt'");
    $ord = mysql_fetch_array($queryctmm);
    
    $queryprd = mysql_query("SELECT * FROM products WHERE id = '$idp'");
    $product = mysql_fetch_array($queryprd);
    
    $tpl->SERVICE_ID = base64_encode($services['id']);
    $tpl->SERVICE_ORDER = $ord['p_order'];
    $tpl->SERVICE_DOMAIN = $services['domain'];
    $tpl->SERVICE_TLD = $services['tld'];
    $tpl->SERVICE_AMOUNT = number_format($services['amount'],2,'.','');
    
    if($idp == '0') {
	$tpl->SERVICE_PRODUCT = 'Register Domain'; 
	} else {
    $tpl->SERVICE_PRODUCT = $product['product'];
    }
    
    if($ord['status'] == 'S') {
	$tpl->SERVICE_STATUS = '<span class="label label-success">CONFIRMED</span>';	
    }
    if($ord['status'] == 'A') {
	$tpl->SERVICE_STATUS = '<span class="label label-warning">OPEN</span>';	
    }
    if($ord['status'] == 'N') {
	$tpl->SERVICE_STATUS = '<span class="label label-danger">BLOCKED</span>';	
    }
    $datedue =  date('Y-m-d', strtotime('+30 days', strtotime($invoice['payment_date'])));
	$tpl->SERVICE_NEXTDUE = data_br($datedue);
    $tpl->block("BLOCK_SERVICES");
	
    } 
    
    
    
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->CURRENCY = $config['moeda'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
