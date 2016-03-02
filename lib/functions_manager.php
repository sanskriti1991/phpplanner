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

/* Add new event */
function new_item($stamp,$userid,$caption,$description,$private=0,$color=0) {
			if (getUserPermission($_SESSION['userid'],"allow_add")) {
				mysql_query("INSERT INTO cal_items (`date`,`caption`,`description`,`added_by`,`last_updated`,`private`,`color`) VALUES(". $stamp .", '". $caption ."', '". $description ."', ". $userid .", NOW(), '". $private ."', $color)") or die(mysql_error());
				return true;
			} else {
				return false;
			}
}

/* Delete event */

function delete_item($id) {
		mysql_query("DELETE FROM cal_items WHERE id = ". $id) or die(mysql_error());
}	

/* Edit event */
	
function edit_item($id,$userid,$newCaption,$new_escription,$private=0,$color=0) {
		$SQL = mysql_query("SELECT * FROM cal_items WHERE id = ". $id );//" AND added_by = ". $userid);
		if (mysql_num_rows($SQL) > 0){ //|| getUserPermission($_SESSION['userid'],"allow_edit")) {
				mysql_query("UPDATE cal_items set caption = '". $newCaption ."', description = '". $new_escription ."', last_updated = NOW(), edited_by = ". $userid .", private = ". $private .", color = $color WHERE id = ". $id) or die(mysql_error());
				return true;
		} else {
				return false;
		}
}

/* Move event */
function move_item($id,$to)
{
	$SQL = mysql_query("SELECT * FROM cal_items WHERE id = ". $id ." AND added_by = ". $_SESSION['userid']);
		if (mysql_num_rows($SQL) > 0 || getUserPermission($_SESSION['userid'],"allow_edit")) 
		{
			mysql_query("UPDATE cal_items SET `date` = '$to' WHERE id = ". $id);
			return true;
		} else {
			return false;
		}
}
?>