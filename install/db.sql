SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `passwd` text COLLATE utf8_unicode_ci NOT NULL,
  `ga_token` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `admins` (`id`, `username`, `passwd`, `ga_token`) VALUES
(1,	'admin',	'8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918',	'');

DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE `admin_logs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `admin_username` text COLLATE utf8_unicode_ci NOT NULL,
  `ip` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `browser` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `sender` int(255) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `deposits`;
CREATE TABLE `deposits` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `player_id` int(255) NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `received` int(1) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `txid` text COLLATE utf8_unicode_ci NOT NULL,
  `time_generated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `giveaway_ip_limit`;
CREATE TABLE `giveaway_ip_limit` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ip` text COLLATE utf8_unicode_ci NOT NULL,
  `claimed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `investors`;
CREATE TABLE `investors` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `player_id` int(255) NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `profit` double NOT NULL DEFAULT '0',
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `hash` text COLLATE utf8_unicode_ci NOT NULL,
  `balance` double NOT NULL DEFAULT '0',
  `alias` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_last_active` datetime NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `lastip` text COLLATE utf8_unicode_ci NOT NULL,
  `server_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `client_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `last_server_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `last_client_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `last_final_result` text COLLATE utf8_unicode_ci NOT NULL,
  `t_bets` int(255) NOT NULL DEFAULT '0',
  `t_wagered` double NOT NULL DEFAULT '0',
  `t_wins` int(255) NOT NULL DEFAULT '0',
  `t_profit` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `spins`;
CREATE TABLE `spins` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `player` int(255) NOT NULL,
  `bet_amount` double NOT NULL,
  `server_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `client_seed` text COLLATE utf8_unicode_ci NOT NULL,
  `result` text COLLATE utf8_unicode_ci NOT NULL,
  `multiplier` double NOT NULL,
  `payout` double NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `player` (`player`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `system`;
CREATE TABLE `system` (
  `id` int(1) NOT NULL DEFAULT '1',
  `autoalias_increment` int(255) NOT NULL DEFAULT '1',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `currency` text COLLATE utf8_unicode_ci NOT NULL,
  `currency_sign` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `giveaway` int(1) NOT NULL DEFAULT '0',
  `giveaway_amount` double NOT NULL DEFAULT '0',
  `chat_enable` int(1) NOT NULL DEFAULT '1',
  `t_bets` int(255) NOT NULL DEFAULT '0',
  `t_wagered` double NOT NULL DEFAULT '0',
  `t_wins` int(255) NOT NULL DEFAULT '0',
  `t_loses` int(255) NOT NULL DEFAULT '0',
  `t_ties` int(255) NOT NULL DEFAULT '0',
  `t_player_profit` double NOT NULL DEFAULT '0',
  `giveaway_freq` int(255) NOT NULL DEFAULT '30',
  `min_bet` double NOT NULL DEFAULT '0.00000001',
  `min_withdrawal` double NOT NULL DEFAULT '0.001',
  `min_deposit` double NOT NULL DEFAULT '0.00000001',
  `min_confirmations` int(255) NOT NULL DEFAULT '1',
  `bankroll_maxbet_ratio` double NOT NULL DEFAULT '25',
  `inv_enable` int(1) NOT NULL DEFAULT '1',
  `inv_perc` double NOT NULL DEFAULT '0',
  `inv_min` double NOT NULL DEFAULT '0.0001',
  `inv_casproit` double NOT NULL DEFAULT '0',
  `deposits_last_round` datetime NOT NULL,
  `installed` int(1) NOT NULL DEFAULT '0',
  `maintenance` int(1) NOT NULL DEFAULT '0',
  `withdrawal_mode` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `system` (`id`,`active_theme`) VALUES
(1,'Basic');

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `player_id` int(255) NOT NULL,
  `amount` double NOT NULL,
  `txid` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 2015-07-30 16:01:34