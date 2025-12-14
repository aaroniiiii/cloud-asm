USE chaagee;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 02:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chaagee`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--
CREATE TABLE `category` (
 `category_id` VARCHAR(4) NOT NULL,
 `category_name` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--
INSERT INTO `category` (`category_id`, `category_name`) VALUES
('c001', 'Cold Coffee'),
('c002', 'Hot Coffee');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--
CREATE TABLE `orders` (
 `id` INT(11) NOT NULL,
 `date` DATETIME DEFAULT NULL,
 `customer_name` VARCHAR(100) DEFAULT NULL,
 `customer_email` VARCHAR(100) DEFAULT NULL,
 `total_unit` INT(11) DEFAULT NULL,
 `total_price` DECIMAL(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--
INSERT INTO `orders` (`id`, `date`, `customer_name`, `customer_email`, `total_unit`, `total_price`) VALUES
(8, '2025-11-25 18:27:01', 'by', '1@gmail.com', 1, 8.00),
(9, '2025-11-25 18:57:28', 'by', '1@gmail.com', 7, 87.00),
(10, '2025-11-25 18:59:50', 'by', '1@gmail.com', 5, 50.00),
(11, '2025-11-25 19:07:47', 'ing', '2@gmail.com', 3, 27.00),
(12, '2025-11-25 20:59:34', 'ing', '2@gmail.com', 2, 18.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders_detail`
--
CREATE TABLE `orders_detail` (
 `orders_id` INT(11) NOT NULL,
 `product_id` INT(11) NOT NULL,
 `unit` INT(11) DEFAULT NULL,
 `price` DECIMAL(10,2) DEFAULT NULL,
 `subtotal` DECIMAL(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_detail`
--
INSERT INTO `orders_detail` (`orders_id`, `product_id`, `unit`, `price`, `subtotal`) VALUES
(8, 2, 1, 8.00, 8.00),
(9, 1, 1, 9.00, 9.00),
(9, 3, 1, 12.00, 12.00),
(9, 4, 1, 11.00, 11.00),
(9, 5, 2, 14.00, 28.00),
(9, 6, 1, 13.00, 13.00),
(9, 7, 1, 14.00, 14.00),
(10, 2, 3, 8.00, 24.00),
(10, 8, 2, 13.00, 26.00),
(11, 1, 3, 9.00, 27.00),
(12, 1, 2, 9.00, 18.00);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--
CREATE TABLE `product` (
 `id` INT(4) NOT NULL,
 `name` VARCHAR(100) NOT NULL,
 `price` DECIMAL(7,2) NOT NULL,
 `description` VARCHAR(300) NOT NULL,
 `photo` VARCHAR(100) NOT NULL,
 `category_id` VARCHAR(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--
INSERT INTO `product` (`id`, `name`, `price`, `description`, `photo`, `category_id`) VALUES
(1, 'Cold - Americano', 9.00, 'A refreshing coffee drink made by pouring espresso over cold water and ice, giving a crisp, strong taste.\r\n\r\n', 'americano.png', 'c001'),
(2, 'Hot - Americano', 8.00, 'A bold, smooth coffee made by adding hot water to rich espresso, creating a clean and classic flavor.', 'H_americano.png', 'c002'),
(3, 'Cold - Latte', 12.00, 'Chilled milk mixed with espresso and poured over ice, creating a cool, silky coffee drink', 'latte.png', 'c001'),
(4, 'Hot - Latte', 11.00, 'A creamy espresso drink made with steamed milk and a light layer of foam for a smooth, mellow flavor.', 'H_latte.png', 'c002'),
(5, 'Cold - Mocha', 14.00, 'Espresso, cold milk, and chocolate served over ice, offering a refreshing and chocolatey coffee treat.', 'mocha.png', 'c001'),
(6, 'Hot - Latte', 13.00, 'A rich blend of espresso, steamed milk, and chocolate syrup, topped with foam or whipped cream for a sweet, indulgent taste.', 'H_mocha.png', 'c002'),
(7, 'Cold - Matcha', 14.00, 'Vibrant matcha mixed with cold milk and poured over ice, giving a refreshing, slightly sweet green tea flavor.', 'matcha.png', 'c001'),
(8, 'Hot - Matcha', 13.00, 'A warm, earthy blend of finely ground matcha green tea whisked with steamed milk for a smooth, calming drink.', 'H_matcha.png', 'c002');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category` ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_detail`
--
ALTER TABLE `orders_detail` ADD PRIMARY KEY (`orders_id`,`product_id`), ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product` ADD PRIMARY KEY (`id`), ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders` MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product` MODIFY `id` INT(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders_detail`
--
ALTER TABLE `orders_detail` ADD CONSTRAINT `orders_detail_ibfk_1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON
DELETE CASCADE, ADD CONSTRAINT `orders_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`); COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
