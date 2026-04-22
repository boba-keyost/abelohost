-- @description: Unlink test categories
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-1') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-1') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-2');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-2') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-2');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-3') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-3');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-3') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-2');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-4') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-4') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-3');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-4') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-2');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-5') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-6') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE FROM `posts_categories` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-6') AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-3');
