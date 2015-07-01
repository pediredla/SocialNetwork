-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2015 at 08:05 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `SocialNetwork`
--

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE IF NOT EXISTS `Comment` (
`CommentID` int(10) NOT NULL,
  `PostID` int(10) NOT NULL,
  `CommentedBy` varchar(10) DEFAULT NULL,
  `Text` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Comment`
--

INSERT INTO `Comment` (`CommentID`, `PostID`, `CommentedBy`, `Text`) VALUES
(8, 4, 'test', 'hello'),
(9, 5, 'test', 'asdasd'),
(10, 4, 'test', 'qweeqwe'),
(11, 5, 'test', 'zxczxczxc'),
(12, 4, 'test', 'tyutyu'),
(13, 5, 'test', 'ghjghjghj'),
(14, 4, 'test', 'vbnvbmyk'),
(15, 5, 'test', '12312ascxzc'),
(16, 4, 'test', '12easdgeaszxc'),
(17, 5, 'test', 'asfwrweqdqer'),
(18, 4, 'test', NULL),
(19, 5, 'test', NULL),
(20, 4, 'test', 'qwasasd'),
(21, 5, 'test', NULL),
(22, 1, 'pediredla', 'erri puka'),
(23, 2, 'pediredla', NULL),
(24, 1, 'pediredla', 'sdasd'),
(25, 2, 'pediredla', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `DAY`
--

CREATE TABLE IF NOT EXISTS `DAY` (
`DayID` int(10) NOT NULL,
  `Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `DB_Group`
--

CREATE TABLE IF NOT EXISTS `DB_Group` (
`DBID` int(11) NOT NULL,
  `Role` varchar(20) NOT NULL,
  `Permissions` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `DB_Group`
--

INSERT INTO `DB_Group` (`DBID`, `Role`, `Permissions`) VALUES
(1, 'Normal User', ''),
(2, 'Admin', '{"admin":1 \r\n"moderator" :1}');

-- --------------------------------------------------------

--
-- Table structure for table `Friends`
--

CREATE TABLE IF NOT EXISTS `Friends` (
`FID` int(10) NOT NULL,
  `UserID` varchar(10) DEFAULT NULL,
  `FriendsWith` varchar(10) DEFAULT NULL,
  `Date` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Friends`
--

INSERT INTO `Friends` (`FID`, `UserID`, `FriendsWith`, `Date`) VALUES
(1, 'pediredla', 'batman', '2015-04-23 10:23:40'),
(2, 'batman', 'pediredla', '2015-04-23 10:23:40'),
(37, 'test', 'pediredla', '2015-04-24 09:59:17'),
(38, 'pediredla', 'test', '2015-04-24 09:59:17'),
(39, 'test', 'batman', '2015-04-24 09:59:24'),
(40, 'batman', 'test', '2015-04-24 09:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

CREATE TABLE IF NOT EXISTS `Groups` (
  `GroupID` int(10) NOT NULL,
  `GroupName` varchar(10) NOT NULL,
  `Type` varchar(10) NOT NULL,
  `Admin` varchar(10) DEFAULT NULL,
  `CreatedBy` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Likes`
--

CREATE TABLE IF NOT EXISTS `Likes` (
`LikeID` int(10) NOT NULL,
  `PostID` int(10) NOT NULL,
  `LikedBy` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Post`
--

CREATE TABLE IF NOT EXISTS `Post` (
`PostID` int(10) NOT NULL,
  `PostedBy` varchar(10) DEFAULT NULL,
  `RecievedBy` varchar(10) DEFAULT NULL,
  `Text` varchar(100) NOT NULL,
  `DateCreated` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Post`
--

INSERT INTO `Post` (`PostID`, `PostedBy`, `RecievedBy`, `Text`, `DateCreated`) VALUES
(1, 'pediredla', 'pediredla', 'Hello', '2015-04-24 00:04:34'),
(2, 'pediredla', 'pediredla', 'asdasd', '2015-04-24 00:11:57'),
(3, 'pediredla', 'batman', 'helasdasd', '2015-04-24 00:12:17'),
(4, 'test', 'test', 'Lanjakodaka edi test', '2015-04-24 06:34:55'),
(5, 'test', 'test', 'erri puka', '2015-04-24 06:36:19');

-- --------------------------------------------------------

--
-- Table structure for table `Requests`
--

CREATE TABLE IF NOT EXISTS `Requests` (
`ReqID` int(20) NOT NULL,
  `UserID` varchar(20) NOT NULL,
  `FriendsWith` varchar(20) NOT NULL,
  `Status` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Requests`
--

INSERT INTO `Requests` (`ReqID`, `UserID`, `FriendsWith`, `Status`) VALUES
(3, 'pediredla', 'test', 'Accepted'),
(4, 'pediredla', 'bane', 'Rejected'),
(5, 'batman', 'test', 'Accepted'),
(6, 'bane', 'test', 'Rejected');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
`node_id` int(10) NOT NULL,
  `UserID` varchar(10) CHARACTER SET utf16 NOT NULL,
  `EmailAddress` varchar(20) CHARACTER SET utf16 NOT NULL,
  `FirstName` varchar(10) CHARACTER SET utf16 DEFAULT NULL,
  `LastName` varchar(10) CHARACTER SET utf16 DEFAULT NULL,
  `Password` varchar(64) CHARACTER SET utf16 NOT NULL,
  `Gender` varchar(8) CHARACTER SET utf16 DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `Hometown` varchar(10) CHARACTER SET utf16 DEFAULT NULL,
  `CurrentTown` varchar(10) CHARACTER SET utf16 DEFAULT NULL,
  `Group` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12403 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`node_id`, `UserID`, `EmailAddress`, `FirstName`, `LastName`, `Password`, `Gender`, `DOB`, `Hometown`, `CurrentTown`, `Group`) VALUES
(12381, 'pediredla', 'anil@xyz.com', 'Anil', 'Pediredla', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Male', '1991-01-01', NULL, NULL, 1),
(12392, 'batman', 'batman@wayn1e.com', 'Batman', 'Batsy', '089542505d659cecbb988bb5ccff5bccf85be2dfa8c221359079aee2531298bb', 'Male', '1991-01-01', NULL, 'Arlington', 1),
(12393, 'test', 'test@test.com', 'Trail', 'test', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Male', '1991-01-01', 'Unknown', 'Unknown', 1),
(12402, 'bane', 'bane@xyz.com', 'Bane', 'None', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Male', '1978-09-03', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `User_session`
--

CREATE TABLE IF NOT EXISTS `User_session` (
`SessionID` int(11) NOT NULL COMMENT 'Keeps track of user''s sessions',
  `UserID` int(11) NOT NULL,
  `Hash` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
 ADD PRIMARY KEY (`CommentID`);

--
-- Indexes for table `DAY`
--
ALTER TABLE `DAY`
 ADD PRIMARY KEY (`DayID`);

--
-- Indexes for table `DB_Group`
--
ALTER TABLE `DB_Group`
 ADD PRIMARY KEY (`DBID`);

--
-- Indexes for table `Friends`
--
ALTER TABLE `Friends`
 ADD PRIMARY KEY (`FID`);

--
-- Indexes for table `Groups`
--
ALTER TABLE `Groups`
 ADD PRIMARY KEY (`GroupID`);

--
-- Indexes for table `Likes`
--
ALTER TABLE `Likes`
 ADD PRIMARY KEY (`LikeID`);

--
-- Indexes for table `Post`
--
ALTER TABLE `Post`
 ADD PRIMARY KEY (`PostID`);

--
-- Indexes for table `Requests`
--
ALTER TABLE `Requests`
 ADD PRIMARY KEY (`ReqID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
 ADD PRIMARY KEY (`node_id`), ADD UNIQUE KEY `User_id` (`node_id`), ADD UNIQUE KEY `UserID` (`UserID`), ADD UNIQUE KEY `EmailAddress` (`EmailAddress`);

--
-- Indexes for table `User_session`
--
ALTER TABLE `User_session`
 ADD PRIMARY KEY (`SessionID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
MODIFY `CommentID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `DAY`
--
ALTER TABLE `DAY`
MODIFY `DayID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `DB_Group`
--
ALTER TABLE `DB_Group`
MODIFY `DBID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Friends`
--
ALTER TABLE `Friends`
MODIFY `FID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `Likes`
--
ALTER TABLE `Likes`
MODIFY `LikeID` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
MODIFY `PostID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Requests`
--
ALTER TABLE `Requests`
MODIFY `ReqID` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
MODIFY `node_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12403;
--
-- AUTO_INCREMENT for table `User_session`
--
ALTER TABLE `User_session`
MODIFY `SessionID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Keeps track of user''s sessions';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
