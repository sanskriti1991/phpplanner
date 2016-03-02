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
	
	if ($config['show_hints'] && hide() == false) 
	{ 
		echo "<DIV ID=\"dek\" CLASS=\"dek\"></DIV>";
		echo "<script language=\"JavaScript\" src=\"hints.js\" type=\"text/JavaScript\"></script>";
	} 
	 
		   @$months .= sprintf("\t<option value=\"%s\">%s</option>\n"
					, mktime(0,0,0,12,1,$current['year']-1)
					, lang('last_year') ."..."
					);
					
	for ($i = 1; $i <= 12; $i++)
			$months .= sprintf("\t<option value=\"%s\"%s>%s</option>\n"
					, mktime(0,0,0,$i,1,$current['year'])
					, (date("n",mktime(0,0,0,$i,1,$current['year'])) == $current['month']) ? " selected" : ""
					, ftime("%B %Y", mktime(0,0,0,$i,1,$current['year']))
					);

			$months .= sprintf("\t<option value=\"%s\">%s</option>\n"
					, mktime(0,0,0,1,1,$current['year']+1)
					, lang('next_year') ."..."
					);


make_header($config['name'].'<p></p>
<form name="TopForm" action="'. basename($_SERVER['PHP_SELF']) .'" method="GET">
	<a href="index.php?view='. mktime(0,0,0,$current['month']-1,1,$current['year']) .'">&laquo;'. lang('previous') .'</a> 
	<select name="view" onChange="TopForm.submit()">
'. $months .'
	</select>
	<a href="index.php?view='. mktime(0,0,0,$current['month']+1,1,$current['year']) .'">'. lang('next') .'&raquo;</a><br>
</form>');

	echo '<table width="'. $config["table_width"] .'" border="0" align="center" cellpadding="0" cellspacing="1" class="blackBacking">';
	echo "\n<tr>";
	echo "\n<td align=\"center\" class=\"weekday-names\">". lang('week') ."</td>";
	echo "\n<td align=\"center\" class=\"weekday-names\">". lang('sunday') ."</td>";
	echo "\n<td align=\"center\" class=\"weekday-names\">". lang('monday') ."</td>";
	echo "\n<td align=\"center\" class=\"weekday-names\">". lang('tuesday') ."</td>";
	echo "\n<td align=\"center\" class=\"weekday-names\">". lang('wednesday') ."</td>";
	echo "\n<td align=\"center\" class=\"weekday-names\">". lang('thursday') ."</td>";
	echo "\n<td align=\"center\" class=\"weekday-names\">". lang('friday') ."</td>";
	echo "\n<td align=\"center\" class=\"weekday-names\">". lang('saturday') ."</td>";
	
  	
   	$current['first_day_of_month'] = date("w", mktime(0,0,0,$current['month'],1,$current['year']));
	$current['number_of_rows'] = ceil(($current['days_in_month']+$current['first_day_of_month'])/7);
	
	
	$SQL = mysql_query("SELECT color, caption, description, private, DAYOFMONTH(FROM_UNIXTIME(`date`-3000)) as `day` FROM cal_items 
						WHERE `date` >= ". mktime(0,0,0,$current['month'],1,$current['year']) ." 
							AND `date` < ". mktime(0,0,0,$current['month'],$current['days_in_month'],$current['year']) ." 
							AND (private = ". $_SESSION['userid'] ." OR private = 0)") or die(mysql_error());

	$events = array();
	while ( $rs = mysql_fetch_assoc($SQL) )
	{
		$events[$rs['day']][] = $rs;
	}
			

		
	for ($x = 0; $x <= 42; $x++)
	{
	
		/* We've run out of days and rows, lets stop then */
		if ($x >= ($current['first_day_of_month']+$current['days_in_month']) && $x % 7 == 0 )
		{
			break;
		}
		
		/* If we've printed 7 days, lets change week */
		if ( $x % 7 == 0 )
		{
			new_week($x);
		} 

		/* Display the first "empty" days of the month */
		if ( $x < $current['first_day_of_month'] )
		{
			wrap_event();
		}
		
		/* Display the last "empty" days of the month */
		elseif ( $x >= $current['first_day_of_month']+$current['days_in_month'] )
		{
			wrap_event();
		}
		
		/* Display the days of the month */
		else
		{
			if ( !isset($events[$x-$current['first_day_of_month']]) )
				new_empty_event($x);
			else
				new_event($x,$events[$x-$current['first_day_of_month']]);
		}
		

		
	}

echo "</tr>";
echo "</table>";
/*		
			
			
for ($i = 0; $i != $current['number_of_rows']; $i++) {
   	if ( !isset($day) ) 
	{
		$day = 1;
	}

	printf("\n</tr><tr>\n\n\t<td valign=\"top\" class=\"calweek\" width=\"39\" height=\"%d\">%d</td>\n\n"
			,$config['cell_height']
			,date("W",mktime(0,0,0,$current['month'],$day+1,$current['year']))
	);
    	
    	if ($i == 0)
		{
    		for ($col = 1; $col <= $current['first_day_of_month']; $col++)
			{
				echo "<td class=\"calNotDay\" height=\"". $config['cell_height'] ."\">&nbsp;</td>\n\n";
			}
		}

   		while ($day <= $current['days_in_month']) 
		{
					$col_date = mktime(0,0,0,$current['month'],$day,$current['year']);
					
					$SQL = mysql_query("SELECT color, caption, description, private FROM cal_items 
										WHERE `date` >= ". $col_date ." 
										AND `date` < ". mktime(0,0,0,$current['month'],$day+1,$current['year']) ." 
										AND (private = ". $_SESSION['userid'] ." OR private = 0)"
									  );
					
						$headlines = "";
						$full_headline = "";
						$color = 0;
						
					while ( $RS = mysql_fetch_array($SQL) ) 
					{
						$headlines .= ( !empty($RS['caption']) ) ? "<br />". substr(trim($RS['caption']),0,15) : "";
						
						$full_headline .= sprintf("<b>&raquo; %s%s%s</b><br>&nbsp;%s<br>"
								,FormatOutput($RS['caption'],false,true)
								,($RS['private'] == $_SESSION['userid'] && $_SESSION['userid'] != 0) ? " (private)" : ""
								,($RS['color'] != 0) ? " - <u>". ucwords($config['colors'][$RS['color']][2]) ."</u>" : ""
								,FormatOutput(wordwrap($RS['description'],75,"<br />&nbsp;", 1),false,true)
								);
								
						if ($RS['color'] > $color) 
							$color = $RS['color'];	
					}

					$hint = " onMouseOver = \"popup('". str_replace("\r\n","<br>&nbsp;",addslashes($full_headline)) ."');\" onMouseOut=\"kill()\"";
						
					printf("<td valign=\"top\" class=\"%s\" bgcolor=\"%s\" width=\"%d\" height=\"%d\">"
							,($col_date == mktime(0,0,0,date("n"),date("j"),date("Y"))) ? "calCurrentDay" : "calOtherDay"
							,$config['colors'][$color][0]
							,round(($config['table_width']-38)/7,0)
							,$config['cell_height']
					); 
					
					printf("<a href=\"manage.php?stamp=%d\"%s>%s&nbsp;%s {%d}"
							,$col_date
							,($headlines != "" && hide() == false) ? $hint : ""
							,($col_date == mktime(0,0,0,date("n"),date("j"),date("Y"))) ? '<img src="./images/today.gif" height="16" width="16" border="0" alt="'. lang('today') .'" align="$align">'. special_image($color) : special_image($color) ."&nbsp;"
							,ftime("%a %d",$col_date)
							,mysql_num_rows($SQL)
							
					);
						
					printf("%s</a></td>\n\n"
							,(hide() == true) ? "<br /><br /><center>". lang('hidden') ."</center>" : $headlines
					);
					
		
				
		$day++;
		if ((date("w", $col_date)+1) == 7) 
			break;
					
		} // end while
 
} // end for

for ($i = $day; ($i+$col-2) % 7 != 0; $i++)
	echo "<td class=\"calNotDay\" height=\"". $config['cell_height'] ."\">&nbsp;</td>\n\n";
*/


make_end_html() 
?>