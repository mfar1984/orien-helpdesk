-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 10:22 PM
-- Server version: 9.3.0
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orien`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci,
  `new_values` text COLLATE utf8mb4_unicode_ci,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `description`, `old_values`, `new_values`, `subject_id`, `subject_type`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'logout', 'auth', 'User logged out', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:05:05', '2026-01-10 08:05:05'),
(2, 1, 'login', 'auth', 'User logged in successfully', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:05:12', '2026-01-10 08:05:12'),
(3, 1, 'update', 'settings', 'General settings updated', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:05:39', '2026-01-10 08:05:39'),
(4, 3, 'logout', 'auth', 'User logged out', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2026-01-10 08:07:52', '2026-01-10 08:07:52'),
(5, NULL, 'login', 'auth', 'User logged in successfully', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2026-01-10 08:07:57', '2026-01-10 08:07:57'),
(6, 1, 'delete', 'settings', 'Ticket category deleted: Billing', '{\"id\":3,\"name\":\"Billing\",\"slug\":\"billing\",\"description\":null,\"color\":\"#10b981\",\"icon\":\"payments\",\"sort_order\":3,\"status\":\"active\",\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-09T09:05:18.000000Z\",\"deleted_at\":null}', NULL, 3, 'App\\Models\\TicketCategory', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:15:49', '2026-01-10 08:15:49'),
(7, 1, 'restore', 'categories', 'TicketCategory restored: Billing', NULL, NULL, 3, 'App\\Models\\TicketCategory', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:16:00', '2026-01-10 08:16:00'),
(8, 1, 'delete', 'settings', 'Ticket category deleted: Billing', '{\"id\":3,\"name\":\"Billing\",\"slug\":\"billing\",\"description\":null,\"color\":\"#10b981\",\"icon\":\"payments\",\"sort_order\":3,\"status\":\"active\",\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-10T08:16:00.000000Z\",\"deleted_at\":null}', NULL, 3, 'App\\Models\\TicketCategory', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:16:18', '2026-01-10 08:16:18'),
(9, 1, 'delete', 'tools', 'Bad word removed: suck', '{\"id\":2,\"word\":\"suck\",\"reason\":null,\"severity\":\"high\",\"added_by\":2,\"status\":\"active\",\"created_at\":\"2026-01-10T05:30:09.000000Z\",\"updated_at\":\"2026-01-10T05:30:09.000000Z\",\"deleted_at\":null}', NULL, 2, 'App\\Models\\BadWord', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:16:28', '2026-01-10 08:16:28'),
(10, 1, 'restore', 'categories', 'TicketCategory restored: Billing', NULL, NULL, 3, 'App\\Models\\TicketCategory', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:16:38', '2026-01-10 08:16:38'),
(11, 1, 'restore', 'tools', 'BadWord restored: ID #2', NULL, NULL, 2, 'App\\Models\\BadWord', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:16:44', '2026-01-10 08:16:44'),
(12, 1, 'update', 'settings', 'General settings updated', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:17:09', '2026-01-10 08:17:09'),
(13, 1, 'update', 'settings', 'General settings updated', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 09:15:24', '2026-01-10 09:15:24'),
(14, 1, 'reply', 'tickets', 'Reply added to Ticket #1: Nice Logo', NULL, NULL, 1, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 09:40:08', '2026-01-10 09:40:08'),
(15, 1, 'reply', 'tickets', 'Reply added to Ticket #1: Nice Logo', NULL, NULL, 1, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 09:46:25', '2026-01-10 09:46:25'),
(16, 1, 'reply', 'tickets', 'Reply added to Ticket #1: Nice Logo', NULL, NULL, 1, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:28:05', '2026-01-10 10:28:05'),
(17, 1, 'update', 'roles', 'Role updated: Administrator', '{\"name\":\"Administrator\",\"status\":\"active\",\"permissions\":[\"dashboard.view\",\"tickets.view\",\"tickets.create\",\"tickets.edit\",\"tickets.delete\",\"tickets.manage\",\"knowledgebase_view.view\",\"knowledgebase_articles.view\",\"knowledgebase_articles.create\",\"knowledgebase_articles.edit\",\"knowledgebase_articles.delete\",\"knowledgebase_categories.view\",\"knowledgebase_categories.create\",\"knowledgebase_categories.edit\",\"knowledgebase_categories.delete\",\"reports.view\",\"reports.export\",\"tools_ban_emails.view\",\"tools_ban_emails.create\",\"tools_ban_emails.edit\",\"tools_ban_emails.delete\",\"tools_ban_emails.export\",\"tools_ban_ips.view\",\"tools_ban_ips.create\",\"tools_ban_ips.edit\",\"tools_ban_ips.delete\",\"tools_ban_ips.export\",\"tools_whitelist_ips.view\",\"tools_whitelist_ips.create\",\"tools_whitelist_ips.edit\",\"tools_whitelist_ips.delete\",\"tools_whitelist_ips.export\",\"tools_whitelist_emails.view\",\"tools_whitelist_emails.create\",\"tools_whitelist_emails.edit\",\"tools_whitelist_emails.delete\",\"tools_whitelist_emails.export\",\"tools_bad_words.view\",\"tools_bad_words.create\",\"tools_bad_words.edit\",\"tools_bad_words.delete\",\"tools_bad_words.export\",\"tools_bad_websites.view\",\"tools_bad_websites.create\",\"tools_bad_websites.edit\",\"tools_bad_websites.delete\",\"tools_bad_websites.export\",\"settings_general.view\",\"settings_general.edit\",\"settings_integrations_email.view\",\"settings_integrations_email.edit\",\"settings_integrations_weather.view\",\"settings_integrations_weather.edit\",\"settings_integrations_api.view\",\"settings_integrations_api.edit\",\"settings_integrations_spam.view\",\"settings_integrations_spam.edit\",\"settings_integrations_recycle.view\",\"settings_integrations_recycle.delete\",\"settings_integrations_recycle.manage\",\"settings_ticket_categories.view\",\"settings_ticket_categories.create\",\"settings_ticket_categories.edit\",\"settings_ticket_categories.delete\",\"settings_priorities.view\",\"settings_priorities.create\",\"settings_priorities.edit\",\"settings_priorities.delete\",\"settings_status.view\",\"settings_status.create\",\"settings_status.edit\",\"settings_status.delete\",\"settings_sla.view\",\"settings_sla.create\",\"settings_sla.edit\",\"settings_sla.delete\",\"settings_email_templates.view\",\"settings_email_templates.create\",\"settings_email_templates.edit\",\"settings_email_templates.delete\",\"settings_roles.view\",\"settings_roles.create\",\"settings_roles.edit\",\"settings_roles.delete\",\"settings_users_admin.view\",\"settings_users_admin.create\",\"settings_users_admin.edit\",\"settings_users_admin.delete\",\"settings_users_admin.manage\",\"settings_users_agents.view\",\"settings_users_agents.create\",\"settings_users_agents.edit\",\"settings_users_agents.delete\",\"settings_users_agents.manage\",\"settings_users_customers.view\",\"settings_users_customers.create\",\"settings_users_customers.edit\",\"settings_users_customers.delete\",\"settings_users_customers.manage\",\"settings_activity_logs.view\",\"settings_activity_logs.delete\",\"settings_activity_logs.export\",\"settings_audit_logs.view\",\"settings_audit_logs.delete\",\"settings_audit_logs.export\"]}', '[]', 1, 'App\\Models\\Role', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:35:03', '2026-01-10 10:35:03'),
(18, 1, 'update', 'settings', 'Telegram gateway settings updated', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:42:57', '2026-01-10 10:42:57'),
(19, 1, 'update', 'settings', 'Telegram gateway settings updated', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:43:58', '2026-01-10 10:43:58'),
(20, 1, 'status_change', 'tickets', 'Ticket status changed from \'Open\' to \'Closed\'', '{\"status\":\"Open\"}', '{\"status\":\"Closed\"}', 1, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:44:25', '2026-01-10 10:44:25'),
(21, 1, 'delete', 'settings', 'Ticket category deleted: Billing', '{\"id\":3,\"name\":\"Billing\",\"slug\":\"billing\",\"description\":null,\"color\":\"#10b981\",\"icon\":\"payments\",\"sort_order\":3,\"status\":\"active\",\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-10T08:16:38.000000Z\",\"deleted_at\":null}', NULL, 3, 'App\\Models\\TicketCategory', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:53:24', '2026-01-10 10:53:24'),
(22, 1, 'restore', 'categories', 'TicketCategory restored: Billing', NULL, NULL, 3, 'App\\Models\\TicketCategory', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:53:39', '2026-01-10 10:53:39'),
(23, 1, 'update', 'settings', 'General settings updated', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:57:40', '2026-01-10 10:57:40'),
(24, 1, 'delete', 'tools', 'Email ban removed: spam@spam.com', '{\"id\":3,\"email\":\"spam@spam.com\",\"reason\":\"spam@spam.com\",\"added_by\":2,\"created_at\":\"2026-01-10T05:28:40.000000Z\",\"updated_at\":\"2026-01-10T05:28:40.000000Z\",\"deleted_at\":null}', NULL, 3, 'App\\Models\\BannedEmail', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:57:56', '2026-01-10 10:57:56'),
(25, 1, 'restore', 'tools', 'BannedEmail restored: ID #3', NULL, NULL, 3, 'App\\Models\\BannedEmail', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:58:11', '2026-01-10 10:58:11'),
(26, 1, 'create', 'tools', 'Bad word added: babi', NULL, '{\"word\":\"babi\",\"reason\":null,\"severity\":\"high\",\"added_by\":1,\"updated_at\":\"2026-01-10T10:58:22.000000Z\",\"created_at\":\"2026-01-10T10:58:22.000000Z\",\"id\":3}', 3, 'App\\Models\\BadWord', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:58:22', '2026-01-10 10:58:22'),
(27, 3, 'login', 'auth', 'User logged in successfully', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2026-01-10 10:58:44', '2026-01-10 10:58:44'),
(28, 3, 'create', 'tickets', 'Ticket created: Email Notification and Telegram', NULL, '{\"created_by\":3,\"subject\":\"Email Notification and Telegram\",\"description\":\"Email Notification and Telegram\",\"priority_id\":\"4\",\"category_id\":\"2\",\"status_id\":1,\"sla_rule_id\":6,\"due_date\":\"2026-01-10T10:59:09.000000Z\",\"ticket_number\":\"TKT-2026-0002\",\"updated_at\":\"2026-01-10T10:59:09.000000Z\",\"created_at\":\"2026-01-10T10:59:09.000000Z\",\"id\":2,\"creator\":{\"name\":\"Faizan Rahman\",\"first_name\":\"Faizan\",\"last_name\":\"Rahman\",\"username\":\"faizanrahman84@gmail.com\",\"email\":\"faizanrahman84@gmail.com\",\"phone\":null,\"mobile\":null,\"address\":null,\"city\":null,\"state\":null,\"postcode\":null,\"country\":\"MY\",\"company_name\":null,\"company_registration\":null,\"company_phone\":null,\"company_email\":null,\"company_address\":null,\"company_website\":null,\"industry\":null,\"status\":\"active\",\"login_attempts\":0,\"locked_at\":null,\"suspended_at\":null,\"suspended_reason\":null,\"last_login_at\":\"2026-01-10T10:58:44.000000Z\",\"email_verified_at\":null,\"hash_link\":\"845ed07bad1d704af5dd21af762001dd9f159cfb283297bbd9a3f88decd46aaf\",\"role_id\":3,\"user_type\":\"customer\",\"two_factor_enabled\":false,\"two_factor_confirmed_at\":null,\"created_at\":\"2026-01-09T09:10:14.000000Z\",\"updated_at\":\"2026-01-10T10:58:44.000000Z\",\"deleted_at\":null},\"category\":{\"id\":2,\"name\":\"Technical\",\"slug\":\"technical\",\"description\":null,\"color\":\"#8b5cf6\",\"icon\":\"build\",\"sort_order\":2,\"status\":\"active\",\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-09T09:05:18.000000Z\",\"deleted_at\":null},\"priority\":{\"id\":4,\"name\":\"Critical\",\"slug\":\"critical\",\"description\":null,\"color\":\"#ef4444\",\"icon\":\"priority_high\",\"sort_order\":4,\"status\":\"active\",\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-09T09:05:18.000000Z\",\"deleted_at\":null},\"status\":{\"id\":1,\"name\":\"Open\",\"slug\":\"open\",\"description\":null,\"color\":\"#3b82f6\",\"icon\":\"radio_button_unchecked\",\"sort_order\":1,\"is_default\":true,\"is_closed\":false,\"status\":\"active\",\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-09T09:05:18.000000Z\",\"deleted_at\":null}}', 2, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2026-01-10 10:59:11', '2026-01-10 10:59:11'),
(29, 1, 'reply', 'tickets', 'Reply added to Ticket #2: Email Notification and Telegram', NULL, NULL, 2, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:59:41', '2026-01-10 10:59:41'),
(30, 1, 'reply', 'tickets', 'Reply added to Ticket #2: Email Notification and Telegram', NULL, NULL, 2, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:59:55', '2026-01-10 10:59:55'),
(31, 1, 'assign', 'tickets', 'Ticket #TKT-2026-0002 assigned to: Agent Orien', NULL, '{\"assignees\":[\"Agent Orien\"]}', 2, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:00:04', '2026-01-10 11:00:04'),
(32, 1, 'status_change', 'tickets', 'Ticket #TKT-2026-0002 status changed from \'Open\' to \'In Progress\'', '{\"status\":\"Open\"}', '{\"status\":\"In Progress\"}', 2, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:00:12', '2026-01-10 11:00:12'),
(33, 1, 'status_change', 'tickets', 'Ticket #TKT-2026-0002 status changed from \'In Progress\' to \'Closed\'', '{\"status\":\"In Progress\"}', '{\"status\":\"Closed\"}', 2, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:00:25', '2026-01-10 11:00:25'),
(34, 1, 'delete', 'tickets', 'Ticket deleted: Nice Logo', '{\"id\":1,\"ticket_number\":\"TKT-2026-0001\",\"created_by\":3,\"assigned_to\":2,\"subject\":\"Nice Logo\",\"description\":\"Nice Logo\",\"category_id\":5,\"priority_id\":4,\"status_id\":5,\"sla_rule_id\":5,\"due_date\":\"2026-01-09T09:11:50.000000Z\",\"resolved_at\":null,\"closed_at\":\"2026-01-10T10:44:23.000000Z\",\"deleted_at\":null,\"created_at\":\"2026-01-09T09:11:50.000000Z\",\"updated_at\":\"2026-01-10T10:44:23.000000Z\"}', NULL, 1, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:04:46', '2026-01-10 11:04:46'),
(35, 1, 'delete', 'tickets', 'Ticket deleted: Email Notification and Telegram', '{\"id\":2,\"ticket_number\":\"TKT-2026-0002\",\"created_by\":3,\"assigned_to\":2,\"subject\":\"Email Notification and Telegram\",\"description\":\"Email Notification and Telegram\",\"category_id\":2,\"priority_id\":4,\"status_id\":5,\"sla_rule_id\":6,\"due_date\":\"2026-01-10T10:59:09.000000Z\",\"resolved_at\":null,\"closed_at\":\"2026-01-10T11:00:24.000000Z\",\"deleted_at\":null,\"created_at\":\"2026-01-10T10:59:09.000000Z\",\"updated_at\":\"2026-01-10T11:00:24.000000Z\"}', NULL, 2, 'App\\Models\\Ticket', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:04:53', '2026-01-10 11:04:53'),
(36, 1, 'delete', 'tools', 'Email ban removed: spam@spam.com', '{\"id\":3,\"email\":\"spam@spam.com\",\"reason\":\"spam@spam.com\",\"added_by\":2,\"created_at\":\"2026-01-10T05:28:40.000000Z\",\"updated_at\":\"2026-01-10T10:58:11.000000Z\",\"deleted_at\":null}', NULL, 3, 'App\\Models\\BannedEmail', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:05:08', '2026-01-10 11:05:08'),
(37, 1, 'delete', 'tools', 'IP ban removed: 75.18.18.15', '{\"id\":1,\"ip_address\":\"75.18.18.15\",\"reason\":\"ban ip spammer\",\"added_by\":2,\"created_at\":\"2026-01-10T05:29:23.000000Z\",\"updated_at\":\"2026-01-10T05:29:23.000000Z\",\"deleted_at\":null}', NULL, 1, 'App\\Models\\BannedIp', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:05:15', '2026-01-10 11:05:15'),
(38, 1, 'delete', 'tools', 'Bad word removed: babi', '{\"id\":3,\"word\":\"babi\",\"reason\":null,\"severity\":\"high\",\"added_by\":1,\"status\":\"active\",\"created_at\":\"2026-01-10T10:58:22.000000Z\",\"updated_at\":\"2026-01-10T10:58:22.000000Z\",\"deleted_at\":null}', NULL, 3, 'App\\Models\\BadWord', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:05:26', '2026-01-10 11:05:26'),
(39, 1, 'delete', 'tools', 'Bad website removed: https://www.xvideos.com', '{\"id\":1,\"url\":\"https:\\/\\/www.xvideos.com\",\"reason\":null,\"severity\":\"high\",\"added_by\":2,\"status\":\"active\",\"created_at\":\"2026-01-10T05:30:20.000000Z\",\"updated_at\":\"2026-01-10T05:30:20.000000Z\",\"deleted_at\":null}', NULL, 1, 'App\\Models\\BadWebsite', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:05:35', '2026-01-10 11:05:35'),
(40, 1, 'delete', 'tools', 'Bad word removed: suck', '{\"id\":2,\"word\":\"suck\",\"reason\":null,\"severity\":\"high\",\"added_by\":2,\"status\":\"active\",\"created_at\":\"2026-01-10T05:30:09.000000Z\",\"updated_at\":\"2026-01-10T08:16:44.000000Z\",\"deleted_at\":null}', NULL, 2, 'App\\Models\\BadWord', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:05:46', '2026-01-10 11:05:46'),
(41, 1, 'export', 'reports', 'Report exported: 2026-01-01 to 2026-01-31', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:06:11', '2026-01-10 11:06:11'),
(42, 1, 'update', 'settings', 'General settings updated', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:22:04', '2026-01-10 11:22:04'),
(43, 3, 'logout', 'auth', 'User logged out', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2026-01-10 11:22:08', '2026-01-10 11:22:08'),
(44, 1, 'update', 'settings', 'General settings updated', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:24:31', '2026-01-10 11:24:31'),
(45, 1, 'create', 'tools', 'Bulk banned 1 emails, restored 1', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:50:59', '2026-01-10 11:50:59'),
(46, 1, 'update', 'tools', 'Ban email updated: spam2@spam.com', '{\"email\":\"spam1@spam.com\",\"reason\":\"Spam Email\"}', '[]', 5, 'App\\Models\\BannedEmail', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:51:29', '2026-01-10 11:51:29'),
(47, 1, 'delete', 'tools', 'Email ban removed: spam2@spam.com', '{\"id\":5,\"email\":\"spam2@spam.com\",\"reason\":\"Spam Email\",\"added_by\":1,\"created_at\":\"2026-01-10T11:50:59.000000Z\",\"updated_at\":\"2026-01-10T11:51:29.000000Z\",\"deleted_at\":null}', NULL, 5, 'App\\Models\\BannedEmail', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:51:42', '2026-01-10 11:51:42'),
(48, 1, 'delete', 'tools', 'Email ban removed: spam@spam.com', '{\"id\":3,\"email\":\"spam@spam.com\",\"reason\":\"Spam Email\",\"added_by\":1,\"created_at\":\"2026-01-10T05:28:40.000000Z\",\"updated_at\":\"2026-01-10T11:50:59.000000Z\",\"deleted_at\":null}', NULL, 3, 'App\\Models\\BannedEmail', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:51:49', '2026-01-10 11:51:49'),
(49, 1, 'delete', 'users', 'User deleted: Khairunnisa Sabawi', '{\"name\":\"Khairunnisa Sabawi\",\"first_name\":\"Khairunnisa\",\"last_name\":\"Sabawi\",\"username\":\"khairuni90@gmail.com\",\"email\":\"khairuni90@gmail.com\",\"phone\":null,\"mobile\":null,\"address\":null,\"city\":null,\"state\":null,\"postcode\":null,\"country\":\"MY\",\"company_name\":null,\"company_registration\":null,\"company_phone\":null,\"company_email\":null,\"company_address\":null,\"company_website\":null,\"industry\":null,\"status\":\"active\",\"login_attempts\":0,\"locked_at\":null,\"suspended_at\":null,\"suspended_reason\":null,\"last_login_at\":\"2026-01-10T08:07:57.000000Z\",\"email_verified_at\":null,\"hash_link\":\"14ac1dc54753210f1ecd5b5ca38290344ac227f95cd6361de8bc7808bbc9622c\",\"role_id\":3,\"user_type\":\"customer\",\"two_factor_enabled\":false,\"two_factor_confirmed_at\":null,\"created_at\":\"2026-01-10T02:49:02.000000Z\",\"updated_at\":\"2026-01-10T08:07:57.000000Z\",\"deleted_at\":null,\"role\":{\"id\":3,\"name\":\"Customer\",\"slug\":\"customer\",\"description\":\"Customer with limited access to own tickets\",\"permissions\":[\"dashboard.view\",\"tickets.view\",\"tickets.create\",\"tickets.edit\",\"tickets.delete\",\"tickets.export\",\"knowledgebase_view.view\",\"reports.view\",\"reports.export\"],\"status\":\"active\",\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-10T06:59:43.000000Z\",\"deleted_at\":null}}', NULL, 6, 'App\\Models\\User', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:53:04', '2026-01-10 11:53:04'),
(50, 1, 'delete', 'users', 'User deleted: Administrator', '{\"name\":\"Administrator\",\"first_name\":\"Admin\",\"last_name\":\"User\",\"username\":\"administrator\",\"email\":\"admin@orien.local\",\"phone\":null,\"mobile\":null,\"address\":null,\"city\":null,\"state\":null,\"postcode\":null,\"country\":null,\"company_name\":null,\"company_registration\":null,\"company_phone\":null,\"company_email\":null,\"company_address\":null,\"company_website\":null,\"industry\":null,\"status\":\"active\",\"login_attempts\":0,\"locked_at\":null,\"suspended_at\":null,\"suspended_reason\":null,\"last_login_at\":\"2026-01-10T08:05:12.000000Z\",\"email_verified_at\":null,\"hash_link\":\"9b5f9a5edd311ddcb119bd18be03d83e510f1e6e1edf99c0259eeefaab94ac2b\",\"role_id\":1,\"user_type\":\"administrator\",\"two_factor_enabled\":false,\"two_factor_confirmed_at\":null,\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-10T08:05:12.000000Z\",\"deleted_at\":null,\"role\":{\"id\":1,\"name\":\"Administrator\",\"slug\":\"administrator\",\"description\":\"Full system access with all permissions\",\"permissions\":[\"dashboard.view\",\"tickets.view\",\"tickets.create\",\"tickets.edit\",\"tickets.delete\",\"tickets.manage\",\"knowledgebase_view.view\",\"knowledgebase_articles.view\",\"knowledgebase_articles.create\",\"knowledgebase_articles.edit\",\"knowledgebase_articles.delete\",\"knowledgebase_categories.view\",\"knowledgebase_categories.create\",\"knowledgebase_categories.edit\",\"knowledgebase_categories.delete\",\"reports.view\",\"reports.export\",\"tools_ban_emails.view\",\"tools_ban_emails.create\",\"tools_ban_emails.edit\",\"tools_ban_emails.delete\",\"tools_ban_emails.export\",\"tools_ban_ips.view\",\"tools_ban_ips.create\",\"tools_ban_ips.edit\",\"tools_ban_ips.delete\",\"tools_ban_ips.export\",\"tools_whitelist_ips.view\",\"tools_whitelist_ips.create\",\"tools_whitelist_ips.edit\",\"tools_whitelist_ips.delete\",\"tools_whitelist_ips.export\",\"tools_whitelist_emails.view\",\"tools_whitelist_emails.create\",\"tools_whitelist_emails.edit\",\"tools_whitelist_emails.delete\",\"tools_whitelist_emails.export\",\"tools_bad_words.view\",\"tools_bad_words.create\",\"tools_bad_words.edit\",\"tools_bad_words.delete\",\"tools_bad_words.export\",\"tools_bad_websites.view\",\"tools_bad_websites.create\",\"tools_bad_websites.edit\",\"tools_bad_websites.delete\",\"tools_bad_websites.export\",\"settings_general.view\",\"settings_general.edit\",\"settings_integrations_email.view\",\"settings_integrations_email.edit\",\"settings_integrations_telegram.view\",\"settings_integrations_telegram.edit\",\"settings_integrations_weather.view\",\"settings_integrations_weather.edit\",\"settings_integrations_api.view\",\"settings_integrations_api.edit\",\"settings_integrations_spam.view\",\"settings_integrations_spam.edit\",\"settings_integrations_recycle.view\",\"settings_integrations_recycle.delete\",\"settings_integrations_recycle.manage\",\"settings_ticket_categories.view\",\"settings_ticket_categories.create\",\"settings_ticket_categories.edit\",\"settings_ticket_categories.delete\",\"settings_priorities.view\",\"settings_priorities.create\",\"settings_priorities.edit\",\"settings_priorities.delete\",\"settings_status.view\",\"settings_status.create\",\"settings_status.edit\",\"settings_status.delete\",\"settings_sla.view\",\"settings_sla.create\",\"settings_sla.edit\",\"settings_sla.delete\",\"settings_email_templates.view\",\"settings_email_templates.create\",\"settings_email_templates.edit\",\"settings_email_templates.delete\",\"settings_roles.view\",\"settings_roles.create\",\"settings_roles.edit\",\"settings_roles.delete\",\"settings_users_admin.view\",\"settings_users_admin.create\",\"settings_users_admin.edit\",\"settings_users_admin.delete\",\"settings_users_admin.manage\",\"settings_users_agents.view\",\"settings_users_agents.create\",\"settings_users_agents.edit\",\"settings_users_agents.delete\",\"settings_users_agents.manage\",\"settings_users_customers.view\",\"settings_users_customers.create\",\"settings_users_customers.edit\",\"settings_users_customers.delete\",\"settings_users_customers.manage\",\"settings_activity_logs.view\",\"settings_activity_logs.delete\",\"settings_activity_logs.export\",\"settings_audit_logs.view\",\"settings_audit_logs.delete\",\"settings_audit_logs.export\"],\"status\":\"active\",\"created_at\":\"2026-01-09T09:05:18.000000Z\",\"updated_at\":\"2026-01-10T10:35:03.000000Z\",\"deleted_at\":null}}', NULL, 1, 'App\\Models\\User', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:53:09', '2026-01-10 11:53:09'),
(51, NULL, 'failed_login', 'auth', 'Failed login attempt for: admin@orien.local', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:54:37', '2026-01-10 11:54:37'),
(52, 2, 'login', 'auth', 'User logged in successfully', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:55:01', '2026-01-10 11:55:01'),
(53, 2, 'logout', 'auth', 'User logged out', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:56:20', '2026-01-10 11:56:20'),
(54, 7, 'login', 'auth', 'User logged in successfully', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:56:36', '2026-01-10 11:56:36'),
(55, 7, 'restore', 'users', 'User restored: Administrator', NULL, NULL, 1, 'App\\Models\\User', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:56:53', '2026-01-10 11:56:53'),
(56, 7, 'logout', 'auth', 'User logged out', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 13:30:11', '2026-01-10 13:30:11'),
(57, 7, 'login', 'auth', 'User logged in successfully', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 14:03:52', '2026-01-10 14:03:52'),
(58, 7, 'login', 'auth', 'User logged in successfully', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 14:04:07', '2026-01-10 14:04:07'),
(59, 7, 'logout', 'auth', 'User logged out', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 14:05:54', '2026-01-10 14:05:54');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `user_type` enum('administrator','agent','customer') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'view',
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'GET',
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `subject_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `request_data` json DEFAULT NULL,
  `response_code` int NOT NULL DEFAULT '200',
  `response_time_ms` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `user_type`, `action`, `module`, `route_name`, `url`, `method`, `subject_type`, `subject_id`, `subject_name`, `ip_address`, `user_agent`, `request_data`, `response_code`, `response_time_ms`, `created_at`) VALUES
(1, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=audit', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"audit\"}', 200, 31, '2026-01-10 09:09:54'),
(2, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=activity', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"activity\"}', 200, 22, '2026-01-10 09:09:57'),
(3, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=audit', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"audit\"}', 200, 13, '2026-01-10 09:10:05'),
(4, 1, 'administrator', 'view', 'dashboard', 'dashboard', 'http://localhost:8000', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 31, '2026-01-10 09:10:21'),
(5, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 47, '2026-01-10 09:10:21'),
(6, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 33, '2026-01-10 09:10:23'),
(7, 1, 'administrator', 'view', 'reports', 'reports.index', 'http://localhost:8000/reports', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 34, '2026-01-10 09:10:23'),
(8, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 10, '2026-01-10 09:10:24'),
(9, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?search=&tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\", \"search\": null}', 200, 20, '2026-01-10 09:10:30'),
(10, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=whitelist-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-ip\"}', 200, 17, '2026-01-10 09:10:31'),
(11, 1, 'administrator', 'view', 'reports', 'reports.index', 'http://localhost:8000/reports', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 33, '2026-01-10 09:10:32'),
(12, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 38, '2026-01-10 09:10:35'),
(13, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=email-templates', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"email-templates\"}', 200, 29, '2026-01-10 09:10:36'),
(14, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=sla-rules', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"sla-rules\"}', 200, 39, '2026-01-10 09:10:37'),
(15, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=status', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"status\"}', 200, 18, '2026-01-10 09:10:37'),
(16, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 28, '2026-01-10 09:10:38'),
(17, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=audit', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"audit\"}', 200, 20, '2026-01-10 09:10:40'),
(18, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?datetime_from=2026-01-10T17%3A09&datetime_to=2026-01-10T17%3A10&tab=audit&user_id=', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"audit\", \"user_id\": null, \"datetime_to\": \"2026-01-10T17:10\", \"datetime_from\": \"2026-01-10T17:09\"}', 200, 26, '2026-01-10 09:10:48'),
(19, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 14, '2026-01-10 09:11:06'),
(20, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 13, '2026-01-10 09:11:08'),
(21, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 21, '2026-01-10 09:11:10'),
(22, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 37, '2026-01-10 09:11:10'),
(23, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 15, '2026-01-10 09:11:11'),
(24, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 21, '2026-01-10 09:11:15'),
(25, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 50, '2026-01-10 09:14:27'),
(26, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=audit', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"audit\"}', 200, 12, '2026-01-10 09:14:34'),
(27, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=activity', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"activity\"}', 200, 37, '2026-01-10 09:14:36'),
(28, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 15, '2026-01-10 09:15:12'),
(29, 1, 'administrator', 'update', 'settings', 'settings.general.save', 'http://localhost:8000/settings/general', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"timezone\": \"Asia/Kuala_Lumpur\", \"date_format\": \"d M Y\", \"system_name\": \"ORIEN Helpdesk\", \"time_format\": \"H:i:s\", \"company_name\": \"ORIEN NET SOLUTIONS SDN BHD\", \"company_email\": \"info@orien.com.my\", \"company_phone\": \"0389381811\", \"company_address\": \"No. C-6-2 Blok C Putra Walk, Jalan PP 25, Tmn. Pinggiran Putra,\\\\r\\\\nSek. 2, Bandar Putra Permai, 43300 Seri Kembangan Selangor.\", \"company_website\": \"https://www.orien.com.my\", \"pagination_size\": \"10\", \"session_timeout\": \"120\", \"lockout_duration\": \"15\", \"allowed_file_types\": \"pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip\", \"company_short_name\": \"ORIEN\", \"company_ssm_number\": \"883498-M\", \"email_user_created\": \"1\", \"max_login_attempts\": \"5\", \"attachment_max_size\": \"10\", \"password_min_length\": \"8\", \"email_ticket_created\": \"1\", \"email_ticket_replied\": \"1\", \"email_ticket_assigned\": \"1\", \"ticket_auto_close_days\": \"7\", \"email_ticket_status_changed\": \"1\"}', 302, 29, '2026-01-10 09:15:24'),
(30, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 11, '2026-01-10 09:15:24'),
(31, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 31, '2026-01-10 09:15:37'),
(32, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=sla-rules', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"sla-rules\"}', 200, 38, '2026-01-10 09:15:38'),
(33, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=status', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"status\"}', 200, 44, '2026-01-10 09:15:38'),
(34, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=priorities', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"priorities\"}', 200, 17, '2026-01-10 09:15:40'),
(35, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=categories', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"categories\"}', 200, 24, '2026-01-10 09:15:41'),
(36, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=sla-rules', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"sla-rules\"}', 200, 35, '2026-01-10 09:15:42'),
(37, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 20, '2026-01-10 09:15:47'),
(38, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 39, '2026-01-10 09:17:27'),
(39, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 18, '2026-01-10 09:17:33'),
(40, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 84, '2026-01-10 09:24:48'),
(41, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 13, '2026-01-10 09:24:50'),
(42, 1, 'administrator', 'view', 'roles', 'settings.roles', 'http://localhost:8000/settings/roles', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 46, '2026-01-10 09:27:16'),
(43, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 20, '2026-01-10 09:27:24'),
(44, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 56, '2026-01-10 09:27:27'),
(45, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=audit', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"audit\"}', 200, 21, '2026-01-10 09:27:30'),
(46, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 28, '2026-01-10 09:27:33'),
(47, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 20, '2026-01-10 09:29:18'),
(48, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 325, '2026-01-10 09:31:19'),
(49, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"email\"}', 200, 246, '2026-01-10 09:31:21'),
(50, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 494, '2026-01-10 09:39:28'),
(51, 1, 'administrator', 'view', 'users', 'settings.users.edit', 'http://localhost:8000/settings/users/9b5f9a5edd311ddcb119bd18be03d83e510f1e6e1edf99c0259eeefaab94ac2b/edit', 'GET', 'App\\Models\\User', 1, 'Administrator', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 132, '2026-01-10 09:39:30'),
(52, 1, 'administrator', 'update', 'users', 'settings.users.update', 'http://localhost:8000/settings/users/9b5f9a5edd311ddcb119bd18be03d83e510f1e6e1edf99c0259eeefaab94ac2b', 'PUT', 'App\\Models\\User', 1, 'Administrator', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"city\": null, \"email\": \"faizanrahman84@gmail.com\", \"phone\": null, \"state\": null, \"mobile\": null, \"status\": \"active\", \"address\": null, \"country\": null, \"role_id\": \"1\", \"industry\": null, \"postcode\": null, \"username\": \"administrator\", \"last_name\": \"User\", \"first_name\": \"Admin\", \"company_name\": null, \"company_email\": \"faizanrahman84@gmail.com\", \"company_phone\": null, \"company_address\": null, \"company_website\": null, \"company_registration\": null}', 302, 215, '2026-01-10 09:39:46'),
(53, 1, 'administrator', 'view', 'users', 'settings.users.edit', 'http://localhost:8000/settings/users/9b5f9a5edd311ddcb119bd18be03d83e510f1e6e1edf99c0259eeefaab94ac2b/edit', 'GET', 'App\\Models\\User', 1, 'Administrator', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 365, '2026-01-10 09:39:46'),
(54, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 308, '2026-01-10 09:39:49'),
(55, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users?tab=agents', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"agents\"}', 200, 402, '2026-01-10 09:39:53'),
(56, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users?tab=customers', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"customers\"}', 200, 300, '2026-01-10 09:39:55'),
(57, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 278, '2026-01-10 09:39:59'),
(58, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 604, '2026-01-10 09:40:00'),
(59, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/1', 'GET', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 944, '2026-01-10 09:40:03'),
(60, 1, 'administrator', 'reply', 'tickets', 'tickets.reply', 'http://localhost:8000/tickets/1/reply', 'POST', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"message\": \"ok faham\", \"working_time\": null}', 302, 993, '2026-01-10 09:40:08'),
(61, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/1', 'GET', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 601, '2026-01-10 09:40:09'),
(62, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 169, '2026-01-10 09:40:12'),
(63, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 305, '2026-01-10 09:40:16'),
(64, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=email-templates', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"email-templates\"}', 200, 265, '2026-01-10 09:40:17'),
(65, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 960, '2026-01-10 09:46:12'),
(66, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/1', 'GET', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 818, '2026-01-10 09:46:14'),
(67, 1, 'administrator', 'reply', 'tickets', 'tickets.reply', 'http://localhost:8000/tickets/1/reply', 'POST', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"message\": \"notification email\", \"working_time\": null}', 302, 2277, '2026-01-10 09:46:25'),
(68, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/1', 'GET', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 937, '2026-01-10 09:46:26'),
(69, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 722, '2026-01-10 10:09:09'),
(70, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=audit', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"audit\"}', 200, 269, '2026-01-10 10:09:11'),
(71, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?datetime_from=2026-01-10T17%3A40&datetime_to=2026-01-10T18%3A09&tab=audit&user_id=', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"audit\", \"user_id\": null, \"datetime_to\": \"2026-01-10T18:09\", \"datetime_from\": \"2026-01-10T17:40\"}', 200, 621, '2026-01-10 10:09:28'),
(72, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 332, '2026-01-10 10:13:03'),
(73, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 905, '2026-01-10 10:27:52'),
(74, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/1', 'GET', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 792, '2026-01-10 10:27:55'),
(75, 1, 'administrator', 'reply', 'tickets', 'tickets.reply', 'http://localhost:8000/tickets/1/reply', 'POST', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"message\": \"Update Email Notification\", \"working_time\": null}', 302, 2213, '2026-01-10 10:28:05'),
(76, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/1', 'GET', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 405, '2026-01-10 10:28:06'),
(77, 1, 'administrator', 'view', 'roles', 'settings.roles', 'http://localhost:8000/settings/roles', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 566, '2026-01-10 10:29:22'),
(78, 1, 'administrator', 'view', 'roles', 'settings.roles.create', 'http://localhost:8000/settings/roles/create', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 191, '2026-01-10 10:30:07'),
(79, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 190, '2026-01-10 10:34:46'),
(80, 1, 'administrator', 'view', 'roles', 'settings.roles', 'http://localhost:8000/settings/roles', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 618, '2026-01-10 10:34:50'),
(81, 1, 'administrator', 'view', 'roles', 'settings.roles.edit', 'http://localhost:8000/settings/roles/1/edit', 'GET', 'App\\Models\\Role', 1, 'Administrator', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 314, '2026-01-10 10:34:52'),
(82, 1, 'administrator', 'update', 'roles', 'settings.roles.update', 'http://localhost:8000/settings/roles/1', 'PUT', 'App\\Models\\Role', 1, 'Administrator', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"name\": \"Administrator\", \"status\": \"active\", \"description\": \"Full system access with all permissions\", \"permissions\": [\"dashboard.view\", \"tickets.view\", \"tickets.create\", \"tickets.edit\", \"tickets.delete\", \"tickets.manage\", \"knowledgebase_view.view\", \"knowledgebase_articles.view\", \"knowledgebase_articles.create\", \"knowledgebase_articles.edit\", \"knowledgebase_articles.delete\", \"knowledgebase_categories.view\", \"knowledgebase_categories.create\", \"knowledgebase_categories.edit\", \"knowledgebase_categories.delete\", \"reports.view\", \"reports.export\", \"tools_ban_emails.view\", \"tools_ban_emails.create\", \"tools_ban_emails.edit\", \"tools_ban_emails.delete\", \"tools_ban_emails.export\", \"tools_ban_ips.view\", \"tools_ban_ips.create\", \"tools_ban_ips.edit\", \"tools_ban_ips.delete\", \"tools_ban_ips.export\", \"tools_whitelist_ips.view\", \"tools_whitelist_ips.create\", \"tools_whitelist_ips.edit\", \"tools_whitelist_ips.delete\", \"tools_whitelist_ips.export\", \"tools_whitelist_emails.view\", \"tools_whitelist_emails.create\", \"tools_whitelist_emails.edit\", \"tools_whitelist_emails.delete\", \"tools_whitelist_emails.export\", \"tools_bad_words.view\", \"tools_bad_words.create\", \"tools_bad_words.edit\", \"tools_bad_words.delete\", \"tools_bad_words.export\", \"tools_bad_websites.view\", \"tools_bad_websites.create\", \"tools_bad_websites.edit\", \"tools_bad_websites.delete\", \"tools_bad_websites.export\", \"settings_general.view\", \"settings_general.edit\", \"settings_integrations_email.view\", \"settings_integrations_email.edit\", \"settings_integrations_telegram.view\", \"settings_integrations_telegram.edit\", \"settings_integrations_weather.view\", \"settings_integrations_weather.edit\", \"settings_integrations_api.view\", \"settings_integrations_api.edit\", \"settings_integrations_spam.view\", \"settings_integrations_spam.edit\", \"settings_integrations_recycle.view\", \"settings_integrations_recycle.delete\", \"settings_integrations_recycle.manage\", \"settings_ticket_categories.view\", \"settings_ticket_categories.create\", \"settings_ticket_categories.edit\", \"settings_ticket_categories.delete\", \"settings_priorities.view\", \"settings_priorities.create\", \"settings_priorities.edit\", \"settings_priorities.delete\", \"settings_status.view\", \"settings_status.create\", \"settings_status.edit\", \"settings_status.delete\", \"settings_sla.view\", \"settings_sla.create\", \"settings_sla.edit\", \"settings_sla.delete\", \"settings_email_templates.view\", \"settings_email_templates.create\", \"settings_email_templates.edit\", \"settings_email_templates.delete\", \"settings_roles.view\", \"settings_roles.create\", \"settings_roles.edit\", \"settings_roles.delete\", \"settings_users_admin.view\", \"settings_users_admin.create\", \"settings_users_admin.edit\", \"settings_users_admin.delete\", \"settings_users_admin.manage\", \"settings_users_agents.view\", \"settings_users_agents.create\", \"settings_users_agents.edit\", \"settings_users_agents.delete\", \"settings_users_agents.manage\", \"settings_users_customers.view\", \"settings_users_customers.create\", \"settings_users_customers.edit\", \"settings_users_customers.delete\", \"settings_users_customers.manage\", \"settings_activity_logs.view\", \"settings_activity_logs.delete\", \"settings_activity_logs.export\", \"settings_audit_logs.view\", \"settings_audit_logs.delete\", \"settings_audit_logs.export\"]}', 302, 204, '2026-01-10 10:35:03'),
(83, 1, 'administrator', 'view', 'roles', 'settings.roles', 'http://localhost:8000/settings/roles', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 267, '2026-01-10 10:35:04'),
(84, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 244, '2026-01-10 10:35:05'),
(85, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 192, '2026-01-10 10:35:06'),
(86, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 233, '2026-01-10 10:40:07'),
(87, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"email\"}', 200, 375, '2026-01-10 10:40:24'),
(88, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 132, '2026-01-10 10:40:38'),
(89, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"email\"}', 200, 293, '2026-01-10 10:40:42'),
(90, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=weather', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"weather\"}', 200, 286, '2026-01-10 10:40:44'),
(91, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=api', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"api\"}', 200, 176, '2026-01-10 10:40:48'),
(92, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 107, '2026-01-10 10:40:49'),
(93, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"email\"}', 200, 250, '2026-01-10 10:41:19'),
(94, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 101, '2026-01-10 10:41:21'),
(95, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 181, '2026-01-10 10:42:24'),
(96, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 79, '2026-01-10 10:42:57'),
(97, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 140, '2026-01-10 10:43:46'),
(98, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"email\"}', 200, 199, '2026-01-10 10:43:51'),
(99, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 116, '2026-01-10 10:43:52'),
(100, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 170, '2026-01-10 10:43:58'),
(101, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 609, '2026-01-10 10:44:17'),
(102, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/1', 'GET', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 862, '2026-01-10 10:44:19'),
(103, 1, 'administrator', 'status_change', 'tickets', 'tickets.updateStatus', 'http://localhost:8000/tickets/1/update-status', 'POST', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"status_id\": \"5\"}', 302, 2213, '2026-01-10 10:44:25'),
(104, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/1', 'GET', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 719, '2026-01-10 10:44:26'),
(105, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 152, '2026-01-10 10:44:36'),
(106, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 160, '2026-01-10 10:44:38'),
(107, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 441, '2026-01-10 10:45:08'),
(108, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs?tab=activity', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"activity\"}', 200, 366, '2026-01-10 10:45:14'),
(109, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 372, '2026-01-10 10:45:59'),
(110, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 248, '2026-01-10 10:46:00'),
(111, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 66, '2026-01-10 10:53:11'),
(112, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 211, '2026-01-10 10:53:17'),
(113, 1, 'administrator', 'delete', 'categories', 'settings.categories.category.destroy', 'http://localhost:8000/settings/categories/category/3', 'DELETE', 'App\\Models\\TicketCategory', 3, 'Billing', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1431, '2026-01-10 10:53:25'),
(114, 1, 'administrator', 'view', 'categories', 'settings.categories', 'http://localhost:8000/settings/categories?tab=categories', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"categories\"}', 200, 311, '2026-01-10 10:53:26'),
(115, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 183, '2026-01-10 10:53:33'),
(116, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=recycle-bin', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"recycle-bin\"}', 200, 260, '2026-01-10 10:53:34'),
(117, 1, 'administrator', 'restore', 'recycle_bin', 'recycle-bin.restore', 'http://localhost:8000/recycle-bin/ticket_categories/3/restore', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 223, '2026-01-10 10:53:39'),
(118, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=recycle-bin', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"recycle-bin\"}', 200, 397, '2026-01-10 10:53:39'),
(119, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 622, '2026-01-10 10:53:46'),
(120, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 545, '2026-01-10 10:57:21'),
(121, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 252, '2026-01-10 10:57:23'),
(122, 1, 'administrator', 'update', 'settings', 'settings.general.save', 'http://localhost:8000/settings/general', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"timezone\": \"Asia/Kuala_Lumpur\", \"date_format\": \"d M Y\", \"system_name\": \"ORIEN Helpdesk\", \"time_format\": \"H:i:s\", \"company_name\": \"ORIEN NET SOLUTIONS SDN BHD\", \"company_email\": \"info@orien.com.my\", \"company_phone\": \"0389381811\", \"company_address\": \"No. C-6-2 Blok C Putra Walk, Jalan PP 25, Tmn. Pinggiran Putra,\\\\r\\\\nSek. 2, Bandar Putra Permai, 43300 Seri Kembangan Selangor.\", \"company_website\": \"https://www.orien.com.my\", \"pagination_size\": \"10\", \"session_timeout\": \"120\", \"lockout_duration\": \"15\", \"allowed_file_types\": \"pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip\", \"company_short_name\": \"ORIEN\", \"company_ssm_number\": \"883498-M\", \"email_user_created\": \"1\", \"max_login_attempts\": \"5\", \"attachment_max_size\": \"5\", \"password_min_length\": \"8\", \"email_ticket_created\": \"1\", \"email_ticket_replied\": \"1\", \"email_ticket_assigned\": \"1\", \"ticket_auto_close_days\": \"7\", \"email_ticket_status_changed\": \"1\"}', 302, 1730, '2026-01-10 10:57:41'),
(123, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 249, '2026-01-10 10:57:42'),
(124, 1, 'administrator', 'view', 'reports', 'reports.index', 'http://localhost:8000/reports', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 838, '2026-01-10 10:57:46'),
(125, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 320, '2026-01-10 10:57:48'),
(126, 1, 'administrator', 'delete', 'tools', 'tools.ban-email.destroy', 'http://localhost:8000/tools/ban-email/3', 'DELETE', 'App\\Models\\BannedEmail', 3, 'spam@spam.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1129, '2026-01-10 10:57:57'),
(127, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\"}', 200, 256, '2026-01-10 10:57:57'),
(128, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 235, '2026-01-10 10:58:06'),
(129, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=recycle-bin', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"recycle-bin\"}', 200, 717, '2026-01-10 10:58:08'),
(130, 1, 'administrator', 'restore', 'recycle_bin', 'recycle-bin.restore', 'http://localhost:8000/recycle-bin/banned_emails/3/restore', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1006, '2026-01-10 10:58:12'),
(131, 1, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=recycle-bin', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"recycle-bin\"}', 200, 222, '2026-01-10 10:58:12'),
(132, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 101, '2026-01-10 10:58:14'),
(133, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-word', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-word\"}', 200, 86, '2026-01-10 10:58:15');
INSERT INTO `audit_logs` (`id`, `user_id`, `user_type`, `action`, `module`, `route_name`, `url`, `method`, `subject_type`, `subject_id`, `subject_name`, `ip_address`, `user_agent`, `request_data`, `response_code`, `response_time_ms`, `created_at`) VALUES
(134, 1, 'administrator', 'create', 'tools', 'tools.bad-word.store', 'http://localhost:8000/tools/bad-word', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"word\": \"babi\", \"reason\": null, \"severity\": \"high\"}', 302, 1070, '2026-01-10 10:58:23'),
(135, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-word', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-word\"}', 200, 258, '2026-01-10 10:58:24'),
(136, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 564, '2026-01-10 10:58:31'),
(137, 3, 'customer', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', NULL, 200, 698, '2026-01-10 10:58:46'),
(138, 3, 'customer', 'view', 'tickets', 'tickets.create', 'http://localhost:8000/tickets/create', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', NULL, 200, 216, '2026-01-10 10:58:49'),
(139, 3, 'customer', 'create', 'tickets', 'tickets.store', 'http://localhost:8000/tickets', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '{\"subject\": \"Email Notification and Telegram\", \"category_id\": \"2\", \"description\": \"Email Notification and Telegram\", \"priority_id\": \"4\"}', 302, 4136, '2026-01-10 10:59:12'),
(140, 3, 'customer', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', NULL, 200, 523, '2026-01-10 10:59:13'),
(141, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 786, '2026-01-10 10:59:31'),
(142, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/2', 'GET', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 670, '2026-01-10 10:59:33'),
(143, 1, 'administrator', 'reply', 'tickets', 'tickets.reply', 'http://localhost:8000/tickets/2/reply', 'POST', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"message\": \"Receive\", \"working_time\": null}', 302, 4189, '2026-01-10 10:59:42'),
(144, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/2', 'GET', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 507, '2026-01-10 10:59:43'),
(145, 1, 'administrator', 'reply', 'tickets', 'tickets.reply', 'http://localhost:8000/tickets/2/reply', 'POST', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"message\": \"Faham\", \"working_time\": null, \"is_internal_note\": \"1\"}', 302, 2084, '2026-01-10 10:59:56'),
(146, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/2', 'GET', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 667, '2026-01-10 10:59:57'),
(147, 1, 'administrator', 'assign', 'tickets', 'tickets.assign', 'http://localhost:8000/tickets/2/assign', 'POST', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"assigned_to\": [\"2\"]}', 302, 2343, '2026-01-10 11:00:05'),
(148, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/2', 'GET', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 693, '2026-01-10 11:00:06'),
(149, 1, 'administrator', 'status_change', 'tickets', 'tickets.updateStatus', 'http://localhost:8000/tickets/2/update-status', 'POST', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"status_id\": \"2\"}', 302, 2814, '2026-01-10 11:00:13'),
(150, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/2', 'GET', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 59, '2026-01-10 11:00:13'),
(151, 1, 'administrator', 'status_change', 'tickets', 'tickets.updateStatus', 'http://localhost:8000/tickets/2/update-status', 'POST', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"status_id\": \"5\"}', 302, 2139, '2026-01-10 11:00:26'),
(152, 1, 'administrator', 'view', 'tickets', 'tickets.show', 'http://localhost:8000/tickets/2', 'GET', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 699, '2026-01-10 11:00:27'),
(153, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 250, '2026-01-10 11:00:53'),
(154, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 264, '2026-01-10 11:00:57'),
(155, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users?tab=customers', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"customers\"}', 200, 365, '2026-01-10 11:00:59'),
(156, 1, 'administrator', 'view', 'activity_logs', 'settings.activity-logs', 'http://localhost:8000/settings/activity-logs', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 390, '2026-01-10 11:01:02'),
(157, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 244, '2026-01-10 11:01:09'),
(158, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 237, '2026-01-10 11:01:20'),
(159, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\"}', 200, 216, '2026-01-10 11:02:08'),
(160, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-ip\"}', 200, 231, '2026-01-10 11:02:15'),
(161, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-word', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-word\"}', 200, 237, '2026-01-10 11:02:18'),
(162, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-website', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-website\"}', 200, 204, '2026-01-10 11:02:22'),
(163, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=whitelist-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-ip\"}', 200, 196, '2026-01-10 11:03:32'),
(164, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=whitelist-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-email\"}', 200, 244, '2026-01-10 11:03:38'),
(165, 1, 'administrator', 'view', 'roles', 'settings.roles', 'http://localhost:8000/settings/roles', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 557, '2026-01-10 11:04:30'),
(166, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 295, '2026-01-10 11:04:32'),
(167, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets?tab=assigned-to-others', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"assigned-to-others\"}', 200, 595, '2026-01-10 11:04:40'),
(168, 1, 'administrator', 'delete', 'tickets', 'tickets.destroy', 'http://localhost:8000/tickets/1', 'DELETE', NULL, 1, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1240, '2026-01-10 11:04:47'),
(169, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 331, '2026-01-10 11:04:47'),
(170, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets?tab=assigned-to-others', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"assigned-to-others\"}', 200, 464, '2026-01-10 11:04:49'),
(171, 1, 'administrator', 'delete', 'tickets', 'tickets.destroy', 'http://localhost:8000/tickets/2', 'DELETE', NULL, 2, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1251, '2026-01-10 11:04:54'),
(172, 1, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 469, '2026-01-10 11:04:55'),
(173, 1, 'administrator', 'view', 'knowledgebase', 'knowledgebase.index', 'http://localhost:8000/knowledgebase', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 269, '2026-01-10 11:04:57'),
(174, 1, 'administrator', 'view', 'knowledgebase', 'knowledgebase.settings', 'http://localhost:8000/knowledgebase/settings', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 327, '2026-01-10 11:04:58'),
(175, 1, 'administrator', 'view', 'reports', 'reports.index', 'http://localhost:8000/reports', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 653, '2026-01-10 11:04:59'),
(176, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 203, '2026-01-10 11:05:01'),
(177, 1, 'administrator', 'delete', 'tools', 'tools.ban-email.destroy', 'http://localhost:8000/tools/ban-email/3', 'DELETE', 'App\\Models\\BannedEmail', 3, 'spam@spam.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1171, '2026-01-10 11:05:09'),
(178, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\"}', 200, 100, '2026-01-10 11:05:09'),
(179, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-ip\"}', 200, 266, '2026-01-10 11:05:10'),
(180, 1, 'administrator', 'delete', 'tools', 'tools.ban-ip.destroy', 'http://localhost:8000/tools/ban-ip/1', 'DELETE', 'App\\Models\\BannedIp', 1, '#1', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1365, '2026-01-10 11:05:16'),
(181, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-ip\"}', 200, 45, '2026-01-10 11:05:17'),
(182, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=whitelist-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-ip\"}', 200, 122, '2026-01-10 11:05:19'),
(183, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=whitelist-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-email\"}', 200, 272, '2026-01-10 11:05:20'),
(184, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-word', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-word\"}', 200, 213, '2026-01-10 11:05:21'),
(185, 1, 'administrator', 'delete', 'tools', 'tools.bad-word.destroy', 'http://localhost:8000/tools/bad-word/3', 'DELETE', 'App\\Models\\BadWord', 3, '#3', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1207, '2026-01-10 11:05:27'),
(186, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-word', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-word\"}', 200, 174, '2026-01-10 11:05:27'),
(187, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-website', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-website\"}', 200, 345, '2026-01-10 11:05:29'),
(188, 1, 'administrator', 'delete', 'tools', 'tools.bad-website.destroy', 'http://localhost:8000/tools/bad-website/1', 'DELETE', 'App\\Models\\BadWebsite', 1, '#1', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1187, '2026-01-10 11:05:36'),
(189, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-website', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-website\"}', 200, 207, '2026-01-10 11:05:36'),
(190, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-word', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-word\"}', 200, 266, '2026-01-10 11:05:37'),
(191, 1, 'administrator', 'delete', 'tools', 'tools.bad-word.destroy', 'http://localhost:8000/tools/bad-word/2', 'DELETE', 'App\\Models\\BadWord', 2, '#2', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1204, '2026-01-10 11:05:47'),
(192, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-word', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-word\"}', 200, 299, '2026-01-10 11:05:47'),
(193, 1, 'administrator', 'view', 'reports', 'reports.index', 'http://localhost:8000/reports', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 332, '2026-01-10 11:06:08'),
(194, 1, 'administrator', 'export', 'reports', 'reports.export', 'http://localhost:8000/reports/export', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"status_id\": null, \"account_id\": null, \"priority_id\": null, \"end_datetime\": \"2026-01-31T23:59\", \"start_datetime\": \"2026-01-01T00:00\"}', 200, 1532, '2026-01-10 11:06:12'),
(195, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 462, '2026-01-10 11:08:25'),
(196, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 842, '2026-01-10 11:11:56'),
(197, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 433, '2026-01-10 11:14:10'),
(198, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 280, '2026-01-10 11:14:19'),
(199, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 285, '2026-01-10 11:14:24'),
(200, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 361, '2026-01-10 11:16:07'),
(201, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 364, '2026-01-10 11:16:17'),
(202, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 583, '2026-01-10 11:18:59'),
(203, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 494, '2026-01-10 11:21:50'),
(204, 1, 'administrator', 'view', 'dashboard', 'dashboard', 'http://localhost:8000', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 195, '2026-01-10 11:21:55'),
(205, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 167, '2026-01-10 11:21:57'),
(206, 1, 'administrator', 'update', 'settings', 'settings.general.save', 'http://localhost:8000/settings/general', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"timezone\": \"Asia/Kuala_Lumpur\", \"hero_image\": {}, \"date_format\": \"d M Y\", \"system_name\": \"ORIEN Helpdesk\", \"time_format\": \"H:i:s\", \"company_name\": \"ORIEN NET SOLUTIONS SDN BHD\", \"company_email\": \"info@orien.com.my\", \"company_phone\": \"0389381811\", \"company_address\": \"No. C-6-2 Blok C Putra Walk, Jalan PP 25, Tmn. Pinggiran Putra,\\\\r\\\\nSek. 2, Bandar Putra Permai, 43300 Seri Kembangan Selangor.\", \"company_website\": \"https://www.orien.com.my\", \"pagination_size\": \"10\", \"session_timeout\": \"120\", \"lockout_duration\": \"15\", \"allowed_file_types\": \"pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip\", \"company_short_name\": \"ORIEN\", \"company_ssm_number\": \"883498-M\", \"email_user_created\": \"1\", \"max_login_attempts\": \"5\", \"attachment_max_size\": \"5\", \"password_min_length\": \"8\", \"email_ticket_created\": \"1\", \"email_ticket_replied\": \"1\", \"email_ticket_assigned\": \"1\", \"ticket_auto_close_days\": \"7\", \"email_ticket_status_changed\": \"1\"}', 302, 1645, '2026-01-10 11:22:05'),
(207, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 184, '2026-01-10 11:22:05'),
(208, 1, 'administrator', 'update', 'settings', 'settings.general.save', 'http://localhost:8000/settings/general', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"logo\": {}, \"favicon\": {}, \"timezone\": \"Asia/Kuala_Lumpur\", \"date_format\": \"d M Y\", \"system_name\": \"ORIEN Helpdesk\", \"time_format\": \"H:i:s\", \"company_name\": \"ORIEN NET SOLUTIONS SDN BHD\", \"company_email\": \"info@orien.com.my\", \"company_phone\": \"0389381811\", \"company_address\": \"No. C-6-2 Blok C Putra Walk, Jalan PP 25, Tmn. Pinggiran Putra,\\\\r\\\\nSek. 2, Bandar Putra Permai, 43300 Seri Kembangan Selangor.\", \"company_website\": \"https://www.orien.com.my\", \"pagination_size\": \"10\", \"session_timeout\": \"120\", \"lockout_duration\": \"15\", \"allowed_file_types\": \"pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip\", \"company_short_name\": \"ORIEN\", \"company_ssm_number\": \"883498-M\", \"email_user_created\": \"1\", \"max_login_attempts\": \"5\", \"attachment_max_size\": \"5\", \"password_min_length\": \"8\", \"email_ticket_created\": \"1\", \"email_ticket_replied\": \"1\", \"email_ticket_assigned\": \"1\", \"ticket_auto_close_days\": \"7\", \"email_ticket_status_changed\": \"1\"}', 302, 2338, '2026-01-10 11:24:32'),
(209, 1, 'administrator', 'view', 'settings', 'settings.general', 'http://localhost:8000/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 354, '2026-01-10 11:24:32'),
(210, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 497, '2026-01-10 11:25:08'),
(211, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 346, '2026-01-10 11:27:53'),
(212, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 432, '2026-01-10 11:50:43'),
(213, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\"}', 200, 289, '2026-01-10 11:51:00'),
(214, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-ip\"}', 200, 168, '2026-01-10 11:51:21'),
(215, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\"}', 200, 203, '2026-01-10 11:51:22'),
(216, 1, 'administrator', 'update', 'tools', 'tools.ban-email.update', 'http://localhost:8000/tools/ban-email/5', 'PUT', 'App\\Models\\BannedEmail', 5, 'spam2@spam.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"email\": \"spam2@spam.com\", \"reason\": \"Spam Email\"}', 302, 1193, '2026-01-10 11:51:30'),
(217, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\"}', 200, 203, '2026-01-10 11:51:30'),
(218, 1, 'administrator', 'delete', 'tools', 'tools.ban-email.destroy', 'http://localhost:8000/tools/ban-email/5', 'DELETE', 'App\\Models\\BannedEmail', 5, 'spam2@spam.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1095, '2026-01-10 11:51:43'),
(219, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\"}', 200, 269, '2026-01-10 11:51:44'),
(220, 1, 'administrator', 'delete', 'tools', 'tools.ban-email.destroy', 'http://localhost:8000/tools/ban-email/3', 'DELETE', 'App\\Models\\BannedEmail', 3, 'spam@spam.com', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1056, '2026-01-10 11:51:50'),
(221, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-email\"}', 200, 119, '2026-01-10 11:51:51'),
(222, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=ban-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-ip\"}', 200, 152, '2026-01-10 11:51:52'),
(223, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=whitelist-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-ip\"}', 200, 245, '2026-01-10 11:51:54'),
(224, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=whitelist-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-email\"}', 200, 109, '2026-01-10 11:51:55'),
(225, 1, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools?tab=bad-website', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-website\"}', 200, 133, '2026-01-10 11:51:56'),
(226, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 187, '2026-01-10 11:52:57'),
(227, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users?tab=customers', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"customers\"}', 200, 265, '2026-01-10 11:52:59'),
(228, 1, 'administrator', 'delete', 'users', 'settings.users.destroy', 'http://localhost:8000/settings/users/14ac1dc54753210f1ecd5b5ca38290344ac227f95cd6361de8bc7808bbc9622c', 'DELETE', 'App\\Models\\User', 6, 'Khairunnisa Sabawi', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1166, '2026-01-10 11:53:05'),
(229, 1, 'administrator', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 205, '2026-01-10 11:53:06'),
(230, 1, 'administrator', 'delete', 'users', 'settings.users.destroy', 'http://localhost:8000/settings/users/9b5f9a5edd311ddcb119bd18be03d83e510f1e6e1edf99c0259eeefaab94ac2b', 'DELETE', 'App\\Models\\User', 1, 'Administrator', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1061, '2026-01-10 11:53:10'),
(231, 2, 'agent', 'view', 'users', 'settings.users', 'http://localhost:8000/settings/users', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 403, 52, '2026-01-10 11:55:02'),
(232, 2, 'agent', 'view', 'dashboard', 'dashboard', 'http://localhost:8000', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 655, '2026-01-10 11:55:08'),
(233, 2, 'agent', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 246, '2026-01-10 11:55:10'),
(234, 7, 'administrator', 'view', 'dashboard', 'dashboard', 'http://localhost:8000', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 665, '2026-01-10 11:56:38'),
(235, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 390, '2026-01-10 11:56:43'),
(236, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=recycle-bin', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"recycle-bin\"}', 200, 948, '2026-01-10 11:56:46'),
(237, 7, 'administrator', 'restore', 'recycle_bin', 'recycle-bin.restore', 'http://localhost:8000/recycle-bin/users/1/restore', 'POST', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 302, 1125, '2026-01-10 11:56:54'),
(238, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://localhost:8000/settings/integrations?tab=recycle-bin', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"recycle-bin\"}', 200, 1123, '2026-01-10 11:56:56'),
(239, 7, 'administrator', 'view', 'tools', 'tools.index', 'http://localhost:8000/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 718, '2026-01-10 13:22:11'),
(240, 7, 'administrator', 'view', 'dashboard', 'dashboard', 'http://localhost:8000', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 594, '2026-01-10 13:24:40'),
(241, 7, 'administrator', 'view', 'tickets', 'tickets.index', 'http://localhost:8000/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 796, '2026-01-10 13:30:06'),
(242, 7, 'administrator', 'view', 'dashboard', 'dashboard', 'http://1ade55c392d4.ngrok-free.app', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 459, '2026-01-10 14:04:09'),
(243, 7, 'administrator', 'view', 'tickets', 'tickets.index', 'http://1ade55c392d4.ngrok-free.app/tickets', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 540, '2026-01-10 14:04:45'),
(244, 7, 'administrator', 'view', 'knowledgebase', 'knowledgebase.index', 'http://1ade55c392d4.ngrok-free.app/knowledgebase', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 352, '2026-01-10 14:04:47'),
(245, 7, 'administrator', 'view', 'knowledgebase', 'knowledgebase.settings', 'http://1ade55c392d4.ngrok-free.app/knowledgebase/settings', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 124, '2026-01-10 14:04:49'),
(246, 7, 'administrator', 'view', 'knowledgebase', 'knowledgebase.settings', 'http://1ade55c392d4.ngrok-free.app/knowledgebase/settings?tab=categories', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"categories\"}', 200, 266, '2026-01-10 14:04:54'),
(247, 7, 'administrator', 'view', 'reports', 'reports.index', 'http://1ade55c392d4.ngrok-free.app/reports', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 544, '2026-01-10 14:04:56'),
(248, 7, 'administrator', 'view', 'tools', 'tools.index', 'http://1ade55c392d4.ngrok-free.app/tools', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 279, '2026-01-10 14:04:59'),
(249, 7, 'administrator', 'view', 'tools', 'tools.index', 'http://1ade55c392d4.ngrok-free.app/tools?tab=ban-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"ban-ip\"}', 200, 96, '2026-01-10 14:05:01'),
(250, 7, 'administrator', 'view', 'tools', 'tools.index', 'http://1ade55c392d4.ngrok-free.app/tools?tab=whitelist-ip', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-ip\"}', 200, 211, '2026-01-10 14:05:02'),
(251, 7, 'administrator', 'view', 'tools', 'tools.index', 'http://1ade55c392d4.ngrok-free.app/tools?tab=whitelist-email', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"whitelist-email\"}', 200, 200, '2026-01-10 14:05:03'),
(252, 7, 'administrator', 'view', 'tools', 'tools.index', 'http://1ade55c392d4.ngrok-free.app/tools?tab=bad-word', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-word\"}', 200, 138, '2026-01-10 14:05:04'),
(253, 7, 'administrator', 'view', 'tools', 'tools.index', 'http://1ade55c392d4.ngrok-free.app/tools?tab=bad-website', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"bad-website\"}', 200, 125, '2026-01-10 14:05:05'),
(254, 7, 'administrator', 'view', 'settings', 'settings.general', 'http://1ade55c392d4.ngrok-free.app/settings/general', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 156, '2026-01-10 14:05:11'),
(255, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://1ade55c392d4.ngrok-free.app/settings/integrations', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 200, 181, '2026-01-10 14:05:15'),
(256, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://1ade55c392d4.ngrok-free.app/settings/integrations?tab=telegram', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"telegram\"}', 200, 62, '2026-01-10 14:05:17'),
(257, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://1ade55c392d4.ngrok-free.app/settings/integrations?tab=weather', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"weather\"}', 200, 159, '2026-01-10 14:05:19'),
(258, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://1ade55c392d4.ngrok-free.app/settings/integrations?tab=api', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"api\"}', 200, 111, '2026-01-10 14:05:21'),
(259, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://1ade55c392d4.ngrok-free.app/settings/integrations?tab=spam', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"spam\"}', 200, 277, '2026-01-10 14:05:23'),
(260, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://1ade55c392d4.ngrok-free.app/settings/integrations?tab=recycle-bin', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"recycle-bin\"}', 200, 796, '2026-01-10 14:05:25'),
(261, 7, 'administrator', 'empty', 'recycle_bin', 'recycle-bin.empty-all', 'http://1ade55c392d4.ngrok-free.app/recycle-bin/empty-all', 'DELETE', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', NULL, 500, 934, '2026-01-10 14:05:33'),
(262, 7, 'administrator', 'view', 'integrations', 'settings.integrations', 'http://1ade55c392d4.ngrok-free.app/settings/integrations?tab=recycle-bin', 'GET', NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '{\"tab\": \"recycle-bin\"}', 200, 484, '2026-01-10 14:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `bad_websites`
--

CREATE TABLE `bad_websites` (
  `id` bigint UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `severity` enum('low','medium','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `added_by` bigint UNSIGNED NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bad_words`
--

CREATE TABLE `bad_words` (
  `id` bigint UNSIGNED NOT NULL,
  `word` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `severity` enum('low','medium','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `added_by` bigint UNSIGNED NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banned_emails`
--

CREATE TABLE `banned_emails` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banned_ips`
--

CREATE TABLE `banned_ips` (
  `id` bigint UNSIGNED NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'notification',
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `slug`, `subject`, `type`, `body`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ticket Created', 'ticket-created', 'Your ticket #{{ticket_id}} has been created', 'notification', '<p>Dear {{customer_name}},</p><p>Thank you for contacting us. Your ticket <strong>#{{ticket_id}}</strong> has been created successfully.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Category:</strong> {{ticket_category}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p>Our team will review your request and respond as soon as possible.</p><p>You can track your ticket status by logging into your account.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to customer when a new ticket is created', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(2, 'Ticket Updated', 'ticket-updated', 'Your ticket #{{ticket_id}} has been updated', 'notification', '<p>Dear {{customer_name}},</p><p>Your ticket <strong>#{{ticket_id}}</strong> has been updated.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>New Status:</strong> {{ticket_status}}</p><p>Please login to view the latest updates and any responses from our team.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to customer when ticket status changes', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(3, 'Ticket Resolved', 'ticket-resolved', 'Your ticket #{{ticket_id}} has been resolved', 'notification', '<p>Dear {{customer_name}},</p><p>Great news! Your ticket <strong>#{{ticket_id}}</strong> has been resolved.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Resolution:</strong> {{resolution_notes}}</p><p>If you have any further questions or if the issue persists, please feel free to reopen the ticket or create a new one.</p><p>We would appreciate if you could take a moment to rate our support.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to customer when ticket is marked as resolved', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(4, 'Ticket Closed', 'ticket-closed', 'Your ticket #{{ticket_id}} has been closed', 'notification', '<p>Dear {{customer_name}},</p><p>Your ticket <strong>#{{ticket_id}}</strong> has been closed.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p>Thank you for using our support services. If you need further assistance, please don\'t hesitate to create a new ticket.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to customer when ticket is closed', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(5, 'New Reply from Agent', 'new-reply-from-agent', 'New reply on your ticket #{{ticket_id}}', 'notification', '<p>Dear {{customer_name}},</p><p>You have received a new reply on your ticket <strong>#{{ticket_id}}</strong>.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Reply from:</strong> {{agent_name}}</p><hr><p>{{reply_content}}</p><hr><p>Please login to your account to view the full conversation and respond.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to customer when agent replies to ticket', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(6, 'New Reply from Customer', 'new-reply-from-customer', '[Ticket #{{ticket_id}}] New reply from {{customer_name}}', 'notification', '<p>Hello {{agent_name}},</p><p>Customer <strong>{{customer_name}}</strong> has replied to ticket <strong>#{{ticket_id}}</strong>.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><hr><p>{{reply_content}}</p><hr><p>Please login to the helpdesk to respond.</p>', 'Sent to assigned agent when customer replies', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(7, 'Auto Reply', 'auto-reply', 'We received your message - Ticket #{{ticket_id}}', 'auto-reply', '<p>Dear {{customer_name}},</p><p>Thank you for your email. This is an automated response to confirm that we have received your message.</p><p>Your ticket number is <strong>#{{ticket_id}}</strong>. Please use this number for any future correspondence.</p><p>Our support team will respond within {{sla_response_time}}.</p><p>In the meantime, you may find answers to common questions in our <a href=\"{{knowledgebase_url}}\">Knowledge Base</a>.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Automatic response when ticket is created via email', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(8, 'Out of Office Auto Reply', 'out-of-office-auto-reply', 'We received your message - Currently Out of Office', 'auto-reply', '<p>Dear {{customer_name}},</p><p>Thank you for contacting us. Our office is currently closed.</p><p>Your ticket <strong>#{{ticket_id}}</strong> has been created and will be reviewed when we return.</p><p><strong>Business Hours:</strong> {{business_hours}}</p><p>For urgent matters, please call our emergency line: {{emergency_phone}}</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Auto response during non-business hours', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(9, 'SLA Escalation - First Response', 'sla-escalation-first-response', '[URGENT] Ticket #{{ticket_id}} - First Response SLA Breach', 'escalation', '<p><strong style=\"color: #dc2626;\">⚠️ SLA BREACH ALERT</strong></p><p>Ticket <strong>#{{ticket_id}}</strong> has breached its first response SLA and requires immediate attention.</p><p><strong>Customer:</strong> {{customer_name}}</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p><strong>Category:</strong> {{ticket_category}}</p><p><strong>Created:</strong> {{ticket_created_at}}</p><p><strong>Time Elapsed:</strong> {{time_elapsed}}</p><p><strong>SLA Target:</strong> {{sla_response_time}}</p><p>Please take action immediately.</p>', 'Sent when first response SLA is breached', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(10, 'SLA Escalation - Resolution', 'sla-escalation-resolution', '[CRITICAL] Ticket #{{ticket_id}} - Resolution SLA Breach', 'escalation', '<p><strong style=\"color: #dc2626;\">🚨 CRITICAL SLA BREACH</strong></p><p>Ticket <strong>#{{ticket_id}}</strong> has breached its resolution SLA.</p><p><strong>Customer:</strong> {{customer_name}}</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p><strong>Assigned To:</strong> {{assigned_agent}}</p><p><strong>Created:</strong> {{ticket_created_at}}</p><p><strong>Time Elapsed:</strong> {{time_elapsed}}</p><p><strong>SLA Target:</strong> {{sla_resolution_time}}</p><p>This ticket requires immediate escalation and resolution.</p>', 'Sent when resolution SLA is breached', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(11, 'SLA Warning - Approaching Deadline', 'sla-warning-approaching-deadline', '[WARNING] Ticket #{{ticket_id}} - SLA Deadline Approaching', 'escalation', '<p><strong style=\"color: #f59e0b;\">⏰ SLA WARNING</strong></p><p>Ticket <strong>#{{ticket_id}}</strong> is approaching its SLA deadline.</p><p><strong>Customer:</strong> {{customer_name}}</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p><strong>Time Remaining:</strong> {{time_remaining}}</p><p>Please ensure this ticket is addressed before the deadline.</p>', 'Sent when SLA deadline is approaching', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(12, 'Ticket Reminder - Awaiting Response', 'ticket-reminder-awaiting-response', 'Reminder: Your ticket #{{ticket_id}} is awaiting your response', 'reminder', '<p>Dear {{customer_name}},</p><p>This is a friendly reminder that your ticket <strong>#{{ticket_id}}</strong> is awaiting your response.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Last Updated:</strong> {{last_updated}}</p><p>If you have resolved the issue or no longer need assistance, please let us know so we can close the ticket.</p><p>If we don\'t hear from you within {{auto_close_days}} days, the ticket will be automatically closed.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to customer when waiting for their response', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(13, 'Ticket Auto-Close Warning', 'ticket-auto-close-warning', 'Your ticket #{{ticket_id}} will be closed soon', 'reminder', '<p>Dear {{customer_name}},</p><p>Your ticket <strong>#{{ticket_id}}</strong> has been inactive and will be automatically closed in {{days_until_close}} days.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p>If you still need assistance, please reply to this email or login to update your ticket.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent before auto-closing inactive ticket', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(14, 'Welcome Email', 'welcome-email', 'Welcome to {{company_name}} Support', 'welcome', '<p>Dear {{customer_name}},</p><p>Welcome to {{company_name}} Support!</p><p>Your account has been created successfully. You can now:</p><ul><li>Submit support tickets</li><li>Track your ticket status</li><li>Browse our Knowledge Base</li><li>View your ticket history</li></ul><p><strong>Your Login Details:</strong></p><p>Email: {{customer_email}}</p><p>Login URL: {{login_url}}</p><p>If you have any questions, feel free to create a support ticket.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to new users when they register', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(15, 'Password Reset', 'password-reset', 'Reset Your Password - {{company_name}}', 'notification', '<p>Dear {{customer_name}},</p><p>You are receiving this email because we received a password reset request for your account.</p><p style=\"margin: 30px 0;\"><a href=\"{{reset_url}}\" style=\"background-color: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;\">Reset Password</a></p><p>This password reset link will expire in {{expiry_time}}.</p><p>If you did not request a password reset, no further action is required. Your password will remain unchanged.</p><p><strong>For security reasons:</strong></p><ul><li>Never share your password with anyone</li><li>Use a strong, unique password</li><li>Enable two-factor authentication if available</li></ul><p>If you\'re having trouble clicking the button, copy and paste the URL below into your web browser:</p><p style=\"color: #6b7280; word-break: break-all;\">{{reset_url}}</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to users when they request a password reset', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(16, 'Ticket Assigned to Agent', 'ticket-assigned-to-agent', 'New ticket assigned: #{{ticket_id}}', 'notification', '<p>Hello {{agent_name}},</p><p>A new ticket has been assigned to you.</p><p><strong>Ticket ID:</strong> #{{ticket_id}}</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Customer:</strong> {{customer_name}}</p><p><strong>Priority:</strong> {{ticket_priority}}</p><p><strong>Category:</strong> {{ticket_category}}</p><p><strong>SLA Response Time:</strong> {{sla_response_time}}</p><hr><p>{{ticket_content}}</p><hr><p>Please login to the helpdesk to respond.</p>', 'Sent to agent when ticket is assigned to them', 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18'),
(17, 'Ticket Status Update', 'ticket-status-update', 'Your ticket #{{ticket_id}} status has been updated', 'notification', '<p>Dear {{customer_name}},</p><p>Your ticket <strong>#{{ticket_id}}</strong> status has been updated.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Previous Status:</strong> {{old_status}}</p><p><strong>New Status:</strong> {{new_status}}</p><p>Please login to view the latest updates and any responses from our team.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent to customer when ticket status changes', 'active', '2026-01-10 09:45:31', '2026-01-10 09:45:31'),
(18, 'Ticket Reply Notification', 'ticket-reply-notification', 'New reply on your ticket #{{ticket_id}}', 'notification', '<p>Dear {{customer_name}},</p><p>You have received a new reply on your ticket <strong>#{{ticket_id}}</strong>.</p><p><strong>Subject:</strong> {{ticket_subject}}</p><p><strong>Reply from:</strong> {{reply_author}}</p><hr><p>{{reply_content}}</p><hr><p>Please login to your account to view the full conversation and respond.</p><p>Best regards,<br>{{company_name}} Support Team</p>', 'Sent when ticket receives a reply', 'active', '2026-01-10 09:45:32', '2026-01-10 09:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kb_articles`
--

CREATE TABLE `kb_articles` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int UNSIGNED NOT NULL DEFAULT '0',
  `read_time` int UNSIGNED NOT NULL DEFAULT '1',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kb_articles`
--

INSERT INTO `kb_articles` (`id`, `category_id`, `title`, `slug`, `excerpt`, `content`, `views`, `read_time`, `status`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Selamat Datang ke Sistem Helpdesk Orien', 'selamat-datang-sistem-helpdesk-orien', 'Panduan komprehensif untuk memulakan penggunaan sistem helpdesk Orien. Ketahui cara mendaftar, log masuk, dan memahami antara muka pengguna.', '<h2>Pengenalan Sistem Helpdesk Orien</h2>\n<p>Selamat datang ke Sistem Helpdesk Orien! Platform ini direka khas untuk memudahkan komunikasi antara anda dan pasukan sokongan teknikal kami. Dengan sistem ini, anda boleh membuat, menjejak, dan menguruskan semua permintaan sokongan anda di satu tempat yang berpusat.</p>\n\n<h3>Apa Itu Sistem Helpdesk?</h3>\n<p>Sistem helpdesk adalah platform digital yang membolehkan pengguna menghantar permintaan bantuan teknikal (dikenali sebagai \"tiket\") kepada pasukan sokongan. Setiap tiket akan dijejak dari awal hingga penyelesaian, memastikan tiada permintaan yang terlepas pandang.</p>\n\n<h3>Kelebihan Menggunakan Sistem Helpdesk Orien</h3>\n<ul>\n    <li><strong>Penjejakan Telus:</strong> Anda boleh melihat status tiket anda pada bila-bila masa dan mengetahui tahap kemajuan penyelesaian.</li>\n    <li><strong>Komunikasi Berpusat:</strong> Semua perbualan dan dokumen berkaitan disimpan dalam satu tiket untuk rujukan mudah.</li>\n    <li><strong>Respons Pantas:</strong> Sistem keutamaan memastikan isu kritikal ditangani dengan segera.</li>\n    <li><strong>Sejarah Lengkap:</strong> Akses sejarah semua tiket anda untuk rujukan masa hadapan.</li>\n    <li><strong>Notifikasi Automatik:</strong> Terima e-mel notifikasi untuk setiap kemaskini pada tiket anda.</li>\n</ul>\n\n<h3>Cara Mengakses Sistem</h3>\n<p>Anda boleh mengakses sistem helpdesk melalui pelayar web di mana-mana peranti. Sistem ini serasi dengan:</p>\n<ul>\n    <li>Google Chrome (disyorkan)</li>\n    <li>Mozilla Firefox</li>\n    <li>Microsoft Edge</li>\n    <li>Safari</li>\n</ul>\n\n<h3>Struktur Antara Muka Pengguna</h3>\n<p>Antara muka sistem helpdesk terbahagi kepada beberapa bahagian utama:</p>\n\n<h4>1. Bar Navigasi Sisi (Sidebar)</h4>\n<p>Terletak di sebelah kiri skrin, bar navigasi ini mengandungi menu utama untuk mengakses pelbagai fungsi sistem seperti Dashboard, Tiket, dan Pangkalan Pengetahuan.</p>\n\n<h4>2. Dashboard</h4>\n<p>Halaman utama yang memaparkan ringkasan statistik tiket anda, tiket terkini, dan carta prestasi.</p>\n\n<h4>3. Senarai Tiket</h4>\n<p>Halaman yang memaparkan semua tiket anda dengan pilihan penapisan dan carian.</p>\n\n<h4>4. Pangkalan Pengetahuan</h4>\n<p>Koleksi artikel bantuan dan panduan yang boleh membantu anda menyelesaikan masalah tanpa perlu membuat tiket.</p>\n\n<h3>Langkah Seterusnya</h3>\n<p>Sekarang anda telah memahami asas sistem helpdesk, kami cadangkan anda membaca artikel-artikel berikut:</p>\n<ul>\n    <li>Cara Mendaftar Akaun Baru</li>\n    <li>Cara Membuat Tiket Sokongan Baru</li>\n    <li>Memahami Status dan Keutamaan Tiket</li>\n</ul>\n\n<p>Jika anda memerlukan bantuan tambahan, jangan teragak-agak untuk menghubungi pasukan sokongan kami melalui sistem tiket atau e-mel.</p>', 378, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 05:12:27', NULL),
(2, 1, 'Cara Mendaftar Akaun Baru', 'cara-mendaftar-akaun-baru', 'Langkah demi langkah untuk mendaftar akaun baru dalam sistem helpdesk. Termasuk maklumat yang diperlukan dan proses pengesahan.', '<h2>Panduan Pendaftaran Akaun Baru</h2>\n<p>Untuk menggunakan sistem helpdesk Orien, anda perlu mendaftar akaun terlebih dahulu. Proses pendaftaran adalah mudah dan hanya mengambil masa beberapa minit sahaja.</p>\n\n<h3>Maklumat yang Diperlukan</h3>\n<p>Sebelum memulakan pendaftaran, sila pastikan anda mempunyai maklumat berikut:</p>\n<ul>\n    <li><strong>Nama Penuh:</strong> Nama penuh anda seperti dalam dokumen rasmi.</li>\n    <li><strong>Alamat E-mel:</strong> E-mel yang aktif dan boleh diakses. E-mel ini akan digunakan untuk log masuk dan menerima notifikasi.</li>\n    <li><strong>Kata Laluan:</strong> Kata laluan yang kukuh dengan sekurang-kurangnya 8 aksara.</li>\n    <li><strong>Nombor Telefon:</strong> Nombor telefon yang boleh dihubungi (pilihan).</li>\n</ul>\n\n<h3>Langkah-langkah Pendaftaran</h3>\n\n<h4>Langkah 1: Akses Halaman Pendaftaran</h4>\n<p>Klik butang \"Daftar\" atau \"Register\" di halaman log masuk sistem helpdesk.</p>\n\n<h4>Langkah 2: Isi Borang Pendaftaran</h4>\n<p>Lengkapkan semua medan yang diperlukan dalam borang pendaftaran:</p>\n<ul>\n    <li>Masukkan nama penuh anda</li>\n    <li>Masukkan alamat e-mel yang sah</li>\n    <li>Cipta kata laluan yang kukuh</li>\n    <li>Sahkan kata laluan dengan menaipnya semula</li>\n    <li>Masukkan nombor telefon (jika diminta)</li>\n</ul>\n\n<h4>Langkah 3: Terima Terma dan Syarat</h4>\n<p>Baca dan tandakan kotak persetujuan untuk terma dan syarat penggunaan sistem.</p>\n\n<h4>Langkah 4: Hantar Pendaftaran</h4>\n<p>Klik butang \"Daftar\" untuk menghantar permohonan pendaftaran anda.</p>\n\n<h4>Langkah 5: Pengesahan E-mel</h4>\n<p>Semak peti masuk e-mel anda untuk e-mel pengesahan. Klik pautan dalam e-mel tersebut untuk mengaktifkan akaun anda.</p>\n\n<h3>Keperluan Kata Laluan</h3>\n<p>Untuk keselamatan akaun anda, kata laluan mestilah memenuhi kriteria berikut:</p>\n<ul>\n    <li>Sekurang-kurangnya 8 aksara</li>\n    <li>Mengandungi huruf besar dan huruf kecil</li>\n    <li>Mengandungi sekurang-kurangnya satu nombor</li>\n    <li>Mengandungi sekurang-kurangnya satu aksara khas (!@#$%^&*)</li>\n</ul>\n\n<h3>Masalah Semasa Pendaftaran?</h3>\n<p>Jika anda menghadapi masalah semasa pendaftaran, sila semak perkara berikut:</p>\n<ul>\n    <li><strong>E-mel sudah digunakan:</strong> Setiap alamat e-mel hanya boleh didaftarkan sekali. Jika anda sudah mempunyai akaun, sila gunakan fungsi \"Lupa Kata Laluan\".</li>\n    <li><strong>E-mel pengesahan tidak diterima:</strong> Semak folder spam/junk anda. E-mel pengesahan mungkin tersaring ke sana.</li>\n    <li><strong>Kata laluan tidak diterima:</strong> Pastikan kata laluan anda memenuhi semua keperluan keselamatan.</li>\n</ul>\n\n<h3>Selepas Pendaftaran</h3>\n<p>Setelah akaun anda berjaya didaftarkan dan disahkan, anda boleh:</p>\n<ul>\n    <li>Log masuk ke sistem helpdesk</li>\n    <li>Melengkapkan profil anda dengan maklumat tambahan</li>\n    <li>Mula membuat tiket sokongan</li>\n    <li>Mengakses pangkalan pengetahuan</li>\n</ul>', 161, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(3, 1, 'Memahami Dashboard Pengguna', 'memahami-dashboard-pengguna', 'Penjelasan terperinci tentang setiap komponen dalam dashboard pengguna dan cara menggunakannya dengan efektif.', '<h2>Memahami Dashboard Pengguna</h2>\n<p>Dashboard adalah halaman utama yang anda lihat selepas log masuk ke sistem helpdesk. Ia menyediakan gambaran keseluruhan tentang aktiviti tiket anda dan akses pantas ke fungsi-fungsi penting.</p>\n\n<h3>Komponen Utama Dashboard</h3>\n\n<h4>1. Kad Statistik</h4>\n<p>Di bahagian atas dashboard, anda akan melihat beberapa kad statistik yang memaparkan:</p>\n<ul>\n    <li><strong>Jumlah Tiket:</strong> Bilangan keseluruhan tiket yang anda telah buat.</li>\n    <li><strong>Tiket Terbuka:</strong> Bilangan tiket yang masih dalam proses penyelesaian.</li>\n    <li><strong>Tiket Ditutup:</strong> Bilangan tiket yang telah selesai diselesaikan.</li>\n    <li><strong>Tiket Tertunda:</strong> Bilangan tiket yang menunggu maklum balas atau tindakan.</li>\n</ul>\n\n<h4>2. Carta dan Graf</h4>\n<p>Dashboard memaparkan beberapa carta visual untuk membantu anda memahami corak tiket anda:</p>\n<ul>\n    <li><strong>Tiket Mengikut Status:</strong> Carta donat yang menunjukkan taburan tiket berdasarkan status.</li>\n    <li><strong>Tiket Mengikut Keutamaan:</strong> Carta bar yang menunjukkan bilangan tiket untuk setiap tahap keutamaan.</li>\n    <li><strong>Trend Tiket:</strong> Graf garis yang menunjukkan bilangan tiket dalam 7 hari terakhir.</li>\n</ul>\n\n<h4>3. Tiket Terkini</h4>\n<p>Senarai 5 tiket terkini anda dengan maklumat ringkas termasuk:</p>\n<ul>\n    <li>Tajuk tiket</li>\n    <li>Nombor tiket</li>\n    <li>Status semasa</li>\n    <li>Masa dibuat</li>\n</ul>\n\n<h3>Navigasi dari Dashboard</h3>\n<p>Dari dashboard, anda boleh:</p>\n<ul>\n    <li>Klik pada mana-mana tiket untuk melihat butiran penuh</li>\n    <li>Klik \"Lihat Semua\" untuk pergi ke senarai tiket lengkap</li>\n    <li>Gunakan menu sisi untuk mengakses bahagian lain sistem</li>\n</ul>\n\n<h3>Maklumat Berdasarkan Peranan</h3>\n<p>Dashboard memaparkan maklumat yang berbeza berdasarkan peranan anda:</p>\n<ul>\n    <li><strong>Pelanggan:</strong> Melihat statistik tiket peribadi sahaja.</li>\n    <li><strong>Ejen:</strong> Melihat statistik tiket yang ditugaskan kepada mereka.</li>\n    <li><strong>Pentadbir:</strong> Melihat statistik keseluruhan sistem.</li>\n</ul>', 124, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(4, 2, 'Cara Membuat Tiket Sokongan Baru', 'cara-membuat-tiket-sokongan-baru', 'Panduan lengkap untuk membuat tiket sokongan teknikal baru. Termasuk cara memilih kategori, keutamaan, dan menulis penerangan yang berkesan.', '<h2>Panduan Membuat Tiket Sokongan Baru</h2>\n<p>Tiket sokongan adalah cara utama untuk mendapatkan bantuan daripada pasukan sokongan teknikal kami. Panduan ini akan membantu anda membuat tiket yang berkesan untuk mempercepatkan proses penyelesaian.</p>\n\n<h3>Bila Perlu Membuat Tiket?</h3>\n<p>Anda perlu membuat tiket apabila:</p>\n<ul>\n    <li>Menghadapi masalah teknikal yang tidak dapat diselesaikan sendiri</li>\n    <li>Memerlukan bantuan atau maklumat daripada pasukan sokongan</li>\n    <li>Ingin melaporkan pepijat atau isu dalam sistem</li>\n    <li>Memerlukan perubahan atau penambahbaikan pada perkhidmatan</li>\n</ul>\n\n<h3>Langkah-langkah Membuat Tiket</h3>\n\n<h4>Langkah 1: Akses Halaman Tiket Baru</h4>\n<p>Klik butang \"Tiket Baru\" atau \"Buat Tiket\" di menu navigasi atau dashboard.</p>\n\n<h4>Langkah 2: Pilih Kategori</h4>\n<p>Pilih kategori yang paling sesuai dengan isu anda. Kategori yang tepat membantu pasukan sokongan mengarahkan tiket anda kepada pakar yang betul. Kategori yang tersedia termasuk:</p>\n<ul>\n    <li>Sokongan Teknikal</li>\n    <li>Pertanyaan Umum</li>\n    <li>Laporan Pepijat</li>\n    <li>Permintaan Ciri</li>\n    <li>Bil dan Pembayaran</li>\n</ul>\n\n<h4>Langkah 3: Tetapkan Keutamaan</h4>\n<p>Pilih tahap keutamaan berdasarkan kesan isu terhadap operasi anda:</p>\n<ul>\n    <li><strong>Kritikal:</strong> Sistem tidak berfungsi langsung, tiada penyelesaian sementara</li>\n    <li><strong>Tinggi:</strong> Fungsi utama terjejas teruk, impak besar kepada operasi</li>\n    <li><strong>Sederhana:</strong> Fungsi terjejas tetapi ada penyelesaian sementara</li>\n    <li><strong>Rendah:</strong> Isu kecil atau pertanyaan umum</li>\n</ul>\n\n<h4>Langkah 4: Tulis Tajuk yang Jelas</h4>\n<p>Tulis tajuk yang ringkas tetapi deskriptif. Contoh tajuk yang baik:</p>\n<ul>\n    <li>\"Tidak dapat log masuk selepas tukar kata laluan\"</li>\n    <li>\"Laporan bulanan tidak dapat dimuat turun\"</li>\n    <li>\"Ralat 500 semasa menghantar borang\"</li>\n</ul>\n\n<h4>Langkah 5: Tulis Penerangan Terperinci</h4>\n<p>Dalam penerangan, sertakan maklumat berikut:</p>\n<ul>\n    <li><strong>Apa yang berlaku:</strong> Terangkan masalah dengan jelas</li>\n    <li><strong>Bila ia berlaku:</strong> Tarikh dan masa isu mula berlaku</li>\n    <li><strong>Langkah untuk menghasilkan semula:</strong> Bagaimana kami boleh melihat masalah yang sama</li>\n    <li><strong>Apa yang anda jangkakan:</strong> Hasil yang sepatutnya berlaku</li>\n    <li><strong>Apa yang anda sudah cuba:</strong> Langkah penyelesaian yang sudah dicuba</li>\n</ul>\n\n<h4>Langkah 6: Lampirkan Fail (Jika Perlu)</h4>\n<p>Lampirkan tangkapan skrin, log ralat, atau dokumen yang berkaitan untuk membantu pasukan sokongan memahami isu anda dengan lebih baik.</p>\n\n<h4>Langkah 7: Hantar Tiket</h4>\n<p>Semak semula semua maklumat dan klik \"Hantar\" untuk membuat tiket anda.</p>\n\n<h3>Tips untuk Tiket yang Berkesan</h3>\n<ul>\n    <li>Satu tiket untuk satu isu - jangan gabungkan berbilang masalah dalam satu tiket</li>\n    <li>Gunakan bahasa yang jelas dan profesional</li>\n    <li>Sertakan maklumat teknikal yang relevan (pelayar, sistem operasi, dll.)</li>\n    <li>Lampirkan tangkapan skrin jika boleh</li>\n    <li>Nyatakan kesan isu terhadap operasi anda</li>\n</ul>\n\n<h3>Selepas Membuat Tiket</h3>\n<p>Selepas tiket dihantar:</p>\n<ul>\n    <li>Anda akan menerima e-mel pengesahan dengan nombor tiket</li>\n    <li>Tiket akan dipaparkan dalam senarai tiket anda</li>\n    <li>Pasukan sokongan akan menyemak dan membalas dalam masa yang ditetapkan</li>\n    <li>Anda akan dimaklumkan melalui e-mel untuk setiap kemaskini</li>\n</ul>', 311, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(5, 2, 'Memahami Status dan Keutamaan Tiket', 'memahami-status-keutamaan-tiket', 'Penjelasan tentang pelbagai status tiket dan tahap keutamaan. Ketahui maksud setiap status dan bila ia digunakan.', '<h2>Memahami Status dan Keutamaan Tiket</h2>\n<p>Setiap tiket dalam sistem mempunyai status dan tahap keutamaan yang menunjukkan keadaan semasa dan kepentingan tiket tersebut. Memahami makna setiap status dan keutamaan akan membantu anda menjejak kemajuan tiket anda dengan lebih baik.</p>\n\n<h3>Status Tiket</h3>\n<p>Status tiket menunjukkan di mana tiket anda berada dalam proses penyelesaian:</p>\n\n<h4>🔵 Terbuka (Open)</h4>\n<p>Tiket baru yang telah diterima tetapi belum ditugaskan atau mula diproses oleh pasukan sokongan. Ini adalah status awal untuk semua tiket baru.</p>\n\n<h4>🟡 Dalam Proses (In Progress)</h4>\n<p>Tiket sedang aktif diusahakan oleh ejen sokongan. Pasukan sokongan sedang menyiasat atau menyelesaikan isu anda.</p>\n\n<h4>🟠 Menunggu (Pending)</h4>\n<p>Tiket memerlukan maklumat tambahan atau tindakan daripada anda sebelum dapat diteruskan. Sila semak dan balas tiket anda secepat mungkin.</p>\n\n<h4>🟣 Dibuka Semula (Reopened)</h4>\n<p>Tiket yang sebelum ini ditutup tetapi dibuka semula kerana isu yang sama berulang atau penyelesaian tidak memuaskan.</p>\n\n<h4>🟢 Ditutup (Closed)</h4>\n<p>Tiket telah selesai diselesaikan dan ditutup. Jika anda masih menghadapi masalah yang sama, anda boleh membuka semula tiket ini atau membuat tiket baru.</p>\n\n<h3>Tahap Keutamaan</h3>\n<p>Keutamaan menentukan betapa segeranya tiket anda perlu ditangani:</p>\n\n<h4>🔴 Kritikal</h4>\n<p><strong>Masa Respons:</strong> 1 jam<br>\n<strong>Bila digunakan:</strong> Sistem tidak berfungsi langsung, tiada penyelesaian sementara, impak kepada ramai pengguna atau operasi kritikal perniagaan.</p>\n\n<h4>🟠 Tinggi</h4>\n<p><strong>Masa Respons:</strong> 4 jam<br>\n<strong>Bila digunakan:</strong> Fungsi utama terjejas teruk, impak besar kepada produktiviti, ada penyelesaian sementara tetapi tidak praktikal untuk jangka panjang.</p>\n\n<h4>🔵 Sederhana</h4>\n<p><strong>Masa Respons:</strong> 8 jam<br>\n<strong>Bila digunakan:</strong> Fungsi terjejas tetapi ada penyelesaian sementara yang boleh diterima, impak sederhana kepada operasi.</p>\n\n<h4>🟢 Rendah</h4>\n<p><strong>Masa Respons:</strong> 24 jam<br>\n<strong>Bila digunakan:</strong> Isu kecil, pertanyaan umum, permintaan maklumat, atau cadangan penambahbaikan.</p>\n\n<h3>Aliran Kerja Tiket</h3>\n<p>Berikut adalah aliran biasa untuk sebuah tiket:</p>\n<ol>\n    <li><strong>Terbuka:</strong> Tiket baru dibuat oleh pengguna</li>\n    <li><strong>Dalam Proses:</strong> Ejen mula menyiasat isu</li>\n    <li><strong>Menunggu:</strong> (Jika perlu) Menunggu maklumat tambahan daripada pengguna</li>\n    <li><strong>Dalam Proses:</strong> Ejen meneruskan penyelesaian selepas menerima maklumat</li>\n    <li><strong>Ditutup:</strong> Isu diselesaikan dan tiket ditutup</li>\n</ol>\n\n<h3>Perjanjian Tahap Perkhidmatan (SLA)</h3>\n<p>Setiap tahap keutamaan mempunyai masa respons dan penyelesaian yang dijanjikan. Pasukan sokongan kami komited untuk mematuhi SLA ini bagi memastikan isu anda ditangani dalam masa yang munasabah.</p>', 197, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(6, 2, 'Cara Membalas dan Mengemaskini Tiket', 'cara-membalas-mengemaskini-tiket', 'Panduan untuk berkomunikasi dengan pasukan sokongan melalui sistem tiket. Termasuk cara menambah lampiran dan maklumat tambahan.', '<h2>Cara Membalas dan Mengemaskini Tiket</h2>\n<p>Komunikasi yang berkesan dengan pasukan sokongan adalah kunci untuk penyelesaian isu yang pantas. Panduan ini menerangkan cara membalas tiket dan menambah maklumat tambahan.</p>\n\n<h3>Membalas Tiket</h3>\n\n<h4>Langkah 1: Buka Tiket</h4>\n<p>Pergi ke senarai tiket anda dan klik pada tiket yang ingin dibalas untuk membuka halaman butiran tiket.</p>\n\n<h4>Langkah 2: Tulis Balasan</h4>\n<p>Di bahagian bawah halaman tiket, anda akan melihat kotak teks untuk menulis balasan. Taip mesej anda di sini.</p>\n\n<h4>Langkah 3: Lampirkan Fail (Pilihan)</h4>\n<p>Jika perlu, klik butang lampiran untuk menambah fail seperti tangkapan skrin atau dokumen.</p>\n\n<h4>Langkah 4: Hantar Balasan</h4>\n<p>Klik butang \"Hantar Balasan\" untuk menghantar mesej anda kepada pasukan sokongan.</p>\n\n<h3>Tips untuk Balasan yang Berkesan</h3>\n<ul>\n    <li><strong>Balas dengan segera:</strong> Balasan pantas membantu mempercepatkan penyelesaian</li>\n    <li><strong>Jawab semua soalan:</strong> Pastikan anda menjawab semua soalan yang ditanya oleh ejen</li>\n    <li><strong>Berikan maklumat lengkap:</strong> Sertakan semua maklumat yang diminta</li>\n    <li><strong>Gunakan bahasa yang jelas:</strong> Elakkan singkatan atau istilah yang mungkin tidak difahami</li>\n    <li><strong>Sertakan bukti:</strong> Lampirkan tangkapan skrin atau log jika relevan</li>\n</ul>\n\n<h3>Menambah Lampiran</h3>\n<p>Anda boleh melampirkan pelbagai jenis fail untuk membantu menjelaskan isu anda:</p>\n<ul>\n    <li><strong>Imej:</strong> PNG, JPG, GIF (maksimum 5MB)</li>\n    <li><strong>Dokumen:</strong> PDF, DOC, DOCX, XLS, XLSX (maksimum 10MB)</li>\n    <li><strong>Fail teks:</strong> TXT, LOG (maksimum 2MB)</li>\n    <li><strong>Arkib:</strong> ZIP (maksimum 10MB)</li>\n</ul>\n\n<h3>Masa Bekerja untuk Balasan</h3>\n<p>Anda boleh merekodkan masa yang dihabiskan untuk menyelesaikan isu (untuk ejen dan pentadbir). Ini membantu dalam pelaporan dan pengurusan prestasi.</p>\n\n<h3>Nota Dalaman</h3>\n<p>Ejen dan pentadbir boleh menambah nota dalaman yang tidak dapat dilihat oleh pelanggan. Ini berguna untuk komunikasi antara pasukan sokongan.</p>', 184, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(7, 2, 'Menjejak Sejarah dan Aktiviti Tiket', 'menjejak-sejarah-aktiviti-tiket', 'Cara melihat sejarah lengkap tiket anda termasuk semua perubahan status, balasan, dan aktiviti yang berkaitan.', '<h2>Menjejak Sejarah dan Aktiviti Tiket</h2>\n<p>Sistem helpdesk merekodkan semua aktiviti berkaitan tiket anda. Anda boleh melihat sejarah lengkap untuk memahami perjalanan penyelesaian isu anda.</p>\n\n<h3>Maklumat yang Direkodkan</h3>\n<p>Sejarah tiket merekodkan:</p>\n<ul>\n    <li>Tarikh dan masa tiket dibuat</li>\n    <li>Semua balasan dan komunikasi</li>\n    <li>Perubahan status tiket</li>\n    <li>Perubahan keutamaan</li>\n    <li>Penugasan ejen</li>\n    <li>Lampiran yang dimuat naik</li>\n    <li>Nota dalaman (untuk ejen sahaja)</li>\n</ul>\n\n<h3>Cara Melihat Sejarah</h3>\n<ol>\n    <li>Buka tiket yang ingin dilihat sejarahnya</li>\n    <li>Tatal ke bahagian perbualan/chat</li>\n    <li>Semua aktiviti dipaparkan dalam susunan kronologi</li>\n</ol>\n\n<h3>Memahami Garis Masa Tiket</h3>\n<p>Setiap entri dalam sejarah memaparkan:</p>\n<ul>\n    <li><strong>Tarikh dan Masa:</strong> Bila aktiviti berlaku</li>\n    <li><strong>Pengguna:</strong> Siapa yang melakukan tindakan</li>\n    <li><strong>Jenis Aktiviti:</strong> Apa yang dilakukan (balasan, perubahan status, dll.)</li>\n    <li><strong>Butiran:</strong> Maklumat terperinci tentang aktiviti</li>\n</ul>', 118, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(8, 3, 'Mengemaskini Profil dan Maklumat Peribadi', 'mengemaskini-profil-maklumat-peribadi', 'Panduan untuk mengemaskini maklumat profil anda termasuk nama, e-mel, nombor telefon, dan maklumat syarikat.', '<h2>Mengemaskini Profil dan Maklumat Peribadi</h2>\n<p>Menjaga maklumat profil anda terkini adalah penting untuk memastikan komunikasi yang lancar dengan pasukan sokongan. Panduan ini menerangkan cara mengemaskini pelbagai maklumat profil anda.</p>\n\n<h3>Mengakses Halaman Profil</h3>\n<ol>\n    <li>Klik pada nama atau avatar anda di penjuru kanan atas</li>\n    <li>Pilih \"Profil\" atau \"Tetapan Akaun\" dari menu</li>\n</ol>\n\n<h3>Maklumat yang Boleh Dikemaskini</h3>\n\n<h4>Maklumat Peribadi</h4>\n<ul>\n    <li><strong>Nama Penuh:</strong> Nama yang akan dipaparkan dalam sistem</li>\n    <li><strong>Nama Pengguna:</strong> Pengecam unik anda dalam sistem</li>\n    <li><strong>Alamat E-mel:</strong> E-mel untuk log masuk dan notifikasi</li>\n    <li><strong>Nombor Telefon:</strong> Nombor untuk dihubungi jika perlu</li>\n    <li><strong>Nombor Mudah Alih:</strong> Nombor telefon bimbit</li>\n</ul>\n\n<h4>Alamat</h4>\n<ul>\n    <li>Alamat jalan</li>\n    <li>Bandar</li>\n    <li>Negeri</li>\n    <li>Poskod</li>\n    <li>Negara</li>\n</ul>\n\n<h4>Maklumat Syarikat (Jika Berkaitan)</h4>\n<ul>\n    <li>Nama syarikat</li>\n    <li>Nombor pendaftaran</li>\n    <li>Telefon syarikat</li>\n    <li>E-mel syarikat</li>\n    <li>Alamat syarikat</li>\n    <li>Laman web</li>\n    <li>Industri</li>\n</ul>\n\n<h3>Langkah Mengemaskini Profil</h3>\n<ol>\n    <li>Akses halaman profil anda</li>\n    <li>Klik butang \"Edit\" atau terus ubah maklumat dalam medan</li>\n    <li>Kemaskini maklumat yang diperlukan</li>\n    <li>Klik \"Simpan\" untuk menyimpan perubahan</li>\n</ol>\n\n<h3>Menukar Gambar Profil</h3>\n<ol>\n    <li>Klik pada gambar profil semasa</li>\n    <li>Pilih fail imej baru dari komputer anda</li>\n    <li>Laraskan kedudukan dan saiz jika perlu</li>\n    <li>Simpan perubahan</li>\n</ol>', 167, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(9, 3, 'Menukar Kata Laluan dengan Selamat', 'menukar-kata-laluan-selamat', 'Langkah-langkah untuk menukar kata laluan akaun anda dan amalan terbaik untuk mencipta kata laluan yang kukuh.', '<h2>Menukar Kata Laluan dengan Selamat</h2>\n<p>Menukar kata laluan secara berkala adalah amalan keselamatan yang baik. Panduan ini menerangkan cara menukar kata laluan dan tips untuk mencipta kata laluan yang kukuh.</p>\n\n<h3>Bila Perlu Menukar Kata Laluan?</h3>\n<ul>\n    <li>Secara berkala (setiap 3-6 bulan)</li>\n    <li>Jika anda mengesyaki akaun anda telah dikompromi</li>\n    <li>Selepas berkongsi kata laluan dengan orang lain</li>\n    <li>Jika anda menggunakan kata laluan yang sama di laman web lain yang telah digodam</li>\n</ul>\n\n<h3>Langkah Menukar Kata Laluan</h3>\n<ol>\n    <li>Pergi ke halaman Profil atau Tetapan Akaun</li>\n    <li>Cari bahagian \"Keselamatan\" atau \"Kata Laluan\"</li>\n    <li>Masukkan kata laluan semasa anda</li>\n    <li>Masukkan kata laluan baru</li>\n    <li>Sahkan kata laluan baru</li>\n    <li>Klik \"Kemaskini Kata Laluan\"</li>\n</ol>\n\n<h3>Keperluan Kata Laluan</h3>\n<p>Kata laluan baru mestilah:</p>\n<ul>\n    <li>Sekurang-kurangnya 8 aksara</li>\n    <li>Mengandungi huruf besar (A-Z)</li>\n    <li>Mengandungi huruf kecil (a-z)</li>\n    <li>Mengandungi nombor (0-9)</li>\n    <li>Mengandungi aksara khas (!@#$%^&*)</li>\n    <li>Berbeza daripada 5 kata laluan sebelumnya</li>\n</ul>\n\n<h3>Tips Kata Laluan Kukuh</h3>\n<ul>\n    <li><strong>Gunakan frasa:</strong> \"SayaSukaM4k4nN4siLem@k!\" lebih kukuh dan mudah diingat</li>\n    <li><strong>Elakkan maklumat peribadi:</strong> Jangan gunakan nama, tarikh lahir, atau nombor telefon</li>\n    <li><strong>Unik untuk setiap akaun:</strong> Jangan gunakan kata laluan yang sama di mana-mana</li>\n    <li><strong>Gunakan pengurus kata laluan:</strong> Aplikasi seperti LastPass atau 1Password boleh membantu</li>\n</ul>\n\n<h3>Apa yang Berlaku Selepas Tukar?</h3>\n<ul>\n    <li>Anda akan dilog keluar dari semua sesi aktif</li>\n    <li>E-mel pengesahan akan dihantar</li>\n    <li>Anda perlu log masuk semula dengan kata laluan baru</li>\n</ul>', 106, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(10, 3, 'Mengaktifkan Pengesahan Dua Faktor (2FA)', 'mengaktifkan-pengesahan-dua-faktor', 'Panduan lengkap untuk mengaktifkan dan menggunakan pengesahan dua faktor bagi meningkatkan keselamatan akaun anda.', '<h2>Mengaktifkan Pengesahan Dua Faktor (2FA)</h2>\n<p>Pengesahan Dua Faktor (2FA) menambah lapisan keselamatan tambahan kepada akaun anda. Walaupun seseorang mengetahui kata laluan anda, mereka masih memerlukan kod dari telefon anda untuk log masuk.</p>\n\n<h3>Apa Itu 2FA?</h3>\n<p>2FA memerlukan dua bentuk pengesahan untuk log masuk:</p>\n<ol>\n    <li><strong>Sesuatu yang anda tahu:</strong> Kata laluan anda</li>\n    <li><strong>Sesuatu yang anda miliki:</strong> Kod dari aplikasi authenticator di telefon anda</li>\n</ol>\n\n<h3>Keperluan untuk 2FA</h3>\n<ul>\n    <li>Telefon pintar (Android atau iOS)</li>\n    <li>Aplikasi authenticator seperti:\n        <ul>\n            <li>Google Authenticator</li>\n            <li>Microsoft Authenticator</li>\n            <li>Authy</li>\n        </ul>\n    </li>\n</ul>\n\n<h3>Langkah Mengaktifkan 2FA</h3>\n\n<h4>Langkah 1: Muat Turun Aplikasi Authenticator</h4>\n<p>Muat turun dan pasang aplikasi authenticator pilihan anda dari App Store atau Google Play Store.</p>\n\n<h4>Langkah 2: Akses Tetapan 2FA</h4>\n<p>Pergi ke Profil > Keselamatan > Pengesahan Dua Faktor dan klik \"Aktifkan\".</p>\n\n<h4>Langkah 3: Imbas Kod QR</h4>\n<p>Buka aplikasi authenticator dan imbas kod QR yang dipaparkan di skrin. Atau masukkan kod rahsia secara manual.</p>\n\n<h4>Langkah 4: Masukkan Kod Pengesahan</h4>\n<p>Masukkan kod 6 digit yang dipaparkan dalam aplikasi authenticator untuk mengesahkan persediaan.</p>\n\n<h4>Langkah 5: Simpan Kod Pemulihan</h4>\n<p>Simpan kod pemulihan di tempat yang selamat. Kod ini diperlukan jika anda kehilangan akses kepada telefon anda.</p>\n\n<h3>Log Masuk dengan 2FA</h3>\n<ol>\n    <li>Masukkan e-mel dan kata laluan seperti biasa</li>\n    <li>Apabila diminta, buka aplikasi authenticator</li>\n    <li>Masukkan kod 6 digit yang dipaparkan</li>\n    <li>Kod berubah setiap 30 saat, pastikan anda masukkan kod terkini</li>\n</ol>\n\n<h3>Kod Pemulihan</h3>\n<p>Kod pemulihan adalah kod sekali guna yang boleh digunakan jika anda tidak dapat mengakses aplikasi authenticator. Setiap kod hanya boleh digunakan sekali. Simpan kod ini dengan selamat!</p>\n\n<h3>Menyahaktifkan 2FA</h3>\n<p>Jika anda perlu menyahaktifkan 2FA:</p>\n<ol>\n    <li>Pergi ke Profil > Keselamatan > Pengesahan Dua Faktor</li>\n    <li>Klik \"Nyahaktifkan\"</li>\n    <li>Masukkan kata laluan anda untuk pengesahan</li>\n</ol>\n<p><strong>Amaran:</strong> Menyahaktifkan 2FA akan mengurangkan keselamatan akaun anda.</p>', 118, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(11, 4, 'Berapa Lama Masa Respons untuk Tiket Saya?', 'masa-respons-tiket', 'Maklumat tentang jangka masa respons berdasarkan tahap keutamaan tiket dan waktu operasi pasukan sokongan.', '<h2>Masa Respons untuk Tiket Sokongan</h2>\n<p>Kami komited untuk memberikan respons pantas kepada semua permintaan sokongan. Masa respons bergantung kepada tahap keutamaan tiket dan waktu operasi pasukan sokongan.</p>\n\n<h3>Jadual Masa Respons</h3>\n<table>\n    <tr>\n        <th>Keutamaan</th>\n        <th>Masa Respons Pertama</th>\n        <th>Sasaran Penyelesaian</th>\n    </tr>\n    <tr>\n        <td>🔴 Kritikal</td>\n        <td>1 jam</td>\n        <td>4 jam</td>\n    </tr>\n    <tr>\n        <td>🟠 Tinggi</td>\n        <td>4 jam</td>\n        <td>8 jam</td>\n    </tr>\n    <tr>\n        <td>🔵 Sederhana</td>\n        <td>8 jam</td>\n        <td>24 jam</td>\n    </tr>\n    <tr>\n        <td>🟢 Rendah</td>\n        <td>24 jam</td>\n        <td>48 jam</td>\n    </tr>\n</table>\n\n<h3>Waktu Operasi</h3>\n<p>Pasukan sokongan kami beroperasi pada:</p>\n<ul>\n    <li><strong>Isnin - Jumaat:</strong> 9:00 pagi - 6:00 petang</li>\n    <li><strong>Sabtu:</strong> 9:00 pagi - 1:00 tengah hari</li>\n    <li><strong>Ahad & Cuti Umum:</strong> Tutup</li>\n</ul>\n\n<h3>Sokongan Kecemasan</h3>\n<p>Untuk isu kritikal di luar waktu operasi, tiket dengan keutamaan \"Kritikal\" akan dimaklumkan kepada pasukan on-call dan akan ditangani secepat mungkin.</p>\n\n<h3>Faktor yang Mempengaruhi Masa Respons</h3>\n<ul>\n    <li>Kerumitan isu</li>\n    <li>Kelengkapan maklumat yang diberikan</li>\n    <li>Keperluan untuk melibatkan pihak ketiga</li>\n    <li>Beban kerja semasa pasukan sokongan</li>\n</ul>\n\n<h3>Cara Mempercepatkan Penyelesaian</h3>\n<ul>\n    <li>Pilih keutamaan yang tepat</li>\n    <li>Berikan maklumat lengkap dalam tiket</li>\n    <li>Balas dengan segera apabila diminta maklumat tambahan</li>\n    <li>Lampirkan tangkapan skrin atau bukti yang relevan</li>\n</ul>', 238, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(12, 4, 'Bagaimana Jika Saya Lupa Kata Laluan?', 'lupa-kata-laluan', 'Panduan untuk memulihkan akses ke akaun anda jika anda terlupa kata laluan.', '<h2>Memulihkan Akses Akaun - Lupa Kata Laluan</h2>\n<p>Jika anda terlupa kata laluan, jangan risau! Anda boleh menetapkan semula kata laluan dengan mudah melalui e-mel.</p>\n\n<h3>Langkah Menetapkan Semula Kata Laluan</h3>\n\n<h4>Langkah 1: Akses Halaman Lupa Kata Laluan</h4>\n<p>Di halaman log masuk, klik pautan \"Lupa Kata Laluan?\" atau \"Forgot Password?\"</p>\n\n<h4>Langkah 2: Masukkan Alamat E-mel</h4>\n<p>Masukkan alamat e-mel yang anda gunakan untuk mendaftar akaun.</p>\n\n<h4>Langkah 3: Semak E-mel</h4>\n<p>Semak peti masuk e-mel anda untuk e-mel tetapan semula kata laluan. E-mel ini mengandungi pautan khas untuk menetapkan kata laluan baru.</p>\n\n<h4>Langkah 4: Klik Pautan</h4>\n<p>Klik pautan dalam e-mel. Pautan ini sah untuk 60 minit sahaja.</p>\n\n<h4>Langkah 5: Cipta Kata Laluan Baru</h4>\n<p>Masukkan kata laluan baru anda dan sahkan. Pastikan kata laluan memenuhi keperluan keselamatan.</p>\n\n<h4>Langkah 6: Log Masuk</h4>\n<p>Setelah berjaya, anda boleh log masuk dengan kata laluan baru.</p>\n\n<h3>E-mel Tidak Diterima?</h3>\n<ul>\n    <li>Semak folder spam/junk</li>\n    <li>Pastikan alamat e-mel betul</li>\n    <li>Tunggu beberapa minit dan cuba semula</li>\n    <li>Hubungi sokongan jika masalah berterusan</li>\n</ul>\n\n<h3>Keselamatan</h3>\n<ul>\n    <li>Pautan tetapan semula hanya sah untuk 60 minit</li>\n    <li>Setiap pautan hanya boleh digunakan sekali</li>\n    <li>Jangan kongsi pautan dengan sesiapa</li>\n</ul>', 371, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(13, 4, 'Bolehkah Saya Membatalkan atau Menutup Tiket?', 'membatalkan-menutup-tiket', 'Maklumat tentang cara dan bila anda boleh membatalkan atau menutup tiket sokongan yang telah dibuat.', '<h2>Membatalkan atau Menutup Tiket</h2>\n<p>Ada kalanya anda mungkin perlu membatalkan tiket yang telah dibuat atau menutup tiket yang telah selesai. Panduan ini menerangkan bila dan bagaimana untuk melakukannya.</p>\n\n<h3>Bila Boleh Membatalkan Tiket?</h3>\n<ul>\n    <li>Isu telah selesai dengan sendirinya</li>\n    <li>Tiket dibuat secara tidak sengaja</li>\n    <li>Maklumat dalam tiket tidak lagi relevan</li>\n    <li>Anda telah menemui penyelesaian sendiri</li>\n</ul>\n\n<h3>Cara Menutup Tiket</h3>\n<ol>\n    <li>Buka tiket yang ingin ditutup</li>\n    <li>Tambah balasan menerangkan sebab penutupan</li>\n    <li>Hubungi ejen untuk meminta tiket ditutup</li>\n</ol>\n\n<h3>Membuka Semula Tiket</h3>\n<p>Jika isu berulang selepas tiket ditutup:</p>\n<ol>\n    <li>Buka tiket yang telah ditutup</li>\n    <li>Klik \"Buka Semula Tiket\"</li>\n    <li>Terangkan mengapa tiket perlu dibuka semula</li>\n</ol>\n\n<h3>Nota Penting</h3>\n<ul>\n    <li>Tiket yang ditutup masih boleh dilihat dalam sejarah</li>\n    <li>Anda tidak boleh memadam tiket secara kekal</li>\n    <li>Tiket boleh dibuka semula dalam tempoh 30 hari</li>\n</ul>', 168, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(14, 5, 'Tidak Dapat Log Masuk ke Akaun', 'tidak-dapat-log-masuk', 'Penyelesaian untuk masalah log masuk yang biasa dihadapi termasuk masalah kata laluan, akaun dikunci, dan isu teknikal.', '<h2>Penyelesaian Masalah Log Masuk</h2>\n<p>Menghadapi masalah untuk log masuk ke akaun anda? Panduan ini menyenaraikan masalah biasa dan penyelesaiannya.</p>\n\n<h3>Masalah 1: Kata Laluan Salah</h3>\n<p><strong>Gejala:</strong> Mesej \"Kata laluan tidak sah\" atau \"Invalid password\"</p>\n<p><strong>Penyelesaian:</strong></p>\n<ul>\n    <li>Pastikan Caps Lock tidak aktif</li>\n    <li>Cuba taip kata laluan dengan perlahan</li>\n    <li>Gunakan fungsi \"Tunjukkan Kata Laluan\" untuk memeriksa</li>\n    <li>Gunakan \"Lupa Kata Laluan\" jika perlu</li>\n</ul>\n\n<h3>Masalah 2: Akaun Dikunci</h3>\n<p><strong>Gejala:</strong> Mesej \"Akaun anda telah dikunci\" selepas beberapa percubaan gagal</p>\n<p><strong>Penyelesaian:</strong></p>\n<ul>\n    <li>Tunggu 30 minit sebelum mencuba semula</li>\n    <li>Gunakan \"Lupa Kata Laluan\" untuk menetapkan semula</li>\n    <li>Hubungi sokongan jika masalah berterusan</li>\n</ul>\n\n<h3>Masalah 3: E-mel Tidak Dikenali</h3>\n<p><strong>Gejala:</strong> Mesej \"E-mel tidak dijumpai\" atau \"Email not found\"</p>\n<p><strong>Penyelesaian:</strong></p>\n<ul>\n    <li>Pastikan anda menggunakan e-mel yang betul</li>\n    <li>Semak ejaan alamat e-mel</li>\n    <li>Cuba e-mel lain yang mungkin anda gunakan</li>\n    <li>Daftar akaun baru jika belum mempunyai akaun</li>\n</ul>\n\n<h3>Masalah 4: Akaun Dinyahaktifkan</h3>\n<p><strong>Gejala:</strong> Mesej \"Akaun anda telah dinyahaktifkan\"</p>\n<p><strong>Penyelesaian:</strong></p>\n<ul>\n    <li>Hubungi pentadbir sistem</li>\n    <li>Minta pengaktifan semula akaun</li>\n</ul>\n\n<h3>Masalah 5: Halaman Tidak Dimuatkan</h3>\n<p><strong>Gejala:</strong> Halaman log masuk tidak dipaparkan atau lambat</p>\n<p><strong>Penyelesaian:</strong></p>\n<ul>\n    <li>Semak sambungan internet anda</li>\n    <li>Kosongkan cache pelayar</li>\n    <li>Cuba pelayar lain</li>\n    <li>Nyahaktifkan sambungan pelayar (extensions)</li>\n</ul>\n\n<h3>Masalah 6: Kod 2FA Tidak Diterima</h3>\n<p><strong>Gejala:</strong> Kod pengesahan dua faktor ditolak</p>\n<p><strong>Penyelesaian:</strong></p>\n<ul>\n    <li>Pastikan masa pada telefon anda tepat</li>\n    <li>Tunggu kod baru (kod berubah setiap 30 saat)</li>\n    <li>Gunakan kod pemulihan jika ada</li>\n    <li>Hubungi sokongan untuk menyahaktifkan 2FA</li>\n</ul>', 268, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 08:42:26', NULL),
(15, 5, 'E-mel Notifikasi Tidak Diterima', 'emel-notifikasi-tidak-diterima', 'Langkah-langkah untuk menyelesaikan masalah e-mel notifikasi yang tidak sampai ke peti masuk anda.', '<h2>E-mel Notifikasi Tidak Diterima</h2>\n<p>Jika anda tidak menerima e-mel notifikasi dari sistem helpdesk, ikuti langkah-langkah penyelesaian berikut.</p>\n\n<h3>Langkah 1: Semak Folder Spam/Junk</h3>\n<p>E-mel dari sistem mungkin tersaring ke folder spam. Semak folder ini dan tandakan e-mel dari kami sebagai \"Bukan Spam\".</p>\n\n<h3>Langkah 2: Tambah ke Senarai Putih</h3>\n<p>Tambahkan alamat e-mel pengirim kami ke senarai kenalan atau senarai putih anda:</p>\n<ul>\n    <li>noreply@orien.com.my</li>\n    <li>support@orien.com.my</li>\n</ul>\n\n<h3>Langkah 3: Semak Alamat E-mel</h3>\n<p>Pastikan alamat e-mel dalam profil anda betul dan aktif. Pergi ke Profil untuk mengesahkan.</p>\n\n<h3>Langkah 4: Semak Tetapan Notifikasi</h3>\n<p>Pastikan notifikasi e-mel diaktifkan dalam tetapan akaun anda.</p>\n\n<h3>Langkah 5: Semak Peti Masuk Penuh</h3>\n<p>Jika peti masuk anda penuh, e-mel baru tidak dapat diterima. Kosongkan ruang dalam peti masuk anda.</p>\n\n<h3>Langkah 6: Hubungi Pentadbir E-mel</h3>\n<p>Jika anda menggunakan e-mel korporat, hubungi pentadbir IT anda untuk memastikan e-mel dari sistem kami tidak disekat.</p>\n\n<h3>Jenis E-mel yang Dihantar</h3>\n<ul>\n    <li>Pengesahan pendaftaran</li>\n    <li>Tetapan semula kata laluan</li>\n    <li>Notifikasi tiket baru</li>\n    <li>Notifikasi balasan tiket</li>\n    <li>Notifikasi perubahan status</li>\n    <li>Notifikasi penugasan</li>\n</ul>', 150, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(16, 5, 'Lampiran Gagal Dimuat Naik', 'lampiran-gagal-dimuat-naik', 'Penyelesaian untuk masalah muat naik lampiran termasuk had saiz fail dan format yang disokong.', '<h2>Penyelesaian Masalah Muat Naik Lampiran</h2>\n<p>Jika anda menghadapi masalah memuat naik lampiran ke tiket, panduan ini akan membantu anda menyelesaikannya.</p>\n\n<h3>Had Saiz Fail</h3>\n<p>Setiap lampiran mempunyai had saiz maksimum:</p>\n<ul>\n    <li><strong>Imej:</strong> 5MB setiap fail</li>\n    <li><strong>Dokumen:</strong> 10MB setiap fail</li>\n    <li><strong>Arkib:</strong> 10MB setiap fail</li>\n    <li><strong>Jumlah keseluruhan:</strong> 25MB setiap tiket</li>\n</ul>\n\n<h3>Format Fail yang Disokong</h3>\n<ul>\n    <li><strong>Imej:</strong> PNG, JPG, JPEG, GIF, BMP</li>\n    <li><strong>Dokumen:</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</li>\n    <li><strong>Teks:</strong> TXT, LOG, CSV</li>\n    <li><strong>Arkib:</strong> ZIP, RAR</li>\n</ul>\n\n<h3>Masalah Biasa dan Penyelesaian</h3>\n\n<h4>Fail Terlalu Besar</h4>\n<ul>\n    <li>Kompres imej menggunakan alat dalam talian</li>\n    <li>Kompres fail ke format ZIP</li>\n    <li>Bahagikan fail besar kepada beberapa bahagian</li>\n    <li>Gunakan pautan perkongsian awan (Google Drive, Dropbox)</li>\n</ul>\n\n<h4>Format Tidak Disokong</h4>\n<ul>\n    <li>Tukar format fail ke format yang disokong</li>\n    <li>Untuk fail EXE atau format berbahaya, sertakan dalam arkib ZIP</li>\n</ul>\n\n<h4>Muat Naik Gagal</h4>\n<ul>\n    <li>Semak sambungan internet</li>\n    <li>Cuba fail yang lebih kecil untuk menguji</li>\n    <li>Kosongkan cache pelayar</li>\n    <li>Cuba pelayar lain</li>\n</ul>\n\n<h3>Alternatif untuk Fail Besar</h3>\n<p>Untuk fail yang melebihi had saiz:</p>\n<ol>\n    <li>Muat naik ke perkhidmatan awan (Google Drive, OneDrive, Dropbox)</li>\n    <li>Kongsi pautan dalam balasan tiket</li>\n    <li>Pastikan pautan boleh diakses oleh pasukan sokongan</li>\n</ol>', 118, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(17, 6, 'Polisi Privasi dan Perlindungan Data', 'polisi-privasi-perlindungan-data', 'Maklumat lengkap tentang bagaimana kami mengumpul, menyimpan, dan melindungi data peribadi anda.', '<h2>Polisi Privasi dan Perlindungan Data</h2>\n<p>Kami komited untuk melindungi privasi dan data peribadi anda. Polisi ini menerangkan bagaimana kami mengumpul, menggunakan, dan melindungi maklumat anda.</p>\n\n<h3>Maklumat yang Dikumpul</h3>\n<p>Kami mengumpul maklumat berikut:</p>\n<ul>\n    <li><strong>Maklumat Akaun:</strong> Nama, e-mel, nombor telefon</li>\n    <li><strong>Maklumat Tiket:</strong> Kandungan tiket, lampiran, perbualan</li>\n    <li><strong>Maklumat Teknikal:</strong> Alamat IP, jenis pelayar, log akses</li>\n    <li><strong>Maklumat Syarikat:</strong> Nama syarikat, alamat (jika diberikan)</li>\n</ul>\n\n<h3>Penggunaan Maklumat</h3>\n<p>Maklumat anda digunakan untuk:</p>\n<ul>\n    <li>Menyediakan perkhidmatan sokongan</li>\n    <li>Menghubungi anda berkaitan tiket</li>\n    <li>Meningkatkan perkhidmatan kami</li>\n    <li>Mematuhi keperluan undang-undang</li>\n</ul>\n\n<h3>Perlindungan Data</h3>\n<p>Kami melaksanakan langkah-langkah keselamatan berikut:</p>\n<ul>\n    <li>Enkripsi data dalam transit (HTTPS/TLS)</li>\n    <li>Enkripsi data dalam simpanan</li>\n    <li>Kawalan akses berasaskan peranan</li>\n    <li>Pemantauan keselamatan 24/7</li>\n    <li>Sandaran data berkala</li>\n</ul>\n\n<h3>Perkongsian Data</h3>\n<p>Kami TIDAK berkongsi data anda dengan pihak ketiga kecuali:</p>\n<ul>\n    <li>Dengan persetujuan anda</li>\n    <li>Untuk mematuhi keperluan undang-undang</li>\n    <li>Dengan penyedia perkhidmatan yang terikat dengan perjanjian kerahsiaan</li>\n</ul>\n\n<h3>Hak Anda</h3>\n<p>Anda mempunyai hak untuk:</p>\n<ul>\n    <li>Mengakses data peribadi anda</li>\n    <li>Membetulkan data yang tidak tepat</li>\n    <li>Meminta pemadaman data</li>\n    <li>Membantah pemprosesan data</li>\n    <li>Memindahkan data anda</li>\n</ul>\n\n<h3>Tempoh Penyimpanan</h3>\n<p>Data anda disimpan selama:</p>\n<ul>\n    <li><strong>Data akaun:</strong> Selagi akaun aktif + 2 tahun</li>\n    <li><strong>Data tiket:</strong> 5 tahun selepas tiket ditutup</li>\n    <li><strong>Log akses:</strong> 1 tahun</li>\n</ul>\n\n<h3>Hubungi Kami</h3>\n<p>Untuk sebarang pertanyaan berkaitan privasi, hubungi Pegawai Perlindungan Data kami di privacy@orien.com.my</p>', 146, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(18, 6, 'Terma dan Syarat Penggunaan', 'terma-syarat-penggunaan', 'Terma dan syarat yang mengawal penggunaan sistem helpdesk Orien.', '<h2>Terma dan Syarat Penggunaan</h2>\n<p>Dengan menggunakan sistem helpdesk Orien, anda bersetuju dengan terma dan syarat berikut.</p>\n\n<h3>1. Definisi</h3>\n<ul>\n    <li><strong>\"Sistem\"</strong> merujuk kepada platform helpdesk Orien</li>\n    <li><strong>\"Pengguna\"</strong> merujuk kepada individu yang menggunakan sistem</li>\n    <li><strong>\"Perkhidmatan\"</strong> merujuk kepada sokongan teknikal yang disediakan</li>\n</ul>\n\n<h3>2. Kelayakan Penggunaan</h3>\n<ul>\n    <li>Anda mestilah berumur 18 tahun ke atas</li>\n    <li>Anda mestilah mempunyai kuasa untuk mewakili organisasi anda (jika berkaitan)</li>\n    <li>Maklumat yang diberikan mestilah tepat dan terkini</li>\n</ul>\n\n<h3>3. Tanggungjawab Pengguna</h3>\n<ul>\n    <li>Menjaga kerahsiaan kata laluan</li>\n    <li>Menggunakan sistem untuk tujuan yang sah sahaja</li>\n    <li>Tidak menyalahgunakan sistem atau mengganggu pengguna lain</li>\n    <li>Melaporkan sebarang pelanggaran keselamatan</li>\n</ul>\n\n<h3>4. Penggunaan yang Dilarang</h3>\n<ul>\n    <li>Menghantar kandungan yang menyalahi undang-undang</li>\n    <li>Menyebarkan virus atau perisian hasad</li>\n    <li>Cuba mengakses akaun pengguna lain</li>\n    <li>Menggunakan sistem untuk spam atau penipuan</li>\n</ul>\n\n<h3>5. Hak Harta Intelek</h3>\n<p>Semua kandungan dalam sistem adalah hak milik Orien atau pemberi lesennya. Anda tidak boleh menyalin, mengubah, atau mengedar kandungan tanpa kebenaran.</p>\n\n<h3>6. Had Liabiliti</h3>\n<p>Orien tidak bertanggungjawab atas:</p>\n<ul>\n    <li>Kerugian tidak langsung atau berbangkit</li>\n    <li>Gangguan perkhidmatan di luar kawalan kami</li>\n    <li>Tindakan pihak ketiga</li>\n</ul>\n\n<h3>7. Penamatan</h3>\n<p>Kami berhak untuk menangguhkan atau menamatkan akses anda jika anda melanggar terma ini.</p>\n\n<h3>8. Perubahan Terma</h3>\n<p>Kami mungkin mengemas kini terma ini dari semasa ke semasa. Perubahan akan dimaklumkan melalui e-mel atau notifikasi dalam sistem.</p>\n\n<h3>9. Undang-undang yang Terpakai</h3>\n<p>Terma ini tertakluk kepada undang-undang Malaysia.</p>', 97, 1, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(19, 6, 'Prosedur Eskalasi Tiket', 'prosedur-eskalasi-tiket', 'Panduan tentang proses eskalasi tiket jika isu anda memerlukan perhatian segera atau penyelesaian di peringkat lebih tinggi.', '<h2>Prosedur Eskalasi Tiket</h2>\n<p>Jika isu anda memerlukan perhatian segera atau tidak dapat diselesaikan di peringkat sokongan biasa, prosedur eskalasi akan diaktifkan.</p>\n\n<h3>Bila Eskalasi Berlaku?</h3>\n<ul>\n    <li>Tiket tidak diselesaikan dalam tempoh SLA</li>\n    <li>Isu memerlukan kepakaran teknikal yang lebih tinggi</li>\n    <li>Pelanggan meminta eskalasi</li>\n    <li>Isu melibatkan berbilang sistem atau jabatan</li>\n</ul>\n\n<h3>Peringkat Eskalasi</h3>\n\n<h4>Peringkat 1: Sokongan Barisan Hadapan</h4>\n<p>Ejen sokongan biasa yang mengendalikan tiket anda. Kebanyakan isu diselesaikan di peringkat ini.</p>\n\n<h4>Peringkat 2: Sokongan Teknikal</h4>\n<p>Pakar teknikal dengan pengetahuan mendalam. Mengendalikan isu yang lebih kompleks.</p>\n\n<h4>Peringkat 3: Pasukan Kejuruteraan</h4>\n<p>Jurutera perisian yang boleh mengakses kod sumber dan membuat perubahan sistem.</p>\n\n<h4>Peringkat 4: Pengurusan</h4>\n<p>Pengurus dan eksekutif untuk isu kritikal yang memerlukan keputusan perniagaan.</p>\n\n<h3>Cara Meminta Eskalasi</h3>\n<ol>\n    <li>Balas tiket anda dengan permintaan eskalasi</li>\n    <li>Nyatakan sebab mengapa anda merasakan eskalasi diperlukan</li>\n    <li>Berikan maklumat tambahan yang relevan</li>\n</ol>\n\n<h3>Apa yang Berlaku Selepas Eskalasi?</h3>\n<ul>\n    <li>Tiket akan ditugaskan kepada pakar peringkat lebih tinggi</li>\n    <li>Anda akan dimaklumkan tentang perubahan penugasan</li>\n    <li>Masa respons mungkin berbeza bergantung kepada kerumitan</li>\n    <li>Anda mungkin dihubungi untuk maklumat tambahan</li>\n</ul>\n\n<h3>Eskalasi Automatik</h3>\n<p>Sistem akan secara automatik mengeskala tiket jika:</p>\n<ul>\n    <li>Tiket tidak dibalas dalam tempoh SLA</li>\n    <li>Tiket dibuka semula lebih dari 2 kali</li>\n    <li>Pelanggan memberikan maklum balas negatif</li>\n</ul>\n\n<h3>Tips untuk Eskalasi yang Berkesan</h3>\n<ul>\n    <li>Berikan konteks lengkap tentang isu</li>\n    <li>Nyatakan impak kepada perniagaan anda</li>\n    <li>Senaraikan langkah yang telah dicuba</li>\n    <li>Bersikap profesional dalam komunikasi</li>\n</ul>', 128, 2, 'published', '2026-01-10 00:31:06', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kb_categories`
--

CREATE TABLE `kb_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'folder',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3b82f6',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kb_categories`
--

INSERT INTO `kb_categories` (`id`, `name`, `slug`, `description`, `icon`, `color`, `sort_order`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Panduan Bermula', 'panduan-bermula', 'Panduan lengkap untuk pengguna baru memulakan penggunaan sistem helpdesk. Termasuk cara pendaftaran, log masuk, dan navigasi asas.', 'rocket_launch', '#3b82f6', 1, 'active', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(2, 'Pengurusan Tiket', 'pengurusan-tiket', 'Segala panduan berkaitan dengan cara membuat, mengemaskini, dan menguruskan tiket sokongan teknikal anda.', 'confirmation_number', '#10b981', 2, 'active', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(3, 'Akaun & Keselamatan', 'akaun-keselamatan', 'Maklumat tentang pengurusan akaun pengguna, tetapan keselamatan, dan perlindungan data peribadi.', 'security', '#8b5cf6', 3, 'active', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(4, 'Soalan Lazim (FAQ)', 'soalan-lazim', 'Jawapan kepada soalan-soalan yang kerap ditanya oleh pengguna sistem helpdesk.', 'help', '#f59e0b', 4, 'active', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(5, 'Penyelesaian Masalah', 'penyelesaian-masalah', 'Panduan untuk menyelesaikan masalah teknikal yang biasa dihadapi oleh pengguna.', 'build', '#ef4444', 5, 'active', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL),
(6, 'Polisi & Prosedur', 'polisi-prosedur', 'Maklumat tentang polisi syarikat, prosedur operasi standard, dan garis panduan penggunaan sistem.', 'policy', '#06b6d4', 6, 'active', '2026-01-10 00:31:06', '2026-01-10 00:31:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000003_create_roles_table', 1),
(5, '2024_01_01_000004_create_ticket_categories_table', 1),
(6, '2024_01_01_000005_create_settings_table', 1),
(7, '2024_01_01_000006_create_knowledgebase_tables', 1),
(8, '2026_01_09_000001_create_tickets_table', 1),
(9, '2026_01_09_000002_create_ticket_replies_table', 1),
(10, '2026_01_09_000003_create_ticket_attachments_table', 1),
(11, '2026_01_09_000004_add_category_id_to_sla_rules_table', 1),
(12, '2026_01_09_100001_create_security_tools_tables', 1),
(15, '2026_01_09_000005_create_ticket_assignments_table', 2),
(16, '2026_01_09_000006_migrate_existing_assignments', 2),
(17, '2026_01_10_022546_create_bad_words_and_websites_tables', 3),
(18, '2026_01_10_023139_add_working_time_to_ticket_replies_table', 4),
(19, '2026_01_10_025052_add_two_factor_columns_to_users_table', 5),
(20, '2026_01_10_113637_add_security_fields_to_users_table', 6),
(21, '2026_01_10_115445_modify_status_enum_in_users_table', 7),
(22, '2026_01_10_130722_add_user_type_to_users_table', 8),
(23, '2026_01_10_150728_create_activity_logs_table', 9),
(24, '2026_01_10_155542_add_soft_deletes_to_all_tables', 10),
(25, '2026_01_10_162610_create_audit_logs_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `permissions` json DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `permissions`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator', 'administrator', 'Full system access with all permissions', '[\"dashboard.view\", \"tickets.view\", \"tickets.create\", \"tickets.edit\", \"tickets.delete\", \"tickets.manage\", \"knowledgebase_view.view\", \"knowledgebase_articles.view\", \"knowledgebase_articles.create\", \"knowledgebase_articles.edit\", \"knowledgebase_articles.delete\", \"knowledgebase_categories.view\", \"knowledgebase_categories.create\", \"knowledgebase_categories.edit\", \"knowledgebase_categories.delete\", \"reports.view\", \"reports.export\", \"tools_ban_emails.view\", \"tools_ban_emails.create\", \"tools_ban_emails.edit\", \"tools_ban_emails.delete\", \"tools_ban_emails.export\", \"tools_ban_ips.view\", \"tools_ban_ips.create\", \"tools_ban_ips.edit\", \"tools_ban_ips.delete\", \"tools_ban_ips.export\", \"tools_whitelist_ips.view\", \"tools_whitelist_ips.create\", \"tools_whitelist_ips.edit\", \"tools_whitelist_ips.delete\", \"tools_whitelist_ips.export\", \"tools_whitelist_emails.view\", \"tools_whitelist_emails.create\", \"tools_whitelist_emails.edit\", \"tools_whitelist_emails.delete\", \"tools_whitelist_emails.export\", \"tools_bad_words.view\", \"tools_bad_words.create\", \"tools_bad_words.edit\", \"tools_bad_words.delete\", \"tools_bad_words.export\", \"tools_bad_websites.view\", \"tools_bad_websites.create\", \"tools_bad_websites.edit\", \"tools_bad_websites.delete\", \"tools_bad_websites.export\", \"settings_general.view\", \"settings_general.edit\", \"settings_integrations_email.view\", \"settings_integrations_email.edit\", \"settings_integrations_telegram.view\", \"settings_integrations_telegram.edit\", \"settings_integrations_weather.view\", \"settings_integrations_weather.edit\", \"settings_integrations_api.view\", \"settings_integrations_api.edit\", \"settings_integrations_spam.view\", \"settings_integrations_spam.edit\", \"settings_integrations_recycle.view\", \"settings_integrations_recycle.delete\", \"settings_integrations_recycle.manage\", \"settings_ticket_categories.view\", \"settings_ticket_categories.create\", \"settings_ticket_categories.edit\", \"settings_ticket_categories.delete\", \"settings_priorities.view\", \"settings_priorities.create\", \"settings_priorities.edit\", \"settings_priorities.delete\", \"settings_status.view\", \"settings_status.create\", \"settings_status.edit\", \"settings_status.delete\", \"settings_sla.view\", \"settings_sla.create\", \"settings_sla.edit\", \"settings_sla.delete\", \"settings_email_templates.view\", \"settings_email_templates.create\", \"settings_email_templates.edit\", \"settings_email_templates.delete\", \"settings_roles.view\", \"settings_roles.create\", \"settings_roles.edit\", \"settings_roles.delete\", \"settings_users_admin.view\", \"settings_users_admin.create\", \"settings_users_admin.edit\", \"settings_users_admin.delete\", \"settings_users_admin.manage\", \"settings_users_agents.view\", \"settings_users_agents.create\", \"settings_users_agents.edit\", \"settings_users_agents.delete\", \"settings_users_agents.manage\", \"settings_users_customers.view\", \"settings_users_customers.create\", \"settings_users_customers.edit\", \"settings_users_customers.delete\", \"settings_users_customers.manage\", \"settings_activity_logs.view\", \"settings_activity_logs.delete\", \"settings_activity_logs.export\", \"settings_audit_logs.view\", \"settings_audit_logs.delete\", \"settings_audit_logs.export\"]', 'active', '2026-01-09 09:05:18', '2026-01-10 10:35:03', NULL),
(2, 'Agent', 'agent', 'Support agent with ticket management access', '[\"dashboard.view\", \"tickets.view\", \"tickets.create\", \"tickets.edit\", \"tickets.delete\", \"tickets.export\", \"knowledgebase_view.view\", \"knowledgebase_articles.view\", \"knowledgebase_articles.create\", \"knowledgebase_articles.edit\", \"knowledgebase_articles.delete\", \"knowledgebase_categories.view\", \"knowledgebase_categories.create\", \"knowledgebase_categories.edit\", \"knowledgebase_categories.delete\", \"reports.view\", \"reports.export\", \"tools_ban_emails.view\", \"tools_ban_emails.create\", \"tools_ban_emails.edit\", \"tools_ban_emails.delete\", \"tools_ban_ips.view\", \"tools_ban_ips.create\", \"tools_ban_ips.edit\", \"tools_ban_ips.delete\", \"tools_whitelist_ips.view\", \"tools_whitelist_ips.create\", \"tools_whitelist_ips.edit\", \"tools_whitelist_ips.delete\", \"tools_whitelist_emails.view\", \"tools_whitelist_emails.create\", \"tools_whitelist_emails.edit\", \"tools_whitelist_emails.delete\", \"tools_bad_words.view\", \"tools_bad_words.create\", \"tools_bad_words.edit\", \"tools_bad_words.delete\", \"tools_bad_websites.view\", \"tools_bad_websites.create\", \"tools_bad_websites.edit\", \"tools_bad_websites.delete\"]', 'active', '2026-01-09 09:05:18', '2026-01-10 06:58:00', NULL),
(3, 'Customer', 'customer', 'Customer with limited access to own tickets', '[\"dashboard.view\", \"tickets.view\", \"tickets.create\", \"tickets.edit\", \"tickets.delete\", \"tickets.export\", \"knowledgebase_view.view\", \"reports.view\", \"reports.export\"]', 'active', '2026-01-09 09:05:18', '2026-01-10 06:59:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3QXuXPXb5ng5HH3xHQaF5B1lm7ROisQTbXiNpRiG', NULL, '127.0.0.1', 'TelegramBot (like TwitterBot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicVM4cnlaVUtVYXdmRXlHR3d2Uk8xVXFFdTNWYTFSd0NiejNoWUJhNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xYWRlNTVjMzkyZDQubmdyb2stZnJlZS5hcHAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1768053225),
('CHaotCQEutOQBtik75O9Y60j3IEZ48Qvgz70vbbA', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/23B85 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS1FkM3cyZllqUDBESXcyMGs4cW1lODdhcnZSOTZDVE1LN0xLNWtKdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xYWRlNTVjMzkyZDQubmdyb2stZnJlZS5hcHAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjM0OiJodHRwOi8vMWFkZTU1YzM5MmQ0Lm5ncm9rLWZyZWUuYXBwIjt9fQ==', 1768053879),
('hN6C6B4sbjkzMJNseBHUbBBY0NtUSe7pQJIKT6CN', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWXpkNms0bUVnTFlrNmswbXBSNmhkUmJtZzA0SzhSQUpReTV2MHVNbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjIxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiO319', 1768054899),
('LQigzqik1WZQXwNTeC7aw38iHsQwMJWJkslV4n47', NULL, '127.0.0.1', 'TelegramBot (like TwitterBot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN0g5U2s5VDFvdWZEVHk1YlBpTlNkZzBEVERMWURqb2FhRk5xcGREQyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzFhZGU1NWMzOTJkNC5uZ3Jvay1mcmVlLmFwcCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMWFkZTU1YzM5MmQ0Lm5ncm9rLWZyZWUuYXBwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768053869),
('MTWkOmKvo87NApT5OQLvh9Fu7TtLgxzwWSXQeR0I', 7, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiclZ4VHhlcm5JYjBxRkU5c3Rvc0N1dkZJTW1kNDZZbjhIWDZ1SWE4VCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMWFkZTU1YzM5MmQ0Lm5ncm9rLWZyZWUuYXBwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', 1768053833),
('olwW9rPpt0V3gnLyOCezEiumUSiXiEXwwYE1hK55', NULL, '127.0.0.1', 'TelegramBot (like TwitterBot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY0xWNXo3a3lqMFlVYU5aUVFHWndCTjJCWjBrcnNoVFFJZzREZTRaVyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzFhZGU1NWMzOTJkNC5uZ3Jvay1mcmVlLmFwcCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMWFkZTU1YzM5MmQ0Lm5ncm9rLWZyZWUuYXBwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768053869),
('rCuGQrx6R02LhikTEhdkyySJemLAhkquNbGvUXy2', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.151 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicHAxb3FXN3Y2M1NSbUpwRXd1NFlCTEFqUEtGU0ZBRlZIM1duSkRQMSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzFhZGU1NWMzOTJkNC5uZ3Jvay1mcmVlLmFwcCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMWFkZTU1YzM5MmQ0Lm5ncm9rLWZyZWUuYXBwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768054036),
('TbTtEPuoe5QRFB7JXGTkCA6hfXgFShJS8UjYCj4S', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDRiaHk4Q01KS1ZOZjNadDRaZW5xN1VybXdVMlRIRU5ZRVA0TWJmVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xYWRlNTVjMzkyZDQubmdyb2stZnJlZS5hcHAvZm9yZ290LXBhc3N3b3JkIjtzOjU6InJvdXRlIjtzOjE2OiJwYXNzd29yZC5yZXF1ZXN0Ijt9fQ==', 1768054423),
('wIqjazCIxVGuApmIaPlYcUTOPbtGStvIKBdjkEYO', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Mobile/23B85 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGhTZG1ndW01dEVLeXNzbE9McDZrTzkzeUVkN2ZaOHZOYlE3dXpqOSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzFhZGU1NWMzOTJkNC5uZ3Jvay1mcmVlLmFwcCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMWFkZTU1YzM5MmQ0Lm5ncm9rLWZyZWUuYXBwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768054146);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'system_name', '\"ORIEN Helpdesk\"', 'company', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(2, 'company_name', '\"ORIEN NET SOLUTIONS SDN BHD\"', 'company', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(3, 'company_short_name', '\"ORIEN\"', 'company', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(4, 'company_ssm_number', '\"883498-M\"', 'company', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(5, 'company_email', '\"info@orien.com.my\"', 'company', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(6, 'company_phone', '\"0389381811\"', 'company', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(7, 'company_website', '\"https://www.orien.com.my\"', 'company', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(8, 'company_address', '\"No. C-6-2 Blok C Putra Walk, Jalan PP 25, Tmn. Pinggiran Putra,\\\\r\\\\nSek. 2, Bandar Putra Permai, 43300 Seri Kembangan Selangor.\"', 'company', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(9, 'timezone', '\"Asia/Kuala_Lumpur\"', 'datetime', '2026-01-09 09:17:46', '2026-01-10 08:05:39'),
(10, 'date_format', '\"d M Y\"', 'datetime', '2026-01-09 09:17:46', '2026-01-10 08:05:39'),
(11, 'time_format', '\"H:i:s\"', 'datetime', '2026-01-09 09:17:46', '2026-01-10 08:05:39'),
(12, 'currency', '\"MYR\"', 'regional', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(13, 'currency_symbol', '\"RM\"', 'regional', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(14, 'language', '\"en\"', 'regional', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(15, 'session_timeout', '\"120\"', 'security', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(16, 'password_min_length', '\"8\"', 'security', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(17, 'max_login_attempts', '\"5\"', 'security', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(18, 'lockout_duration', '\"15\"', 'security', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(19, 'email_ticket_created', 'true', 'notifications', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(20, 'email_ticket_replied', 'true', 'notifications', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(21, 'email_ticket_status_changed', 'true', 'notifications', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(22, 'email_ticket_assigned', 'true', 'notifications', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(23, 'email_user_created', 'true', 'notifications', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(24, 'pagination_size', '\"10\"', 'system', '2026-01-09 09:17:46', '2026-01-10 09:15:24'),
(25, 'ticket_auto_close_days', '\"7\"', 'system', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(26, 'attachment_max_size', '\"5\"', 'system', '2026-01-09 09:17:46', '2026-01-10 10:57:40'),
(27, 'allowed_file_types', '\"pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip\"', 'system', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(28, 'require_2fa', 'false', 'security', '2026-01-09 09:17:46', '2026-01-10 03:12:40'),
(29, 'email_settings', '{\"host\": \"indigo.herosite.pro\", \"port\": \"587\", \"password\": \"C@kC@k2026\", \"provider\": \"smtp\", \"username\": \"orien@techs.my\", \"from_name\": \"ORIEN Helpdesk\", \"encryption\": \"tls\", \"from_address\": \"orien@techs.my\", \"gmail_client_id\": null, \"gmail_client_secret\": null}', 'integration_email', '2026-01-09 18:41:55', '2026-01-09 18:41:55'),
(30, 'weather_settings', '{\"units\": \"metric\", \"api_key\": \"dcbba489c6b1a63f05da7649824261c4\", \"default_city\": \"Seri Kembangan\"}', 'integration_weather', '2026-01-09 18:42:42', '2026-01-09 18:42:42'),
(31, 'spam_settings', '{\"check_on_login\": true, \"check_on_ticket\": true, \"abuseipdb_api_key\": \"7b407e75cc57339a375b32570445046d0469e113233a12d6b7ede6e318424d4d69082c4cde996cbc\", \"abuseipdb_enabled\": true, \"purgomalum_enabled\": true, \"abuseipdb_threshold\": \"80\", \"auto_add_to_database\": true, \"safebrowsing_api_key\": \"AIzaSyB8kNSRbqCw-h9zdHlToTP3uUxdeqwn9_s\", \"safebrowsing_enabled\": true, \"check_on_registration\": true, \"stopforumspam_enabled\": true, \"stopforumspam_threshold\": \"90\"}', 'integration_spam', '2026-01-10 02:36:38', '2026-01-10 02:39:03'),
(35, 'favicon', '\"branding/7k20nnRcuuIJ7S0PpDPZRRLZB2igcLIOn3jxkH3N.png\"', 'branding', '2026-01-10 03:12:40', '2026-01-10 11:24:30'),
(36, 'logo', '\"branding/SZGDqM6KdA82RwNz2RllLserShmH5mHVC5yMogP2.png\"', 'branding', '2026-01-10 03:12:40', '2026-01-10 11:24:30'),
(37, 'hero_image', '\"branding/IzXEBO7dJqgDp9dJQ70Fkn84Ojbqj0Np6m85T3s3.png\"', 'branding', '2026-01-10 03:12:40', '2026-01-10 11:22:04'),
(38, 'telegram_settings', '{\"enabled\": true, \"bot_token\": \"8522671983:AAGzHcW6EGLos1zWnpndFCXiyyI0VJRQCBk\", \"channel_id\": \"-1003534845666\", \"bot_username\": \"orienmybot\", \"owner_user_id\": \"473855787\", \"owner_username\": \"devilguardian\", \"notify_new_ticket\": true, \"notify_ticket_reply\": true, \"notify_ticket_closed\": true}', 'integration_telegram', '2026-01-10 10:42:57', '2026-01-10 10:43:58');

-- --------------------------------------------------------

--
-- Table structure for table `sla_rules`
--

CREATE TABLE `sla_rules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `response_time` int NOT NULL DEFAULT '60',
  `resolution_time` int NOT NULL DEFAULT '480',
  `priority_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sla_rules`
--

INSERT INTO `sla_rules` (`id`, `name`, `slug`, `description`, `response_time`, `resolution_time`, `priority_id`, `category_id`, `sort_order`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Default SLA', 'default-sla', NULL, 480, 2880, NULL, NULL, 10, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(2, 'Low Priority SLA', 'low-priority-sla', NULL, 480, 2880, 1, NULL, 9, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(3, 'Medium Priority SLA', 'medium-priority-sla', NULL, 240, 1440, 2, NULL, 8, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(4, 'High Priority SLA', 'high-priority-sla', NULL, 60, 480, 3, NULL, 7, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(5, 'Critical Priority SLA', 'critical-priority-sla', NULL, 15, 120, 4, NULL, 6, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(6, 'Critical Technical Issues', 'critical-technical-issues', NULL, 10, 60, 4, 2, 1, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(7, 'High Technical Issues', 'high-technical-issues', NULL, 30, 240, 3, 2, 2, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(8, 'Critical Billing Issues', 'critical-billing-issues', NULL, 15, 120, 4, 3, 3, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(9, 'High Billing Issues', 'high-billing-issues', NULL, 60, 360, 3, 3, 4, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `assigned_to` bigint UNSIGNED DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `priority_id` bigint UNSIGNED NOT NULL,
  `status_id` bigint UNSIGNED NOT NULL,
  `sla_rule_id` bigint UNSIGNED DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_assignments`
--

CREATE TABLE `ticket_assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_attachments`
--

CREATE TABLE `ticket_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `ticket_reply_id` bigint UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_categories`
--

CREATE TABLE `ticket_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3b82f6',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'category',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_categories`
--

INSERT INTO `ticket_categories` (`id`, `name`, `slug`, `description`, `color`, `icon`, `sort_order`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'General', 'general', NULL, '#3b82f6', 'category', 1, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(2, 'Technical', 'technical', NULL, '#8b5cf6', 'build', 2, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(3, 'Billing', 'billing', NULL, '#10b981', 'payments', 3, 'active', '2026-01-09 09:05:18', '2026-01-10 10:53:39', NULL),
(4, 'Sales', 'sales', NULL, '#f59e0b', 'storefront', 4, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(5, 'Support', 'support', NULL, '#ef4444', 'support_agent', 5, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_priorities`
--

CREATE TABLE `ticket_priorities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3b82f6',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'flag',
  `sort_order` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_priorities`
--

INSERT INTO `ticket_priorities` (`id`, `name`, `slug`, `description`, `color`, `icon`, `sort_order`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Low', 'low', NULL, '#22c55e', 'arrow_downward', 1, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(2, 'Medium', 'medium', NULL, '#f59e0b', 'remove', 2, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(3, 'High', 'high', NULL, '#f97316', 'arrow_upward', 3, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(4, 'Critical', 'critical', NULL, '#ef4444', 'priority_high', 4, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `working_time` decimal(8,2) DEFAULT NULL,
  `is_internal_note` tinyint(1) NOT NULL DEFAULT '0',
  `status_changed_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_changed_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority_changed_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority_changed_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_statuses`
--

CREATE TABLE `ticket_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3b82f6',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'toggle_on',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_statuses`
--

INSERT INTO `ticket_statuses` (`id`, `name`, `slug`, `description`, `color`, `icon`, `sort_order`, `is_default`, `is_closed`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Open', 'open', NULL, '#3b82f6', 'radio_button_unchecked', 1, 1, 0, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(2, 'In Progress', 'in-progress', NULL, '#f59e0b', 'pending', 2, 0, 0, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(3, 'Waiting', 'waiting', NULL, '#8b5cf6', 'hourglass_empty', 3, 0, 0, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(4, 'Resolved', 'resolved', NULL, '#10b981', 'check_circle', 4, 0, 1, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL),
(5, 'Closed', 'closed', NULL, '#6b7280', 'cancel', 5, 0, 1, 'active', '2026-01-09 09:05:18', '2026-01-09 09:05:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_registration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` text COLLATE utf8mb4_unicode_ci,
  `company_website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `login_attempts` int NOT NULL DEFAULT '0',
  `locked_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `suspended_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash_link` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `user_type` enum('administrator','agent','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`, `username`, `email`, `phone`, `mobile`, `address`, `city`, `state`, `postcode`, `country`, `company_name`, `company_registration`, `company_phone`, `company_email`, `company_address`, `company_website`, `industry`, `status`, `login_attempts`, `locked_at`, `suspended_at`, `suspended_reason`, `last_login_at`, `email_verified_at`, `password`, `hash_link`, `role_id`, `user_type`, `remember_token`, `two_factor_enabled`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator', 'Admin', 'User', 'administrator', 'admin@orien.local', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, NULL, NULL, '2026-01-10 08:05:12', NULL, '$argon2id$v=19$m=65536,t=4,p=1$TFJTSzExZDloN3hhT2lyZQ$ZCUREtDZOUuHtwlglPPxNeUVJNNiDtySxcRVSxhAnU8', '9b5f9a5edd311ddcb119bd18be03d83e510f1e6e1edf99c0259eeefaab94ac2b', 1, 'administrator', NULL, 0, NULL, NULL, NULL, '2026-01-09 09:05:18', '2026-01-10 11:56:53', NULL),
(2, 'Agent Orien', 'Agent', 'Orien', 'agents@orien.com.my', 'agents@orien.com.my', NULL, NULL, NULL, NULL, NULL, NULL, 'MY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, NULL, NULL, '2026-01-10 11:55:01', NULL, '$argon2id$v=19$m=65536,t=4,p=1$YWsydjB5OHowWS84S1RYUA$22AGq6TXrtAaWgEpigBP35f2v0jRNXEl1HIr7W8DbzI', '81ada9c395c95ea503e47184a7e7699ffd571a8ff84b1d8682d40bccc9e0c4b8', 2, 'agent', NULL, 0, NULL, NULL, NULL, '2026-01-09 09:09:43', '2026-01-10 11:55:01', NULL),
(3, 'Faizan Rahman', 'Faizan', 'Rahman', 'faizanrahman84@gmail.com', 'faizanrahman84@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 'MY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, NULL, NULL, '2026-01-10 10:58:44', NULL, '$argon2id$v=19$m=65536,t=4,p=1$YjUwTTNSNGFZRHBjR0lyQQ$eIQJzO2WIBULBNY+RBYsL2fhYzAZu3J46MfAZKnlncI', '845ed07bad1d704af5dd21af762001dd9f159cfb283297bbd9a3f88decd46aaf', 3, 'customer', NULL, 0, NULL, NULL, NULL, '2026-01-09 09:10:14', '2026-01-10 10:58:44', NULL),
(5, 'Test Test', 'Test', 'Test', 'test@test.com', 'test@test.com', NULL, NULL, NULL, NULL, NULL, NULL, 'MY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, NULL, NULL, NULL, NULL, '$argon2id$v=19$m=65536,t=4,p=1$eUs0RS9pNDR1enlvZ3Zrcw$00SCL/mtC6sI8BzOd4nhtr5MS3UjpvJA8GLyLB41U8Y', '24716e3eb4035d94427f8d70d6848d95fcc145166ed70bad06dbb3ed9e2e3adb', 2, 'agent', NULL, 0, NULL, NULL, NULL, '2026-01-09 17:34:19', '2026-01-10 03:59:48', NULL),
(7, 'Administrator Root', NULL, NULL, 'administrator_root', 'administrator@root', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', 0, NULL, NULL, NULL, '2026-01-10 14:04:07', '2026-01-10 11:56:33', '$argon2id$v=19$m=65536,t=4,p=1$NnFReHQzaG5DcVhwL1NpdA$cTc2jf4cwMcctfiYUh4wtfg0EKnSexR2UXxzO9e5oC8', '1941f569b05ef91ea891a0005b5ca9949c09cc637978e880d2d90b1003e68289', 1, 'administrator', NULL, 0, NULL, NULL, NULL, '2026-01-10 11:56:33', '2026-01-10 14:04:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `whitelist_emails`
--

CREATE TABLE `whitelist_emails` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whitelist_ips`
--

CREATE TABLE `whitelist_ips` (
  `id` bigint UNSIGNED NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `activity_logs_module_action_index` (`module`,`action`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_module_created_at_index` (`module`,`created_at`),
  ADD KEY `audit_logs_created_at_index` (`created_at`);

--
-- Indexes for table `bad_websites`
--
ALTER TABLE `bad_websites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bad_websites_added_by_foreign` (`added_by`);

--
-- Indexes for table `bad_words`
--
ALTER TABLE `bad_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bad_words_added_by_foreign` (`added_by`);

--
-- Indexes for table `banned_emails`
--
ALTER TABLE `banned_emails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `banned_emails_email_unique` (`email`),
  ADD KEY `banned_emails_added_by_foreign` (`added_by`);

--
-- Indexes for table `banned_ips`
--
ALTER TABLE `banned_ips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `banned_ips_ip_address_unique` (`ip_address`),
  ADD KEY `banned_ips_added_by_foreign` (`added_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_templates_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kb_articles`
--
ALTER TABLE `kb_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kb_articles_slug_unique` (`slug`),
  ADD KEY `kb_articles_category_id_foreign` (`category_id`);

--
-- Indexes for table `kb_categories`
--
ALTER TABLE `kb_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kb_categories_slug_unique` (`slug`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `sla_rules`
--
ALTER TABLE `sla_rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sla_rules_slug_unique` (`slug`),
  ADD KEY `sla_rules_priority_id_foreign` (`priority_id`),
  ADD KEY `sla_rules_category_id_foreign` (`category_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_ticket_number_unique` (`ticket_number`),
  ADD KEY `tickets_category_id_foreign` (`category_id`),
  ADD KEY `tickets_sla_rule_id_foreign` (`sla_rule_id`),
  ADD KEY `tickets_ticket_number_index` (`ticket_number`),
  ADD KEY `tickets_created_by_index` (`created_by`),
  ADD KEY `tickets_assigned_to_index` (`assigned_to`),
  ADD KEY `tickets_status_id_index` (`status_id`),
  ADD KEY `tickets_priority_id_index` (`priority_id`),
  ADD KEY `tickets_due_date_index` (`due_date`);

--
-- Indexes for table `ticket_assignments`
--
ALTER TABLE `ticket_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_assignments_ticket_id_user_id_unique` (`ticket_id`,`user_id`),
  ADD KEY `ticket_assignments_user_id_foreign` (`user_id`),
  ADD KEY `ticket_assignments_ticket_id_user_id_index` (`ticket_id`,`user_id`);

--
-- Indexes for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_attachments_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `ticket_attachments_ticket_id_index` (`ticket_id`),
  ADD KEY `ticket_attachments_ticket_reply_id_index` (`ticket_reply_id`);

--
-- Indexes for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_categories_slug_unique` (`slug`);

--
-- Indexes for table `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_priorities_slug_unique` (`slug`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_ticket_id_index` (`ticket_id`),
  ADD KEY `ticket_replies_user_id_index` (`user_id`),
  ADD KEY `ticket_replies_is_internal_note_index` (`is_internal_note`);

--
-- Indexes for table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_statuses_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_hash_link_unique` (`hash_link`),
  ADD KEY `users_hash_link_index` (`hash_link`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `whitelist_emails`
--
ALTER TABLE `whitelist_emails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `whitelist_emails_email_unique` (`email`),
  ADD KEY `whitelist_emails_added_by_foreign` (`added_by`);

--
-- Indexes for table `whitelist_ips`
--
ALTER TABLE `whitelist_ips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `whitelist_ips_ip_address_unique` (`ip_address`),
  ADD KEY `whitelist_ips_added_by_foreign` (`added_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `bad_websites`
--
ALTER TABLE `bad_websites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bad_words`
--
ALTER TABLE `bad_words`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `banned_emails`
--
ALTER TABLE `banned_emails`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `banned_ips`
--
ALTER TABLE `banned_ips`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kb_articles`
--
ALTER TABLE `kb_articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `kb_categories`
--
ALTER TABLE `kb_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `sla_rules`
--
ALTER TABLE `sla_rules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ticket_assignments`
--
ALTER TABLE `ticket_assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_categories`
--
ALTER TABLE `ticket_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `whitelist_emails`
--
ALTER TABLE `whitelist_emails`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whitelist_ips`
--
ALTER TABLE `whitelist_ips`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bad_websites`
--
ALTER TABLE `bad_websites`
  ADD CONSTRAINT `bad_websites_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bad_words`
--
ALTER TABLE `bad_words`
  ADD CONSTRAINT `bad_words_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `banned_emails`
--
ALTER TABLE `banned_emails`
  ADD CONSTRAINT `banned_emails_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `banned_ips`
--
ALTER TABLE `banned_ips`
  ADD CONSTRAINT `banned_ips_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kb_articles`
--
ALTER TABLE `kb_articles`
  ADD CONSTRAINT `kb_articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `kb_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sla_rules`
--
ALTER TABLE `sla_rules`
  ADD CONSTRAINT `sla_rules_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `ticket_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sla_rules_priority_id_foreign` FOREIGN KEY (`priority_id`) REFERENCES `ticket_priorities` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `ticket_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_priority_id_foreign` FOREIGN KEY (`priority_id`) REFERENCES `ticket_priorities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_sla_rule_id_foreign` FOREIGN KEY (`sla_rule_id`) REFERENCES `sla_rules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `ticket_statuses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_assignments`
--
ALTER TABLE `ticket_assignments`
  ADD CONSTRAINT `ticket_assignments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_attachments`
--
ALTER TABLE `ticket_attachments`
  ADD CONSTRAINT `ticket_attachments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_attachments_ticket_reply_id_foreign` FOREIGN KEY (`ticket_reply_id`) REFERENCES `ticket_replies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `whitelist_emails`
--
ALTER TABLE `whitelist_emails`
  ADD CONSTRAINT `whitelist_emails_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `whitelist_ips`
--
ALTER TABLE `whitelist_ips`
  ADD CONSTRAINT `whitelist_ips_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
