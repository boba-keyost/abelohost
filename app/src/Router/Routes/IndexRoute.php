<?php

namespace Router\Routes;

use DB\Models\Types\CategoryWithPosts;
use Renderers\RendererType;
use Router\BaseRoute;
use Router\RouteAttribute;

#[RouteAttribute("", "/", ["renderer" => RendererType::Html])]
class IndexRoute extends BaseRoute
{
    public function handle(array $parameters = [], mixed $body = null): mixed
    {
        $lastCategories = $this->getDb()->categories()->getCategoriesWithPosts();
        $lastPosts = [];
        if (!empty($lastCategories)) {
            $postIds = [];
            /** @var CategoryWithPosts $category */
            foreach ($lastCategories as $category) {
                /** @var array $categoryPosts */
                $categoryPosts = $category->posts_ids;
                $i = 0;
                while ($i < count($categoryPosts) && $i < 3) {
                    $pid = $categoryPosts[$i];
                    if (!in_array($pid, $postIds)) {
                        $postIds[] = $pid;
                    }
                    $i++;
                }
            }
            $lastPosts = $this->getDb()->posts()->getPostsByIds($postIds);
        }
        $data = [
            'posts' => $lastPosts,
            'categories' => $lastCategories,
        ];

        return ["parameters" => $parameters, "body" => $body, "data" => $data];
    }
}
