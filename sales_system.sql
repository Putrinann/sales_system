-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:4306
-- Generation Time: May 13, 2025 at 10:02 PM
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
-- Database: `sales_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `size` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `stock`, `price`, `create_at`, `size`) VALUES
(1, 'NIKE Running Shoes', 45, 250000.00, '2025-05-13 03:23:52', '37,38,39,40'),
(2, 'SKECHERS Sneakers Basic', 53, 540000.00, '2025-05-13 03:23:52', '37,38,39,40'),
(3, 'Leather Boots', 14, 300000.00, '2025-05-13 03:23:52', '37,38,39,40'),
(4, 'CARVIL Sandals', 32, 150000.00, '2025-05-13 03:23:52', '37,38,39,40'),
(5, 'CONVERSE chuck taylor', 9, 450000.00, '2025-05-13 03:23:52', '38,39,40'),
(6, 'Vans Classic', 1, 520000.00, '2025-05-13 03:23:52', '38,39,40'),
(7, 'Dr. Martens Boots', 16, 1200000.00, '2025-05-13 03:23:52', '38,40'),
(8, 'High Heels', 29, 200000.00, '2025-05-14 00:06:48', '37,38,39,40'),
(9, 'Pantofel Office', 34, 150000.00, '2025-05-14 00:08:19', '37,38,39,40'),
(10, 'ADIDAS School', 16, 450000.00, '2025-05-14 00:09:40', '36,37,38,39,40'),
(11, 'Heels premium', 14, 480000.00, '2025-05-14 00:10:17', '38,39'),
(12, 'Kids Sandal', 61, 125000.00, '2025-05-14 00:11:55', '34,35,36');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `product_id`, `quantity`, `total_price`, `created_at`) VALUES
(1, '682259090747e', 3, 4, 1200000.00, '2025-05-13 03:24:41'),
(2, '682259090747e', 4, 2, 500000.00, '2025-05-13 03:24:41'),
(3, '682259090747e', 2, 1, 250000.00, '2025-05-13 03:24:41'),
(4, '682259337b49b', 3, 1, 300000.00, '2025-05-13 03:25:23'),
(5, '6822df16c9ba9', 2, 1, 250000.00, '2025-05-13 12:56:38'),
(6, '6822df16c9ba9', 5, 1, 450000.00, '2025-05-13 12:56:38'),
(7, '68237da9398af', 2, 1, 250000.00, '2025-05-14 00:13:13'),
(8, '68237da9398af', 3, 1, 300000.00, '2025-05-14 00:13:13'),
(9, '68237da9398af', 10, 1, 450000.00, '2025-05-14 00:13:13'),
(10, '68237e0e9ab86', 11, 1, 480000.00, '2025-05-14 00:14:54'),
(11, '68237e0e9ab86', 10, 3, 1350000.00, '2025-05-14 00:14:54'),
(12, '68237e32572e9', 12, 4, 500000.00, '2025-05-14 00:15:30'),
(13, '68237e32572e9', 4, 3, 450000.00, '2025-05-14 00:15:30'),
(14, '68237ed23a038', 2, 3, 750000.00, '2025-05-14 00:18:10'),
(15, '68237ed23a038', 6, 2, 1040000.00, '2025-05-14 00:18:10'),
(16, '68237ed23a038', 7, 2, 2400000.00, '2025-05-14 00:18:10'),
(17, '68238ca4473b3', 8, 1, 200000.00, '2025-05-14 01:17:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
