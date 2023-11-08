-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 18, 2019 at 01:48 PM
-- Server version: 5.6.35
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_questions`
--

CREATE TABLE `bank_questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `difficulty_level_id` int(10) UNSIGNED DEFAULT NULL,
  `choice_one` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `choice_two` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `choice_three` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `choice_four` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `multiple_choice_correct_answer` tinyint(3) UNSIGNED DEFAULT NULL,
  `true_false_correct_answer` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text_correct_answer` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bank_questions`
--

INSERT INTO `bank_questions` (`id`, `creator_id`, `type`, `title`, `difficulty_level_id`, `choice_one`, `choice_two`, `choice_three`, `choice_four`, `multiple_choice_correct_answer`, `true_false_correct_answer`, `text_correct_answer`, `created_at`, `updated_at`) VALUES
(1, 1, 'multiple_choice', 'سوال چهارگزینه‌ای اول؟', 0, 'گزینه ۱', 'گزینه ۲', 'گزینه ۳', 'گزینه ۴', 1, '', '', '2019-06-25 01:05:35', '2019-06-25 01:05:35'),
(2, 1, 'multiple_choice', 'سوال چهارگزینه‌ای دوم؟', 0, 'گزینه ۱', 'گزینه ۲', 'گزینه ۳', 'گزینه ۴', 1, '', '', '2019-06-25 01:06:07', '2019-06-25 01:06:07'),
(3, 1, 'true_false', 'سوال بلی خیر اول؟', 0, '', '', '', '', 0, 'true', '', '2019-06-25 01:06:32', '2019-06-25 01:06:32'),
(4, 1, 'true_false', 'سوال بلی خیر دوم؟', 0, '', '', '', '', 0, 'true', '', '2019-06-25 01:06:46', '2019-06-25 01:06:46'),
(5, 1, 'text', 'سوال متنی اول؟', 0, '', '', '', '', 0, '', 'پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح ...', '2019-06-25 01:07:16', '2019-06-25 01:07:16'),
(6, 1, 'text', 'سوال متنی دوم؟', 0, '', '', '', '', 0, '', 'پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح پاسخ صحیح ...', '2019-06-25 01:07:37', '2019-06-25 01:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `creator_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'دسته‌بندی نمونه', '2019-07-13 21:30:46', '2019-07-13 21:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `corrections`
--

CREATE TABLE `corrections` (
  `id` int(10) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED DEFAULT NULL,
  `submission_id` int(10) UNSIGNED NOT NULL,
  `automatic_total_correct` double(8,2) DEFAULT NULL,
  `automatic_total_uncorrect` double(8,2) DEFAULT NULL,
  `automatic_total_unanswered` double(8,2) DEFAULT NULL,
  `automatic_score` double(8,2) DEFAULT NULL,
  `automatic_score_by_percentage` double(8,2) DEFAULT NULL,
  `manual_correction` text COLLATE utf8_unicode_ci,
  `manual_score` double(8,2) DEFAULT NULL,
  `manual_score_by_percentage` double(8,2) DEFAULT NULL,
  `total_score` double(8,2) DEFAULT NULL,
  `total_score_by_percentage` double(8,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `correction_done` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `difficulty_levels`
--

CREATE TABLE `difficulty_levels` (
  `id` int(10) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `difficulty_levels`
--

INSERT INTO `difficulty_levels` (`id`, `creator_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'سخت', '2019-07-09 01:07:41', '2019-07-09 01:07:41'),
(2, 1, 'آسان', '2019-07-09 01:07:46', '2019-07-09 01:07:46'),
(3, 1, 'متوسط', '2019-07-09 01:07:52', '2019-07-09 01:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(10) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `total_score` int(10) UNSIGNED DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `creator_id`, `name`, `category_id`, `total_score`, `start_time`, `end_time`, `duration`, `created_at`, `updated_at`, `enabled`) VALUES
(1, 1, 'آزمون نمونه', 0, 30, '2019-07-11 19:30:00', '2019-07-14 19:30:00', 2, '2019-06-25 01:08:16', '2019-07-14 02:24:27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2019_06_21_174455_create_exams_table', 1),
('2019_06_21_174605_create_categories_table', 1),
('2019_06_21_174723_create_difficulty_levels_table', 1),
('2019_06_21_174752_create_questions_table', 1),
('2019_06_21_174800_create_bank_questions_table', 1),
('2019_06_21_174822_create_submissions_table', 1),
('2019_06_21_174829_create_corrections_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `creator_id` int(10) UNSIGNED DEFAULT NULL,
  `exam_id` int(10) UNSIGNED NOT NULL,
  `order` tinyint(3) UNSIGNED DEFAULT NULL,
  `type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `difficulty_level_id` int(10) UNSIGNED DEFAULT NULL,
  `choice_one` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `choice_two` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `choice_three` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `choice_four` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `multiple_choice_correct_answer` tinyint(3) UNSIGNED DEFAULT NULL,
  `true_false_correct_answer` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text_correct_answer` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `exam_id` int(10) UNSIGNED NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `submissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `automatic_correction_done` tinyint(1) NOT NULL DEFAULT '0',
  `manual_correction_done` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `group` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `lname`, `email`, `password`, `group`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ادمین', 'سیستم', 'admin@ahmadian.info', '$2y$10$gZELQGOkyMRM1igMUCLQU.FnoY2x1pQT5/hElKVutmBBiBUcoWGii', '', 'wwfBR6PQ2SBkHj26akKgN8bT3qtRzFpfvvoXz1d2lta31d97rNn3jqjrTy7I', '2016-09-10 14:54:15', '2019-07-13 21:36:01'),
(2, 'مهرزاد', 'احمدیان', 'mehrzad@ahmadian.info', '$2y$10$gZELQGOkyMRM1igMUCLQU.FnoY2x1pQT5/hElKVutmBBiBUcoWGii', 'instructor', 'xzxMfRXaigfgkKhR7VtVaclvftI9Ot3ZOUyjdEeLWY5pZwrjAgmpMpYtqp8T', '2016-09-10 01:34:40', '2018-06-27 00:57:57'),
(3, 'استاد', 'نمونه', 'instructor@ahmadian.info', '$2y$10$haEuxHBIt8U5uXsIoQSJLO2hFHzhjSvoKovtRrtv4WENuwhP3zF2a', 'instructor', NULL, '2019-06-24 12:47:03', '2019-06-24 12:47:03'),
(4, 'محصل', 'نمونه', 'learner@ahmadian.info', '$2y$10$eWtJVxQC3/TA2cjQnAZzvuVDzZu/5BZOygSfeY.oGpXBsBJSOhCD.', 'learner', 'vhY7doeCAVwMi4NyMhDRGVBt0ZRExNemmhhp6nilF6qqpxKhXjVGTLmNSmz8', '2019-06-24 12:47:29', '2019-06-25 00:39:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_questions`
--
ALTER TABLE `bank_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `corrections`
--
ALTER TABLE `corrections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `difficulty_levels`
--
ALTER TABLE `difficulty_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_questions`
--
ALTER TABLE `bank_questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `corrections`
--
ALTER TABLE `corrections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `difficulty_levels`
--
ALTER TABLE `difficulty_levels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
