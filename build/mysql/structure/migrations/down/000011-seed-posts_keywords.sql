-- @description: Link test keywords
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-1') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-1');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-1') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-2');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-2') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-2');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-3') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-3');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-3') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-4');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-4') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-4');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-4') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-5');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-4') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-6');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-5') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-1');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-6') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-5');
DELETE FROM `posts_keywords` WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-6') AND `keyword_id` = (SELECT `id` FROM `keywords` WHERE `slug` = 'keyword-6');
