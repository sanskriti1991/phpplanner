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

	$config = array();

	$config['mysql_host'] = "localhost";
	$config['mysql_username'] = "root";
	$config['mysql_password'] = "c1am2016";
	$config['mysql_database'] = "phpplanner";

	$config['path_to_calendar'] = "/"; // could be /home/path/
	$config['mail_host'] = "localhost";
	$config['mail_from'] = "PHP Planner Robot";
	$config['mail_from_mail'] = "mail@localhost";
	$config['mail_signature'] = "Best regards\nThe administrative team";

	$config['name'] = "PHP Planner"; 			// The name of your calendar
	$config['cell_height'] = 60;				// Set the height of the top and bottom cells inside the calendar
	$config['table_width'] = 755; 				// The width of the main table
	$config['show_hints'] = true; 				// Disable or Enable the javascript hints
	$config['hide'] = false; 					// With this set to true all items are hidden to users whom does not have a valid account
	$config['require_mail_validation'] = true; 	// If set to true then an E-Mail will be sent after registration, and users need to respond to this mail before they can become active members
	$config['date_syntax'] = "D M d, Y G:i"; 	// Basic date syntax

	$config['Language'] = "en"; 				// Default Language
	$config['Languages'] = array(
									 'dk' => 'Danish'
									,'en' => 'English'
									,'fr' => 'French'
								);

	$config['colors'] = array(
#							 id => color, name_of_color, name_of_speciel, image
							  0 => array('#FFFFFF','White', 'default', '') # This is the default color of all cells
							 ,1 => array('#990000','Red', 'important', 'special.gif')
							 ,2 => array('#FF9900','Orange', 'notice', 'special.gif')
							 ,3 => array('#EEEEEE','Gray', 'vacation', '')
							);
?>
