CREATE TABLE `gn4meta` (
  `id` tinyint(3) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `sysmsg` text NOT NULL,
  `bnds` varchar(255) NOT NULL default '',
  `naps` varchar(255) NOT NULL default '',
  `wars` varchar(255) NOT NULL default '',
  `duell` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


INSERT INTO gn4meta SELECT a.ticid as `id`, a.value as name, b.value as sysmsg, '' as bnds, '' as naps, '' as wars, null as duell FROM `gn4vars` as a LEFT JOIN `gn4vars` as b ON(a.ticid = b.ticid AND b.name='systemnachricht') WHERE a.name='ticeb'

# Zum aufräumen der vars:
# DELETE gn4vars WHERE name = 'ticeb' OR name = 'systemnachricht';