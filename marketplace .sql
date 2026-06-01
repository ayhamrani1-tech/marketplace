-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2026 at 02:14 AM
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
-- Database: `marketplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user1`, `user2`, `item_id`, `created_at`) VALUES
(1, 15, 1, 29, '2026-05-05 19:46:43'),
(2, 16, 15, 37, '2026-05-05 19:48:31'),
(3, 14, 15, 37, '2026-05-07 21:03:56'),
(4, 17, 15, 37, '2026-05-13 19:39:41'),
(5, 16, 17, 38, '2026-05-13 20:47:15'),
(6, 14, 16, 39, '2026-05-13 20:48:02'),
(7, 17, 16, 39, '2026-05-13 20:53:25'),
(8, 18, 16, 39, '2026-05-23 20:07:13'),
(9, 19, 18, 42, '2026-05-28 14:58:34'),
(10, 19, 15, 37, '2026-05-31 01:53:14');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourites`
--

INSERT INTO `favourites` (`id`, `user_id`, `item_id`, `created_at`) VALUES
(4, 15, 37, '2026-05-07 21:02:19'),
(5, 14, 37, '2026-05-07 21:03:48'),
(8, 16, 38, '2026-05-13 20:47:13'),
(9, 17, 40, '2026-05-17 01:01:50'),
(11, 18, 39, '2026-05-25 16:14:09');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `phone` char(10) NOT NULL,
  `seller_name` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `title`, `description`, `price`, `phone`, `seller_name`, `user_id`, `image`) VALUES
(37, 'سس', 'ضصص', 0.00, '0796657675', NULL, 15, NULL),
(39, 'كرسي', 'شخب', 0.00, '0796657675', NULL, 16, '1778690913_20260110_092233032_iOS.jpg'),
(42, 'kk', 'k-', 0.00, '0796657675', NULL, 18, '1779714845_Screenshot 2026-05-20 230341.png');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `message`, `created_at`) VALUES
(1, 1, 15, 'مرحبا', '2026-05-05 19:46:49'),
(2, 2, 16, 'مرحبا', '2026-05-05 19:48:33'),
(3, 2, 16, 'مرحبا', '2026-05-05 19:48:46'),
(4, 2, 15, 'هلا', '2026-05-05 19:54:04'),
(5, 2, 15, 'هلا', '2026-05-05 20:01:37'),
(6, 2, 15, 'هلا', '2026-05-05 20:06:47'),
(7, 4, 17, 'مرحبا', '2026-05-13 19:39:50'),
(8, 2, 16, 'اهلا', '2026-05-13 19:40:10'),
(9, 5, 16, 'مرحبااا', '2026-05-13 20:47:36'),
(10, 6, 14, 'مرحبااااا', '2026-05-13 20:48:10'),
(11, 8, 18, 'hhiiiiiiii', '2026-05-23 20:07:30'),
(12, 9, 19, 'مرحبا', '2026-05-31 01:50:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `verification_code` varchar(6) DEFAULT NULL,
  `code_created_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `reset_token` varchar(64) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `verification_code`, `code_created_at`, `is_verified`, `reset_token`, `token_expires`) VALUES
(14, 'ast', 'ast22@cit.just.edu.jo', '$2y$10$ufBqxynrihG5zwhCrbq32OntTCS7xIeemcH5pb0zsDF3KbYnGW9yq', '0798734611', NULL, NULL, 1, NULL, NULL),
(16, 'ايهم ابو الحاج', 'arabualhaj22@cit.just.edu.jo', '$2y$10$UEvTVfzZNHLSDYYRRCRJ8.KbUze63vH4p5svAx5BAYc4UKtEr4FyS', '098734652', NULL, NULL, 1, NULL, NULL),
(17, 'ahamd', 'ahmad@cit.just.edu.jo', '$2y$10$Do7mkkv3hWZJU1.xmI1XaOOyuK1hohig5qyYSNUW2JGpXqLtx1Pey', '0798734617', NULL, NULL, 1, NULL, NULL),
(18, 'mahmoud', 'm@cit.just.edu.jo', '$2y$10$WZ2oCVKyIJhuYo2XjuuJbewblouxLVc4tvsJf./WoJX7m8v6xYFx.', '0798734617', NULL, NULL, 1, NULL, NULL),
(19, 'ayha', 'aaaa@cit.just.edu.jo', '$2y$10$myMWgja0YSGoegd6FEUnweuHmbZtBgdwsHtSRBTmYASxgvOtDTUNa', '0798734611', NULL, NULL, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
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
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
