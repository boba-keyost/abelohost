-- @description: Link deleted posts and categories
INSERT IGNORE INTO `posts_categories` (`post_id`, `category_id`)
VALUES ((SELECT `id` FROM `posts` WHERE `slug` = 'post-7-deleted'),
        (SELECT `id` FROM `categories` WHERE `slug` = 'category-4-deleted')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-8-deleted'),
     (SELECT `id` FROM `categories` WHERE `slug` = 'category-1'));
