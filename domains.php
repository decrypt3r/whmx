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
	
    $tpl = new MVC("theme/$thema/domains.html"); 
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
    $querysrs = mysql_query("SELECT * FROM domains WHERE customer = '$id_customer' order by id DESC");
    while($domain = mysql_fetch_array($querysrs)){

    $tpl->DOMAIN_ID = $domain['id'];
	$tpl->DOMAIN_REG_DATE = data_en($domain['date_register']);
	$tpl->DOMAIN_NEXT_DATE = data_en($domain['date_expire']);
    $tpl->DOMAIN_DOMAIN = $domain['domain'];
    $tpl->DOMAIN_DNS1 = $domain['dns1'];
    $tpl->DOMAIN_DNS2 = $domain['dns2'];
    $tpl->DOMAIN_TLD = $domain['tdl'];
    $tpl->DOMAIN_AMOUNT = number_format($domain['amount'],2,'.','');
    
    if($domain['status'] == 'S') {
	$tpl->DOMAIN_STATUS = '<span class="label label-info">NEW REGISTER</span>';	
    }
    if($domain['status'] == 'N') {
	$tpl->DOMAIN_STATUS = '<span class="label label-danger">DISABLED</span>';	
    }
    if($domain['status'] == 'B') {
	$tpl->DOMAIN_STATUS = '<span class="label label-danger">BLOCKED</span>';	
    }
    if($domain['status'] == 'R') {
	$tpl->DOMAIN_STATUS = '<span class="label label-success">REGISTERED</span>';	
    }
    if($domain['status'] == 'A') {
	$tpl->DOMAIN_STATUS = '<span class="label label-success">ACTIVATED</span>';	
    }
    $tpl->block("BLOCK_DOMAINS");
    }
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->CURRENCY = $config['moeda'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
