-- @description: Delete test posts
DELETE FROM `posts` WHERE `slug` IN ('post-1',
    'post-2',
    'post-3',
    'post-4',
    'post-5',
    'post-6');