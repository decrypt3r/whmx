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
	
    $tpl = new MVC("theme/$thema/account.html"); 
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
	
    if (@$_POST['opr'] == 'update') { 
		
    $name = anti_injection($_POST['name']);
    $company = anti_injection($_POST['company']);
    $email = anti_injection($_POST['email']);
    $cpf_cnpj = anti_injection($_POST['cpf_cnpj']);
    $address = anti_injection($_POST['address']);
    $number = anti_injection($_POST['number']);
    $neighborhood = anti_injection($_POST['neighborhood']);
    $city = anti_injection($_POST['city']);
    $state = anti_injection($_POST['state']);
    $zipcode = anti_injection($_POST['zipcode']);
    $country = anti_injection($_POST['country']);
    $registerID = $logado['id'];
    
    $crud = new crud('customers'); // Table
    $crud->atualizar("name='$name',company='$company',cpf_cnpj='$cpf_cnpj',email='$email',address='$address',number='$number',neighborhood='$neighborhood',city='$city',state='$state',zipcode='$zipcode',country='$country'", "id='$registerID'");
	
	header("Location: account.php?mod=update");
    }
    
    
    $tpl->INPUT_NAME = $logado['name'];
    $tpl->INPUT_COMPANY = $logado['company'];
    $tpl->INPUT_EMAIL = $logado['email'];
    $tpl->INPUT_TAX = $logado['cpf_cnpj'];
    $tpl->INPUT_ADDRESS = $logado['address'];
    $tpl->INPUT_NUMBER = $logado['number'];
    $tpl->INPUT_NEIGHBORHOOD = $logado['neighborhood'];
    $tpl->INPUT_CITY = $logado['city'];
    $tpl->INPUT_STATE = $logado['state'];
    $tpl->INPUT_ZIPCODE = $logado['zipcode'];
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->ERRO_COMPANY = $lang['VALIDATE_COMPANY'];
	$tpl->ERRO_NAME = $lang['VALIDATE_NAME'];
	$tpl->ERRO_MIN_NAME = $lang['VALIDATE_NAME_MIN'];
	$tpl->ERRO_PWD = $lang['VALIDATE_PASS'];
	$tpl->ERRO_MIN_PWD = $lang['VALIDATE_PASS_MIN'];
	$tpl->ERRO_CONFIRM_PWD = $lang['VALIDATE_PASS_CONFIRM'];
	$tpl->ERROR_EMAIL = $lang['VALIDATE_EMAIL'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
