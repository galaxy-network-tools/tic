DROP TABLE IF EXISTS `gn4nachtwache`;
CREATE TABLE `gn4nachtwache` (
  `time` int(11) NOT NULL default '0',
  `ticid` tinyint(4) NOT NULL default '0',
  `gala` int(11) NOT NULL default '0',
  `planet1` tinyint(2) NOT NULL default '0',
  `done1` enum('0','1') NOT NULL default '0',
  `planet2` tinyint(2) NOT NULL default '0',
  `done2` enum('0','1') NOT NULL default '0',
  `planet3` tinyint(2) NOT NULL default '0',
  `done3` enum('0','1') NOT NULL default '0',
  `planet4` tinyint(2) NOT NULL default '0',
  `done4` enum('0','1') NOT NULL default '0',
  `planet5` tinyint(2) NOT NULL default '0',
  `done5` enum('0','1') NOT NULL default '0',
  `planet6` tinyint(2) NOT NULL default '0',
  `done6` enum('0','1') NOT NULL default '0',
  `planet7` tinyint(2) NOT NULL default '0',
  `done7` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`time`,`gala`)
) TYPE=MyISAM;