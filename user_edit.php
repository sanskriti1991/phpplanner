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
	if (isset($_POST['username'])) {
		if (IsEmailValid($_POST['email'])) {
			mysql_query("UPDATE cal_users SET username = '". $_POST['edtUsername'] ."', name = '". $_POST['name'] ."', email = '". $_POST['email'] ."' WHERE id = ". $_SESSION['userid']);
		} else {
			notice(lang('email_novalid'));
		}
	}
	
	if (isset($_POST['edtPassword'],$_POST['edtPassword2']) && !empty($_POST['edtPassword'])) {
		if ($_POST['edtPassword'] == $_POST['edtPassword2']) {
			mysql_query("UPDATE cal_users SET password = '". MD5($_POST['edtPassword']) ."' WHERE id = ". $_SESSION['userid']);
		} else {
			notice(lang('password_nomatch'));
		}
	
	}

	make_template_header();
	make_header(lang('edit_profile'));
	$SQL = mysql_query("SELECT * FROM cal_users WHERE id = ". $_SESSION['userid']);
	$RS = mysql_fetch_array($SQL);

?> <br>

<form name="form1" method="post" action="user_edit.php">
        <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr valign="top"> 
            <td width="120"><font size="2" face="Verdana"><?= lang('username') ?></font></td>
            <td> <font size="2" face="Verdana"> 
              <input name="edtUsername" type="text" size="60" value="<?= $RS['username'] ?>">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="120"><font size="2" face="Verdana"><?= lang('password') ?></font></td>
            <td><font size="2" face="Verdana"> 
              <input name="edtPassword" type="password" size="60">
              <font size="1"><br>
              </font></font></td>
          </tr>
          <tr valign="top"> 
            <td width="120"><font size="2" face="Verdana"><?= lang('retype_password') ?></font></td>
            <td><font size="2" face="Verdana"> 
              <input name="edtPassword2" type="password" size="60">
              <font size="1"><br>
              </font></font></td>
          </tr>
          <tr valign="top"> 
            
      <td colspan="2"><font size="2" face="Verdana"><font size="1">
	  <?= lang('password_note') ?><br>
        <?= lang('password_change_warning') ?><br>
        <br>
        </font></font></td>
          </tr>
          <tr valign="top"> 
            <td><font size="2" face="Verdana"><?= lang('name') ?></font></td>
            <td><font size="2" face="Verdana"> 
              <input name="name" type="text" size="60"  value="<?= $RS['name'] ?>">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="120"><font size="2" face="Verdana"><?= lang('email') ?></font></td>
            <td><font size="2" face="Verdana"> 
              <input name="email" type="text" size="60" value="<?= $RS['email'] ?>">
              </font></td>
          </tr>
          <tr> 
            
      <td colspan="2"><font size="2" face="Verdana"> <br>
        <input type="submit" name="Submit" value="<?= lang('edit_profile') ?>">
        <br>
        <br>
        </font></td>
          </tr>
        </table>
      </form>
	  <?php make_end_html() ?>