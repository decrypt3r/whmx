<?php
    session_start();
    ob_start();
    require_once("adm/config/conexao.class.php");
    require_once("adm/config/crud.class.php");

    $con = new conexao(); 
    $con->connect(); 
    
    $dbconfig = mysql_query("SELECT * FROM config WHERE id = '1'");
    $config = mysql_fetch_array($dbconfig);
    
    $registerID = $_POST['domain']; 
    $domain = $_POST['basedomain']; 
	$dns1 = $_POST['dns1']; 
	$dns2 = $_POST['dns2']; 
    
    $crud = new crud('domains'); // Table
    $crud->atualizar("dns1='$dns1',dns2='$dns2'", "id='$registerID'");
	
	$api_key = $config['above'];
    $dns = $dns1 . "," . $dns2;

  $query_url = "https://www.above.com/registrar/api/query.html?key=" . urlencode($api_key) . "&query=update_dns&domain=" . urlencode($domain) ."&dns=". urlencode($dns);
  $res = file_get_contents($query_url);
  if($res === false){
  
    exit("Connection error!");
 
  } else {
  
    $results = simplexml_load_string($res); 
    $result_attr = $results->attributes();
    
    if($result_attr['code'] == 100) { 
      echo htmlentities($domain) . ": Nameservers Updated Successfully";
    } else {     
      echo htmlentities($domain) . ": " . $results->msg;
    }
    
  }
	
	
?>
