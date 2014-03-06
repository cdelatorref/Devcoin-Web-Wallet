<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * email File Config
 *
 * File     : email.php
 * Created  : Feb 20, 2014 
 * 
 * Author   : cdelatorref
 * URL      : http://www.devtome.com/doku.php?id=wiki:user:cdelatorref
 * -------------------------------------------------------------------
 * You can use this config file if the SMTP is not installed in your server
 */

//Mail server configuration
//---------------------------------------------------------------------
//SMTP Server Address.
$config['smtp_host']   = '95.85.36.199';
//SMTP Protocol.
$config['protocol']  = 'smtp';
//SMTP Port
$config['smtp_port']  = '26';
//Mail content configuration
//---------------------------------------------------------------------
//Mail remitent (Join)
$config['fromMailJoin']="staff@thardferr.com";
//Sender's name (Join)
$config['fromJoin']="Thardferr Staff";
//Subject for the Join message
$config['joinSubject']="Thardferr's activation code.";
//Subject for the Reset message
$config['resetSubject']="Thardferr's password reset.";
//Join E-mail text top
$config['mail_body1']=
"Dear user:

In order to complete your registration in our system, it's important to go to the next link, you can click it or copy/paste in your web browser.

";

//Join E-mail text bottom
$config['mail_body2']=
"
NOTE: Don't reply this e-mail.

Regards
Thardferr Staff

";
//Reset password text top
$config['reset_body1']=
"
Dear user:

In order to reset your password, it's important to go to the next link, you can click it or copy/paste in your web browser.

";
//Reset password text bottom
$config['reset_body2']=
"
NOTE: Don't reply this e-mail.

Regards
Thardferr Staff
";
?>