-- @description: create full-search index
CREATE FULLTEXT INDEX posts_search_index
    ON `posts` (`name`, `description`, `content`);