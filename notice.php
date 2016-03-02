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
	make_template_header();
	make_header("Notice display"); 
	
		printf("<div align=\"center\"><strong>\n<font size=\"2\">%s%s%s</strong><a href=\"%s\"><u>%s</u></a>%s</font></div>"
			, str_repeat("<br />",4)
			, (isset($_GET['msg'])) ? $_GET['msg'] : ""
			, str_repeat("<br />",2)
			, (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : "index.php"
			, lang('return')  
			, str_repeat("<br />",5)
		);
	
	make_end_html() 
?>