-- @description: Add deleted categories
INSERT IGNORE INTO `categories` (`slug`, `name`, `deleted_at`)
VALUES ('category-4-deleted', 'Category 4 deleted', UNIX_TIMESTAMP() + 1);
