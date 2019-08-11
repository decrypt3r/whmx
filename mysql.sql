-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Máquina: localhost:3306
-- Data de Criação: 13-Fev-2016 às 19:25
-- Versão do servidor: 5.5.45-cll-lve
-- versão do PHP: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `erpmkcom_p`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` int(11) DEFAULT NULL,
  `request` int(11) DEFAULT NULL,
  `server` int(11) DEFAULT NULL,
  `package` varchar(160) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `acctuser` varchar(255) DEFAULT NULL,
  `acctpass` varchar(255) DEFAULT NULL,
  `cpmod` varchar(255) DEFAULT 'x3',
  `ip` varchar(255) DEFAULT NULL,
  `cgi` enum('y','n') DEFAULT NULL,
  `frontpage` enum('y','n') DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('S','N') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cancellations`
--

CREATE TABLE IF NOT EXISTS `cancellations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` int(11) DEFAULT NULL,
  `request` int(11) DEFAULT NULL,
  `request_tb` int(11) DEFAULT NULL,
  `msg` longtext,
  `date` varchar(36) DEFAULT NULL,
  `type` enum('1','2') DEFAULT NULL,
  `status` enum('S','N') DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `dad` int(11) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(36) DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  `ip` varchar(90) DEFAULT NULL,
  `os` varchar(160) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `msg` text,
  `sessao` varchar(60) DEFAULT NULL,
  `tp` enum('1','2') DEFAULT NULL,
  `status` enum('S','N') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cms`
--

CREATE TABLE IF NOT EXISTS `cms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `menu` varchar(36) DEFAULT NULL,
  `status` enum('S','N') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `base` varchar(255) DEFAULT NULL,
  `thema` varchar(60) DEFAULT 'default',
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `email` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_email` varchar(255) DEFAULT NULL,
  `smtp_user` varchar(255) DEFAULT NULL,
  `smtp_pass` varchar(90) DEFAULT NULL,
  `smtp_port` varchar(11) DEFAULT NULL,
  `smtp_type` enum('N','SSL','TLS') DEFAULT NULL,
  `signature` longtext,
  `paypal` varchar(1) DEFAULT 'N',
  `paypal_user` varchar(255) DEFAULT NULL,
  `paypal_pwd` varchar(255) DEFAULT NULL,
  `paypal_signature` varchar(255) DEFAULT NULL,
  `pagseguro` varchar(1) DEFAULT 'N',
  `pagseguro_email` varchar(255) DEFAULT NULL,
  `pagseguro_token` varchar(255) DEFAULT NULL,
  `bcash` varchar(1) DEFAULT 'N',
  `bcash_email` varchar(255) DEFAULT NULL,
  `bcash_token` varchar(255) DEFAULT NULL,
  `mercadopago` enum('S','N') DEFAULT NULL,
  `mp_moeda` varchar(9) DEFAULT 'ARS',
  `mp_id` varchar(255) DEFAULT NULL,
  `mp_token` varchar(255) DEFAULT NULL,
  `skrill` enum('S','N') DEFAULT NULL,
  `skrill_email` varchar(255) DEFAULT NULL,
  `skrill_moeda` varchar(255) DEFAULT 'USD',
  `skrill_lang` varchar(255) DEFAULT 'EN',
  `moip` enum('S','N') NULL DEFAULT 'N',
  `moip_email` varchar(255) DEFAULT NULL,
  `moip_token` varchar(255) DEFAULT NULL,
  `dias_faturas` varchar(2) DEFAULT '5',
  `dias_dominio` varchar(2) DEFAULT '30',
  `dias_lembrete` varchar(2) DEFAULT '3',
  `dias_suspender` varchar(2) DEFAULT '3',
  `dias_tickets` varchar(2) DEFAULT '3',
  `dias_vencimento1` varchar(2) DEFAULT '1',
  `dias_vencimento2` varchar(2) DEFAULT '2',
  `dias_vencimento3` varchar(2) DEFAULT '3',
  `moeda` enum('USD','BRL') DEFAULT NULL,
  `above` varchar(160) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `config`
--

INSERT INTO `config` (`id`, `base`, `thema`, `title`, `description`, `keywords`, `email`, `company`, `logo`, `smtp_host`, `smtp_email`, `smtp_user`, `smtp_pass`, `smtp_port`, `smtp_type`, `signature`, `paypal`, `paypal_user`, `paypal_pwd`, `paypal_signature`, `pagseguro`, `pagseguro_email`, `pagseguro_token`, `bcash`, `bcash_email`, `bcash_token`, `mercadopago`, `mp_moeda`, `mp_id`, `mp_token`, `skrill`, `skrill_email`, `skrill_moeda`, `skrill_lang`, `moip`, `moip_email`, `moip_token`, `dias_faturas`, `dias_dominio`, `dias_lembrete`, `dias_suspender`, `dias_tickets`, `dias_vencimento1`, `dias_vencimento2`, `dias_vencimento3`, `moeda`, `above`) VALUES
(1, 'http://localhost/whmx', 'default', 'WHMX Billing', NULL, NULL, 'obviosistemas@gmail.com', 'Your Company', '', 'mail.erpmk.com.br', 'dev@erpmk.com.br', 'dev@erpmk.com.br', 'erp2000MK', '587', 'TLS', '', 'S', 'gianckluiz@hotmail.com', 'Test', 'Test', 'N', 'Test', 'Test', NULL, NULL, NULL, 'S', 'BRL', '4885243831100035', 'Rya8YedtGpr4RdEUieNX57jfNK198790', 'S', 'test@xm.cm', 'USD', 'EN', 'N', '', '', '3', '30', '2', '6', '5', '1', '2', '3', 'USD', '3zOKFH1n2tf8ysryaY9GAKZkl3OQeWPy');

-- --------------------------------------------------------

--
-- Estrutura da tabela `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(160) DEFAULT NULL,
  `company` varchar(160) DEFAULT NULL,
  `cpf_cnpj` varchar(60) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pass` varchar(160) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `phrase` varchar(160) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `number` varchar(11) DEFAULT NULL,
  `neighborhood` varchar(160) DEFAULT NULL,
  `city` varchar(160) DEFAULT NULL,
  `state` varchar(160) DEFAULT NULL,
  `zipcode` varchar(60) DEFAULT NULL,
  `country` varchar(60) DEFAULT NULL,
  `obs` text,
  `ip` varchar(90) DEFAULT NULL,
  `os` varchar(160) DEFAULT NULL,
  `status` enum('S','B','N') DEFAULT 'S',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `departments`
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_department` varchar(255) DEFAULT NULL,
  `email_department` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `domains`
--

CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` varchar(11) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `tdl` varchar(90) DEFAULT NULL,
  `dns1` varchar(255) DEFAULT NULL,
  `dns2` varchar(255) DEFAULT NULL,
  `date_register` date DEFAULT NULL,
  `date_expire` date DEFAULT NULL,
  `amount` varchar(11) DEFAULT NULL,
  `amount_recurrent` varchar(11) DEFAULT NULL,
  `period` varchar(11) DEFAULT NULL,
  `code` varchar(90) DEFAULT NULL,
  `epp` varchar(255) DEFAULT NULL,
  `autorenew` varchar(90) DEFAULT NULL,
  `obs` text,
  `status` enum('R','C','A','B','S','N') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `email_template`
--

CREATE TABLE IF NOT EXISTS `email_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `msg` longtext,
  `from_mail` varchar(255) NOT NULL,
  `name_from` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Extraindo dados da tabela `email_template`
--

INSERT INTO `email_template` (`id`, `name`, `subject`, `msg`, `from_mail`, `name_from`) VALUES
(1, 'Welcome Customers', 'Welcome Customers', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS --><title></title><style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}</style><!-- 100% background wrapper (grey background) --><table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">	<tbody>		<tr>			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />			<!-- 600px container (white background) -->			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">				<tbody>					<tr>						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Welcome Customer</span></font></td>					</tr>					<tr>						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />						&nbsp;</div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Welcome to our site now have access to industry-leading hosting services.<br />						<br />						<strong>Login</strong>: %EMAIL%<br />						<strong>Password</strong>:&nbsp;%SENHA% </span></font><br />						<br />						Access your restricted area for more information.<br />						&nbsp;</div>						</td>					</tr>					<tr>						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />						<br />						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />						<br />						You are receiving this email because you opted in on our website.<br />						<br />						%ASSINATURA%</span></font><br />						<br />						&nbsp;</td>					</tr>				</tbody>			</table>			<!--/600px container --></td>		</tr>	</tbody></table><!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(2, 'Ticket Replied\r\n', 'Ticket Replied', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Ticket New Message</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">A new message was left on your Ticket.</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><br />\r\n						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>Ticket</strong>: %TICKET%<br />\r\n						<strong>Subject</strong>: %ASSUNTO%<br />\r\n						<strong>Login</strong>: %EMAIL%<br />\r\n						<strong>Password</strong>:&nbsp;%SENHA%<br />\r\n						<br />\r\n						Access your restricted area for more information.<br />\r\n						---------------------------------------------------------------------------------------------------------------------<br />\r\n						<strong>Message Ticket:</strong><br />\r\n						%MENSAGEM%</span></font><br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(3, 'Ticket Closed', 'Ticket Closed', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Ticket Closed</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">Please be advised that your ticket has just been closed in our system.</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><br />\r\n						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>Login</strong>: %EMAIL%<br />\r\n						<strong>Password</strong>:&nbsp;%SENHA% </span></font><br />\r\n						<br />\r\n						Access your restricted area for more information.</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(4, 'Ticket Open', 'Ticket Open', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Ticket Open</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Please be advised that your ticket has just been opended in our system.</span></font></div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><br />\r\n						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>Ticket</strong>: %TICKET%<br />\r\n						<strong>Subject</strong>: %ASSUNTO%<br />\r\n						<strong>Login</strong>: %EMAIL%<br />\r\n						<strong>Password</strong>:&nbsp;%SENHA%<br />\r\n						<br />\r\n						Access your restricted area for more information.</span></font><br />\r\n						---------------------------------------------------------------------------------------------------------------------<br />\r\n						<strong>Message Ticket:</strong><br />\r\n						%MENSAGEM%<br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(5, 'Welcome', 'Welcome Hosting', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS --><title></title><style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}</style><!-- 100% background wrapper (grey background) --><table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">	<tbody>		<tr>			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />			<!-- 600px container (white background) -->			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">				<tbody>					<tr>						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Welcome to your new hosting account</span></font></td>					</tr>					<tr>						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">&nbsp;						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Hello %NOME%</span></font></div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">						<pre class="tw-data-text vk_txt tw-ta tw-text-small" data-fulltext="" data-placeholder="TraduÃ§Ã£o" dir="ltr" id="tw-target-text" style="unicode-bidi: -webkit-isolate; font-family: inherit; border: none; padding: 0px 0.14em 0px 0px; position: relative; margin-top: 0px; margin-bottom: 0px; resize: none; overflow: hidden; width: 237.5px; color: rgb(33, 33, 33); height: 72px; font-size: 16px !important; line-height: 24px !important;"><span lang="en" style="font-size: 13px; font-style: normal; line-height: 20.8px; white-space: normal;"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS">We are at the same time happy and honored to offer our services to you!</font></span></pre>						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>Customer Information</strong></span></font></div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>Name</strong>: %NOME%<br />						<strong>E-mai</strong>: %EMAIL%<br />						<strong>Address</strong>: %ENDERECO%<br />						<strong>City</strong>: %CIDADE%<br />						<strong>TAXID</strong>: %CPFCNPJ%<br />						<br />						THIS DOCUMENT HAS PASSWORD AND IMPORTANT DATA FOR PERFECT YOUR AREA MANAGEMENT IS RECOMMENDED THAT THE DOCUMENT IS SAVED FOR USE WHERE NECESSARY!</span></font></div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><br />						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>NAMESERVER</strong> 1: %DNS1%<br />						<strong>NAMESERVER</strong> 2: %DNS2%<br />						<strong>DATA FOR SETTING E- MAIL:</strong><br />						* Server POP3: mail.%DOMINIO%&nbsp;<br />						* Server SMTP: mail.%DOMINIO% Port&nbsp;SMTP 587&nbsp;<br />						* Report my server requeires authentication</span></font><br />						&nbsp;</div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">* <strong>URL: cPanel </strong><br />						%DOMINIO%:2082&nbsp;( after being transferred / spread DNS )<br />						* Provisional LINK: %</span></font>URLSERVER%&nbsp;<br />						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">* <strong>USER</strong> : %CP_LOGIN%<br />						* <strong>PASSWORD</strong> : %CP_SENHA%</span></font></div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><br />						<strong>CDN</strong> :</div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">						<p>* Direct Activation from cPanel in CloudFlare<br />						* Superior Performance to sites and applications<br />						* 100 % automatic and transparent<br />						* Global coverage<br />						* No additional charge</p>						</div>						</td>					</tr>					<tr>						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />						<br />						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />						<br />						You are receiving this email because you opted in on our website.<br />						<br />						%ASSINATURA%</span></font><br />						<br />						&nbsp;</td>					</tr>				</tbody>			</table>			<!--/600px container --></td>		</tr>	</tbody></table><!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(6, 'Additional Product or Service', 'Additional Product or Service', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {\r\n  margin: 0;\r\n  padding: 0;\r\n  -ms-text-size-adjust: 100%;\r\n  -webkit-text-size-adjust: 100%;\r\n}\r\n\r\ntable {\r\n  border-spacing: 0;\r\n}\r\n\r\ntable td {\r\n  border-collapse: collapse;\r\n}\r\n\r\n.ExternalClass {\r\n  width: 100%;\r\n}\r\n\r\n.ExternalClass,\r\n.ExternalClass p,\r\n.ExternalClass span,\r\n.ExternalClass font,\r\n.ExternalClass td,\r\n.ExternalClass div {\r\n  line-height: 100%;\r\n}\r\n\r\n.ReadMsgBody {\r\n  width: 100%;\r\n  background-color: #ebebeb;\r\n}\r\n\r\ntable {\r\n  mso-table-lspace: 0pt;\r\n  mso-table-rspace: 0pt;\r\n}\r\n\r\nimg {\r\n  -ms-interpolation-mode: bicubic;\r\n}\r\n\r\n.yshortcuts a {\r\n  border-bottom: none !important;\r\n}\r\n\r\n@media screen and (max-width: 599px) {\r\n  .force-row,\r\n  .container {\r\n    width: 100% !important;\r\n    max-width: 100% !important;\r\n  }\r\n}\r\n@media screen and (max-width: 400px) {\r\n  .container-padding {\r\n    padding-left: 12px !important;\r\n    padding-right: 12px !important;\r\n  }\r\n}\r\n.ios-footer a {\r\n  color: #aaaaaa !important;\r\n  text-decoration: underline;\r\n}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Registration / Billing Additional Services</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME% this is an email from your invoice created in our system</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Please be advised that your domain is subject to blockage, if payment is not made on the due date.</span></font><br />\r\n						<br />\r\n						<strong>The additional service information.</strong><br />\r\n						<br />\r\n						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Payment: %FORMA_PAGTO%<br />\r\n						Total $ %TOTAL% USD<br />\r\n						Date: %DATABR%</span></font><br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(7, 'New Subscription', 'New Subscription', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Service Registration</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">To finish the subscription process, please check the information below<br />\r\n						<br />\r\n						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Domain: %DOMINIO%</span></font><br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(8, 'Bill Delayed', 'Bill Delayed', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Invoice Open</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Please be advised that have not reported in our system for confirmation of payment for maturing invoice in %DATABR%<br />\r\n						<br />\r\n						Payment: %FORMA_PAGTO%<br />\r\n						Total: $ %TOTAL% USD</span></font><br />\r\n						<br />\r\n						Please be advised that the services provided by us are subject to locking at any time from the sending of this message<br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(9, 'Account Block', 'Account Block', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {\r\n  margin: 0;\r\n  padding: 0;\r\n  -ms-text-size-adjust: 100%;\r\n  -webkit-text-size-adjust: 100%;\r\n}\r\n\r\ntable {\r\n  border-spacing: 0;\r\n}\r\n\r\ntable td {\r\n  border-collapse: collapse;\r\n}\r\n\r\n.ExternalClass {\r\n  width: 100%;\r\n}\r\n\r\n.ExternalClass,\r\n.ExternalClass p,\r\n.ExternalClass span,\r\n.ExternalClass font,\r\n.ExternalClass td,\r\n.ExternalClass div {\r\n  line-height: 100%;\r\n}\r\n\r\n.ReadMsgBody {\r\n  width: 100%;\r\n  background-color: #ebebeb;\r\n}\r\n\r\ntable {\r\n  mso-table-lspace: 0pt;\r\n  mso-table-rspace: 0pt;\r\n}\r\n\r\nimg {\r\n  -ms-interpolation-mode: bicubic;\r\n}\r\n\r\n.yshortcuts a {\r\n  border-bottom: none !important;\r\n}\r\n\r\n@media screen and (max-width: 599px) {\r\n  .force-row,\r\n  .container {\r\n    width: 100% !important;\r\n    max-width: 100% !important;\r\n  }\r\n}\r\n@media screen and (max-width: 400px) {\r\n  .container-padding {\r\n    padding-left: 12px !important;\r\n    padding-right: 12px !important;\r\n  }\r\n}\r\n.ios-footer a {\r\n  color: #aaaaaa !important;\r\n  text-decoration: underline;\r\n}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Welcome to your new hosting account</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">&nbsp;\r\n						<p class="title" style="font-family: Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 600; color: rgb(55, 69, 80);"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						<br />\r\n						<var><samp>Please be advised that the services provided by us are locked from the date of sending this message for late payment reasons.<br />\r\n						<br />\r\n						To regularize their situation with us, please contact our financial department.</samp></var></p>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(10, 'Unlocked Account', 'Unlocked Account', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="padding-bottom: 12px; padding-left: 24px; padding-right: 24px;">Unlocking Service&nbsp;</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Please be advised that the services provided by us in unlocked from this message is sent.<br />\r\n						<br />\r\n						Login: %EMAIL%<br />\r\n						Password:&nbsp;%SENHA%<br />\r\n						<br />\r\n						Access your restricted area for more information.</span></font><br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO');
INSERT INTO `email_template` (`id`, `name`, `subject`, `msg`, `from_mail`, `name_from`) VALUES
(11, 'Domain Register', 'Domain Register', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {\r\n  margin: 0;\r\n  padding: 0;\r\n  -ms-text-size-adjust: 100%;\r\n  -webkit-text-size-adjust: 100%;\r\n}\r\n\r\ntable {\r\n  border-spacing: 0;\r\n}\r\n\r\ntable td {\r\n  border-collapse: collapse;\r\n}\r\n\r\n.ExternalClass {\r\n  width: 100%;\r\n}\r\n\r\n.ExternalClass,\r\n.ExternalClass p,\r\n.ExternalClass span,\r\n.ExternalClass font,\r\n.ExternalClass td,\r\n.ExternalClass div {\r\n  line-height: 100%;\r\n}\r\n\r\n.ReadMsgBody {\r\n  width: 100%;\r\n  background-color: #ebebeb;\r\n}\r\n\r\ntable {\r\n  mso-table-lspace: 0pt;\r\n  mso-table-rspace: 0pt;\r\n}\r\n\r\nimg {\r\n  -ms-interpolation-mode: bicubic;\r\n}\r\n\r\n.yshortcuts a {\r\n  border-bottom: none !important;\r\n}\r\n\r\n@media screen and (max-width: 599px) {\r\n  .force-row,\r\n  .container {\r\n    width: 100% !important;\r\n    max-width: 100% !important;\r\n  }\r\n}\r\n@media screen and (max-width: 400px) {\r\n  .container-padding {\r\n    padding-left: 12px !important;\r\n    padding-right: 12px !important;\r\n  }\r\n}\r\n.ios-footer a {\r\n  color: #aaaaaa !important;\r\n  text-decoration: underline;\r\n}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Welcome to your new hosting account</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">&nbsp;\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Hello %NOME%</span></font></div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">\r\n						<pre class="tw-data-text vk_txt tw-ta tw-text-small" data-fulltext="" data-placeholder="TraduÃ§Ã£o" dir="ltr" id="tw-target-text" style="unicode-bidi: -webkit-isolate; font-family: inherit; border: none; padding: 0px 0.14em 0px 0px; position: relative; margin-top: 0px; margin-bottom: 0px; resize: none; overflow: hidden; width: 237.5px; color: rgb(33, 33, 33); height: 72px; font-size: 16px !important; line-height: 24px !important;">\r\n<span lang="en" style="font-size: 13px; font-style: normal; line-height: 20.8px; white-space: normal;"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS">We are at the same time happy and honored to offer our services to you!</font></span></pre>\r\n						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>Customer Information</strong></span></font></div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>Name</strong>: %NOME%<br />\r\n						<strong>E-mai</strong>: %EMAIL%<br />\r\n						<strong>Address</strong>: %ENDERECO%<br />\r\n						<strong>City</strong>: %CIDADE%<br />\r\n						<strong>TAXID</strong>: %CPFCNPJ%<br />\r\n						<br />\r\n						THIS DOCUMENT HAS PASSWORD AND IMPORTANT DATA FOR PERFECT YOUR AREA MANAGEMENT IS RECOMMENDED THAT THE DOCUMENT IS SAVED FOR USE WHERE NECESSARY!</span></font></div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><br />\r\n						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>NAMESERVER</strong> 1: %DNS1%<br />\r\n						<strong>NAMESERVER</strong> 2: %DNS2%<br />\r\n						<strong>DATA FOR SETTING E- MAIL:</strong><br />\r\n						* Server POP3: mail.%DOMINIO%&nbsp;<br />\r\n						* Server SMTP: mail.%DOMINIO% Port&nbsp;SMTP 587&nbsp;<br />\r\n						* Report my server requeires authentication</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><br />\r\n						<strong>CDN</strong> :</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">\r\n						<p>* Direct Activation from cPanel in CloudFlare<br />\r\n						* Superior Performance to sites and applications<br />\r\n						* 100 % automatic and transparent<br />\r\n						* Global coverage<br />\r\n						* No additional charge</p>\r\n						</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(12, 'Payment Confirmation', 'Payment Confirmation', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Payment Confirmation</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Please be advised that payment for your invoice with the data below has just been confirmed by our financial department.<br />\r\n						<br />\r\n						Order: %</span></font>PEDIDO%<br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(13, 'Invoice', 'Invoice', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px">New Invoice</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><span style="color: rgb(33, 33, 33); font-family: arial, sans-serif; font-size: 16px; line-height: 24px; white-space: pre-wrap;">Hello <strong>%NOME%</strong> this is an email from your invoice created in our system</span><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><span style="color: rgb(33, 33, 33); font-family: arial, sans-serif; font-size: 16px; line-height: 24px; white-space: pre-wrap;">Please be advised that your domain is subject to blockage, if payment is not made on the due date.<br />\r\n						<br />\r\n						Payment: <strong>%FORMA_PAGTO%</strong><br />\r\n						Total $ <strong>%TOTAL%</strong> USD<br />\r\n						Date: %DATABR%</span><br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%<br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(14, 'New Register Domain', 'New Register Domain', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS -->\r\n<title></title>\r\n<style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}\r\n</style>\r\n<!-- 100% background wrapper (grey background) -->\r\n<table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">\r\n	<tbody>\r\n		<tr>\r\n			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />\r\n			<!-- 600px container (white background) -->\r\n			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">\r\n				<tbody>\r\n					<tr>\r\n						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Domain Registration</span></font></td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">\r\n						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />\r\n						&nbsp;</div>\r\n\r\n						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">To finish the subscription process, please check the information below<br />\r\n						<br />\r\n						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Domain: %DOMINIO%</span></font><br />\r\n						&nbsp;</div>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />\r\n						<br />\r\n						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />\r\n						<br />\r\n						You are receiving this email because you opted in on our website.<br />\r\n						<br />\r\n						%ASSINATURA%</span></font><br />\r\n						<br />\r\n						&nbsp;</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			<!--/600px container --></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n<!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(15, 'New Password', 'New Password', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS --><title></title><style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}</style><!-- 100% background wrapper (grey background) --><table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">	<tbody>		<tr>			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />			<!-- 600px container (white background) -->			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">				<tbody>					<tr>						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Password Reset</span></font></td>					</tr>					<tr>						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />						&nbsp;</div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">Your new password generated by the system is:<br />						<br />						<font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;"><strong>Login</strong>: %EMAIL%<br />						<strong>Password</strong>:&nbsp;%SENHA% </span></font><br />						<br />						Request date password: %DATABR%</div>						</td>					</tr>					<tr>						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />						<br />						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />						<br />						You are receiving this email because you opted in on our website.<br />						<br />						%ASSINATURA%</span></font><br />						<br />						&nbsp;</td>					</tr>				</tbody>			</table>			<!--/600px container --></td>		</tr>	</tbody></table><!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO'),
(16, 'New Customer Register', 'New Customer Register', '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><!-- So that mobile will display zoomed in --><meta http-equiv="X-UA-Compatible" content="IE=edge"><!-- enable media queries for windows phone 8 --><meta name="format-detection" content="telephone=no"><!-- disable auto telephone linking in iOS --><title></title><style type="text/css">body {  margin: 0;  padding: 0;  -ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%;}table {  border-spacing: 0;}table td {  border-collapse: collapse;}.ExternalClass {  width: 100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {  line-height: 100%;}.ReadMsgBody {  width: 100%;  background-color: #ebebeb;}table {  mso-table-lspace: 0pt;  mso-table-rspace: 0pt;}img {  -ms-interpolation-mode: bicubic;}.yshortcuts a {  border-bottom: none !important;}@media screen and (max-width: 599px) {  .force-row,  .container {    width: 100% !important;    max-width: 100% !important;  }}@media screen and (max-width: 400px) {  .container-padding {    padding-left: 12px !important;    padding-right: 12px !important;  }}.ios-footer a {  color: #aaaaaa !important;  text-decoration: underline;}</style><!-- 100% background wrapper (grey background) --><table bgcolor="#F0F0F0" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">	<tbody>		<tr>			<td align="center" bgcolor="#F0F0F0" style="background-color: #F0F0F0;" valign="top"><br />			<!-- 600px container (white background) -->			<table border="0" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px" width="600">				<tbody>					<tr>						<td align="left" class="container-padding header" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px"><font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; font-weight: normal; line-height: 20.8px;">Welcome Customer</span></font></td>					</tr>					<tr>						<td align="left" class="container-padding content" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">						<div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">&nbsp;</div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Hello %NOME%&nbsp;</span></font><br />						&nbsp;</div>						<div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333"><font face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Welcome to our site now have access to industry-leading hosting services.<br />						<br />						<strong>Login</strong>: %EMAIL%<br />						<strong>Password</strong>:&nbsp;%SENHA% </span></font><br />						<br />						Access your restricted area for more information.<br />						&nbsp;</div>						</td>					</tr>					<tr>						<td align="left" class="container-padding footer-text" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px"><br />						<br />						<font color="#333333" face="sans-serif, Arial, Verdana, Trebuchet MS"><span style="font-size: 13px; line-height: 20.8px;">Sample Footer: &copy; 2015 WHMX.<br />						<br />						You are receiving this email because you opted in on our website.<br />						<br />						%ASSINATURA%</span></font><br />						<br />						&nbsp;</td>					</tr>				</tbody>			</table>			<!--/600px container --></td>		</tr>	</tbody></table><!--/100% background wrapper-->', 'demo@azead.com.br', 'WHMX DEMO');

-- --------------------------------------------------------

--
-- Estrutura da tabela `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice` varchar(16) DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  `request` int(11) DEFAULT NULL,
  `amount` varchar(11) DEFAULT NULL,
  `total` varchar(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment` varchar(90) DEFAULT NULL,
  `dia` varchar(2) DEFAULT NULL,
  `mes` varchar(2) DEFAULT NULL,
  `ano` varchar(4) DEFAULT NULL,
  `tp` enum('1','2') DEFAULT '1',
  `status` enum('billed','payable','confirmed','open','close','canceled') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ipn`
--

CREATE TABLE IF NOT EXISTS `ipn` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `txn_id` int(10) unsigned DEFAULT NULL,
  `txn_type` varchar(55) DEFAULT NULL,
  `receiver_email` varchar(127) NOT NULL,
  `payment_status` varchar(17) DEFAULT NULL,
  `pending_reason` varchar(17) DEFAULT NULL,
  `reason_code` varchar(31) DEFAULT NULL,
  `custom` varchar(45) DEFAULT NULL,
  `invoice` varchar(45) DEFAULT NULL,
  `notification` mediumtext NOT NULL,
  `hash` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash_UNIQUE` (`hash`),
  KEY `custom` (`custom`,`payment_status`),
  KEY `invoice` (`invoice`,`payment_status`),
  KEY `type` (`txn_type`,`payment_status`),
  KEY `id` (`txn_id`,`payment_status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_tb`
--

CREATE TABLE IF NOT EXISTS `log_tb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `ip` varchar(36) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora` varchar(160) DEFAULT NULL,
  `navegador` varchar(160) DEFAULT NULL,
  `msg` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mercadopago`
--

CREATE TABLE IF NOT EXISTS `mercadopago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collection_id` varchar(255) DEFAULT NULL,
  `collection_status` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `merchant_order_id` varchar(255) DEFAULT NULL,
  `preference_id` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket` varchar(11) DEFAULT NULL,
  `customer` varchar(11) DEFAULT NULL,
  `user` varchar(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `hour` varchar(16) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `msg` longtext,
  `anexo` varchar(255) DEFAULT NULL,
  `type` enum('1','2') DEFAULT NULL,
  `status` enum('S','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `packages`
--

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` varchar(11) DEFAULT NULL,
  `name` varchar(90) DEFAULT NULL,
  `quota` varchar(11) DEFAULT NULL,
  `bandwidth` varchar(11) DEFAULT NULL,
  `subdomain` varchar(11) DEFAULT NULL,
  `park` varchar(11) DEFAULT NULL,
  `addon` varchar(11) DEFAULT NULL,
  `ftp` varchar(11) DEFAULT NULL,
  `pop` varchar(11) DEFAULT NULL,
  `list` varchar(11) DEFAULT NULL,
  `psql` varchar(11) DEFAULT NULL,
  `feature` varchar(90) DEFAULT 'default',
  `ip` varchar(60) DEFAULT '0',
  `cgi` varchar(60) DEFAULT '0',
  `fronpage` varchar(60) DEFAULT '0',
  `lang` varchar(2) DEFAULT 'en',
  `theme` enum('x3','rvskin','paper_lantern') DEFAULT 'x3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` int(11) DEFAULT NULL,
  `p_type` int(11) DEFAULT NULL,
  `p_group` int(11) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `module` enum('cPanel/WHM') DEFAULT NULL,
  `package` varchar(160) DEFAULT NULL,
  `email_template` int(11) DEFAULT NULL,
  `description` longtext,
  `automation` enum('1','2','3','4') DEFAULT NULL,
  `price` varchar(11) DEFAULT NULL,
  `status` enum('S','N') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `promotions`
--

CREATE TABLE IF NOT EXISTS `promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `code` varchar(16) DEFAULT NULL,
  `valor` varchar(2) DEFAULT '0',
  `utilized` varchar(11) NOT NULL DEFAULT '0',
  `status` enum('S','N') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_order` varchar(16) DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  `payment` enum('PayPal','PagSeguro','bCash','Boleto') DEFAULT NULL,
  `date` date DEFAULT NULL,
  `hour` varchar(16) DEFAULT NULL,
  `invoice_email` varchar(2) DEFAULT NULL,
  `billing` varchar(2) DEFAULT NULL,
  `billing_email` varchar(2) DEFAULT NULL,
  `sessao` varchar(255) DEFAULT NULL,
  `obs` text,
  `status` enum('S','N','A') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `requests_tbl`
--

CREATE TABLE IF NOT EXISTS `requests_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request` int(11) DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  `product` int(11) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `tld` varchar(60) DEFAULT NULL,
  `username` varchar(60) DEFAULT NULL,
  `userpwd` varchar(60) DEFAULT NULL,
  `dns1` varchar(255) DEFAULT NULL,
  `dns2` varchar(255) DEFAULT NULL,
  `dns3` varchar(255) DEFAULT NULL,
  `dns4` varchar(255) DEFAULT NULL,
  `qtd` varchar(11) NOT NULL,
  `amount` varchar(11) NOT NULL,
  `actions` enum('Register','Transfer') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servers`
--

CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `module` enum('cPanel/WHM') DEFAULT NULL,
  `ip` varchar(90) DEFAULT NULL,
  `company` varchar(160) DEFAULT NULL,
  `price` varchar(11) DEFAULT NULL,
  `dns1` varchar(255) DEFAULT NULL,
  `ip1` varchar(90) DEFAULT NULL,
  `dns2` varchar(255) DEFAULT NULL,
  `ip2` varchar(90) DEFAULT NULL,
  `max` varchar(11) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `pwd` varchar(160) DEFAULT NULL,
  `whmkey` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tdl`
--

CREATE TABLE IF NOT EXISTS `tdl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tdl` varchar(160) DEFAULT NULL,
  `amount` varchar(11) DEFAULT NULL,
  `period` enum('1','2','3','4','5','6','7','8','9','10') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `temp_cart`
--

CREATE TABLE IF NOT EXISTS `temp_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sessao` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `price_domain` varchar(11) DEFAULT NULL,
  `tld` varchar(60) DEFAULT NULL,
  `dns1` varchar(255) DEFAULT NULL,
  `dns2` varchar(255) DEFAULT NULL,
  `dns3` varchar(255) DEFAULT NULL,
  `dns4` varchar(255) DEFAULT NULL,
  `domainoption` enum('register','owndomain') DEFAULT NULL,
  `product` int(11) DEFAULT '0',
  `price_product` varchar(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `hour` varchar(16) DEFAULT NULL,
  `period` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tpk` varchar(16) DEFAULT NULL,
  `customer` varchar(11) DEFAULT NULL,
  `user` varchar(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `hour` varchar(16) DEFAULT NULL,
  `department` varchar(11) DEFAULT NULL,
  `priority` enum('B','M','A') DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `msg` longtext,
  `anexo` varchar(255) DEFAULT NULL,
  `status` enum('1','2','3','4','5') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invoice` varchar(127) DEFAULT NULL,
  `custom` varchar(255) DEFAULT NULL,
  `txn_type` varchar(55) NOT NULL,
  `txn_id` int(11) NOT NULL,
  `payer_id` varchar(13) NOT NULL,
  `currency` char(3) NOT NULL,
  `gross` decimal(10,2) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `handling` decimal(10,2) DEFAULT NULL,
  `shipping` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(17) DEFAULT NULL,
  `pending_reason` varchar(17) DEFAULT NULL,
  `reason_code` varchar(31) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payer` (`payer_id`,`payment_status`),
  KEY `txn` (`txn_id`,`payment_status`),
  KEY `custom` (`custom`,`payment_status`),
  KEY `invoice` (`invoice`,`payment_status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tutorial`
--

CREATE TABLE IF NOT EXISTS `tutorial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext,
  `youtube` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_user` varchar(160) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(60) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(160) DEFAULT NULL,
  `state` varchar(90) DEFAULT NULL,
  `zipcode` varchar(60) DEFAULT NULL,
  `login` varchar(90) DEFAULT NULL,
  `senha` varchar(60) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `signature` text,
  `nivel` enum('1','2','3','4','5') DEFAULT NULL,
  `suporte` int(11) DEFAULT NULL,
  `status` enum('S','N') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name_user`, `email`, `phone`, `address`, `city`, `state`, `zipcode`, `login`, `senha`, `salt`, `signature`, `nivel`, `suporte`, `status`) VALUES
(1, 'Obvio Sistemas', 'You E-mail', 'Your Phone', 'Your Address', 'Your City', 'Your State', 'ZIPCODE', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'YWRtaW4=', '<p>My e-mail signature model.<br><br>  Thanks for your visit.</p>', '1', 1, 'S');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
