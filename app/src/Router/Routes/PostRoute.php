<?php

namespace Router\Routes;

use Renderers\RendererType;
use Router\BaseRoute;
use Router\Error;
use Router\RouteAttribute;
use Router\ServerInfo;

#[RouteAttribute(
    "",
    "/post/{slug}",
    ["renderer" => RendererType::Html, "rendererParameters" => ["tplName" => "post"]],
)]
class PostRoute extends BaseRoute
{
    public function handle(array $parameters = [], mixed $body = null): mixed
    {
        $post = $this->getDb()->posts()->getPostWithViewsBySlug($parameters["slug"] ?? "");
        if (!$post) {
            throw Error::notFound("Post not found");
        }

        $categories = $this->getDb()->posts()->getPostCategoryIdsWithName($post->id);
        $keywords = $this->getDb()->posts()->getPostKeywordIdsWithName($post->id);

        $categoryList = [];
        $keywordsList = [];

        $categoryIdList = [];
        $keywordIdsList = [];

        foreach ($categories as $category) {
            $categoryIdList[] = $category->id;
            $categoryList[] = $category->name;
        }

        foreach ($keywords as $keyword) {
            $keywordIdsList[] = $keyword->id;
            $keywordsList[] = $keyword->name;
        }

        $titleKeywords = array_reduce(
            explode(" ", $post->name),
            function (array $keywords, string $word) {
                $word = preg_replace("/\W/", "", trim($word));
                if (strlen($word) > 3) {
                    $keywords[] = $word;
                }
                return $keywords;
            },
            [],
        );

        $queries = [
            '"' . $post->name . '"',
            implode(" ", $keywordsList),
            implode(" ", $titleKeywords),
        ];

        $similarPosts = $this->getDb()->posts()->getSimilarPosts(
            $post->id,
            $categoryIdList,
            $keywordIdsList,
            "(" . implode(") (", $queries) . ")",
            3,
        );

        if (!empty($parameters["serverInfo"])) {
            /** @var ServerInfo $serverInfo */
            $serverInfo = $parameters["serverInfo"];
            $res = $this->getDb()->posts()->addPostView(
                $post->id,
                $serverInfo->getVisitorId(),
                json_encode($serverInfo, JSON_PRETTY_PRINT),
            );
            if ($res) {
                $post->views += 1;
            }
        }

        return [
            'post' => $post,
            'categories' => $categoryList,
            'keywords' => $keywordsList,
            'similar_posts' => $similarPosts,
        ];
    }
}
