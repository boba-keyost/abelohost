-- @description: Create keywords table
CREATE TABLE IF NOT EXISTS keywords
(
    `id`           INT AUTO_INCREMENT,
    `name`         VARCHAR(255) NULL,
    `enabled`    BIT(1)       NOT NULL DEFAULT b'1',
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` BIGINT       NOT NULL DEFAULT '0', -- soft delete field
    PRIMARY KEY (`id`)
)
    ENGINE = innodb;