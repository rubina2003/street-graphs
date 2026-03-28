-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2025 at 06:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `streetgraphs`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$HREcXmPWDJ5hOF8qatXY4eM8W/Z7o9bOdR8RlpN7CtJI3hkZovs2a');

-- --------------------------------------------------------

--
-- Table structure for table `billing_info`
--

CREATE TABLE `billing_info` (
  `billing_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `print_size` varchar(20) NOT NULL,
  `print_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `payment_method` varchar(50) DEFAULT 'Cash on Delivery'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `print_size` varchar(20) DEFAULT NULL,
  `print_type` varchar(20) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `title`, `category`, `price`, `image_path`, `uploaded_at`, `stock`) VALUES
(4, 'Tabby Cat', 'animal', 1400.00, 'uploads/animal1.jpg', '2025-06-06 16:28:56', 0),
(6, 'White Rabbit', 'animal', 1350.00, 'uploads/animal3.jpg', '2025-06-06 16:28:56', 4),
(7, 'Pandas', 'animal', 1500.00, 'uploads/animal4.webp', '2025-06-06 16:28:56', 4),
(8, 'Persian Cat', 'animal', 1200.00, 'uploads/animal5.jpg', '2025-06-06 16:28:56', 9),
(9, 'Durbar Spire', 'heritage', 1300.00, 'uploads/heritage1.jpeg', '2025-06-06 16:28:56', 6),
(10, 'Temple Canopy', 'heritage', 1250.00, 'uploads/heritage2.png', '2025-06-06 16:28:56', 6),
(11, 'Flying Over Pagoda', 'heritage', 1400.00, 'uploads/heritage3.png', '2025-06-06 16:28:56', 8),
(12, 'Ason Shrine', 'heritage', 1380.00, 'uploads/heritage4.png', '2025-06-06 16:28:56', 4),
(13, 'Bhaktapur Bell', 'heritage', 1450.00, 'uploads/heritage5.png', '2025-06-06 16:28:56', 11),
(14, 'Stone Mandala', 'heritage', 1330.00, 'uploads/heritage6.png', '2025-06-06 16:28:56', 7),
(15, 'Rooftop Pagoda', 'heritage', 1425.00, 'uploads/heritage7.png', '2025-06-06 16:28:56', 5),
(16, 'Kathmandu Nights', 'heritage', 1470.00, 'uploads/heritage8.png', '2025-06-06 16:28:56', 10),
(17, 'Backpacker', 'people', 1000.00, 'uploads/people1.jpg', '2025-06-06 16:28:56', 9),
(18, 'Rainy Street Walker', 'people', 950.00, 'uploads/people2.jpg', '2025-06-06 16:28:56', 4),
(19, 'Mother and Child', 'people', 1100.00, 'uploads/people3.jpg', '2025-06-06 16:28:56', 6),
(20, 'Woman in Car', 'people', 980.00, 'uploads/people4.jpg', '2025-06-06 16:28:56', 10),
(21, 'Street Vendor', 'people', 1050.00, 'uploads/people5.jpg', '2025-06-06 16:28:56', 8),
(22, 'Couple in Rain', 'people', 1200.00, 'uploads/people6.jpg', '2025-06-06 16:28:56', 4),
(23, 'Flute Seller', 'people', 1250.00, 'uploads/people7.jpg', '2025-06-06 16:28:56', 6),
(24, 'Myna Bird', 'nature', 1600.00, 'uploads/nature1.jpg', '2025-06-06 16:28:56', 5),
(25, 'Pigeon Landing', 'nature', 1550.00, 'uploads/nature2.jpg', '2025-06-06 16:28:56', 7),
(26, 'Flying Pigeon', 'nature', 1700.00, 'uploads/nature3.jpg', '2025-06-06 16:28:56', 8),
(27, 'Mountain Peaks', 'nature', 1450.00, 'uploads/nature4.jpg', '2025-06-06 16:28:56', 6),
(28, 'Green Vines', 'nature', 1650.00, 'uploads/nature5.png', '2025-06-06 16:28:56', 8),
(29, 'Misty Hills', 'nature', 1500.00, 'uploads/nature6.png', '2025-06-06 16:28:56', 10),
(30, 'Fallen Leaves', 'nature', 1720.00, 'uploads/nature7.jpg', '2025-06-06 16:28:56', 4),
(31, 'Street Mural', 'street', 1550.00, 'uploads/street1.png', '2025-06-06 16:28:56', 8),
(32, 'Pizza Sign', 'street', 1320.00, 'uploads/street2.png', '2025-06-06 16:28:56', 7),
(33, 'Tangled Wires', 'street', 1380.00, 'uploads/street3.png', '2025-06-06 16:28:56', 6),
(34, 'Red Lanterns', 'street', 1280.00, 'uploads/street4.png', '2025-06-06 16:28:56', 5),
(35, 'Power Lines', 'street', 1420.00, 'uploads/street5.png', '2025-06-06 16:28:56', 9),
(36, 'Prayer Flags Alley', 'street', 1350.00, 'uploads/street6.png', '2025-06-06 16:28:56', 10),
(37, 'Art Gallery', 'street', 1470.00, 'uploads/street7.png', '2025-06-06 16:28:56', 6),
(38, 'Narrow Alley', 'street', 1300.00, 'uploads/street8.png', '2025-06-06 16:28:56', 8),
(39, 'No Entry Sign', 'street', 1220.00, 'uploads/street9.png', '2025-06-06 16:28:56', 4),
(40, 'Nepali Flag Alley', 'street', 1390.00, 'uploads/street10.png', '2025-06-06 16:28:56', 7),
(43, 'Bird Flying', 'animal', 1100.00, 'uploads/1749953712_nature8.jpg', '2025-06-15 02:15:12', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(3, 'Bikesh Maharjan', 'bikesh1@gmail.com', '$2y$10$RplnkhlS.XyVsnt7jesDb.UQLK3AqHOHPt/IRM9CKEi7vMGVcmsuS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `billing_info`
--
ALTER TABLE `billing_info`
  ADD PRIMARY KEY (`billing_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `fk_order_billing` (`order_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_order_items_photo` (`photo_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `billing_info`
--
ALTER TABLE `billing_info`
  MODIFY `billing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `billing_info`
--
ALTER TABLE `billing_info`
  ADD CONSTRAINT `billing_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_billing` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_photo` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
