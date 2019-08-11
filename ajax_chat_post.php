<?php
    session_start();
    ob_start();
    require_once("adm/config/conexao.class.php");
    require_once("adm/config/crud.class.php");
	
	if ($_SESSION['chat_customer'] == '') {
	$chatCtm = substr(md5( time()) ,0,36);
	$_SESSION['chat_customer'] = $chatCtm;
	} else {
	$_SESSION['chat_customer'];
	}
	$sessao = $_SESSION['chat_customer'];
    
    $con = new conexao(); 
    $con->connect(); 
    
    $msg = utf8_decode($_POST['msg']);
    $date = date('Y-m-d H:i:s');
    $customer = $_SESSION['id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $getOs = getOs();
    
    
    $crud = new crud('chat');  // table
    $crud->inserir("customer,sessao,date,msg,tp,ip,os,status", "'$customer','$sessao','$date','$msg','1','$ip','$getOs','S'");
    
    ?>
    
