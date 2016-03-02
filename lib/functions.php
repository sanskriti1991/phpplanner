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

function lang($str) {
	global $lang; 
	$str = strtolower($str);

	if ( isset($lang[$str]) )
		return $lang[$str];
	else
		return $str;
}
function notice($str) 
{
	header("Location: notice.php?msg=". urlencode($str));
	die();
}
	
	/* Idea by Missarh, sets a cookie right away */
function set_cookie($name,$value,$expire=1234567,$path=NULL,$domain=NULL,$secure=0) 
{
	setcookie($name,$value,time()+$expire,$path,$domain,$secure); 
	$_COOKIE[$name] = $value;	
}

	/* Idea by Missarh, deletes a cookie */
function delete_cookie($name) 
{
	setcookie($name,"",time() - 3600);
	$_COOKIE[$name] = '';
	unset($_COOKIE[$name]);
}

function add_links($str)
{
	
	$ret = " " . $str;
	$rpl_string = "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>";
	$ret = preg_replace("#([\n ])([a-z]+?)://([^\t <\n\r]+)#i", $rpl_string, $ret);
	
	$rpl_string = "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">\\2.\\3\\4</a>";
	$ret = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^\t <\n\r]*)?)#i", $rpl_string, $ret);

	$rpl_string = "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>";
	$ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", $rpl_string, $ret);
	
	$ret = substr($ret, 1);
	
	return $ret;
}
function FormatOutput($str,$replaceLB=false,$removeHTML=true) 
{
	$str = trim($str);
	
	if ($removeHTML) 
	{
		$str = htmlentities($str);
	}
	
	$str = add_links($str);
			
	$str = stripslashes($str);
		
	if ($replaceLB) 
	{
		$str = nl2br($str);
	}
			
	return $str;
}

function ftime($s,$stamp=NULL) {
	return ucwords(strftime($s,$stamp));
}

function special_image($id=0,$align="middle") {
	global $config;
	if ($id != 0) {
		$pic = $config['colors'][$id][3];
		if (is_file("./images/". $pic))
			return '<img src="./images/'. $pic .'" height="16" width="16" border="0" alt="'. ucwords($config['colors'][$id][2]) .' event" align="$align">';	
		else
			return "";
	}
}

function login($username,$password,$auto=true,$redirect=true)
{
	global $config;
	delete_cookie("cal_username");
	delete_cookie("cal_password");
	
	$SQL = mysql_query("SELECT id FROM cal_users WHERE username = '". $username."' AND password = '".  $password ."' and allow_add = 'true'") or die(mysql_error());
	
	if (mysql_num_rows($SQL) > 0) 
	{
		$RS = mysql_fetch_array($SQL);
		mysql_query("UPDATE cal_users SET last_login = NOW(),last_ip = '". $_SERVER['REMOTE_ADDR'] ."' WHERE id = ". $RS['id']) or die(mysql_error()); 
		$_SESSION['userid'] = $RS['id'];
		if ((bool)$auto == true)
		{
			set_cookie('cal_username',$username,3153600);
			set_cookie('cal_password',$password,3153600);
		}
		if ($redirect)
			notice(lang('correct_pass'));
	} 
	else
		if ($redirect)
			notice(lang('error_wrong_pass'));
		
}

	function new_week($index)
	{
		global $current, $config;
		$week = date("W", mktime(0,0,0,$current['month'],1,$current['year']) ) + ($index/7);
		echo <<<WEEK

	</tr>
	<tr>
<!-- NEW WEEK # {$week}-->
	<td valign="top" class="calweek" width="39" height="{$config['cell_height']}">{$week}</td>

WEEK;
	
	}
	
	function wrap_event($stamp=NULL,$link=false,$content='',$strhint='')
	{
		global $config, $current;
		$width = round(($config['table_width']-38)/7,0);
		
		if ( !is_null($stamp) )
		{
			$stamp = mktime(0,0,0,$current['month'],$stamp-$current['first_day_of_month']+1,$current['year']);
			$content = '&nbsp;&nbsp;'. ftime("%a %d", $stamp ) . "<br>". $content;
		}
		
		
		if ( $content != '' && hide() != true )
		{
			$hint = ftime("%a %d", $stamp );
			$hint = "onMouseOver = \"popup('". str_replace( "\r\n", "<br>&nbsp;", addslashes($strhint) ) ."');\" onMouseOut=\"kill()\"";
		}			
		

		if ( $link === true )
		{
			$content = '<a href="manage.php?stamp='. $stamp .'" '. @$hint .'>'. $content .'</a>';
		}
		
		echo <<<EVENT
		
<!-- NO EVENT -->
	<td class="calNotDay" height="{$config['cell_height']}" width="{$width}" valign="top">{$content}</td>
EVENT;

	}
	
	function new_empty_event($x=NULL)
	{
		wrap_event($x,true);
	}
	
	function new_event($x,$ary)
	{
		$res = array();
		foreach ($ary as $y => $item)
		{
			$res[] = $item['caption'];
			$hint[] = '<strong>&#8226; '. $item['caption'] .' '. ( $item['private'] != 0 ? ' (Private)' : '' ).'</strong>'; 
			$hint[] = $item['description']; 
		}
		
		wrap_event( $x,true,implode("<br>",$res), implode("<br>",$hint));
	
	
	}
?>