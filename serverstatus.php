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
	
    $tpl = new MVC("theme/$thema/serverstatus.html"); 
    $tpl->addFile("NAVBAR", "theme/$thema/inc/navbar.html"); 
    $tpl->addFile("DEMO", "theme/$thema/inc/demo.html"); 
    $tpl->addFile("FOOTER", "theme/$thema/inc/footer.html"); 
    
    // SESSION FOR ACCOUNTS 
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
	
	
	$querysr = mysql_query("SELECT * FROM servers order by id DESC");
    while($srv = mysql_fetch_array($querysr)){
    $srvon = $srv['ip'];
    $tpl->SERVER = $srv['server'];
    $buh = strtok( exec( "cat /proc/uptime" ), "." );
	$days = sprintf( "%2d", ($buh/(3600*24)) );
	$hours = sprintf( "%2d", ( ($buh % (3600*24)) / 3600) );
	$min = sprintf( "%2d", ($buh % (3600*24) % 3600)/60 );
	$sec = sprintf( "%2d", ($buh % (3600*24) % 3600)%60 );
 
    $tpl->UPTIME = "$days days, $hours hours, $min minutes, $sec seconds";
    
    $fp = @fsockopen($srvon, 80, $errno, $errstr, 1);
	if($fp >= 1){ 
	$tpl->CPANEL = 'success';
	$tpl->STATUS_CPANEL = '80';
	
	}else{
	$tpl->TD = 'danger';
	$tpl->STATUS = '80';
	} 
	
	$fpb = @fsockopen($srvon, 21, $errno, $errstr, 1);
	if($fpb >= 1){ 
	$tpl->FTP = 'success';
	$tpl->STATUS_FTP = '21';
	
	}else{
	$tpl->FTP = 'danger';
	$tpl->STATUS_FTP = '21';
	} 
	
	$fpc = @fsockopen($srvon, 25, $errno, $errstr, 1);
	if($fpc >= 1){ 
	$tpl->SMTP = 'success';
	$tpl->STATUS_SMTP = '25';
	
	}else{
	$tpl->SMTP = 'danger';
	$tpl->STATUS_SMTP = '25';
	} 
	
	$fpd = @fsockopen($srvon, 3306, $errno, $errstr, 1);
	if($fpd >= 1){ 
	$tpl->MYSQL = 'success';
	$tpl->STATUS_MYSQL = '3306';
	
	}else{
	$tpl->MYSQL = 'danger';
	$tpl->STATUS_MYSQL = '3306';
	} 
    
    $fpd = @fsockopen($srvon, 443, $errno, $errstr, 1);
	if($fpd >= 1){ 
	$tpl->SSL = 'success';
	$tpl->STATUS_SSL = '443';
	
	}else{
	$tpl->SSL = 'danger';
	$tpl->STATUS_SSL = '443';
	} 
    
    $fpe = @fsockopen($srvon, 21, $errno, $errstr, 1);
	if($fpe >= 1){ 
	$tpl->SSH = 'success';
	$tpl->STATUS_SSH = '21';
	
	}else{
	$tpl->SSH = 'danger';
	$tpl->STATUS_SSH = '21';
	} 
    
    $tpl->block("BLOCK_SERVERS");
	
    } 
	
    
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];

	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
