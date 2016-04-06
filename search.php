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
	make_header(lang('search'));
?>
<br>
<form name="form1" method="post" action="<?= basename($_SERVER['PHP_SELF']) ?>">
  <div align="center"><?= lang('search_query') ?>
    <input type="text" name="query" value="<?php if (isset($_POST['query'])) { echo $_POST['query']; } ?>">
    <input type="submit" name="Submit" value="<?= lang('search') ?>">
  </div>
</form>
<?php
	  echo $_SESSION['userid'];
	 if (isset($_POST['query'])) 
	 {
         //In search.php don't browse only personal items
         //by default admin is user id = 1 and can even see pvt events added by any other user but all other users can only see their own pvt events
	 $SQL = mysql_query("SELECT `date`, color, private, caption, description,id, added_by, edited_by, UNIX_TIMESTAMP(last_updated) as lastupdated 
						FROM cal_items 
						WHERE (private = 0 OR private = ". $_SESSION['userid'].") 
                        AND (caption LIKE '%". $_POST['query'] ."%' 
						OR description LIKE '%". $_POST['query'] ."%'
                        )
						ORDER BY `date` DESC"
						);
	  	if ( !hide() ) 
	  	{
			while ( $RS = mysql_fetch_array($SQL) )
			{
				show_event($RS);
			}
			echo "<center>". mysql_num_rows($SQL) ." ". lang('search_found') ."</center>";
	   	}
			
		if ( mysql_num_rows($SQL) <= 0 || hide() )
		{
			echo "<br><center>". lang('search_nomatch') ."</center><br>";
		}	
	} 
	?>
<br>
&nbsp;&nbsp;<a href="index.php"><img src="images/back.jpg" width="16" height="16" border="0" align="middle" alt="<?= lang('back_to_calendar') ?>">
<?= lang('back_to_calendar') ?>
</a>
<?php make_end_html() ?>
