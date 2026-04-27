-- @description: Unlink test categories
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-9')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-10')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-11')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-12')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-13')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-14')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-15')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-16')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-17')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-18')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-19')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');