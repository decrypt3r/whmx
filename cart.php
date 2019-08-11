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
    
    $con = new conexao(); 
    $con->connect(); 
    
    
    $dbconfig = mysql_query("SELECT * FROM config WHERE id = '1'");
    $config = mysql_fetch_array($dbconfig);
    $thema = $config['thema'];

	use megaphp\view\MVC;
	
    $tpl = new MVC("theme/$thema/cart.html"); 
    $tpl->addFile("NAVBAR", "theme/$thema/inc/navbar.html"); 
    $tpl->addFile("DEMO", "theme/$thema/inc/demo.html"); 
    $tpl->addFile("FOOTER", "theme/$thema/inc/footer.html"); 
	
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
	
	$a = (isset ( $_GET ['a'] ) ? $_GET ['a'] : 'a');
	switch ($a) {
	
 	case 'hosting' : 
 	$grp = anti_injection($_GET['grp']);
    $queryprd = mysql_query("SELECT * FROM products WHERE status = 'S' AND p_group = '$grp' order by id DESC");
    $total = mysql_num_rows($queryprd);
    while($prd = mysql_fetch_array($queryprd)){
    
    $tpl->PRODUCT_ID = $prd['id'];
    $tpl->PRODUCT_NAME = $prd['product'];
	$tpl->PRODUCT_DESCRIPTION = $prd['description'];
	$tpl->PRODUCT_PRICE = number_format($prd['price'],2,'.','');
    $tpl->block("BLOCK_PRODUCTS");
	
    }
 	break;
 	
 	case 'confproduct' : 
 	$tpl->IDERPS = anti_injection($_GET['id']);
 	$idp = $_GET['id'];
 	$tps = mysql_query("SELECT * FROM temp_cart WHERE id = '$idp'");
    $tmp = mysql_fetch_array($tps);
    $tld = $tmp['tld'];
    $domn = mysql_query("SELECT * FROM tdl WHERE tdl = '$tld'");
    $dmn = mysql_fetch_array($domn);
    $tpl->DOMAIN = $tmp['domain'];
    $tpl->TLD = $tmp['tld'];
    $tpl->DNS1 = $tmp['dns1'];
    $tpl->DNS2 = $tmp['dns2'];
    $tpl->DNS3 = $tmp['dns3'];
    $tpl->DNS4 = $tmp['dns4'];
    $tpl->PERIOD = $dmn['period'];
   
    $tpl->block("BLOCK_CONFIGDOMAINS");
 	break;
 	
 	
 	case 'add' :
 	
 	$querytld = mysql_query("SELECT * FROM tdl order by id ASC");
    while($tld = mysql_fetch_array($querytld)){
    
    $tpl->TLD_TLD = $tld['tdl'];
    
    if($tld['tdl'] == $_GET['tld']) { $tpl->SELECTED = "selected"; } else { $tpl->SELECTED = ""; } 
    $tpl->block("BLOCK_SELECT_TLD");
	
    } 
 	 
	$tpl->block("BLOCK_FORM");	
 	break;
 	
 	case 'domain' : 
 	$tld = $_SESSION['tld'];
 	$domn = mysql_query("SELECT * FROM tdl WHERE tdl = '$tld'");
    $dmn = mysql_fetch_array($domn);
 	$tpl->PRICE_DOMAIN = $dmn['amount'];
 	$tpl->ACTIVE_DOMAIN = "active";
 	$tpl->PERIOD = $dmn['period'];
	$tpl->block("BLOCK_DOMAINS");
 	break;
		    	       
	case 'home' :
 	default :
 	$tpl->block("BLOCK_NONE");
 	break;
	}
    
    
    
    $querygrp = mysql_query("SELECT * FROM groups order by name ASC");
    while($grp = mysql_fetch_array($querygrp)){
    
    $tpl->GROUP_ID = $grp['id'];
    $tpl->GROUP_NAME = $grp['name'];
    
    if($grp['id'] == $_GET['grp']) { $tpl->ACTIVE_PRODUCT = "active"; } else { $tpl->ACTIVE_PRODUCT = ""; } 
    $tpl->block("BLOCK_GROUPS");
	
    }  
    
	
    $tpl->TLD = $_GET['tld'];
    $tpl->DOMAIN = $_GET['sld'];
    $tpl->PPID = $_GET['ppid'];
    $tpl->PRICE_P = $_POST['price_p'];
    $tpl->TOKEN = rand_char(2);
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->CURRENCY = $config['moeda'];
	
    $tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
