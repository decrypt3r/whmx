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
    require_once("adm/whm.class.php");// WHM API
    require_once("adm/config/mail/class.phpmailer.php");
    
    $con = new conexao(); 
    
    $con->connect(); 
    
    unset($_SESSION['tld']);
    unset($_SESSION['domain']);
    
    $dbconfig = mysql_query("SELECT * FROM config WHERE id = '1'");
    $config = mysql_fetch_array($dbconfig);
    $thema = $config['thema'];

	use megaphp\view\MVC;
	
    $tpl = new MVC("theme/$thema/checkout.html"); 
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
	
	if ($_SESSION['referencia'] == '') {
	$codigoCaptcha = substr(md5( time()) ,0,36);
	$_SESSION['referencia'] = $codigoCaptcha;
	} else {
	$_SESSION['referencia'];
	}
	
	if($_POST['validpromocode'] <> '') { 
	$validpromocode = anti_injection($_POST['validpromocode']); 
	$tpl->VALID_PROMOCODE = $validpromocode; 
	// PROMOCODE
	$prm = mysql_query("SELECT * FROM promotions WHERE code = '$validpromocode' AND status = 'S' AND date < NOW() ");
	$promocode = mysql_fetch_array($prm);
	$idcupomse = $promocode['id'];
	$sql = mysql_query("UPDATE promotions SET `utilized` = ( `utilized` + 1) WHERE id = '$idcupomse'");	
	$tpl->block("BLOCK_PROMOCODE");
	// END
    }
	
	$acao = anti_injection($_POST['acao']);
	$cod =  anti_injection($_POST['cod']);
	if ($acao == "incluir")
	{	
			
				$idsession = $_SESSION['referencia'];
				$date = date('Y-m-d');
				$hour = date('H:i:s');
				
				if($_POST['domainoption'] == 'register') { 
				$domain = anti_injection($_POST['domainnew']);
				$tld = anti_injection($_POST['tldnew']);
				$dns1 = anti_injection($_POST['dns1']);
				$dns2 = anti_injection($_POST['dns2']);
				$dns3 = anti_injection($_POST['dns3']);
				$dns4 = anti_injection($_POST['dns4']);
				$product = anti_injection($_POST['ppid']);
				
				$domn = mysql_query("SELECT * FROM tdl WHERE tdl = '$tld'");
				$dmn = mysql_fetch_array($domn);
				
				$price_domain = $dmn['amount'];
				
			    } else {
				$domain = anti_injection($_POST['domain']);
				$tld = anti_injection($_POST['tld']);	
				$product = anti_injection($_POST['ppid']);
				}
				$domainoption = anti_injection($_POST['domainoption']);
				$price_product = anti_injection($_POST['price_p']);
				
				$crud = new crud('temp_cart');  // table
				$crud->inserir("sessao,domain,price_domain,tld,dns1,dns2,dns3,dns4,domainoption,product,price_product,date,hour,period", "'$idsession','$domain','$price_domain','$tld','$dns1','$dns2','$dns3','$dns4','$domainoption','$product','$price_product','$date','$hour',''");	
			
	}	
	
	if ($acao == "atualizar")
	{
	
	$idcart = anti_injection($_POST['idcart']);
	$dns1 = anti_injection($_POST['dns1']);
	$dns2 = anti_injection($_POST['dns2']);
	$dns3 = anti_injection($_POST['dns3']);
	$dns4 = anti_injection($_POST['dns4']);
	
	$crud = new crud('temp_cart'); // Table
    $crud->atualizar("dns1='$dns1',dns2='$dns2',dns3='$dns3',dns4='$dns4'", "id='$idcart'");	
		
	}

	if ($acao == "excluir")
	{
		if ($cod != '')
		{
			if (is_numeric($cod))
			{	
				
			$crud = new crud('temp_cart'); // table
			$crud->excluir("id = $id"); // ID	
				
			}
		}
	}

	$sql_meu_carrinho = "SELECT * FROM temp_cart WHERE sessao = '".$_SESSION['referencia']."' ORDER BY id DESC";
	$exec_meu_carrinho =  mysql_query($sql_meu_carrinho);
	$qtd_meu_carrinho = mysql_num_rows($exec_meu_carrinho);
    
	  if ($qtd_meu_carrinho > 0)
	  {
		$soma_carrinho = 0;
		while ($row_rs_produto_carrinho = mysql_fetch_assoc($exec_meu_carrinho))
		{
		
		$soma_carrinho += ($row_rs_produto_carrinho['price_domain']+$row_rs_produto_carrinho['price_product']);
		
		$idpd = $row_rs_produto_carrinho['product'];
		$sql_product = mysql_query("SELECT * FROM products WHERE id = '$idpd' ORDER BY id DESC");
		$product =  mysql_fetch_assoc($sql_product);
		
		$iddp = $row_rs_produto_carrinho['tld'];
		$sql_domain = mysql_query("SELECT * FROM tdl WHERE tdl = '$iddp' ORDER BY id DESC");
		$domain =  mysql_fetch_assoc($sql_domain);
		
		$tpl->PRODUCT_ID = $row_rs_produto_carrinho['id'];
		$tpl->PRODUCT_NAME = $product['product'];
		
		if($row_rs_produto_carrinho['domainoption'] == 'register') { 
		$tpl->CART_PRICE = number_format($row_rs_produto_carrinho['price_product'] + $row_rs_produto_carrinho['price_domain'],2,'.','');
		$tpl->PERIOD = $domain['period'] . " / Years";
		$tpl->block("BLOCK_REGISTER");	
		}
		if($row_rs_produto_carrinho['domainoption'] == 'owndomain') { 
		$tpl->CART_PRICE = number_format($row_rs_produto_carrinho['price_product'],2,'.','');	
		}
		
		$tpl->DOMAIN = $row_rs_produto_carrinho['domain']."".$row_rs_produto_carrinho['tld'];
		$tpl->block("BLOCK_CHECKOUT");	
		}
		}
	
	    
    
    if ( !isset($_SESSION['id']) ){
	$tpl->INPUT_FORM = '<input type="hidden" name="submit" value="register"/>';
	$tpl->block("BLOCK_FORM_REGISTER");
	} else {
		
	$tpl->CUSTOMER_NAME = $logado['name'];
	$tpl->CUSTOMER_COMPANY = $logado['company'];
	$tpl->CUSTOMER_MAIL = $logado['email'];
	$tpl->CUSTOMER_TAXID = $logado['cpf_cnpj'];
	$tpl->CUSTOMER_ADDRESS = $logado['address'];
	$tpl->CUSTOMER_NUMBER = $logado['number'];
	$tpl->CUSTOMER_CITY = $logado['city'];
	$tpl->CUSTOMER_STATE = $logado['state'];
	$tpl->CUSTOMER_ZIPCODE = $logado['zipcode'];
	$tpl->INPUT_FORM = '<input type="hidden" name="submit" value="logged"/>';	
	$tpl->block("BLOCK_CUSTOMER");	
	}
	
	if ((isset($_GET["action"])) && ($_GET["action"] == "delete")) {
    $id = anti_injection($_GET['idc']); 
    $crud = new crud('temp_cart'); // Table
    $crud->excluir("id = $id"); // ID
    
    header("Location: checkout.php");
    }
    if ((isset($_GET["action"])) && ($_GET["action"] == "ssrd")) {
    $ssn = anti_injection($_GET['ssn']); 
    mysql_query("DELETE FROM temp_cart WHERE sessao='$ssn'");
    
    header("Location: checkout.php");
    }
    
    // PROMOCODE
		$valor = $soma_carrinho; // Total
		$percentual = $promocode['valor'] / 100.0; // %
		$valor_final = $valor - ($percentual * $valor);
		$xc = $soma_carrinho - $valor_final;
		define('DESCONTA', $xc);	 
		// END 	
	    $tpl->DESCONTO = number_format($xc,2,'.','');
		$tpl->SUBCART = number_format($soma_carrinho,2,'.','');
		$tpl->TOTAL = number_format($soma_carrinho - $xc,2,'.','');
    
    if($_POST['submit'] == 'register') { 	
	// Registra e Fatura ou Login
	if ($_POST["loginemail"] == '') {
	// CRIAR
	if( $_SESSION['captcha'] == $_POST['captcha']){
		
	$name = anti_injection($_POST['name']);
    $company = anti_injection($_POST['company']);
    $email = anti_injection($_POST['email']);
    $cpf_cnpj = anti_injection($_POST['cpf_cnpj']);
    $passemail = anti_injection($_POST['password']);
    $pass = md5($_POST['password']);
    $salt = base64_encode($_POST['password']);
    $address = anti_injection($_POST['address']);
    $number = anti_injection($_POST['number']);
    $city = anti_injection($_POST['city']);
    $state = anti_injection($_POST['state']);
    $zipcode = anti_injection($_POST['zipcode']);
    $country = anti_injection($_POST['country']);
    
    $cm = mysql_query("SELECT * FROM customers WHERE email = '$email'");
	$vrf = mysql_fetch_array($cm);	
    $verifica = mysql_num_rows($cm); 
    
    if($verifica == '1') { 
    $tpl->ERROR_REGISTER = '<div class="alert alert-danger">Error - Sorry email address already registered!</div>';
	} else { 	
	
	$crud = new crud('customers');  // table
    $crud->inserir("name,company,cpf_cnpj,email,pass,salt,address,number,city,state,zipcode,country,status", "'$name','$company','$cpf_cnpj','$email','$pass','$salt','$address','$number','$city','$state','$zipcode','$country','S'");
	
	$querylogin = mysql_query("SELECT * FROM customers WHERE email = '$email' AND pass = '$pass'");
    $renewlogin = mysql_fetch_array($querylogin);
	$_SESSION['id'] = $renewlogin['id']; // New Login
	
	// SEND MAIL 
	$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '16'");
    $template_mail = mysql_fetch_array($query_tpl);
	
	$data_email = date('d/m/Y H:i:s');
	$dataen_email = date('m/d/Y H:i:s');
	$corpoDoEmail = $template_mail['msg']; 
	$signature = $config['signature'];
	$logo = $config['logo'];
	$companyc = $config['company'];
	
	$mail = new PHPMailer();
	$mail->IsSMTP();		
	$mail->SMTPDebug = 0;		
	$mail->SMTPAuth = true;		
	$mail->IsHTML(true);
	$mail->Host = $config['smtp_host'];	
	$mail->Port = $config['smtp_port'];		
	$mail->Username = $config['smtp_user'];	
	$mail->Password = $config['smtp_pass'];	

	$mail->From = $template_mail['from_mail'];  
	$mail->FromName = $template_mail['name_from']; 
    $email = $_POST['email'];
	$mail->AddAddress($email);
	$mail->IsHTML(true); 
	$mail->CharSet = 'iso-8859-1'; 
	$mail->Subject  = $template_mail['subject']; 
	
	// MAIL Variables
    $corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMPRESA%',  $companyc, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATABR%', $data_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATAEN%', $dataen_email, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
    
    // END Variables
	$mail->Body = $corpoDoEmail;
	$SendOK = $mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	if ($SendOK) {
	header("Location: checkout.php?a=register"); // redirect send mail ok
    } else {
	header("Location: checkout.php?a=register"); // Kill Error send mail	
	}
		
	}	
	}
	// END 
	} else {
	// Fazer Login e Faturar
	
	$email = anti_injection($_POST['loginemail']);
	$passmdr = md5($_POST['loginpw']);
    
    $query = mysql_query("SELECT * FROM customers WHERE email = '$email' AND pass = '$passmdr'");
    $linha = mysql_fetch_array($query);
	$contagem = mysql_num_rows($query); 
	
	if (@$contagem == 1 ) {
	if($linha['status'] == 'S') { 
	$_SESSION['id'] = $linha['id']; // ID 
	header("Location: checkout.php?a=login"); // redirect send mail ok
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
	}
    // END 
   
    
    
    if($_POST['submit'] == 'logged') { 	
	
	// Faturar
	
	$sessao = anti_injection($_POST['idrefersession']);
	$customer = anti_injection($logado['id']);
	$notes = anti_injection($_POST['notes']);
	$paymentmethod = anti_injection($_POST['paymentmethod']);
	$p_order = rand(111111,999999);
	$datex = date('Y-m-d');
	$hourx = date('H:i:s');
	
	$tmcart = mysql_query("SELECT * FROM temp_cart WHERE sessao = '$sessao'");
	$tempcart = mysql_fetch_array($tmcart);
	
	$crud = new crud('requests');  // table
    $crud->inserir("p_order,customer,payment,date,hour,sessao,obs,status", "'$p_order','$customer','$paymentmethod','$datex','$hourx','$sessao','$notes','A'");
	
	$query1 = mysql_query("SELECT MAX(ID) as id FROM requests");
    $dados1 = mysql_fetch_assoc($query1);
    $id_request = $dados1['id'];
	
	// Order & Domain
	$tmcarts = mysql_query("SELECT * FROM temp_cart WHERE sessao = '$sessao'");
	while($ccr = mysql_fetch_array($tmcarts)){
	
	$valortdsp = $ccr['price_product'] + $ccr['price_domain'];
	$product = $ccr['product'];
	$domain = $ccr['domain'];
	$tld = $ccr['tld'];
	$dns1 = $ccr['dns1'];
	$dns2 = $ccr['dns2'];
	$dns3 = $ccr['dns3'];
	$dns4 = $ccr['dns4'];
	$qtd = '1';
	
	if($ccr['domainoption'] == 'owndomain') { 
	$actions = 'Transfer';	
	}
	if($ccr['domainoption'] == 'register') { 
	$actions = 'Register';	
	}
	
	// Domains
	if($ccr['domainoption'] == 'register') { 
		
	$date_expire = date('Y-m-d', strtotime("+365 days"));
	$date_register = date('Y-m-d');
	
	$tplds = mysql_query("SELECT * FROM tdl WHERE tdl = '$tld'");
	$ctld = mysql_fetch_array($tplds);
		
	$amount = number_format($ctld['amount'],2,'.','');
	$amount_recurrent = number_format($ctld['amount'],2,'.','');
	
	$crud = new crud('domains');  // table
    $crud->inserir("customer,domain,tdl,dns1,dns2,date_register,date_expire,amount,amount_recurrent,autorenew,status", "'$customer','$domain','$tld','$dns1','$dns2','$date_register','$date_expire','$amount','$amount_recurrent','no','S'");
	}
	// End
	$crud = new crud('requests_tbl');  // table
    $crud->inserir("request,customer,product,domain,tld,dns1,dns2,dns3,dns4,qtd,amount,actions", "'$id_request','$customer','$product','$domain','$tld','$dns1','$dns2','$dns3','$dns4','$qtd','$valortdsp','$actions'");
	
	$query3 = mysql_query("SELECT MAX(ID) as id FROM requests_tbl");
    $dados3 = mysql_fetch_assoc($query3);
    $id_request_tb = $dados3['id'];
	
	// AUTO
	$tbprds = mysql_query("SELECT * FROM products WHERE id = '$product'");
	$tbproduct = mysql_fetch_array($tbprds);
	if($tbproduct['automation'] == '2') { 
	$idsrvc = $tbproduct['server'];
	$dbcs = mysql_query("SELECT * FROM servers WHERE id = '$idsrvc'");
    $srv = mysql_fetch_array($dbcs);
	$whmpwd = $srv['pwd'];
	$whmuser = $srv['user'];
	$whmdomain = $srv['ip'];
	
	$WHM = new WHM( false , $whmdomain, $whmuser, $whmpwd);
	
	$rg = rand(111,999);
	$acctDomain = $domain.''.$tld;
	$acctUser = $domain.''.$rg;
	$acctPass = geraSenha(10, true, true, true);
	$acctPackg = $tbproduct['package'];
	$acctEmail = $logado['email'];
	$accDate = date('Y-m-d');
	$infowhm = $WHM->create_account($acctDomain,$acctUser,$acctPass,$acctEmail,$acctPackg);
	if($infowhm) { 
    $crud = new crud('accounts');  // table
    $crud->inserir("request,customer,server,package,domain,acctuser,acctpass,date,status", "'$id_request_tb','$customer','$idsrvc','$acctPackg','$acctDomain','$acctUser','$acctPass','$accDate','S'");
	
	// EMAIL 
	$emailcustomer = $logado['email'];
	$namecustomer = $logado['name'];
	$passcustomer = base64_decode($logado['salt']);
	$srvdns1 = $srv['dns1'];
	$srvdns2 = $srv['dns2'];
	$email_template = $tbproduct['email_template'];
	
	$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '$email_template'");
    $template_mail = mysql_fetch_array($query_tpl);
	
	$mail = new PHPMailer();
	$mail->IsSMTP();		
	$mail->SMTPDebug = 0;		
	$mail->SMTPAuth = true;		
	$mail->IsHTML(true);
	$mail->Host = $config['smtp_host'];	
	$mail->Port = $config['smtp_port'];		
	$mail->Username = $config['smtp_user'];	
	$mail->Password = $config['smtp_pass'];	

	$mail->From = $template_mail['from_mail'];  
	$mail->FromName = $template_mail['name_from']; 

	$mail->AddAddress($emailcustomer);
	$mail->IsHTML(true); 
	$mail->CharSet = 'iso-8859-1'; 
	$mail->Subject  = $template_mail['subject']; 
	
	$corpoDoEmail = $template_mail['msg']; 
	$signature = $config['signature'];
	$logo = $config['logo'];
	$company = $config['company'];

	
	// MAIL Variables
    $corpoDoEmail = str_replace( '%URLSERVER%', $whmdomain, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMAIL%', $emailcustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%NOME%', $namecustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SENHA%', $passcustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%CP_SENHA%', $acctPass, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%CP_LOGIN%', $acctUser, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DNS1%', $srvdns1, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DNS2%', $srvdns2, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DOMINIO%', $acctDomain, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SIGNATURE%', $signature, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGO%', $logo, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%COMPANY%', $company, $corpoDoEmail );
    
    
    // END Variables
	$mail->Body = $corpoDoEmail;
	$SendOK = $mail->Send();
	if ($SendOK) {
    }
	
	
    } // END OK WHM
	
	} // END AUTO

	}
	// Billing
	$q = mysql_query("SELECT SUM(amount * qtd) as amount FROM requests_tbl WHERE request = '$id_request'");
 	$r = mysql_fetch_array($q);
	$totalorder = number_format(($r["amount"] - $_POST['valordesconto']),2,'.','');
	
	$dvc = $config['dias_faturas'];
	$payment_date = date('Y-m-d', strtotime("+$dvc days"));
	$arrayData = explode("-",$payment_date);
    $ano = $arrayData[0];
    $mes = $arrayData[1];
    $dia = $arrayData[2];
    $date = date('Y-m-d');
	$invoice = rand(111111,999999);
	$crud = new crud('invoices');  // table
    $crud->inserir("customer,invoice,request,amount,total,date,expiration_date,payment_date,payment,dia,mes,ano,status", "'$customer','$invoice','$id_request','$amb','$totalorder','$date','$expiration_date','$payment_date','$paymentmethod','$dia','$mes','$ano','open'");
	
	$queryc = mysql_query("SELECT MAX(ID) as id FROM invoices");
    $dadosx = mysql_fetch_assoc($queryc);
    $inovc = $dadosx['id'];
	
	$gers = base64_encode($inovc);
	
	// EMAIL 
	$emailcustomer = $logado['email'];
	$namecustomer = $logado['name'];
	$companycustomer = $logado['company'];
	$city = $logado['city'];
	$address = $logado['address'];
	$cpf_cnpj = $logado['cpf_cnpj'];
	$passcustomer = base64_decode($logado['salt']);
	
	$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '13'");
    $template_mail = mysql_fetch_array($query_tpl);
	
	$mail = new PHPMailer();
	$mail->IsSMTP();		
	$mail->SMTPDebug = 0;		
	$mail->SMTPAuth = true;		
	$mail->IsHTML(true);
	$mail->Host = $config['smtp_host'];	
	$mail->Port = $config['smtp_port'];		
	$mail->Username = $config['smtp_user'];	
	$mail->Password = $config['smtp_pass'];	

	$mail->From = $template_mail['from_mail'];  
	$mail->FromName = $template_mail['name_from']; 

	$mail->AddAddress($emailcustomer);
	$mail->IsHTML(true); 
	$mail->CharSet = 'iso-8859-1'; 
	$mail->Subject  = $template_mail['subject']; 
	
	$corpoDoEmail = $template_mail['msg']; 
	$signature = $config['signature'];
	$logo = $config['logo'];
	$company = $config['company'];
	$dvc = $config['dias_faturas'];
	$vencimentobr = date('d/m/Y', strtotime("+$dvc days"));
	$vencimentorn = date('m-d-Y', strtotime("+$dvc days"));
	
	// MAIL Variables
    $corpoDoEmail = str_replace( '%NOME%', $namecustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%PEDIDO%', $p_order, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%FORMA_PAGTO%', $payment, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SENHA%',  $passcustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMPRESA%',  $companycustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%EMAIL%',  $emailcustomer, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%TOTAL%',  $totalorder, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATABR%',  $vencimentobr, $corpoDoEmail );
    $corpoDoEmail = str_replace( '%DATAEN%',  $vencimentorn, $corpoDoEmail );
    
    // END Variables
	$mail->Body = $corpoDoEmail;
	$SendOK = $mail->Send();
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	if ($SendOK) {
    }
    
    
	// KILL 
	unset($_SESSION['referencia']);
	
	header("Location: invoice-print.php?ord=$gers"); // redirect send mail ok
	
	}
	
	
    $tpl->SESSRID = $_SESSION['referencia'];
    $tpl->BASE = $config['base'];
	$tpl->TITLE = $config['title'];
	$tpl->COMPANY = $config['company'];
	$tpl->CURRENCY = $config['moeda'];
	$tpl->IPREG = $_SERVER['REMOTE_ADDR'];
	$tpl->IDSESSION = $_SESSION['referencia'];
	$tpl->ERRO_COMPANY = $lang['VALIDATE_COMPANY'];
	$tpl->ERRO_NAME = $lang['VALIDATE_NAME'];
	$tpl->ERRO_MIN_NAME = $lang['VALIDATE_NAME_MIN'];
	$tpl->ERRO_PWD = $lang['VALIDATE_PASS'];
	$tpl->ERRO_MIN_PWD = $lang['VALIDATE_PASS_MIN'];
	$tpl->ERRO_CONFIRM_PWD = $lang['VALIDATE_PASS_CONFIRM'];
	$tpl->ERROR_EMAIL = $lang['VALIDATE_EMAIL'];
	
	if($config['paypal'] == 'S') { 
		$tpl->block("BLOCK_PAYPAL");	
	}
	if($config['pagseguro'] == 'S') { 
		$tpl->block("BLOCK_PAGSEGURO");	
	}
	if($config['mercadopago'] == 'S') { 
		$tpl->block("BLOCK_MP");	
	}
	if($config['moip'] == 'S') { 
		$tpl->block("BLOCK_MOIP");	
	}
	if($config['skrill'] == 'S') { 
		$tpl->block("BLOCK_SKRILL");	
	}
	
	
	
	
    $tpl->PAGELANG = basename($_SERVER['PHP_SELF']);
    // SHOW TPL
    $tpl->show();

?>
