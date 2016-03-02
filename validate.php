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
$password = $_GET['s'];
$user = urldecode($_GET['user']);

$SQL = mysql_query("SELECT id FROM cal_users WHERE username = '". $user ."' AND password ='". $password ."' AND allow_add = 'false'") or die(mysql_error());

if (@mysql_num_rows($SQL) <= 0) {
	notice(lang('account_not_found'));
} else {
	mysql_query("UPDATE cal_users SET allow_add = 'true' WHERE password = '". $password ."' AND username = '". $user ."' AND allow_add = 'false'") or die(mysql_error());
	notice(lang('account_activated'));
}
?>