<?php

namespace Router\Routes;

use Features\Pagination\Pagination;
use Features\SortFields\SortFields;
use Renderers\RendererType;
use Router\BaseRoute;
use Router\Error;
use Router\RouteAttribute;

#[RouteAttribute(
    "",
    "/category/{slug}",
    ["renderer" => RendererType::Html, "rendererParameters" => ["tplName" => "category"]],
)]
class CategoryRoute extends BaseRoute
{
    public function handle(array $parameters = [], mixed $body = null): mixed
    {
        $category = $this->getDb()->categories()->getCategoryBySlug($parameters['slug']);
        if (!$category) {
            throw Error::notFound("Category not found");
        }
        $posts = [];
        $pagination = Pagination::init($parameters["query"] ?? []);
        $sortFields = SortFields::init(
            $parameters["query"] ?? [],
            $this->getDb()->posts()->getAllowedSortFields(),
            $this->getDb()->posts()->getDefaultSortFields()
        );
        $postsIds = $this->getDb()->posts()->getPostsIdsByFilter(
            [
                "category_id" => $category->id,
            ],
        );
        if ($postsIds->count() > 0) {
            $pagination->setTotal(count($postsIds));
            $posts = $this->getDb()->posts()->getPostsWithViewsByIds(
                $postsIds,
                $sortFields,
                $pagination->getLimit(),
                $pagination->getOffset(),
            );
        }


        return [
            'posts' => $posts,
            'category' => $category,
            'pagination' => $pagination->getPagesList(),
            'sort' => $sortFields->getSortList(),
            'query_parameters' => $parameters["query"] ?? [],
        ];
    }
}
