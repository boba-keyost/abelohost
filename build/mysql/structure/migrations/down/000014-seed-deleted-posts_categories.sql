-- @description: Unlink deleted posts and categories
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-7-deleted')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-4-deleted');
DELETE
FROM `posts_categories`
WHERE `post_id` = (SELECT `id` FROM `posts` WHERE `slug` = 'post-8-deleted')
  AND `category_id` = (SELECT `id` FROM `categories` WHERE `slug` = 'category-1');