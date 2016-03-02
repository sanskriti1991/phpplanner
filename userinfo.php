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
	if (isset($_GET['edit'])) {
		if (isset($_GET['allow_edit'])) {
			$key = "allow_edit";
		} elseif (isset($_GET['allow_delete'])) {
			$key = "allow_delete";
		} elseif (isset($_GET['allow_add'])) {
			$key = "allow_add";
		} elseif (isset($_GET['is_admin'])) {
			$key = "is_admin";
		} 
		
		mysql_query("UPDATE cal_users SET ". $key ." = '". $_GET[$key] ."' WHERE id = ". $_GET['edit']) or die(mysql_error());
		
	}
	
	if (isset($_GET['edit'])) {
		$usrid = $_GET['edit'];
	}  else {
		$usrid = $_GET['userid'];
	}

	if (isset($_GET['delete'])) {
		mysql_query("DELETE FROM cal_users where id = ". $_GET['delete']) or die(mysql_error());
	}
	$SQL = mysql_query("SELECT name, last_ip, last_login, id, email, UNIX_TIMESTAMP(`last_login`) AS lastlogin FROM cal_users WHERE id = ". $usrid);
	$RS = mysql_fetch_array($SQL);

	make_template_header();
	if (isset($_GET['delete'])) { notice("User deleted"); }
	
	make_template_header();
	make_header($RS['name']); 
	
?>
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><font size="2"><strong><?= lang('name') ?></strong><br>
      <?= $RS['name'] ?>
      <br>
      <br>
      <strong><?= lang('email') ?></strong><br>
      <?php
		echo "<a href=\"mailto:". $RS['email'] ."\">". $RS['email'] ."</a>"; 
		?>
      <br>
      <br>
      <strong><?= lang('last_ip') ?></strong><br>
      <?php
		if (getUserPermission($_SESSION['userid'],"is_admin") || $RS['id'] == $_SESSION['userid']) {
			echo  $RS['last_ip'] ." / ". getHostByAddr($RS['last_ip']);
		} else {
			echo "[ hidden ]";
		}
		
		 ?>
      <br>
      <br>
      <strong><?= lang('last_login') ?></strong><br>
      <?= date($config['date_syntax'],$RS['lastlogin']) ?>
      <br>
      </font></td>
    <td align="right" valign="top">
	<?php
if (getUserPermission($RS['id'],"is_admin"))
	echo "<font size=\"2\">". $RS['name'] . " ". lang('is_an_admin') ."</font><p>";
?>
		  
      <table width="200" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td colspan="2"><strong><font size="2"><?= lang('statistics') ?></font></strong></td>
        </tr>
        <tr> 
          <td><font size="1"><?= lang('total_events') .' '. lang('added') ?></font></td>
          <td><font size="1"> 
            <?php
		$subSQL = mysql_query("SELECT COUNT(id) as Total FROM cal_items WHERE added_by = ". $RS['id']) or die(mysql_error());
		$subRS = mysql_fetch_array($subSQL);		
		echo $subRS['Total'] ?>
            </font></td>
        </tr>
        <tr> 
          <td><font size="1"><?= lang('total_events') .' '. lang('edited') ?></font></td>
          <td><font size="1"> 
            <?php
		$subSQL = mysql_query("SELECT COUNT(id) as Total FROM cal_items WHERE edited_by = ". $RS['id']) or die(mysql_error());
		$subRS = mysql_fetch_array($subSQL);		
		echo $subRS['Total'] ?>
            </font></td>
        </tr>
        <tr> 
          <td><font size="1"><?= lang('total_private') ?></font></td>
          <td><font size="1"> 
            <?php
		$subSQL = mysql_query("SELECT COUNT(id) as Total FROM cal_items WHERE private = ". $RS['id']) or die(mysql_error());
		$subRS = mysql_fetch_array($subSQL);		
		echo $subRS['Total'] ?>
            </font></td>
        </tr>
        <tr> 
          <td><font size="1"><?= lang('can_add') ?></font></td>
          <td><font size="1"> 
            <?php
		  if (getUserPermission($RS['id'],"allow_add")) {
		  	echo "Yes";
		  } else {
		  	echo "No";
		  }
			?>
            </font></td>
        </tr>
        <!--<tr> 
          <td><font size="1"><?php //lang('can_edit') ?></font></td>
          <td><font size="1"> 
            <?php
		  /*if (getUserPermission($RS['id'],"allow_edit")) {
		  	echo "Yes";
		  } else {
		  	echo "No";
		  }*/
			?>
            </font></td>
        </tr>-->
        <tr> 
          <td><font size="1"><?= lang('can_delete') ?></font></td>
          <td><font size="1"> 
            <?php
		  if (getUserPermission($RS['id'],"allow_delete")) {
		  	echo "Yes";
		  } else {
		  	echo "No";
		  }
			?>
            </font></td>
        </tr>
        <tr> 
          <td colspan="2" align="center" valign="top"> <font size="1"> 
            <?php
		
if (getUserPermission($_SESSION['userid'],"is_admin")) {
	  		echo "<font size=\"1\">Manage Permissions<br>";
			
printf("[<a href=\"userinfo.php?edit=%d&%s=%s\"><font color=\"%s\">%s</font></a>]"
	, $RS['id']
	, "allow_add"
	, (GetuserPermission($RS['id'],"allow_add")) ? "false" : "true"
	, (GetuserPermission($RS['id'],"allow_add")) ? "green" : "red"
	, lang('toggle_add')
);	 

/*printf("[<a href=\"userinfo.php?edit=%d&%s=%s\"><font color=\"%s\">%s</font></a>]"
	, $RS['id']
	, "allow_edit"
	, (GetuserPermission($RS['id'],"allow_edit")) ? "false" : "true"
	, (GetuserPermission($RS['id'],"allow_edit")) ? "green" : "red"
	, lang('toggle_edit')
);*/

printf("[<a href=\"userinfo.php?edit=%d&%s=%s\"><font color=\"%s\">%s</font></a>]"
	, $RS['id']
	, "allow_delete"
	, (GetuserPermission($RS['id'],"allow_delete")) ? "false" : "true"
	, (GetuserPermission($RS['id'],"allow_delete")) ? "green" : "red"
	, lang('toggle_delete')
);
 
printf("[<a href=\"userinfo.php?edit=%d&%s=%s\"><font color=\"%s\">%s</font></a>]"
	, $RS['id']
	, "is_admin"
	, (GetuserPermission($RS['id'],"allow_delete")) ? "false" : "true"
	, (GetuserPermission($RS['id'],"allow_delete")) ? "green" : "red"
	, lang('toggle_admin')
);
echo "<br>[<a href=\"userinfo.php?delete=". $RS['id'] ."\">". lang('delete_user') ."</a>]";	 
}	  
?>
            </font></td>
        </tr>
      </table>
</td>
  </tr>
</table>
<br>
      </p>
 
<?php make_end_html() ?>