-- @description: update post images
UPDATE `posts`
SET `image` = CONCAT(
        'https://picsum.photos/id/',
        REPLACE(SUBSTR(`image`, LENGTH('/assets/images/post-pic-') + 1), '.jpg', ''),
        '/200')
WHERE image LIKE '/assets/images/post-pic-%';