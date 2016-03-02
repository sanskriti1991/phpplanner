<?php

// Copyright (C) 2002 by Tom Sommer Jensen <webmaster@tsn.dk> | <www.tsn.dk>

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

	require_once("common.php");
	if (isset($_POST['email']) && !IsEmailValid($_POST['email'])) {
		notice(lang('email_novalid'));
	}
	
	if (isset($_POST['Submit'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['password2'], $_POST['name']) && IsEmailValid($_POST['email'])) {
		$SQL = mysql_query("SELECT * FROM cal_users WHERE username = '". $_POST['username'] ."' OR password = '". MD5($_POST['password']) ."' OR email = '". $_POST['email'] ."'");
		if (mysql_num_rows($SQL) > 0) {
			notice(lang('account_exists'));
		}
		
		if ($_POST['password'] != $_POST['password2']) {
			notice(lang('password_nomatch'));
		}
		
		$SQL = sprintf("INSERT INTO cal_users (username, password, email, name, last_ip) VALUES('%s','%s', '%s','%s', '%s')"
				,$_POST['username']
				,MD5($_POST['password'])
				,$_POST['email']
				,$_POST['name']
				,$_SERVER['REMOTE_ADDR']
			);
				
		mysql_query($SQL) or die(mysql_error());
		
	   		$mail_Priority = "X-Priority: 2\n";
		    $mail_from = "From: ". $config['mail_from'] ." <". $config['mail_from_mail'] .">\n";
    		$mail_mailer = "X-Mailer: PHP/". phpversion();
    		$mail_to = $_POST['email'];
			
			if ($config['require_mail_validation']) {
    			$mail_subject = "Your validation for ". $config['name'];
    			$mail_message = "Hi, ". $_POST['name'] .", we would like to welcome you to our calendar.\n\n";
   		 		$mail_message .= "To get started you need to validate your account, this can be done by clicking the link below.\n\n";
    			$mail_message .= "http://". $_SERVER['HTTP_HOST'] . $config['path_to_calendar'] ."validate.php?user=". URLEncode($_POST['username'])  ."&s=". MD5($_POST['password']) ."\n";
    			$mail_message .= "Once you have validated your account you will have access to a number of features around the site\n\n";
    		} else {
    			$mail_subject = "Welcome to calendar: ". $config['name'];
    			$mail_message = "Hi, ". $_POST['name'] .", we would like to welcome you to our calendar.\n";
				$mail_message .= "The main reason for this email is to provide you with your password and username. You already have access to the calendar, but need to login with the information below\n\n";	
			
			}  
    	    $mail_message .= "Your username is \'". $_POST['username'] ."\'\n";
    	    $mail_message .= "Your password is \'". $_POST['password'] ."\'\n";
    	    $mail_message .= "Please SAVE this information because you will NOT be able to get this information again without changing password\n";
			$mail_message .= "\n-------";
			$mail_message .= "\n". $config['mail_signature'];
		
    		$mail_header = $mail_Priority . $mail_from . $mail_mailer;

    	if (!(@mail($mail_to,$mail_subject,$mail_message, $mail_header))) {
     			notice("Encounted an error while trying to send a mail to ". $mail_to);
    	}

        	notice(lang('register_success'));

	
	} else {
	//	header("Location: register.php?error=You forgot to add some information, add fields are required");
	}
	make_template_header();
	make_header(lang('register'));
	
	if ($config['require_mail_validation']) 
	{ 
		echo lang('register_email_validation');	
	} else 
	{ 
		echo lang('register_no_validation');
	}
	if (isset($_GET['error'])) 
	{
	  		echo "<br><center><font color=\"red\">ERROR: ". $_GET['error'] ."</font></center>";
	  
	}
	  
	  ?>
      <form name="form1" method="post" action="register.php">
        <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr valign="top"> 
            <td width="120"><font size="2" face="Verdana"><?= lang('username') ?></font></td>
            <td> <font size="2" face="Verdana"> 
              <input name="username" type="text" size="60">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="120"><font size="2" face="Verdana"><?= lang('password') ?></font></td>
            <td><font size="2" face="Verdana"> 
              <input name="password" type="password" size="60">
              <font size="1"><br>
              </font></font></td>
          </tr>
          <tr valign="top"> 
            <td width="120"><font size="2" face="Verdana"><?= lang('retype_password') ?></font></td>
            <td><font size="2" face="Verdana"> 
              <input name="password2" type="password" size="60">
              <font size="1"><br>
              </font></font></td>
          </tr>
          <tr valign="top"> 
            <td colspan="2"><font size="2" face="Verdana"><font size="1"><?= lang('password_note') ?><br>
              <br>
              </font></font></td>
          </tr>
          <tr valign="top"> 
            <td><font size="2" face="Verdana"><?= lang('name') ?></font></td>
            <td><font size="2" face="Verdana"> 
              <input name="name" type="text" size="60">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="120"><font size="2" face="Verdana"><?= lang('email') ?></font></td>
            <td><font size="2" face="Verdana"> 
              <input name="email" type="text" size="60">
              </font></td>
          </tr>
          <tr> 
            <td colspan="2"><font size="2" face="Verdana"> 
              <input type="submit" name="Submit" value="<?= lang('submit') ?>">
              </font></td>
          </tr>
        </table>
      </form>
	  <?php make_end_html() ?>