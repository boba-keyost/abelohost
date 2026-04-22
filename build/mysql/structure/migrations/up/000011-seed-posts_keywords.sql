-- @description: Link test keywords
INSERT IGNORE INTO `posts_keywords` (`post_id`, `keyword_id`)
VALUES
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-1'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-1'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-2')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-2'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-2')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-3'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-3')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-3'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-4')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-4'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-4')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-4'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-5')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-4'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-6')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-5'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-1')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-6'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-5')),
    ((SELECT `id` FROM `posts` WHERE `slug` = 'post-6'), (SELECT `id` FROM `keywords` WHERE `name` = 'keyword-6'));
