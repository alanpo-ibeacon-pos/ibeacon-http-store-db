-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.21-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for attendance
CREATE DATABASE IF NOT EXISTS `attendance` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `attendance`;


-- Dumping structure for table attendance.attended
CREATE TABLE IF NOT EXISTS `attended` (
  `eid` mediumint(8) unsigned NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL,
  `first_detected` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_detected` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`eid`,`pid`),
  KEY `eid` (`eid`),
  KEY `pid` (`pid`),
  CONSTRAINT `fk_attended_eid_events` FOREIGN KEY (`eid`) REFERENCES `events` (`eid`),
  CONSTRAINT `fk_attended_pid_participaints` FOREIGN KEY (`pid`) REFERENCES `participants` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='`events` -- `participants_devices`';

-- Data exporting was unselected.


-- Dumping structure for table attendance.events
CREATE TABLE IF NOT EXISTS `events` (
  `eid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `locid` smallint(5) unsigned NOT NULL,
  `startDateTime` datetime NOT NULL,
  `endDateTime` datetime NOT NULL,
  PRIMARY KEY (`eid`),
  KEY `fk_events_locid_location` (`locid`),
  CONSTRAINT `fk_events_locid_location` FOREIGN KEY (`locid`) REFERENCES `location` (`locid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table attendance.location
CREATE TABLE IF NOT EXISTS `location` (
  `locid` smallint(5) unsigned NOT NULL,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`locid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table attendance.location_detector
CREATE TABLE IF NOT EXISTS `location_detector` (
  `locid` smallint(5) unsigned NOT NULL,
  `detectorMac` bigint(8) unsigned NOT NULL,
  PRIMARY KEY (`locid`,`detectorMac`),
  CONSTRAINT `fk_locationdetector_locid_location` FOREIGN KEY (`locid`) REFERENCES `location` (`locid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table attendance.participants
CREATE TABLE IF NOT EXISTS `participants` (
  `pid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table attendance.participant_devices
CREATE TABLE IF NOT EXISTS `participant_devices` (
  `pid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` binary(16) NOT NULL,
  `major` int(11) NOT NULL DEFAULT '0',
  `minor` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uuid`,`major`,`minor`),
  KEY `pid` (`pid`),
  CONSTRAINT `fk_participantdevices_pid_participants` FOREIGN KEY (`pid`) REFERENCES `participants` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- Data exporting was unselected.


-- Dumping database structure for ibeacon_traces
CREATE DATABASE IF NOT EXISTS `ibeacon_traces` /*!40100 DEFAULT CHARACTER SET ascii */;
USE `ibeacon_traces`;


-- Dumping structure for view ibeacon_traces.formatted
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `formatted` (
	`datetime` DATETIME NOT NULL,
	`strSelfMac` CHAR(17) NULL COLLATE 'ascii_general_ci',
	`strUuid` CHAR(36) NULL COLLATE 'ascii_general_ci',
	`major` INT(11) NOT NULL,
	`minor` INT(11) NOT NULL,
	`strMac` CHAR(17) NULL COLLATE 'ascii_general_ci',
	`txpower` INT(11) NOT NULL,
	`rssi` INT(11) NOT NULL
) ENGINE=MyISAM;


-- Dumping structure for function ibeacon_traces.strmac_from_ulong
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `strmac_from_ulong`(`intMac` BIGINT(8) UNSIGNED) RETURNS char(17) CHARSET ascii
    NO SQL
    DETERMINISTIC
    COMMENT 'converts a unsigned bigint(8) into formatted mac address string'
BEGIN
DECLARE mac CHAR(12);
SET mac = LPAD(HEX(intMac), 12, '0');
RETURN CONCAT(LEFT(mac, 2), ':', MID(mac, 3,2), ':', MID(mac, 5,2), ':', MID(mac, 7,2), ':', MID(mac, 9,2), ':', RIGHT(mac, 2));
END//
DELIMITER ;


-- Dumping structure for table ibeacon_traces.traces
CREATE TABLE IF NOT EXISTS `traces` (
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `selfMac` bigint(8) unsigned NOT NULL,
  `uuid` binary(16) NOT NULL,
  `major` int(11) NOT NULL,
  `minor` int(11) NOT NULL,
  `mac` bigint(8) unsigned NOT NULL,
  `txpower` int(11) NOT NULL,
  `rssi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- Data exporting was unselected.


-- Dumping structure for function ibeacon_traces.uuid_from_bin
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `uuid_from_bin`(`b` BINARY(16)) RETURNS char(36) CHARSET ascii
    NO SQL
    DETERMINISTIC
    COMMENT 'converts binary(16) into string formatted as uuid'
BEGIN
DECLARE hex CHAR(32);
SET hex = HEX(b);
RETURN CONCAT(LEFT(hex, 8), '-', MID(hex, 9,4), '-', MID(hex, 13,4), '-', MID(hex, 17,4), '-', RIGHT(hex, 12));
END//
DELIMITER ;


-- Dumping structure for view ibeacon_traces.formatted
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `formatted`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `formatted` AS SELECT `datetime`, strmac_from_ulong(selfMac) AS strSelfMac, uuid_from_bin(`uuid`) AS strUuid, major, minor, strmac_from_ulong(mac) AS strMac, txpower, rssi FROM traces ;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
