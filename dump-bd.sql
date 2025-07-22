-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.4.3 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Дамп структуры базы данных app-xo4u-servis-local
CREATE DATABASE IF NOT EXISTS `app-xo4u-servis-local` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `app-xo4u-servis-local`;

-- Дамп структуры для таблица app-xo4u-servis-local.api_yandex_direct_accounts
CREATE TABLE IF NOT EXISTS `api_yandex_direct_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `yandex_login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `yandex_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('user','admin','templates') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `user_id` bigint unsigned DEFAULT NULL,
  `agency_binding` enum('bound','unbound') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unbound',
  `agency_account_id` bigint unsigned DEFAULT NULL,
  `agency_login` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency_oauth_token` text COLLATE utf8mb4_unicode_ci,
  `oauth_token` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `api_yandex_direct_accounts_user_id_foreign` (`user_id`),
  KEY `api_yandex_direct_accounts_agency_account_id_foreign` (`agency_account_id`),
  CONSTRAINT `api_yandex_direct_accounts_agency_account_id_foreign` FOREIGN KEY (`agency_account_id`) REFERENCES `api_yandex_direct_agency_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `api_yandex_direct_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.api_yandex_direct_accounts: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.api_yandex_direct_agency_accounts
CREATE TABLE IF NOT EXISTS `api_yandex_direct_agency_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('own','partner') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'own',
  `user_id` bigint unsigned DEFAULT NULL,
  `yandex_login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `yandex_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `oauth_token` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `api_yandex_direct_agency_accounts_user_id_foreign` (`user_id`),
  CONSTRAINT `api_yandex_direct_agency_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.api_yandex_direct_agency_accounts: ~0 rows (приблизительно)
INSERT INTO `api_yandex_direct_agency_accounts` (`id`, `type`, `user_id`, `yandex_login`, `yandex_password`, `oauth_token`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'own', NULL, 'dir.mandarin@yandex.ru', 'obivan0704', 'y0__xDbja6QARifiTggma767xNLiq1U4wsdqq2B0k3XTUyTEYpuXQ', 1, '2025-07-20 12:18:37', '2025-07-20 12:18:37');

-- Дамп структуры для таблица app-xo4u-servis-local.api_yandex_direct_settings
CREATE TABLE IF NOT EXISTS `api_yandex_direct_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `app_login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mandain5@yandex.ru',
  `app_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'xo4u-servis',
  `app_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'https://oauth.yandex.ru/client/4b516980adea471abbe91d5e9b7d6634',
  `client_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '4b516980adea471abbe91d5e9b7d6634',
  `client_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '8d0267598b0340adb0d751700ba7eaf7',
  `redirect_uri` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'https://oauth.yandex.ru/verification_code',
  `auth_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'https://oauth.yandex.ru/authorize?response_type=token&client_id=4b516980adea471abbe91d5e9b7d6634',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.api_yandex_direct_settings: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.cache: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.cache_locks: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.failed_jobs: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.jobs: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.job_batches: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.migrations: ~9 rows (приблизительно)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_07_20_094225_add_fields_to_users_table', 2),
	(5, '2025_07_20_094813_create_personal_access_tokens_table', 3),
	(6, '2025_07_20_142233_create_api_yandex_direct_settings_table', 4),
	(7, '2025_07_20_142238_create_api_yandex_direct_accounts_table', 4),
	(8, '2025_07_20_143143_add_is_active_to_api_yandex_direct_accounts_table', 5),
	(9, '2025_07_20_151433_create_api_yandex_direct_agency_accounts_table', 6),
	(10, '2025_07_20_151439_create_api_yandex_direct_accounts_table_new', 6);

-- Дамп структуры для таблица app-xo4u-servis-local.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.password_reset_tokens: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.personal_access_tokens: ~0 rows (приблизительно)

-- Дамп структуры для таблица app-xo4u-servis-local.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.sessions: ~3 rows (приблизительно)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('dOab1jmVpmAWShDEq4SbLNl4gUzv6AxVT7p5ItVC', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 YaBrowser/25.6.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibjlZTGhocVYyN1k0UFFxcllyU0Y5ekY1dTVLalUwSjkyWnZyeHZFZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9hcHAteG80dS1zZXJ2aXMtbG9jYWwudGVzdC9hZG1pbi95YW5kZXgtZGlyZWN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1753198056),
	('gS6w04yC9hoh7duC8c3puUZv6dSFooEKEdu10nN1', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 YaBrowser/25.6.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieFVSRzhLQWpMVm5rOTdLY0d2VkUwZEp2RFVnNUNDbzJkYkJOTlo2MSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9hcHAteG80dS1zZXJ2aXMtbG9jYWwudGVzdC9hZG1pbi95YW5kZXgtZGlyZWN0L2FnZW5jeS1hY2NvdW50cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1753037185),
	('nsAeq8K4XvyPJ1fzDe2yOJpDAzdz0ijj00Ai3Nei', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 YaBrowser/25.6.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNk5jRmlrM3ZkYzJpbnR5eGl1b2NQN3NmSlBNZW9MQWRzTHQycTZTTCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo2OToiaHR0cDovL2FwcC14bzR1LXNlcnZpcy1sb2NhbC50ZXN0L2FkbWluL3lhbmRleC1kaXJlY3QvYWdlbmN5LWFjY291bnRzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9hcHAteG80dS1zZXJ2aXMtbG9jYWwudGVzdC9hZG1pbi95YW5kZXgtZGlyZWN0L2FnZW5jeS1hY2NvdW50cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1753187817);

-- Дамп структуры для таблица app-xo4u-servis-local.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_methods` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы app-xo4u-servis-local.users: ~0 rows (приблизительно)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `balance`, `payment_methods`) VALUES
	(1, 'Басов Леонид', 'admin@admin.ru', NULL, '$2y$12$NCeSpnd1rYtSrT6Q38V2NeHjzRKpb1qo2NmpuUjS3Ol5ogjq3d8xq', NULL, '2025-07-20 08:50:16', '2025-07-20 08:50:16', 'user', 0.00, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
