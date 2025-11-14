-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 08:45 AM
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
  `major` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `points`, `name`, `department`, `program`, `major`, `contact_number`) VALUES
(1, 'admin1', 'admin111', 'admin', NULL, 'Alice Dela Cruz', 'IT Department', NULL, NULL, '09151045628'),
(2, 'admin2', 'admin456', 'admin', NULL, 'Bob Santos', 'Engineering Department', NULL, NULL, '09284561234'),
(5, 'student1', 'stud123', 'student', 60, 'Juan Dela Cruz', 'Computer Science', 'BSCS', 'Software Development', '09562347891'),
(6, 'student2', 'stud456', 'student', 95, 'Maria Lopez', 'Engineering', 'BSEE', 'Power Systems', '09671239845'),
(7, 'student3', 'stud789', 'student', 80, 'Jose Lim', 'Business', 'BSBA', 'Marketing', '09782345612'),
(8, 'student4', 'stud101', 'student', 110, 'Anna Reyes', 'Computer Science', 'BSCS', 'Data Science', '09893214567'),
(9, 'student5', 'stud102', 'student', 75, 'Mark Tan', 'Engineering', 'BSEE', 'Electronics', '09981236745'),
(10, 'student6', 'stud103', 'student', 90, 'Sofia Cruz', 'Business', 'BSBA', 'Finance', '09183456721'),
(11, 'student7', 'stud104', 'student', 10, 'Leo Santos', 'Computer Science', 'BSCS', 'AI & Machine Learning', '09276451239'),
(12, 'student8', 'stud105', 'student', 85, 'Clara Mendoza', 'Engineering', 'BSEE', 'Robotics', '09394567812'),
(13, 'student9', 'stud106', 'student', 100, 'Rafael Gomez', 'Business', 'BSBA', 'Entrepreneurship', '09486543219'),
(14, 'student10', 'stud107', 'student', 70, 'Isabel Torres', 'Computer Science', 'BSCS', 'Cybersecurity', '09573412986');

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
