<?php declare(strict_types=1);

namespace Shopware\Core\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1554900301AddRatingTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1554900301;
    }

    public function update(Connection $connection): void
    {
        // implement update

        $connection->executeUpdate('
            DROP TABLE IF EXISTS `product_rating`;
        ');
        $connection->executeUpdate('
            CREATE TABLE `product_rating` (
                `id` BINARY(16) NOT NULL,
                `product_id` BINARY(16) NULL,
                `customer_id` BINARY(16) NULL,
                `sales_channel_id` BINARY(16) NULL,
                `language_id` BINARY(16) NULL,
                `external_user` VARCHAR(255) NULL,
                `external_email` VARCHAR(255) NULL,
                `title` VARCHAR(255) NULL,
                `content` LONGTEXT NULL,
                `positive` INT(11) NULL,
                `negative` INT(11) NULL,
                `points` DOUBLE NULL,
                `status` TINYINT(1) NULL DEFAULT \'0\',
                `comment` VARCHAR(255) NULL,
                `comment_created_at` DATETIME(3) NULL,
                `attributes` JSON NULL,
                `updated_at` DATETIME(3) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `product_version_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `fk.product_rating.product_id` (`product_id`,`product_version_id`),
                KEY `fk.product_rating.customer_id` (`customer_id`),
                KEY `fk.product_rating.sales_channel_id` (`sales_channel_id`),
                KEY `fk.product_rating.language_id` (`language_id`),
                CONSTRAINT `fk.product_rating.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.product_rating.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.product_rating.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.product_rating.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
