-- @description: Create posts to keywords link table
CREATE TABLE IF NOT EXISTS posts_keywords
(
    `post_id`    INT,
    `keyword_id` INT,
    UNIQUE KEY `post_keyword` (`post_id`, `keyword_id`)
)
    ENGINE = innodb;