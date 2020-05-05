-- phpMyAdmin SQL Dump
-- version 2.8.0.2-Debian-4
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 03, 2006 at 09:47 AM
-- Server version: 5.0.20
-- PHP Version: 5.1.2-1+b1
-- 
-- Database: `spqr`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `gn4channels`
-- 
-- Creation: Apr 20, 2006 at 02:51 PM
-- Last update: Apr 28, 2006 at 04:06 PM
-- 

DROP TABLE IF EXISTS `gn4channels`;
CREATE TABLE IF NOT EXISTS `gn4channels` (
  `id` mediumint(9) NOT NULL auto_increment,
  `channame` varchar(63) NOT NULL,
  `joincommand` varchar(127) NOT NULL,
  `pass` varchar(63) NOT NULL,
  `ally` mediumint(9) NOT NULL default '0',
  `metachan` tinyint(4) NOT NULL default '0',
  `guard` tinyint(4) NOT NULL default '0',
  `answer` tinyint(4) NOT NULL default '0',
  `voicerang` tinyint(4) NOT NULL default '-1',
  `oprang` tinyint(4) NOT NULL default '2',
  `accessrang` tinyint(4) NOT NULL default '0',
  `inviterang` tinyint(4) NOT NULL default '0',
  `opcontrol` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;
