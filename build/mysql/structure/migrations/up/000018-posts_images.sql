-- @description: update post images
UPDATE `posts`
SET `image` = CONCAT(
        '/assets/images/post-pic-',
        REPLACE(SUBSTR(`image`, LENGTH('https://picsum.photos/id/') + 1), '/200', ''),
        '.jpg')
WHERE image LIKE 'https://picsum.photos/id/%';