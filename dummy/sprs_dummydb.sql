-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 05:50 AM
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
-- Database: `sprs_dummydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student','teacher') NOT NULL,
  `points` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `program` varchar(100) DEFAULT NULL,
  `major` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `points`, `name`, `department`, `program`, `major`) VALUES
(1, 'admin1', 'admin123', 'admin', NULL, 'Alice Dela Cruz', 'IT Department', NULL, NULL),
(2, 'admin2', 'admin456', 'admin', NULL, 'Bob Santos', 'Engineering Department', NULL, NULL),
(5, 'student1', 'stud123', 'student', 60, 'Juan Dela Cruz', 'Computer Science', 'BSCS', 'Software Development'),
(6, 'student2', 'stud456', 'student', 95, 'Maria Lopez', 'Engineering', 'BSEE', 'Power Systems'),
(7, 'student3', 'stud789', 'student', 80, 'Jose Lim', 'Business', 'BSBA', 'Marketing'),
(8, 'student4', 'stud101', 'student', 110, 'Anna Reyes', 'Computer Science', 'BSCS', 'Data Science'),
(9, 'student5', 'stud102', 'student', 75, 'Mark Tan', 'Engineering', 'BSEE', 'Electronics'),
(10, 'student6', 'stud103', 'student', 90, 'Sofia Cruz', 'Business', 'BSBA', 'Finance'),
(11, 'student7', 'stud104', 'student', 10, 'Leo Santos', 'Computer Science', 'BSCS', 'AI & Machine Learning'),
(12, 'student8', 'stud105', 'student', 85, 'Clara Mendoza', 'Engineering', 'BSEE', 'Robotics'),
(13, 'student9', 'stud106', 'student', 100, 'Rafael Gomez', 'Business', 'BSBA', 'Entrepreneurship'),
(14, 'student10', 'stud107', 'student', 70, 'Isabel Torres', 'Computer Science', 'BSCS', 'Cybersecurity');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
