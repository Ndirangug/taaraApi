-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 14, 2018 at 06:29 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taara`
--
CREATE DATABASE IF NOT EXISTS `taara` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `taara`;

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--
-- Creation: May 14, 2018 at 11:50 AM
--

CREATE TABLE `checkouts` (
  `checkoutID` bigint(255) NOT NULL,
  `amount` int(20) NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `checkouts`:
--   `userID`
--       `users` -> `userID`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--
-- Creation: May 14, 2018 at 11:25 AM
--

CREATE TABLE `product` (
  `barcode` varchar(50) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `product_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `product`:
--

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`barcode`, `product_title`, `product_description`) VALUES
('6161105100041', 'Daily Nation', 'Friday, May 4 2018'),
('6161105860327', 'Kartasi Exercise Book', '200 pages, ruled, A5 hardcover');

-- --------------------------------------------------------

--
-- Table structure for table `product_occurrence`
--
-- Creation: May 14, 2018 at 12:34 PM
--

CREATE TABLE `product_occurrence` (
  `occurrenceID` int(50) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `storeID` int(20) NOT NULL,
  `price` int(15) NOT NULL,
  `rfid` int(100) NOT NULL,
  `VAT` int(2) NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `checkoutID` bigint(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `product_occurrence`:
--   `storeID`
--       `stores` -> `storeID`
--   `barcode`
--       `product` -> `barcode`
--

--
-- Dumping data for table `product_occurrence`
--

INSERT INTO `product_occurrence` (`occurrenceID`, `barcode`, `storeID`, `price`, `rfid`, `VAT`, `paid`, `checkoutID`) VALUES
(41, '6161105100041', 2, 60, 5412, 16, 0, 0),
(42, '6161105100041', 3, 59, 2315, 16, 0, 0),
(43, '6161105100041', 4, 60, 24555, 16, 0, 0),
(44, '6161105100041', 5, 60, 115151, 16, 0, 0),
(45, '6161105860327', 2, 10, 4412, 16, 0, 0),
(46, '6161105860327', 3, 8, 454, 16, 0, 0),
(47, '6161105860327', 4, 9, 2151, 16, 1, 0),
(48, '6161105860327', 5, 10, 6515, 16, 0, 0),
(49, '6161105860327', 4, 60, 21521, 16, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--
-- Creation: May 14, 2018 at 11:31 AM
--

CREATE TABLE `stores` (
  `storeID` int(20) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `mpesa_till_number` varchar(10) NOT NULL,
  `supermarketID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `stores`:
--   `supermarketID`
--       `supermarkets` -> `supermarketID`
--

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`storeID`, `store_name`, `location`, `mpesa_till_number`, `supermarketID`) VALUES
(2, 'tuskys tom mboya', 'cbd', '9876', 20),
(3, 'Tuskys pioneer branch(moi avenue)', 'cbd', '5425', 20),
(4, 'the mall(westlands)', 'westlands', '5654', 19),
(5, 'buffalo mall(naivasha)', 'naivasha', '1215', 19);

-- --------------------------------------------------------

--
-- Table structure for table `supermarkets`
--
-- Creation: May 14, 2018 at 11:10 AM
--

CREATE TABLE `supermarkets` (
  `supermarketID` int(10) NOT NULL,
  `supermarket_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `supermarkets`:
--

--
-- Dumping data for table `supermarkets`
--

INSERT INTO `supermarkets` (`supermarketID`, `supermarket_name`) VALUES
(19, 'naivas'),
(20, 'tuskys');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: May 13, 2018 at 09:51 PM
--

CREATE TABLE `users` (
  `userID` int(255) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `first_name`, `last_name`, `email`, `phone`) VALUES
(5, 'George', 'Ndirangu', 'ndirangu.mepawa@outlook.com', '254746649576'),
(7, 'Wachira', 'Ndisho', 'ndirangu.mepawa@gmail.com', '254746649576');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`checkoutID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`barcode`);

--
-- Indexes for table `product_occurrence`
--
ALTER TABLE `product_occurrence`
  ADD PRIMARY KEY (`occurrenceID`),
  ADD UNIQUE KEY `rfid` (`rfid`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `storeID` (`storeID`) USING BTREE,
  ADD KEY `checkoutID` (`checkoutID`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`storeID`),
  ADD KEY `supermarketID` (`supermarketID`);

--
-- Indexes for table `supermarkets`
--
ALTER TABLE `supermarkets`
  ADD PRIMARY KEY (`supermarketID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `checkoutID` bigint(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_occurrence`
--
ALTER TABLE `product_occurrence`
  MODIFY `occurrenceID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `storeID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supermarkets`
--
ALTER TABLE `supermarkets`
  MODIFY `supermarketID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD CONSTRAINT `checkouts_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_occurrence`
--
ALTER TABLE `product_occurrence`
  ADD CONSTRAINT `product_occurrence_to_store` FOREIGN KEY (`storeID`) REFERENCES `stores` (`storeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `to_product` FOREIGN KEY (`barcode`) REFERENCES `product` (`barcode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `to_supermarkets` FOREIGN KEY (`supermarketID`) REFERENCES `supermarkets` (`supermarketID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
