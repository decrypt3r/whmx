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

ini_set('max_execution_time', 0); 
?>
<html>
	<head>
		<title>Database Installer</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
		<script>
		function check_db(x , e){
			e.preventDefault();
			var hostname = document.forms["db_install"]["db_host"].value;  
			var dbname = document.forms["db_install"]["db_name"].value;  
			var dbusername = document.forms["db_install"]["db_username"].value;  
			if (hostname == "" || hostname == null)  
				{  
					jQuery('#db_host').css('border','1px solid #FF0000');
					return false;
				}
			else if (dbname == "" || dbname == null)  
				{  
					jQuery('#db_name').css('border','1px solid #FF0000');
					return false;
				}
			else if (dbusername == "" || dbusername == null)  
				{  
					jQuery('#db_username').css('border','1px solid #FF0000');
					return false;
				}
			else {
					jQuery('#db_install').submit();
					return true;
				}
			
		} 
		
		jQuery(document).ready(function(){
				jQuery('.db_field').mouseover(function(){
						jQuery(this).css('border','1px solid #666');
					});
			});
			
		//ajax call for drop table	
		function drop_db() {
			 var checkboxChecked = $('input[name="drop_chk"]:checked').length ;
			 if(checkboxChecked > 0){
				 jQuery('#chk-label').hide();
				 jQuery('.img-loader').show();
				 jQuery.ajax
					({ 
						url: 'install.php',
						data: {action: 'drop'},
						type: 'post',
						success: function(response) {
									  jQuery('#show_drop_action td').html(response);
									  jQuery('.drop_info').hide();
									  jQuery('.img-loader').hide();
								  }
					});
			 }
			 else {
				 jQuery('#chk-label').show();
			}
		}
		</script>
		
	</head>
	<body>
	<?php
		session_start();
		function Delete_tables(){
			$conn = mysqli_connect($_SESSION['hostname'], $_SESSION['dbusername'], $_SESSION['dbpassword'], $_SESSION['dbname']);
			
			$result = mysqli_query($conn, "show tables"); 
			while($table = mysqli_fetch_array($result, MYSQLI_BOTH)) { 
				//echo "<tr><td>".$table[0] . "</td></tr>";				
				$drop_tab = mysqli_query($conn , 'DROP TABLE IF EXISTS `'.$_SESSION['dbname'].'`.`'.$table[0].'`');
			}
			if($drop_tab) {
					//return "<p class='drop-noti'>Tables droped successfully</p><script>jQuery(document).ready(function(){jQuery('.drop-noti').fadeOut(5000);window.location = location.href; });</script>";
					return "<p class='drop-noti'>Tables droped successfully</p><script>jQuery(document).ready(function(){setTimeout(function(){jQuery('.drop-noti').fadeOut(function() {window.location = window.location.href; });}, 5000);});</script>";
						
				}
				else {
					return mysqli_error($conn);	
				}
		}
		if(isset($_POST['action']) && !empty($_POST['action'])) {
			$action = $_POST['action'];
			switch($action) {
				case 'drop' : $result = Delete_tables();break;
				case 'test' : test();break;
			}
			echo $result; die;
		}		
		?>
		
	<div class="container">
    <br>
      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="javascript:void(0);">Install WHMX</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
           <li><a href="javascript:void(0);" id="hide">Install</a></li>
           <li><a href="javascript:void(0);" id="show">Requirements</a></li>
           </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
	<div style="display:none" id="req">
			  <?php
// Check PHP version
if (! version_compare(phpversion(), '5.5.0', '>='))
{
	$error = true;
	$requirements[] = array(
		'success' => false,
		'msg' => 'PHP ' . phpversion() . ' - Version 5.5.0 or higher is required'
	);
}
else
{
	$requirements[] = array(
		'success' => true,
		'msg' => 'PHP ' . phpversion() . ''
	);
}

// Check mod rewrite
$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) == 'cgi')
{
	if (! strpos(shell_exec('/usr/local/apache/bin/apachectl -l'), 'mod_rewrite') !== false)
	{
		$error = true;
		$requirements[] = array(
			'success' => false,
			'msg' => 'Mod Rewrite isn\'t enabled'
		);
	}
	else
	{
		$requirements[] = array(
			'success' => true,
			'msg' => 'Mod Rewrite is enabled'
		);
	}
}
else
{
	if (! in_array('mod_rewrite', apache_get_modules()))
	{
		$error = true;
		$requirements[] = array(
			'success' => false,
			'msg' => 'Mod Rewrite isn\'t enabled'
		);
	}
	else
	{
		$requirements[] = array(
			'success' => true,
			'msg' => 'Mod Rewrite is enabled'
		);
	}
}

// Check if CURL is enabled and has the correct version
if (! function_exists('curl_version'))
{
	$error = true;
	$requirements[] = array(
		'success' => false,
		'msg' => 'cURL is not enabled'
	);
}
else
{
	// Check CURL version
	$curl_version = curl_version();
	if (! version_compare($curl_version['version'], '7.19.4', '>='))
	{
		$error = true;
		$requirements[] = array(
			'success' => false,
			'msg' => 'cURL ' . $curl_version['version'] . ' - Version 7.19.4 or higher is required'
		);
	}
	else
	{
		$requirements[] = array(
			'success' => true,
			'msg' => 'cURL ' . $curl_version['version'] . ''
		);
	}
}

// Check if SQLite is installed
if (! extension_loaded('sqlite3'))
{
	$error = true;
	$requirements[] = array(
		'success' => false,
		'msg' => 'SQLite3 is not installed'
	);
}
else
{
	$requirements[] = array(
		'success' => true,
		'msg' => 'SQLite3 is installed'
	);
}

// Check if PDO SQLite is installed
if (! extension_loaded('pdo_sqlite'))
{
	$error = true;
	$requirements[] = array(
		'success' => false,
		'msg' => 'SQLite PDO drivers not installed'
	);
}
else
{
	$requirements[] = array(
		'success' => true,
		'msg' => 'SQLite PDO drivers installed'
	);
}

// Check if mcrypt is installed
if (! extension_loaded('mcrypt'))
{
	$error = true;
	$requirements[] = array(
		'success' => false,
		'msg' => 'Mcrypt extension is missing'
	);
}
else
{
	$requirements[] = array(
		'success' => true,
		'msg' => 'Mcrypt extension is installed'
	);
}

// Check if fileinfo is installed
if (! extension_loaded('fileinfo') || ! function_exists('mime_content_type'))
{
	$error = true;
	$requirements[] = array(
		'success' => false,
		'msg' => 'Fileinfo extension is missing'
	);
}
else
{
	$requirements[] = array(
		'success' => true,
		'msg' => 'Fileinfo extension is installed'
	);
}

foreach($requirements as $requirement)
{
	if ($requirement['success'])
	{
		echo '<div class="alert alert-success"><i class="fa fa-check text-success"></i> ' . $requirement['msg'] . '</div>';
	}
	else
	{
		echo '<div class="alert alert-danger"><i class="fa fa-times text-danger"></i> ' . $requirement['msg'] . '</div>';
	}
}

?>
	</div>
	
      <div class="jumbotron">
		
       <form name="db_install" id="db_install" method="post">
			<?php  //session_start();?>
			
			<div class="form-group">
            <label for="Input">Database Hostname:</label>
            <input type="text" class="form-control" name="db_host" id="db_host" value="<?php if(isset($_SESSION['hostname'])) {echo $_SESSION['hostname'];}?>" placeholder="localhost or MySQL server host name" />
			</div>
			
			<div class="form-group">
            <label for="Input">Database Name:</label>
            <input type="text" name="db_name" class="form-control" id="db_name" value="<?php if(isset($_SESSION['dbname'])){ echo $_SESSION['dbname'];}?>" placeholder="MySQL Database to install tables" />
			</div>
			
			<div class="form-group">
            <label for="Input">Database Username:</label>
            <input type="text" name="db_username" class="form-control" id="db_username" value="<?php if(isset($_SESSION['dbusername'])){ echo $_SESSION['dbusername'];}?>" placeholder="MySQL Username" />
			</div>
			
			<div class="form-group">
            <label for="Input">Database Password:</label>
            <input type="text" name="db_password" class="form-control" id="db_password" value="<?php if(isset($_SESSION['dbpassword'])){ echo $_SESSION['dbpassword'];}?>" placeholder="MySQL Password" />
		    </div>
		    
		    <input onclick="return check_db(this, event)" type="submit"  id="db_submit" class="btn btn-primary btn-lg" value="Install" />
			<input  type="hidden" name="db_submit" class="db_submit" value="Install" />
			
			<div class="overlay-message" style="display:none;">
			<span style="display: inline-block; position: relative; bottom: 8px; left: 5px;">Please wait database installation is being processing.</span> 
							
				</div>
			
		</form>
       
         <table style="margin: 0px auto; width: 50%;color:#ff0000">	
			<tr id="show_drop_action"><td> </td></tr>
			<?php			
			$filename = 'mysql.sql';
			
			if(isset($_POST['db_submit'])){
				$dbhost = check_data($_POST["db_host"]);
				$dbname = check_data($_POST["db_name"]);
				$dbusername = check_data($_POST["db_username"]);
				$dbpassword = check_data($_POST["db_password"]);
				
				$varhost = "host";	
				$varuser = "usuario";
				$varsenha = "senha";
				$varbd = "banco";
				$varvar = "bd";

$dados = "<?php

#################################################################
##  Database Installer          							   ##
##-------------------------------------------------------------##
##  Version: 1.00                                              ##
##-------------------------------------------------------------##
##  Author: Gianck Luiz obviosistemas@gmail.com         	   ##
##-------------------------------------------------------------##
##  Copyright ©2015 . All rights reserved.	                   ##
##-------------------------------------------------------------##
#################################################################

$$varhost = '$dbhost';
$$varuser = '$dbusername';
$$varsenha = '$dbpassword';
$$varbd = '$dbname';

$$varvar = mysql_connect($$varhost,$$varuser,$$varsenha);
mysql_select_db($$varbd) or die(mysql_error());

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
			
define('ENVIRONMENT', 'production');

/*
 *---------------------------------------------------------------
 * SET TIMEZONE
 *---------------------------------------------------------------
 *
 * Timezone Country And Charset
 */

ini_set('allow_url_fopen', 1);
date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: text/html; charset=ISO-8859-1', true);
									
?>";
				$arquivo = fopen("adm/config/conexao.php", "w");
				fwrite($arquivo, $dados);
				fclose($arquivo);
								// END
				
				if($dbhost == ''){
					echo "<div class='alert alert-danger'>"."Please Enter Hostname"."</div>" ;
				}
				elseif($dbname == ''){
					echo "<div class='alert alert-danger'>"."Please Enter Database Name"."</div>" ;
				}
				elseif($dbusername == ''){
					echo "<div class='alert alert-danger'>"."Please Enter Database Username"."</div>" ;
				}
				else {					
					// Connect to MySQL server
					$connect =  mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
					if(!$connect){
					    echo '<div class="alert alert-danger">'.'Error connecting to MySQL server: ' . mysqli_connect_error.'</div>';
					}
					
					// Select database
					$result = mysqli_query($connect, "SHOW TABLE STATUS");
					$dbsize = 0;
					while($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
						$dbsize += $row["Data_length"] + $row["Index_length"];
					}
					
					if($dbsize != '' || $dbsize != null){
						//save data in session
						//session_start();
						if(!isset($_SESSION['dbname'])) {
							$_SESSION['hostname']   = $dbhost;
							$_SESSION['dbname']     = $dbname;
							$_SESSION['dbusername'] = $dbusername;
							$_SESSION['dbpassword'] = $dbpassword;
						}
						
						echo "<div class='alert alert-danger'>"."Database is not empty. Drop the tables to reinstall"."</div>";
						echo "<div class='alert alert-warning'><input type='checkbox' name='drop_chk' id='chk-box' value='0' /><span style='color:#000;'> Delete the existing tables</span> <input type='button' onclick='drop_db()' class='btn btn-danger' value='Delete' /> <span style='display:none;' class='img-loader'>Please Wait...</span> <span id='chk-label' style='display:none;'>Please Check the checkbox</span></div>";
					}
					else {
						if($connect) {
							
							// Temporary variable, used to store current query
							$templine = '';
							
							// Read in entire file
							$lines = file($filename);
							?>
							<script>
								jQuery('.overlay-message').show();
							</script>
							<?php
							// Loop through each line
							foreach ($lines as $line)
							{
								// Skip it if it's a comment
								if (substr($line, 0, 2) == '--' || substr($line, 0, 2) == '/*' || $line == '')
									continue;

								// Add this line to the current segment
								$templine .= $line;
								
								// If it has a semicolon at the end, it's the end of the query
								if (substr(trim($line), -1, 1) == ';')
								{
									// Perform the query
									mysqli_query($connect, $templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error($connect) . '<br /><br />');
									
									// Reset temp variable to empty
									$templine = '';
								}
							}
							if(!mysqli_error($connect)){
								
								echo '<div class="alert alert-success">Tables imported successfully</div>';
							}
							else{
								echo '<div class="alert alert-danger">Tables is not imported</div>';
							}
							session_destroy();
							?>
							<script>
								jQuery('.overlay-message').hide();
							</script>
							<?php
						}
						else{
							echo '<div class="alert alert-warning">'.'Database not correct: '. mysqli_error($connect).'</div>';
						}
					  }					
				}
			
			}
			if(isset($_GET['del'])) {
				//Delete_tables();
			  }
			
			function check_data($data) {
			   $data = trim($data);
			   $data = stripslashes($data);
			   $data = htmlspecialchars($data);
			   return $data;
			}			
			
		?>
		</table>
       
       
      </div>

    </div> <!-- /container -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    $("#hide").click(function(){
        $("#req").hide();
    });
    $("#show").click(function(){
        $("#req").show();
    });
    });
    </script>
	</body>
</html>
