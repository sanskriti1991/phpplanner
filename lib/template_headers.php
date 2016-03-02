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

function html_comment($str) {
	return chr(10) . chr(10) . '<!-- '. $str .' -->' . chr(10);
}

/* Make basic HTML header */
function make_header($str) {
	global $config , $current;
		echo html_comment('START Main Table');
		echo chr(10) . '<table width="'. $config["table_width"] .'" border="0" align="center" cellpadding="0" cellspacing="1" class="blackBacking">';
		echo chr(10) . '<tr>';
		echo chr(10) . '<td class="menu-top">';
		echo html_comment('START Menu Table');
		echo chr(10) . '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
		echo chr(10) . '<tr>';
		echo chr(10) . '<td width="200" class="menu-top">';
		echo chr(10) . '<a href="index.php"><img src="'. $config['path_to_calendar'] .'images/logo.jpg" width="200" height="54" align="bottom" alt="'. $current['application'] .'"></a></td>';
		echo chr(10) . '<td class="menu-top" align="center">';
		echo $str;
		echo chr(10) . '</td>';
		echo chr(10) . '<td width="200" class="menu-top" align="right">';
		echo chr(10) . '<a href="search.php"><img src="'. $config['path_to_calendar'] .'images/search.gif" width="16" height="16" align="bottom" alt="Search"> '. lang('search')  .'</a>&nbsp;<br>';
		echo chr(10) . '<a href="members.php"><img src="'. $config['path_to_calendar'] .'images/members.gif" width="16" height="16" align="bottom" alt="View members"> '. lang('members') .'</a>&nbsp;</td>';
		echo chr(10) . '</tr>';
		echo chr(10) . '</table>';
		echo html_comment('END menu Table');
		echo chr(10) . '</td>';
		echo chr(10) . '</tr>';
		echo chr(10) . '<tr>';
		echo html_comment('START Calendar/Content cell');
		echo chr(10) . '<td class="calBackground">';
		echo chr(10);
}
	
/* Make top-header */
function make_template_header() {
		global $config, $current;
			echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
			echo chr(10) . '<HTML>';
			echo chr(10) . '<HEAD>';
			echo chr(10) . chr(9) .'<link href="'. $config['path_to_calendar'] .'css.css" rel="stylesheet" type="text/css">';
			echo chr(10) . chr(9) .'<script language="JavaScript1.2" src="'. $config['path_to_calendar'] .'javascript.js" type="text/JavaScript1.2"></script>';
			echo chr(10) . chr(9) .'<TITLE>'. $current['title'] .'</TITLE>';
			echo chr(10) . chr(9) .'<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
			echo chr(10) . '</HEAD>';
			echo chr(10) . '<BODY>';
	
}

function make_end_html() {
	global $copyright, $current, $config;
	echo "</td>\n</tr>\n<tr>";
	echo "<td align=\"center\" valign=\"middle\" class=\"calNotDay\"><br>";
		include_once("bottom.php");
	echo "</td>\n</tr>";
	echo "</table>";
	echo html_comment('END Main Table');
	echo '<table border="0" cellspacing="0" cellpadding="0" align="center" width="'. $config['table_width'] .'">';
  	echo '<tr>';
    echo '<td align="center" width="20%"><font size="1">PHP Planner v.'. $current['version'] .'</font></td>';
    echo '<td align="center" width="60%">'. $copyright .'</td>';
    echo '<td align="center" width="20%">';
	echo '<form name="lang_changer" action="" method="POST">';
	echo '<font size="1">'. lang('language') .': <select name="new_lang" onChange="lang_changer.submit()">';
	foreach($config['Languages'] as $prefix => $lang) {
		printf("<option value=\"%s\"%s>%s</option>"
				, $prefix
				, ($prefix == $_SESSION['language']) ? " SELECTED" : ""
				, $lang
			);
	}
	echo '</select></font>';
	echo '</form>';
	echo '</td>';
 	echo '</tr>';
	echo '</table>';
	
	echo "</body>";
	echo "</HTML>";
}

function show_event($SQLarray) {
		global $config, $current;

		echo "<br>\n<table class=\"blackBacking\" width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"2\" cellspacing=\"1\">"; // Make Table
		$now = mktime(0,0,0,$current['month'],$current['day'],$current['year']);
		if ($SQLarray['date'] > $now)
			$_print = "colFuture";
		elseif ($SQLarray['date'] < $now)
			$_print = "colPast";
		else
			$_print = "colNow";
		
		echo "<tr>\n<td class=\"". $_print ."\"><font size=\"1\">";
			if ($SQLarray['color'] != 0) 
				echo "<u>". ucwords($config['colors'][$SQLarray['color']][2]) ."</u> - ";
				
			if ($SQLarray['private'] == $_SESSION['userid'] && $_SESSION['userid'] != 0) 
				echo "(". lang('private') .") ";
				
	  			echo "<strong>". $SQLarray['caption'] ."</strong>";
		
		printf(", %s <a href=\"userinfo.php?userid=%d\">%s</a> (%s)"
				,($SQLarray['edited_by'] != 0) ? lang('edited_by') : lang('added_by')
				,($SQLarray['edited_by'] != 0) ? $SQLarray['edited_by'] : $SQLarray['added_by']
				,($SQLarray['edited_by'] != 0) ? getusername($SQLarray['edited_by']) : getusername($SQLarray['added_by'])
				,ftime('%a %b %d, %Y %H:%M',$SQLarray['lastupdated'])
		);

		//if (getuserpermission($_SESSION['userid'],"allow_edit") || ($_SESSION['userid'] == $SQLarray['added_by']))
			echo "&nbsp;&nbsp; [<a href=\"". addparem("edit=". $SQLarray['id'])  ."\">". lang('edit') ."</a>]"; // If allowed print edit link

		if (getuserpermission($_SESSION['userid'],"allow_delete") || ($_SESSION['userid'] == $SQLarray['added_by']))
			echo " [<a href=\"". addparem("delete=". $SQLarray['id']) ."\">". lang('delete') ."</a>]"; // If allowed print delete link
			
		printf("</font></td></tr>\n<tr>\n<td class=\"calNotDay\"><div class=\"post\">%s%s</div></td>\n</tr>\n</table>\n\n"
			,($SQLarray['color'] != 0) ? special_image($SQLarray['color']) ." " : ""
			,formatoutput($SQLarray['description'],true,true)
		);
}
?>