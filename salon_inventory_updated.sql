-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 02:48 PM
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
-- Database: `salon_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_name`, `stock`, `price`) VALUES
(1, 'Shampoo - Red ', 29, 300.00),
(2, 'Shampoo - Blue', 31, 200.00),
(5, 'Conditioner - Yellow', 20, 150.00);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `report_content` text NOT NULL,
  `submitted_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `report_content`, `submitted_by`, `created_at`) VALUES
(1, 'skibidi', 'admin', '2025-05-08 10:05:42'),
(7, 'Hi', 'Faqmikoo', '2025-05-08 10:27:55'),
(8, 'Hi', 'Faqmikoo', '2025-05-08 10:30:31'),
(9, 'yow', 'Faqmikoo', '2025-05-08 10:30:37'),
(10, 'wassup', 'Faqmikoo', '2025-05-08 10:31:00'),
(11, 'Amememe', 'rin', '2025-05-08 10:31:39'),
(12, 'Amememe', 'rin', '2025-05-08 10:33:18'),
(15, '3 second', 'rin', '2025-05-08 10:34:57'),
(16, '3 second admin', 'Faqmikoo', '2025-05-08 10:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','superadmin','staff') NOT NULL DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(9, 'admin', '$2y$10$1f0qzC3cBvG5hW8c7tIUCOMsUU5tVQ8ZizGZPAd1Y9AJD19Bp8voe', 'admin', '2025-04-25 17:26:31'),
(12, 'superadmin', '$2y$10$tzd0/5Mr7ko5o1ZFxfMeKuRsrUCQ7/udtEVRb0iI4bRs4Xdlh48Qq', 'superadmin', '2025-04-30 16:33:13'),
(16, 'Faqmikoo', '$2y$10$pw/8TRfNRplU0Cbdozd3QOdufqhvpsBHXn0j6P.iopRfIyjMLv3R.', 'staff', '2025-05-07 13:53:26'),
(17, 'rin', '$2y$10$cReKToRrTq1Mou87y0br1uF3NtvaE8ThVrDDTs.tDN13eN68l3b5a', 'staff', '2025-05-08 08:52:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
