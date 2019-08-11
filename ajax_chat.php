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
	
	$idc = $_SESSION['id'];
	
	if($_GET['cc'] == 'y') {
	
	$cm = mysql_query("SELECT * FROM chat WHERE customer = '$idc' AND user > '0' AND tp = '2'");
    echo $qtd = mysql_num_rows($cm);
	
	} else {
	
	$query = mysql_query("SELECT * FROM chat WHERE customer = '$idc' order by id DESC");
 	while($result = mysql_fetch_array($query)){ 
	$idcp = $result['customer']; 	
	$acct = mysql_query("SELECT * FROM customers WHERE id = '$idcp'");
 	$customer = mysql_fetch_array($acct);
	$idcr = $result['user']; 
	$ussrs = mysql_query("SELECT * FROM users WHERE id = '$idcr'");
 	$user = mysql_fetch_array($ussrs);
 	
    if($result['tp'] == '1') { ?>
                       <li class="right clearfix"><span class="chat-img pull-right">
                           <img src="<?php echo gravatar($customer['email']); ?>" style="width:50px;height:50px;" alt="<?php echo $customer['name']; ?>" class="img-circle" />
							</span>
                            <div class="chat-body clearfix">
                                <div class="header"> <small class=" text-muted"><span class="glyphicon glyphicon-time"></span><?php $time = strtotime($result['date']); echo humanTiming($time); ?> ago</small>
									<strong class="pull-right primary-font"><?php echo $customer['name']; ?> - <?php echo $customer['email']; ?></strong>
                                </div>
                                <p><?php echo $result['msg']; ?></p>
                            </div>
                        </li>
                        <?php } ?>
                        <?php if($result['tp'] == '2') { ?>
                        <li class="left clearfix warning"><span class="chat-img pull-left">
                           <img src="<?php echo gravatar($user['email']); ?>" style="width:50px;height:50px;" alt="<?php echo $user['name_user']; ?>" class="img-circle" />
							</span>

                            <div class="chat-body clearfix warning">
                                <div class="header"> <strong class="primary-font"><?php echo $user['name_user']; ?></strong>  <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span><?php $time = strtotime($result['date']); echo humanTiming($time); ?> ago</small>

                                </div>
                                <p><?php echo $result['msg']; ?></p>
                            </div>
                        </li>
                        
						<?php } ?>
			 <?php } ?>
 <?php } ?>
