-- @description: Add deleted posts
INSERT IGNORE INTO `posts` (`slug`, `name`, `image`, `description`, `content`, `deleted_at`)
VALUES ('post-7-deleted', 'Deleted 7', 'https://picsum.photos/id/7/200', '', 'Deleted 7', 0),
    ('post-8-deleted', 'Deleted 8', 'https://picsum.photos/id/8/200', '', 'Deleted 8', UNIX_TIMESTAMP());

