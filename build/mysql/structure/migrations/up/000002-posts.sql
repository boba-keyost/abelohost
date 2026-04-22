-- @description: Create posts table
CREATE TABLE IF NOT EXISTS posts
(
    `id`          INT AUTO_INCREMENT,
    `slug`        VARCHAR(255) NOT NULL,
    `name`        VARCHAR(255) NULL,
    `image`       VARCHAR(255) NULL,
    `description` TEXT,
    `content`     TEXT,
    `enabled`     BIT(1)       NOT NULL DEFAULT b'1',
    `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at`  BIGINT       NOT NULL DEFAULT '0', -- soft delete field
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug_unique` (`slug`, `deleted_at`)
)
    ENGINE = innodb;