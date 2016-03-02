<?php

$SQL = array();

$SQL[] = <<<EOD
CREATE TABLE `cal_items` (
  `id` int(11) NOT NULL auto_increment,
  `date` tinytext NOT NULL,
  `caption` tinytext NOT NULL,
  `description` tinytext NOT NULL,
  `added_by` tinyint(3) NOT NULL default '0',
  `edited_by` tinyint(4) default '0',
  `private` int(11) NOT NULL default '0',
  `color` int(11) NOT NULL default '0',
  `last_updated` datetime default '0000-00-00 00:00:00',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;
EOD;

$SQL[] = <<<EOD
CREATE TABLE `cal_users` (
  `id` tinyint(4) NOT NULL auto_increment,
  `username` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `email` tinytext NOT NULL,
  `allow_delete` enum('true','false') NOT NULL default 'false',
  `allow_add` enum('true','false') NOT NULL default 'false',
  `allow_edit` enum('true','false') NOT NULL default 'false',
  `is_admin` enum('true','false') NOT NULL default 'false',
  `last_ip` tinytext,
  `last_login` datetime default '0000-00-00 00:00:00',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;
EOD;

$SQL[] = "INSERT INTO `cal_users` (`id`, `username`, `password`, `name`, `email`, `allow_delete`, `allow_add`, `allow_edit`, `is_admin`, `last_ip`, `last_login`) VALUES (1, 'admin', '63a9f0ea7bb98050796b649e85481845', 'Administrator', 'administrator@localhost', 'true', 'true', 'true', 'true', '127.0.0.1', NOW());";

foreach($SQL as $q)
	@mysql_query($q);

rename(__FILE__,__FILE__ . '.lock');
unset($SQL);
?>