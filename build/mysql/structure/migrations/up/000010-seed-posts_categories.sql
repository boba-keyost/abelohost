-- @description: Link test categories
INSERT IGNORE INTO `posts_categories` (`post_id`, `category_id`)
VALUES
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-1'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-1'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-2')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-2'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-2')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-3'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-3')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-3'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-2')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-4'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-4'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-3')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-4'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-2')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-5'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-6'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-6'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-3'));
