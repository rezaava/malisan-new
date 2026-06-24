-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 24, 2026 at 08:16 AM
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
-- Database: `malisan`
--

-- --------------------------------------------------------

--
-- Table structure for table `amalis`
--

CREATE TABLE `amalis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nomre` double NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `angizeshes`
--

CREATE TABLE `angizeshes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `angizeshes`
--

INSERT INTO `angizeshes` (`id`, `text`, `level`, `created_at`, `updated_at`) VALUES
(1, 'test\r\n<br>\r\n<br>\r\ntest', 7, NULL, NULL),
(2, 'عالی', 1, NULL, NULL),
(3, 'خوب', 2, NULL, NULL),
(4, 'متوسط', 3, NULL, NULL),
(5, 'قابل قبول', 4, NULL, NULL),
(6, '12', 5, NULL, NULL),
(7, '10 ', 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  `answer` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `quiz_id`, `question_id`, `answer`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 4, '2026-06-24 02:25:42', '2026-06-24 02:25:46'),
(2, 1, 5, 1, '2026-06-24 02:25:46', '2026-06-24 02:25:50'),
(3, 1, 1, 1, '2026-06-24 02:25:50', '2026-06-24 02:25:53'),
(4, 1, 4, 4, '2026-06-24 02:25:53', '2026-06-24 02:25:58'),
(5, 1, 2, 1, '2026-06-24 02:25:58', '2026-06-24 02:26:00'),
(6, 2, 2, NULL, '2026-06-24 02:38:38', '2026-06-24 02:38:38'),
(7, 3, 5, 4, '2026-06-24 02:39:31', '2026-06-24 02:39:33'),
(8, 3, 2, 1, '2026-06-24 02:39:33', '2026-06-24 02:39:34'),
(9, 3, 4, 3, '2026-06-24 02:39:34', '2026-06-24 02:39:35'),
(10, 3, 3, 2, '2026-06-24 02:39:35', '2026-06-24 02:39:36'),
(11, 3, 1, 4, '2026-06-24 02:39:36', '2026-06-24 02:39:37');

-- --------------------------------------------------------

--
-- Table structure for table `azmons`
--

CREATE TABLE `azmons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` int(11) NOT NULL,
  `code` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `zarib` double(8,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `num` int(11) NOT NULL,
  `sath` int(11) NOT NULL,
  `sessions` varchar(191) NOT NULL,
  `show_nomre` int(11) NOT NULL DEFAULT 0,
  `time` int(11) DEFAULT NULL,
  `start` timestamp NULL DEFAULT NULL,
  `end` timestamp NULL DEFAULT NULL,
  `show_ans` int(11) NOT NULL DEFAULT 0,
  `show_state` int(11) NOT NULL DEFAULT 0,
  `changeable` int(11) NOT NULL DEFAULT 0,
  `show_remain` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) NOT NULL,
  `student_id` bigint(20) NOT NULL,
  `seen` int(11) NOT NULL DEFAULT 3,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

CREATE TABLE `checkouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `paid_account` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `archieve` int(11) NOT NULL DEFAULT 0,
  `header` varchar(191) DEFAULT NULL,
  `code` varchar(191) NOT NULL,
  `private` int(11) NOT NULL DEFAULT 0,
  `period` int(11) NOT NULL DEFAULT 3,
  `type` int(11) NOT NULL DEFAULT 0,
  `desc` text DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `length` int(11) NOT NULL DEFAULT 0,
  `sessions_length` int(11) NOT NULL DEFAULT 0,
  `majazi` text DEFAULT NULL,
  `max_session` int(11) NOT NULL DEFAULT 16,
  `num_q` int(11) NOT NULL DEFAULT 3,
  `score_e` int(11) NOT NULL DEFAULT 0,
  `score_d` int(11) NOT NULL DEFAULT 0,
  `score_q` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `active` int(11) NOT NULL DEFAULT 1,
  `quiz` int(11) NOT NULL DEFAULT 1,
  `davari` int(11) NOT NULL DEFAULT 1,
  `faaliat` int(11) NOT NULL DEFAULT 1,
  `pishraft` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `archieve`, `header`, `code`, `private`, `period`, `type`, `desc`, `price`, `length`, `sessions_length`, `majazi`, `max_session`, `num_q`, `score_e`, `score_d`, `score_q`, `status`, `active`, `quiz`, `davari`, `faaliat`, `pishraft`, `created_at`, `updated_at`) VALUES
(1, 'awkidhawu', 0, NULL, 'PGeAm', 0, 3, 0, NULL, 0, 0, 0, NULL, 16, 3, 0, 0, 0, 1, 1, 1, 1, 1, 1, '2026-06-24 02:20:58', '2026-06-24 02:20:58');

-- --------------------------------------------------------

--
-- Table structure for table `course_user`
--

CREATE TABLE `course_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `course_id` bigint(20) NOT NULL,
  `role_id` int(11) NOT NULL,
  `term` varchar(191) DEFAULT NULL,
  `paid` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_user`
--

INSERT INTO `course_user` (`id`, `user_id`, `course_id`, `role_id`, `term`, `paid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 2, NULL, 0, NULL, NULL, NULL),
(2, 2, 1, 3, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coworkers`
--

CREATE TABLE `coworkers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `konkor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `session_id` bigint(20) NOT NULL,
  `text` longtext NOT NULL,
  `counter` int(11) NOT NULL DEFAULT 0,
  `status` int(11) DEFAULT NULL COMMENT '1.ali 2.khob 3.motevaset 4.bad',
  `comment1` varchar(191) DEFAULT NULL,
  `score2` int(11) NOT NULL DEFAULT 0,
  `score3` int(11) NOT NULL DEFAULT 0,
  `comment2` text DEFAULT NULL,
  `comment3` text DEFAULT NULL,
  `is_edit` int(11) NOT NULL DEFAULT 0,
  `score` int(11) NOT NULL DEFAULT 0,
  `level` enum('1','2') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `file` text DEFAULT NULL,
  `session_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_answers`
--

CREATE TABLE `exercise_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `exercise_id` bigint(20) NOT NULL,
  `answer` text DEFAULT NULL,
  `file` varchar(191) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1.ali 2.khob 3.motevaset 4.bad',
  `comment` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fcms`
--

CREATE TABLE `fcms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` mediumtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `formats`
--

CREATE TABLE `formats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text NOT NULL,
  `mime_type` text NOT NULL,
  `extension` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konkorqs`
--

CREATE TABLE `konkorqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer1` text NOT NULL,
  `answer2` text NOT NULL,
  `answer3` text NOT NULL,
  `answer4` text NOT NULL,
  `answer` int(11) NOT NULL,
  `checker` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `year` varchar(191) DEFAULT NULL,
  `konkor_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konkors`
--

CREATE TABLE `konkors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `reshte` varchar(191) NOT NULL,
  `gerayesh` varchar(191) NOT NULL,
  `dars` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leitners`
--

CREATE TABLE `leitners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `konkor_id` int(11) DEFAULT NULL,
  `box` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `sender` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(2, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(3, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(4, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(5, '2016_06_01_000004_create_oauth_clients_table', 1),
(6, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(7, '2019_08_19_000000_create_failed_jobs_table', 1),
(8, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(9, '2026_06_17_052013_create_amalis_table', 1),
(10, '2026_06_17_052134_create_angizeshes_table', 1),
(11, '2026_06_17_052157_create_answers_table', 1),
(12, '2026_06_17_052409_create_azmons_table', 1),
(13, '2026_06_17_052429_create_categories_table', 1),
(14, '2026_06_17_052444_create_chats_table', 1),
(15, '2026_06_17_052820_create_checkouts_table', 1),
(16, '2026_06_17_052841_create_courses_table', 1),
(17, '2026_06_17_052900_create_course_users_table', 1),
(18, '2026_06_17_053106_create_coworkers_table', 1),
(19, '2026_06_17_053129_create_discussions_table', 1),
(20, '2026_06_17_053145_create_exercises_table', 1),
(21, '2026_06_17_053429_create_exercise_answers_table', 1),
(22, '2026_06_17_053455_create_fcms_table', 1),
(23, '2026_06_17_053511_create_formats_table', 1),
(24, '2026_06_17_053656_create_incomes_table', 1),
(25, '2026_06_17_053711_create_konkorqs_table', 1),
(26, '2026_06_17_053734_create_konkors_table', 1),
(27, '2026_06_17_053939_create_leitners_table', 1),
(28, '2026_06_17_054000_create_messages_table', 1),
(29, '2026_06_17_054015_create_options_table', 1),
(30, '2026_06_17_054349_create_option_users_table', 1),
(31, '2026_06_17_054407_create_questions_table', 1),
(32, '2026_06_17_054424_create_quizzes_table', 1),
(33, '2026_06_17_054717_create_scores_table', 1),
(34, '2026_06_17_054753_create_scorings_table', 1),
(35, '2026_06_17_054813_create_sessions_table', 1),
(36, '2026_06_17_055255_create_settings_table', 1),
(37, '2026_06_17_055308_create_shops_table', 1),
(38, '2026_06_17_055321_create_student_adjectives_table', 1),
(39, '2026_06_17_055339_create_student_events_table', 1),
(40, '2026_06_17_055354_create_surveys_table', 1),
(41, '2026_06_17_055825_create_touradmins_table', 1),
(42, '2026_06_17_055841_create_tours_table', 1),
(43, '2026_06_17_055855_create_tourusers_table', 1),
(44, '2026_06_17_055910_create_transactions_table', 1),
(45, '2026_06_17_055951_create_users_table', 1),
(46, '2026_06_17_084803_laratrust_setup_tables', 1),
(47, '2026_06_23_051023_add_last_seen_to_users_table', 1),
(48, '2026_06_23_054746_add_soft_deletes_to_course_user_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `survey_id` int(11) NOT NULL,
  `option` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `option_users`
--

CREATE TABLE `option_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `answer` text NOT NULL COMMENT 'text or option_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `answer1` text NOT NULL,
  `answer2` text NOT NULL,
  `answer3` text NOT NULL,
  `answer4` text NOT NULL,
  `answer` text NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `session_id` bigint(20) NOT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1.ali 2.khob 3.motevaset 4.bad',
  `star` int(11) NOT NULL DEFAULT 0,
  `counter` int(11) NOT NULL DEFAULT 0,
  `is_edit` int(11) NOT NULL DEFAULT 0,
  `score` int(11) NOT NULL DEFAULT 0,
  `level` enum('1','2') NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `answer1`, `answer2`, `answer3`, `answer4`, `answer`, `user_id`, `session_id`, `status`, `star`, `counter`, `is_edit`, `score`, `level`, `comment`, `created_at`, `updated_at`) VALUES
(1, '111111111111111', '111111111111', '22222222222222222', '3333333333', '44444444444', '1', 2, 1, 1, 0, 0, 0, 0, '1', NULL, '2026-06-24 02:24:02', '2026-06-24 02:24:02'),
(2, '2222222222', '11111', '222222', '333333', '44444444444', '1', 2, 1, 2, 0, 0, 0, 0, '1', NULL, '2026-06-24 02:24:20', '2026-06-24 02:24:20'),
(3, '44444', '111111', '22222', '333333', '444444', '1', 2, 1, 1, 0, 0, 0, 0, '1', NULL, '2026-06-24 02:24:40', '2026-06-24 02:24:40'),
(4, '5555555', '11111111', '22222222', '3333333', '44444444', '1', 2, 1, 1, 0, 0, 0, 0, '1', NULL, '2026-06-24 02:24:53', '2026-06-24 02:24:53'),
(5, 'ihdukldmwkl', 'wgudhyjb', 'qyhbdyjqbw', 'bkhweyjfgwgyu', 'uywgfwyjf', '1', 2, 1, 2, 0, 0, 0, 0, '1', NULL, '2026-06-24 02:25:09', '2026-06-24 02:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) NOT NULL,
  `azmon_id` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `score` bigint(20) DEFAULT NULL,
  `start` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `azmon_id`, `user_id`, `score`, `start`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 2, 12, '2026-06-24 02:25:42', '2026-06-24 02:25:42', '2026-06-24 02:26:00'),
(2, 1, NULL, 2, NULL, '2026-06-24 02:38:38', '2026-06-24 02:38:38', '2026-06-24 02:38:38'),
(3, 1, NULL, 2, 4, '2026-06-24 02:39:31', '2026-06-24 02:39:31', '2026-06-24 02:39:37');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'مدیر سیستم', 'مدیریت کامل سیستم', '2026-06-24 02:19:41', '2026-06-24 02:19:41'),
(2, 'teacher', 'استاد', 'مدیریت دوره‌ها', '2026-06-24 02:19:41', '2026-06-24 02:19:41'),
(3, 'student', 'دانشجو', 'دسترسی به دوره‌ها', '2026-06-24 02:19:41', '2026-06-24 02:19:41');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`, `user_type`) VALUES
(2, 1, 'App\\Models\\User'),
(3, 2, 'App\\Models\\User');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `sub_id` bigint(20) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '1.question 2.disc',
  `score1` int(11) NOT NULL COMMENT '1.type 2.gozine beham rikhte 3.ok 4.irad darad',
  `score2` int(11) NOT NULL,
  `score3` int(11) NOT NULL,
  `comment2` text DEFAULT NULL,
  `comment3` text DEFAULT NULL,
  `comment1` text DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scorings`
--

CREATE TABLE `scorings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` int(11) NOT NULL,
  `q_1` varchar(191) NOT NULL DEFAULT '1',
  `q_2` varchar(191) NOT NULL DEFAULT '0.5',
  `q_3` varchar(191) NOT NULL DEFAULT '0.25',
  `q_4` varchar(191) NOT NULL DEFAULT '-0.25',
  `d_1` varchar(191) NOT NULL DEFAULT '1',
  `d_2` varchar(191) NOT NULL DEFAULT '0.8',
  `d_3` varchar(191) NOT NULL DEFAULT '0.65',
  `d_4` varchar(191) NOT NULL DEFAULT '-0.25',
  `e_1` varchar(191) NOT NULL DEFAULT '1',
  `e_2` varchar(191) NOT NULL DEFAULT '0.8',
  `e_3` varchar(191) NOT NULL DEFAULT '0.65',
  `e_4` varchar(191) NOT NULL DEFAULT '-0.15',
  `s_1` varchar(191) NOT NULL DEFAULT '1',
  `s_2` varchar(191) NOT NULL DEFAULT '0.8',
  `s_3` varchar(191) NOT NULL DEFAULT '0.65',
  `s_4` varchar(191) NOT NULL DEFAULT '-0.15',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scorings`
--

INSERT INTO `scorings` (`id`, `course_id`, `q_1`, `q_2`, `q_3`, `q_4`, `d_1`, `d_2`, `d_3`, `d_4`, `e_1`, `e_2`, `e_3`, `e_4`, `s_1`, `s_2`, `s_3`, `s_4`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '0.5', '0.25', '-0.25', '1', '0.8', '0.65', '-0.25', '1', '0.8', '0.65', '-0.15', '1', '0.8', '0.65', '-0.15', '2026-06-24 02:20:58', '2026-06-24 02:20:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) NOT NULL,
  `text` longtext DEFAULT NULL,
  `file` varchar(191) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `majazi` text DEFAULT NULL,
  `aparat` longtext DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `number` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `course_id`, `text`, `file`, `link`, `majazi`, `aparat`, `active`, `number`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, 1, 1, 'adkawudhawuk', '2026-06-24 02:21:05', '2026-06-24 02:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` int(11) NOT NULL,
  `jalasat` int(11) NOT NULL DEFAULT 16,
  `tarahi_soal_nomre` int(11) DEFAULT 10,
  `tarahi_soal_desc` varchar(191) DEFAULT 'قبل از طرح سوال کلیه سوالاتی که برای این جلسه تا بحال طرح شده است را مشاهده کنید و سوالی طرح کنید که تکراری نباشد',
  `ersal_gozaresh_nomre` int(11) DEFAULT 10,
  `ersal_gozaresh_desc` varchar(191) DEFAULT 'خلاصه ای از آنچه در این جلسه یاد گرفته اید بنویسید یا اگر مطلب جدیدی دارید با ذکر منبع بیان کنید',
  `taklif_seminar_nomre` int(11) DEFAULT 0,
  `taklif_seminar_desc` varchar(191) DEFAULT NULL,
  `taklif_seminar_type` tinyint(4) DEFAULT 1 COMMENT '1.taklif 2.seminar',
  `quiz_mid_nomre` int(11) DEFAULT 0,
  `quiz_mid_desc` varchar(191) DEFAULT '1',
  `quiz_mid_type` tinyint(4) DEFAULT 1 COMMENT '1.quiz 2.mid',
  `pishraft_nomre` int(11) DEFAULT 40,
  `pishraft_desc` varchar(191) DEFAULT 'امتیاز این قسمت توسط سیستم محاسبه و به دانشجو نمایش می دهد',
  `talash_nomre` int(11) DEFAULT 40,
  `talash_desc` varchar(191) DEFAULT 'امتیاز این قسمت توسط سیستم محاسبه و به دانشجو نمایش می دهد',
  `hozor_nomre` int(11) DEFAULT 0,
  `hozor_desc` varchar(191) DEFAULT NULL,
  `amali_nomre` int(11) DEFAULT 0,
  `amali_desc` varchar(191) DEFAULT 'امتیاز این بخش در فعالیت مستمر دانشجو محاسبه نمی شود و توسط استاد در پایان ترم داده می شود.',
  `final_nomre` int(11) DEFAULT 30,
  `final_desc` varchar(191) DEFAULT 'امتیاز این بخش در فعالیت مستمر دانشجو محاسبه نمی شود و توسط استاد در پایان ترم داده می شود.',
  `erfagh_nomre` int(11) DEFAULT 0,
  `erfagh_desc` varchar(191) DEFAULT NULL,
  `soal_last` tinyint(4) NOT NULL DEFAULT 1,
  `gozaresh_last` tinyint(4) NOT NULL DEFAULT 1,
  `mostamar_nomre` int(11) NOT NULL DEFAULT 12,
  `taklif_last` tinyint(4) NOT NULL DEFAULT 1,
  `max_soal` int(11) DEFAULT 3,
  `min_soal` int(11) DEFAULT 2,
  `min_davari` int(11) NOT NULL DEFAULT 14,
  `max_taklif` int(11) NOT NULL DEFAULT 8,
  `max_seminar` int(11) DEFAULT 4,
  `max_gozaresh` int(11) DEFAULT 4,
  `max_gheibat` int(11) DEFAULT 3,
  `min_w_khod` int(11) DEFAULT 14,
  `q_num` int(11) DEFAULT 10,
  `sath_khod` int(11) DEFAULT 2,
  `show_khod` int(11) DEFAULT 1,
  `quiz_num` int(11) DEFAULT 1,
  `sath_quiz` int(11) DEFAULT 3,
  `natije` int(11) DEFAULT 1,
  `show_quiz` int(11) DEFAULT 1,
  `azmon_nomre` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `course_id`, `jalasat`, `tarahi_soal_nomre`, `tarahi_soal_desc`, `ersal_gozaresh_nomre`, `ersal_gozaresh_desc`, `taklif_seminar_nomre`, `taklif_seminar_desc`, `taklif_seminar_type`, `quiz_mid_nomre`, `quiz_mid_desc`, `quiz_mid_type`, `pishraft_nomre`, `pishraft_desc`, `talash_nomre`, `talash_desc`, `hozor_nomre`, `hozor_desc`, `amali_nomre`, `amali_desc`, `final_nomre`, `final_desc`, `erfagh_nomre`, `erfagh_desc`, `soal_last`, `gozaresh_last`, `mostamar_nomre`, `taklif_last`, `max_soal`, `min_soal`, `min_davari`, `max_taklif`, `max_seminar`, `max_gozaresh`, `max_gheibat`, `min_w_khod`, `q_num`, `sath_khod`, `show_khod`, `quiz_num`, `sath_quiz`, `natije`, `show_quiz`, `azmon_nomre`, `created_at`, `updated_at`) VALUES
(1, 1, 16, 0, 'قبل از طرح سوال کلیه سوالاتی که برای این جلسه تا بحال طرح شده است را مشاهده کنید و سوالی طرح کنید که تکراری نباشد', 0, 'خلاصه ای از آنچه در این جلسه یاد گرفته اید بنویسید یا اگر مطلب جدیدی دارید با ذکر منبع بیان کنید', 0, NULL, 1, 0, '1', 1, 40, 'امتیاز این قسمت توسط سیستم محاسبه و به دانشجو نمایش می دهد', 40, 'امتیاز این قسمت توسط سیستم محاسبه و به دانشجو نمایش می دهد', 0, NULL, 0, 'امتیاز این بخش در فعالیت مستمر دانشجو محاسبه نمی شود و توسط استاد در پایان ترم داده می شود.', 30, 'امتیاز این بخش در فعالیت مستمر دانشجو محاسبه نمی شود و توسط استاد در پایان ترم داده می شود.', 0, NULL, 1, 1, 12, 1, 5, 2, 14, 8, 5, 4, 3, 14, 5, 2, 0, 10, 2, 0, 0, 0, '2026-06-24 02:20:58', '2026-06-24 02:39:10');

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `discount_percent` int(11) NOT NULL,
  `address` mediumtext DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `checkout_sheba` varchar(255) DEFAULT NULL,
  `checkout_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_adjectives`
--

CREATE TABLE `student_adjectives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `adjective` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_events`
--

CREATE TABLE `student_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `event` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `group` int(11) DEFAULT 0 COMMENT '0.all else course_id',
  `text` text NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1.text 2.radio 3.check',
  `desc_add` int(11) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `touradmins`
--

CREATE TABLE `touradmins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `title` mediumtext DEFAULT NULL,
  `image` mediumtext DEFAULT NULL,
  `title_hint` mediumtext DEFAULT NULL,
  `abs_hint` mediumtext DEFAULT NULL,
  `keyword_hint` mediumtext DEFAULT NULL,
  `file_hint` mediumtext DEFAULT NULL,
  `sponser_name` mediumtext DEFAULT NULL,
  `sponser_image` mediumtext DEFAULT NULL,
  `donate` mediumtext DEFAULT NULL,
  `title_min` int(11) DEFAULT NULL,
  `title_max` int(11) DEFAULT NULL,
  `abs_min` int(11) DEFAULT NULL,
  `abs_max` int(11) DEFAULT NULL,
  `key_min` int(11) DEFAULT NULL,
  `key_max` int(11) DEFAULT NULL,
  `keyword_min` int(11) DEFAULT NULL,
  `keyword_max` int(11) DEFAULT NULL,
  `file_max` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tourusers`
--

CREATE TABLE `tourusers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
  `abstract` mediumtext NOT NULL,
  `keywords` mediumtext NOT NULL,
  `file` mediumtext NOT NULL,
  `davar` int(11) DEFAULT NULL,
  `desc_title` text DEFAULT NULL,
  `desc_key` text DEFAULT NULL,
  `desc_abs` text DEFAULT NULL,
  `desc_file` text DEFAULT NULL,
  `score_file` int(11) DEFAULT NULL,
  `score_abs` int(11) DEFAULT NULL,
  `score_key` int(11) DEFAULT NULL,
  `score_title` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `last_seen` timestamp NULL DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 1,
  `name` varchar(191) DEFAULT NULL,
  `family` varchar(191) DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL COMMENT '0 female 1 male',
  `email` varchar(191) DEFAULT NULL,
  `national` varchar(191) DEFAULT NULL,
  `shenasname` varchar(191) DEFAULT NULL,
  `personal` varchar(191) DEFAULT NULL,
  `birthdate` varchar(191) DEFAULT NULL,
  `city_birth` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `postal` varchar(191) DEFAULT NULL,
  `tel` varchar(191) DEFAULT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `sms` varchar(5) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `tour` int(11) NOT NULL DEFAULT 0,
  `tel_work` varchar(191) DEFAULT NULL,
  `uni_email` varchar(191) DEFAULT NULL,
  `web` varchar(191) DEFAULT NULL,
  `scholar` varchar(191) DEFAULT NULL,
  `social` varchar(191) DEFAULT NULL,
  `degree` varchar(191) DEFAULT NULL,
  `field` varchar(191) DEFAULT NULL COMMENT 'reshte',
  `trend` varchar(191) DEFAULT NULL COMMENT 'gerayesh',
  `trend_en` varchar(191) DEFAULT NULL COMMENT 'gerayesh',
  `research` varchar(191) DEFAULT NULL COMMENT 'pajohesh',
  `image` varchar(191) DEFAULT NULL,
  `shaba` varchar(191) DEFAULT NULL,
  `turn` varchar(191) DEFAULT NULL COMMENT 'dore',
  `password` varchar(191) NOT NULL,
  `aneto_token` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `last_seen`, `role`, `name`, `family`, `gender`, `email`, `national`, `shenasname`, `personal`, `birthdate`, `city_birth`, `city`, `address`, `postal`, `tel`, `mobile`, `sms`, `active`, `tour`, `tel_work`, `uni_email`, `web`, `scholar`, `social`, `degree`, `field`, `trend`, `trend_en`, `research`, `image`, `shaba`, `turn`, `password`, `aneto_token`, `created_at`, `updated_at`) VALUES
(1, '2026-06-24 02:39:15', 2, 'awidjawi', 'uahdawui', NULL, NULL, '4421982172', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '09135578416', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$vAYyXuOY5/dh32zJsSOA8OlPzhbX1xEqCHWkC2MbH3p2kiRbUeInO', NULL, '2026-06-24 02:20:34', '2026-06-24 02:39:15'),
(2, '2026-06-24 02:39:45', 3, 'aildhuagywgbk', 'kaudhawudhawkh', NULL, NULL, '4421982171', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '09135578411', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$jj0IHl20CjwMiKbBgmjB9eSy7i5j27Ld8d3VkzWr99ITru6zTRNeG', NULL, '2026-06-24 02:23:07', '2026-06-24 02:39:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amalis`
--
ALTER TABLE `amalis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `angizeshes`
--
ALTER TABLE `angizeshes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `azmons`
--
ALTER TABLE `azmons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_user`
--
ALTER TABLE `course_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coworkers`
--
ALTER TABLE `coworkers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercise_answers`
--
ALTER TABLE `exercise_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fcms`
--
ALTER TABLE `fcms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `formats`
--
ALTER TABLE `formats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konkorqs`
--
ALTER TABLE `konkorqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `konkors`
--
ALTER TABLE `konkors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leitners`
--
ALTER TABLE `leitners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `option_users`
--
ALTER TABLE `option_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`,`user_type`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`,`user_type`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scorings`
--
ALTER TABLE `scorings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_adjectives`
--
ALTER TABLE `student_adjectives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_events`
--
ALTER TABLE `student_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `touradmins`
--
ALTER TABLE `touradmins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tourusers`
--
ALTER TABLE `tourusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
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
-- AUTO_INCREMENT for table `amalis`
--
ALTER TABLE `amalis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `angizeshes`
--
ALTER TABLE `angizeshes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `azmons`
--
ALTER TABLE `azmons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_user`
--
ALTER TABLE `course_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coworkers`
--
ALTER TABLE `coworkers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exercise_answers`
--
ALTER TABLE `exercise_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fcms`
--
ALTER TABLE `fcms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `formats`
--
ALTER TABLE `formats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konkorqs`
--
ALTER TABLE `konkorqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konkors`
--
ALTER TABLE `konkors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leitners`
--
ALTER TABLE `leitners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `option_users`
--
ALTER TABLE `option_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scorings`
--
ALTER TABLE `scorings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_adjectives`
--
ALTER TABLE `student_adjectives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_events`
--
ALTER TABLE `student_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `touradmins`
--
ALTER TABLE `touradmins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tourusers`
--
ALTER TABLE `tourusers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
