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
    include_once("adm/config/common.php"); // Language
    
    $con = new conexao(); 
    $con->connect(); 
    
    $dbconfig = mysql_query("SELECT * FROM config WHERE id = '1'");
    $config = mysql_fetch_array($dbconfig);
    
    $id_invoices = base64_decode(anti_injection($_GET['ord']));
    $inv = mysql_query("SELECT * FROM invoices WHERE id = '$id_invoices'");
    $invoice = mysql_fetch_array($inv);
    
    $id_customer = $invoice['customer'];
    $ctm = mysql_query("SELECT * FROM customers WHERE id = '$id_customer'");
    $customer = mysql_fetch_array($ctm);
    
    // SESSION FOR ACCOUNTS 
	//LOGADO
	if ( !isset($_SESSION['id']) ){
	echo "<script>
   	window.location = 'login.php';
    </script>";
	} else {
    $idbase = $_SESSION['id'];
	$cslogin = mysql_query("SELECT * FROM customers WHERE id = + $idbase");
	$logado = mysql_fetch_array($cslogin);	
	}
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Invoice # <?php echo $invoice['invoice']; ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<style>
	.invoice-title h2, .invoice-title h3 {
    display: inline-block;
	}

	.table > tbody > tr > .no-line {
		border-top: none;
	}

	.table > thead > tr > .no-line {
		border-bottom: none;
	}

	.table > tbody > tr > .thick-line {
		border-top: 2px solid;
	}
	</style>
    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
  </head>
  <body>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<h2>Invoice</h2><h3 class="pull-right">Order # <?php echo $invoice['invoice']; ?></h3>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<strong>Billed To:</strong><br>
    					<?php echo $customer['name']; ?><br>
    					<?php echo $customer['address']; ?> <?php echo $customer['number']; ?><br>
    					<?php echo $customer['city']; ?><br>
    					<?php echo $customer['state']; ?> <?php echo $customer['zipcode']; ?>
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
        			<strong>Info:</strong><br>
    					Email:<br>
    					<?php echo $customer['email']; ?><br>
    					TAX ID<br>
    					<?php echo $customer['cpf_cnpj']; ?>
    				</address>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    					<strong>Payment Method:</strong><br>
    					
    					<?php if($invoice['status'] == 'confirmed') { ?>
						<?php echo $invoice['payment']; ?><br><hr>
    					<input type="submit" class="btn btn-info btn-lg" value="Invoice Paid">
    					<? } else { ?>
							
    					<?php if($invoice['payment'] == 'PayPal') { ?>
    					<?php echo $invoice['payment']; ?>
    					<hr>
    					<FORM ACTION="https://www.paypal.com/cgi-bin/webscr" target="paypal" METHOD="POST">
<input TYPE="hidden" NAME="cmd" VALUE="_xclick">
<input TYPE="hidden" NAME="business" VALUE="<?php echo $config['paypal_user']; ?>">
<input type="hidden" name="return" value="<?php echo $config['base']; ?>/dashboard.php" />
<input type="hidden" name="cancel" value="<?php echo $config['base']; ?>/paypal_cancel.php" />
<input type="hidden" name="notify_url" value="<?php echo $config['base']; ?>/paypal/index.php" />
<input type="hidden" name="quantity" value="1" />
<input TYPE="hidden" NAME="item_name" VALUE="Invoice #<?php echo $invoice['invoice']; ?>">
<input TYPE="hidden" NAME="invoice" VALUE="<?php echo $invoice['invoice']; ?>">
<input TYPE="hidden" NAME="item_number" VALUE="<?php echo $invoice['invoice']; ?>">
<input TYPE="hidden" NAME="amount" VALUE="<?php echo number_format($invoice['total'],2,'.',''); ?>">
<input TYPE="hidden" NAME="currency_code" VALUE="<?php echo $config['moeda']; ?>">
<input TYPE="hidden" NAME="first_name" VALUE="<?php echo $customer['name']; ?>">
<input TYPE="hidden" NAME="email" VALUE="<?php echo $customer['email']; ?>">
<input type="submit" class="btn btn-success btn-lg" value="Pay with PayPal">
</FORM> <?php } ?>

  <?php if($invoice['payment'] == 'PagSeguro') { ?>
    					<?php echo $invoice['payment']; ?>
    					<hr> 
		<form method="post" target="pagseguro" action="https://pagseguro.uol.com.br/v2/checkout/payment.html">  
        <input name="receiverEmail" type="hidden" value="<?php echo $config['pagseguro_email']; ?>">  
        <input name="currency" type="hidden" value="BRL">  
        <input name="itemId1" type="hidden" value="<?php echo $invoice['id']; ?>">  
        <input name="itemDescription1" type="hidden" value="Fatura #<?php echo $invoice['invoice']; ?>">  
        <input name="itemAmount1" type="hidden" value="<?php echo number_format($invoice['total'],2,'.',''); ?>">  
        <input name="itemQuantity1" type="hidden" value="1">  
        <input name="itemWeight1" type="hidden" value="1000">   
        <input name="reference" type="hidden" value="<?php echo $invoice['invoice']; ?>">  
        <input name="senderName" type="hidden" value="<?php echo $customer['name']; ?>">  
        <input name="senderEmail" type="hidden" value="<?php echo $customer['email']; ?>">  
		<input type="submit" class="btn btn-success btn-lg" value="Pagar com PagSeguro">
		</form>  
	<?php } ?>

					
					<?php if($invoice['payment'] == 'MercadoPago') { ?>
    					<?php echo $invoice['payment']; ?>
    					<hr> 

<?php
require_once ('mercadopago/mercadopago.php');

$unity_price = number_format($invoice['total'],2,'.','');
$currency_id = $config['mp_moeda'];
$mp_client_id = $config['mp_id'];
$mp_secret_id = $config['mp_token'];
$invoice_id = $invoice['invoice'];

$mp_p_name = $customer['name'];
$mp_p_email = $customer['email'];

$mp = new MP("$mp_client_id", "$mp_secret_id");
$mp_failure = $config['base'] . "/mp_cancel.php";
$mp_success = $config['base'] . "/mp_success.php";
$mp_pending = $config['base'] . "/mp_pending.php";

define('UNIT_PRICE', (float)$unity_price);

$preference_data = array(
	"items" => array(
		array(
			"title" => "Invoice #$invoice_id",
			"quantity" => 1,
			"currency_id" => "$currency_id", // Available currencies at: https://api.mercadopago.com/currencies
			"unit_price" => UNIT_PRICE
		)
	),
	"id" => $invoice_id,
	"payer" => array(
	"email" => "$mp_p_email",
	"name" => "$mp_p_name"
	),
	
	"back_urls" => array(
        "success" => "$mp_success",
        "failure" => "$mp_failure",
        "pending" => "$mp_pending"
    )
	
);

$preference = $mp->create_preference($preference_data);
?>
<a href="<?php echo $preference['response']['init_point']; ?>" name="MP-Checkout" class="blue-rn-m">Pay with MercadoPago</a>
<script type="text/javascript" src="https://www.mercadopago.com/org-img/jsapi/mptools/buttons/render.js"></script>
 
	<?php } ?>

<?php if($invoice['payment'] == 'Skrill') { ?>
    					<?php echo $invoice['payment']; ?>
    					<hr> 

<form action="https://www.moneybookers.com/app/payment.pl" method="post" target=_blank>
  <input type="hidden" name="pay_to_email" value="<?php echo $config['skrill_email']; ?>"/>
  <input type="hidden" name="status_url" value="<?php echo $config['base']; ?>/dashboard.php"/> 
  <input type="hidden" name="language" value="<?php echo $config['skrill_lang']; ?>"/>
  <input type="hidden" name="amount" value="<?php echo number_format($invoice['total'],2,'.',''); ?>"/>
  <input type="hidden" name="currency" value="<?php echo $config['skrill_moeda']; ?>"/>
  <input type="hidden" name="transaction_id" value="<?php echo $invoice['invoice']; ?>"/>
  <input type="hidden" name="return_url" value="<?php echo $config['base']; ?>/dashboard.php"/>
  <input type="hidden" name="recipient_description" value="Invoice #<?php echo $invoice['invoice']; ?>"/>
  <input type="hidden" name="merchant_fields" value="order_id" />
  <input type="hidden" name="order_id" value="<?php echo $invoice['invoice']; ?>" />
  <input type="submit" class="btn btn-success btn-lg" value="Pay with Skrill">
</form>
<?php } ?>


<?php if($invoice['payment'] == 'MoIP') { ?>
    					<?php echo $invoice['payment']; ?>
    					<hr> 

<form action="https://www.moip.com.br/PagamentoMoIP.do" method="POST" target=_blank>
  <input type="hidden" name="id_carteira" value="<?php echo $config['moip_email']; ?>"/>
  <input type="hidden" name="valor" value="<?php echo number_format($invoice['total'],2,'',''); ?>"/>
  <input type="hidden" name="nome" value="Invoice #<?php echo $invoice['invoice']; ?>"/>
  <input name="pagador_nome" type="hidden" value="<?php echo $customer['name']; ?>">  
  <input name="pagador_email" type="hidden" value="<?php echo $customer['email']; ?>"> 
  <input type="hidden" name="id_transacao" value="<?php echo $invoice['invoice']; ?>" />
  <input type="submit" class="btn btn-success btn-lg" value="Pay with MoIP">
</form>
<?php } ?>




					<?php } ?>
					
					
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
    					<strong>Order Date:</strong><br>
    					<?php echo data_en($invoice['date']); ?><br><br>
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Order summary</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Item</strong></td>
        							<td class="text-center"><strong>Price</strong></td>
        							<td class="text-center"><strong>Domain</strong></td>
        							<td class="text-right"><strong>Action</strong></td>
                                </tr>
    						</thead>
    						<tbody>
    				<?php
					$ird = $invoice['request'];
 		  			$queryRF = mysql_query("SELECT p.*, c.product AS product FROM requests_tbl AS p INNER JOIN products AS c ON p.product = c.id WHERE p.request = '$ird' ORDER BY p.id DESC");
 		  			while($qres = mysql_fetch_array($queryRF)){
					?>
    							<tr>
    								<td><?php echo $qres['product']; ?></td>
    								<td class="text-center"><?php echo number_format($qres['amount'],2,'.',''); ?></td>
    								<td class="text-center"><?php echo $qres['domain']; ?><?php echo $qres['tld']; ?></td>
    								<td class="text-right"><?php echo $qres['actions']; ?></td>
    							</tr>
                                <?php } ?>
                                <?php
					$irc = $customer['id'];
					
					$ctr = mysql_query("SELECT * FROM requests_tbl WHERE request = '$ird' AND customer = '$irc'");
					$reqts = mysql_fetch_array($ctr);
					$ircm = $reqts['domain'];
 		  			$queryRFC = mysql_query("SELECT * FROM domains WHERE customer = '$irc' and domain = '$ircm' ORDER BY id DESC");
 		  			while($qresc = mysql_fetch_array($queryRFC)){
					?>
    							<tr>
    								<td>Register/Transfer</td>
    								<td class="text-center"><?php echo number_format($qresc['amount'],2,'.',''); ?></td>
    								<td class="text-center"><?php echo $qresc['domain']; ?><?php echo $qresc['tdl']; ?></td>
    								<td class="text-right">x</td>
    							</tr>
                                <?php } ?>
        <?php
        $q = mysql_query("SELECT SUM(amount * qtd) as amount FROM requests_tbl WHERE request = '$ird'");
 		$r = mysql_fetch_array($q);
	    $total = number_format($r["amount"],2,'.','');
	    ?>
    							<tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-center"><strong>Due Date</strong></td>
    								<td class="thick-line text-right"><?php echo data_en($invoice['payment_date']); ?></td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Total </strong></td>
    								<td class="no-line text-right">$<?php echo $total; ?></td>
    							</tr>
    							
    						</tbody>
    					</table>
    					<center><a href="dashboard.php">Back to Client Area</a></center>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>


<!-- JS -->    
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </body>
</html>

