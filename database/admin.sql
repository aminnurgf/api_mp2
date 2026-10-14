-- Import file ini lewat phpMyAdmin hosting (database: thsi_43112148_api)
-- Pengganti "php artisan migrate --seed" untuk hosting tanpa SSH.

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tandai migration admin sudah dijalankan
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_10_07_000001_create_admin_table', 1
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_10_07_000001_create_admin_table');

-- Admin default: admin / admin123 (password bcrypt)
INSERT IGNORE INTO `admin` (`username`, `password`) VALUES
('admin', '$2y$10$dh0GmUh3U61WHVH/VVdXoOpv523mUaj5GZy168rOIN5LR7.XtZca.');

