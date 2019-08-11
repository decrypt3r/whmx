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
	require_once("adm/config/mail/class.phpmailer.php");
	require_once("adm/whm.class.php");// WHM API
	
    $con = new conexao(); 
    $con->connect(); 
    
    $dbconfig = mysql_query("SELECT * FROM config WHERE id = '1'");
    $config = mysql_fetch_array($dbconfig);
		
		$mail = new PHPMailer();
		$mail->IsSMTP();		
		$mail->SMTPDebug = 0;		
		$mail->SMTPAuth = true;		
		$mail->IsHTML(true);
		$mail->Host = $config['smtp_host'];	
		$mail->Port = $config['smtp_port'];		
		$mail->Username = $config['smtp_user'];	
		$mail->Password = $config['smtp_pass'];	
		$mail->IsHTML(true); 
		$mail->CharSet = 'iso-8859-1'; 
		$signature = $config['signature'];
		$logo = $config['logo'];
		$company = $config['company'];
    
    // Gerar Faturas 	
    $mesatual = date('m');
   	$anoatual = date('Y');
   		
    $inv = mysql_query("SELECT * FROM invoices WHERE status = 'open' || status = 'confirmed' AND  mes = '$mesatual' AND ano = '$anoatual' order by id DESC");
    while($invoice = mysql_fetch_array($inv)){
 
		$crate_invoice_dy = $config['dias_faturas'];
		$hoje = date('Y-m-d');
		$data = $invoice['payment_date'];
		$vencimento = date('Y-m-d', strtotime('+'.$crate_invoice_dy.' days', strtotime($data)));

		$a = explode("-","$vencimento");
		$b = explode("-","$hoje");
		$antiga= mktime(0, 0, 0, $b[1], $b[2], $b[0]);
		$atual= mktime(0, 0, 0, $a[1], $a[2], $a[0]);
		$diferenca= $atual-$antiga;
		$dias = floor($diferenca/84600);

		$customer = $invoice['customer'];
		$request = $invoice['request'];
		$amount = number_format($invoice['amount'],2,'.','');
		$total = number_format($invoice['total'],2,'.','');
		$date = date('Y-m-d', strtotime("+30 days"));
		
		$payment_date = date('Y-m-d', strtotime($date. " + $crate_invoice_dy days"));
		$payment_date_emailen = date('m-d-Y', strtotime($date. " + $crate_invoice_dy days"));
		$payment_date_emailbr = date('d/m/Y', strtotime($date. " + $crate_invoice_dy days"));
		$arrayData = explode("-",$payment_date);
		$ano = $arrayData[0];
		$mes = $arrayData[1];
		$dia = $arrayData[2];
		$payment = $invoice['payment'];		

		if($dias=='3') {
		
		$invcid = rand(111111,999999);
		
		$crud = new crud('invoices');  // table
		$crud->inserir("invoice,customer,request,amount,total,date,payment_date,payment,dia,mes,ano,status", "'$invcid','$customer','$request','$amount','$total','$date','$payment_date','$payment','$dia','$mes','$ano','open'");
		
		$queryC = mysql_query("SELECT * FROM customers WHERE id = '$customer'");
		$ctm = mysql_fetch_array($queryC);
		$name = $ctm['name'];
		$email = $ctm['email'];
		$cpf_cnpj = $ctm['cpf_cnpj'];
		$address = $ctm['address'];
		$city = $ctm['city'];
		$passemail = base64_decode($ctm['salt']);
		// SEND MAIL 
		
		$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '13'");
		$template_mail = mysql_fetch_array($query_tpl);
		$mail->From = $template_mail['from_mail'];  
		$mail->FromName = $template_mail['name_from']; 
		$mail->AddAddress($email);
		$mail->Subject  = $template_mail['subject']; 
		$corpoDoEmail = $template_mail['msg']; 
		
		// MAIL Variables
		$corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%PEDIDO%', $invcid, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%FORMA_PAGTO%', $payment, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%TOTAL%',  $total, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATABR%',  $payment_date_emailbr, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATAEN%',  $payment_date_emailen, $corpoDoEmail );
		
		// END Variables
		$mail->Body = $corpoDoEmail;
		$SendOK = $mail->Send();
		if ($SendOK) {}
		
		
		}
	} 
   	   
   	   
   	// Gerar emails atrasos + corte de hospedagem.
   	$mesatual = date('m');
   	$anoatual = date('Y');
   	
    $invr = mysql_query("SELECT * FROM invoices WHERE status = 'open' AND mes = '$mesatual' AND ano = '$anoatual' order by id DESC");
    while($invoicer = mysql_fetch_array($invr)){
	
	
		$dias_vencimento1 = $config['dias_vencimento1'];
		
		$hoje = date('Y-m-d');
		$data = $invoicer['payment_date'];
		$vencimento = date('Y-m-d', strtotime('+'.$dias_vencimento1.' days', strtotime($data)));

		$a = explode("-","$vencimento");
		$b = explode("-","$hoje");
		$antiga= mktime(0, 0, 0, $b[1], $b[2], $b[0]);
		$atual= mktime(0, 0, 0, $a[1], $a[2], $a[0]);
		$diferenca= $atual-$antiga;
		$dias = floor($diferenca/84600);

		$customer = $invoicer['customer'];
		$idreqts = $invoicer['request'];
		$payment = $invoicer['payment'];
		$invcid = $invoicer['invoice'];
		$payment_date_emailbr = data_br($invoicer['payment_date']);
		$payment_date_emailen = data_en($invoicer['payment_date']);
		
		if($dias=="-2") {
			
		$total = number_format($invoicer['total'],2,'.','');
		
		$queryC = mysql_query("SELECT * FROM customers WHERE id = '$customer'");
		$ctm = mysql_fetch_array($queryC);
		$name = $ctm['name'];
		$email = $ctm['email'];
		$cpf_cnpj = $ctm['cpf_cnpj'];
		$address = $ctm['address'];
		$city = $ctm['city'];
		$passemail = base64_decode($ctm['salt']);
		// SEND MAIL 
		
		$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '8'");
		$template_mail = mysql_fetch_array($query_tpl);
		$mail->From = $template_mail['from_mail'];  
		$mail->FromName = $template_mail['name_from']; 
		$mail->AddAddress($email);
		$mail->Subject  = $template_mail['subject']; 
		$corpoDoEmail = $template_mail['msg']; 
		
		// MAIL Variables
		$corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%PEDIDO%', $invcid, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%FORMA_PAGTO%', $payment, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%TOTAL%',  $total, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATABR%',  $payment_date_emailbr, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATAEN%',  $payment_date_emailen, $corpoDoEmail );
		
		// END Variables
		$mail->Body = $corpoDoEmail;
		$SendOK = $mail->Send();
		if ($SendOK) {}
		
		} elseif($dias=='-3') {
		
		$total = number_format($invoicer['total'],2,'.','');
		
		$queryC = mysql_query("SELECT * FROM customers WHERE id = '$customer'");
		$ctm = mysql_fetch_array($queryC);
		$name = $ctm['name'];
		$email = $ctm['email'];
		$cpf_cnpj = $ctm['cpf_cnpj'];
		$address = $ctm['address'];
		$city = $ctm['city'];
		$passemail = base64_decode($ctm['salt']);
		// SEND MAIL 
		
		$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '8'");
		$template_mail = mysql_fetch_array($query_tpl);
		$mail->From = $template_mail['from_mail'];  
		$mail->FromName = $template_mail['name_from']; 
		$mail->AddAddress($email);
		$mail->Subject  = $template_mail['subject']; 
		$corpoDoEmail = $template_mail['msg']; 
		
		// MAIL Variables
		$corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%PEDIDO%', $invcid, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%FORMA_PAGTO%', $payment, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%TOTAL%',  $total, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATABR%',  $payment_date_emailbr, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATAEN%',  $payment_date_emailen, $corpoDoEmail );
		
		// END Variables
		$mail->Body = $corpoDoEmail;
		$SendOK = $mail->Send();
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		if ($SendOK) {}	
		
	    } elseif($dias=='-4') {
		
		$total = number_format($invoicer['total'],2,'.','');
		
		$queryC = mysql_query("SELECT * FROM customers WHERE id = '$customer'");
		$ctm = mysql_fetch_array($queryC);
		$name = $ctm['name'];
		$email = $ctm['email'];
		$cpf_cnpj = $ctm['cpf_cnpj'];
		$address = $ctm['address'];
		$city = $ctm['city'];
		$passemail = base64_decode($ctm['salt']);
		// SEND MAIL 
		
		$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '8'");
		$template_mail = mysql_fetch_array($query_tpl);
		$mail->From = $template_mail['from_mail'];  
		$mail->FromName = $template_mail['name_from']; 
		$mail->AddAddress($email);
		$mail->Subject  = $template_mail['subject']; 
		$corpoDoEmail = $template_mail['msg']; 
		
		// MAIL Variables
		$corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%PEDIDO%', $invcid, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%FORMA_PAGTO%', $payment, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%TOTAL%',  $total, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATABR%',  $payment_date_emailbr, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATAEN%',  $payment_date_emailen, $corpoDoEmail );
		
		// END Variables
		$mail->Body = $corpoDoEmail;
		$SendOK = $mail->Send();
		if ($SendOK) {}	
		
	    }  elseif($dias == '-5') {
		
		$crud = new crud('requests'); // Table
		$crud->atualizar("status='N'", "id='$idreqts'");
		
		$inq = mysql_query("SELECT * FROM requests_tbl WHERE request = '$idreqts' AND customer = '$customer' order by id DESC");
        while($requesttb = mysql_fetch_array($inq)){
		$idres = $requesttb['id'];
		
		$crud = new crud('accounts'); // Table
		$crud->atualizar("status='N'", "request='$idres'");
		
		$accts = mysql_query("SELECT * FROM accounts WHERE request = '$idres' AND status = 'N'");
		$acc = mysql_fetch_array($accts);
		
		$product = $requesttb['product'];
		$tbprds = mysql_query("SELECT * FROM products WHERE id = '$product'");
	    $tbproduct = mysql_fetch_array($tbprds);
	    $idsrvc = $tbproduct['server'];
		    $dbcs = mysql_query("SELECT * FROM servers WHERE id = '$idsrvc'");
			$srv = mysql_fetch_array($dbcs);
			$whmpwd = $srv['pwd'];
			$whmuser = $srv['user'];
			$whmdomain = $srv['ip'];
			$acctUser = $acc['acctuser'];
			
			$WHM = new WHM( false , $whmdomain, $whmuser, $whmpwd);
		    $reason = 'Due';
			$resultcron = $WHM->suspend_account($acctUser,$reason); 
		
		$queryC = mysql_query("SELECT * FROM customers WHERE id = '$customer'");
		$ctm = mysql_fetch_array($queryC);
		$name = $ctm['name'];
		$email = $ctm['email'];
		$cpf_cnpj = $ctm['cpf_cnpj'];
		$address = $ctm['address'];
		$city = $ctm['city'];
		$passemail = base64_decode($ctm['salt']);
		// SEND MAIL 
		
		$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '9'");
		$template_mail = mysql_fetch_array($query_tpl);
		$mail->From = $template_mail['from_mail'];  
		$mail->FromName = $template_mail['name_from']; 
		$mail->AddAddress($email);
		$mail->Subject  = $template_mail['subject']; 
		$corpoDoEmail = $template_mail['msg']; 
		
		// MAIL Variables
		$corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%PEDIDO%', $invcid, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%FORMA_PAGTO%', $payment, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%TOTAL%',  $total, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATABR%',  $payment_date_emailbr, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATAEN%',  $payment_date_emailen, $corpoDoEmail );
		
		// END Variables
		$mail->Body = $corpoDoEmail;
		$SendOK = $mail->Send();
		if ($SendOK) {}	
		
		
	    }
	}
}    
   	   
   	   
   	    // Confirmar Pagamento
   	$anoatual = date('Y');
   		
    $inrc = mysql_query("SELECT * FROM invoices WHERE status = 'confirmed' AND tp = '1' AND ano = '$anoatual' order by id DESC");
    while($cob = mysql_fetch_array($inrc)){
	
		$registerID = $cob['id'];
		$customer = $cob['customer'];
		$request = $cob['request'];
		$payment = $cob['payment'];
		$total = number_format($cob['total'],2,'.','');
		$ddfr = date('Y-m-d');
		$payment_date_emailbr = data_br($ddfr); 
		$payment_date_emailen = data_en($ddfr); 
		
		$queryC = mysql_query("SELECT * FROM customers WHERE id = '$customer'");
		$ctm = mysql_fetch_array($queryC);
		$name = $ctm['name'];
		$email = $ctm['email'];
		$cpf_cnpj = $ctm['cpf_cnpj'];
		$address = $ctm['address'];
		$city = $ctm['city'];
		$passemail = base64_decode($ctm['salt']);
		
		$crud = new crud('invoices'); // Table
		$crud->atualizar("status='confirmed',tp='2'", "id='$registerID'");
		
		// UPTADE
	
		$crud = new crud('requests'); // Table
		$crud->atualizar("status='S'", "id='$registerID'");
		
		$querySx = mysql_query("SELECT * FROM requests_tbl WHERE request = '$registerID'");
 		while($resultd = mysql_fetch_array($querySx)){ 
		$idres = $resultd['id'];
		
		$accts = mysql_query("SELECT * FROM accounts WHERE request = '$idres'");
		$acc = mysql_fetch_array($accts);	
		
		$crud = new crud('accounts'); // Table
		$crud->atualizar("status='S'", "request='$idres'");
		
		$product = $resultd['product'];
		$tbprds = mysql_query("SELECT * FROM products WHERE id = '$product'");
	    $tbproduct = mysql_fetch_array($tbprds);
	    $idsrvc = $tbproduct['server'];
		    $dbcs = mysql_query("SELECT * FROM servers WHERE id = '$idsrvc'");
			$srv = mysql_fetch_array($dbcs);
			$whmpwd = $srv['pwd'];
			$whmuser = $srv['user'];
			$whmdomain = $srv['ip'];
			$acctUser = $acc['acctuser'];
			
			$WHM = new WHM( false , $whmdomain, $whmuser, $whmpwd);
			
			$infowhm = $WHM->unsuspend_account($acctUser); 
			if($infowhm) { 
			}
		
	    }
		
		// SEND MAIL 
		
		$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '12'");
		$template_mail = mysql_fetch_array($query_tpl);
		$mail->From = $template_mail['from_mail'];  
		$mail->FromName = $template_mail['name_from']; 
		$mail->AddAddress($email);
		$mail->Subject  = $template_mail['subject']; 
		$corpoDoEmail = $template_mail['msg']; 
		
		// MAIL Variables
		$corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%PEDIDO%', $invcid, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%FORMA_PAGTO%', $payment, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%TOTAL%',  $total, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATABR%',  $payment_date_emailbr, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATAEN%',  $payment_date_emailen, $corpoDoEmail );
		
		// END Variables
		$mail->Body = $corpoDoEmail;
		$SendOK = $mail->Send();
		if ($SendOK) {}
		
		// DESBLOQUEIO
		
		// SEND MAIL 
		
		$query_tpl = mysql_query("SELECT * FROM email_template WHERE id = '10'");
		$template_mail = mysql_fetch_array($query_tpl);
		$mail->From = $template_mail['from_mail'];  
		$mail->FromName = $template_mail['name_from']; 
		$mail->AddAddress($email);
		$mail->Subject  = $template_mail['subject']; 
		$corpoDoEmail = $template_mail['msg']; 
		
		// MAIL Variables
		$corpoDoEmail = str_replace( '%NOME%', $name, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%PEDIDO%', $invcid, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%FORMA_PAGTO%', $payment, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SENHA%',  $passemail, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMPRESA%',  $company, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CPFCNPJ%',  $cpf_cnpj, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%ENDERECO%',  $address, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%CIDADE%',  $city, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%EMAIL%',  $email, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%SIGNATURE%',  $signature, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%LOGO%',  $logo, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%TOTAL%',  $total, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATABR%',  $payment_date_emailbr, $corpoDoEmail );
		$corpoDoEmail = str_replace( '%DATAEN%',  $payment_date_emailen, $corpoDoEmail );
		
		// END Variables
		$mail->Body = $corpoDoEmail;
		$SendOK = $mail->Send();
		if ($SendOK) {}
		
		
	} 
	
	// Excluir pedidos de cancelamento site;   		
    $cancels = mysql_query("SELECT * FROM cancellations order by id DESC");
    while($cancd = mysql_fetch_array($cancels)){
	$xidref = $cancd['request'];
	$cancelidspr = $cancd['id'];
		
		$querycc = mysql_query("SELECT * FROM requests_tbl WHERE request = '$xidref'");
 		while($resultd = mysql_fetch_array($querycc)){ 
		$idres = $resultd['id'];
		
		$accts = mysql_query("SELECT * FROM accounts WHERE request = '$idres'");
		$acc = mysql_fetch_array($accts);	
		
		$product = $resultd['product'];
		$tbprds = mysql_query("SELECT * FROM products WHERE id = '$product'");
	    $tbproduct = mysql_fetch_array($tbprds);
	    $idsrvc = $tbproduct['server'];
		    $dbcs = mysql_query("SELECT * FROM servers WHERE id = '$idsrvc'");
			$srv = mysql_fetch_array($dbcs);
			$whmpwd = $srv['pwd'];
			$whmuser = $srv['user'];
			$whmdomain = $srv['ip'];
			$acctUser = $acc['acctuser'];
			
			$WHM = new WHM( false , $whmdomain, $whmuser, $whmpwd);
			
			$infowhm = $WHM->delete_account($acctUser); 
			if($infowhm) { 
			}
			
			$crud = new crud('requests'); // Table
			$crud->excluir("id = $xidref"); // ID
			
			$crud = new crud('requests_tbl'); // Table
			$crud->excluir("request = $xidref"); // ID
			
			$crud = new crud('invoices'); // Table
			$crud->excluir("request = $xidref"); // ID			
			
			$crud = new crud('accounts'); // Table
			$crud->excluir("request = $idres"); // ID
			
			
		
	    }
	    
	    $crud = new crud('cancellations'); // Table
		$crud->excluir("id = $cancelidspr"); // ID
		
	} 
	
	
		$datacron = date('d/m/Y H:i:s');
	    // SEND MAIL CRON
		$mail->From = $config['smtp_email'];  
		$mail->FromName = $config['company'];  
		$emailadmin = $config['email'];  
		$mail->AddAddress($emailadmin);
		$mail->Subject  = "CronJobs Activity";
		$corpoDoEmail = '
		<b>Cron Job Report for '.$datacron.'</b>
		<br>
		Invoices Created<br>
		Late Fees Added<br>
		Unpaid Invoice Payment Reminders Sent<br>
		Overdue Invoice Reminders Sent<br>
		Domain Renewal Notices Sent<br>
		Cancellation Requests Processed<br>
		Services Suspended<br><br>
		<hr size="1">
		Updated Disk & Bandwidth Usage Stats.<br>
		';
		// END Variables
		$mail->Body = $corpoDoEmail;
		$SendOK = $mail->Send();
		if ($SendOK) {}
	
?>
