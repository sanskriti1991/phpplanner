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

echo html_comment("END Calendar/Content cell");

if($_SESSION['userid'] == 0) { 
	echo html_comment("START Bottom Form");
	echo '<form action="'. $current['base'] .'" enctype="multipart/form-data" method="post" name="login">';
    echo chr(10) . lang('username'); 
    echo chr(10) . '<input name="username" type="text" id="username">';
    echo chr(10) . lang('password');
    echo chr(10) . '<input name="password" type="password" id="password">';
	echo chr(10) . 'Auto <input name="autologin" type="checkbox" id="autologin" value="true" class="remove_css">';
    echo chr(10) . '<input type="submit" name="login" value="'. lang('log_in') .'">';
    echo chr(10) . '[<a href="register.php">'. lang('register') .'</a>]';
	echo chr(10) . '</form>';
	echo html_comment("END Bottom Form");
	
} else
	printf(lang('logged_in_as') ." <strong><a href=\"userinfo.php?userid=%d\">%s</a></strong> [<a href=\"user_edit.php\">". lang('edit_profile') ."</a>] [<a href=\"%s\">". lang('log_out') ."</a>]<br />"
		, $_SESSION['userid']
		, getusername($_SESSION['userid'])
		, addparem("mode=logout")
	);
	
	echo chr(10) . lang('server_time') .' : '. ftime("%c",time()) .'<br />';
	
	$subSQL = mysql_query("SELECT COUNT(id) as Total FROM cal_users WHERE allow_add = 'true'") or die(mysql_error());
	$subRS = mysql_fetch_array($subSQL);		
		echo lang('total_users') .": ". $subRS['Total'];
		
	$subSQL = mysql_query("SELECT COUNT(id) as Total FROM cal_items") or die(mysql_error());
	$subRS = mysql_fetch_array($subSQL);		
		echo " - ". lang('total_events') .": ". $subRS['Total'];
		echo html_comment("END bottom.php");

?>