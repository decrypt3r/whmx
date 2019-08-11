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

    if (@$_GET['cLogin'] == 'login') { 
	$email = base64_decode(anti_injection($_GET['u']));
	$passmdr = anti_injection($_GET['p']);
    
    $query = mysql_query("SELECT * FROM customers WHERE email = '$email' AND pass = '$passmdr'");
    $linha = mysql_fetch_array($query);
	$contagem = mysql_num_rows($query); 
	
	if (@$contagem == 1 ) {

	$_SESSION['id'] = $linha['id']; // ID 
	echo "<script>
		window.location = 'dashboard.php';
		</script>";
	}
	}
    
	

?>
