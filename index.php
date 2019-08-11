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
    $filename = 'adm/config/conexao.php';
    if (!file_exists($filename)) {
	header("Location: install.php");
    }
    
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
	
    $tpl = new MVC("theme/$thema/index.html"); 
    $tpl->addFile("NAVBAR", "theme/$thema/inc/navbar.html"); 
    $tpl->addFile("DEMO", "theme/$thema/inc/demo.html"); 
    $tpl->addFile("FOOTER", "theme/$thema/inc/footer.html"); 
    
    require('adm/config/whois.php');
	
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

    
    if(isset ($_POST['check'])){
	
	if (isset($_POST['domain'])){ 
			if (check_availability($_POST['domain']) == true) { 
				$tpl->CHECK = "<strong>".$_POST['domain'] . "</strong> avaliable!";
				$tpl->block("BLOCK_AVALIABLE");
			} else {
				$tpl->CHECK = "<strong>".$_POST['domain'] . "</strong> not avaliable!";
				$tpl->block("BLOCK_NOT_AVALIABLE");
			}
			$tpl->CHECKWHOIS = "<pre>".show_whois($_POST['domain'])."</pre>"; 
			$tpl->block("BLOCK_WHOIS");
			$line = anti_injection($_POST['domain']);
			$domain = substr($line, 0, strpos($line, "."));
			$tld = substr($line, strpos($line, "."), (strlen($line) - strlen($domain)));
			$tpl->TLD = $tld;
			$tpl->DOMAIN = $domain;	
			
			$costld = mysql_query("SELECT * FROM tdl WHERE tdl = '$tld'");
			$rtld = mysql_fetch_array($costld);
			$tpl->AMOUNT = number_format($rtld['amount'],2,'.','');
			$tpl->PERIOD = $rtld['period'];
			
		}
		
	// END CHECK DOMAIN 
    }
    
    if(isset ($_POST['hosting'])){
	

	$line = anti_injection($_POST['domain']);
	$domain = substr($line, 0, strpos($line, "."));
    $tld = substr($line, strpos($line, "."), (strlen($line) - strlen($domain)));
	$tpl->TLD = $tld;
	$tpl->DOMAIN = $domain;
	$costld = mysql_query("SELECT * FROM tdl WHERE tdl = '$tld'");
	$rtld = mysql_fetch_array($costld);
	$tpl->AMOUNT = number_format($rtld['amount'],2,'.','');
    $tpl->PERIOD = $rtld['period'];	
	$tpl->block("BLOCK_HOSTING");
    }
    
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->CURRENCY = $config['moeda'];
	
	//DATE DEMO
	$tpl->DATE_DEMO = date('m-d-Y');
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
