-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2022 at 12:12 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `journal_entry_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `je_headers`
--

CREATE TABLE `je_headers` (
  `JE_ID` int(11) NOT NULL,
  `JE_Number` int(11) NOT NULL,
  `JE_Date` date NOT NULL,
  `JE_Note` text NOT NULL,
  `JE_Totals_D` int(11) NOT NULL,
  `JE_Totals_C` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `je_lines`
--

CREATE TABLE `je_lines` (
  `JE_Line_ID` int(11) NOT NULL,
  `Account_ID` int(11) NOT NULL,
  `JE_ID` int(11) NOT NULL,
  `D_Amount` int(11) DEFAULT NULL,
  `C_Amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `master_accounts`
--

CREATE TABLE `master_accounts` (
  `Account_ID` int(11) NOT NULL,
  `Account_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `master_accounts`
--

INSERT INTO `master_accounts` (`Account_ID`, `Account_Name`) VALUES
(1, 'cash'),
(2, 'accounts receivable'),
(3, 'fixed assets'),
(4, 'current assets'),
(5, 'prepaid expenses'),
(6, 'long term loans'),
(7, 'owners equity'),
(8, 'capital'),
(9, 'unearned revenue'),
(10, 'accrued expenses');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `je_headers`
--
ALTER TABLE `je_headers`
  ADD PRIMARY KEY (`JE_ID`);

--
-- Indexes for table `je_lines`
--
ALTER TABLE `je_lines`
  ADD PRIMARY KEY (`JE_Line_ID`),
  ADD KEY `fk_je_header` (`JE_ID`),
  ADD KEY `fk_master_account` (`Account_ID`);

--
-- Indexes for table `master_accounts`
--
ALTER TABLE `master_accounts`
  ADD PRIMARY KEY (`Account_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `je_headers`
--
ALTER TABLE `je_headers`
  MODIFY `JE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `je_lines`
--
ALTER TABLE `je_lines`
  MODIFY `JE_Line_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `master_accounts`
--
ALTER TABLE `master_accounts`
  MODIFY `Account_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `je_lines`
--
ALTER TABLE `je_lines`
  ADD CONSTRAINT `fk_je_header` FOREIGN KEY (`JE_ID`) REFERENCES `je_headers` (`JE_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_master_account` FOREIGN KEY (`Account_ID`) REFERENCES `master_accounts` (`Account_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
