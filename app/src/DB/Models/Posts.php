<?php

namespace DB\Models;

use DB\Models\Types\IdName;
use DB\Models\Types\PostsWithViews;
use DB\Models\Types\PostWithScore;
use DB\ParamsList;
use DB\Rows;
use PDO;

class Posts extends BaseModel
{
    public function getAllowedSortFields(): array
    {
        return ["name", "updated_at", "created_at", "id", "views"];
    }

    public function getDefaultSortFields(): array
    {
        return [
            ["field" => "updated_at", "order" => "DESC"],
            ["field" => "created_at", "order" => "DESC"],
            ["field" => "id", "order" => "DESC"],
        ];
    }

    protected const string QUERY_GET_LAST_POST_IDS = "SELECT
    `id` 
FROM `posts` 
WHERE `deleted_at` = 0 
ORDER BY `updated_at` DESC, `created_at` DESC, `id` DESC";

    protected const string POST_FIELDS = "`p`.`id`,
    `p`.`slug`,
    `p`.`name`,
    `p`.`image`,
    `p`.`description`,
    `p`.`content`,
    `p`.`created_at`,
    `p`.`updated_at`";

    protected const string QUERY_GET_LAST_POSTS_FOR_CATEGORY = "SELECT
    " . self::POST_FIELDS . "
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
    " . self::POST_FIELDS . ",
    count(`pv`.`id`) as `views`
FROM `posts` AS `p`
LEFT JOIN `post_views` AS `pv` ON `pv`.`post_id` = `p`.`id`
WHERE 
    `p`.`id` IN (:post_ids)
    AND `p`.`deleted_at` = 0
GROUP BY `p`.`id`
";

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

    protected const string QUERY_SELECT_POST_WITH_VIEWS_BY_SLUG = "SELECT
    " . self::POST_FIELDS . ",
    count(`pv`.`id`) as `views`
FROM 
    `posts` AS `p`
LEFT JOIN `post_views` AS `pv` ON `pv`.`post_id` = `p`.`id`
INNER JOIN `posts_categories` AS `pc` ON `pc`.`post_id` = `p`.`id`
INNER JOIN `categories` AS `c` ON `pc`.`category_id` = `c`.`id`
WHERE `p`.`slug` = :slug
    AND `c`.`deleted_at` = 0
    AND `p`.`deleted_at` = 0
GROUP BY `p`.`id`
";

    public function getPostWithViewsBySlug($slug): PostsWithViews | false
    {
        return $this->getObject(
            static::QUERY_SELECT_POST_WITH_VIEWS_BY_SLUG,
            ["slug" => $slug],
            PostsWithViews::class,
        );
    }

    protected const string QUERY_GET_POST_CATEGORY_IDS_WITH_NAME = "SELECT 
    `c`.`id`, 
    `c`.`name` 
FROM `posts_categories` AS `pc`
    INNER JOIN `categories` AS `c` ON `pc`.`category_id` = `c`.`id`
WHERE `pc`.`post_id` = :post_id
AND `c`.`deleted_at` = 0";

    public function getPostCategoryIdsWithName(int $postId): Rows
    {
        return $this->getRowsObject(
            static::QUERY_GET_POST_CATEGORY_IDS_WITH_NAME,
            ["post_id" => [$postId, PDO::PARAM_INT]],
            IdName::class,
        );
    }

    protected const string QUERY_GET_POST_KEYWORD_IDS_WITH_NAME = "SELECT 
    `k`.`id`, 
    `k`.`name` 
FROM `posts_keywords` AS `pk`
    INNER JOIN `keywords` AS `k` ON `pk`.`keyword_id` = `k`.`id`
WHERE `pk`.`post_id` = :post_id";

    public function getPostKeywordIdsWithName(int $postId): Rows
    {
        return $this->getRowsObject(
            static::QUERY_GET_POST_KEYWORD_IDS_WITH_NAME,
            ["post_id" => [$postId, PDO::PARAM_INT]],
            IdName::class,
        );
    }

    protected const string QUERY_ADD_POST_VIEW = "INSERT INTO `post_views` (`post_id`, `visitor_id`, `meta`) 
    VALUES (:post_id, :visitor_id, :meta)
";

    public function addPostView(int $postId, string $visitorId, string $meta): bool
    {
        return $this->execute(
            static::QUERY_ADD_POST_VIEW,
            [
                "post_id" => [$postId, PDO::PARAM_INT],
                "visitor_id" => $visitorId,
                "meta" => $meta,
            ],
        );
    }

    protected const string QUERY_GET_SIMILAR_POSTS = "SELECT
    " . self::POST_FIELDS . ",
    ifnull(sum(`pc`.`category_id` IN (:category_id)) / count(DISTINCT pc.category_id), 0) as category_score,
    ifnull(sum(`pk`.`keyword_id` IN (:keyword_id)) / count(DISTINCT pk.keyword_id), 0) as keyword_score,
    MATCH(`p`.`name`, `p`.`description`, `p`.`content`) AGAINST(:query in BOOLEAN MODE) AS `content_score`
FROM
    `posts` as `p`
INNER JOIN posts_categories as `pc`
    ON `pc`.post_id = `p`.`id`
INNER JOIN `categories` AS `c` ON `pc`.`category_id` = `c`.`id`
LEFT JOIN posts_keywords as `pk`
    ON `pk`.post_id = `p`.`id`
WHERE
    `p`.`id` != :post_id
    AND `p`.`deleted_at` = 0
    AND `c`.`deleted_at` = 0
GROUP BY `p`.id
HAVING
   category_score + keyword_score + content_score > 0
ORDER BY category_score + keyword_score + content_score DESC";

    public function getSimilarPosts(
        int $postId,
        array $categoryIdList,
        array $keywordIdsList,
        string $query,
        int $limit = 3
    ): Rows {
        $params = [
            "post_id" => $postId,
            "category_id" => [$categoryIdList, PDO::PARAM_INT],
            "keyword_id" => [$keywordIdsList, PDO::PARAM_INT],
            "query" => $query,
        ];
        return $this->getRowsObject(
            $this->queryWithLimit(
                $this->queryWithList(
                    static::QUERY_GET_SIMILAR_POSTS,
                    $params,
                ),
                $limit,
                0
            ),
            $this->paramsWithLimit(
                $this->paramsWithList(
                    $params
                ),
                $limit,
                0,
            ),
            PostWithScore::class
        );
    }
}
