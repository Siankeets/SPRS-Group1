-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2025 at 05:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sprs_maindb`
--

-- --------------------------------------------------------

--
-- Table structure for table `eventparticipants`
--

CREATE TABLE `eventparticipants` (
  `participantID` int(11) NOT NULL,
  `eventID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `attended` tinyint(1) DEFAULT 0,
  `pointsEarned` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pointsledger`
--

CREATE TABLE `pointsledger` (
  `ledgerID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `pointsChange` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `changeDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--
/*!!! roles go away since new users table will have required info !!!*/
CREATE TABLE `roles` (
  `roleID` int(11) NOT NULL,
  `roleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleID`, `roleName`) VALUES
(1, 'student'),
(2, 'professor'),
(3, 'council'),
(4, 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `schoolevents`
--

CREATE TABLE `schoolevents` (
  `eventID` int(11) NOT NULL,
  `eventName` varchar(128) NOT NULL,
  `eventDescription` text DEFAULT NULL,
  `eventCreatorID` int(11) NOT NULL,
  `eventMinCap` int(11) NOT NULL,
  `eventMaxCap` int(11) NOT NULL,
  `eventStartDate` datetime NOT NULL,
  `eventEndDate` datetime NOT NULL,
  `eventCreationDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentprofiles`
--
/*!!! student profiles go away since new users table will have required info !!!*/
CREATE TABLE `studentprofiles` (
  `profileID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `yearLevel` int(11) DEFAULT NULL,
  `section` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `srCode` varchar(15) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `roleID` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `srCode`, `username`, `password`, `roleID`, `email`, `phone`) VALUES
(1, 'admin', 'admin', 'admin', 3, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  ADD PRIMARY KEY (`participantID`),
  ADD KEY `eventID` (`eventID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `pointsledger`
--
ALTER TABLE `pointsledger`
  ADD PRIMARY KEY (`ledgerID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleID`);

--
-- Indexes for table `schoolevents`
--
ALTER TABLE `schoolevents`
  ADD PRIMARY KEY (`eventID`),
  ADD KEY `eventCreatorID` (`eventCreatorID`);

--
-- Indexes for table `studentprofiles`
--
ALTER TABLE `studentprofiles`
  ADD PRIMARY KEY (`profileID`),
  ADD UNIQUE KEY `userID` (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `srCode` (`srCode`),
  ADD KEY `roleID` (`roleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  MODIFY `participantID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pointsledger`
--
ALTER TABLE `pointsledger`
  MODIFY `ledgerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schoolevents`
--
ALTER TABLE `schoolevents`
  MODIFY `eventID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `studentprofiles`
--
ALTER TABLE `studentprofiles`
  MODIFY `profileID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eventparticipants`
--
ALTER TABLE `eventparticipants`
  ADD CONSTRAINT `eventparticipants_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `schoolevents` (`eventID`),
  ADD CONSTRAINT `eventparticipants_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);

--
-- Constraints for table `pointsledger`
--
ALTER TABLE `pointsledger`
  ADD CONSTRAINT `pointsledger_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);

--
-- Constraints for table `schoolevents`
--
ALTER TABLE `schoolevents`
  ADD CONSTRAINT `schoolevents_ibfk_1` FOREIGN KEY (`eventCreatorID`) REFERENCES `users` (`id`);

--
-- Constraints for table `studentprofiles`
--
ALTER TABLE `studentprofiles`
  ADD CONSTRAINT `studentprofiles_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`roleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
