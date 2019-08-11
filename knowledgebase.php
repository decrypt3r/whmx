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
	
    $tpl = new MVC("theme/$thema/knowledgebase.html"); 
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
	
	$a = (isset ( $_GET ['a'] ) ? $_GET ['a'] : 'a');
	switch ($a) {
	
 	case 'displaycat' : 
	
	$catid = anti_injection($_GET['catid']);
	$querycts = mysql_query("SELECT * FROM tutorial WHERE category = '$catid' order by id DESC");
    while($articles = mysql_fetch_array($querycts)){
    
    $tpl->ARTG_ID = $articles['id'];
    $tpl->ARTG_TITLE = $articles['title'];
    $tpl->ARTG_DESC = limitText(strip_tags($articles['description']),160);
    $tpl->block("BLOCK_ARTICLES");
	
    } 
    $tpl->ARTG_CAT = $_GET['catid'];
	$tpl->block("BLOCK_ARTICLE");
	break;
	
	case 'displayarticle' : 
	
	$id = anti_injection($_GET['id']);
	$catid = anti_injection($_GET['catid']);
	$queryctgs = mysql_query("SELECT * FROM category WHERE id = '$catid'");
    $category = mysql_fetch_array($queryctgs);
	$tpl->DPARTG_CATID = $category['id'];
	$tpl->DPARTG_NAME = $category['name'];
	$qtd_cat = mysql_num_rows($queryctgs);
    $tpl->DPARTG_QTD = $qtd_cat;
	$tpl->block("BLOCK_CATG");
	
	$querycts = mysql_query("SELECT * FROM tutorial WHERE id = '$id'");
    $articles = mysql_fetch_array($querycts);
    
    $tpl->DPARTG_TITLE = $articles['title'];
    $tpl->DPARTG_DESC = $articles['description'];
    $tpl->block("BLOCK_DISPLAY");
	break;
	
	
	case 'search' : 
	
	$search = anti_injection($_POST['search']);
	
	$querycts = mysql_query("SELECT * FROM tutorial WHERE description like '%".$search."%' or title like '%".$search."%'");
    while($articles = mysql_fetch_array($querycts)){
    $tpl->SEARCH_ID = $articles['id'];
    $tpl->SEARCH_CAT = $articles['category'];
    $tpl->SEARCH_TITLE = $articles['title'];
    $tpl->SEARCH_DESC = limitText(strip_tags($articles['description']),160);
    $tpl->block("BLOCK_RESULTS");
    }
    $tpl->SEARCH = $search;
    $tpl->block("BLOCK_SEARCH");
	break;
	
	
	case 'home' :
 	default :
 	
 	$querycts = mysql_query("SELECT * FROM category order by name ASC");
    while($category = mysql_fetch_array($querycts)){
    $idcatr = $category['id'];
    $tpl->KNOW_ID = $category['id'];
    $tpl->KNOW_NAME = $category['name'];
    $tpl->KNOW_DESC = limitText($category['description'],160);
    $tpl->block("BLOCK_CATEGORIES");
	$cmg = mysql_query("SELECT * FROM category WHERE id = '$idcatr'");
    $qtd_cat = mysql_num_rows($cmg);
    $tpl->KNOW_QTD = $qtd_cat;
    } 
    
    
 	$tpl->block("BLOCK_NONE");
 	break;
	}
    
    
	
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	
	$tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
