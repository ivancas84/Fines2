-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 29-05-2026 a las 18:33:26
-- Versión del servidor: 10.6.20-MariaDB
-- Versión de PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `planfi10_wp211`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_actionscheduler_actions`
--

CREATE TABLE `wpwt_actionscheduler_actions` (
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `hook` varchar(191) NOT NULL,
  `status` varchar(20) NOT NULL,
  `scheduled_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `scheduled_date_local` datetime DEFAULT '0000-00-00 00:00:00',
  `priority` tinyint(3) UNSIGNED NOT NULL DEFAULT 10,
  `args` varchar(191) DEFAULT NULL,
  `schedule` longtext DEFAULT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `last_attempt_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `last_attempt_local` datetime DEFAULT '0000-00-00 00:00:00',
  `claim_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `extended_args` varchar(8000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_actionscheduler_claims`
--

CREATE TABLE `wpwt_actionscheduler_claims` (
  `claim_id` bigint(20) UNSIGNED NOT NULL,
  `date_created_gmt` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_actionscheduler_groups`
--

CREATE TABLE `wpwt_actionscheduler_groups` (
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_actionscheduler_logs`
--

CREATE TABLE `wpwt_actionscheduler_logs` (
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `action_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `log_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `log_date_local` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_bit_fm_logs`
--

CREATE TABLE `wpwt_bit_fm_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `command` varchar(32) NOT NULL,
  `details` longtext NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_commentmeta`
--

CREATE TABLE `wpwt_commentmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_comments`
--

CREATE TABLE `wpwt_comments` (
  `comment_ID` bigint(20) UNSIGNED NOT NULL,
  `comment_post_ID` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT 0,
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_e_events`
--

CREATE TABLE `wpwt_e_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_data` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_e_submissions`
--

CREATE TABLE `wpwt_e_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(60) DEFAULT NULL,
  `hash_id` varchar(60) NOT NULL,
  `main_meta_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Id of main field. to represent the main meta field',
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `referer` varchar(500) NOT NULL,
  `referer_title` varchar(300) DEFAULT NULL,
  `element_id` varchar(20) NOT NULL,
  `form_name` varchar(60) NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_ip` varchar(46) NOT NULL,
  `user_agent` text NOT NULL,
  `actions_count` int(11) DEFAULT 0,
  `actions_succeeded_count` int(11) DEFAULT 0,
  `status` varchar(20) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `meta` text DEFAULT NULL,
  `created_at_gmt` datetime NOT NULL,
  `updated_at_gmt` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_e_submissions_actions_log`
--

CREATE TABLE `wpwt_e_submissions_actions_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL,
  `action_name` varchar(60) NOT NULL,
  `action_label` varchar(60) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `log` text DEFAULT NULL,
  `created_at_gmt` datetime NOT NULL,
  `updated_at_gmt` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_e_submissions_values`
--

CREATE TABLE `wpwt_e_submissions_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `submission_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `key` varchar(60) DEFAULT NULL,
  `value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_frmt_form_entry`
--

CREATE TABLE `wpwt_frmt_form_entry` (
  `entry_id` bigint(20) UNSIGNED NOT NULL,
  `entry_type` varchar(191) NOT NULL,
  `draft_id` varchar(12) DEFAULT NULL,
  `form_id` bigint(20) UNSIGNED NOT NULL,
  `is_spam` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('active','spam','draft','abandoned') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_frmt_form_entry_meta`
--

CREATE TABLE `wpwt_frmt_form_entry_meta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `entry_id` bigint(20) UNSIGNED NOT NULL,
  `meta_key` varchar(191) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_frmt_form_reports`
--

CREATE TABLE `wpwt_frmt_form_reports` (
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `report_value` longtext NOT NULL,
  `status` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_frmt_form_views`
--

CREATE TABLE `wpwt_frmt_form_views` (
  `view_id` bigint(20) UNSIGNED NOT NULL,
  `form_id` bigint(20) UNSIGNED NOT NULL,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(191) DEFAULT NULL,
  `count` mediumint(8) UNSIGNED NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_links`
--

CREATE TABLE `wpwt_links` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `link_rating` int(11) NOT NULL DEFAULT 0,
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_loginizer_logs`
--

CREATE TABLE `wpwt_loginizer_logs` (
  `username` varchar(255) NOT NULL DEFAULT '',
  `time` int(10) NOT NULL DEFAULT 0,
  `count` int(10) NOT NULL DEFAULT 0,
  `lockout` int(10) NOT NULL DEFAULT 0,
  `ip` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_login_redirects`
--

CREATE TABLE `wpwt_login_redirects` (
  `id` bigint(20) NOT NULL,
  `rul_type` varchar(100) NOT NULL,
  `rul_value` varchar(191) DEFAULT NULL,
  `rul_url` longtext DEFAULT NULL,
  `rul_url_logout` longtext DEFAULT NULL,
  `rul_order` int(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_options`
--

CREATE TABLE `wpwt_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_postmeta`
--

CREATE TABLE `wpwt_postmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_posts`
--

CREATE TABLE `wpwt_posts` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(255) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_agents`
--

CREATE TABLE `wpwt_psmsc_agents` (
  `id` bigint(20) NOT NULL,
  `user` bigint(20) DEFAULT 0,
  `customer` bigint(20) DEFAULT 0,
  `role` int(11) NOT NULL DEFAULT 0,
  `name` varchar(200) NOT NULL,
  `workload` int(11) DEFAULT NULL,
  `unresolved_count` int(11) DEFAULT NULL,
  `is_agentgroup` int(1) NOT NULL DEFAULT 0,
  `is_active` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_archived_threads`
--

CREATE TABLE `wpwt_psmsc_archived_threads` (
  `id` bigint(20) NOT NULL,
  `ticket` bigint(20) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `customer` bigint(20) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `body` longtext NOT NULL,
  `attachments` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `seen` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_archived_tickets`
--

CREATE TABLE `wpwt_psmsc_archived_tickets` (
  `id` bigint(20) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `customer` bigint(20) NOT NULL,
  `subject` text NOT NULL,
  `status` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `assigned_agent` text DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `agent_created` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `add_recipients` text DEFAULT NULL,
  `prev_assignee` text DEFAULT NULL,
  `date_closed` datetime DEFAULT NULL,
  `user_type` varchar(100) NOT NULL,
  `last_reply_on` datetime DEFAULT NULL,
  `last_reply_by` bigint(20) NOT NULL,
  `auth_code` varchar(50) DEFAULT NULL,
  `cust_24` tinytext DEFAULT NULL,
  `cust_25` tinytext DEFAULT NULL,
  `cust_26` tinytext DEFAULT NULL,
  `cust_27` tinytext DEFAULT NULL,
  `cust_28` tinytext DEFAULT NULL,
  `tags` tinytext DEFAULT NULL,
  `live_agents` tinytext DEFAULT NULL,
  `last_reply_source` varchar(50) DEFAULT NULL,
  `misc` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_attachments`
--

CREATE TABLE `wpwt_psmsc_attachments` (
  `id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `file_path` text NOT NULL,
  `is_image` int(1) NOT NULL DEFAULT 0,
  `is_active` int(1) NOT NULL DEFAULT 0,
  `is_uploaded` int(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL,
  `source` varchar(200) NOT NULL,
  `source_id` bigint(20) NOT NULL DEFAULT 0,
  `ticket_id` bigint(20) NOT NULL DEFAULT 0,
  `customer_id` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_categories`
--

CREATE TABLE `wpwt_psmsc_categories` (
  `id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `load_order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_customers`
--

CREATE TABLE `wpwt_psmsc_customers` (
  `id` bigint(20) NOT NULL,
  `user` bigint(20) NOT NULL,
  `ticket_count` int(11) NOT NULL DEFAULT 0,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_custom_fields`
--

CREATE TABLE `wpwt_psmsc_custom_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `extra_info` text DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `field` varchar(50) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `default_value` text DEFAULT NULL,
  `placeholder_text` text DEFAULT NULL,
  `char_limit` int(11) DEFAULT NULL,
  `date_display_as` varchar(50) DEFAULT NULL,
  `date_format` varchar(50) DEFAULT NULL,
  `date_range` varchar(50) DEFAULT NULL,
  `start_range` datetime DEFAULT NULL,
  `end_range` datetime DEFAULT NULL,
  `time_format` int(11) DEFAULT NULL,
  `is_personal_info` int(1) NOT NULL DEFAULT 0,
  `is_auto_fill` int(1) DEFAULT NULL,
  `allow_ticket_form` int(1) DEFAULT 1,
  `allow_my_profile` int(1) DEFAULT 1,
  `tl_width` int(3) NOT NULL DEFAULT 100,
  `load_order` int(11) NOT NULL DEFAULT 1,
  `number_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_email_notifications`
--

CREATE TABLE `wpwt_psmsc_email_notifications` (
  `id` int(11) NOT NULL,
  `from_name` varchar(200) DEFAULT NULL,
  `from_email` varchar(200) DEFAULT NULL,
  `reply_to` varchar(200) DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `to_email` text DEFAULT NULL,
  `cc_email` text DEFAULT NULL,
  `bcc_email` text DEFAULT NULL,
  `attachments` text DEFAULT NULL,
  `attempt` int(1) DEFAULT 0,
  `priority` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_email_otp`
--

CREATE TABLE `wpwt_psmsc_email_otp` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `date_expiry` datetime NOT NULL,
  `data` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_holidays`
--

CREATE TABLE `wpwt_psmsc_holidays` (
  `id` int(11) NOT NULL,
  `agent` bigint(20) NOT NULL,
  `holiday` datetime NOT NULL,
  `is_recurring` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_logs`
--

CREATE TABLE `wpwt_psmsc_logs` (
  `id` bigint(20) NOT NULL,
  `type` varchar(200) NOT NULL,
  `ref_id` bigint(20) NOT NULL,
  `modified_by` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_options`
--

CREATE TABLE `wpwt_psmsc_options` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `custom_field` int(11) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL,
  `load_order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_priorities`
--

CREATE TABLE `wpwt_psmsc_priorities` (
  `id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `color` varchar(50) NOT NULL,
  `bg_color` varchar(50) NOT NULL,
  `load_order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_scheduled_tasks`
--

CREATE TABLE `wpwt_psmsc_scheduled_tasks` (
  `id` int(11) NOT NULL,
  `class` varchar(200) NOT NULL,
  `method` varchar(200) NOT NULL,
  `args` tinytext DEFAULT NULL,
  `is_manual` int(1) NOT NULL DEFAULT 0,
  `warning_text` tinytext DEFAULT NULL,
  `warning_link_text` tinytext DEFAULT NULL,
  `progressbar_text` tinytext DEFAULT NULL,
  `pages` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_statuses`
--

CREATE TABLE `wpwt_psmsc_statuses` (
  `id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `color` varchar(50) NOT NULL,
  `bg_color` varchar(50) NOT NULL,
  `load_order` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_threads`
--

CREATE TABLE `wpwt_psmsc_threads` (
  `id` bigint(20) NOT NULL,
  `ticket` bigint(20) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `customer` bigint(20) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `body` longtext NOT NULL,
  `attachments` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `seen` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_tickets`
--

CREATE TABLE `wpwt_psmsc_tickets` (
  `id` bigint(20) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `customer` bigint(20) NOT NULL,
  `subject` text NOT NULL,
  `status` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `assigned_agent` text DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `agent_created` int(11) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `source` varchar(50) DEFAULT NULL,
  `browser` varchar(50) DEFAULT NULL,
  `os` varchar(50) DEFAULT NULL,
  `add_recipients` text DEFAULT NULL,
  `prev_assignee` text DEFAULT NULL,
  `date_closed` datetime DEFAULT NULL,
  `user_type` varchar(100) NOT NULL,
  `last_reply_on` datetime DEFAULT NULL,
  `last_reply_by` bigint(20) NOT NULL,
  `auth_code` varchar(50) DEFAULT NULL,
  `cust_24` tinytext DEFAULT NULL,
  `cust_25` tinytext DEFAULT NULL,
  `cust_26` tinytext DEFAULT NULL,
  `cust_27` tinytext DEFAULT NULL,
  `cust_28` tinytext DEFAULT NULL,
  `tags` tinytext DEFAULT NULL,
  `live_agents` tinytext DEFAULT NULL,
  `last_reply_source` varchar(50) DEFAULT NULL,
  `misc` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_ticket_tags`
--

CREATE TABLE `wpwt_psmsc_ticket_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` tinytext NOT NULL,
  `color` varchar(50) NOT NULL,
  `bg_color` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_wh_exceptions`
--

CREATE TABLE `wpwt_psmsc_wh_exceptions` (
  `id` int(11) NOT NULL,
  `agent` bigint(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `exception_date` datetime NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_psmsc_working_hrs`
--

CREATE TABLE `wpwt_psmsc_working_hrs` (
  `id` int(11) NOT NULL,
  `agent` bigint(20) NOT NULL,
  `day` tinyint(4) NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `end_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_termmeta`
--

CREATE TABLE `wpwt_termmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_terms`
--

CREATE TABLE `wpwt_terms` (
  `term_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_term_relationships`
--

CREATE TABLE `wpwt_term_relationships` (
  `object_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `term_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_term_taxonomy`
--

CREATE TABLE `wpwt_term_taxonomy` (
  `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `count` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_usermeta`
--

CREATE TABLE `wpwt_usermeta` (
  `umeta_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_users`
--

CREATE TABLE `wpwt_users` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(255) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 0,
  `display_name` varchar(250) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_yoast_indexable`
--

CREATE TABLE `wpwt_yoast_indexable` (
  `id` int(11) UNSIGNED NOT NULL,
  `permalink` longtext DEFAULT NULL,
  `permalink_hash` varchar(40) DEFAULT NULL,
  `object_id` bigint(20) DEFAULT NULL,
  `object_type` varchar(32) NOT NULL,
  `object_sub_type` varchar(32) DEFAULT NULL,
  `author_id` bigint(20) DEFAULT NULL,
  `post_parent` bigint(20) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `breadcrumb_title` text DEFAULT NULL,
  `post_status` varchar(20) DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT NULL,
  `is_protected` tinyint(1) DEFAULT 0,
  `has_public_posts` tinyint(1) DEFAULT NULL,
  `number_of_pages` int(11) UNSIGNED DEFAULT NULL,
  `canonical` longtext DEFAULT NULL,
  `primary_focus_keyword` varchar(191) DEFAULT NULL,
  `primary_focus_keyword_score` int(3) DEFAULT NULL,
  `readability_score` int(3) DEFAULT NULL,
  `is_cornerstone` tinyint(1) DEFAULT 0,
  `is_robots_noindex` tinyint(1) DEFAULT 0,
  `is_robots_nofollow` tinyint(1) DEFAULT 0,
  `is_robots_noarchive` tinyint(1) DEFAULT 0,
  `is_robots_noimageindex` tinyint(1) DEFAULT 0,
  `is_robots_nosnippet` tinyint(1) DEFAULT 0,
  `twitter_title` text DEFAULT NULL,
  `twitter_image` longtext DEFAULT NULL,
  `twitter_description` longtext DEFAULT NULL,
  `twitter_image_id` varchar(191) DEFAULT NULL,
  `twitter_image_source` text DEFAULT NULL,
  `open_graph_title` text DEFAULT NULL,
  `open_graph_description` longtext DEFAULT NULL,
  `open_graph_image` longtext DEFAULT NULL,
  `open_graph_image_id` varchar(191) DEFAULT NULL,
  `open_graph_image_source` text DEFAULT NULL,
  `open_graph_image_meta` mediumtext DEFAULT NULL,
  `link_count` int(11) DEFAULT NULL,
  `incoming_link_count` int(11) DEFAULT NULL,
  `prominent_words_version` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blog_id` bigint(20) NOT NULL DEFAULT 1,
  `language` varchar(32) DEFAULT NULL,
  `region` varchar(32) DEFAULT NULL,
  `schema_page_type` varchar(64) DEFAULT NULL,
  `schema_article_type` varchar(64) DEFAULT NULL,
  `has_ancestors` tinyint(1) DEFAULT 0,
  `estimated_reading_time_minutes` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT 1,
  `object_last_modified` datetime DEFAULT NULL,
  `object_published_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_yoast_indexable_hierarchy`
--

CREATE TABLE `wpwt_yoast_indexable_hierarchy` (
  `indexable_id` int(11) UNSIGNED NOT NULL,
  `ancestor_id` int(11) UNSIGNED NOT NULL,
  `depth` int(11) UNSIGNED DEFAULT NULL,
  `blog_id` bigint(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_yoast_migrations`
--

CREATE TABLE `wpwt_yoast_migrations` (
  `id` int(11) UNSIGNED NOT NULL,
  `version` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_yoast_primary_term`
--

CREATE TABLE `wpwt_yoast_primary_term` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `term_id` bigint(20) DEFAULT NULL,
  `taxonomy` varchar(32) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `blog_id` bigint(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wpwt_yoast_seo_links`
--

CREATE TABLE `wpwt_yoast_seo_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(8) DEFAULT NULL,
  `indexable_id` int(11) UNSIGNED DEFAULT NULL,
  `target_indexable_id` int(11) UNSIGNED DEFAULT NULL,
  `height` int(11) UNSIGNED DEFAULT NULL,
  `width` int(11) UNSIGNED DEFAULT NULL,
  `size` int(11) UNSIGNED DEFAULT NULL,
  `language` varchar(32) DEFAULT NULL,
  `region` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `wpwt_actionscheduler_actions`
--
ALTER TABLE `wpwt_actionscheduler_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `hook` (`hook`),
  ADD KEY `status` (`status`),
  ADD KEY `scheduled_date_gmt` (`scheduled_date_gmt`),
  ADD KEY `args` (`args`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `last_attempt_gmt` (`last_attempt_gmt`),
  ADD KEY `claim_id_status_scheduled_date_gmt` (`claim_id`,`status`,`scheduled_date_gmt`);

--
-- Indices de la tabla `wpwt_actionscheduler_claims`
--
ALTER TABLE `wpwt_actionscheduler_claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `date_created_gmt` (`date_created_gmt`);

--
-- Indices de la tabla `wpwt_actionscheduler_groups`
--
ALTER TABLE `wpwt_actionscheduler_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `slug` (`slug`(191));

--
-- Indices de la tabla `wpwt_actionscheduler_logs`
--
ALTER TABLE `wpwt_actionscheduler_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `log_date_gmt` (`log_date_gmt`);

--
-- Indices de la tabla `wpwt_bit_fm_logs`
--
ALTER TABLE `wpwt_bit_fm_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_commentmeta`
--
ALTER TABLE `wpwt_commentmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indices de la tabla `wpwt_comments`
--
ALTER TABLE `wpwt_comments`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `comment_post_ID` (`comment_post_ID`),
  ADD KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  ADD KEY `comment_date_gmt` (`comment_date_gmt`),
  ADD KEY `comment_parent` (`comment_parent`),
  ADD KEY `comment_author_email` (`comment_author_email`(10));

--
-- Indices de la tabla `wpwt_e_events`
--
ALTER TABLE `wpwt_e_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_at_index` (`created_at`);

--
-- Indices de la tabla `wpwt_e_submissions`
--
ALTER TABLE `wpwt_e_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hash_id_unique_index` (`hash_id`),
  ADD KEY `main_meta_id_index` (`main_meta_id`),
  ADD KEY `hash_id_index` (`hash_id`),
  ADD KEY `type_index` (`type`),
  ADD KEY `post_id_index` (`post_id`),
  ADD KEY `element_id_index` (`element_id`),
  ADD KEY `campaign_id_index` (`campaign_id`),
  ADD KEY `user_id_index` (`user_id`),
  ADD KEY `user_ip_index` (`user_ip`),
  ADD KEY `status_index` (`status`),
  ADD KEY `is_read_index` (`is_read`),
  ADD KEY `created_at_gmt_index` (`created_at_gmt`),
  ADD KEY `updated_at_gmt_index` (`updated_at_gmt`),
  ADD KEY `created_at_index` (`created_at`),
  ADD KEY `updated_at_index` (`updated_at`),
  ADD KEY `referer_index` (`referer`(191)),
  ADD KEY `referer_title_index` (`referer_title`(191));

--
-- Indices de la tabla `wpwt_e_submissions_actions_log`
--
ALTER TABLE `wpwt_e_submissions_actions_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id_index` (`submission_id`),
  ADD KEY `action_name_index` (`action_name`),
  ADD KEY `status_index` (`status`),
  ADD KEY `created_at_gmt_index` (`created_at_gmt`),
  ADD KEY `updated_at_gmt_index` (`updated_at_gmt`),
  ADD KEY `created_at_index` (`created_at`),
  ADD KEY `updated_at_index` (`updated_at`);

--
-- Indices de la tabla `wpwt_e_submissions_values`
--
ALTER TABLE `wpwt_e_submissions_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submission_id_index` (`submission_id`),
  ADD KEY `key_index` (`key`);

--
-- Indices de la tabla `wpwt_frmt_form_entry`
--
ALTER TABLE `wpwt_frmt_form_entry`
  ADD PRIMARY KEY (`entry_id`),
  ADD KEY `entry_is_spam` (`is_spam`),
  ADD KEY `entry_type` (`entry_type`),
  ADD KEY `entry_form_id` (`form_id`),
  ADD KEY `entry_status` (`status`),
  ADD KEY `entry_form_status` (`form_id`,`status`);

--
-- Indices de la tabla `wpwt_frmt_form_entry_meta`
--
ALTER TABLE `wpwt_frmt_form_entry_meta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `meta_key` (`meta_key`),
  ADD KEY `meta_entry_id` (`entry_id`),
  ADD KEY `meta_key_object` (`entry_id`,`meta_key`);

--
-- Indices de la tabla `wpwt_frmt_form_reports`
--
ALTER TABLE `wpwt_frmt_form_reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indices de la tabla `wpwt_frmt_form_views`
--
ALTER TABLE `wpwt_frmt_form_views`
  ADD PRIMARY KEY (`view_id`),
  ADD KEY `view_form_id` (`form_id`),
  ADD KEY `view_ip` (`ip`),
  ADD KEY `view_form_object` (`form_id`,`view_id`),
  ADD KEY `view_form_object_ip` (`form_id`,`view_id`,`ip`);

--
-- Indices de la tabla `wpwt_links`
--
ALTER TABLE `wpwt_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_visible` (`link_visible`);

--
-- Indices de la tabla `wpwt_loginizer_logs`
--
ALTER TABLE `wpwt_loginizer_logs`
  ADD UNIQUE KEY `ip` (`ip`);

--
-- Indices de la tabla `wpwt_login_redirects`
--
ALTER TABLE `wpwt_login_redirects`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_options`
--
ALTER TABLE `wpwt_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`),
  ADD KEY `autoload` (`autoload`);

--
-- Indices de la tabla `wpwt_postmeta`
--
ALTER TABLE `wpwt_postmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indices de la tabla `wpwt_posts`
--
ALTER TABLE `wpwt_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`),
  ADD KEY `type_status_author` (`post_type`,`post_status`,`post_author`);

--
-- Indices de la tabla `wpwt_psmsc_agents`
--
ALTER TABLE `wpwt_psmsc_agents`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_archived_threads`
--
ALTER TABLE `wpwt_psmsc_archived_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ticket_id` (`ticket`);

--
-- Indices de la tabla `wpwt_psmsc_archived_tickets`
--
ALTER TABLE `wpwt_psmsc_archived_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cf_status` (`status`),
  ADD KEY `idx_cf_customer` (`customer`),
  ADD KEY `idx_cf_category` (`category`),
  ADD KEY `idx_cf_priority` (`priority`),
  ADD KEY `idx_cf_date_updated` (`date_updated`),
  ADD KEY `idx_cf_date_created` (`date_created`),
  ADD KEY `idx_cf_date_closed` (`date_closed`);

--
-- Indices de la tabla `wpwt_psmsc_attachments`
--
ALTER TABLE `wpwt_psmsc_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_categories`
--
ALTER TABLE `wpwt_psmsc_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_customers`
--
ALTER TABLE `wpwt_psmsc_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_custom_fields`
--
ALTER TABLE `wpwt_psmsc_custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_email_notifications`
--
ALTER TABLE `wpwt_psmsc_email_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_email_otp`
--
ALTER TABLE `wpwt_psmsc_email_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_holidays`
--
ALTER TABLE `wpwt_psmsc_holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_logs`
--
ALTER TABLE `wpwt_psmsc_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_options`
--
ALTER TABLE `wpwt_psmsc_options`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_priorities`
--
ALTER TABLE `wpwt_psmsc_priorities`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_scheduled_tasks`
--
ALTER TABLE `wpwt_psmsc_scheduled_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_statuses`
--
ALTER TABLE `wpwt_psmsc_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_threads`
--
ALTER TABLE `wpwt_psmsc_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ticket_id` (`ticket`);

--
-- Indices de la tabla `wpwt_psmsc_tickets`
--
ALTER TABLE `wpwt_psmsc_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cf_status` (`status`),
  ADD KEY `idx_cf_customer` (`customer`),
  ADD KEY `idx_cf_category` (`category`),
  ADD KEY `idx_cf_priority` (`priority`),
  ADD KEY `idx_cf_date_updated` (`date_updated`),
  ADD KEY `idx_cf_date_created` (`date_created`),
  ADD KEY `idx_cf_date_closed` (`date_closed`);

--
-- Indices de la tabla `wpwt_psmsc_ticket_tags`
--
ALTER TABLE `wpwt_psmsc_ticket_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_wh_exceptions`
--
ALTER TABLE `wpwt_psmsc_wh_exceptions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_psmsc_working_hrs`
--
ALTER TABLE `wpwt_psmsc_working_hrs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wpwt_termmeta`
--
ALTER TABLE `wpwt_termmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indices de la tabla `wpwt_terms`
--
ALTER TABLE `wpwt_terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `name` (`name`(191));

--
-- Indices de la tabla `wpwt_term_relationships`
--
ALTER TABLE `wpwt_term_relationships`
  ADD PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  ADD KEY `term_taxonomy_id` (`term_taxonomy_id`);

--
-- Indices de la tabla `wpwt_term_taxonomy`
--
ALTER TABLE `wpwt_term_taxonomy`
  ADD PRIMARY KEY (`term_taxonomy_id`),
  ADD UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  ADD KEY `taxonomy` (`taxonomy`);

--
-- Indices de la tabla `wpwt_usermeta`
--
ALTER TABLE `wpwt_usermeta`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indices de la tabla `wpwt_users`
--
ALTER TABLE `wpwt_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- Indices de la tabla `wpwt_yoast_indexable`
--
ALTER TABLE `wpwt_yoast_indexable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `object_type_and_sub_type` (`object_type`,`object_sub_type`),
  ADD KEY `object_id_and_type` (`object_id`,`object_type`),
  ADD KEY `permalink_hash_and_object_type` (`permalink_hash`,`object_type`),
  ADD KEY `subpages` (`post_parent`,`object_type`,`post_status`,`object_id`),
  ADD KEY `prominent_words` (`prominent_words_version`,`object_type`,`object_sub_type`,`post_status`),
  ADD KEY `published_sitemap_index` (`object_published_at`,`is_robots_noindex`,`object_type`,`object_sub_type`);

--
-- Indices de la tabla `wpwt_yoast_indexable_hierarchy`
--
ALTER TABLE `wpwt_yoast_indexable_hierarchy`
  ADD PRIMARY KEY (`indexable_id`,`ancestor_id`),
  ADD KEY `indexable_id` (`indexable_id`),
  ADD KEY `ancestor_id` (`ancestor_id`),
  ADD KEY `depth` (`depth`);

--
-- Indices de la tabla `wpwt_yoast_migrations`
--
ALTER TABLE `wpwt_yoast_migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wpwt_yoast_migrations_version` (`version`);

--
-- Indices de la tabla `wpwt_yoast_primary_term`
--
ALTER TABLE `wpwt_yoast_primary_term`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_taxonomy` (`post_id`,`taxonomy`),
  ADD KEY `post_term` (`post_id`,`term_id`);

--
-- Indices de la tabla `wpwt_yoast_seo_links`
--
ALTER TABLE `wpwt_yoast_seo_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `link_direction` (`post_id`,`type`),
  ADD KEY `indexable_link_direction` (`indexable_id`,`type`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `wpwt_actionscheduler_actions`
--
ALTER TABLE `wpwt_actionscheduler_actions`
  MODIFY `action_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_actionscheduler_claims`
--
ALTER TABLE `wpwt_actionscheduler_claims`
  MODIFY `claim_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_actionscheduler_groups`
--
ALTER TABLE `wpwt_actionscheduler_groups`
  MODIFY `group_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_actionscheduler_logs`
--
ALTER TABLE `wpwt_actionscheduler_logs`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_bit_fm_logs`
--
ALTER TABLE `wpwt_bit_fm_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_commentmeta`
--
ALTER TABLE `wpwt_commentmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_comments`
--
ALTER TABLE `wpwt_comments`
  MODIFY `comment_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_e_events`
--
ALTER TABLE `wpwt_e_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_e_submissions`
--
ALTER TABLE `wpwt_e_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_e_submissions_actions_log`
--
ALTER TABLE `wpwt_e_submissions_actions_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_e_submissions_values`
--
ALTER TABLE `wpwt_e_submissions_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_frmt_form_entry`
--
ALTER TABLE `wpwt_frmt_form_entry`
  MODIFY `entry_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_frmt_form_entry_meta`
--
ALTER TABLE `wpwt_frmt_form_entry_meta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_frmt_form_reports`
--
ALTER TABLE `wpwt_frmt_form_reports`
  MODIFY `report_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_frmt_form_views`
--
ALTER TABLE `wpwt_frmt_form_views`
  MODIFY `view_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_links`
--
ALTER TABLE `wpwt_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_login_redirects`
--
ALTER TABLE `wpwt_login_redirects`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_options`
--
ALTER TABLE `wpwt_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_postmeta`
--
ALTER TABLE `wpwt_postmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_posts`
--
ALTER TABLE `wpwt_posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_agents`
--
ALTER TABLE `wpwt_psmsc_agents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_archived_threads`
--
ALTER TABLE `wpwt_psmsc_archived_threads`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_archived_tickets`
--
ALTER TABLE `wpwt_psmsc_archived_tickets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_attachments`
--
ALTER TABLE `wpwt_psmsc_attachments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_categories`
--
ALTER TABLE `wpwt_psmsc_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_customers`
--
ALTER TABLE `wpwt_psmsc_customers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_custom_fields`
--
ALTER TABLE `wpwt_psmsc_custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_email_notifications`
--
ALTER TABLE `wpwt_psmsc_email_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_email_otp`
--
ALTER TABLE `wpwt_psmsc_email_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_holidays`
--
ALTER TABLE `wpwt_psmsc_holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_logs`
--
ALTER TABLE `wpwt_psmsc_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_options`
--
ALTER TABLE `wpwt_psmsc_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_priorities`
--
ALTER TABLE `wpwt_psmsc_priorities`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_scheduled_tasks`
--
ALTER TABLE `wpwt_psmsc_scheduled_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_statuses`
--
ALTER TABLE `wpwt_psmsc_statuses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_threads`
--
ALTER TABLE `wpwt_psmsc_threads`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_tickets`
--
ALTER TABLE `wpwt_psmsc_tickets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_ticket_tags`
--
ALTER TABLE `wpwt_psmsc_ticket_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_wh_exceptions`
--
ALTER TABLE `wpwt_psmsc_wh_exceptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_psmsc_working_hrs`
--
ALTER TABLE `wpwt_psmsc_working_hrs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_termmeta`
--
ALTER TABLE `wpwt_termmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_terms`
--
ALTER TABLE `wpwt_terms`
  MODIFY `term_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_term_taxonomy`
--
ALTER TABLE `wpwt_term_taxonomy`
  MODIFY `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_usermeta`
--
ALTER TABLE `wpwt_usermeta`
  MODIFY `umeta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_users`
--
ALTER TABLE `wpwt_users`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_yoast_indexable`
--
ALTER TABLE `wpwt_yoast_indexable`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_yoast_migrations`
--
ALTER TABLE `wpwt_yoast_migrations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_yoast_primary_term`
--
ALTER TABLE `wpwt_yoast_primary_term`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `wpwt_yoast_seo_links`
--
ALTER TABLE `wpwt_yoast_seo_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
