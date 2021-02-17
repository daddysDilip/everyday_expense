-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 25, 2021 at 11:00 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shukulyatra_18012021`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 1, '2021-01-20 00:00:00', NULL),
(2, 'User', 1, '2021-01-20 00:00:00', NULL),
(3, 'Category', 1, '2021-01-20 00:00:00', NULL),
(4, 'Products', 1, '2021-01-20 00:00:00', NULL),
(5, 'Module', 1, '2021-01-25 00:00:00', NULL),
(6, 'Roles', 1, '2021-01-25 09:47:11', '2021-01-25 09:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('krishna.daddyscode@gmail.com', '$2y$10$9/HDAQ7MaeHRAHhgX299dOdbTto3MSC1FiR4V76WoTX4LUloK3rau', '2021-01-19 03:41:52');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `view` tinyint(1) DEFAULT NULL,
  `add` tinyint(1) DEFAULT NULL,
  `edit` tinyint(1) DEFAULT NULL,
  `delete` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `moduleid`, `roleid`, `view`, `add`, `edit`, `delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, NULL, 1, '2021-01-20 00:00:00', '2021-01-25 09:56:00'),
(2, 2, 1, NULL, NULL, NULL, 1, '2021-01-20 07:49:23', '2021-01-25 09:56:00'),
(3, 3, 1, NULL, 1, 1, NULL, '2021-01-20 07:49:23', '2021-01-25 09:56:00'),
(4, 4, 1, NULL, 1, NULL, 1, '2021-01-20 07:49:23', '2021-01-25 09:56:00'),
(5, 1, 2, NULL, 1, NULL, NULL, '2021-01-20 07:50:08', '2021-01-21 07:18:43'),
(6, 2, 2, 1, NULL, NULL, NULL, '2021-01-20 07:50:08', '2021-01-21 07:18:43'),
(7, 4, 2, 1, NULL, 1, NULL, '2021-01-20 07:50:08', '2021-01-21 07:18:43'),
(8, 3, 2, NULL, 1, NULL, NULL, '2021-01-21 07:18:43', '2021-01-21 07:18:43'),
(9, 5, 1, 1, 1, 1, 1, '2021-01-25 08:39:36', '2021-01-25 09:56:00'),
(10, 6, 1, 1, 1, 1, 1, '2021-01-25 09:55:57', '2021-01-25 09:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` text,
  `level` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `level`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin', 0, 'admin', 1, '2021-01-18 00:00:00', NULL),
(2, 'Company Management', 'Company Management', 1, 'user', 1, '2021-01-18 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'krishna maniya', 'admin', 'krishna.daddyscode@gmail.com', NULL, '$2y$10$kv1Q5JmJkSwH.n91TP8axe8328qpluWyMxqAGK3f8VPYk9/2wqSKC', 1, 'QEstnwAav3fDYmYkAfe7DsVSKDcULwegAkgOQxnazuqLgoXFszZHBi32DZmr', '2021-01-18 00:57:06', '2021-01-18 00:57:06'),
(2, 'krishna maniya', 'user', 'krishna.daddyscodeu@gmail.com', NULL, '$2y$10$q5lIFBBBUVnWCCd3rGtalO.u/OkbvQqn0JTgNY1SW3VeyPF6nXYXK', 2, 'ouy73JxQuNG5Rmf2iYsT4QM6YRmvlbMWMeYR5q3a6t4ahz0g8FColqvQbw7w', '2021-01-18 03:05:40', '2021-01-18 03:05:40');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
