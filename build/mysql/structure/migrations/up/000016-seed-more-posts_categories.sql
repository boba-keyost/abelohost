-- @description: Link more categories
INSERT IGNORE INTO `posts_categories` (`post_id`, `category_id`)
VALUES ((SELECT `id` FROM `posts` WHERE `slug` = 'post-9'),
        (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-10'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-10'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-11'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-12'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-13'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-14'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-15'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-16'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-17'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-18'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-19'), (SELECT `id` FROM `categories` WHERE `slug` = 'category-1'));
