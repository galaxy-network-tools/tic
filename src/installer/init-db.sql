CREATE DATABASE  IF NOT EXISTS `tic` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tic`;
-- MySQL dump 10.13  Distrib 8.0.21, for macos10.15 (x86_64)
--
-- Host: 127.0.0.1    Database: tic
-- ------------------------------------------------------
-- Server version	5.7.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `gn4accounts`
--

DROP TABLE IF EXISTS `gn4accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `passwort` varchar(50) NOT NULL DEFAULT '',
  `session` varchar(50) DEFAULT NULL,
  `pwdandern` int(11) NOT NULL DEFAULT '1',
  `galaxie` int(11) NOT NULL DEFAULT '0',
  `planet` int(11) NOT NULL DEFAULT '0',
  `rang` int(11) NOT NULL DEFAULT '0',
  `allianz` int(11) NOT NULL DEFAULT '0',
  `authnick` varchar(20) NOT NULL DEFAULT '',
  `scantyp` int(11) NOT NULL DEFAULT '0',
  `svs` bigint(11) NOT NULL DEFAULT '0',
  `sbs` bigint(20) NOT NULL DEFAULT '0',
  `deff` int(11) NOT NULL DEFAULT '0',
  `unreadnews` int(11) NOT NULL DEFAULT '1',
  `lastlogin` varchar(20) NOT NULL DEFAULT '',
  `lastlogin_time` int(11) NOT NULL DEFAULT '0',
  `umod` varchar(21) NOT NULL DEFAULT '',
  `scans` bigint(20) NOT NULL DEFAULT '0',
  `spy` int(11) NOT NULL DEFAULT '0',
  `pwdStore` varchar(50) NOT NULL DEFAULT '',
  `handy` varchar(50) NOT NULL DEFAULT '',
  `messangerID` varchar(100) NOT NULL DEFAULT '',
  `infotext` varchar(50) NOT NULL DEFAULT '',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `zeitformat` varchar(8) NOT NULL DEFAULT 'hh:mm',
  `taktiksort` varchar(10) NOT NULL DEFAULT '0 asc',
  `help` int(1) NOT NULL DEFAULT '1',
  `tcausw` char(1) NOT NULL DEFAULT '1',
  `versuche` int(1) NOT NULL DEFAULT '0',
  `attplaner` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gn4allianzen`
--

DROP TABLE IF EXISTS `gn4allianzen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4allianzen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `tag` varchar(10) NOT NULL DEFAULT '',
  `info_bnds` varchar(50) NOT NULL DEFAULT '',
  `info_naps` varchar(50) NOT NULL DEFAULT '',
  `info_inoffizielle_naps` varchar(50) NOT NULL DEFAULT '',
  `info_kriege` varchar(50) NOT NULL DEFAULT '',
  `code` int(11) NOT NULL DEFAULT '0',
  `blind` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gn4attflotten`
--

DROP TABLE IF EXISTS `gn4attflotten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4attflotten` (
  `lfd` int(11) DEFAULT NULL,
  `id` int(11) DEFAULT NULL,
  `flottenr` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4attflotten`
--

LOCK TABLES `gn4attflotten` WRITE;
/*!40000 ALTER TABLE `gn4attflotten` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4attflotten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4attplanung`
--

DROP TABLE IF EXISTS `gn4attplanung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4attplanung` (
  `lfd` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `galaxie` int(11) DEFAULT NULL,
  `planet` int(11) DEFAULT NULL,
  `attdatum` date DEFAULT NULL,
  `attzeit` time DEFAULT NULL,
  `attstatus` int(11) DEFAULT '0',
  `freigabe` tinyint(4) DEFAULT '0',
  `info` varchar(255) DEFAULT NULL,
  `forall` tinyint(4) DEFAULT '0',
  `formeta` int(11) DEFAULT '0',
  `forallianz` int(11) DEFAULT '0',
  PRIMARY KEY (`lfd`),
  UNIQUE KEY `lfd` (`lfd`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4attplanung`
--

LOCK TABLES `gn4attplanung` WRITE;
/*!40000 ALTER TABLE `gn4attplanung` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4attplanung` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4channels`
--

DROP TABLE IF EXISTS `gn4channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4channels` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `channame` varchar(63) NOT NULL DEFAULT '',
  `joincommand` varchar(127) NOT NULL DEFAULT '',
  `pass` varchar(63) NOT NULL DEFAULT '',
  `ally` mediumint(9) NOT NULL DEFAULT '0',
  `metachan` tinyint(4) NOT NULL DEFAULT '0',
  `guard` tinyint(4) NOT NULL DEFAULT '0',
  `answer` tinyint(4) NOT NULL DEFAULT '0',
  `voicerang` tinyint(4) NOT NULL DEFAULT '-1',
  `oprang` tinyint(4) NOT NULL DEFAULT '2',
  `accessrang` tinyint(4) NOT NULL DEFAULT '0',
  `inviterang` tinyint(4) NOT NULL DEFAULT '0',
  `opcontrol` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4channels`
--

LOCK TABLES `gn4channels` WRITE;
/*!40000 ALTER TABLE `gn4channels` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4cron`
--

DROP TABLE IF EXISTS `gn4cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4cron` (
  `time` int(14) DEFAULT NULL,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `count` int(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4cron`
--

LOCK TABLES `gn4cron` WRITE;
/*!40000 ALTER TABLE `gn4cron` DISABLE KEYS */;
INSERT INTO `gn4cron` VALUES (1797101,'1',406742181);
/*!40000 ALTER TABLE `gn4cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4flottenbewegungen`
--

DROP TABLE IF EXISTS `gn4flottenbewegungen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4flottenbewegungen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `modus` int(11) NOT NULL DEFAULT '0',
  `angreifer_galaxie` int(11) NOT NULL DEFAULT '0',
  `angreifer_planet` int(11) NOT NULL DEFAULT '0',
  `verteidiger_galaxie` int(11) NOT NULL DEFAULT '0',
  `verteidiger_planet` int(11) NOT NULL DEFAULT '0',
  `save` char(1) NOT NULL DEFAULT '',
  `eta` int(11) NOT NULL DEFAULT '0',
  `flugzeit` int(11) NOT NULL DEFAULT '0',
  `flottennr` int(11) NOT NULL DEFAULT '0',
  `ankunft` int(14) NOT NULL DEFAULT '0',
  `flugzeit_ende` int(14) NOT NULL DEFAULT '0',
  `ruckflug_ende` int(14) NOT NULL DEFAULT '0',
  `tparser` tinyint(4) NOT NULL DEFAULT '0',
  `erfasser` varchar(50) NOT NULL DEFAULT '',
  `erfasst_am` varchar(55) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `start_koords` (`angreifer_galaxie`,`angreifer_planet`),
  KEY `ziel_koords` (`verteidiger_galaxie`,`verteidiger_planet`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4flottenbewegungen`
--

LOCK TABLES `gn4flottenbewegungen` WRITE;
/*!40000 ALTER TABLE `gn4flottenbewegungen` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4flottenbewegungen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4forum`
--

DROP TABLE IF EXISTS `gn4forum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `autorid` int(11) NOT NULL DEFAULT '0',
  `zeit` varchar(20) NOT NULL DEFAULT '',
  `belongsto` int(11) NOT NULL DEFAULT '0',
  `topic` varchar(50) NOT NULL DEFAULT '',
  `text` varchar(50) NOT NULL DEFAULT '',
  `allianz` int(11) NOT NULL DEFAULT '0',
  `priority` bigint(20) NOT NULL DEFAULT '0',
  `wichtig` int(11) NOT NULL DEFAULT '0',
  `lastpost` int(11) NOT NULL DEFAULT '0',
  `views` bigint(20) NOT NULL DEFAULT '0',
  `geandert` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4forum`
--

LOCK TABLES `gn4forum` WRITE;
/*!40000 ALTER TABLE `gn4forum` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4forum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4galfleetupdated`
--

DROP TABLE IF EXISTS `gn4galfleetupdated`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4galfleetupdated` (
  `gal` int(9) NOT NULL,
  `t` int(14) NOT NULL,
  `erfasser` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`gal`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4galfleetupdated`
--

LOCK TABLES `gn4galfleetupdated` WRITE;
/*!40000 ALTER TABLE `gn4galfleetupdated` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4galfleetupdated` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4gnuser`
--

DROP TABLE IF EXISTS `gn4gnuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4gnuser` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `gala` int(12) NOT NULL DEFAULT '0',
  `planet` int(12) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `kommentare` varchar(50) NOT NULL DEFAULT '',
  `erfasst` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_koords` (`gala`,`planet`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4gnuser`
--

LOCK TABLES `gn4gnuser` WRITE;
/*!40000 ALTER TABLE `gn4gnuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4gnuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4incplanets`
--

DROP TABLE IF EXISTS `gn4incplanets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4incplanets` (
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `planet` smallint(6) NOT NULL DEFAULT '0',
  `gala` smallint(6) NOT NULL DEFAULT '0',
  `bestaetigt` varchar(200) NOT NULL DEFAULT '',
  `vorgemerkt` varchar(200) NOT NULL DEFAULT '',
  `frei` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4incplanets`
--

LOCK TABLES `gn4incplanets` WRITE;
/*!40000 ALTER TABLE `gn4incplanets` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4incplanets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4log`
--

DROP TABLE IF EXISTS `gn4log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `accid` int(11) NOT NULL DEFAULT '0',
  `rang` int(11) NOT NULL DEFAULT '0',
  `allianz` int(11) NOT NULL DEFAULT '0',
  `zeit` varchar(20) NOT NULL DEFAULT '',
  `aktion` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4log`
--

LOCK TABLES `gn4log` WRITE;
/*!40000 ALTER TABLE `gn4log` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4meta`
--

DROP TABLE IF EXISTS `gn4meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4meta` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `sysmsg` varchar(255) NOT NULL DEFAULT '',
  `bnds` varchar(255) NOT NULL DEFAULT '',
  `naps` varchar(255) NOT NULL DEFAULT '',
  `wars` varchar(255) NOT NULL DEFAULT '',
  `duell` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gn4nachrichten`
--

DROP TABLE IF EXISTS `gn4nachrichten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4nachrichten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '0',
  `titel` varchar(50) NOT NULL DEFAULT '',
  `zeit` varchar(20) NOT NULL DEFAULT '',
  `text` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4nachrichten`
--

LOCK TABLES `gn4nachrichten` WRITE;
/*!40000 ALTER TABLE `gn4nachrichten` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4nachrichten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4nachtwache`
--

DROP TABLE IF EXISTS `gn4nachtwache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4nachtwache` (
  `time` int(11) NOT NULL DEFAULT '0',
  `ticid` tinyint(4) NOT NULL DEFAULT '0',
  `gala` int(11) NOT NULL DEFAULT '0',
  `planet1` tinyint(2) NOT NULL DEFAULT '0',
  `done1` enum('0','1') NOT NULL DEFAULT '0',
  `planet2` tinyint(2) NOT NULL DEFAULT '0',
  `done2` enum('0','1') NOT NULL DEFAULT '0',
  `planet3` tinyint(2) NOT NULL DEFAULT '0',
  `done3` enum('0','1') NOT NULL DEFAULT '0',
  `planet4` tinyint(2) NOT NULL DEFAULT '0',
  `done4` enum('0','1') NOT NULL DEFAULT '0',
  `planet5` tinyint(2) NOT NULL DEFAULT '0',
  `done5` enum('0','1') NOT NULL DEFAULT '0',
  `planet6` tinyint(2) NOT NULL DEFAULT '0',
  `done6` enum('0','1') NOT NULL DEFAULT '0',
  `planet7` tinyint(2) NOT NULL DEFAULT '0',
  `done7` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`time`,`gala`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4nachtwache`
--

LOCK TABLES `gn4nachtwache` WRITE;
/*!40000 ALTER TABLE `gn4nachtwache` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4nachtwache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4scanblock`
--

DROP TABLE IF EXISTS `gn4scanblock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4scanblock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `g` int(5) DEFAULT NULL,
  `p` int(5) DEFAULT NULL,
  `t` int(11) DEFAULT NULL,
  `svs` int(6) DEFAULT NULL,
  `sg` int(5) DEFAULT NULL,
  `sp` int(5) DEFAULT NULL,
  `sname` varchar(255) DEFAULT NULL,
  `typ` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4scanblock`
--

LOCK TABLES `gn4scanblock` WRITE;
/*!40000 ALTER TABLE `gn4scanblock` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4scanblock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4scans`
--

DROP TABLE IF EXISTS `gn4scans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4scans` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `zeit` varchar(20) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL DEFAULT '0',
  `g` int(11) NOT NULL DEFAULT '0',
  `p` int(11) NOT NULL DEFAULT '0',
  `rg` int(11) NOT NULL DEFAULT '0',
  `rp` int(11) NOT NULL DEFAULT '0',
  `gen` int(11) NOT NULL DEFAULT '0',
  `pts` decimal(10,0) NOT NULL DEFAULT '0',
  `s` int(11) NOT NULL DEFAULT '0',
  `d` int(11) NOT NULL DEFAULT '0',
  `me` int(11) NOT NULL DEFAULT '0',
  `ke` int(11) NOT NULL DEFAULT '0',
  `a` int(11) NOT NULL DEFAULT '0',
  `sf0j` bigint(20) NOT NULL DEFAULT '0',
  `sf0b` bigint(20) NOT NULL DEFAULT '0',
  `sf0f` bigint(20) NOT NULL DEFAULT '0',
  `sf0z` bigint(20) NOT NULL DEFAULT '0',
  `sf0kr` bigint(20) NOT NULL DEFAULT '0',
  `sf0sa` bigint(20) NOT NULL DEFAULT '0',
  `sf0t` bigint(20) NOT NULL DEFAULT '0',
  `sf0ko` bigint(20) NOT NULL DEFAULT '0',
  `sf0ka` bigint(20) NOT NULL DEFAULT '0',
  `sf0su` bigint(20) NOT NULL DEFAULT '0',
  `sf1j` bigint(20) NOT NULL DEFAULT '0',
  `sf1b` bigint(20) NOT NULL DEFAULT '0',
  `sf1f` bigint(20) NOT NULL DEFAULT '0',
  `sf1z` bigint(20) NOT NULL DEFAULT '0',
  `sf1kr` bigint(20) NOT NULL DEFAULT '0',
  `sf1sa` bigint(20) NOT NULL DEFAULT '0',
  `sf1t` bigint(20) NOT NULL DEFAULT '0',
  `sf1ko` bigint(20) NOT NULL DEFAULT '0',
  `sf1ka` bigint(20) NOT NULL DEFAULT '0',
  `sf1su` bigint(20) NOT NULL DEFAULT '0',
  `status1` int(11) NOT NULL DEFAULT '0',
  `ziel1` varchar(20) NOT NULL DEFAULT '',
  `sf2j` bigint(20) NOT NULL DEFAULT '0',
  `sf2b` bigint(20) NOT NULL DEFAULT '0',
  `sf2f` bigint(20) NOT NULL DEFAULT '0',
  `sf2z` bigint(20) NOT NULL DEFAULT '0',
  `sf2kr` bigint(20) NOT NULL DEFAULT '0',
  `sf2sa` bigint(20) NOT NULL DEFAULT '0',
  `sf2t` bigint(20) NOT NULL DEFAULT '0',
  `sf2ko` bigint(20) NOT NULL DEFAULT '0',
  `sf2ka` bigint(20) NOT NULL DEFAULT '0',
  `sf2su` bigint(20) NOT NULL DEFAULT '0',
  `status2` int(11) NOT NULL DEFAULT '0',
  `ziel2` varchar(20) NOT NULL DEFAULT '',
  `sfj` bigint(20) NOT NULL DEFAULT '0',
  `sfb` bigint(20) NOT NULL DEFAULT '0',
  `sff` bigint(20) NOT NULL DEFAULT '0',
  `sfz` bigint(20) NOT NULL DEFAULT '0',
  `sfkr` bigint(20) NOT NULL DEFAULT '0',
  `sfsa` bigint(20) NOT NULL DEFAULT '0',
  `sft` bigint(20) NOT NULL DEFAULT '0',
  `sfko` bigint(20) NOT NULL DEFAULT '0',
  `sfka` bigint(20) NOT NULL DEFAULT '0',
  `sfsu` bigint(20) NOT NULL DEFAULT '0',
  `glo` bigint(20) NOT NULL DEFAULT '0',
  `glr` bigint(20) NOT NULL DEFAULT '0',
  `gmr` bigint(20) NOT NULL DEFAULT '0',
  `gsr` bigint(20) NOT NULL DEFAULT '0',
  `ga` bigint(20) NOT NULL DEFAULT '0',
  `gr` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `scan_koords` (`rg`,`rp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4scans`
--

LOCK TABLES `gn4scans` WRITE;
/*!40000 ALTER TABLE `gn4scans` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4scans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4scans_history`
--

DROP TABLE IF EXISTS `gn4scans_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4scans_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ticid` varchar(5) NOT NULL DEFAULT '',
  `zeit` varchar(20) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL DEFAULT '0',
  `g` int(11) NOT NULL DEFAULT '0',
  `p` int(11) NOT NULL DEFAULT '0',
  `rg` int(11) NOT NULL DEFAULT '0',
  `rp` int(11) NOT NULL DEFAULT '0',
  `gen` int(11) NOT NULL DEFAULT '0',
  `pts` decimal(10,0) NOT NULL DEFAULT '0',
  `s` int(11) NOT NULL DEFAULT '0',
  `d` int(11) NOT NULL DEFAULT '0',
  `me` int(11) NOT NULL DEFAULT '0',
  `ke` int(11) NOT NULL DEFAULT '0',
  `a` int(11) NOT NULL DEFAULT '0',
  `sf0j` bigint(20) NOT NULL DEFAULT '0',
  `sf0b` bigint(20) NOT NULL DEFAULT '0',
  `sf0f` bigint(20) NOT NULL DEFAULT '0',
  `sf0z` bigint(20) NOT NULL DEFAULT '0',
  `sf0kr` bigint(20) NOT NULL DEFAULT '0',
  `sf0sa` bigint(20) NOT NULL DEFAULT '0',
  `sf0t` bigint(20) NOT NULL DEFAULT '0',
  `sf0ko` bigint(20) NOT NULL DEFAULT '0',
  `sf0ka` bigint(20) NOT NULL DEFAULT '0',
  `sf0su` bigint(20) NOT NULL DEFAULT '0',
  `sf1j` bigint(20) NOT NULL DEFAULT '0',
  `sf1b` bigint(20) NOT NULL DEFAULT '0',
  `sf1f` bigint(20) NOT NULL DEFAULT '0',
  `sf1z` bigint(20) NOT NULL DEFAULT '0',
  `sf1kr` bigint(20) NOT NULL DEFAULT '0',
  `sf1sa` bigint(20) NOT NULL DEFAULT '0',
  `sf1t` bigint(20) NOT NULL DEFAULT '0',
  `sf1ko` bigint(20) NOT NULL DEFAULT '0',
  `sf1ka` bigint(20) NOT NULL DEFAULT '0',
  `sf1su` bigint(20) NOT NULL DEFAULT '0',
  `status1` int(11) NOT NULL DEFAULT '0',
  `ziel1` varchar(20) NOT NULL DEFAULT '',
  `sf2j` bigint(20) NOT NULL DEFAULT '0',
  `sf2b` bigint(20) NOT NULL DEFAULT '0',
  `sf2f` bigint(20) NOT NULL DEFAULT '0',
  `sf2z` bigint(20) NOT NULL DEFAULT '0',
  `sf2kr` bigint(20) NOT NULL DEFAULT '0',
  `sf2sa` bigint(20) NOT NULL DEFAULT '0',
  `sf2t` bigint(20) NOT NULL DEFAULT '0',
  `sf2ko` bigint(20) NOT NULL DEFAULT '0',
  `sf2ka` bigint(20) NOT NULL DEFAULT '0',
  `sf2su` bigint(20) NOT NULL DEFAULT '0',
  `status2` int(11) NOT NULL DEFAULT '0',
  `ziel2` varchar(20) NOT NULL DEFAULT '',
  `sfj` bigint(20) NOT NULL DEFAULT '0',
  `sfb` bigint(20) NOT NULL DEFAULT '0',
  `sff` bigint(20) NOT NULL DEFAULT '0',
  `sfz` bigint(20) NOT NULL DEFAULT '0',
  `sfkr` bigint(20) NOT NULL DEFAULT '0',
  `sfsa` bigint(20) NOT NULL DEFAULT '0',
  `sft` bigint(20) NOT NULL DEFAULT '0',
  `sfko` bigint(20) NOT NULL DEFAULT '0',
  `sfka` bigint(20) NOT NULL DEFAULT '0',
  `sfsu` bigint(20) NOT NULL DEFAULT '0',
  `glo` bigint(20) NOT NULL DEFAULT '0',
  `glr` bigint(20) NOT NULL DEFAULT '0',
  `gmr` bigint(20) NOT NULL DEFAULT '0',
  `gsr` bigint(20) NOT NULL DEFAULT '0',
  `ga` bigint(20) NOT NULL DEFAULT '0',
  `gr` bigint(20) NOT NULL DEFAULT '0',
  `erfasser_svs` int(11) DEFAULT NULL,
  `erfasser` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scan_koords` (`rg`,`rp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4scans_history`
--

LOCK TABLES `gn4scans_history` WRITE;
/*!40000 ALTER TABLE `gn4scans_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `gn4scans_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gn4vars`
--

DROP TABLE IF EXISTS `gn4vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gn4vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(50) NOT NULL DEFAULT '',
  `ticid` varchar(5) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gn4vars`
--

LOCK TABLES `gn4vars` WRITE;
/*!40000 ALTER TABLE `gn4vars` DISABLE KEYS */;
INSERT INTO `gn4vars` VALUES (1,'lastscanclean','21:15 02.04.2021','1'),(2,'forumpriority','0','1'),(3,'lasttick','21:15:00','1'),(4,'style','../gnstyle','1'),(5,'attplaner','aktiv','1'),(6,'botpw','','1'),(7,'tickdauer','15','1');
/*!40000 ALTER TABLE `gn4vars` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

DROP TRIGGER IF EXISTS `history_i`;
CREATE TRIGGER `history_i` AFTER INSERT ON `gn4scans`
 FOR EACH ROW insert into gn4scans_history (
	ticid,
	zeit,
	type,
	g,
	p,
	rg,
	rp,
	gen,
	pts,
	s,
	d,
	me,
	ke,
	a,
	sf0j,
	sf0b,
	sf0f,
	sf0z,
	sf0kr,
	sf0sa,
	sf0t,
	sf0ko,
	sf0ka,
	sf0su,
	sf1j,
	sf1b,
	sf1f,
	sf1z,
	sf1kr,
	sf1sa,
	sf1t,
	sf1ko,
	sf1ka,
	sf1su,
	status1,
	ziel1,
	sf2j,
	sf2b,
	sf2f,
	sf2z,
	sf2kr,
	sf2sa,
	sf2t,
	sf2ko,
	sf2ka,
	sf2su,
	status2,
	ziel2,
	sfj,
	sfb,
	sff,
	sfz,
	sfkr,
	sfsa,
	sft,
	sfko,
	sfka,
	sfsu,
	glo,
	glr,
	gmr,
	gsr,
	ga,
	gr,
	erfasser_svs,
	erfasser)
VALUES(
	NEW.ticid,
	NEW.zeit,
	NEW.type,
	NEW.g,
	NEW.p,
	NEW.rg,
	NEW.rp,
	NEW.gen,
	NEW.pts,
	NEW.s,
	NEW.d,
	NEW.me,
	NEW.ke,
	NEW.a,
	NEW.sf0j,
	NEW.sf0b,
	NEW.sf0f,
	NEW.sf0z,
	NEW.sf0kr,
	NEW.sf0sa,
	NEW.sf0t,
	NEW.sf0ko,
	NEW.sf0ka,
	NEW.sf0su,
	NEW.sf1j,
	NEW.sf1b,
	NEW.sf1f,
	NEW.sf1z,
	NEW.sf1kr,
	NEW.sf1sa,
	NEW.sf1t,
	NEW.sf1ko,
	NEW.sf1ka,
	NEW.sf1su,
	NEW.status1,
	NEW.ziel1,
	NEW.sf2j,
	NEW.sf2b,
	NEW.sf2f,
	NEW.sf2z,
	NEW.sf2kr,
	NEW.sf2sa,
	NEW.sf2t,
	NEW.sf2ko,
	NEW.sf2ka,
	NEW.sf2su,
	NEW.status2,
	NEW.ziel2,
	NEW.sfj,
	NEW.sfb,
	NEW.sff,
	NEW.sfz,
	NEW.sfkr,
	NEW.sfsa,
	NEW.sft,
	NEW.sfko,
	NEW.sfka,
	NEW.sfsu,
	NEW.glo,
	NEW.glr,
	NEW.gmr,
	NEW.gsr,
	NEW.ga,
	NEW.gr
	);

-- Dump completed on 2021-04-02 21:19:27
