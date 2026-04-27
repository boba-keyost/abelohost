<?php

namespace DB\Models;

use DB\Models\Types\Category;
use DB\Models\Types\CategoryWithPosts;
use DB\Rows;

class Categories extends BaseModel
{
    protected const string QUERY_SELECT_CATEGORIES_SLUGS_WITH_POSTS = "SELECT
    `c`.`id`,
    `c`.`slug`,
    `c`.`name`,
    `c`.`created_at`,
    `c`.`updated_at`,
    group_concat(DISTINCT `pc`.`post_id`) as `posts_ids`
FROM 
    `categories` AS `c`
INNER JOIN
    `posts_categories` AS `pc` ON `pc`.`category_id` = `c`.`id`
INNER JOIN 
    `posts` AS `p` ON `p`.`id` = `pc`.`post_id`
WHERE `p`.`deleted_at` = 0 AND `c`.`deleted_at` = 0
GROUP BY `c`.`slug`, `c`.`name`, `c`.`id`  
ORDER BY max(`p`.`updated_at`) DESC, max(`p`.`created_at`) DESC
";

    public function getCategoriesWithPosts(int $limit = -1, int $offset = 0): Rows
    {
        return $this->getRowsObject(
            $this->queryWithLimit(static::QUERY_SELECT_CATEGORIES_SLUGS_WITH_POSTS, $limit, $offset),
            $this->paramsWithLimit([], $limit, $offset),
            CategoryWithPosts::class,
        );
    }

    protected const string QUERY_SELECT_CATEGORY_BY_SLUG = "SELECT
    `c`.`id`,
    `c`.`slug`,
    `c`.`name`,
    `c`.`created_at`,
    `c`.`updated_at`
FROM 
    `categories` AS `c`
WHERE `c`.`slug` = :slug
AND `c`.`deleted_at` = 0
";

    public function getCategoryBySlug(string $slug): Category | false
    {
        return $this->getObject(
            static::QUERY_SELECT_CATEGORY_BY_SLUG,
            ["slug" => $slug],
            Category::class,
        );
    }
}
