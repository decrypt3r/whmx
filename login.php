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
	
    $tpl = new MVC("theme/$thema/login.html"); 
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
    
    if (@$_POST['opr'] == 'login') { 
	$email = anti_injection($_POST['username']);
	$passmdr = md5(anti_injection($_POST['password']));
    
    $query = mysql_query("SELECT * FROM customers WHERE email = '$email' AND pass = '$passmdr'");
    $linha = mysql_fetch_array($query);
	$contagem = mysql_num_rows($query); 
	
	if (@$contagem == 1 ) {
	if($linha['status'] == 'S') { 
	$_SESSION['id'] = $linha['id']; // ID 
	echo "<script>
		window.location = 'dashboard.php';
		</script>";
	} else { 
	
	$tpl->LOGIN_BLOCK = "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4><i class='icon fa fa-ban'></i> Alert!</h4> Sorry, your access has been blocked!
                  </div>"; 
	}
	} else {
	$tpl->LOGIN_ERROR = "<div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                    <h4><i class='icon fa fa-ban'></i> Alert!</h4> User attention or invalid password!
                  </div>"; 
	}
	}
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->INPUT_EMAIL = $_POST['username'];
	if (@$_GET['register'] == 'new') { 
	$tpl->REGISTER_LOGIN = '<div class="alert alert-success">Success - Congratulations your account has been successfully created! </div>';
	}
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
