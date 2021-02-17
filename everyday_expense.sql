-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 17, 2021 at 06:52 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `everyday_expense`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_users`
--

DROP TABLE IF EXISTS `app_users`;
CREATE TABLE IF NOT EXISTS `app_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK of table',
  `name` varchar(255) NOT NULL COMMENT 'user''s full name',
  `username` varchar(255) NOT NULL COMMENT 'ither fb id or google id for unique user name',
  `email` varchar(255) NOT NULL COMMENT 'user''s email',
  `user_image` varchar(255) DEFAULT NULL COMMENT 'user image if exists',
  `mobile` varchar(15) DEFAULT NULL COMMENT 'user''s mobile number',
  `country_id` int(11) DEFAULT NULL COMMENT 'FK of country table',
  `language_id` int(11) DEFAULT NULL COMMENT 'FK of languages table',
  `gender` enum('male','female','other') DEFAULT NULL COMMENT 'user''s gender',
  `profession` varchar(255) DEFAULT NULL COMMENT 'user''s profession',
  `password` varchar(255) DEFAULT NULL COMMENT 'password if user from signup',
  `last_open` datetime DEFAULT NULL COMMENT 'last time of user used app',
  `login_type` enum('facebook','google') DEFAULT NULL COMMENT 'user login time',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_users`
--

INSERT INTO `app_users` (`id`, `name`, `username`, `email`, `user_image`, `mobile`, `country_id`, `language_id`, `gender`, `profession`, `password`, `last_open`, `login_type`, `created_at`, `updated_at`) VALUES
(1, 'Dilip', 'k23j434hk24jh2', 'dilip@gmail.com', NULL, '9856968568', 1, 1, 'male', 'Developer', '123123', '2021-02-01 12:25:19', 'facebook', '2021-02-02 12:30:24', '2021-02-03 13:15:00'),
(6, 'Dilip patel', 'ddd111d1d1d1daaqw', 'dilip@gmail.com', '1612415277.png', '9879879877', NULL, NULL, 'male', NULL, NULL, NULL, 'facebook', '2021-02-03 12:15:56', '2021-02-04 05:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name` varchar(255) NOT NULL COMMENT 'category name',
  `key_slug` varchar(255) NOT NULL COMMENT 'slug of string',
  `icon` varchar(255) DEFAULT NULL COMMENT 'category icon',
  `type` tinyint(1) NOT NULL COMMENT '1=expense,0=income',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `key_slug`, `icon`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Shopping', 'shopping', 'category_icon/ic_hayat_&_murat.png', 1, 1, '2021-02-02 05:31:15', '2021-02-16 16:27:16'),
(2, 'Bills', 'bills', 'category_icon/ic_square.png', 1, 1, '2021-02-02 05:56:38', '2021-02-16 16:27:10'),
(3, 'Salary', 'salary', 'category_icon/ic_funny.png', 0, 1, '2021-02-02 06:10:15', '2021-02-16 16:27:03'),
(4, 'Bonus', 'bonus', 'category_icon/ic_animated.png', 0, 1, '2021-02-16 10:56:49', '2021-02-16 10:56:49'),
(5, 'Electric', 'electric', 'category_icon/ic_mahadev.png', 1, 1, '2021-02-16 10:58:10', '2021-02-16 10:58:10'),
(6, 'Fruit', 'fruit', 'category_icon/ic_christmas.png', 1, 1, '2021-02-16 10:58:44', '2021-02-16 10:58:44'),
(7, 'Shoes', 'shoes', 'category_icon/ic_marathi.png', 1, 1, '2021-02-16 10:59:08', '2021-02-16 10:59:08'),
(8, 'Gifts', 'gifts', 'category_icon/ic_happy_new_year.png', 1, 1, '2021-02-16 10:59:39', '2021-02-16 10:59:39');

-- --------------------------------------------------------

--
-- Table structure for table `category_translation`
--

DROP TABLE IF EXISTS `category_translation`;
CREATE TABLE IF NOT EXISTS `category_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `category_id` int(11) NOT NULL COMMENT 'FK category table',
  `language_id` int(11) NOT NULL COMMENT 'FK languages table',
  `translation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'translation in selected language',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category_translation`
--

INSERT INTO `category_translation` (`id`, `category_id`, `language_id`, `translation`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 'બીલ', '2021-02-08 12:30:34', '2021-02-08 12:30:34'),
(2, 2, 1, 'बिले', '2021-02-08 12:32:15', '2021-02-08 12:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `content_translation`
--

DROP TABLE IF EXISTS `content_translation`;
CREATE TABLE IF NOT EXISTS `content_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `string_id` int(11) NOT NULL COMMENT 'FK tbl_content table',
  `language_id` int(11) NOT NULL COMMENT 'FK languages table',
  `translation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'string translation in certain language',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COMMENT='records for actual string translation in certain language';

--
-- Dumping data for table `content_translation`
--

INSERT INTO `content_translation` (`id`, `string_id`, `language_id`, `translation`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'देश चुनें', '2021-02-08 05:23:57', '2021-02-08 05:23:57'),
(2, 1, 2, 'देश निवडा', '2021-02-08 05:41:21', '2021-02-08 05:41:21'),
(3, 1, 4, 'દેશ પસંદ કરો', '2021-02-08 05:41:45', '2021-02-08 05:43:28'),
(4, 2, 4, 'ભાષા પસંદ કરો', '2021-02-08 05:44:05', '2021-02-08 05:44:05'),
(5, 2, 1, 'भाषा का चयन करें', '2021-02-08 06:24:21', '2021-02-08 06:24:21'),
(6, 2, 2, 'भाषा निवडा', '2021-02-08 06:25:27', '2021-02-08 06:25:27'),
(7, 3, 2, 'खर्च किंवा उत्पन्न जोडा', '2021-02-08 06:48:48', '2021-02-08 06:48:48'),
(8, 3, 4, 'ખર્ચ અથવા આવક ઉમેરો', '2021-02-08 06:49:03', '2021-02-08 06:49:03'),
(9, 3, 1, 'व्यय या आय जोड़ें', '2021-02-08 06:49:19', '2021-02-08 06:49:19'),
(10, 4, 1, 'आय', '2021-02-08 06:51:08', '2021-02-08 06:51:08'),
(11, 4, 4, 'આવક', '2021-02-08 06:52:41', '2021-02-08 06:52:41'),
(12, 4, 2, 'उत्पन्न', '2021-02-08 06:53:12', '2021-02-08 06:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name` varchar(255) NOT NULL COMMENT 'country name',
  `currency_name` varchar(255) NOT NULL COMMENT 'country''s currency name',
  `currency_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'currency symbol',
  `status` tinyint(1) NOT NULL COMMENT '1=active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`, `currency_name`, `currency_symbol`, `status`, `created_at`, `updated_at`) VALUES
(1, 'India', 'Rupee', '₹', 1, '2021-02-01 10:06:24', '2021-02-01 10:19:57'),
(2, 'USA', 'dollar', '$', 1, '2021-02-17 05:24:19', '2021-02-17 05:24:19'),
(3, 'france', 'euro', '€', 1, '2021-02-17 05:25:12', '2021-02-17 05:25:12'),
(4, 'Turkey', 'turkish lira', '₺', 1, '2021-02-17 05:26:11', '2021-02-17 05:26:11'),
(5, 'United Kingdom', 'Pound sterling', '£', 1, '2021-02-17 05:27:08', '2021-02-17 05:27:08'),
(6, 'Switzerland', 'Swiss franc', 'CHf', 1, '2021-02-17 05:28:05', '2021-02-17 05:28:05'),
(7, 'China', 'Renminbi', '¥', 1, '2021-02-17 05:28:41', '2021-02-17 05:28:41'),
(8, 'Philippines', 'Philippine peso', '₱', 1, '2021-02-17 05:29:36', '2021-02-17 05:29:36'),
(9, 'Korea', 'South Korean Wan', '₩', 1, '2021-02-17 05:31:03', '2021-02-17 05:31:03'),
(10, 'Bangladesh', 'Taka', '৳', 1, '2021-02-17 05:32:06', '2021-02-17 05:32:06'),
(11, 'Costa Rica', 'Costa Rican Colon', '₡', 1, '2021-02-17 05:32:56', '2021-02-17 05:32:56'),
(12, 'Djibouti', 'Djibouti Franc', '₣', 1, '2021-02-17 05:33:31', '2021-02-17 05:33:31'),
(13, 'Algeria', 'Algerian Dinar', 'د.ج', 1, '2021-02-17 05:33:55', '2021-02-17 05:33:55'),
(14, 'Georgia', 'Lari', 'ლ', 1, '2021-02-17 05:34:40', '2021-02-17 05:34:40'),
(15, 'Israel', 'New Israeli Shekel', '₪', 1, '2021-02-17 05:35:14', '2021-02-17 05:35:14'),
(16, 'Iraq', 'Iraqi Dinar', 'ع.د', 1, '2021-02-17 05:35:41', '2021-02-17 05:35:41'),
(17, 'Iran', 'Iranian Rial', '﷼', 1, '2021-02-17 05:36:04', '2021-02-17 05:36:04'),
(18, 'Jordan', 'Jordanian Dinar', 'د.ا', 1, '2021-02-17 05:36:29', '2021-02-17 05:36:29'),
(19, 'Nigeria', 'Naira', '₦', 1, '2021-02-17 05:37:11', '2021-02-17 05:37:11'),
(20, 'Taiwan', 'Taiwan Dollar', '$', 1, '2021-02-17 05:40:51', '2021-02-17 05:40:51'),
(21, 'Belgium', 'Euro', '€', 1, '2021-02-17 05:41:37', '2021-02-17 05:41:37'),
(22, 'Germany', 'euro', '€', 1, '2021-02-17 05:41:56', '2021-02-17 05:41:56'),
(23, 'Slovenia', 'euro', '€', 1, '2021-02-17 05:42:24', '2021-02-17 05:42:24'),
(24, 'Portugal', 'euro', '€', 1, '2021-02-17 05:42:39', '2021-02-17 05:42:39'),
(25, 'Italy', 'euro', '€', 1, '2021-02-17 05:42:54', '2021-02-17 05:42:54'),
(26, 'Luxembourg', 'euro', '€', 1, '2021-02-17 05:43:10', '2021-02-17 05:43:10');

-- --------------------------------------------------------

--
-- Table structure for table `in_app_user`
--

DROP TABLE IF EXISTS `in_app_user`;
CREATE TABLE IF NOT EXISTS `in_app_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `user_id` int(11) NOT NULL COMMENT 'FK app_users table',
  `start_time` datetime NOT NULL COMMENT 'app use start date time',
  `end_time` datetime NOT NULL COMMENT 'app use end date time',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name` varchar(255) NOT NULL COMMENT 'Language name',
  `name_in_language` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'EX:- gujarati -> ગુજરાતી',
  `code` char(5) NOT NULL COMMENT 'Language ISO 639-1 Language Code code ex:- en',
  `country_id` int(11) NOT NULL COMMENT 'FK country table(for base country)',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `name_in_language`, `code`, `country_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hindi', 'हिन्दी', 'hi', 1, 1, '2021-02-01 11:50:11', '2021-02-05 10:58:53'),
(2, 'Marathi', 'मराठी', 'mr', 1, 1, '2021-02-01 11:50:39', '2021-02-05 10:59:24'),
(3, 'English', 'english', 'en', 1, 1, '2021-02-01 11:50:11', '2021-02-05 10:59:34'),
(4, 'Gujarati', 'ગુજરાતી', 'gu', 1, 1, '2021-02-05 05:50:10', '2021-02-05 05:50:48');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 0, '2021-01-20 00:00:00', '2021-01-27 05:47:39'),
(2, 'User', 1, '2021-01-20 00:00:00', NULL),
(3, 'Category', 1, '2021-01-20 00:00:00', NULL),
(4, 'Products', 1, '2021-01-20 00:00:00', NULL),
(5, 'Module', 1, '2021-01-25 00:00:00', NULL),
(6, 'Roles', 1, '2021-01-25 09:47:11', '2021-01-25 09:47:11'),
(8, 'Countries', 1, '2021-01-27 05:48:14', '2021-01-27 05:52:19'),
(10, 'Languages', 1, '2021-02-01 11:05:20', '2021-02-01 11:05:20'),
(11, 'Category', 1, '2021-02-01 12:17:15', '2021-02-01 12:17:15'),
(12, 'AppUsers', 1, '2021-02-02 07:20:15', '2021-02-02 07:20:15'),
(13, 'Content', 1, '2021-02-05 09:39:40', '2021-02-05 09:39:40'),
(14, 'Translation', 1, '2021-02-05 11:10:55', '2021-02-05 11:10:55'),
(15, 'CategoryTranslation', 1, '2021-02-08 12:08:44', '2021-02-08 12:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('d051261531d5173a84d5044bb6605a9baebd2452bf46a8dad3bed67f7665837a063812f9b3891ad1', 3, 2, 'EverydayExpense', '[]', 0, '2021-02-04 01:43:41', '2021-02-04 01:43:41', '2022-02-04 07:13:41'),
('e9cd7096e2b724687abfd5e1b37fe177a81802c1a473d0bf23347f9e17de717ac03dd78b14d9a461', 4, 2, 'EverydayExpense', '[]', 0, '2021-02-04 02:02:58', '2021-02-04 02:02:58', '2022-02-04 07:32:58'),
('9e9eb0cfd28dced11405c61e9591277c5f391a9ca9962021b8d90185a2f026c01a94ae8758754bd1', NULL, 2, 'EverydayExpense', '[]', 0, '2021-02-04 02:10:34', '2021-02-04 02:10:34', '2022-02-04 07:40:34'),
('4722c0320fd25bd0d8c27b8f1f11e4b04a690414e81367fa048b394d23cbe2bb73755f6b7c0328d8', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-04 03:10:38', '2021-02-04 03:10:38', '2022-02-04 08:40:38'),
('48eb4bf0326a50b0392c0df63fe71526d19b2098076dd280140c131c550dc9a312f86758ad1ad2f9', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-04 03:45:36', '2021-02-04 03:45:36', '2022-02-04 09:15:36'),
('c99b5cd5b130b44a8a585e697bc7241cd27db682e9b2aa416d753e97c249cd62b55610cc62c0ff46', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-08 03:15:24', '2021-02-08 03:15:24', '2022-02-08 08:45:24'),
('f9cf38c97efe913803d8c72ee585bc85b9e2437f5d56cec29f04f955cf67c173e8485cc40677c25f', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-16 06:03:39', '2021-02-16 06:03:39', '2022-02-16 11:33:39'),
('da8eff04be577f7a0df25a31348fe2e6dc690aac448245abebe63ad9eed0b4a606785de32764fcbf', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-16 06:04:35', '2021-02-16 06:04:35', '2022-02-16 11:34:35'),
('bd751f8ee86d754a6f24c3dc72b9c429f22c6f5affd5262d32250943fea20b3c7af61c88ea1c824a', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-16 06:05:26', '2021-02-16 06:05:26', '2022-02-16 11:35:26'),
('8ff3c11997f07721c433e2b1eafb7a62bca79abf781451925c42ff0810e20930e2bf0b4e76399d9f', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-16 06:05:51', '2021-02-16 06:05:51', '2022-02-16 11:35:51'),
('545e2dd68b8d34c079c4dfe2a2632ed65ec4c23a9374a4b03449feb0a2ff95d3a716e6eef8a83e21', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-16 06:14:43', '2021-02-16 06:14:43', '2022-02-16 11:44:43'),
('9618a37447a2862155c16f56c9c803b97dd19b2f880858f1bc632c1551580bab3b216cb92a36680a', 5, 2, 'EverydayExpense', '[]', 0, '2021-02-16 06:16:22', '2021-02-16 06:16:22', '2022-02-16 11:46:22');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'EverydayExpense Personal Access Client', '78Rq2Fy23f3sVrGJx2J9dtf8EaHdEYsV5gVrrGJm', NULL, 'http://localhost', 1, 0, 0, '2021-02-04 01:19:15', '2021-02-04 01:19:15'),
(2, NULL, 'EverydayExpense Personal Access Client', 'ISUsINMkL40bLZa3TtHOIQyOMrm0XtfJ4Z8SYaxv', NULL, 'http://localhost', 1, 0, 0, '2021-02-04 01:20:24', '2021-02-04 01:20:24'),
(3, NULL, 'EverydayExpense Password Grant Client', 'USQPmWpkLfmPiYeszSXLbTm0Id8fmFBgyQFpmEqm', 'users', 'http://localhost', 0, 1, 0, '2021-02-04 01:20:24', '2021-02-04 01:20:24');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2021-02-04 01:20:24', '2021-02-04 01:20:24');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `moduleid`, `roleid`, `view`, `add`, `edit`, `delete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, NULL, 1, '2021-01-20 00:00:00', '2021-01-25 09:56:00'),
(2, 2, 1, NULL, NULL, NULL, 1, '2021-01-20 07:49:23', '2021-02-05 09:40:02'),
(3, 3, 1, NULL, 1, 1, NULL, '2021-01-20 07:49:23', '2021-02-05 09:40:02'),
(4, 4, 1, NULL, 1, NULL, 1, '2021-01-20 07:49:23', '2021-02-05 09:40:02'),
(5, 1, 2, NULL, 1, NULL, NULL, '2021-01-20 07:50:08', '2021-01-21 07:18:43'),
(6, 2, 2, 1, NULL, NULL, NULL, '2021-01-20 07:50:08', '2021-01-21 07:18:43'),
(7, 4, 2, 1, NULL, 1, NULL, '2021-01-20 07:50:08', '2021-01-21 07:18:43'),
(8, 3, 2, NULL, 1, NULL, NULL, '2021-01-21 07:18:43', '2021-01-21 07:18:43'),
(9, 5, 1, 1, 1, 1, 1, '2021-01-25 08:39:36', '2021-02-05 09:40:02'),
(10, 6, 1, 1, 1, 1, 1, '2021-01-25 09:55:57', '2021-02-05 09:40:02'),
(11, 8, 1, 1, 1, 1, 1, '2021-01-27 05:48:50', '2021-02-05 09:40:02'),
(12, 9, 1, 1, 1, 1, 1, '2021-01-27 06:46:46', '2021-01-27 06:46:46'),
(13, 10, 1, 1, 1, 1, 1, '2021-02-01 11:05:29', '2021-02-05 09:40:02'),
(14, 11, 1, 1, 1, 1, 1, '2021-02-01 12:17:24', '2021-02-05 09:40:02'),
(15, 12, 1, 1, NULL, NULL, NULL, '2021-02-02 07:20:31', '2021-02-05 09:40:02'),
(16, 13, 1, 1, 1, 1, 1, '2021-02-05 09:40:02', '2021-02-05 09:40:02'),
(17, 14, 1, 1, 1, 1, 1, '2021-02-05 11:11:12', '2021-02-08 12:09:03'),
(18, 15, 1, 1, 1, 1, 1, '2021-02-08 12:09:03', '2021-02-08 12:09:03');

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
-- Table structure for table `tbl_content`
--

DROP TABLE IF EXISTS `tbl_content`;
CREATE TABLE IF NOT EXISTS `tbl_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `key_slug` varchar(255) NOT NULL COMMENT 'slug of string',
  `english_string` varchar(255) NOT NULL COMMENT 'key string in english',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='for save default string in English and key_slug';

--
-- Dumping data for table `tbl_content`
--

INSERT INTO `tbl_content` (`id`, `key_slug`, `english_string`, `status`, `created_at`, `updated_at`) VALUES
(1, 'select-country', 'Select Country', 1, '2021-02-05 09:58:22', '2021-02-05 10:33:39'),
(2, 'select-language', 'Select Language', 1, '2021-02-05 10:29:35', '2021-02-05 10:33:31'),
(3, 'add-expense-or-income', 'Add Expense or Income', 1, '2021-02-08 06:48:07', '2021-02-08 06:48:07'),
(4, 'income', 'Income', 1, '2021-02-08 06:50:47', '2021-02-08 06:50:47');

-- --------------------------------------------------------

--
-- Table structure for table `transactions_receipts`
--

DROP TABLE IF EXISTS `transactions_receipts`;
CREATE TABLE IF NOT EXISTS `transactions_receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `transactions_id` int(11) NOT NULL COMMENT 'FK user_transactions',
  `receipts_image` varchar(255) NOT NULL COMMENT 'receipts image',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=appUser, 0= AdminUser',
  `user_image` varchar(255) DEFAULT NULL COMMENT 'user image if exists',
  `mobile` varchar(15) DEFAULT NULL COMMENT 'user''s mobile number',
  `country_id` int(11) DEFAULT NULL COMMENT 'FK of country table',
  `language_id` int(11) DEFAULT NULL COMMENT 'FK of languages table',
  `gender` enum('male','female','other') DEFAULT NULL COMMENT 'user''s gender',
  `last_open` datetime DEFAULT NULL COMMENT 'last time of user used app',
  `login_type` enum('facebook','google') DEFAULT NULL COMMENT 'user login time',
  `api_token` text,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `user_type`, `user_image`, `mobile`, `country_id`, `language_id`, `gender`, `last_open`, `login_type`, `api_token`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'krishna.daddyscode@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$kv1Q5JmJkSwH.n91TP8axe8328qpluWyMxqAGK3f8VPYk9/2wqSKC', 1, 'TF9POfUM7qzqggmxawz76bvCe5SN1QX8ulZjFajxy3BdclUZkDgrxTCzPrIK', '2021-01-17 19:27:06', '2021-01-17 19:27:06'),
(2, 'krishna maniya', 'user', 'krishna.daddyscodeu@gmail.com', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$q5lIFBBBUVnWCCd3rGtalO.u/OkbvQqn0JTgNY1SW3VeyPF6nXYXK', 2, 'ouy73JxQuNG5Rmf2iYsT4QM6YRmvlbMWMeYR5q3a6t4ahz0g8FColqvQbw7w', '2021-01-17 21:35:40', '2021-01-17 21:35:40'),
(5, 'Dilip patel', 'ddd111d1d1d1daaqw', 'dilip@gmail.com', 1, '1613475982.png', '9879879877', 1, 1, 'male', NULL, 'facebook', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiOTYxOGEzNzQ0N2EyODYyMTU1YzE2ZjU2YzljODAzYjk3ZGQxOWIyZjg4MDg1OGYxYmM2MzJjMTU1MTU4MGJhYjNiMjE2Y2I5MmEzNjY4MGEiLCJpYXQiOiIxNjEzNDc1OTgyLjI4MzMyOSIsIm5iZiI6IjE2MTM0NzU5ODIuMjgzMzM0IiwiZXhwIjoiMTY0NTAxMTk4Mi4yNjU5MTQiLCJzdWIiOiI1Iiwic2NvcGVzIjpbXX0.TAvbH6f7tifWUD3gX1rSrmiN9aGeDUasbb9qu2-5yOFrcmszAfMxDaSRoI-wpk47CPLd91b6RtE8jY_iFGfJnfghsKoLo26z4bE72g0z_JC2BdxPqQbv-TAZ7rDgDuNRuNqYIv8FuD8Sf9MlXANjSbrmfmR017EtwSMO2lWHurFMuVu2Gal2_W4cuAm1S9Ob62dBlfnXL9-uN38oh3UFfBlnFVaBAlFLGO9-0A8Y8dR-wYc5BGur96nENVuK8TlCVf9xl6y26CeGB1Rx6Cfb5KEze8QwWWjj45pIQ-mVNO8mg2yIKqaDqZY-pzWkRbYIYghb69zOcUduoul1C3SjI73WA_z4Op0hPR_0XooyzPJ1ILkyqP1MYcoGXwTDDUZs950BhGfjXoCrT8cSogcpwjpsVIXiXVm0_E97wNXLvlK39xVnFvUY2VdwuPaaewc7Wd3l-RU_g0l5cJL-xCFKbJb0ILpERXua9hBT93D6n60SvK2L8f4ow91MAjtQFsBPx8t8DAD4eR-DnITD9xCllqBg03HyTh-fZWcQYbVV7Rq7HebMO0jqrDz9rJmDRQpAizZ3ngcVY-ZVw1w1ULpqeWS1kogBq3vBONAe0PHKUhEfgflZVi2KINM_g4O4IIEUKOjf7J2AfDVXDA2x4XLj7C_4iGWh4_j4yCD2j80xN-o', NULL, NULL, NULL, NULL, '2021-02-04 02:10:34', '2021-02-16 06:16:22');

-- --------------------------------------------------------

--
-- Table structure for table `user_category`
--

DROP TABLE IF EXISTS `user_category`;
CREATE TABLE IF NOT EXISTS `user_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `user_id` int(11) NOT NULL COMMENT 'FK from users table',
  `category_id` int(11) DEFAULT NULL COMMENT 'FK category table, can be null if user defined',
  `category_name` varchar(255) DEFAULT NULL COMMENT 'category name(if user defined)',
  `type` tinyint(1) DEFAULT NULL COMMENT '1=expense,0=income if user defined',
  `is_user_defiend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=default,1=user defined',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_category`
--

INSERT INTO `user_category` (`id`, `user_id`, `category_id`, `category_name`, `type`, `is_user_defiend`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'Shopping', 1, 0, '2021-02-16 17:16:22', NULL),
(2, 5, 2, 'Bills', 1, 0, '2021-02-16 17:16:22', NULL),
(3, 5, 3, 'Salary', 0, 0, '2021-02-16 17:16:22', NULL),
(4, 5, 4, 'Bonus', 0, 0, '2021-02-16 17:16:22', NULL),
(5, 5, 5, 'Electric', 1, 0, '2021-02-16 17:16:22', NULL),
(6, 5, 6, 'Fruit', 1, 0, '2021-02-16 17:16:22', NULL),
(7, 5, 7, 'Shoes', 1, 0, '2021-02-16 17:16:22', NULL),
(8, 5, 8, 'Gifts', 1, 0, '2021-02-16 17:16:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_date_format`
--

DROP TABLE IF EXISTS `user_date_format`;
CREATE TABLE IF NOT EXISTS `user_date_format` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `user_id` int(11) NOT NULL COMMENT 'FK users table',
  `date_format` varchar(100) NOT NULL COMMENT 'date format string which is stored by front application',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_reminders`
--

DROP TABLE IF EXISTS `user_reminders`;
CREATE TABLE IF NOT EXISTS `user_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `user_id` int(11) NOT NULL COMMENT 'FK users table',
  `title` varchar(255) NOT NULL COMMENT 'Reminder title',
  `frequency` enum('daily','weekly','Monthly') NOT NULL DEFAULT 'daily' COMMENT 'Reminder frequency',
  `week_day` enum('sun','mon','tus','wed','thu','fri','sat') DEFAULT NULL COMMENT 'if select frequency as weekly then view one more dropdown for select week day',
  `month_day` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') DEFAULT NULL COMMENT 'if select frequency as Monthly then view one more dropdown for select month day',
  `reminder_time` time NOT NULL COMMENT 'Reminder Time (time picker)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_tickets`
--

DROP TABLE IF EXISTS `user_tickets`;
CREATE TABLE IF NOT EXISTS `user_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `name` varchar(100) NOT NULL COMMENT 'Ticket name',
  `user_id` int(11) NOT NULL COMMENT 'FK users table',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_tickets`
--

INSERT INTO `user_tickets` (`id`, `name`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'personal edit', 5, '2021-02-16 10:34:14', '2021-02-16 10:43:40'),
(2, 'personal', 5, '2021-02-16 11:47:10', '2021-02-16 11:47:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_transactions`
--

DROP TABLE IF EXISTS `user_transactions`;
CREATE TABLE IF NOT EXISTS `user_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK',
  `user_id` int(11) NOT NULL COMMENT 'FK users table',
  `ticket_id` int(11) NOT NULL COMMENT 'FK user_tickets table',
  `user_category_id` int(11) NOT NULL COMMENT 'user_category table FK',
  `transaction_date` date NOT NULL COMMENT 'transaction date',
  `transaction_type` enum('1','0') NOT NULL COMMENT '1=expense,0=income	',
  `currency_symbol` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Currency-symbol',
  `total_amount` float NOT NULL COMMENT 'transaction total amount',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'transaction remark',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_transactions`
--

INSERT INTO `user_transactions` (`id`, `user_id`, `ticket_id`, `user_category_id`, `transaction_date`, `transaction_type`, `currency_symbol`, `total_amount`, `remark`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 4, '2021-02-14', '0', '$', 2000, 'test remark', '2021-02-16 12:35:16', '2021-02-16 12:41:35'),
(2, 5, 1, 5, '2021-02-14', '1', '$', 100, 'test remark', '2021-02-16 12:39:48', '2021-02-16 12:39:48'),
(3, 5, 1, 5, '2021-02-14', '1', '$', 100, 'test remark www', '2021-02-17 05:10:40', '2021-02-17 05:10:40');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
