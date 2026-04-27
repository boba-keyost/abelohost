-- @description: Remove deleted posts
DELETE
FROM `posts`
WHERE `slug` IN ('post-7-deleted', 'post-8-deleted');

