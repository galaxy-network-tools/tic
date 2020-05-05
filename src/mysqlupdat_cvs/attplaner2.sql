CREATE TABLE `gn4attflotten` (
`lfd` int(11) default NULL,
`id` int(11) default NULL,
`flottenr` tinyint(4) default '1'
) TYPE=MyISAM;

CREATE TABLE `gn4attplanung` (
`lfd` int(11) NOT NULL auto_increment,
`id` int(11) default NULL,
`galaxie` int(11) default NULL,
`planet` int(11) default NULL,
`attdatum` date default NULL,
`attzeit` time default NULL,
`attstatus` int(11) default '0',
`freigabe` tinyint(4) default '0',
`info` varchar(255) default NULL,
`forall` tinyint(4) default '0',
`formeta` int(11) default '0',
`forallianz` int(11) default '0',
PRIMARY KEY (`lfd`),
UNIQUE KEY `lfd` (`lfd`)
) TYPE=MyISAM;

ALTER TABLE `gn4accounts` ADD `attplaner` TINYINT( 4 ) DEFAULT '0' NOT NULL ;