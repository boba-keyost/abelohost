-- @description: Create posts views table
CREATE TABLE IF NOT EXISTS post_views
(
    `id`         INT AUTO_INCREMENT PRIMARY KEY,
    `post_id`    INT          NOT NULL,
    `visitor_id` VARCHAR(255) NOT NULL,
    `date`       DATETIME DEFAULT CURRENT_TIMESTAMP,
    `meta`       TEXT
)
    ENGINE = innodb;