-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2023 at 09:20 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `internal_evaluation`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblrules`
--

CREATE TABLE `tblrules` (
  `rule_id` varchar(64) NOT NULL,
  `teacher_id` varchar(64) NOT NULL,
  `subject_id` varchar(64) NOT NULL,
  `year` year(4) NOT NULL,
  `semester` enum('FALL','SPRING') DEFAULT NULL,
  `category` enum('THEORY','PRACTICAL') DEFAULT NULL,
  `rule` varchar(256) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `department` varchar(64) DEFAULT NULL,
  `section` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblrules`
--

INSERT INTO `tblrules` (`rule_id`, `teacher_id`, `subject_id`, `year`, `semester`, `category`, `rule`, `description`, `department`, `section`) VALUES
('25bacdd9-b114-4b2b-a5b5-fe0246266024', 'aa97-ba8a', '4409-9989', '2023', 'FALL', 'THEORY', 'attendance * 0.70 + ut * 1.20 + assginment * 1.20 + assessment * 1.20 * presentation * 0.5', 'weighted evaluation metric for the theory evaluation of web technology', 'Computer', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `tblvariables`
--

CREATE TABLE `tblvariables` (
  `rule_id` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value_type` varchar(64) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblvariables`
--

INSERT INTO `tblvariables` (`rule_id`, `name`, `value_type`, `description`) VALUES
('25bacdd9-b114-4b2b-a5b5-fe0246266024', 'attendance', 'float', ''),
('25bacdd9-b114-4b2b-a5b5-fe0246266024', 'ut', 'float', ''),
('25bacdd9-b114-4b2b-a5b5-fe0246266024', 'assginment', 'float', ''),
('25bacdd9-b114-4b2b-a5b5-fe0246266024', 'assessment', 'float', ''),
('25bacdd9-b114-4b2b-a5b5-fe0246266024', 'presentation', 'float', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblrules`
--
ALTER TABLE `tblrules`
  ADD UNIQUE KEY `teacher_id` (`teacher_id`,`subject_id`,`semester`,`year`,`category`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
