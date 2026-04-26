<?php

namespace Router\Routes;

use DB\Models\Types\CategoryWithPosts;
use Renderers\RendererType;
use Router\BaseRoute;
use Router\RouteAttribute;

#[RouteAttribute(
    "",
    "/post/{slug}",
    ["renderer" => RendererType::Html, "rendererParameters" => ["tplName" => "post"]],
)]
class PostRoute extends BaseRoute
{
    public function handle(array $parameters = [], mixed $body = null): mixed
    {
        $category = $this->getDb()->categories()->getCategoryByPost();
        $posts = [];
        if (!empty($lastCategories)) {
            $postIds = [];
            /** @var CategoryWithPosts $category */
            foreach ($lastCategories as $category) {
                $category->posts_ids = array_slice($category->posts_ids, 0, 3);
                /** @var array $categoryPosts */
                $categoryPosts = $category->posts_ids;
                $i = 0;
                while ($i < count($categoryPosts)) {
                    $pid = $categoryPosts[$i];
                    if (!in_array($pid, $postIds)) {
                        $postIds[] = $pid;
                    }
                    $i++;
                }
            }
            $postsList = $this->getDb()->posts()->getPostsWithViewsByIds($postIds);
            foreach ($postsList as $post) {
                $posts[$post->id] = $post;
            }
        }

        return [
            'posts' => $posts,
            'categories' => $lastCategories,
        ];
    }
}
