-- @description: Create posts to categories link table
CREATE TABLE IF NOT EXISTS posts_categories
(
    `post_id`     INT,
    `category_id` INT,
    UNIQUE KEY `post_category` (`post_id`, `category_id`)
)
    ENGINE = innodb;