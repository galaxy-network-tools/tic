DROP TABLE IF EXISTS `gn4log`;
CREATE TABLE `gn4log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type` tinyint(1) unsigned NOT NULL default '0',
  `ticid` tinyint(4) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `accid` int(11) NOT NULL default '0',
  `rang` int(11) NOT NULL default '0',
  `allianz` int(11) NOT NULL default '0',
  `zeit` varchar(19) NOT NULL default '0',
  `aktion` text NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;