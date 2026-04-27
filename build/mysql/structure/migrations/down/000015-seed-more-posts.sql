-- @description: Add test posts
DELETE
FROM `posts`
WHERE `slug` IN ('post-9',
                 'post-10',
                 'post-11',
                 'post-12',
                 'post-13',
                 'post-14',
                 'post-15',
                 'post-16',
                 'post-17',
                 'post-18',
                 'post-19');