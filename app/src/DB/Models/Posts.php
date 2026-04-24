<?php

namespace DB\Models;

use DB\Models\Types\Post;
use DB\ParamsList;
use PDO;

class Posts extends BaseModel
{
    protected const string QUERY_GET_LAST_POST_IDS = "SELECT
    `id` 
FROM `posts` 
WHERE `deleted_at` = 0 
ORDER BY `updated_at` DESC, `created_at` DESC, `id` DESC";

    protected const string QUERY_GET_POST_KEYWORDS = "SELECT 
    `k`.`name` 
FROM `posts_keywords` AS `pk`
    INNER JOIN `keywords` ON `pk`.`keyword_id` = `k`.`id`
WHERE `pk`.`post_id` = ?";

    protected const string QUERY_GET_LAST_POSTS_FOR_CATEGORY = "SELECT
    `p`.`id`,
    `p`.`slug`,
    `p`.`name`,
    `p`.`image`
    `p`.`description`,
    `p`.`content`,
    `p`.`created_at`,
    `p`.`updated_at`
FROM `posts` AS `p`
INNER JOIN `posts_categories` AS `pc` ON `pc`.`category_id` = `p`.`id`
WHERE 
    `pc`.`category_id` = :category_id
    `p`.`deleted_at` = 0
ORDER BY `p`.`updated_at` DESC, `p`.`created_at` DESC, `p`.`id` DESC
";

    protected const string QUERY_GET_POSTS_BY_IDS = "SELECT
    `p`.`id`,
    `p`.`slug`,
    `p`.`name`,
    `p`.`image`,
    `p`.`description`,
    `p`.`content`,
    `p`.`created_at`,
    `p`.`updated_at`
FROM `posts` AS `p`
INNER JOIN `posts_categories` AS `pc` ON `pc`.`category_id` = `p`.`id`
WHERE 
    `p`.`id` IN (:post_ids)
    AND `p`.`deleted_at` = 0
ORDER BY `p`.`updated_at` DESC, `p`.`created_at` DESC, `p`.`id` DESC
";

    public function getLastPostIds(int $limit = 3, int $offset = 0): array
    {
        return $this->getColumn(
            $this->queryWithLimit(static::QUERY_GET_LAST_POST_IDS, $limit, $offset),
            $this->paramsWithLimit([], $limit, $offset),
        );
    }

    public function getPostsByIds(array $ids, int $limit = -1, int $offset = 0): array
    {
        $params = ParamsList::prepare(['post_ids' => [$ids, PDO::PARAM_INT]]);
        return $this->getRowsObject(
            $this->queryWithLimit(
                $this->queryWithList(
                    static::QUERY_GET_POSTS_BY_IDS,
                    $params
                ),
                $limit,
                $offset
            ),
            $this->paramsWithLimit(
                $this->paramsWithList($params),
                $limit,
                $offset
            ),
            Post::class,
        );
    }
}
