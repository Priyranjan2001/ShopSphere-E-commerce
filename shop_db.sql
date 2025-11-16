-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 02:30 PM
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
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`) VALUES
(1, 'Shopsphere', '1234'),
(2, 'PriyRanjan', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(90, 12, 9, ' Butterscotch Ice Cream', 69, 1, 'butter.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(22, 1, 'kumar', 'kya98267@gmail.com', '9876543210', 'hi'),
(23, 1, 'Mahi', 'mamtak905@gmail.com', '9876543210', 'hi'),
(24, 1, 'PriyRanjan', 'priyranjankumar383@gmail.com', '9931447458', 'hyy');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` datetime DEFAULT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `delivery_status` varchar(50) DEFAULT 'preparing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `delivery_status`) VALUES
(1, 2, 'kunal kumar', '123478', 'kya98267@gmail.com', 'paytm', 'INT, 45t54, gfdfb, dfgszdf, cxbsf, fdb, India - 560011', 'Margherita Pizza (189 x 1)', 189, '2025-04-26 22:44:21', 'pending', 'on the way'),
(2, 1, 'kundan kumar', '7301998267', 'kundankumarcims2004@gmail.com', 'paytm', '85/1, 8776, 1st A cross 2nd Block Jayanagar, Jayanagar, Bangalore, Karnataka, India - 560011', 'Kiwi Juice (69 x 1)', 69, '2025-04-27 00:07:23', 'completed', 'on the way'),
(3, 3, 'Madhu Kumar', '9876543210', 'knmadhukumar80733@gmail.com', 'paytm', '85/1, 8776, 1st A cross 2nd Block Jayanagar, Jayanagar, Bangalore, Karnataka, India - 560011', 'Lemon Drink (49 x 1) -  Butterscotch Ice Cream (69 x 1) - Egg Biryani (129 x 1)', 247, '2025-04-28 10:45:22', 'completed', 'on the way'),
(4, 1, 'kundan kumar', '7301998267', 'kundankumarcims2004@gmail.com', 'Phone Pay', '85/1, 8776, 1st A cross 2nd Block Jayanagar, Jayanagar, Bangalore, Karnataka, India - 560011', 'Margherita Pizza (189 x 1) - Rasgulla (45 x 1) - Orange Drink (59 x 1)', 293, '2025-04-28 14:39:08', 'cancelled,Amount Ref', '🚫order cancelled'),
(5, 4, 'Praveen', '8438978943', 'gowdapraveen7248@gmail.com', 'paytm', '851, 8776, 1st A cross 2nd Block Jayanagar, Jayanagar, Bangalore, Karnataka, India - 324134', 'Chennai Thali (175 x 1) - Capsicum Pizza (189 x 1)', 364, '2025-04-29 12:13:34', 'completed', 'delivered'),
(6, 5, 'Viresh Kumar', '8884072670', 'vireshlawani123@gmail.com', 'paytm', '85, 8776, 1st A cross 2nd Block , Jayanagar, Bangalore, Karnataka, India - 324134', 'Margherita Pizza (189 x 1) - Noodles (89 x 1) - Capsicum Pizza (189 x 1)', 467, '2025-04-29 14:04:56', 'completed', 'on the way'),
(7, 6, 'manu', '5670000000', 'manu@gmail.com', 'paytm', '5, 10, jayanagar, Jayanagar, Bangalore, Karnataka, India - 232435', 'Chennai Thali (175 x 1) - Jeera Rice (149 x 1)', 324, '2025-04-29 14:30:39', 'pending', 'preparing'),
(8, 8, 'kundan kumar', '7231467675', 'kunal070404@gmail.com', 'paytm', '453, 454, gfdfb, dfgszdf, cxbsf, fdb, India - 560011', ' Butterscotch Ice Cream (69 x 2) - Capsicum Pizza (189 x 1) - Kiwi Juice (69 x 1)', 396, '2025-05-01 02:25:26', 'completed', 'delivered'),
(9, 8, 'kundan kumar', '7231467675', 'kunal070404@gmail.com', 'cash on delivery', '453, 454, gfdfb, dfgszdf, cxbsf, fdb, India - 560011', 'Burger (69 x 1) -  Butterscotch Ice Cream (69 x 1) - Rasgulla (45 x 1)', 183, '2025-05-02 10:01:32', 'pending', 'preparing'),
(10, 1, 'kundan kumar', '7301998267', 'kundankumarcims2004@gmail.com', 'paytm', '85/1, 8776, 1st A cross 2nd Block Jayanagar, Jayanagar, Bangalore, Karnataka, India - 560011', 'Margherita Pizza (189 x 1) - Lemon Drink (49 x 1)', 238, '2025-05-02 12:52:14', 'cancelled,Amount Ref', '🚫order cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `image` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `image`, `description`) VALUES
(1, 'Capsicum Pizza', 'fast food', 189, 'pizza-1.png', ''),
(2, 'Margherita Pizza', 'fast food', 189, 'pizza-4.png', ''),
(3, 'Chennai Thali', 'main dish', 175, 'chennai thali.png', ''),
(4, 'Lemon Drink', 'drinks', 49, 'drink-3.png', ''),
(5, 'Salad', 'main dish', 59, 'salad-1672505_1280.jpg', ''),
(6, 'Noodles', 'fast food', 89, 'ai-generated-8725190_1280.png', ''),
(8, 'Orange Drink', 'drinks', 59, 'drink-1.png', ''),
(9, ' Butterscotch Ice Cream', 'desserts', 69, 'butter.jpg', ''),
(10, 'Egg Biryani', 'main dish', 129, 'eggbir.png', ''),
(11, 'Jeera Rice', 'main dish', 149, 'jeera rice.jpg', ''),
(12, 'Chicken Biryani', 'main dish', 189, 'chibiryani.jpg', ''),
(13, 'Cake', 'desserts', 49, 'dessert-4.png', ''),
(14, 'Egg Curry', 'main dish', 129, 'eggcurr.webp', ''),
(15, 'Kabab ', 'main dish', 89, 'kabab.jpg', ''),
(16, 'Kiwi Juice', 'drinks', 69, 'kiwi.jpg', ''),
(17, 'Chicken Roll', 'fast food', 90, 'roll.jpg', ''),
(18, 'Paneer', 'main dish', 129, 'paneer.jpg', ''),
(19, 'Tandoori Chicken', 'main dish', 149, 'tandori.jpg', ''),
(20, 'Meals', 'main dish', 80, 'meals.jpg', ''),
(21, 'Chicken Lollipop', 'main dish', 139, 'lollipop.jpg', ''),
(22, 'Rasgulla', 'desserts', 45, 'sweets-577228_1280.jpg', ''),
(23, 'Jalebi', 'desserts', 59, 'fresh-jalebi-818316_1280.jpg', ''),
(24, 'Fresh Fruit Cake', 'desserts', 69, 'dessert-6.png', ''),
(25, 'Burger', 'fast food', 69, 'burger-1.png', ''),
(26, 'Momos', 'fast food', 79, 'momos-6749181_1280.jpg', ''),
(27, 'Pine Apple Juice', 'drinks', 69, 'pine appl.jpg', ''),
(28, 'Strawberry Milkshake ', 'drinks', 89, 'strawberry.jpg', ''),
(29, 'Choco Kesar Almond ', 'desserts', 129, 'kesar.jpg', ''),
(30, 'Cold Coffee', 'drinks', 79, 'drink-2.png', ''),
(31, 'Chana Masala', 'main dish', 179, 'chanamasala.png', 'A tangy and spicy blend of dried spices, perfect to sprinkle on fruits, salads, and street food for that extra zing.');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `reply_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`id`, `message_id`, `admin_id`, `reply_text`, `reply_date`) VALUES
(1, 1, 1, 'hello', '2025-04-12 14:30:02'),
(14, 22, 1, 'hello', '2025-04-26 18:46:39'),
(15, 22, 1, 'hello', '2025-04-26 19:00:55'),
(16, 24, 1, 'hello', '2025-04-28 18:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `number`, `password`, `address`, `reset_token`, `reset_expires`) VALUES
(1, 'kundan kumar', 'kundankumarcims2004@gmail.com', '7301998267', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '85/1, 8776, 1st A cross 2nd Block Jayanagar, Jayanagar, Bangalore, Karnataka, India - 560011', NULL, NULL),
(2, 'kunal kumar', 'kya98267@gmail.com', '123478', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'INT, 45t54, gfdfb, dfgszdf, cxbsf, fdb, India - 560011', NULL, NULL),
(3, 'Madhu Kumar', 'knmadhukumar80733@gmail.com', '9876543210', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '85/1, 8776, 1st A cross 2nd Block Jayanagar, Jayanagar, Bangalore, Karnataka, India - 560011', NULL, NULL),
(4, 'Praveen', 'gowdapraveen7248@gmail.com', '8438978943', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '851, 8776, 1st A cross 2nd Block Jayanagar, Jayanagar, Bangalore, Karnataka, India - 324134', NULL, NULL),
(5, 'Viresh Kumar', 'vireshlawani123@gmail.com', '8884072670', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '85, 8776, 1st A cross 2nd Block , Jayanagar, Bangalore, Karnataka, India - 324134', NULL, NULL),
(6, 'manu', 'manu@gmail.com', '5670000000', '771435c469f83f6aa9c405ac5e1ed06314a94f2d', '5, 10, jayanagar, Jayanagar, Bangalore, Karnataka, India - 232435', NULL, NULL),
(7, 'Kundan Kumar', 'kunal060604@gmail.com', '7217241546', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '', NULL, NULL),
(8, 'kundan kumar', 'kunal070404@gmail.com', '7231467675', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '453, 454, gfdfb, dfgszdf, cxbsf, fdb, India - 560011', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
