CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `guard_name` varchar(255) NOT NULL DEFAULT 'web',
  `created_at` timestamp NULL DEFAULT NOW(),
  `updated_at` timestamp NULL DEFAULT NOW(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` VALUES (1,'admin',NULL,'web', now(), now()),(2,'user',NULL,'web',now(),now());

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NOW(),
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NOW(),
  `updated_at` timestamp NULL DEFAULT NOW(),
  `deleted_at` timestamp NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `users` VALUES (1,'Admin','admin@stockmanager.com',NULL,"$2y$12$rvHcffso41gs65JvHzLC/uKq8kEEdn9KbUCN8jpQvzS6heeomXX5O",1,1,now(),now(),NULL);
INSERT INTO `users` VALUES (2,'Alice','alice@stockmanager.com',NULL,"$2y$12$QnBtNLNZ.wZUPXH3VdrvxeSC41moGmWRXOwDfwdUHQfElZosgqJCu",2,1,now(),now(),NULL);


CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NOW(),
  `updated_at` timestamp NULL DEFAULT NOW(),
  `deleted_at` timestamp NULL DEFAULT NOW(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES (1,'Electronic',NULL,0,now(),now(),NULL),(2,'Sport',NULL,0,now(),now(),NULL);

CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` double NOT NULL DEFAULT 0,
  `category_id` bigint(20) unsigned NOT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NOW(),
  `updated_at` timestamp NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  KEY `items_category_id` (`category_id`),
  KEY `items_author_id_foreign` (`author_id`),
  CONSTRAINT `items_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `items_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  `transaction_type` enum('IN','OUT') NOT NULL,
  `quantity` int(11) NOT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NOW(),
  `updated_at` timestamp NULL DEFAULT NOW(),
  PRIMARY KEY (`id`),
  KEY `transactions_item_id_foreign` (`item_id`),
  KEY `transactions_author_id_foreign` (`author_id`),
  CONSTRAINT `transactions_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transactions_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;