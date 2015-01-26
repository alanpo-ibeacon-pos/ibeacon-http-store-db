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
-- Dumping data for table attendance.attended: ~1 rows (approximately)
/*!40000 ALTER TABLE `attended` DISABLE KEYS */;
INSERT INTO `attended` (`eid`, `pid`, `first_detected`, `last_detected`) VALUES
	(91, 205922, '2015-01-27 05:39:01', '2015-01-27 05:43:45');
/*!40000 ALTER TABLE `attended` ENABLE KEYS */;

-- Dumping data for table attendance.events: ~1 rows (approximately)
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`eid`, `name`, `locid`, `startDateTime`, `endDateTime`) VALUES
	(91, 'Event91', 1, '2015-01-27 00:00:00', '2015-01-27 06:00:00');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;

-- Dumping data for table attendance.location: ~1 rows (approximately)
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` (`locid`, `name`) VALUES
	(1, 'Location1');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;

-- Dumping data for table attendance.location_detector: ~1 rows (approximately)
/*!40000 ALTER TABLE `location_detector` DISABLE KEYS */;
INSERT INTO `location_detector` (`locid`, `detectorMac`) VALUES
	(1, 113780617483);
/*!40000 ALTER TABLE `location_detector` ENABLE KEYS */;

-- Dumping data for table attendance.participants: ~0 rows (approximately)
/*!40000 ALTER TABLE `participants` DISABLE KEYS */;
INSERT INTO `participants` (`pid`, `name`) VALUES
	(205922, '你啊媽個仔');
/*!40000 ALTER TABLE `participants` ENABLE KEYS */;

-- Dumping data for table attendance.participant_devices: ~1 rows (approximately)
/*!40000 ALTER TABLE `participant_devices` DISABLE KEYS */;
INSERT INTO `participant_devices` (`pid`, `uuid`, `major`, `minor`) VALUES
	(205922, _binary 0xE2C56DB5DFFB48D2B060D0F53F96E0, 0, 0);
/*!40000 ALTER TABLE `participant_devices` ENABLE KEYS */;

-- Dumping data for table ibeacon_traces.traces: ~0 rows (approximately)
/*!40000 ALTER TABLE `traces` DISABLE KEYS */;
INSERT INTO `traces` (`datetime`, `selfMac`, `uuid`, `major`, `minor`, `mac`, `txpower`, `rssi`) VALUES
	('2015-01-27 04:42:36', 113780617483, _binary 0xE2C56DB5DFFB48D2B060D0F53F96E0, 0, 0, 136780173000720, -58, -63),
	('2015-01-27 04:42:36', 113780617483, _binary 0xE2C56DB5DFFB48D2B060D0F53F96E0, 0, 0, 136780173000720, -58, -60);
/*!40000 ALTER TABLE `traces` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
