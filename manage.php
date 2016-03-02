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
	if (!isset($_GET['stamp']))
		notice(lang('error_missing_arg'));
	
	if (isset($_POST['edit'],$_GET['id'])) {
	
		edit_item($_GET['id'],$_SESSION['userid'],$_POST['caption'],$_POST['description'],$_POST['private'],$_POST['color']);
		header("Location: manage.php?stamp=". $_GET['stamp']);
	
	} elseif (isset($_GET['delete'])) {
		
		delete_item($_GET['delete']);
		header("Location: manage.php?stamp=". $_GET['stamp']);
	
	} elseif (isset($_POST['move'])) {
	
		move_item($_GET['id'],$_POST['move']);
		header("Location: manage.php?stamp=". $_GET['stamp']);		
	
	} elseif (isset($_POST['add'])) {

		new_item($_GET['stamp'],$_SESSION['userid'],$_POST['caption'],$_POST['description'],$_POST['private'],$_POST['color']);
		header("Location: manage.php?stamp=". $_GET['stamp']);
	
	}
	make_template_header();
	make_header("<br>". ftime ("%A, %B %d. - %Y",$_GET['stamp']));
	
		if (isset($_GET['edit'])) {
			$SQL = mysql_query("SELECT color, private, caption, description, id FROM cal_items WHERE id = ". $_GET['edit']);
			$RS = mysql_fetch_array($SQL);
		}
		
	if ( isset($_GET['edit']) || isset($_GET['add']) )
	{

	 ?>

<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <form name="managerform" method="post" action="<?= basename($_SERVER['PHP_SELF']) ?>?stamp=<?php echo $_GET['stamp'] . ( isset($_GET['edit']) ? '&id='. $RS['id'] : '') ?>">
        <table border="0" align="center" cellpadding="2" cellspacing="0">
          <tr align="left" valign="top">
            <td colspan="2"><strong><font size="2" face="Verdana">
              <?= lang('edit_event') ?>
              </font></strong></td>
          </tr>
          <tr align="left" valign="top">
            <td><font size="2" face="Verdana">
              <?= lang('caption') ?>
              </font></td>
            <td><font size="2" face="Verdana">
              <input name="caption" type="text" size="61" value="<?= @$RS['caption'] ?>" style="width:330px">
              </font></td>
          </tr>
          <tr align="left" valign="top">
            <td><font size="2" face="Verdana">
              <?= lang('description') ?>
              </font></td>
            <td><font size="2" face="Verdana">
              <textarea name="description" cols="60" rows="6" style="width:330px"><?= @$RS['description'] ?>
</textarea>
              </font></td>
          </tr>
          <tr align="left" valign="top">
            <td valign="middle"><font size="2">
              <?= lang('private') ?>
?</font></td>
            <td valign="middle"><font size="2">
              <select name="private">
                <option value="0" <?= ( (@$RS['private'] == 0) ? ' SELECTED' : '') ?>>
                <?= lang('private_all') ?>
                </option>
                <option value="<?= $_SESSION['userid'] ?>" <?= ( (@$RS['private'] != 0) ? ' SELECTED' : '') ?>>
                <?= lang('private_only_you') ?>
                </option>
              </select>
            </font></td>
          </tr>
          <tr align="left" valign="top">
            <td valign="middle"><font size="2">
              <?= lang('color') ?>
?</font></td>
            <td valign="middle"><font size="2">
              <select name="color">
                <?php
		foreach($config['colors'] as $id => $item)
		{
			list($color,$name,$desc) = $item;
			
          	printf("<option value=\"". $id ."\"%s>". $desc ." - (". $name .")</option>",
					($RS['color'] == $id) ? " SELECTED" : ""
				);
		}
		?>
              </select>
            </font></td>
          </tr>
          <tr align="left" valign="top">
            <td colspan="2"><font size="2">              <font size="1">
              <?= lang('special_info') ?>
            </font> </font></td>
          </tr>
          <tr align="left" valign="top">
            <td colspan="2"><font size="2">
              <input name="<?= ( isset($_GET['edit']) ? 'edit' : 'add') ?>" type="submit" id="edit" value="<?= ( isset($_GET['edit']) ? lang('edit_event') : lang('add_new') )?>">
              </font></td>
          </tr>
        </table>
      </form>
    </td>
    <?php if ( isset($_GET['edit']) )  { ?>
    <td valign="top">
      <table border="0" align="center" cellpadding="2" cellspacing="0">
        <tr align="left" valign="top">
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
            <?= lang('move_event') ?>
            </strong></font></td>
        </tr>
        <tr align="left" valign="top">
          <td>
            <form name="form1" method="post" action="<?= basename($_SERVER['PHP_SELF']) . "?stamp=". $_GET['stamp'] ."&id=". $RS['id'] ?>">
              <?php

	  echo "<select name=\"move\">";
	  for ($i = date("j",$_GET['stamp']) - 15; $i <= date("j",$_GET['stamp']) + 15; $i++) 
	  {
	  	$dt_stamp = mktime(0,0,0,date("n",$_GET['stamp']),$i,date("Y",$_GET['stamp']));
		printf("<option value=\"". $dt_stamp ."\"%s>". ftime("%a, %d, %b",$dt_stamp) ."</option>"
				,($_GET['stamp'] == $dt_stamp) ? " SELECTED" : "");
	  }
	  echo "</select>";
	  ?>
              <br>
              <input type="submit" name="Submit" value="<?= lang('move_event') ?>">
            </form>
          </td>
        </tr>
      </table>
    </td>
    <?php } ?>
  </tr>
</table>
<?php
}
	$SQL = mysql_query("SELECT `date`, color, private, caption, description,id, added_by, edited_by, UNIX_TIMESTAMP(last_updated) as lastupdated 
						FROM cal_items 
						WHERE `date` >= ". $_GET['stamp'] ." 
						AND `date` < ". ($_GET['stamp']+86400) ." 
						AND (private = ". $_SESSION['userid'] ."  OR private = 0)"
						);
	
	if (!hide()) 
	{
		while ($RS = mysql_fetch_array($SQL))
		{
			show_event($RS);
		}
			
		if ( mysql_num_rows($SQL) <= 0 && !hide() )
		{
			echo "<br><center>". lang('no_items') ."</center><br>";
		}
	} 
	
	elseif ( hide() )
	{
		echo "<br><center>". lang('items_hidden') ."</center><br>";
	}
		
	echo "<br />";
	
    if (getUserPermission($_SESSION['userid'],"allow_add"))
	{
		echo '&nbsp;&nbsp;<a href="manage.php?stamp='. $_GET['stamp'] .'&add=true"><img src="images/additem.jpg" width="16" height="16" border="0" align="middle" alt="Add new item"> '. lang('add_new_event') .'</a><br>'; 
	}
		echo '&nbsp;&nbsp;<a href="index.php?view='. $_GET['stamp'] .'"><img src="images/back.jpg" width="16" height="16" border="0" align="middle" alt="Back to index"> '. lang('back_to_calendar') .'</a>';

make_end_html() 
?>
