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

	require_once("config.php");
	
	mysql_connect($config['mysql_host'],$config['mysql_username'],$config['mysql_password']) 
	or die("<h1>MySQL Error</h1>We got an error while trying to connect to the database: <br />-- ". mysql_error() ."<br /><br />Check your mySQL information");
	
	mysql_select_db($config['mysql_database']);
	
	
	
	/* MySQL installer */
	if ( is_file('install_mysql.php.lock') )
		define('MYSQL_INSTALLED',true);
		
	if ( !defined('MYSQL_INSTALLED') )
		include('install_mysql.php');
		
	require_once("lib/functions.php");
	
   	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header('Content-Language: da',false);
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	error_reporting(E_ALL);
	set_time_limit(90);
	$current = array();
	
	$current['version'] = "0.4";
	$current['application'] = "PHP Planner";
	$current['title'] = $config['name'] ." -- ". $current['application'] ." ". $current['version'];
	
	ini_set("set_magic_quotes_gpc",true);
	ini_set("register_globals", false);
	ini_set("session.use_trans_sid",false);
	ini_set("include_path",".;lib/");
	ini_set("SMTP",$config['mail_host']);
	session_start();
	
	if (isset($_POST['new_lang'])) $_SESSION['language'] = $_POST['new_lang'];
	if (!isset($_SESSION['language'])) $_SESSION['language'] = $config['Language'];
	
	$lang = array();
	require_once("lang/en.php");  // for fallback
	if ( is_file("lang/". $_SESSION['language'] .".php") )
	{
		include_once("lang/". $_SESSION['language'] .".php");
	}
	
	setlocale (LC_TIME, $lang['lc_time']);
	
	if (!isset($_SESSION['userid']))
	{
		$_SESSION['userid'] = 0;
	}
	if ( !ereg('MSIE',$_SERVER['HTTP_USER_AGENT']) )
	{
		$config['show_hints'] = false;
	}

	$current['base'] = basename($_SERVER['PHP_SELF']);
	if ( isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ) 
			$current['base'] .= "?". $_SERVER['QUERY_STRING'];	

// ************************************************************************

	if ( isset($_POST['username'],$_POST['password'],$_POST['login']) )
	{
		login($_POST['username'],md5($_POST['password']),@$_POST['autologin']);	
	}
	
	if ( isset($_COOKIE['cal_username'],$_COOKIE['cal_password']) && $_SESSION['userid'] == 0) 
	{
		login($_COOKIE['cal_username'],$_COOKIE['cal_password'],true,false);	
	}
	
// ************************************************************************
	if (isset($_GET['view']))  
		$date = $_GET['view'];
	else 
		$date = time();
	
// ************************************************************************
	if (isset($_GET['mode']) && $_GET['mode'] == 'logout') 
	{
			delete_cookie("cal_username");
			delete_cookie("cal_password");
			$_SESSION['userid'] = 0;
			header("Location: ". $_SERVER['HTTP_REFERER']);
			die();
	}
	
// ************************************************************************

	
// ************************************************************************
	function AddParem($parem) 
	{
		global $current;
		$newbase = sprintf("%s%s%s"
				, $current['base']
				, ( eregi('\?', $current['base']) ) ? "&" : "?"
				, $parem
		);
		return $newbase;
	}
// ************************************************************************
	function GetUserPermission($userid,$permission) 
	{
		$SQL = mysql_query("SELECT $permission FROM cal_users where id = $userid") or die(mysql_error());
		$RS = mysql_fetch_array($SQL);
		if ($RS[$permission] == 'true') {
			return true;
		} else {
			return false;
		}
	
	}
	
// ************************************************************************
	function hide() 
	{
		global $config;
		if (!$config['hide'] || $_SESSION['userid'] != 0)
			return false;
		else
			return true;	
	}

// ************************************************************************
    function IsEmailValid($email) 
	{
        return eregi("^[0-9a-zA-Z_-]+@{1}[0-9a-zA-Z-]+\..+$",$email);
    }

// ************************************************************************	
	function MakePopUp($userid) 
	{
		return "<a href=\"#\" onclick=\"openUserInfo('". $userid ."');\">". getUserName($userid) ."</a>";
	}
	
// ************************************************************************
	function GetUsername($userid) 
	{
		$SQL = mysql_query("SELECT name FROM cal_users where id = $userid") or die(mysql_error());
		$RS = mysql_fetch_array($SQL);
		return $RS['name'];	
	}

// ************************************************************************


	require_once("lib/template_headers.php");
	require_once("lib/functions_manager.php");

	/* Do not REMOVE! */
  	$copyright = "<FONT COLOR=\"#000000\" FACE=\"Verdana\" SIZE=\"1\">Copyright &copy; 2002 - 2003. All Rights Reserved.<br />";
  	$copyright .= "Released under the GPL license<br />";
	$copyright .= "A <A HREF=\"http://dreamcoder.dk\" TARGET=\"_blank\">dreamcoder.dk</a> product - ";
	$copyright .= "<a href=\"http://phpplanner.sourceforge.net\" target=\"_blank\">phpplanner.sourceforge.net</a></font></div>";
	
	$current['month'] = date("n",$date);
	$current['year'] = date("Y",$date);
	$current['day'] = date("j",$date);
   	$current['days_in_month'] = date("t",$date);

?>