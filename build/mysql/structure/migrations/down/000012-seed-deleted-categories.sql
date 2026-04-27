-- @description: Remove deleted categories
DELETE
FROM `categories`
WHERE `slug` = 'category-4-deleted';
