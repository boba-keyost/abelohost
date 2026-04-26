<?php

namespace DB\Models;

use DB\Models\Types\PostsWithViews;
use DB\ParamsList;
use DB\Rows;
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

    public function getLastPostIds(int $limit = 3, int $offset = 0): Rows
    {
        return $this->getColumn(
            $this->queryWithLimit(static::QUERY_GET_LAST_POST_IDS, $limit, $offset),
            0,
            $this->paramsWithLimit([], $limit, $offset),
        );
    }

    protected const string QUERY_GET_POSTS_WITH_VIEWS_BY_IDS = "SELECT
    `p`.`id`,
    `p`.`slug`,
    `p`.`name`,
    `p`.`image`,
    `p`.`description`,
    `p`.`content`,
    `p`.`created_at`,
    `p`.`updated_at`,
    count(`pv`.`id`) as `views`
FROM `posts` AS `p`
LEFT JOIN `post_views` AS `pv` ON `pv`.`post_id` = `p`.`id`
WHERE 
    `p`.`id` IN (:post_ids)
    AND `p`.`deleted_at` = 0
GROUP BY `p`.`id`
";


    public function getAllowedSortFields(): array
    {
        return ["name", "updated_at", "created_at", "id", "views"];
    }

    public function getDefaultSortFields(): array
    {
        return [
            ["field" => "updated_at", "direction" => "DSC"],
            ["field" => "created_at", "direction" => "DSC"],
            ["field" => "id", "direction" => "DSC"],
        ];
    }

    public function getPostsWithViewsByIds(
        iterable $ids,
        ?iterable $sortFields = null,
        int $limit = -1,
        int $offset = 0
    ): Rows {
        $sortFields = $this->prepareSortField($sortFields);

        $params = ParamsList::prepare(['post_ids' => [$ids, PDO::PARAM_INT]]);
        return $this->getRowsObject(
            $this->queryWithLimit(
                $this->queryWithList(
                    $this->queryWithSorting(
                        static::QUERY_GET_POSTS_WITH_VIEWS_BY_IDS,
                        $sortFields,
                        $params
                    ),
                    $params
                ),
                $limit,
                $offset
            ),
            $this->paramsWithLimit(
                $this->paramsWithList(
                    $this->paramsWithSorting(
                        $params,
                        $sortFields,
                    ),
                ),
                $limit,
                $offset
            ),
            PostsWithViews::class,
        );
    }

    protected const string QUERY_GET_POST_IDS_BY_FILTER = "SELECT
    `pc`.`post_id`
FROM `posts_categories` AS `pc`
INNER JOIN `posts` AS `p` ON `p`.`id` = `pc`.`post_id`
WHERE 
    (ISNULL(:category_id) OR `pc`.`category_id` = :category_id)
    AND `p`.`deleted_at` = 0
GROUP BY `pc`.`post_id`
";

    public function getPostsIdsByFilter(array $filter): Rows
    {
        $params = [
            'category_id' => $filter['category_id'] ?? null,
        ];
        return $this->getColumn(
            static::QUERY_GET_POST_IDS_BY_FILTER,
            0,
            $params,
        );
    }
}
