--
-- Table structure for table `city_descriptions`
--

CREATE TABLE `city_descriptions` (
  `CityID` int(8) NOT NULL AUTO_INCREMENT,
  `CityName` varchar(26) DEFAULT NULL,
  `StateProvCode` varchar(2) DEFAULT NULL,
  `CityDesc` text,
  PRIMARY KEY (`CityID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `warehouse_listing`
--

CREATE TABLE `warehouse_listing` (
  `ListingID` int(8) NOT NULL AUTO_INCREMENT,
  `Title` varchar(56) DEFAULT NULL,
  `SpaceNumber` varchar(43) DEFAULT NULL,
  `SpaceAvailable` varchar(12) DEFAULT NULL,
  `RentalRate` varchar(22) DEFAULT NULL,
  `MonthlyRate` float DEFAULT NULL,
  `StreetAddress` varchar(89) DEFAULT NULL,
  `CityName` varchar(26) DEFAULT NULL,
  `StateProvCode` varchar(2) DEFAULT NULL,
  `PostalCode` varchar(10) DEFAULT NULL,
  `LotSize` varchar(12) DEFAULT NULL,
  `LastUpdate` varchar(12) DEFAULT NULL,
  `SpaceAvailableMax` varchar(12) DEFAULT NULL,
  `SpaceAvailableMin` varchar(12) DEFAULT NULL,
  `PropertySubType` varchar(28) DEFAULT NULL,
  `PropertyType` varchar(15) DEFAULT NULL,
  `RentalRateMin` varchar(24) DEFAULT NULL,
  `SpaceAvailableTotal` varchar(12) DEFAULT NULL,
  `Description` text,
  `Latitude` varchar(28) DEFAULT NULL,
  `Longitude` varchar(29) DEFAULT NULL,
  `PhotoURL` varchar(200) DEFAULT NULL,
  `PhotoURL2` varchar(200) DEFAULT NULL,
  `PhotoURL3` varchar(200) DEFAULT NULL,
  `PhotoURL4` varchar(200) DEFAULT NULL,
  `PhotoURL5` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ListingID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
